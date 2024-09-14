<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/classes/Database.php";

class UserNotFoundException extends Exception {
    public function __construct() {
        parent::__construct("User not found");
    }
}

class UtilisateurModel
{
    static function getEspaces(int $id_utilisateur): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT 
    e.nom, 
    e.id_espace,
    e.article_wp,
    cd.role,
    COUNT(DISTINCT a.id_article) AS nb_non_lu
FROM 
    espace e
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
    e.id_espace, e.nom, cd.role
;");

        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    static function getAllCategories(int $id_utilisateur): array
    {

        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT c.* FROM categorie c INNER JOIN espace ep ON ep.id_espace = c.id_espace INNER JOIN contient_des cd ON cd.id_espace = ep.id_espace WHERE cd.id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    /**
     * Exemple : getUserDetailsFromId(1) renvoie `Array ( [id_utilisateur] => 1 [nom] => Doe [prenom] => John [email] => john@example.com [date_inscription] => 1714580239 )`
     */
    static function getUserDetailsFromId(int $id_utilisateur): array | UserNotFoundException
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT id_utilisateur, nom, prenom, email, date_inscription FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        if (count($res) == 0) {
            throw new UserNotFoundException();
        }
        return $res[0];
    }

    /**
     * Permet de vérifier si un utilisateur existe avec l'id passé en paramètre.
     * @param id_utilisateur - l'identifiant à tester
     * @example UtilisateurModel::userExist(13) renvoie `true` si l'utilisateur existe, `false` sinon
     */
    static function userExist(int $id_utilisateur): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT COUNT(id_utilisateur) as nb_user FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }

    /**
     * Exemple : getUserDetailsFromMail(`john@example.com`) renvoie `Array ( [id_utilisateur] => 1 [nom] => Doe [prenom] => John [email] => john@example.com [date_inscription] => 1714580239 )`
     * Renvoie une liste vide si l'user existe pas.
     */
    static function getUserDetailsFromMail(string $mail): array
    {
        $mysqli = Database::connexion();

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
        $mysqli = Database::connexion();

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
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT e.nom AS nom_espace, i.id_invitation, i.id_utilisateur_inviteur, i.reader_only
        FROM utilisateur u
        INNER JOIN invitation i ON i.id_utilisateur=u.id_utilisateur
        INNER JOIN espace e ON e.id_espace = i.id_espace
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

    /**
     * Renvoi tous les articles non lu d'un utilisateur
     */
    static function getArticlesNonLu(int $id_utilisateur, int $numero_page): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT a.*, f.*
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        INNER JOIN contient c ON c.id_flux = f.id_flux
        INNER JOIN categorie cg ON cg.id_categorie = c.id_categorie
        INNER JOIN espace e ON e.id_espace = cg.id_espace
        LEFT JOIN est_lu el ON a.id_article = el.id_article
        INNER JOIN contient_des cd ON cd.id_espace = e.id_espace
        INNER JOIN utilisateur u ON u.id_utilisateur = cd.id_utilisateur
        WHERE u.id_utilisateur = ? AND el.id_article IS NULL
        ORDER BY date_pub DESC LIMIT 100 OFFSET ?");

        $stmt->bind_param("ii", $id_utilisateur, $numero_page);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    static function getArticlesFavoris(int $id_utilisateur, int $numero_page): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT a.*, f.*
        FROM article a
        INNER JOIN flux_rss f ON a.id_flux = f.id_flux
        INNER JOIN ajout_collection ac ON ac.id_article = a.id_article
        INNER JOIN utilisateur u ON u.id_utilisateur = ac.id_utilisateur
        WHERE u.id_utilisateur = ? AND ac.id_collection = 1
        ORDER BY date_pub DESC LIMIT 100 OFFSET ?");

        $stmt->bind_param("ii", $id_utilisateur, $numero_page);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    /**
     * Renvoie le nombre de favoris total d'un utilisateur
     */
    static function getNombresFavoris(int $id_utilisateur): int
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT COUNT(a.id_article) as nb_articles
        FROM article a
        INNER JOIN ajout_collection ac ON ac.id_article = a.id_article
        INNER JOIN utilisateur u ON u.id_utilisateur = aa.id_utilisateur
        WHERE u.id_utilisateur = ? AND ac.id_collection = 1");

        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res[0]["nb_articles"];
    }

    /**
     * Renvoi le hash de connexion et l'id appartenant à l'utilisateur
     */
    static function getHashAndID(string $email): false|array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT hash_password, id_utilisateur FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        if (count($res) > 0) {
            return $res[0];
        }
        return false;
    }

    static function createUser(string $nom, string $prenom, string $email, string $hash_password): int
    {
        $mysqli = Database::connexion();

        try {
            $current_timestamp = time();
            $sql = "INSERT INTO utilisateur (nom, prenom, email, hash_password, date_inscription) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->stmt_init();
            $stmt->prepare($sql);
            $stmt->bind_param("ssssi", $nom, $prenom, $email, $hash_password, $current_timestamp);
            $stmt->execute();
            $id_user = $mysqli->insert_id;
            $stmt->close();

            // Lie l'espace partagé crée à l'user
            $sql = "INSERT INTO espace (nom) VALUES (?)";
            $stmt = $mysqli->stmt_init();
            $stmt->prepare($sql);
            $nom_espace = "Espace de " . $prenom;
            $stmt->bind_param("s", $nom_espace);
            $stmt->execute();
            $id_espace = $mysqli->insert_id;
            $stmt->close();


            // Crée un espace partagé pour l'user
            $sql = "INSERT INTO contient_des (id_utilisateur, id_espace, role) VALUES (?, ?, 'admin')";
            $stmt = $mysqli->stmt_init();
            $stmt->prepare($sql);
            $stmt->bind_param("ii", $id_user, $id_espace);
            $stmt->execute();

            $stmt->close();
            $mysqli->close();
        } catch (Exception $e) {
            $mysqli->close();
            return -1;
        }

        return $id_user;
    }

    static function getAllCollections(int $id_utilisateur): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT c.id_collection, c.nom FROM collection c WHERE c.id_createur = ?");

        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    static function generateResetPasswordToken(int $id_utilisateur): string
    {
        $token = bin2hex(random_bytes(32));
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM reset_password_token WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO reset_password_token (id_utilisateur, token) VALUES (?, ?)");
        $stmt->bind_param("is", $id_utilisateur, $token);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        return $token;
    }

    static function sendResetPasswordEmail(string $email, string $token)
    {

        require __DIR__ . "/../vendor/autoload.php";

        $env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");

        $url = $env["DOMAIN"] . "/email-reset-password.php?email=" . $email . "&token=" . $token;
        $resend = Resend::client($env["RESEND_API_KEY"]);

        $resend->emails->send([
            'from' => $env["RESEND_EMAIL"],
            'to' => $email,
            'subject' => 'MyRSS | Réinitialisation de votre mot de passe',
            'html' => 'Bonjour,<br><p>Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="' . $url . '">' . $url . '</a><br><br>Cordialement,<br>L\'équipe Troover</p>'
        ]);
    }

    static function isTokenValid(string $email, string $token): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT id_utilisateur FROM reset_password_token WHERE token = ? AND id_utilisateur = (SELECT id_utilisateur FROM utilisateur WHERE email = ?)");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }

    static function resetPassword(string $email, string $password): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("UPDATE utilisateur SET hash_password = ? WHERE email = ?");
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        return true;
    }

    static function deleteToken(string $email, string $token): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM reset_password_token WHERE token = ? AND id_utilisateur = (SELECT id_utilisateur FROM utilisateur WHERE email = ?)");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        return true;
    }

    static function getNumbersOfNotifsAndInvits(int $id_utilisateur): int
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT COUNT(id_notification) as nb_notif FROM notification WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmt = $mysqli->prepare("SELECT COUNT(id_invitation) as nb_invit FROM invitation WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res2 = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res[0]["nb_notif"] + $res2[0]["nb_invit"];
    }

    static function getFluxs(int $id_utilisateur): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT f.* FROM flux_rss f INNER JOIN contient c ON f.id_flux = c.id_flux INNER JOIN categorie cg ON c.id_categorie = cg.id_categorie INNER JOIN espace e ON cg.id_espace = e.id_espace INNER JOIN contient_des cd ON e.id_espace = cd.id_espace WHERE cd.id_utilisateur = ? ORDER BY f.nom");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    static function getAllRules(int $id_utilisateur): array
    {
        $mysqli = Database::connexion();
        $stmt = $mysqli->prepare("SELECT r.*, f.nom AS nom_flux, f.adresse_url FROM regle r INNER JOIN flux_rss f ON r.id_flux = f.id_flux WHERE r.id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res;
    }
}
