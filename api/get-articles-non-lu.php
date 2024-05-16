<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if(!isset($_GET["numero_page"])){
    http_response_code(400);
    die(json_encode(["error" => "numero_page parameter required"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];
$numero_page = $_GET["numero_page"];

if(!ctype_digit($numero_page)){
    http_response_code(400);
    die(json_encode(["error" => "numero_page must be a number"]));
}

require_once "../model/UtilisateurModel.php";

$articles = UtilisateurModel::getArticlesNonLu($id_utilisateur, $numero_page);
die(json_encode(["articles" => $articles]));
