<?php

session_start();

$res = [];

if (isset($_SESSION["user_id"])) {

    $id_utilisateur = $_SESSION["user_id"];

    require_once "../model/model.php";

    $articles = getEspaces($id_utilisateur);
    die(json_encode(["espaces" => $articles]));
}

die(json_encode(["error" => "authentification required"]));
