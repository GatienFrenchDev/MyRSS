<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partager | RSS Troover</title>

    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/espace.css">
    <link rel="stylesheet" href="css/share.css">

    <script src="/js/classes/API.js"></script>
    <script src="/js/share.js"></script>

    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>
    <?php
    createSideBar(1);
    ?>

    <div id="main">
        <div>
            <header>
                <h1>Avec qui voulez vous partager cette article ?</h1>
                <p>Liste de toutes les personnes présentes dans l'espace.</p>
            </header>
            <?php foreach ($participants as $participant) { ?>
                <button class="espace-container" onclick="shareArticle(<?= $id_article ?>, <?= $participant['id_utilisateur']?>)">
                    <div class="espace">
                        <div>
                            <h3 style="margin-bottom: 2px;"><?= $participant["prenom"] ?> <?= $participant["nom"] ?></h3>
                        </div>
                        <div>
                            <span style="display: flex; justify-content: center; align-items: center;"><?= $participant["email"] ?></span>
                        </div>
                    </div>
                </button>
            <?php } ?>
        </div>
    </div>

</body>

</html>