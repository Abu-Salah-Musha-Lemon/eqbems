# Assumptions and Constraints

## Verified repository constraints

- The project uses Laravel 12 and PHP constraint `^8.2`; team guidance recommends PHP 8.3+.
- The Composer manifest includes Spatie Permission, Activitylog, Media Library, Backup, and Excel packages. Installing a package does not mean its feature is configured or tested.
- `.env.example` defaults to SQLite, database sessions/cache/queue, and log mail. MySQL is the selected production target in ADR-004.
- Current migrations include Laravel auth/cache/queue support, Spatie Permission tables, and `institutions`. The target academic/question/exam/result tables are not yet implemented.
- `institution.organization_id` is an indexed nullable integer with no currently migrated organizations table or foreign key. It is not sufficient to claim tenant isolation.
- Frontend dependencies and views are mixed: Bootstrap 5 dependency exists; Breeze/Tailwind assets remain; legacy theme assets are present.

## Assumptions pending confirmation

- Primary deployment initially serves one or more institutions from one application/database.
- MySQL 8.x/InnoDB is the production database target.
- Blade server-rendered pages are the first UI approach; a public API is not yet a required interface.
- Approved question versions and exam versions must remain historically immutable.
- UTC timestamps are stored and displayed using institution/user timezone configuration.

## Constraints

- Existing database rows may exist in developer environments; schema changes must be backward-safe and migration-based.
- SQLite tests are useful but do not replace MySQL integration tests.
- Exam answers and student records are sensitive and must not be used in sample seeders or public bug reports.
- Production deployment is blocked until access-control, secret-management, backup/restore, and audit requirements are verified.

## Open decisions log

| ID | Decision needed | Owner | Due gate |
|---|---|---|---|
| D-01 | Organization/institution tenancy model and uniqueness rules | Product + architecture | Before organization foreign keys/memberships |
| D-02 | Canonical permission vocabulary (`edit` vs `update`) | Architecture | Before RBAC feature tests |
| D-03 | Bootstrap/Tailwind migration boundary | UI owner | Before new screens proliferate |
| D-04 | Exam delivery in V1 or V2 | Product owner | Before exam-domain schema |
| D-05 | Audit log retention and file/database backup destinations | Operations + product | Before staging with real data |
