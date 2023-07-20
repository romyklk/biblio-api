# biblio-api

## Description

Dans ce projet nous allons créer une API REST pour une bibliothèque municipale. Cette API permettra de gérer des livres, des auteurs, des éditeurs, des genres et des nationalités.

## INSTALLATION

1. Créer une application microservice Symfony nommée `biblio-api`
    * `symfony new biblio-api`

2. Installer le pack ORM qui va nous permettre de créer des entités 
    * `composer require symfony/orm-pack`

3. Faire la configuration de la base de données `api-bibliotek` dans le fichier `.env`
    * `DATABASE_URL=mysql://user:password@host:port/db_name`

4. Créer la base de données
    *`symfony console doctrine:database:create`

5. Créer les entités suivantes : avec la commande 
`composer require symfony/maker-bundle --dev`
`symfony console make:entity`

   -  `nationality` avec les champs suivants:
       * `id`
       * `libelle` (string)
  
   - `author` avec les champs suivants :
      * `id`
      * `firstname` (string)
      * `lastname` (string)
      * `createdAt` (datetimeimmutable)
      * `nationality` (relatoion) ManyToOne

    - `genre` avec les champs suivants :
      * `id`
      * `libelle` (string)
  
    - `editor` avec les champs suivants :
        * `id`
        * `name` (string)

    - `book` avec les champs suivants :
        * `id`
        * `title` (string)
        * `isbn` (string)
        * `price` (float)
        * `resume` (text)
        * `publicationDate` (datetime)
        * `language` (string)
        * `author` (relation) ManyToOne
        * `editor` (relation) ManyToOne
        * `genre` (relation) ManyToOne
        * `createdAt` (datetimeimmutable)

6. Créer les migrations
   `symfony console make:migration`
   `symfony console doctrine:migrations:migrate`

7. Créer les fixtures
   `composer require orm-fixtures --dev`
    `symfony console make:fixtures` nommé `BiblioFixtures`

8. Ajouter la librairie `fakerphp/faker` pour générer des données aléatoires
    `composer require fakerphp/faker`
9. Créer les données aléatoires dans le fichier `BiblioFixtures.php`
    - Créer les fixtures dans l'ordre suivant :
        * `Nationality`
        * `Genre`
        * `Editor`
        * `Author`
        * `Book`
10. Charger les données dans la base de données
    `symfony console doctrine:fixtures:load`

11. Installer `easyadmin` pour avoir une interface d'administration
    `composer require easycorp/easyadmin-bundle`

12. Créer le dashboard avec la commande 
    `symfony console make:admin:dashboard`

13. Créer les CRUD avec la commande
    `symfony console make:admin:crud`

14. Configurer les CRUD dans le fichier `src/Controller/Admin/DashboardController.php`
    - `Nationality`
    - `Genre`
    - `Editor`
    - `Author`
    - `Book`

## API

1. Créer un controller `GenreController` avec la commande 
    `symfony console make:controller` nommé `GenreController`

