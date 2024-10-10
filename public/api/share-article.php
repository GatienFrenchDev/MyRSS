<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_POST["id_article"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_flux parameter needed"]));
}

if (!isset($_POST["id_utilisateur"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_utilisateur parameter needed"]));
}

$id_article = $_POST["id_article"];
$id_destinataire = $_POST["id_utilisateur"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if($id_destinataire == $id_utilisateur) {
    http_response_code(400);
    die(json_encode(["error" => "cannot share article with yourself"]));
}

if (!is_numeric($id_article) || !is_numeric($id_destinataire)) {
    http_response_code(400);
    die(json_encode(["error" => "id_article and id_utilisateur must be numeric"]));
}

require_once __DIR__ . "/../../src/model/EspaceModel.php";

if(!EspaceModel::possedeUnEspaceEnCommun($id_utilisateur, $id_destinataire)) {
    http_response_code(403);
    die(json_encode(["error" => "forbidden"]));
}

require_once __DIR__ . "/../../src/model/NotificationModel.php";
require_once __DIR__ . "/../../src/model/ArticleModel.php";


$article = null;

try{
    $article = ArticleModel::getArticle($id_article);
} catch(ArticleNotFoundException $e) {
    http_response_code(404);
    die(json_encode(["error" => "article not found"]));
}

NotificationModel::partagerArticle($article, $id_utilisateur, $id_destinataire);
die(json_encode(["success" => "article shared"]));
