<?php

/**
 * Ce script interroge tous les flux rss répertoriés dans la base de données.
 * Il ajoute ensuite tous les articles qui ne sont pas encore stockés dans la db.
 */


$i = 0;
$t0 = time();

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/FluxModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/ArticleModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src//lib/tools.phpp";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/classes/Article.php";


echo "<h1> Récupération des flux RSS </h1>";
echo "<p> Ce  script interroge tous les flux rss répertoriés dans la db et ajoute les nouveaux articles à la db.</p>";


$liste_flux = FluxModel::getAllRSSFlux();

// Pour chaque flux rss stocké dans la db
foreach($liste_flux as $sourceRSS){

    $t1 = time();

    echo "<h2>" . $sourceRSS["nom"] . "</h2>";

    // On récupère tous les articles notés dans le xml du flux rss courant
    $articles = getArticlesFromRSSFlux($sourceRSS["id_flux"], $sourceRSS["adresse_url"]);
    // On récupère le dernier article en date du flux rss courant stocké dans la db
    $dernier_article = FluxModel::getDernierUrlArticle($sourceRSS["id_flux"]);
    $dernier_url_en_date = "";
    // On procéde ensuite par comparaison via l'url de l'article.
    // On considère que si 2 articles ont le même url d'article alors ils sont identiques.
    if(!is_null($dernier_article)){
        $dernier_url_en_date = $dernier_article->getUrlArticle();
    }

    echo "<ul>";

    foreach($articles as $article){
        print_r("<li>".$article->getTitre()."</li>");
        if($article->getUrlArticle() == $dernier_url_en_date){
            print_r("<b>Dernier article trouvé !</b>");
            break;
        }
        $i++;
        ArticleModel::insertArticleIntoDB($article, $sourceRSS["id_flux"]);
    }

    echo "</ul>";
    echo "<br><i>" . time()-$t1 ."s pour ce flux</i>";
}

echo "<br><code>".$i." nouveaux articles ajoutés !</code>";
echo "<br><b>Temps total écoulé : " . time()-$t0 . "s</b>";