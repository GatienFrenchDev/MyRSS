<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
}

if(!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] == 0){
    http_response_code(403);
    header("Location: /");
}

require_once __DIR__ . "/../views/components/side-bar.php";
require_once __DIR__ . "/../views/equipes.php";