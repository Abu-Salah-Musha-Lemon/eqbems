# Start Here: EQBEMS Project Readiness

**Document status:** Current working guide, reviewed 2026-10-13. This is not a claim that the application is production-ready.

## What this repository is

EQBEMS is a Laravel 12 starter application for an intended institution-aware question-bank and examination-management product. The repository contains Laravel/Breeze scaffolding, baseline application tables, Spatie permission-table scaffolding, an institution migration/model/service/controller attempt, and a substantial set of target architecture documents.

## What it is not yet

It is not yet a completed question bank, exam-generation, examination-delivery, grading, or results platform. Those are target requirements. Do not build a release plan from the target database alone; confirm a feature exists in code and tests.

## Read in this order

1. [README](../README.md) — setup overview and repository status.
2. [Product Requirements](01_FOUNDATION/PRODUCT_REQUIREMENTS.md) — what the product must do.
3. [Scope and Roadmap](01_FOUNDATION/PROJECT_SCOPE.md) and [Roadmap](01_FOUNDATION/ROADMAP.md) — release boundaries and order.
4. [Initial Implementation Backlog](01_FOUNDATION/IMPLEMENTATION_BACKLOG.md) — first tasks and acceptance criteria.
5. [System Architecture](02_ARCHITECTURE/SYSTEM_ARCHITECTURE.md) — how code should be organized.
6. [Database Design](03_DATABASE/DATABASE_DESIGN.md), [Dictionary](03_DATABASE/DATABASE_DICTIONARY.md), and [ERD](03_DATABASE/ERD.md) — current vs target schema.
7. [Local Setup](04_DEVELOPMENT/LOCAL_SETUP.md) and [Testing](04_DEVELOPMENT/TESTING.md).
8. [Security](../SECURITY.md) and [Security Architecture](02_ARCHITECTURE/SECURITY_ARCHITECTURE.md).

## Confirmed blockers before end-to-end feature work

These observations come from the checked-in source, not assumptions about how the deployed database behaves:

1. **Spatie role integration:** `app/Models/User.php` does not currently use `Spatie\Permission\Traits\HasRoles`, but `database/seeders/AdminUserSeeder.php` calls `$user->assignRole('Super Admin')`. The default `db:seed` path is therefore expected to fail until this is corrected and tested.
2. **Permission mismatch:** `PermissionSeeder` inserts `institution.edit`, while the institution policy/Form Request expects `institution.update`. Choose one canonical permission and update the seeder, policy, request, route/UI checks, and tests together.
3. **Institution service wiring:** the constructor injection in `InstitutionController` is commented out while methods access `$this->institutionService`. Restore dependency injection.
4. **Pagination API:** `InstitutionController@index()` calls `InstitutionService::paginate()`, but the current service does not define it. Implement the method or call the intended query implementation.
5. **Views/routes:** institution create/edit views are empty in the inspected tree; `show` references a view whose presence should be verified; `/dashboard` returns `view('dashboard')` while the custom controller returns `dashboard.index` on `/dashboard2`. Consolidate route/view paths.
6. **Duplicate request classes:** `app/Http/Requests/StoreInstitutionRequest.php` and `UpdateInstitutionRequest.php` coexist with `app/Http/Requests/Institution/...`; the controller uses the nested namespace. Remove duplicates or establish a clear convention.
7. **Authorization exposure:** institution routes have `auth` middleware but no consistent permission middleware. Policies and Form Requests must be complete and tested; all controller actions must authorize, including `show`.
8. **Seed account safety:** the admin seeder sets a predictable password. This is only suitable for disposable local development, not shared or production environments.
9. **Test coverage:** existing tests are mainly Laravel/Breeze scaffold tests. There are no adequate institution/RBAC domain acceptance tests yet.
10. **Frontend standard:** Bootstrap 5 is an installed dependency and selected design direction, but Breeze/Tailwind and legacy theme assets remain. New code should follow the documented standard and the old views/assets need a deliberate migration plan.

Resolve these blockers in a small first hardening sprint. Do not paper over them by marking features “complete” in `PROJECT_MANIFEST.md`.

## Definition of ready for the first implementation sprint

- [ ] Fresh checkout installs with documented commands.
- [ ] Migrations run on SQLite test DB and the chosen development database.
- [ ] `php artisan route:list` completes without unresolved route/controller methods.
- [ ] `php artisan test` baseline result is recorded.
- [ ] User model role assignment, permissions, and role middleware pass tests.
- [ ] Institution index/create/store/edit/update/delete flows work and reject unauthorized users.
- [ ] Empty and duplicate forms/views are resolved.
- [ ] Every claim in the feature catalog has evidence or a planned status.
- [ ] `.env`, SQLite runtime DB, logs, and uploaded data are absent from distribution archives.

## Evidence labels used throughout the docs

- **Implemented:** source/migration exists. This alone does not mean it works end-to-end.
- **Verified:** tested in the stated environment and the result recorded.
- **In progress:** some source exists but acceptance criteria are not all met.
- **Planned:** requirement/design only, not implemented.
- **Decision required:** conflicting choices must be resolved before implementation.
