<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_POST["id_article"])) {
    die(json_encode(["error" => "id_article parameter needed"]));
    exit;
}

$id_article = $_POST["id_article"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/ArchiveModel.php";

// si l'article doit être supprimé des favoris (lors de la vérification on l'ajoute aux favoris)
if(!ArchiveModel::addToFavorites($id_utilisateur, $id_article)){
    ArchiveModel::removeFromFavorites($id_utilisateur, $id_article);
}
