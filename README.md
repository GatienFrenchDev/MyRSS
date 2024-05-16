# 📰 MyRSS

![PHP](./docs/img/php.svg)
![MySQL](./docs/img/mysql.svg)
![JS](./docs/img/js.svg)

## Outil collaboratif de gestion de flux RSS

Un projet développé à Tours, France.

## Fonctionnalités

_Toutes les fonctionnalitées notées ci dessous ne sont pas forcement encore disponibles pour l'instant mais seront ajoutées au fur et à mesure du temps._

- Création d'espaces collaboratifs avec différents utilisateurs.
- Ajout de flux RSS dans des catégories spécifiques.
- Prise en charge des fluxs RSS, des chaînes YouTube, Google News, etc.
- Recommandations de fluxs RSS et d'articles entre utilisateurs.
- Taux de rafraîchissement des articles inférieur à 5 minutes.
- Export des articles aux formats xlsx, csv, json, ...

## Technologies utilisées

MyRSS s'appuie sur plusieurs projets open source pour fonctionner efficacement :

- [moment.js](https://github.com/moment/moment/) - Une bibliothèque JavaScript pour l'analyse, la validation, la manipulation et le formatage des dates.
- [rss-php](https://github.com/dg/rss-php) - Une petite bibliothèque PHP pour faciliter le traitement des flux RSS.

MyRSS est basé sur PHP 8.2 et MariaDB 10.4 (testé également sous Ubuntu 24.04 avec PHP 8.3.6 et MySQL 8.0.36-2ubuntu3)

## Déploiement local sous Windows pour environnement de développement

- Téléchargez d'abord [XAMPP](https://www.apachefriends.org/fr/index.html) avec les modules `Apache` et `MySQL` (l'installation par défaut suffira).
- Une fois installé, lancez XAMPP en tant qu'administrateur (clic droit sur `xampp.exe` > `Exécuter en tant qu'administrateur`).
- Démarrez `Apache` et `MySQL` depuis le panneau de contrôle de XAMPP.
- Téléchargez le code source de ce dépôt (bouton vert en haut à droite sur le dépôt, intitulé `<> Code`, puis `Download ZIP`).
- Placez la totalité des fichiers contenu dans le dossier `MyRSS-Main` de l'archive dans le dossier `C:/xampp/htdocs`, en ayant préalablement vidé le contenu du dossier.
- Accédez à l'adresse `http://localhost/phpmyadmin`, créer une base de données intitulée `myrss` puis allez dans l'onglet `Importer` et importez le fichier nommé `/docs/db_example.sql` présent dans le dépôt.
- Remplacez dans le fichier `lib/tools.php:3` les `xxxxx` par votre clé d'API YouTube pour que l'ajout de chaines YouTube en tant que flux fonctionne (voir https://console.cloud.google.com/apis/api/youtube.googleapis.com/credentials si besoin de créer une clé d'API).
- Le site web devrait maintenant fonctionner sans problème ! Un compte de test est déjà crée dans la base de données avec les identifiants suivants :
```
mail : john@example.com
pass : password
```
- Pour récupérer les nouveaux articles, il faut envoyer une requete GET à l'url `/scripts/fetch-all-fluxs`

## Déploiment sous Ubuntu pour production

⏳ à venir ...

## Déploiment sous Docker pour production
⏳ à venir également...

## Structure du projet

MyRSS essaye de se baser sur une architecture MVC.

```
.
├───api                 # Endpoints API appelés depuis le JS côté client
├───docs                # Fichiers utiles à la documentation du projet
|   ├───img             
│   └───mcd             
├───includes            # Fichiers PHP appelés lors de l’envoi de formulaire
├───lib                 # Librairies PHP utiles au projet
├───model               # Regroupement des fonctions interrogeant la db
├───scripts             # Script à executer pour récupérer les derniers articles
├───tests               
└───view                # Templates HTML
    ├───components      # Composants HTML ré-utilisés
    ├───css             
    └───js              
        ├───classes
        └───lib         # Librairies tierces utilisés dans le JS
```

## Crédits
- https://github.com/Ileriayo/markdown-badges : fournisseur des badges présents dans le readme

- https://www.looping-mcd.fr/ - Logiciel de modélisation conceptuelle utilisé pour réaliser le mcd puis mld