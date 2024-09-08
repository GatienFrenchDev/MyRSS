<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Règles | RSS Troover</title>
    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/regles.css">
</head>

<body>
    <?php
    createSideBar(5);
    ?>
    <div class="container">

        <form method="post">
            <label style="font-size: x-large; font-weight: bold;">
                Nom de la règle
                <input type="text" name="nom" placeholder="Règle JO 2024" style="font-size: large;" required>
            </label>

            <hr>

            <label>
                Le titre contient un des mots clés suivants (délimités par des points virgules).
                <input type="text" name="contient-titre" placeholder="JO;Paris;Olympique">
            </label>

            <select name="operateur" id="operateur" style="width: fit-content;">
                <option value="et">ET</option>
                <option value="ou">OU</option>
            </select>

            <label>
                La description contient un des mots clés suivants (délimités par des points virgules).
                <input type="text" name="contient-description" placeholder="JO;Macron;JO 2024; Paris ;Gare de Lyon">
            </label>

            <hr>

            <label>
                <div style="display: flex; gap: 10px; align-items: baseline;">
                    Sensible à la casse
                    <input type="checkbox" name="sensible-casse">
                </div>
            </label>

            <label>
                L'article doit appartenir au flux suivant
                <select name="flux">
                    <?php
                    foreach ($fluxs as $flux) { ?>
                        <option value='<?= $flux["id_flux"] ?>'><?= $flux["nom"] ?></option>
                    <?php } ?>
                </select>
            </label>

            <hr>

            <label>
                Quand la règle est vérifiée, alors
                <select name="action" id="action">
                    <option value="1">Ajouter l'article aux favoris</option>
                    <option value="2">Recevoir une notification sur la plateforme</option>
                    <option value="3">Recevoir une notification par mail</option>
                </select>
            </label>

           <button>Créer cette règle</button>
        </form>

    </div>
</body>

</html>