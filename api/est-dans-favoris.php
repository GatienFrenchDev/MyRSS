<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["id_article"])) {
    die(json_encode(["error" => "id_article parameter needed"]));
    exit;
}


$id_article = $_GET["id_article"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/ArchiveModel.php";

die(json_encode(["res" => ArchiveModel::appartientAuxFavoris($id_utilisateur, $id_article)]));