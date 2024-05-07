<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

$id_utilisateur = $_SESSION["user_id"];

require_once "../model/UtilisateurModel.php";

$articles = UtilisateurModel::getEspaces($id_utilisateur);
die(json_encode(["espaces" => $articles]));
