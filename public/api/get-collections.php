<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];


if(isset($_GET["id_article"])){
    require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CollectionModel.php";

    $id_article = $_GET["id_article"];
    $collections = CollectionModel::getCollections($id_utilisateur, $id_article);
    die(json_encode(["collections" => $collections]));
}
else{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";
    $collections = UtilisateurModel::getAllCollections($id_utilisateur);
    die(json_encode(["collections" => $collections]));
}



