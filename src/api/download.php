<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];

if (isset($_GET["id_espace"])) {

    require_once "../model/EspaceModel.php";

    $id_espace = $_GET["id_espace"];

    if(!EspaceModel::appartientA($id_utilisateur, $id_espace)){
        http_response_code(403);
        die(json_encode(["error" => "this espace does not belong to you"]));
    }

    $articles = EspaceModel::getAllArticles($id_espace);

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment;filename=articles.csv");

    $output = fopen('php://output', 'w');

    if (count($articles) > 0) {
        fputcsv($output, array_keys($articles[0]));
    }

    foreach ($articles as $article) {
        fputcsv($output, $article);
    }
    
    fclose($output);

    exit;

}

else if(isset($_GET["id_categorie"])){

    require_once "../model/CategorieModel.php";

    $id_categorie = $_GET["id_categorie"];

    if(!CategorieModel::appartientA($id_utilisateur, $id_categorie)){
        http_response_code(403);
        die(json_encode(["error" => "this category does not belong to you"]));
    }

    $articles = CategorieModel::getAllArticles($id_categorie);

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment;filename=articles.csv");

    $output = fopen('php://output', 'w');

    if (count($articles) > 0) {
        fputcsv($output, array_keys($articles[0]));
    }

    foreach ($articles as $article) {
        fputcsv($output, $article);
    }
    
    fclose($output);

    exit;

}

else if(isset($_GET["id_flux"])){
    require_once "../model/FluxModel.php";

    $id_flux = $_GET["id_flux"];

    $articles = FluxModel::getAllArticles($id_flux);

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment;filename=articles.csv");

    $output = fopen('php://output', 'w');

    if (count($articles) > 0) {
        fputcsv($output, array_keys($articles[0]));
    }

    foreach ($articles as $article) {
        fputcsv($output, $article);
    }
    
    fclose($output);

    exit;
}


http_response_code(400);
die(json_encode(["error" => "missing some parameter"]));

