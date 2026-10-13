---
adr_id: ADR-005
title: Adopt Spatie Laravel Permission for Authorization
status: Accepted
date: 2026-08-02

decision_makers:
  - Solution Architect
  - System Analyst
  - Product Owner

review_date: 2027-08-02

related_documents:
  - SECURITY_ARCHITECTURE.md
  - PROJECT_STANDARDS.md
  - SYSTEM_ARCHITECTURE.md
  - USER_MANAGEMENT.md
---

# ADR-005: Adopt Spatie Laravel Permission for Authorization

---

# Status

Accepted

---

# Context

EQBEMS is a multi-role enterprise application that manages academic institutions, examinations, question banks, reporting, and system administration.

Different users require different levels of access. The authorization system must be flexible, maintainable, secure, and scalable.

The application requires:

- Role-Based Access Control (RBAC)
- Fine-grained permissions
- Easy permission assignment
- Integration with Laravel
- High maintainability
- Enterprise-grade security

---

# Problem Statement

Which authorization solution should be adopted for Version 1?

Possible options include:

- Laravel Gates
- Laravel Policies only
- Custom RBAC
- Spatie Laravel Permission

The selected solution must support enterprise authorization requirements while minimizing maintenance complexity.

---

# Decision

EQBEMS will adopt **Spatie Laravel Permission** as the primary Role-Based Access Control (RBAC) package.

Authorization will be implemented using:

- Roles
- Permissions
- Laravel Policies
- Middleware
- Form Requests (where appropriate)

Spatie will manage role and permission assignments, while Laravel Policies will enforce business-specific authorization rules.

---

# Decision Drivers

This decision is based on:

- Mature and widely adopted package
- Excellent Laravel integration
- Active maintenance
- Comprehensive documentation
- Flexible permission model
- Easy role management
- Database-driven authorization
- Proven enterprise usage

---

# Alternatives Considered

## Option 1 — Spatie Laravel Permission

### Advantages

- Mature ecosystem
- Well documented
- Simple API
- Role inheritance support
- Database-driven
- Laravel best practice

### Disadvantages

- Additional package dependency
- Requires understanding of package conventions

**Decision:** Accepted

---

## Option 2 — Laravel Gates

### Advantages

- Built into Laravel
- Lightweight

### Disadvantages

- Not ideal for managing many roles and permissions
- Permissions become difficult to maintain at scale

**Decision:** Rejected as the primary authorization mechanism

---

## Option 3 — Laravel Policies Only

### Advantages

- Native Laravel feature
- Excellent model-level authorization

### Disadvantages

- Does not provide complete RBAC management
- Role and permission administration must be built manually

**Decision:** Rejected as a standalone solution

---

## Option 4 — Custom RBAC

### Advantages

- Fully customizable

### Disadvantages

- Higher development cost
- Increased maintenance burden
- Greater security risk if implemented incorrectly

**Decision:** Rejected

---

# Authorization Principles

The authorization system shall follow:

- Least Privilege Principle
- Separation of Duties
- Role-Based Access Control
- Defense in Depth
- Explicit Permission Assignment
- Auditability

---

# Authorization Model

The hierarchy is:

```
User
   │
   ▼
Role
   │
   ▼
Permission
```

Example:

```
Administrator
    ├── Institution Management
    ├── User Management
    ├── Question Bank
    ├── Reports
    └── Settings

Teacher
    ├── Question Management
    ├── Blueprint
    └── Examination

Exam Controller
    ├── Publish Exam
    ├── Generate Question Paper
    └── View Reports
```

---

# Role Strategy

Default roles may include:

- Super Administrator
- Institution Administrator
- Academic Administrator
- Department Head
- Teacher
- Question Setter
- Moderator
- Examiner
- Exam Controller
- Student
- Auditor

Roles should remain configurable rather than hard-coded.

---

# Permission Naming Standard

Permissions should use a consistent format:

```
institution.view
institution.create
institution.update
institution.delete

question.view
question.create
question.update
question.delete

exam.publish
exam.approve

result.publish

user.manage

report.export
```

---

# Policy Strategy

Business rules belong in Laravel Policies.

Examples:

- InstitutionPolicy
- QuestionPolicy
- ExamPolicy
- ResultPolicy

Policies should determine whether an authenticated user can perform a specific action on a resource.

---

# Middleware Strategy

Route protection should use middleware.

Examples:

```
role:Super Administrator

permission:institution.create

permission:exam.publish
```

Middleware should enforce access before the request reaches the controller.

---

# Security Standards

The authorization implementation shall:

- Never rely on UI restrictions alone.
- Validate authorization on the server.
- Log critical authorization events.
- Deny access by default.
- Follow the Principle of Least Privilege.

---

# Consequences

## Positive

- Strong RBAC implementation
- Easy permission management
- High maintainability
- Excellent Laravel integration
- Reduced custom authorization code
- Scalable authorization model

## Negative

- Dependency on a third-party package
- Team members must understand Spatie conventions

---

# Risks

Potential risks include:

- Permission misconfiguration
- Excessive privilege assignment
- Missing policy checks
- Inconsistent permission naming

Mitigation:

- Permission review
- Code review
- Security testing
- Documentation
- Automated authorization tests

---

# Future Evolution

Future versions may support:

- Multi-tenant role isolation
- Dynamic permission groups
- Time-based permissions
- Approval workflows
- Delegated administration
- External Identity Providers (OAuth / SSO / LDAP)

Such enhancements require additional ADRs where appropriate.

---

# Related ADRs

- ADR-001 — Use Laravel 12
- ADR-002 — Adopt Modular Monolith Architecture
- ADR-003 — Adopt Bootstrap 5
- ADR-004 — Adopt MySQL

---

# References

- Laravel Authorization Documentation
- Laravel Policies Documentation
- Spatie Laravel Permission Documentation
- OWASP Access Control Guidelines
- NIST RBAC Model

---

# Revision History

| Version | Date | Description |
|----------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial authorization technology decision recorded |

---

# Approval

Status: Accepted

Approved By:

- Solution Architect
- Product Owner
- Technical Lead

Effective Date:

2026-08-02