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
        "placeholder" => "https://exemple.com/feed",
    ],

    "yt" =>  [
        "titre" => "YouTube",
        "description" => "Entrez l'identifiant de la chaine YouTube",
        "placeholder" => "https://www.youtube.com/@nobodyplaylists/"
    ],

];


if (!isset($_GET["type"])) {
    http_response_code(400);
    header("Location : index.php");
    exit;
}

if (!isset($_GET["id_categorie"])) {
    http_response_code(400);
    header("Location : index.php");
    exit;
}

$nom_type = $_GET["type"];
$id_categorie = $_GET["id_categorie"];

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
