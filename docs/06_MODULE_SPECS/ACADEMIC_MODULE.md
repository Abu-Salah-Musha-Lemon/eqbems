# Module Specification: Academic Structure

**Status:** Planned; no corresponding domain migrations are present in the current schema.

## Goal

Represent the academic context used to classify questions, build blueprints, register candidates, and report outcomes. Do not implement a fixed hierarchy until stakeholders confirm which levels are mandatory for schools, colleges, universities, and training providers.

## Candidate entities

- Academic year/period and semester/term.
- Faculty/school and department.
- Program/course of study and batch/cohort.
- Subject/course unit, chapter, and topic/outcome.
- Optional subject offering/enrollment relationships if class membership is required.

These names are design candidates. Resolve overlaps (for example, “course” vs “subject”) in the glossary before migrations.

## Rules

- Every record has explicit institution/tenant scope.
- Parent-child relationships must be constrained in the database.
- Periods have validated start/end dates and cannot overlap where business rules forbid overlap.
- Code uniqueness is scoped explicitly (global, institution, or parent scope).
- Deleting a parent with child questions/exams must be blocked, retired, or handled through a reviewed archival strategy.
- Question classification should survive changes to display labels; use stable IDs, not free-form strings, for joins.

## Implementation sequence

1. Stakeholder-approved ERD and glossary.
2. Migrations, indexes, foreign keys, delete behavior.
3. Models/relationships and factories.
4. Policies and institution-scoped queries.
5. Request validation and CRUD workflows.
6. Feature tests for valid/invalid hierarchy and scope isolation.
7. Documentation update and data dictionary/ERD review.

## Acceptance criteria

A fresh database can create a valid structure, rejects invalid relationships/duplicate codes/invalid dates, prevents unauthorized cross-institution access, and preserves academic history referenced by questions/exams.
