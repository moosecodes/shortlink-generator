<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Shortlink App

## Overview

The Shortlink App is a web application that allows users to create, manage, and track shortlinks. It provides features such as creating new shortlinks, updating existing ones, tracking clicks, and viewing analytics. The app is built using Laravel for the backend and Vue.js with Vuetify for the frontend.

## Features

-   Create new shortlinks
-   Update existing shortlinks
-   Track total and unique clicks
-   View analytics for each shortlink
-   User authentication and authorization
-   Dark mode support

## Installation

### Prerequisites

-   PHP >= 8.0
-   Composer
-   Node.js >= 14.x
-   npm or yarn
-   MySQL or any other supported database

### Backend Setup

1. Clone the repository:

    ```sh
    git clone https://github.com/yourusername/shortlink-app.git
    cd shortlink-app
    ```

2. Install PHP dependencies:

    ```sh
    composer install
    ```

3. Copy the [.env.example](http://_vscodecontentref_/3) file to [.env](http://_vscodecontentref_/4) and update the environment variables:

    ```sh
    cp .env.example .env
    ```

4. Generate the application key:

    ```sh
    php artisan key:generate
    ```

5. Set up the database and run migrations:

    ```sh
    php artisan migrate
    ```

6. Seed the database (optional):

    ```sh
    php artisan db:seed
    ```

### Frontend Setup

1. Install Node.js dependencies:

    ```sh
    npm install
    ```

2. Build the frontend assets:

    ```sh
    npm run dev
    ```

## Usage

1. Start the Laravel development server:

    ```sh
    php artisan serve
    ```

2. Open your browser and navigate to `http://localhost:8000`.

3. Register a new user or log in with existing credentials.

4. Use the dashboard to create, update, and manage shortlinks.

## API Endpoints

-   `POST /api/shortlinks` - Create a new shortlink
-   `PATCH /api/shortlinks/{id}` - Update an existing shortlink
-   `GET /api/shortlinks/all` - Get all shortlinks
-   `GET /api/shortlinks/active` - Get all active shortlinks
-   `GET /api/shortlinks/{id}` - Get a specific shortlink
-   `DELETE /api/shortlinks/{id}` - Delete a shortlink
-   `GET /shortlinks/redirect/{short_code}` - Redirect to the original URL

## Contributing

Contributions are welcome! Please read the contributing guidelines for more information.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Acknowledgements

-   [Laravel](https://laravel.com/)
-   [Vue.js](https://vuejs.org/)
-   [Vuetify](https://vuetifyjs.com/)
-   [Inertia.js](https://inertiajs.com/)
-   [Jetstream](https://jetstream.laravel.com/)
