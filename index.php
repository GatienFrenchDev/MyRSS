<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login");
    exit;
}

$user_id = $_SESSION["user_id"];

require("model/model.php");

require("view/components/version-beta.php");
require("view/components/side-bar.php");

require("view/homepage.php");
