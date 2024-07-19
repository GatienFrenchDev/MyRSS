<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_POST["id_categorie"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_categorie parameter needed"]));
}

if (!isset($_POST["id_flux"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_flux parameter needed"]));
}

if (!isset($_POST["nom"])) {
    http_response_code(400);
    die(json_encode(["error" => "nom parameter needed"]));
}

$id_categorie = $_POST["id_categorie"];
$id_flux = $_POST["id_flux"];
$nom = $_POST["nom"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";

$nom = substr($nom, 0, 32);

if(!CategorieModel::appartientA($id_utilisateur, $id_categorie)){
    http_response_code(403);
    die(json_encode(["error" => "not enough rights to rename this categorie"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/FluxModel.php";

FluxModel::renameFlux($id_flux, $id_categorie, $nom);
die(json_encode(["success" => "Successfully renamed flux"]));
