# Business Requirements Document (BRD)

**Status:** Working baseline; stakeholders must validate the workflows before production implementation.

## Business objective

Create a controlled workflow for managing institution and academic reference data, question lifecycle, exam design/generation, and (in a later release) marks/results, with auditable decisions and scoped permissions.

## Business requirements

| ID | Requirement | Priority | Release target | Acceptance evidence |
|---|---|---:|---|---|
| BR-001 | Administrators can manage accounts and access rights | Must | Foundation | Positive and denied-access tests for user/role changes |
| BR-002 | Authorized staff can create and maintain institution profiles | Must | Foundation | Institution CRUD tests, validation, authorization, soft-delete behavior |
| BR-003 | Institution data access is scoped according to the approved tenancy model | Must | Foundation design gate | Written tenancy decision and cross-institution denial tests |
| BR-004 | Academic units, subjects, chapters, and topics can be structured consistently | High | V1 | CRUD tests and foreign-key/integrity tests |
| BR-005 | Questions can be classified and searched by taxonomy, difficulty, type, and status | High | V1 | Question-bank acceptance suite |
| BR-006 | Question creation, review, revision, approval, and retirement are traceable | High | V1 | Workflow transition tests and actor/timestamp history |
| BR-007 | Blueprints can express question counts/marks by approved classifications | High | V1 | Validation and feasibility tests for blueprint rules |
| BR-008 | Exam generation selects eligible questions and records a reproducible immutable snapshot | High | V1/V2 gate | Re-generation/review tests; historic paper remains unchanged after question edits |
| BR-009 | Answer keys are not returned by student-facing queries or exports | Critical | Before any candidate delivery | Explicit leakage tests across HTML/API/export/cache/log paths |
| BR-010 | Authorized users can record marks and calculate results using approved grading rules | High | V2 | Decimal precision, calculation, override/audit tests |
| BR-011 | Results can be reviewed and published under a controlled workflow | High | V2 | State-transition and visibility tests |
| BR-012 | Sensitive business actions are recorded in audit logs | High | Before production | Log completeness and access tests |
| BR-013 | Operations staff can back up and restore the application and database | Must | Before production | Documented restore drill with measured result |

## Core workflows (target)

### Question lifecycle

Draft → Submitted for review → Approved or returned for changes → Revised/versioned → Retired. Only approved, active versions should be eligible for exam generation. The project must define whether reviewers may approve their own questions; the recommended default is no.

### Exam preparation

Exam controller selects an academic context, creates a blueprint, validates whether the question bank can satisfy it, generates a draft, reviews the selected question snapshot, approves the paper, and controls access. Generation must not mutate question-bank content.

### Result lifecycle (later release)

Candidate list locked → marks entered → validation/review → approved → published → corrected only through a controlled, audited amendment process.

## Business rules to decide with stakeholders

- Whether one deployment contains multiple organizations, multiple institutions, or both.
- Whether institution codes are globally unique or unique within an organization.
- Whether question content may be shared across institutions.
- Which roles can create, review, approve, generate, print, and publish an exam.
- How question reuse, randomization, variants, negative marking, rounding, absent candidates, and result correction work.
- Retention period for questions, exams, candidate responses, results, and audit records.
- Whether candidate delivery is in scope for the first production release.

No schema should be finalized for these unresolved rules without a documented decision.
