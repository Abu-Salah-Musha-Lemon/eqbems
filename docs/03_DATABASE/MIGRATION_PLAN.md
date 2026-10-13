# Database Migration Plan

## Principles

- `database/migrations/` is the current physical schema source of truth.
- Add forward migrations for schema changes after a migration has been shared or deployed.
- Review data impact, constraints, indexes, rollback safety, and environment differences before merging.
- Migrations should be deterministic and should not depend on production data or external services.
- Test fresh install and upgrade from the previous schema on SQLite and MySQL where relevant.
- Avoid destructive `migrate:fresh` on any environment with data that matters.

## Current migration-backed baseline

1. Laravel users/password resets/sessions.
2. Laravel cache tables.
3. Laravel database queue tables.
4. Spatie Permission tables.
5. Institutions.

See the migrations themselves and `DATABASE_DICTIONARY.md` for current fields/constraints.

## Proposed implementation order

The target order must be revisited after the tenancy decision:

1. Resolve organizations/institutions/memberships and actor foreign keys.
2. Academic periods and academic hierarchy.
3. Subject/topic taxonomy and reusable lookup entities.
4. Question master, immutable question versions, options/answers, media metadata, tags/topics, review history.
5. Blueprint and blueprint rules.
6. Generation runs and immutable exam/version/question snapshots.
7. Candidate/exam assignments and delivery/session data if candidate delivery is in scope.
8. Marking, grading scheme, result, publication/correction history.
9. Reporting-specific views/indexes only after measured queries justify them.

## Before each migration PR

- [ ] Requirements and relationship changes are approved.
- [ ] `DATABASE_DESIGN.md`, dictionary, ERD, and module spec are updated.
- [ ] Foreign-key delete behavior is explicit (`CASCADE`, `RESTRICT`, or `SET NULL`).
- [ ] Unique rules account for soft deletes and tenant scope.
- [ ] Indexes support expected joins/filters and naming limits.
- [ ] Sensitive answer/result columns are not mixed into general candidate-facing reads.
- [ ] `up()` and intended rollback behavior have tests or a safe manual plan.
- [ ] Fresh migration passes against SQLite and MySQL where applicable.
- [ ] No existing migration has been modified to disguise a schema change.

## Production rollout

1. Take and verify a backup.
2. Rehearse migration against a recent sanitized production-like copy.
3. Estimate lock/downtime/data backfill impact.
4. Deploy application compatibility code before a breaking migration when needed.
5. Run migrations with the release process (not arbitrary web requests).
6. Check `php artisan migrate:status`, smoke-test key routes, and inspect logs.
7. Follow an explicit rollback/forward-fix plan; never assume reversing a data migration restores lost values.
