<?php

session_start();

$user_invalid_password = false;
$user_existe_db = true;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    return;
}

if (!isset($_POST["email"])) {
    http_response_code(400);
    die("missing email parameter");
}

if (!isset($_POST["password"])) {
    http_response_code(400);
    die("missing password parameter");
}

$email = $_POST["email"];
$password = $_POST["password"];
$ip = $_SERVER['REMOTE_ADDR'];

require_once "./model/UtilisateurModel.php";
require_once "./lib/SimpleAntiBruteForce.php";


if(!SimpleAntiBruteForce::isAuthorized($ip, $email)){
    http_response_code(429);
    die("Trop de tentatives de connexion... Par mesure de sécurite, vous êtes bloqué pour 5 minutes");
}
	

$user_details = UtilisateurModel::getHashAndID($email);

if (!$user_details) {
    $user_existe_db = false;
    return;
}

if (password_verify($password, $user_details["hash_password"])) {
    session_regenerate_id();

    $_SESSION["id_utilisateur"] = $user_details["id_utilisateur"];

    header("Location: /");
    exit;
} else {
    $user_invalid_password = true;
    SimpleAntiBruteForce::addFailedAttempt($ip, $email);
}
