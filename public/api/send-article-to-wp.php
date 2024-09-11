<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_POST["id_espace"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_espace parameter needed"]));
}

if(!isset($_POST["id_article"])){
    http_response_code(400);
    die(json_encode(["error" => "id_article parameter needed"]));
}

if(!isset($_POST["categorie"])){
    http_response_code(400);
    die(json_encode(["error" => "categorie parameter needed"]));
}

$id_espace = $_POST["id_espace"];
$id_article = $_POST["id_article"];
$categorie = $_POST["categorie"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if(!is_numeric($id_espace)){
    http_response_code(400);
    die(json_encode(["error" => "id_espace must be an integer"]));
}

if(!is_numeric($id_article)){
    http_response_code(400);
    die(json_encode(["error" => "id_article must be an integer"]));
}

if(!in_array($categorie, ["brief", "post_ci", "mercato"])){
    http_response_code(400);
    die(json_encode(["error" => "categorie must be one of 'brief', 'post_ci' or 'mercato'"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/classes/WordPress.php";

if(!EspaceModel::hasReadRights($id_utilisateur, $id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "you are not part of this space"]));
}

if(!EspaceModel::hasAccessToWP($id_espace)){
    http_response_code(403);
    die(json_encode(["error" => "you don't have access to the wordpress"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";

$user_details = UtilisateurModel::getUserDetailsFromId($id_utilisateur);
$nom_a_afficher = $user_details["prenom"] . " " . $user_details["nom"];

try{
    WordPress::pushToHTI($id_article, $nom_a_afficher, WordPressCategories::from($categorie));
}
catch(ArticleNotFoundException $e){
    http_response_code(404);
    die(json_encode(["error" => "article not found"]));
}
catch(WordPressException $e){
    http_response_code(500);
    die(json_encode(["error" => "wordpress error"]));
}

http_response_code(201);

die(json_encode(["success" => "Successfully created article in wordpress"]));