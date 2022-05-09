<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table des matières</summary>
  <ol>
    <li><a href="#projet-e-commerce">Projet E-Commerce</a></li>
    <li><a href="#stack-technique">Stack technique</a></li>
    <li><a href="#pré-requis">Pré requis</a></li>
    <li><a href="#installation">Installation</a></li>
    <li><a href="#utilisation">Utilisation</a></li>
    <li><a href="#collaborateurs">Collaborateurs</a></li>
  </ol>
</details>


## Projet E-Commerce

Ce projet est un projet de site e-commerce vendant des chaussures.

### Stack technique

* [Symfony](https://symfony.com/)
* [Bootstrap](https://getbootstrap.com)
* [JQuery](https://jquery.com)

### Pré-requis

Voici les pré-requis pour pouvoir faire fonctionner le projet.
* php
* composer
* yarn
* PostgreSQL

### Installation

Pour pouvoir utiliser le site voici les étapes à suivre :

1. Cloner le repo
   ```sh
   git clone https://github.com/kameronramah/E-commerce.git
   ```
2. Créer un fichier .env à la racine du projet et le remplir sur l'exemple du .env.sample

3. Installer les dépendances
   ```sh
   composer install
   ```
   ```sh
   yarn install
   ```
4. Générer les assets
   ```sh
   yarn build
   ```
5. Créer la base de données
   ```sh
   php bin/console doctrine:database:create
   ```
6. Mettre à jour le schéma
   ```sh
   php bin/console doctrine:schema:update --force
   ```
7. Générer les fixtures
   ```sh
   php bin/console doctrine:fixtures:load
   ```
8. Démarrer le projet
   ```sh
   symfony server:start
   ```

## Utilisation

Vous pouvez désormais utiliser le site.
Par défaut un compte admin est créé pour avoir accès à l'interface d'administration voici ses identifiants :
* Email : admin@admin.com
* Mot de passe : adminPass


## Collaborateurs

* [Kameron Ramaherison](https://github.com/kameronramah)
* [Arthy Uthayasuhanthan](https://github.com/arthy-uthayasuhanthan)

