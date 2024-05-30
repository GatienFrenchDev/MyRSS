<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if(!isset($_GET["id_article"])){
    http_response_code(400);
    die(json_encode(["error" => "id_article parameter is needed"]));
}


$id_utilisateur = $_SESSION["id_utilisateur"];
$id_article = $_GET["id_article"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CollectionModel.php";

$collections = CollectionModel::getCollections($id_utilisateur, $id_article);

die(json_encode(["collections" => $collections]));
