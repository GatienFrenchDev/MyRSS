<?php

require $_SERVER['DOCUMENT_ROOT'] . "/lib/Feed.php";
require "Article.php";


function getEspaces(int $user_id): array
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    // ok.. je reconnais que la requête est un peu monstrueuse
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

function getArticlesInsideEspace(int $id_espace, int $numero_page): array
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

function getNombreNonLuInsideCategorie(int $id_categorie): int
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

function getNombreNonLuInsideFlux(int $id_flux): int
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

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

function getArticlesInsideCategorie(int $id_cateogrie, int $numero_page): array
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

function getArticlesFromFlux(int $id_flux): array
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT a.*, f.*, 
    CASE WHEN el.id_article IS NOT NULL THEN 1 ELSE 0 END AS est_lu
    FROM article a
    INNER JOIN flux_rss f ON a.id_flux = f.id_flux
    LEFT JOIN est_lu el ON a.id_article = el.id_article
    WHERE a.id_flux = ? ORDER BY date_pub DESC LIMIT 100");
    $stmt->bind_param("i", $id_flux);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $mysqli->close();

    return $res;
}

function setArticleLu(int $id_article, int $id_espace): int
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

function setArticleNonLu(int $id_article, int $id_espace): void
{
    $mysqli = require "../includes/database.inc.php";

    $stmt = $mysqli->prepare("DELETE FROM est_lu WHERE id_article = ? AND id_espace = ?");
    $stmt->bind_param("ii", $id_article, $id_espace);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function rename_espace(int $id_espace, string $nom): void
{
    $mysqli = require "../includes/database.inc.php";
    $stmt = $mysqli->prepare("UPDATE espace_partage SET nom = ? WHERE id_espace = ?");
    $stmt->bind_param("si", $nom, $id_espace);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function rename_categorie(int $id_categorie, string $nom): void
{
    $mysqli = require "../includes/database.inc.php";
    $stmt = $mysqli->prepare("UPDATE categorie SET nom = ? WHERE id_categorie = ?");
    $stmt->bind_param("si", $nom, $id_categorie);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function deleteCategorie(int $id_categorie): void
{
    $mysqli = require "../includes/database.inc.php";
    $stmt = $mysqli->prepare("DELETE FROM categorie WHERE id_categorie = ?");
    $stmt->bind_param("i", $id_categorie);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function deleteEspace(int $id_espace): void
{
    $mysqli = require "../includes/database.inc.php";
    $stmt = $mysqli->prepare("DELETE FROM espace_partage WHERE id_espace = ?");
    $stmt->bind_param("i", $id_espace);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}


function getFluxRSSInsideCategorie(int $id_categorie): array
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
        $ligne["nb_non_lu"] = getNombreNonLuInsideFlux($ligne["id_flux"]);
    }

    $stmt->close();
    $mysqli->close();

    return $res;
}

function updateNomFromFlux(int $id_flux, string $nom): void
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("UPDATE flux_rss SET nom = ? WHERE id_flux = ?");
    $stmt->bind_param("si", $nom, $id_flux);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

/**
 * @param user_id - identifiant de l'utilisateur
 * @param numero_page - premiere page commence à 0 donc $numero_page appartient à [0;+inf[
 */
function getAllArticles(int $user_id, int $numero_page): array
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

function isFluxRSSindb(string $url): bool
{
    $mysqli = require "../includes/database.inc.php";

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
function ajouterFluxRSSindb(string $url, string $type_flux): int
{
    $mysqli = require "../includes/database.inc.php";

    $stmt = $mysqli->prepare("INSERT INTO flux_rss (adresse_url, nom, type_flux) VALUES (?, 'Flux sans nom', ?)");
    $stmt->bind_param("ss", $url, $type_flux);
    $stmt->execute();
    $id_flux = $mysqli->insert_id;
    $stmt->close();
    $mysqli->close();
    return $id_flux;
}


function addRSSFluxToCategorie(int $id_flux, int $id_categorie): void
{
    $mysqli = require "../includes/database.inc.php";

    $stmt = $mysqli->prepare("INSERT INTO contient (id_flux, id_categorie) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_flux, $id_categorie);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getAllCategoriesFromUser(int $id_utilisateur): array
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

// retourne -1 si pas dans db
function getIDFromURL(string $url): int
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

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

function getCategoriesFromEspace(int $id_espace): array
{

    $mysqli = require "../includes/database.inc.php";

    $stmt = $mysqli->prepare("SELECT * FROM categorie WHERE id_espace = ? AND id_parent IS NULL");
    $stmt->bind_param("i", $id_espace);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $mysqli->close();
    foreach ($res as &$categorie) {
        $categorie["nb_non_lu"] = getNombreNonLuInsideCategorie($categorie["id_categorie"]);
    }
    return $res;
}

function getSubCategories(int $id_categorie): array
{

    $mysqli = require "../includes/database.inc.php";

    $stmt = $mysqli->prepare("SELECT * FROM categorie WHERE id_parent = ?");
    $stmt->bind_param("i", $id_categorie);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $mysqli->close();

    foreach ($res as &$categorie) {
        $categorie["nb_non_lu"] = getNombreNonLuInsideCategorie($categorie["id_categorie"]);
    }

    return $res;
}


// Si id_categorie = -1 on renvoi les espaces de la personne
function getParentsCategories(int $id_categorie): array
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    if (!isset($_SESSION["user_id"])) {
        return [];
    }


    if ($id_categorie < 0) {
        return getEspaces($_SESSION["user_id"]);
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

function invitationAppartientA(int $id_user, int $id_invitation): bool
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT * FROM invitation WHERE id_utilisateur = ? AND id_invitation = ?");
    $stmt->bind_param("ii", $id_user, $id_invitation);
    $stmt->execute();
    $stmt->store_result();
    $res = $stmt->num_rows();

    $stmt->close();
    $mysqli->close();

    return $res != 0;
}

function notificationAppartientA(int $id_user, int $id_notification): bool
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT * FROM notification WHERE id_utilisateur = ? AND id_notification = ?");
    $stmt->bind_param("ii", $id_user, $id_notification);
    $stmt->execute();
    $stmt->store_result();
    $res = $stmt->num_rows();

    $stmt->close();
    $mysqli->close();

    return $res != 0;
}


function espaceAppartientA(int $id_user, int $id_espace): bool
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


function categorieAppartientA(int $id_user, int $id_categorie): bool
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

/*
Exemple :
Array ( [0] => Array ( [id_flux] => 1 [adresse_url] => https://www.lemonde.fr/sante/rss_full.xml ) [1] => Array ( [id_flux] => 2 [adresse_url] => https://www.lefigaro.fr/rss/figaro_sante.xml ) )
*/
function getAllRSSFlux(): array
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");
    $sql = "SELECT nom, id_flux, adresse_url FROM flux_rss";
    $res = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();
    return $res;
}

function insertArticleIntoDB(Article $article, int $id_flux): int
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

function getArticlesFromRSSFlux(int $id_flux, string $url): array
{
    $rss = null;
    $articles = [];

    if (str_starts_with($url, "https://www.youtube.com/feeds/videos.xml?channel_id=")) {
        $videos = [];

        $xml = new DOMDocument();
        $xml->load($url);

        updateNomFromFlux($id_flux, $xml->getElementsByTagName("title")->item(0)->nodeValue);

        foreach($xml->getElementsByTagName("entry") as $node){
            $titre = $node->getElementsByTagName('title')->item(0)->nodeValue;
            $titre = substr($titre, 0, 255);
            $description = $node->getElementsByTagName('description')->item(0)->nodeValue;
            $description = substr($description, 0, 255);
            $lien = $node->getElementsByTagName('link')->item(0)->getAttribute('href');
            $date_pub = (int) strtotime($node->getElementsByTagName('published')->item(0)->nodeValue);
            $videos[] = new Article($titre, $description, $lien, $date_pub);
        }

        return $videos;
    }

    try {
        $rss = Feed::loadRss($url);
    } catch (\Throwable $th) {
        return $articles;
    }

    updateNomFromFlux($id_flux, $rss->title);

    foreach ($rss->item as $item) {
        $ts = intval($item->timestamp);
        if ($ts > time()) {
            $ts = time();
        }
        if ($ts < 946681200) {
            $ts = 946681200;
        }
        $articles[] = new Article(
            strip_tags($item->title, "<br><b><i>"),
            strip_tags($item->description, "<br><b><i>"),
            $item->link,
            $ts
        );
    }

    return $articles;
}

/*
Si categorie parent null alors mettre $id_categorie_parent à -1.
Renvoi id de la categorie qui vient d'etre crée
*/
function pushNewCategorieToDB(string $nom, int $id_categorie_parent, int $id_espace): int
{
    $mysqli = require "../includes/database.inc.php";

    if ($id_categorie_parent <= 0) {
        $stmt = $mysqli->prepare("INSERT INTO categorie (nom, id_espace) VALUES (?, ?)");
        $stmt->bind_param("si", $nom, $id_espace);
        $stmt->execute();
        $id_categorie = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_categorie;
    } else {
        $stmt = $mysqli->prepare("INSERT INTO categorie (nom, id_espace, id_parent) VALUES ('?', ?, ?)");
        $stmt->bind_param("sii", $nom, $id_espace, $id_categorie_parent);
        $stmt->execute();
        $id_categorie = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_categorie;
    }
}


function pushNewEspaceToDB(string $nom, int $id_utilisateur): int
{
    $mysqli = require "../includes/database.inc.php";


    $stmt = $mysqli->prepare("INSERT INTO espace_partage (nom) VALUES (?)");
    $stmt->bind_param("s", $nom);
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

function accepterInvitation(int $id_invitation): void
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT * FROM invitation WHERE id_invitation = ?");
    $stmt->bind_param("i", $id_invitation);
    $stmt->execute();
    $ligne = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

    $id_utilisateur = $ligne["id_utilisateur"];
    $id_espace = $ligne["id_espace"];

    $stmt = $mysqli->prepare("DELETE FROM invitation WHERE id_invitation = ?");
    $stmt->bind_param("i", $id_invitation);
    $stmt->execute();

    $stmt = $mysqli->prepare("INSERT INTO contient_des (id_utilisateur, id_espace) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_utilisateur, $id_espace);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
}

/**
 * Renvoi vrai si l'invitation a bien été envoyée.
 */
function creerInvitation(string $email, int $id_espace, int $id_utilisateur_inviteur): bool
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $ligne = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // si l'utilisateur n'existe pas
    if (count($ligne) == 0) {
        return false;
    }

    $ligne = $ligne[0];

    $id_utilisateur = $ligne["id_utilisateur"];

    $stmt = $mysqli->prepare("INSERT INTO invitation (id_utilisateur, id_espace, id_utilisateur_inviteur) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id_utilisateur, $id_espace, $id_utilisateur_inviteur);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();

    return true;
}

function getInvitations(int $id_utilisateur): array
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
        $res[$i]["invitateur"] = getUserDetailsFromId($res[$i]["id_utilisateur_inviteur"]);
    }
    $stmt->close();
    $mysqli->close();
    return $res;
}

function refuserInvitation(int $id_invitation)
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("DELETE FROM invitation WHERE id_invitation = ?");
    $stmt->bind_param("i", $id_invitation);
    $stmt->execute();
}

function getDernierUrlArticle(int $id_flux): Article | null
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT * FROM article WHERE id_flux = ? ORDER BY date_pub DESC LIMIT 1");
    $stmt->bind_param("i", $id_flux);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) == 0) {
        return null;
    }

    $res = $res[0];

    return new Article($res["titre"], $res["description"], $res["url_article"], $res["date_pub"]);
}


/**
 * Renvoie une liste vide si l'user existe pas.
 * Exemple : getUserDetailsFromId(1) renvoie `Array ( [id_utilisateur] => 1 [nom] => Doe [prenom] => John [email] => john@exemple.com [date_inscription] => 1714580239 )`
 */
function getUserDetailsFromId(int $id_utilisateur): array
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
 * Permet de vérifier si un flux rss existe avec l'id passé en paramètre.
 * e.g. : `rssFluxExist(13)` renvoie `true` si il existe un flux rss dans la db avec l'id 13
 * @param id_flux - l'identifiant à tester
 */
function rssFluxExist(int $id_flux): bool
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("SELECT COUNT(id_flux) as nb_fluxs FROM flux_rss WHERE id_flux = ?;");
    $stmt->bind_param("i", $id_flux);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $mysqli->close();
    return count($res) > 0;
}

/**
 * Permet de vérifier si un utilisateur existe avec l'id passé en paramètre.
 * e.g. : `userExist(13)` renvoie `true` si il existe un utilisateur dans la db avec l'id 13
 * @param id_utilisateur - l'identifiant à tester
 */
function userExist(int $id_utilisateur): bool
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

function sendNotification(int $id_destinataire, string $titre, string $message): void
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("INSERT INTO `notification` (`titre`, `description`, `id_utilisateur`) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $titre, $message, $id_destinataire);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}


/**
 * Exemple : getUserDetailsFromMail(`john@exemple.com`) renvoie `Array ( [id_utilisateur] => 1 [nom] => Doe [prenom] => John [email] => john@exemple.com [date_inscription] => 1714580239 )`
 * Renvoie une liste vide si l'user existe pas.
 */
function getUserDetailsFromMail(string $mail): array
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

function getNotifications(int $id_utilisateur): array
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

function getFluxDetailsFromId(int $id_flux): array
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

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

function deleteNotification(int $id_notification): void
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("DELETE FROM notification WHERE id_notification = ?");
    $stmt->bind_param("i", $id_notification);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function removeFluxFromCategorie(int $id_flux, int $id_categorie)
{
    $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

    $stmt = $mysqli->prepare("DELETE FROM contient WHERE id_flux = ? AND id_categorie = ?");
    $stmt->bind_param("ii", $id_flux, $id_categorie);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
