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

// vérification que ce soit un nombre
if(!ctype_digit($numero_page)){
    http_response_code(400);
    header("Location: recherche");
    exit;
}
$numero_page = intval($numero_page);

$params = parse_url($_SERVER["REQUEST_URI"]);
parse_str($params["query"], $query);

require_once "./model/ArticleModel.php";
require_once "./lib/tools.php";

// vérifie que le parametre `debut` soit bien au format yyyy-mm-dd si il est défini
if(isset($_GET["debut"])){
    if(!correctFormatForFormDate($_GET["debut"]) && $_GET["debut"] != ""){
        http_response_code(400);
        header("Location: recherche");
        exit;
    }
}

// vérifie que le parametre `fin` soit bien au format yyyy-mm-dd si il est défini
if(isset($_GET["fin"])){
    if(!correctFormatForFormDate($_GET["fin"]) && $_GET["fin"] != ""){
        http_response_code(400);
        header("Location: recherche");
        exit;
    }
}

$articles = ArticleModel::rechercheAvancee($query, $id_utilisateur);

// le mot qui est recherché par la personne
$mot_recherche = isset($_GET["text"])?urldecode($_GET["text"]):"";

$url_page_precedente = "";
$url_page_suivante = "";

$parametre_href_url_page_precedente = "";
$parametre_href_url_page_suivante = "";

if(count($articles) > 99){
    $url_page_suivante = str_replace("numero-page=".$numero_page, "numero-page=".intval($numero_page)+1, $_SERVER["REQUEST_URI"]);
    $parametre_href_url_page_suivante = 'href="'.$url_page_suivante.'"';
}

if($numero_page > 0){
    $url_page_precedente = str_replace("numero-page=".$numero_page, "numero-page=".intval($numero_page)-1, $_SERVER["REQUEST_URI"]);
    $parametre_href_url_page_precedente = 'href="'.$url_page_precedente.'"';
}



require_once "view/components/side-bar.php";
require_once "view/resultat.php";
