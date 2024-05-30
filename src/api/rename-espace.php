<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_espace"])) {
    die(json_encode(["error" => "id_espace parameter needed"]));
}

if (!isset($_GET["nom"])) {
    die(json_encode(["error" => "nom parameter needed"]));
}

$id_espace = $_GET["id_espace"];
$nom = $_GET["nom"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/EspaceModel.php";

if(strlen($nom) > 32){
    http_response_code(414);
    die(json_encode(["error" => "nom parameter too long (max 32 chars)"]));
}

if(!EspaceModel::estProprio($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
}

EspaceModel::renameEspace($id_espace, $nom);
die(json_encode(["success" => "Successfully renamed espace"]));
