<?php

require_once __DIR__ . "/../classes/Database.php";

class NotificationModel
{
    static function notificationAppartientA(int $id_user, int $id_notification): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM notification WHERE id_utilisateur = ? AND id_notification = ?");
        $stmt->bind_param("ii", $id_user, $id_notification);
        $stmt->execute();
        $stmt->store_result();
        $res = $stmt->num_rows();

        $stmt->close();
        $mysqli->close();

        return $res != 0;
    }

    static function sendNotification(int $id_destinataire, string $titre, string $message): void
    {
        $mysqli = Database::connexion();
    
        $stmt = $mysqli->prepare("INSERT INTO `notification` (`titre`, `description`, `id_utilisateur`) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titre, $message, $id_destinataire);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function deleteNotification(int $id_notification): void
    {
        $mysqli = Database::connexion();
    
        $stmt = $mysqli->prepare("DELETE FROM notification WHERE id_notification = ?");
        $stmt->bind_param("i", $id_notification);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    static function deleteAll(int $id_utilisateur): void
    {
        $mysqli = Database::connexion();
    
        $stmt = $mysqli->prepare("DELETE FROM notification WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }
}
