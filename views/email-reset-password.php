<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset mot de passe | MyRSS</title>
    <link rel="stylesheet" href="css/inscription.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>


    <div class="top-bar">
        <img src="img/logo_troover.png" height="32" alt="logo de troover">

        <div style="width: 25vw;"></div>

        <div class="login">
        </div>
    </div>


    <form method="post">

        <h2>Choix du mdp</h2>

        <input type="password" placeholder="•••••••" name="password" required autocomplete="new-password" minlength="8" maxlength="32">
        <input type="hidden" name="token" value="<?= $token ?>">
        <input type="hidden" name="email" value="<?= $email ?>">

        <input type="submit" value="Définir comme mdp">

    </form>

</body>

</html>