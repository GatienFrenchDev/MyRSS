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
    <script src="js/classes/API.js" defer></script>
    <script src="js/regle.js" defer></script>

</head>

<body>
    <?php
    createSideBar(5);
    ?>
    <div class="container">

        <div style="width: 600px;">

            <?php
            if (count($rules) === 0) {
            ?>
                <h1 class="titre">Aucune règle définie.</h1>
            <?php
            } else {
            ?>
                <h1 class="titre">Règles</h1>
                <?php
                foreach ($rules as $rule) {
                ?>
                    <div class="rule-container" id="rule-<?= $rule['id_regle'] ?>">
                        <div>
                            <h2><?= $rule["nom"] ?></h2>
                            <p class="rule-description">Se déclenche quand <b><?= $rule["nom_flux"] ?></b> publie un nouvelle article<?php echo (!empty($rule["contient_titre"]) ? (" qui contient <code>" . strip_tags($rule["contient_titre"]) . "</code> dans le titre") : "") ?><?php echo (!empty($rule["contient_description"]) ? ", qui contient <code>" . strip_tags($rule["contient_description"]) . "</code> dans la description" : "") ?>.</p>
                        </div>
                        <button id="btn-delete" aria-label="supprimer règle" onclick="deleteRule(<?= $rule['id_regle'] ?>)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M14 10V17M10 10V17" stroke="currentColor" stroke-width=".5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                <?php
                }
                ?>
            <?php
            }
            ?>
            <a href="nouvelle-regle">
                <button>Créer une nouvelle règle</button>
            </a>
        </div>
    </div>
</body>

</html>