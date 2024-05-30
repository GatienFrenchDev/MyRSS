# Déploiement local sous Windows pour environnement de développement

- Téléchargez d'abord [XAMPP](https://www.apachefriends.org/fr/index.html) avec les modules `Apache` et `MySQL` (l'installation par défaut suffira).
- Une fois installé, lancez XAMPP en tant qu'administrateur (clic droit sur `xampp.exe` > `Exécuter en tant qu'administrateur`).
- Démarrez `Apache` et `MySQL` depuis le panneau de contrôle de XAMPP.
- Téléchargez le code source de ce dépôt (bouton vert en haut à droite sur le dépôt, intitulé `<> Code`, puis `Download ZIP`).
- Placez la totalité des fichiers contenu dans le dossier `MyRSS-Main` de l'archive dans le dossier `C:/xampp/htdocs`, en ayant préalablement vidé le contenu du dossier.
- Accédez à l'adresse `http://localhost/phpmyadmin`, créer une base de données intitulée `myrss` puis allez dans l'onglet `Importer` et importez le fichier nommé `/docs/db_dev.sql` présent dans le dépôt.
- Renseigner dans un fichier `.env` (cf `env.example`) les identifiants de la base de données MySQL ainsi que votre clé API YouTube (Pour vous procurer une clé d'API YouTube Data API v3
 vous pouvez vous rendre sur Google Cloud Platform en passant par [ce lien](https://console.cloud.google.com/apis/api/youtube.googleapis.com/credentials)). 
 
 Voici un exemple de fichier .env configuré
```
DB_HOST="127.0.0.1"
DB_NAME="myrss"
DB_USERNAME="user"
DB_PASSWORD="sUp3rP@ssw0rd!"

YTB_API_KEY="AZitayCXaqXkl3f9MOPH3UL7fQn-pfBi56xe6k"
```

- Le site web devrait maintenant fonctionner sans problème ! Un compte de test est déjà crée dans la base de données avec les identifiants suivants :
```
mail : john@example.com
pass : password
```
- Pour récupérer les nouveaux articles, il faut envoyer une requete GET à l'url `/scripts/fetch-all-fluxs`