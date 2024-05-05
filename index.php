<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login");
    exit;
}

require("model/model.php");

require("view/components/side-bar.php");

require("view/homepage.php");
require("view/components/version-beta.php");
