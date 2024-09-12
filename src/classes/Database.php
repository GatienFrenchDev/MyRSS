<?php
class Database
{
    static function connexion(): mysqli | Exception
    {
        $env = parse_ini_file(__DIR__ . "/../../.env");
        $mysqli = new mysqli($env["DB_HOST"], $env["DB_USERNAME"], $env["DB_PASSWORD"], $env["DB_NAME"]);

        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }

        $mysqli->set_charset("utf8mb4");

        return $mysqli;
    }

    /**
     * Delete all articles that are older than 3 weeks and not in favorites or in collection.
     */
    static function clearOldArticles(): void
    {
        $env = parse_ini_file(__DIR__ . "/../../.env");

        $mysqli = Database::connexion();
        $stmt = $mysqli->prepare("DELETE FROM article WHERE date_pub < ? AND id_article NOT IN (SELECT id_article FROM ajout_collection)");
        $ts = time() - 3600 * 24 * $env["RETENTION_DAYS"];
        $stmt->bind_param("i", $ts);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }
}
