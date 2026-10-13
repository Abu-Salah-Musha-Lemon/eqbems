---
document_id: DOC-030
document_name: DATABASE_DESIGN.md
project_name: Enterprise Question Bank & Examination Management System (EQBEMS)
version: 3.0.0
status: Draft for implementation review
document_owner: Solution Architecture Team
document_type: Technical Architecture
last_reviewed: 2026-10-13
related_documents:
  - PROJECT_MANIFEST.md
  - docs/01_FOUNDATION/01_PROJECT_OVERVIEW.md
  - docs/01_FOUNDATION/02_BUSINESS_REQUIREMENTS.md
  - docs/01_FOUNDATION/FEATURE_CATALOG.md
  - docs/01_FOUNDATION/PROJECT_SCOPE.md
  - docs/01_FOUNDATION/PROJECT_STANDARDS.md
  - docs/02_ARCHITECTURE/SYSTEM_ARCHITECTURE.md
  - docs/02_ARCHITECTURE/ADR-004-use-mysql.md
  - docs/02_ARCHITECTURE/ADR-005-use-spatie-permission.md
---

# Database Design

**Enterprise Question Bank & Examination Management System (EQBEMS)**

> This is the canonical logical database design for the repository. It distinguishes the schema that exists in migrations today from the target schema described for planned modules. A table marked **Proposed** is not present in the current migration set and must not be treated as deployed.

## 1. Purpose and scope

This document specifies the database architecture, entity model, naming and type rules, constraints, lifecycle handling, security requirements, indexing strategy, migration order, and implementation checklist for EQBEMS.

The design supports the documented product domains:

- Authentication, user accounts, roles, and permissions.
- Institution administration and institution-specific configuration.
- Academic structures: faculties, departments, programs, batches, academic years, semesters, subjects, chapters, and topics.
- Question bank: question formats, categories, difficulty and taxonomy lookups, immutable content versions, options, answers, media, tags, reviews, and approvals.
- Blueprint-driven exam planning, generation, immutable exam versions, and question selection snapshots.
- Student enrollment, candidate rosters, result entry, grade calculation, result publication, audit trails, and notifications as later modules are delivered.

The system is a Laravel modular monolith backed by one relational database. Modules are logical boundaries, not separate databases.

### 1.1 What was inspected

This design was prepared from the repository's `docs/` folder, `PROJECT_MANIFEST.md`, current Laravel migrations, Eloquent models, seeders, and database configuration. The repository is currently at foundation / institution-module development stage; domain schemas for academic, question-bank, blueprint, examination, and result features are predominantly planned rather than migrated.

### 1.2 Status labels

| Label | Meaning |
|---|---|
| **Implemented** | Defined by a migration currently present in `database/migrations/`. This means code exists; it does not by itself prove that every environment has run it. |
| **Proposed — V1** | A design target for the first product release, consistent with the scope and feature catalog. It still requires migrations and tests. |
| **Proposed — V2** | Intended for examination/result delivery identified in the roadmap. |
| **Future / optional** | A capability explicitly deferred or included to support later growth. |
| **Decision required** | A documented design inconsistency or dependency that must be resolved before the affected migration is created. |

## 2. Architecture and technology

| Concern | Standard |
|---|---|
| Application | Laravel 12, PHP 8.3+ |
| ORM and schema management | Eloquent ORM and Laravel migrations |
| Target production database | MySQL 8.x, InnoDB |
| Character set | `utf8mb4` |
| Preferred collation | `utf8mb4_unicode_ci` unless a reviewed language/search requirement needs another collation |
| Time storage | Store timestamps in UTC; convert to an institution/user timezone only at the application boundary |
| Authorization | Laravel policies plus Spatie Laravel Permission |
| Architecture | Modular monolith, shared relational database |
| Source of truth | Migrations for deployed schema; this document for the intended target and the decisions required to reach it |

**Configuration alignment required:** `config/database.php` currently defaults `DB_CONNECTION` to `sqlite`, while ADR-004 selects MySQL as the production database. Keep SQLite for isolated tests if useful, but explicitly set and verify MySQL for production and integration testing before relying on MySQL-specific constraints, JSON behavior, collations, or query plans.

## 3. Design principles

1. Use relational normalization (normally 3NF) for transactional facts and reference data.
2. Enforce relationships with database foreign keys, not only application validation, wherever the relationship is not polymorphic.
3. Keep controllers thin; validate with Form Requests, orchestrate with services, authorize with policies, and transact multi-row business operations.
4. Use stable numeric primary keys internally and UUIDs for public-facing route identifiers where appropriate.
5. Prefer lookup tables over database `ENUM` for business classifications that may change or be institution-configurable.
6. Store question and exam versions as immutable records. Historical exams must not change when a reusable question is edited later.
7. Preserve answer-key confidentiality: the learner-facing query path must never load answer-key fields.
8. Use exact decimal types for marks, credits, percentages, and grade points; do not use floating-point columns for academic calculations.
9. Use soft deletes for recoverable master-data records; do not soft-delete or rewrite immutable audit/history records to hide history.
10. Avoid storing files as database blobs. Store file bytes in a configured filesystem/object store and store metadata/path/checksum in the database.
11. Add indexes based on expected filters, joins, uniqueness, and measured query plans. Avoid adding indexes without a query or constraint need.
12. Multi-tenant or institution-scoped reads must enforce tenant/institution access in the policy/service layer as well as through foreign-key relationships.

## 4. Naming conventions and common column profiles

### 4.1 Names

- Tables: lowercase plural `snake_case`, e.g. `academic_years`, `exam_blueprint_rules`.
- Columns: lowercase `snake_case`, e.g. `institution_id`, `approved_at`.
- Foreign keys: `<referenced_singular>_id`, e.g. `question_version_id`.
- Pivot tables: meaningful relationship name, e.g. `question_version_topics`.
- Indexes: `<table>_<column(s)>_index` or Laravel's generated naming when within database identifier limits.
- Unique constraints: `<table>_<column(s)>_unique`.
- Check constraints: `<table>_<rule>_check`.

### 4.2 Standard data types

| Logical value | MySQL type | Rule |
|---|---|---|
| Internal identifier | `BIGINT UNSIGNED` | `AUTO_INCREMENT` primary key for normal domain tables |
| Public identifier | `CHAR(36)` or Laravel `UUID` equivalent | Unique UUID; use where a public URL/API identifier is needed |
| Short label/code | `VARCHAR(n)` | Set an explicit, business-appropriate maximum |
| Long question/explanation/instructions | `LONGTEXT` or `TEXT` | Use `LONGTEXT` for potentially large formatted question content |
| Money-like academic values | `DECIMAL(p,s)` | Marks commonly `DECIMAL(8,2)`; credits/grade points use suitable fixed scale |
| Boolean | `BOOLEAN` / `TINYINT(1)` | Use for true/false flags, not complex lifecycle state |
| Calendar date | `DATE` | Use for academic periods and scheduled dates |
| Event timestamp | `TIMESTAMP` or `DATETIME` | Define timezone behavior and nullability intentionally |
| Flexible bounded metadata | `JSON` | Only for snapshots/configuration that cannot be represented cleanly in normalized rows; index only when needed |
| Binary large object | Avoid in transactional tables | Store file bytes outside DB; persist URI/path, MIME type, size, checksum |

### 4.3 Common column profiles

The domain table specifications below name the business-specific columns. Apply the corresponding shared profile to each table; the profile is part of that table's schema, not an optional suggestion.

| Profile | Required columns | Apply to |
|---|---|---|
| **A — Mutable domain master** | `id`, `uuid` where externally addressable, `created_at`, `updated_at`; `deleted_at` for recoverable records; `created_by` / `updated_by` where operator traceability matters | Institutions and academic/question/exam configuration masters |
| **B — Relationship/pivot** | Composite or surrogate key as appropriate, both foreign keys, relationship attributes, `created_at`; add `updated_at` or `deleted_at` only if the relation itself is editable/history-preserving | Tags, topics, enrollments, scoped memberships |
| **C — Immutable version/history** | `id`, stable parent FK, version/sequence number or event timestamp, immutable payload, actor FK where available, `created_at`; no routine update/delete path | Question versions, exam versions, review/approval history, generation records, audit logs |
| **D — Laravel infrastructure** | Follow Laravel/package conventions exactly | Auth, cache, queue, password-reset, session, notification, and Spatie tables |

For every actor column (`created_by`, `updated_by`, `approved_by`, `reviewer_id`, etc.), use `BIGINT UNSIGNED` to match `users.id`; choose `RESTRICT` or `SET NULL` based on whether the history must survive user deletion. Prefer deactivating user accounts to deleting them.

## 5. Current implementation inventory

The following tables are defined in the migrations in the supplied repository. They are the implemented baseline and must be checked against the actual environment with `php artisan migrate:status` before deployment.

### 5.1 `users` — Implemented

| Column | Type / constraint | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK, auto-increment | Internal user ID |
| `name` | `VARCHAR(255)`, not null | Display name |
| `email` | `VARCHAR(255)`, not null, unique | Login/account identity; enforce normalized case behavior intentionally |
| `email_verified_at` | `TIMESTAMP`, nullable | Email verification state |
| `password` | `VARCHAR(255)`, not null | Password hash only; never plaintext |
| `remember_token` | `VARCHAR(100)`, nullable | Laravel remember-me token |
| `created_at`, `updated_at` | timestamps, nullable per Laravel | Standard Laravel timestamps |

**Model alignment:** `App\Models\User` currently fills `name`, `email`, and `password`; it hides password and remember token and casts passwords as hashed. The model should use Spatie's `HasRoles` trait before calling package role methods such as `assignRole()`; verify this because the current `User` model does not include the trait.

### 5.2 `password_reset_tokens` — Implemented

| Column | Type / constraint | Notes |
|---|---|---|
| `email` | `VARCHAR(255)` PK | Account lookup key |
| `token` | `VARCHAR(255)` | Reset token material; protect reads and logs |
| `created_at` | timestamp, nullable | Expiry is enforced by Laravel configuration/application behavior |

### 5.3 `sessions` — Implemented

| Column | Type / constraint | Notes |
|---|---|---|
| `id` | `VARCHAR(255)` PK | Session identifier |
| `user_id` | `BIGINT UNSIGNED`, nullable, indexed | No FK is currently declared in the migration |
| `ip_address` | `VARCHAR(45)`, nullable | IPv4/IPv6 address |
| `user_agent` | `TEXT`, nullable | Client description |
| `payload` | `LONGTEXT` | Serialized session payload; restrict DB access |
| `last_activity` | integer, indexed | Laravel session expiry/cleanup support |

### 5.4 Cache and queue infrastructure — Implemented

| Table | Key columns / constraints | Purpose |
|---|---|---|
| `cache` | `key` PK, `value` medium text, indexed `expiration` | Database cache driver |
| `cache_locks` | `key` PK, `owner`, indexed `expiration` | Atomic cache locks |
| `jobs` | `id` PK, indexed `queue`, payload, attempts, reservation/availability/creation timestamps stored as integers | Database queue |
| `job_batches` | `id` string PK; job counters, failed IDs, options, timestamps | Queue batch tracking |
| `failed_jobs` | `id` PK, unique `uuid`, connection, queue, payload, exception, `failed_at` | Failed-job diagnostics; restrict exception payload access |

### 5.5 Spatie Laravel Permission tables — Implemented

Current migration creates the standard package tables using `config/permission.php`; `teams` is set to `false`.

| Table | Main columns / keys | Relationship |
|---|---|---|
| `permissions` | `id`, `name`, `guard_name`, timestamps; unique (`name`, `guard_name`) | Permission catalogue |
| `roles` | `id`, `name`, `guard_name`, timestamps; unique (`name`, `guard_name`) when teams are disabled | Role catalogue |
| `role_has_permissions` | `role_id`, `permission_id`; composite PK; FKs cascade on delete | Many-to-many roles and permissions |
| `model_has_roles` | `role_id`, `model_type`, `model_id`; composite PK; model ID/type index; role FK | Polymorphic role assignment |
| `model_has_permissions` | `permission_id`, `model_type`, `model_id`; composite PK; model ID/type index; permission FK | Polymorphic direct permission assignment |

The model assignment tables are polymorphic; MySQL cannot add a normal FK from `model_id` to every possible model table. Keep these relationships under package management. If per-institution or per-organization role scoping is required, make an explicit ADR and configure Spatie teams or a scoped membership strategy; do not assume the current global-role schema supplies tenant isolation.

### 5.6 `institutions` — Implemented

This describes the actual migration, not the older conflicting institution table example in the prior design draft.

| Column | Type / constraint | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal identifier |
| `uuid` | UUID, unique | Public route key; `Institution` model uses `HasUuids` and routes by `uuid` |
| `code` | `VARCHAR(50)`, unique, not null | Institution code; currently globally unique, including soft-deleted rows |
| `name` | `VARCHAR(255)`, not null | Official name |
| `email` | `VARCHAR(255)`, nullable | Official email |
| `phone` | `VARCHAR(30)`, nullable | Telephone |
| `website` | `VARCHAR(255)`, nullable | Website URL |
| `address` | `TEXT`, nullable | Single free-form address in current MVP schema |
| `city` | `VARCHAR(255)`, nullable | City |
| `state` | `VARCHAR(255)`, nullable | State/division |
| `postal_code` | `VARCHAR(20)`, nullable | Postal code |
| `country` | `VARCHAR(100)`, default `Bangladesh` | Country label |
| `logo` | `VARCHAR(255)`, nullable | Storage path; bytes live on `public` disk in current service |
| `status` | boolean, default `true` | Active/inactive flag |
| `organization_id` | `BIGINT UNSIGNED`, nullable, indexed | Reserved for future SaaS support; currently has no FK/table relationship |
| `created_by`, `updated_by` | nullable FKs to `users.id`, `ON DELETE SET NULL` | Audit actors |
| `created_at`, `updated_at` | timestamps | Laravel timestamps |
| `deleted_at` | nullable timestamp | Soft delete |

Indexes: unique `uuid`, unique `code`, index `organization_id`, index `name`, index `status`, and indexes generated for `created_by`/`updated_by` foreign keys. The model's mass-assignable fields and boolean cast should remain synchronized with this migration.

**Decision required:** the earlier version of this document proposed a different `institutions` shape (`code VARCHAR(20)`, `short_name`, `established_year`, and `logo_path`) and companion tables. The current migration is authoritative. Do not create a second incompatible version of `institutions`; evolve the existing schema with forward-only migrations.

## 6. Target entity relationship overview

The intended domain relationship flow is:

```text
users ──< institution audit actors
institutions ──< faculties ──< departments ──< programs ──< batches
institutions ──< academic_years
programs ──< semesters
programs / departments ──< subjects ──< chapters ──< topics
institutions + subjects ──< questions ──< question_versions
question_versions ──< version_options / version_answers / version_media
question_versions >──< topics and tags
question_versions ──< reviews and approvals
institutions + academic period + subject ──< exams ──< exam_sections
exams ──< blueprints ──< blueprint_rules
exam_versions ──< exam_questions ──< exam_question_answer_keys
exam_generation_runs ──< exam_questions
users ──< student_profiles ──< student_enrollments
exams ──< exam_candidates ──< exam_results ──< exam_result_items
institutions / users / domain records ──< audit_logs
```

The diagram is conceptual. A concrete migration may need a two-step FK addition for circular pointers such as `questions.current_version_id` and `exams.current_version_id`.

## 7. Institution management — Proposed V1

### 7.1 `institution_addresses`

Purpose: allow an institution to retain multiple campus, mailing, billing, or administrative addresses rather than overloading the current single `institutions.address` field.

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal key |
| `uuid` | UUID, unique | Public identifier |
| `institution_id` | FK → `institutions.id`, not null | Parent; index |
| `address_type` | `VARCHAR(30)`, default `MAIN` | Controlled value, e.g. MAIN, CAMPUS, MAILING |
| `address_line_1`, `address_line_2` | `VARCHAR(255)`; second nullable | Address lines |
| `city`, `state`, `district` | `VARCHAR(100)`, nullable | Geographic details |
| `postal_code` | `VARCHAR(20)`, nullable | Postal code |
| `country` | `VARCHAR(100)`, default `Bangladesh` | Country |
| `latitude` | `DECIMAL(10,8)`, nullable | Coordinate validation required |
| `longitude` | `DECIMAL(11,8)`, nullable | Coordinate validation required |
| `is_primary` | boolean, default false | Enforce at most one active primary address per institution in a transaction or via an appropriate DB design |
| `status` | `SMALLINT UNSIGNED`, default 1 | 1 active, 0 inactive |
| `remarks` | `TEXT`, nullable | Internal notes |
| Standard profile | A with `deleted_at` | `created_at`, `updated_at`, `deleted_at` |

FK delete rule: `institutions.id` → `institution_addresses.institution_id` should be `RESTRICT` or parent soft-delete only; do not cascade-delete addresses on hard deletion without an approved retention policy.

### 7.2 `institution_contacts`

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal key |
| `uuid` | UUID, unique | Public identifier |
| `institution_id` | FK → `institutions.id`, not null | Parent; index |
| `person_name` | `VARCHAR(150)`, not null | Contact person |
| `designation`, `department` | `VARCHAR(100)`, nullable | Job and department |
| `email` | `VARCHAR(150)`, nullable | Email; validate at application level |
| `phone`, `mobile` | `VARCHAR(30)`, nullable | Keep as strings, not numeric values |
| `extension` | `VARCHAR(10)`, nullable | Office extension |
| `contact_type` | `VARCHAR(30)`, default `OFFICIAL` | Controlled value |
| `is_primary` | boolean, default false | At most one primary contact per required scope |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `remarks` | `TEXT`, nullable | Internal notes |
| Standard profile | A with `deleted_at` | `created_at`, `updated_at`, `deleted_at` |

Indexes: (`institution_id`, `status`), (`institution_id`, `contact_type`). Protect personal contact information through role-based access.

### 7.3 `institution_settings`

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal key |
| `uuid` | UUID, unique | Public identifier |
| `institution_id` | FK → `institutions.id`, not null | Parent |
| `setting_key` | `VARCHAR(100)`, not null | Machine key |
| `setting_value` | `LONGTEXT`, nullable | Encoded value; value must match `data_type` |
| `data_type` | `VARCHAR(30)`, default `string` | STRING, INTEGER, DECIMAL, BOOLEAN, JSON, DATE |
| `group_name` | `VARCHAR(50)`, default `general` | Group for UI/configuration |
| `is_public` | boolean, default false | Public settings only; never use this to expose secrets by itself |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `remarks` | `TEXT`, nullable | Notes |
| Standard profile | A with `deleted_at` | `created_at`, `updated_at`, `deleted_at` |

Constraint: unique (`institution_id`, `setting_key`) among retained rows. Store secrets in a secrets manager/environment configuration, not in this table. If a setting contains structured JSON, validate its schema and never interpolate it into SQL.

## 8. Academic structure — Proposed V1

All academic entities belong to an institution. Repeated `institution_id` values in child entities are intentional for tenant filtering and practical indexing, but must agree with their parent records. Enforce this with composite foreign keys where feasible or validate it transactionally in services and policies.

### 8.1 `faculties`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Parent institution |
| `code` | `VARCHAR(20)`, required | Unique within institution |
| `name` | `VARCHAR(255)`, required | Faculty name |
| `short_name` | `VARCHAR(100)`, nullable | Abbreviation |
| `dean_name` | `VARCHAR(150)`, nullable | Optional contact/display data |
| `office_phone` | `VARCHAR(30)`, nullable | Telephone |
| `office_email` | `VARCHAR(150)`, nullable | Email |
| `description`, `remarks` | `TEXT`, nullable | Description and internal notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Include actor columns if operational audit is required |

Unique: (`institution_id`, `code`). Index: (`institution_id`, `status`).

### 8.2 `departments`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `faculty_id` | FK → `faculties.id`, required | Parent faculty |
| `code` | `VARCHAR(20)`, required | Unique within institution |
| `name` | `VARCHAR(255)`, required | Department name |
| `short_name` | `VARCHAR(100)`, nullable | Abbreviation |
| `head_name` | `VARCHAR(150)`, nullable | Optional display/contact data |
| `office_phone` | `VARCHAR(30)`, nullable | Telephone |
| `office_email` | `VARCHAR(150)`, nullable | Email |
| `description`, `remarks` | `TEXT`, nullable | Description and internal notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`institution_id`, `code`). FK delete rule for a faculty with departments: `RESTRICT`.

### 8.3 `programs`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `faculty_id` | FK → `faculties.id`, required | Parent faculty |
| `department_id` | FK → `departments.id`, required | Parent department |
| `code` | `VARCHAR(20)`, required | Institution-scoped program code |
| `name` | `VARCHAR(255)`, required | Program name |
| `short_name` | `VARCHAR(100)`, nullable | Display abbreviation |
| `degree` | `VARCHAR(100)`, required | E.g. Bachelor of Science |
| `duration_years` | `DECIMAL(4,1)`, default 4.0 | Program length |
| `total_semesters` | `SMALLINT UNSIGNED`, default 8 | Expected semester count |
| `total_credits` | `DECIMAL(6,2)`, nullable | Total credits |
| `description`, `remarks` | `TEXT`, nullable | Description and notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`institution_id`, `code`). Validate faculty and department institution ownership.

### 8.4 `academic_years`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Parent institution |
| `code` | `VARCHAR(20)`, required | E.g. AY-2026-27; unique per institution |
| `name` | `VARCHAR(100)`, required | Display label |
| `start_date`, `end_date` | `DATE`, required | `start_date <= end_date` |
| `is_current` | boolean, default false | At most one current year per institution |
| `description`, `remarks` | `TEXT`, nullable | Description and internal notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`institution_id`, `code`). Enforce the one-current-year rule transactionally or by a reviewed schema strategy. Do not rely on a simple non-unique boolean index alone.

### 8.5 `semesters`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `program_id` | FK → `programs.id`, required | Program |
| `academic_year_id` | FK → `academic_years.id`, required | Parent year |
| `code` | `VARCHAR(20)`, required | Unique in program/year scope |
| `name` | `VARCHAR(100)`, required | Display label |
| `semester_no` | `SMALLINT UNSIGNED`, required | Sequence number |
| `start_date`, `end_date` | `DATE`, required | Start must not exceed end |
| `is_current` | boolean, default false | Current semester per program/year policy |
| `description`, `remarks` | `TEXT`, nullable | Description and notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`program_id`, `academic_year_id`, `semester_no`) or (`program_id`, `academic_year_id`, `code`) according to the institution's calendar model; select one canonical rule before migration.

### 8.6 `batches`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `faculty_id`, `department_id`, `program_id` | FKs to respective masters, required | Denormalized hierarchy for queryability; validate consistency |
| `academic_year_id` | FK → `academic_years.id`, required | Admission year |
| `code` | `VARCHAR(30)`, required | Unique per institution |
| `name` | `VARCHAR(150)`, required | Batch/cohort label |
| `admission_year`, `expected_graduation_year` | `YEAR`, required/nullable | Graduation cannot predate admission |
| `capacity` | `SMALLINT UNSIGNED`, nullable | Optional upper capacity |
| `description`, `remarks` | `TEXT`, nullable | Description and notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`institution_id`, `code`).

### 8.7 `subjects`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `faculty_id`, `department_id`, `program_id` | FKs, required | Academic ownership; validate hierarchy consistency |
| `code` | `VARCHAR(30)`, required | Unique within institution/program policy |
| `name` | `VARCHAR(255)`, required | Subject/course name |
| `short_name` | `VARCHAR(100)`, nullable | Abbreviation |
| `credit_hours` | `DECIMAL(4,2)`, default 3.00 | Credit value |
| `theory_marks`, `practical_marks`, `total_marks` | `SMALLINT UNSIGNED`, nullable | Assessment allocation; reconcile totals in service/constraint |
| `description`, `remarks` | `TEXT`, nullable | Description and notes |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: normally (`program_id`, `code`) or (`institution_id`, `code`); adopt the institution's course-catalog policy explicitly.

### 8.8 `chapters`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `subject_id` | FK → `subjects.id`, required | Parent subject |
| `chapter_no` | `SMALLINT UNSIGNED`, required | Position within subject |
| `code` | `VARCHAR(30)`, nullable | Optional human code |
| `name` | `VARCHAR(255)`, required | Chapter title |
| `description`, `learning_objectives` | `TEXT`, nullable | Learning text |
| `estimated_hours` | `DECIMAL(4,2)`, nullable | Planned study/teaching hours |
| `display_order` | `SMALLINT UNSIGNED`, default 1 | Display sequence |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `remarks` | `TEXT`, nullable | Internal notes |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`subject_id`, `chapter_no`) and optionally (`subject_id`, `code`). Derive faculty/program from subject when possible instead of persisting duplicated hierarchy values.

### 8.9 `topics`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Common identifiers |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `chapter_id` | FK → `chapters.id`, required | Parent chapter |
| `topic_no` | `SMALLINT UNSIGNED`, required | Position within chapter |
| `code` | `VARCHAR(30)`, nullable | Optional code |
| `name` | `VARCHAR(255)`, required | Topic title |
| `description`, `learning_objectives` | `TEXT`, nullable | Topic details |
| `estimated_hours` | `DECIMAL(4,2)`, nullable | Estimated time |
| `difficulty_level_id` | FK → `difficulty_levels.id`, nullable | Uses shared lookup rather than a hard-coded SQL `ENUM` |
| `display_order` | `SMALLINT UNSIGNED`, default 1 | Display sequence |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `remarks` | `TEXT`, nullable | Internal notes |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`chapter_id`, `topic_no`). Derive subject/program from chapter unless measured query requirements justify storing and maintaining redundant FKs.

## 9. Question bank — Proposed V1, with version history required for immutable exams

### 9.1 Lookups

All lookup tables use `id` PK, `uuid` unique where exposed, `code` unique within its scope, `name`, optional `description`, `display_order`, `status`, and standard profile A with `deleted_at` unless noted.

| Table | Specific rules / fields |
|---|---|
| `question_categories` | `code VARCHAR(30)` unique; `name VARCHAR(150)` required; categories classify business use, e.g. academic, admission, practice, certification. Global or institution-specific scope must be explicitly chosen. |
| `question_types` | `code VARCHAR(30)` unique; `name VARCHAR(150)` required; `has_options BOOLEAN DEFAULT false`; `allows_multiple_answers BOOLEAN DEFAULT false`; `auto_gradable BOOLEAN DEFAULT true`. Examples: MCQ, true/false, essay, short answer, matching, numerical, programming. |
| `difficulty_levels` | `code VARCHAR(30)` unique; `name VARCHAR(100)` required; optional numeric `rank`/`display_order`; keep the canonical codes stable (e.g. EASY, MEDIUM, HARD). |
| `blooms_taxonomy_levels` | `code VARCHAR(30)` unique; `name VARCHAR(100)` required; `rank SMALLINT UNSIGNED` for ordered cognitive levels; optional description. |
| `question_sources` | `code VARCHAR(30)` unique; `name VARCHAR(150)` required; `source_type VARCHAR(30)`; optional `reference_url VARCHAR(1000)`, `license VARCHAR(150)`, `description TEXT`; avoid retaining copyrighted source content without permission. |
| `exam_types` | `code VARCHAR(30)` unique; `name VARCHAR(100)` required; description and status; examples: QUIZ, MIDTERM, FINAL, PRACTICE. Required by the exam entity. |

### 9.2 `questions` — stable question identity

The question row is the stable identity; content that must be historically reproducible belongs in `question_versions`. This separates lifecycle identity from versioned wording and answer options.

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal ID |
| `uuid` | UUID, unique | Public identifier |
| `institution_id` | FK → `institutions.id`, required | Owner/tenant |
| `subject_id` | FK → `subjects.id`, required | Main subject context |
| `current_version_id` | FK → `question_versions.id`, nullable initially | Points to currently selected/approved version; add FK after both tables exist |
| `lifecycle_status` | `VARCHAR(24)`, default `DRAFT` | DRAFT, IN_REVIEW, APPROVED, RETIRED; define transitions in service layer |
| `is_active` | boolean, default true | Whether available for new use; separate from approval status |
| `created_by`, `updated_by` | FK → `users.id`; updater nullable | Traceability |
| Standard profile | A with `deleted_at` | Common timestamps |

Indexes: (`institution_id`, `subject_id`, `lifecycle_status`), (`institution_id`, `is_active`). Do not duplicate current question text here if `question_versions` is the canonical content store.

### 9.3 `question_versions` — immutable content snapshots

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Version record ID |
| `uuid` | UUID, unique | Public version identifier |
| `question_id` | FK → `questions.id`, required | Stable parent |
| `version_number` | `INT UNSIGNED`, required | Monotonically increasing per question |
| `version_label` | `VARCHAR(50)`, nullable | Optional label |
| `question_text` | `LONGTEXT`, required | Canonical content snapshot |
| `explanation` | `LONGTEXT`, nullable | Explanation; restrict to authorized staff until release policy allows it |
| `question_type_id` | FK → `question_types.id`, required | Question format |
| `difficulty_level_id` | FK → `difficulty_levels.id`, required | Difficulty |
| `blooms_taxonomy_level_id` | FK → `blooms_taxonomy_levels.id`, nullable | Cognitive level |
| `question_category_id` | FK → `question_categories.id`, required | Category |
| `question_source_id` | FK → `question_sources.id`, nullable | Provenance |
| `language_code` | `VARCHAR(10)`, default `en` | Language tag/code |
| `default_marks`, `negative_marks` | `DECIMAL(8,2)`, defaults 1.00 / 0.00 | Must be non-negative |
| `estimated_time_seconds` | `INT UNSIGNED`, nullable | Expected response time |
| `status` | `VARCHAR(30)`, default `DRAFT` | DRAFT, IN_REVIEW, APPROVED, REJECTED, PUBLISHED, RETIRED |
| `change_type` | `VARCHAR(30)`, default `UPDATE` | CREATE, CONTENT_UPDATE, METADATA_UPDATE, CORRECTION, etc. |
| `change_summary` | `VARCHAR(1000)`, nullable | Why a new version was created |
| `content_hash` | `CHAR(64)`, nullable | SHA-256 digest for integrity/deduplication |
| `created_by` | FK → `users.id`, required | Version author |
| `created_at` | timestamp, required | Immutable creation time; omit `updated_at` for strict immutability |

Constraints: unique (`question_id`, `version_number`); indexes (`question_id`, `status`), (`question_type_id`, `difficulty_level_id`), and `content_hash` if duplicate detection is enabled. Updates/deletes to approved or referenced versions are forbidden; create a new version instead.

### 9.4 Version-dependent question content

The prior draft linked options, answers, media, topic links, and tags only to a master question. That is insufficient for exact historical reproduction if those child records are edited between question versions. The target design binds content-dependent child records to `question_version_id`.

#### `question_version_options`

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal ID |
| `uuid` | UUID, unique | Public ID if required |
| `question_version_id` | FK → `question_versions.id`, required | Version owner |
| `option_key` | `VARCHAR(10)`, required | A/B/C or another stable key |
| `option_text` | `LONGTEXT`, required | Option content |
| `media_type` | `VARCHAR(30)`, nullable | Optional option media kind |
| `media_path` | `VARCHAR(500)`, nullable | Storage path or link; validate and authorize |
| `display_order` | `SMALLINT UNSIGNED`, default 1 | Display order |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `remarks` | `TEXT`, nullable | Internal note |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique (`question_version_id`, `option_key`); index (`question_version_id`, `display_order`).

#### `question_version_answers`

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal ID |
| `question_version_id` | FK → `question_versions.id`, required | Version owner |
| `question_version_option_id` | FK → `question_version_options.id`, nullable | Set for option-based answers |
| `answer_type` | `VARCHAR(30)`, required | OPTION, TEXT, NUMBER, RANGE, MATCH, CODE, etc. |
| `answer_value` | `LONGTEXT`, nullable | Canonical expected answer; encrypt only if approved by threat model, always tightly authorize |
| `answer_order` | `SMALLINT UNSIGNED`, default 1 | Supports multiple acceptable answers |
| `score_weight` | `DECIMAL(8,4)`, default 1.0000 | Relative score weight |
| `case_sensitive` | boolean, default false | Text grading behavior |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| Standard profile | A with `deleted_at` | Common timestamps |

Validate that an answer option belongs to the same question version. Index (`question_version_id`, `answer_order`). Answer records are confidential assessment data.

#### `question_version_topics`

| Column | Type / rule | Notes |
|---|---|---|
| `question_version_id` | FK → `question_versions.id` | Composite key part |
| `topic_id` | FK → `topics.id` | Composite key part |
| `relevance_weight` | `DECIMAL(5,2)`, default 1.00 | Relevance/coverage weighting |
| `is_primary` | boolean, default false | Primary topic marker |
| `display_order` | `SMALLINT UNSIGNED`, default 1 | UI/display ordering |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `created_by` | FK → `users.id`, nullable | Actor |
| `created_at`, `updated_at` | timestamps | Relationship timestamps |

Primary key (`question_version_id`, `topic_id`); index (`topic_id`, `question_version_id`). Enforce one primary topic per version via service/transaction or a database strategy.

#### `tags` and `question_version_tags`

`tags` contains `id`, optional `uuid`, nullable `institution_id` for approved shared/global tags, `name VARCHAR(100)`, `slug VARCHAR(120)`, `description VARCHAR(500)`, `tag_type VARCHAR(30)`, `display_order SMALLINT UNSIGNED DEFAULT 1`, `status SMALLINT UNSIGNED DEFAULT 1`, nullable actor FKs, standard timestamps, and `deleted_at`.

- Unique (`institution_id`, `slug`) for institution-scoped tags; global tag uniqueness must be handled intentionally because MySQL permits multiple `NULL` values in a conventional unique key.
- `question_version_tags` has composite PK (`question_version_id`, `tag_id`), `relevance_weight DECIMAL(5,2) DEFAULT 1.00`, nullable `assigned_by` FK → `users.id`, and `created_at`.
- Do not delete tags that are referenced by published questions/exams; deactivate them instead.

#### `question_version_media`

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal ID |
| `uuid` | UUID, unique | Public ID |
| `institution_id` | FK → `institutions.id`, required | Tenant scope |
| `question_version_id` | FK → `question_versions.id`, required | Version owning the media |
| `media_type` | `VARCHAR(30)`, required | IMAGE, AUDIO, VIDEO, DIAGRAM, FILE |
| `storage_disk` | `VARCHAR(50)`, default `public` | Filesystem disk name |
| `storage_path` | `VARCHAR(1000)`, required | Relative storage key, never client-supplied absolute filesystem path |
| `original_filename`, `stored_filename` | `VARCHAR(255)` | Original and generated names |
| `mime_type` | `VARCHAR(100)` | Detected MIME type, not trusted client header |
| `file_extension` | `VARCHAR(20)`, nullable | Suffix after validation |
| `file_size_bytes` | `BIGINT UNSIGNED` | Enforce upload-size limits |
| `checksum` | `VARCHAR(128)`, nullable | Integrity/deduplication |
| `title` | `VARCHAR(255)`, nullable | Display title |
| `alt_text` | `VARCHAR(500)`, nullable | Accessibility text |
| `caption` | `TEXT`, nullable | Caption |
| `duration_seconds` | `DECIMAL(12,3)`, nullable | Audio/video duration |
| `width`, `height` | `INT UNSIGNED`, nullable | Image/video dimensions |
| `display_order` | `SMALLINT UNSIGNED`, default 1 | Ordering |
| `is_required` | boolean, default false | Required for question display |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `uploaded_by` | FK → `users.id`, nullable | Actor |
| Standard profile | A with `deleted_at` | Common timestamps |

Store binary files in configured storage, not the database. Gate access by institution, question status, exam release policy, and signed/temporary URLs as appropriate. Scan uploads and validate MIME type, size, extension, and checksum.

### 9.5 `question_reviews`

Append one record for each review attempt; do not overwrite prior review history.

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Review ID |
| `question_id` | FK → `questions.id`, required | Stable question |
| `question_version_id` | FK → `question_versions.id`, required | Exact reviewed content |
| `reviewer_id` | FK → `users.id`, required | Reviewer |
| `review_status` | `VARCHAR(30)`, default `PENDING` | PENDING, APPROVED, NEEDS_REVISION, REJECTED |
| `quality_score`, `accuracy_score`, `clarity_score`, `difficulty_score`, `relevance_score` | nullable `DECIMAL(5,2)` | Define score range, normally 0–100 |
| `feedback`, `rejection_reason` | nullable `LONGTEXT` | Review rationale |
| `reviewed_at` | nullable timestamp | Completion time |
| `created_at`, `updated_at` | timestamps | Review event data |

Indexes: (`question_version_id`, `review_status`), (`reviewer_id`, `created_at`). Verify the question version belongs to `question_id`.

### 9.6 `question_approvals`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Approval event |
| `question_id` | FK → `questions.id`, required | Stable question |
| `question_version_id` | FK → `question_versions.id`, required | Exact version being decided |
| `approver_id` | FK → `users.id`, required | Authorized approver |
| `decision` | `VARCHAR(30)`, default `PENDING` | PENDING, APPROVED, REJECTED, REVOKED |
| `approval_level` | `SMALLINT UNSIGNED`, default 1 | Multi-level approval sequence |
| `comments`, `rejection_reason` | nullable `LONGTEXT` | Decision notes |
| `decided_at` | nullable timestamp | Decision time |
| `created_at`, `updated_at` | timestamps | History metadata |

Indexes: (`question_version_id`, `approval_level`), (`question_version_id`, `decision`). Approval is per version; approval of one version never approves future versions. Use transactions when changing current-version pointers or publishing state.

## 10. Examination and blueprint — Proposed V1/V2

The feature catalog places exam creation and publication in V2. The entities below are defined now for architectural completeness but are not implemented by current migrations.

### 10.1 `exams`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Exam identity |
| `institution_id` | FK → `institutions.id`, required | Owner institution |
| `academic_year_id` | FK → `academic_years.id`, nullable | Academic period |
| `semester_id` | FK → `semesters.id`, nullable | Semester, when applicable |
| `subject_id` | FK → `subjects.id`, required | Subject assessed |
| `batch_id` | FK → `batches.id`, nullable | Target cohort where relevant |
| `exam_type_id` | FK → `exam_types.id`, required | Exam classification |
| `code` | `VARCHAR(50)`, required | Institution-scoped code |
| `title` | `VARCHAR(255)`, required | Display title |
| `slug` | `VARCHAR(300)`, required | Optional URL/search slug; unique in intended scope |
| `description` | `TEXT`, nullable | Description |
| `instructions` | `LONGTEXT`, nullable | Candidate instructions |
| `language_code` | `VARCHAR(10)`, default `en` | Main language |
| `total_marks` | `DECIMAL(8,2)`, default 0 | Calculated/validated from version content |
| `total_questions` | `INT UNSIGNED`, default 0 | Calculated/validated from version content |
| `duration_minutes` | `INT UNSIGNED`, required | Positive duration when published |
| `passing_marks` | `DECIMAL(8,2)`, nullable | Must not exceed total marks |
| `generation_mode` | `VARCHAR(20)`, default `MANUAL` | MANUAL, AUTOMATIC, HYBRID |
| `status` | `VARCHAR(30)`, default `DRAFT` | DRAFT, BLUEPRINT_READY, GENERATED, IN_REVIEW, APPROVED, PUBLISHED, LOCKED, ARCHIVED |
| `current_version_id` | nullable FK → `exam_versions.id` | Add FK after `exam_versions`; denotes active published/current snapshot |
| `created_by`, `updated_by`, `approved_by`, `published_by` | nullable/required user FKs according to event | Actor traceability |
| `approved_at`, `published_at`, `locked_at`, `archived_at` | nullable timestamps | Lifecycle timestamps |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`institution_id`, `code`); index (`institution_id`, `status`), (`subject_id`, `academic_year_id`), and (`batch_id`, `status`) where used. `curriculum_id` and `class_id` from the older draft are not defined elsewhere in the delivered academic model; do not add them until their entities and rules exist. The design uses `semester_id` and `batch_id` instead.

### 10.2 `exam_sections`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Section identity |
| `exam_id` | FK → `exams.id`, required | Parent exam |
| `section_code` | `VARCHAR(30)`, required | Stable code within exam |
| `section_number` | `SMALLINT UNSIGNED`, default 1 | Logical section number |
| `title` | `VARCHAR(255)`, required | Title |
| `short_title` | `VARCHAR(100)`, nullable | Short label |
| `description` | `TEXT`, nullable | Description |
| `instructions` | `LONGTEXT`, nullable | Section instructions |
| `question_type_id` | FK → `question_types.id`, nullable | Optional type restriction |
| `selection_mode` | `VARCHAR(20)`, default `AUTO` | AUTO or MANUAL; validate accepted codes |
| `question_count` | `INT UNSIGNED`, default 0 | Target questions |
| `total_marks` | `DECIMAL(8,2)`, default 0 | Must reconcile to the section's generated items |
| `marks_per_question` | `DECIMAL(8,2)`, nullable | Only for uniform scoring |
| `negative_marks` | `DECIMAL(8,2)`, default 0 | Non-negative penalty |
| `is_optional` | boolean, default false | Optional section behavior |
| `min_questions`, `max_questions` | nullable `INT UNSIGNED` | Validate min ≤ max and target within permitted range |
| `display_order` | `SMALLINT UNSIGNED`, default 1 | Display order |
| `status` | `SMALLINT UNSIGNED`, default 1 | Active/inactive |
| `created_by`, `updated_by` | nullable FKs → `users.id` | Actors |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`exam_id`, `section_code`) and (`exam_id`, `section_number`). Delete should be restricted once referenced by a published exam version.

### 10.3 `exam_blueprints`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Blueprint identity |
| `exam_id` | FK → `exams.id`, required | Parent exam |
| `exam_section_id` | nullable FK → `exam_sections.id` | Null means exam-level blueprint; otherwise section-level |
| `name` | `VARCHAR(255)`, required | Blueprint title |
| `code` | `VARCHAR(50)`, required | Unique within its scope |
| `description` | `TEXT`, nullable | Purpose |
| `blueprint_type` | `VARCHAR(30)`, default `EXAM` | EXAM, SECTION, CUSTOM |
| `generation_strategy` | `VARCHAR(30)`, default `CONSTRAINT_BASED` | CONSTRAINT_BASED, RANDOM, WEIGHTED_RANDOM, RULE_BASED, OPTIMIZATION, AI_ASSISTED, HYBRID |
| `selection_mode` | `VARCHAR(20)`, default `AUTO` | AUTO, MANUAL, HYBRID |
| `target_question_count` | `INT UNSIGNED`, default 0 | Count target |
| `target_total_marks` | `DECIMAL(8,2)`, default 0 | Marks target |
| `min_question_count`, `max_question_count` | nullable `INT UNSIGNED` | Allowed count range |
| `randomization_enabled` | boolean, default true | Shuffle candidates/order when permitted |
| `avoid_duplicates` | boolean, default true | Duplicate prevention |
| `allow_question_reuse` | boolean, default false | Policy flag; usage checks still required |
| `enforce_topic_coverage`, `enforce_difficulty_distribution`, `enforce_bloom_distribution` | booleans, default true/false per approved policy | Coverage/distribution rules |
| `tolerance_percentage` | `DECIMAL(5,2)`, default 0 | Explicit acceptable tolerance |
| `random_seed` | `BIGINT UNSIGNED`, nullable | Reproducible seeded selection |
| `algorithm_version` | `VARCHAR(50)`, nullable | Generation software version |
| `priority` | `SMALLINT UNSIGNED`, default 1 | Candidate precedence |
| `status` | `VARCHAR(30)`, default `DRAFT` | DRAFT, VALIDATED, ACTIVE, ARCHIVED |
| `created_by`, `updated_by` | nullable FKs → `users.id` | Actors |
| Standard profile | A with `deleted_at` | Common timestamps |

Constraint: (`exam_id`, `code`) unique. Validate that a section-level blueprint's section belongs to the same exam. Keep the blueprint configuration separate from the generated exam composition.

### 10.4 `exam_blueprint_rules`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Rule identity |
| `exam_blueprint_id` | FK → `exam_blueprints.id`, required | Parent blueprint |
| `parent_rule_id` | nullable self-FK → `exam_blueprint_rules.id` | Nested/composite rules |
| `rule_code` | `VARCHAR(50)`, required | Unique within blueprint |
| `rule_type` | `VARCHAR(40)`, required | QUESTION_TYPE, DIFFICULTY, BLOOM_LEVEL, CHAPTER, TOPIC, MARKS, QUESTION_COUNT, PERCENTAGE, LANGUAGE, SOURCE, TAG, CURRICULUM, CLASS, SUBJECT, APPROVAL_STATUS, USAGE_LIMIT, DUPLICATE_SIMILARITY, DATE_RANGE, CUSTOM |
| `target_type` | `VARCHAR(40)`, nullable | Target entity kind when applicable |
| `target_id` | `BIGINT UNSIGNED`, nullable | Polymorphic target; cannot have a normal FK to multiple target tables |
| `operator` | `VARCHAR(20)`, default `EQUALS` | EQUALS, NOT_EQUALS, IN, BETWEEN, GT, GTE, LT, LTE, etc. |
| `value` | `VARCHAR(255)`, nullable | Scalar or compact value |
| `value_min`, `value_max` | nullable `DECIMAL(10,2)` | Range limits |
| `percentage` | nullable `DECIMAL(5,2)` | Percent constraint |
| `required_count`, `minimum_count`, `maximum_count` | nullable `INT UNSIGNED` | Quantity constraints |
| `marks` | nullable `DECIMAL(8,2)` | Marks constraint |
| `constraint_mode` | `VARCHAR(10)`, default `HARD` | HARD or SOFT |
| `priority` | `SMALLINT UNSIGNED`, default 1 | Ordering/precedence |
| `weight` | `DECIMAL(8,4)`, default 1.0000 | Soft constraint weight |
| `tolerance_percentage` | nullable `DECIMAL(5,2)` | Rule-level tolerance |
| `is_active` | boolean, default true | Rule activation |
| `sequence` | `SMALLINT UNSIGNED`, default 1 | Evaluation order |
| `metadata` | nullable `JSON` | Type-specific non-authoritative details; validate schema |
| `created_by`, `updated_by` | nullable FKs → `users.id` | Actors |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`exam_blueprint_id`, `rule_code`). Use application/domain validation for `target_type`/`target_id` integrity. Prefer typed FK columns if the finite set of target types becomes stable. Hard constraints must never be silently relaxed; soft-constraint relaxation must be recorded in the generation run.

### 10.5 `exam_generation_runs`

This is an append-only execution/audit record that explains how a generated paper was produced.

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Run identity |
| `exam_id` | FK → `exams.id`, required | Parent exam |
| `exam_blueprint_id` | nullable FK → `exam_blueprints.id` | Primary blueprint |
| `requested_by` | FK → `users.id`, required | Initiating user |
| `status` | `VARCHAR(24)`, required | QUEUED, RUNNING, SUCCEEDED, FAILED, CANCELLED |
| `generation_strategy` | `VARCHAR(30)`, required | Strategy selected |
| `algorithm_version` | `VARCHAR(50)`, required | Reproducibility |
| `random_seed` | nullable `BIGINT UNSIGNED` | Seed used |
| `input_snapshot` | `JSON`, required | Immutable sanitized snapshot of selected rules/configuration |
| `candidate_count`, `selected_count` | `INT UNSIGNED`, default 0 | Pool and result counts |
| `warnings` | nullable `JSON` | Non-fatal deviations |
| `failure_code` | `VARCHAR(50)`, nullable | Stable machine code |
| `failure_summary` | nullable `TEXT` | Sanitized operational explanation; no secrets or full payload dumps |
| `started_at`, `finished_at` | nullable timestamps | Execution interval |
| `created_at` | timestamp | Run creation |

Indexes: (`exam_id`, `created_at`), (`status`, `created_at`). Do not hard-delete generation runs for papers that reached approval/publication.

### 10.6 `exam_versions`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Immutable exam version |
| `exam_id` | FK → `exams.id`, required | Parent exam identity |
| `version_number` | `INT UNSIGNED`, required | Monotonic per exam |
| `status` | `VARCHAR(24)`, required | DRAFT, IN_REVIEW, APPROVED, PUBLISHED, SUPERSEDED, VOID |
| `title_snapshot` | `VARCHAR(255)`, required | Exact title at this version |
| `instructions_snapshot` | nullable `LONGTEXT` | Candidate instructions snapshot |
| `total_marks` | `DECIMAL(8,2)`, required | Snapshot total |
| `total_questions` | `INT UNSIGNED`, required | Snapshot count |
| `duration_minutes` | `INT UNSIGNED`, required | Snapshot duration |
| `content_hash` | `CHAR(64)`, nullable | Integrity digest |
| `created_by` | FK → `users.id`, required | Version creator |
| `approved_by`, `published_by` | nullable FKs → `users.id` | Lifecycle actors |
| `approved_at`, `published_at` | nullable timestamps | Lifecycle events |
| `created_at` | timestamp | Immutable creation time |

Unique: (`exam_id`, `version_number`). A published version's content must not be updated; corrections create a new version. The current pointer on `exams` can be updated only in the same transaction that publishes/selects the new version.

### 10.7 `exam_questions`

The generated composition of a particular exam version. It references both the stable question and the exact question version used, then preserves render-critical snapshots so a later edit cannot change a historic paper.

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Item identity |
| `exam_id` | FK → `exams.id`, required | Indexed parent for queries |
| `exam_version_id` | FK → `exam_versions.id`, required | Target exam version |
| `exam_section_id` | nullable FK → `exam_sections.id` | Section, if applicable |
| `question_id` | FK → `questions.id`, required | Stable identity; useful for usage analytics |
| `question_version_id` | FK → `question_versions.id`, required | Authoritative exact source version |
| `generation_run_id` | nullable FK → `exam_generation_runs.id` | Generation audit link |
| `blueprint_id` | nullable FK → `exam_blueprints.id` | Blueprint used |
| `blueprint_rule_id` | nullable FK → `exam_blueprint_rules.id` | Principal selection rule, where identifiable |
| `question_number` | `INT UNSIGNED`, required | Paper-level display number |
| `section_question_number` | nullable `INT UNSIGNED` | Section-local number |
| `question_order` | `INT UNSIGNED`, required | Stable item ordering |
| `marks`, `negative_marks` | `DECIMAL(8,2)`, defaults 0 | Scoring snapshot |
| `is_compulsory`, `is_optional` | booleans | Must not both be true |
| `option_group` | `VARCHAR(50)`, nullable | Related optional questions/grouping |
| `randomization_position` | nullable `INT UNSIGNED` | Reproducibility/display ordering |
| `selection_method` | `VARCHAR(30)`, required | AUTO, MANUAL, AI_ASSISTED, HYBRID, IMPORTED, CLONED |
| `selection_score` | nullable `DECIMAL(12,6)` | Algorithm score; not a truth/confidence guarantee |
| `difficulty_snapshot`, `bloom_level_snapshot` | nullable `VARCHAR(30)` | Reporting snapshot |
| `marks_snapshot` | `DECIMAL(8,2)`, required | Source marks at selection time |
| `question_text_snapshot` | `LONGTEXT`, required | Exact rendered question text |
| `options_snapshot` | nullable `JSON` | Student-visible option content only; no answer flags |
| `metadata_snapshot` | nullable `JSON` | Non-sensitive render metadata |
| `status` | `VARCHAR(24)`, default `ACTIVE` | ACTIVE, REPLACED, REMOVED, VOID |
| `created_by`, `updated_by` | nullable FKs → `users.id` | Actors |
| Standard profile | C with `created_at`; only permit mutable status through audited commands | Historical items retained |

Constraints: unique (`exam_version_id`, `question_number`); unique (`exam_version_id`, `question_version_id`) when duplicate versions are forbidden; index (`question_version_id`, `exam_id`), (`exam_section_id`, `question_order`), (`generation_run_id`). Validate that each referenced section/version belongs to the same exam. Use a transaction to generate or replace a complete exam composition.

### 10.8 `exam_question_answer_keys`

This separate table avoids putting correct answers in the same row/API projection as student-visible question text.

| Column | Type / rule | Notes |
|---|---|---|
| `exam_question_id` | FK → `exam_questions.id`, PK or unique FK | One answer key per generated item; a versioned child structure may be used for multiple key entries |
| `answer_snapshot` | `JSON`, required | Exact authorized answer key/scoring rubric snapshot |
| `created_at` | timestamp | Snapshot time |

Only exam controllers/services that generate official answer keys or grade responses may read this table. Never include it in student APIs, Blade view models, cache values visible to students, or export payloads. Consider encryption at rest and separate DB permissions according to the threat model.

## 11. Student and result management — Proposed V2

Student identity and roster management are not implemented in the current migration set. Do not overload `users` with every academic enrollment attribute; keep authentication identity separate from institution-specific student records.

### 11.1 `student_profiles`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Profile identity |
| `user_id` | unique FK → `users.id`, required | Login identity; one primary profile per user in this version |
| `institution_id` | FK → `institutions.id`, required | Owning institution |
| `student_number` | `VARCHAR(50)`, required | Institution-issued identifier |
| `date_of_birth` | nullable `DATE` | Collect only if required by policy |
| `admission_date` | nullable `DATE` | Admission date |
| `profile_status` | `VARCHAR(24)`, default `ACTIVE` | ACTIVE, SUSPENDED, GRADUATED, WITHDRAWN |
| `created_by`, `updated_by` | nullable user FKs | Actors |
| Standard profile | A with `deleted_at` | Common timestamps |

Unique: (`institution_id`, `student_number`). Treat personally identifying data as sensitive and enforce minimum access/retention.

### 11.2 `student_enrollments`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Enrollment record |
| `student_profile_id` | FK → `student_profiles.id`, required | Student |
| `institution_id` | FK → `institutions.id`, required | Tenant scope; validate agreement with profile |
| `program_id` | FK → `programs.id`, required | Program |
| `batch_id` | nullable FK → `batches.id` | Cohort |
| `academic_year_id` | FK → `academic_years.id`, required | Enrollment year |
| `semester_id` | nullable FK → `semesters.id` | Semester where applicable |
| `enrollment_status` | `VARCHAR(24)`, default `ACTIVE` | ACTIVE, COMPLETED, WITHDRAWN, SUSPENDED |
| `enrolled_at` | nullable `DATE` | Effective enrollment date |
| `ended_at` | nullable `DATE` | Completion/withdrawal date |
| `created_by` | nullable FK → `users.id` | Operator |
| `created_at`, `updated_at` | timestamps | Mutable enrollment record |

Index (`student_profile_id`, `academic_year_id`), (`program_id`, `batch_id`, `enrollment_status`). Define the uniqueness policy for reenrollment and repeated semesters before adding a unique constraint.

### 11.3 `exam_candidates`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Candidate registration |
| `exam_id` | FK → `exams.id`, required | Exam identity |
| `exam_version_id` | FK → `exam_versions.id`, required | Exact paper version assigned |
| `student_profile_id` | FK → `student_profiles.id`, required | Candidate |
| `registration_number` | `VARCHAR(50)`, nullable | Exam/session-specific registration number |
| `attendance_status` | `VARCHAR(20)`, default `REGISTERED` | REGISTERED, PRESENT, ABSENT, EXCUSED |
| `attendance_at` | nullable timestamp | Attendance event |
| `accommodation_snapshot` | nullable `JSON` | Only approved, necessary exam accommodations; sensitive access |
| `status` | `VARCHAR(24)`, default `ACTIVE` | ACTIVE, WITHDRAWN, DISQUALIFIED |
| `created_by` | nullable FK → `users.id` | Registrar/operator |
| `created_at`, `updated_at` | timestamps | Candidate roster lifecycle |

Unique (`exam_id`, `student_profile_id`). Add tenant/institution agreement validation; never trust client-supplied `student_profile_id` without policy checks.

### 11.4 `grading_schemes` and `grading_scheme_items`

`grading_schemes` contains `id`, `uuid`, `institution_id` FK, `code VARCHAR(30)`, `name VARCHAR(150)`, optional `academic_year_id` FK, `status`, actor fields, and standard timestamps/soft delete. Unique (`institution_id`, `code`).

`grading_scheme_items` contains `id`, `grading_scheme_id` FK, `minimum_percentage DECIMAL(5,2)`, `maximum_percentage DECIMAL(5,2)`, `letter_grade VARCHAR(10)`, `grade_point DECIMAL(5,2)`, nullable `description`, and `display_order SMALLINT UNSIGNED`. Index (`grading_scheme_id`, `minimum_percentage`). Scheme ranges must be non-overlapping, collectively cover the intended score range, and have minimum ≤ maximum.

### 11.5 `exam_results`

| Column | Type / rule | Notes |
|---|---|---|
| `id`, `uuid` | PK and unique UUID | Result record |
| `exam_candidate_id` | unique FK → `exam_candidates.id`, required | One final result per candidate for the assigned exam version; use result revisions/history if re-publication is allowed |
| `exam_version_id` | FK → `exam_versions.id`, required | Score basis |
| `grading_scheme_id` | nullable FK → `grading_schemes.id` | Scheme used at calculation time |
| `marks_obtained` | `DECIMAL(8,2)`, default 0 | Raw total |
| `maximum_marks` | `DECIMAL(8,2)`, required | Possible total at that version |
| `percentage` | `DECIMAL(6,3)`, nullable | Calculated; preserve calculation inputs |
| `letter_grade` | `VARCHAR(10)`, nullable | Grade snapshot |
| `grade_point` | `DECIMAL(5,2)`, nullable | GPA/grade-point snapshot |
| `result_status` | `VARCHAR(24)`, default `DRAFT` | DRAFT, UNDER_REVIEW, APPROVED, PUBLISHED, WITHHELD, VOID |
| `entered_by`, `reviewed_by`, `published_by` | nullable user FKs | Actors |
| `entered_at`, `reviewed_at`, `published_at` | nullable timestamps | Lifecycle times |
| `remarks` | nullable `TEXT` | Internal comments; do not expose by default |
| `created_at`, `updated_at` | timestamps | Result record lifecycle |

Indexes (`result_status`, `published_at`), and FK indexes. Validate `0 <= marks_obtained <= maximum_marks` except where an approved policy permits exceptional penalties/extra credit. Use transactions for mark aggregation, grade calculation, approval, and publication. Published results must be immutable; a correction should create a revision/change event.

### 11.6 `exam_result_items`

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Detail row |
| `exam_result_id` | FK → `exam_results.id`, required | Parent result |
| `exam_section_id` | nullable FK → `exam_sections.id` | Section-level breakdown |
| `exam_question_id` | nullable FK → `exam_questions.id` | Item-level marks where manual item grading is supported |
| `marks_obtained` | `DECIMAL(8,2)`, default 0 | Awarded marks |
| `maximum_marks` | `DECIMAL(8,2)`, required | Possible marks snapshot |
| `feedback` | nullable `TEXT` | Feedback not intended as internal-only unless release is approved |
| `graded_by` | nullable FK → `users.id` | Marker |
| `graded_at` | nullable timestamp | Marking time |
| `created_at`, `updated_at` | timestamps | Mutable until result approval |

Require at least one of `exam_section_id` or `exam_question_id`; when both are set they must belong to the same exam/version. Use a unique key appropriate to the detail grain to prevent duplicate marking rows.

### 11.7 Online responses are not in V1/V2 unless separately approved

`student_responses`, online attempts, timer events, autosave snapshots, proctoring records, OMR ingestion, and CBT answer events belong to the explicitly deferred online examination scope. Do not add them as a side effect of implementing manual/offline result entry. An approved online-exam architecture should define privacy, retention, event ordering, idempotency, and high-volume write requirements first.

## 12. Cross-cutting and future tables

### 12.1 `audit_logs` — Proposed, required before sensitive production workflows

Append-only record of actor activity and important domain changes. Prefer explicit domain audit events for security-critical transitions; this generic table supplements rather than replaces event-specific records.

| Column | Type / rule | Notes |
|---|---|---|
| `id` | `BIGINT UNSIGNED` PK | Internal ID |
| `uuid` | UUID, unique | Event identifier |
| `institution_id` | nullable FK → `institutions.id` | Scope where relevant |
| `actor_id` | nullable FK → `users.id`, `ON DELETE SET NULL` | Actor; null for system events |
| `event` | `VARCHAR(100)`, required | Stable event code, e.g. QUESTION_APPROVED |
| `subject_type` | `VARCHAR(150)`, nullable | Morph type / domain kind |
| `subject_id` | `BIGINT UNSIGNED`, nullable | Related object ID; polymorphic and therefore not directly FK-constrained |
| `description` | `TEXT`, nullable | Sanitized human description |
| `old_values`, `new_values` | nullable `JSON` | Allowlisted fields only; never passwords, reset tokens, answer keys, or secrets |
| `ip_address` | `VARCHAR(45)`, nullable | Request address when legally/operationally appropriate |
| `user_agent` | nullable `TEXT` | Client metadata; size-limit and sanitize |
| `occurred_at` | timestamp, required | Event time in UTC |

Indexes: (`institution_id`, `occurred_at`), (`actor_id`, `occurred_at`), (`subject_type`, `subject_id`, `occurred_at`), (`event`, `occurred_at`). No update/delete in normal application flows; define retention, access, and archival rules.

### 12.2 Notifications

If database notifications are enabled, use Laravel's standard notifications table with `id` (UUID/string primary key), `type`, polymorphic `notifiable_type` / `notifiable_id`, `data` JSON/text, nullable `read_at`, and timestamps. Keep payloads minimal; do not include exam answer keys, passwords, secret links, or unnecessary personal information. The notification table is not currently created by the supplied migrations.

### 12.3 Organization / tenant support — Future / decision required

The implemented `institutions.organization_id` anticipates SaaS ownership, but there is no `organizations` table or FK. Before using this column for tenancy, choose one design:

1. Create `organizations` and add a real FK, lifecycle, unique rules, and tenant policies; or
2. Remove the unused column through a reviewed migration if organization tenancy is not planned.

Do not use a nullable, unconstrained integer as a security boundary. Add an `institution_user` membership pivot only if users need explicit institution assignments; it should have FK constraints, `user_id`, `institution_id`, membership status, timestamps, and a unique (`user_id`, `institution_id`) key. Existing Spatie roles are global because teams are disabled.

## 13. Relationship and deletion rules

| Relationship | Preferred deletion behavior | Rationale |
|---|---|---|
| Institution → academic/question/exam masters | `RESTRICT` hard deletion; soft-delete/archive parent | Historical and ownership integrity |
| User → audit actor fields | `SET NULL` when history must survive; otherwise deactivate the user | Preserve historical records |
| Role/permission pivot rows → role/permission | Package-defined `CASCADE` | Remove obsolete assignments when definitions are deleted |
| Question → versions | `RESTRICT`; retire/soft-delete master instead | Versions may be referenced by exams/reviews |
| Question version → options/answers/media/topic/tag mappings | `CASCADE` only for never-published draft versions; restrict/retain after use | Avoid breaking historical exam snapshots |
| Exam → sections/blueprints | Restrict after version publication; archive/soft-delete editable draft data | Preserve approved papers |
| Exam version → exam questions and answer keys | Retain immutable children | Published paper integrity |
| Exam candidate → result | Restrict hard deletion; retain academic record per retention policy | Results are official records |
| Audit/history records | No normal hard delete/update | Auditability |

Soft deletes do not automatically cascade to child rows. Every soft-delete operation must define whether children are hidden, archived, retained, or still readable by privileged administrators. Do not rely on ORM global scopes as the only tenant or security control.

## 14. Integrity and lifecycle rules

### 14.1 Academic consistency

- A child row's `institution_id` must match the institution of each referenced parent.
- Departments must belong to the referenced faculty; programs must belong to the referenced department/faculty; batches must belong to the referenced program/year; subjects must belong to the stated program/department/faculty.
- Start date must be on or before end date for academic years, semesters, and scheduled periods.
- Where the schema repeats parent IDs for faster tenant filtering, add composite FK constraints where practical or verify consistency within the same DB transaction. Never rely only on front-end selects.
- Do not allow deletion of referenced subjects/topics after a published exam uses a version snapshot; retire instead.

### 14.2 Question lifecycle

`DRAFT → IN_REVIEW → APPROVED → PUBLISHED → RETIRED`, with `NEEDS_REVISION` / `REJECTED` transitions documented per workflow. The exact status set may differ by module but must be centralized as constants/enums in application code.

- Every content edit requiring historical tracking creates a new `question_versions` row.
- An approval/review record references the exact version reviewed.
- Only approved/published versions may be selected for an official exam.
- Question child content is version-bound.
- Published/referenced versions are immutable.
- `current_version_id` must point to a version belonging to that same question and in an allowed state.

### 14.3 Exam lifecycle and generation

`DRAFT → BLUEPRINT_READY → GENERATED → IN_REVIEW → APPROVED → PUBLISHED → LOCKED / ARCHIVED` (use only transitions supported by the product release).

- Generation is all-or-nothing within a transaction for DB writes. Use a queue job for expensive generation but atomically persist the final composition.
- Record the input/rule snapshot, strategy, algorithm version, random seed, candidate count, selected count, warnings, and failure code in `exam_generation_runs`.
- Validate candidate-pool sufficiency before generation. Hard-rule failures abort; soft-rule deviations require explanation and audit record.
- Calculate and verify total question count and total marks before approving/publishing.
- A generated paper references exact `question_version_id` values and stores render snapshots; updating the source question must not alter past papers.
- Any published-paper correction creates a new `exam_versions` row; do not modify the published version in place.
- Correct answers/rubrics live outside student-readable query projections.

### 14.4 Results lifecycle

`DRAFT → UNDER_REVIEW → APPROVED → PUBLISHED`, with `WITHHELD` / `VOID` as controlled exceptions.

- Marks aggregation, grade calculation, approval, and publication are transactional.
- Persist the grading scheme and grade/percentage snapshots used, so a future grading-policy edit does not silently rewrite historical results.
- Only authorized exam/result roles can enter or change unpublished marks.
- Published result changes require a correction process with actor, reason, prior value, new value, and timestamp.
- A report should read from published results by default; draft results require explicit permission.

## 15. Indexing and query performance

### 15.1 Required indexing principles

- The primary key and every FK used in joins must be indexed (InnoDB may require/create FK indexes; declare needed indexes explicitly to make behavior predictable).
- Add composite indexes in the order of equality filters / join keys first, then range/order columns.
- Add unique constraints as close to the business rule as possible; application uniqueness checks are not concurrency-safe by themselves.
- Use full-text indexing only after confirming language support, expected query behavior, and MySQL deployment. For question content search, evaluate MySQL FULLTEXT or a dedicated search service instead of repeated leading-wildcard `LIKE` scans.
- Use database `EXPLAIN` and representative data before adding denormalized counters or cache layers.

### 15.2 Suggested initial composite indexes

| Table | Suggested index | Query supported |
|---|---|---|
| `institutions` | (`status`, `name`) if used | Active institution listing/search |
| `faculties` | (`institution_id`, `status`) | Institution academic hierarchy |
| `departments` | (`faculty_id`, `status`) | Faculty department listing |
| `programs` | (`department_id`, `status`) | Department program listing |
| `academic_years` | (`institution_id`, `start_date`, `end_date`) | Period filtering |
| `subjects` | (`program_id`, `status`, `name`) | Program subject lists |
| `topics` | (`chapter_id`, `display_order`) | Ordered topic retrieval |
| `questions` | (`institution_id`, `subject_id`, `lifecycle_status`, `is_active`) | Authorized question pool |
| `question_versions` | (`question_id`, `version_number`) unique; (`status`, `question_type_id`, `difficulty_level_id`) | Current/history version and candidate filter |
| `question_version_topics` | (`topic_id`, `question_version_id`) | Topic-based question selection |
| `exam_generation_runs` | (`exam_id`, `created_at`); (`status`, `created_at`) | Run history/operations |
| `exam_questions` | (`exam_version_id`, `question_number`) unique; (`question_version_id`, `exam_id`) | Paper rendering and usage history |
| `exam_candidates` | (`exam_id`, `student_profile_id`) unique | Candidate roster |
| `exam_results` | (`result_status`, `published_at`) | Result publication/reports |
| `audit_logs` | (`institution_id`, `occurred_at`); (`actor_id`, `occurred_at`) | Audit timeline |

Avoid redundant single-column indexes when a composite index already serves the same leftmost-prefix access pattern. Confirm all index widths and key-length limits against the exact MySQL version and collation.

## 16. Transactions, concurrency, and idempotency

Use `DB::transaction()` for any operation that changes more than one related record or must preserve a domain invariant. At minimum:

- Create/update institution plus associated metadata, if companion tables are introduced.
- Create new question version plus options/answers/media/topic/tag mappings.
- Submit review/approval and update lifecycle/current-version pointers.
- Generate an exam and persist selected items, snapshots, and generation-run outcome.
- Publish an exam version and update `exams.current_version_id`.
- Enter/aggregate/approve/publish results.

For concurrent edits, use optimistic version checks (e.g. compare expected current version) or row-level locks. Use idempotency keys for retried queue jobs and import operations. Do not wrap external file uploads in a long-running DB transaction; stage the file, transact the metadata, and clean up orphaned files on failure.

## 17. Security, privacy, and retention

- Never store plaintext passwords, reset tokens in application logs, API secrets, or private keys in ordinary domain tables.
- Give the production DB account only the required schema privileges; do not use a root/admin account from the web process.
- Use parameterized Eloquent/query-builder queries; never concatenate user input into SQL fragments, sort expressions, or raw where clauses.
- Enforce role, permission, institution ownership, and record-level policies in the backend. Hiding a button in Blade is not authorization.
- Treat question answer keys, unpublished exams, student personal details, marks, results, and audit trails as sensitive.
- Student-facing views use explicit DTOs/select lists that exclude answers, reviewer feedback, internal remarks, unpublished results, storage keys, and audit metadata.
- Log important events such as account/role changes, question approval, blueprint changes, exam generation/publication, marks edits, and result publication. Redact secret and high-risk fields.
- Set retention periods for session rows, password reset rows, failed jobs, media, audit logs, and student/result records. Backups and exports follow the same privacy/access controls as production data.
- Do not delete official academic/exam records merely to satisfy user deactivation. Apply approved legal/institutional retention and anonymization rules.

## 18. Migration and seeding standards

### 18.1 Migration rules

1. Create one migration per logical schema change, with a clear timestamp/name.
2. Use forward-only, reviewed migrations in shared/prod environments. `down()` is for local rollback convenience, not a reason to destroy official records.
3. Prefer adding nullable columns, backfilling, validating, and then tightening constraints for data-bearing databases.
4. Create referenced parent tables before child tables. Add circular pointer FKs (`current_version_id`) only after both tables exist.
5. Do not edit a migration that may already have run in another environment; add a new migration.
6. Use the same integer size/signedness for FK and referenced PK.
7. Keep migration names and constraints within MySQL identifier limits.
8. Verify migrations using the production DB engine in CI, not only SQLite.
9. Add integration tests for FK delete behavior, unique constraints, soft-delete policy, version immutability, and transaction rollback.
10. Check the migration status and backup before a production migration.

### 18.2 Recommended migration order

1. Laravel baseline: `users`, password reset, sessions, cache, queues, migrations repository.
2. Spatie: permissions, roles, and pivots.
3. Institutions (already present) and optional institution address/contact/settings extensions.
4. Academic lookup and hierarchy: academic years, faculties, departments, programs, semesters, batches, subjects, chapters, topics.
5. Question-bank lookups: categories, types, difficulty levels, Bloom levels, sources.
6. Questions and question versions; add `current_version_id` after version table exists.
7. Version-dependent content: options, answers, media, topic/tag maps, reviews, approvals.
8. Examination lookups (`exam_types`), exams, sections, blueprints, rules, exam versions.
9. Generation runs, exam-question snapshots, isolated answer keys.
10. Student profiles/enrollments, candidate rosters, grading schemes, results, result details.
11. Audit logs and database notifications where approved.
12. Optional organization/tenant tables and scoped memberships only after a tenancy decision.

### 18.3 Seeder and factory rules

- Seed reference values with stable codes and `firstOrCreate`/upsert semantics so repeated runs are idempotent.
- Factories generate syntactically valid values and maintain valid foreign keys; tests must not rely on production data.
- Seed only development/test accounts by default. The current `AdminUserSeeder` creates `admin@eqbems.test` with the known password `password`; prevent that seeder from creating a predictable credential in production, and require rotation if it was ever deployed.
- Seed roles and permissions separately from user accounts, and clear/refresh Spatie permission cache when required by the package lifecycle.
- Do not seed real student, examination, or personal data in shared development environments.

## 19. Backup, recovery, and operations

- Define an automated backup schedule based on recovery point objective (RPO) and recovery time objective (RTO); the product documents do not currently specify numerical RPO/RTO targets.
- Back up the database and uploaded question media/object storage consistently. A database backup without media objects is incomplete for question-paper reconstruction.
- Encrypt backups at rest and in transit; scope backup access narrowly and periodically test restoration in an isolated environment.
- For MySQL production, use consistent snapshots or point-in-time recovery with binary logs where supported by the operating model.
- Document migration rollback/forward-fix playbooks, backup verification, and post-restore integrity checks.
- Monitor storage growth, slow queries, failed jobs, queue latency, lock contention, deadlocks, and migration failures.
- Archive large append-only audit tables by policy when needed; partition only after production volume and retention needs justify the operational complexity.

## 20. Known repository gaps and required decisions

| ID | Finding | Required action |
|---|---|---|
| DB-001 | Production ADR specifies MySQL, but `config/database.php` defaults to SQLite. | Configure and test the intended connection per environment; make MySQL the verified integration/production target. |
| DB-002 | The old database document's institution schema conflicts with the actual `institutions` migration. | Use the migration schema in §5.6 as current truth; add future fields through migrations rather than recreating the table. |
| DB-003 | `institutions.organization_id` is indexed but has no `organizations` table or FK. | Create full tenant design with FK and policies or remove the unused column via migration before relying on it. |
| DB-004 | Spatie teams are disabled and `User` currently lacks `HasRoles`, despite seeders calling `assignRole`. | Add/configure the trait and role tests; decide whether global roles are enough or institution-scoped teams/memberships are required. |
| DB-005 | Academic/question/exam/result tables are described in docs but have no migrations/models in the current source inventory. | Implement in the documented dependency order; do not report those modules as database-complete. |
| DB-006 | The prior draft referenced `DATABASE_DICTIONARY.md` and `TABLE_RELATIONSHIP_MATRIX.md`, but those files are absent from the supplied `docs/03_DATABASE/` folder. | This document includes the core field inventory and relationship rules; split them into separate controlled documents only if useful. |
| DB-007 | The old question design attached media/options/answers to a master question, which does not preserve all child content by question version. | Use the version-bound child tables in §9.4 or introduce equivalent `question_version_*` tables before implementing version history. |
| DB-008 | The old exam draft references `curriculum_id` and `class_id`, while the delivered academic hierarchy defines programs, batches, and semesters. | Use `semester_id` / `batch_id` in the target exam design unless separate curriculum/class entities are formally added. |
| DB-009 | The previous exam-question sketch included answer data beside student-renderable question snapshots. | Use isolated `exam_question_answer_keys`; enforce explicit data projections and authorization. |
| DB-010 | Current seeded Super Admin credentials are predictable. | Restrict the seeder to local/testing and require secure production account provisioning/credential rotation. |

## 21. Database documentation and review checklist

Before merging any database-related feature, reviewers should verify:

- [ ] The affected entity and relationships are documented here and in the module requirements.
- [ ] A migration, Eloquent model, relationships, casts, and factories are present where the module is intended to be implemented.
- [ ] PK/FK sizes and signedness match; referential actions are intentional.
- [ ] Unique rules are represented by DB constraints, including tenant scope and soft-delete behavior.
- [ ] Required composite indexes support demonstrated queries.
- [ ] Validation and policy checks enforce institutional/tenant ownership, not only existence of foreign IDs.
- [ ] Multi-row changes are transactional and safe under concurrent requests/retries.
- [ ] Versioned/published content remains immutable and exam snapshots are reproducible.
- [ ] Answer keys, sensitive results, personal data, tokens, and audit payloads are never accidentally exposed.
- [ ] Soft-delete, restoration, hard-delete, and data-retention rules are defined.
- [ ] Tests run against the selected database engine and cover integrity constraints and rollback cases.
- [ ] Seeders are idempotent and cannot install predictable production credentials.
- [ ] Backup/restore, migration safety, and operational monitoring have been considered for production.
- [ ] Related docs and this file are updated when a business rule or relationship changes.

## 22. Related source references

- `PROJECT_MANIFEST.md` — current project phase, modules, and development rules.
- `docs/01_FOUNDATION/01_PROJECT_OVERVIEW.md` — business purpose and stakeholder context.
- `docs/01_FOUNDATION/02_BUSINESS_REQUIREMENTS.md` — business workflow requirements.
- `docs/01_FOUNDATION/FEATURE_CATALOG.md` — feature statuses and release roadmap.
- `docs/01_FOUNDATION/PROJECT_SCOPE.md` — release boundaries and deferred features.
- `docs/01_FOUNDATION/PROJECT_STANDARDS.md` — coding and naming standards.
- `docs/02_ARCHITECTURE/ADR-004-use-mysql.md` — database technology decision.
- `docs/02_ARCHITECTURE/ADR-005-use-spatie-permission.md` — role/permission decision.
- `database/migrations/` — exact implemented schema.
- `app/Models/` and `database/seeders/` — current model and seed integration.

---

**Document status:** Draft for implementation review. Update this file together with migrations, models, tests, and module documentation whenever schema decisions change.
