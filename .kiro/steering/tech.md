# Tech Stack

## Backend
- **PHP 8.3** with **Laravel 13** (latest framework conventions apply)
- **Laravel Breeze** — authentication scaffolding
- **Laravel Pint** — code style fixer (PSR-12 based)
- **PHPUnit 12** — testing framework
- **SQLite** — default database (file: `database/database.sqlite`)

## Frontend
- **Blade** — server-side templating (no SPA framework)
- **Tailwind CSS v3** with `@tailwindcss/forms` plugin
- **Alpine.js v3** — lightweight reactivity (loaded globally via `app.js`)
- **Vite 8** with `laravel-vite-plugin` — asset bundling
- **SweetAlert2** — flash notifications (loaded via CDN in app layout)
- **Chart.js** — dashboard charts (loaded via CDN in app layout)
- **Inter** — primary font (Google Fonts)

## Key Conventions
- Flash notifications use the `swal_msg` session key with `title`, `text`, and `icon` fields — handled automatically by the app layout's inline script.
- Alpine.js state is initialized on `<body>` via `x-data` for global sidebar state (`sidebarOpen`, `sidebarCollapsed`).
- CSS utility classes are extended in `resources/css/app.css` under `@layer components` (e.g., `.btn-primary`, `.card`, `.form-input-custom`). Use these before writing inline Tailwind chains.

## Common Commands

```bash
# First-time setup
composer run setup

# Start all dev services (Laravel + Vite + Queue + Pail log viewer)
composer run dev

# Run tests
composer run test

# Build frontend assets for production
npm run build

# Run Vite dev server only
npm run dev

# Fix code style with Pint
./vendor/bin/pint

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Clear all caches
php artisan optimize:clear
```
