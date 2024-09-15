<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espaces | RSS Troover</title>

    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/espace.css">

    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>
    <?php
    createSideBar(6);
    ?>

    <div id="main">
        <div>
            <header>
                <h1>Espaces</h1>
                <p>Liste de tous les espaces enregistrés dans la base de données.</p>
            </header>
            <?php foreach ($espaces as $espace) { ?>
                <a href="espace?id=<?= $espace["id_espace"] ?>" class="espace-container">
                    <div class="espace">
                        <div>
                            <h3 style="margin-bottom: 2px;"><?= $espace["nom"] ?></h3>
                            <p><?= $espace["article_wp"] == 1 ? "(Accès autorisé au WP)" : "" ?></p>
                        </div>
                        <span style="display: flex; justify-content: center; align-items: center;"><?= $espace["nb_utilisateurs"] ?> participant<?= $espace["nb_utilisateurs"] > 1 ? "s" : "" ?></span>
                    </div>
                </a>
                <hr>
            <?php } ?>
        </div>
    </div>

</body>

</html>