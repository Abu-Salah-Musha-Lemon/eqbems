# Development Workflow

## Standard feature lifecycle

1. Confirm the user problem and acceptance criteria in the product/feature docs.
2. Check architecture, tenancy, data-classification, and security impact.
3. Update the relevant ADR first if a significant design choice changes.
4. Plan schema and migration impact before coding dependent behavior.
5. Implement the smallest vertical slice: migration/model, validation, policy, behavior, UI/interface, tests, docs.
6. Run tests/style/frontend build and review migration behavior on a clean database.
7. Update feature status based on evidence and add a changelog entry for meaningful changes.
8. Review archive/secrets/log changes before merging.

## Branch and review guidance

Use focused branches. Avoid combining unrelated refactors and features. Code review must consider authorization, cross-institution scope, query complexity, data retention, and rollback/deployment effects.

## Migration discipline

- Never edit a migration that may have been run in another environment to represent a new schema change.
- Use forward migrations, and coordinate data backfills for existing rows.
- Avoid destructive rollback assumptions.
- Run `php artisan migrate:status` and validate new setup instructions.

## Documentation update matrix

| Change | Docs to update |
|---|---|
| Business workflow | BRD, product requirements, feature catalog, module spec |
| New/changed table | Database design, dictionary, ERD, migration plan, module spec |
| Permission/role | Stakeholders, RBAC spec, security docs, tests |
| Architecture choice | New/updated ADR, architecture map, folder guide |
| Local tooling | README, local setup, CI/test docs |
| Production operation | Deployment, backup/recovery, observability, security |

## “Done” means

Acceptance criteria pass, tests are included, authorization is enforced, migrations are repeatable, UI errors/loading/empty states are considered, docs match the code, and review feedback is resolved. If part is incomplete, call it In progress and record the remaining work.
