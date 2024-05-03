<?php

/*

Description : retourne tous les articles inscrits sous cette catégorie

Route :
- /api/get-articles.php

Method :
- GET

Parameters :
- id_categorie
- id_espace
- id_flux
*/

session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["numero_page"])) {
    http_response_code(400);
    die(json_encode(["error" => "current number of page is required"]));
    exit;
}

$numero_page = $_GET["numero_page"];

if(!ctype_digit($numero_page)){
    http_response_code(400);
    die(json_encode(["error" => "numer_page must be a number"]));
    exit;
}


require "../model/model.php";

$id_utilisateur = $_SESSION["user_id"];

if (isset($_GET["id_categorie"])) {
    $id_categorie = $_GET["id_categorie"];
    
    if(!categorieAppartientA($id_utilisateur, $id_categorie)){
        die(json_encode(["error" => "categorie does not belong to you"]));
        exit;
    }

    die(json_encode(["articles" => getArticlesInsideCategorie($id_categorie, $numero_page)]));
    exit;
}

else if (isset($_GET["id_espace"])){
    $id_espace = $_GET["id_espace"];
    
    if(!espaceAppartientA($id_utilisateur, $id_espace)){
        die(json_encode(["error" => "espace does not belong to you"]));
        exit;
    }

    die(json_encode(["articles" => getArticlesInsideEspace($id_espace, $numero_page)]));
    exit;
}

else if (isset($_GET["id_flux"])){
    $id_flux = $_GET["id_flux"];
    die(json_encode(["articles" => getArticlesFromFlux($id_flux)]));
    exit;
}

else{
    die(json_encode(["articles" => getAllArticles($id_utilisateur, $numero_page)]));
    exit;
}
