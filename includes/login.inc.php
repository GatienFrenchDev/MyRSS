<?php

session_start();

$user_invalid_password = false;
$user_existe_db = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require "includes/database.inc.php";

    $sql = sprintf("SELECT * FROM utilisateur WHERE email = '%s'", $mysqli->real_escape_string($_POST["email"]));

    $res = $mysqli->query($sql);

    $user = $res->fetch_assoc();

    if ($user) {

        if (password_verify($_POST["password"], $user["hash_password"])) {

            session_start();

            session_regenerate_id();

            $_SESSION["id_utilisateur"] = $user["id_utilisateur"];

            header("Location: /");
            exit;
        }
    } else {
        $user_existe_db = false;
    }

    if ($user_existe_db) {
        $user_invalid_password = true;
    }
}