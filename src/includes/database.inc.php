<?php

$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
$mysqli = new mysqli($env["DB_HOST"], $env["DB_USERNAME"], $env["DB_PASSWORD"], $env["DB_NAME"]);

if ($mysqli->connect_error) {
    die("Erreur de connexion à la db : ". $mysqli->connect_error);
}

return $mysqli;