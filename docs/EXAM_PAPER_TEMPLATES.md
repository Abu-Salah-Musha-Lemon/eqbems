# EQBEMS — Bangladesh-style Print & Online Examination Templates

## What is included

This update adapts the **layout and question format**, not the copyrighted questions, from the reference papers the user supplied:

1. Newton Publications HSC ICT test-paper PDF — creative/CQ stem (উদ্দীপক), ক/খ/গ/ঘ, 1+2+3+4 marks; separate objective paper.
2. HSC ICT MCQ reference PDF — compact numbered four-choice MCQ question sheet.
3. HSC Higher Mathematics CQ reference PDF — board-style questions and nested parts (scanned source; do not assume exact extractable text).
4. The provided `demo questions.docx` — institution/exam/class/subject heading, duration/full marks, candidate-friendly exam instructions and mixed question sections.

Printed papers include the **institution header, exam title/session, class/level, subject and optional code, duration, full marks, candidate name/roll spaces, instructions, questions, and page-friendly typesetting**. The printed paper never displays model answers. The browser's **Print / Save as PDF** produces an A4 PDF, without a new PHP PDF-generation package.

## Update an existing installation (Windows / XAMPP)

**Back up your MySQL database** before installing any code or migrating. Copy the files or extract the patch into the Laravel project root alongside `artisan`. The new migration is additive; do **not** run `migrate:fresh`.

```powershell
php artisan optimize:clear
php artisan migrate
composer dump-autoload
php artisan serve
```

There are now **7 migration files**. The seventh migration adds CQ details, paper metadata, online settings, attempt and response tables. Your six previously migrated files remain unchanged.

Optionally add original ICT demonstration questions by running:

```powershell
php artisan db:seed --class=BoardFormatDemoSeeder
```

This seeder uses the Laravel **Question/QuestionVersion/Subject/Topic factories** and adds 1 ICT subject, 1 topic, 2 original 10-mark creative questions and 4 original MCQs to the existing demo institution. It is repeatable; it does not delete the existing 40 demonstration questions. Demo questions are auto-approved for local testing only; have staff review real exam items separately.

## Print question-paper workflow

1. Sign in as **Super Admin** or **Exam Controller** to create/edit the layout. Teachers with `exam.view` may print.
2. Create questions in **Question bank**. Select **Creative/CQ** for 1+2+3+4 marking: assessment type must be **Short answer**. Supply **stimulus**, four parts (ক/খ/গ/ঘ), and a model marking guide. All question versions must be approved before the exam can be generated.
3. Create a **Blueprint**. Select subject, question count, optional type `short` and format `creative` (CQ only), or type `mcq` and format `standard` (MCQ only).
4. Click **Generate exam**. Review the snapshot in **Examinations**.
5. Use **Paper settings** to choose standard / compact MCQ / CQ layout, and edit the header (session, class, paper code, instructions).
6. Choose **Print / Save PDF** on the exam page. In the opened tab, click **Print / Save as PDF**. Choose A4 paper; allow background graphics only if desired.
7. After confirming the paper, click **Issue paper**. The immutable question snapshots are fixed.

For the optional ICT samples, try these two blueprints:

| Blueprint | Question count | Type | Format | Print layout |
|---|---:|---|---|---|
| HSC ICT — Creative Practice | 2 | short | creative | Creative/CQ |
| HSC ICT — MCQ Practice | 4 | mcq | standard | Compact MCQ |

You can print draft papers for internal proofreading; **do not distribute drafts to students**.

## Online examination workflow

1. The examiner **issues** the paper and opens **Paper settings**.
2. Set **Allow online attempts = Yes** and, optionally, an opening/closing time.
3. A user assigned the **Student** role must belong to **the same institution** as the paper. Students click **My online exams** in the sidebar, then **Start exam**. Starting begins their timed attempt. One attempt is allowed per student/exam.
4. Students answer four-choice MCQs, True/False, free-text short questions, or the four separate CQ parts. **Save answers** preserves progress. **Submit final answers** is irreversible.
5. A timer is displayed, but **server time is authoritative**: when the deadline passes, only previously saved responses are submitted automatically on the next request.
6. Objective items are automatically marked using the immutable snapshot answer key. Written/CQ responses go to **Grade online attempts** for authorized markers, with separate CQ scoring limits **1 / 2 / 3 / 4**.
7. A fully graded attempt creates a **draft result**. An authorized examiner publishes the draft result in the existing **Results** module. Students can only see published results. Staff-only answer keys remain private.

## Boundaries / constraints

- This is an end-to-end **MVP for fixed-mark exams**. **Answer-any-N** choice rules, dynamic maximum marks, multi-section weights, proctoring, remote identity verification, file uploads, candidate scheduling/enrollment lists, automatic recovery of unsaved browser text, OCR/import of entire PDFs, math-equation editors, automatic marking of essay answers, and essay plagiarism checks are **not included**. For those assessment patterns, create separate paper sections or extend the schema and marking logic before real use.
- Grading is at the question level for standard short answers and at the **part level for CQ**. CQ total marks are fixed at **1+2+3+4=10** in this release.
- The printed layout is responsive and A4; use the browser's print-preview to verify pagination and Bengali/math glyphs on each workstation. The math reference is scanned, so its complex equation layout is *not* fully recreated.
- All answer keys are teacher/reviewer-only. Do not copy public-domain status or permissions from the examples: the original reference PDFs and DOCX were used as **format inspirations only** and are not redistributed in this project.
- To avoid user-role privilege escalation, enrollment/registration remains controlled by the administrator. Online students must have a real, authenticated account with the correct role and institution.
- Deployment needs HTTPS, secure sessions, backups, rate limiting, server timezone configuration, and a security/QA review. It has not been production certified.

## Verification

```powershell
php artisan migrate:status
php artisan test tests/Feature/ExamPaperTemplateTest.php
php artisan test
```

In environments without `vendor/` (including the source-only ZIP), run `composer install` after enabling the required GD and ZIP PHP extensions. On a Windows PowerShell machine that blocks `npm.ps1`, use `npm.cmd install` and `npm.cmd run build`.

The package was PHP syntax-checked in a Linux container. Full Laravel/DB feature tests require an installed `vendor/` directory and configured PHP/MySQL (they were not run as part of packaging).
