<?php

session_start();

$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/NotificationModel.php";

NotificationModel::deleteAll($id_utilisateur);

http_response_code(204);