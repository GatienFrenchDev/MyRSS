<?php

require "../model/model.php";

foreach(getAllRSSFlux() as $sourceRSS){

    echo "<h1>" . $sourceRSS["nom"] . " : OK </h1>";

    $articles = getArticlesFromRSSFlux($sourceRSS["id_flux"], $sourceRSS["adresse_url"]);
    $dernier_article = getDernierUrlArticle($sourceRSS["id_flux"]);
    $dernier_url_en_date = "";
    if(!is_null($dernier_article)){
        $dernier_url_en_date = $dernier_article->getUrlArticle();
    }

    print_r($dernier_url_en_date);

    foreach($articles as $article){
        print_r($article->getTitre());
        print_r("<br>");
        if($article->getUrlArticle() == $dernier_url_en_date){
            print_r("<b>Dernier article trouvé !</b>");
            break;
        }
        insertArticleIntoDB($article, $sourceRSS["id_flux"]);
    }
}

