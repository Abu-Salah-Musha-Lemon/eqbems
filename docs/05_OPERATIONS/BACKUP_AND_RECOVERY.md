# Backup and Recovery

## Scope

A usable restore requires database data, uploaded files/private exam assets, required configuration/secrets, and a version-matched deployable artifact. Laravel log/cache directories are not substitutes for backups.

## Policy to approve before production

- Define RPO (maximum acceptable data loss) and RTO (maximum restoration time) with the business owner; do not invent targets without an operational commitment.
- Use encrypted, access-controlled, off-host backups. Separate backup credentials from application credentials.
- Back up database and files with a consistency approach appropriate to their update patterns.
- Define retention, deletion, and legal hold policy; backup copies can preserve data beyond in-app deletion.
- Monitor backup completion and storage capacity; alert on failures.

## Database backup (illustrative MySQL command)

Use the organization's secret manager to pass credentials; do not place passwords in shell history or this document.

```bash
mysqldump --single-transaction --routines --triggers --databases eqbems > eqbems.sql
```

Encrypt the output, transfer it to the approved backup destination, restrict access, and verify file integrity. The exact command/options depend on server version and privileges. SQLite local database files may be copied only when the application is quiesced or using an appropriate SQLite backup method.

## Filesystem backup

Back up the configured upload disks separately. Identify which files are public assets and which are private exam/question/result materials. Keep environment secrets in an approved secret manager and document recovery access without placing actual secrets in the repository.

## Restore drill

1. Provision isolated restore infrastructure.
2. Retrieve and verify a selected backup artifact.
3. Restore the database and files.
4. deploy the matching application version and compatible config.
5. Run migration status, integrity checks, login/access smoke tests, and file access checks.
6. Record duration, failures, data consistency, and whether the agreed RPO/RTO was met.
7. Destroy or securely retain the test environment according to policy.

## Incident recovery

Preserve evidence, restrict compromised access, rotate credentials as appropriate, restore to a clean environment, validate integrity, and communicate according to the incident plan. Never restore a compromised backup without investigating the compromise window.
