<?php

// Ex : api/get-categories.php?id_espace=... OU api/get-categories.php?id_categorie=...

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(400);
    die(json_encode(["error" => "authentification required"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];

if (isset($_GET["id_espace"])) {

    $id_espace = $_GET["id_espace"];

    require_once "../model/EspaceModel.php";

    if (!EspaceModel::appartientA($id_utilisateur, $id_espace)) {
        die(json_encode(["error" => "espace does not belong to you"]));
    }
    die(json_encode(["categories" => EspaceModel::getCategoriesFromEspace($id_espace)]));
}

if (isset($_GET["id_categorie"])) {

    $id_categorie = $_GET["id_categorie"];

    require_once "../model/CategorieModel.php";

    if (!CategorieModel::appartientA($id_utilisateur, $id_categorie)) {
        die(json_encode(["error" => "categorie does not belong to you"]));
    }
    die(json_encode(["categories" => CategorieModel::getSubCategories($id_categorie)]));
}

die(json_encode(["error" => "espace parameter or categorie parameter is needed"]));
