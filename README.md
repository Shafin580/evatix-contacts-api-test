
# Evatix-Contact-API

A simple CRUD API application build using laravel.

## Requirements

* Please make sure you have php version 8+ and composer installed in your system

## Commands

* Please go to project root directory and run the following commands sequentially:

`composer install`

`composer dumpautoload`

`php artisan config:clear`

`php artisan cache:clear`

`php artisan route:clear`

`php artisan view:clear`

`php artisan serve`

The project should be running now.

* The endpoints are stored in postman json files and included in the repo. Please go to postman_collection_environment folder which is located in the project root directory.

* Please change the postman environment variable (baseUrl) according to your laravel app running on local host and port

### Note: The .env file is already included in the repo for easier project setup and the project is also connected to remote mysql database so no need to run migration commands.