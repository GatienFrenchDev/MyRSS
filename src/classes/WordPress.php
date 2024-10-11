<?php

class WordPressException extends Exception
{
    public function __construct()
    {
        parent::__construct("WordPress error");
    }
}

enum WordPressCategories : string {
    case BRIEF = "brief";
    case POST_CI = "post_ci";
    case MERCATO = "mercato";
}

class WordPress
{

    /**
     * Push an article to the WordPress API
     * 
     * @param int $id_article The id of the article to push
     * @param string $nom The name of the user pushing the article
     * @param WordPressCategories $categorie The category of the article
     * 
     * @return bool True if the article was pushed successfully, false otherwise
     * 
     * @throws ArticleNotFoundException If the article was not found
     * @throws WordPressException If an error occured while pushing the article
     * 
     * @example WordPress::pushToHTI(24608, "Martin Dupont", WordPressCategories::MERCATO);
     */
    static function pushToHTI(int $id_article, string $nom, WordPressCategories $categorie): bool | WordPressException | ArticleNotFoundException
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/ArticleModel.php";

        $article = ArticleModel::getArticle($id_article);

        $env = parse_ini_file(__DIR__ . "/../../.env");

        $url = $env["WP_DOMAIN"] . "/wp-json/wp/v2/" . $categorie->value;

        $payload = [
            "title" => $article->getTitre(),
            "content" => "--\nArticle envoyé depuis RSS Troover par ". $nom  . "\n" . $article->getUrlArticle() ."\n--\n\n" . $article->getDescription(),
            "status" => "draft"
        ];

        $payload = json_encode($payload);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $env["WP_USERNAME"] . ":" . $env["WP_PASSWORD"]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info["http_code"] != 201 || !$res){
            throw new WordPressException();
        }

        return true;
    }
}
