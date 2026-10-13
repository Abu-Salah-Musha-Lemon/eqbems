# Module Specification: Results and Grading

**Status:** Planned for a later release; no result/marks domain migrations are present in the current schema.

## Goal

Provide controlled marks entry, grade calculation, review, publication, and audited correction for a specific candidate/exam version.

## Core concepts

- Candidate registration/assignment to a particular exam version.
- Marking records per question/section and assessor.
- Grading scheme with explicit boundaries, pass rules, rounding, and missing/absent handling.
- Result aggregate and publication state.
- Review/approval and correction history.

## Rules

- Use fixed-precision decimal columns and explicit rounding policy.
- Define whether partial marks, negative marking, moderation, moderation adjustments, rechecks, and absent candidates are supported.
- Only authorized markers can edit assigned marks; reviewer privileges should be separate where feasible.
- Published results cannot be silently overwritten; corrections need a reason, actor, timestamp, and preserved prior value.
- Candidate-facing views expose only the candidate's own published result unless policy explicitly authorizes broader access.
- Exports must enforce the same scope and hide internal notes/private data.

## Acceptance criteria

- Valid marks cannot exceed question maximums unless explicit rules permit it.
- Grade calculations are deterministic at boundaries and tested with decimal cases.
- Unauthorized mark changes and cross-candidate reads are denied.
- State transitions are enforced and audited.
- Published result corrections preserve history and require a reason.
- Backups/restores preserve result and audit consistency.
