<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recherche | MyRSS</title>
    <link rel="stylesheet" href="view/css/colors.css">
    <link rel="stylesheet" href="view/css/style.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/resultat.css">
</head>

<body>
    <?php
    createSideBar(3);
    ?>
    <div class="container">

        <h1>Résultat</h1>
        <span><?= count($articles) > 1 ? (count($articles)<100?count($articles) . " articles trouvés":"+ 100 articles trouvés") : (count($articles) . " article trouvé") ?></span>

        <section>
            <?php foreach ($articles as $article) { ?>
                <article>
                    <h3><a href="<?= $article["url_article"] ?>" target="_blank"><?= $article["titre"] ?></a></h3>
                    <p style="font-size: 14px;">Publié le <?= date("d/m/Y" ,$article["date_pub"]) ?> par <?= $article["nom"] ?></p>
                    <p><?= $article["description"] ?></p>
                </article>

            <?php } ?>
        </section>

        <?php if(count($articles) >= 100 || $numero_page != 0){ ?>
        <section id="navigation">
            <a <?= $parametre_href_url_page_precedente ?>>précédent</a>
            <a <?= $parametre_href_url_page_suivante ?>>suivant</a>
        </section>
        <?php } ?>

    </div>
</body>

</html>