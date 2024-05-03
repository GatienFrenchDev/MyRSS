<?php

$prenom = $_POST["prenom"];
$nom = $_POST["nom"];
$email = $_POST["email"];
$password = $_POST["password"];
$password_confirmation = $_POST["password-confirmation"];

// début vérifications des données saisies par le formulaire
if(empty($prenom)){
    http_response_code(400);
    die(json_encode(["error" => "parameter prenom required"]));
    exit;
}

if(empty($nom)){
    http_response_code(400);
    die(json_encode(["error" => "parameter nom required"]));
    exit;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    die(json_encode(["error" => "parameter email required"]));
    exit;
}

if(strlen($password) < 8){
    http_response_code(400);
    die(json_encode(["error" => "password must be at least 8 characters"]));
    exit;
}

if($password != $password_confirmation){
    http_response_code(400);
    die(json_encode(["error" => "2 passwords must match"]));
    exit;
}
// fin des vérifications des données saisies par le formulaure

$hash_password = password_hash($password, PASSWORD_DEFAULT);

$mysqli = require "database.inc.php";

$current_timestamp = time();

// Ajoute l'user dans la db
$sql = "INSERT INTO utilisateur (nom, prenom, email, hash_password, date_inscription) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();
$stmt->prepare($sql);
$stmt->bind_param("ssssi", $nom, $prenom, $email, $hash_password, $current_timestamp);
$stmt->execute();
$id_user = $mysqli->insert_id;
$stmt->close();

// Lie l'espace partagé crée à l'user
$sql = "INSERT INTO espace_partage (nom) VALUES (?)";
$stmt = $mysqli->stmt_init();
$stmt->prepare($sql);
$nom_espace = "Espace de " . $prenom;
$stmt->bind_param("s", $nom_espace);
$stmt->execute();
$id_espace = $mysqli->insert_id;
$stmt->close();


// Crée un espace partagé pour l'user
$sql = "INSERT INTO contient_des (id_utilisateur, id_espace) VALUES (?, ?)";
$stmt = $mysqli->stmt_init();
$stmt->prepare($sql);
$stmt->bind_param("ii", $id_user, $id_espace);
$stmt->execute();
$stmt->close();



$mysqli->close();
header("Location: ../index.php");
exit;