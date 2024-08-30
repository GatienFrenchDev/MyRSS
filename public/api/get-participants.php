<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(400);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_espace"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_espace parameter is needed"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];
$id_espace = $_GET["id_espace"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";

if(!EspaceModel::hasReadRights($id_utilisateur, $id_espace)) {
    http_response_code(403);
    die(json_encode(["error" => "you don't have the rights to access this space"]));
}

die(json_encode(["participants" => EspaceModel::getParticipants($id_espace)]));


