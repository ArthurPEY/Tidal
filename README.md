# Membres du groupes :  
- Courbin Michel
- Guillot Antony
- Micheli Sébastien
- Pey Arthur

# Prérequis pour le fonctionnement :
- Créer la base de données "account" dans pgAdmin : copier/coller la partie "Cree la BDD" dans le fichier "BDD account tuto" pour configurer la nouvelle base de données.

# Fonctionnement du site :
- Lancer le fichier route.php pour accéder à la page d'accueil du site.
- La page d'accueil permet de rediriger vers les autres pages.
- Il est possible de se créer un compte et de se connecter en haut à droite de la page. 
- Les informations des comptes sont stockés dans une base de données.
- Il existe un compte administrateur : Identifiant = root, Mot de passe = root. Depuis un compte administrateur, il est possible d'accéder à la page panel admin. Cette page permet de gérer les autres comptes.
- La page pathologie permet de faire des recherches parmi les différentes pathologies présentent dans la base de données selon leur "Type", "Pathologie" et "Méridien".
- Lorsque l'utilisateur s'est connecté, on peut faire une recherche par mot-clef dans la page pathologie. Cette recherche prend en compte les critères précédents ainsi que la description.
- Le fichier API.php est indépendant du site. Il permet de faire une recherche dans la base de données à l'aide des méthodes $_GET ou $_POST avec Postman. Voir les commentaires du fichier pour son utilisation.
