<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_categorie"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_categorie parameter needed"]));
}

if (!isset($_GET["nom"])) {
    http_response_code(400);
    die(json_encode(["error" => "nom parameter needed"]));
}

$id_categorie = $_GET["id_categorie"];
$nom = $_GET["nom"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";

if(strlen($nom) > 32){
    http_response_code(414);
    die(json_encode(["error" => "nom parameter too long (max 32 chars)"]));
}

if(!CategorieModel::appartientA($id_utilisateur, $id_categorie)){
    http_response_code(403);
    die(json_encode(["error" => "categorie does not belong to you"]));
}

CategorieModel::renameCategorie($id_categorie, $nom);
die(json_encode(["success" => "Successfully renamed categorie"]));
