# Groupe 7

# Membres du groupes :  
- Courbin Michel
- Guillot Antony
- Micheli Sébastien
- Pey Arthur

# Prérequis pour le fonctionnement :
- Ouvrir la base de donnée fournie : http://localhost:50080/pgadmin4/ et générer la base de données "postgres".
- Créer une nouvelle base de données "accounts" dans pgAdmin : copier/coller la partie "Cree la BDD" dans le fichier "BDD account tuto" pour configurer la nouvelle base de données.
- On utilise la VM Debian fournie sur e-campus comme serveur.
- Dans cette VM on crée un dossier partagé entre : le dossier contenant les fichiers du site, et le dossier /var/www/html dans la VM.

# Fonctionnement du site :
## Lancement :
- Ouvrir le lien : http://localhost:50080/ puis lancer route.php.
- Pour lancer directement route.php depuis ce lien, on peut modifier le fichier /etc/apache2/site-available/000-default.conf en ajoutant la ligne : DirectoryIndex route.php

## Le site :
- La page d'accueil permet de rediriger vers les autres pages.
- Il est possible de se créer un compte et de se connecter en haut à droite de la page. 
- Les informations des comptes sont stockées dans une base de données.
- Il existe un compte administrateur : Identifiant = root, Mot de passe = root. Depuis un compte administrateur, il est possible d'accéder à la page panel admin. Cette page permet de gérer les autres comptes.
- La page pathologie permet de faire des recherches parmi les différentes pathologies présentent dans la base de données selon leur "Type", "Pathologie" et "Méridien".
- Lorsque l'utilisateur s'est connecté, on peut faire une recherche par mot-clef dans la page pathologie. Cette recherche prend en compte les critères précédents ainsi que la description.

# API :
- Le fichier API.php est indépendant du site. Il permet de faire une recherche dans la base de données à l'aide des méthodes $_GET ou $_POST avec Postman. Voir les commentaires du fichier pour son utilisation.
## Description du fonctionnement :
- Permet de faire une recherche dans la base de données à l'aide de Postman. 
- URL à placer dans Postman : http://localhost:50080/API.php
- La recherche est faite à l'aide des méthodes $_GET ou $_POST. (ligne 37 : changer $methode par "GET" ou "POST" pour choisir la méthode, GET par défaut)
- Méthode $_GET : utiliser une URL de ce type : http://localhost:50080/API.php?type=tfc&patho=zang&mer=Foie&rech=bouche , où l'on peut modifier les paramètres recherchés entre les "=" et "&"
- Méthode $_POST : ajouter les key "type", "patho", "mer", et "recherche" dans la partie Body.


# Bonus :
- Arthur a hébergé le site en ligne : http://acupuncturecpe.000webhostapp.com/route.php?page=index
-> Utilisateur admin : username : "AdminCPE", mdp : "AdminCPE"

# Ajout après évaluation :
- Rien n'a été ajouté.
