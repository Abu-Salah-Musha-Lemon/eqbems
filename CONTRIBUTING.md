# Contributing to EQBEMS

Thank you for helping improve EQBEMS. The project is under active development; small, tested, reviewable changes are preferred over broad rewrites.

## Before you start

1. Read [the start-here guide](docs/00_START_HERE.md), [requirements](docs/01_FOUNDATION/PRODUCT_REQUIREMENTS.md), [architecture](docs/02_ARCHITECTURE/SYSTEM_ARCHITECTURE.md), and [coding standards](docs/01_FOUNDATION/PROJECT_STANDARDS.md).
2. Set up a local environment using [LOCAL_SETUP.md](docs/04_DEVELOPMENT/LOCAL_SETUP.md).
3. Check [known blockers](docs/00_START_HERE.md) and existing issues before building on incomplete behavior.

## Branches and commits

- Create a focused branch such as `feat/institution-search`, `fix/rbac-permissions`, or `docs/local-setup`.
- Use concise imperative commit messages, e.g. `Fix institution update permission`.
- Keep each pull request focused and explain user impact, migration impact, test evidence, and any follow-up required.

## Development rules

- Follow Laravel conventions and the naming rules in `docs/01_FOUNDATION/PROJECT_STANDARDS.md`.
- Validate incoming web requests with Form Requests when validation/authorization is non-trivial.
- Enforce authorization server-side with policies/gates and permissions. Hiding a button is not access control.
- Use a service/action when it encapsulates meaningful business orchestration or transactions; do not add empty boilerplate classes to satisfy a checklist.
- Schema changes must be made through new Laravel migrations. Do not edit an already-shared migration to change deployed schema.
- Add or update tests for bug fixes and features. Include negative tests for unauthorized and invalid requests.
- Update `DATABASE_DESIGN.md`, `DATABASE_DICTIONARY.md`, feature status, and module specification when a schema or workflow changes.
- Never commit `.env`, credentials, user data, real exam content, logs, generated secrets, or production database files.

## Before opening a pull request

Run the checks that apply to your change:

```bash
php artisan test
./vendor/bin/pint --test
npm run build
php artisan migrate:status
```

If a command fails due to a known repository blocker, report the exact command, output summary, environment, and why it is blocked. Do not claim tests pass if they were not run.

## Pull request checklist

- [ ] The purpose and scope are clear.
- [ ] Validation and authorization are server-side.
- [ ] Migrations are reversible where practical and safe for existing data.
- [ ] Tests cover success, invalid input, and unauthorized access.
- [ ] UI is usable at supported widths and has labels/error messages.
- [ ] Documentation and feature status are current.
- [ ] No secrets, logs, build scratch files, or private data are included.
