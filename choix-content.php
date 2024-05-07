<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login");
    exit;
}

require_once "view/components/side-bar.php";

require_once "view/choix-content.php";
