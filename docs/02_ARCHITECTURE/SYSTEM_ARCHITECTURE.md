# System Architecture

**Architecture direction:** Laravel modular monolith. **Current implementation:** conventional Laravel `app/`, `routes/`, `resources/`, and `database/` layout; fully isolated `modules/` source directories do not exist yet.

## Goals

- Keep a single deployable application and relational database while organizing domains around stable boundaries.
- Enforce security and business invariants at the server and database layers.
- Keep business transitions testable and avoid large controller methods.
- Use migrations, feature tests, and docs as a reproducible change history.

## Logical layers

```text
Browser
  -> routes/middleware
  -> authentication + authorization (policies/gates/permissions)
  -> Form Request (validation + request authorization)
  -> controller (HTTP orchestration)
  -> service/action (business workflow + transaction where required)
  -> Eloquent model/relationships
  -> migrations/database
```

Events, observers, queues, notifications, file storage, and exports are cross-cutting capabilities used only where the feature requires them. Audit logging must be intentional and tested for sensitive operations.

## Current code map

- `app/Http/Controllers/`: HTTP controllers, including institution/profile/dashboard controllers.
- `app/Http/Requests/`: Form Requests; institution request files currently exist in both top-level and nested paths and need consolidation.
- `app/Services/`: current service classes; InstitutionService exists but does not yet provide the paginate method used by controller; QuestionService is empty.
- `app/Actions/Institution/`: institution create/update/delete action classes exist, but usage should be reconciled with the service layer rather than duplicating behavior.
- `app/Models/`: Eloquent models.
- `app/Policies/`: resource policies.
- `database/migrations/`: current schema source of truth.
- `database/seeders/`: reference role/permission/admin seeders; current role assignment is blocked by missing `HasRoles` trait.
- `resources/views/`: Blade views; several institution views are empty or incomplete.
- `routes/`: web and Breeze auth routes.
- `tests/`: Laravel/Breeze scaffold tests and limited application-specific coverage.

## Module boundaries (target)

1. Identity & Access
2. Institution/Tenancy
3. Academic Structure
4. Question Bank
5. Blueprint & Generation
6. Examination Delivery
7. Results & Grading
8. Reporting & Export
9. Administration/Audit

Modules are logical boundaries; do not create microservices or separate databases without an approved ADR. Cross-module relationships use IDs and well-defined interfaces. Avoid circular service calls.

## Rules

- Authorization must occur before data is returned or mutated.
- Form Request authorization and policies must agree on permission vocabulary.
- Multi-row state transitions use DB transactions; external side effects (mail/files) need compensation/cleanup because DB transactions do not roll back filesystem changes.
- Use explicit query scopes for institution/tenant restrictions and test cross-scope access.
- The exact current schema is defined by migrations, not by target ERDs.
- Approved question versions and generated exam versions must be immutable at the application level and protected by tests.

## State and version boundaries

Mutable masters (institution, subject) may be edited under policy. Question content and approved exam snapshots require separate version/history records. Do not mutate an already-issued paper when an underlying reusable question changes.

## Deployment shape

Initial target is one Laravel application, one relational database, configured filesystem, queue worker for queued jobs, and scheduled tasks where enabled. Scale these components based on measured workload; do not assume Redis, object storage, or horizontal scaling is already configured.
