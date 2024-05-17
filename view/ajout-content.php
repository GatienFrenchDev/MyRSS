<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout <?= htmlspecialchars($type["titre"]) ?> | MyRSS</title>
    <link rel="stylesheet" href="view/css/style.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/add-content.css">
</head>

<body>
    <?php
    createSideBar(0);
    ?>
    <div class="container" style="padding: 0;">

        <div class="top-bar">
            <a href="<?= $url_btn_retour ?>">
                <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="#707070" stroke-width="2px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                </svg>
            </a>
            <div>
                <p>Ajouter un flux</p>
                <h2><?= htmlspecialchars($type["titre"]) ?></h2>
            </div>
        </div>

        <form action="includes/ajout-flux-rss.inc.php" method="post">

            <h3><?= htmlspecialchars($type["description"]) ?></h3>

            <input type="hidden" name="type_flux" value="<?= $nom_type ?>">

            <input type="hidden" name="espace" value="<?= $id_espace ?>">

            <?php
            if(!is_null($id_categorie)){ ?>

            <input type="hidden" name="categorie" value="<?= $id_categorie ?>">
            
            <?php
            }
            ?>

            <div>
                <input name="adresse" type="<?= $type["type_input"] ?>" id="adresse" placeholder="<?= htmlspecialchars($type["placeholder"]) ?>" required>
                <input type="submit" value="Ajouter" id="envoi">
            </div>

        </form>

    </div>
</body>

</html>