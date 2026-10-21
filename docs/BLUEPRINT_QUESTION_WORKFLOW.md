# Blueprint question management — EQBEMS

This release fixes the missing Add Question / Approve Question workflow inside the Blueprint module.

## Install on your EXISTING XAMPP project (Windows PowerShell)

1. Make a backup of your project files and MySQL database in phpMyAdmin. Keep the original `.env` file.
2. Extract `EQBEMS_Blueprint_Questions_Patch.zip` into your Laravel project root (the folder with `artisan`), preserving paths and replacing files with the same names. **Do not delete your project or `.env`.**
3. Open PowerShell in the project folder and execute:

   ```powershell
   php artisan optimize:clear
   php artisan migrate
   php artisan db:seed --class=RolePermissionSeeder
   php artisan route:list --name=blueprints
   php artisan serve
   ```

   `RolePermissionSeeder` refreshes the standard application's role matrix: Institution Admin, Organization Admin and Exam Controller can review questions submitted by **other** authors; Moderators can access the Blueprint question screen. Exam Controllers can now create questions. **Note:** the seeder uses `syncPermissions` and overwrites custom permissions for the named default roles; if you've customized these roles, update your role permissions manually instead of rerunning the seeder.

4. Log out and in again if your permissions are not immediately refreshed; ensure the user has an appropriate role.
5. Visit **Blueprints → Manage questions**. The question manager opens for a saved Blueprint (create a Blueprint first).

The installation adds migration `2026_10_20_000003_create_blueprint_questions_table.php` (the **eighth** migration in this project). Existing questions, exams, marks and results are preserved. **Never run `migrate:fresh` against a database you wish to keep.**

## Workflow

- **Create Blueprint**: select subject, count, type/format/difficulty and save.
- **Manage questions → Add new question**: the subject, type and format are prefilled. Save a draft.
- **Submit for approval**: the author submits their draft from the Blueprint question manager or Question Bank detail page.
- **Approve / Review**: a different authorized reviewer sees submitted questions and can approve or reject. The UI provides a quick Approve button and a detail review page where a rejection reason can be entered.
- **Add to blueprint**: available only for **approved** questions matching subject, type, difficulty and format. Unapproved questions are not selectable. This manually pins the question for paper generation.
- **Generate exam**: all pinned questions are included, plus randomly chosen additional matching approved questions until the requested question count is met. A selected question's newest *approved* version is used, even when a newer draft exists.
- **Remove**: unpin a question. This only affects future exam generation; issued/generated papers remain immutable snapshots.

A Blueprint can also be used without manually selected questions: generation randomly chooses from its eligible approved pool. A Blueprint **does not auto-approve** newly authored questions.

## Permissions and common questions

| Task | Required permission | Rule |
|---|---|---|
| View Blueprint questions | `blueprint.view` and `question.view` | Own institution, except Super Admin |
| Add draft | `question.create` and `blueprint.view` (when creating from Blueprint) | Subject must match Blueprint |
| Submit draft | `question.create` | Own authored question (Super Admin may submit any draft) |
| Approve/reject | `question.review` | Different author **and** version creator; status must be `submitted` |
| Pin/unpin | `blueprint.manage` | Approved and matching question, own institution, no more than `question_count` |
| Generate | `exam.generate` | All pinned questions still eligible, enough approved questions |

### Why can't I see the Approve button?

- The question is still a `draft`: first click **Submit for approval**.
- You are the question's author or version creator: use another authorized reviewer account.
- Your role lacks `question.review`, or you cannot access this institution.
- The question is already approved/rejected, or you applied a filter hiding submitted questions.

### Why can't I select a question?

- It has no approved version matching the Blueprint's subject, type, format and difficulty.
- You lack `blueprint.manage`.
- You already selected `question_count` questions; remove one or edit the Blueprint to allow more.

### Why can't I edit a Blueprint filter?

Previously manually selected questions must remain eligible. Remove incompatible selections first; then save the new subject/type/difficulty/format filters.

## Tests

```powershell
php artisan test tests/Feature/BlueprintQuestionManagementTest.php
php artisan test tests/Feature/BlueprintRepairTest.php
php artisan test tests/Feature/DomainWorkflowTest.php
```

These tests cover approval access, creation from a Blueprint, manually pinned exam questions, duplicate selection protection, cross-institution restrictions, filter changes and use of historical approved versions. Actual Laravel/MySQL testing must be performed with Composer dependencies installed.
