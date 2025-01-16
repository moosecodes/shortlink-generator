<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Laravel Shortlink App

A Laravel application for creating, managing, and tracking shortlinks.

### Features

-   Create shortlinks
-   Manage shortlinks (edit, delete)
-   Track clicks
-   Track unique clicks
-   View detailed analytics (device, browser, country, referrer, user agent)
-   Toggle activation status of shortlinks

### Requirements

-   PHP 8.0 or higher
-   Composer
-   Node.js and npm
-   MySQL or any other supported database

### Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/your-username/shortlink-app.git
    cd shortlink-app
    ```

2. Install dependencies:

    ```sh
    composer install
    npm install
    npm run buil
    bpm run dev
    ```

3. Copy the [.env.example](http://_vscodecontentref_/2) file to [.env](http://_vscodecontentref_/3) and configure your environment variables:

    ```sh
    cp .env.example .env
    ```

4. Generate the application key:

    ```sh
    php artisan key:generate
    ```

5. Run the database migrations:

    ```sh
    php artisan migrate
    ```

6. Seed the database (optional):

    ```sh
    php artisan db:seed
    ```

7. Build the frontend assets:

    ```sh
    npm run dev
    ```

8. Start the development server:

    ```sh
    php artisan serve
    ```

### Usage

1. Access the application in your browser at `http://localhost`.

2. Use the interface to create, manage, and track shortlinks.

### API Endpoints

-   `GET /api/shortlinks/show/all` - Fetch all shortlinks
-   `POST /api/shortlinks/create` - Create a new shortlink
-   `PATCH /api/shortlink/update/{shortlink_id}` - Update a shortlink
-   `DELETE /api/shortlinks/delete/{shortlink_id}` - Delete a shortlink
-   `PATCH /api/shortlinks/activate/{shortlink_id}` - Activate a shortlink
-   `PATCH /api/shortlinks/deactivate/{shortlink_id}` - Deactivate a shortlink
-   `POST /api/shortlinks/redirect/urls` - Fetch redirected URLs

### Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add new feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Create a new Pull Request.

### License

This project is licensed under the MIT License. See the LICENSE file for details.
