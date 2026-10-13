# Module Specification: Question Bank

**Status:** Planned/scaffolding; current migrations do not define the question domain and `QuestionService` is empty.

## Goal

Store reusable, classified, reviewable question content whose exact approved version can be reused in exams without rewriting history.

## Core concepts

- Question identity/master record.
- Immutable question content version.
- Question type (e.g. MCQ, true/false, short/long answer) and difficulty.
- Subject/topic/taxonomy mappings and tags.
- Options and answer key/explanation separated from candidate-facing payloads.
- Media metadata/storage reference.
- Review/approval history with reviewer, state, timestamp, and reason.
- Status/lifecycle and author/owner scope.

## Proposed lifecycle

Draft → Submitted → Approved or Changes Requested/Rejected → New Draft Version → Approved Version → Retired. Define resubmission rules and prevent self-approval by default unless the product owner approves an exception.

## Security and integrity rules

- Store exact content by version; never overwrite an approved version that may appear in generated exams.
- Only approved, active versions are eligible for blueprint generation.
- Answer keys are loaded only by authorized workflows and never serialized to candidate-facing resources.
- Question media access follows the same permission/scope rules as the question.
- Audit content edits, reviews, approvals, and status changes.
- Search/filter queries must always apply institution and permission scope.

## Acceptance criteria

- Valid question versions can be created and edited through new versions.
- Validation prevents incompatible option/answer structures for each type.
- Approvals record reviewer and cannot silently bypass workflow.
- Cross-scope questions do not appear in search or generation.
- Candidate DTO/API does not include answer-key fields.
- An exam snapshot referencing version N remains unchanged after version N+1 is created.
- Tests cover question type rules, approvals, version history, file security, and query scope.
