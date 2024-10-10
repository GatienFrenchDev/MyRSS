<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
}

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] == 0) {
    http_response_code(403);
    header("Location: /");
}

if (!isset($_GET["id"])) {
    http_response_code(400);
    header("Location: /");
}

$id_espace = $_GET["id"];

if (!is_numeric($id_espace)) {
    http_response_code(400);
    header("Location: /");
}

require_once __DIR__ . "/../src/model/EspaceModel.php";

try {
    $espace = EspaceModel::getDetails($id_espace);
    $participants = EspaceModel::getParticipants($id_espace);
} catch (EspaceNotFoundException $e) {
    http_response_code(404);
    header("Location: /espaces");
}

require_once __DIR__ . "/../views/components/side-bar.php";
require_once __DIR__ . "/../views/espace.php";
