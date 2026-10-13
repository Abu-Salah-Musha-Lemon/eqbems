# Module Specification: Institution Management

**Current status:** Schema/model/service/request/controller scaffolding exists; end-to-end CRUD is not yet verified.

## Purpose

Maintain institution master records used to scope academic data and administration.

## Current schema

See `docs/03_DATABASE/DATABASE_DICTIONARY.md`. The `institutions` table has UUID, globally unique `code` (50 chars), `name`, contact/location fields, `logo`, boolean `status`, nullable indexed `organization_id`, actor foreign keys, timestamps, and soft deletes. There is no `organizations` table/FK in the current migrations.

## User stories

- As an authorized administrator, I can list/search institutions I am allowed to access.
- I can create an institution with a unique code and valid optional contact/logo fields.
- I can view/edit the institution and see validation errors without losing entered data.
- I can deactivate or soft-delete an institution according to policy.
- I cannot access an institution outside my authorized scope.
- A logo is served only from its intended visibility boundary.

## Current blockers

- Controller constructor injection is commented out.
- Controller calls `InstitutionService::paginate()`, but the service lacks this method.
- `show` view and create/edit form contents are missing/incomplete in the inspected tree.
- Duplicate institution request classes exist at two namespace paths.
- Permission seeders/policies disagree on `institution.edit` vs `institution.update`.
- `show` must authorize; every route must be scoped and tested.
- Service deletes the existing logo before it knows the replacement upload succeeded; file and DB operations need safer ordering/cleanup.
- `organization_id` is only a placeholder field until the tenancy design is approved.

## Data/validation rules

- Normalize code consistently (uppercase/trim if that is the business rule) and enforce uniqueness in DB.
- Decide whether soft-deleted institution codes can be reused; current global unique index says no.
- Validate email/URL/phone and image MIME/size.
- Keep actor fields server-assigned from the authenticated user; never trust submitted `created_by`/`updated_by` values.
- Avoid public exposure of confidential files; institution logo can be public only by explicit decision.

## Acceptance criteria

- Index, show, create, store, edit, update, delete and any restore/status flows work.
- Policy/request/middleware permissions align and fail closed.
- Search/pagination work with bounded page size and no N+1 query problem.
- Duplicate code and invalid upload are rejected.
- Cross-institution access is denied after tenant model is implemented.
- Soft delete/restore constraints, unique-code semantics, and file cleanup are covered by tests.
