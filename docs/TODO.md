# A faire

## Grosses taches

- système de vérification par email lors de la création du compte (générer un uuid puis faire un lien du style /valider?id_utilisateur=xxxx&uuid=xxxxxx)
https://www.php.net/manual/en/function.mail.php
https://www.php.net/manual/fr/function.uniqid.php

- système de reset de mdp par email

- marquer un article comme traité (fonctionne par espace partagé)

- recommander un flux à une personne (clic droit sur le flux puis saisi de l'adresse email puis envoi notification à la personne) (voir  `view/js/classes/FluxRSS.js:37`)

- ✅ ~~système de pagination dans les articles (utiliser mot clé SQL OFFSET LIMIT, stocker en variable la page courante en js et modifier endpoint API pour avoir un truc du style : `/api/getArticles?page=1&per_page=25`)
https://sql.sh/cours/limit~~ **Fini le 21/04 par Gatien** 

- lorsque l'on clique sur `Ajouter un flux` que l'on se positionne directement dans le répoirtoire courant (faire référence à la variable globale `arborescence` et aller sur la page `choix-content.php` avec en query `?id_espace=xx&id_categorie=xx`)

## Points d'améliorations

### `/`

- documenter chaque fonction comme dans la fonction `getIDFromYoutubeChannel()` dans le fichier `/lib/tools.php`

- changer nom variable session `$_SESSION["id_user"]` en `$_SESSION["id_utilisateur"]` (pour consistance nommage variables dans tout le projet)

- utiliser seulement des `require ""` et pas des `require()` ou `include` ou `include_once`

- revoir la structure du projet (voir good pratices php project)

https://phptherightway.com/

https://github.com/php-pds/skeleton

- documenter et typer tous les fonctions (php et js)
https://stackoverflow.com/questions/34205666/utilizing-docstrings
https://docs.devsense.com/en/vs/editor/phpdoc

- faire un peu d'erreur handling dans `model.php`
https://www.php.net/manual/en/language.exceptions.php

- documenter toute l'API avec postman
https://www.postman.com/

- mettre code reponse php sur chaque retour API
https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP

- mettre tout le code en anglais (pas de variables, commentaires, nom de fonctions, etc en francais)

- changer le type de balise représentant un article (au lieu que l'article soit une `<div>`, l'article doit être un `<article>`)
https://developer.mozilla.org/fr/docs/Web/HTML/Element/article

- constitance naming functions en camelCase(surtout dans fichier `model/model.php`)

- faire toutes les requetes SQL avec des `prepared statements`
https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php

- créditer auteur librairie `rss-php`
https://github.com/dg/rss-php

- créditer auteur libraire `moment.js`
https://momentjs.com/


### `/api`
- refaire le systeme de check (pour éviter les `if` emboités)
https://www.geeksforgeeks.org/writing-clean-else-statements/

- documenter chaque endpoint (voir ex à `/api/get-articles.php`)

- vérifier tous les parametres dans les `GET` et `POST` avec les fonctions `filter_var()` et renvoyer une erreur (données du type URL, email, ...)
https://www.php.net/manual/fr/function.filter-var.php

### `/includes/inscription.inc.php`
- améliorer la vérification des données d'entrées par le formulaire

- changer mode de requete mysqli pour des requetes parametriques

### `/view/js`
- répartir les fonctions js en plusieurs fichiers (voir peut être créer des classes si besoin...)


## Trucs utiles

http://www.google.com/s2/favicons?domain=univ-tours.fr
> Pour récup favicon d'un site simplement

https://www.youtube.com/feeds/videos.xml?channel_id=UC2edQ0WvtZnYFg-o1WyNSpg
> Pour récup content d'une chaine YouTube
