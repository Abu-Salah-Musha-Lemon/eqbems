# EQBEMS — Enterprise Question Bank & Examination Management System

EQBEMS is a Laravel web application being built to manage institutions, academic structures, question banks, examination preparation, and results. The repository is currently in **foundation / institution-module development**. It is not yet a complete examination platform.

## Current status at a glance

| Area | Repository status | Important qualification |
|---|---|---|
| Laravel foundation | Present | Laravel 12; PHP constraint in Composer is `^8.2`; project standard is PHP 8.3+ |
| Authentication | Scaffolded | Laravel Breeze routes and tests exist; verify all flows in the local environment |
| Roles and permissions | Package/schema/seeder scaffolding present | User model integration and permission naming are incomplete; do not consider RBAC complete |
| Dashboard | Partial | Two dashboard routes/views exist with different paths; route/view alignment needs cleanup |
| Institutions | In progress | Migration/model/request/service/controller files exist, but service injection/pagination and view work remain incomplete |
| Academic structure | Planned | No domain migrations yet |
| Question bank | Planned/scaffolding | No domain schema or working feature flow yet |
| Blueprint, exams, results | Planned | Treat target docs as design, not deployed functionality |
| Automated tests | Partial | Existing tests are mainly Laravel/Breeze examples; domain acceptance tests are missing |

See [the documentation index](docs/README.md) and [known blockers](docs/00_START_HERE.md).

## Requirements

- PHP version accepted by `composer.json` (`^8.2`); use PHP 8.3+ as the project standard.
- Composer 2.x.
- Node.js/npm compatible with the locked Vite toolchain.
- SQLite for quick local bootstrapping, or MySQL 8.x for production-like development.
- PHP extensions required by Laravel and the installed Composer packages, including PDO for the selected database and file-info/mbstring/XML support as required by the dependency set.

Check `composer.json`, `package.json`, and your installed runtime for exact version constraints. Do not assume a globally installed toolchain is compatible without checking it.

## Quick local setup (SQLite)

1. Clone or extract the repository and enter its root directory.
2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create the environment file and application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`.

4. Ensure the SQLite database file exists. From the project root:

   ```bash
   touch database/database.sqlite
   ```

   In PowerShell: `New-Item -ItemType File -Force database/database.sqlite`.

5. Confirm `.env` uses `DB_CONNECTION=sqlite` and `DB_DATABASE` is unset or points to `database/database.sqlite`.
6. Run migrations:

   ```bash
   php artisan config:clear
   php artisan migrate
   ```

7. Install frontend dependencies and compile assets:

   ```bash
   npm install
   npm run build
   ```

8. If public file uploads are used, create the public storage link:

   ```bash
   php artisan storage:link
   ```

9. Start the app:

   ```bash
   php artisan serve
   ```

   Open the URL printed by Artisan. For frontend hot reload, use a second terminal and run `npm run dev`.

**Important:** the repository currently has known code blockers. Migrations may run, but do not assume seeding, RBAC-protected routes, or institution CRUD works end-to-end until the blockers in `docs/00_START_HERE.md` are fixed and covered by tests. In particular, `php artisan db:seed` calls `assignRole()` while `App\Models\User` currently lacks Spatie's `HasRoles` trait.

## MySQL setup

Create an empty database and a least-privilege database user, then set the following in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eqbems
DB_USERNAME=eqbems_app
DB_PASSWORD=replace-with-a-local-secret
```

Run `php artisan config:clear` and `php artisan migrate`. Use MySQL 8.x/InnoDB for production-like verification. SQLite tests do not prove every MySQL-specific constraint, collation, JSON behavior, or query plan.

## Useful commands

```bash
php artisan route:list
php artisan migrate:status
php artisan test
./vendor/bin/pint --test
npm run build
```

`composer run dev` starts the configured concurrent development processes (web server, queue listener, log tail, and Vite). Use it only after the environment and dependencies are configured.

## Documentation entry points

- [Start here / project readiness](docs/00_START_HERE.md)
- [Requirements and scope](docs/01_FOUNDATION/PRODUCT_REQUIREMENTS.md)
- [Architecture](docs/02_ARCHITECTURE/SYSTEM_ARCHITECTURE.md)
- [Database design](docs/03_DATABASE/DATABASE_DESIGN.md)
- [Local setup](docs/04_DEVELOPMENT/LOCAL_SETUP.md)
- [Security](SECURITY.md)
- [Roadmap](docs/01_FOUNDATION/ROADMAP.md)

## Source-of-truth rules

- Migrations are the source of truth for the schema currently represented in source control.
- Current source code is the source of truth for implementation status; the manifest and feature catalog must not mark untested or incomplete functionality as complete.
- `docs/03_DATABASE/DATABASE_DESIGN.md` includes both implemented tables and proposed target tables. Proposed tables are not deployed until migrations exist and have been applied.
- Environment-specific secrets belong in `.env` and must never be committed or included in distributable archives.

## License

See [LICENSE](LICENSE). Review third-party asset and dependency licenses before distribution.
