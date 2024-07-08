<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: login");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";

$id_utilisateur = $_SESSION["id_utilisateur"];

$invitations = UtilisateurModel::getInvitations($id_utilisateur);
$notifications = UtilisateurModel::getNotifications($id_utilisateur);

require_once $_SERVER['DOCUMENT_ROOT'] . "/views/components/side-bar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/views/notifications.php";
