# Observability and Operational Readiness

## Current state

Laravel logging is configured, and the project includes activity-log and backup dependencies, but a complete production monitoring/alerting setup is not verified. Package installation is not operational readiness.

## Minimum operational signals

- HTTP error rate and response latency.
- Login failures, password-reset throttling, authorization denials for high-risk routes.
- Queue backlog, failed jobs, retry rates, and scheduler heartbeat if queues/schedules are used.
- Database connection failures, slow queries, lock waits, disk/storage capacity.
- Backup success/failure and age of the last verified backup.
- Application exception rate and repeated failed migrations.

## Logging rules

- Use structured context where feasible (request/correlation ID, actor ID, action, target ID, outcome) without logging secrets.
- Never log plaintext passwords, reset tokens, APP_KEY, answer keys, complete private exam papers, or unnecessary personal data.
- Restrict log readers, encrypt/secure transfers, and set retention.
- Treat exception messages, failed-job payloads, and uploaded filenames as potentially sensitive.

## Alerts and response

Define severity, on-call ownership, notification paths, and runbooks before production. Alerts should be actionable, have an owner, and avoid disclosing sensitive content. Record incident timeline, root cause, customer impact, and prevention tasks.

## Health checks

Laravel's configured `/up` health endpoint exists via `bootstrap/app.php`. It should be integrated into deployment checks; it does not by itself prove database, queue, email, or storage dependencies are healthy. Add dependency checks deliberately with appropriate exposure limits.
