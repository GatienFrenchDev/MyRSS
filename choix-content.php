<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login");
    exit;
}

include_once "view/components/side-bar.php";

include_once "view/choix-content.php";
