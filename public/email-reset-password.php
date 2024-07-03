<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/SimpleAntiBruteForce.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    if (!isset($_GET["email"])) {
        http_response_code(400);
        die("missing email parameter");
    }
    
    if (!isset($_GET["token"])) {
        http_response_code(400);
        die("missing token parameter");
    }

    $token = $_GET["token"];
    $email = $_GET["email"];

    require_once $_SERVER['DOCUMENT_ROOT'] . "/views/email-reset-password.php";
    exit;
}

if (!isset($_POST["password"])) {
    http_response_code(400);
    die("missing password parameter");
}

if (!isset($_POST["email"])) {
    http_response_code(400);
    die("missing email parameter");
}

if (!isset($_POST["token"])) {
    http_response_code(400);
    die("missing token parameter");
}

$password = $_POST["password"];
$token = $_POST["token"];
$email = $_POST["email"];

$ip = $_SERVER['REMOTE_ADDR'];


if(!SimpleAntiBruteForce::isAuthorized($ip, $email)){
    http_response_code(429);
    die("Trop de tentatives de connexion... Par mesure de sécurite, vous êtes bloqué pour 5 minutes");
}    

if(!UtilisateurModel::isTokenValid($email, $token)){
    http_response_code(400);
    SimpleAntiBruteForce::registerFailedAttempt($ip, $email);
    die("Token invalide");
}

if(strlen($password) < 8){
    http_response_code(400);
    die("Le mot de passe doit contenir au moins 8 caractères");
}

if(strlen($password) > 32){
    http_response_code(400);
    die("Le mot de passe doit contenir au maximum 32 caractères");
}

UtilisateurModel::resetPassword($email, password_hash($password, PASSWORD_DEFAULT));
UtilisateurModel::deleteToken($email, $token);

header("Location: /login");

