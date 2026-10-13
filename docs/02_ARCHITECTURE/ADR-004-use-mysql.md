# ADR-004: Use MySQL 8.x in Production

- **Status:** Accepted
- **Scope:** Persistent relational database

## Context

EQBEMS requires relational integrity and transactions across institution, academic, question, exam, and results data.

## Decision

Use MySQL 8.x with InnoDB and `utf8mb4` as the production target. SQLite may be used for fast local bootstrap and isolated test suites; MySQL integration tests are required before release.

## Consequences

- `.env.example` currently defaults to SQLite; contributors who need production-like behavior must configure MySQL explicitly.
- Do not assume SQLite tests validate MySQL collation, JSON, locking, migration, or query-plan behavior.
- Review migrations for engine-specific features and run migration/rollback rehearsals against an empty MySQL database.
