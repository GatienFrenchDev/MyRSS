<?php

session_start();

$id_rule = $_POST["id_rule"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_POST["id_rule"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_rule parameter needed"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/RegleModel.php";

if(!RegleModel::belongsToUser($id_rule, $id_utilisateur)){
    http_response_code(403);
    die(json_encode(["error" => "rule does not belong to user"]));
}

RegleModel::deleteRule($id_rule);
die(json_encode(["success" => "rule deleted"]));