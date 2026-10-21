# EQBEMS — Local Setup, Validation, Tests and Troubleshooting

Based on the reviewed Laravel 12 source (October 2026). Primary environment: **Windows 10/11, XAMPP, PowerShell, VS Code**. Use a dedicated database with no valuable existing data when first testing.

## 0. Prerequisites

- PHP 8.2+ (PHP 8.3 recommended), CLI with required Laravel/Composer extensions including `gd`, `zip`, `mbstring`, `xml`, `fileinfo` and a PDO DB driver.
- Composer 2.
- Node.js 22 and npm.
- MySQL via XAMPP **or** PHP SQLite extensions enabled.
- VS Code / Git optional but recommended.

**No** `vendor/` or `node_modules/` directories are included in the reviewed source ZIP. You must install dependencies on your computer.

From PowerShell, inside the project root (the folder containing `artisan`, `composer.json` and `package.json`):

```powershell
Get-Location
Test-Path .\artisan
php -v
php --ini
php -m | findstr /i "gd zip mbstring xml pdo_sqlite sqlite3 pdo_mysql"
composer --version
node -v
npm.cmd -v
```

## 1. Prepare the project

Extract the ZIP. If a nested source directory is created, open **the directory that contains `artisan`**. Do not accidentally execute commands in the outer archive folder.

```powershell
composer install
Test-Path .\vendor\autoload.php
```

`True` is required before any `php artisan` command. If Composer reports missing `ext-gd` or `ext-zip`, edit the `php.ini` shown by `php --ini` (often `C:\xampp\php\php.ini`), enable `extension=gd` and `extension=zip`, restart the terminal, and retry `composer install`.

```powershell
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
}
notepad .env
php artisan key:generate
```

## 2. Choose a database — only one option

### Option A: SQLite for local development/testing

In `.env`:

```dotenv
APP_NAME=EQBEMS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
ALLOW_PUBLIC_REGISTRATION=false
```

Ensure `DB_DATABASE` is unset/commented, enable `pdo_sqlite` and `sqlite3` as required, then:

```powershell
if (-not (Test-Path .\database\database.sqlite)) {
    New-Item -ItemType File .\database\database.sqlite | Out-Null
}
```

### Option B: XAMPP MySQL

Start XAMPP MySQL; create an **empty** `eqbems` database in phpMyAdmin. In `.env` use your actual local DB credentials:

```dotenv
APP_NAME=EQBEMS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eqbems
DB_USERNAME=root
DB_PASSWORD=
ALLOW_PUBLIC_REGISTRATION=false
```

The sample `root`/empty password is only common to **local** XAMPP setups, not recommended for production. Verify your PHP CLI has `pdo_mysql`.

## 3. Bootstrap Super Admin safely

Before the initial seed, add **your own** unique account (do not publish these values):

```dotenv
ADMIN_EMAIL=your-real-admin-email@example.com
ADMIN_PASSWORD=REPLACE_WITH_A_NEW_UNIQUE_PASSWORD_AT_LEAST_12_CHARS
```

Run from the source root:

```powershell
php artisan optimize:clear
php artisan migrate --seed
php artisan migrate:status
```

The seeder creates roles and permissions and creates a Super Admin **only if both bootstrap variables are present**. When `APP_ENV=local`, `DatabaseSeeder` also seeds **pre-approved demonstration questions** via `QuestionSeeder` (which calls `SubjectSeeder`). They are fixtures, not independent human-reviewed real exam content. Do not import demo content to production.

After initial Super Admin creation, remove `ADMIN_PASSWORD` and (if not needed) `ADMIN_EMAIL` from `.env`; run `php artisan optimize:clear`. If you need a separate demo scenario, review `SEEDING_GUIDE.md`, `FACTORY_SEEDING_GUIDE.md`, and the named `BoardFormatDemoSeeder` / `ReviewWorkflowDemoSeeder` first instead of assuming they run by default.

## 4. Install / run browser assets

```powershell
npm.cmd ci
npm.cmd run build
php artisan storage:link
php artisan serve
```

Visit `http://127.0.0.1:8000/login`. For HMR during active frontend development, run `npm.cmd run dev` in another terminal alongside `php artisan serve`.

`npm.cmd` avoids Windows PowerShell's `npm.ps1` execution policy failure. The `npm ci` step uses the included `package-lock.json` and does not intentionally update dependency versions.

**First-use UI order:** login Super Admin → institutions → users/roles → subjects and topics → questions → submit → review queue → blueprints → eligible question selection → generate draft exam → preview/issue → online settings → student attempt → grade/publish. See `04_WORKFLOW_AND_DATA_MAP.md`.

## 5. Verification commands (run in order)

```powershell
# PHP-side runtime available?
php artisan --version
php artisan route:list
php artisan migrate:status

# Isolated and full tests
php artisan test --filter=GradeServiceTest
php artisan test --filter=RegressionSafeguardsTest
php artisan test

# PHP formatting/lint (if present after composer install)
.\vendor\bin\pint --test

# Frontend
npm.cmd run build
```

**Testing database:** Check `phpunit.xml` and the test bootstrap first. Use SQLite in-memory or a dedicated throwaway database; NEVER point a test suite at your development or production data. Some feature tests create/drop tables via Laravel's database-refresh traits.

### Suggested smoke-test checklist

- [ ] Login with the seeded Super Admin; guest access restricted.
- [ ] Create one institution; display/edit/soft-delete/restore according to permissions.
- [ ] Create staff roles and a student linked to the institution.
- [ ] Verify a different institution's user cannot view/edit these records.
- [ ] Create one subject/topic/academic year.
- [ ] Add valid MCQ / True–False / short answer / creative CQ questions.
- [ ] Submit draft for review; check that the original author cannot approve it.
- [ ] Reject one submission with reason, revise, and approve the new version.
- [ ] Attach approved questions to a blueprint; confirm matching question counts.
- [ ] Generate a draft exam; ensure manually pinned questions appear exactly once.
- [ ] Issue paper and verify candidate-facing paper has **no answer key**.
- [ ] Configure an online schedule; have a student start, save, submit.
- [ ] Verify time limits, duplicate submission handling, and tenant restrictions.
- [ ] Confirm objective automatic marks; mark short/CQ responses manually.
- [ ] Publish a result; confirm only the correct student sees the result.
- [ ] Edit a published mark with reason and check `result_revisions`.
- [ ] Confirm frontend CSS/JS and printable layout render correctly in browser.

## 6. Common errors and targeted fixes

| Symptom | Cause to check | Safe action |
|---|---|---|
| Missing `vendor/autoload.php` | Composer install failed/not run | Enable missing PHP extensions and rerun `composer install` |
| Missing `ext-gd` / `ext-zip` | Wrong/disabled CLI PHP extensions | `php --ini`; enable extensions; verify with `php -m` |
| `npm.ps1` blocked | PowerShell execution policy | Use `npm.cmd ci` and `npm.cmd run build` |
| `could not find driver` | PDO MySQL/SQLite missing | Enable correct driver in CLI `php.ini` |
| MySQL connection refused / access denied | Service, port or credentials | Start XAMPP MySQL and verify `.env`, then clear config |
| `SQLSTATE` missing tables | DB not migrated or wrong DB | `php artisan migrate:status`, confirm database, then `migrate` |
| Blank/no Super Admin login | Initial bootstrap missing | Set admin variables and run `php artisan db:seed` cautiously |
| `Vite manifest not found` | Assets not built | `npm.cmd ci` + `npm.cmd run build` |
| 403 access denied | Permission or institution scope | Check role seeder, user roles, `AccessScope`, policies |
| 409 conflict on question / exam / attempt | Invalid state transition/duplicate | Follow the documented lifecycle; don't bypass controller checks |
| No reviewable questions | Not submitted or no review permission | Check `question_versions.status`, staff role and institution |
| Fewer eligible questions than expected | Filters, approval states, type/format mismatch | `BlueprintPoolService`, blueprint filters and selected questions |
| Exam cannot open online | Still draft, empty, disabled, early/late schedule | Issue first, enable online, set valid dates |
| Draft result invisible to student | Intended publication gate | Authorized staff must publish the result |
| Tests fail unexpectedly | Inconsistent test DB / missing extensions | Review `phpunit.xml`, env and migrations; don't use real data |

**Do not** solve migration failures by deleting tables with valuable data, or solve missing PHP extensions by permanently skipping platform requirements. Never paste a complete `.env` into a public issue.

## 7. Team development workflow

1. Pull latest code and create a feature branch (`feature/...`, `fix/...`).
2. Confirm baseline `php artisan test` and `npm.cmd run build` before edits; record any failures.
3. Trace route → controller → request/service → model → view → test.
4. Write/revise a test for the required behavior **including unauthorized and cross-institution cases**.
5. Make minimal changes; use new migration files for schema changes.
6. Run tests, frontend build and linter; manually smoke-test the relevant page.
7. Update this handbook when folder/route/permission behavior changes.
8. Open a PR describing the change, tests run, risks and DB migration steps.

## 8. Deployment cautions

This document is local-development guidance **not a production runbook**. For deployment, set `APP_ENV=production`, `APP_DEBUG=false`, unique `APP_KEY`, secure database and secrets, TLS, correct permissions, safe queue/scheduler, backup/restore testing, log policy, asset build and explicit release smoke tests. Laravel document root should be `public/`—do not expose project root or `.env`. Read `SECURITY.md` and `docs/05_OPERATIONS/*` before planning production rollout.
