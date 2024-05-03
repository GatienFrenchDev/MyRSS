<?php

require "../model/model.php";
require "../lib/tools.php";

session_start();

// Cas où l'utilisateur n'est pas connecté
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    die(json_encode(["error" => "login needed"]));
    exit;
}

// Cas où il manque un paramètre dans la requête
if (!(isset($_POST["adresse"]) && isset($_POST["categorie"]) && isset($_POST["type_flux"]))) {
    http_response_code(400);
    die(json_encode(["error" => "missing parameters"]));
    exit;
}


$id_utilisateur = $_SESSION["user_id"];
$id_categorie = $_POST["categorie"];
$url = $_POST["adresse"];
$type_flux = $_POST["type_flux"];


// Cas où l'url passé en paramètre n'est pas valide
if(!filter_var($url, FILTER_VALIDATE_URL)){
    http_response_code(400);
    die(json_encode(["error" => "url parameter is not a valid url"]));
    exit;
}

// Cas où la catégorie passé en paramètre n'appartient pas à l'utilisateur
if (!categorieAppartientA($id_utilisateur, $id_categorie)) {
    http_response_code(403);
    die(json_encode(["error" => "id_categorie does not belong to you"]));
    exit;
}

// Cas où le flux RSS est déjà dans la db
if (isFluxRSSindb($url)) {
    $id_flux = getIDFromURL($url);
    addRSSFluxToCategorie($id_flux, $id_categorie);
    header("Location: ../index.php");
    exit;
}

// Cas où le flux RSS est un flux YouTube
if ($type_flux == "yt") {

    // Cas où l'url n'est pas un URL d'une chaine YouTube
    if (!str_starts_with($url, "https://www.youtube.com/")) {
        http_response_code(400);
        die(json_encode(["error" => "adresse parameter incorrect"]));
        exit;
    }

    // l'ID de la chaine YouTube (ex : `UCsBjURrPoezykLs9EqgamOA`)
    $channel_username = getUsernameFromYouTubeUrl($url);

    // Cas où l'API YouTube ne retrouve pas la chaine YouTube passé en paramètre
    if (is_null($channel_username)) {
        http_response_code(400);
        die(json_encode(["error" => "invalid youtube channel"]));
        exit;
    }

    $url = "https://www.youtube.com/feeds/videos.xml?channel_id=" . getIDFromYoutubeChannel($channel_username);
    $id_flux = ajouterFluxRSSindb($url, $type_flux);
    addRSSFluxToCategorie($id_flux, $id_categorie);
}

// Cas où le flux RSS est un flux RSS natif
else if($type_flux == "rss"){
    $id_flux = ajouterFluxRSSindb($url, $type_flux);
    addRSSFluxToCategorie($id_flux, $id_categorie);
}

header("Location: ../index.php");
exit;
