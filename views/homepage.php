<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyRSS</title>

    <link rel="stylesheet" href="css/articles.css">
    <link rel="stylesheet" href="css/article-reader.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/context-menu.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/onglet.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/lib/moment.js" defer></script>

    <script src="js/classes/API.js" defer></script>
    <script src="js/classes/Arborescence.js" defer></script>
    <script src="js/classes/ArticleReader.js" defer></script>
    <script src="js/classes/Article.js" defer></script>
    <script src="js/classes/BoutonAjoutDossier.js" defer></script>
    <script src="js/classes/BoutonAjoutFavoris.js" defer></script>
    <script src="js/classes/BoutonAjoutFlux.js" defer></script>
    <script src="js/classes/BoutonAjoutCollection.js" defer></script>
    <script src="js/classes/BoutonArticleTraite.js" defer></script>
    <script src="js/classes/Categorie.js" defer></script>
    <script src="js/classes/ContainerArticle.js" defer></script>
    <script src="js/classes/ContextMenu.js" defer></script>
    <script src="js/classes/Espace.js" defer></script>
    <script src="js/classes/FluxRSS.js" defer></script>
    <script src="js/classes/Header.js" defer></script>
    <script src="js/classes/BoutonAfficherCollections.js" defer></script>
    <script src="js/classes/CollectionItem.js" defer></script>
    <script src="js/classes/Tools.js" defer></script>

    <script src="js/main.js" defer></script>
</head>

<body>

    <div id="context-menu">
    </div>

    <?php
    createSideBar(1);
    ?>

    <div class="onglet">

        <div class="top">

            <img src="/docs/img/logo_troover.svg" height="35" alt="logo troover">

        </div>

        <div class="categories">

            <div class="categorie categorie-active" id="tous-les-posts">
                <div>
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12H15M3 6H21M3 18H21" stroke-width="2px" stroke-linecap="round" stroke-linejoin="round" fill="#707070"></path>
                    </svg>
                    <p>Tous les posts</p>
                </div>
            </div>

            <div class="categorie" id="btn-afficher-non-lu">
                <div>
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.5 12H5.88197C6.56717 12 7.19357 12.3871 7.5 13C7.80643 13.6129 8.43283 14 9.11803 14H14.882C15.5672 14 16.1936 13.6129 16.5 13C16.8064 12.3871 17.4328 12 18.118 12H21.5M8.96656 4H15.0334C16.1103 4 16.6487 4 17.1241 4.16396C17.5445 4.30896 17.9274 4.5456 18.2451 4.85675C18.6043 5.2086 18.8451 5.6902 19.3267 6.65337L21.4932 10.9865C21.6822 11.3645 21.7767 11.5535 21.8434 11.7515C21.9026 11.9275 21.9453 12.1085 21.971 12.2923C22 12.4992 22 12.7105 22 13.1331V15.2C22 16.8802 22 17.7202 21.673 18.362C21.3854 18.9265 20.9265 19.3854 20.362 19.673C19.7202 20 18.8802 20 17.2 20H6.8C5.11984 20 4.27976 20 3.63803 19.673C3.07354 19.3854 2.6146 18.9265 2.32698 18.362C2 17.7202 2 16.8802 2 15.2V13.1331C2 12.7105 2 12.4992 2.02897 12.2923C2.05471 12.1085 2.09744 11.9275 2.15662 11.7515C2.22326 11.5535 2.31776 11.3645 2.50675 10.9865L4.67331 6.65337C5.1549 5.69019 5.3957 5.2086 5.75495 4.85675C6.07263 4.5456 6.45551 4.30896 6.87589 4.16396C7.35125 4 7.88969 4 8.96656 4Z" stroke="#A3A3A3" stroke-width="2px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                    </svg>
                    <p>Non lu</p>
                    <p class="side-info" id="nb-total-non-lu"></p>
                </div>
            </div>

            <div class="categorie" id="btn-afficher-favoris">
                <div>
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" id="svg-favori" class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2827 3.45332C11.5131 2.98638 11.6284 2.75291 11.7848 2.67831C11.9209 2.61341 12.0791 2.61341 12.2152 2.67831C12.3717 2.75291 12.4869 2.98638 12.7174 3.45332L14.9041 7.88328C14.9721 8.02113 15.0061 8.09006 15.0558 8.14358C15.0999 8.19096 15.1527 8.22935 15.2113 8.25662C15.2776 8.28742 15.3536 8.29854 15.5057 8.32077L20.397 9.03571C20.9121 9.11099 21.1696 9.14863 21.2888 9.27444C21.3925 9.38389 21.4412 9.5343 21.4215 9.68377C21.3988 9.85558 21.2124 10.0372 20.8395 10.4004L17.3014 13.8464C17.1912 13.9538 17.136 14.0076 17.1004 14.0715C17.0689 14.128 17.0487 14.1902 17.0409 14.2545C17.0321 14.3271 17.0451 14.403 17.0711 14.5547L17.906 19.4221C17.994 19.9355 18.038 20.1922 17.9553 20.3445C17.8833 20.477 17.7554 20.57 17.6071 20.5975C17.4366 20.6291 17.2061 20.5078 16.7451 20.2654L12.3724 17.9658C12.2361 17.8942 12.168 17.8584 12.0962 17.8443C12.0327 17.8318 11.9673 17.8318 11.9038 17.8443C11.832 17.8584 11.7639 17.8942 11.6277 17.9658L7.25492 20.2654C6.79392 20.5078 6.56341 20.6291 6.39297 20.5975C6.24468 20.57 6.11672 20.477 6.04474 20.3445C5.962 20.1922 6.00603 19.9355 6.09407 19.4221L6.92889 14.5547C6.95491 14.403 6.96793 14.3271 6.95912 14.2545C6.95132 14.1902 6.93111 14.128 6.89961 14.0715C6.86402 14.0076 6.80888 13.9538 6.69859 13.8464L3.16056 10.4004C2.78766 10.0372 2.60121 9.85558 2.57853 9.68377C2.55879 9.5343 2.60755 9.38389 2.71125 9.27444C2.83044 9.14863 3.08797 9.11099 3.60304 9.03571L8.49431 8.32077C8.64642 8.29854 8.72248 8.28742 8.78872 8.25662C8.84736 8.22935 8.90016 8.19096 8.94419 8.14358C8.99391 8.09006 9.02793 8.02113 9.09597 7.88328L11.2827 3.45332Z" stroke="#A3A3A3" stroke-width="2px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                    </svg>
                    <p>Articles favoris</p>
                    <p class="side-info"></p>

                </div>
            </div>
        </div>

        <div id="arborescence">

        </div>

        <div class="ajout-dossier">
            <span>
                <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon small" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="#A3A3A3" stroke-width="2px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                </svg>
            </span>

            <p>Ajouter</p>
        </div>

    </div>

    <div class="articles">

        <div class="article-header">
            <span>
                Tous les posts
            </span>
        </div>

        <div id="container-articles">
        </div>

    </div>

    <div id="article-reader">
    </div>

</body>

</html>
