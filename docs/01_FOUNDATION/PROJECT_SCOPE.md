# Project Scope and Release Boundaries

## Current implementation milestone: Foundation hardening

The current milestone is not a product release. Its goal is to make the foundation reproducible and reliable before adding more domain features.

**Included:** local setup, auth verification, RBAC repair, institution CRUD, consistent routing/views, first application-specific test suite, accurate docs, and migration validation on SQLite plus MySQL.

**Exit criteria:** all critical blockers in `docs/00_START_HERE.md` are resolved or explicitly accepted, and CI can reproduce migrations/tests/build from a clean checkout.

## Planned product releases

### V1 — Governed reference data and question bank

- Account, role, permission, and institution administration.
- Approved organization/institution scoping strategy.
- Academic structure and classification.
- Question authoring, versioning, review/approval, search, tags/media.
- Blueprint management and a controlled draft generation workflow, if feasible within the V1 acceptance criteria.
- Activity/audit records for sensitive transitions.

### V2 — Examination and results lifecycle

- Exam scheduling, generated/approved paper versions, candidate lists/access if selected by stakeholders.
- Mark entry, grading, review, result publication/correction workflows.
- Reports and exports with authorization checks.

### Future / decision-based

- Advanced analytics, external integrations, mobile apps, AI assistance, proctoring, multi-region SaaS, and service extraction.

## Explicit exclusions for initial releases

- Real-time proctoring and biometric identity checks.
- Public unauthenticated question/exam APIs.
- Direct production use of the sample admin credentials.
- Claims of full compliance with any legal/privacy framework before a review is done.

## Scope control

A new feature requires a product owner, user problem, acceptance criteria, data/security impact, release placement, test plan, and documentation changes. “The table is in the design document” is not sufficient evidence that a feature belongs in the current sprint.
