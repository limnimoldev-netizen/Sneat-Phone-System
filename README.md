# Sneat Phone System

A Laravel application for phone inventory, sales, customers, expenses, installment loans, and reports. The interface supports English and Khmer.

## Local setup

Install PHP 8.1 or later in the PHP 8 series, Composer 2, Node.js 22, npm, and MySQL. Enable PHP's PDO MySQL driver for local development and PDO SQLite for tests. Run `composer check-platform-reqs` to check the other PHP extensions required by the installed dependencies.

```sh
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Create an empty MySQL database and set the `DB_*` values in `.env`. Set `SEED_ADMIN_PASSWORD` to a unique password of at least 12 characters. Then run:

```sh
php artisan migrate --seed
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000` and sign in as `administrator` with the password you set. Remove `SEED_ADMIN_PASSWORD` from `.env` after seeding. Run the full seed only on a fresh database. For an existing installation, back up the database and run `php artisan migrate`.

## Checks

```sh
php artisan test
php artisan route:list
php artisan view:cache
npm run build
```

The test suite uses an in-memory SQLite database. Build the frontend assets before running the browser-facing feature tests. Use `npm run dev` while editing frontend assets locally.
