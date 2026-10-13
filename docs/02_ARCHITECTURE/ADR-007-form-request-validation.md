# ADR-007: Use Form Requests for Request Validation

- **Status:** Accepted
- **Scope:** HTTP input validation and request-level authorization

## Context

Validation and authorization logic embedded in controllers becomes repetitive and is easy to omit.

## Decision

Use Laravel Form Requests for non-trivial create/update flows. Keep resource-level authorization in policies; request authorization may apply the same coarse permission check, but the permission vocabulary and policy checks must agree.

## Consequences

- Validation must match database constraints and normalize inputs consistently.
- Do not trust route model IDs from a request; unique-ignore rules should use the bound model's key.
- Current nested Institution Form Requests exist alongside duplicate top-level request classes; consolidate to one convention.
- Add tests for invalid input and unauthorized input, not just successful form submission.
