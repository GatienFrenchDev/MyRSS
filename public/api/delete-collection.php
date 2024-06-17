<?php

session_start();

$id_collection = $_GET["id_collection"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_collection"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_collection parameter needed"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CollectionModel.php";

if(!CollectionModel::collectionAppartientAUtilisateur($id_collection, $id_utilisateur)){
    http_response_code(403);
    die(json_encode(["error" => "this collection does not belong to you"]));
}

CollectionModel::delete($id_collection);