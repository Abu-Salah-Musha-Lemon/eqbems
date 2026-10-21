# EQBEMS MVP — Delivery Verification

**Delivery date:** 2026-10-20

## Checks performed in authoring environment

- Confirmed all non-empty PHP source files in `app/`, `bootstrap/`, `config/`, `database/`, `routes/`, and `tests/` pass `php -l` (91 files at final authoring pass).
- Executed `App\Services\GradeService` directly from CLI with decimal threshold checks (0, 49.99, 50, 60, 69.99, 70, 79.99, 80, 100); results matched the documented grade boundaries.
- Cross-checked the newly created Blade views for matching conditional/loop closing directives and route references.
- Checked the generated ZIP archive contents and integrity after packaging.

## Checks that could not be performed here

- `composer install`, `php artisan migrate --seed`, `php artisan test`, `php artisan route:list`, and MySQL execution: this environment has **no Composer**, **no `pdo_sqlite`**, and **no `mbstring`/PHP XML extensions**.
- `npm ci && npm run build`: frontend dependencies were not installed in the build sandbox.
- Browser workflow / UI interaction, database migrations on SQLite/MySQL, accessibility, security penetration tests and deployment validation.

## Required release acceptance checklist

1. Install prerequisites, copy `.env.example`, configure a database, and run the commands in `README.md`.
2. Run fresh migrations and seed a securely provisioned bootstrap Super Admin.
3. Execute `php artisan test` and fix any environment-specific or functional regressions.
4. Execute `npm ci && npm run build` and verify CSS/JS/Blade rendering.
5. Smoke-test every role, unauthorized routes, and cross-institution isolation using two institutions.
6. Exercise full flow: create institution → staff → subject/topic → question → moderator review → blueprint → exam issue → marking → publication → correction.
7. Test MySQL migrations/transactions, backup + recovery and secrets management before production.

This is a working-source **MVP implementation**, not a certified production deployment or implementation of all future-phase requirements in `docs/`.
