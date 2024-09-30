# 📰 MyRSS

![PHP](./docs/img/php.svg)
![MySQL](./docs/img/mysql.svg)
![JS](./docs/img/js.svg)
![Docker](./docs/img/docker.svg)

## Lecteur de flux RSS avancé et collaboratif

Un projet développé à Tours, France.

## Fonctionnalités

_Toutes les fonctionnalitées notées ci dessous ne sont pas forcement encore disponibles pour l'instant mais seront ajoutées au fur et à mesure du temps._

- Création d'espaces collaboratifs avec différents utilisateurs.
- Ajout de flux RSS dans des catégories spécifiques.
- Prise en charge des fluxs RSS 2.0 et Atom, des chaînes YouTube, Google News, etc.
- Recommandations de fluxs RSS et d'articles entre utilisateurs.
- Taux de rafraîchissement des articles inférieur à 5 minutes.
- Export des articles aux formats xlsx, csv, json, ...

## Technologies utilisées

MyRSS s'appuie sur plusieurs projets open source pour fonctionner efficacement :

- [moment.js](https://github.com/moment/moment/) - Une bibliothèque JavaScript pour l'analyse, la validation, la manipulation et le formatage des dates.
- [SimpleAntiBruteForce](https://github.com/GatienFrenchDev/SimpleAntiBruteForce) - Une petite bibliothèque PHP codé par moi même pour l'occasion afin de gérer les tentatives de connexion erronées sur le formulaire de connexion

MyRSS fonctionne actuellement sous Docker 27.2.0 avec PHP 8.3.9 et MySQL 9 (testé également sous Ubuntu 24.04 avec PHP 8.3.6 et MySQL 8.0.36-2ubuntu3)

## Déploiment de MyRSS
- [Guide d'installation pour Docker](./docs/installation_guide_docker.md) (recommandé)
- [Guide d'installation pour XAMPP](./docs/installation_guide_xampp.md)
- [Guide d'installation pour Ubuntu](./docs/installation_guide_ubuntu.md)

## Structure du projet

La structure de **MyRSS** est organisée selon une architecture MVC (Modèle-Vue-Contrôleur), ce qui permet une séparation claire des responsabilités entre les différentes parties de l'application. Cette organisation facilite le développement, la maintenance et l'évolution du projet. Voici un aperçu détaillé des différents répertoires et de leur contenu :


```
.
├───docs            # Fichiers utiles à la documentation du projet
│   ├───img
│   └───mcd
├───lib             # Librairies PHP utiles au projet
├───public
│   ├───api         # Endpoints API appelés depuis le JS côté client
│   ├───css
│   └───js
│       ├───classes
│       └───lib     # Librairies tierces utilisés dans le JS
├───scripts
├───src
│   ├───classes
│   └───model       # Regroupement des fonctions interrogeant la db
├───tests
└───views           # Templates HTML
    └───components  # Composants HTML ré-utilisés
```

## Modèle Conceptuel des Données (MCD) et Modèle Logique des Données (MLD)

La section suivante présente les modèles de données utilisés dans l'application web MyRSS. Ces modèles sont essentiels pour comprendre la structure de la base de données et les relations entre les différentes entités.

### Modèle Conceptuel des Données (MCD)
Le MCD décrit de manière abstraite les entités et leurs relations, sans se soucier de la manière dont elles seront implémentées dans la base de données. Il s'agit d'une représentation graphique des concepts clés et de leurs interactions dans le système. Le MCD de MyRSS a été réalisé à l'aide du logiciel Looping.

![MCD](./docs/img/mcd.jpg)

### Modèle Logique des Données (MLD)
Le MLD (Modèle Logique des Données) détaille de manière précise et spécifique la structure de la base de données, en traduisant les concepts abstraits du MCD (Modèle Conceptuel des Données) en termes concrets de tables, de colonnes et de contraintes. Il s'agit d'une représentation technique qui définit comment les données seront stockées, organisées et interconnectées dans le système de gestion de base de données. Le MLD de MyRSS a été réalisé sur le site https://drawsql.app et est accesible en ligne à [cette adresse](https://drawsql.app/teams/gatiendev/diagrams/myrss).

![MLD](./docs/img/mld.png)

## Contribution

Toute contribution sur le projet est la bienvenue ! Vous trouverez tous les renseingments nécessaires dans le fichier [CONTRIBUTING.md](./CONTRIBUTING.md)

## Licence

**MyRSS** est distribué sous la [licence GPLv3](./LICENSE).


## Crédits
- https://github.com/Ileriayo/markdown-badges : fournisseur des badges présents dans le readme

- https://www.looping-mcd.fr/ - Logiciel de modélisation conceptuelle utilisé pour réaliser le mcd puis mld