<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    header("Location: login");
    exit;
}

require_once "model/UtilisateurModel.php";

$id_utilisateur = $_SESSION["user_id"];

$utilisateur = UtilisateurModel::getUserDetailsFromId($id_utilisateur);

require_once "view/components/side-bar.php";
require_once "view/compte.php";
