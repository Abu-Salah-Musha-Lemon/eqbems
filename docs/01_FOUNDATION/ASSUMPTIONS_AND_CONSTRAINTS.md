---
document_id: DOC-009
document_name: Assumptions and Constraints
project_name: Enterprise Question Bank & Examination Management System
project_short_name: EQBEMS
version: 1.0.0
status: Draft
document_owner: Abu Salah Musha Lemon
prepared_by: System Analysis & Architecture Team
last_updated: 2026-08-02

related_documents:
  - PROJECT_MANIFEST.md
  - PROJECT_OVERVIEW.md
  - PRODUCT_REQUIREMENTS.md
  - BUSINESS_REQUIREMENTS.md
  - PROJECT_SCOPE.md
  - RISK_REGISTER.md
---

# ASSUMPTIONS AND CONSTRAINTS

> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# 1. Purpose

This document records the assumptions and constraints that influence the planning, design, development, testing, deployment, and maintenance of the EQBEMS project.

These statements provide a common understanding for all project stakeholders and help identify potential risks if assumptions prove false or constraints change.

---

# 2. Objectives

The objectives of this document are to:

- Define planning assumptions.
- Identify project limitations.
- Improve project transparency.
- Support risk management.
- Assist decision-making.
- Prevent unrealistic expectations.

---

# 3. Project Assumptions

## A-001 Framework

Laravel 12 will remain the primary backend framework throughout Version 1.

---

## A-002 Programming Language

PHP 8.3 or later will be available in the deployment environment.

---

## A-003 Database

MySQL will be used as the primary relational database management system.

---

## A-004 Frontend

Bootstrap 5, Blade, and JavaScript will be used for the user interface.

---

## A-005 Authentication

Laravel Breeze will provide the initial authentication system.

---

## A-006 Authorization

Role-Based Access Control (RBAC) will be implemented using the Spatie Permission package.

---

## A-007 Development Methodology

Development will follow an iterative and incremental approach with documentation updated alongside implementation.

---

## A-008 Version Control

Git and GitHub will be used for source code management and collaboration.

---

## A-009 Deployment Environment

The production environment will support PHP, Composer, MySQL, and required Laravel extensions.

---

## A-010 Documentation

Project documentation will be maintained as a living artifact and updated whenever requirements or implementation change.

---

# 4. Business Assumptions

- Educational institutions require centralized examination management.
- Users have basic computer literacy.
- Internet access is available for all administrative users.
- Institutions are willing to migrate from manual processes.
- Organizational approval processes remain stable.

---

# 5. Technical Assumptions

- Server infrastructure is sufficient for Version 1.
- Standard Laravel packages will satisfy most functional requirements.
- Modern web browsers will be used by end users.
- HTTPS will be available in production.
- Daily database backups can be configured.

---

# 6. Project Constraints

## C-001 Development Team

Development is initially performed by a small team.

---

## C-002 Budget

Development budget is limited.

---

## C-003 Timeline

Features will be delivered incrementally across multiple versions.

---

## C-004 Scope

Only approved Version 1 features will be implemented.

---

## C-005 Technology Stack

The project must remain compatible with the selected technology stack unless officially approved.

---

## C-006 Database

The primary database platform is MySQL.

---

## C-007 Architecture

The project will follow a Modular Monolith architecture during initial releases.

---

## C-008 Browser Support

The application will target current versions of major modern browsers.

---

## C-009 Internet Dependency

The web application requires an active network connection for normal operation.

---

## C-010 Documentation

No feature should be implemented without corresponding documentation updates.

---

# 7. Operational Constraints

- Limited testing resources.
- Limited infrastructure for staging.
- Limited availability of real production data during development.
- Incremental user training.

---

# 8. Regulatory Constraints

The system should support institutional policies and remain adaptable to future regulatory requirements.

---

# 9. Change Management

If an assumption becomes invalid or a constraint changes:

1. Record the change.
2. Assess project impact.
3. Update related documentation.
4. Review affected requirements.
5. Update the Risk Register if necessary.
6. Obtain approval before implementation.

---

# 10. Traceability

Each assumption and constraint should be traceable to:

- Business Requirements
- Product Requirements
- Architecture Decisions
- Risk Register
- Project Scope

---

# 11. Review Schedule

This document should be reviewed:

- Before each major release.
- After significant scope changes.
- During architecture reviews.
- Before production deployment.

---

# 12. Related Documents

- PROJECT_MANIFEST.md
- PROJECT_SCOPE.md
- PRODUCT_REQUIREMENTS.md
- BUSINESS_REQUIREMENTS.md
- RISK_REGISTER.md
- SYSTEM_ARCHITECTURE.md

---

# 13. Revision History

| Version | Date | Description |
|---------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial document created |

---

# 14. Approval

Document Status: Draft

Approval Status: Pending

Baseline Version: 1.0