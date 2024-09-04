<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte | RSS Troover</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/compte.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>
    <?php
    createSideBar(0);
    ?>
    <div class="container">
        <div>
            <h1>Mon profil</h1>
            <h3><?= $utilisateur["prenom"] ?> <?= $utilisateur["nom"] ?></h3>
            <p>Inscrit depuis le <b><?= date("d/m/Y", $utilisateur["date_inscription"]) ?></b> avec l'email <b><?= $utilisateur["email"] ?></b></p>
        </div>

    </div>
</body>

</html>