<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/views/inscription.php";
    exit;
}

// début vérifications des données saisies par le formulaire
if(!isset($_POST["prenom"])){
    http_response_code(400);
    die(json_encode(["error" => "prenom parameter required"]));
}

if(!isset($_POST["nom"])){
    http_response_code(400);
    die(json_encode(["error" => "nom parameter required"]));
}

if(!isset($_POST["email"])){
    http_response_code(400);
    die(json_encode(["error" => "email parameter required"]));
}

if(!isset($_POST["password"])){
    http_response_code(400);
    die(json_encode(["error" => "password parameter required"]));
}

if(!isset($_POST["password-confirmation"])){
    http_response_code(400);
    die(json_encode(["error" => "password-confirmation parameter required"]));
}

$prenom = $_POST["prenom"];
$nom = $_POST["nom"];
$email = $_POST["email"];
$password = $_POST["password"];
$password_confirmation = $_POST["password-confirmation"];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    die(json_encode(["error" => "parameter email required"]));
}

if(strlen($password) < 8){
    http_response_code(400);
    die(json_encode(["error" => "password must be at least 8 characters"]));
}

if($password != $password_confirmation){
    http_response_code(400);
    die(json_encode(["error" => "2 passwords must match"]));
}
// fin des vérifications des données saisies par le formulaire

$hash_password = password_hash($password, PASSWORD_DEFAULT);

require $_SERVER['DOCUMENT_ROOT'] . "/src/model/UtilisateurModel.php";

$id_utilisateur = UtilisateurModel::createUser($nom, $prenom, $email, $hash_password);

if($id_utilisateur == -1){
    http_response_code(500);
    die(json_encode(["error" => "An error occurred while creating the user"]));
}

session_start();

$_SESSION["id_utilisateur"] = $id_utilisateur;

header("Location: /");
exit;