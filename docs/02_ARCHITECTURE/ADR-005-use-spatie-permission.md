# ADR-005: Use Spatie Laravel Permission

- **Status:** Accepted; integration incomplete
- **Scope:** Role/permission storage and checks

## Context

The product needs named roles, granular permissions, and framework middleware/policy integration.

## Decision

Use `spatie/laravel-permission` for role and permission persistence and assign/check permissions through the supported model trait and Laravel policies. Policies remain responsible for resource-level and institution-scope rules.

## Consequences

- The `User` model must use `Spatie\Permission\Traits\HasRoles` before calling role APIs such as `assignRole()`.
- Seeded roles do not gain useful access until permissions are assigned to roles and tested.
- Permission names must be canonical. Current code conflicts between `institution.edit` and `institution.update`; resolve before using RBAC.
- The current config has teams disabled. Institution-specific/organization-specific roles require an explicit teams/membership design decision; current global roles do not provide tenant isolation.
