# EQBEMS — Question Bank & Examination Management System

**Updated October 20, 2026.** This version extends the original Laravel 12 repository into an **end-to-end functional MVP implementation**. It adds academic catalogs, independent question-version approval, blueprint-based exam generation, fixed exam snapshots, student results, grade calculation, and role/institution isolation. The previous repository contained only a partial foundation.

> **Verification status:** PHP source lint and a standalone GradeService smoke check passed here. Full Laravel test execution, database migrations, CSS/JS builds, and browser-level validation **have not been executed in this environment**, because its PHP runtime has no `pdo_sqlite`, `mbstring`, or XML extensions and Composer is not installed. The application is delivered as source code, **not a claim of production readiness**. Run the checks below on your own system before using real data.

## Tech stack

Laravel 12 / PHP 8.3+ • Spatie Permission 6 • MySQL 8 or SQLite for development • Blade + Bootstrap 5 • Node 22 / Vite. This ZIP intentionally excludes PHP `vendor/`, npm `node_modules/`, `.env`, database contents, and live passwords. Third-party theme assets from the original repository are preserved under `public/`.

## Implemented workflows

| Area | Implementation |
|---|---|
| Account access | Breeze login/profile/password reset; opt-in public registration; Super Admin-provisioned users and institution assignments; role permission matrix |
| Institutions | CRUD, UUID route binding, soft-delete restore, file logo validation, role checks, institution-restricted lists |
| Academic catalog | Institution-owned subjects + topics with CRUD, academic-year entry and dates |
| Question bank | MCQ/true-false/short answer, topics, difficulties, marks, draft editing, submission, reviewer approval/rejection, revision history, no self-approval |
| Blueprint | Count, duration, subject, optional question-type and difficulty filters |
| Exams | Generate from approved versions only, explain insufficient pool, immutable content and answer-key snapshots, issue workflow, separate answer-key permission |
| Results | Record student score for issued exam, fixed-cent grade boundaries A/B/C/D/F, publish, student-only result visibility, correction reason/history |
| Observability | Database-backed dashboard numbers scoped by permission, structured application tests, GitHub Actions check definitions |

### Role matrix (seeded)

`Super Admin` can manage everything across institutions. `Organization Admin` and `Institution Admin` may manage academics, question drafts, and blueprints in their assigned institution. `Question Setter` may author/submit questions, `Moderator` reviews another user's submissions, `Exam Controller` generates/issues exams, accesses answer keys, grades and publishes, `Teacher` can author and mark, and `Student` can view only their own published results. Roles are managed by a Super Admin.

**Institution isolation:** Every non-global user must be assigned exactly one institution. `Super Admin` is global. Academic/exam/result pages apply institution scoping on every query/action. Assign a different role and institution via Users → Edit; global permission grants alone do not bypass scope.

## Install on Windows (Laragon / XAMPP / WAMP)

1. Install **PHP 8.3+** with OpenSSL, mbstring, XML, fileinfo, PDO SQLite (for SQLite) or PDO MySQL (for MySQL), and ZIP support; install Composer 2, Node.js 22, npm. Make sure they work from PowerShell: `php -v`, `composer -V`, `node -v`, `npm -v`.
2. Extract ZIP and open PowerShell in the `eqbems-main` folder.
3. Run:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File -Force database/database.sqlite
```

4. Edit `.env`:

```dotenv
APP_NAME=EQBEMS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
ALLOW_PUBLIC_REGISTRATION=false
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=REPLACE_WITH_A_UNIQUE_RANDOM_PASSWORD_AT_LEAST_12_CHARACTERS
```

**Important:** `DB_DATABASE` can be omitted for the default Laravel SQLite path; if specifying it, use a valid absolute path on your machine if Laravel cannot resolve the relative path. The email and password above are examples, **not functioning credentials**. Choose your own real admin email and strong secret; do not commit them.

5. Run:

```powershell
php artisan optimize:clear
php artisan migrate --seed
npm ci
npm run build
php artisan storage:link
php artisan serve
```

6. Open `http://127.0.0.1:8000/login` and sign in with the admin account you seeded. Remove `ADMIN_PASSWORD` from `.env` after bootstrap so it is not retained in an active config file. Clear cached config when changing `.env`.

For production or MySQL, set `DB_CONNECTION=mysql` and `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`; use a restricted DB account. Never enable `APP_DEBUG=true` in production. See the existing operations documentation and conduct a full security review before deployment.

## End-to-end first-use walkthrough

1. Log in as the bootstrap **Super Admin**, then create an **Institution**.
2. In **Users**, create a **Question Setter**, a separate **Moderator**, an **Exam Controller**, and a **Student**. Assign each to the institution and choose their role.
3. Create a **Subject** under Academic, then add some **Topics**.
4. Sign in as **Question Setter**, create MCQ/true-false/short-answer questions and **Submit for review**.
5. Sign in as **Moderator**, approve questions in Question Bank. An author cannot approve their own submission.
6. Sign in as **Exam Controller**, make a **Blueprint**, then click **Generate exam**. The pool must contain enough approved questions matching its filters.
7. Open the generated exam and **Issue paper**. Questions and answer keys are stored as snapshots. The separate answer-key page is restricted.
8. Use **Results → Enter marks**, choose an issued exam and student, record marks, then publish the result. The Student role sees only its own published records. To correct a published result, provide a reason; a history row is saved.

## Tests and code quality

```bash
php artisan migrate:fresh --seed  # WARNING: destroys existing DB data
php artisan test
./vendor/bin/pint --test
npm run build
```

`tests/Feature/DomainWorkflowTest.php` covers permissions, institution isolation, approval, generation, immutable snapshots and results. `tests/Unit/GradeServiceTest.php` covers grading thresholds. `.github/workflows/quality.yml` configures CI using PHP 8.3 / SQLite / Node 22. These automated tests are **included but not run** in this environment, so investigate any issues on the target machine before release.

## MVP boundaries and next decisions

The source implements a practical smaller workflow, **not every feature imagined by the original 87 KB database-design planning document**. Not included: full faculty/department/program hierarchy, configurable question-distribution rules, question media library/tags, randomized reproducibility seed, candidate registration/enrollment and exam attempt submission, question-level marking and moderation, grading policy administration, imports/exports, printable PDFs, institutional backups, full audit log of every action, accessibility review, load testing, approval signatures, enterprise multi-tenant organization memberships, or compliance certification. Institution scoping assumes a single institution per non-global user, with Super Admin unrestricted. `organization_id` remains a placeholder and is not a tenancy boundary. Test against both SQLite and MySQL, then conduct a security/privacy and deployment review before any production rollout.

## Original planning reference

The original `docs/` describes a larger target architecture and still contains historical “planned” status declarations. Those are **not authoritative implementation-status reports** for this MVP. Use this README and the actual migrations/controllers/views/tests as the current implementation reference; use `docs/` to plan future functionality.
