<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/UtilisateurModel.php";

$espaces = UtilisateurModel::getEspaces($id_utilisateur);

die(json_encode(["espaces" => $espaces]));
