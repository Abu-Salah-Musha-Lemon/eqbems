# Implementation Roadmap

This is a dependency-based roadmap, not a calendar promise. Each phase exits only when its acceptance criteria pass.

## Phase 0 — Foundation hardening (first)

**Deliverables:** local setup guide, clean migrations, auth verification, RBAC integration, permission vocabulary, working institution CRUD, consolidated dashboard routes, tests/CI baseline, archive hygiene.

**Exit criteria:** clean install works; migration and test commands run; unauthorized requests are rejected; institution create/read/update/delete paths work; no test seeding blocker remains.

## Phase 1 — Institution and access model

- Decide whether the application is single-tenant, institution-scoped, or organization→institution multi-tenant.
- Implement membership/scope model as approved; do not rely on the current unreferenced `organization_id` field.
- Complete account lifecycle, role assignment, invitation/registration rules, and audit trails.

## Phase 2 — Academic structure

- Agree parent-child structure and lifecycle/deletion constraints.
- Add reviewed migrations, models/relations, requests, policies, services/actions as needed, screens, seeders/factories, and feature tests.
- Validate institution scoping and duplicate/overlap rules.

## Phase 3 — Question bank

- Add question classification, body/options, versioning, media metadata, review/approval states, search/filtering, and audit history.
- Make answer-key handling private by design.
- Validate history and approval conflict rules.

## Phase 4 — Blueprint and generation

- Finalize blueprint constraints and generation failure explanations.
- Implement eligible-question selection and immutable generation snapshots.
- Test mark totals, classification distributions, repeat/duplicate behavior, and reproducibility.

## Phase 5 — Examination delivery and results (V2 scope gate)

- Decide candidate delivery and access states.
- Build exam versions, candidate rosters/access rules, secure delivery where approved.
- Add marks, grade scales, review, result publication/correction, and controlled exports.

## Phase 6 — Production readiness

- Security review, MySQL integration tests, load/query tests, backup/restore drill, monitoring/alerting, data retention review, deployment rehearsal, rollback plan, and operator training.

## Working agreement

Do not start a dependent phase while its core data/security decisions remain unresolved. Update the Feature Catalog and Changelog as each deliverable moves from Planned to Implemented to Verified.
