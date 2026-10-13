---
adr_id: ADR-004
title: Adopt MySQL as the Primary Database Management System
status: Accepted
date: 2026-08-02
decision_makers:
  - Solution Architect
  - System Analyst
  - Product Owner

review_date: 2027-08-02

related_documents:
  - DATABASE_DESIGN.md
  - PROJECT_STANDARDS.md
  - SYSTEM_ARCHITECTURE.md
  - BACKUP_AND_RECOVERY.md
---

# ADR-004: Adopt MySQL as the Primary Database Management System

---

# Status

Accepted

---

# Context

EQBEMS is an enterprise-grade web application that manages institutions, academic structures, users, question banks, examinations, results, and audit logs.

The application requires a reliable relational database capable of supporting transactional workloads, maintaining data integrity, and integrating seamlessly with the Laravel ecosystem.

The selected DBMS must provide:

- ACID-compliant transactions
- Referential integrity
- Strong indexing support
- Backup and recovery options
- High compatibility with Laravel
- Mature tooling
- Wide community support
- Scalability for future growth

---

# Problem Statement

Which relational database management system should be selected as the primary database for Version 1?

Candidate technologies:

- MySQL
- PostgreSQL
- MariaDB
- Microsoft SQL Server
- SQLite

The chosen database should balance reliability, performance, maintainability, licensing, hosting availability, and developer productivity.

---

# Decision

EQBEMS will use **MySQL 8.x** as the primary production database.

The application will access the database exclusively through Laravel Eloquent ORM and Query Builder. Direct SQL should be limited to cases where ORM cannot provide an efficient solution.

---

# Decision Drivers

The decision is based on the following factors:

- Excellent Laravel compatibility
- Mature relational database engine
- ACID transaction support
- Foreign key constraints
- Reliable indexing
- Broad hosting support
- Strong documentation
- Large developer community
- Familiarity within the development team
- Low operational cost

---

# Alternatives Considered

## Option 1 — MySQL

### Advantages

- Native Laravel support
- Excellent documentation
- Mature ecosystem
- Reliable performance
- Easy deployment
- Broad hosting availability

### Disadvantages

- Some advanced SQL features are less extensive than PostgreSQL.
- Complex analytical queries may require additional optimization.

**Decision:** Accepted

---

## Option 2 — PostgreSQL

### Advantages

- Advanced SQL features
- Excellent JSON support
- Strong standards compliance
- Powerful indexing and extensions

### Disadvantages

- Higher learning curve.
- More advanced features are unnecessary for Version 1.
- Increased operational complexity for the current project scope.

**Decision:** Rejected for Version 1 (may be reconsidered in the future)

---

## Option 3 — MariaDB

### Advantages

- MySQL-compatible
- Open source
- Good performance

### Disadvantages

- Differences from MySQL continue to increase over time.
- Laravel ecosystem and documentation more commonly target MySQL.

**Decision:** Rejected

---

## Option 4 — Microsoft SQL Server

### Advantages

- Enterprise-grade
- Strong BI integration
- Advanced administrative tools

### Disadvantages

- Higher licensing and operational costs.
- Less suitable for the current deployment strategy.

**Decision:** Rejected

---

## Option 5 — SQLite

### Advantages

- Zero configuration
- Excellent for testing

### Disadvantages

- Not suitable for enterprise multi-user production workloads.

**Decision:** Rejected for Production (may be used for automated testing where appropriate)

---

# Database Principles

The database architecture shall follow these principles:

- Data integrity first
- Normalized schema
- Referential integrity
- Consistent naming conventions
- Transactional consistency
- Performance through indexing
- Secure access
- Backup before destructive operations

---

# Schema Standards

The database should:

- Use InnoDB storage engine.
- Use UTF-8 encoding (`utf8mb4`).
- Use `utf8mb4_unicode_ci` (or project-approved collation).
- Use foreign keys where appropriate.
- Include `created_at` and `updated_at` timestamps.
- Use soft deletes for important business entities where justified.

---

# Naming Standards

| Item | Standard |
|------|----------|
| Tables | snake_case plural |
| Columns | snake_case |
| Foreign Keys | `<table>_id` |
| Pivot Tables | Alphabetical combination of related tables |
| Indexes | Descriptive names following Laravel conventions |

Examples:

```
institutions
academic_years
question_banks
institution_id
created_at
updated_at
```

---

# Transaction Standards

Transactions must be used for:

- Examination publishing
- Question approval workflows
- Result publication
- Bulk data imports
- Financial or other multi-step operations (if introduced)

---

# Performance Standards

The database should:

- Use indexes for frequently searched columns.
- Avoid unnecessary joins.
- Prevent N+1 query issues.
- Optimize pagination.
- Prefer eager loading when appropriate.
- Review slow queries regularly.

---

# Backup Strategy

Production databases should support:

- Daily automated backups
- Weekly full backups
- Periodic restore verification
- Off-site backup storage (where available)

Backup procedures are documented separately.

---

# Security Requirements

The database shall:

- Restrict direct production access.
- Use least-privilege database accounts.
- Protect credentials via environment variables.
- Never store plaintext passwords.
- Encrypt sensitive data when required by business rules.

---

# Consequences

## Positive

- Excellent Laravel integration
- Reliable transactional behavior
- Mature ecosystem
- Lower operational cost
- Wide hosting compatibility
- Easier onboarding for developers

## Negative

- Future migration to another DBMS may require SQL review.
- Some advanced analytical capabilities available in PostgreSQL are not leveraged.

---

# Risks

Potential risks include:

- Poor indexing
- Inefficient queries
- Uncontrolled schema growth
- Missing foreign key constraints
- Backup failures

Mitigation:

- Architecture reviews
- Query optimization
- Migration reviews
- Automated backups
- Performance testing

---

# Future Evolution

Future versions may introduce:

- Read replicas
- Database partitioning
- Query caching
- Full-text search
- External reporting database
- PostgreSQL support (if business requirements justify)

Such changes require a new ADR.

---

# Related ADRs

- ADR-001 — Use Laravel 12
- ADR-002 — Adopt Modular Monolith Architecture
- ADR-003 — Adopt Bootstrap 5
- ADR-005 — Use Spatie Permission

---

# References

- MySQL 8 Documentation
- Laravel Database Documentation
- Laravel Eloquent ORM Documentation
- InnoDB Storage Engine Documentation

---

# Revision History

| Version | Date | Description |
|----------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial database technology decision recorded |

---

# Approval

Status: Accepted

Approved By:

- Solution Architect
- Product Owner
- Technical Lead

Effective Date:

2026-08-02