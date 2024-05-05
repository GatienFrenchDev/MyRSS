<?php

session_start();

$id_notification = $_GET["id_notification"];
$id_utilisateur = $_SESSION["user_id"];

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["id_notification"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_notification parameter needed"]));
    exit;
}

require "../model/model.php";

if(!notificationAppartientA($id_utilisateur, $id_notification)){
    http_response_code(403);
    die(json_encode(["error" => "notification does not belong to you"]));
    exit;
}

deleteNotification($id_notification);
header("Location: ../index.php");