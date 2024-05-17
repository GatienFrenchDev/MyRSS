<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recherche | MyRSS</title>
    <link rel="stylesheet" href="view/css/style.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/resultat.css">
</head>

<body>
    <?php
    createSideBar(3);
    ?>
    <div class="container">

        <h1>Résultat</h1>
        <span><?= count($articles) > 1 ? (count($articles)<100?count($articles) . " articles trouvés":"+ 100 articles trouvés") : (count($articles) . " article trouvé") ?></span>

        <section>
            <!-- ( [id_article] => 3606 [titre] => NVIDIA, Teradyne and Siemens Gather in the ‘City of Robotics’ to Discuss Autonomous Machines and AI [description] => Senior executives from NVIDIA, Siemens and Teradyne Robotics gathered this week in Odense, Denmark, to mark the launch of Teradyne’s new headquarters and discuss the massive advances coming to the robotics industry. One of Denmark’s oldest cities and known as the city of robotics, Odense is home to over 160 robotics companies with 3,700 employees Read Article [date_pub] => 1715808673 [id_flux] => 27 [url_article] => https://blogs.nvidia.com/blog/nvidia-teradyne-siemens-robotics-autonomous-machines-ai/ [date_ajout] => 1715854757 [adresse_url] => https://blogs.nvidia.com/feed/ [nom] => NVIDIA Blog [type_flux] => rss ) -->
            <?php foreach ($articles as $article) { ?>
                <article>
                    <h3><?= $article["titre"] ?></h3>
                    <p style="font-size: 14px;">Publié le <?= date("d/m/Y" ,$article["date_pub"]) ?> par <?= $article["nom"] ?></p>
                    <p><?= $article["description"] ?></p>
                </article>

            <?php } ?>
        </section>

    </div>
</body>

</html>