<?php

class Article{

    private int $article_timestamp;
    private string $titre;
    private string $description;
    private string $url_article;

    public function __construct(string $titre, string $description, string $url_article, int $article_timestamp) {
        $this->article_timestamp = $article_timestamp;
        $this->titre = $titre;
        $this->description = $description;
        $this->url_article = $url_article;
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