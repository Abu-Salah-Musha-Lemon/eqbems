# Risk Register

Scores are qualitative and must be revisited after mitigations.

| ID | Risk | Likelihood / impact | Mitigation | Owner / status |
|---|---|---|---|---|
| R-01 | Permission integration is incomplete, causing failed seeding or incorrect access | High / Critical | Add `HasRoles`, align permission names, write allow/deny tests | Development / Open |
| R-02 | Institution CRUD is not end-to-end wired | High / High | Restore service injection, implement pagination, complete views, feature tests | Development / Open |
| R-03 | Planned database is mistaken for deployed schema | High / High | Label current vs target schema; create migrations only after reviewed decisions | Architecture / Mitigated by docs, ongoing |
| R-04 | Tenant isolation not defined or enforced | High / Critical | Decide tenancy model; scope queries/policies; cross-tenant tests | Product + architecture / Open |
| R-05 | Answer keys leak to candidates through response, export, log, or cache | Medium / Critical | Separate query/DTO/access path; leakage tests; approval states | Security / Future gate |
| R-06 | Sample admin credentials are reused outside local development | Medium / Critical | Replace predictable seeder bootstrap before staging/production | Development / Open |
| R-07 | Secrets or runtime artifacts included in archives | Medium / High | Distribution ignore/allow-list; archive inspection; secret scan | Operations / Open |
| R-08 | UI framework drift increases maintenance cost | High / Medium | Bootstrap 5 for new work; plan Breeze/legacy migration | UI owner / Open |
| R-09 | Tests pass only on SQLite but deployment uses MySQL | Medium / High | MySQL integration CI and staging migration rehearsal | QA / Open |
| R-10 | Exam/history rows are mutable after approval | Medium / Critical | Immutable versions/snapshots; database/application constraints and tests | Architecture / Planned |
| R-11 | Backup exists but restoration is not possible | Medium / Critical | Encrypted off-host backups and scheduled restore drill | Operations / Open |
| R-12 | Scope expands faster than core workflows stabilize | High / Medium | Milestone gates, owner-approved changes, evidence-based feature catalog | Product / Ongoing |
