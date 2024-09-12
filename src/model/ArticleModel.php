<?php

require_once __DIR__ . "/../classes/Database.php";
require_once __DIR__ . "/../classes/Article.php";

class ArticleModel
{

    static function getArticle(int $id_article): Article | ArticleNotFoundException
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM article WHERE id_article = ?");
        $stmt->bind_param("i", $id_article);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $mysqli->close();

        if ($res === null) {
            throw new ArticleNotFoundException();
        }

        return new Article($res["titre"], $res["description"], $res["url_article"], $res["date_pub"], $res["url_image"]);
    }

    static function setArticleLu(int $id_article, int $id_espace): void
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("INSERT INTO est_lu (id_article, id_espace) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function setArticleNonLu(int $id_article, int $id_espace): void
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM est_lu WHERE id_article = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function marquerCommeTraite(int $id_article, int $id_espace): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("INSERT INTO est_traite (id_article, id_espace) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $res = true;
        try {
            $stmt->execute();
        } catch (Exception $e) {
            $res = false;
        }
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    static function removeFromTraite(int $id_article, int $id_espace): void
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM est_traite WHERE id_article = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    /**
     * Renvoi vrai si l'article est noté comme traité dans cet espace.
     */
    static function articleEstTraite(int $id_article, int $id_espace): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT id_article FROM est_traite WHERE id_article = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }

    static function insertArticleIntoDB(Article $article, int $id_flux): int
    {
        $mysqli = Database::connexion();

        $titre = $article->getTitre();
        $description = $article->getDescription();
        $date_pub = $article->getTimestamp();
        $url_article = $article->getUrlArticle();
        $url_image = $article->getUrlImage();
        $current_ts = time();

        $stmt = $mysqli->prepare("INSERT INTO article (titre, description, date_pub, id_flux, url_article, date_ajout, url_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisis", $titre, $description, $date_pub, $id_flux, $url_article, $current_ts, $url_image);
        try {
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return -1;
        }
        $id_flux = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_flux;
    }

    /**
     * @param Article[] $articles
     * @param int $id_flux
     */
    static function insertArticlesIntoDB(array $articles, int $id_flux): array
    {
        $mysqli = Database::connexion();

        if (count($articles) === 0) {
            return [];
        }

        // Prepare the initial part of the query
        $query = "INSERT INTO article (titre, description, date_pub, id_flux, url_article, date_ajout, url_image) VALUES ";

        $values = [];
        $types = "";
        $current_ts = time();
        $stmt = null;  // Initialize $stmt to null

        try {
            // Loop through each article and create the placeholders for the query
            foreach ($articles as $article) {
                $values[] = $article->getTitre();
                $values[] = $article->getDescription();
                $values[] = $article->getTimestamp();
                $values[] = $id_flux;
                $values[] = $article->getUrlArticle();
                $values[] = $current_ts;
                $values[] = $article->getUrlImage();

                // Add placeholders for each set of values
                $query .= "(?, ?, ?, ?, ?, ?, ?),";
                $types .= "ssiisis";  // Type string corresponding to each set of values
            }

            // Remove the trailing comma from the query (important)
            $query = rtrim($query, ',');

            // Prepare the statement
            $stmt = $mysqli->prepare($query);

            if (!$stmt) {
                throw new Exception("Failed to prepare SQL statement: " . $mysqli->error);
            }

            // Bind all parameters dynamically
            $stmt->bind_param($types, ...$values);

            // Execute the query
            $stmt->execute();

            // Get the ID of the first inserted row
            $first_inserted_id = $mysqli->insert_id;

            // Prepare an array to store the results
            $result = [];

            // Loop through the articles and prepare the result array
            for ($i = 0; $i < count($articles); $i++) {
                $articles[$i]->setId($first_inserted_id + $i);  // Set the id_article for each article
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return []; // Return empty array in case of failure
        } finally {
            // Only attempt to close if $stmt was successfully initialized
            if ($stmt) {
                $stmt->close();
            }
            $mysqli->close();
        }

        // Return the array of articles with the desired keys
        return $articles;
    }

    static function rechercheAvancee(array $query, int $id_utilisateur): array
    {
        $mysqli = Database::connexion();

        $requete_sql = "SELECT a.*, f.*
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        INNER JOIN contient c ON c.id_flux = f.id_flux
        INNER JOIN categorie cg ON cg.id_categorie = c.id_categorie
        INNER JOIN espace e ON e.id_espace = cg.id_espace
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        INNER JOIN contient_des cd ON cd.id_espace = e.id_espace
        INNER JOIN utilisateur u ON u.id_utilisateur = cd.id_utilisateur
        WHERE u.id_utilisateur = ? AND (a.titre LIKE ? OR a.description LIKE ?)";

        $id_categorie = 0;

        if (isset($query["categorie"])) {
            $id_categorie = intval($query["categorie"]);
            if ($id_categorie != 0) {
                $requete_sql .= " AND cg.id_categorie = ?";
            }
        }

        if (isset($query["article-lu"]) && !isset($query["article-non-lu"])) {
            $requete_sql .= " AND el.id_article IS NOT NULL";
        }
        if (!isset($query["article-lu"]) && isset($query["article-non-lu"])) {
            $requete_sql .= " AND el.id_article IS NULL";
        }

        if ($query["debut"] != null) {
            $ts = strtotime($query["debut"]);
            $requete_sql .= " AND a.date_pub > " . $ts;
        }

        if ($query["fin"] != null) {
            $ts = strtotime($query["fin"]);
            $ts += 86400;
            $requete_sql .= " AND a.date_pub < " . $ts;
        }

        if ($query["tri"] == "asc") {
            $requete_sql .= " ORDER BY date_pub ASC LIMIT 100 OFFSET ?";
        } else {
            $requete_sql .= " ORDER BY date_pub DESC LIMIT 100 OFFSET ?";
        }



        $texte = $query["text"] == null ? "" : $query["text"];
        $texte = "%$texte%";

        $offset = $query["numero-page"] * 100;

        $stmt = $mysqli->prepare($requete_sql);

        if ($id_categorie != 0) {
            $stmt->bind_param("issii", $id_utilisateur, $texte, $texte, $id_categorie, $offset);
        } else {
            $stmt->bind_param("issi", $id_utilisateur, $texte, $texte, $offset);
        }

        $stmt->execute();

        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $res;
    }
}
