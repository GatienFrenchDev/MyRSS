<?php

// Ex : api/get-categories.php?id_espace=... OU api/get-categories.php?id_categorie=...

session_start();

if (isset($_SESSION["user_id"])) {

    if (isset($_GET["id_espace"])) {

        $id_espace = $_GET["id_espace"];
        $id_utilisateur = $_SESSION["user_id"];

        require_once "../model/model.php";

        if (espaceAppartientA($id_utilisateur, $id_espace)) {
            $categories = getCategoriesFromEspace($id_espace);
            die(json_encode(["categories" => $categories]));
        }
        die(json_encode(["error" => "espace does not belong to you"]));
    }

    if (isset($_GET["id_categorie"])) {
        $id_categorie = $_GET["id_categorie"];
        $id_utilisateur = $_SESSION["user_id"];

        require_once "../model/model.php";

        if(categorieAppartientA($id_utilisateur, $id_categorie)){
            $articles = getSubCategories($id_categorie);
            die(json_encode(["categories" => $articles]));
        }
        die(json_encode(["error" => "categorie does not belong to you"]));

    }

    die(json_encode(["error" => "espace parameter needed"]));
}

die(json_encode(["error" => "authentification required"]));
