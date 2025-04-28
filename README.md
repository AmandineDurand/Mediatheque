# Projet Médiathèque

Ce projet réalisé avec Symfony est un site fonctionnel pour la médiathèque de Montpellier. L’objectif de ce dernier est d’offrir une interface intuitive permettant la gestion des documents, des adhésions, des abonnements, des prêts et des contentieux tout en respectant les différents droits des utilisateurs. 

## Prés-requis

- [Docker](https://www.docker.com/)
- [Docker Composer](https://docs.docker.com/compose/)

## Usage

Voici les étapes à suivre pour accéder au site 

1. Cloner ce répertoire GitHub

Pour cela, ouvrez un terminal et placez vous dans le dossier où vous souhaitez avoir le projet via la commande suivante :
```bash
cd chemin/vers/dossier
```

Ensuite, exécutez la commande suivante
```bash
git clone https://github.com/AmandineDurand/Mediatheque.git
```

Vous avez désormais tout le code sur votre ordinateur.

2. Construire les conteneurs

Exécutez la commande suivante :
```bash
docker-compose build
```

Dans le logiciel Docker Desktop, vous devez avoir le conteneur "médiatheque" avec les éléments 'apache', 'phpmyadmin' et 'database'

3. Démarrer les conteneurs

Pour finir, exécutez la commande suivante
```bash
docker-compose up -d
```

Votre conteneur devrait maintenant être lancé et vous devez voir une pastille verte devant chaque élément.

4. Accéder aux URLs

Vous avez donc accès :
- à la base de données PHPMyAdmin via l'URL `http://localhost:8080/`
- au site vie l'URL `http://localhost:8000`

## A noter

Si la page PHPMyAdmin affiche une erreur lors de la connexion, faites réessayer ou rechargez la page (plusieurs fois peuvent être nécessaires)
La navigation dans le site peut prendre un peu de temps, soyez patient 😉