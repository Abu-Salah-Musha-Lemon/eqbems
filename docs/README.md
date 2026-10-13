# Documentation Index

This documentation set separates product requirements, architecture choices, actual implementation, and the target design. Update it in the same pull request as relevant code/schema changes.

## Start and orient

- [Start Here](00_START_HERE.md) — repository status, known blockers, recommended reading order.
- [Project Overview](01_FOUNDATION/01_PROJECT_OVERVIEW.md) — product purpose and actors.
- [Product Requirements](01_FOUNDATION/PRODUCT_REQUIREMENTS.md) — functional and nonfunctional requirements.
- [Project Scope](01_FOUNDATION/PROJECT_SCOPE.md) and [Roadmap](01_FOUNDATION/ROADMAP.md) — releases and sequence.
- [Feature Catalog](01_FOUNDATION/FEATURE_CATALOG.md) — status register; statuses must follow evidence.
- [Initial Implementation Backlog](01_FOUNDATION/IMPLEMENTATION_BACKLOG.md) — ordered tasks with acceptance criteria.

## Architecture

- [System Architecture](02_ARCHITECTURE/SYSTEM_ARCHITECTURE.md)
- [Folder Structure](02_ARCHITECTURE/FOLDER_STRUCTURE.md)
- [Security Architecture](02_ARCHITECTURE/SECURITY_ARCHITECTURE.md)
- [Module Dependency Map](02_ARCHITECTURE/MODULE_DEPENDENCY_MAP.md)
- [Architecture Decision Records](02_ARCHITECTURE/ARCHITECTURE_DECISION_RECORDS.md)

## Database

- [Database Design](03_DATABASE/DATABASE_DESIGN.md) — full logical design, explicitly distinguishing implemented and proposed tables.
- [Database Dictionary](03_DATABASE/DATABASE_DICTIONARY.md) — current migration-backed schema.
- [ERD](03_DATABASE/ERD.md) — current and target relationship diagrams.
- [Migration Plan](03_DATABASE/MIGRATION_PLAN.md) — sequence and safety rules.

## Development and operations

- [Local Setup](04_DEVELOPMENT/LOCAL_SETUP.md)
- [Testing Strategy](04_DEVELOPMENT/TESTING.md)
- [Development Workflow](04_DEVELOPMENT/DEVELOPMENT_WORKFLOW.md)
- [Coding Guidelines](04_DEVELOPMENT/CODING_GUIDELINES.md)
- [Deployment](05_OPERATIONS/DEPLOYMENT.md)
- [Backup and Recovery](05_OPERATIONS/BACKUP_AND_RECOVERY.md)
- [Observability](05_OPERATIONS/OBSERVABILITY.md)

## Module specifications

- [Authentication and RBAC](06_MODULE_SPECS/AUTH_AND_RBAC.md)
- [Institution Module](06_MODULE_SPECS/INSTITUTION_MODULE.md)
- [Academic Module](06_MODULE_SPECS/ACADEMIC_MODULE.md)
- [Question Bank Module](06_MODULE_SPECS/QUESTION_BANK_MODULE.md)
- [Examination Module](06_MODULE_SPECS/EXAMINATION_MODULE.md)
- [Results Module](06_MODULE_SPECS/RESULTS_MODULE.md)

## Document governance

1. Source code and migrations describe what exists; these documents describe requirements and decisions.
2. Never use a planned schema or feature list as evidence of implementation.
3. A feature status becomes **Verified** only after repeatable tests and acceptance checks pass.
4. ADRs record important decisions and must be changed by adding a new ADR when an accepted decision is reversed.
5. Record unknowns and decisions rather than silently inventing requirements.
