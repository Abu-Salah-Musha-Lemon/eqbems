# Project Standards

## General rules

1. Match code to the current Laravel version and repository conventions.
2. Make implementation status evidence-based. A package, class, route, or schema alone does not equal a completed feature.
3. Favor readable, explicit code over generic enterprise boilerplate.
4. Keep each change small enough to review and test.
5. Update relevant docs and ADRs in the same change as material design/schema changes.

## PHP/Laravel

- Use PSR-12 style and Laravel conventions; run Laravel Pint.
- Prefer constructor/property injection or method injection over unresolved service properties.
- Use Form Requests for non-trivial request validation/authorization.
- Use policies/gates for resource authorization. UI visibility is not authorization.
- Use services/actions for meaningful business orchestration, transactions, or reusable workflows; avoid empty placeholder services.
- Use Eloquent relationships and database constraints together.
- Use explicit return types where they improve clarity and align with existing code style.
- Use named routes for redirects and links.
- Keep controllers focused on HTTP orchestration; keep calculation and state-transition rules testable outside controllers.

## Database

- Make schema changes through new migrations. Avoid editing migrations after they have been shared/applied.
- Use plural snake_case table names and snake_case fields.
- Add foreign keys and unique constraints for enforceable invariants.
- Use decimal columns for marks/grade points, not float.
- Plan deletes deliberately; preserve exam/version/audit history.
- Test migrations from an empty database and, where relevant, against MySQL.

## Frontend

- Bootstrap 5 is the forward standard for newly built screens until an ADR changes it.
- Existing Breeze/Tailwind markup and legacy theme assets remain transitional debt; do not mix new styling conventions arbitrarily.
- Use semantic HTML, visible labels, clear validation messages, keyboard access, and responsive layouts.
- Do not expose answer keys, hidden permissions, secrets, or unfiltered data in markup/JavaScript.

## Naming

- Classes: PascalCase; methods/properties: camelCase.
- Database: plural snake_case; foreign key `<singular>_id`.
- Permissions: lowercase dotted action vocabulary, e.g. `institution.view`, `institution.create`, `institution.update`, `institution.delete`.
- Tests: describe observable behavior, including unauthorized/invalid cases.

## Module completion checklist

A module requires only the components justified by its workflows, but at minimum: migration/schema, model/relations, authorization, validation, application behavior, UI/routes or documented interface, tests, and docs. Observers/events/jobs are added only when there is a clear reason.
