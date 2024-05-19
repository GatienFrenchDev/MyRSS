<?php

class EspaceModel
{
    static function getArticlesInsideEspace(int $id_espace, int $numero_page): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $numero_page *= 100;

        $stmt = $mysqli->prepare("SELECT a.*, f.*, 
    CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu
    FROM article a
    INNER JOIN flux_rss f ON a.id_flux = f.id_flux
    INNER JOIN contient c ON c.id_flux = f.id_flux
    LEFT JOIN est_lu el ON a.id_article = el.id_article
    INNER JOIN categorie cat ON cat.id_categorie = c.id_categorie
    WHERE cat.id_espace = ? AND cat.id_parent IS NULL ORDER BY date_pub DESC LIMIT 100 OFFSET ?");
        $stmt->bind_param("ii", $id_espace, $numero_page);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function renameEspace(int $id_espace, string $nom): void
    {
        $mysqli = require "../includes/database.inc.php";
        $stmt = $mysqli->prepare("UPDATE espace_partage SET nom = ? WHERE id_espace = ?");
        $stmt->bind_param("si", $nom, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function deleteEspace(int $id_espace): void
    {
        $mysqli = require "../includes/database.inc.php";
        $stmt = $mysqli->prepare("DELETE FROM espace_partage WHERE id_espace = ?");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function getCategoriesFromEspace(int $id_espace): array
    {

        require_once "../model/CategorieModel.php";

        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("SELECT * FROM categorie WHERE id_espace = ? AND id_parent IS NULL");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();
        foreach ($res as &$categorie) {
            $categorie["nb_non_lu"] = CategorieModel::getNombreNonLuInsideCategorie($categorie["id_categorie"]);
        }
        return $res;
    }

    /**
     * Permet de vérifier si l'espace appartient ou non à l'utilisateur.
     */
    static function espaceAppartientA(int $id_user, int $id_espace): bool
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT * FROM contient_des WHERE id_utilisateur = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_user, $id_espace);
        $stmt->execute();
        $stmt->store_result();
        $res = $stmt->num_rows();

        $stmt->close();
        $mysqli->close();

        return $res != 0;
    }

    static function pushNewEspaceToDB(string $nom, int $id_utilisateur): int
    {
        $mysqli = require "../includes/database.inc.php";


        $stmt = $mysqli->prepare("INSERT INTO espace_partage (nom, id_proprietaire) VALUES (?, ?)");
        $stmt->bind_param("si", $nom, $id_utilisateur);
        $stmt->execute();
        $id_espace = $mysqli->insert_id;
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO contient_des (id_utilisateur, id_espace) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_utilisateur, $id_espace);
        $stmt->execute();
        $stmt->close();



        $mysqli->close();
        return $id_espace;
    }

    /**
     * Renvoie le nom de l'espace
     */
    static function getNom(int $id_espace): string
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT nom FROM espace_partage WHERE id_espace = ?");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        if (count($res) == 0) {
            return "";
        }

        return $res[0]["nom"];
    }

    /**
     * Retourne vrai seulement si l'espace a été créé par l'utilisateur
     */
    static function estProprio(int $id_utilisateur, int $id_espace): bool
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT COUNT(id_espace) FROM espace_partage WHERE id_espace = ? AND id_proprietaire = ?");
        $stmt->bind_param("ii", $id_espace, $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return count($res) > 0;
    }

    /**
     * Permet de faire quitter l'espace à un user.
     * NOTE : ne supprime pas l'espace, l'user n'est juste plus associé à cette espace.
     * A utiliser théoriquement seulement lorsqu'un user veut quitter un espace au quel il avait été invité
     */
    static function quitterEspace(int $id_utilisateur, int $id_espace): void
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("DELETE FROM contient_des WHERE id_utilisateur = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_utilisateur, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }
}
