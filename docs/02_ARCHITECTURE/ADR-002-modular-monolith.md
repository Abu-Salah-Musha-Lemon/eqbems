# ADR-002: Use a Modular Monolith

- **Status:** Accepted as target architecture
- **Scope:** Domain organization and deployment shape

## Context

The product spans institutions, academics, questions, exams, and results but is being built by a small team. A distributed architecture would add network, deployment, and operational complexity before it is justified.

## Decision

Use one Laravel application and one relational database initially. Establish logical module boundaries, one clear owner for each domain concept, and explicit dependency direction. Extract services only if measured scale/team needs justify it and a new ADR is approved.

## Consequences

- Domain modules share deployment and transaction boundaries.
- Avoid circular dependencies and cross-module direct manipulation of another module's internals.
- Target logical modules are documented in `MODULE_DEPENDENCY_MAP.md`.
- The current repository still uses the conventional Laravel `app/` structure; the term “modular monolith” describes architectural direction, not a claim that module packages already exist.
