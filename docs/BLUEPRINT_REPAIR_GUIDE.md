# EQBEMS — Blueprint Repair and Usage Guide

This patch corrects usability and validation problems in the **Blueprints** section of the Laravel 12 project.

## Problems addressed

1. **Blueprints were not editable.** You can now open a saved blueprint, change its title, subject (within the same institution), question count, duration, type, difficulty, and paper format.
2. **Generate Exam was offered with an insufficient question pool.** The index now displays available/required approved questions and disables the generate button if the selection cannot be fulfilled. The server independently revalidates this on generation.
3. **Filtered availability was unclear.** The create/edit form now checks the live matching count whenever subject, type, difficulty, or format changes.
4. **Filters could not be reset when editing.** Clearing a filter correctly saves `NULL`, which means “any”.
5. **Cross-institution subject reassignment.** Existing blueprints cannot be moved between institutions, and every subject and blueprint endpoint uses institution authorization.
6. **Separate query implementations drifted.** The UI and generator now rely on the same approved-version query in `BlueprintPoolService`.

## Install the patch safely on Windows (PowerShell)

1. Back up the project folder and export your current `eqbems` MySQL database via phpMyAdmin.
2. Extract **EQBEMS_Blueprints_Fix_Patch.zip** into the Laravel project root (the folder containing `artisan`), preserving folders. Allow overwrite of matching project source files. **Do not overwrite `.env`**, and do not copy the outer `eqbems-main` folder from the full ZIP into your current root.
3. Stop the `php artisan serve` process with Ctrl+C.
4. Run:

   ```powershell
   php artisan optimize:clear
   php artisan migrate:status
   php artisan migrate
   php artisan route:list --name=blueprints
   php artisan serve
   ```

5. Sign in with **Exam Controller** or **Super Admin**, navigate to **Blueprints**, and create/edit a blueprint. Use `npm.cmd run build` only if your frontend assets require rebuilding (this patch changes Blade views rather than Vite assets).

### Migration count

The full print/online exam project should have **7 migration files**. The last one, `2026_10_20_000002_create_online_exam_and_paper_format_tables.php`, adds `format` to `blueprints` and `question_versions` and supports the online exam tables. **This repair adds no migration.** If your MySQL error says `Unknown column 'format'`, you probably have not applied the previous print/online-exam migration yet. Run `php artisan migrate` against the correct database.

### Creating an exam from a blueprint

1. Create or seed your subjects and questions.
2. Ensure the question versions have `status = approved` (draft/submitted questions do not count).
3. Set blueprint **Subject**, **Question Type**, **Format**, **Difficulty**, **Question Count**, and **Duration**.
4. The live count previews eligible questions; the main Blueprint list shows the exact count for each stored blueprint.
5. Once **Approved pool** meets **Requested**, use **Generate exam**.
6. Verify the draft paper and then **Issue** it from the Exams section.

**Special case: Creative/CQ.** Select **Short Answer** or **All types**, and **Creative/CQ** format. Both a question's type and its format are filters. If `format = creative` but all questions are `standard`, the eligible count is zero by design.

**Approval note:** Sample questions seeded for demonstration might be marked approved for testing. Do not use them in real assessments without human review.

## Troubleshooting

| Symptom | Likely reason | Fix |
| --- | --- | --- |
| Blueprints list is empty | No blueprints saved for your institution | Create one with an authorized role |
| Subjects dropdown empty | No subject associated with your institution | Create subjects or seed demo subjects; confirm your user's `institution_id` |
| Approved pool shows 0 | No approved matching questions | Approve suitable questions; relax type/format/difficulty filters |
| `Unknown column 'format'` | Previous online-exam migration pending | `php artisan migrate` and verify `.env` database |
| 403 Forbidden | Role has no permission or cross-institution access attempted | Use Exam Controller/authorized role; check institution assignment |
| Cannot delete blueprint | Existing generated exams reference it | Keep for historical integrity; edit for future paper generation |
| `php artisan migrate`: Nothing to migrate | All migration files already recorded | This patch has no new migration; check `migrate:status` |
| Cannot generate | Pool became too small or filters mismatch | See index count; update blueprint or approve additional questions |

## Tests

```powershell
php artisan test tests/Feature/BlueprintRepairTest.php
php artisan test tests/Feature/DomainWorkflowTest.php
php artisan test tests/Feature/ExamPaperTemplateTest.php
```

The tests require a configured Laravel installation, Composer's `vendor/` tree, and an available test database driver. **Do not use `php artisan migrate:fresh` against your existing data.**

## Known limits

This fix does not implement complex blueprint sections (e.g., 10 MCQ + 3 creative parts in the same paper), topic weightings, or multi-section marks distribution. Those would require a separate planning feature and possibly schema changes.
