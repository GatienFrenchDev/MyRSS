<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
}

require_once __DIR__ . "../../src/model/UtilisateurModel.php";

$rules = UtilisateurModel::getAllRules($_SESSION["id_utilisateur"]);

require_once __DIR__ . "../../views/components/side-bar.php";
require_once __DIR__  . "../../views/regles.php";
