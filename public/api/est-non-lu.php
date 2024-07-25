<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_article"])) {
    die(json_encode(["error" => "id_article parameter needed"]));
}

if (!isset($_GET["id_espace"])) {
    die(json_encode(["error" => "id_espace parameter needed"]));
}

$id_article = $_GET["id_article"];
$id_espace = $_GET["id_espace"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/ArticleModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";

if(!EspaceModel::hasReadRights($id_utilisateur, $id_espace)){
    die(json_encode(["error" => "espace does not belong to you"]));
}

ArticleModel::setArticleNonLu($id_article, $id_espace);