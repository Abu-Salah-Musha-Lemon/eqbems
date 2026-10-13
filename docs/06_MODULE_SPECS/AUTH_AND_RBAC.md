# Module Specification: Authentication and RBAC

**Current status:** Authentication scaffolding and permission package/schema exist; RBAC integration is incomplete.

## Responsibilities

- Authenticate users and manage session/password lifecycle.
- Establish account status and registration/provisioning policy.
- Assign roles and check permissions.
- Provide auditable, least-privilege administrative operations.
- Supply the actor identity and scope consumed by all domain policies.

## Current repository facts and blockers

- Laravel Breeze auth routes/controllers/tests exist.
- Spatie Permission dependency, config, table migration, role and permission seeders exist.
- `App\Models\User` does not include `HasRoles`, but `AdminUserSeeder` calls `assignRole()`.
- Seeded permission `institution.edit` does not match checks for `institution.update`.
- Roles are seeded, but the current seeders do not establish a complete, tested role-to-permission matrix.
- `UserController.php` is empty despite the resource route declaration.

## Required implementation sequence

1. Decide public registration vs admin-provisioned accounts.
2. Add the correct Spatie trait to the user model and test role APIs.
3. Create one permission vocabulary and seed it idempotently.
4. Assign explicit permission sets to each role via seeder or administration workflow.
5. Restrict role/permission management to a small set of privileged roles.
6. Add account activation/deactivation and secure bootstrap-admin provisioning.
7. Add policies/middleware to all relevant routes and authorizations to every controller action.
8. Add institution membership/scope as approved; global roles alone are insufficient for tenant isolation.
9. Record role/permission changes in the audit trail.

## Acceptance criteria

- Fresh migration + seed run is repeatable and succeeds.
- Unauthorized users cannot view, create, update, delete, restore, or administer protected resources.
- Changing a user's role changes effective access consistently after cache invalidation.
- User from one institution cannot access another institution's resources by changing an ID/UUID.
- No production default password is used.
- Password/reset/session/security event behavior is tested and configured for production.
