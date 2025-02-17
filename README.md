# Laravel Project Setup

This guide will help you set up and run the Laravel project on your local machine.

## Prerequisites

-   PHP
-   Composer
-   MySQL

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/AshirButt925/DigitalTolksTest.git
    cd DigitalTolksTest
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Copy the `.env.example` to `.env` and configure your environment variables:**

    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

5. **Set up your database:**

    Ensure your `.env` file has the correct database credentials.

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Seed the database with a default user:**

    ```bash
    php artisan db:seed --class=DefaultUserSeeder
    ```

    - Default user credentials:
        - Email: `ashir@yopmail.com`
        - Password: `password`

## Running the Project

1. **Start the development server:**

    ```bash
    php artisan serve
    ```

    The application will be available at [http://localhost:8000](http://localhost:8000).

2. **Access the Swagger documentation:**

    Visit [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation) to view the API documentation.

## Using the API

1. **Login to obtain an API authentication token:**

    Use the login API with the default user credentials to get the authentication token.

2. **Access secured translation APIs:**

    Use the session-based authentication token to access the secured translation APIs.

3. **Export translations:**

    Use the `translate/export` API to export translations for the frontend application. The first export may take some time, but subsequent exports will be faster due to caching.

## Testing

1. **Run tests:**

    Use the following command to run tests using Pest:

    ```bash
    php artisan test
    ```

## Notes

-   Ensure your `.env` file is correctly configured for your local environment.
-   The application uses caching to optimize translation exports.
