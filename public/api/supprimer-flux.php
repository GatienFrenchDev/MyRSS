<?php

session_start();

$id_flux = $_GET["id_flux"];
$id_categorie = $_GET["id_categorie"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_flux"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_flux parameter needed"]));
}

if (!isset($_GET["id_categorie"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_categorie parameter needed"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";

if(!CategorieModel::appartientA($id_utilisateur, $id_categorie)){
    http_response_code(403);
    die(json_encode(["error" => "this categorie does not belong to you"]));
}

CategorieModel::removeFluxFromCategorie($id_flux, $id_categorie);