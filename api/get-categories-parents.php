<?php

session_start();

$res = [];

if (isset($_SESSION["user_id"])) {

    if (isset($_GET["id_categorie"])) {

        $id_categorie = $_GET["id_categorie"];
        $id_utilisateur = $_SESSION["user_id"];

        require_once "../model/model.php";

        if($id_categorie < 0){
            $espaces = getEspaces($id_utilisateur);
            die(json_encode(["categories" => $espaces]));
        }

        if(categorieAppartientA($id_utilisateur, $id_categorie)){
            $articles = getParentsCategories($id_categorie);
            die(json_encode(["categories" => $articles]));
        }
        die(json_encode(["error" => "id_categorie does not belong to you"]));

    }

    die(json_encode(["error" => "id_categorie parameter needed"]));
}

die(json_encode(["error" => "authentification required"]));
