<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if(!isset($_GET["numero-page"])){
    http_response_code(400);
    die(json_encode(["error" => "numero-page parameter is missing"]));
}

$numero_page = $_GET["numero-page"];
$id_utilisateur = $_SESSION["id_utilisateur"];

// vérification que ce soit un nombre
if(!ctype_digit($numero_page)){
    http_response_code(400);
    die(json_encode(["error" => "numero-page parameter must be a number"]));
}
$numero_page = intval($numero_page);

$params = parse_url($_SERVER["REQUEST_URI"]);
parse_str($params["query"], $query);

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/ArticleModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src//lib/tools.phpp";

// vérifie que le parametre `debut` soit bien au format yyyy-mm-dd si il est défini
if(isset($_GET["debut"])){
    if(!correctFormatForFormDate($_GET["debut"]) && $_GET["debut"] != ""){
        http_response_code(400);
        die(json_encode(["error" => "debut paramter is not a valid date"]));
    }
}

// vérifie que le parametre `fin` soit bien au format yyyy-mm-dd si il est défini
if(isset($_GET["fin"])){
    if(!correctFormatForFormDate($_GET["fin"]) && $_GET["fin"] != ""){
        http_response_code(400);
        die(json_encode(["error" => "fin paramter is not a valid date"]));
    }
}

die(json_encode(["articles" => ArticleModel::rechercheAvancee($query, $id_utilisateur)]));

