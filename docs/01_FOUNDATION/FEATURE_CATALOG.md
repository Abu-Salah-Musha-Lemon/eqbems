---
document_id: DOC-003
document_name: Feature Catalog
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

# FEATURE CATALOG

> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# Purpose

This document contains the official registry of every feature planned for EQBEMS.

Every feature has:

- Unique Feature ID
- Module
- Feature Name
- Priority
- Version
- Status

This document is the master reference for planning, development, testing, documentation, and future maintenance.

---

# Feature Status Legend

| Status | Meaning |
|---------|----------|
| Planned | Feature approved but not started |
| In Progress | Development ongoing |
| Completed | Feature implemented |
| Testing | Under testing |
| Deferred | Postponed |
| Future | Planned for future versions |

---

# Priority Legend

| Priority | Meaning |
|----------|----------|
| Critical | System cannot work without it |
| High | Required for MVP |
| Medium | Important but not blocking |
| Low | Nice to have |

---

# Version Legend

| Version | Meaning |
|----------|----------|
| V1 | Community Edition |
| V2 | Professional Edition |
| V3 | Enterprise Edition |
| Future | Long-term roadmap |

---

# Module Index

| Module Code | Module |
|-------------|--------|
| AUTH | Authentication |
| DASH | Dashboard |
| USER | User Management |
| ROLE | Role Management |
| PERM | Permission |
| INST | Institution |
| ACAD | Academic Structure |
| QB | Question Bank |
| BP | Blueprint |
| EXAM | Examination |
| RES | Result |
| RPT | Reports |
| SET | Settings |
| NOTI | Notification |
| LOG | Activity Log |

---

# AUTHENTICATION

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| AUTH-001 | User Login | Critical | V1 | Completed |
| AUTH-002 | User Logout | Critical | V1 | Completed |
| AUTH-003 | Forgot Password | High | V1 | Planned |
| AUTH-004 | Password Reset | High | V1 | Planned |
| AUTH-005 | Email Verification | Medium | V1 | Planned |

---

# DASHBOARD

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| DASH-001 | Dashboard Home | Critical | V1 | Completed |
| DASH-002 | Statistics Cards | High | V1 | Planned |
| DASH-003 | Quick Actions | High | V1 | Planned |
| DASH-004 | Recent Activities | Medium | V1 | Planned |
| DASH-005 | Notifications Widget | Medium | V2 | Future |

---

# USER MANAGEMENT

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| USER-001 | Create User | Critical | V1 | Planned |
| USER-002 | Update User | Critical | V1 | Planned |
| USER-003 | Delete User | High | V1 | Planned |
| USER-004 | User Profile | High | V1 | Planned |
| USER-005 | User Status | High | V1 | Planned |

---

# ROLE & PERMISSION

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| ROLE-001 | Create Role | Critical | V1 | Completed |
| ROLE-002 | Edit Role | High | V1 | Completed |
| ROLE-003 | Delete Role | Medium | V1 | Planned |
| PERM-001 | Assign Permission | Critical | V1 | Completed |
| PERM-002 | Revoke Permission | High | V1 | Planned |

---

# INSTITUTION

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| INST-001 | Create Institution | Critical | V1 | In Progress |
| INST-002 | Edit Institution | Critical | V1 | In Progress |
| INST-003 | Delete Institution | High | V1 | Planned |
| INST-004 | Institution Logo | High | V1 | Planned |
| INST-005 | Institution Code | High | V1 | Planned |
| INST-006 | Institution Settings | High | V1 | Planned |
| INST-007 | Search Institution | Medium | V1 | Planned |
| INST-008 | Institution Status | Medium | V1 | Planned |
| INST-009 | Institution Audit Log | Medium | V2 | Future |
| INST-010 | Export Institution | Low | V2 | Future |

---

# ACADEMIC STRUCTURE

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| ACAD-001 | Academic Year | Critical | V1 | Planned |
| ACAD-002 | Faculty | Critical | V1 | Planned |
| ACAD-003 | Department | Critical | V1 | Planned |
| ACAD-004 | Program | High | V1 | Planned |
| ACAD-005 | Semester | High | V1 | Planned |
| ACAD-006 | Course | High | V1 | Planned |
| ACAD-007 | Subject | High | V1 | Planned |
| ACAD-008 | Chapter | Medium | V1 | Planned |
| ACAD-009 | Topic | Medium | V1 | Planned |

---

# QUESTION BANK

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| QB-001 | Create Question | Critical | V1 | Planned |
| QB-002 | Edit Question | Critical | V1 | Planned |
| QB-003 | Delete Question | High | V1 | Planned |
| QB-004 | Question Approval | Critical | V1 | Planned |
| QB-005 | Question Versioning | High | V2 | Future |
| QB-006 | Import Questions | Medium | V2 | Future |
| QB-007 | Export Questions | Medium | V2 | Future |
| QB-008 | Bloom's Taxonomy | Medium | V2 | Future |
| QB-009 | Difficulty Level | High | V1 | Planned |
| QB-010 | Duplicate Detection | Low | Future | Future |

---

# BLUEPRINT

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| BP-001 | Create Blueprint | High | V1 | Planned |
| BP-002 | Marks Distribution | High | V1 | Planned |
| BP-003 | Learning Outcome Mapping | Medium | V2 | Future |

---

# EXAMINATION

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| EXAM-001 | Create Exam | Critical | V2 | Planned |
| EXAM-002 | Schedule Exam | High | V2 | Planned |
| EXAM-003 | Generate Question Paper | Critical | V2 | Planned |
| EXAM-004 | Publish Exam | Medium | V2 | Planned |

---

# RESULT

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| RES-001 | Marks Entry | High | V2 | Planned |
| RES-002 | GPA Calculation | High | V2 | Planned |
| RES-003 | Publish Result | High | V2 | Planned |
| RES-004 | Transcript | Medium | V3 | Future |

---

# REPORTS

| ID | Feature | Priority | Version | Status |
|----|----------|----------|----------|---------|
| RPT-001 | Institution Reports | Medium | V2 | Planned |
| RPT-002 | Academic Reports | Medium | V2 | Planned |
| RPT-003 | Exam Reports | Medium | V2 | Planned |
| RPT-004 | Result Reports | Medium | V2 | Planned |

---

# FUTURE FEATURES

| ID | Feature | Version |
|----|----------|----------|
| FUT-001 | AI Question Generator | Future |
| FUT-002 | Online CBT | Future |
| FUT-003 | OMR Integration | Future |
| FUT-004 | Mobile Application | Future |
| FUT-005 | REST API | Future |
| FUT-006 | LMS Integration | Future |
| FUT-007 | SMS Gateway | Future |
| FUT-008 | Email Notification | Future |
| FUT-009 | Multi-language Support | Future |

---

# Feature Summary

| Category | Total |
|----------|------:|
| Foundation | 20 |
| Institution | 10 |
| Academic | 9 |
| Question Bank | 10 |
| Blueprint | 3 |
| Examination | 4 |
| Result | 4 |
| Reports | 4 |
| Future | 9 |

> **Note:** This catalog is a living document. New features may be added through the project's change management process. Every new feature must receive a unique Feature ID before development begins.