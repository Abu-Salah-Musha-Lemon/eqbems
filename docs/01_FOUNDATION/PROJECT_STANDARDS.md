---
document_id: DOC-011
document_name: Project Standards
project_name: Enterprise Question Bank & Examination Management System
project_short_name: EQBEMS
version: 1.0.0
status: Draft
document_owner: Abu Salah Musha Lemon
prepared_by: System Analysis & Architecture Team
last_updated: 2026-08-02

related_documents:
  - PROJECT_MANIFEST.md
  - SYSTEM_ARCHITECTURE.md
  - DATABASE_DESIGN.md
  - FEATURE_CATALOG.md
---

# PROJECT STANDARDS

> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# 1. Purpose

This document defines the official development standards for the EQBEMS project.

These standards ensure:

- Consistent development
- Maintainable code
- High software quality
- Better collaboration
- Easier maintenance
- Enterprise-level governance

All contributors must follow these standards.

---

# 2. General Principles

The project follows these principles:

- Documentation First
- Clean Code
- SOLID Principles
- DRY (Don't Repeat Yourself)
- KISS (Keep It Simple)
- YAGNI (You Aren't Gonna Need It)
- Separation of Concerns
- Convention over Configuration
- Security by Design

---

# 3. Technology Standards

| Layer | Standard |
|--------|----------|
| Backend | Laravel 12 |
| Language | PHP 8.3+ |
| Frontend | Bootstrap 5 |
| Template Engine | Blade |
| Database | MySQL |
| Version Control | Git |
| Package Manager | Composer |
| Asset Builder | Vite |
| Testing | PHPUnit |

---

# 4. Project Structure

The project must follow a predictable folder structure.

Example:

```

app/
Http/
Models/
Services/
Repositories/
Policies/
Observers/

database/
migrations/
seeders/
factories/

resources/
views/
js/
css/

routes/

docs/

```

No custom folders should be added without architectural approval.

---

# 5. Naming Conventions

## PHP Classes

PascalCase

Example:

```

InstitutionController

QuestionBankService

AcademicYear

```

---

## Variables

camelCase

Example:

```

$institutionName

$userProfile

```

---

## Functions

camelCase

Example:

```

createInstitution()

calculateGPA()

```

---

## Database Tables

snake_case (plural)

Examples:

```

institutions

question_banks

academic_years

```

---

## Database Columns

snake_case

Examples:

```

institution_name

created_at

updated_at

```

---

## Routes

Use plural nouns and RESTful naming.

Examples:

```

/institutions

/question-bank

/exams

```

---

## Blade Views

snake_case

Examples:

```

create.blade.php

edit.blade.php

index.blade.php

```

---

# 6. Coding Standards

- Follow PSR-12 coding style.
- Keep controllers thin.
- Move business logic to services.
- Use Form Requests for validation.
- Avoid duplicated logic.
- Write meaningful method names.
- Use dependency injection.
- Keep methods focused on one responsibility.

---

# 7. Controller Standards

Controllers should only:

- Receive requests
- Authorize actions
- Validate requests
- Call services
- Return responses

Controllers should NOT contain complex business logic.

---

# 8. Service Layer Standards

Business logic belongs inside Service classes.

Examples:

- InstitutionService
- QuestionService
- ExamService

Responsibilities:

- Business rules
- Transactions
- Coordination between models
- Complex operations

---

# 9. Model Standards

Models should:

- Define relationships
- Define scopes
- Define casts
- Define accessors/mutators

Avoid placing business workflows directly inside models.

---

# 10. Validation Standards

All user input must be validated using Form Request classes.

Example:

```

StoreInstitutionRequest

UpdateInstitutionRequest

```

Validation rules must not be placed directly in controllers.

---

# 11. Authorization Standards

Authorization must use:

- Policies
- Gates (only when appropriate)
- Spatie Roles & Permissions

Never rely solely on UI restrictions.

---

# 12. Database Standards

- Use foreign keys.
- Use indexes where appropriate.
- Prefer soft deletes for important entities.
- Use timestamps.
- Avoid redundant columns.
- Follow normalization unless justified.

---

# 13. Git Standards

## Branches

```

main

develop

feature/<feature-id>

bugfix/<issue-id>

hotfix/<issue-id>

```

Example:

```

feature/INST-001

feature/QB-012

```

---

## Commit Format

```

[MODULE-ID] Short description

```

Examples:

```

[INST-001] Create institution migration

[QB-004] Implement question approval workflow

[AUTH-002] Fix logout issue

```

---

# 14. Documentation Standards

Every completed feature must update:

- Feature Catalog
- Changelog
- Module Documentation
- API Documentation (if applicable)
- User Guide (if UI changes)

---

# 15. Security Standards

- Validate all input.
- Escape output.
- Use CSRF protection.
- Never trust client-side validation.
- Use prepared statements (via Eloquent/Query Builder).
- Enforce RBAC.
- Log security-sensitive events.

---

# 16. Error Handling Standards

- Use meaningful exception messages.
- Log unexpected errors.
- Show user-friendly error pages.
- Never expose stack traces in production.

---

# 17. Testing Standards

Every critical feature should have:

- Feature Tests
- Unit Tests (where applicable)

Bug fixes should include regression tests whenever practical.

---

# 18. Code Review Checklist

Before merging:

- Coding standards followed
- Validation implemented
- Authorization checked
- Tests passed
- Documentation updated
- No debug code
- No commented dead code

---

# 19. Definition of Done (DoD)

A feature is considered complete only if:

- Code implemented
- Tests pass
- Documentation updated
- Feature Catalog updated
- Reviewed
- Approved
- Merged

---

# 20. Change Control

Changes to these standards require:

1. Architecture review.
2. Documentation update.
3. Team approval.
4. Version increment.

---

# 21. Related Documents

- SYSTEM_ARCHITECTURE.md
- DATABASE_DESIGN.md
- FEATURE_CATALOG.md
- CODING_STANDARD.md (future, if separated)

---

# 22. Revision History

| Version | Date | Description |
|---------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial standards established |

---

# 23. Approval

Document Status: Draft

Approval Status: Pending

Baseline Version: 1.0