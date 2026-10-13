# Coding Guidelines

## Controllers and requests

- Keep controllers focused on HTTP flow and response selection.
- Use Form Requests for non-trivial validation and request-level authorization.
- Use policies for model/resource decisions; all sensitive controller actions must authorize.
- Normalize user input in one place, and make unique validation agree with DB constraints including model-binding/soft-delete behavior.

## Services/actions and transactions

- Add a service/action for cohesive business behavior, not as empty boilerplate.
- Put multi-row database state transitions in a transaction.
- Do not assume DB transactions undo file deletion, upload, email, or queue dispatch; explicitly order operations or provide cleanup/compensation.
- Keep transaction boundaries visible and test failure paths.

## Models and database

- Define relationships explicitly and avoid N+1 queries on collection screens.
- Prefer database constraints for uniqueness and referential integrity.
- Protect immutable historical content from routine updates.
- Prefer status/deactivation and preserved references over hard deleting users attached to audit records.

## Files and exports

- Validate uploads, use generated names, store in the intended disk, and check who can read each file.
- Delete/replace old uploads safely after the new file is stored successfully.
- Escape spreadsheet formulas and avoid exporting unapproved/sensitive columns.

## Frontend

- Use Bootstrap 5 for new screens unless a newer ADR changes the standard.
- Preserve current functionality while transitional Breeze/Tailwind screens exist; avoid duplicating layout systems in a single feature.
- Include labels, accessible error messages, focus indicators, and responsive states.

## Code quality

Run Pint and application tests. Add static-analysis checks to CI as configuration becomes stable. Do not suppress an analyzer/test failure without documenting the reason and a follow-up.
