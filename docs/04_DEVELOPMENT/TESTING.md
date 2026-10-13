# Testing Strategy

## Purpose

Tests are release evidence. A feature must not be marked Verified because a page renders once or because a test class exists.

## Current state

The repository contains Laravel/Pest and Breeze example tests, including authentication/profile flows. Application-specific tests for RBAC and institution CRUD are not yet sufficient. Record actual baseline results before treating failures as regressions or as known blockers.

## Test layers

### Unit tests

Test pure business rules such as blueprint feasibility, mark calculations, grade boundaries, question eligibility, and state transitions without requiring HTTP.

### Feature tests

Test HTTP routes and user-visible behavior with real Laravel middleware, Form Requests, policies, database migrations, and storage fakes. Cover authorized and unauthorized requests.

### Database integration tests

Test migrations, foreign keys, uniqueness, soft deletes, transactions, and concurrency-sensitive operations. Use SQLite in-memory for fast tests and MySQL in CI for production-like behavior.

### Security regression tests

Test scope isolation, answer-key secrecy, authorization on exports/downloads, role changes, and approved-version immutability.

## Minimum foundation test matrix

| Area | Positive case | Negative case |
|---|---|---|
| Authentication | Valid user can log in and log out | Invalid password and throttled attempts fail safely |
| Password reset | Valid flow uses configured token handling | Expired/invalid token is rejected |
| RBAC | Assigned permission allows intended action | Missing permission denies action |
| Institution | Authorized user creates/updates/deletes under rules | Duplicate code, invalid logo, and unauthorized action rejected |
| Tenant scope | User accesses assigned institution | Cross-institution ID substitution is denied |
| Seeders | Seeders are repeatable/idempotent | Missing trait/config or duplicate seed does not silently corrupt access |
| Migrations | Clean database migrates | Constraint violations are rejected |

## Commands

```bash
php artisan test
php artisan test --filter=Institution
./vendor/bin/pint --test
npm run build
```

For focused tests, add domain folders under `tests/Feature/` and `tests/Unit/`. Use factories for ordinary test data, and do not commit real student/exam data.

## Test quality rules

- Assert HTTP status and persisted side effects, not only that a view exists.
- Assert denied access and data leakage cases explicitly.
- Use database/storage/mail fakes where appropriate.
- Keep tests deterministic; avoid external services and current-time assumptions unless frozen.
- Do not disable authorization middleware globally to make a test pass.
- Document environment and exact command for any skipped or blocked test.

## Release gate

CI should run dependency validation, tests, code style, static analysis if configured, and frontend build. MySQL migration/integration checks are required before production release.
