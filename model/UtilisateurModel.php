<?php

class UtilisateurModel
{
    static function getEspaces(int $user_id): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT 
        e.nom, 
        e.id_espace,
        COUNT(DISTINCT a.id_article) AS nb_non_lu
    FROM 
        espace_partage e
    INNER JOIN 
        contient_des cd ON e.id_espace = cd.id_espace
    INNER JOIN 
        utilisateur u ON u.id_utilisateur = cd.id_utilisateur
    LEFT JOIN 
        categorie c ON e.id_espace = c.id_espace
    LEFT JOIN 
        contient ct ON c.id_categorie = ct.id_categorie
    LEFT JOIN 
        article a ON ct.id_flux = a.id_flux
    LEFT JOIN 
        est_lu el ON a.id_article = el.id_article AND e.id_espace = el.id_espace
    WHERE 
        u.id_utilisateur = ?
        AND (el.id_article IS NULL OR a.id_article IS NULL) -- Pour les articles non lus ou s'il n'y a pas d'article
    GROUP BY 
        e.id_espace;");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    /**
     * @param user_id - identifiant de l'utilisateur
     * @param numero_page - premiere page commence à 0 donc $numero_page appartient à [0;+inf[
     */
    static function getAllArticles(int $user_id, int $numero_page): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $numero_page *= 100;

        $stmt = $mysqli->prepare("SELECT a.*, f.*,
       CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu
       FROM article a
       INNER JOIN flux_rss f ON a.id_flux = f.id_flux
       INNER JOIN contient c ON c.id_flux = f.id_flux
       INNER JOIN categorie cg ON cg.id_categorie = c.id_categorie
       INNER JOIN espace_partage e ON e.id_espace = cg.id_espace
       LEFT JOIN est_lu el ON a.id_article = el.id_article
       INNER JOIN contient_des cd ON cd.id_espace = e.id_espace
       INNER JOIN utilisateur u ON u.id_utilisateur = cd.id_utilisateur
       WHERE u.id_utilisateur = ?
       ORDER BY date_pub DESC LIMIT 100 OFFSET ?");
        $stmt->bind_param("ii", $user_id, $numero_page);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function getAllCategoriesFromUser(int $id_utilisateur): array
    {

        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT c.* FROM categorie c INNER JOIN espace_partage ep ON ep.id_espace = c.id_espace INNER JOIN contient_des cd ON cd.id_espace = ep.id_espace WHERE cd.id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    /**
     * Renvoie une liste vide si l'user existe pas.
     * Exemple : getUserDetailsFromId(1) renvoie `Array ( [id_utilisateur] => 1 [nom] => Doe [prenom] => John [email] => john@example.com [date_inscription] => 1714580239 )`
     */
    static function getUserDetailsFromId(int $id_utilisateur): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT id_utilisateur, nom, prenom, email, date_inscription FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        if (count($res) > 0) {
            return $res[0];
        }
        return [];
    }

    /**
     * Permet de vérifier si un utilisateur existe avec l'id passé en paramètre.
     * e.g. : `userExist(13)` renvoie `true` si il existe un utilisateur dans la db avec l'id 13
     * @param id_utilisateur - l'identifiant à tester
     */
    static function userExist(int $id_utilisateur): bool
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT COUNT(id_utilisateur) as nb_user FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }

    /**
     * Exemple : getUserDetailsFromMail(`john@exemple.com`) renvoie `Array ( [id_utilisateur] => 1 [nom] => Doe [prenom] => John [email] => john@exemple.com [date_inscription] => 1714580239 )`
     * Renvoie une liste vide si l'user existe pas.
     */
    static function getUserDetailsFromMail(string $mail): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT id_utilisateur, nom, prenom, email, date_inscription FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        if (count($res) > 0) {
            return $res[0];
        }
        return [];
    }

    static function getNotifications(int $id_utilisateur): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT * FROM notification WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        if (count($res) > 0) {
            return $res;
        }
        return [];
    }

    static function getInvitations(int $id_utilisateur): array
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT e.nom AS nom_espace, i.id_invitation, i.id_utilisateur_inviteur
        FROM utilisateur u
        INNER JOIN invitation i ON i.id_utilisateur=u.id_utilisateur
        INNER JOIN espace_partage e ON e.id_espace = i.id_espace
        WHERE i.id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        for ($i = 0; $i < count($res); $i++) {
            $res[$i]["invitateur"] = UtilisateurModel::getUserDetailsFromId($res[$i]["id_utilisateur_inviteur"]);
        }
        $stmt->close();
        $mysqli->close();
        return $res;
    }
}
