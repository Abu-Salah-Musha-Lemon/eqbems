# ADR-001: Use Laravel 12

- **Status:** Accepted
- **Decision date:** Initial project setup; exact date not verified
- **Scope:** Backend web framework

## Context

EQBEMS needs routing, authentication, request validation, database migrations/ORM, queues, filesystem handling, and test support with a familiar PHP ecosystem.

## Decision

Use Laravel 12 and Eloquent migrations/models as the application foundation. `composer.json` declares `laravel/framework: ^12.0` and PHP `^8.2`; team guidance uses PHP 8.3+ as the preferred standard.

## Consequences

- Follow Laravel conventions and supported upgrade paths.
- Verify PHP/runtime and required extensions in CI and deployment.
- Use migrations as the source of truth for current schema.
- A framework version in Composer is not a guarantee that the deployed runtime has all requirements installed.

## Implementation status

Framework dependency is present in the manifest/lock file. Clean installation and deployment remain environment-dependent and should be verified by CI.
