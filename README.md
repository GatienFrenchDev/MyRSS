# 📰 MyRSS

## Outil collaboratif de gestion de flux RSS

Un projet développé à Tours, France.

## Fonctionnalités

_Toutes les fonctionnalitées notées ci dessous ne sont pas forcement encore disponibles pour l'instant mais seront ajoutées au fur et à mesure du temps._

- Création d'espaces collaboratifs avec différents utilisateurs.
- Ajout de flux RSS dans des catégories spécifiques.
- Prise en charge des flux RSS, des chaînes YouTube, Google News, etc.
- Taux de rafraîchissement des articles inférieur à 5 minutes.
- Export des articles aux formats xlsx, csv, json, ...

## Technologies utilisées

MyRSS s'appuie sur plusieurs projets open source pour fonctionner efficacement :

- [moment.js](https://github.com/moment/moment/) - Une bibliothèque JavaScript pour l'analyse, la validation, la manipulation et le formatage des dates.
- [rss-php](https://github.com/dg/rss-php) - Une petite bibliothèque PHP pour faciliter le traitement des flux RSS.
- [looping](https://www.looping-mcd.fr/) - Un logiciel de modélisation conceptuelle de données / modèle logique de données.

### Basé sur les technologies

<div style="display:flex;">
  <img src="https://upload.wikimedia.org/wikipedia/commons/3/31/Webysther_20160423_-_Elephpant.svg" height="36">
  <img src="https://d1.awsstatic.com/logos/partners/MariaDB_Logo.d8a208f0a889a8f0f0551b8391a065ea79c54f3a.png" height="36">
  <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Unofficial_JavaScript_logo_2.svg" height="36">
</div>

PHP 8.2, MariaDB 10.4

## Déploiement local sous Windows

- Téléchargez d'abord [XAMPP](https://www.apachefriends.org/fr/index.html) avec les modules `Apache` et `MySQL` (l'installation par défaut suffira).
- Une fois installé, lancez XAMPP en tant qu'administrateur (clic droit sur `xampp.exe` > `Exécuter en tant qu'administrateur`).
- Démarrez `Apache` et `MySQL` depuis le panneau de contrôle de XAMPP.
- Téléchargez le code source de ce dépôt (bouton vert en haut à droite sur le dépôt, intitulé `<> Code`, puis `Download ZIP`).
- Placez la totalité des fichiers contenu dans le dossier `MyRSS-Main` de l'archive dans le dossier `C:/xampp/htdocs`, en ayant préalablement vidé le contenu du dossier.
- Accédez à l'adresse `http://localhost/phpmyadmin`, créer une base de données intitulée `myrss` puis allez dans l'onglet `Importer` et importez le fichier nommé `/docs/db_exemple.sql` présent dans le dépôt.
- Remplacez dans le fichier `lib/tools.php:3` les `xxxxx` par votre clé d'API YouTube pour que l'ajout de chaines YouTube en tant que flux fonctionne(voir https://console.cloud.google.com/apis/api/youtube.googleapis.com/credentials si besoin de créer une clé d'API).
- Le site web devrait maintenant fonctionner sans problème ! Un compte de test est déjà crée dans la base de données avec les identifiants suivants :
```
mail : user@exemple.com
pass : password
```