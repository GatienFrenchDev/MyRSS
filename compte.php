<?php

require "model/model.php";


session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    header("Location: login");
    exit;
}

$id_utilisateur = $_SESSION["user_id"];

$utilisateur = getUserDetailsFromId($id_utilisateur);

require "view/components/side-bar.php";
require "view/compte.php";
