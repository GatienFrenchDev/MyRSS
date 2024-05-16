<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login.php");
    exit;
}

$types = [
    "rss" =>  [
        "titre" => "RSS",
        "description" => "Entrez l'adresse du flux",
        "placeholder" => "https://example.com/feed",
        "type_input" => "url"
    ],

    "yt" =>  [
        "titre" => "YouTube",
        "description" => "Entrez l'identifiant de la chaine YouTube",
        "placeholder" => "https://www.youtube.com/@Apple",
        "type_input" => "url"
    ],

    "categorie" =>  [
        "titre" => "Catégorie",
        "description" => "Entrez le nom de la catégorie à créer",
        "placeholder" => "Actualités",
        "type_input" => "text"
    ],

    "google-news" =>  [
        "titre" => "Google News",
        "description" => "Entrez votre recherche Google News",
        "placeholder" => "Guerre Ukraine",
        "type_input" => "text"
    ],

];

if(!isset($_GET["id_espace"])){
    http_response_code(400);
    header("Location : /");
    exit;
}

if (!isset($_GET["type"])) {
    http_response_code(400);
    header("Location : /");
    exit;
}


$id_espace = $_GET["id_espace"];
$id_categorie = null;

if(isset($_GET["id_categorie"])){
    $id_categorie = $_GET["id_categorie"];
}


$nom_type = $_GET["type"];
$url_btn_retour = is_null($id_categorie)?"choix-content.php?id_espace=".$id_espace:"choix-content.php?id_espace=".$id_espace."&id_categorie=".$id_categorie;


require_once "model/UtilisateurModel.php";


if (array_key_exists($nom_type, $types)) {
    $type = $types[$nom_type];
    require_once "view/components/side-bar.php";
    require_once "view/ajout-content.php";
} else {
    http_response_code(400);
    header("Location : index.php");
    exit;
}
