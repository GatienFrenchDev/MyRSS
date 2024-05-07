<?php

// Ex : api/create-new-espace.php?nom=...


session_start();

$res = [];

if (isset($_SESSION["user_id"])) {

    if (isset($_GET["nom"])) {

        $nom = $_GET["nom"];

        $id_utilisateur = $_SESSION["user_id"];

        require_once "../model/EspaceModel.php";

        die(json_encode(["id_espace" => EspaceModel::pushNewEspaceToDB($nom, $id_utilisateur)]));


    }

    die(json_encode(["error" => "missing parameters"]));
}

die(json_encode(["error" => "authentification required"]));
