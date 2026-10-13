# Folder Structure and Placement Rules

This document describes both the **current** repository and the **target conventions**. It does not imply that planned folders already exist.

## Current important paths

```text
app/
  Actions/Institution/       # institution actions currently present
  DTO/                       # data transfer objects currently present
  Http/Controllers/          # web controllers
  Http/Requests/             # request validation; consolidate duplicate institution requests
  Models/                    # Eloquent models
  Policies/                  # authorization policies
  Services/                  # business services
  Providers/                 # service providers / Gate policy registration
bootstrap/                   # Laravel bootstrap and middleware aliases
config/                      # framework/package configuration
database/
  factories/                 # factories
  migrations/                # authoritative source for current schema
  seeders/                   # development/reference seeders
resources/
  css/                       # app styles
  views/                     # Blade pages, layouts, partials
routes/                      # web.php, auth.php, console.php
tests/                       # Unit and Feature tests
docs/                        # project documentation
public/                      # web root and static/legacy assets
```

## Target conventions

- Keep HTTP concerns in controllers and Form Requests.
- Use `app/Actions/<Domain>/` for a single explicit business operation or command when it adds clarity.
- Use `app/Services/<Domain>/` or the existing `app/Services` convention for cohesive workflows; choose one folder convention and apply it consistently before new modules proliferate.
- Put Eloquent models and relationships in `app/Models/` unless the project later adopts a module namespace via an ADR.
- Put policies in `app/Policies/`; register/auto-discover policies consistently.
- Keep migrations timestamped and never silently edit shared migration history.
- Store application tests by behavior in `tests/Feature/` and isolated logic in `tests/Unit/`.
- Put module specs and data/architecture docs in `docs/06_MODULE_SPECS/`, `docs/02_ARCHITECTURE/`, and `docs/03_DATABASE/`.

## Future physical module layout (not currently implemented)

If the application grows enough to justify physically grouping modules, create an ADR and migrate one module at a time. Do not scatter code into empty `modules/`, `packages/`, or `plugins/` directories merely because earlier design documents mention them.

## Placement examples

| Concern | Preferred location |
|---|---|
| Request validation | `app/Http/Requests/<Domain>/...Request.php` |
| Authorization rule | `app/Policies/<Model>Policy.php` |
| Database schema | `database/migrations/` |
| Model relation | `app/Models/` |
| Business workflow | Action/service class, chosen consistently |
| Route registration | `routes/web.php` or `routes/auth.php` |
| User-facing page | `resources/views/<domain>/...blade.php` |
| Feature behavior test | `tests/Feature/<Domain>/...php` |
| Decision record | `docs/02_ARCHITECTURE/ADR-*.md` |
