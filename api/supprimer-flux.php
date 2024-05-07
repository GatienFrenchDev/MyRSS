<?php

session_start();

$id_flux = $_GET["id_flux"];
$id_categorie = $_GET["id_categorie"];
$id_utilisateur = $_SESSION["user_id"];

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["id_flux"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_flux parameter needed"]));
    exit;
}

if (!isset($_GET["id_categorie"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_categorie parameter needed"]));
    exit;
}

require_once "../model/CategorieModel.php";

if(!CategorieModel::categorieAppartientA($id_utilisateur, $id_categorie)){
    http_response_code(403);
    die(json_encode(["error" => "this categorie does not belong to you"]));
    exit;
}

CategorieModel::removeFluxFromCategorie($id_flux, $id_categorie);