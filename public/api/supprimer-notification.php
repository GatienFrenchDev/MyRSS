<?php

session_start();

$id_notification = $_GET["id_notification"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_notification"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_notification parameter needed"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/NotificationModel.php";

if(!NotificationModel::notificationAppartientA($id_utilisateur, $id_notification)){
    http_response_code(403);
    die(json_encode(["error" => "notification does not belong to you"]));
}

NotificationModel::deleteNotification($id_notification);