# Module Specification: Blueprint and Examination

**Status:** Planned; no blueprint/exam-generation migrations or fully implemented workflow are present.

## Goal

Turn approved blueprint constraints and eligible question versions into a reviewable, auditable paper with controlled access and immutable history.

## Blueprint requirements

A blueprint may define total marks, duration, subject/topic/difficulty/type distributions, number of questions, required/optional sections, and selection rules. Each rule needs validation for non-negative counts/marks, coherent totals, and available eligible questions.

## Generation workflow

1. Validate blueprint and candidate question pool.
2. Explain infeasibility instead of silently generating an invalid paper.
3. Select eligible approved versions under defined randomization/no-repeat constraints.
4. Store generation parameters and exact selected question-version IDs/content snapshot.
5. Allow authorized review; record the reviewer and decisions.
6. Approve/issue a version; later changes create a new version rather than mutating the issued one.
7. Keep answer keys restricted to approved roles and state transitions.

## Security rules

- Require explicit permission and scope for generate, preview, approve, print, export, and answer-key access.
- Do not rely on obscured URLs or hidden UI controls.
- Never expose answer keys to candidate-facing queries.
- Log access to high-sensitivity exam content where required by policy.
- Define how generated files are stored, encrypted, expired, and downloaded.

## Acceptance criteria

- Blueprint totals/distributions are mathematically consistent.
- Generation handles insufficient question pools deterministically and reports why.
- Generated paper can be reproduced/audited from stored inputs.
- Edits to source questions do not mutate an approved exam snapshot.
- Unauthorized users cannot preview/export/obtain answer keys.
- Concurrent generation/approval cannot create duplicate or inconsistent states.
