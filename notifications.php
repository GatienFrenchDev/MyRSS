<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: login");
    exit;
}

require_once("model/UtilisateurModel.php");

$id_utilisateur = $_SESSION["id_utilisateur"];

$invitations = UtilisateurModel::getInvitations($id_utilisateur);
$notifications = UtilisateurModel::getNotifications($id_utilisateur);

require_once("view/components/side-bar.php");
require_once("view/notifications.php");
require_once("view/components/version-beta.php");


