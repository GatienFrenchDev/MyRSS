# A faire

Ce fichier regroupe toutes les taches à réaliser pour contribuer à l'avancement du projet.

## Taches principales

- système de vérification par email lors de la création du compte (générer un uuid puis faire un lien du style /valider?id_utilisateur=xxxx&uuid=xxxxxx)
https://www.php.net/manual/en/function.mail.php
https://www.php.net/manual/fr/function.uniqid.php

- système de reset de mdp par email

- marquer un article comme traité (fonctionne par espace partagé)

- changer les endpoints GET en POST de manière à ce que ce soit cohérent

- bien vérifier à check que les parametres du style numero_page soit des nombres (avec function `ctype_digit()`)

- améliorer UI dans `scripts/fetch-all-fluxs.php`

- lorsque l'on ajoute un flux, l'interroger pour ajouter les articles dans la db

- faire systeme de notif en bas à gauche de l'écran pour afficher des notifs du style : "Flux ajouté avec succés, Invitation envoyé avec succés, ..."

## Points d'améliorations

### `/`

- idéalement remplacer la classe `mysqli` par `PDO` dans `/model`

- documenter chaque fonction comme dans la fonction `getIDFromYoutubeChannel()` dans le fichier `/lib/tools.php`

- documenter et typer tous les fonctions (php et js)
https://stackoverflow.com/questions/34205666/utilizing-docstrings
https://docs.devsense.com/en/vs/editor/phpdoc

- faire un peu d'erreur handling dans `model.php`
https://www.php.net/manual/en/language.exceptions.php

- documenter toute l'API avec une collection postman
https://www.postman.com/

- mettre code reponse php sur chaque retour API
https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP

- mettre tout le code en anglais (pas de variables, commentaires, nom de fonctions, etc en francais)

- changer le type de balise représentant un article (au lieu que l'article soit une `<div>`, l'article doit être un `<article>`)
https://developer.mozilla.org/fr/docs/Web/HTML/Element/article


### `/api`
- documenter chaque endpoint (voir ex à `/api/get-articles.php`)

- vérifier tous les parametres dans les `GET` et `POST` avec les fonctions `filter_var()` et renvoyer une erreur (données du type URL, email, ...)
https://www.php.net/manual/fr/function.filter-var.php

### `/includes/inscription.inc.php`
- améliorer la vérification des données d'entrées par le formulaire