<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recherche | MyRSS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/side-bar.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/recherche.css">
</head>

<body>
    <?php
    createSideBar(3);
    ?>
    <div class="container">


        <form action="resultat">
            <h1>Votre recherche</h1>
            <input type="text" placeholder="Mot clé" id="text" name="text" autocomplete="off">
            <span>
                <input type="hidden" name="numero-page" value="0">
                <div>
                    <label for="fin">Publié avant le</label>
                    <input type="date" name="fin" id="fin">
                </div>
                <div>
                    <label for="debut">Publié après le</label>
                    <input type="date" name="debut" id="debut">
                </div>

                <div>
                    <label for="article-lu">Article lu</label>
                    <input type="checkbox" name="article-lu" id="article-lu" checked>
                </div>
                <div>
                    <label for="article-non-lu">Article non lu</label>
                    <input type="checkbox" name="article-non-lu" id="article-non-lu" checked>
                </div>

                <div>
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie">
                        <option value="0">Toutes les catégories</option>
                        <?php
                        foreach ($categories as $categorie) {
                            echo "<option value='" . $categorie["id_categorie"] . "'>" . $categorie["nom"] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="tri">Trier par ordre</label>
                    <select name="tri" id="tri">
                        <option value="desc">Décroissant</option>
                        <option value="asc">Croissant</option>
                    </select>
                </div>

            </span>
            <input type="submit" value="Valider" id="valider">
        </form>

    </div>
</body>

</html>