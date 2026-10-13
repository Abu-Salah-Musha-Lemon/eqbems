# Database Dictionary — Current Migration-Backed Schema

**Scope:** only tables defined by current files in `database/migrations/`. For proposed academic/question/exam/result entities, see `DATABASE_DESIGN.md`; they are not current schema until migrations are added.

Run `php artisan migrate:status` in each environment before using this table as a statement about the actual installed database.

## Application and Laravel tables

| Table | Source | Main purpose | Notable constraints / notes |
|---|---|---|---|
| `users` | `0001_01_01_000000_create_users_table.php` | User identity | Unique email; hashed password at model layer; currently `User` lacks Spatie `HasRoles` trait |
| `password_reset_tokens` | same migration | Password reset token lookup | Email primary key; protect token material and logs |
| `sessions` | same migration | Database-backed sessions | Nullable indexed `user_id`; migration does not declare user FK |
| `cache` | `0001_01_01_000001_create_cache_table.php` | Database cache | `key` primary key; expiration index |
| `cache_locks` | same migration | Atomic cache locks | Key/owner/expiration data |
| `jobs` | `0001_01_01_000002_create_jobs_table.php` | Database queue | Queue index and reservation metadata |
| `job_batches` | same migration | Queue batches | String ID, job counts, failed job IDs/options |
| `failed_jobs` | same migration | Failed job diagnostics | Unique UUID; exception/payload data should be restricted |

## Spatie Permission tables

Source: `2026_07_04_190837_create_permission_tables.php`, names from `config/permission.php`.

| Table | Purpose | Key design |
|---|---|---|
| `permissions` | Permission catalog | Unique (`name`, `guard_name`) |
| `roles` | Role catalog | Unique (`name`, `guard_name`); no team column when `teams=false` |
| `role_has_permissions` | Role-to-permission pivot | Composite primary key; FKs to roles and permissions cascade on delete |
| `model_has_roles` | Polymorphic role assignment | Role FK plus (`model_id`, `model_type`) index; no ordinary FK from polymorphic model ID |
| `model_has_permissions` | Polymorphic direct permission assignment | Permission FK plus (`model_id`, `model_type`) index |

Teams are disabled in current `config/permission.php`. Do not interpret these tables as institution-scoped RBAC.

## `institutions`

Source: `2026_07_05_142437_create_institutions_table.php`.

| Column | Definition / rule |
|---|---|
| `id` | Unsigned bigint auto-increment primary key |
| `uuid` | UUID, unique |
| `code` | String(50), globally unique and required |
| `name` | String(255), required; indexed |
| `email` | String, nullable |
| `phone` | String(30), nullable |
| `website` | String, nullable |
| `address` | Text, nullable |
| `city` | String, nullable |
| `state` | String, nullable |
| `postal_code` | String(20), nullable |
| `country` | String(100), default `Bangladesh` |
| `logo` | String path, nullable |
| `status` | Boolean, default true; indexed |
| `organization_id` | Unsigned bigint nullable, indexed; no FK/organizations table currently exists |
| `created_by`, `updated_by` | Nullable user FKs; `SET NULL` on user deletion |
| `created_at`, `updated_at` | Timestamps |
| `deleted_at` | Nullable soft-delete timestamp |

Notes:

- The `Institution` model uses UUID route keys and soft deletes.
- `code` uniqueness is database-wide and continues to include soft-deleted rows unless the schema is changed deliberately.
- The model/service currently allow logo upload to the `public` disk. Confirm that this is an intended public asset before production use.
- The original proposed institution design had different columns; do not use old `short_name`, `logo_path`, or different code lengths as current facts.

## Migration inventory

Use these commands to confirm source and environment state:

```bash
find database/migrations -maxdepth 1 -type f -name '*.php' -print
php artisan migrate:status
```

The target academic, question-bank, blueprint, examination, candidate, and result tables are described in `DATABASE_DESIGN.md` but are not defined by the current migration set inspected for this document.
