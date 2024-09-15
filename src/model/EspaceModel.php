<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/classes/Database.php";

class EspaceNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Espace not found");
    }
}

class EspaceModel
{
    static function getArticlesInsideEspace(int $id_espace, int $numero_page): array
    {
        $mysqli = Database::connexion();

        $numero_page *= 100;

        $stmt = $mysqli->prepare("SELECT a.*, f.*, 
    CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu,
    CASE WHEN et.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_traite
    FROM article a
    INNER JOIN flux_rss f ON a.id_flux = f.id_flux
    INNER JOIN contient c ON c.id_flux = f.id_flux
    LEFT JOIN est_lu el ON a.id_article = el.id_article
    LEFT JOIN est_traite et ON a.id_article = et.id_article
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
        $mysqli = Database::connexion();
        $stmt = $mysqli->prepare("UPDATE espace SET nom = ? WHERE id_espace = ?");
        $stmt->bind_param("si", $nom, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function deleteEspace(int $id_espace): void
    {
        $mysqli = Database::connexion();
        $stmt = $mysqli->prepare("DELETE FROM espace WHERE id_espace = ?");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function getCategoriesFromEspace(int $id_espace): array
    {

        require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";

        $mysqli = Database::connexion();

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

    static function hasReadRights(int $id_user, int $id_espace): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM contient_des WHERE id_utilisateur = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_user, $id_espace);
        $stmt->execute();
        $stmt->store_result();
        $res = $stmt->num_rows();

        $stmt->close();
        $mysqli->close();

        return $res != 0;
    }

    /**
     * Créer un nouvel espace et le lie à l'utilisateur.
     */
    static function createNew(string $nom, int $id_utilisateur): int
    {
        $mysqli = Database::connexion();


        $stmt = $mysqli->prepare("INSERT INTO espace (nom) VALUES (?)");
        $stmt->bind_param("s", $nom);
        $stmt->execute();
        $id_espace = $mysqli->insert_id;
        $stmt->close();

        $role = "admin";

        $stmt = $mysqli->prepare("INSERT INTO contient_des (id_utilisateur, id_espace, role) VALUES (?, ?, ?)");
        $stmt->bind_param("ii", $id_utilisateur, $id_espace, $role);
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
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT nom FROM espace WHERE id_espace = ?");
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

    static function hasWriteRights(int $id_utilisateur, int $id_espace): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT id_espace FROM contient_des WHERE id_espace = ? AND id_utilisateur = ? AND role != 'reader'");
        $stmt->bind_param("ii", $id_espace, $id_utilisateur);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return count($res) > 0;
    }

    /**
     * Retourne vrai seulement si l'espace a été créé par l'utilisateur
     */
    static function estProprio(int $id_utilisateur, int $id_espace): bool
    {
        $mysqli = Database::connexion();

        $role = "admin";

        $stmt = $mysqli->prepare("SELECT COUNT(id_espace) FROM contient_des WHERE id_espace = ? AND id_utilisateur = ? AND role = ?");
        $stmt->bind_param("iis", $id_espace, $id_utilisateur, $role);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return count($res) > 0;
    }

    static function getDetails(int $id_espace): array | EspaceNotFoundException
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT e.*, COUNT(c.id_utilisateur) AS nb_utilisateurs FROM espace e INNER JOIN contient_des c ON e.id_espace = c.id_espace WHERE e.id_espace = ? GROUP BY e.id_espace");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        if (count($res) == 0) {
            return new EspaceNotFoundException();
        }

        return $res[0];
    }

    /**
     * Permet de faire quitter l'espace à un user.
     * NOTE : ne supprime pas l'espace, l'user n'est juste plus associé à cette espace.
     * A utiliser théoriquement seulement lorsqu'un user veut quitter un espace au quel il avait été invité
     */
    static function quitterEspace(int $id_utilisateur, int $id_espace): void
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM contient_des WHERE id_utilisateur = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_utilisateur, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function getAll(): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT e.*, COUNT(c.id_utilisateur) AS nb_utilisateurs FROM espace e INNER JOIN contient_des c ON e.id_espace = c.id_espace GROUP BY e.id_espace ORDER BY article_wp DESC, nb_utilisateurs DESC, e.nom ASC");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    /**
     * Retoure TOUS les articles contenu dans un espace
     */
    static function getAllArticles(int $id_espace): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT a.id_article, a.titre, a.description, a.url_article, a.date_pub AS date_publication, f.nom AS nom_flux, f.adresse_url AS adresse_flux,
    CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu,
    CASE WHEN et.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_traite
    FROM article a
    INNER JOIN flux_rss f ON a.id_flux = f.id_flux
    INNER JOIN contient c ON c.id_flux = f.id_flux
    LEFT JOIN est_lu el ON a.id_article = el.id_article
    LEFT JOIN est_traite et ON a.id_article = et.id_article
    INNER JOIN categorie cat ON cat.id_categorie = c.id_categorie
    WHERE cat.id_espace = ? AND cat.id_parent IS NULL ORDER BY date_pub DESC");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function getParticipants(int $id_espace): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT u.id_utilisateur, u.nom, u.prenom, u.email, c.role FROM utilisateur u
    INNER JOIN contient_des c ON u.id_utilisateur = c.id_utilisateur
    WHERE c.id_espace = ? ORDER BY c.role ASC");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return $res;
    }

    static function hasAccessToWP(int $id_espace):bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT id_espace FROM espace WHERE id_espace = ? AND article_wp = 1");
        $stmt->bind_param("i", $id_espace);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return count($res) > 0;
    }
}
