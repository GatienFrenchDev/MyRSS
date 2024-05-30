<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/tools.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/FluxModel.php";

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
    if(!isset($_POST["espace"])){
        http_response_code(400);
        die(json_encode(["error" => "missing `espace` parameter"]));
    }

    $id_espace = $_POST["espace"];

    if(isset($_POST["categorie"])){
        $id_categorie = $_POST["categorie"];
        if(!CategorieModel::appartientA($id_utilisateur, $id_categorie)){
            http_response_code(401);
            die(json_encode(["error" => "this category does not belong to you"]));
        }
        CategorieModel::pushNewCategorieToDB($url, $id_categorie, $id_espace);
    }
    else{
        $id_espace = $_POST["espace"];
        if(!EspaceModel::appartientA($id_utilisateur, $id_espace)){
            http_response_code(401);
            die(json_encode(["error" => "this espace does not belong to you"]));
        }
        CategorieModel::pushNewCategorieToDB($url, -1, $id_espace);
    }

    header("Location: /");

}

if (!isset($_POST["categorie"])) {
    http_response_code(400);
    die(json_encode(["error" => "categorie parameter needed"]));
}


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

    // l'ID de la chaine YouTube (ex : `UCsBjURrPoezykLs9EqgamOA`)
    $channel_username = getUsernameFromYouTubeUrl($url);

    // Cas où l'API YouTube ne retrouve pas la chaine YouTube passé en paramètre
    if (is_null($channel_username)) {
        http_response_code(400);
        die(json_encode(["error" => "invalid youtube channel"]));
    }

    $url = "https://www.youtube.com/feeds/videos.xml?channel_id=" . getIDFromYoutubeChannel($channel_username);
    if (!FluxModel::isFluxRSSindb($url)) {
        $id_flux = FluxModel::ajouterFluxRSSindb($url, $type_flux);
        CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
    }
}

// Cas où le flux rss est un sujet google news
else if($type_flux == "google-news"){
    $url = "https://news.google.com/rss/search?hl=fr&gl=FR&ceid=FR%3Afr&oc=11&q=ukraine?gl=FR&ceid=FR%253Afr&hl=fr&q=".urlencode($url);
    $id_flux = null;
    if(FluxModel::isFluxRSSindb($url)){
        $id_flux = FluxModel::getIDFromURL($url);
    }
    else{
        $id_flux = FluxModel::ajouterFluxRSSindb($url, $type_flux);
    }
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
}

// Cas où le flux rss est un sujet bing news
else if($type_flux == "bing-news"){
    $url = "https://www.bing.com/news/search?q=".urlencode($url)."%20loc%3aFR&qft=sortbydate%3d%221%22&format=RSS";
    $id_flux = null;
    if(FluxModel::isFluxRSSindb($url)){
        $id_flux = FluxModel::getIDFromURL($url);
    }
    else{
        $id_flux = FluxModel::ajouterFluxRSSindb($url, $type_flux);
    }
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
}

// Cas où le flux RSS est un flux RSS natif
else if ($type_flux == "rss") {
    $id_flux = FluxModel::ajouterFluxRSSindb($url, $type_flux);
    CategorieModel::addRSSFluxToCategorie($id_flux, $id_categorie);
}

header("Location: ../index.php");
exit;
