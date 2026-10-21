# EQBEMS — Developer Change Guide and Onboarding Tasks

A new contributor should know where to edit code, how to prove that a change works, and what data/security invariants not to break.

## 1. Work on one complete vertical slice

Example: **Add an optional `reference_link` field to a subject.**

1. Add a **new** timestamped migration to alter `subjects` (nullable string, appropriate size). Do not rewrite a shared migration already run in other environments.
2. Update `app/Models/Subject.php` fillable properties.
3. Update the validator in `app/Http/Controllers/SubjectController.php` (`validated(...)`).
4. Update `resources/views/subjects/form.blade.php` to display the field with `old(...)` and existing value.
5. Update show/list page(s) only if the field should be visible.
6. Write a feature test: valid link is stored; bad input rejected; unauthorized account gets 403; wrong-institution actor denied.
7. Run `php artisan migrate`, `php artisan test`, and `npm.cmd run build` if layout/JS changed.
8. Add migration and compatibility notes to the pull request.

**Do not blindly copy this pattern to a secret or personally sensitive field.** Validate, encrypt, authorize and limit access as required.

## 2. Example: adjust question-review workflow

Trace these files in order:

```text
routes/web.php
  -> QuestionController@submit or @review
  -> QuestionReviewController@index (queue)
  -> QuestionVersion model
  -> resources/views/questions/show.blade.php
  -> resources/views/questions/review-queue.blade.php
  -> tests/Feature/QuestionReviewQueueTest.php
  -> tests/Feature/RegressionSafeguardsTest.php
```

Preserve: submitted-only review, no self-review, rejection reason, atomic transition, and approved-version eligibility. Verify that updating a question cannot rewrite the historic version already snapshotted into a generated exam.

## 3. Example: add a new report page

- Add named GET route inside `auth` group in `routes/web.php`.
- Add controller in `app/Http/Controllers/` with `can('...')` **and** tenant restrictions.
- Query in an institution-scoped way (`AccessScope::restrict`).
- Return a new Blade view in `resources/views/reports/` (create directory when necessary).
- Use existing layout components/navigation conventions.
- Add visible menu link only for allowed roles, and test the backend direct URL for unauthorized users.
- Add a Feature test covering staff allowed, staff denied, cross-tenant denied, and student denied.

## 4. Example: changing grades

`GradeService.php` uses integer hundredths to compare `score / total` to thresholds; the currently coded bands are A 80+, B 70+, C 60+, D 50+, F below 50. If academic policy changes:

1. Obtain an approved grading policy (avoid silently changing meaning of historical published results).
2. Update `GradeService` and `tests/Unit/GradeServiceTest.php` at each exact boundary.
3. Decide whether old results should stay unchanged or require a versioned regrade/audit.
4. Test `ResultController@store/@update` and `OnlineExamController@completeGrading` paths.
5. Record the new effective date and policy in documentation.

## 5. Anti-patterns to avoid

- Do not put tenant-sensitive queries in a controller without institution checks.
- Do not rely on Blade nav visibility instead of server-side authorization.
- Do not mix submitted/reviewed question versions into editable drafts.
- Do not expose `exam_questions.answer_snapshot` to the student take view.
- Do not alter a published exam's snapshots by modifying base questions.
- Do not update the same student/exam result by accidentally inserting duplicates.
- Do not drop/deactivate foreign-key constraints just to make deletion succeed.
- Do not modify `vendor/`, `node_modules/`, or generated `public/build/` assets manually.
- Do not add business logic into the zero-byte `Actions` or `DTO` placeholders without documenting ownership and adding tests.
- Do not interpret long-standing project design documents as proof that a feature was shipped.

## 6. First-week junior developer exercise plan

| Day | Exercise | Evidence of completion |
|---|---|---|
| 1 | Install dependencies, migrate throwaway database, run route list/tests | Clean terminal log and environment checklist |
| 2 | Trace institution → subject → question create in code | Written call chain and model relationship diagram |
| 3 | Trace submitted → review → revised version; test self-review blocked | Automated HTTP test or documented repro |
| 4 | Trace blueprint → generation → issued snapshot → online attempt | Step-by-step walkthrough with database rows |
| 5 | Fix one small UI/validation defect with tests | PR, screenshots, tests, no security regression |

## 7. Recommended feature PR checklist

- [ ] Requirement and acceptance criteria documented.
- [ ] Affected route/controller/service/model/view listed.
- [ ] Database migration added if needed; destructive changes avoided.
- [ ] Input validation and output escaping reviewed.
- [ ] Role/permission and tenant isolation checked.
- [ ] Normal path, failure path, state transition and concurrency considered.
- [ ] Laravel tests passed in disposable DB, or failures disclosed.
- [ ] Frontend build succeeded, or failure disclosed.
- [ ] User-facing UI manually smoke-tested, or not-tested status disclosed.
- [ ] README/developer docs updated to match behavior.
- [ ] No secrets, credentials, logs, backups, or databases committed.
