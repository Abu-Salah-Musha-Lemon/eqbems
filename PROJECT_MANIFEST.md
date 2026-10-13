# EQBEMS Project Manifest

> **Status:** Development foundation. This file describes the repository as inspected on 2026-10-13; it is not a release certification.

## Identity

| Field | Value |
|---|---|
| Project | Enterprise Question Bank & Examination Management System |
| Code | EQBEMS |
| Version | `0.1.0-dev` (working label; no release tag verified) |
| Product type | Web application for education/examination workflows |
| Framework | Laravel 12 (`laravel/framework` constraint `^12.0`) |
| PHP | Composer constraint `^8.2`; team standard PHP 8.3+ |
| UI | Blade/Vite; Bootstrap 5 package is present, while Breeze/Tailwind and legacy theme assets remain in the repository |
| Database target | MySQL 8.x for production; `.env.example` defaults to SQLite for local bootstrap |
| Authentication | Laravel Breeze scaffolding |
| Authorization | Spatie Laravel Permission is installed and has migrations/seeders; integration is incomplete |
| Architecture direction | Modular monolith / layered Laravel application |
| Current phase | Foundation and institution-module stabilization |

## Implementation status

| Capability | Status | Evidence / caveat |
|---|---|---|
| Laravel application skeleton | Present | `composer.json`, `artisan`, `bootstrap/app.php` |
| Authentication scaffold | Present, needs verification | `routes/auth.php`, Breeze controllers and feature tests |
| User/role/permission integration | Incomplete | `User` lacks `HasRoles`; seeder calls `assignRole()`; update/edit permission names conflict |
| Institution data schema | Migration present | `database/migrations/2026_07_05_142437_create_institutions_table.php` |
| Institution CRUD | In progress / not verified | Controller service injection is commented; `paginate()` is called but absent from service; views are incomplete; duplicate Form Requests exist |
| Dashboard | Partial | `/dashboard` and `/dashboard2` use different view paths |
| Academic structures | Planned | No current migrations for target domain entities |
| Question bank | Planned/scaffolding | `QuestionService` is empty; no question-domain migrations |
| Blueprint/exam/results | Planned | Design documents only; do not represent as implemented |
| Test suite | Partial | Mostly framework/Breeze tests; domain tests needed |

## Architectural flow (target)

```text
HTTP route -> authentication middleware -> policy/authorization -> Form Request
          -> controller -> application service/action -> Eloquent model -> migration-backed database
```

Observers, events, notifications, and queues are added where a concrete use case requires them; they are not mandatory boilerplate for every module.

## Completion rule

A capability may be marked **Complete** only when its code exists, its routes/views or public interface work, its authorization and validation are enforced, relevant tests pass, migrations are reproducible, and its user/developer documentation is updated. A package installed or a table created is not sufficient.

## Current next steps

1. Fix and test authentication/RBAC integration.
2. Make institution CRUD coherent: controller dependency injection, pagination API, views, request duplication, policy/permission names, file cleanup.
3. Add focused tests for roles, permissions, and institution CRUD.
4. Confirm database setup standard for all contributors and CI.
5. Implement academic structures before question bank and examination features.
