<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/tools.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/FluxModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/ArticleModel.php";

session_start();

// Cas où l'utilisateur n'est pas connecté
if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "login needed"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($_POST["type_flux"])) {
    http_response_code(400);
    die(json_encode(["error" => "type_flux parameter needed"]));
}

if (!isset($_POST["adresse"])) {
    http_response_code(400);
    die(json_encode(["error" => "adresse parameter needed"]));
}

$type_flux = $_POST["type_flux"];
$url = $_POST["adresse"];


// Cas où on souhaite créer un dossier (logique à prendre à part car on peut avoir un `id_espace` au lieu d'un `id_categorie`)
if ($type_flux == "categorie") {
    if (!isset($_POST["espace"])) {
        http_response_code(400);
        die(json_encode(["error" => "missing `espace` parameter"]));
    }

    $id_espace = $_POST["espace"];

    if (isset($_POST["categorie"])) {
        $id_categorie = $_POST["categorie"];
        if (!CategorieModel::appartientA($id_utilisateur, $id_categorie)) {
            http_response_code(401);
            die(json_encode(["error" => "this category does not belong to you"]));
        }
        CategorieModel::pushNewCategorieToDB($url, $id_categorie, $id_espace);
    } else {
        $id_espace = $_POST["espace"];
        if (!EspaceModel::hasReadRights($id_utilisateur, $id_espace)) {
            http_response_code(401);
            die(json_encode(["error" => "this espace does not belong to you"]));
        }
        CategorieModel::pushNewCategorieToDB($url, -1, $id_espace);
    }

    http_response_code(201);
    header("Location: /");
    exit;
}

if (!isset($_POST["categorie"])) {
    http_response_code(400);
    die(json_encode(["error" => "categorie parameter needed"]));
}

$id_flux = null;


$id_utilisateur = $_SESSION["id_utilisateur"];
$id_categorie = $_POST["categorie"];

// Cas où la catégorie passé en paramètre n'appartient pas à l'utilisateur
if (!CategorieModel::appartientA($id_utilisateur, $id_categorie)) {
    http_response_code(403);
    die(json_encode(["error" => "id_categorie does not belong to you"]));
}

// Cas où le flux RSS est déjà dans la db
if (FluxModel::isFluxRSSindb($url)) {
    $id_flux = FluxModel::getIDFromURL($url);
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
    header("Location: ../");
    exit;
}

// Cas où le flux RSS est un flux YouTube
if ($type_flux == "yt") {

    // Cas où l'url n'est pas un URL d'une chaine YouTube
    if (!str_starts_with($url, "https://www.youtube.com/")) {
        http_response_code(400);
        die(json_encode(["error" => "adresse parameter incorrect"]));
    }

    // le nom d'utilisateur de la chaine YouTube (ex: code pour https://www.youtube.com/@code)
    $channel_username = getUsernameFromYouTubeUrl($url);

    // Cas où l'API YouTube ne retrouve pas la chaine YouTube passé en paramètre
    if (is_null($channel_username)) {
        http_response_code(400);
        die(json_encode(["error" => "invalid youtube channel"]));
    }

    $url = "https://www.youtube.com/feeds/videos.xml?channel_id=" . getIDFromYoutubeChannel($channel_username);
    if (!FluxModel::isFluxRSSindb($url)) {
        $id_flux = FluxModel::insertFeedIntoDB($url, $type_flux);
        CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
    }
}

// Cas où le flux rss est un sujet google news
else if ($type_flux == "google-news") {
    $url = "https://news.google.com/rss/search?hl=fr&gl=FR&ceid=FR%3Afr&oc=11?gl=FR&ceid=FR%253Afr&hl=fr&q=" . urlencode($url);
    $id_flux = null;
    if (FluxModel::isFluxRSSindb($url)) {
        $id_flux = FluxModel::getIDFromURL($url);
    } else {
        $id_flux = FluxModel::insertFeedIntoDB($url, $type_flux);
    }
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
}

// Cas où le flux rss est un sujet bing news
else if ($type_flux == "bing-news") {
    $url = "https://www.bing.com/news/search?q=" . urlencode($url) . "%20loc%3aFR&qft=sortbydate%3d%221%22&format=RSS";
    $id_flux = null;
    if (FluxModel::isFluxRSSindb($url)) {
        $id_flux = FluxModel::getIDFromURL($url);
    } else {
        $id_flux = FluxModel::insertFeedIntoDB($url, $type_flux);
    }
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
}

// Cas où le flux rss est un flux rss classique
else if ($type_flux == "rss") {
    $id_flux = FluxModel::insertFeedIntoDB($url, $type_flux);
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
}

else {
    http_response_code(400);
    die(json_encode(["error" => "type_flux parameter incorrect"]));
}

if($id_flux == null) {
    header("Location: ../");
    exit;
}

$articles = getArticlesFromRSSFlux($id_flux, $url);

if($articles == null) {
    header("Location: ../");
    exit;
}

// On récupère le dernier article en date du flux rss courant stocké dans la db
$dernier_article = FluxModel::getDernierUrlArticle($id_flux);
$dernier_url_en_date = "";
// On procéde ensuite par comparaison via l'url de l'article.
// On considère que si 2 articles ont le même url d'article alors ils sont identiques.
if (!is_null($dernier_article)) {
    $dernier_url_en_date = $dernier_article->getUrlArticle();
}

foreach ($articles as $article) {
    if ($article->getUrlArticle() == $dernier_url_en_date) {
        break;
    }
    ArticleModel::insertArticleIntoDB($article, $id_flux);
}

header("Location: ../index.php");
exit;
