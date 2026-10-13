---
adr_id: ADR-007
title: Adopt Laravel Form Request Validation
status: Accepted
date: 2026-08-02

decision_makers:
  - Solution Architect
  - System Analyst
  - Product Owner

review_date: 2027-08-02

related_documents:
  - PROJECT_STANDARDS.md
  - SYSTEM_ARCHITECTURE.md
  - SECURITY_ARCHITECTURE.md
---

# ADR-007: Adopt Laravel Form Request Validation

---

# Status

Accepted

---

# Context

EQBEMS receives user input from multiple sources, including:

- Institution Management
- Academic Management
- User Management
- Question Bank
- Examination
- Result Processing
- System Settings

Consistent validation is required to ensure data quality, maintain security, and reduce duplicated validation logic.

---

# Problem Statement

How should incoming HTTP requests be validated?

Possible approaches include:

- `$request->validate()` inside controllers
- Manual validation
- Form Request classes
- Custom validation middleware

The selected solution should support maintainability, testability, and Laravel best practices.

---

# Decision

EQBEMS will use **Laravel Form Request classes** for validating all HTTP requests.

Every Create, Update, Import, and other user-submitted operation must have its own dedicated Form Request class.

Examples:

```
StoreInstitutionRequest
UpdateInstitutionRequest

StoreQuestionRequest
UpdateQuestionRequest

StoreExamRequest
PublishExamRequest
```

Validation rules must **not** be written directly inside controllers.

---

# Decision Drivers

The decision is based on:

- Separation of concerns
- Cleaner controllers
- Reusable validation logic
- Better automated testing
- Centralized validation
- Native Laravel support
- Improved maintainability

---

# Alternatives Considered

## Option 1 — Form Request Classes

### Advantages

- Clean architecture
- Reusable validation
- Supports authorization
- Easy testing
- Laravel standard

### Disadvantages

- More classes to maintain

**Decision:** Accepted

---

## Option 2 — Controller Validation

Example:

```php
$request->validate([
    'name' => 'required|max:255',
]);
```

### Advantages

- Simple
- Quick

### Disadvantages

- Large controllers
- Duplicated validation
- Difficult maintenance

**Decision:** Rejected

---

## Option 3 — Manual Validation

### Advantages

- Full control

### Disadvantages

- Verbose
- Error-prone
- Inconsistent implementation

**Decision:** Rejected

---

## Option 4 — Middleware Validation

### Advantages

- Centralized execution

### Disadvantages

- Not suitable for feature-specific rules
- Reduced readability

**Decision:** Rejected

---

# Validation Responsibilities

## Form Request

Responsible for:

- Input validation
- Field format validation
- Required field checks
- Data type validation
- Length validation
- Basic authorization

---

## Service Layer

Responsible for:

- Business rule validation
- Duplicate business checks
- Cross-module validation
- Transaction-level validation

Examples:

- Institution code must be unique within business rules.
- A published exam cannot be modified.
- A result cannot be published twice.

---

## Controller

Responsible for:

- Receiving validated data
- Calling services
- Returning responses

Controllers should not contain validation rules.

---

# Authorization Strategy

Each Form Request should implement:

```php
public function authorize(): bool
{
    return true;
}
```

or delegate authorization to Policies when business rules require resource-level checks.

---

# Naming Convention

Form Request names must follow:

```
Store<Model>Request
Update<Model>Request
Delete<Model>Request
Import<Model>Request
Publish<Model>Request
Approve<Model>Request
Reject<Model>Request
```

Examples:

```
StoreInstitutionRequest
UpdateInstitutionRequest
ApproveQuestionRequest
PublishExamRequest
ImportStudentRequest
```

---

# Folder Structure

```
app/
└── Http/
    └── Requests/
        ├── Institution/
        │   ├── StoreInstitutionRequest.php
        │   └── UpdateInstitutionRequest.php
        │
        ├── Question/
        │   ├── StoreQuestionRequest.php
        │   └── UpdateQuestionRequest.php
        │
        └── Exam/
            ├── StoreExamRequest.php
            └── PublishExamRequest.php
```

Organizing requests by module is preferred over placing all request classes in a single directory.

---

# Validation Standards

Validation should include:

- Required fields
- Data type validation
- Maximum length
- Minimum length
- Enum validation
- Date validation
- File validation
- Image validation
- Numeric ranges
- Array validation
- Nested validation

Custom validation rules should be implemented only when built-in Laravel rules are insufficient.

---

# Error Handling

Validation errors shall:

- Return HTTP 422 for API responses.
- Display user-friendly messages for web requests.
- Preserve old input where appropriate.
- Never expose internal implementation details.

---

# Consequences

## Positive

- Thin controllers
- Centralized validation
- Better maintainability
- Improved testability
- Consistent validation across modules

## Negative

- Larger number of classes
- Additional project structure

---

# Risks

Potential risks:

- Duplicate validation rules
- Missing Form Requests
- Business rules placed in validation layer
- Overly complex request classes

Mitigation:

- Code review
- Documentation
- Project standards
- Architectural governance

---

# Future Evolution

Future versions may introduce:

- Custom Validation Rule objects
- Localized validation messages
- Validation pipelines
- Shared validation traits

Any significant change requires a new ADR.

---

# Related ADRs

- ADR-001 — Use Laravel 12
- ADR-002 — Adopt Modular Monolith Architecture
- ADR-005 — Use Spatie Permission
- ADR-006 — Adopt Service Layer Pattern

---

# References

- Laravel Validation Documentation
- Laravel Form Request Documentation
- Laravel Authorization Documentation
- OWASP Input Validation Cheat Sheet

---

# Revision History

| Version | Date | Description |
|----------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial validation strategy recorded |

---

# Approval

Status: Accepted

Approved By:

- Solution Architect
- Product Owner
- Technical Lead

Effective Date:

2026-08-02