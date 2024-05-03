<?php

session_start();

if (!isset($_SESSION["user_id"])) {
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

$nom_type = $_GET["type"];

require "model/model.php";


if (array_key_exists($nom_type, $types)) {
    $categories = getAllCategoriesFromUser($_SESSION["user_id"]);
    $type = $types[$nom_type];
    require "view/components/side-bar.php";
    require "view/ajout-content.php";
} else {
    http_response_code(400);
    header("Location : index.php");
    exit;
}
