<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout | MyRSS</title>
    <link rel="stylesheet" href="view/css/style.css">
    <link rel="stylesheet" href="view/css/side-bar.css">
    <link rel="stylesheet" href="view/css/font.css">
    <link rel="stylesheet" href="view/css/add-content.css">
</head>

<body>
    <?php
    createSideBar(0);
    ?>

    <div class="container">

        <div class="categorie-ajout">
            <h4>Fluxs disponibles</h4>

            <div class="type">
                <a href="ajout-content?type=rss">
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 11C3 10.4477 3.44772 10 4 10C6.65216 10 9.1957 11.0536 11.0711 12.9289C12.9464 14.8043 14 17.3478 14 20C14 20.5523 13.5523 21 13 21C12.4477 21 12 20.5523 12 20C12 17.8783 11.1571 15.8434 9.65685 14.3431C8.15656 12.8429 6.12173 12 4 12C3.44772 12 3 11.5523 3 11Z" fill="#FFFFFF"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 4C3 3.44772 3.44772 3 4 3C8.50868 3 12.8327 4.79107 16.0208 7.97919C19.2089 11.1673 21 15.4913 21 20C21 20.5523 20.5523 21 20 21C19.4477 21 19 20.5523 19 20C19 16.0218 17.4196 12.2064 14.6066 9.3934C11.7936 6.58035 7.97825 5 4 5C3.44772 5 3 4.55228 3 4Z" fill="#FFFFFF"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 19C3 17.8954 3.89543 17 5 17C6.10457 17 7 17.8954 7 19C7 20.1046 6.10457 21 5 21C3.89543 21 3 20.1046 3 19Z" fill="#FFFFFF"></path>
                    </svg>
                    <span>Flux RSS</span>
                </a>

                <a href="ajout-content?type=yt">
                    <svg id="yt-icon" xmlns:xlink="http://www.w3.org/1999/xlink" class="icon" width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.20445 10.4471C4 11.2101 4 12.1401 4 14C4 15.8599 4 16.7899 4.20445 17.5529C4.75925 19.6235 6.37653 21.2408 8.44709 21.7956C9.21008 22 10.1401 22 12 22H16C17.8599 22 18.7899 22 19.5529 21.7956C21.6235 21.2408 23.2408 19.6235 23.7956 17.5529C24 16.7899 24 15.8599 24 14C24 12.1401 24 11.2101 23.7956 10.4471C23.2408 8.37653 21.6235 6.75925 19.5529 6.20445C18.7899 6 17.8599 6 16 6H12C10.1401 6 9.21008 6 8.44709 6.20445C6.37653 6.75925 4.75925 8.37653 4.20445 10.4471ZM18 14.0004L12 18V10L18 14.0004Z" fill="#FFFFFF"></path>
                    </svg>
                    <span>Youtube</span>
                </a>
            </div>
        </div>

    </div>
</body>

</html>