<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_article"])) {
    die(json_encode(["error" => "id_article parameter needed"]));
}

if (!isset($_GET["id_collection"])) {
    die(json_encode(["error" => "id_collection parameter needed"]));
}


$id_article = $_GET["id_article"];
$id_collection = $_GET["id_collection"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/CollectionModel.php";

die(json_encode(["res" => CollectionModel::appartientA($id_utilisateur, $id_article, $id_collection)]));