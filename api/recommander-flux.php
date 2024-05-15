<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
    exit;
}

if (!isset($_GET["id_flux"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_flux parameter needed"]));
    exit;
}

if (!isset($_GET["mail_destinataire"])) {
    http_response_code(400);
    die(json_encode(["error" => "mail_destinataire parameter needed"]));
    exit;
}

$id_flux = $_GET["id_flux"];
$mail_destinataire = $_GET["mail_destinataire"];
$id_utilisateur = $_SESSION["id_utilisateur"];

require_once "../model/FluxModel.php";
require_once "../model/UtilisateurModel.php";
require_once "../model/NotificationModel.php";

if(!FluxModel::rssFluxExist($id_flux)){
    http_response_code(404);
    die(json_encode(["error" => "this flux does not exist"]));
    exit;
}

$destinataire = UtilisateurModel::getUserDetailsFromMail($mail_destinataire);
$user = UtilisateurModel::getUserDetailsFromId($id_utilisateur);

$flux = FluxModel::getFluxDetailsFromId($id_flux);

// cas où le destinataire n'existe pas
if(count($destinataire) == 0){ 
    http_response_code(404);
    die(json_encode(["error" => "this user does not exist"]));
    exit;
}

// cas où la personne se recommande elle même un flux
if($destinataire["id_utilisateur"] == $id_utilisateur){
    http_response_code(400);
    die(json_encode(["error" => "2 users need to be different"]));
    exit;
}

NotificationModel::sendNotification($destinataire["id_utilisateur"], "Nouvelle recommandation", "<b>" . $user["prenom"]. " " . $user["nom"] . "</b> vous recommande ce flux<br><code> " . $flux["adresse_url"] . "</code>");