<?php

class ArchiveModel
{
    /**
     * Renvoie vrai si l'article était pas déjà dans les favoris et a bien été ajouté.
     */
    static function addToFavorites(int $id_utilisateur, int $id_article): bool
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("INSERT INTO ajout_archive (id_utilisateur, id_article, id_archive) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $id_utilisateur, $id_article);
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

    static function removeFromFavorites(int $id_utilisateur, int $id_article): bool
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("DELETE FROM ajout_archive WHERE id_utilisateur = ? AND id_article = ?");
        $stmt->bind_param("ii", $id_utilisateur, $id_article);
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

    /**
     * Renvoie `true` si l'utilisateur a ajouté cet article à ses favoris
     */
    static function appartientAuxFavoris($id_utilisateur, $id_article): bool
    {
        $mysqli = require "../includes/database.inc.php";

        $stmt = $mysqli->prepare("SELECT * FROM ajout_archive WHERE id_utilisateur = ? AND id_article = ?");
        $stmt->bind_param("ii", $id_utilisateur, $id_article);
        $res = true;
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return count($res) > 0;
    }
}
