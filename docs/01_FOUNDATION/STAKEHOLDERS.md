---
document_id: DOC-006
document_name: Stakeholder Analysis Document
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
---

# STAKEHOLDER ANALYSIS

> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# 1. Purpose

This document identifies all stakeholders involved in the EQBEMS project, defines their responsibilities, expectations, authority, and interaction with the system.

The purpose is to ensure that every stakeholder's needs are considered during analysis, design, development, testing, deployment, and maintenance.

---

# 2. Objectives

The stakeholder analysis aims to:

- Identify everyone affected by the system.
- Understand stakeholder expectations.
- Define responsibilities.
- Reduce communication gaps.
- Improve requirement gathering.
- Support project planning.
- Improve user acceptance.

---

# 3. Stakeholder Categories

Stakeholders are divided into two major groups:

## Internal Stakeholders

People who directly use or manage the system.

## External Stakeholders

People or organizations who benefit from or influence the system but do not necessarily operate it daily.

---

# 4. Internal Stakeholders

## 4.1 Super Administrator

### Description

Owns and controls the entire application.

### Responsibilities

- Manage all institutions
- System configuration
- Global settings
- Security policies
- User administration
- Backup monitoring

### System Access

Full

---

## 4.2 Institution Administrator

### Responsibilities

- Manage institution profile
- Manage institution users
- Configure academic information
- Monitor institution activities

### Access Level

Institution Only

---

## 4.3 Academic Administrator

### Responsibilities

- Academic year
- Faculty
- Department
- Program
- Semester
- Subject

### Access Level

Academic Module

---

## 4.4 Teacher

### Responsibilities

- Manage assigned courses
- Create questions
- Review question bank
- Participate in examinations

---

## 4.5 Question Setter

### Responsibilities

- Create questions
- Edit draft questions
- Submit questions for approval

---

## 4.6 Moderator

### Responsibilities

- Review submitted questions
- Approve questions
- Reject questions
- Request corrections

---

## 4.7 Examination Committee

### Responsibilities

- Prepare examinations
- Select approved questions
- Generate question papers
- Publish examination schedules

---

## 4.8 Result Officer

### Responsibilities

- Enter marks
- Verify results
- Publish results
- Generate transcripts

---

# 5. External Stakeholders

## Students

### Responsibilities

- View examination schedules
- View published results
- Download transcripts (future)

---

## Educational Institution

### Interest

- Improve academic quality
- Reduce administrative workload
- Improve security

---

## Regulatory Authority

Examples:

- National University
- University Grants Commission (UGC)
- Education Boards

### Interest

- Compliance
- Reporting
- Standardization

---

## Software Maintenance Team

Responsible for:

- Maintenance
- Updates
- Security fixes
- Deployment
- Database optimization

---

# 6. Stakeholder Expectations

| Stakeholder | Primary Expectation |
|-------------|--------------------|
| Super Admin | Full control |
| Institution Admin | Easy administration |
| Academic Admin | Academic management |
| Teacher | Easy question creation |
| Moderator | Secure approval process |
| Exam Committee | Fast exam preparation |
| Result Officer | Accurate result processing |
| Student | Quick access to results |
| Institution | Efficient operations |

---

# 7. Stakeholder Responsibilities Matrix

| Stakeholder | Create | Review | Approve | View | Configure |
|--------------|:------:|:------:|:-------:|:----:|:---------:|
| Super Admin | ✓ | ✓ | ✓ | ✓ | ✓ |
| Institution Admin | ✓ | ✓ | ✓ | ✓ | ✓ |
| Academic Admin | ✓ | ✓ | | ✓ | ✓ |
| Teacher | ✓ | | | ✓ | |
| Question Setter | ✓ | | | ✓ | |
| Moderator | | ✓ | ✓ | ✓ | |
| Exam Committee | ✓ | ✓ | ✓ | ✓ | |
| Result Officer | ✓ | ✓ | | ✓ | |
| Student | | | | ✓ | |

---

# 8. Communication Matrix

| Stakeholder | Communication Type | Frequency |
|-------------|-------------------|-----------|
| Super Admin | Project Review | Monthly |
| Institution Admin | Operational Updates | Weekly |
| Teachers | Academic Updates | Weekly |
| Moderator | Approval Notifications | Daily |
| Exam Committee | Examination Planning | Weekly |
| Result Officer | Result Processing | As Needed |
| Students | Result Notifications | Event Based |

---

# 9. Power–Interest Matrix

## High Power / High Interest

- Super Administrator
- Institution Administrator

These stakeholders should be managed closely.

---

## High Power / Low Interest

- Regulatory Authorities

Keep satisfied.

---

## Low Power / High Interest

- Teachers
- Moderators
- Students

Keep informed.

---

## Low Power / Low Interest

- External Visitors

Monitor with minimum effort.

---

# 10. Stakeholder Risks

Potential risks include:

- Resistance to system adoption
- Lack of training
- Incorrect permissions
- Poor communication
- Requirement misunderstandings
- Process deviations

---

# 11. Risk Mitigation

- User training
- Documentation
- Role-based access control
- Audit logging
- Approval workflows
- Regular communication

---

# 12. Success Criteria

Stakeholder management will be considered successful if:

- All stakeholders understand their responsibilities.
- Access permissions are correctly implemented.
- Business processes are followed.
- Users adopt the system successfully.
- Communication gaps are minimized.

---

# 13. Related Documents

- PROJECT_OVERVIEW.md
- PRODUCT_REQUIREMENTS.md
- BUSINESS_REQUIREMENTS.md
- FEATURE_CATALOG.md
- SYSTEM_ARCHITECTURE.md

---

# 14. Approval

Document Status: Draft

Approval Status: Pending

Baseline Version: 1.0