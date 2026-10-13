# Architecture Decision Records (ADR) Index

ADRs record chosen direction and its implementation status. A decision may be accepted while its rollout remains incomplete.

| ADR | Decision | Decision status | Implementation status |
|---|---|---|---|
| [ADR-001](ADR-001-use-laravel-12.md) | Laravel 12 | Accepted | Present in Composer constraint |
| [ADR-002](ADR-002-modular-monolith.md) | Modular monolith | Accepted as target | Current code remains conventional Laravel layout |
| [ADR-003](ADR-003-use-bootstrap-5.md) | Bootstrap 5 for new screens | Accepted as forward standard | Incomplete; Breeze/Tailwind and legacy assets remain |
| [ADR-004](ADR-004-use-mysql.md) | MySQL 8.x production target | Accepted | `.env.example` defaults to SQLite for local work |
| [ADR-005](ADR-005-use-spatie-permission.md) | Spatie Permission for RBAC | Accepted | Package/schema present, `User` integration incomplete |
| [ADR-006](ADR-006-service-layer.md) | Services/actions for meaningful workflows | Accepted | Partially applied; injection/API inconsistencies exist |
| [ADR-007](ADR-007-form-request-validation.md) | Form Requests for request validation | Accepted | Present for institution, duplicate classes require cleanup |

When reversing an accepted decision, add a new ADR explaining the change rather than silently editing history. Review any document that still says the service-layer decision is Proposed; the individual ADR and this index now use Accepted with incomplete rollout.
