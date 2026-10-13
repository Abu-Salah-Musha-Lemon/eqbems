# Feature Catalog and Status Register

**Source rule:** status reflects the inspected repository, not the ambition of the product. “Present” means some source exists; it does not imply end-to-end verification.

| Feature ID | Capability | Current status | Release target | Gap / acceptance gate |
|---|---|---|---|---|
| AUTH-01 | Login/logout/password flows | Scaffolded; verify | Foundation | Run Breeze tests on clean install and verify mail/reset configuration |
| AUTH-02 | Registration policy | Scaffolded | Foundation | Decide whether public registration stays enabled |
| RBAC-01 | Permission tables/config | Present | Foundation | Migration/config consistency check |
| RBAC-02 | Role assignment on users | Blocked/incomplete | Foundation | Add `HasRoles`; add seeder/role tests |
| RBAC-03 | Institution permissions | Incomplete | Foundation | Align `institution.edit` versus `institution.update`; test denial/allow |
| DASH-01 | Dashboard routes/views | Partial | Foundation | Consolidate `/dashboard` and `/dashboard2`, fix view path, render real data or label placeholders |
| INST-01 | Institution schema/model | Present, needs review | Foundation | Schema/model/route binding alignment and migration test |
| INST-02 | Institution CRUD | In progress / not verified | Foundation | Service injection/pagination, complete views, policy for every action, tests |
| USER-01 | Admin user CRUD | Incomplete | Foundation/V1 | `UserController` and full workflow must be implemented and tested |
| ACAD-01 | Academic master data | Planned | V1 | Approved schema and CRUD/workflow specs required |
| QB-01 | Question authoring/search | Planned/scaffolding | V1 | Question schema/versioning/security tests required |
| QB-02 | Question review/approval | Planned | V1 | State machine, independent review rule, audit trail required |
| BP-01 | Blueprint rules | Planned | V1 candidate | Define feasibility and total-mark validation |
| EXAM-01 | Exam generation/snapshots | Planned | V1/V2 decision | Immutable version and reproducibility tests required |
| EXAM-02 | Candidate delivery | Planned | V2 pending scope | Decide delivery/security/access model |
| RES-01 | Marks, grading, publication | Planned | V2 | Grade rules, rounding, correction/audit requirements required |
| RPT-01 | Reports/exports | Planned | V2 | Permission-filtered query and export leakage tests |
| OPS-01 | Backup/restore | Package installed, process unverified | Pre-production | Configure backup destination and execute restore drill |

Statuses available: **Planned**, **In progress**, **Implemented (unverified)**, **Verified**, **Blocked**, **Deferred**. Only use Verified when tests and acceptance criteria pass and the command/environment is recorded.
