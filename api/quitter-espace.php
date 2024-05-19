<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_espace"])) {
    die(json_encode(["error" => "id_espace parameter needed"]));
}

$id_espace = $_GET["id_espace"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/EspaceModel.php";

if(!EspaceModel::espaceAppartientA($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
}

EspaceModel::quitterEspace($id_utilisateur, $id_espace);
die(json_encode(["success" => "Successfully leaved espace"]));
