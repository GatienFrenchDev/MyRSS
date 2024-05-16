<?php

class ArticleModel
{
    static function setArticleLu(int $id_article, int $id_espace): int
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("INSERT INTO est_lu (id_article, id_espace) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $id_flux = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_flux;
    }

    static function setArticleNonLu(int $id_article, int $id_espace): void
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("DELETE FROM est_lu WHERE id_article = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function insertArticleIntoDB(Article $article, int $id_flux): int
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $titre = $article->getTitre();
        $description = $article->getDescription();
        $date_pub = $article->getTimestamp();
        $url_article = $article->getUrlArticle();
        $current_ts = time();

        $stmt = $mysqli->prepare("INSERT INTO article (titre, description, date_pub, id_flux, url_article, date_ajout) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisi", $titre, $description, $date_pub, $id_flux, $url_article, $current_ts);
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

    static function rechercheAvancee(array $query, int $id_utilisateur): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $requete_sql = "SELECT a.*, f.*
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        INNER JOIN contient c ON c.id_flux = f.id_flux
        INNER JOIN categorie cg ON cg.id_categorie = c.id_categorie
        INNER JOIN espace_partage e ON e.id_espace = cg.id_espace
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        INNER JOIN contient_des cd ON cd.id_espace = e.id_espace
        INNER JOIN utilisateur u ON u.id_utilisateur = cd.id_utilisateur
        WHERE u.id_utilisateur = ? AND (a.titre LIKE ? OR a.description LIKE ?)";

        if(isset($query["article-lu"]) && !isset($query["article-non-lu"])){
            $requete_sql .= " AND el.id_article IS NOT NULL";
        }
        if(!isset($query["article-lu"]) && isset($query["article-non-lu"])){
            $requete_sql .= " AND el.id_article IS NULL";
        }

        if($query["debut"] != null){
            $ts = strtotime($query["debut"]);
            $requete_sql .= " AND a.date_pub > " . $ts;
        }

        if($query["fin"] != null){
            $ts = strtotime($query["fin"]);
            $ts += 86400;
            $requete_sql .= " AND a.date_pub < " . $ts;
        }


        $requete_sql .= " ORDER BY date_pub DESC LIMIT 100 OFFSET ?";

        $texte = $query["text"] == null ? "":$query["text"];
        $texte = "%$texte%";

        $offset = $query["numero-page"]*100;

        $stmt = $mysqli->prepare($requete_sql);
        $stmt->bind_param("issi", $id_utilisateur, $texte, $texte, $offset);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $res;
    }
}
