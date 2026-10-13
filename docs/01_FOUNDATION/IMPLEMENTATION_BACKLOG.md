# Initial Implementation Backlog

This backlog is ordered by dependency and risk. Tasks are proposed work, not a claim that they are already in progress. Convert them into tracker issues if the project uses an issue tracker.

## P0 — Establish a reproducible baseline

### EQB-001: Validate clean local setup

- **Goal:** A developer can install dependencies, configure SQLite, migrate, build frontend assets, and run the app using the README.
- **Acceptance:** the exact steps succeed from a clean checkout; record PHP/Composer/Node versions and commands; no `.env`, logs, or database data are committed.
- **Dependencies:** none.

### EQB-002: Repair RBAC user integration

- **Goal:** Spatie role operations work on `User`.
- **Tasks:** add `HasRoles`; select one canonical permission vocabulary; align seeders, policies, Form Requests, middleware, navigation, and tests; verify package cache reset behavior.
- **Acceptance:** fresh `migrate` + seed succeeds; seeded roles/permissions are deterministic; allowed action succeeds and denied action is rejected by server-side checks.
- **Dependencies:** EQB-001.

### EQB-003: Define access scope / tenancy model

- **Goal:** Decide whether deployment is single-tenant, institution-scoped, or organization-to-institution multi-tenant.
- **Tasks:** specify memberships, data ownership, unique codes, super-admin bypass, cross-scope queries, and data deletion behavior. Decide whether `organization_id` remains, gets an `organizations` FK/table, or is removed before schema expansion.
- **Acceptance:** approved ADR, updated ERD/data dictionary, and cross-scope authorization plan.
- **Dependencies:** product owner + architecture review.

### EQB-004: Fix and test institution CRUD

- **Goal:** Working, authorized institution CRUD.
- **Tasks:** restore controller service injection; implement pagination; reconcile service/action duplication; consolidate duplicate Form Requests; complete index/create/show/edit views; align route names and authorization; safely handle logo replacement and deletion; set actor IDs server-side.
- **Acceptance:** feature tests cover index/show/create/store/edit/update/delete and invalid/unauthorized cases; route/view paths are consistent; the test suite passes.
- **Dependencies:** EQB-002 and EQB-003 where tenant scope affects records.

### EQB-005: Consolidate dashboard routes/views

- **Goal:** One canonical dashboard route and view.
- **Tasks:** choose `/dashboard` name/path; remove or redirect duplicate `/dashboard2`; decide verified-email requirement; distinguish real metrics from placeholder cards.
- **Acceptance:** intended route is authenticated, view resolves, all displayed metrics are backed by authorized queries, and tests document access rules.
- **Dependencies:** EQB-002.

### EQB-006: Establish CI/test baseline

- **Goal:** repeatable checks on clean branches.
- **Tasks:** run tests, Pint, frontend build; add MySQL migration/integration job before production release; record known test baseline.
- **Acceptance:** CI executes and fails the build on required check failures; skips are explicit and justified.
- **Dependencies:** EQB-001.

## P1 — Build product data foundations

### EQB-007: Approve academic data model

- **Goal:** Resolve academic hierarchy names, parent relationships, institution scope, date/overlap rules, and deletion semantics.
- **Acceptance:** updated requirements, glossary, ADR (if needed), ERD, migration plan, and reviewed migrations.

### EQB-008: Implement academic structure vertical slice

- **Goal:** CRUD a first selected academic entity with scope/security/tests, then extend incrementally.
- **Acceptance:** migration, model relationships, validation, policy, UI, tests, and docs agree. Avoid generating every planned table without working workflows.
- **Dependencies:** EQB-003 and EQB-007.

### EQB-009: Approve question versioning and review rules

- **Goal:** Define question types, answer structure, immutable versions, approval transitions, self-approval, taxonomy, tags/media, and visibility.
- **Acceptance:** state diagram, versioning rules, security review, ERD and tests drafted before schema implementation.

### EQB-010: Implement question-bank vertical slice

- **Goal:** Create, submit, review, approve, version, and search question content.
- **Acceptance:** authorized workflow tests pass; ineligible/draft content cannot enter generation; answer keys are excluded from candidate-facing representations.
- **Dependencies:** EQB-008 and EQB-009.

## P2 — Exam and result lifecycle

### EQB-011: Approve blueprint/generation constraints

- **Goal:** Specify totals, type/topic/difficulty distributions, selection/reuse, randomness, feasibility, and reproducibility.
- **Acceptance:** rules and edge cases reviewed; generation design and tests define failure explanations.

### EQB-012: Build immutable exam generation

- **Goal:** Generate a reviewable paper from approved question versions.
- **Acceptance:** stored generation run and exact question-version snapshot; later edits cannot mutate issued paper; answer-key leakage tests pass.
- **Dependencies:** EQB-010 and EQB-011.

### EQB-013: Approve and implement results workflow

- **Goal:** Define marking, grade boundaries, rounding, publication, recheck, and correction.
- **Acceptance:** fixed-decimal calculations and all transitions tested; corrections preserve history; candidate reads are scoped.
- **Dependencies:** EQB-012 and product V2 scope approval.

## P3 — Production readiness

### EQB-014: Security and privacy review

- Threat model, least-privilege roles, upload/export checks, dependency review, secret handling, retention, and incident process.

### EQB-015: Backup/restore and deployment rehearsal

- MySQL upgrade rehearsal, off-host encrypted backups, restore drill, monitoring, health checks, rollback/forward-fix plan, smoke tests.

### EQB-016: Pilot acceptance

- Define pilot cohort, training, success metrics, support process, issue triage, and sign-off criteria.

## Work policy

A task is complete only when its acceptance criteria pass and evidence is recorded. Update the Feature Catalog, Changelog, and relevant module/database docs as part of the task.
