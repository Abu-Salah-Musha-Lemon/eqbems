# Product Requirements

**Status:** Baseline specification for planning. Features below are requirements until marked implemented and verified in the [Feature Catalog](FEATURE_CATALOG.md).

## Product outcomes

1. Maintain accurate institution and academic master data.
2. Enable a controlled, searchable, reusable question bank.
3. Allow exam controllers to build auditable blueprints and generate papers from eligible question versions.
4. Preserve exam-history integrity when source questions are edited later.
5. Support controlled marking, grade calculation, and publication in a later release.
6. Enforce authorization and audit significant actions.

## Functional requirements

### Identity and access

- PR-AUTH-01: Users can register only if registration is intentionally enabled by project policy; production may require administrator provisioning.
- PR-AUTH-02: Users can log in, log out, change passwords, and use a secure password-reset process.
- PR-AUTH-03: Role and permission assignment is restricted to authorized administrators.
- PR-AUTH-04: Protected actions check authorization on the server, not only in navigation/UI.
- PR-AUTH-05: Account deactivation prevents new sessions and preserves historical actor references.

### Institution and tenancy

- PR-INS-01: Authorized staff can create, view, edit, deactivate, and (where policy allows) restore institutions.
- PR-INS-02: Institution code uniqueness follows the approved tenancy decision.
- PR-INS-03: Uploaded logos have validated file type/size and controlled storage paths.
- PR-INS-04: Cross-institution data reads/writes are denied unless explicitly permitted.

### Academic structure

- PR-ACAD-01: Institutions can define academic years/periods and a hierarchical structure such as faculty, department, program, semester, subject, chapter, and topic where applicable.
- PR-ACAD-02: Every academic entity has a clear owner/scope and lifecycle rules.
- PR-ACAD-03: Referential integrity prevents deleting parent data that active child records require.

### Question bank

- PR-QB-01: Questions carry type, difficulty, subject/topic mapping, author, lifecycle status, and timestamps.
- PR-QB-02: Question body, options, answer/explanation, and media are versioned so approved historical papers remain reproducible.
- PR-QB-03: Approval decisions include reviewer, time, outcome, and comments/reason.
- PR-QB-04: Search and filters use only authorized/in-scope questions.
- PR-QB-05: Correct answers are stored and served separately from candidate-facing question content.

### Blueprint and exam generation

- PR-EXAM-01: A blueprint defines required counts/marks by agreed classifications and total marks/time limits.
- PR-EXAM-02: Generation validates rule feasibility before producing a paper.
- PR-EXAM-03: Each generation run records its inputs and the selected immutable question versions.
- PR-EXAM-04: Approved exam content is immutable; corrections create a new version and an audit event.
- PR-EXAM-05: Access to exam content and answer keys is role- and state-dependent.

### Results (later release)

- PR-RES-01: Marks use decimal types and explicit rounding rules.
- PR-RES-02: Marks and grades have controlled state transitions and audit history.
- PR-RES-03: Published results are visible only to authorized users/candidates.
- PR-RES-04: Corrections to published results require a reason and preserve the prior value.

## Nonfunctional requirements

| ID | Requirement | Verification approach |
|---|---|---|
| NFR-01 Security | Deny-by-default authorization for sensitive operations | Route/policy tests, security review |
| NFR-02 Integrity | Enforce relationships and uniqueness in DB as well as app logic | Migration review and DB constraint tests |
| NFR-03 Auditability | Critical transitions record actor/time/target/outcome | Feature tests and audit review |
| NFR-04 Availability | Initial target to be agreed before production | Hosting SLO and monitoring design |
| NFR-05 Performance | Establish target with realistic dataset; avoid unsupported numeric promises | Load/query-plan tests |
| NFR-06 Maintainability | Laravel conventions, small modules, ADRs, automated test baseline | Code review and CI |
| NFR-07 Accessibility | Keyboard navigation, labels, error associations, contrast, focus states | Manual and automated checks |
| NFR-08 Portability | Migrations and deployment are reproducible; SQLite tests do not replace MySQL validation | Clean install/CI run |
| NFR-09 Privacy | Collect minimum personal data and define retention | Data inventory and policy review |
| NFR-10 Recovery | Database/filesystem backup with tested restoration | Restore drill |

## Out of scope unless separately approved

AI-generated question content, remote proctoring/biometrics, payments, full LMS content delivery, mobile native apps, and microservices. Each adds requirements, cost, privacy, or operational complexity and must have an approved scope change.
