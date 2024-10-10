<?php

require_once __DIR__ . "/../model/ArticleModel.php";

class Rule
{

    private int $id_rule;
    private int $id_utilisateur;
    private string $nom;
    private string $contient_titre;
    private string $contient_description;
    private string $operator;
    private bool $case_sensitive;
    private int $id_flux;
    private int $action;


    public function __construct(int $id_rule, int $id_utilisateur, string $nom, string $contient_titre, string $contient_description, string $operator, bool $case_sensitive, int $id_flux, int $action)
    {
        $this->id_rule = $id_rule;
        $this->id_utilisateur = $id_utilisateur;
        $this->nom = $nom;
        $this->contient_titre = $contient_titre;
        $this->contient_description = $contient_description;
        $this->operator = $operator;
        $this->case_sensitive = $case_sensitive;
        $this->id_flux = $id_flux;
        $this->action = $action;
    }

    public function getIdRule(): int
    {
        return $this->id_rule;
    }

    public function getIdFlux(): int
    {
        return $this->id_flux;
    }

    /**
     * Check if the article matches the rule
     * @param Article $article the article to check
     * @return bool true if the article matches the rule, false otherwise
     */
    public function isVerified(Article $article): bool
    {
        $titre = $article->getTitre();
        $description = $article->getDescription();
        $titre = $this->case_sensitive ? $titre : strtolower($titre);
        $description = $this->case_sensitive ? $description : strtolower($description);
        $contient_titre = $this->case_sensitive ? $this->contient_titre : strtolower($this->contient_titre);
        $contient_description = $this->case_sensitive ? $this->contient_description : strtolower($this->contient_description);
        $titre_match = strpos($titre, $contient_titre) !== false;
        $description_match = strpos($description, $contient_description) !== false;
        if ($this->operator == "AND") {
            return $titre_match && $description_match;
        } else {
            return $titre_match || $description_match;
        }
    }

    /**
     * Trigger the action of the rule
     */
    public function triggerAction(Article $article): void
    {

        require_once __DIR__ . "/../model/CollectionModel.php";
        require_once __DIR__ . "/../model/NotificationModel.php";

        if ($this->action == 1) {
            // add to favorites
            CollectionModel::addToCollection($this->id_utilisateur, $article->getId(), 1);
        } elseif ($this->action == 2) {
            // create a notification for the user
            NotificationModel::sendNotification($this->id_utilisateur, "[REGLE] Nouvel article correspondant à votre règle <i>" . $this->nom . "</i> a été trouvé", "L'article nommé <i>'" . mb_substr($article->getTitre(), 0, 50) . "...'</i> correspond à une de vos règles. <a target='_blank' href='". $article->getUrlArticle() ."'>Lien vers l'article</a>");
        } elseif ($this->action == 3) {
            // send an email to the user
            require __DIR__ . "/../../vendor/autoload.php";
            require_once __DIR__ . "/../model/UtilisateurModel.php";

            $env = parse_ini_file(__DIR__ . "/../../.env");

            $resend = Resend::client($env["RESEND_API_KEY"]);
            
            $user_details = UtilisateurModel::getUserDetailsFromId($this->id_utilisateur);

            $resend->emails->send([
                'from' => $env["RESEND_EMAIL"],
                'to' => $user_details["email"],
                'subject' => 'MyRSS | Nouvel article correspondant à votre règle',
                'html' => 'Bonjour ' . $user_details["prenom"] . ' ' . $user_details["nom"] . ',<br><br>Un nouvel article correspondant à votre règle intitulée ' . $this->nom . ' a été détecté.<br>Lien vers l\'article: ' . $article->getUrlArticle() .'<br><br>Cordialement,<br><br>L\'équipe Troover'
            ]);
        }
    }
}
