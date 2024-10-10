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

    <script src="/js/classes/API.js"></script>
    <script src="/js/details-espace.js"></script>

    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>

    <?php
    createSideBar(6);
    ?>

    <div id="container-espace">

        <a href="espaces">
            ↩ Retour aux espaces
        </a>

        <h1><?= $espace["nom"] ?></h1>
        <h2>Accès au WP : <?= $espace["article_wp"] == 1 ? "Oui" : "Non" ?></h2>
        <div style="display: flex; gap: 8px;">
            <button style="padding: 4px; width: fit-content;" onclick="toggleAccessToWP(<?= $espace['id_espace'] ?>)">Donner l'accès au WP</button>
            <button style="padding: 4px; width: fit-content;" onclick="deleteEspace(<?= $espace['id_espace'] ?>)">Supprimer l'espace</button>
        </div>

        <div class="participants">
            <h2>Participant<?= $espace["nb_utilisateurs"] > 1 ? "s" : ""?> (<?= $espace["nb_utilisateurs"] ?>)</h2>
            <ul>
                <?php foreach ($participants as $participant): ?>
                    <li>
                        <p><?= $participant["prenom"] ?> <?= $participant["nom"] ?> (<?= $participant["email"] ?>) en tant que <b><?= $participant["role"] ?></b></p>
                        <button onclick="removeParticipant(<?= $participant['id_utilisateur'] ?>, <?= $espace['id_espace'] ?>)">Retirer de l'espace</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</body>

</html>