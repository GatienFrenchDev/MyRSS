<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche | MyRSS</title>

    <link rel="stylesheet" href="view/css/articles.css">
    <link rel="stylesheet" href="view/css/article-reader.css">
    <link rel="stylesheet" href="view/css/colors.css">
    <link rel="stylesheet" href="view/css/context-menu.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/onglet.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/style.css">

    <link rel="stylesheet" href="view/css/resultat.css">

    <script src="view/js/lib/moment.js" defer></script>

    <script src="view/js/classes/API.js" defer></script>
    <script src="view/js/classes/ArticleReader.js" defer></script>
    <script src="view/js/classes/Article.js" defer></script>
    <script src="view/js/classes/BoutonAjoutFavoris.js" defer></script>
    <script src="view/js/classes/ContainerArticle.js" defer></script>
    <script src="view/js/classes/Tools.js" defer></script>
    <script src="view/js/classes/BoutonAfficherTags.js" defer></script>
    <script src="view/js/classes/TagItem.js" defer></script>


    <script src="view/js/recherche.js" defer></script>

</head>

<body>

    <div id="context-menu">
    </div>

    <?php
    createSideBar(3);
    ?>



    <div class="articles">

        <div class="article-header">
            <span>
                Résultat de la recherche
            </span>
        </div>

        <div id="container-articles">
        </div>

    </div>

    <div id="article-reader">
    </div>

</body>

</html>