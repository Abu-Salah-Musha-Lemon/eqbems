# EQBEMS — Subject and Question Demo Seeders

The following PHP files are included in `database/seeders/` (now powered by Laravel model factories):

- `SubjectSeeder.php` — creates a demo institution, 5 CSE subjects and 20 associated topics.
- `QuestionSeeder.php` — automatically calls `SubjectSeeder`, then adds 40 fully linked demo questions and their version 1 records.
- `DatabaseSeeder.php` — invokes the normal role/permission/admin seeders and, **only when `APP_ENV=local`**, the question demo seeder.

**No new migration files are needed.** Your 6 existing migrations already define the subjects, topics, questions and question_versions tables.

## Before you start

1. Start **MySQL** from XAMPP.
2. Open your existing EQBEMS project's `.env` and verify `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD`.
3. Keep `APP_ENV=local` on your own development machine (not on a public production server).
4. Make sure `php artisan migrate:status` reports all 6 migrations as **Ran**.
5. If you have not created a Super Admin, set your own strong `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` before running `php artisan db:seed`. Do not share or commit `.env`.

## Commands (PowerShell / XAMPP)

Open a terminal **in the Laravel project root** (the directory containing `artisan` and `composer.json`):

```powershell
php artisan optimize:clear
php artisan migrate:status
```

**Recommended: add subjects and questions together**:

```powershell
php artisan db:seed --class=QuestionSeeder
```

`QuestionSeeder` also invokes `SubjectSeeder`, so you only need the single command above.

**Or seed in two stages**:

```powershell
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=QuestionSeeder
```

**Or run all standard seeders and demo data at once**, when `.env` uses `APP_ENV=local`:

```powershell
php artisan db:seed
```

This also runs role, permission, role-permission, and optional admin seeders. If `APP_ENV` is *not* `local`, `DatabaseSeeder` deliberately omits demo content. Explicitly calling `--class=QuestionSeeder` still runs it, so do not do that on production databases.

## Content inserted

| Subject code | Subject | Topics | MCQ | True/false | Short answer | Questions |
|---|---|---:|---:|---:|---:|---:|
| CSE101 | Programming Fundamentals | 4 | 4 | 2 | 2 | 8 |
| CSE201 | Data Structures and Algorithms | 4 | 4 | 2 | 2 | 8 |
| CSE301 | Database Management Systems | 4 | 4 | 2 | 2 | 8 |
| CSE401 | Computer Networks | 4 | 4 | 2 | 2 | 8 |
| CSE402 | Software Engineering | 4 | 4 | 2 | 2 | 8 |
| **Total** | **5 subjects** | **20** | **20** | **10** | **10** | **40** |

The default institution is **EQBEMS Demo Institution**, institution code `EQBEMS-DEMO`. This will appear in the institution and subject lists for a Super Admin; institution-scoped users see only their own institution.

If you want to seed these *sample records* into a different **existing and active** institution, put its exact code in `.env` before running the seeder:

```ini
DEMO_INSTITUTION_CODE=YOUR-INSTITUTION-CODE
```

If that code does not exist, a new demo institution is created with that code. **Do not point this at a real production institution.** If the code identifies a soft-deleted or inactive institution, the seeder stops without altering it.

## How question records are stored

The schema has a `questions` table for institution/subject/topic and a `question_versions` table for the text, options, correct answer, marks, difficulty and review status. The seeder creates one version per question. MCQ options are a JSON object using keys `A`, `B`, `C` and `D`, and its `answer_text` contains the correct letter. True/false answers are `True` or `False`.

### Important: demo approval status

The 40 sample questions are inserted with `status=approved` **solely so exam-paper generation can be demonstrated immediately**. They are tagged `EQBEMS-DEMO` in `review_note`, and no human reviewer is falsely recorded (`reviewed_by=NULL`, `reviewed_at=NULL`). They are synthetic test data **not suitable for actual examinations without independent review**. Real questions should use the normal draft → submit → moderator approval workflow.

## Safe to rerun

The seeders check for existing subject/topic keys before calling `Model::factory()->create()`, and use a stable demo marker per question. Running them multiple times avoids inserting duplicates and does **not** overwrite existing questions or human review edits. They do not call `migrate:fresh` and do not truncate your data.

## Check the seeded records

You can inspect them in phpMyAdmin or via Laravel Tinker:

```powershell
php artisan tinker
```

In the Tinker prompt:

```php
\App\Models\Subject::whereHas('institution', fn ($q) => $q->where('code', 'EQBEMS-DEMO'))->count(); // 5
\App\Models\Question::whereHas('subject', fn ($q) => $q->where('code', 'CSE101'))->count(); // 8 in demo database
\App\Models\QuestionVersion::where('status', 'approved')->count(); // >= 40 if no other approved questions
```

Then type `exit` to leave Tinker. The exact totals may be higher when other questions already exist.

## Automated tests

The source archive also includes `tests/Feature/DemoSeederTest.php` to check counts, option keys and repeatability:

```powershell
php artisan test tests/Feature/DemoSeederTest.php
```

This test uses the project's configured test database (`phpunit.xml` specifies an in-memory SQLite database). Do not point the test suite at your live database.

## What to do if a command fails

- `Table '...subjects' doesn't exist`: run `php artisan migrate:status`, then `php artisan migrate` if any migration is pending.
- `SQLSTATE[HY000] [2002]`: start MySQL in XAMPP and verify `.env` database port.
- `Class "Database\\Seeders\\QuestionSeeder" not found`: verify both files are inside `database/seeders/` and run `composer dump-autoload`.
- Demo institution is missing from the UI: verify you logged in with the Super Admin role. Users tied to another institution cannot see the demo institution's data.
- The `ADMIN_EMAIL` warning does not stop demo data seeding, but you still must configure an admin account before you can log in.

## Factory implementation

Five new factories in `database/factories/` are used by the seeders: `InstitutionFactory`, `SubjectFactory`, `TopicFactory`, `QuestionFactory` and `QuestionVersionFactory`. Their models now use `HasFactory`. For running additional random factory test records or troubleshooting, see [`FACTORY_SEEDING_GUIDE.md`](FACTORY_SEEDING_GUIDE.md).
