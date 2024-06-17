# Guide d'installation pour Ubuntu

Guide d'installation testé avec **Ubuntu Server 24.04** et **PHP 8.5**

## Installation d'Apache, PHP et MySQL
Pour installer Apache, PHP et MySQL.

```bash
$ sudo apt update && sudo apt upgrade
$ sudo apt install php apache2 php-mysql php-xml mariadb-server
```

## Configuration et sécurisation de MariaDB
MariaDB fournit un script pour aider la configuration initiale. Executez ce script en tapant cette commande.

```bash
$ sudo mysql_secure_installation
```

- Le script devrait demander un mdp (celui par défaut est vide). Ensuite un message de ce style devrait s'afficher :
```
Switch to unix_socket authentication [Y/n]
```

Pour ce projet nous nous contenterons de l'authentification par mot de passe. On renseignera donc la touche `n`

- Le script devrait ensuite nous demander de changer le mot de passe root. 
```
Change the root password? [Y/n]
```
Appuyer sur `Y` et définisez un mot de passe sécurisé.

- Le script propose de supprimer l'accès sans compte.
```
Remove anonymous users? [Y/n]
```
Nous allons effectivement supprimer l'accès à la db sans compte en appuyant sur la touche `y`.

- Le script propose ensuite de supprimer l'accès root depuis une autre IP que localhost.
```
Disallow root login remotely? [Y/n]
```
Nous allons également activer cette option de sécurité en appuyant sur la touche `y`.

- Le script propose de supprimer la base de données `test`.
```
Remove test database and access to it? [Y/n]
```
Nous allons la supprimer en validant avec la touche `y`.

- Le script propose maintenant de recharger les bases de données.
```
Reload privilege tables now? [Y/n]
```
Nous allons accepter ce rechargement en appuyant sur `y`.

## Configuration du serveur Apache
Nous allons devoir apporter quelques modifications à Apache pour que les URLs ne portent pas l'extension `.php`.

On vient tout d'abord redemarréer apache2 et activer `mod_rewrite` à l'aide de cette commande :
```bash
$ sudo systemctl restart apache2 && sudo a2enmod rewrite
```

Et ensuite, on vient apporter quelques modifications dans le fichier de configuration Apache.
```bash
$ sudo vi /etc/apache2/sites-available/000-default.conf
```
On va venir ajouter la balise `<Directory>` dans la balise `<VirtualHost>` :
```
<VirtualHost *:80>
    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    . . .
</VirtualHost>
```

Une fois ces modifications éffectuées, on peut relancer `apache2` à l'aide de cette commande :
```bash
$ sudo systemctl restart apache2
```

## Installation du code source sur Apache
On va donc maintenant installer la codebase dans le repertoire Apache.
Pour cela on va tout d'abord cloner le repo :
```bash
cd /var/www/ && sudo rm -r html/* && sudo git clone https://github.com/GatienFrenchDev/MyRSS && sudo mv MyRSS html && sudo systemctl restart apache2
```

On vient ensuite configurer le fichier `.env` avec les informations nécessaires.
```bash
$ vi .env
```

## Importation de la base de données template dans MariaDB
Avant d'importer le fichier .sql template, il nous faut d'abord déjà créer la base de données vide intitulé `myrss`.

Pour cela on ouvre le shell de MariaDB en executant la commande suivante.
```bash
$ sudo mariadb
```

Et on execute la commande SQL suivante : 
```sql
CREATE DATABASE myrss;
```
On ferme le shell MariaDB en appyant sur `Ctrl+D` et on vient ensuite importer le fichier .sql. Pour cela on utilise la commande suivante.
```bash
$ sudo mysql -u root -p myrss < docs/db_prod.sql
```
