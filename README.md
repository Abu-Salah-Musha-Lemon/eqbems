# EQBEMS — Installation, Error Fixes & Project Guide

> **Developer handoff:** Start with [DEVELOPER_START_HERE.md](DEVELOPER_START_HERE.md) for the complete source-specific folder map, workflows, database ERD, routes, permission matrix, Windows setup, file-by-file index and developer task checklist.


**Project:** EQBEMS (Question Bank & Examination Management System)  
**Framework:** Laravel 12, PHP, Blade, Vite / Node.js  
**Target setup:** Windows 10/11 + XAMPP + VS Code PowerShell  
**Last updated:** October 20, 2026

> This README documents the **actual errors reported during installation** and how to resolve them, followed by a clean setup procedure. It is tailored to the `EQBEMS_Updated_Full_Source_MVP` archive. Errors in the *Additional troubleshooting* section are preventative examples, **not** errors already reported or verified on your computer. The application is an MVP; a full Laravel test run and browser acceptance testing are still required.

## Contents

1. [Reported errors — summary](#1-reported-errors--summary)
2. [Error 1: Composer missing GD and ZIP PHP extensions](#2-error-1-composer-missing-gd-and-zip-php-extensions)
3. [Error 2: Missing vendor/autoload.php when running Artisan](#3-error-2-missing-vendorautoloadphp-when-running-artisan)
4. [Error 3: PowerShell blocks npm.ps1](#4-error-3-powershell-blocks-npmps1)
5. [Clean installation on Windows — step by step](#5-clean-installation-on-windows--step-by-step)
6. [Database and migrations](#6-database-and-migrations)
7. [Additional troubleshooting — possible future errors](#7-additional-troubleshooting--possible-future-errors)
8. [Quick diagnostic commands](#8-quick-diagnostic-commands)
9. [Project scope, tests, and security](#9-project-scope-tests-and-security)
10. [Sample subjects and question bank seeders](#10-sample-subjects-and-question-bank-seeders)

---

## 1. Reported errors — summary

| Command | Reported error | Root cause | Correct first fix |
|---|---|---|---|
| `composer install` | `ext-gd * -> it is missing from your system` | CLI PHP GD extension is disabled or unavailable | Enable `extension=gd` in the CLI `php.ini` |
| `composer install` | `ext-zip ^1.14.0 -> it is missing from your system` | CLI PHP ZIP extension is disabled or unavailable | Enable `extension=zip` in the CLI `php.ini` |
| `php artisan key:generate` | `vendor/autoload.php: Failed to open stream` | Composer failed before creating a complete `vendor/` directory | Fix the extension errors, then rerun `composer install` |
| `npm install` | `npm.ps1 cannot be loaded because running scripts is disabled` | PowerShell execution policy blocks the npm PowerShell wrapper | Run `npm.cmd install` or `npm.cmd ci` |

**Important:** Resolve Composer dependency installation **before** running any `php artisan` command. Resolve `npm` PowerShell issues **without** disabling machine-wide security settings.

## 2. Error 1: Composer missing GD and ZIP PHP extensions

### Error message you received

```text
Your lock file does not contain a compatible set of packages.
Problem 1:
- phpoffice/phpspreadsheet 1.30.5 requires ext-gd * -> it is missing from your system.
Problem 2:
- spatie/laravel-backup 9.3.6 requires ext-zip ^1.14.0 -> it is missing from your system.
Problem 3:
- maatwebsite/excel requires phpoffice/phpspreadsheet, which also requires ext-gd.
```

### Why it happens

Composer checks required PHP extensions before installing packages. Your Composer output identified this configuration file:

```text
C:\xampp\php\php.ini
```

The XAMPP PHP installation may have the GD and ZIP extension files available but not enabled for **CLI PHP** (the PHP executed by your terminal).

### Fix

**Step 1 — Confirm which PHP and php.ini the terminal is using:**

```powershell
where.exe php
php -v
php --ini
```

Use the `Loaded Configuration File` shown by `php --ini`. For the error reported, that file is `C:\xampp\php\php.ini`.

**Step 2 — Open that file:**

```powershell
notepad C:\xampp\php\php.ini
```

**Step 3 — Search for these lines and remove the semicolon (`;`) from the beginning.** (Depending on your PHP version, the original lines may read `;extension=gd` and `;extension=zip`.)

```ini
extension=gd
extension=zip
```

Do not add duplicate lines if these extensions are already enabled. Save `php.ini` and open a **new** PowerShell terminal. If XAMPP Apache is also serving the application, restart Apache to reload its PHP configuration; this is not normally necessary for CLI PHP.

**Step 4 — Verify:**

```powershell
php -m | findstr /i "gd zip"
```

Expected output contains **both** extensions:

```text
gd
zip
```

If an extension is still missing, inspect whether `C:\xampp\php\ext\php_gd.dll` and `C:\xampp\php\ext\php_zip.dll` exist, and review `extension_dir` and any PHP startup warnings from `php -m`. Also make sure Composer and the terminal are using the same PHP executable.

**Step 5 — Retry Composer:**

```powershell
composer install
```

> **Avoid:** `composer update` is not the fix for missing PHP extensions and can change locked dependency versions. Avoid `--ignore-platform-req=ext-gd` or `--ignore-platform-req=ext-zip` as a permanent workaround: packages may still fail at runtime.

## 3. Error 2: Missing vendor/autoload.php when running Artisan

### Error message you received

```text
PHP Warning: require(...\vendor\autoload.php): Failed to open stream: No such file or directory
PHP Fatal error: Failed opening required '...\vendor\autoload.php' in artisan on line 10
```

### Why it happens

`artisan` depends on Composer's generated `vendor/autoload.php`. Because `composer install` failed on the GD/ZIP requirements, Laravel's dependencies and autoloader were not installed successfully. This is a **secondary error**, not a separate missing Laravel file to create manually.

### Fix

After applying the GD/ZIP fix:

```powershell
composer install
Test-Path .\vendor\autoload.php
```

`Test-Path` must return `True`. Then, if this is your first setup:

```powershell
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
}
php artisan key:generate
```

Expected key-generation result resembles `INFO  Application key set successfully.`

If `Test-Path` is `False`, recheck the complete output of `composer install`. Do **not** download a random `autoload.php` or create that file manually.

## 4. Error 3: PowerShell blocks npm.ps1

### Error message you received

```text
npm : File C:\Program Files\nodejs\npm.ps1 cannot be loaded because
running scripts is disabled on this system.
CategoryInfo          : SecurityError
FullyQualifiedErrorId : UnauthorizedAccess
```

### Why it happens

Windows PowerShell resolves the `npm` command to the `npm.ps1` wrapper and blocks its execution according to the PowerShell script execution policy. This is **not an application code error** and does not imply Node.js is missing.

### Recommended fix — no execution-policy change

Use `npm.cmd`, which invokes the Windows command wrapper instead of the PowerShell script:

```powershell
node -v
npm.cmd -v
npm.cmd ci
npm.cmd run build
```

- `npm.cmd ci` is preferred **for this project** because a `package-lock.json` is already included; it performs a clean installation from the lockfile.
- `npm.cmd install` is also valid if you are intentionally updating/installing dependencies without a clean lockfile install.
- `npm.cmd run dev` launches the Vite development watcher when you need live frontend changes.

### Optional alternative — allow local scripts for your user only

If you want the plain `npm` command in PowerShell:

```powershell
Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned
```

Then open a new terminal and try `npm -v`. This changes your **current user's** policy, so using `npm.cmd` remains the less intrusive option. Some organization-managed devices may override the setting; do not weaken organization policy to work around it.

## 5. Clean installation on Windows — step by step

Use these steps **in order** after fixing the errors above. The commands assume you are inside the actual Laravel project root: a folder containing `artisan`, `composer.json`, `package.json`, and `.env.example`.

### Step A — Prerequisites and correct folder

Recommended development environment: PHP **8.3+** (Composer requires PHP `^8.2`), Composer 2, Node.js 22, and XAMPP MySQL or SQLite. Required PHP extensions depend on the installed packages and database, including `gd`, `zip`, `mbstring`, `xml`, `fileinfo`, and `pdo_mysql` or `pdo_sqlite`.

```powershell
php -v
composer --version
node -v
npm.cmd -v
php --ini
Test-Path .\artisan
```

If `Test-Path .\artisan` returns `False`, enter the extracted `eqbems-main` subfolder (or the folder containing `artisan`) and retry. The downloaded archive stores project files under `eqbems-main/`.

### Step B — Install backend dependencies

```powershell
composer install
```

Do not continue until this command succeeds.

### Step C — Create the Laravel environment file

```powershell
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
}
notepad .env
```

Configure **one** database option from Section 6 below. The application must also have the following lines configured for development:

```dotenv
APP_NAME=EQBEMS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
ALLOW_PUBLIC_REGISTRATION=false
ADMIN_EMAIL=your-own-admin@example.com
ADMIN_PASSWORD=REPLACE_WITH_YOUR_OWN_UNIQUE_PASSWORD_OF_AT_LEAST_12_CHARACTERS
```

**Security:** Replace both admin placeholders with your own values. Never publish your `.env` or real passwords. The admin is only created if both values are present when you seed the database; there is **no shipped default admin password**.

### Step D — Generate your application key

```powershell
php artisan key:generate
php artisan optimize:clear
```

### Step E — Create and migrate the database

Choose SQLite or MySQL in Section 6. Once configured:

```powershell
php artisan migrate --seed
php artisan migrate:status
```

The initial `--seed` runs the provided role/permission seeders and (when configured correctly) creates the Super Admin. For an already migrated database, you can run `php artisan db:seed` to run seeders again.

### Step F — Build the frontend

```powershell
npm.cmd ci
npm.cmd run build
```

A successful production build creates frontend assets for Vite. If you are actively developing and want the Vite hot-reload server, use `npm.cmd run dev` in another terminal instead of relying on the build alone.

### Step G — Finish setup and start the server

```powershell
php artisan storage:link
php artisan serve
```

Open **http://127.0.0.1:8000/login** and log in using the Super Admin email and password set in `.env` at the time of seeding. `storage:link` may report that the link already exists; you normally only need to create it once.

### Step H — After first Super Admin creation

Remove `ADMIN_PASSWORD` from `.env` (and preferably the temporary bootstrap `ADMIN_EMAIL` line if you no longer need it), then clear cached configuration:

```powershell
php artisan optimize:clear
```

Do **not** run `php artisan migrate:fresh` on a database with important data. It drops the existing tables.

## 6. Database and migrations

### Option A — SQLite (simple local development)

Confirm SQLite support:

```powershell
php -m | findstr /i "pdo_sqlite sqlite3"
```

If the extensions are absent, enable `pdo_sqlite` and `sqlite3` in the same PHP configuration file, then verify again. In `.env` use:

```dotenv
DB_CONNECTION=sqlite
```

Remove or comment out any `DB_DATABASE` override for SQLite so Laravel uses its standard `database/database.sqlite` path. Create the file:

```powershell
if (-not (Test-Path .\database\database.sqlite)) {
    New-Item -ItemType File -Path .\database\database.sqlite | Out-Null
}
php artisan optimize:clear
php artisan migrate --seed
```

### Option B — XAMPP MySQL

Start **MySQL** in XAMPP Control Panel and create a database named `eqbems` in phpMyAdmin or through MySQL. Confirm PDO MySQL support:

```powershell
php -m | findstr /i "pdo_mysql"
```

Example local `.env` settings (change credentials if yours differ):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eqbems
DB_USERNAME=root
DB_PASSWORD=
```

After saving `.env`:

```powershell
php artisan optimize:clear
php artisan migrate --seed
php artisan migrate:status
```

> These `root` / blank password settings are a **common local XAMPP example only**; use your actual XAMPP credentials. A separate restricted MySQL user is preferable beyond local development.

### Migration inventory

This ZIP contains **6 migration files** (not just 6 database tables):

1. `0001_01_01_000000_create_users_table.php`
2. `0001_01_01_000001_create_cache_table.php`
3. `0001_01_01_000002_create_jobs_table.php`
4. `2026_07_04_190837_create_permission_tables.php`
5. `2026_07_05_142437_create_institutions_table.php`
6. `2026_10_20_000001_create_academic_and_exam_tables.php`

The sixth migration also creates the academic years, subjects, topics, question versions, blueprints, exams, exam-question snapshots, results, and result revision tables. It is normal for one migration to create multiple tables.

Check applied migrations with:

```powershell
php artisan migrate:status
```

`Ran` means the migration is applied; `Pending` means it is not yet applied.

## 7. Additional troubleshooting — possible future errors

These are common setup problems that **may** occur later. They were **not** part of the errors you reported.

| Possible error | Likely cause | Safe next action |
|---|---|---|
| `No application encryption key has been specified` | `.env` is missing or `APP_KEY` is empty | Copy `.env.example` if needed, then run `php artisan key:generate` |
| `could not find driver` | PHP lacks the PDO extension for the selected database | Enable `pdo_mysql` or `pdo_sqlite` in the CLI `php.ini`, verify with `php -m` |
| `SQLSTATE[HY000] [1049] Unknown database 'eqbems'` | MySQL database does not exist | Create `eqbems` in phpMyAdmin; then retry `php artisan migrate` |
| `SQLSTATE[HY000] [1045] Access denied` | Incorrect MySQL username/password | Fix `.env` credentials; run `php artisan optimize:clear` |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL service stopped or host/port wrong | Start MySQL in XAMPP, confirm port, review `.env` |
| `database/database.sqlite does not exist` | SQLite database file was never created or path is wrong | Create file under `database/`; remove invalid relative `DB_DATABASE` override |
| `Vite manifest not found` | Frontend assets not built | Run `npm.cmd ci`, then `npm.cmd run build` |
| `npm is not recognized` / `node is not recognized` | Node.js not installed or not in PATH | Install Node.js, reopen terminal, verify `node -v` and `npm.cmd -v` |
| `vendor/autoload.php not found` | Composer dependencies absent | Resolve `composer install` errors and rerun `composer install` |
| Migration `table already exists` | Database state and migration history disagree | Inspect `php artisan migrate:status` and existing tables; **do not** reset a database with valuable data |
| Cannot log in as Super Admin | Admin bootstrap variables were empty when seeding, or seed failed | Configure your chosen `ADMIN_EMAIL` and `ADMIN_PASSWORD` (12+ characters) and run `php artisan db:seed` |
| `php artisan serve` port 8000 is in use | Another program uses the port | Run `php artisan serve --port=8001` and open `http://127.0.0.1:8001` |
| `storage:link` reports link already exists | Storage link was already created | Usually safe to continue; only troubleshoot if uploads cannot be accessed |
| `php artisan test` reports SQLite driver error | The test configuration uses in-memory SQLite | Enable `pdo_sqlite`/`sqlite3` or configure a safe dedicated test database |

When an error persists, capture the **full terminal output** plus the results of `php -v`, `php --ini`, `where.exe php`, and relevant `php -m` extension checks. Do not share passwords or the full `.env` file.

## 8. Quick diagnostic commands

Copy/paste these from the Laravel project root in VS Code PowerShell:

```powershell
# Where am I?
Get-Location
Test-Path .\artisan

# PHP/Composer checks
where.exe php
php -v
php --ini
php -m | findstr /i "gd zip mbstring xml pdo_mysql pdo_sqlite sqlite3"
composer --version
Test-Path .\vendor\autoload.php

# Node/npm checks
node -v
npm.cmd -v

# Laravel checks (only after Composer succeeds)
php artisan --version
php artisan migrate:status
php artisan route:list
```

### Minimal command order for a fresh local install

```powershell
# 1. First enable gd + zip in CLI php.ini, as explained above.
composer install

# 2. Create and edit .env. Configure SQLite or MySQL and admin credentials.
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
notepad .env

# 3. Application setup
php artisan key:generate
php artisan optimize:clear

# 4. If using SQLite only, create database/database.sqlite first.
#    If using MySQL, create the named database in phpMyAdmin first.
php artisan migrate --seed

# 5. Frontend build (npm.cmd avoids PowerShell npm.ps1 blocking)
npm.cmd ci
npm.cmd run build

# 6. Start
php artisan storage:link
php artisan serve
```

## 9. Project scope, tests, and security

### Implemented MVP areas

- Authentication, user accounts, roles, permissions, and institution restrictions.
- Institution management and academic catalog (academic years, subjects, topics).
- Question bank (MCQ, true/false, short response), submission, review, and question versions.
- Blueprint-based question selection, exam generation, issuing, and answer-key restrictions.
- Student results, grade calculations, publication, and revision history.

### Run tests after installation

```powershell
php artisan test
npm.cmd run build
```

**Verification disclaimer:** Previous source-level PHP lint and grading checks passed during project preparation, but the full Laravel tests, real database migrations, frontend build, and browser workflows were not executed in that environment. Running these on your Windows installation is necessary before declaring the system complete or production ready.

### Security notes

- Never commit `.env`, passwords, `vendor/`, or `node_modules/` to a public repository.
- `APP_DEBUG=true` is for local development only. Set `APP_DEBUG=false` in production.
- Do not use `--ignore-platform-req` flags to hide missing PHP dependencies.
- Never run `migrate:fresh` or destructive database commands against a database containing data you need to keep.
- Admin accounts should be created with your own unique secret and appropriate institutional access controls.

**For feature details and architecture notes, see `PROJECT_README_ORIGINAL.md` in the accompanying updated ZIP.**


## 10. Sample subjects and question bank seeders

This updated archive contains **SubjectSeeder.php** and **QuestionSeeder.php**. Together they add a dedicated demo institution, **5 CSE subjects**, **20 topics** and **40 sample questions** (MCQs, true/false and short answers) to an already migrated database. There are **no additional migrations**. The sample questions are automatically approved **for local demonstration only**, with an explicit note that no real review took place.

To create demo subjects and questions inside your existing EQBEMS project:

```powershell
php artisan db:seed --class=QuestionSeeder
```

This command calls `SubjectSeeder` automatically and can be repeated without duplicating the seeded records. With `APP_ENV=local`, `php artisan db:seed` runs the usual role/admin setup and these demo records too. **Do not run demo seeding on a production database.** For all options, verification and security warnings, see [SEEDING_GUIDE.md](SEEDING_GUIDE.md).

---

## Bangladesh-style Exam Paper Templates (new)

The project now supports standard, compact MCQ and creative/CQ layouts for A4 printing, plus secure timed online exam attempts, objective auto-marking and manual CQ grading. A new, additive migration is included. See [EXAM_PAPER_TEMPLATES.md](docs/EXAM_PAPER_TEMPLATES.md) for setup, exact Windows commands, and the optional factory-based ICT demo seeder.


## Blueprint question management (new)

Blueprints now provide **Manage questions**, where staff can create drafts, submit for review, approve questions independently, pin approved matching questions, and generate papers containing their pinned selections. See [`docs/BLUEPRINT_QUESTION_WORKFLOW.md`](docs/BLUEPRINT_QUESTION_WORKFLOW.md) for migration, permission refresh, testing and troubleshooting instructions. Run `php artisan migrate` after applying the update.


## Question review / approval queue (latest patch)

See [QUESTION_REVIEW_APPROVAL_FIX_GUIDE.md](QUESTION_REVIEW_APPROVAL_FIX_GUIDE.md) for the new independent review screen, eligible roles, two-account test instructions, and optional pending-question demo seeder.
