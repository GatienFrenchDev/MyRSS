<?php

require_once __DIR__ . "../../classes/Database.php";
require_once __DIR__ . "../../classes/Rule.php";

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

    /**
     * Get all rules from the database
     * @return Rule[] array of Rule objects
     */
    static function getAllRules(): array
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM regle");
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();
        
        $rules = array();
        
        foreach ($res as $rule) {
            $rules[] = new Rule($rule["id_regle"], $rule["id_utilisateur"], $rule["nom"], $rule["contient_titre"], $rule["contient_description"], $rule["operateur"], $rule["sensible_casse"], $rule["id_flux"], $rule["action"]);
        }

        return $rules;
    }

    static function deleteRule(int $id_rule)
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM regle WHERE id_regle = ?");
        $stmt->bind_param("i", $id_rule);
        $stmt->execute();
        $stmt->close();

        $mysqli->close();
    }

    static function belongsToUser(int $id_rule, int $id_user): bool
    {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("SELECT * FROM regle WHERE id_regle = ? AND id_utilisateur = ?");
        $stmt->bind_param("ii", $id_rule, $id_user);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $mysqli->close();

        return count($res) > 0;
    }
}
