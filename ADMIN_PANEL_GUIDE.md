# EQBEMS Admin Interface

The updated admin panel includes authenticated screens for Institutions, Subjects/Topics, Academic Years, Question Bank, Blueprints, Examinations, Results and Super Admin-only Users. Navigation links hide actions that the current role does not have permission to use; server-side controllers independently enforce those permissions and institution scope.

## Getting started

See [README.md](README.md) for complete Windows/Linux setup, initial admin account seeding (no default password), and a walkthrough from creating a subject to publishing a student's exam result.

The UI uses Bootstrap 5, Bootstrap Icons and optional CDN-hosted DataTables, Toastr and SweetAlert. Connectivity to those CDNs is required for all optional enhancements, although ordinary forms and server-side validation do not depend on JavaScript. Uploads use Laravel's `public` storage disk and require `php artisan storage:link`.

## Caveats

The original documentation described the question bank, exams and results as “coming soon”; this has changed in the included MVP code. Full Laravel tests and browser smoke tests are still required on a machine with dependencies installed. Do not deploy real candidate data without security review and backup/restore procedures.
