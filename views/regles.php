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

        <?php
        if (count($rules) === 0) {
        ?>
            <h1>Vous n'avez pas encore de règles</h1>
        <?php
        } else {
        ?>
            <div>
                <h1>Vos règles</h1>
                <table>
                    <tr>
                        <th>Nom</th>
                        <th>Flux</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    foreach ($rules as $rule) {
                    ?>
                        <tr>
                            <td><?= $rule["nom"] ?></td>
                            <td><?= $rule["nom_flux"] ?></td>
                            <td><?= $rule["action"] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <a href="nouvelle-regle">
                    <button>Créer une nouvelle règle</button>
                </a>
            </div>
        <?php
        }
        ?>
    </div>
</body>

</html>