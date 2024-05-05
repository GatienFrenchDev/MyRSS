<?php

session_start();

$id_utilisateur = $_SESSION["user_id"];

if (!isset($id_utilisateur)) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["id_espace"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_espace parameter needed"]));
    exit;
}

if (!isset($_GET["email"])) {
    http_response_code(400);
    die(json_encode(["error" => "email parameter needed"]));
    exit;
}

$id_espace = $_GET["id_espace"];
$email = $_GET["email"];

require "../model/model.php";

if(!espaceAppartientA($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
    exit;
}

if(!creerInvitation($email, $id_espace, $id_utilisateur)){
    http_response_code(400);
    die(json_encode(["error" => "email does not exist in our db"]));
    exit;
}