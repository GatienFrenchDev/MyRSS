<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recherche | MyRSS</title>
    <link rel="stylesheet" href="view/css/style.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/recherche.css">
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
                <label for="debut">Publié entre le</label>
                <input type="date" name="debut" id="debut">
                <input type="hidden" name="numero-page" value="0">
                <label for="fin">et le</label>
                <input type="date" name="fin" id="fin">
                <label for="article-lu">Article lu</label>
                <input type="checkbox" name="article-lu" id="article-lu" checked>
                <label for="article-non-lu">Article non lu</label>
                <input type="checkbox" name="article-non-lu" id="article-non-lu" checked>
                <label for="tri">Trier par ordre</label>
                <select name="tri" id="tri">
                    <option value="desc">Décroissant</option>
                    <option value="asc">Croissant</option>
                </select>
            </span>
            <input type="submit" value="Valider" id="valider">
        </form>

    </div>
</body>

</html>