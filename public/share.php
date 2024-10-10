<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
}

if (!isset($_GET["article"])) {
    http_response_code(400);
    header("Location: /");
}

if (!isset($_GET["espace"])) {
    http_response_code(400);
    header("Location: /");
}

$id_article = $_GET["article"];
$id_espace = $_GET["espace"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if (!is_numeric($id_article) || !is_numeric($id_espace)) {
    http_response_code(400);
    header("Location: /");
}

require_once __DIR__ . "/../src/model/EspaceModel.php";

if (!EspaceModel::hasReadRights($id_utilisateur, $id_espace)) {
    http_response_code(403);
    header("Location: /");
}

$participants = EspaceModel::getParticipants($id_espace);

// On supprime l'utilisateur courant de la liste des participants
foreach ($participants as $key => $participant) {
    if ($participant["id_utilisateur"] == $id_utilisateur) {
        unset($participants[$key]);
    }
}

require_once __DIR__ . "/../src/model/ArticleModel.php";


require_once __DIR__ . "/../views/components/side-bar.php";
require_once __DIR__ . "/../views/share.php";
