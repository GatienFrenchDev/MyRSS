<?php
/*
Description : retourne tous les articles inscrits sous cette catégorie

Route :
- /api/get-articles.php

Method :
- GET

Possible parameters :
- id_categorie
- id_espace
- id_flux
*/

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["numero_page"])) {
    http_response_code(400);
    die(json_encode(["error" => "current number of page is required"]));
}

$numero_page = $_GET["numero_page"];

if (!ctype_digit($numero_page)) {
    http_response_code(400);
    die(json_encode(["error" => "numero_page must be a number"]));
}


require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/FluxModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CollectionModel.php";

$id_utilisateur = $_SESSION["id_utilisateur"];

if (isset($_GET["id_categorie"])) {
    $id_categorie = $_GET["id_categorie"];

    if (!CategorieModel::appartientA($id_utilisateur, $id_categorie)) {
        die(json_encode(["error" => "categorie does not belong to you"]));
    }

    die(json_encode(["articles" => CategorieModel::getArticlesInsideCategorie($id_categorie, $numero_page)]));
} else if (isset($_GET["id_espace"])) {
    $id_espace = $_GET["id_espace"];

    if (!EspaceModel::appartientA($id_utilisateur, $id_espace)) {
        die(json_encode(["error" => "espace does not belong to you"]));
    }

    die(json_encode(["articles" => EspaceModel::getArticlesInsideEspace($id_espace, $numero_page)]));
} else if (isset($_GET["id_flux"])) {
    $id_flux = $_GET["id_flux"];
    die(json_encode(["articles" => FluxModel::getArticlesFromFlux($id_flux, $numero_page)]));
} else if (isset($_GET["id_collection"])) {
    $id_collection = $_GET["id_collection"];
    die(json_encode(["articles" => CollectionModel::getArticlesInsideCollection($id_collection, $numero_page)]));
} else {
    die(json_encode(["articles" => UtilisateurModel::getAllArticles($id_utilisateur, $numero_page)]));
}
