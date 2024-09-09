<?php
class Database
{
    static function connexion()
    {
        $env = parse_ini_file(__DIR__ . "/../../.env");
        $mysqli = new mysqli($env["DB_HOST"], $env["DB_USERNAME"], $env["DB_PASSWORD"], $env["DB_NAME"]);

        if ($mysqli->connect_error) {
            die("Erreur de connexion à la db : " . $mysqli->connect_error);
        }

        $mysqli->set_charset("utf8mb4");

        return $mysqli;
    }
}
