# EQBEMS — Laravel Factory-Based Subject and Question Seeders

This update replaces direct `Model::create()` calls in the demo subject/question seeders with **Laravel 12 Eloquent model factories**. It preserves the schema, the five predefined subjects, 20 topics, and 40 verified demo questions from the previous package.

## Files added

```text
database/factories/
  InstitutionFactory.php
  SubjectFactory.php
  TopicFactory.php
  QuestionFactory.php
  QuestionVersionFactory.php
```

## Existing files modified

```text
app/Models/Institution.php
app/Models/Subject.php
app/Models/Topic.php
app/Models/Question.php
app/Models/QuestionVersion.php

database/seeders/SubjectSeeder.php
database/seeders/QuestionSeeder.php

tests/Feature/DemoSeederTest.php
```

The five models use Laravel's `HasFactory` trait. `DatabaseSeeder.php` is unchanged: it runs the demo question seeder automatically only when `APP_ENV=local`.

## Run in Windows PowerShell (XAMPP)

You already migrated your database, so **do not run `migrate:fresh`**. In your existing Laravel project directory, start XAMPP's MySQL and check your `.env` database settings. Then run:

```powershell
php artisan optimize:clear
php artisan migrate:status
php artisan db:seed --class=QuestionSeeder
```

`QuestionSeeder` automatically calls `SubjectSeeder` before inserting questions. If you only want the subjects/topics, instead run:

```powershell
php artisan db:seed --class=SubjectSeeder
```

To run the project's complete default seeding flow (permissions, optional admin, and local-only demos):

```powershell
php artisan db:seed
```

If Laravel says a factory class cannot be found, run `composer dump-autoload`. You need Composer's development dependencies installed locally because Faker is declared as `require-dev`. If you use `composer install --no-dev`, the Faker-backed demo factories may not be available.

## Exactly what is created

| Table | Expected new demo rows in an empty demo database |
|---|---:|
| `institutions` | 1 (code `EQBEMS-DEMO`) |
| `subjects` | 5 |
| `topics` | 20 |
| `questions` | 40 |
| `question_versions` | 40 |

The five sample subjects are Programming Fundamentals, Data Structures and Algorithms, Database Management Systems, Computer Networks, and Software Engineering. Each has 8 demo questions (4 MCQ, 2 true/false, 2 short). If you already seeded these records with the prior version, rerunning **will not create additional records** or replace edited question content.

The 40 known examples are **deterministic** so their questions and correct answers remain meaningful and useful for testing. Their records are now created *through factories*; factory definitions also use Faker for generating additional random test data. This combines reproducible demo content with Laravel's factory API.

### Why there are two tables for one question

The `questions` table stores ownership (`institution_id`, `subject_id`, `topic_id`) and the `question_versions` table stores the body, options, answer, difficulty, marks and approval status. Creating a `Question` alone does **not** insert its version. The factory-backed seeder creates both.

## Generate extra random questions using factories (optional)

Run:

```powershell
php artisan tinker
```

Then paste the following PHP commands **one at a time** at the `>>>` prompt:

```php
$subject = \App\Models\Subject::where('code', 'CSE101')->whereHas('institution', fn ($q) => $q->where('code', 'EQBEMS-DEMO'))->firstOrFail();
$topic = $subject->topics()->firstOrFail();

// Create 10 additional question records under one real subject and topic.
$questions = \App\Models\Question::factory()->count(10)->create([
    'institution_id' => $subject->institution_id,
    'subject_id' => $subject->id,
    'topic_id' => $topic->id,
]);

// Add an MCQ version for every new question.
$questions->each(fn ($question) => \App\Models\QuestionVersion::factory()->for($question)->create());
```

Each version defaults to `draft`, not approved. This optional example creates 10 **new** questions each time it runs; it is not idempotent. Unlike the fixed 40-question catalog, Faker-based factory defaults reuse a small curated set of example question/answer pairs. These are demo fixtures, not a large pool of unique examination questions.

To create a true/false question version or a short-answer version instead:

```php
$question = \App\Models\Question::factory()->create([
    'institution_id' => $subject->institution_id,
    'subject_id' => $subject->id,
    'topic_id' => $topic->id,
]);
\App\Models\QuestionVersion::factory()->for($question)->trueFalse()->create();
```

Use `shortAnswer()` in place of `trueFalse()` for a short-answer example. Type `exit` to leave Tinker.

## Tests

```powershell
php artisan test tests/Feature/DemoSeederTest.php
```

The test suite uses SQLite in-memory per `phpunit.xml`, not your live MySQL database (provided the testing configuration is respected). The tests cover seeded counts, repeatability, type/option validation and factory relationships.

## Safety

- No new migration or database reset is needed.
- `SubjectSeeder` and `QuestionSeeder` preserve existing demo records by code and stable question marker; they do not overwrite reviewed/edited versions.
- Sample questions are marked `approved` **for local exam generation only**, using a machine-generated note; no human reviewer is impersonated.
- `DatabaseSeeder` runs demo inserts automatically only in `local`; direct calls to demo seeders are possible in any environment, so use them only on development/test databases.
- Never share `.env` credentials or copy a test database into production.

## Troubleshooting

| Problem | What to check |
|---|---|
| `SQLSTATE[HY000] [2002]` | Start MySQL in XAMPP and check `.env` host/port. |
| `Table ... subjects does not exist` | Run `php artisan migrate:status`, then `php artisan migrate` for pending migrations. |
| `Call to undefined method ::factory()` | Confirm the five updated model files with `HasFactory` were copied. |
| `Class Database\Factories\... not found` | Copy the five factory files to `database/factories` and run `composer dump-autoload`. |
| `Class Faker\Factory not found` | Install local Composer development dependencies: `composer install`. |
| `No questions visible` | Check your logged-in user's institution and UI permissions. Demo questions belong to `EQBEMS-DEMO`. |
| Admin not created | Set `ADMIN_EMAIL` and a strong `ADMIN_PASSWORD` in `.env`; rerun `php artisan db:seed`. |
