<?php

class Article{

    private int $article_timestamp;
    private string $titre;
    private string $description;
    private string $url_article;

    public function __construct(string $titre, string $description, string $url_article, int $article_timestamp) {
        $this->article_timestamp = $article_timestamp;
        $this->titre = substr($titre, 0, 255);
        $this->description = substr($description, 0, 255);
        $this->url_article = substr($url_article, 0, 512);
    }

    public function getTitre():string{
        return $this->titre;
    }

    public function getDescription():string{
        return $this->description;
    }

    public function getUrlArticle():string{
        return $this->url_article;
    }

    public function getTimestamp():int{
        return $this->article_timestamp;
    }

}