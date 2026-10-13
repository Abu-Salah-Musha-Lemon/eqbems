---
document_id: DOC-007
document_name: Project Glossary
project_name: Enterprise Question Bank & Examination Management System
project_short_name: EQBEMS
version: 1.0.0
status: Draft
document_owner: Abu Salah Musha Lemon
prepared_by: System Analysis & Architecture Team
last_updated: 2026-08-02

related_documents:
  - PROJECT_MANIFEST.md
  - PRODUCT_REQUIREMENTS.md
  - BUSINESS_REQUIREMENTS.md
  - SYSTEM_ARCHITECTURE.md
---

# PROJECT GLOSSARY

> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# 1. Purpose

This glossary defines the business, academic and technical terms used throughout the EQBEMS project.

Its purpose is to ensure that all stakeholders use consistent terminology and interpret project documents in the same way.

---

# 2. How to Use This Document

Every important term used in requirements, architecture, database design, source code, UI, reports and documentation should appear in this glossary.

When introducing a new business or technical term, update this document first.

---

# 3. Business Terms

| Term | Definition |
|------|------------|
| Institution | An educational organization using EQBEMS. |
| Campus | A physical branch of an institution. |
| Faculty | A major academic division (e.g., Faculty of Science). |
| Department | A subdivision under a faculty. |
| Program | A specific academic program (e.g., B.Sc. in CSE). |
| Academic Year | A yearly academic session. |
| Semester | A defined academic period within a program. |
| Course | A unit of study offered in a semester. |
| Subject | A specific subject within a course (if your model distinguishes them). |
| Chapter | A logical section of a subject. |
| Topic | A smaller unit within a chapter. |
| Student | A learner enrolled in an institution. |
| Teacher | A faculty member responsible for teaching and assessment. |

---

# 4. Examination Terms

| Term | Definition |
|------|------------|
| Question Bank | Central repository of all approved questions. |
| Question | An individual assessment item. |
| Question Setter | User responsible for preparing questions. |
| Moderator | User responsible for reviewing and approving questions. |
| Blueprint | Rules describing question distribution by topic, marks, or difficulty. |
| Examination | An assessment conducted for one or more courses. |
| Question Paper | Final set of questions generated for an examination. |
| Marks | Numerical score awarded to a student. |
| Grade | Letter representation of academic performance. |
| GPA | Grade Point Average. |
| CGPA | Cumulative Grade Point Average. |
| Transcript | Official academic record of a student. |

---

# 5. User & Security Terms

| Term | Definition |
|------|------------|
| Authentication | Verifying the identity of a user. |
| Authorization | Determining what an authenticated user is allowed to do. |
| RBAC | Role-Based Access Control. |
| Role | A collection of permissions assigned to a user. |
| Permission | A specific action a user may perform. |
| Session | Active authenticated interaction between a user and the system. |
| Audit Log | Chronological record of important user and system activities. |

---

# 6. Technical Terms

| Term | Definition |
|------|------------|
| Laravel | PHP framework used for backend development. |
| Bootstrap | CSS framework used for the frontend. |
| Blade | Laravel templating engine. |
| Migration | Version-controlled database schema definition. |
| Seeder | Script used to populate initial data. |
| Factory | Tool for generating sample/test data. |
| Model | Represents a database entity. |
| Controller | Handles incoming HTTP requests. |
| Request | Encapsulates and validates incoming user input. |
| Middleware | Filters requests before they reach controllers. |
| Route | URL endpoint mapped to application logic. |
| Service Layer | Contains business logic separated from controllers. |
| Policy | Authorization logic for a model or resource. |
| Repository | Optional abstraction layer for data access. |
| Eloquent ORM | Laravel's Object Relational Mapping system. |

---

# 7. Database Terms

| Term | Definition |
|------|------------|
| Table | Database structure storing records. |
| Record | A single row in a table. |
| Primary Key | Unique identifier for each record. |
| Foreign Key | Field referencing another table. |
| Index | Database structure used to speed up queries. |
| Constraint | Rule enforcing data integrity. |
| Soft Delete | Logical deletion while preserving data. |

---

# 8. Development Terms

| Term | Definition |
|------|------------|
| Module | Independent functional area of the system. |
| Feature | A specific capability delivered by a module. |
| Feature ID | Unique identifier assigned to a feature (e.g., INST-001). |
| Sprint | Time-boxed development iteration. |
| MVP | Minimum Viable Product. |
| Release | Official version delivered to users. |
| Changelog | Record of changes between versions. |

---

# 9. Documentation Terms

| Term | Definition |
|------|------------|
| PRD | Product Requirements Document. |
| BRD | Business Requirements Document. |
| SRS | Software Requirements Specification. |
| RTM | Requirement Traceability Matrix. |
| ADR | Architecture Decision Record. |
| UML | Unified Modeling Language. |
| BPMN | Business Process Model and Notation. |

---

# 10. Abbreviations

| Abbreviation | Meaning |
|-------------|---------|
| EQBEMS | Enterprise Question Bank & Examination Management System |
| RBAC | Role-Based Access Control |
| API | Application Programming Interface |
| CRUD | Create, Read, Update, Delete |
| UI | User Interface |
| UX | User Experience |
| DBMS | Database Management System |
| ORM | Object Relational Mapping |
| REST | Representational State Transfer |
| JWT | JSON Web Token |
| KPI | Key Performance Indicator |
| QA | Quality Assurance |
| CI/CD | Continuous Integration / Continuous Deployment |

---

# 11. Naming Conventions

- Module names use **PascalCase** (e.g., `QuestionBank`).
- Database tables use **snake_case plural** (e.g., `question_banks`).
- Feature IDs follow `<MODULE>-<NUMBER>` (e.g., `QB-001`).
- Documents use **UPPERCASE_WITH_UNDERSCORES** (e.g., `PROJECT_SCOPE.md`).

---

# 12. Maintenance Rules

- Every new business term must be added to this glossary.
- Existing definitions should be updated if business rules change.
- Deprecated terms should be marked as obsolete rather than removed.
- This glossary should remain synchronized with all project documentation.

---

# 13. Related Documents

- PROJECT_MANIFEST.md
- PRODUCT_REQUIREMENTS.md
- BUSINESS_REQUIREMENTS.md
- FEATURE_CATALOG.md
- SYSTEM_ARCHITECTURE.md

---

# 14. Revision History

| Version | Date | Description |
|---------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial glossary created |

---

# 15. Approval

Document Status: Draft

Approval Status: Pending

Baseline Version: 1.0