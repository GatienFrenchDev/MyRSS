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

    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>

    <?php
    createSideBar(6);
    ?>

    <div id="container-espace">

        <h1><?= $espace["nom"] ?></h1>

        <div class="participants">
            <h2>Participants</h2>
            <?php foreach ($participants as $participant): ?>
                <div class="participant">
                    <p><?= $participant["prenom"] ?> <?= $participant["nom"] ?></p>
                    <p><?= $participant["email"] ?></p>
                    <p><?= $participant["role"] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>