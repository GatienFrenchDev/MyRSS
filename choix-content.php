<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
    exit;
}

$nom = "non définie";

$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "model/EspaceModel.php";
require_once "model/CategorieModel.php";


if(isset($_GET["id_espace"])){

    $id_espace = $_GET["id_espace"];
    
    // on s'assure que l'id espace est un nombre
    if(!ctype_digit($id_espace)){
        http_response_code(400);
        die(json_encode(["error" => "id_espace is not a number"]));
    }

    // on s'assure que l'espace appartient à la personne
    if(!EspaceModel::espaceAppartientA($id_utilisateur, $id_espace)){
        http_response_code(403);
        die(json_encode(["error" => "id_espace does not belong to you"]));
    }

    $nom = EspaceModel::getNom($id_espace);

    // chaine de charactere que l'on vient mettre dans les balises `<a>`. eg : `id_categorie=13` ou alors `id_espace=11`
    $parametre_balise = "id_espace=".$id_espace;


}
else if(isset($_GET["id_categorie"])){

    $id_categorie = $_GET["id_categorie"];
    
    // on s'assure que l'id espace est un nombre
    if(!ctype_digit($id_categorie)){
        http_response_code(400);
        die(json_encode(["error" => "id_categorie is not a number"]));
    }

    // on s'assure que la catégorie appartient à la personne
    if(!CategorieModel::categorieAppartientA($id_utilisateur, $id_categorie)){
        http_response_code(403);
        die(json_encode(["error" => "id_categorie does not belong to you"]));
    }

    $nom = CategorieModel::getNom($id_categorie);

    // chaine de charactere que l'on vient mettre dans les balises `<a>`. eg : `id_categorie=13` ou alors `id_espace=11`
    $parametre_balise = "id_categorie=".$id_categorie;

}
else{
    http_response_code(400);
    die(json_encode(["error" => "missing some parameters"]));
}

require_once "view/components/side-bar.php";

require_once "view/choix-content.php";
