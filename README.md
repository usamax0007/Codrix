# Codrix

Company website built with Laravel.

## Requirements

- PHP >= 8.4
- Composer
- Node.js & npm (for frontend assets)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000).

## Pages

- Home
- About
- Services
- Portfolio
- Team
- Why Choose Us
- Testimonials
- Contact
