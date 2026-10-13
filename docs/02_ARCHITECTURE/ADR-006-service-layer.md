# ADR-006: Use Services or Actions for Meaningful Business Workflows

- **Status:** Accepted
- **Scope:** Business logic organization

## Context

Complex workflows such as question approval, exam generation, and result publication involve multiple rules, writes, and side effects. Controllers should not own those workflows.

## Decision

Use an application service or action class when it encapsulates a meaningful business operation, transaction, or reusable workflow. Controllers handle HTTP orchestration; Form Requests handle request validation; models represent persistence/relationships; policies enforce authorization.

## Consequences

- Avoid both fat controllers and empty service classes created only to satisfy a generic file checklist.
- Use one consistent namespace/folder convention per domain.
- Transactions should cover related database writes; filesystem/mail actions need explicit cleanup/compensation.
- Current InstitutionController has its service constructor commented out and calls a missing `paginate()` method. Existing Action classes and InstitutionService should be reconciled so the same business behavior is not duplicated.
