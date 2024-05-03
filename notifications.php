<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login");
    exit;
}

require("model/model.php");

$id_utilisateur = $_SESSION["user_id"];

$invitations = getInvitations($id_utilisateur);

require("view/components/version-beta.php");
require("view/components/side-bar.php");
require("view/notifications.php");


