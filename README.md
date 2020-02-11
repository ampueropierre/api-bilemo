# API Bilemo - P7
 
 Création d'une API pour l'entreprise BileMo qui propose un catalogue de téléphone mobile, dans le cadre de la formation
 OpenClassroom Développeur d'application - PHP/Symfony.
 
 ## Environnement de développement
 
 - Linux
 - Composer 1.6.3
 - PHP 7.2.24
 - Apache 2.4.29
 - MySQL 5.7,28
 - git 2.17.1
 
 ## Instalation
 
 Clonez le repository Github
 
 ```bash
 git clone https://github.com/ampueropierre/api-bilemo.git
 ```
 
 Installer les dépendances
 
 ```
 composer install
 ```
 
 Créer la BDD
 
 ```
 php bin/console doctrine:database:create
 ```
 
 Créer les tables
 
 ```
 php bin/console doctrine:schema:create
 ```
 
 Installer la Fixture (démo de données fictives)
 
 ```
 php bin/console doctrine:fixture:load
 ```
 
 URL de la documentation
 
 ```
 http://127.0.0.1:8000/api/doc
 ```
 
 Tester les requêtes avec un compte User
 > login: user@bilemo.com
 >
 > password: bilemo
 
 <br>
 Enjoy !
 
 
