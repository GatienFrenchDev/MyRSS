<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | MyRSS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>

<body>
    <?php
    createSideBar(2);
    ?>


    <div class="container">
        <?php

        if (count($invitations) == 0 && count($notifications) == 0) {
            echo ('<p style="padding:20px;"><i>Pas de nouvelles notifications...</i></p>');
        }

        foreach ($invitations as $invitation) {
        ?>
            <article class="notification">
                <h3>Invitation</h3>
                <p>Invitation à rejoindre l'espace <i><?= $invitation["nom_espace"] ?></i> de <b><?= $invitation["invitateur"]["prenom"] ?> <?= $invitation["invitateur"]["nom"] ?></b> en tant que <?= $invitation["reader_only"] ? "lecteur seul" : "collaborateur" ?></p>
                <a href="api/accepter-invitation.php?id_invitation=<?= $invitation["id_invitation"] ?>">Accepter</a>
                <a href="api/refuser-invitation.php?id_invitation=<?= $invitation["id_invitation"] ?>">Refuser</a>
            </article>

        <?php
        }
        foreach ($notifications as $notification) {
        ?>
            <article class="notification">
                <h3><?= htmlspecialchars($notification["titre"]) ?></h3>
                <?php
                if (isset($notification["description"])) {
                ?>
                    <p><?= $notification["description"] ?></p>
                <?php
                }
                ?>
                <a href="api/supprimer-notification?id_notification=<?=$notification["id_notification"]?>">Supprimer</a>
            </article>

        <?php
        }
        ?>

    </div>


</body>

</html>