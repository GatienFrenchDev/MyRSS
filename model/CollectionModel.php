<?php

class CollectionModel
{
    /**
     * Renvoie vrai si l'article était pas déjà dans la collection et a bien été ajouté.
     */
    static function addToCollection(int $id_utilisateur, int $id_article, int $id_collection): bool
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("INSERT INTO ajout_collection (id_utilisateur, id_article, id_collection) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $id_utilisateur, $id_article, $id_collection);
        $res = true;
        try {
            $stmt->execute();
        } catch (Exception $e) {
            $res = false;
        }
        $stmt->close();
        $mysqli->close();
        return $res;
    }

    static function removeFromCollection(int $id_utilisateur, int $id_article, int $id_collection): void
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("DELETE FROM ajout_collection WHERE id_utilisateur = ? AND id_article = ? AND id_collection = ?");
        $stmt->bind_param("iii", $id_utilisateur, $id_article, $id_collection);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    /**
     * Renvoie `true` si l'utilisateur a ajouté cet article à ses favoris
     */
    static function appartientA($id_utilisateur, $id_article, $id_collection): bool
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("SELECT * FROM ajout_collection WHERE id_utilisateur = ? AND id_article = ? AND id_collection = ?");
        $stmt->bind_param("iii", $id_utilisateur, $id_article, $id_collection);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }
}
