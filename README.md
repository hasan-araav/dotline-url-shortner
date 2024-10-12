# Dotline URL Shortener

## Prerequisites

This project uses the following external services:
- Google Safe Browsing API for validating malicious URLs
- Maxmind GeoIP Database for geolocation

## Setup

1. Update your `.env` file with the following credentials:
2. Copy the .env.exmpale and then just update the following keys:

   ```
   GOOGLE_SAFE_BROWSING_API_KEY=
   MAXMIND_USER_ID=
   MAXMIND_LICENSE_KEY=
   ```

2. Configure your database credentials in the `.env` file.

3. Install dependencies:
   ```
   composer install && npm install
   ```

4. Build frontend assets:
   ```
   npm run dev
   ```

5. Run database migrations:
   ```
   php artisan migrate
   ```

## Running the Application

You can run the application using one of the following methods:

- PHP's built-in server:
  ```
  php artisan serve
  ```
- Laravel Valet:
  ```
  valet link
  valet secure
  ```

## Monitoring and Queue Management

This project uses Laravel Horizon for job monitoring:

1. Start the Horizon dashboard:
   ```
   php artisan horizon
   ```

2. Start the queue worker:
   ```
   php artisan queue:work
   ```

3. Start the schedule worker:
   ```
   php artisan schedule:work
   ```

## Enjoy!

Your URL shortener service is now set up and ready to use. Happy shortening!