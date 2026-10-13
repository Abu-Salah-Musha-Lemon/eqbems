---
document_id: DOC-030
document_name: DATABASE_DESIGN.md
project_name: Enterprise Question Bank & Examination Management System (EQBEMS)
version: 2.0.0
status: Approved
owner: Solution Architecture Team
document_type: Technical Architecture

related_documents:
  - PROJECT_MANIFEST.md
  - SYSTEM_ARCHITECTURE.md
  - FOLDER_STRUCTURE.md
  - DATABASE_DICTIONARY.md
  - TABLE_RELATIONSHIP_MATRIX.md
  - ADR-004-use-mysql.md
---

# DATABASE DESIGN

Enterprise Question Bank & Examination Management System (EQBEMS)

---

# 1. Purpose

This document defines the official database architecture and design of the Enterprise Question Bank & Examination Management System (EQBEMS).

It acts as the single source of truth for the database layer of the application.

This document must be followed before creating:

- Database Schema
- Laravel Migrations
- Eloquent Models
- Relationships
- Factories
- Seeders
- Queries
- Reports
- APIs

Every developer working on this project must read this document before implementing any database-related feature.

---

# 2. Objectives

The database has been designed to achieve the following objectives.

• High Performance

• Data Integrity

• Security

• Scalability

• Maintainability

• Reliability

• Extensibility

• Auditability

• High Availability

• Future Multi-campus Support

• Future Multi-language Support

• AI-ready Data Structure

---

# 3. Database Technology Stack

Database Server

MySQL 8.x

Storage Engine

InnoDB

Character Set

utf8mb4

Collation

utf8mb4_unicode_ci

Timezone

UTC

Application Layer

Laravel 12

ORM

Laravel Eloquent ORM

Migration Tool

Laravel Migration

Seeder

Laravel Seeder

Factory

Laravel Factory

---

# 4. Database Architecture

EQBEMS follows a centralized relational database architecture.

```
Browser
      │
Laravel Application
      │
Business Services
      │
Laravel Eloquent ORM
      │
MySQL Database
```

The application uses a single database with logical separation through modules.

No module owns a separate database.

Modules communicate through foreign key relationships.

---

# 5. Database Design Principles

The following principles are mandatory.

• Third Normal Form (3NF)

• Referential Integrity

• Foreign Key Constraints

• Atomic Transactions

• Consistent Naming

• Data Consistency

• Minimal Redundancy

• Reusable Reference Data

• Soft Delete where appropriate

• Audit-friendly Design

• Index-driven Performance

• Explicit Relationships

• Prefer Lookup Tables over ENUM for expandable business values.

• Avoid Duplicate Business Data.

---

# 6. Database Standards

The following standards apply to every table.

Primary Keys

BIGINT UNSIGNED AUTO_INCREMENT

Foreign Keys

BIGINT UNSIGNED

Storage Engine

InnoDB

Character Set

utf8mb4

Collation

utf8mb4_unicode_ci

Default Timestamp

CURRENT_TIMESTAMP

---

# 7. Naming Convention

## Database

Lowercase

Example

```
eqbems
```

---

## Tables

snake_case

Plural

Examples

```
institutions

academic_years

question_banks

questions

exam_results
```

---

## Columns

snake_case

Examples

```
institution_name

question_title

created_at

updated_at

deleted_at
```

---

## Foreign Keys

Always

table_name_id

Examples

```
institution_id

faculty_id

department_id

subject_id

question_bank_id

exam_id

user_id
```

---

## Pivot Tables

Alphabetical Order

Examples

```
role_user

permission_role

question_tag
```

---

## Index Names

```
idx_table_column
```

Examples

```
idx_questions_subject

idx_users_email
```

---

## Foreign Key Names

```
fk_child_parent
```

Examples

```
fk_questions_subjects

fk_departments_faculties
```

---

## Unique Constraint Names

```
uq_table_column
```

---

## Check Constraint Names

```
chk_table_rule
```

---

# 8. Common Columns Standard

Every business table should include the following columns where applicable.

| Column | Required |
|----------|-----------|
| id | Yes |
| created_at | Yes |
| updated_at | Yes |
| deleted_at | Optional |
| status | Optional |
| display_order | Optional |
| remarks | Optional |

Audit information such as:

created_by

updated_by

deleted_by

will be maintained through the Audit Log System instead of being duplicated across every business table.

---

# DATABASE_DESIGN.md

## Part-1 (Version 2.0)

## Section-2

---

# 9. Data Type Standards

The following data types must be used consistently throughout the project.

| Data Type | MySQL Type | Usage |
|------------|------------|-------|
| Primary Key | BIGINT UNSIGNED | Auto Increment ID |
| Foreign Key | BIGINT UNSIGNED | References another table |
| Name | VARCHAR(255) | Institution, Faculty, Subject Name |
| Short Name | VARCHAR(100) | Abbreviation |
| Code | VARCHAR(50) | Unique Code |
| Email | VARCHAR(150) | Email Address |
| Phone | VARCHAR(30) | Mobile / Telephone |
| URL | VARCHAR(255) | Website |
| Description | TEXT | Medium Description |
| Long Description | LONGTEXT | Large Content |
| Integer | INT | Count |
| Small Integer | SMALLINT | Lookup Values |
| Tiny Integer | TINYINT | Status Flags |
| Decimal | DECIMAL(12,2) | Marks, Fees |
| Boolean | BOOLEAN | True / False |
| Date | DATE | Academic Date |
| Time | TIME | Time Only |
| DateTime | DATETIME | Event Time |
| Timestamp | TIMESTAMP | Laravel Timestamps |
| Year | YEAR | Established Year |
| JSON | JSON | Flexible Configuration Data |
| UUID | CHAR(36) | Future External/Public Identifier |

---

# 10. Status Strategy

Status values should be standardized across the system.

Preferred implementation order:

1. Lookup / Reference Table (Recommended)
2. SMALLINT Codes
3. ENUM (Only when values are fixed and unlikely to change)

Example Lookup Values

| Code | Meaning |
|------|---------|
| 1 | Active |
| 2 | Inactive |
| 3 | Draft |
| 4 | Pending |
| 5 | Approved |
| 6 | Rejected |
| 7 | Archived |

Business modules should not invent their own status meanings.

Status values must remain consistent across the application.

---

# 11. Soft Delete Strategy

Soft Delete is enabled only for business entities where recovery is meaningful.

Enable Soft Delete For:

- Institutions
- Institution Addresses
- Institution Contacts
- Faculties
- Departments
- Programs
- Batches
- Academic Years
- Semesters
- Courses
- Subjects
- Chapters
- Topics
- Question Banks
- Questions
- Blueprints
- Exams
- Results

Do NOT Enable Soft Delete For:

- Cache Tables
- Sessions
- Failed Jobs
- Password Reset Tokens
- Temporary Import Tables
- Queue Tables
- Log Cache Tables

Reason:

System tables should remain lightweight and should not accumulate deleted records.

---

# 12. Audit Strategy

EQBEMS uses a centralized Audit Log strategy.

Business tables SHOULD NOT contain:

- created_by
- updated_by
- deleted_by

Instead, all business operations should be recorded inside:

audit_logs

Future package recommendation:

spatie/laravel-activitylog

Audit Log should capture:

- User ID
- Action
- Module
- Record ID
- Old Values
- New Values
- IP Address
- User Agent
- Timestamp

Benefits

- Smaller Business Tables
- Complete Change History
- Easier Compliance
- Better Reporting
- Easier Debugging

---

# 13. Table Classification

All tables belong to one of the following categories.

## Master Tables

Stores long-term business entities.

Examples

- institutions
- faculties
- departments
- programs
- courses
- subjects

---

## Transaction Tables

Stores operational records.

Examples

- exams
- exam_questions
- results
- result_items

---

## Configuration Tables

Stores system configuration.

Examples

- settings
- institution_settings
- notification_templates

---

## Reference Tables

Stores reusable lookup values.

Examples

- statuses
- grade_scales
- question_difficulties
- academic_levels

---

## Pivot Tables

Manages many-to-many relationships.

Examples

- role_user
- permission_role
- question_tag

---

## Audit Tables

Stores activity history.

Examples

- audit_logs
- login_logs
- activity_logs

---

## Temporary Tables

Stores temporary or import data.

Examples

- import_batches
- import_rows
- temp_uploads

---

## System Tables

Managed by Laravel or the framework.

Examples

- jobs
- failed_jobs
- cache
- cache_locks
- sessions
- migrations
- password_reset_tokens

---

# 14. General Database Rules

Every table must have a clear business purpose.

Every foreign key must reference a valid parent table.

Every foreign key should be indexed.

Avoid duplicate data.

Avoid storing calculated values unless required for performance.

Store only one fact in one place.

Never store comma-separated IDs.

Always normalize before denormalizing.

Business logic belongs to the application layer, not inside database tables.

---

# DATABASE_DESIGN.md

## Part-1 (Version 2.0)

## Section-3

---

# 15. Module-wise Database Overview

The EQBEMS database is organized into logical business modules.

Each module contains a group of related tables.

The goal is to separate business responsibilities while maintaining a single relational database.

---

# Module 1 : Institution Management

Purpose

Stores institution-level information.

Tables

- institutions
- institution_addresses
- institution_contacts
- institution_settings

Future Tables

- institution_documents
- institution_branches

---

# Module 2 : Academic Management

Purpose

Manages the complete academic hierarchy.

Tables

- faculties
- departments
- programs
- batches
- academic_years
- semesters
- courses
- subjects
- chapters
- topics

---

# Module 3 : User & Access Management

Purpose

Authentication and Authorization.

Tables

- users
- roles
- permissions
- role_has_permissions
- model_has_roles
- model_has_permissions

Optional

- user_profiles
- user_devices
- user_sessions

---

# Module 4 : Question Bank

Purpose

Stores reusable questions.

Tables

- question_banks
- questions
- question_options
- question_tags
- question_tag_items
- question_attachments

Future

- question_versions
- question_reviews

---

# Module 5 : Blueprint Management

Purpose

Defines exam blueprint rules.

Tables

- blueprints
- blueprint_sections
- blueprint_rules

---

# Module 6 : Examination

Purpose

Manages examinations.

Tables

- exams
- exam_sessions
- exam_questions
- exam_invigilators
- exam_publish_logs

Future

- online_exam_attempts
- exam_devices

---

# Module 7 : Result Management

Purpose

Stores examination results.

Tables

- results
- result_items
- grades
- grade_scales

Future

- transcripts
- cgpa_records

---

# Module 8 : Reporting

Purpose

Supports reporting.

Tables

No permanent report tables by default.

Reports should be generated dynamically.

Materialized tables may be introduced later for performance.

---

# Module 9 : Notification

Purpose

Notification management.

Tables

- notifications
- notification_templates

Future

- notification_queue
- notification_logs

---

# Module 10 : Audit

Purpose

Tracks every important system activity.

Tables

- audit_logs
- login_logs
- activity_logs

---

# Module 11 : Configuration

Purpose

Application configuration.

Tables

- settings
- system_configs

Future

- feature_flags
- application_preferences

---

# 16. High-Level Entity Relationship

Institution

│

├── Institution Address

├── Institution Contact

└── Institution Setting

↓

Faculty

↓

Department

↓

Program

↓

Batch

↓

Academic Year

↓

Semester

↓

Course

↓

Subject

↓

Chapter

↓

Topic

↓

Question Bank

↓

Question

↓

Blueprint

↓

Exam

↓

Result

---

# 17. Module Dependency Flow

Institution

↓

Academic

↓

Users

↓

Question Bank

↓

Blueprint

↓

Examination

↓

Results

↓

Reports

This dependency must never be violated.

Example

Question Bank cannot exist without Academic Structure.

Exam cannot exist without Blueprint.

Result cannot exist without Exam.

---

# 18. Cross Module Relationships

Institution

Has Many

Faculties

Institution

Has Many

Departments

Institution

Has Many

Users

Faculty

Has Many

Departments

Department

Has Many

Programs

Program

Has Many

Batches

Batch

Belongs To

Academic Year

Semester

Has Many

Courses

Course

Has Many

Subjects

Subject

Has Many

Chapters

Chapter

Has Many

Topics

Topic

Has Many

Questions

Question Bank

Has Many

Questions

Blueprint

Has Many

Blueprint Sections

Exam

Has Many

Exam Questions

Exam

Has Many

Results

Result

Has Many

Result Items

---

# 19. Future Database Expansion

The database is designed to support future modules without major redesign.

Planned expansions include:

- Multi-campus
- Multi-tenant
- REST API
- Mobile Application
- AI Question Generator
- Question Versioning
- Online Examination
- Digital Evaluation
- Learning Analytics
- LMS Integration
- SSO Integration
- Cloud Deployment

---
# DATABASE_DESIGN.md

## Part-1 (Version 2.0)

## Section-4

---

# 20. Database Security Rules

The database must always be accessed through the Laravel Application Layer.

Approved methods:

- Laravel Eloquent ORM
- Laravel Query Builder

Avoid:

- Raw SQL
- Direct database manipulation
- Dynamic SQL concatenation

---

## SQL Injection Protection

All queries must use parameter binding.

Correct

```
User::where('email', $email)->first();
```

Incorrect

```
SELECT * FROM users WHERE email = '$email'
```

Never build SQL statements using string concatenation.

---

## Sensitive Data

Sensitive information must never be stored in plain text.

Examples

Password

API Keys

Remember Tokens

Recovery Codes

Passwords must always be hashed using Laravel Hash.

---

## Database Credentials

Database credentials belong only inside

```
.env
```

Never commit

```
.env
```

Never hardcode credentials inside PHP source code.

---

# 21. Performance Strategy

The database must remain optimized as the project grows.

General Rules

✓ Index searchable columns

✓ Index every foreign key

✓ Use pagination

✓ Use eager loading

✓ Avoid N+1 queries

✓ Keep transactions short

✓ Use caching where appropriate

Avoid

✗ SELECT *

✗ Unnecessary joins

✗ Duplicate records

✗ Long-running transactions

---

# 22. Index Strategy

Indexes improve query performance.

Primary Index

```
PRIMARY KEY(id)
```

Unique Index

```
users.email

institutions.code
```

Foreign Key Index

```
department_id

subject_id

institution_id
```

Composite Index Example

```
academic_year_id

semester_id
```

should become

```
INDEX

academic_year_id,

semester_id
```

---

## Index Naming

Examples

```
idx_users_email

idx_questions_subject

idx_exam_session
```

---

# 23. Foreign Key Rules

Every relationship must use a foreign key whenever possible.

Example

```
departments

institution_id

↓

institutions.id
```

Rules

Every FK must reference a valid parent.

Delete strategy must be defined.

Update strategy must be defined.

---

## Delete Strategy

Recommended

```
RESTRICT
```

or

```
CASCADE
```

depending on business rules.

Avoid orphan records.

---

# 24. Migration Standards

Every migration must create only one table.

Correct

```
create_users_table
```

Incorrect

```
create_users_and_roles_tables
```

Migration naming

```
create_xxx_table

add_xxx_to_table

remove_xxx_from_table
```

---

## Migration Order

Reference Tables

↓

Master Tables

↓

Transaction Tables

↓

Pivot Tables

↓

Logs

---

# 25. Seeder Standards

Every master table must have

Seeder

Factory

Examples

```
InstitutionSeeder

FacultySeeder

DepartmentSeeder

RoleSeeder

PermissionSeeder
```

Seeders should be

Repeatable

Deterministic

Independent

---

# 26. Backup Strategy

Production Database

Daily Backup

Weekly Full Backup

Monthly Archive

Backups should be

Encrypted

Verified

Stored off-site

Never rely on a single backup location.

---

# 27. Recovery Strategy

Recovery objectives

RPO

≤ 24 Hours

RTO

≤ 2 Hours

Disaster recovery procedures should be documented separately.

---

# 28. Future Scalability

The database has been designed to support

Multi-campus

Multi-language

REST API

GraphQL API

Mobile Application

Cloud Deployment

Horizontal Scaling

AI Question Generator

Online Examination

Digital Evaluation

Analytics Dashboard

LMS Integration

---

# 29. Database Documentation Rules

Every schema change must update

DATABASE_DESIGN.md

DATABASE_DICTIONARY.md

TABLE_RELATIONSHIP_MATRIX.md

ERD.md

Migration files

Related ADR (if architecture changes)

Documentation is part of the implementation.

---

# 30. Database Review Checklist

Before approving any database change, verify:

☐ Naming conventions followed

☐ Table purpose documented

☐ Foreign keys defined

☐ Indexes added

☐ Constraints defined

☐ Soft Delete reviewed

☐ Audit impact reviewed

☐ Seeder prepared

☐ Factory prepared

☐ Relationships documented

☐ Migration tested

☐ Rollback tested

☐ Performance reviewed

☐ Documentation updated

---

# End of DATABASE_DESIGN.md Part-1

Next

DATABASE_DESIGN.md Part-2

Institution Module

Tables

- institutions
- institution_addresses
- institution_contacts
- institution_settings

Each table will include

✓ Purpose

✓ Business Rules

✓ Columns

✓ Constraints

✓ Relationships

✓ Indexes

✓ Laravel Migration Notes

✓ Seeder

✓ Factory

✓ Example Data

✓ Eloquent Relationship

# DATABASE_DESIGN.md

Version: 2.0
Part: 2
Module: Institution Management

---

# Institution Module

## Purpose

The Institution Module is the root module of the Enterprise Question Bank & Examination Management System (EQBEMS).

Every academic structure, examination, question bank, user, and report belongs to an Institution.

This module stores the master information of educational institutions and provides a centralized foundation for all other modules.

---

# Module Objectives

- Manage institution information
- Store official contact information
- Maintain institution settings
- Support future multi-campus architecture
- Provide a central reference for all academic modules

---

# Tables

Current Tables

- institutions
- institution_addresses
- institution_contacts
- institution_settings

Future Tables

- institution_documents
- institution_branches
- institution_accreditations

---

# Entity Relationship

Institution
│
├── InstitutionAddress (One-to-Many)
├── InstitutionContact (One-to-Many)
└── InstitutionSetting (One-to-One)

Institution
│
├── Faculties
├── Departments
├── Programs
├── Users
├── Question Banks
└── Exams

---

# Table : institutions

## Purpose

Stores the master record of an educational institution.

Only active institutions can participate in academic activities.

---

## Table Name

institutions

---

## Primary Key

id

---

## Description

This table represents an educational organization such as:

- University
- College
- Polytechnic
- Medical College
- Training Institute

Version-1 supports one active institution.

The database design is prepared for future multi-institution support.

---

# Columns

| Column | Type | Nullable | Default | Description |
|----------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| code | VARCHAR(20) | No | — | Unique Institution Code |
| name | VARCHAR(255) | No | — | Official Institution Name |
| short_name | VARCHAR(100) | Yes | NULL | Short Display Name |
| email | VARCHAR(150) | Yes | NULL | Official Email Address |
| phone | VARCHAR(30) | Yes | NULL | Primary Contact Number |
| website | VARCHAR(255) | Yes | NULL | Official Website |
| established_year | YEAR | Yes | NULL | Establishment Year |
| logo_path | VARCHAR(255) | Yes | NULL | Institution Logo Path |
| description | TEXT | Yes | NULL | Institution Description |
| status | SMALLINT UNSIGNED | No | 1 | Current Status |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Record Creation Time |
| updated_at | TIMESTAMP | Yes | NULL | Last Update Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete Timestamp |

---

# Column Rules

code

- Required
- Maximum 20 characters
- Unique
- Uppercase recommended

name

- Required
- Maximum 255 characters

email

- Optional
- Must be unique if provided

website

- Optional
- Must be a valid URL

status

Reference Table (Future)

1 = Active

2 = Inactive

3 = Archived

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

UNIQUE(code)

UNIQUE(email)

---

# Indexes

INDEX(name)

INDEX(status)

---

# Foreign Keys

None

This is a root/master table.

---

# Relationships

Institution

Has One

InstitutionSetting

Institution

Has Many

InstitutionAddress

Institution

Has Many

InstitutionContact

Institution

Has Many

Faculty

Institution

Has Many

Department

Institution

Has Many

Program

Institution

Has Many

Batch

Institution

Has Many

AcademicYear

Institution

Has Many

Semester

Institution

Has Many

Subject

Institution

Has Many

QuestionBank

Institution

Has Many

Exam

Institution

Has Many

User

---

# Business Rules

1. Institution Code must be unique.

2. Institution Name is mandatory.

3. Institution cannot be permanently deleted while dependent records exist.

4. Only Active institutions can be selected.

5. Version-1 allows one active institution.

6. Future versions support multiple institutions.

---

# Laravel Model

Institution

---

# Migration

create_institutions_table

---

# Seeder

InstitutionSeeder

---

# Factory

InstitutionFactory

---

# Example Record

| Field | Value |
|---------|-------------------------------|
| code | DIU |
| name | Daffodil International University |
| short_name | DIU |
| email | info@diu.edu.bd |
| phone | +88029611111 |
| website | https://daffodilvarsity.edu.bd |
| established_year | 2002 |
| status | Active |

---

# Validation Rules (Laravel)

code

required

string

max:20

unique:institutions

name

required

string

max:255

email

nullable

email

unique:institutions

website

nullable

url

logo_path

nullable

string

max:255

status

required

integer

---

# Notes

- All timestamps use Laravel conventions.
- Soft Deletes are enabled.
- Business audit information is handled through the Audit Log module.
- Institution is the root entity of the entire system.

---

# Table : institution_addresses

---

## Purpose

Stores all physical and mailing addresses of an institution.

An institution may have multiple addresses.

Examples

- Head Office

- Campus

- Branch

- Billing Address

- Mailing Address

Only one address may be marked as the primary address.

---

## Table Name

institution_addresses

---

## Primary Key

id

---

## Description

This table stores complete address information for an institution.

The design supports future expansion for:

- Multi-campus

- Multiple Branches

- GIS Integration

- Google Maps

- International Address Formats

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|--------------------|----------|----------|----------------------------|
| id | BIGINT UNSIGNED | No | AI | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | - | Institution Reference |
| address_type | VARCHAR(30) | No | Main | Address Category |
| address_line_1 | VARCHAR(255) | No | - | Address Line 1 |
| address_line_2 | VARCHAR(255) | Yes | NULL | Address Line 2 |
| city | VARCHAR(100) | Yes | NULL | City |
| state | VARCHAR(100) | Yes | NULL | State / Division |
| district | VARCHAR(100) | Yes | NULL | District |
| postal_code | VARCHAR(20) | Yes | NULL | Postal Code |
| country | VARCHAR(100) | No | Bangladesh | Country |
| latitude | DECIMAL(10,8) | Yes | NULL | GPS Latitude |
| longitude | DECIMAL(11,8) | Yes | NULL | GPS Longitude |
| is_primary | BOOLEAN | No | FALSE | Primary Address |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Address Types

Recommended Values

Main

Campus

Branch

Billing

Shipping

Mailing

Temporary

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

uuid

Foreign Key

institution_id

↓

institutions.id

---

# Indexes

INDEX(institution_id)

INDEX(address_type)

INDEX(status)

---

# Relationships

Institution

HasMany

InstitutionAddress

InstitutionAddress

BelongsTo

Institution

---

# Business Rules

1.

One institution may have multiple addresses.

2.

Only one address can be primary.

3.

Deleted addresses are hidden.

4.

Country defaults to Bangladesh.

5.

Latitude and Longitude are optional.

---

# Laravel Model

InstitutionAddress

---

# Migration

create_institution_addresses_table

---

# Seeder

InstitutionAddressSeeder

---

# Factory

InstitutionAddressFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

address_type

required

max:30

address_line_1

required

max:255

postal_code

nullable

max:20

latitude

nullable

numeric

longitude

nullable

numeric

status

required

integer

---

# Example Record

institution_id

1

address_type

Main

address_line_1

Ashulia, Savar

city

Dhaka

district

Dhaka

postal_code

1341

country

Bangladesh

is_primary

true

status

Active

---

# Notes

Soft Delete Enabled.

Supports future GIS integration.

Supports multiple addresses.

Supports international address formats.

---

# DATABASE_DESIGN.md

## Part-2

## Section-3

# Table : institution_contacts

---

## Purpose

Stores official contact persons for an institution.

An institution may have multiple contact persons, but only one can be marked as the primary contact.

This table stores communication information for administrative, academic and emergency purposes.

---

## Table Name

institution_contacts

---

## Primary Key

id

---

## Description

Each record represents one contact person associated with an institution.

Examples

- Principal
- Vice Chancellor
- Registrar
- Administrative Officer
- IT Administrator
- Admission Officer

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|-------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Institution Reference |
| person_name | VARCHAR(150) | No | — | Contact Person Name |
| designation | VARCHAR(100) | Yes | NULL | Job Title |
| department | VARCHAR(100) | Yes | NULL | Department Name |
| email | VARCHAR(150) | Yes | NULL | Email Address |
| phone | VARCHAR(30) | Yes | NULL | Office Phone |
| mobile | VARCHAR(30) | Yes | NULL | Mobile Number |
| extension | VARCHAR(10) | Yes | NULL | Office Extension |
| contact_type | VARCHAR(30) | No | Official | Contact Category |
| is_primary | BOOLEAN | No | FALSE | Primary Contact |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Additional Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Contact Types

Recommended Values

- Official
- Academic
- Administrative
- Finance
- Admission
- Technical
- Emergency

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

uuid

Foreign Key

institution_id

↓

institutions.id

---

# Indexes

INDEX(institution_id)

INDEX(contact_type)

INDEX(status)

INDEX(person_name)

---

# Relationships

Institution

HasMany

InstitutionContact

InstitutionContact

BelongsTo

Institution

---

# Business Rules

1. One institution may have multiple contact persons.

2. Only one contact person may be marked as the primary contact.

3. Email should be unique within the same institution if provided.

4. Deleted contacts are not displayed in the application.

5. Primary contact should always be active.

---

# Laravel Model

InstitutionContact

---

# Migration

create_institution_contacts_table

---

# Seeder

InstitutionContactSeeder

---

# Factory

InstitutionContactFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

person_name

required

string

max:150

designation

nullable

max:100

department

nullable

max:100

email

nullable

email

max:150

phone

nullable

max:30

mobile

nullable

max:30

extension

nullable

max:10

contact_type

required

max:30

status

required

integer

---

# Example Record

| Field | Value |
|--------|-------------------------------|
| institution_id | 1 |
| person_name | Dr. Md. Rahman |
| designation | Registrar |
| department | Administration |
| email | registrar@example.edu |
| phone | +88029611234 |
| mobile | +8801712345678 |
| extension | 101 |
| contact_type | Official |
| is_primary | true |
| status | Active |

---

# Notes

- Supports multiple contacts for each institution.
- Soft Delete is enabled.
- UUID is used for external references and API integrations.
- Primary contact should be used for official communication.
- Future versions may separate phone numbers into a dedicated contact_numbers table if multiple numbers per person become necessary.

---

# Table : institution_settings

---

## Purpose

Stores configurable settings for an institution using a flexible key-value architecture.

Instead of creating new database columns for every new setting, new configuration values are stored as records.

This design minimizes future database migrations and supports unlimited configuration options.

---

## Table Name

institution_settings

---

## Primary Key

id

---

## Description

Each record represents one configurable setting.

Examples

timezone

locale

date_format

logo_path

smtp_host

smtp_port

theme

default_language

question_shuffle

exam_duration

---

# Columns

| Column | Type | Nullable | Default | Description |
|----------|------------------|----------|----------|-----------------------------|
| id | BIGINT UNSIGNED | No | AI | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Institution Reference |
| setting_key | VARCHAR(100) | No | — | Configuration Key |
| setting_value | LONGTEXT | Yes | NULL | Configuration Value |
| data_type | VARCHAR(30) | No | string | Value Data Type |
| group_name | VARCHAR(50) | No | general | Setting Group |
| is_public | BOOLEAN | No | FALSE | Publicly Accessible |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Additional Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Supported Data Types

string

integer

boolean

json

date

datetime

text

float

---

# Setting Groups

general

branding

localization

mail

security

academic

examination

question_bank

notification

api

ai

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

(uuid)

UNIQUE(institution_id, setting_key)

Foreign Key

institution_id

↓

institutions.id

---

# Indexes

INDEX(institution_id)

INDEX(setting_key)

INDEX(group_name)

INDEX(status)

---

# Relationships

Institution

HasMany

InstitutionSetting

InstitutionSetting

BelongsTo

Institution

---

# Business Rules

1.

Each setting_key must be unique within an institution.

2.

New settings must not require schema changes.

3.

Setting values may contain JSON.

4.

Only active settings are loaded.

5.

Sensitive settings should be encrypted at the application layer.

---

# Laravel Model

InstitutionSetting

---

# Migration

create_institution_settings_table

---

# Seeder

InstitutionSettingSeeder

---

# Factory

InstitutionSettingFactory

---

# Example Records

| institution_id | setting_key | setting_value | group_name |
|----------------|-------------|---------------|------------|
| 1 | timezone | Asia/Dhaka | general |
| 1 | locale | en | localization |
| 1 | logo_path | uploads/logo.png | branding |
| 1 | exam_duration | 120 | examination |
| 1 | smtp_host | smtp.gmail.com | mail |

---

# Validation Rules

institution_id

required

exists:institutions,id

setting_key

required

string

max:100

setting_value

nullable

data_type

required

group_name

required

status

required

integer

---

# Notes

- Uses a flexible key-value architecture.
- Eliminates frequent migration changes.
- Supports unlimited future configuration.
- Compatible with API, mobile applications, and multi-tenant architecture.
- Sensitive values should be encrypted before storage.

---

# Module Summary

| Table | Purpose |
|---------|--------------------------------------------|
| institutions | Master Institution Information |
| institution_addresses | Physical & Mailing Addresses |
| institution_contacts | Official Contact Persons |
| institution_settings | Dynamic Institution Configuration |

---

# Laravel Models

Institution

InstitutionAddress

InstitutionContact

InstitutionSetting

---

# Migration Files

create_institutions_table

create_institution_addresses_table

create_institution_contacts_table

create_institution_settings_table

---

# Seeder Files

InstitutionSeeder

InstitutionAddressSeeder

InstitutionContactSeeder

InstitutionSettingSeeder

---

# Factory Files

InstitutionFactory

InstitutionAddressFactory

InstitutionContactFactory

InstitutionSettingFactory

---

# End of DATABASE_DESIGN.md


# DATABASE_DESIGN.md

Version: 2.0

Part-3

Academic Module

---

# Academic Module

## Purpose

The Academic Module defines the academic hierarchy of an educational institution.

All Question Banks, Blueprints, Examinations, Results, and Reports depend on this structure.

This module is considered the backbone of the entire EQBEMS.

---

# Module Objectives

The Academic Module is responsible for:

- Faculty Management
- Department Management
- Program Management
- Batch Management
- Academic Year Management
- Semester Management
- Subject Management
- Chapter Management
- Topic Management

---

# Academic Hierarchy

Institution

↓

Faculty

↓

Department

↓

Program

↓

Batch

↓

Subject

↓

Chapter

↓

Topic

Academic Years and Semesters are linked with Programs and Batches, not directly with Subjects.

---

# Current Tables

faculties

departments

programs

batches

academic_years

semesters

subjects

chapters

topics

---

# Future Tables

course_outcomes

program_outcomes

learning_outcomes

course_offerings

credit_transfers

curriculums

---

# Table : faculties

## Purpose

Stores faculty information under an institution.

Examples

Faculty of Science

Faculty of Engineering

Faculty of Business Studies

Faculty of Arts

Faculty of Social Science

---

## Table Name

faculties

---

## Primary Key

id

---

## Description

A Faculty is the highest academic division under an Institution.

Each Faculty contains one or more Departments.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|-----------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| code | VARCHAR(20) | No | — | Faculty Code |
| name | VARCHAR(255) | No | — | Faculty Name |
| short_name | VARCHAR(100) | Yes | NULL | Short Name |
| dean_name | VARCHAR(150) | Yes | NULL | Current Dean |
| office_phone | VARCHAR(30) | Yes | NULL | Office Phone |
| office_email | VARCHAR(150) | Yes | NULL | Official Email |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

UNIQUE(uuid)

UNIQUE(institution_id, code)

---

# Foreign Key

institution_id

↓

institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(code)

INDEX(name)

INDEX(status)

---

# Relationships

Institution

HasMany

Faculty

Faculty

BelongsTo

Institution

Faculty

HasMany

Department

---

# Business Rules

1. Every Faculty belongs to one Institution.

2. Faculty Code must be unique within the same Institution.

3. Faculty Name is mandatory.

4. A Faculty cannot be deleted while Departments exist.

5. Only Active Faculties are selectable.

---

# Laravel Model

Faculty

---

# Migration

create_faculties_table

---

# Seeder

FacultySeeder

---

# Factory

FacultyFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

code

required

max:20

name

required

max:255

office_email

nullable

email

office_phone

nullable

max:30

status

required

integer

---

# Example Record

| Field | Value |
|--------|------------------------------|
| institution_id | 1 |
| code | FE |
| name | Faculty of Engineering |
| short_name | Engineering |
| dean_name | Prof. Dr. A. Rahman |
| office_email | engineering@example.edu |
| office_phone | +88029611234 |
| status | Active |

---

# Notes

- UUID is used for public APIs.
- Soft Delete is enabled.
- Faculty is a master academic entity.
- Faculty must always belong to an Institution.

---

End of Section-1

# Table : departments

---

## Purpose

Stores academic departments under a faculty.

A Department belongs to one Institution and one Faculty.

Each Department may offer one or more academic Programs.

Examples

- Department of Computer Science & Engineering
- Department of Electrical Engineering
- Department of Mathematics
- Department of English
- Department of Business Administration

---

## Table Name

departments

---

## Primary Key

id

---

## Description

Departments are the primary academic units responsible for offering Programs, managing faculty members, and maintaining Subjects within a Faculty.

Each Department belongs to exactly one Faculty.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|-------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| faculty_id | BIGINT UNSIGNED | No | — | Parent Faculty |
| code | VARCHAR(20) | No | — | Department Code |
| name | VARCHAR(255) | No | — | Department Name |
| short_name | VARCHAR(100) | Yes | NULL | Short Name |
| head_name | VARCHAR(150) | Yes | NULL | Department Head |
| office_phone | VARCHAR(30) | Yes | NULL | Office Phone |
| office_email | VARCHAR(150) | Yes | NULL | Official Email |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

UNIQUE(uuid)

UNIQUE(institution_id, faculty_id, code)

---

# Foreign Keys

institution_id

↓

institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

faculty_id

↓

faculties.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(faculty_id)

INDEX(code)

INDEX(name)

INDEX(status)

---

# Relationships

Institution

HasMany

Department

Faculty

HasMany

Department

Department

BelongsTo

Institution

Department

BelongsTo

Faculty

Department

HasMany

Program

---

# Business Rules

1. Every Department belongs to one Institution.

2. Every Department belongs to one Faculty.

3. Department Code must be unique within the same Faculty.

4. Department Name is mandatory.

5. A Department cannot be deleted while Programs exist.

6. Only Active Departments are available for selection.

---

# Laravel Model

Department

---

# Migration

create_departments_table

---

# Seeder

DepartmentSeeder

---

# Factory

DepartmentFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

faculty_id

required

exists:faculties,id

code

required

string

max:20

name

required

string

max:255

office_email

nullable

email

office_phone

nullable

max:30

status

required

integer

---

# Example Record

| Field | Value |
|--------|------------------------------|
| institution_id | 1 |
| faculty_id | 1 |
| code | CSE |
| name | Department of Computer Science & Engineering |
| short_name | CSE |
| head_name | Prof. Dr. Hasan Mahmud |
| office_email | cse@example.edu |
| office_phone | +88029611250 |
| status | Active |

---

# Notes

- UUID is used for API and external integrations.
- Soft Delete is enabled.
- Department is a core academic entity.
- Every Department must belong to both an Institution and a Faculty.
- Programs cannot exist without a Department.

---

End of Section-2

# Table : programs

---

## Purpose

Stores academic programs offered by a department.

A Program belongs to one Institution, one Faculty and one Department.

Each Program may contain multiple Batches, Academic Years, Semesters and Subjects.

Examples

- B.Sc. in Computer Science & Engineering
- BBA
- MBA
- M.Sc. in Computer Science
- Diploma in Civil Engineering

---

## Table Name

programs

---

## Primary Key

id

---

## Description

A Program represents an academic degree or certification offered by a Department.

Programs define the academic structure that students follow throughout their study period.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| faculty_id | BIGINT UNSIGNED | No | — | Parent Faculty |
| department_id | BIGINT UNSIGNED | No | — | Parent Department |
| code | VARCHAR(20) | No | — | Program Code |
| name | VARCHAR(255) | No | — | Program Name |
| short_name | VARCHAR(100) | Yes | NULL | Short Name |
| degree | VARCHAR(100) | No | — | Degree Name |
| duration_years | DECIMAL(4,1) | No | 4.0 | Program Duration |
| total_semesters | SMALLINT UNSIGNED | No | 8 | Total Semesters |
| total_credits | DECIMAL(6,2) | Yes | NULL | Total Credits |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

Primary Key

PRIMARY KEY(id)

Unique

UNIQUE(uuid)

UNIQUE(institution_id, department_id, code)

---

# Foreign Keys

institution_id

↓

institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

faculty_id

↓

faculties.id

ON DELETE RESTRICT

ON UPDATE CASCADE

department_id

↓

departments.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(faculty_id)

INDEX(department_id)

INDEX(code)

INDEX(name)

INDEX(status)

---

# Relationships

Institution

HasMany

Program

Faculty

HasMany

Program

Department

HasMany

Program

Program

BelongsTo

Institution

Program

BelongsTo

Faculty

Program

BelongsTo

Department

Program

HasMany

Batch

Program

HasMany

AcademicYear

Program

HasMany

Semester

Program

HasMany

Subject

---

# Business Rules

1. Every Program belongs to one Institution.

2. Every Program belongs to one Faculty.

3. Every Program belongs to one Department.

4. Program Code must be unique within the same Department.

5. Program Name is mandatory.

6. Program cannot be deleted while Batches or Subjects exist.

7. Only Active Programs are available for selection.

---

# Laravel Model

Program

---

# Migration

create_programs_table

---

# Seeder

ProgramSeeder

---

# Factory

ProgramFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

faculty_id

required

exists:faculties,id

department_id

required

exists:departments,id

code

required

string

max:20

name

required

string

max:255

degree

required

string

max:100

duration_years

required

numeric

total_semesters

required

integer

total_credits

nullable

numeric

status

required

integer

---

# Example Record

| Field | Value |
|--------|-----------------------------|
| institution_id | 1 |
| faculty_id | 1 |
| department_id | 1 |
| code | BSC-CSE |
| name | Bachelor of Science in Computer Science & Engineering |
| short_name | B.Sc. CSE |
| degree | Bachelor |
| duration_years | 4.0 |
| total_semesters | 8 |
| total_credits | 160.00 |
| status | Active |

---

# Notes

- UUID is used for external integrations.
- Soft Delete is enabled.
- Program is a master academic entity.
- Every Program must belong to one Department.
- Batches, Academic Years, Semesters and Subjects are organized under a Program.

---

End of Section-3
# Table : batches

---

## Purpose

Stores student admission batches (cohorts) under a specific Program and Academic Year.

A Batch represents a group of students admitted in the same intake.

Examples:

- CSE-61
- CSE-62
- BBA-32
- MBA-18

---

## Table Name

batches

---

## Primary Key

id

---

## Description

Each Batch belongs to one Institution, one Faculty, one Department, one Program, and one Academic Year.

A Batch groups students for academic planning, examinations, attendance, and reporting.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| faculty_id | BIGINT UNSIGNED | No | — | Parent Faculty |
| department_id | BIGINT UNSIGNED | No | — | Parent Department |
| program_id | BIGINT UNSIGNED | No | — | Parent Program |
| academic_year_id | BIGINT UNSIGNED | No | — | Admission Academic Year |
| code | VARCHAR(30) | No | — | Batch Code |
| name | VARCHAR(150) | No | — | Batch Name |
| admission_year | YEAR | No | — | Admission Year |
| expected_graduation_year | YEAR | Yes | NULL | Expected Graduation |
| capacity | SMALLINT UNSIGNED | Yes | NULL | Maximum Students |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(program_id, code)

---

# Foreign Keys

institution_id → institutions.id

faculty_id → faculties.id

department_id → departments.id

program_id → programs.id

academic_year_id → academic_years.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(program_id)

INDEX(academic_year_id)

INDEX(code)

INDEX(status)

---

# Relationships

Institution

HasMany

Batch

Faculty

HasMany

Batch

Department

HasMany

Batch

Program

HasMany

Batch

AcademicYear

HasMany

Batch

Batch

BelongsTo

Institution

Batch

BelongsTo

Faculty

Batch

BelongsTo

Department

Batch

BelongsTo

Program

Batch

BelongsTo

AcademicYear

Batch

HasMany

Student (Future)

Batch

HasMany

Exam (Future)

---

# Business Rules

1. Every Batch belongs to one Institution.

2. Every Batch belongs to one Program.

3. Every Batch belongs to one Academic Year.

4. Batch Code must be unique within the same Program.

5. Admission Year is mandatory.

6. Capacity must be greater than zero if specified.

7. Deleted batches are not shown in normal operations.

---

# Laravel Model

Batch

---

# Migration

create_batches_table

---

# Seeder

BatchSeeder

---

# Factory

BatchFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

program_id

required

exists:programs,id

academic_year_id

required

exists:academic_years,id

code

required

string

max:30

name

required

string

max:150

admission_year

required

date_format:Y

capacity

nullable

integer

min:1

status

required

integer

---

# Example Record

| Field | Value |
|--------|----------------|
| institution_id | 1 |
| program_id | 1 |
| academic_year_id | 3 |
| code | CSE-61 |
| name | CSE 61st Batch |
| admission_year | 2025 |
| expected_graduation_year | 2029 |
| capacity | 120 |
| status | Active |

---

# Notes

- UUID is used for API integrations.
- Soft Delete is enabled.
- Batch is a cohort, not a class section.
- One Program can have many Batches.
- One Academic Year can contain many Batches.

---

End of Section-4

# Table : academic_years

---

## Purpose

Stores academic years (academic sessions) of an institution.

Academic Years are shared across all Programs within the same Institution.

Examples

- 2024–2025
- 2025–2026
- 2026–2027

---

## Table Name

academic_years

---

## Primary Key

id

---

## Description

An Academic Year defines an official educational session of an Institution.

It is used for student admission, Batch creation, Semester scheduling, Examination planning, and Result publication.

Each Institution may maintain multiple Academic Years.

Only one Academic Year can be marked as the Current Academic Year.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| code | VARCHAR(20) | No | — | Academic Year Code |
| name | VARCHAR(100) | No | — | Academic Year Name |
| start_date | DATE | No | — | Academic Year Start Date |
| end_date | DATE | No | — | Academic Year End Date |
| is_current | BOOLEAN | No | FALSE | Current Academic Year |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(institution_id, code)

---

# Foreign Key

institution_id

↓

institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(code)

INDEX(is_current)

INDEX(status)

---

# Relationships

Institution

HasMany

AcademicYear

AcademicYear

BelongsTo

Institution

AcademicYear

HasMany

Batch

AcademicYear

HasMany

Semester

---

# Business Rules

1. Every Academic Year belongs to one Institution.

2. Academic Year Code must be unique within the Institution.

3. Start Date must be earlier than End Date.

4. Only one Academic Year can be marked as Current.

5. Deleted Academic Years are hidden from normal operations.

---

# Laravel Model

AcademicYear

---

# Migration

create_academic_years_table

---

# Seeder

AcademicYearSeeder

---

# Factory

AcademicYearFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

code

required

string

max:20

name

required

string

max:100

start_date

required

date

end_date

required

date

after:start_date

is_current

boolean

status

required

integer

---

# Example Record

| Field | Value |
|--------|----------------|
| institution_id | 1 |
| code | AY-2025-26 |
| name | Academic Year 2025–2026 |
| start_date | 2025-01-01 |
| end_date | 2025-12-31 |
| is_current | true |
| status | Active |

---

# Notes

- UUID is used for public APIs.
- Soft Delete is enabled.
- Only one Academic Year may be active at a time.
- Shared by all Programs under the same Institution.

---

End of Section-5

# Table : semesters

---

## Purpose

Stores semester information for academic programs.

A Semester belongs to one Institution, one Program, and one Academic Year.

It is used to organize subjects, examinations, registrations, and academic progress.

Examples

- 1st Semester
- 2nd Semester
- Spring 2025
- Fall 2025

---

## Table Name

semesters

---

## Primary Key

id

---

## Description

A Semester represents an academic period within a Program.

Each Semester is associated with an Academic Year and may contain multiple Subjects.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| program_id | BIGINT UNSIGNED | No | — | Parent Program |
| academic_year_id | BIGINT UNSIGNED | No | — | Academic Year |
| code | VARCHAR(20) | No | — | Semester Code |
| name | VARCHAR(100) | No | — | Semester Name |
| semester_no | SMALLINT UNSIGNED | No | — | Semester Number |
| start_date | DATE | No | — | Semester Start Date |
| end_date | DATE | No | — | Semester End Date |
| is_current | BOOLEAN | No | FALSE | Current Semester |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(program_id, academic_year_id, semester_no)

---

# Foreign Keys

institution_id → institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

program_id → programs.id

ON DELETE RESTRICT

ON UPDATE CASCADE

academic_year_id → academic_years.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(program_id)

INDEX(academic_year_id)

INDEX(semester_no)

INDEX(status)

---

# Relationships

Institution

HasMany

Semester

Program

HasMany

Semester

AcademicYear

HasMany

Semester

Semester

BelongsTo

Institution

Semester

BelongsTo

Program

Semester

BelongsTo

AcademicYear

Semester

HasMany

SemesterSubject (Future)

Semester

HasMany

Exam (Future)

---

# Business Rules

1. Every Semester belongs to one Institution.

2. Every Semester belongs to one Program.

3. Every Semester belongs to one Academic Year.

4. Semester Number must be unique within the same Program and Academic Year.

5. Start Date must be earlier than End Date.

6. Only one Semester can be marked as Current within a Program.

---

# Laravel Model

Semester

---

# Migration

create_semesters_table

---

# Seeder

SemesterSeeder

---

# Factory

SemesterFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

program_id

required

exists:programs,id

academic_year_id

required

exists:academic_years,id

code

required

string

max:20

name

required

string

max:100

semester_no

required

integer

min:1

start_date

required

date

end_date

required

date

after:start_date

status

required

integer

---

# Example Record

| Field | Value |
|--------|----------------|
| institution_id | 1 |
| program_id | 1 |
| academic_year_id | 2 |
| code | SEM-01 |
| name | 1st Semester |
| semester_no | 1 |
| start_date | 2025-01-15 |
| end_date | 2025-06-15 |
| is_current | true |
| status | Active |

---

# Notes

- UUID is used for API integrations.
- Soft Delete is enabled.
- Semester belongs to a Program, not directly to a Batch.
- Subjects will be assigned through the semester_subjects table in a future module.

---

End of Section-6

# DATABASE_DESIGN.md

Version: 2.0

Part-3

Section-7

# Table : subjects

---

## Purpose

Stores master subject/course information for academic programs.

A Subject belongs to one Institution, one Department, and one Program.

Each Subject can contain multiple Chapters, Topics, Question Banks and Examinations.

Subjects are assigned to Semesters through the `semester_subjects` table (future enhancement).

---

## Table Name

subjects

---

## Primary Key

id

---

## Description

A Subject represents an academic course within a Program.

Examples

- Structured Programming
- Data Structures
- Database Management System
- Operating System
- Artificial Intelligence

Each Subject is reusable and serves as the academic foundation for syllabus, question banks, examinations and result processing.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| faculty_id | BIGINT UNSIGNED | No | — | Parent Faculty |
| department_id | BIGINT UNSIGNED | No | — | Parent Department |
| program_id | BIGINT UNSIGNED | No | — | Parent Program |
| code | VARCHAR(30) | No | — | Subject Code |
| name | VARCHAR(255) | No | — | Subject Name |
| short_name | VARCHAR(100) | Yes | NULL | Short Name |
| credit_hours | DECIMAL(4,2) | No | 3.00 | Credit Hours |
| theory_marks | SMALLINT UNSIGNED | Yes | NULL | Theory Marks |
| practical_marks | SMALLINT UNSIGNED | Yes | NULL | Practical Marks |
| total_marks | SMALLINT UNSIGNED | Yes | NULL | Total Marks |
| description | TEXT | Yes | NULL | Description |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(program_id, code)

---

# Foreign Keys

institution_id → institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

faculty_id → faculties.id

ON DELETE RESTRICT

ON UPDATE CASCADE

department_id → departments.id

ON DELETE RESTRICT

ON UPDATE CASCADE

program_id → programs.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(program_id)

INDEX(code)

INDEX(name)

INDEX(status)

---

# Relationships

Institution

HasMany

Subject

Faculty

HasMany

Subject

Department

HasMany

Subject

Program

HasMany

Subject

Subject

BelongsTo

Institution

Subject

BelongsTo

Faculty

Subject

BelongsTo

Department

Subject

BelongsTo

Program

Subject

HasMany

Chapter

Subject

HasMany

QuestionBank (Future)

Subject

HasMany

Blueprint (Future)

Subject

BelongsToMany

Semester (Future)

---

# Business Rules

1. Every Subject belongs to one Institution.

2. Every Subject belongs to one Department.

3. Every Subject belongs to one Program.

4. Subject Code must be unique within the same Program.

5. Subject Name is mandatory.

6. Credit Hours must be greater than zero.

7. Total Marks should equal Theory + Practical (if both exist).

8. Deleted Subjects cannot be used for new examinations.

---

# Laravel Model

Subject

---

# Migration

create_subjects_table

---

# Seeder

SubjectSeeder

---

# Factory

SubjectFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

faculty_id

required

exists:faculties,id

department_id

required

exists:departments,id

program_id

required

exists:programs,id

code

required

string

max:30

name

required

string

max:255

credit_hours

required

numeric

min:0.5

theory_marks

nullable

integer

practical_marks

nullable

integer

total_marks

nullable

integer

status

required

integer

---

# Example Record

| Field | Value |
|--------|------------------------------|
| institution_id | 1 |
| faculty_id | 1 |
| department_id | 1 |
| program_id | 1 |
| code | CSE-221 |
| name | Data Structures |
| short_name | DS |
| credit_hours | 3.00 |
| theory_marks | 70 |
| practical_marks | 30 |
| total_marks | 100 |
| status | Active |

---

# Notes

- UUID is used for API integrations.
- Soft Delete is enabled.
- Subject is the parent entity of Chapters and Topics.
- Question Bank, Blueprint and Examination modules will reference this table.
- Semester assignment will be handled through a future `semester_subjects` pivot table.

---

End of Section-7

# DATABASE_DESIGN.md

Version: 2.0

Part-3

Section-8

# Table : chapters

---

## Purpose

Stores syllabus chapters under a Subject.

Each Chapter represents a logical unit of study and is used for syllabus management, question categorization, blueprint generation and examination planning.

A Subject may contain multiple Chapters.

---

## Table Name

chapters

---

## Primary Key

id

---

## Description

A Chapter is a subdivision of a Subject.

Examples

- Introduction to Programming
- Control Statements
- Arrays
- Linked Lists
- Trees
- Graphs

Each Chapter contains one or more Topics.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| faculty_id | BIGINT UNSIGNED | No | — | Parent Faculty |
| department_id | BIGINT UNSIGNED | No | — | Parent Department |
| program_id | BIGINT UNSIGNED | No | — | Parent Program |
| subject_id | BIGINT UNSIGNED | No | — | Parent Subject |
| chapter_no | SMALLINT UNSIGNED | No | — | Chapter Number |
| code | VARCHAR(30) | Yes | NULL | Chapter Code |
| name | VARCHAR(255) | No | — | Chapter Name |
| description | TEXT | Yes | NULL | Description |
| learning_objectives | TEXT | Yes | NULL | Learning Objectives |
| estimated_hours | DECIMAL(4,2) | Yes | NULL | Estimated Teaching Hours |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(subject_id, chapter_no)

---

# Foreign Keys

institution_id → institutions.id

faculty_id → faculties.id

department_id → departments.id

program_id → programs.id

subject_id → subjects.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(subject_id)

INDEX(chapter_no)

INDEX(display_order)

INDEX(status)

---

# Relationships

Institution

HasMany

Chapter

Program

HasMany

Chapter

Subject

HasMany

Chapter

Chapter

BelongsTo

Institution

Chapter

BelongsTo

Program

Chapter

BelongsTo

Subject

Chapter

HasMany

Topic

Chapter

HasMany

Question (Future)

---

# Business Rules

1. Every Chapter belongs to one Subject.

2. Chapter Number must be unique within the same Subject.

3. Chapter Name is mandatory.

4. Display Order controls syllabus presentation.

5. Deleted Chapters cannot be assigned to new Questions.

---

# Laravel Model

Chapter

---

# Migration

create_chapters_table

---

# Seeder

ChapterSeeder

---

# Factory

ChapterFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

subject_id

required

exists:subjects,id

chapter_no

required

integer

min:1

name

required

string

max:255

estimated_hours

nullable

numeric

display_order

required

integer

status

required

integer

---

# Example Record

| Field | Value |
|--------|-----------------------------|
| institution_id | 1 |
| subject_id | 5 |
| chapter_no | 3 |
| code | CH-03 |
| name | Linked Lists |
| estimated_hours | 8.00 |
| display_order | 3 |
| status | Active |

---

# Notes

- UUID is used for API integrations.
- Soft Delete is enabled.
- Chapter is the parent entity of Topics.
- Question Bank and Blueprint modules will categorize questions by Chapter.
- Learning Objectives support Outcome-Based Education (OBE) in future versions.

---

End of Section-8

# Table : topics

---

## Purpose

Stores syllabus topics under each Chapter.

Topics represent the smallest academic learning unit and are used for question categorization, blueprint generation, examinations, analytics and learning outcome mapping.

Each Chapter may contain multiple Topics.

---

## Table Name

topics

---

## Primary Key

id

---

## Description

A Topic represents a detailed subject area within a Chapter.

Examples

Chapter

Linked Lists

Topics

Introduction

Insertion

Deletion

Traversal

Searching

Applications

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| faculty_id | BIGINT UNSIGNED | No | — | Parent Faculty |
| department_id | BIGINT UNSIGNED | No | — | Parent Department |
| program_id | BIGINT UNSIGNED | No | — | Parent Program |
| subject_id | BIGINT UNSIGNED | No | — | Parent Subject |
| chapter_id | BIGINT UNSIGNED | No | — | Parent Chapter |
| topic_no | SMALLINT UNSIGNED | No | — | Topic Number |
| code | VARCHAR(30) | Yes | NULL | Topic Code |
| name | VARCHAR(255) | No | — | Topic Name |
| description | TEXT | Yes | NULL | Description |
| learning_objectives | TEXT | Yes | NULL | Learning Objectives |
| estimated_hours | DECIMAL(4,2) | Yes | NULL | Estimated Teaching Hours |
| difficulty_level | ENUM('Easy','Medium','Hard') | No | Medium | Recommended Difficulty |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(chapter_id, topic_no)

---

# Foreign Keys

institution_id → institutions.id

faculty_id → faculties.id

department_id → departments.id

program_id → programs.id

subject_id → subjects.id

chapter_id → chapters.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(subject_id)

INDEX(chapter_id)

INDEX(display_order)

INDEX(status)

---

# Relationships

Institution

HasMany

Topic

Subject

HasMany

Topic

Chapter

HasMany

Topic

Topic

BelongsTo

Institution

Topic

BelongsTo

Subject

Topic

BelongsTo

Chapter

Topic

BelongsToMany

Question (Future)

---

# Business Rules

1. Every Topic belongs to one Chapter.

2. Topic Number must be unique within the same Chapter.

3. Topic Name is mandatory.

4. Topics are used to classify Questions.

5. Deleted Topics cannot be assigned to new Questions.

---

# Laravel Model

Topic

---

# Migration

create_topics_table

---

# Seeder

TopicSeeder

---

# Factory

TopicFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

chapter_id

required

exists:chapters,id

topic_no

required

integer

min:1

name

required

string

max:255

difficulty_level

required

in:Easy,Medium,Hard

estimated_hours

nullable

numeric

display_order

required

integer

status

required

integer

---

# Example Record

| Field | Value |
|--------|-----------------------------|
| institution_id | 1 |
| subject_id | 5 |
| chapter_id | 8 |
| topic_no | 2 |
| code | TP-02 |
| name | Insertion in Linked List |
| difficulty_level | Medium |
| estimated_hours | 2.00 |
| display_order | 2 |
| status | Active |

---

# Notes

- UUID is used for API integrations.
- Soft Delete is enabled.
- Topic is the smallest academic unit.
- Question Bank, Blueprint and Analytics modules depend on Topics.
- Questions will be linked through the `question_topics` pivot table in future versions.

---

# Academic Module Summary

## Tables

1. faculties
2. departments
3. programs
4. batches
5. academic_years
6. semesters
7. subjects
8. chapters
9. topics

---

## Relationships

Institution
└── Faculties
    └── Departments
        └── Programs
            ├── Batches
            ├── Semesters
            └── Subjects
                └── Chapters
                    └── Topics

---

## Future Supporting Tables

- semester_subjects
- program_subjects
- question_topics
- course_outcomes
- learning_outcomes
- program_outcomes
- academic_calendars

---

## End of Part-3

# DATABASE_DESIGN.md

Version: 2.0

Part-4

Question Bank Module

---

# Question Bank Module

## Purpose

The Question Bank Module is the core component of EQBEMS.

It manages the complete lifecycle of questions including creation, categorization, review, approval, versioning, media attachments, tagging and examination usage.

The module is designed to support multiple educational systems including universities, colleges, schools, professional certifications and recruitment examinations.

---

# Module Objectives

The Question Bank Module is responsible for:

- Question Creation
- Question Classification
- Question Version Control
- Question Approval Workflow
- Question Tagging
- Question Media Management
- Question Search
- Question Analytics
- Question Reuse
- Question Archiving

---

# Question Lifecycle

Draft

↓

Under Review

↓

Approved

↓

Published

↓

Used in Examination

↓

Archived

---

# Supported Question Types

- Multiple Choice Question (MCQ)
- Multiple Response
- True / False
- Fill in the Blank
- Short Answer
- Essay
- Matching
- Ordering
- Numerical
- Programming
- Mathematical Formula
- Image Based
- Audio Based
- Case Study
- File Upload

---

# Lookup Tables

The following lookup tables are used throughout the Question Bank Module.

1. question_categories
2. question_types
3. difficulty_levels
4. blooms_taxonomy_levels
5. question_sources

---

# Table : question_categories

## Purpose

Stores business classifications of questions.

Examples

- Academic
- Admission
- Board Examination
- Job Recruitment
- Practice
- Mock Test
- Certification

---

## Table Name

question_categories

---

## Primary Key

id

---

## Columns

| Column | Type | Nullable | Default | Description |
|---------|------|----------|----------|-------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| code | VARCHAR(30) | No | — | Category Code |
| name | VARCHAR(150) | No | — | Category Name |
| description | TEXT | Yes | NULL | Description |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(code)

UNIQUE(name)

---

# Relationships

QuestionCategory

HasMany

Question

---

# Business Rules

1. Category Name must be unique.
2. Categories can be disabled but not permanently removed if questions exist.
3. Only Active Categories are available for new questions.

---

# Laravel Model

QuestionCategory

---

# Migration

create_question_categories_table

---

# Seeder

QuestionCategorySeeder

---

# Factory

QuestionCategoryFactory

---

# Example Records

| Code | Name |
|------|------|
| ACD | Academic |
| ADM | Admission |
| JOB | Job Recruitment |
| BRD | Board Examination |
| PRC | Practice |
| CERT | Certification |

---

# Table : question_types

## Purpose

Stores supported question formats.

---

## Examples

- MCQ
- True/False
- Multiple Response
- Essay
- Short Answer
- Fill in the Blank
- Matching
- Numerical
- Programming
- Image Based

---

## Table Name

question_types

---

## Primary Key

id

---

## Columns

| Column | Type | Nullable | Default | Description |
|---------|------|----------|----------|-------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT |
| uuid | CHAR(36) | No | UUID |
| code | VARCHAR(30) | No | — |
| name | VARCHAR(150) | No | — |
| has_options | BOOLEAN | No | FALSE |
| allows_multiple_answers | BOOLEAN | No | FALSE |
| auto_gradable | BOOLEAN | No | TRUE |
| description | TEXT | Yes | NULL |
| display_order | SMALLINT UNSIGNED | No | 1 |
| status | SMALLINT UNSIGNED | No | 1 |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |
| deleted_at | TIMESTAMP |

---

# Example Records

| Code | Name | Auto Gradable |
|------|------|---------------|
| MCQ | Multiple Choice | Yes |
| TF | True / False | Yes |
| SA | Short Answer | No |
| ESSAY | Essay | No |
| MATCH | Matching | Yes |
| NUM | Numerical | Yes |
| CODE | Programming | Depends |

---

End of Section-1

# Table : questions

---

## Purpose

Stores the master record of every question in the Question Bank.

The table contains only the core metadata and question content.

Options, Answers, Media, Topics, Tags, Reviews, and Versions are stored in separate related tables.

---

## Table Name

questions

---

## Primary Key

id

---

## Description

A Question is the central entity of the Question Bank.

Each Question belongs to an Institution, Subject, Chapter, Question Category, Question Type, Difficulty Level, Bloom's Taxonomy Level, and Question Source.

The Question may later be attached to one or more Exams.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| subject_id | BIGINT UNSIGNED | No | — | Subject |
| chapter_id | BIGINT UNSIGNED | Yes | NULL | Chapter |
| question_category_id | BIGINT UNSIGNED | No | — | Category |
| question_type_id | BIGINT UNSIGNED | No | — | Question Type |
| difficulty_level_id | BIGINT UNSIGNED | No | — | Difficulty |
| blooms_taxonomy_level_id | BIGINT UNSIGNED | Yes | NULL | Bloom Level |
| question_source_id | BIGINT UNSIGNED | Yes | NULL | Source |
| language_code | VARCHAR(10) | No | en | ISO Language Code |
| title | VARCHAR(255) | Yes | NULL | Optional Title |
| question_text | LONGTEXT | No | — | Main Question Text |
| explanation | LONGTEXT | Yes | NULL | Explanation |
| default_marks | DECIMAL(5,2) | No | 1.00 | Marks |
| negative_marks | DECIMAL(5,2) | No | 0.00 | Negative Marks |
| estimated_time_seconds | INTEGER UNSIGNED | Yes | NULL | Expected Time |
| is_case_study | BOOLEAN | No | FALSE | Case Study Question |
| is_active | BOOLEAN | No | TRUE | Active Status |
| is_approved | BOOLEAN | No | FALSE | Approval Status |
| version | INTEGER UNSIGNED | No | 1 | Current Version |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_by | BIGINT UNSIGNED | No | — | Creator |
| updated_by | BIGINT UNSIGNED | Yes | NULL | Last Editor |
| approved_by | BIGINT UNSIGNED | Yes | NULL | Approver |
| approved_at | TIMESTAMP | Yes | NULL | Approval Time |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

---

# Foreign Keys

institution_id → institutions.id

subject_id → subjects.id

chapter_id → chapters.id

question_category_id → question_categories.id

question_type_id → question_types.id

difficulty_level_id → difficulty_levels.id

blooms_taxonomy_level_id → blooms_taxonomy_levels.id

question_source_id → question_sources.id

created_by → users.id

updated_by → users.id

approved_by → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(subject_id)

INDEX(question_type_id)

INDEX(difficulty_level_id)

INDEX(question_category_id)

INDEX(created_by)

INDEX(is_active)

INDEX(is_approved)

FULLTEXT(question_text)

---

# Relationships

Institution

HasMany

Question

Subject

HasMany

Question

Chapter

HasMany

Question

QuestionCategory

HasMany

Question

QuestionType

HasMany

Question

DifficultyLevel

HasMany

Question

BloomTaxonomyLevel

HasMany

Question

QuestionSource

HasMany

Question

Question

BelongsTo

Institution

Question

BelongsTo

Subject

Question

BelongsTo

Chapter

Question

BelongsTo

QuestionCategory

Question

BelongsTo

QuestionType

Question

BelongsTo

DifficultyLevel

Question

BelongsTo

BloomTaxonomyLevel

Question

BelongsTo

QuestionSource

Question

HasMany

QuestionOption

Question

HasMany

QuestionAnswer

Question

HasMany

QuestionMedia

Question

BelongsToMany

Topic

Question

BelongsToMany

Tag

Question

HasMany

QuestionVersion

Question

HasMany

QuestionReview

Question

BelongsToMany

Exam

---

# Business Rules

1. Every Question belongs to one Subject.
2. Every Question belongs to one Question Type.
3. Every Question belongs to one Difficulty Level.
4. A Question cannot be approved twice.
5. Only Approved Questions can appear in Exams.
6. Deleted Questions remain available in historical Exam records.
7. Question Type determines whether Options and Answers are required.

---

# Laravel Model

Question

---

# Migration

create_questions_table

---

# Seeder

QuestionSeeder

---

# Factory

QuestionFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

subject_id

required

exists:subjects,id

question_category_id

required

exists:question_categories,id

question_type_id

required

exists:question_types,id

difficulty_level_id

required

exists:difficulty_levels,id

question_text

required

string

default_marks

required

numeric

min:0

negative_marks

required

numeric

min:0

language_code

required

max:10

is_active

boolean

is_approved

boolean

---

# Example Record

| Field | Value |
|--------|--------------------------------|
| subject_id | 5 |
| question_type_id | 1 (MCQ) |
| difficulty_level_id | 2 (Medium) |
| question_text | What is polymorphism in OOP? |
| default_marks | 2.00 |
| negative_marks | 0.50 |
| estimated_time_seconds | 90 |
| is_approved | Yes |

---

# Notes

- Question content is stored only once.
- Options, Answers and Media are stored separately.
- Supports multilingual questions.
- Supports AI-generated and manually created questions.
- Optimized for full-text search.

---

End of Section-2

# Table : question_options

---

## Purpose

Stores selectable options for questions that require answer choices.

Each Question may have zero, one, or many Options depending on the Question Type.

Examples:

- MCQ
- Multiple Response
- True / False
- Matching
- Ordering

---

## Table Name

question_options

---

## Primary Key

id

---

## Description

Each record represents a single selectable option belonging to a Question.

The correctness of an option is **not** stored here. Correct answers are maintained in the `question_answers` table.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|------|----------|----------|-------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| option_key | VARCHAR(10) | No | — | A, B, C, D... |
| option_text | LONGTEXT | Yes | NULL | Option Text |
| media_type | VARCHAR(30) | Yes | NULL | Image, Audio, Video |
| media_path | VARCHAR(500) | Yes | NULL | Media File Path |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(question_id, option_key)

---

# Foreign Keys

question_id

↓

questions.id

ON DELETE CASCADE

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(option_key)

INDEX(display_order)

INDEX(status)

---

# Relationships

Question

HasMany

QuestionOption

QuestionOption

BelongsTo

Question

QuestionOption

HasMany

QuestionAnswer (Future Reference)

---

# Business Rules

1. Every Option belongs to one Question.

2. Option Key must be unique within the same Question.

3. Display Order determines the presentation sequence.

4. Option Text may be empty if the option is represented only by media.

5. Options are allowed only for supported Question Types.

---

# Laravel Model

QuestionOption

---

# Migration

create_question_options_table

---

# Seeder

QuestionOptionSeeder

---

# Factory

QuestionOptionFactory

---

# Validation Rules

question_id

required

exists:questions,id

option_key

required

string

max:10

option_text

nullable

string

display_order

required

integer

min:1

status

required

integer

---

# Example Record

| Field | Value |
|--------|-------------------------|
| question_id | 101 |
| option_key | A |
| option_text | Polymorphism |
| display_order | 1 |
| status | Active |

---

# Notes

- UUID is used for public APIs.
- Soft Delete is enabled.
- Correct answers are NOT stored in this table.
- Supports text-only, media-only, or mixed options.
- Unlimited number of options per question.

---

End of Section-3

# Table : question_answers

---

## Purpose

Stores the correct answer(s) for a question.

This table supports all supported Question Types including MCQ, Multiple Response, True/False, Essay, Numerical, Programming, Matching and future extensions.

A Question may have one or multiple Answer records.

---

## Table Name

question_answers

---

## Primary Key

id

---

## Description

Each record represents one valid answer for a Question.

For option-based questions, the answer references a Question Option.

For text or numeric questions, the answer value is stored directly.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|------|----------|----------|-------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| question_option_id | BIGINT UNSIGNED | Yes | NULL | Correct Option (if applicable) |
| answer_type | VARCHAR(30) | No | — | OPTION, TEXT, NUMBER, JSON, etc. |
| answer_value | LONGTEXT | Yes | NULL | Correct Answer Value |
| answer_order | SMALLINT UNSIGNED | No | 1 | Sequence for Multiple Answers |
| score_weight | DECIMAL(5,2) | No | 1.00 | Marks Weight |
| case_sensitive | BOOLEAN | No | FALSE | Case-sensitive comparison |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

---

# Foreign Keys

question_id → questions.id

ON DELETE CASCADE

ON UPDATE CASCADE

question_option_id → question_options.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(question_option_id)

INDEX(answer_type)

INDEX(status)

---

# Relationships

Question

HasMany

QuestionAnswer

QuestionAnswer

BelongsTo

Question

QuestionOption

HasMany

QuestionAnswer

QuestionAnswer

BelongsTo

QuestionOption

---

# Business Rules

1. Every Answer belongs to one Question.

2. A Question may have multiple Answer records.

3. MCQ answers reference `question_option_id`.

4. Essay, Numerical and Programming answers use `answer_value`.

5. `answer_type` determines how the answer is evaluated.

6. Deleted Answers remain available for historical audit.

---

# Laravel Model

QuestionAnswer

---

# Migration

create_question_answers_table

---

# Seeder

QuestionAnswerSeeder

---

# Factory

QuestionAnswerFactory

---

# Validation Rules

question_id

required

exists:questions,id

question_option_id

nullable

exists:question_options,id

answer_type

required

string

max:30

answer_value

nullable

string

score_weight

required

numeric

min:0

case_sensitive

boolean

status

required

integer

---

# Example Records

## MCQ

| Field | Value |
|--------|-------|
| question_id | 101 |
| question_option_id | 402 |
| answer_type | OPTION |
| score_weight | 1.00 |

---

## Multiple Response

| question_id | option_id |
|-------------|-----------|
| 201 | 801 |
| 201 | 803 |
| 201 | 804 |

---

## Essay

| Field | Value |
|--------|----------------|
| question_id | 305 |
| answer_type | TEXT |
| answer_value | Polymorphism allows one interface to represent multiple forms. |

---

## Numerical

| Field | Value |
|--------|--------|
| question_id | 510 |
| answer_type | NUMBER |
| answer_value | 3.1416 |

---

# Notes

- Supports single and multiple correct answers.
- Supports option-based and text-based evaluation.
- Compatible with AI-assisted evaluation workflows.
- Soft Delete is enabled.
- UUID is used for API integrations.

---

End of Section-4

# DATABASE_DESIGN.md

Version: 2.0

Part-4

Section-5

# Table : question_topics

---

## Purpose

Stores the relationship between Questions and Topics.

A Question may cover multiple Topics, and a Topic may contain multiple Questions.

This table provides the Many-to-Many relationship required for Question Bank classification.

---

## Table Name

question_topics

---

## Primary Key

id

---

## Description

`question_topics` is a pivot/mapping table between:

    questions

and

    topics

It allows a Question to be associated with one or more Topics.

Example:

Question:

    "Explain the difference between Stack and Queue."

Topics:

    Stack
    Queue

The same Question can therefore be associated with multiple Topics.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| question_id | BIGINT UNSIGNED | No | — | Question |
| topic_id | BIGINT UNSIGNED | No | — | Topic |
| relevance_weight | DECIMAL(5,2) | No | 1.00 | Topic Relevance Weight |
| is_primary | BOOLEAN | No | FALSE | Primary Topic |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| remarks | TEXT | Yes | NULL | Internal Notes |
| created_by | BIGINT UNSIGNED | No | — | User who created mapping |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(question_id, topic_id)

---

# Foreign Keys

question_id → questions.id

ON DELETE CASCADE

ON UPDATE CASCADE

topic_id → topics.id

ON DELETE RESTRICT

ON UPDATE CASCADE

created_by → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(topic_id)

INDEX(is_primary)

INDEX(status)

INDEX(created_by)

INDEX(question_id, display_order)

INDEX(topic_id, display_order)

---

# Relationships

Question

BelongsToMany

Topic

through

question_topics

Topic

BelongsToMany

Question

through

question_topics

Question

HasMany

QuestionTopic

Topic

HasMany

QuestionTopic

QuestionTopic

BelongsTo

Question

QuestionTopic

BelongsTo

Topic

QuestionTopic

BelongsTo

User

---

# Business Rules

1. Every mapping must reference an existing Question.

2. Every mapping must reference an existing Topic.

3. The same Question cannot be mapped to the same Topic more than once.

4. Every Question should have at least one Topic before it becomes Approved.

5. A Question may have multiple Topics.

6. A Topic may contain unlimited Questions.

7. Only one Topic may be marked as the Primary Topic for a Question.

8. `relevance_weight` represents the importance of the Topic to the Question.

9. Deleted mappings must not be used for new Question classification.

10. Historical examination records must not be affected by deletion of a mapping.

---

# Primary Topic Rule

A Question may have:

    Primary Topic
    Secondary Topic
    Secondary Topic
    Secondary Topic

Example:

Question:

    "Compare binary search and linear search."

Mappings:

| Topic | Relevance | Primary |
|-------|-----------|---------|
| Binary Search | 0.70 | Yes |
| Searching Algorithms | 0.20 | No |
| Algorithm Analysis | 0.10 | No |

Only one mapping may have:

    is_primary = TRUE

---

# Relevance Weight

`relevance_weight` represents how strongly the Question relates to a Topic.

Recommended range:

    0.00 – 1.00

Example:

    1.00 = Completely related
    0.75 = Highly related
    0.50 = Moderately related
    0.25 = Slightly related

The total relevance weight does not have to equal 1.00.

This field is primarily used for analytics, blueprinting and AI-assisted classification.

---

# Display Order

`display_order` controls the order in which Topic associations are displayed.

Example:

| display_order | Topic |
|---------------|-------|
| 1 | Binary Search |
| 2 | Searching |
| 3 | Complexity Analysis |

---

# Laravel Model

QuestionTopic

---

# Migration

create_question_topics_table

---

# Seeder

QuestionTopicSeeder

---

# Factory

QuestionTopicFactory

---

# Laravel Relationships

## Question Model

    public function topics()
    {
        return $this->belongsToMany(
            Topic::class,
            'question_topics'
        );
    }

---

## Topic Model

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'question_topics'
        );
    }

---

# Validation Rules

question_id

required

exists:questions,id

topic_id

required

exists:topics,id

relevance_weight

required

numeric

min:0

max:1

is_primary

boolean

display_order

required

integer

min:1

status

required

integer

---

# Example Record

| Field | Value |
|--------|-------|
| question_id | 101 |
| topic_id | 25 |
| relevance_weight | 1.00 |
| is_primary | true |
| display_order | 1 |
| status | Active |
| created_by | 7 |

---

# Example Many-to-Many Mapping

Question:

    Q-101

Topics:

    Topic-25
    Topic-26
    Topic-30

Database:

| id | question_id | topic_id | relevance_weight | is_primary |
|----|-------------|----------|------------------|------------|
| 1 | 101 | 25 | 0.70 | Yes |
| 2 | 101 | 26 | 0.20 | No |
| 3 | 101 | 30 | 0.10 | No |

---

# Query Examples

## Get all Topics of a Question

    Question::find($id)
        ->topics()
        ->get();

---

## Get all Questions of a Topic

    Topic::find($id)
        ->questions()
        ->get();

---

## Get Primary Topic

    QuestionTopic::where('question_id', $id)
        ->where('is_primary', true)
        ->first();

---

# Question Approval Dependency

Before a Question is approved:

    Question
        ↓
    Topic Mapping
        ↓
    Primary Topic
        ↓
    Approval

Recommended rule:

    Approved Question
        MUST have
        at least one active Topic mapping.

---

# Question Bank Analytics

This table enables:

- Questions per Topic
- Questions per Chapter
- Questions per Subject
- Topic-wise Difficulty Analysis
- Topic-wise Bloom Analysis
- Topic-wise Question Distribution
- Blueprint Coverage
- Question Gap Analysis
- AI-based Topic Classification
- Automated Question Selection

---

# Blueprint Integration

Future Blueprint module can use this mapping to calculate:

    Required Questions
            vs
    Available Questions

Example:

| Topic | Required | Available |
|-------|----------|-----------|
| Arrays | 5 | 120 |
| Linked List | 4 | 80 |
| Trees | 3 | 25 |
| Graph | 3 | 8 |

This allows the system to identify insufficient Question Bank coverage.

---

# Important Integrity Rule

A Question Topic mapping must belong to the same academic hierarchy.

Conceptually:

    Question
        ↓
    Subject
        ↓
    Chapter
        ↓
    Topic

Therefore, the application/service layer must verify that:

    topic.subject_id
        ==
    question.subject_id

A Question from Subject A must not be mapped to a Topic belonging to Subject B.

This validation MUST be performed before creating or updating a mapping.

---

# Soft Delete Consideration

The pivot table uses `deleted_at` because classification history may be useful for audit purposes.

However:

Deleted mappings must be excluded from normal Question Bank queries.

Historical Question/Exam records must remain intact.

---

# Performance Considerations

The following indexes are important:

    INDEX(question_id)

    INDEX(topic_id)

    INDEX(question_id, display_order)

    INDEX(topic_id, display_order)

These indexes support:

- Question → Topics
- Topic → Questions
- Topic-wise filtering
- Question Bank search
- Blueprint generation
- Analytics queries

---

# Security Considerations

Only authorized users should be able to create or modify Question-Topic mappings.

Recommended permissions:

    question.topic.view
    question.topic.assign
    question.topic.update
    question.topic.delete

Question Setter:

    Assign Topics

Moderator:

    Review Topics

Admin:

    Full Control

---

# Audit Considerations

The following information should be retained:

    created_by
    created_at
    updated_at
    deleted_at

For advanced audit requirements, a dedicated activity log should be used instead of adding excessive audit columns to this table.

---

# Future Extensions

Possible future tables:

    question_topic_learning_outcomes

    topic_question_statistics

    question_classification_logs

These can support:

- OBE
- Bloom's Taxonomy
- AI Classification
- Question Analytics
- Automated Curriculum Mapping

---

# Final Relationship

Institution

    ↓

Program

    ↓

Subject

    ↓

Chapter

    ↓

Topic

    ↓
    ↕
Question

Therefore:

    Question ←→ Topic

is a

    MANY-TO-MANY

relationship.

---

End of Section-5

# DATABASE_DESIGN.md

Version: 2.0

Part-4

Section-6

# Table : tags

---

## Purpose

Stores reusable classification tags that can be assigned to Questions.

Tags provide a flexible, user-defined classification mechanism beyond the formal academic hierarchy of Subject, Chapter and Topic.

Examples:

- recursion
- algorithm
- database
- normalization
- inheritance
- important
- previous-year
- frequently-asked
- numerical
- conceptual

---

## Table Name

tags

---

## Primary Key

id

---

## Description

A Tag is a reusable metadata label.

Unlike Topics, Tags do not represent a formal position in the academic syllabus.

For example:

Subject:

    Data Structures

Chapter:

    Trees

Topic:

    Binary Search Tree

Tags:

    recursion
    algorithm
    important
    viva

A Question may have multiple Tags.

A Tag may be assigned to many Questions.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| name | VARCHAR(100) | No | — | Tag Name |
| slug | VARCHAR(120) | No | — | URL/Search Slug |
| description | VARCHAR(500) | Yes | NULL | Tag Description |
| tag_type | VARCHAR(30) | No | MANUAL | Tag Classification |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| created_by | BIGINT UNSIGNED | No | — | Creator |
| updated_by | BIGINT UNSIGNED | Yes | NULL | Last Editor |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Tag Types

Recommended values:

    MANUAL
    SYSTEM
    AI_GENERATED
    IMPORTED

These values represent how the Tag was created.

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(institution_id, slug)

UNIQUE(institution_id, name)

---

# Foreign Keys

institution_id → institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

created_by → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

updated_by → users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(name)

INDEX(slug)

INDEX(tag_type)

INDEX(status)

---

# Relationships

Institution

HasMany

Tag

Tag

BelongsTo

Institution

Tag

BelongsToMany

Question

through

question_tags

---

# Business Rules

1. Tag names must be unique within an Institution.

2. Tag names may be reused across different Institutions.

3. Only Active Tags can be assigned to new Questions.

4. A Tag may be assigned to multiple Questions.

5. A Question may have multiple Tags.

6. Tags do not replace formal Subject, Chapter or Topic classification.

7. System-generated and AI-generated Tags must remain identifiable through `tag_type`.

---

# Laravel Model

Tag

---

# Migration

create_tags_table

---

# Seeder

TagSeeder

---

# Factory

TagFactory

---

# Example Records

| Name | Slug | Type |
|------|------|------|
| Recursion | recursion | MANUAL |
| Algorithm | algorithm | MANUAL |
| Frequently Asked | frequently-asked | SYSTEM |
| AI Generated | ai-generated | SYSTEM |
| Important | important | MANUAL |

---

# Table : question_tags

---

## Purpose

Stores the Many-to-Many relationship between Questions and Tags.

---

## Table Name

question_tags

---

## Primary Key

id

---

## Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| question_id | BIGINT UNSIGNED | No | — | Question |
| tag_id | BIGINT UNSIGNED | No | — | Tag |
| relevance_weight | DECIMAL(5,2) | No | 1.00 | Tag Relevance |
| assigned_by | BIGINT UNSIGNED | No | — | User who assigned Tag |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(question_id, tag_id)

---

# Foreign Keys

question_id → questions.id

ON DELETE CASCADE

ON UPDATE CASCADE

tag_id → tags.id

ON DELETE RESTRICT

ON UPDATE CASCADE

assigned_by → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(tag_id)

INDEX(assigned_by)

INDEX(question_id, tag_id)

INDEX(tag_id, question_id)

---

# Relationships

Question

BelongsToMany

Tag

through

question_tags

Tag

BelongsToMany

Question

through

question_tags

Question

HasMany

QuestionTag

Tag

HasMany

QuestionTag

QuestionTag

BelongsTo

Question

QuestionTag

BelongsTo

Tag

QuestionTag

BelongsTo

User

---

# Business Rules

1. A Question cannot have the same Tag more than once.

2. Only Active Tags can be assigned to active Questions.

3. Deleted mappings must be excluded from normal searches.

4. A Question may have unlimited Tags.

5. A Tag may be used by unlimited Questions.

6. Tag assignment must respect Institution ownership.

7. A Question from Institution A cannot be assigned a Tag belonging to Institution B.

---

# Laravel Relationships

## Question Model

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'question_tags'
        );
    }

---

## Tag Model

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'question_tags'
        );
    }

---

# Validation Rules

## Tag

institution_id

required

exists:institutions,id

name

required

string

max:100

slug

required

string

max:120

tag_type

required

in:MANUAL,SYSTEM,AI_GENERATED,IMPORTED

status

required

integer

---

## Question Tag

question_id

required

exists:questions,id

tag_id

required

exists:tags,id

relevance_weight

required

numeric

min:0

max:1

assigned_by

required

exists:users,id

---

# Example

Question:

    Q-101

Tags:

    recursion
    algorithm
    important

Database:

| question_id | tag_id | relevance_weight |
|--------------|--------|------------------|
| 101 | 5 | 1.00 |
| 101 | 8 | 0.90 |
| 101 | 12 | 0.75 |

---

# Topic vs Tag

Tags must not be confused with Topics.

## Topic

Represents formal academic structure.

Example:

    Subject
        ↓
    Chapter
        ↓
    Topic

Example:

    Data Structures
        ↓
    Trees
        ↓
    Binary Search Tree

---

## Tag

Represents flexible metadata.

Example:

    recursion
    important
    previous-year
    conceptual
    viva
    frequently-asked

---

# Search Integration

Tags can be used for:

- Question Search
- Advanced Filtering
- Question Bank Navigation
- Exam Question Selection
- Practice Set Generation
- AI Question Classification
- Recommendation Systems
- Analytics

Example Query:

    Find all approved MCQ questions

    Subject = Data Structures

    Difficulty = Medium

    Tag = recursion

---

# AI Integration

AI-generated Tags may be assigned automatically.

Example:

Question:

    "Explain recursive tree traversal."

AI-generated Tags:

    recursion
    tree
    traversal
    algorithm

These Tags must be stored with:

    tag_type = AI_GENERATED

The system should retain the ability for a Teacher or Moderator to review or remove AI-generated Tags.

---

# Tag Governance

To prevent Tag duplication:

Bad:

    recursion
    Recursion
    Recursive
    recursive-algorithm

Recommended:

    recursion

The application should normalize Tag input before creation.

Recommended normalization:

- Trim whitespace
- Convert repeated spaces to one space
- Normalize slug
- Perform case-insensitive duplicate checking
- Prevent unnecessary special characters

---

# Security

Recommended permissions:

    tag.view
    tag.create
    tag.update
    tag.delete
    question.tag.assign
    question.tag.remove

Recommended Role Access:

Question Setter:

    View
    Assign

Moderator:

    View
    Assign
    Remove

Admin:

    Full Control

---

# Audit

The following information is retained:

    created_by
    updated_by
    assigned_by
    created_at
    updated_at
    deleted_at

For complete audit history, use the centralized Activity Log module.

---

# Performance Considerations

For large Question Banks, indexes on both sides of the pivot table are mandatory.

Recommended:

    INDEX(question_id, tag_id)

    INDEX(tag_id, question_id)

This supports:

    Question → Tags

and

    Tag → Questions

efficiently.

---

# Scalability

The design supports:

    1 Institution
        ↓
    Thousands of Tags
        ↓
    Millions of Questions
        ↓
    Many Millions of Tag Mappings

The pivot table should therefore be treated as a high-volume table.

---

# Future Extensions

Possible future tables:

    tag_synonyms

    tag_aliases

    tag_categories

    tag_usage_statistics

    ai_tag_suggestions

These can support:

- Tag Governance
- Search Synonyms
- AI Classification
- Recommendation Engine
- Advanced Analytics

---

# Final Relationship

Question

    ↕
question_tags

    ↕

Tag

Therefore:

    Question ←→ Tag

is a

    MANY-TO-MANY

relationship.

---

End of Section-6

# DATABASE_DESIGN.md

Version: 2.0

Part-4

Section-7

# Table : question_media

---

## Purpose

Stores media and file attachments associated with Questions.

The Question Bank must support multimedia questions including:

- Images
- Diagrams
- Audio
- Video
- PDF
- Documents
- Mathematical figures
- Scanned materials
- Programming files
- Reference attachments

Media must be stored separately from the `questions` table to maintain database normalization and support scalable file storage.

---

# Table Name

question_media

---

# Primary Key

id

---

# Description

Each record represents one media attachment associated with a Question.

The actual binary file must NOT be stored directly inside the database.

The database stores metadata and the storage reference.

Recommended storage architecture:

    Application
        ↓
    question_media
        ↓
    Object/File Storage
        ↓
    Image / Audio / Video / Document

Examples of storage:

- Local filesystem
- Amazon S3
- Cloudflare R2
- Google Cloud Storage
- Azure Blob Storage
- MinIO

The application should access files through a storage abstraction layer.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Parent Institution |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| media_type | VARCHAR(30) | No | — | IMAGE, AUDIO, VIDEO, DOCUMENT, etc. |
| storage_disk | VARCHAR(50) | No | local | Storage Disk |
| storage_path | VARCHAR(1000) | No | — | File Storage Path |
| original_filename | VARCHAR(255) | Yes | NULL | Original File Name |
| stored_filename | VARCHAR(255) | Yes | NULL | Internal File Name |
| mime_type | VARCHAR(100) | Yes | NULL | MIME Type |
| file_extension | VARCHAR(20) | Yes | NULL | File Extension |
| file_size_bytes | BIGINT UNSIGNED | Yes | NULL | File Size |
| checksum | VARCHAR(128) | Yes | NULL | File Integrity Hash |
| title | VARCHAR(255) | Yes | NULL | Media Title |
| alt_text | VARCHAR(500) | Yes | NULL | Accessibility Text |
| caption | TEXT | Yes | NULL | Caption |
| duration_seconds | DECIMAL(12,3) | Yes | NULL | Audio/Video Duration |
| width | INTEGER UNSIGNED | Yes | NULL | Image/Video Width |
| height | INTEGER UNSIGNED | Yes | NULL | Image/Video Height |
| display_order | SMALLINT UNSIGNED | No | 1 | Display Order |
| is_required | BOOLEAN | No | FALSE | Required for Question |
| status | SMALLINT UNSIGNED | No | 1 | Status |
| uploaded_by | BIGINT UNSIGNED | No | — | Uploader |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Supported Media Types

Recommended values:

    IMAGE

    AUDIO

    VIDEO

    DOCUMENT

    PDF

    SPREADSHEET

    PRESENTATION

    ARCHIVE

    CODE

    OTHER

---

# Important Design Rule

The database should NOT use MySQL `BLOB` for normal Question Bank media.

Avoid:

    file_data BLOB

Instead use:

    storage_disk

    storage_path

    checksum

    mime_type

    file_size_bytes

This keeps the database lightweight and allows large files to be handled by dedicated storage systems.

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

---

# Foreign Keys

institution_id → institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

question_id → questions.id

ON DELETE CASCADE

ON UPDATE CASCADE

uploaded_by → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(institution_id)

INDEX(question_id)

INDEX(media_type)

INDEX(status)

INDEX(uploaded_by)

INDEX(checksum)

INDEX(question_id, display_order)

---

# Relationships

Question

HasMany

QuestionMedia

QuestionMedia

BelongsTo

Question

QuestionMedia

BelongsTo

Institution

QuestionMedia

BelongsTo

User

---

# Business Rules

1. Every media record belongs to one Question.

2. Every media record belongs to one Institution.

3. Only authorized users can upload Question media.

4. The physical file must exist in the configured storage system before the media record becomes active.

5. Unsupported file types must be rejected.

6. File size limits must be enforced by application validation.

7. Media must be scanned for malicious content before becoming available to users.

8. Deleted media must not appear in active Question views.

9. Historical examination records must remain valid if media is later archived.

10. The same physical file may be reused when the storage architecture supports content-addressable storage.

---

# File Validation

Recommended validation layers:

    Extension Validation
        ↓
    MIME Type Validation
        ↓
    File Signature Validation
        ↓
    Malware / Antivirus Scan
        ↓
    Storage
        ↓
    Database Record

Do not trust the uploaded filename or extension alone.

---

# Recommended File Limits

These are application-level defaults and may be changed by Institution policy.

| Media Type | Recommended Maximum |
|------------|---------------------|
| Image | 10 MB |
| Audio | 50 MB |
| Video | 500 MB |
| PDF | 50 MB |
| Document | 25 MB |
| Spreadsheet | 25 MB |
| Presentation | 50 MB |
| Code | 10 MB |

The final limit must be configurable.

---

# Storage Architecture

Recommended:

    question_media.storage_disk

Examples:

    local

    s3

    r2

    gcs

    azure

    minio

The application should not hard-code a particular storage provider.

---

# Example Storage Path

    institutions/1/questions/101/media/8f2c-image.png

Recommended logical structure:

    institutions/
        {institution_id}/
            questions/
                {question_id}/
                    media/
                        {media_uuid}/
                            file

This provides better isolation and organization.

---

# Filename Security

Never use the original filename as the physical storage filename.

Bad:

    question-image.jpg

Recommended:

    UUID-based filename

Example:

    8f5d8d4c-2d4c-4b0a-a9d7-xxxxxxxxxxxx.jpg

The original filename should only be retained as metadata.

---

# Checksum

`checksum` is used to verify file integrity and detect duplicate files.

Recommended algorithms:

    SHA-256

Example:

    sha256(file)

The checksum can also be used for future deduplication.

---

# Accessibility

For images, `alt_text` should be provided whenever possible.

Example:

    alt_text =
    "Diagram showing binary search tree traversal"

This is important for:

- Screen Readers
- Accessibility
- WCAG compliance
- Educational usability

---

# Image Metadata

For image-based Questions:

    width

    height

    mime_type

    file_size_bytes

may be populated automatically.

Example:

| Field | Value |
|-------|-------|
| media_type | IMAGE |
| mime_type | image/png |
| width | 1920 |
| height | 1080 |
| file_size_bytes | 245760 |

---

# Audio / Video Metadata

For audio/video:

    duration_seconds

should be populated when available.

Example:

| Field | Value |
|-------|-------|
| media_type | AUDIO |
| mime_type | audio/mpeg |
| duration_seconds | 45.250 |

---

# Display Order

Multiple media files can be attached to one Question.

Example:

| Order | Media |
|-------|-------|
| 1 | Question Diagram |
| 2 | Reference Image |
| 3 | Audio |
| 4 | Explanation Video |

---

# Required Media

`is_required` indicates whether the media is necessary for understanding or answering the Question.

Example:

    is_required = TRUE

for a diagram-based question where the Question cannot be answered without viewing the diagram.

---

# Laravel Model

QuestionMedia

---

# Migration

create_question_media_table

---

# Seeder

QuestionMediaSeeder

---

# Factory

QuestionMediaFactory

---

# Validation Rules

institution_id

required

exists:institutions,id

question_id

required

exists:questions,id

media_type

required

string

max:30

storage_disk

required

string

max:50

storage_path

required

string

max:1000

mime_type

nullable

string

max:100

file_size_bytes

nullable

integer

min:1

display_order

required

integer

min:1

is_required

boolean

status

required

integer

---

# Laravel Relationship

## Question Model

    public function media()
    {
        return $this->hasMany(
            QuestionMedia::class
        );
    }

---

# Example Record

| Field | Value |
|--------|------------------------------|
| question_id | 101 |
| media_type | IMAGE |
| storage_disk | s3 |
| storage_path | institutions/1/questions/101/media/a81f/image.png |
| original_filename | binary-tree.png |
| mime_type | image/png |
| file_size_bytes | 245760 |
| checksum | SHA256_HASH |
| alt_text | Binary search tree diagram |
| display_order | 1 |
| is_required | true |
| status | Active |

---

# Question Rendering

When displaying a Question:

    Question
        ↓
    Question Text
        ↓
    Required Media
        ↓
    Options
        ↓
    Answer Interface

Media should be rendered according to its `media_type`.

---

# Security

Recommended permissions:

    question.media.view

    question.media.upload

    question.media.update

    question.media.delete

---

# Upload Security

The application must:

- Validate MIME type
- Validate file signature
- Limit file size
- Generate secure storage names
- Prevent executable uploads
- Scan files for malware
- Prevent directory traversal
- Store files outside executable web roots where possible
- Generate temporary/signed URLs for private files
- Enforce Institution-level authorization

---

# Privacy

Some Question media may contain:

- copyrighted textbook material
- examination material
- confidential documents
- unpublished questions
- internal diagrams

Therefore media should NOT automatically be publicly accessible.

Recommended:

    Private Storage

with controlled access through the application.

---

# CDN

For high-volume public or semi-public media, a CDN may be used.

Architecture:

    User

      ↓

    CDN

      ↓

    Object Storage

      ↓

    Application Metadata

The database remains responsible only for metadata and storage references.

---

# Question Option Media

Question Options may also require media.

Example:

    Question:
        Which diagram represents a BST?

    Option A:
        Image A

    Option B:
        Image B

    Option C:
        Image C

    Option D:
        Image D

The current `question_media` table is Question-level media.

For Option-level media, use a separate table:

    question_option_media

This prevents `question_media` from becoming polymorphic and keeps foreign-key integrity explicit.

---

# Future Table

## question_option_media

Recommended columns:

    id
    uuid
    question_option_id
    storage_disk
    storage_path
    original_filename
    mime_type
    file_size_bytes
    checksum
    alt_text
    caption
    display_order
    status
    uploaded_by
    created_at
    updated_at
    deleted_at

This table should follow the same storage and security rules as `question_media`.

---

# Media Processing Pipeline

For image:

    Upload
      ↓
    Validate
      ↓
    Scan
      ↓
    Store Original
      ↓
    Generate Thumbnail
      ↓
    Save Metadata
      ↓
    Activate

For Video:

    Upload
      ↓
    Validate
      ↓
    Scan
      ↓
    Store
      ↓
    Extract Metadata
      ↓
    Optional Transcoding
      ↓
    Save Metadata
      ↓
    Activate

---

# Audit

At minimum retain:

    uploaded_by
    created_at
    updated_at
    deleted_at

For enterprise auditing, the centralized Activity Log should record:

    upload
    update
    delete
    restore
    download
    access

---

# Performance Considerations

Do not load media metadata unnecessarily when retrieving a large Question Bank.

Use eager loading only when required:

    Question::with('media')

For Question lists, use selective columns.

Large files must never be returned directly from database queries.

---

# Scalability

The design supports:

    Millions of Questions

and

    Millions of Media Records

provided that physical files are stored in object/file storage rather than MySQL.

For large deployments:

    MySQL
        ↓
    Metadata

    Object Storage
        ↓
    Binary Files

    CDN
        ↓
    End Users

---

# Future Extensions

Possible future tables:

    question_media_versions

    question_media_thumbnails

    question_media_transcoding_jobs

    question_media_access_logs

    question_option_media

These can support:

- Media Versioning
- Thumbnail Management
- Video Transcoding
- Access Auditing
- Option-level Media

---

# Final Relationship

Question

    ↓

question_media

    ↓

Storage System

Therefore:

    Question
        1
        |
        N
    QuestionMedia

---

End of Section-7

# DATABASE_DESIGN.md

Version: 2.0

Part-4

Section-8

# Table : question_versions

---

## Purpose

Stores immutable historical versions of Questions.

Every significant Question modification creates a new version record.

The table provides:

- Version History
- Revision Tracking
- Auditability
- Content Recovery
- Moderation History
- Approval History Support
- Change Comparison
- Rollback Support
- Regulatory and Academic Traceability

---

# Table Name

question_versions

---

# Primary Key

id

---

# Description

A Question Version represents a snapshot of a Question at a specific point in time.

Example:

    Question 101

        Version 1
        ↓
        Version 2
        ↓
        Version 3
        ↓
        Version 4

Each version is immutable after creation.

The current Question record represents the active working state, while `question_versions` preserves historical snapshots.

---

# Version Lifecycle

Draft

↓

Version 1

↓

Revision

↓

Version 2

↓

Moderator Review

↓

Version 3

↓

Approved

↓

Published

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| version_number | INTEGER UNSIGNED | No | 1 | Version Number |
| version_label | VARCHAR(50) | Yes | NULL | Human-readable Version Label |
| question_text | LONGTEXT | No | — | Question Text Snapshot |
| explanation | LONGTEXT | Yes | NULL | Explanation Snapshot |
| question_type_id | BIGINT UNSIGNED | No | — | Question Type |
| difficulty_level_id | BIGINT UNSIGNED | No | — | Difficulty Level |
| blooms_taxonomy_level_id | BIGINT UNSIGNED | Yes | NULL | Bloom Level |
| question_category_id | BIGINT UNSIGNED | No | — | Question Category |
| language_code | VARCHAR(10) | No | en | Language |
| default_marks | DECIMAL(5,2) | No | 1.00 | Marks Snapshot |
| negative_marks | DECIMAL(5,2) | No | 0.00 | Negative Marks Snapshot |
| estimated_time_seconds | INTEGER UNSIGNED | Yes | NULL | Estimated Time |
| status | VARCHAR(30) | No | DRAFT | Version Status |
| change_type | VARCHAR(30) | No | UPDATE | Change Type |
| change_summary | VARCHAR(1000) | Yes | NULL | Description of Change |
| content_hash | CHAR(64) | Yes | NULL | SHA-256 Content Hash |
| created_by | BIGINT UNSIGNED | No | — | Version Creator |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Version Creation Time |

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

UNIQUE(question_id, version_number)

---

# Foreign Keys

question_id → questions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

question_type_id → question_types.id

ON DELETE RESTRICT

ON UPDATE CASCADE

difficulty_level_id → difficulty_levels.id

ON DELETE RESTRICT

ON UPDATE CASCADE

blooms_taxonomy_level_id → blooms_taxonomy_levels.id

ON DELETE SET NULL

ON UPDATE CASCADE

question_category_id → question_categories.id

ON DELETE RESTRICT

ON UPDATE CASCADE

created_by → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(version_number)

INDEX(status)

INDEX(change_type)

INDEX(created_by)

INDEX(created_at)

INDEX(question_id, version_number)

INDEX(question_id, status)

INDEX(content_hash)

---

# Relationships

Question

HasMany

QuestionVersion

QuestionVersion

BelongsTo

Question

QuestionVersion

BelongsTo

QuestionType

QuestionVersion

BelongsTo

DifficultyLevel

QuestionVersion

BelongsTo

BloomTaxonomyLevel

QuestionVersion

BelongsTo

QuestionCategory

QuestionVersion

BelongsTo

User

---

# Business Rules

1. Every version belongs to exactly one Question.

2. Version numbers must be sequential within a Question.

3. A version must never be physically updated after creation.

4. A version must never be deleted during normal application operation.

5. New Question modifications create a new version.

6. Only the current active version can be published.

7. Historical versions must remain readable.

8. A version must preserve the Question metadata required to reconstruct the Question state.

9. Version creation must happen inside a database transaction together with the corresponding Question update.

10. Version numbers must be generated safely under concurrent requests.

---

# Version Numbering

For a Question:

    Version 1
    Version 2
    Version 3
    Version 4

The next version number is:

    MAX(version_number) + 1

However, application code must not rely on a simple read-then-increment operation under concurrency.

Recommended implementation:

    Database Transaction
        +
    Row Lock
        +
    MAX(version_number) + 1

or an equivalent concurrency-safe mechanism.

---

# Version Status

Recommended values:

    DRAFT

    UNDER_REVIEW

    APPROVED

    PUBLISHED

    REJECTED

    ARCHIVED

These values describe the lifecycle state of the version.

---

# Change Types

Recommended values:

    CREATE

    UPDATE

    CONTENT_UPDATE

    METADATA_UPDATE

    OPTION_UPDATE

    ANSWER_UPDATE

    MEDIA_UPDATE

    TOPIC_UPDATE

    TAG_UPDATE

    RESTORE

    IMPORT

    AI_GENERATED

    AI_REVISED

---

# Important Rule

Not every child-table modification must duplicate the entire Question record.

For example:

    Option Changed

may create a new Question Version whose snapshot references the current Question state.

The application/service layer must define which changes create a new version.

---

# Snapshot Principle

A Question Version should represent the complete logical state of the Question at that point in time.

Therefore the version snapshot should include:

    Question Text

    Explanation

    Question Type

    Category

    Difficulty

    Bloom Level

    Language

    Marks

    Negative Marks

    Estimated Time

---

# Child Entity Versioning

Question content may also depend on:

    question_options

    question_answers

    question_media

    question_topics

    question_tags

Therefore, a complete historical reconstruction may require version information for these child entities.

Recommended future architecture:

    question_version_options

    question_version_answers

    question_version_media

    question_version_topics

    question_version_tags

These tables are optional for the initial implementation.

---

# Recommended Version-2 Architecture

For strict immutable examination history:

    questions

        ↓

    question_versions

        ↓

    question_version_options

        ↓

    question_version_answers

        ↓

    question_version_media

        ↓

    question_version_topics

This creates a complete immutable Question snapshot.

---

# Content Hash

`content_hash` stores a SHA-256 hash of the normalized version content.

Example:

    SHA256(
        question_text
        +
        explanation
        +
        question_type
        +
        marks
        +
        options
        +
        answers
    )

Purpose:

- Detect accidental changes
- Detect duplicate versions
- Verify integrity
- Support audit
- Detect unauthorized modification

---

# Example Version History

Question:

    Q-101

| Version | Status | Change | Created By |
|---------|--------|--------|------------|
| 1 | DRAFT | CREATE | Teacher |
| 2 | UNDER_REVIEW | CONTENT_UPDATE | Teacher |
| 3 | REJECTED | REVIEW | Moderator |
| 4 | UNDER_REVIEW | CONTENT_UPDATE | Teacher |
| 5 | APPROVED | APPROVAL | Moderator |
| 6 | PUBLISHED | PUBLISH | Admin |

---

# Laravel Model

QuestionVersion

---

# Migration

create_question_versions_table

---

# Seeder

QuestionVersionSeeder

---

# Factory

QuestionVersionFactory

---

# Laravel Relationship

## Question Model

    public function versions()
    {
        return $this->hasMany(
            QuestionVersion::class
        );
    }

---

## QuestionVersion Model

    public function question()
    {
        return $this->belongsTo(
            Question::class
        );
    }

---

# Validation Rules

question_id

required

exists:questions,id

version_number

required

integer

min:1

question_text

required

string

question_type_id

required

exists:question_types,id

difficulty_level_id

required

exists:difficulty_levels,id

question_category_id

required

exists:question_categories,id

language_code

required

string

max:10

default_marks

required

numeric

min:0

negative_marks

required

numeric

min:0

status

required

string

max:30

change_type

required

string

max:30

created_by

required

exists:users,id

---

# Example Record

| Field | Value |
|-------|-------|
| question_id | 101 |
| version_number | 3 |
| version_label | v3 |
| question_text | Explain binary search. |
| question_type_id | 1 |
| difficulty_level_id | 2 |
| default_marks | 5.00 |
| negative_marks | 0.00 |
| status | UNDER_REVIEW |
| change_type | CONTENT_UPDATE |
| created_by | 25 |

---

# Approval Integration

Version approval should operate on a specific version.

Example:

    Question 101

        Current Version = 4

        Version 4
            ↓
        UNDER_REVIEW
            ↓
        Moderator
            ↓
        APPROVED

If the Question is edited after approval:

    Version 4 = APPROVED

    Version 5 = DRAFT

The approved Version 4 must remain unchanged.

---

# Publishing Rule

Only an approved version may be published.

Invalid:

    DRAFT
      ↓
    PUBLISHED

Valid:

    DRAFT
      ↓
    UNDER_REVIEW
      ↓
    APPROVED
      ↓
    PUBLISHED

---

# Rollback

Rollback must NOT delete newer versions.

Example:

    v1
    v2
    v3
    v4

If v4 contains an error:

    Rollback to v2

The system should create:

    v5

where v5 is a new version based on v2.

Never:

    DELETE v3
    DELETE v4

This preserves the complete audit trail.

---

# Audit Requirements

The system should be able to answer:

    Who created this Question?

    Who changed it?

    What changed?

    When did the change occur?

    Which version was approved?

    Who approved it?

    Which version was published?

    Which version was used in an Examination?

---

# Security

Recommended permissions:

    question.version.view

    question.version.create

    question.version.compare

    question.version.restore

    question.version.publish

Only authorized users may create new versions.

Historical versions must be read-only.

---

# Version Comparison

The application should support comparison between two versions.

Example:

    Version 3
        VS
    Version 4

Comparison should identify changes in:

- Question Text
- Explanation
- Marks
- Difficulty
- Bloom Level
- Options
- Answers
- Topics
- Tags
- Media

---

# AI Integration

AI-generated revisions must create a new version.

Example:

    Version 5
        ↓
    AI Revision
        ↓
    Version 6

The system must never overwrite the approved version directly with AI output.

Recommended:

    change_type = AI_REVISED

The AI-generated version must then pass the normal review and approval workflow.

---

# Import Integration

Imported Questions should create a version.

Example:

    Existing Question

        ↓

    Import Process

        ↓

    New Version

        ↓

    Review

This ensures imported content remains traceable.

---

# Transaction Requirement

Question update and Version creation must occur within the same database transaction.

Conceptually:

    BEGIN TRANSACTION

        Update Question

        Create Question Version

        Update related metadata

    COMMIT

If any operation fails:

    ROLLBACK

This prevents the Question and Version History from becoming inconsistent.

---

# Immutability

The application must treat Question Versions as immutable.

Allowed:

    SELECT

Not allowed through normal application workflow:

    UPDATE

    DELETE

Changes must always result in:

    NEW VERSION

---

# Performance Considerations

Question versions may grow significantly over time.

For example:

    1,000,000 Questions

    ×

    Average 5 Versions

    =

    5,000,000 Version Records

Therefore:

- Index `question_id`
- Index `created_at`
- Use pagination
- Avoid loading all versions at once
- Archive very old versions if required by retention policy

---

# Future Partitioning

If version history becomes extremely large, partitioning or archival strategies may be considered.

Possible strategy:

    question_versions

partitioned by:

    created_at

However, partitioning should only be introduced after real production workload analysis.

---

# Data Retention

Question Versions should normally be retained indefinitely for:

- Academic Audit
- Examination Disputes
- Content Governance
- Accreditation
- Compliance
- Historical Analysis

Actual retention policy may vary by Institution.

---

# Future Extensions

Possible future tables:

    question_version_options

    question_version_answers

    question_version_media

    question_version_topics

    question_version_tags

    question_version_events

These provide complete immutable snapshots.

---

# Final Relationship

Question

    1
    |
    N
    ↓

QuestionVersion

Each Question may have unlimited historical versions.

---

# Core Principle

Never overwrite historical Question content.

Instead:

    Old Version
        ↓
    Immutable

    New Change
        ↓
    New Version

This principle guarantees Question Bank traceability.

---

End of Section-8

# DATABASE_DESIGN.md

Version: 2.0

Part-4

Section-9

# Tables

1. question_reviews
2. question_approvals

---

# 1. Table : question_reviews

---

## Purpose

Stores the review activities performed on Question Versions.

A review represents an evaluator's assessment of a Question before approval or publication.

Reviews may be performed by:

- Moderator
- Senior Teacher
- Subject Expert
- Principal
- Administrator

---

## Table Name

question_reviews

---

## Primary Key

id

---

## Description

A Review is an evaluation event against a specific Question Version.

A Question may have multiple reviews.

Example:

    Question 101

        Version 3

            ↓

        Reviewer A
            ↓
        Review

        Reviewer B
            ↓
        Review

The review history must remain available for audit.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| question_version_id | BIGINT UNSIGNED | No | — | Reviewed Version |
| reviewer_id | BIGINT UNSIGNED | No | — | Reviewer |
| review_status | VARCHAR(30) | No | PENDING | Review Status |
| quality_score | DECIMAL(5,2) | Yes | NULL | Overall Quality Score |
| accuracy_score | DECIMAL(5,2) | Yes | NULL | Accuracy Score |
| clarity_score | DECIMAL(5,2) | Yes | NULL | Clarity Score |
| difficulty_score | DECIMAL(5,2) | Yes | NULL | Difficulty Assessment |
| relevance_score | DECIMAL(5,2) | Yes | NULL | Curriculum Relevance |
| feedback | LONGTEXT | Yes | NULL | Reviewer Feedback |
| rejection_reason | LONGTEXT | Yes | NULL | Rejection Reason |
| reviewed_at | TIMESTAMP | Yes | NULL | Review Completion Time |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Record Creation Time |
| updated_at | TIMESTAMP | Yes | NULL | Last Update Time |

---

# Review Status

Recommended values:

    PENDING

    IN_PROGRESS

    APPROVED

    CHANGES_REQUESTED

    REJECTED

    WITHDRAWN

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

---

# Foreign Keys

question_id → questions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

question_version_id → question_versions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

reviewer_id → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(question_version_id)

INDEX(reviewer_id)

INDEX(review_status)

INDEX(reviewed_at)

INDEX(question_version_id, review_status)

---

# Relationships

Question

HasMany

QuestionReview

QuestionVersion

HasMany

QuestionReview

User

HasMany

QuestionReview

QuestionReview

BelongsTo

Question

QuestionReview

BelongsTo

QuestionVersion

QuestionReview

BelongsTo

User

---

# Business Rules

1. Every Review must belong to a specific Question Version.

2. A Reviewer must have permission to review the Question.

3. A Reviewer must not normally approve their own Question.

4. A completed Review cannot be silently modified.

5. Rejected Questions must contain a rejection reason.

6. Changes Requested should contain actionable feedback.

7. Review scores are optional because some review workflows may use qualitative assessment only.

8. A Question may require multiple independent Reviews depending on Institution policy.

---

# Review Scoring

Recommended score range:

    0.00 – 100.00

Example:

| Criterion | Score |
|-----------|------:|
| Accuracy | 95 |
| Clarity | 90 |
| Difficulty | 85 |
| Relevance | 95 |

Overall Quality:

    91.25

The actual scoring formula should be implemented in the Service Layer or configurable evaluation policy, not hard-coded into the database.

---

# Review Workflow

    Draft Version

        ↓

    Submit for Review

        ↓

    PENDING

        ↓

    IN_PROGRESS

        ↓

    Reviewer Evaluation

        ↓

    ┌───────────────────────┐
    │                       │
    ↓                       ↓
APPROVED          CHANGES_REQUESTED
                            │
                            ↓
                       New Version
                            │
                            ↓
                       New Review

---

# Laravel Model

QuestionReview

---

# Migration

create_question_reviews_table

---

# Factory

QuestionReviewFactory

---

# Validation Rules

question_id

required

exists:questions,id

question_version_id

required

exists:question_versions,id

reviewer_id

required

exists:users,id

review_status

required

string

max:30

quality_score

nullable

numeric

min:0

max:100

accuracy_score

nullable

numeric

min:0

max:100

clarity_score

nullable

numeric

min:0

max:100

difficulty_score

nullable

numeric

min:0

max:100

relevance_score

nullable

numeric

min:0

max:100

---

# 2. Table : question_approvals

---

## Purpose

Stores the authoritative approval decisions for Question Versions.

Approval is performed against a specific version rather than the Question as a whole.

---

## Table Name

question_approvals

---

## Primary Key

id

---

## Description

An Approval record represents a formal decision regarding a Question Version.

Possible decisions include:

    APPROVED

    REJECTED

    REVOKED

The table provides an auditable approval history.

---

# Columns

| Column | Type | Nullable | Default | Description |
|---------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| question_version_id | BIGINT UNSIGNED | No | — | Target Version |
| approver_id | BIGINT UNSIGNED | No | — | Approver |
| decision | VARCHAR(30) | No | PENDING | Approval Decision |
| approval_level | SMALLINT UNSIGNED | No | 1 | Approval Level |
| comments | LONGTEXT | Yes | NULL | Approval Comments |
| rejection_reason | LONGTEXT | Yes | NULL | Rejection Reason |
| decided_at | TIMESTAMP | Yes | NULL | Decision Time |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Record Creation Time |
| updated_at | TIMESTAMP | Yes | NULL | Last Update Time |

---

# Approval Decisions

Recommended values:

    PENDING

    APPROVED

    REJECTED

    REVOKED

---

# Approval Levels

The system may support multiple approval levels.

Example:

    Level 1

    Teacher Review

        ↓

    Level 2

    Moderator Approval

        ↓

    Level 3

    Principal / Admin Approval

Not every Institution must use all levels.

The number of levels should be configurable.

---

# Constraints

PRIMARY KEY(id)

UNIQUE(uuid)

---

# Foreign Keys

question_id → questions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

question_version_id → question_versions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

approver_id → users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

# Indexes

INDEX(question_id)

INDEX(question_version_id)

INDEX(approver_id)

INDEX(decision)

INDEX(approval_level)

INDEX(decided_at)

INDEX(question_version_id, approval_level)

INDEX(question_version_id, decision)

---

# Relationships

Question

HasMany

QuestionApproval

QuestionVersion

HasMany

QuestionApproval

User

HasMany

QuestionApproval

QuestionApproval

BelongsTo

Question

QuestionApproval

BelongsTo

QuestionVersion

QuestionApproval

BelongsTo

User

---

# Business Rules

1. Approval always applies to a specific Question Version.

2. An unapproved Version cannot be published.

3. A rejected Version cannot be published.

4. Approval decisions must be auditable.

5. An approver cannot normally approve their own Question.

6. Approval Level must follow the configured workflow.

7. Rejection must include a reason.

8. Revocation must preserve the original approval record.

9. Approval records must not be physically deleted.

10. A newer Question Version does not automatically inherit approval from an older Version.

---

# Approval Inheritance Rule

Suppose:

    Version 3 = APPROVED

Then the Question Setter edits the Question.

The system creates:

    Version 4 = DRAFT

Version 4 is NOT automatically approved.

Therefore:

    Version 3
        APPROVED

    Version 4
        DRAFT

This prevents unreviewed changes from reaching examinations.

---

# Approval Workflow

    Question Version

        ↓

    Review

        ↓

    Approval Level 1

        ↓

    Approval Level 2

        ↓

    Final Approval

        ↓

    Publishable

---

# Publishing Rule

A Question Version may become Published only when the configured approval policy is satisfied.

Example:

    Required Approval Levels = 2

Then:

    Level 1 = APPROVED
    Level 2 = APPROVED

must both be satisfied.

If:

    Level 1 = APPROVED
    Level 2 = REJECTED

the version cannot be published.

---

# Approval Revocation

Approval may need to be revoked because of:

- Incorrect Answer
- Curriculum Change
- Duplicate Question
- Copyright Issue
- Examination Security Issue
- Policy Violation
- Moderator Decision

Revocation creates an auditable event.

The original approval record must remain intact.

---

# Laravel Model

QuestionApproval

---

# Migration

create_question_approvals_table

---

# Factory

QuestionApprovalFactory

---

# Validation Rules

question_id

required

exists:questions,id

question_version_id

required

exists:question_versions,id

approver_id

required

exists:users,id

decision

required

string

max:30

approval_level

required

integer

min:1

---

# Current State vs History

The system has two concepts:

## Current State

Stored in:

    questions

and related active records.

## Historical State

Stored in:

    question_versions

    question_reviews

    question_approvals

Historical tables must be treated as append-only audit records.

---

# Recommended Service Layer

Approval logic must NOT be implemented directly inside Controllers.

Recommended Services:

    QuestionVersionService

    QuestionReviewService

    QuestionApprovalService

    QuestionPublishingService

---

# Example Approval Flow

Question:

    Q-1001

Version:

    V5

Workflow:

    Teacher
        ↓
    Submit V5
        ↓
    Moderator Review
        ↓
    APPROVED
        ↓
    Principal Approval
        ↓
    APPROVED
        ↓
    Publish V5

---

# Rejection Flow

    V5

    ↓

    Moderator

    ↓

    REJECTED

    ↓

    Rejection Reason

    ↓

    Teacher

    ↓

    Creates V6

    ↓

    New Review

    ↓

    New Approval

The old V5 remains unchanged.

---

# Security

Recommended permissions:

    question.review.view

    question.review.create

    question.review.update

    question.approval.view

    question.approval.approve

    question.approval.reject

    question.approval.revoke

    question.publish

---

# Role Mapping

Recommended initial mapping:

| Role | Review | Approve | Publish |
|------|--------|---------|---------|
| Super Admin | Yes | Yes | Yes |
| School Admin | Yes | Configurable | Configurable |
| Principal | Yes | Yes | Configurable |
| Teacher | Own Questions | No | No |
| Question Setter | Own Questions | No | No |
| Moderator | Yes | Yes | No |

Actual permissions must be configurable through the authorization system.

---

# Audit Requirements

The system must be able to answer:

    Who reviewed the Question?

    Which version was reviewed?

    Who approved it?

    At which approval level?

    When was it approved?

    Was it later revoked?

    Why was it rejected?

    Which version eventually became published?

---

# Part-4 Module Summary

The complete Question Bank architecture is now:

    Institution
        ↓
    Subject
        ↓
    Chapter
        ↓
    Topic
        ↓
    Question
        │
        ├── question_options
        │
        ├── question_answers
        │
        ├── question_topics
        │
        ├── question_tags
        │
        ├── question_media
        │
        ├── question_versions
        │
        ├── question_reviews
        │
        └── question_approvals

---

# Question Lifecycle

    CREATE
      ↓
    DRAFT
      ↓
    VERSION
      ↓
    REVIEW
      ↓
    APPROVAL
      ↓
    PUBLISH
      ↓
    EXAMINATION
      ↓
    ARCHIVE

---

# Core Integrity Rules

1. Questions must belong to valid academic entities.

2. Questions must have a valid Question Type.

3. Questions must have a valid Difficulty Level.

4. Questions should have at least one Topic before approval.

5. Question Versions are immutable.

6. Reviews reference specific Question Versions.

7. Approvals reference specific Question Versions.

8. Approval of one Version does not approve another Version.

9. Only approved Versions can be published.

10. Historical review and approval records must never be deleted.

11. Question content changes must create a new Version.

12. Examination history must not depend on the current Question state.

---

# Recommended Future Tables

The following tables are intentionally reserved for later modules:

    question_version_options

    question_version_answers

    question_version_media

    question_version_topics

    question_version_tags

    question_statistics

    question_usage

    question_exam_items

    question_duplicate_checks

    question_ai_metadata

    question_generation_logs

---

# Part-4 Table Inventory

| # | Table | Purpose |
|---|-------|---------|
| 1 | question_categories | Question Business Classification |
| 2 | question_types | Question Format |
| 3 | difficulty_levels | Difficulty Classification |
| 4 | blooms_taxonomy_levels | Cognitive Classification |
| 5 | question_sources | Question Provenance |
| 6 | questions | Core Question |
| 7 | question_options | Answer Choices |
| 8 | question_answers | Correct Answer Data |
| 9 | question_topics | Question ↔ Topic Mapping |
| 10 | tags | Flexible Metadata |
| 11 | question_tags | Question ↔ Tag Mapping |
| 12 | question_media | Question Media |
| 13 | question_versions | Immutable Version History |
| 14 | question_reviews | Review History |
| 15 | question_approvals | Approval History |

---

# Part-4 Architecture

The Question Bank Module follows these principles:

    Normalization

    Separation of Concerns

    Immutable Version History

    Role-Based Authorization

    Multi-Institution Support

    Auditability

    Media Storage Abstraction

    Workflow-Based Approval

    Scalable Search

    Future AI Integration

---

# End of Part-4

# DATABASE_DESIGN.md

Version: 2.0

Part-5

# Examination, Blueprint & Exam Generation Database Design

---

# Part-5 Overview

Part-5 defines the database architecture for the Examination Module.

The Examination Module is responsible for:

- Exam creation
- Exam configuration
- Exam blueprint
- Section management
- Marks distribution
- Question distribution
- Difficulty distribution
- Bloom's Taxonomy distribution
- Topic coverage
- Question selection
- Automatic Question Generation
- Manual Question Selection
- Generated Exam Snapshot
- Exam Versioning
- Exam Publishing
- Exam Locking
- Examination History

---

# Architectural Principle

The system must separate:

    Question Bank

from

    Examination Instance

The Question Bank contains reusable Questions.

The Examination Module determines which Questions are selected for a specific Exam.

Therefore:

    Question Bank
          ↓
    Blueprint
          ↓
    Question Selection
          ↓
    Exam Draft
          ↓
    Validation
          ↓
    Final Exam Snapshot
          ↓
    Publish
          ↓
    Examination

---

# Critical Design Rule

An Examination must never depend permanently on the current state of the Question Bank.

When an Exam is finalized, the selected Questions must be snapshotted.

Therefore:

    Question Bank Question

and

    Exam Question

are logically different entities.

---

# Part-5 Table Inventory

Part-5 introduces the following primary tables:

1. exams
2. exam_sections
3. exam_blueprints
4. exam_blueprint_rules
5. exam_blueprint_rule_topics
6. exam_questions
7. exam_question_options
8. exam_versions
9. exam_generation_runs
10. exam_generation_results

---

# Examination Architecture

Institution

    ↓

Exam

    ├── Exam Sections
    │
    ├── Blueprint
    │
    ├── Blueprint Rules
    │
    ├── Generation Runs
    │
    ├── Questions
    │
    └── Versions

---

# Exam Lifecycle

    DRAFT
      ↓
    CONFIGURED
      ↓
    BLUEPRINT_READY
      ↓
    GENERATING
      ↓
    GENERATED
      ↓
    VALIDATION
      ↓
    APPROVED
      ↓
    PUBLISHED
      ↓
    LOCKED
      ↓
    ARCHIVED

---

# Generation Modes

The system should support:

    MANUAL

    AUTOMATIC

    HYBRID

---

# Manual Generation

Teacher or Question Setter manually selects Questions.

    Question Bank
        ↓
    Manual Selection
        ↓
    Exam

---

# Automatic Generation

System selects Questions according to Blueprint Rules.

    Blueprint
        ↓
    Rule Engine
        ↓
    Candidate Questions
        ↓
    Filtering
        ↓
    Scoring
        ↓
    Selection
        ↓
    Validation
        ↓
    Exam

---

# Hybrid Generation

Some Questions are manually selected while the remaining Questions are automatically generated.

Example:

    Required Questions = 50

    Manual = 20

    Automatic = 30

---

# Core Design Principles

1. Blueprint must be independent from generated Question records.

2. Exam Questions must preserve the selected Question Version.

3. Published Exams must be immutable.

4. Generation must be reproducible.

5. Automatic generation must be auditable.

6. Blueprint rules must be validated before generation.

7. Question selection must respect Institution ownership.

8. A Question from one Institution must not appear in another Institution's Exam unless explicitly shared by policy.

---

# Part-5 Sections

Section-1

    exams

Section-2

    exam_sections

Section-3

    exam_blueprints

Section-4

    exam_blueprint_rules

Section-5

    exam_blueprint_rule_topics

Section-6

    exam_questions

Section-7

    exam_question_options

Section-8

    exam_versions

Section-9

    exam_generation_runs

Section-10

    exam_generation_results

---

# Future Extensions

The following modules may be added later:

    exam_schedules

    exam_centers

    exam_sessions

    exam_candidates

    exam_attempts

    exam_answers

    exam_results

    exam_marking

    exam_result_statistics

These should remain separate from the Question Generation architecture.

---

# Scalability Target

The architecture must support:

    1 Institution

to

    1,000 Institutions

and potentially:

    100,000,000+

Questions

with:

    Thousands of Exams

    Millions of Exam Questions

    Large-scale Automatic Generation

---

# Data Flow

Question Bank:

    Questions
       ↓
    Versions
       ↓
    Topics
       ↓
    Tags
       ↓
    Difficulty
       ↓
    Bloom's Level

Examination:

    Exam
       ↓
    Blueprint
       ↓
    Rules
       ↓
    Candidate Pool
       ↓
    Generation Engine
       ↓
    Selected Questions
       ↓
    Exam Snapshot
       ↓
    Publication

---

# Important Separation

Blueprint answers:

    "What kind of Questions should this Exam contain?"

Exam Questions answer:

    "Which exact Questions were selected?"

Therefore:

    Blueprint ≠ Exam Questions

This separation is fundamental to the architecture.

---

# Exam Generation Example

Suppose:

    Exam = HSC Physics Final

    Total Marks = 100

Blueprint:

    MCQ = 30

    Short = 20

    Creative = 50

Difficulty:

    Easy = 30%

    Medium = 50%

    Hard = 20%

Bloom:

    Remember = 20%

    Understand = 30%

    Apply = 30%

    Analyze = 20%

The generation engine must find Questions satisfying these constraints as closely as possible.

---

# Constraint Priority

Not all generation constraints have equal importance.

Recommended priority:

    1. Institution / Subject Integrity

    2. Question Eligibility

    3. Question Type

    4. Required Marks

    5. Blueprint Structure

    6. Topic Coverage

    7. Difficulty Distribution

    8. Bloom Distribution

    9. Diversity

    10. Randomization

Hard constraints must never be violated.

Soft constraints may be optimized when an exact solution is impossible.

---

# Generation Failure

If the Question Bank cannot satisfy a Blueprint:

The system must NOT silently generate an invalid Exam.

Instead:

    GENERATION_FAILED

with diagnostic information such as:

    Insufficient Questions

    Topic Coverage Failure

    Difficulty Distribution Failure

    Bloom Distribution Failure

    Duplicate Question Conflict

    Marks Distribution Conflict

---

# Auditability

Every automatic generation must record:

    Who initiated it

    When it started

    When it finished

    Which Blueprint was used

    Which Rules were applied

    Which Question Versions were selected

    Random Seed

    Generation Algorithm Version

    Result

    Failure Reason

---

# Randomization

Automatic Question generation must support a seed.

Example:

    random_seed = 845712

The same:

    Blueprint
    +
    Question Bank State
    +
    Algorithm Version
    +
    Random Seed

should produce a reproducible generation result where deterministic behavior is supported.

---

# Exam Snapshot Principle

Once an Exam is approved/published:

    Question Bank changes

must NOT modify:

    Published Exam

Therefore Exam Questions must reference a specific:

    question_version_id

and preserve the required snapshot data.

---

# Recommended Transaction Boundary

Exam generation should use a transaction around the finalization stage.

Conceptually:

    BEGIN

        Create Exam Version

        Select Questions

        Create Exam Questions

        Validate Blueprint

        Validate Marks

        Validate Distribution

    COMMIT

If validation fails:

    ROLLBACK

---

# Part-5 Final Architecture

    exams
       │
       ├── exam_sections
       │
       ├── exam_blueprints
       │      │
       │      └── exam_blueprint_rules
       │                 │
       │                 └── exam_blueprint_rule_topics
       │
       ├── exam_questions
       │      │
       │      └── exam_question_options
       │
       ├── exam_versions
       │
       └── exam_generation_runs
                    │
                    └── exam_generation_results

---

# End of Part-5 Overview

ঠিক আছে। এখন `DATABASE_DESIGN.md → Part-5 → Section-1`। এখানে মূল `exams` table থাকবে। এই table একটি পরীক্ষার logical identity, institution ownership, subject, exam type, lifecycle এবং generation configuration-এর মূল কেন্দ্র হবে।

```markdown id="82641em"
# DATABASE_DESIGN.md

Version: 2.0

Part-5

Section-1

# Table : exams

---

## Purpose

Stores the core identity and configuration of an Examination.

An Exam represents a formal examination entity created by an Institution.

Examples:

- Class 6 Mathematics Monthly Exam
- SSC Physics Model Test
- HSC Chemistry Final Examination
- University Physics Midterm
- School Admission Test
- Weekly Mathematics Assessment

The `exams` table stores the high-level examination configuration.

It does NOT directly store the individual Questions.

Questions selected for an Exam are stored in:

    exam_questions

---

# Table Name

exams

---

# Primary Key

id

---

# Description

An Exam belongs to one Institution and may be associated with:

- Academic Year
- Subject
- Class / Grade
- Exam Type
- Curriculum
- Created By
- Approved By
- Published By

The Exam acts as the parent entity for:

    exam_sections

    exam_blueprints

    exam_questions

    exam_versions

    exam_generation_runs

---

# Columns

| Column | Type | Nullable | Default | Description |
|--------|----------------------|----------|----------|-------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| institution_id | BIGINT UNSIGNED | No | — | Owning Institution |
| academic_year_id | BIGINT UNSIGNED | Yes | NULL | Academic Year |
| curriculum_id | BIGINT UNSIGNED | Yes | NULL | Curriculum |
| subject_id | BIGINT UNSIGNED | No | — | Subject |
| class_id | BIGINT UNSIGNED | Yes | NULL | Class / Grade |
| exam_type_id | BIGINT UNSIGNED | No | — | Exam Type |
| code | VARCHAR(50) | No | — | Human-readable Exam Code |
| title | VARCHAR(255) | No | — | Exam Title |
| slug | VARCHAR(300) | No | — | URL/Search Slug |
| description | TEXT | Yes | NULL | Exam Description |
| instructions | LONGTEXT | Yes | NULL | Candidate Instructions |
| language_code | VARCHAR(10) | No | en | Primary Language |
| total_marks | DECIMAL(8,2) | No | 0.00 | Total Exam Marks |
| total_questions | INTEGER UNSIGNED | No | 0 | Number of Questions |
| duration_minutes | INTEGER UNSIGNED | No | 0 | Exam Duration |
| passing_marks | DECIMAL(8,2) | Yes | NULL | Minimum Passing Marks |
| generation_mode | VARCHAR(20) | No | MANUAL | Generation Mode |
| status | VARCHAR(30) | No | DRAFT | Exam Status |
| current_version_id | BIGINT UNSIGNED | Yes | NULL | Current Exam Version |
| created_by | BIGINT UNSIGNED | No | — | Creator |
| updated_by | BIGINT UNSIGNED | Yes | NULL | Last Editor |
| approved_by | BIGINT UNSIGNED | Yes | NULL | Approver |
| approved_at | TIMESTAMP | Yes | NULL | Approval Time |
| published_by | BIGINT UNSIGNED | Yes | NULL | Publisher |
| published_at | TIMESTAMP | Yes | NULL | Publication Time |
| locked_at | TIMESTAMP | Yes | NULL | Lock Time |
| archived_at | TIMESTAMP | Yes | NULL | Archive Time |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Created Time |
| updated_at | TIMESTAMP | Yes | NULL | Updated Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Primary Key

    id

Type:

    BIGINT UNSIGNED

Purpose:

Internal database identifier.

---

# Public Identifier

    uuid

Type:

    CHAR(36)

Purpose:

Used for external references where exposing sequential database IDs is undesirable.

Example:

    7f5c8d7e-xxxx-xxxx-xxxx-xxxxxxxxxxxx

Constraint:

    UNIQUE(uuid)

---

# Institution Ownership

    institution_id

Every Exam must belong to exactly one Institution.

This is mandatory for multi-institution support.

Example:

    Institution A
        ↓
    Exam 1
    Exam 2
    Exam 3

    Institution B
        ↓
    Exam 4
    Exam 5

An Exam belonging to Institution A must not access Questions owned by Institution B unless an explicit content-sharing mechanism exists.

---

# Academic Year

    academic_year_id

Optional because some examinations may not be associated with a formal academic year.

Examples:

    2025

    2025-2026

    2026

If the Academic Year module is mandatory in a particular Institution, application-level validation can enforce it.

---

# Curriculum

    curriculum_id

Identifies the curriculum under which the Exam is created.

Examples:

    National Curriculum

    Cambridge

    Edexcel

    IB

    Institution Custom Curriculum

This is important because the same Subject may have different syllabus structures across curricula.

---

# Subject

    subject_id

Every Exam must have a primary Subject.

Examples:

    Physics

    Mathematics

    Chemistry

    Biology

    Computer Science

Foreign Key:

    subjects.id

---

# Class / Grade

    class_id

Optional because not all examinations are class-based.

Examples:

    Class 6

    Class 8

    SSC

    HSC

    Undergraduate

    Admission Test

---

# Exam Type

    exam_type_id

Defines the type/category of examination.

Examples:

    Class Test

    Monthly Test

    Midterm

    Final

    Model Test

    Admission Test

    Practice Test

    Quiz

    Mock Examination

---

# Exam Code

    code

Human-readable unique identifier.

Example:

    PHY-HSC-FINAL-2026-001

Recommended constraint:

    UNIQUE(institution_id, code)

The same code may exist in another Institution.

---

# Exam Title

    title

Example:

    HSC Physics Final Examination 2026

Maximum:

    255 characters

---

# Slug

    slug

Used for internal routing/search.

Example:

    hsc-physics-final-examination-2026

Recommended constraint:

    UNIQUE(institution_id, slug)

---

# Description

Optional long-form description.

Example:

    Final examination covering Chapters 1-10
    according to the 2026 Physics curriculum.

---

# Instructions

Stores candidate-facing examination instructions.

Example:

    Answer all MCQ questions.

    Use black or blue ink.

    Do not use calculators unless permitted.

The application may later support structured instructions through a separate table.

---

# Language

    language_code

Recommended values follow BCP-47 / ISO-style language codes.

Examples:

    en

    bn

    ar

    hi

    ur

    fr

    de

For multilingual Exams, future versions may support:

    exam_translations

---

# Total Marks

    total_marks

Represents the maximum marks of the Exam.

Example:

    100.00

This value should normally be calculated from the Exam structure.

The application must validate:

    SUM(exam_question_marks)
        =
    total_marks

before publication.

---

# Total Questions

    total_questions

Represents the number of Questions included in the Exam.

Example:

    50

This should normally equal:

    COUNT(exam_questions)

for the current Exam Version.

---

# Duration

    duration_minutes

Exam duration in minutes.

Examples:

    30

    60

    120

    180

---

# Passing Marks

    passing_marks

Optional.

Example:

    Total Marks = 100

    Passing Marks = 40

Validation:

    passing_marks <= total_marks

---

# Generation Mode

Recommended values:

    MANUAL

    AUTOMATIC

    HYBRID

---

## MANUAL

All Questions are selected manually.

Example:

    Teacher selects 50 Questions.

---

## AUTOMATIC

Questions are selected by the Exam Generation Engine according to Blueprint rules.

---

## HYBRID

Some Questions are manually selected while the remaining Questions are automatically generated.

Example:

    Total = 50

    Manual = 20

    Automatic = 30

---

# Exam Status

Recommended lifecycle values:

    DRAFT

    CONFIGURED

    BLUEPRINT_READY

    GENERATING

    GENERATED

    UNDER_REVIEW

    APPROVED

    PUBLISHED

    LOCKED

    ARCHIVED

    CANCELLED

---

# Status Lifecycle

Recommended transition:

    DRAFT
      ↓
    CONFIGURED
      ↓
    BLUEPRINT_READY
      ↓
    GENERATING
      ↓
    GENERATED
      ↓
    UNDER_REVIEW
      ↓
    APPROVED
      ↓
    PUBLISHED
      ↓
    LOCKED
      ↓
    ARCHIVED

Cancellation may occur according to business policy.

---

# Current Version

    current_version_id

References the current active record in:

    exam_versions

This provides a direct pointer to the current Exam Version.

Important:

The relationship is intentionally nullable because the Exam may exist before its first Version is created.

---

# Creator

    created_by

References:

    users.id

Records who created the Exam.

---

# Last Editor

    updated_by

References:

    users.id

Records the last user who modified the Exam.

---

# Approval

    approved_by

    approved_at

These fields provide a convenient current-state reference.

The authoritative approval history remains:

    question_approvals

for Questions, and the future Exam Approval module for Exams.

These fields must not replace an audit/event history.

---

# Publication

    published_by

    published_at

Records who published the Exam and when.

---

# Locking

    locked_at

Once an Exam is locked, normal content modification must be disabled.

Locking is important for examination integrity.

Example:

    PUBLISHED
        ↓
    LOCKED

After locking:

    Questions cannot be replaced.

    Marks cannot be changed.

    Blueprint cannot be modified.

    Answer configuration cannot be modified.

---

# Archiving

    archived_at

Used when an Exam is no longer active but must remain available for historical purposes.

Archiving must not delete examination history.

---

# Soft Delete

    deleted_at

The Exam should use Soft Deletes.

Published or locked Exams should normally never be physically deleted.

---

# Foreign Keys

institution_id

    →

institutions.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

academic_year_id

    →

academic_years.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

curriculum_id

    →

curricula.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

subject_id

    →

subjects.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

class_id

    →

classes.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

exam_type_id

    →

exam_types.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

created_by

    →

users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

updated_by

    →

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

approved_by

    →

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

published_by

    →

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

current_version_id

    →

exam_versions.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Indexes

Recommended:

    INDEX(institution_id)

    INDEX(subject_id)

    INDEX(academic_year_id)

    INDEX(curriculum_id)

    INDEX(class_id)

    INDEX(exam_type_id)

    INDEX(status)

    INDEX(generation_mode)

    INDEX(created_by)

    INDEX(published_at)

    INDEX(locked_at)

    INDEX(institution_id, status)

    INDEX(institution_id, subject_id)

    INDEX(institution_id, academic_year_id)

---

# Unique Constraints

Recommended:

    UNIQUE(institution_id, code)

    UNIQUE(institution_id, slug)

    UNIQUE(uuid)

---

# Relationships

Institution

    HasMany

    Exam

---

Subject

    HasMany

    Exam

---

AcademicYear

    HasMany

    Exam

---

Curriculum

    HasMany

    Exam

---

Class

    HasMany

    Exam

---

ExamType

    HasMany

    Exam

---

User

    HasMany

    Exam

through created_by

---

Exam

    HasMany

    ExamSection

---

Exam

    HasMany

    ExamBlueprint

---

Exam

    HasMany

    ExamQuestion

---

Exam

    HasMany

    ExamVersion

---

Exam

    HasMany

    ExamGenerationRun

---

Exam

    BelongsTo

    ExamVersion

through current_version_id

---

# Laravel Model

Exam

---

# Migration

create_exams_table

---

# Factory

ExamFactory

---

# Seeder

ExamSeeder

---

# Laravel Relationships

## Exam Model

    public function institution()
    {
        return $this->belongsTo(
            Institution::class
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class
        );
    }

    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class
        );
    }

    public function curriculum()
    {
        return $this->belongsTo(
            Curriculum::class
        );
    }

    public function class()
    {
        return $this->belongsTo(
            SchoolClass::class
        );
    }

    public function examType()
    {
        return $this->belongsTo(
            ExamType::class
        );
    }

    public function sections()
    {
        return $this->hasMany(
            ExamSection::class
        );
    }

    public function blueprints()
    {
        return $this->hasMany(
            ExamBlueprint::class
        );
    }

    public function questions()
    {
        return $this->hasMany(
            ExamQuestion::class
        );
    }

    public function versions()
    {
        return $this->hasMany(
            ExamVersion::class
        );
    }

    public function currentVersion()
    {
        return $this->belongsTo(
            ExamVersion::class,
            'current_version_id'
        );
    }

    public function generationRuns()
    {
        return $this->hasMany(
            ExamGenerationRun::class
        );
    }
```

# Validation Rules

```text
institution_id

required

exists:institutions,id


subject_id

required

exists:subjects,id


exam_type_id

required

exists:exam_types,id


code

required

string

max:50


title

required

string

max:255


slug

required

string

max:300


language_code

required

string

max:10


total_marks

required

numeric

min:0


total_questions

required

integer

min:1


duration_minutes

required

integer

min:1


passing_marks

nullable

numeric

min:0


generation_mode

required

in:MANUAL,AUTOMATIC,HYBRID


status

required

in:
DRAFT,
CONFIGURED,
BLUEPRINT_READY,
GENERATING,
GENERATED,
UNDER_REVIEW,
APPROVED,
PUBLISHED,
LOCKED,
ARCHIVED,
CANCELLED
```

# Business Rules

```text
1. Every Exam belongs to exactly one Institution.

2. Every Exam has exactly one primary Subject.

3. Every Exam has exactly one Exam Type.

4. Exam Code must be unique within an Institution.

5. Exam Slug must be unique within an Institution.

6. Total Marks must equal the sum of published Exam Question marks.

7. Total Questions must equal the number of active Exam Questions.

8. Passing Marks cannot exceed Total Marks.

9. An Exam cannot be published without valid Exam Questions.

10. An Exam cannot be published if required Blueprint constraints are unsatisfied.

11. An Exam cannot be published without the required approval.

12. A Published Exam must not be modified directly.

13. A Locked Exam must be immutable.

14. Archived Exams must remain available for historical reporting.

15. Deleted Exams must not be physically removed if examination history depends on them.
```

# Generation Rules

For:

```
generation_mode = MANUAL
```

the system must not automatically replace manually selected Questions.

For:

```
generation_mode = AUTOMATIC
```

the Exam Generation Engine must use:

```
exam_blueprints
```

and

```
exam_blueprint_rules
```

For:

```
generation_mode = HYBRID
```

the engine must distinguish:

```
MANUAL_SELECTED
```

from

```
AUTO_SELECTED
```

Questions.

---

# Exam Ownership

The Institution is the primary ownership boundary.

Authorization checks must verify:

```
authenticated_user
    ↓
Institution
    ↓
Exam
    ↓
Subject
    ↓
Blueprint
    ↓
Question Bank
```

Cross-Institution access must be denied by default.

---

# Exam Security

Published examination content may be sensitive.

Therefore:

* Draft Exams may be editable.
* Under-review Exams should have restricted access.
* Approved Exams should be restricted.
* Published Exams should be controlled.
* Locked Exams should be immutable.
* Question data should not be exposed through unauthorized APIs.
* Exam generation logs should not expose answer keys to unauthorized users.

---

# Exam Code Example

```text
PHY-HSC-FINAL-2026-001
```

Meaning:

```text
PHY
=
Physics

HSC
=
Higher Secondary

FINAL
=
Final Examination

2026
=
Academic Year

001
=
Sequence
```

---

# Example Exam

| Field            | Value                              |
| ---------------- | ---------------------------------- |
| code             | PHY-HSC-FINAL-2026-001             |
| title            | HSC Physics Final Examination 2026 |
| subject          | Physics                            |
| exam_type        | Final                              |
| total_marks      | 100                                |
| total_questions  | 50                                 |
| duration_minutes | 180                                |
| passing_marks    | 40                                 |
| generation_mode  | AUTOMATIC                          |
| status           | BLUEPRINT_READY                    |

---

# Example Lifecycle

```text
Create Exam
    ↓
DRAFT
    ↓
Configure Subject
    ↓
Configure Marks
    ↓
Configure Duration
    ↓
Configure Blueprint
    ↓
BLUEPRINT_READY
    ↓
Generate Questions
    ↓
GENERATED
    ↓
Review
    ↓
UNDER_REVIEW
    ↓
Approve
    ↓
APPROVED
    ↓
Publish
    ↓
PUBLISHED
    ↓
Lock
    ↓
LOCKED
```

---

# Transaction Rules

Critical Exam state changes must use database transactions.

Examples:

```
Generate Exam

Approve Exam

Publish Exam

Lock Exam
```

For example:

```text
BEGIN TRANSACTION

    Validate Exam

    Validate Blueprint

    Validate Questions

    Create Exam Version

    Create Exam Questions

    Update Exam Status

    Update Current Version

COMMIT
```

On failure:

```text
ROLLBACK
```

---

# Concurrency

Multiple users may attempt to modify an Exam simultaneously.

Therefore:

* Use database transactions.
* Use row-level locking where required.
* Validate current status before mutation.
* Prevent generation from running concurrently for the same Exam.
* Prevent publishing while generation is running.
* Prevent modification after locking.

---

# Recommended Service Layer

Controllers should not contain Exam business logic.

Recommended Services:

```
ExamService

ExamConfigurationService

ExamBlueprintService

ExamGenerationService

ExamValidationService

ExamPublishingService

ExamLockService
```

---

# Recommended Events

Future Laravel Events:

```
ExamCreated

ExamConfigured

ExamBlueprintReady

ExamGenerationStarted

ExamGenerationCompleted

ExamGenerationFailed

ExamSubmittedForReview

ExamApproved

ExamPublished

ExamLocked

ExamArchived
```

---

# Recommended Notifications

Possible notifications:

```
ExamGenerationCompleted

ExamGenerationFailed

ExamSubmittedForReview

ExamApproved

ExamRejected

ExamPublished
```

---

# Future API Resources

Possible API:

```
GET    /api/exams

POST   /api/exams

GET    /api/exams/{exam}

PUT    /api/exams/{exam}

DELETE /api/exams/{exam}

POST   /api/exams/{exam}/configure

POST   /api/exams/{exam}/generate

POST   /api/exams/{exam}/submit-review

POST   /api/exams/{exam}/approve

POST   /api/exams/{exam}/publish

POST   /api/exams/{exam}/lock
```

---

# Important Architectural Decision

The `exams` table is NOT the final Exam paper.

It is the logical parent/container.

The actual generated paper is represented through:

```
exam_versions
```

and

```
exam_questions
```

This distinction allows:

```
Exam Configuration
    ≠
Generated Exam Paper
```

---

# Example

Exam:

```
HSC Physics Final 2026
```

may produce:

```
Exam Version 1
    ↓
Set A

Exam Version 2
    ↓
Set B

Exam Version 3
    ↓
Set C
```

The same Exam definition can therefore generate multiple valid paper versions.

---

# Data Integrity

The following must always be true:

```text
exam.institution_id
    =
exam_blueprint.institution_id
```

and:

```text
exam.subject_id
    =
blueprint.subject scope
```

and:

```text
exam_questions.exam_id
    =
exam.id
```

and:

```text
exam_questions.question_version_id
    belongs to
exam_questions.question_id
```

and:

```text
published Exam
    →
approved Exam Version
```

---

# End of Section-1
এখন `DATABASE_DESIGN.md → Part-5 → Section-2: exam_sections`। এই table একটি Exam-এর ভেতরের section structure নির্ধারণ করবে—যেমন MCQ, Short Question, Creative/Structured Question ইত্যাদি। Blueprint এবং actual `exam_questions`—দুটিই section-এর সঙ্গে সম্পর্কিত হবে।

````markdown id="52794"
# DATABASE_DESIGN.md

Version: 2.0

Part-5

Section-2

# Table : exam_sections

---

## Purpose

Stores the structural sections of an Examination.

An Exam may contain one or more Sections.

Examples:

    Section A
        Multiple Choice Questions

    Section B
        Short Questions

    Section C
        Creative Questions

A Section defines the structural organization of an Exam.

It does NOT directly define the Question selection algorithm.

Question selection rules are stored in:

    exam_blueprints

    exam_blueprint_rules

---

# Table Name

exam_sections

---

# Primary Key

id

---

# Description

An Exam Section represents a logical grouping of Questions within an Exam.

Example:

    HSC Physics Final Examination

        Section A
            MCQ
            30 Marks

        Section B
            Short Questions
            20 Marks

        Section C
            Creative Questions
            50 Marks

The section structure is used by:

- Exam Rendering
- Blueprint Configuration
- Question Generation
- Marks Calculation
- Candidate Instructions
- PDF Generation
- Exam Navigation
- Result Processing

---

# Columns

| Column | Type | Nullable | Default | Description |
|--------|----------------------|----------|----------|------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| exam_id | BIGINT UNSIGNED | No | — | Parent Exam |
| section_code | VARCHAR(30) | No | — | Section Code |
| section_number | SMALLINT UNSIGNED | No | 1 | Section Sequence |
| title | VARCHAR(255) | No | — | Section Title |
| short_title | VARCHAR(100) | Yes | NULL | Short Display Title |
| description | TEXT | Yes | NULL | Section Description |
| instructions | LONGTEXT | Yes | NULL | Section Instructions |
| question_type_id | BIGINT UNSIGNED | Yes | NULL | Primary Question Type |
| selection_mode | VARCHAR(20) | No | AUTO | Question Selection Mode |
| question_count | INTEGER UNSIGNED | No | 0 | Required Question Count |
| total_marks | DECIMAL(8,2) | No | 0.00 | Section Total Marks |
| marks_per_question | DECIMAL(8,2) | Yes | NULL | Default Marks |
| negative_marks | DECIMAL(8,2) | No | 0.00 | Negative Marks |
| is_optional | BOOLEAN | No | FALSE | Whether Section Is Optional |
| min_questions | INTEGER UNSIGNED | Yes | NULL | Minimum Questions |
| max_questions | INTEGER UNSIGNED | Yes | NULL | Maximum Questions |
| display_order | SMALLINT UNSIGNED | No | 1 | Rendering Order |
| status | SMALLINT UNSIGNED | No | 1 | Active Status |
| created_by | BIGINT UNSIGNED | No | — | Creator |
| updated_by | BIGINT UNSIGNED | Yes | NULL | Last Editor |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Creation Time |
| updated_at | TIMESTAMP | Yes | NULL | Update Time |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Primary Key

    id

Type:

    BIGINT UNSIGNED

---

# Public Identifier

    uuid

Constraint:

    UNIQUE(uuid)

---

# Parent Exam

    exam_id

Every Section belongs to exactly one Exam.

Foreign Key:

    exams.id

---

# Section Code

    section_code

Short machine-readable identifier.

Examples:

    A

    B

    C

    MCQ

    CQ

    SAQ

Recommended constraint:

    UNIQUE(exam_id, section_code)

---

# Section Number

    section_number

Represents the logical section sequence.

Example:

    1
    2
    3

This is separate from `display_order`.

---

# Section Title

Examples:

    Multiple Choice Questions

    Short Questions

    Creative Questions

    Numerical Problems

---

# Short Title

Optional compact title.

Example:

    MCQ

    Short

    Creative

Useful for:

- Mobile UI
- Compact PDF headers
- Navigation
- Tables

---

# Description

Provides additional information about the Section.

Example:

    Answer all questions in this section.

---

# Instructions

Candidate-facing instructions specific to the Section.

Example:

    Select the correct answer for each question.

or:

    Answer any five questions.

---

# Question Type

    question_type_id

Optional primary Question Type associated with the Section.

Example:

    Section A
        →
    MCQ

    Section B
        →
    Short Answer

This is a default structural association.

The actual Question Type of each selected Question remains authoritative at Question level.

---

# Selection Mode

Recommended values:

    AUTO

    MANUAL

    HYBRID

---

## AUTO

Questions are selected automatically according to Blueprint Rules.

---

## MANUAL

Questions are selected manually.

---

## HYBRID

Some Questions are manually selected and the remaining Questions are generated automatically.

---

# Question Count

    question_count

Number of Questions intended for the Section.

Example:

    Section A

    question_count = 30

---

# Total Marks

    total_marks

Total marks allocated to the Section.

Example:

    Section A = 30

    Section B = 20

    Section C = 50

Total:

    100

---

# Marks Per Question

    marks_per_question

Used when every Question in the Section has the same marks.

Example:

    30 Questions

    1 mark each

    marks_per_question = 1.00

For mixed-mark Sections, this field may remain NULL.

---

# Negative Marks

    negative_marks

Penalty applied when applicable.

Example:

    MCQ

    Correct = +1

    Incorrect = -0.25

The actual scoring model may later be extended through an Exam Scoring Policy table.

---

# Optional Section

    is_optional

Determines whether candidates may choose whether to answer this Section.

Example:

    Section C

    Answer any 5 out of 8 Questions.

In such cases:

    is_optional = TRUE

---

# Minimum Questions

    min_questions

Defines the minimum number of Questions required.

Example:

    8 Questions available

    Candidate must answer 5

Then:

    min_questions = 5

---

# Maximum Questions

    max_questions

Defines the maximum number of Questions that may be attempted/selected where applicable.

Example:

    max_questions = 5

---

# Important Distinction

For generated Exam content:

    question_count

means:

    Questions physically included in the Exam.

Whereas:

    min_questions

and

    max_questions

may define:

    Questions the candidate is required/allowed to answer.

Example:

    8 Questions displayed

    Candidate answers 5

Then:

    question_count = 8

    min_questions = 5

    max_questions = 5

---

# Display Order

    display_order

Controls rendering order.

Example:

    Section A → 1

    Section B → 2

    Section C → 3

This is independent from the database ID.

---

# Status

Recommended numeric convention:

    1 = ACTIVE

    0 = INACTIVE

Inactive Sections must not appear in newly generated Exams.

---

# Foreign Keys

exam_id

    →

exams.id

ON DELETE CASCADE

ON UPDATE CASCADE

---

question_type_id

    →

question_types.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

created_by

    →

users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

updated_by

    →

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Indexes

Recommended:

    INDEX(exam_id)

    INDEX(question_type_id)

    INDEX(selection_mode)

    INDEX(status)

    INDEX(display_order)

    INDEX(exam_id, display_order)

    INDEX(exam_id, status)

---

# Unique Constraints

Recommended:

    UNIQUE(uuid)

    UNIQUE(exam_id, section_code)

    UNIQUE(exam_id, section_number)

---

# Relationships

Exam

    HasMany

    ExamSection

---

QuestionType

    HasMany

    ExamSection

---

User

    HasMany

    ExamSection

through:

    created_by

---

ExamSection

    BelongsTo

    Exam

---

ExamSection

    BelongsTo

    QuestionType

---

ExamSection

    HasMany

    ExamQuestion

---

ExamSection

    HasMany

    ExamBlueprint

---

# Laravel Model

ExamSection

---

# Migration

create_exam_sections_table

---

# Factory

ExamSectionFactory

---

# Seeder

ExamSectionSeeder

---

# Laravel Relationships

## Exam Model

    public function sections()
    {
        return $this->hasMany(
            ExamSection::class
        );
    }

---

## ExamSection Model

    public function exam()
    {
        return $this->belongsTo(
            Exam::class
        );
    }

    public function questionType()
    {
        return $this->belongsTo(
            QuestionType::class
        );
    }

    public function questions()
    {
        return $this->hasMany(
            ExamQuestion::class
        );
    }

    public function blueprints()
    {
        return $this->hasMany(
            ExamBlueprint::class
        );
    }

---

# Validation Rules

```text
exam_id

required

exists:exams,id


section_code

required

string

max:30


section_number

required

integer

min:1


title

required

string

max:255


short_title

nullable

string

max:100


question_type_id

nullable

exists:question_types,id


selection_mode

required

in:AUTO,MANUAL,HYBRID


question_count

required

integer

min:0


total_marks

required

numeric

min:0


marks_per_question

nullable

numeric

min:0


negative_marks

required

numeric

min:0


is_optional

boolean


min_questions

nullable

integer

min:0


max_questions

nullable

integer

min:0


display_order

required

integer

min:1


status

required

boolean
````

---

# Business Rules

1. Every Section belongs to exactly one Exam.

2. Section Code must be unique within an Exam.

3. Section Number must be unique within an Exam.

4. Display Order must be deterministic.

5. An inactive Section cannot receive newly generated Questions.

6. Question Count cannot be negative.

7. Total Marks cannot be negative.

8. Negative Marks cannot be negative.

9. Minimum Questions cannot exceed Maximum Questions when both are defined.

10. Maximum Questions cannot exceed Question Count.

11. If `is_optional = FALSE`, candidate-answer rules must be validated according to the Section policy.

12. A Section cannot be published if its required Question structure is incomplete.

---

# Marks Validation

For a uniform-mark Section:

```
question_count
    ×
marks_per_question
    =
total_marks
```

Example:

```
20 × 1
    =
20 Marks
```

---

# Mixed Marks

Some Sections may contain Questions with different marks.

Example:

```
Question 1 = 2 marks

Question 2 = 3 marks

Question 3 = 5 marks
```

In this case:

```
marks_per_question = NULL
```

and:

```
total_marks
```

must be calculated from actual Exam Questions.

---

# Optional Question Pattern

Example:

```
Section C

8 Questions

Answer any 5
```

Database:

```
question_count = 8

min_questions = 5

max_questions = 5

is_optional = TRUE
```

Total displayed marks may be:

```
8 × 10 = 80
```

Candidate-scored maximum:

```
5 × 10 = 50
```

The distinction must be preserved.

---

# Section-Level Generation

If:

```
selection_mode = AUTO
```

the Section must have at least one valid Blueprint Rule.

Example:

```
Section A

AUTO

20 Questions
```

Blueprint:

```
MCQ

Medium

Chapter 1-5
```

---

# Section-Level Manual Selection

If:

```
selection_mode = MANUAL
```

the system expects explicit Questions to be selected.

Generation Engine must not replace manually selected Questions unless explicitly instructed.

---

# Hybrid Selection

Example:

```
Section A

question_count = 20

selection_mode = HYBRID
```

Manual:

```
5 Questions
```

Automatic:

```
15 Questions
```

The system must track the source of each Exam Question.

Recommended values:

```
MANUAL_SELECTED

AUTO_SELECTED

IMPORTED

REUSED
```

This source field belongs to:

```
exam_questions
```

not:

```
exam_sections
```

---

# Section Rendering

The PDF/HTML rendering layer should use:

```
display_order
```

to determine Section order.

Example:

```text
SECTION A
Multiple Choice Questions

1. Question
2. Question
3. Question

SECTION B
Short Questions

4. Question
5. Question

SECTION C
Creative Questions

6. Question
7. Question
```

---

# PDF Generation

Section metadata may be used to generate:

* Section Header
* Section Instructions
* Section Marks
* Question Numbering
* Optional Question Instructions
* Page Break Rules

Example:

```text
SECTION A — MULTIPLE CHOICE QUESTIONS
Marks: 30
Answer all questions.
```

---

# Numbering

Question numbering should NOT depend on:

```
exam_questions.id
```

Instead use a logical:

```
question_number
```

stored in:

```
exam_questions
```

Example:

```text
Section A

1.
2.
3.

Section B

4.
5.
6.
```

or:

```text
Section A

A1
A2
A3

Section B

B1
B2
B3
```

The numbering strategy should be configurable at Exam or Section level in a future version.

---

# Security

Recommended permissions:

```
exam.section.view

exam.section.create

exam.section.update

exam.section.delete
```

---

# Role Access

Recommended:

| Role            | View | Create | Update | Delete       |
| --------------- | ---- | ------ | ------ | ------------ |
| Super Admin     | Yes  | Yes    | Yes    | Yes          |
| School Admin    | Yes  | Yes    | Yes    | Yes          |
| Principal       | Yes  | Yes    | Yes    | Configurable |
| Moderator       | Yes  | No     | Review | No           |
| Teacher         | Yes  | Yes    | Yes    | Configurable |
| Question Setter | Yes  | Yes    | Yes    | Own          |

````

---

# Example

```text
Exam:

HSC Physics Final Examination 2026

Sections:

Section A
    Code = A
    Type = MCQ
    Questions = 30
    Marks = 30
    Selection = AUTO

Section B
    Code = B
    Type = Short
    Questions = 10
    Marks = 20
    Selection = HYBRID

Section C
    Code = C
    Type = Creative
    Questions = 8
    Marks = 50
    Selection = AUTO
````

---

# Example Table

| Section   | Type     | Questions |   Marks | Mode   |
| --------- | -------- | --------- | ------: | ------ |
| A         | MCQ      | 30        |      30 | AUTO   |
| B         | Short    | 10        |      20 | HYBRID |
| C         | Creative | 8         |      50 | AUTO   |
| **Total** |          | **48**    | **100** |        |

---

# Data Integrity

The following must hold:

```text
exam_sections.exam_id
    =
exams.id
```

and:

```text
exam_sections.question_type_id
    =
valid question_types.id
```

and:

```text
SUM(exam_sections.total_marks)
    =
exams.total_marks
```

before publication.

However, this final consistency check must be performed by the application/service layer because aggregate cross-row validation is not safely enforceable through a simple foreign key.

---

# Section vs Blueprint

A Section defines:

```
WHERE Questions belong
```

and:

```
HOW the Exam is structurally organized.
```

A Blueprint defines:

```
WHAT Questions should be selected.
```

Example:

Section:

```
Section A
```

Blueprint:

```
30 MCQ

Difficulty:
    30% Easy
    50% Medium
    20% Hard

Topics:
    Chapter 1
    Chapter 2
    Chapter 3
```

Therefore:

```
ExamSection
    ↓
ExamBlueprint
    ↓
BlueprintRules
    ↓
ExamQuestions
```

````

---

# Section Lifecycle

```text
CREATE
   ↓
CONFIGURE
   ↓
ADD BLUEPRINT
   ↓
GENERATE / SELECT
   ↓
VALIDATE
   ↓
APPROVE
   ↓
PUBLISH
   ↓
LOCK
````

---

# Important Architectural Decision

`exam_sections` stores the **exam structure**, not the final generated Questions.

The actual Question assignment belongs to:

```
exam_questions
```

The selection criteria belong to:

```
exam_blueprints
```

The specific filtering rules belong to:

```
exam_blueprint_rules
```

Therefore:

```text
ExamSection
    ↓
Structure

ExamBlueprint
    ↓
Selection Strategy

ExamBlueprintRule
    ↓
Selection Constraints

ExamQuestion
    ↓
Actual Selected Question
```

---

# Future Extensions

Possible future fields/tables:

```
numbering_scheme

section_layout

section_header_template

section_footer_template

section_scoring_policy_id

section_candidate_rules

section_randomization_policy
```

These should be added only when the corresponding business requirements are finalized.

---

# End of Section-2

এখন `DATABASE_DESIGN.md → Part-5 → Section-3: exam_blueprints`। এই table হলো automatic question-generation architecture-এর কেন্দ্রীয় configuration layer। এখানে Exam-এর “কী ধরনের Question চাই” তার high-level blueprint সংরক্ষণ হবে; নির্দিষ্ট filtering constraints থাকবে পরবর্তী `exam_blueprint_rules` table-এ।

```markdown id="63184"
# DATABASE_DESIGN.md

Version: 2.0

Part-5

Section-3

# Table : exam_blueprints

---

## Purpose

Stores the high-level blueprint configuration for an Examination.

An Exam Blueprint defines the expected structure and distribution of Questions for an Exam or Exam Section.

It answers:

    "What should the generated Exam contain?"

It does NOT directly store the selected Questions.

Actual Questions are stored in:

    exam_questions

Detailed selection constraints are stored in:

    exam_blueprint_rules

---

# Table Name

exam_blueprints

---

# Primary Key

id

---

# Description

An Exam Blueprint is a formal specification of the Question distribution required for an Exam.

It may define:

- Total Questions
- Total Marks
- Question Type Distribution
- Difficulty Distribution
- Bloom's Taxonomy Distribution
- Topic Coverage
- Selection Mode
- Generation Strategy
- Randomization
- Minimum / Maximum Constraints
- Blueprint Status

---

# Blueprint Scope

A Blueprint may apply to:

    Entire Exam

or:

    Specific Exam Section

Therefore:

    exam_id

is mandatory.

and:

    exam_section_id

is optional.

---

# Example

Exam:

    HSC Physics Final 2026

Blueprint:

    Total Questions = 50

    Total Marks = 100

    Difficulty:

        Easy = 30%

        Medium = 50%

        Hard = 20%

    Bloom:

        Remember = 20%

        Understand = 30%

        Apply = 30%

        Analyze = 20%

---

# Columns

| Column | Type | Nullable | Default | Description |
|--------|----------------------|----------|----------|-------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| exam_id | BIGINT UNSIGNED | No | — | Parent Exam |
| exam_section_id | BIGINT UNSIGNED | Yes | NULL | Optional Section |
| name | VARCHAR(255) | No | — | Blueprint Name |
| code | VARCHAR(50) | No | — | Blueprint Code |
| description | TEXT | Yes | NULL | Blueprint Description |
| blueprint_type | VARCHAR(30) | No | EXAM | Blueprint Scope |
| generation_strategy | VARCHAR(30) | No | CONSTRAINT_BASED | Generation Strategy |
| selection_mode | VARCHAR(20) | No | AUTO | Selection Mode |
| target_question_count | INTEGER UNSIGNED | No | 0 | Target Question Count |
| target_total_marks | DECIMAL(8,2) | No | 0.00 | Target Marks |
| min_question_count | INTEGER UNSIGNED | Yes | NULL | Minimum Questions |
| max_question_count | INTEGER UNSIGNED | Yes | NULL | Maximum Questions |
| randomization_enabled | BOOLEAN | No | TRUE | Enable Randomization |
| avoid_duplicates | BOOLEAN | No | TRUE | Avoid Duplicate Questions |
| allow_question_reuse | BOOLEAN | No | FALSE | Allow Reuse |
| enforce_topic_coverage | BOOLEAN | No | TRUE | Enforce Topic Coverage |
| enforce_difficulty_distribution | BOOLEAN | No | TRUE | Enforce Difficulty Distribution |
| enforce_bloom_distribution | BOOLEAN | No | FALSE | Enforce Bloom Distribution |
| tolerance_percentage | DECIMAL(5,2) | No | 0.00 | Distribution Tolerance |
| random_seed | BIGINT UNSIGNED | Yes | NULL | Generation Seed |
| algorithm_version | VARCHAR(50) | Yes | NULL | Generator Version |
| priority | SMALLINT UNSIGNED | No | 1 | Blueprint Priority |
| status | VARCHAR(30) | No | DRAFT | Blueprint Status |
| created_by | BIGINT UNSIGNED | No | — | Creator |
| updated_by | BIGINT UNSIGNED | Yes | NULL | Last Editor |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Creation Time |
| updated_at | TIMESTAMP | Yes | NULL | Last Update |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Primary Key

    id

Type:

    BIGINT UNSIGNED

---

# Public Identifier

    uuid

Type:

    CHAR(36)

Constraint:

    UNIQUE(uuid)

---

# Parent Exam

    exam_id

Every Blueprint belongs to exactly one Exam.

Foreign Key:

    exams.id

---

# Exam Section

    exam_section_id

Optional.

If NULL:

    Blueprint applies to the entire Exam.

If populated:

    Blueprint applies specifically to that Section.

Example:

    Exam
       ↓
    Section A
       ↓
    Blueprint A

---

# Blueprint Name

Example:

    HSC Physics Final Blueprint

or:

    Section A MCQ Blueprint

---

# Blueprint Code

Machine-readable identifier.

Examples:

    PHY-FINAL-2026

    PHY-FINAL-2026-MCQ

Recommended:

    UNIQUE(exam_id, code)

---

# Blueprint Type

Recommended values:

    EXAM

    SECTION

    CUSTOM

---

## EXAM

Blueprint applies to the entire Exam.

---

## SECTION

Blueprint applies to one specific Exam Section.

---

## CUSTOM

Reserved for future advanced generation strategies.

---

# Generation Strategy

Recommended values:

    CONSTRAINT_BASED

    RANDOM

    WEIGHTED_RANDOM

    RULE_BASED

    OPTIMIZATION

    AI_ASSISTED

    HYBRID

---

# CONSTRAINT_BASED

Select Questions that satisfy defined constraints.

Example:

    Subject = Physics

    Chapter = 1-5

    Difficulty = Medium

    Type = MCQ

---

# RANDOM

Randomly select eligible Questions.

---

# WEIGHTED_RANDOM

Random selection using weighted scoring.

Example:

    Easy = 30%

    Medium = 50%

    Hard = 20%

---

# RULE_BASED

Uses explicit Blueprint Rules.

---

# OPTIMIZATION

Attempts to find the best combination of Questions satisfying multiple constraints.

This is recommended for large, complex Exams.

---

# AI_ASSISTED

AI may assist with:

- Question ranking
- Similarity detection
- Difficulty estimation
- Topic matching
- Diversity scoring

AI must NOT bypass mandatory validation rules.

---

# HYBRID

Combines deterministic rules with AI-assisted ranking or optimization.

---

# Selection Mode

Recommended:

    AUTO

    MANUAL

    HYBRID

This should normally align with the parent Exam or Section configuration.

---

# Target Question Count

    target_question_count

Example:

    30

This is the desired number of Questions generated by the Blueprint.

---

# Target Total Marks

    target_total_marks

Example:

    30.00

The generation engine must validate:

    SUM(selected_question_marks)
        =
    target_total_marks

subject to configured tolerance or allowed variation.

---

# Minimum Question Count

    min_question_count

Defines the minimum acceptable number of generated Questions.

Example:

    target = 30

    minimum = 28

This can be useful for optimization-based generation.

However, for a formal fixed Exam, the recommended value is:

    min = target

---

# Maximum Question Count

    max_question_count

Defines the maximum acceptable number of Questions.

For a fixed Exam:

    max = target

---

# Randomization

    randomization_enabled

If TRUE:

    Candidate Questions may be randomized.

If FALSE:

    Deterministic ordering must be used.

---

# Duplicate Prevention

    avoid_duplicates

If TRUE:

The same Question Version must not appear more than once within the generated Exam.

---

# Question Reuse

    allow_question_reuse

Determines whether the Question may be reused.

Recommended default:

    FALSE

This primarily affects generation policy across Sections or Sets.

---

# Topic Coverage

    enforce_topic_coverage

If TRUE:

The generator must attempt to satisfy configured topic requirements.

---

# Difficulty Distribution

    enforce_difficulty_distribution

If TRUE:

Configured difficulty constraints are treated as generation requirements.

Example:

    Easy = 30%

    Medium = 50%

    Hard = 20%

---

# Bloom Distribution

    enforce_bloom_distribution

If TRUE:

Bloom's Taxonomy distribution must be considered.

Example:

    Remember = 20%

    Understand = 30%

    Apply = 30%

    Analyze = 20%

---

# Tolerance

    tolerance_percentage

Allows controlled deviation for soft constraints.

Example:

    target:

    Medium = 50%

    tolerance = 5%

Allowed range:

    45% – 55%

Important:

Tolerance must never override hard constraints such as:

    Institution

    Subject

    Question Type

    Question Version validity

---

# Random Seed

    random_seed

Stores the seed used for deterministic generation.

Example:

    845712

The seed should be recorded in the generation run as well.

This field provides the Blueprint's default seed.

---

# Algorithm Version

    algorithm_version

Example:

    v1.0.0

Purpose:

Generation behavior may change over time.

Recording the algorithm version makes historical generation reproducible and auditable.

---

# Priority

    priority

Used when multiple Blueprints apply.

Lower number may indicate higher priority.

Example:

    1 = Primary

    2 = Secondary

---

# Blueprint Status

Recommended:

    DRAFT

    READY

    ACTIVE

    INACTIVE

    ARCHIVED

---

# Status Lifecycle

    DRAFT
      ↓
    READY
      ↓
    ACTIVE
      ↓
    INACTIVE
      ↓
    ARCHIVED

---

# Foreign Keys

exam_id

    →

exams.id

ON DELETE CASCADE

ON UPDATE CASCADE

---

exam_section_id

    →

exam_sections.id

ON DELETE CASCADE

ON UPDATE CASCADE

---

created_by

    →

users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

updated_by

    →

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Indexes

Recommended:

    INDEX(exam_id)

    INDEX(exam_section_id)

    INDEX(status)

    INDEX(blueprint_type)

    INDEX(selection_mode)

    INDEX(generation_strategy)

    INDEX(priority)

    INDEX(exam_id, status)

    INDEX(exam_section_id, status)

---

# Unique Constraints

Recommended:

    UNIQUE(uuid)

    UNIQUE(exam_id, code)

---

# Relationships

Exam

    HasMany

    ExamBlueprint

---

ExamSection

    HasMany

    ExamBlueprint

---

User

    HasMany

    ExamBlueprint

through:

    created_by

---

ExamBlueprint

    BelongsTo

    Exam

---

ExamBlueprint

    BelongsTo

    ExamSection

---

ExamBlueprint

    HasMany

    ExamBlueprintRule

---

# Laravel Model

ExamBlueprint

---

# Migration

create_exam_blueprints_table

---

# Factory

ExamBlueprintFactory

---

# Seeder

ExamBlueprintSeeder

---

# Laravel Relationships

## Exam Model

    public function blueprints()
    {
        return $this->hasMany(
            ExamBlueprint::class
        );
    }

---

## ExamSection Model

    public function blueprints()
    {
        return $this->hasMany(
            ExamBlueprint::class
        );
    }

---

## ExamBlueprint Model

    public function exam()
    {
        return $this->belongsTo(
            Exam::class
        );
    }

    public function section()
    {
        return $this->belongsTo(
            ExamSection::class,
            'exam_section_id'
        );
    }

    public function rules()
    {
        return $this->hasMany(
            ExamBlueprintRule::class
        );
    }
```

---

# Validation Rules

```text
exam_id

required

exists:exams,id


exam_section_id

nullable

exists:exam_sections,id


name

required

string

max:255


code

required

string

max:50


blueprint_type

required

in:
EXAM,
SECTION,
CUSTOM


generation_strategy

required

in:
CONSTRAINT_BASED,
RANDOM,
WEIGHTED_RANDOM,
RULE_BASED,
OPTIMIZATION,
AI_ASSISTED,
HYBRID


selection_mode

required

in:
AUTO,
MANUAL,
HYBRID


target_question_count

required

integer

min:1


target_total_marks

required

numeric

min:0


min_question_count

nullable

integer

min:1


max_question_count

nullable

integer

min:1


randomization_enabled

boolean


avoid_duplicates

boolean


allow_question_reuse

boolean


enforce_topic_coverage

boolean


enforce_difficulty_distribution

boolean


enforce_bloom_distribution

boolean


tolerance_percentage

required

numeric

min:0

max:100


random_seed

nullable

integer


algorithm_version

nullable

string

max:50


priority

required

integer

min:1


status

required

in:
DRAFT,
READY,
ACTIVE,
INACTIVE,
ARCHIVED
```

---

# Business Rules

1. Every Blueprint belongs to exactly one Exam.

2. A Section Blueprint must reference a valid Exam Section.

3. An Exam Blueprint must not reference a Section belonging to another Exam.

4. `target_question_count` must be greater than zero.

5. `target_total_marks` cannot be negative.

6. `min_question_count` cannot exceed `max_question_count`.

7. `min_question_count` cannot exceed `target_question_count` for a fixed-count Blueprint.

8. `max_question_count` must not be less than `target_question_count` for a fixed-count Blueprint.

9. Blueprint Rules must be valid before Blueprint activation.

10. An inactive Blueprint cannot be used for new generation.

11. A Blueprint used by a published Exam must remain historically identifiable.

12. Changing a Blueprint after Exam generation must not change already generated Exam Questions.

13. Published Exam generation must use a specific Blueprint version or immutable Blueprint snapshot.

14. AI assistance must never bypass hard eligibility constraints.

---

# Exam-Level Blueprint

Example:

```text
Exam:

HSC Physics Final 2026

Blueprint:

Total Questions = 50

Total Marks = 100

Difficulty:

Easy = 15
Medium = 25
Hard = 10
```

Database:

```text
blueprint_type = EXAM

exam_section_id = NULL

target_question_count = 50

target_total_marks = 100
```

---

# Section-Level Blueprint

Example:

```text
Section A

MCQ

30 Questions

30 Marks
```

Database:

```text
blueprint_type = SECTION

exam_section_id = Section A

target_question_count = 30

target_total_marks = 30
```

---

# Blueprint Hierarchy

A complex Exam may use:

```text
Exam Blueprint
      ↓
Section Blueprint
      ↓
Blueprint Rules
      ↓
Topic Rules
      ↓
Question Candidate Pool
```

---

# Example

```text
Exam
│
├── Blueprint
│   │
│   ├── Rule: MCQ
│   ├── Rule: Easy 30%
│   ├── Rule: Medium 50%
│   └── Rule: Hard 20%
│
├── Section A
│   │
│   └── Blueprint
│       │
│       ├── Chapter 1
│       ├── Chapter 2
│       └── Chapter 3
│
└── Section B
    │
    └── Blueprint
        │
        ├── Chapter 4
        ├── Chapter 5
        └── Chapter 6
```

---

# Constraint Types

Blueprint-level configuration may control:

```
Question Count

Total Marks

Difficulty

Bloom Level

Question Type

Topic

Chapter

Language

Source

Tag

Institution

Question Age

Usage Frequency

Duplicate Similarity

Approval Status
```

Detailed constraints belong in:

```
exam_blueprint_rules
```

---

# Hard vs Soft Constraints

The system must distinguish between:

## Hard Constraints

Must be satisfied.

Examples:

```
Institution = Current Institution

Subject = Physics

Question Status = Approved

Question Version = Published/Approved

Question Type = MCQ

Required Marks
```

## Soft Constraints

May be optimized.

Examples:

```
Difficulty Distribution

Bloom Distribution

Topic Balance

Diversity Score

Historical Usage
```

---

# Generation Strategy Selection

Recommended logic:

```text
If constraints are simple:
    CONSTRAINT_BASED

If purely random:
    RANDOM

If probability-weighted:
    WEIGHTED_RANDOM

If explicit rules:
    RULE_BASED

If many conflicting constraints:
    OPTIMIZATION

If AI ranking is required:
    AI_ASSISTED

If rules + AI:
    HYBRID
```

---

# AI-Assisted Generation

AI may be used to calculate:

```
semantic relevance

difficulty prediction

topic similarity

question diversity

duplicate probability

quality score
```

However:

```text
AI Score
    ≠
Eligibility
```

Eligibility must first be determined by deterministic database rules.

---

# Candidate Selection Pipeline

Recommended:

```text
Blueprint
    ↓
Load Rules
    ↓
Build Candidate Pool
    ↓
Apply Hard Constraints
    ↓
Remove Invalid Versions
    ↓
Remove Duplicates
    ↓
Calculate Scores
    ↓
Apply Soft Constraints
    ↓
Optimize Selection
    ↓
Validate Result
    ↓
Persist Exam Questions
```

---

# Insufficient Question Pool

Example:

Blueprint requires:

```text
30 Medium MCQ
```

Question Bank contains:

```text
18 valid Medium MCQ
```

The system must NOT silently select 18 and mark the Exam valid.

Instead:

```text
GENERATION_FAILED
```

Reason:

```text
INSUFFICIENT_ELIGIBLE_QUESTIONS
```

The Generation Run must record the failure.

---

# Tolerance Example

Target:

```text
Difficulty:

Easy = 30%
Medium = 50%
Hard = 20%
```

Tolerance:

```text
5%
```

Allowed:

```text
Easy:
25% – 35%

Medium:
45% – 55%

Hard:
15% – 25%
```

The exact interpretation of tolerance must be defined by the Generation Service.

---

# Blueprint Versioning

Blueprint configuration can change.

Example:

```text
Blueprint V1

30% Easy
50% Medium
20% Hard
```

Later:

```text
Blueprint V2

20% Easy
60% Medium
20% Hard
```

Previously generated Exams must continue referencing V1 configuration.

Therefore future architecture should support:

```
exam_blueprint_versions
```

```

This table is intentionally reserved for the next version of the database design.

---

# Recommended Immediate Implementation

For the initial Laravel implementation:

    exam_blueprints

stores the current Blueprint configuration.

When an Exam enters:

    GENERATING

the system must create an immutable Generation Snapshot containing the Blueprint configuration used for that generation.

That snapshot will be associated with:

    exam_generation_runs
```

---

# Random Seed Rules

If:

```
randomization_enabled = TRUE
```

the Generation Service should create a random seed if one is not supplied.

Example:

```text
random_seed = 845712
```

The seed must be stored in:

```
exam_generation_runs
```

even if it is also stored in the Blueprint.

---

# Algorithm Version Rules

Example:

```text
algorithm_version = v1.2.0
```

If the generation algorithm changes:

```text
v1.2.0
    ↓
v1.3.0
```

old generation records remain associated with:

```text
v1.2.0
```

This allows historical reproduction and debugging.

---

# Security

Recommended permissions:

```
exam.blueprint.view

exam.blueprint.create

exam.blueprint.update

exam.blueprint.delete

exam.blueprint.activate

exam.blueprint.generate
```

````

---

# Role Access

| Role | View | Create | Update | Activate |
|------|------|--------|--------|----------|
| Super Admin | Yes | Yes | Yes | Yes |
| School Admin | Yes | Yes | Yes | Yes |
| Principal | Yes | Yes | Yes | Yes |
| Moderator | Yes | No | Review | No |
| Teacher | Yes | Yes | Yes | Configurable |
| Question Setter | Yes | Yes | Own | No |

---

# Blueprint Validation Before Activation

Before:

```text
status = ACTIVE
````

the system must validate:

```text
1. Exam exists

2. Section exists if required

3. Section belongs to Exam

4. Target Question Count > 0

5. Target Marks >= 0

6. Rules exist

7. Rules are internally consistent

8. Question Type exists if required

9. Topic constraints are valid

10. Difficulty constraints are valid

11. Bloom constraints are valid

12. Required Question Pool is potentially sufficient
```

---

# Example Blueprint

```text
Name:

HSC Physics Final 2026 - MCQ Blueprint

Code:

PHY-HSC-FINAL-2026-MCQ

Type:

SECTION

Strategy:

OPTIMIZATION

Mode:

AUTO

Questions:

30

Marks:

30

Randomization:

TRUE

Duplicate Prevention:

TRUE

Topic Coverage:

TRUE

Difficulty Distribution:

TRUE

Bloom Distribution:

TRUE

Tolerance:

5%

Status:

ACTIVE
```

---

# Data Integrity

The following must hold:

```text
exam_blueprints.exam_id
    =
exams.id
```

If:

```text
exam_section_id IS NOT NULL
```

then:

```text
exam_sections.exam_id
    =
exam_blueprints.exam_id
```

This cross-table consistency must be enforced through application/service validation.

---

# Relationship to Exam Generation

```text
Exam
 ↓
Blueprint
 ↓
Blueprint Rules
 ↓
Candidate Pool
 ↓
Generation Run
 ↓
Generation Result
 ↓
Exam Questions
```

---

# Relationship to Question Bank

The Blueprint must NOT directly store:

```
question_id
```

because a Blueprint describes requirements rather than final Question selections.

Actual Question selection belongs to:

```
exam_generation_results
```

and eventually:

```
exam_questions
```

````

---

# Critical Architectural Rule

Never modify a Blueprint in a way that silently changes an already-generated Exam.

Existing Exam:

```text
Generated using Blueprint A
````

Later:

```text
Blueprint A updated
```

must NOT cause:

```text
Existing Exam Questions
```

to change.

---

# Recommended Service Layer

Blueprint business logic should be handled by:

```
ExamBlueprintService

BlueprintValidationService

BlueprintRuleService

ExamGenerationService
```

---

# Recommended Events

```
BlueprintCreated

BlueprintUpdated

BlueprintValidated

BlueprintActivated

BlueprintDeactivated

BlueprintArchived
```

---

# Recommended Exceptions

Possible domain exceptions:

```
InvalidBlueprintException

BlueprintRuleConflictException

InsufficientQuestionPoolException

InvalidQuestionDistributionException

BlueprintNotActiveException
```

---

# Final Architecture

```text
Exam
 │
 ├── Exam Sections
 │
 └── Exam Blueprint
       │
       ├── Target Count
       ├── Target Marks
       ├── Generation Strategy
       ├── Difficulty Policy
       ├── Bloom Policy
       ├── Topic Coverage
       ├── Randomization
       └── Blueprint Rules
                │
                ↓
         Candidate Selection
                │
                ↓
         Exam Generation
                │
                ↓
          Exam Questions
```

---

# End of Section-3
এখন `DATABASE_DESIGN.md → Part-5 → Section-4: exam_blueprint_rules`। এটি Part-5-এর সবচেয়ে গুরুত্বপূর্ণ অংশগুলোর একটি, কারণ `exam_blueprints` যেখানে high-level generation specification রাখে, `exam_blueprint_rules` সেখানে সেই specification-কে machine-executable constraints-এ ভেঙে দেয়।

````markdown id="41857"
# DATABASE_DESIGN.md

Version: 2.0

Part-5

Section-4

# Table : exam_blueprint_rules

---

## Purpose

Stores the individual rules and constraints used by an Exam Blueprint.

An `exam_blueprint` defines:

    WHAT kind of Exam should be generated.

An `exam_blueprint_rule` defines:

    HOW the Question selection must satisfy that requirement.

Examples:

    30% Easy Questions

    50% Medium Questions

    20% Hard Questions

    10 Questions from Chapter 1

    8 MCQ Questions

    5 Questions from Topic "Newton's Laws"

    20 Questions carrying 1 mark each

---

# Table Name

exam_blueprint_rules

---

# Primary Key

id

---

# Description

Each row represents one independent generation constraint.

A Blueprint can contain many Rules.

Example:

```text
Blueprint
    |
    +-- Rule 1: Question Type = MCQ
    |
    +-- Rule 2: Difficulty = EASY
    |
    +-- Rule 3: Chapter = 1
    |
    +-- Rule 4: Count = 10
    |
    +-- Rule 5: Marks = 1
````

The Generation Engine evaluates these Rules when constructing the candidate pool and selecting Questions.

---

# Design Principle

Rules are intentionally stored as normalized metadata rather than creating a separate database column for every possible constraint.

This allows the system to support future rule types without repeatedly changing the core Blueprint table.

---

# Columns

| Column               | Type              | Nullable | Default           | Description                   |
| -------------------- | ----------------- | -------- | ----------------- | ----------------------------- |
| id                   | BIGINT UNSIGNED   | No       | AUTO_INCREMENT    | Primary Key                   |
| uuid                 | CHAR(36)          | No       | UUID              | Public Identifier             |
| exam_blueprint_id    | BIGINT UNSIGNED   | No       | —                 | Parent Blueprint              |
| parent_rule_id       | BIGINT UNSIGNED   | Yes      | NULL              | Parent Rule                   |
| rule_code            | VARCHAR(50)       | No       | —                 | Unique Rule Identifier        |
| rule_type            | VARCHAR(40)       | No       | —                 | Rule Type                     |
| target_type          | VARCHAR(40)       | Yes      | NULL              | Target Entity Type            |
| target_id            | BIGINT UNSIGNED   | Yes      | NULL              | Target Entity ID              |
| operator             | VARCHAR(20)       | No       | EQUALS            | Comparison Operator           |
| value                | VARCHAR(255)      | Yes      | NULL              | Primary Rule Value            |
| value_min            | DECIMAL(10,2)     | Yes      | NULL              | Minimum Value                 |
| value_max            | DECIMAL(10,2)     | Yes      | NULL              | Maximum Value                 |
| percentage           | DECIMAL(5,2)      | Yes      | NULL              | Percentage Constraint         |
| required_count       | INTEGER UNSIGNED  | Yes      | NULL              | Required Count                |
| minimum_count        | INTEGER UNSIGNED  | Yes      | NULL              | Minimum Count                 |
| maximum_count        | INTEGER UNSIGNED  | Yes      | NULL              | Maximum Count                 |
| marks                | DECIMAL(8,2)      | Yes      | NULL              | Marks Constraint              |
| constraint_mode      | VARCHAR(10)       | No       | HARD              | Hard/Soft Constraint          |
| priority             | SMALLINT UNSIGNED | No       | 1                 | Evaluation Priority           |
| weight               | DECIMAL(8,4)      | No       | 1.0000            | Soft Constraint Weight        |
| tolerance_percentage | DECIMAL(5,2)      | No       | 0.00              | Allowed Tolerance             |
| is_active            | BOOLEAN           | No       | TRUE              | Active Rule                   |
| sequence             | SMALLINT UNSIGNED | No       | 1                 | Evaluation Sequence           |
| metadata             | JSON              | Yes      | NULL              | Additional Rule Configuration |
| created_by           | BIGINT UNSIGNED   | No       | —                 | Creator                       |
| updated_by           | BIGINT UNSIGNED   | Yes      | NULL              | Last Editor                   |
| created_at           | TIMESTAMP         | No       | CURRENT_TIMESTAMP | Creation Time                 |
| updated_at           | TIMESTAMP         | Yes      | NULL              | Update Time                   |
| deleted_at           | TIMESTAMP         | Yes      | NULL              | Soft Delete                   |

---

# Primary Key

```
id
```

Type:

```
BIGINT UNSIGNED
```

---

# Public Identifier

```
uuid
```

Constraint:

```
UNIQUE(uuid)
```

---

# Parent Blueprint

```
exam_blueprint_id
```

Every Rule belongs to exactly one Blueprint.

Foreign Key:

```
exam_blueprints.id
```

---

# Parent Rule

```
parent_rule_id
```

Optional self-referencing foreign key.

Allows hierarchical Rules.

Example:

```text
Difficulty Distribution
    |
    +-- Easy
    |
    +-- Medium
    |
    +-- Hard
```

Or:

```text
Chapter Coverage
    |
    +-- Chapter 1
    |     |
    |     +-- Easy
    |
    +-- Chapter 2
          |
          +-- Medium
```

This enables complex rule trees without requiring a new table for every hierarchy level.

---

# Rule Code

```
rule_code
```

Human/machine-readable identifier.

Examples:

```
DIFF-EASY-001

TYPE-MCQ-001

CHAPTER-01-001

BLOOM-APPLY-001
```

Recommended:

```
UNIQUE(exam_blueprint_id, rule_code)
```

---

# Rule Type

Defines the semantic meaning of the Rule.

Recommended values:

```text
QUESTION_TYPE

DIFFICULTY

BLOOM_LEVEL

CHAPTER

TOPIC

SUBTOPIC

MARKS

QUESTION_COUNT

PERCENTAGE

LANGUAGE

SOURCE

TAG

CURRICULUM

CLASS

SUBJECT

APPROVAL_STATUS

USAGE_LIMIT

DUPLICATE_SIMILARITY

DATE_RANGE

CUSTOM
```

---

# QUESTION_TYPE

Example:

```text
rule_type = QUESTION_TYPE

value = MCQ
```

or:

```text
target_type = question_type

target_id = 1
```

The preferred implementation is to use a foreign entity ID where a normalized lookup table exists.

---

# DIFFICULTY

Example:

```text
rule_type = DIFFICULTY

value = MEDIUM

required_count = 20
```

or:

```text
percentage = 50
```

---

# BLOOM_LEVEL

Example:

```text
rule_type = BLOOM_LEVEL

value = APPLY

percentage = 30
```

---

# CHAPTER

Example:

```text
rule_type = CHAPTER

target_type = chapter

target_id = 5

required_count = 5
```

This is preferred over storing the chapter name in `value`.

---

# TOPIC

Example:

```text
rule_type = TOPIC

target_type = topic

target_id = 21

required_count = 3
```

---

# SUBTOPIC

Same concept as Topic but at a lower hierarchy level.

---

# MARKS

Defines Question mark requirements.

Example:

```text
rule_type = MARKS

marks = 1

required_count = 20
```

Meaning:

```
20 Questions
each carrying 1 mark.
```

---

# QUESTION_COUNT

Explicit count requirement.

Example:

```text
rule_type = QUESTION_COUNT

required_count = 10
```

---

# PERCENTAGE

Used for distribution requirements.

Example:

```text
rule_type = DIFFICULTY

value = EASY

percentage = 30
```

---

# LANGUAGE

Example:

```text
rule_type = LANGUAGE

value = bn

required_count = 10
```

---

# SOURCE

Can restrict Questions to a specific source.

Examples:

```
TEXTBOOK

QUESTION_BANK

TEACHER_CREATED

IMPORTED
```

---

# TAG

Example:

```text
rule_type = TAG

value = IMPORTANT

required_count = 5
```

---

# CURRICULUM

Restricts Questions to a Curriculum.

---

# CLASS

Restricts Questions to a specific Class/Grade.

---

# SUBJECT

Restricts Questions to a specific Subject.

---

# APPROVAL_STATUS

Ensures only Questions in an approved state are eligible.

Example:

```text
rule_type = APPROVAL_STATUS

value = APPROVED
```

---

# USAGE_LIMIT

Controls how frequently a Question may be reused.

Example:

```text
rule_type = USAGE_LIMIT

operator = LESS_THAN

value = 3
```

Meaning:

```
Question usage count < 3
```

---

# DUPLICATE_SIMILARITY

Used to control semantic similarity.

Example:

```text
rule_type = DUPLICATE_SIMILARITY

value_max = 0.80
```

Meaning:

```
Similarity score must not exceed 0.80.
```

This may be evaluated by the Question Generation/Quality Engine.

---

# DATE_RANGE

Restricts Questions by creation/publication date.

Example:

```text
rule_type = DATE_RANGE

metadata = {
    "from": "2025-01-01",
    "to": "2026-12-31"
}
```

---

# CUSTOM

Reserved for future extensibility.

Custom Rules must have a well-defined schema in:

```
metadata
```

and must be validated by the application layer.

---

# Target Type

```
target_type
```

Defines the entity being targeted.

Examples:

```
question_type

difficulty

bloom_level

chapter

topic

subject

class

curriculum

tag
```

---

# Target ID

```
target_id
```

Stores the ID of the target entity where applicable.

Example:

```text
target_type = chapter

target_id = 5
```

This means:

```
Chapter #5
```

---

# Why target_id and value both exist

Not every Rule references a database entity.

Entity-based Rule:

```text
rule_type = CHAPTER

target_type = chapter

target_id = 5
```

Enum/value-based Rule:

```text
rule_type = DIFFICULTY

value = HARD
```

Therefore both representations are supported.

---

# Operator

Recommended values:

```text
EQUALS

NOT_EQUALS

IN

NOT_IN

GREATER_THAN

GREATER_THAN_OR_EQUAL

LESS_THAN

LESS_THAN_OR_EQUAL

BETWEEN

CONTAINS

NOT_CONTAINS
```

---

# Examples

```text
operator = EQUALS

value = MCQ
```

```text
operator = GREATER_THAN

value = 2
```

```text
operator = BETWEEN

value_min = 1

value_max = 5
```

---

# Value

Primary scalar value.

Examples:

```
EASY

MCQ

bn

APPROVED

TEXTBOOK
```

---

# Value Min

Used for range constraints.

Example:

```text
value_min = 0.60
```

---

# Value Max

Used for range constraints.

Example:

```text
value_max = 0.80
```

---

# Percentage

Defines proportional distribution.

Example:

```text
percentage = 30.00
```

Meaning:

```
30% of generated Questions.
```

---

# Required Count

Exact required number.

Example:

```text
required_count = 10
```

Meaning:

```
Exactly 10 Questions must satisfy the Rule.
```

---

# Minimum Count

Minimum acceptable count.

Example:

```text
minimum_count = 8
```

Meaning:

```
At least 8 Questions.
```

---

# Maximum Count

Maximum acceptable count.

Example:

```text
maximum_count = 12
```

Meaning:

```
No more than 12 Questions.
```

---

# Marks

Used for marks-related constraints.

Example:

```text
marks = 2.00
```

Meaning:

```
Question carries 2 marks.
```

---

# Constraint Mode

Two primary modes:

```
HARD

SOFT
```

---

# HARD Constraint

Must be satisfied.

Example:

```text
Question Type = MCQ
```

If there are no eligible MCQ Questions:

```
Generation must fail.
```

The system must not silently violate the constraint.

---

# SOFT Constraint

Used for optimization.

Example:

```text
Difficulty:

Easy = 30%

Tolerance = 5%

Mode = SOFT
```

The engine attempts to satisfy it but may accept a controlled deviation if necessary.

---

# Priority

Defines evaluation order.

Example:

```text
1 = Institution

2 = Subject

3 = Question Type

4 = Approval

5 = Chapter

6 = Difficulty

7 = Bloom
```

Lower number means higher priority.

---

# Weight

Used for soft constraint optimization.

Example:

```text
weight = 10.0
```

Higher weight:

```
greater importance during optimization.
```

Example:

```text
Difficulty = weight 10

Bloom = weight 5

Topic diversity = weight 3
```

---

# Tolerance Percentage

Allows controlled deviation for soft constraints.

Example:

```text
Target = 50%

Tolerance = 5%
```

Allowed:

```text
45% - 55%
```

---

# Active Flag

```
is_active
```

Inactive Rules are ignored by the Generation Engine.

Recommended:

```
TRUE
```

---

# Sequence

Defines Rule processing order.

Example:

```text
1
2
3
4
```

Sequence and priority are different:

```
sequence
    =
processing order

priority
    =
relative importance
```

---

# Metadata

```
metadata
```

JSON field for additional configuration.

Example:

```json
{
    "chapter_ids": [1, 2, 3],
    "exclude_topic_ids": [10, 11],
    "difficulty_weights": {
        "easy": 0.30,
        "medium": 0.50,
        "hard": 0.20
    }
}
```

Metadata must NOT be used to bypass normalized fields when a first-class field already exists.

---

# Foreign Keys

exam_blueprint_id

```
→
```

exam_blueprints.id

ON DELETE CASCADE

ON UPDATE CASCADE

---

parent_rule_id

```
→
```

exam_blueprint_rules.id

ON DELETE CASCADE

ON UPDATE CASCADE

---

created_by

```
→
```

users.id

ON DELETE RESTRICT

ON UPDATE CASCADE

---

updated_by

```
→
```

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Target Entity References

`target_id` is polymorphic in concept.

Therefore a traditional foreign key cannot safely reference multiple tables.

Example:

```text
target_type = chapter
target_id = 5
```

or:

```text
target_type = topic
target_id = 5
```

The application layer must validate that:

```
target_type
    +
target_id
```

points to a valid entity.

---

# Indexes

Recommended:

```text
INDEX(exam_blueprint_id)

INDEX(parent_rule_id)

INDEX(rule_type)

INDEX(target_type, target_id)

INDEX(constraint_mode)

INDEX(priority)

INDEX(sequence)

INDEX(is_active)

INDEX(exam_blueprint_id, is_active)

INDEX(exam_blueprint_id, rule_type)

INDEX(exam_blueprint_id, priority)
```

---

# Unique Constraints

Recommended:

```text
UNIQUE(uuid)

UNIQUE(exam_blueprint_id, rule_code)
```

---

# Relationships

ExamBlueprint

```
HasMany

ExamBlueprintRule
```

---

ExamBlueprintRule

```
BelongsTo

ExamBlueprint
```

---

ExamBlueprintRule

```
BelongsTo

ParentRule
```

---

ExamBlueprintRule

```
HasMany

ChildRules
```

---

User

```
HasMany

ExamBlueprintRule
```

through:

```
created_by
```

---

# Laravel Model

ExamBlueprintRule

---

# Migration

create_exam_blueprint_rules_table

---

# Factory

ExamBlueprintRuleFactory

---

# Seeder

ExamBlueprintRuleSeeder

---

# Laravel Relationships

## ExamBlueprint Model

```php
public function rules()
{
    return $this->hasMany(
        ExamBlueprintRule::class
    );
}
```

---

## ExamBlueprintRule Model

```php
public function blueprint()
{
    return $this->belongsTo(
        ExamBlueprint::class,
        'exam_blueprint_id'
    );
}

public function parent()
{
    return $this->belongsTo(
        ExamBlueprintRule::class,
        'parent_rule_id'
    );
}

public function children()
{
    return $this->hasMany(
        ExamBlueprintRule::class,
        'parent_rule_id'
    );
}
```

---

# Validation Rules

```text
exam_blueprint_id

required

exists:exam_blueprints,id


rule_code

required

string

max:50


rule_type

required

in:
QUESTION_TYPE,
DIFFICULTY,
BLOOM_LEVEL,
CHAPTER,
TOPIC,
SUBTOPIC,
MARKS,
QUESTION_COUNT,
PERCENTAGE,
LANGUAGE,
SOURCE,
TAG,
CURRICULUM,
CLASS,
SUBJECT,
APPROVAL_STATUS,
USAGE_LIMIT,
DUPLICATE_SIMILARITY,
DATE_RANGE,
CUSTOM


target_type

nullable

string

max:40


target_id

nullable

integer

min:1


operator

required

in:
EQUALS,
NOT_EQUALS,
IN,
NOT_IN,
GREATER_THAN,
GREATER_THAN_OR_EQUAL,
LESS_THAN,
LESS_THAN_OR_EQUAL,
BETWEEN,
CONTAINS,
NOT_CONTAINS


value

nullable

string

max:255


value_min

nullable

numeric


value_max

nullable

numeric


percentage

nullable

numeric

min:0

max:100


required_count

nullable

integer

min:0


minimum_count

nullable

integer

min:0


maximum_count

nullable

integer

min:0


marks

nullable

numeric

min:0


constraint_mode

required

in:HARD,SOFT


priority

required

integer

min:1


weight

required

numeric

min:0


tolerance_percentage

required

numeric

min:0

max:100


is_active

boolean


sequence

required

integer

min:1


metadata

nullable

array
```

---

# Rule Validation Matrix

Not every field is valid for every Rule Type.

Example:

| Rule Type            | Main Field        |
| -------------------- | ----------------- |
| QUESTION_TYPE        | target_id / value |
| DIFFICULTY           | value             |
| BLOOM_LEVEL          | value             |
| CHAPTER              | target_id         |
| TOPIC                | target_id         |
| SUBTOPIC             | target_id         |
| MARKS                | marks             |
| QUESTION_COUNT       | required_count    |
| PERCENTAGE           | percentage        |
| LANGUAGE             | value             |
| SOURCE               | value             |
| TAG                  | value             |
| APPROVAL_STATUS      | value             |
| USAGE_LIMIT          | value             |
| DUPLICATE_SIMILARITY | value_max         |

The application must enforce these semantic rules.

---

# Business Rules

1. Every Rule belongs to exactly one Blueprint.

2. A Rule Code must be unique within a Blueprint.

3. Inactive Rules must not participate in generation.

4. A HARD Rule must be satisfied.

5. A SOFT Rule may be relaxed within configured tolerance.

6. `minimum_count` cannot exceed `maximum_count`.

7. `required_count` cannot conflict with `minimum_count` or `maximum_count`.

8. Percentage must be between 0 and 100.

9. Tolerance must be between 0 and 100.

10. Weight cannot be negative.

11. Rule Type must determine which fields are semantically valid.

12. Entity-based Rules must reference valid entities.

13. A Section-specific Blueprint must not contain rules targeting a different Exam's entities.

14. Child Rules must belong to the same Blueprint as their Parent Rule.

15. Circular parent-child Rule relationships are forbidden.

---

# Rule Hierarchy

Example:

```text
DIFFICULTY_DISTRIBUTION
│
├── EASY
│     required_count = 10
│
├── MEDIUM
│     required_count = 15
│
└── HARD
      required_count = 5
```

Database:

```text
Parent Rule
    ↓
Child Rule
```

---

# Example 1 — MCQ Rule

```text
rule_type:

QUESTION_TYPE

value:

MCQ

constraint_mode:

HARD

required_count:

30
```

Meaning:

```
Exactly 30 MCQ Questions.
```

---

# Example 2 — Difficulty Rule

```text
rule_type:

DIFFICULTY

value:

EASY

percentage:

30

constraint_mode:

SOFT

tolerance_percentage:

5
```

Meaning:

```
Target Easy Questions = 30%

Allowed range = 25%-35%
```

---

# Example 3 — Chapter Rule

```text
rule_type:

CHAPTER

target_type:

chapter

target_id:

5

required_count:

5

constraint_mode:

HARD
```

Meaning:

```
Exactly 5 Questions from Chapter 5.
```

---

# Example 4 — Topic Rule

```text
rule_type:

TOPIC

target_type:

topic

target_id:

21

minimum_count:

3

maximum_count:

5

constraint_mode:

HARD
```

Meaning:

```
At least 3 and at most 5 Questions
from Topic 21.
```

---

# Example 5 — Marks Rule

```text
rule_type:

MARKS

marks:

2

required_count:

10

constraint_mode:

HARD
```

Meaning:

```
10 Questions worth 2 marks each.
```

---

# Example 6 — Approval Rule

```text
rule_type:

APPROVAL_STATUS

value:

APPROVED

constraint_mode:

HARD
```

Meaning:

```
Only approved Questions are eligible.
```

---

# Example 7 — Usage Rule

```text
rule_type:

USAGE_LIMIT

operator:

LESS_THAN

value:

3

constraint_mode:

SOFT
```

Meaning:

```
Prefer Questions used fewer than 3 times.
```

---

# Example 8 — Similarity Rule

```text
rule_type:

DUPLICATE_SIMILARITY

operator:

LESS_THAN_OR_EQUAL

value_max:

0.80

constraint_mode:

HARD
```

Meaning:

```
Similarity must not exceed 0.80.
```

The actual similarity engine may use embeddings or another semantic comparison method.

---

# Multiple Rules

Example:

```text
Blueprint:

30 Questions

Rules:

1.
Subject = Physics
HARD

2.
Question Type = MCQ
HARD

3.
Approval Status = APPROVED
HARD

4.
Easy = 30%
SOFT

5.
Medium = 50%
SOFT

6.
Hard = 20%
SOFT

7.
Chapter 1 = 5 Questions
HARD

8.
Chapter 2 = 5 Questions
HARD

9.
Chapter 3 = 5 Questions
HARD
```

---

# Rule Evaluation Pipeline

Recommended order:

```text
1. Institution

2. Subject

3. Curriculum

4. Class

5. Approval Status

6. Question Type

7. Question Version Validity

8. Marks

9. Chapter / Topic

10. Difficulty

11. Bloom Level

12. Usage

13. Similarity

14. Diversity

15. Randomization
```

This prevents expensive operations from being performed on obviously invalid candidates.

---

# Hard Constraint Processing

```text
Candidate Pool
    ↓
Apply HARD Rules
    ↓
Eligible Candidates
```

If:

```text
Eligible Candidates < Required Count
```

then:

```text
Generation Failure
```

must be returned.

The system must NOT silently weaken a HARD Rule.

---

# Soft Constraint Processing

```text
Eligible Candidates
    ↓
Calculate Candidate Scores
    ↓
Apply SOFT Rules
    ↓
Optimization
    ↓
Best Candidate Set
```

---

# Rule Conflict

Example:

```text
Rule 1:

Chapter 1
10 Questions

Rule 2:

Easy
80%

Rule 3:

Hard
80%
```

These rules may be mathematically impossible.

The Blueprint Validator must detect obvious conflicts before generation.

Recommended error:

```text
BLUEPRINT_RULE_CONFLICT
```

---

# Rule Feasibility

Before generation, the system should estimate:

```text
Required Question Count
        vs
Eligible Question Pool
```

Example:

```text
Required:

30 MCQ

Eligible:

18 MCQ
```

Result:

```text
INFEASIBLE
```

---

# Candidate Pool

The Generation Engine should conceptually construct:

```text
Candidate Pool
```

using HARD Rules.

Example:

```text
All Questions
    ↓
Institution Filter
    ↓
Subject Filter
    ↓
Chapter Filter
    ↓
Question Type Filter
    ↓
Approval Filter
    ↓
Marks Filter
    ↓
Eligible Candidate Pool
```

Soft constraints are evaluated afterward.

---

# Rule Snapshot

When an Exam is generated, the active Rules should be copied into the Generation Run snapshot.

This is required for reproducibility.

Future table:

```
exam_generation_runs
```

should store:

```text
blueprint configuration snapshot

rule configuration snapshot

algorithm version

random seed

generation timestamp
```

---

# Immutability After Generation

Changing:

```text
exam_blueprint_rules
```

must NOT change:

```text
exam_questions
```

that were already generated.

Existing generated Exams use their generation snapshot.

---

# Security

Recommended permissions:

```text
exam.blueprint_rule.view

exam.blueprint_rule.create

exam.blueprint_rule.update

exam.blueprint_rule.delete
```

---

# Role Access

| Role            | View | Create | Update | Delete       |
| --------------- | ---- | ------ | ------ | ------------ |
| Super Admin     | Yes  | Yes    | Yes    | Yes          |
| School Admin    | Yes  | Yes    | Yes    | Yes          |
| Principal       | Yes  | Yes    | Yes    | Configurable |
| Moderator       | Yes  | No     | Review | No           |
| Teacher         | Yes  | Yes    | Yes    | Configurable |
| Question Setter | Yes  | Yes    | Own    | Own          |

````

---

# Recommended Service Layer

Rule processing should NOT be implemented inside the Controller.

Recommended services:

```text
BlueprintRuleService

BlueprintRuleValidationService

BlueprintFeasibilityService

QuestionCandidatePoolService

QuestionConstraintEngine
````

---

# Recommended Domain Components

The future Generation Engine can be divided into:

```text
RuleParser
RuleValidator
RuleCompiler
CandidatePoolBuilder
HardConstraintFilter
SoftConstraintScorer
QuestionOptimizer
DuplicateDetector
DistributionValidator
GenerationResultValidator
```

---

# Recommended Exceptions

```text
InvalidBlueprintRuleException

InvalidRuleTargetException

BlueprintRuleConflictException

InvalidRuleOperatorException

RuleEvaluationException

InsufficientCandidatesException

GenerationConstraintViolationException
```

---

# Recommended Events

```text
BlueprintRuleCreated

BlueprintRuleUpdated

BlueprintRuleDeleted

BlueprintRuleActivated

BlueprintRuleDeactivated
```

---

# Example Complete Blueprint

```text
Blueprint:

HSC Physics Final 2026 - MCQ

Target:

30 Questions

Rules:

------------------------------------------------

Rule 01

Subject = Physics

Mode = HARD

------------------------------------------------

Rule 02

Question Type = MCQ

Mode = HARD

Required Count = 30

------------------------------------------------

Rule 03

Approval Status = APPROVED

Mode = HARD

------------------------------------------------

Rule 04

Easy = 30%

Mode = SOFT

Tolerance = 5%

------------------------------------------------

Rule 05

Medium = 50%

Mode = SOFT

Tolerance = 5%

------------------------------------------------

Rule 06

Hard = 20%

Mode = SOFT

Tolerance = 5%

------------------------------------------------

Rule 07

Chapter 1 = 6 Questions

Mode = HARD

------------------------------------------------

Rule 08

Chapter 2 = 6 Questions

Mode = HARD

------------------------------------------------

Rule 09

Chapter 3 = 6 Questions

Mode = HARD

------------------------------------------------

Rule 10

Duplicate Similarity <= 0.80

Mode = HARD

------------------------------------------------

Rule 11

Usage Count < 3

Mode = SOFT

------------------------------------------------
```

---

# Final Architecture

```text
EXAM
 │
 └── EXAM_BLUEPRINT
       │
       ├── Rule: Subject
       │
       ├── Rule: Question Type
       │
       ├── Rule: Approval
       │
       ├── Rule: Difficulty
       │
       ├── Rule: Bloom
       │
       ├── Rule: Chapter
       │
       ├── Rule: Topic
       │
       ├── Rule: Marks
       │
       ├── Rule: Usage
       │
       └── Rule: Similarity
                  │
                  ↓
          RULE EVALUATION ENGINE
                  │
                  ↓
           CANDIDATE QUESTION POOL
                  │
                  ↓
           OPTIMIZATION / RANDOM
                  │
                  ↓
            SELECTED QUESTIONS
                  │
                  ↓
             EXAM_QUESTIONS
```

---

# Critical Design Decision

`exam_blueprint_rules` is a **configuration table**, not a Question-selection table.

Therefore it must never contain:

```text
question_id
```

A Rule describes a condition.

The Generation Engine determines which Questions satisfy that condition.

The final selected Questions are stored separately in:

```text
exam_questions
```

This separation is essential for:

```text
Auditability
Reproducibility
Versioning
Testing
Scalability
Optimization
AI-assisted selection
```

---

# End of Section-4

এখন `DATABASE_DESIGN.md → Part-5 → Section-5: exam_questions`। এই table-টি Blueprint/Generation Engine-এর configuration নয়; এটি generated Exam-এর final, immutable Question composition সংরক্ষণ করবে। বিশেষভাবে `question_version_id` ব্যবহার করা গুরুত্বপূর্ণ, যাতে ভবিষ্যতে Question Bank-এর মূল Question পরিবর্তন হলেও পুরোনো Exam-এর Question ও wording অক্ষুণ্ণ থাকে।

```markdown id="58321"
# DATABASE_DESIGN.md

Version: 2.0

Part-5

Section-5

# Table : exam_questions

---

## Purpose

Stores the actual Questions selected or assigned to an Exam.

This table represents the final Question composition of an Exam.

An Exam Blueprint defines:

    What Questions should be selected.

An Exam Blueprint Rule defines:

    What constraints must be satisfied.

The Generation Engine determines:

    Which Questions satisfy those constraints.

This table stores:

    The Questions actually used in the Exam.

---

# Core Principle

`exam_questions` is a transactional/result table.

It must NOT be used as the Blueprint configuration layer.

Architecture:

```text
Exam
  ↓
Exam Blueprint
  ↓
Blueprint Rules
  ↓
Generation Engine
  ↓
exam_questions
```

---

# Why This Table Is Necessary

A Question Bank may contain:

    100,000,000 Questions

But one Exam may contain:

    20 Questions

or:

    50 Questions

or:

    100 Questions

Only the Questions actually assigned to that Exam belong in:

    exam_questions

---

# Question Version Requirement

The system must reference:

    question_version_id

rather than only:

    question_id

Reason:

A Question may be edited after an Exam has already been generated.

Example:

```text
Question V1

"What is Newton's second law?"

        ↓

Exam 2026 generated

        ↓

Question edited

        ↓

Question V2

"Which equation represents Newton's second law?"
```

The old Exam must continue using:

    Question V1

Therefore:

    exam_questions.question_version_id

is the authoritative Question reference.

---

# Table Name

exam_questions

---

# Primary Key

id

---

# Columns

| Column | Type | Nullable | Default | Description |
|--------|--------------------------|----------|----------|------------------------------------|
| id | BIGINT UNSIGNED | No | AUTO_INCREMENT | Primary Key |
| uuid | CHAR(36) | No | UUID | Public Identifier |
| exam_id | BIGINT UNSIGNED | No | — | Parent Exam |
| exam_section_id | BIGINT UNSIGNED | Yes | NULL | Exam Section |
| question_id | BIGINT UNSIGNED | No | — | Parent Question |
| question_version_id | BIGINT UNSIGNED | No | — | Exact Question Version |
| generation_run_id | BIGINT UNSIGNED | Yes | NULL | Generation Run |
| blueprint_id | BIGINT UNSIGNED | Yes | NULL | Blueprint Used |
| blueprint_rule_id | BIGINT UNSIGNED | Yes | NULL | Primary Selection Rule |
| question_number | INTEGER UNSIGNED | No | — | Display Number |
| section_question_number | INTEGER UNSIGNED | Yes | NULL | Section Position |
| question_order | INTEGER UNSIGNED | No | — | Internal Ordering |
| marks | DECIMAL(8,2) | No | 0.00 | Assigned Marks |
| negative_marks | DECIMAL(8,2) | No | 0.00 | Negative Marks |
| is_compulsory | BOOLEAN | No | TRUE | Mandatory Question |
| is_optional | BOOLEAN | No | FALSE | Optional Question |
| option_group | VARCHAR(50) | Yes | NULL | Optional Group |
| randomization_position | INTEGER UNSIGNED | Yes | NULL | Randomized Position |
| selection_method | VARCHAR(30) | No | MANUAL | Selection Method |
| selection_score | DECIMAL(12,6) | Yes | NULL | Generation Score |
| difficulty_snapshot | VARCHAR(30) | Yes | NULL | Difficulty at Generation |
| bloom_level_snapshot | VARCHAR(30) | Yes | NULL | Bloom Level at Generation |
| marks_snapshot | DECIMAL(8,2) | Yes | NULL | Original Question Marks |
| question_text_snapshot | LONGTEXT | Yes | NULL | Question Text Snapshot |
| options_snapshot | JSON | Yes | NULL | Option Snapshot |
| answer_snapshot | JSON | Yes | NULL | Answer Snapshot |
| metadata_snapshot | JSON | Yes | NULL | Question Metadata Snapshot |
| status | VARCHAR(30) | No | ACTIVE | Assignment Status |
| created_by | BIGINT UNSIGNED | No | — | Creator |
| updated_by | BIGINT UNSIGNED | Yes | NULL | Last Editor |
| created_at | TIMESTAMP | No | CURRENT_TIMESTAMP | Creation Time |
| updated_at | TIMESTAMP | Yes | NULL | Last Update |
| deleted_at | TIMESTAMP | Yes | NULL | Soft Delete |

---

# Primary Key

    id

Type:

    BIGINT UNSIGNED

---

# Public Identifier

    uuid

Type:

    CHAR(36)

Constraint:

    UNIQUE(uuid)

---

# Exam

    exam_id

Every Exam Question belongs to exactly one Exam.

Foreign Key:

    exams.id

---

# Exam Section

    exam_section_id

Optional.

If NULL:

    Question belongs to the Exam directly.

If populated:

    Question belongs to a specific Exam Section.

Example:

```text
Exam
│
├── Section A
│   ├── Question 1
│   ├── Question 2
│   └── Question 3
│
└── Section B
    ├── Question 4
    └── Question 5
```

---

# Question

    question_id

References the logical/parent Question entity.

This allows the system to maintain the relationship:

    Question
        ↓
    Question Version

However, for historical correctness, `question_version_id` is the authoritative reference.

---

# Question Version

    question_version_id

References the exact version used in this Exam.

This field is mandatory.

Foreign Key:

    question_versions.id

---

# Why Both question_id and question_version_id?

The system needs both levels of identity.

Example:

```text
Question
ID = 100

Versions:

1001 → Version 1
1002 → Version 2
1003 → Version 3
```

Exam uses:

```text
question_id = 100

question_version_id = 1001
```

This means:

    Logical Question = 100

    Exact content used = Version 1001

---

# Generation Run

    generation_run_id

References the generation process that selected the Question.

This enables:

    Audit

    Reproducibility

    Debugging

    Statistics

    Regeneration

    Failure analysis

Example:

```text
Generation Run #5001

    ↓

Question #100

Question #204

Question #887
```

---

# Blueprint

    blueprint_id

Stores the Blueprint used during selection.

This is useful for historical traceability.

Example:

```text
Exam Question

    generated using

Blueprint #25
```

---

# Blueprint Rule

    blueprint_rule_id

Optional.

Stores the primary Rule that caused the Question to be selected.

Example:

```text
Rule:

Chapter 5 = 5 Questions

        ↓

Question selected

        ↓

blueprint_rule_id = 88
```

Important:

A Question may satisfy multiple Rules.

Therefore this field should be treated as:

    Primary / originating rule

not:

    Complete rule mapping

Detailed rule satisfaction should be stored separately in a future audit/result table if required.

---

# Question Number

    question_number

The final visible number in the Exam.

Example:

```text
1
2
3
4
5
```

This is not necessarily the same as:

    id

or:

    question_order

---

# Section Question Number

    section_question_number

If an Exam has multiple Sections:

```text
Section A

1
2
3

Section B

1
2
3
```

Then:

    section_question_number

stores the section-local number.

---

# Question Order

    question_order

Internal ordering value.

Used for:

    Sorting

    Reordering

    Rendering

    PDF generation

    Exam paper generation

Example:

```text
question_order = 10
```

does not necessarily mean:

```text
question_number = 10
```

because numbering can be regenerated during rendering.

---

# Marks

    marks

The marks assigned to this Exam Question.

This value must be treated as the Exam-specific marks.

Example:

Question Bank:

    2 marks

Exam:

    5 marks

Then:

    exam_questions.marks = 5

if the Exam configuration explicitly allows that modification.

However, the original Question marks remain available through:

    marks_snapshot

---

# Negative Marks

    negative_marks

Used for examinations with negative marking.

Example:

```text
marks = 1

negative_marks = 0.25
```

If the Question is answered incorrectly:

    -0.25

---

# Compulsory Question

    is_compulsory

Default:

    TRUE

If TRUE:

    Candidate must answer the Question.

---

# Optional Question

    is_optional

Default:

    FALSE

If TRUE:

    Candidate may have an alternative Question or choice.

---

# Compulsory / Optional Integrity

The following combination is invalid:

```text
is_compulsory = TRUE

is_optional = TRUE
```

The application layer must prevent this.

---

# Option Group

    option_group

Used when Questions form an optional choice group.

Example:

```text
Answer any 2 of Questions 5, 6, 7
```

All three Questions may have:

```text
option_group = GROUP-A
```

The Exam renderer can then display:

```text
Answer any TWO.
```

---

# Randomization Position

    randomization_position

Stores the position assigned after Question randomization.

Useful when:

    Question selection order

and:

    final display order

are different.

---

# Selection Method

Recommended values:

```text
AUTO

MANUAL

AI_ASSISTED

HYBRID

IMPORTED

CLONED
```

---

# AUTO

Question selected automatically by deterministic rules.

---

# MANUAL

Question manually assigned by an authorized user.

---

# AI_ASSISTED

AI contributed to ranking or selection.

AI selection must still pass deterministic validation.

---

# HYBRID

Combination of automatic and manual/AI-assisted selection.

---

# IMPORTED

Question assignment came from an imported Exam or external source.

---

# CLONED

Question assignment was copied from another Exam/Set.

---

# Selection Score

    selection_score

Optional numerical score calculated by the generation engine.

Example:

```text
0.948721
```

The score may combine:

    Topic relevance

    Difficulty fit

    Bloom fit

    Diversity

    Usage history

    Similarity

    Quality

    Random component
```

The exact scoring formula belongs to the Generation Engine, not the database schema.

---

# Difficulty Snapshot

    difficulty_snapshot

Stores the difficulty classification at the time of generation.

Example:

    EASY

    MEDIUM

    HARD

Why snapshot?

The Question Bank classification may change later.

The generated Exam must retain historical metadata.

---

# Bloom Level Snapshot

    bloom_level_snapshot

Stores the Bloom's Taxonomy level at generation time.

Examples:

    REMEMBER

    UNDERSTAND

    APPLY

    ANALYZE

    EVALUATE

    CREATE

---

# Marks Snapshot

    marks_snapshot

Stores the original Question Version's marks at generation time.

This differs from:

    marks

because:

    marks
        =
    Exam-specific assigned marks

while:

    marks_snapshot
        =
    Original Question Version marks

---

# Question Text Snapshot

    question_text_snapshot

Stores the exact Question text used when the Exam was generated.

This is a critical historical/audit field.

Example:

```text
"What is Newton's second law of motion?"
```

Even if the Question Version is later modified, the generated Exam remains reproducible.

---

# Why Snapshot the Question Text?

Because an Exam is a historical document.

The system should be able to regenerate:

    The exact same Exam

years later.

Therefore:

```text
Question Bank
    may change

Generated Exam
    must not change
```

---

# Options Snapshot

    options_snapshot

Stores the exact options used in the generated Exam.

Example:

```json id="7bq3b7"
{
    "A": "F = ma",
    "B": "F = mv",
    "C": "F = m/a",
    "D": "F = a/m"
}
```

---

# Answer Snapshot

    answer_snapshot

Stores the answer information required for:

    Answer Key

    Evaluation

    Audit

Example:

```json id="n98n3r"
{
    "correct_option": "A"
}
```

Important:

For student-facing Question Paper rendering, the answer must never be exposed.

---

# Metadata Snapshot

    metadata_snapshot

Stores additional generation-time metadata.

Example:

```json id="a5w0ot"
{
    "chapter_id": 5,
    "topic_id": 21,
    "difficulty": "MEDIUM",
    "bloom_level": "APPLY",
    "language": "bn",
    "source": "TEXTBOOK"
}
```

---

# Status

Recommended:

```text
ACTIVE

REPLACED

REMOVED

VOID
```

---

# ACTIVE

Question is part of the current Exam.

---

# REPLACED

Question was replaced by another Question during editing.

Useful for audit history.

---

# REMOVED

Question was removed from the Exam.

---

# VOID

Question assignment is invalidated.

Example:

    Question found defective after publication.

The row should generally remain for audit purposes rather than being physically deleted.

---

# Foreign Keys

exam_id

    →

exams.id

ON DELETE CASCADE

ON UPDATE CASCADE


exam_section_id

    →

exam_sections.id

ON DELETE SET NULL

ON UPDATE CASCADE


question_id

    →

questions.id

ON DELETE RESTRICT

ON UPDATE CASCADE


question_version_id

    →

question_versions.id

ON DELETE RESTRICT

ON UPDATE CASCADE


generation_run_id

    →

exam_generation_runs.id

ON DELETE SET NULL

ON UPDATE CASCADE


blueprint_id

    →

exam_blueprints.id

ON DELETE SET NULL

ON UPDATE CASCADE


blueprint_rule_id

    →

exam_blueprint_rules.id

ON DELETE SET NULL

ON UPDATE CASCADE


created_by

    →

users.id

ON DELETE RESTRICT

ON UPDATE CASCADE


updated_by

    →

users.id

ON DELETE SET NULL

ON UPDATE CASCADE

---

# Indexes

Recommended:

```text
INDEX(exam_id)

INDEX(exam_section_id)

INDEX(question_id)

INDEX(question_version_id)

INDEX(generation_run_id)

INDEX(blueprint_id)

INDEX(blueprint_rule_id)

INDEX(question_number)

INDEX(section_question_number)

INDEX(question_order)

INDEX(status)

INDEX(selection_method)

INDEX(exam_id, question_order)

INDEX(exam_id, status)

INDEX(exam_section_id, question_order)

INDEX(question_version_id, exam_id)
```

---

# Unique Constraints

Recommended:

```text
UNIQUE(uuid)
```

For active Exam composition:

```text
UNIQUE(exam_id, question_version_id)
```

This prevents the same Question Version from appearing twice in the same Exam.

If the system later allows deliberate duplicates, this constraint should be replaced by application-level validation.

---

# Question Number Constraint

Recommended:

```text
UNIQUE(exam_id, question_number)
```

However, if numbering is section-based, use:

```text
UNIQUE(
    exam_section_id,
    section_question_number
)
```

The exact constraint depends on the Exam rendering architecture.

---

# Important Constraint

The system must ensure:

```text
exam_questions.exam_section_id
    belongs to
exam_questions.exam_id
```

Similarly:

```text
exam_questions.blueprint_id
    belongs to
exam_questions.exam_id
```

and:

```text
exam_questions.generation_run_id
    belongs to
exam_questions.exam_id
```

These cross-table relationships should be validated by the application/service layer.

---

# Relationships

Exam

    HasMany

    ExamQuestion

---

ExamSection

    HasMany

    ExamQuestion

---

Question

    HasMany

    ExamQuestion

---

QuestionVersion

    HasMany

    ExamQuestion

---

ExamGenerationRun

    HasMany

    ExamQuestion

---

ExamBlueprint

    HasMany

    ExamQuestion

---

ExamBlueprintRule

    HasMany

    ExamQuestion

---

# Laravel Model

ExamQuestion

---

# Migration

create_exam_questions_table

---

# Factory

ExamQuestionFactory

---

# Seeder

ExamQuestionSeeder

---

# Laravel Relationships

## Exam Model

```php
public function questions()
{
    return $this->hasMany(
        ExamQuestion::class
    )->orderBy('question_order');
}
```

---

## ExamSection Model

```php
public function questions()
{
    return $this->hasMany(
        ExamQuestion::class,
        'exam_section_id'
    )->orderBy('section_question_number');
}
```

---

## Question Model

```php
public function examQuestions()
{
    return $this->hasMany(
        ExamQuestion::class
    );
}
```

---

## QuestionVersion Model

```php
public function examQuestions()
{
    return $this->hasMany(
        ExamQuestion::class
    );
}
```

---

## ExamQuestion Model

```php
public function exam()
{
    return $this->belongsTo(
        Exam::class
    );
}

public function section()
{
    return $this->belongsTo(
        ExamSection::class,
        'exam_section_id'
    );
}

public function question()
{
    return $this->belongsTo(
        Question::class
    );
}

public function questionVersion()
{
    return $this->belongsTo(
        QuestionVersion::class,
        'question_version_id'
    );
}

public function generationRun()
{
    return $this->belongsTo(
        ExamGenerationRun::class,
        'generation_run_id'
    );
}

public function blueprint()
{
    return $this->belongsTo(
        ExamBlueprint::class,
        'blueprint_id'
    );
}

public function blueprintRule()
{
    return $this->belongsTo(
        ExamBlueprintRule::class,
        'blueprint_rule_id'
    );
}
```

---

# Validation Rules

```text
exam_id

required

exists:exams,id


exam_section_id

nullable

exists:exam_sections,id


question_id

required

exists:questions,id


question_version_id

required

exists:question_versions,id


generation_run_id

nullable

exists:exam_generation_runs,id


blueprint_id

nullable

exists:exam_blueprints,id


blueprint_rule_id

nullable

exists:exam_blueprint_rules,id


question_number

required

integer

min:1


section_question_number

nullable

integer

min:1


question_order

required

integer

min:1


marks

required

numeric

min:0


negative_marks

required

numeric

min:0


is_compulsory

boolean


is_optional

boolean


option_group

nullable

string

max:50


randomization_position

nullable

integer

min:1


selection_method

required

in:
AUTO,
MANUAL,
AI_ASSISTED,
HYBRID,
IMPORTED,
CLONED


selection_score

nullable

numeric


difficulty_snapshot

nullable

string

max:30


bloom_level_snapshot

nullable

string

max:30


marks_snapshot

nullable

numeric

min:0


status

required

in:
ACTIVE,
REPLACED,
REMOVED,
VOID
```

---

# Business Rules

1. Every Exam Question must belong to exactly one Exam.

2. Every Exam Question must reference a valid Question Version.

3. The Question Version must belong to the referenced Question.

4. The Question Version must be valid for the Exam's Subject/Curriculum/Class where applicable.

5. A Question Version must not be duplicated within the same Exam unless deliberate duplication is explicitly enabled.

6. `marks` cannot be negative.

7. `negative_marks` cannot be negative.

8. A Question cannot simultaneously be compulsory and optional.

9. An active Exam Question must have a valid `question_number`.

10. An active Section Question must have a valid `section_question_number`.

11. Removed Questions should not normally be physically deleted.

12. Published Exam Questions should be immutable from normal CRUD operations.

13. Changes after publication must be handled through a controlled replacement workflow.

14. The Question Text Snapshot must represent the exact content used by the generated Exam.

15. Answer information must never be exposed to unauthorized student-facing endpoints.

---

# Generated Exam Immutability

Once an Exam reaches:

```text
PUBLISHED
```

the following fields should normally become immutable:

```text
question_id

question_version_id

question_text_snapshot

options_snapshot

answer_snapshot

marks_snapshot

difficulty_snapshot

bloom_level_snapshot

generation_run_id

blueprint_id
```

If a Question must be changed:

```text
Existing Question
        ↓
Mark as REPLACED
        ↓
Create replacement ExamQuestion
```

This creates an audit trail.

---

# Question Replacement

Example:

```text
Original:

Exam Question #12

Question Version = 5001

Status = ACTIVE
```

Question becomes invalid.

Instead of deleting:

```text
Question #12
Status = REPLACED
```

Then:

```text
Replacement Question #12
Question Version = 5077
Status = ACTIVE
```

The replacement operation must be logged.

---

# Generation Selection

For automatically generated Exams:

```text
selection_method = AUTO
```

For AI-assisted ranking:

```text
selection_method = AI_ASSISTED
```

For manual modification after generation:

```text
selection_method = HYBRID
```

---

# Selection Score

A generation algorithm may calculate:

```text
selection_score
```

from:

```text
Topic Relevance
+
Difficulty Fit
+
Bloom Fit
+
Quality Score
+
Diversity
+
Usage Penalty
+
Randomization
```

The exact formula must remain inside the Generation Engine.

---

# Example

Suppose:

```text
Blueprint:

30 Questions
```

Generation Engine selects:

```text
Question Version 1001
Question Version 1008
Question Version 1023
...
Question Version 3005
```

The database stores:

```text
exam_questions

1 → 1001
2 → 1008
3 → 1023
...
30 → 3005
```

---

# Example Row

```text
exam_id = 25

exam_section_id = 3

question_id = 100

question_version_id = 1004

generation_run_id = 9001

blueprint_id = 25

blueprint_rule_id = 88

question_number = 12

question_order = 12

marks = 1.00

negative_marks = 0.25

is_compulsory = TRUE

is_optional = FALSE

selection_method = AUTO

selection_score = 0.947821

difficulty_snapshot = MEDIUM

bloom_level_snapshot = APPLY

marks_snapshot = 1.00

status = ACTIVE
```

---

# Snapshot Example

```json
{
    "question_text": "What is Newton's second law?",
    "options": {
        "A": "F = ma",
        "B": "F = mv",
        "C": "F = m/a",
        "D": "F = a/m"
    },
    "answer": {
        "correct_option": "A"
    },
    "difficulty": "MEDIUM",
    "bloom_level": "APPLY",
    "marks": 1
}
```

The exact structure may be refined after the final Question/Question Version schema is finalized.

---

# Security Consideration

The following fields are sensitive:

```text
answer_snapshot
metadata_snapshot
selection_score
blueprint_rule_id
generation_run_id
```

Student-facing APIs must not return them.

Recommended API separation:

```text
StudentExamQuestionResource
```

vs:

```text
AdminExamQuestionResource
```

---

# Student-Facing Data

A student should receive only what is required to answer:

```text
question_number

question_text_snapshot

options_snapshot

marks

negative_marks
```

The student should NOT receive:

```text
answer_snapshot

selection_score

blueprint_id

blueprint_rule_id

generation_run_id

internal metadata
```

---

# Teacher/Moderator Data

Authorized staff may additionally receive:

```text
question_id

question_version_id

difficulty_snapshot

bloom_level_snapshot

selection_method

generation_run_id

blueprint_id

blueprint_rule_id
```

subject to permissions.

---

# PDF Generation

The PDF generator should primarily read from:

```text
exam_questions
```

and use:

```text
question_text_snapshot

options_snapshot

marks

question_number

section_question_number
```

This ensures the generated PDF reflects the exact Exam composition.

---

# Answer Key Generation

Answer Key generation should use:

```text
answer_snapshot
```

or the immutable Question Version answer data.

The generated Answer Key should correspond exactly to the Question Paper version.

---

# Exam Rendering Pipeline

```text
Exam
  ↓
Exam Sections
  ↓
exam_questions
  ↓
Question Snapshots
  ↓
Numbering
  ↓
Formatting
  ↓
PDF / Print
```

---

# Generation Pipeline

```text
Exam Blueprint
      ↓
Blueprint Rules
      ↓
Candidate Pool
      ↓
Question Scoring
      ↓
Question Selection
      ↓
Generation Validation
      ↓
exam_questions
      ↓
Exam Publication
```

---

# Validation Before Publication

Before an Exam becomes:

```text
PUBLISHED
```

the system must validate:

```text
1. Required Question Count

2. Total Marks

3. Section Question Count

4. Hard Constraints

5. Soft Constraint Tolerance

6. Duplicate Questions

7. Question Version Validity

8. Question Snapshot Integrity

9. Question Number Sequence

10. Answer Key Integrity

11. Section Ordering

12. Optional/Compulsory Rules
```

---

# Total Marks Validation

Example:

```text
Question 1 = 1 mark
Question 2 = 2 marks
Question 3 = 2 marks
...
```

System calculates:

```text
SUM(exam_questions.marks)
```

and compares with:

```text
exam.total_marks
```

If mismatch:

```text
PUBLICATION_BLOCKED
```

unless the Exam explicitly allows variable marks.

---

# Duplicate Validation

Before publication:

```text
GROUP BY question_version_id
```

If:

```text
COUNT(*) > 1
```

then:

```text
DUPLICATE_QUESTION_DETECTED
```

unless duplicate usage is explicitly allowed.

---

# Ordering Validation

Expected:

```text
1
2
3
4
5
...
N
```

No missing or duplicate numbering should exist for a fixed sequential Exam.

---

# Soft Constraint Validation

Example:

```text
Easy Target = 30%

Tolerance = 5%
```

Actual:

```text
Easy = 33.3%
```

Result:

```text
VALID
```

Actual:

```text
Easy = 42%
```

Result:

```text
INVALID
```

if the Rule is treated as a publication-level requirement.

---

# Hard Constraint Validation

If:

```text
Chapter 5 = 5 Questions
```

Actual:

```text
Chapter 5 = 4
```

Result:

```text
INVALID
```

No automatic publication.

---

# Auditability

Every automatically generated Exam Question should ideally be traceable through:

```text
exam_questions
        ↓
generation_run_id
        ↓
exam_generation_runs
        ↓
blueprint_id
        ↓
exam_blueprints
        ↓
exam_blueprint_rules
```

This enables the system to answer:

    "Why was this Question selected?"

---

# Why Was This Question Selected?

The system should eventually be able to provide:

```text
Question:

Q12

Selected because:

    Subject = Physics
    Type = MCQ
    Chapter = 5
    Difficulty = Medium
    Bloom = Apply
    Usage Count = 1
    Similarity = 0.21
    Selection Score = 0.947821
```

This explanation belongs to the Generation/Audit layer, not directly inside this table.

---

# Scaling Consideration

The Question Bank may contain up to:

    100,000,000 Questions

but a single Exam may contain:

    20-200 Questions

Therefore `exam_questions` remains relatively small compared with:

    questions

and:

    question_versions

Indexes should prioritize:

```text
exam_id

question_version_id

generation_run_id

status
```

---

# Partitioning

Initial implementation:

    No partitioning required.

If future workload becomes extremely large:

    Millions of generated Exams

    Hundreds of millions of exam_questions

then partitioning or archival strategies may be considered.

Potential partition key:

    exam_id

or:

    created_at

The decision should be based on production workload rather than premature optimization.

---

# Recommended Service Layer

Use:

```text
ExamQuestionService

ExamQuestionAssignmentService

ExamQuestionReplacementService

ExamQuestionOrderingService

ExamQuestionValidationService

ExamQuestionSnapshotService
```

---

# Recommended Events

```text
ExamQuestionAssigned

ExamQuestionReordered

ExamQuestionReplaced

ExamQuestionRemoved

ExamQuestionRestored
```

---

# Recommended Exceptions

```text
InvalidExamQuestionException

DuplicateExamQuestionException

InvalidQuestionVersionException

ExamQuestionNumberConflictException

ExamQuestionSectionMismatchException

ExamQuestionSnapshotException

PublishedExamQuestionModificationException
```

---

# Critical Architectural Rules

## Rule 1

Never rely only on `question_id`.

Use:

```text
question_version_id
```

for historical correctness.

---

## Rule 2

Never delete a published Exam Question through normal CRUD.

Use replacement/void workflow.

---

## Rule 3

Never expose `answer_snapshot` to student-facing endpoints.

---

## Rule 4

Generated Exam content must remain reproducible.

---

## Rule 5

Blueprint configuration and generated Question assignment must remain separate.

---

## Rule 6

A Question Version change must never silently alter an already-generated Exam.

---

# Final Architecture

```text
QUESTION BANK
│
├── questions
│
└── question_versions
          │
          │
          ↓
     GENERATION ENGINE
          ↑
          │
    exam_blueprints
          │
          └── exam_blueprint_rules
                    │
                    ↓
             QUESTION SELECTION
                    │
                    ↓
              exam_questions
                    │
                    ├── Question Version
                    ├── Snapshot
                    ├── Marks
                    ├── Ordering
                    ├── Generation Run
                    └── Blueprint Trace
                    │
                    ↓
             EXAM PUBLICATION
                    │
                    ├── Question Paper
                    └── Answer Key
```

---

# End of Section-5

Next:

Section-6

Table:

exam_generation_runs
```

`exam_generation_runs` হবে automatic generation-এর audit এবং reproducibility layer। সেখানে কোন Blueprint, কোন Rules snapshot, কোন algorithm version, কোন random seed, কতজন candidate Question পাওয়া গিয়েছিল, কতগুলো selected হয়েছে, generation সফল/ব্যর্থ হয়েছে—এসব সংরক্ষণ করা হবে।
