# EQBEMS Admin Panel

This update adds a responsive Laravel Blade admin console using Bootstrap 5, Bootstrap Icons, DataTables, Toastr, SweetAlert2, and Chart.js.

## Included
- Admin dashboard with live user/institution counts and registration chart.
- Institution directory, searchable table, create/edit/view pages, status display, and confirmed delete action.
- User management CRUD pages (routes remain protected by the existing `role:Super Admin` middleware).
- Responsive navigation, account/profile shortcut, logout, validation feedback, toast notifications, and reusable confirmation dialogs.
- Honest placeholders for question bank, examinations, and results when their schema/modules are not present yet.

## Install / run
1. Extract the project and open the project directory.
2. Run `composer install` if dependencies are not installed.
3. Copy `.env.example` to `.env` if needed, configure the database, and run `php artisan key:generate` if the app key is missing.
4. Run `php artisan migrate`.
5. Ensure the signed-in admin has the Spatie role `Super Admin` for user management, and the appropriate `institution.view`, `institution.create`, `institution.update`, and `institution.delete` permissions for institution actions.
6. Run `php artisan optimize:clear` and `php artisan serve`.

The admin template loads Bootstrap/DataTables/Toastr/SweetAlert2/Chart.js from public CDNs, so an internet connection is required unless these assets are downloaded and self-hosted.

## Notes
- The existing project migrations currently implement users, permissions, and institutions. Question bank, exams, and results are shown as planned modules and are not represented by fake database data.
- Authorization is intentionally not bypassed. If institution pages return 403, assign the required permissions through your existing role/permission setup.
- Test all write actions against a development database before production deployment.
