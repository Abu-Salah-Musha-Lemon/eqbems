---
adr_id: ADR-006
title: Adopt Service Layer Pattern
status: Accepted
date: 2026-08-02

decision_makers:
  - Solution Architect
  - System Analyst
  - Product Owner

review_date: 2027-08-02

related_documents:
  - SYSTEM_ARCHITECTURE.md
  - PROJECT_STANDARDS.md
  - MODULE_DESIGN_GUIDE.md
---

# ADR-006: Adopt Service Layer Pattern

---

# Status

Accepted

---

# Context

EQBEMS contains complex business processes across multiple domains, including institution management, academic configuration, question banking, examinations, result processing, reporting, and user management.

As the application grows, placing business rules directly inside controllers or models would reduce maintainability and increase coupling.

The project requires:

- Clear separation of responsibilities
- Reusable business logic
- Easier testing
- Modular architecture
- Long-term maintainability

---

# Problem Statement

Where should business logic reside?

Possible approaches include:

- Fat Controllers
- Fat Models
- Service Layer
- Repository Layer Only
- Domain Services (DDD)

---

# Decision

EQBEMS will adopt a **Service Layer Pattern**.

Business rules shall reside in Service classes.

Controllers are responsible only for:

- Receiving HTTP requests
- Authorization
- Delegating validation (Form Requests)
- Calling Services
- Returning responses

Models remain responsible for:

- Relationships
- Attribute casting
- Query scopes
- Accessors / Mutators

Repository classes are **optional** and should only be introduced when there is a clear architectural benefit.

---

# Decision Drivers

The decision is based on:

- Better separation of concerns
- Improved code readability
- Reusable business logic
- Easier automated testing
- Lower controller complexity
- Compatibility with Modular Monolith architecture
- Alignment with Laravel best practices

---

# Alternatives Considered

## Option 1 — Fat Controllers

### Advantages

- Fast to implement
- Simple for very small projects

### Disadvantages

- Poor maintainability
- Code duplication
- Difficult testing
- Large controller classes

**Decision:** Rejected

---

## Option 2 — Fat Models

### Advantages

- Business logic close to data

### Disadvantages

- Very large model classes
- Mixed responsibilities
- Difficult reuse

**Decision:** Rejected

---

## Option 3 — Service Layer

### Advantages

- Clean architecture
- Reusable business logic
- Thin controllers
- Better testing
- Easier maintenance

### Disadvantages

- Additional classes
- Slight increase in project structure complexity

**Decision:** Accepted

---

## Option 4 — Repository Pattern Everywhere

### Advantages

- Data access abstraction
- Easier replacement of data sources

### Disadvantages

- Often duplicates Eloquent
- More boilerplate code
- Unnecessary complexity for standard CRUD

**Decision:** Rejected as a mandatory pattern

---

## Option 5 — Full Domain-Driven Design (DDD)

### Advantages

- Strong domain modeling
- Excellent scalability

### Disadvantages

- High learning curve
- Excessive complexity for Version 1

**Decision:** Deferred

---

# Responsibilities

## Controllers

Responsible for:

- Request handling
- Authorization
- Calling Services
- Returning responses

Must NOT contain:

- Business rules
- Complex calculations
- Transaction management

---

## Services

Responsible for:

- Business rules
- Validation beyond Form Requests
- Transactions
- Workflow orchestration
- Coordination across multiple models
- Integration with external services

---

## Models

Responsible for:

- Relationships
- Scopes
- Attribute casting
- Accessors / Mutators

Should avoid business workflows.

---

# Example Structure

```
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
│
├── Services/
│   ├── InstitutionService.php
│   ├── QuestionService.php
│   ├── ExamService.php
│   └── ResultService.php
│
├── Models/
│   ├── Institution.php
│   ├── Question.php
│   └── Exam.php
```

---

# Repository Strategy

Repository classes should only be created when:

- Complex reporting queries
- Multiple data sources
- External API abstraction
- Cache abstraction
- Query reuse across modules

Simple CRUD operations should use Eloquent directly inside Services.

---

# Transaction Strategy

Database transactions should be managed inside Service classes.

Examples:

- Publish Examination
- Approve Questions
- Publish Results
- Bulk Import

---

# Testing Strategy

Business rules should be tested primarily through Service classes.

Recommended:

- Unit Tests for Services
- Feature Tests for Controllers

---

# Consequences

## Positive

- Thin controllers
- Reusable business logic
- Easier testing
- Better maintainability
- Reduced duplication

## Negative

- More classes to manage
- Developers must understand project architecture

---

# Risks

Potential risks:

- Services becoming excessively large
- Business logic leaking back into controllers
- Circular service dependencies

Mitigation:

- Code reviews
- Module boundaries
- Single Responsibility Principle
- Documentation

---

# Future Evolution

Future versions may introduce:

- Domain Services
- CQRS
- Event-Driven Architecture
- Command Handlers

Only after business complexity justifies these patterns.

---

# Related ADRs

- ADR-001 — Use Laravel 12
- ADR-002 — Adopt Modular Monolith Architecture
- ADR-004 — Adopt MySQL
- ADR-005 — Use Spatie Permission

---

# References

- Laravel Documentation
- Laravel Service Container Documentation
- SOLID Principles
- Clean Architecture (Robert C. Martin)

---

# Revision History

| Version | Date | Description |
|----------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial Service Layer decision recorded |

---

# Approval

Status: Accepted

Approved By:

- Solution Architect
- Product Owner
- Technical Lead

Effective Date:

2026-08-02