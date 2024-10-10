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

if(!isset($_POST["id_utilisateur"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_utilisateur parameter needed"]));
}

$id_utilisateur = $_POST["id_utilisateur"];
$id_espace = $_POST["id_espace"];

if (!is_numeric($id_espace)) {
    http_response_code(400);
    die(json_encode(["error" => "id_espace must be an integer"]));
}

if (!is_numeric($id_utilisateur)) {
    http_response_code(400);
    die(json_encode(["error" => "id_utilisateur must be an integer"]));
}

require_once __DIR__ . "/../../../src/model/UtilisateurModel.php";
require_once __DIR__ . "/../../../src/model/EspaceModel.php";

try {
    $est_admin = UtilisateurModel::estAdmin($_SESSION["id_utilisateur"]);
    if (!$est_admin) {
        http_response_code(403);
        die(json_encode(["error" => "you are not an admin"]));
    }
} catch (UserNotFoundException $e) {
    http_response_code(404);
    die(json_encode(["error" => "the user logged does not exist"]));
}

EspaceModel::retirerUser($id_utilisateur, $id_espace);
die(json_encode(["success" => "user removed from space " + $id_espace + " successfully"]));
