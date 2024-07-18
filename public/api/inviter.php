<?php

/**
 * Ajoute un collaborateur à un espace.
 */

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

if(!isset($_GET["reader_only"])){
    http_response_code(400);
    die(json_encode(["error" => "reader_only parameter needed"]));
}

$id_espace = $_GET["id_espace"];
$email = $_GET["email"];
$reader_only = $_GET["reader_only"];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    die(json_encode(["error" => "email parameter is not a valid email"]));
}

if(is_nan($id_espace)){
    http_response_code(400);
    die(json_encode(["error" => "id_espace parameter is not a number"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/InvitationModel.php";

if(!EspaceModel::estProprio($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
}

if(!InvitationModel::creerInvitation($email, $id_espace, $id_utilisateur, $reader_only == "true")){
    http_response_code(400);
    die(json_encode(["error" => "email does not exist in our db"]));
}
die(json_encode(["success" => "invitation sent successfully"]));