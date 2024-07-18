<?php

session_start();

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);
    die(json_encode(["error" => "authentification required"]));
}

if (!isset($_GET["id_flux"])) {
    http_response_code(400);
    die(json_encode(["error" => "id_flux parameter needed"]));
}

if (!isset($_GET["mail_destinataire"])) {
    http_response_code(400);
    die(json_encode(["error" => "mail_destinataire parameter needed"]));
}

$id_flux = $_GET["id_flux"];
$mail_destinataire = $_GET["mail_destinataire"];
$id_utilisateur = $_SESSION["id_utilisateur"];

if(!filter_var($mail_destinataire, FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    die(json_encode(["error" => "mail_destinataire parameter is not a valid email"]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/FluxModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/NotificationModel.php";

if (!FluxModel::rssFluxExist($id_flux)) {
    http_response_code(404);
    die(json_encode(["error" => "this flux does not exist"]));
}

$destinataire = UtilisateurModel::getUserDetailsFromMail($mail_destinataire);
$user = UtilisateurModel::getUserDetailsFromId($id_utilisateur);

$flux = FluxModel::getFluxDetailsFromId($id_flux);

// cas où le destinataire n'existe pas
if (count($destinataire) == 0) {

    require (__DIR__ . "/../../vendor/autoload.php");

    $env = parse_ini_file(__DIR__ . "/../../.env");

    $resend = Resend::client($env["RESEND_API_KEY"]);

    $resend->emails->send([
        'from' => $env["RESEND_EMAIL"],
        'to' => $mail_destinataire,
        'subject' => 'MyRSS | Recommendation de flux',
        'html' => 'Bonjour,<br><br>' . $user["prenom"] . ' ' . $user["nom"] . ' vous recommande ce flux RSS : <a href="' . $flux["adresse_url"] . '">' . $flux["adresse_url"] . '</a><br><br>Cordialement,<br><br>L\'équipe MyRSS'
    ]);

    die(json_encode(["message" => "email sent"]));
}

// cas où la personne se recommande elle même un flux
if ($destinataire["id_utilisateur"] == $id_utilisateur) {
    http_response_code(400);
    die(json_encode(["error" => "2 users need to be different"]));
}

NotificationModel::sendNotification($destinataire["id_utilisateur"], "Nouvelle recommandation", "<b>" . $user["prenom"] . " " . $user["nom"] . "</b> vous recommande ce flux<br><code> " . $flux["adresse_url"] . "</code>");
