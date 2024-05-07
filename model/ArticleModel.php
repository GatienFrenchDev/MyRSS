<?php

class ArticleModel{
    static function setArticleLu(int $id_article, int $id_espace): int
    {
        $mysqli = require "../includes/database.inc.php";
    
        $stmt = $mysqli->prepare("INSERT INTO est_lu (id_article, id_espace) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $id_flux = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_flux;
    }
    
    static function setArticleNonLu(int $id_article, int $id_espace): void
    {
        $mysqli = require "../includes/database.inc.php";
    
        $stmt = $mysqli->prepare("DELETE FROM est_lu WHERE id_article = ? AND id_espace = ?");
        $stmt->bind_param("ii", $id_article, $id_espace);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function insertArticleIntoDB(Article $article, int $id_flux): int
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");
    
        $titre = $article->getTitre();
        $description = $article->getDescription();
        $date_pub = $article->getTimestamp();
        $url_article = $article->getUrlArticle();
        $current_ts = time();
    
        $stmt = $mysqli->prepare("INSERT INTO article (titre, description, date_pub, id_flux, url_article, date_ajout) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisi", $titre, $description, $date_pub, $id_flux, $url_article, $current_ts);
        try {
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return -1;
        }
        $id_flux = $mysqli->insert_id;
        $stmt->close();
        $mysqli->close();
        return $id_flux;
    }
}