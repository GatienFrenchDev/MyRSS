<?php

// Ex : api/create-new-categorie.php?nom=...&id_espace=...&id_categorie_parent=...


session_start();

$res = [];

if (isset($_SESSION["id_utilisateur"])) {

    if (isset($_GET["nom"]) && isset($_GET["id_espace"]) && isset($_GET["id_categorie_parent"])) {

        $nom = $_GET["nom"];
        $id_espace = $_GET["id_espace"];
        $id_categorie_parent = $_GET["id_categorie_parent"];


        $id_utilisateur = $_SESSION["id_utilisateur"];

        require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/EspaceModel.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/CategorieModel.php";

        if (EspaceModel::appartientA($id_utilisateur, $id_espace)) {
            if(CategorieModel::appartientA($id_utilisateur, $id_categorie_parent) || $id_categorie_parent==-1){
                $id_categorie = CategorieModel::pushNewCategorieToDB($nom, $id_categorie_parent, $id_espace);
                die(json_encode(["id_categorie" => $id_categorie]));
            }
            else{
                die(json_encode(["error" => "id_categorie_parent does not belong to you"]));
            }
        } else {
            die(json_encode(["error" => "id_espace does not belong to you"]));
        }
    }

    die(json_encode(["error" => "missing parameters"]));
}

die(json_encode(["error" => "authentification required"]));
