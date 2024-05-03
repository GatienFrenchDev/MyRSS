<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | MyRSS</title>
    <link rel="stylesheet" href="view/css/style.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/notifications.css">
</head>

<body>
    <?php
    createSideBar(2);
    ?>


    <div class="container">
        <?php

        if (count($invitations) == 0) {
            echo ('<p style="padding:20px;"><i>Pas de nouvelles notifications...</i></p>');
        }

        foreach ($invitations as $invitation) {
        ?>
            <article class="notification">
                <h3>Invitation</h3>
                <p>Invitation à rejoindre l'espace <i><?= $invitation["nom_espace"] ?></i> de <b><?= $invitation["prenom"] ?></b></p>
                <a href="api/accepter-invitation.php?id_invitation=<?= $invitation["id_invitation"] ?>">Accepter</a>
                <a href="api/refuser-invitation.php?id_invitation=<?= $invitation["id_invitation"] ?>">Refuser</a>
            </article>

        <?php
        }
        ?>
    </div>


</body>

</html>