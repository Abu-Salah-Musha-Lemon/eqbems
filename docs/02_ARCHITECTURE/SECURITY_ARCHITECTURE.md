# Security Architecture

**Read with [SECURITY.md](../../SECURITY.md).** This document defines intended controls; the current implementation does not yet satisfy all of them.

## Trust boundaries

- Browser/client input is untrusted.
- Authenticated users are not automatically authorized to every record or institution.
- Uploaded documents/images and exported files are untrusted/high sensitivity.
- Question answers, unreleased exams, student submissions, grades, and audit details are sensitive.
- Operators with database/filesystem access are privileged and must be restricted and audited.

## Authentication

Use Laravel's configured guard and throttling. Decide public registration policy before production. Require verified identity where the workflow needs it. Passwords are hashed using Laravel hashing; never store plaintext. Password reset must use a configured delivery channel and safe expiry/throttling.

## Authorization model

- RBAC (roles/permissions) describes broad action capability.
- Policies and explicit scope checks enforce resource-level access.
- Every read, mutation, download, export, and state transition must be authorized server-side.
- A role name is not a substitute for a permission matrix.
- Institution/organization isolation must be applied to query construction as well as policies; do not load cross-tenant data and rely only on hiding fields.

**Current blocker:** `User` lacks Spatie `HasRoles`, and `institution.edit`/`institution.update` permission vocabulary conflicts. Resolve and test before relying on the role middleware/policies.

## Sensitive question/exam content

- Separate answer-key content from candidate DTOs/queries.
- Restrict question preview, raw exports, and attachments by role, state, and scope.
- Version question content; record which versions were selected into each generated exam.
- Approved/issued exam snapshots are immutable. Corrections create a new version with reason and actor.
- Do not include answer keys in HTML hidden inputs, generic API resources, client-side state, logs, analytics, or cache keys accessible to candidates.

## Uploads and exports

- Validate extension/MIME/size and inspect content where feasible.
- Generate storage names; do not trust path segments from the client.
- Store private examination files on non-public storage. Public institution logos may use a public disk only if content is intended for public access.
- Authorize each download; use short-lived signed URLs if supported by the chosen storage.
- Prevent spreadsheet formula injection in CSV/XLSX exports and avoid exporting columns not permitted for the requesting role.

## Audit

Record actor, action, target, timestamp, outcome, and reason/context for high-risk events: user/role changes, question approvals, exam generation/approval/access, mark changes, result publication, exports, and restore/delete actions. Avoid logging passwords, reset tokens, answer keys, or full private payloads.

## Operations

- Keep `.env` out of version control and distributable ZIPs.
- Production: `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, secure cookies, least privilege, restricted file permissions.
- Patch Composer/npm dependencies on a planned cadence and review security advisories.
- Store backups off-host/encrypted, limit access, and test restoration.
- Add rate limiting to login, password reset, sensitive search/export, and other abuse-prone endpoints.

## Required security tests

1. Guest cannot access protected routes.
2. Authenticated user without permission is denied.
3. User from institution A cannot read or mutate institution B data.
4. Student/candidate responses never contain answer-key fields.
5. A draft/rejected question cannot be selected for generation.
6. Approved exam version cannot be altered by editing a source question.
7. Export/download obeys the same scope as page access.
8. User/role changes and result corrections create audit records.
