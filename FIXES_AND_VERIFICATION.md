# EQBEMS — Source Review, Fixes and Verification

**Review date:** 2026-10-20  
**Input:** `EQBEMS_Updated_Full_Source_MVP(1).zip`  
**Output:** Sanitized fixed source ZIP (without local secrets, logs, compiled templates, or installed dependencies).

## Fixed source defects

1. **Creative question revision lost data.** `QuestionController::revise` copied only common fields, so creative format, stimulus and the four-part assessment rubric were erased in revisions. It now copies all three. Added a feature regression test in `tests/Feature/RegressionSafeguardsTest.php`.
2. **Deleting the only platform Super Admin from `/profile`.** The profile self-service deletion endpoint bypassed the protection already implemented in the user-management controller. It now blocks deletion of the last Super Admin before logging out or deleting the account.
3. **Deleting accounts with academic history from `/profile`.** Previously student accounts with results/online attempts could trigger database foreign-key errors. Self-deletion now produces a readable validation error and retains academic records.
4. **Admin deleting or moving a student with existing online exam attempts.** `UserController` formerly checked only results. It now also checks pending, submitted or graded attempts when restricting account deletion or cross-institution transfer. A regression test covers this.
5. **Legacy Blade template integration.** Replaced direct PHP inclusion of Blade partial source with Blade `@include` directives, and corrected the non-existent `profile` route to `profile.edit` in the older navbar template.
6. **Release file hygiene.** Local `.env` contains an application encryption key and administrator bootstrap credentials, and `storage/logs/laravel.log` contains runtime exception details. The corrected delivery deliberately omits these private runtime files. Keep a separate local `.env` on your machine. Since the original ZIP was shared, assess whether the administrator password and application key should be rotated before deployment.

## Executed checks and results

| Check | Result |
| --- | --- |
| PHP syntax (`php -l`) across `app/`, `bootstrap/`, `config/`, `database/`, `routes/`, `tests/` | **PASS:** 116 files |
| First-party JavaScript syntax (`node --check resources/js/*.js`) | **PASS:** 3 files |
| Independent grading threshold cases, including decimal boundaries | **PASS:** 8 scenarios |
| Static named-route cross-reference on view/controller references | **PASS** for active named routes; request-object `route('token')` and `route('institution')` are not named routes |
| ZIP validation after packaging | See delivery checksum / verification command |
| Laravel automated/Pest feature suite (`php artisan test`) | **NOT RUN:** `vendor/autoload.php` missing (no Composer and required PHP extensions in this environment) |
| Fresh migrations / database integration | **NOT RUN:** no configured database service / required PHP database extensions |
| Frontend Vite production build | **NOT RUN:** `npm ci` could not obtain missing cached packages from registry; `vite` unavailable |
| Browser, end-to-end, security penetration and production testing | **NOT RUN:** dependent running application/services unavailable |

**Important:** A successful static lint is not proof that full features work at runtime. Newly added regression tests must be executed after installing dependencies. The corrected ZIP is **not production-certified**.

## How to complete functional verification (PHP 8.2+ / Node 22)

On a development machine with Composer, PHP extensions required by Laravel (including mbstring, XML, PDO database driver), and a running configured database:

```bash
composer install
cp .env.example .env            # New installation only; Windows cmd: copy .env.example .env
php artisan key:generate       # New installation only: do not reset keys on existing deployments
# Edit .env: configure DB_CONNECTION / database credentials, APP_URL and bootstrap administrator as needed
php artisan migrate --seed
php artisan test
npm ci
npm run build
php artisan serve
```

For local static smoke checks without Laravel dependencies:

```bash
bash scripts/verify-source.sh
```

For **existing installations with real data**, preserve your `.env`, back up your database, use `php artisan migrate` rather than `migrate:fresh`, and plan any credential/key rotation carefully. Never commit `.env` or ship runtime application logs.

## Recommended manual acceptance scenarios

- Log in with every role and verify forbidden routes and institution boundaries.
- Create a CQ question; approve it, revise it, and confirm stimulus and all four parts remain intact; then regenerate a blueprint-backed exam.
- Open an online exam for a student, save and submit it, grade written answers and publish only after review.
- Confirm a student with an online attempt cannot be reassigned to another institution or deleted, and confirm the final Super Admin cannot delete their own profile.
- Test the frontend build and migrations in the actual deployment environment, including backup and restore.
