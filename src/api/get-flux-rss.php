<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_categorie"])) {
    die(json_encode(["error" => "id_categorie parameter needed"]));
}

$id_categorie = $_GET["id_categorie"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/CategorieModel.php";

if(!CategorieModel::appartientA($id_utilisateur, $id_categorie)){
    http_response_code(403);
    die(json_encode(["error" => "categorie does not belong to you"]));
}

$flux_rss = CategorieModel::getFluxRSSInsideCategorie($id_categorie);
die(json_encode(["flux_rss" => $flux_rss]));
