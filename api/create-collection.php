<?php

// Ex : api/create-new-collection.php?nom=...


session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_POST["nom"])) {
    http_response_code(400);
    die(json_encode(["error" => "missing parameters"]));
}


$nom = $_POST["nom"];

$id_utilisateur = $_SESSION["id_utilisateur"];

if(strlen($nom) > 32){
    http_response_code(414);
    die(json_encode(["error" => "nom parameter too long (max 32 chars)"]));
}

require_once "../model/CollectionModel.php";

die(json_encode(["id_collection" => CollectionModel::createNewCollection($id_utilisateur, $nom)]));
