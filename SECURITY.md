# Security Policy

## Current security posture

EQBEMS is pre-release software. It must not be used for real examinations, student records, production credentials, or sensitive institutional data until the security requirements below are implemented and reviewed. The presence of Spatie Permission, Laravel authentication scaffolding, activity-log/backup packages, or a policy class does not by itself establish a secure system.

## Supported versions

Only the active development branch is expected to receive fixes before the first release. Once tagged releases exist, supported versions will be listed here.

## Reporting a vulnerability

Do not publish exploit details, credentials, real student data, or exam answer content in a public issue. Contact the project maintainer privately through the repository's configured private contact channel. If no private channel is published, first ask the maintainer to provide one. Include affected component, impact, reproducible steps, and a proposed mitigation where possible.

## Mandatory controls before production

- Remove `.env` and any database, log, cache, upload, or test artifacts from distributable archives. Keep secrets outside version control.
- Set `APP_DEBUG=false`, use HTTPS, and set secure cookie/session options appropriate for the deployment.
- Rotate any application key or credential that was present in a shared archive or used outside an isolated local environment.
- Configure server-side authorization and institution scoping for every protected read, write, export, and file download.
- Fix the `User`/Spatie role integration and permission naming before using role-based routes.
- Separate question answer keys from learner-facing data access. Test that students cannot retrieve keys from HTML, APIs, exports, logs, or cached responses.
- Validate uploaded file type, size, storage path, and authorization; avoid trusting client-provided filenames or MIME values.
- Apply least-privilege database credentials, restricted filesystem permissions, tested backups, and a patch/update process.
- Add audit events for permission changes, question approvals, exam generation, answer-key access, grade changes, and result publication.
- Define retention/deletion rules for account, academic, exam, and audit data.

## Secret handling

`.env` must remain untracked. `APP_KEY` is environment-specific; do not reuse a known key across environments. Never place tokens/passwords in source, screenshots, issues, sample seeders, or logs. The current admin seeder uses a predictable local password and is for development only; replace it with a controlled bootstrap process before any shared/staging/production environment.

## Incident response outline

1. Restrict or disable affected credentials/routes if safe to do so.
2. Preserve relevant logs without exposing them publicly.
3. Determine scope, affected data, entry point, and time window.
4. Rotate exposed credentials and patch the root cause.
5. Validate the fix with regression tests and review other affected endpoints.
6. Notify impacted parties according to applicable legal/contractual requirements.
7. Record a sanitized incident report and prevention actions.
