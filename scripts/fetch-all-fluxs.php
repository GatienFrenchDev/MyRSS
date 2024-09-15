<?php

/**
 * Ce script interroge tous les flux rss répertoriés dans la base de données.
 * Il ajoute ensuite tous les articles qui ne sont pas encore stockés dans la db.
 */


$i = 0;
$t0 = time();

require_once __DIR__ . "/../src/model/FluxModel.php";
require_once __DIR__ . "/../src/model/ArticleModel.php";
require_once __DIR__ . "/../src/model/RegleModel.php";
require_once __DIR__ . "/../lib/tools.php";
require_once __DIR__ . "/../src/classes/Article.php";
require_once __DIR__ . "/../src/classes/Database.php";


echo "# Récupération des flux RSS\n\n";
echo "## Ce  script interroge tous les flux rss répertoriés dans la db et ajoute les nouveaux articles à la db.\n";
echo "## " . date("d/m/Y H:i:s") . "\n\n";


$liste_flux = FluxModel::getAllRSSFlux();
$rules = RegleModel::getAllRules();

// Pour chaque flux rss stocké dans la db
foreach ($liste_flux as $sourceRSS) {

    $t1 = time();
    
    // On initialise un tableau qui contiendra les articles à insérer dans la db
    $articles_to_insert = array();

    // On supprime les articles trop anciens (> 2 semaines)
    Database::clearOldArticles();

    echo "### " . $sourceRSS["nom"] . "\n";

    // On récupère tous les articles notés dans le xml du flux rss courant
    $articles = getArticlesFromRSSFlux($sourceRSS["id_flux"], $sourceRSS["adresse_url"]);
    // On récupère le dernier article en date du flux rss courant stocké dans la db
    $dernier_article = FluxModel::getLatestInsertedArticle($sourceRSS["id_flux"]);
    $dernier_url_en_date = "";
    // On procéde ensuite par comparaison via l'url de l'article.
    // On considère que si 2 articles ont le même url d'article alors ils sont identiques.
    if (!is_null($dernier_article)) {
        $dernier_url_en_date = $dernier_article->getUrlArticle();
    }

    foreach ($articles as $article) {
        print_r("  - " . $article->getTitre() . "\n");
        if ($article->getUrlArticle() == $dernier_url_en_date) {
            break;
        }
        $articles_to_insert[] = $article;
        $i++;
    }

    $articles_with_id = ArticleModel::insertArticlesIntoDB($articles_to_insert, $sourceRSS["id_flux"]);

    foreach($rules as $rule) {
        if($rule->getIdFlux() != $sourceRSS["id_flux"]) {
            continue;
        }

        foreach($articles_with_id as $article) {
            if($rule->isVerified($article)) {
                $rule->triggerAction($article);
            }
        }

        unset($rule);
    }

    echo " [*] " . time() - $t1 . "s pour traiter ce flux\n\n";
}

echo "==============\n";
echo $i . " nouveaux articles ajoutés !\n";
echo "Temps total écoulé : " . time() - $t0 . "s\n";
echo "==============\n";
