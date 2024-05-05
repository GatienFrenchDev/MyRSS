<?php

session_start();

if (!isset($_SESSION["user_id"])) {
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
$id_utilisateur = $_SESSION["user_id"];

require "../model/model.php";

if(!rssFluxExist($id_flux)){
    http_response_code(404);
    die(json_encode(["error" => "this flux does not exist"]));
    exit;
}

$destinataire = getUserDetailsFromMail($mail_destinataire);
$user = getUserDetailsFromId($id_utilisateur);

$flux = getFluxDetailsFromId($id_flux);

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

sendNotification($destinataire["id_utilisateur"], "Nouvelle recommandation", "<b>" . $user["prenom"]. " " . $user["nom"] . "</b> vous recommande ce flux<br><code> " . $flux["adresse_url"] . "</code>");