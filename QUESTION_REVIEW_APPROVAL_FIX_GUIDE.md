# EQBEMS — Missing Review / Approve Questions Options

This patch adds an independent **Question Review Queue** and makes approval actions easy to find in the Blueprint Question Manager and Question Bank.

## Why the Approve button did not appear

The original UI only rendered the approval controls when **all** of the following were true:

1. The **latest question version** had status `submitted` (not `draft`, `approved`, or `rejected`).
2. The current account had the `question.review` permission.
3. The current account was **not** the question's author and **not** the version creator.
4. The current account could access the question's institution.

The factory demo seeders create already approved example questions, so they will not have pending approval buttons. Further, a question creator **cannot approve their own submission**.

## What's new

- Sidebar **Examination → Review questions** link for authorized reviewers.
- **Question Bank → Review pending questions** button and status filter.
- **Blueprint → Manage questions → Review pending questions** button scoped to the Blueprint's subject.
- New `/questions/review-queue` page listing only submitted questions from **other authors** in the reviewer's institution.
- Question details page shows Approve/Reject and feedback when the logged-in user is eligible to review. The author sees an explanation instead of an unresponsive missing button.
- An optional local-only factory seeder to create two `submitted` demo questions for a realistic review test.
- Feature tests for permissions, review transitions, filtering, and institution boundaries.

## Installation on Windows / XAMPP

**Back up your project and database first.** Extract the patch into the Laravel project root (the folder containing `artisan`) and overwrite only the matching code files. Keep your `.env`, `vendor/`, and existing database.

```powershell
php artisan optimize:clear
php artisan permission:cache-reset
php artisan route:list --path=questions
php artisan serve
```

The package contains no new migrations. **Do not run `migrate:fresh`.** If `permission:cache-reset` is unsupported in your setup, `php artisan optimize:clear` is still useful; check that Spatie Permission is installed.

For existing accounts, confirm their user role in **Super Admin → Users**. Default roles with review capability include **Super Admin**, **Moderator**, **Exam Controller**, **Teacher**, **Institution Admin**, and **Organization Admin**. A reviewer other than Super Admin must belong to the same institution as the question.

If the role-permission mappings were never seeded or are missing, run (on installations using the default roles only):

```powershell
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RolePermissionSeeder
php artisan permission:cache-reset
```

**Warning:** `RolePermissionSeeder` uses `syncPermissions`, which overwrites any customized permission assignments on those default roles. Skip it if you've customized them; assign `question.review` to your intended reviewer role instead.

## Test the approval workflow

1. Log in as a **Question Setter** or staff user with `question.create` and create a question in the **Question Bank** or via **Blueprint → Manage questions → Add new question**.
2. Open the new draft and click **Submit for review**. Its status should change to `submitted`.
3. **Log out**. Log in as a **different user** with `question.review` permission in the *same institution* (e.g. Moderator).
4. Open **Examination → Review questions**. The submitted question should appear with a **Review / Approve / Reject** button.
5. Open it, check the text and answer, and choose **Approve** or **Reject** (rejection requires feedback).
6. On approval, the question is available to Blueprint question selection when it matches Blueprint filters. Return to **Blueprints → Manage questions → Add to blueprint** to pin it into the exam.

### Optional: create two pending sample questions

On your **local development database only**, run:

```powershell
php artisan db:seed --class=ReviewWorkflowDemoSeeder
```

- The seeder uses Laravel model factories to create two `submitted` demo MCQs for the EQBEMS demo institution.
- Rerunning it does not create duplicates. It does not change existing approved questions.
- It creates a separate dummy author with a randomly generated, undisclosed password and `.invalid` email; there are no preset credentials.
- This optional seeder is **not** called automatically by `DatabaseSeeder` and refuses to run outside `APP_ENV=local`.
- To review the sample questions, log in as a separate account with access to the **EQBEMS Demo Institution**, or as Super Admin.

## Troubleshooting

| Symptom | Explanation / fix |
|---|---|
| No **Review questions** sidebar link | Your account doesn't have `question.review`; assign an authorized reviewer role, clear Spatie cache, sign out and back in. |
| Empty review queue | There are no `submitted` questions by other users in an accessible institution, or the subject filter excludes them. The regular demo questions are already approved. |
| Author sees no Approve button | Working as designed: self-approval is prohibited. Ask another authorized reviewer. |
| Reviewer sees HTTP 403 | Check the account role, `question.review` permission, and institution assignment. |
| A submitted question does not appear | Check whether the latest version remains `submitted`. A newer draft/approved version changes queue eligibility. |
| Question approved but not in Blueprint | Approval and Blueprint selection are separate. Open Blueprint → Manage questions, verify type/format/difficulty, and click **Add to blueprint**. |
| Page returns 404 | Verify the patch was extracted into the folder containing `artisan`, then run `php artisan optimize:clear` and `php artisan route:list --path=questions`. |

## Tests

```powershell
php artisan test tests/Feature/QuestionReviewQueueTest.php
php artisan test tests/Feature/BlueprintQuestionManagementTest.php
```

PHP syntax was checked on changed PHP files. The actual Laravel/MySQL integration tests must be executed on a machine with project Composer dependencies and a configured test database.
