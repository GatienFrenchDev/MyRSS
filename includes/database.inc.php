<?php

$host = "127.0.0.1";
$dbname = "myrss";
$username = "root";
$password = "root";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Erreur de connexion à la db : ". $mysqli->connect_error);
}

return $mysqli;