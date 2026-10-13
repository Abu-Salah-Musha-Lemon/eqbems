# Entity Relationship Diagrams

This file separates the current schema from the proposed target design. Diagrams are logical and do not substitute for migration inspection.

## Current implemented relationships (migration-backed)

```mermaid
erDiagram
  USERS ||--o{ INSTITUTIONS_CREATED : creates
  USERS ||--o{ INSTITUTIONS_UPDATED : updates
  ROLES ||--o{ ROLE_HAS_PERMISSIONS : includes
  PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : assigned_to
  ROLES ||--o{ MODEL_HAS_ROLES : assigned_to
  PERMISSIONS ||--o{ MODEL_HAS_PERMISSIONS : granted_to
  USERS {
    bigint id PK
    string name
    string email UK
    string password
  }
  INSTITUTIONS {
    bigint id PK
    uuid uuid UK
    string code UK
    string name
    bigint organization_id "indexed only; no FK currently"
    bigint created_by FK
    bigint updated_by FK
    timestamp deleted_at
  }
  ROLES {
    bigint id PK
    string name
    string guard_name
  }
  PERMISSIONS {
    bigint id PK
    string name
    string guard_name
  }
  ROLE_HAS_PERMISSIONS {
    bigint role_id FK
    bigint permission_id FK
  }
  MODEL_HAS_ROLES {
    bigint role_id FK
    string model_type
    bigint model_id
  }
  MODEL_HAS_PERMISSIONS {
    bigint permission_id FK
    string model_type
    bigint model_id
  }
```

Laravel cache, queue, password reset, and session tables are omitted from the picture for readability; see `DATABASE_DICTIONARY.md`.

## Proposed domain relationship overview

```mermaid
erDiagram
  ORGANIZATIONS ||--o{ INSTITUTIONS : groups
  INSTITUTIONS ||--o{ DEPARTMENTS : contains
  INSTITUTIONS ||--o{ ACADEMIC_YEARS : defines
  DEPARTMENTS ||--o{ PROGRAMS : offers
  PROGRAMS ||--o{ SUBJECTS : includes
  SUBJECTS ||--o{ CHAPTERS : contains
  CHAPTERS ||--o{ TOPICS : contains
  SUBJECTS ||--o{ QUESTIONS : classifies
  QUESTIONS ||--o{ QUESTION_VERSIONS : versions
  QUESTION_VERSIONS ||--o{ QUESTION_OPTIONS : has
  QUESTION_VERSIONS }o--o{ TOPICS : tagged_with
  INSTITUTIONS ||--o{ EXAM_BLUEPRINTS : owns
  EXAM_BLUEPRINTS ||--o{ BLUEPRINT_RULES : defines
  EXAM_BLUEPRINTS ||--o{ GENERATION_RUNS : generates
  GENERATION_RUNS ||--o{ EXAM_VERSIONS : creates
  EXAM_VERSIONS ||--o{ EXAM_QUESTION_SNAPSHOTS : contains
  EXAM_VERSIONS ||--o{ CANDIDATE_EXAMS : assigned_to
  CANDIDATE_EXAMS ||--o{ MARKS : receives
  CANDIDATE_EXAMS ||--o| RESULTS : summarizes
```

**All entities in the second diagram are proposed logical concepts, not a statement that these tables exist.** The final ERD must be updated as domain decisions and migrations are reviewed.
