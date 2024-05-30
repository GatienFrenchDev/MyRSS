<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_POST["id_article"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_article parameter needed"]));
}

if (!isset($_POST["id_espace"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_espace parameter needed"]));
}


$id_article = $_POST["id_article"];
$id_espace = $_POST["id_espace"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/ArticleModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";

if(!EspaceModel::appartientA($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "this espace does not belong to you"]));
}


if(!ArticleModel::marquerCommeTraite($id_article, $id_espace)){
    ArticleModel::removeFromTraite($id_article, $id_espace);
}