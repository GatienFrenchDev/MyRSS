# Déploiment à l'aide de Docker

## Installation de `docker` et de `docker compose`
Pour installer `docker` et `docker compose`, je recommande d'installer [Docker Dekstop](https://www.docker.com/products/docker-desktop/) qui permet directement d'avoir tous les outils d'installés.

Si cela n'est pas possible, je vous encourage à suivre ces deux guides disponibles sur le site de docker :

- https://docs.docker.com/engine/install/
- https://docs.docker.com/compose/install/

_MyRSS utilise la v2 de docker compose, il ne fonctionnera donc pas avec la commande `docker-compose` mais bien avec la commande plus récente `docker compose`_

## Clonage du repo
Cloner le repository à l'aide de la commande suivante :
```bash
$ git clone https://github.com/gatienfrenchdev/myrss && cd myrss
```

## Configuration du fichier .env

Editer le fichier `.env` pour définier les identifiants et username de la base de données (la valeur `DB_HOST` doit rester à `host.docker.internal`).

Vous pouvez copier le fichier example docker à l'aide de la commande suivante :
```bash
$ cp env.example.docker .env && vi .env
```

## Démarrage de docker compose
Une fois cela fait, lancer docker compose à l'aide de la commande suivante :
```
$ docker compose up
```
> Pour lancer l'application en fond vous pouvez utiliser l'option `-d`

**MyRSS devrait desormais être accesible à l'adresse `http://localhost` !**

Le portail PhPMyAdmin devrait être accesible à l'adresse `http://localhost:8001`