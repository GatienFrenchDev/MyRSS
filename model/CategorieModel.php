<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/model/FluxModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/model/UtilisateurModel.php";

class CategorieModel
{
    static function getNombreNonLuInsideCategorie(int $id_categorie): int
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT COUNT(a.id_article) AS nb_non_lu
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        INNER JOIN contient c ON c.id_flux = f.id_flux
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        WHERE c.id_categorie = ? AND el.id_article IS NULL;");
        $stmt->bind_param("i", $id_categorie);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res[0]["nb_non_lu"];
    }

    static function getArticlesInsideCategorie(int $id_cateogrie, int $numero_page): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $numero_page *= 100;

        $stmt = $mysqli->prepare("SELECT a.*, f.*, 
        CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        INNER JOIN contient c ON c.id_flux = f.id_flux
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        WHERE c.id_categorie = ? ORDER BY date_pub DESC LIMIT 100 OFFSET ?");
        $stmt->bind_param("ii", $id_cateogrie, $numero_page);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function renameCategorie(int $id_categorie, string $nom): void
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");
        $stmt = $mysqli->prepare("UPDATE categorie SET nom = ? WHERE id_categorie = ?");
        $stmt->bind_param("si", $nom, $id_categorie);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function deleteCategorie(int $id_categorie): void
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");
        $stmt = $mysqli->prepare("DELETE FROM categorie WHERE id_categorie = ?");
        $stmt->bind_param("i", $id_categorie);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function getFluxRSSInsideCategorie(int $id_categorie): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");


        $stmt = $mysqli->prepare("SELECT f.*
        FROM flux_rss f
        INNER JOIN contient c ON c.id_flux = f.id_flux
        WHERE c.id_categorie = ?");
        $stmt->bind_param("i", $id_categorie);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($res as &$ligne) {
            $ligne["nb_non_lu"] = FluxModel::getNombreNonLuInsideFlux($ligne["id_flux"]);
        }

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function addRSSFluxToCategorie(int $id_flux, int $id_categorie): void
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("INSERT INTO contient (id_flux, id_categorie) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_flux, $id_categorie);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function getSubCategories(int $id_categorie): array
    {


        $mysqli = require "../includes/database.inc.php";


        $stmt = $mysqli->prepare("SELECT * FROM categorie WHERE id_parent = ?");
        $stmt->bind_param("i", $id_categorie);
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
     * Si id_categorie = -1 on renvoi les espaces de la personne
     */
    static function getParentsCategories(int $id_categorie): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        if (!isset($_SESSION["user_id"])) {
            return [];
        }


        if ($id_categorie < 0) {
            return UtilisateurModel::getEspaces($_SESSION["user_id"]);
        }


        $stmt = $mysqli->prepare("
        SELECT *
        FROM categorie c
        WHERE c.id_parent =
        (SELECT c2.id_categorie
        FROM categorie c0
        INNER JOIN categorie c1 ON c1.id_categorie = c0.id_parent
        INNER JOIN categorie c2 ON c2.id_categorie = c1.id_parent
        WHERE c0.id_categorie = ?)
        ");

        $stmt->bind_param("i", $id_categorie);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        if (count($res) > 0) {
            $mysqli->close();
            return $res;
        }

        $stmt = $mysqli->prepare("
        SELECT *
        FROM categorie c
        WHERE c.id_parent =
        (SELECT c1.id_categorie
        FROM categorie c0
        INNER JOIN categorie c1 ON c1.id_categorie = c0.id_parent
        WHERE c0.id_categorie = ?)
        ");

        $stmt->bind_param("i", $id_categorie);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if (count($res) > 0) {
            $stmt->close();
            $mysqli->close();
            return $res;
        }

        return [];
    }

    /*
Si categorie parent null alors mettre $id_categorie_parent à -1.
Renvoi id de la categorie qui vient d'etre crée
*/
    static function pushNewCategorieToDB(string $nom, int $id_categorie_parent, int $id_espace): int
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        if ($id_categorie_parent <= 0) {
            $stmt = $mysqli->prepare("INSERT INTO categorie (nom, id_espace) VALUES (?, ?)");
            $stmt->bind_param("si", $nom, $id_espace);
            $stmt->execute();
            $id_categorie = $mysqli->insert_id;
            $stmt->close();
            $mysqli->close();
            return $id_categorie;
        } else {
            $stmt = $mysqli->prepare("INSERT INTO categorie (nom, id_espace, id_parent) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $nom, $id_espace, $id_categorie_parent);
            $stmt->execute();
            $id_categorie = $mysqli->insert_id;
            $stmt->close();
            $mysqli->close();
            return $id_categorie;
        }
    }

    static function removeFluxFromCategorie(int $id_flux, int $id_categorie)
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("DELETE FROM contient WHERE id_flux = ? AND id_categorie = ?");
        $stmt->bind_param("ii", $id_flux, $id_categorie);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function categorieAppartientA(int $id_user, int $id_categorie): bool
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT * FROM categorie c INNER JOIN contient_des cd ON c.id_espace = cd.id_espace WHERE cd.id_utilisateur = ? AND c.id_categorie = ?");
        $stmt->bind_param("ii", $id_user, $id_categorie);
        $stmt->execute();
        $stmt->store_result();
        $res = $stmt->num_rows();

        $stmt->close();
        $mysqli->close();

        return $res != 0;
    }
}
