<?php

class InvitationModel
{
    static function invitationAppartientA(int $id_user, int $id_invitation): bool
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT * FROM invitation WHERE id_utilisateur = ? AND id_invitation = ?");
        $stmt->bind_param("ii", $id_user, $id_invitation);
        $stmt->execute();
        $stmt->store_result();
        $res = $stmt->num_rows();

        $stmt->close();
        $mysqli->close();

        return $res != 0;
    }


    static function accepterInvitation(int $id_invitation): void
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");

        $stmt = $mysqli->prepare("SELECT * FROM invitation WHERE id_invitation = ?");
        $stmt->bind_param("i", $id_invitation);
        $stmt->execute();
        $ligne = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

        $id_utilisateur = $ligne["id_utilisateur"];
        $id_espace = $ligne["id_espace"];

        $stmt = $mysqli->prepare("DELETE FROM invitation WHERE id_invitation = ?");
        $stmt->bind_param("i", $id_invitation);
        $stmt->execute();

        $stmt = $mysqli->prepare("INSERT INTO contient_des (id_utilisateur, id_espace) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_utilisateur, $id_espace);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    static function refuserInvitation(int $id_invitation)
    {
        $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");
    
        $stmt = $mysqli->prepare("DELETE FROM invitation WHERE id_invitation = ?");
        $stmt->bind_param("i", $id_invitation);
        $stmt->execute();
    }

    /**
    * Renvoi `true` si l'invitation a bien été envoyée.
    */
    static function creerInvitation(string $email, int $id_espace, int $id_utilisateur_inviteur): bool
   {
       $mysqli = require($_SERVER['DOCUMENT_ROOT'] . "/includes/database.inc.php");
   
       $stmt = $mysqli->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
       $stmt->bind_param("s", $email);
       $stmt->execute();
       $ligne = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
   
       // si l'utilisateur n'existe pas
       if (count($ligne) == 0) {
           return false;
       }
   
       $ligne = $ligne[0];
   
       $id_utilisateur = $ligne["id_utilisateur"];
   
       $stmt = $mysqli->prepare("INSERT INTO invitation (id_utilisateur, id_espace, id_utilisateur_inviteur) VALUES (?, ?, ?)");
       $stmt->bind_param("iii", $id_utilisateur, $id_espace, $id_utilisateur_inviteur);
       $stmt->execute();
   
       $stmt->close();
       $mysqli->close();
   
       return true;
   }
}
