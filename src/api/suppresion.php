<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!(isset($_GET["id_categorie"]) xor isset($_GET["id_espace"]))) {
    http_response_code(400);
    die(json_encode(["error" => "id_categorie or id_espace parameter needed"]));
}

require_once "../model/EspaceModel.php";
require_once "../model/CategorieModel.php";

$id_utilisateur = $_SESSION["id_utilisateur"];

if (isset($_GET["id_categorie"])) {

    $id_categorie = $_GET["id_categorie"];

    if (!CategorieModel::appartientA($id_utilisateur, $id_categorie)) {
        http_response_code(403);
        die(json_encode(["error" => "categorie does not belong to you"]));
    }

    CategorieModel::deleteCategorie($id_categorie);

    die(json_encode(["success" => "Successfully deleted categorie"]));
}

$id_espace = $_GET["id_espace"];

if (!EspaceModel::estProprio($id_utilisateur, $id_espace)) {
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
}

EspaceModel::deleteEspace($id_espace);

die(json_encode(["success" => "Successfully deleted espace"]));
