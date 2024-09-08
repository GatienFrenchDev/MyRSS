<?php

require_once __DIR__ . "../../classes/Database.php";

class RegleModel
{
    static function createRegle(int $id_utilisateur, string $nom, string $contient_titre, string $operateur, string $contient_description, bool $sensible_casse, int $id_flux, int $action)
    {

        $case_sensitive =  $sensible_casse ? 1 : 0;

        $mysqli = Database::connexion();

        if ($id_flux > 0) {
            $stmt = $mysqli->prepare("INSERT INTO regle (id_utilisateur, nom, contient_titre, operateur, contient_description, sensible_casse, id_flux, action) VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssiii", $id_utilisateur, $nom, $contient_titre, $operateur, $contient_description, $case_sensitive, $id_flux, $action);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $mysqli->prepare("INSERT INTO regle (id_utilisateur, nom, contient_titre, operateur, contient_description, sensible_casse, action) VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssii", $id_utilisateur, $nom, $contient_titre, $operateur, $contient_description, $case_sensitive, $action);
            $stmt->execute();
            $stmt->close();
        }



        $mysqli->close();
        return true;
    }

    static function getAllRules(): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM regle");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();
        return $res;
    }
}
