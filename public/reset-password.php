<?php


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/views/reset-password.php";
    exit;
}

if (!isset($_POST["email"])) {
    http_response_code(400);
    die("missing email parameter");
}

$email = $_POST["email"];

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";

$user_details = UtilisateurModel::getUserDetailsFromMail($email);

if(!$user_details){
    http_response_code(404);
    die("Cette adresse email n'existe pas dans notre base de données");
}

$token = UtilisateurModel::generateResetPasswordToken($user_details["id_utilisateur"]);

UtilisateurModel::sendResetPasswordEmail($email, $token);

header("Location: /login");