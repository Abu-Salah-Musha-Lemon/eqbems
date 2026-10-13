# Stakeholders, Actors, and Roles

## Stakeholder groups

| Stakeholder | Interest / responsibility |
|---|---|
| Product owner / institutional sponsor | Priorities, policy decisions, acceptance, release boundaries |
| System administrator | Global configuration, platform health, high-privilege access |
| Organization administrator | Organization-scoped institutions and memberships if multi-org model is approved |
| Institution administrator | Institution settings, account membership, academic setup within assigned scope |
| Academic administrator | Academic years, programs, subjects, chapters, topics |
| Question setter | Create/edit own draft questions and submit for review |
| Moderator/reviewer | Review content, request changes, approve/reject under conflict-of-interest rules |
| Examination controller | Configure blueprints, generate/review/approve exams, protect answer keys |
| Teacher/marker | Access assigned paper/candidates and enter marks if this workflow is approved |
| Student/candidate | Access only assigned/published material and own result where enabled |
| Developer/operations | Maintain code, deploy, monitor, back up, and respond to incidents |

## Candidate roles currently seeded

`Super Admin`, `Organization Admin`, `Institution Admin`, `Teacher`, `Question Setter`, `Moderator`, `Exam Controller`, and `Student`.

These role labels are a starting vocabulary, not a finished access-control matrix. Seeded roles must not imply permissions until assignments are defined and tested.

## Minimum policy principles

- Deny by default.
- Separate question authoring from approval where operationally possible.
- Separate exam generation/approval from answer-key release.
- Scope access to the active institution/organization membership.
- Keep high-risk administrative privileges limited and auditable.
- Preserve actor identity on history records; prefer deactivation over hard deletion.

## Decisions requiring owner approval

1. Final role list and whether users may hold multiple roles.
2. Role scope: global, organization, institution, or academic assignment.
3. Whether an institution administrator may invite/manage only users in their institution.
4. Which actions require two-person approval.
5. Whether students and candidate-facing delivery are part of the first public release.
