<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Prerequisites

- PHP >= 8.0
- MySQL >= 5.7
- Composer

# Steps

1 - Clone the Repository

- git clone <https://github.com/haariskh55/Test.git>

2 - Navigate to the Project Directory:

- cd test

3 - Switch main branch

- Git checkout main

4 - Install Dependencies

- composer install (delete composer lock if get error of version in lock file).

5 - Set Up Environment File:

- Copy .env.example to .env
- Configure your database and other settings in .env
- To Configure your database you have to create a database in MySql (e.g : test)
- DB_DATABASE=test
- DB_USERNAME=root
- DB_PASSWORD=

6 - Generate Application Key:

- php artisan key:generate (CLI)

7 - Run Migrations:

- php artisan migrate (CLI)

8 - Serve the Application:

- php artisan serve (CLI)

The application will be served at <http://localhost:8000>

# After Environment change

- php artisan config:clear
- php artisan config:cache
- php artisan optimize:clear
