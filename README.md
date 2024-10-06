
# A Simple CRUD API Application Built Using Laravel

## Requirements
Please make sure you have **PHP version 8+** and **Composer** installed on your system.

## Commands

Please go to the project root directory and run the following commands sequentially:

1. **`composer install`**
   - This command installs all the PHP dependencies required for the project, based on the `composer.json` file.

2. **`composer dumpautoload`**
   - This command regenerates the list of all classes that need to be included when the project runs, which helps optimize the loading of these classes.

3. **`php artisan config:clear`**
   - Clears the configuration cache, ensuring that any changes made to the `.env` file are properly reflected in the application.

4. **`php artisan cache:clear`**
   - Clears the application cache. This is important to ensure that the system doesn't hold onto any outdated cached data while running the project.

5. **`php artisan route:clear`**
   - Clears the route cache. This helps avoid issues with routing if any changes were made after caching routes.

6. **`php artisan jwt:secret (Optional) -> JWT Secret key is already included in the env but if it's not, please run this command`**
   - Generates a secret key for JWT (JSON Web Token) authentication. This key is required to sign the tokens and should be kept secure. The generated key will be added to the `.env` file automatically.

7. **`php artisan serve`**
   - Starts the local development server for Laravel. The project will be accessible via the given host and port (usually `http://127.0.0.1:8000`).

The project should be running now.

## Running Tests

To run tests, use the following command:
```bash
php artisan test
```
This will run all the automated tests for the application to ensure that the functionality is working as expected.

## API Endpoints

The API endpoints are stored in Postman JSON files and are included in the repository. Please navigate to the `postman_collection_environment` folder located in the project root directory.

1. **Postman Collection**: Contains the API requests.
2. **Postman Environment**: Contains variables like the `baseUrl` and `token` for easier testing.

- **Base URL**: Update the Postman environment variable (`baseUrl`) according to the URL where your Laravel app is running (usually `http://127.0.0.1:8000`).
- **Token**: After logging in via the API, copy the new token and update the `token` variable in the Postman environment to authenticate further requests.

## Important Note:
The `.env` file is already included in the repo for easier project setup, and the project is also connected to a remote MySQL database, so **no need to run migration commands**.