<?php

session_start();

$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($id_utilisateur)) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_espace"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_espace parameter needed"]));
}

if (!isset($_GET["email"])) {
    http_response_code(400);
    die(json_encode(["error" => "email parameter needed"]));
}

$id_espace = $_GET["id_espace"];
$email = $_GET["email"];

require_once "../model/EspaceModel.php";
require_once "../model/InvitationModel.php";

if(!EspaceModel::appartientA($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
}

if(!InvitationModel::creerInvitation($email, $id_espace, $id_utilisateur)){
    http_response_code(400);
    die(json_encode(["error" => "email does not exist in our db"]));
}
die(json_encode(["success" => "invitation sent successfully"]));