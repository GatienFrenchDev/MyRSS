<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/classes/Database.php";

class FluxModel
{
    static function getNombreNonLuInsideFlux(int $id_flux): int
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT COUNT(a.id_article) AS nb_non_lu
        FROM article a
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        WHERE a.id_flux = ? AND el.id_article IS NULL;");
        $stmt->bind_param("i", $id_flux);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res[0]["nb_non_lu"];
    }

    static function getArticlesFromFlux(int $id_flux, int $numero_page): array
    {
        $mysqli = Database::connexion();

        $numero_page *= 100;

        $stmt = $mysqli->prepare("SELECT a.*, f.*, 
        CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu,
        CASE WHEN et.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_traite
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        LEFT JOIN est_traite et ON a.id_article = et.id_article
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        WHERE a.id_flux = ? ORDER BY date_pub DESC LIMIT 100 OFFSET ?");
        $stmt->bind_param("ii", $id_flux, $numero_page);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function updateNomFromFlux(int $id_flux, string $nom): void
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("UPDATE flux_rss SET nom = ? WHERE id_flux = ?");
        $stmt->bind_param("si", $nom, $id_flux);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function isFluxRSSindb(string $url): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM flux_rss WHERE adresse_url = ?");
        $stmt->bind_param("s", $url);
        $stmt->execute();
        $stmt->store_result();
        $res = $stmt->num_rows();

        $stmt->close();
        $mysqli->close();

        return $res != 0;
    }

    /**
     * @param url - url du flux rss ( eg. `https://feeds.bbci.co.uk/news/rss.xml`)
     * @param type - type du flux rss ( eg. `rss`)
     * @return int - id du flux rss qui vient d'être ajouté à la db
     */
    static function insertFeedIntoDB(string $url, string $type_flux): int
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("INSERT INTO flux_rss (adresse_url, nom, type_flux) VALUES (?, 'Flux sans nom', ?)");
        $stmt->bind_param("ss", $url, $type_flux);
        $stmt->execute();
        $id_flux = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_flux;
    }

    static // retourne -1 si pas dans db
    function getIDFromURL(string $url): int
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT id_flux FROM flux_rss WHERE adresse_url = ?");
        $stmt->bind_param("s", $url);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        if (count($res) == 0) {
            return -1;
        }

        return $res[0]["id_flux"];
    }


    /*
    Exemple :
    Array ( [0] => Array ( [id_flux] => 1 [adresse_url] => https://www.lemonde.fr/sante/rss_full.xml ) [1] => Array ( [id_flux] => 2 [adresse_url] => https://www.lefigaro.fr/rss/figaro_sante.xml ) )
    */
    static function getAllRSSFlux(): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT nom, id_flux, adresse_url FROM flux_rss");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $res;
    }

    static function getDernierUrlArticle(int $id_flux): Article | null
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM article WHERE id_flux = ? ORDER BY date_pub DESC LIMIT 1");
        $stmt->bind_param("i", $id_flux);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if (count($res) == 0) {
            return null;
        }

        $res = $res[0];

        return new Article($res["titre"], $res["description"], $res["url_article"], $res["date_pub"], $res["url_image"]);
    }

    /**
     * Permet de vérifier si un flux rss existe avec l'id passé en paramètre.
     * e.g. : `rssFluxExist(13)` renvoie `true` si il existe un flux rss dans la db avec l'id 13
     * @param id_flux - l'identifiant à tester
     */
    static function rssFluxExist(int $id_flux): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT COUNT(id_flux) as nb_fluxs FROM flux_rss WHERE id_flux = ?;");
        $stmt->bind_param("i", $id_flux);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }

    static function getFluxDetailsFromId(int $id_flux): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM flux_rss WHERE id_flux = ?");
        $stmt->bind_param("i", $id_flux);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        if (count($res) > 0) {
            return $res[0];
        }
        return [];
    }

    static function getAllArticles(int $id_flux): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT a.id_article, a.titre, a.description, a.url_article, a.date_pub AS date_publication, f.nom AS nom_flux, f.adresse_url AS adresse_flux,
        CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu,
        CASE WHEN et.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_traite
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        LEFT JOIN est_traite et ON a.id_article = et.id_article
        WHERE a.id_flux = ? ORDER BY date_pub DESC");
        $stmt->bind_param("i", $id_flux);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }
}
