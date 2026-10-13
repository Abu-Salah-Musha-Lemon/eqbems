# Local Development Setup

## Prerequisites

- PHP compatible with `composer.json` (`^8.2`; preferred project standard 8.3+).
- Composer 2.x.
- Node/npm compatible with package-lock and Vite 7.
- PHP PDO driver for SQLite or MySQL, plus required Laravel extensions.
- Optional MySQL 8.x for production-like testing.

Check your environment:

```bash
php -v
composer --version
node --version
npm --version
php -m
```

## Install using SQLite

From the repository root:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

For PowerShell, replace the copy/touch commands with:

```powershell
Copy-Item .env.example .env
New-Item -ItemType File -Force database/database.sqlite
```

Confirm `.env` uses `DB_CONNECTION=sqlite`; then run:

```bash
php artisan config:clear
php artisan migrate
npm install
npm run build
php artisan storage:link
php artisan serve
```

Optional hot reload: open another terminal and run `npm run dev`.

## MySQL development

Create a database and a restricted user. Configure `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eqbems
DB_USERNAME=eqbems_app
DB_PASSWORD=local-secret-here
```

Then:

```bash
php artisan config:clear
php artisan migrate
```

The target production database is MySQL 8.x. Use an isolated local database; never point a developer machine at production to test migrations.

## Seed data warning

The current default seeder path is expected to fail because `AdminUserSeeder` calls `assignRole()` but the `User` model has not included Spatie's `HasRoles` trait. Fix and test the RBAC integration before running `php artisan db:seed`. The seeded admin password is predictable and must never be used outside disposable local development.

## First-run checks

```bash
php artisan route:list
php artisan migrate:status
php artisan test
npm run build
```

Keep the full output and note any baseline failure. The current institution controller has known dependency/pagination issues; route registration or a successful asset build is not proof that all pages work.

## Resetting local data

Only on a disposable local database:

```bash
php artisan migrate:fresh
```

This destroys all tables/data on the selected database. Do not run it on shared, staging, or production data.

## Troubleshooting

- **`could not find driver`:** enable/install the PDO driver for the selected DB.
- **Database file not found:** ensure `database/database.sqlite` exists and is writable.
- **Config changes ignored:** run `php artisan config:clear`.
- **Permission table errors:** check `config/permission.php`, the permission migration, and cache via `php artisan permission:cache-reset` if the command is available.
- **Vite manifest missing:** run `npm install` and `npm run build`.
- **Upload URLs fail:** run `php artisan storage:link` and verify filesystem config/permissions.
