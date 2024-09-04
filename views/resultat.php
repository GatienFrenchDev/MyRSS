<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche | RSS Troover</title>

    <link rel="stylesheet" href="css/articles.css">
    <link rel="stylesheet" href="css/article-reader.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/context-menu.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/onglet.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/resultat.css">

    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">


    <script src="js/lib/moment.js" defer></script>

    <script src="js/classes/API.js" defer></script>
    <script src="js/classes/ArticleReader.js" defer></script>
    <script src="js/classes/Article.js" defer></script>
    <script src="js/classes/BoutonAjoutCollection.js" defer></script>
    <script src="js/classes/BoutonAjoutFavoris.js" defer></script>
    <script src="js/classes/ContainerArticle.js" defer></script>
    <script src="js/classes/ContextMenu.js" defer></script>
    <script src="js/classes/Tools.js" defer></script>
    <script src="js/classes/BoutonAfficherCollections.js" defer></script>
    <script src="js/classes/CollectionItem.js" defer></script>


    <script src="js/recherche.js" defer></script>

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