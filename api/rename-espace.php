<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["id_espace"])) {
    die(json_encode(["error" => "id_espace parameter needed"]));
    exit;
}

if (!isset($_GET["nom"])) {
    die(json_encode(["error" => "nom parameter needed"]));
    exit;
}

$id_espace = $_GET["id_espace"];
$nom = $_GET["nom"];
$id_utilisateur = $_SESSION["user_id"];

require "../model/model.php";

if(strlen($nom) > 32){
    http_response_code(414);
    die(json_encode(["error" => "nom parameter too long (max 32 chars)"]));
    exit;  
}

if(!espaceAppartientA($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "espace does not belong to you"]));
    exit;
}

rename_espace($id_espace, $nom);
die(json_encode(["success" => "Successfully renamed espace"]));
exit;
