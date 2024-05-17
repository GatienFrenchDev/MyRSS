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
$id_utilisateur = $_SESSION["id_utilisateur"];

$params = parse_url($_SERVER["REQUEST_URI"]);
parse_str($params["query"], $query);

/*
!!! Faire vérif si debut et fin sont bien au format dd-mm-yyyy !!!
*/

require_once "./model/ArticleModel.php";

$articles = ArticleModel::rechercheAvancee($query, $id_utilisateur);

$mot_recherche = isset($_GET["text"])?urldecode($_GET["text"]):"";

require_once "view/components/side-bar.php";
require_once "view/resultat.php";
