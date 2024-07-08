<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: login");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/views/components/side-bar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/views/homepage.php";
