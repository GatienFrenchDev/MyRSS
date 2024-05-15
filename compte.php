<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
    exit;
}

require_once "model/UtilisateurModel.php";

$id_utilisateur = $_SESSION["id_utilisateur"];

$utilisateur = UtilisateurModel::getUserDetailsFromId($id_utilisateur);

require_once "view/components/side-bar.php";
require_once "view/compte.php";
