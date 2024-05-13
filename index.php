<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: login");
    exit;
}

require_once("view/components/side-bar.php");

require_once("view/homepage.php");
require_once("view/components/version-beta.php");
