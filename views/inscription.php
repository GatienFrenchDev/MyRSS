<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | RSS Troover</title>
    <link rel="stylesheet" href="css/inscription.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="shortcut icon" href="public/img/favicon.jpeg" type="image/x-icon">
</head>

<body>


    <div class="top-bar">

        <img src="img/logo_troover.png" height="32" alt="logo de troover">

        <div style="width: 25vw;"></div>

        <div class="login">
            <span>Compte déjà existant?</span>
            <a href="login">Connexion</a>
        </div>
    </div>


    <form method="post">

        <h2>Inscription</h2>
    
        <input type="text" placeholder="Prénom" name="prenom" required minlength="3" maxlength="45" autocomplete="given-name">

        <input type="text" placeholder="Nom" name="nom" required minlength="3" maxlength="45" autocomplete="family-name">

        <input type="email" placeholder="E-mail" name="email" required autocomplete="email">

        <input type="password" placeholder="Mot de passe" name="password" required minlength="8" maxlength="32" autocomplete="new-password">

        <input type="password" placeholder="Confirmer le mot de passe" name="password-confirmation" required minlength="8" maxlength="32" autocomplete="new-password">

        <input type="submit" value="Commencez maintenant">

        <p>En vous inscrivant vous acceptez<br> les <a href="conditions-utilisation">conditions d'utilisation</a></p>

    </form>




</body>

</html>