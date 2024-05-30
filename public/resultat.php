<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
    exit;
}

if(!isset($_GET["numero-page"])){
    http_response_code(400);
    header("Location: recherche");
    exit;
}

$numero_page = $_GET["numero-page"];

// vérification que `$numero_page` soit un nombre
if(!ctype_digit($numero_page)){
    http_response_code(400);
    header("Location: recherche");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/views/components/side-bar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/views/resultat.php";
