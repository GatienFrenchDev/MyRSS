# Déploiment de MyRSS à l'aide de Docker
Cette option de déploiment est recommandé, aussi bien pour l'environnement de dev que de production.

## Installation de `docker` et de `docker compose`
MyRSS tourne sur une version récente de docker (version utilisé en prod et dev : `Docker version 27.0.3, build 7d4bcd8`).

Pour installer une version récente de docker veuillez suivre le guide officiel de docker : https://docs.docker.com/engine/install/

## Clonage du repo
Cloner le repository à l'aide de la commande suivante :
```bash
git clone https://github.com/gatienfrenchdev/myrss && cd myrss
```

## Configuration du fichier .env

Editer le fichier `.env` pour répondre à votre configuration (la valeur `DB_HOST` doit rester à `db_myrss`, le nom du container mysql).

Vous pouvez copier le fichier example docker et l'éditer à l'aide de la commande suivante :
```bash
cp env.example.dev .env && vi .env
```

## Démarrage de docker compose
Une fois cela fait, lancer le fichier docker compose correspondant à votre environnement (dev ou prod):
```
docker compose -f compose-prod.yml up
```
> Le fichier docker compose de production est configuré pour être utilisé avec traefik.

**MyRSS devrait desormais être accesible à l'adresse `http://localhost:80` !**