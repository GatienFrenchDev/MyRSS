<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace | RSS Troover</title>

    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/espace.css">

    <script src="/js/classes/api.js"></script>
    <script src="/js/details-espace.js"></script>

    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>

    <?php
    createSideBar(6);
    ?>

    <div id="container-espace">

        <h1><?= $espace["nom"] ?></h1>
        <h2>Accès au WP : <?= $espace["article_wp"] == 1 ? "Oui" : "Non" ?></h2>
        <button style="padding: 4px; width: fit-content;" onclick="toggleAccessToWP(<?= $espace['id_espace']?>)">Donner l'accès au WP</button>

        <div class="participants">
            <h2>Participants (<?= $espace["nb_utilisateurs"] ?>)</h2>
            <?php foreach ($participants as $participant): ?>
                <div class="participant">
                    <p><?= $participant["prenom"] ?> <?= $participant["nom"] ?> (<?= $participant["email"] ?>) en tant que <b><?= $participant["role"] ?></b></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>