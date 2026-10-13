# Deployment Guide

**Production deployment is blocked until the readiness checks in this guide and `SECURITY.md` pass.** No specific host/provider is assumed.

## Environment requirements

- A supported PHP runtime satisfying `composer.json` and required extensions.
- Composer dependencies installed for production (`composer install --no-dev --prefer-dist --optimize-autoloader`).
- Node dependencies used to build frontend assets; the built Vite manifest/assets are deployed as part of the release.
- MySQL 8.x/InnoDB database and a least-privilege database account.
- Writable Laravel storage/cache directories, configured filesystem, queue worker if jobs are used, and scheduler if scheduled tasks are configured.
- HTTPS, valid host configuration, a process manager/web server, error monitoring/log retention, and off-host backups.

## Pre-deployment gate

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, and correct `APP_URL`.
- [ ] Unique secret `APP_KEY` securely injected; never share across unrelated environments.
- [ ] Database credentials are scoped to the app and not committed.
- [ ] RBAC and tenant isolation have passed negative tests.
- [ ] No default sample admin password will be active.
- [ ] Migrations tested against a staging copy and backup confirmed.
- [ ] Asset build, automated tests, smoke tests, and security checks pass.
- [ ] File upload/private exam storage paths are configured and permission-checked.
- [ ] Monitoring, incident contact, restore runbook, and rollback/forward-fix plan exist.

## Suggested release sequence

1. Build artifact from a clean, locked dependency state.
2. Back up database and stored files; verify backup completion.
3. Put application in maintenance mode if the migration is not backward-compatible.
4. Deploy code and run reviewed migrations with the release process.
5. Cache configuration/routes/views only after environment values and routes are verified.
6. Restart queue workers/processes to load the release if applicable.
7. Run smoke tests for login, permission-denied behavior, institution pages, health endpoint, storage, and queue.
8. Check logs and error monitoring. Exit maintenance mode only after successful checks.

## Rollback

Prefer rolling code back only when the previous code is compatible with the new schema. Some schema/data changes cannot be safely reversed. If rollback is unsafe, use a forward fix or a documented restore procedure. Decide this before release, not during an incident.

## Web server notes

The web server document root must be `public/`; never expose the repository root, `.env`, `storage`, or `vendor` directly. Disable directory listing and ensure HTTPS redirects/secure cookies are configured at the correct trusted proxy boundary.

## Current caveat

The repository's manifest and source still have application-level blockers. Passing this checklist requires those blockers to be fixed; commands succeeding is not sufficient.
