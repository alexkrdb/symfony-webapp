# symfony-webapp
My first webApp, completely rebuilt using the Symfony framework. Only available translation is Polish.
The main goal of this project is to create a CRUD application using PHP, with the optional use of frameworks. 
However, please note that this project does not involve extensive complexity or advanced functionalities.

To use this app you must:   
  1. Execute composer install(if you don't have composer installed https://getcomposer.org/download/)
  2. Migrate database using command php bin/console doctrine:migrations:migrate
  3. There are no seeders/fixtures, so you should insert some example data into the "courses" table 

