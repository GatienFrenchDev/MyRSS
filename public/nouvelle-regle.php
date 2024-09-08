<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    header("Location: login");
}

$id_utilisateur = $_SESSION["id_utilisateur"];

if($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once __DIR__ . "../../src/model/RegleModel.php";

    if(!isset($_POST["nom"]) || !isset($_POST["flux"]) || !isset($_POST["action"]) ){
        http_response_code(400);
        die("Paramètres manquants");
    }

    $reponse = RegleModel::createRegle($id_utilisateur,
    $_POST["nom"],
    $_POST["contient-titre"],
    $_POST["operateur"] === "et" ? "et" : "ou",
    $_POST["contient-description"],
    isset($_POST["sensible-casse"]),
    $_POST["flux"] > 0 ? $_POST["flux"] : null,
    $_POST["action"]);

    header("Location: regles");
}


require_once __DIR__ . "../../src/model/UtilisateurModel.php";
require_once __DIR__ . "../../src/model/RegleModel.php";

$fluxs = UtilisateurModel::getFluxs($id_utilisateur);

require_once __DIR__ . "../../views/components/side-bar.php";
require_once __DIR__  . "../../views/nouvelle-regle.php";
