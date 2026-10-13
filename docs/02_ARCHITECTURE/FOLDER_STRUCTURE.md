---
document_id: DOC-021
document_name: Folder Structure
project_name: Enterprise Question Bank & Examination Management System (EQBEMS)
version: 1.0.0
status: Draft

related_documents:
  - SYSTEM_ARCHITECTURE.md
  - PROJECT_STANDARDS.md
  - PROJECT_MANIFEST.md
  - DATABASE_DESIGN.md
---

# FOLDER_STRUCTURE.md

# Purpose

This document defines the official folder and file organization of EQBEMS.

Every developer must follow this structure.

No file should be placed in an arbitrary location.

---

# Root Directory

```
EQBEMS/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── docs/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
│
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── vite.config.js
├── phpunit.xml
├── README.md
└── .env
```

---

# Root Folder Responsibilities

| Folder | Responsibility |
|----------|----------------|
| app | Application source code |
| bootstrap | Laravel bootstrap |
| config | Configuration files |
| database | Migration, Seeder, Factory |
| docs | Project documentation |
| public | Public web root |
| resources | Blade, CSS, JS |
| routes | Route definitions |
| storage | Uploaded files & logs |
| tests | Automated tests |
| vendor | Composer packages |

---

# Application Directory

```
app/
│
├── Console/
├── Enums/
├── Events/
├── Exceptions/
├── Helpers/
├── Http/
├── Jobs/
├── Listeners/
├── Mail/
├── Models/
├── Notifications/
├── Observers/
├── Policies/
├── Providers/
├── Rules/
├── Services/
├── Traits/
├── View/
└── Support/
```

---

# Folder Responsibilities

## Console

Contains Artisan Commands.

Example

```
app/Console/Commands/
```

---

## Enums

PHP Enumerations.

Example

```
UserStatus.php

QuestionType.php

ExamStatus.php
```

---

## Events

Business Events.

Example

```
InstitutionCreated.php

QuestionApproved.php

ExamPublished.php
```

---

## Exceptions

Custom Exception Classes.

Example

```
InstitutionNotFoundException.php

ExamAlreadyPublishedException.php
```

---

## Helpers

Reusable helper functions.

Avoid placing business logic here.

---

## Http

Contains all HTTP-related classes.

```
Http/

    Controllers/

    Middleware/

    Requests/
```

---

# Controllers

Location

```
app/Http/Controllers/
```

Structure

```
Controllers/

    Auth/

    Institution/

    Academic/

    User/

    Question/

    Blueprint/

    Exam/

    Result/

    Report/

    Setting/
```

Example

```
InstitutionController.php

QuestionController.php

ExamController.php
```

Controllers should remain thin.

Controllers must NOT contain:

Business Rules

Transactions

Complex Queries

---

# Requests

Location

```
app/Http/Requests/
```

Structure

```
Requests/

    Institution/

    Academic/

    User/

    Question/

    Blueprint/

    Exam/

    Result/

    Report/

    Setting/
```

Example

```
StoreInstitutionRequest.php

UpdateInstitutionRequest.php

StoreQuestionRequest.php

PublishExamRequest.php
```

Every Create and Update action should have its own Form Request.

---

# Middleware

```
Http/

    Middleware/
```

Contains

Authentication

Permission Check

Role Check

Maintenance

Request Logging

---

# Rules

Custom Validation Rules.

Example

```
UniqueInstitutionCode.php

ValidExamSchedule.php
```

---

# Services

Location

```
app/Services/
```

Structure

```
Services/

    Institution/

    Academic/

    User/

    Question/

    Blueprint/

    Exam/

    Result/

    Report/
```

Example

```
InstitutionService.php

QuestionService.php

ExamService.php
```

Business Logic belongs here.

---

# Models

Location

```
app/Models/
```

Example

```
Institution.php

Faculty.php

Department.php

Question.php

Exam.php

Result.php
```

Models contain

Relationships

Scopes

Accessors

Mutators

Casts

Avoid workflow logic.

---

# Policies

Location

```
app/Policies/
```

Example

```
InstitutionPolicy.php

QuestionPolicy.php

ExamPolicy.php
```

Authorization belongs here.

---

# Notifications

Location

```
app/Notifications/
```

Example

```
ExamPublishedNotification.php

PasswordResetNotification.php
```

---

# Jobs

Location

```
app/Jobs/
```

Example

```
GeneratePDFJob.php

ImportQuestionJob.php
```

---

# Observers

Location

```
app/Observers/
```

Example

```
InstitutionObserver.php

QuestionObserver.php
```

---

# Traits

Reusable traits.

Example

```
HasUuid.php

HasAudit.php
```

---

# Support

Shared utility classes.

Example

```
ResponseBuilder.php

DateFormatter.php

FileUploader.php
```

---

# FOLDER_STRUCTURE.md

## Part 2

---

# Resources Directory

Location

```
resources/
```

Structure

```
resources/

├── css/
├── js/
├── images/
├── fonts/
├── lang/
└── views/
```

---

## CSS

```
resources/css/
```

Contains

- app.css
- custom.css
- print.css

Rules

- Global styles only
- Avoid duplicate CSS
- Bootstrap override should be separated

---

## JavaScript

```
resources/js/
```

Structure

```
js/

├── app.js
├── bootstrap.js
├── modules/
├── helpers/
└── plugins/
```

Example

```
modules/

    dashboard.js

    institution.js

    exam.js
```

---

## Images

```
resources/images/
```

Contains

- Logo
- Icons
- Background
- Static Images

---

## Language

```
resources/lang/
```

Example

```
lang/

    en/

    bn/
```

Future ready for localization.

---

# Views

```
resources/views/
```

Structure

```
views/

├── layouts/
├── partials/
├── components/
├── auth/
├── dashboard/
├── institution/
├── academic/
├── users/
├── question/
├── blueprint/
├── exam/
├── result/
├── report/
├── setting/
└── errors/
```

---

## Layouts

```
layouts/

    app.blade.php

    auth.blade.php

    guest.blade.php
```

---

## Partials

Contains reusable fragments.

Examples

```
navbar.blade.php

sidebar.blade.php

footer.blade.php

breadcrumb.blade.php
```

---

## Components

Reusable Blade Components.

Examples

```
alert.blade.php

button.blade.php

modal.blade.php

table.blade.php
```

---

# Routes Directory

Location

```
routes/
```

Structure

```
routes/

├── web.php
├── auth.php
├── institution.php
├── academic.php
├── user.php
├── question.php
├── blueprint.php
├── exam.php
├── result.php
├── report.php
└── setting.php
```

---

## Route Strategy

web.php acts as Route Loader.

Example

```
require __DIR__.'/institution.php';

require __DIR__.'/academic.php';

require __DIR__.'/question.php';
```

---

# Database Directory

Location

```
database/
```

Structure

```
database/

├── factories/
├── migrations/
├── seeders/
```

---

## Migrations

```
migrations/
```

Naming

```
2026_08_01_create_institutions_table.php

2026_08_02_create_departments_table.php
```

Rules

One migration

↓

One table

---

## Seeders

```
seeders/

DatabaseSeeder.php

RoleSeeder.php

PermissionSeeder.php

InstitutionSeeder.php
```

---

## Factories

```
factories/
```

Example

```
InstitutionFactory.php

QuestionFactory.php
```

---

# Public Directory

```
public/
```

Contains

- index.php
- assets
- storage symlink
- favicon

Never store source code here.

---

# Storage Directory

```
storage/
```

Structure

```
storage/

├── app/
├── framework/
└── logs/
```

---

## App

```
storage/app/

public/

institution/

users/

question/

report/

temp/
```

---

## Logs

```
storage/logs/
```

Contains Laravel logs.

---

# Config Directory

```
config/
```

Contains

```
app.php

auth.php

cache.php

database.php

filesystems.php

mail.php

permission.php
```

Never hardcode configuration values.

---

# Bootstrap Directory

Contains Laravel bootstrap files.

Normally untouched.

---

# Tests Directory

```
tests/
```

Structure

```
tests/

├── Feature/
├── Unit/
├── Integration/
└── TestCase.php
```

---

## Feature

```
Feature/

Institution/

Academic/

Question/

Exam/
```

---

## Unit

```
Unit/

Services/

Helpers/

Rules/
```

---

## Integration

```
Integration/

Database/

Queue/

Notification/
```

---

# Docs Directory

```
docs/
```

Structure

```
docs/

01_PROJECT/

02_ARCHITECTURE/

03_DATABASE/

04_SECURITY/

05_API/

06_UI/

07_TESTING/

08_DEPLOYMENT/

09_ADR/

10_REFERENCE/
```

---

# Scripts Directory (Optional)

```
scripts/

backup.sh

deploy.sh

optimize.sh
```

---

# File Placement Rules

Controllers

↓

app/Http/Controllers

Requests

↓

app/Http/Requests

Services

↓

app/Services

Policies

↓

app/Policies

Models

↓

app/Models

Views

↓

resources/views

Routes

↓

routes/

Migrations

↓

database/migrations

Seeders

↓

database/seeders

Tests

↓

tests/

Documentation

↓

docs/

---

# Naming Rules

Controller

InstitutionController

Service

InstitutionService

Policy

InstitutionPolicy

Request

StoreInstitutionRequest

Model

Institution

Migration

create_institutions_table

Seeder

InstitutionSeeder

Factory

InstitutionFactory

Job

GenerateResultJob

Notification

ExamPublishedNotification

---

# Folder Rules

✓ One responsibility per folder

✓ Group files by feature/module where practical

✓ Avoid duplicate helper classes

✓ Avoid deep nesting without justification

✓ Keep naming consistent

✓ Do not mix business logic into Views or Controllers

---

# Module Folder Standard

Every business module follows the same directory pattern.

Example:

Institution Module

app/
├── Http/
│   ├── Controllers/
│   │   └── InstitutionController.php
│   │
│   └── Requests/
│       └── Institution/
│           ├── StoreInstitutionRequest.php
│           └── UpdateInstitutionRequest.php
│
├── Models/
│   └── Institution.php
│
├── Policies/
│   └── InstitutionPolicy.php
│
├── Services/
│   └── Institution/
│       ├── InstitutionService.php
│       ├── InstitutionCodeService.php
│       ├── InstitutionImportService.php
│       └── InstitutionExportService.php
│
├── Events/
│   ├── InstitutionCreated.php
│   └── InstitutionUpdated.php
│
├── Listeners/
│   ├── SendInstitutionNotification.php
│   └── LogInstitutionActivity.php
│
├── Notifications/
│   └── InstitutionCreatedNotification.php
│
└── Observers/
    └── InstitutionObserver.php

resources/
└── views/
    └── institution/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        ├── show.blade.php
        ├── _form.blade.php
        ├── _table.blade.php
        └── _filters.blade.php

tests/
└── Feature/
    └── Institution/
        ├── InstitutionCrudTest.php
        ├── InstitutionValidationTest.php
        └── InstitutionAuthorizationTest.php
```

---

# File Responsibility Matrix

| File | Responsibility |
|------|----------------|
| Model | Database Entity |
| Controller | HTTP Request Handling |
| StoreRequest | Create Validation |
| UpdateRequest | Update Validation |
| Policy | Authorization |
| Service | Business Logic |
| Observer | Model Event Handling |
| Event | Business Event |
| Listener | Event Processing |
| Notification | User Notification |
| View | User Interface |
| Test | Feature Verification |

---

# Controller Responsibilities

Location

```
app/Http/Controllers/
```

Allowed

✓ Call Service

✓ Return View

✓ Redirect

✓ Flash Message

✓ Authorize Request

Not Allowed

✗ Business Logic

✗ DB Query

✗ Transaction

✗ Validation Rules

---

# Request Responsibilities

Location

```
app/Http/Requests/
```

Allowed

✓ Validation Rules

✓ Custom Messages

✓ Authorization (basic)

Not Allowed

✗ Save Data

✗ DB Query

✗ Workflow

---

# Service Responsibilities

Location

```
app/Services/
```

Allowed

✓ Business Logic

✓ Transactions

✓ Multiple Models

✓ Event Dispatch

✓ Queue Dispatch

✓ Workflow

Not Allowed

✗ HTML

✗ Blade

✗ Redirect

✗ Session

---

# Model Responsibilities

Location

```
app/Models/
```

Allowed

✓ Relationship

✓ Scope

✓ Accessor

✓ Mutator

✓ Cast

Not Allowed

✗ Workflow

✗ Business Process

✗ HTTP

---

# Policy Responsibilities

Location

```
app/Policies/
```

Allowed

✓ Authorization

✓ Permission Checking

Not Allowed

✗ Business Logic

✗ Database Update

---

# Observer Responsibilities

Allowed

✓ Logging

✓ Cache Clear

✓ Event Dispatch

✓ Audit

Not Allowed

✗ UI

✗ Redirect

---

# Event Responsibilities

Purpose

Represent business occurrence.

Example

InstitutionCreated

QuestionApproved

ExamPublished

---

# Listener Responsibilities

Purpose

Respond to Events.

Example

Send Email

Create Audit Log

Refresh Cache

---

# Notification Responsibilities

Purpose

Notify Users.

Channels

Email

Database

Future

SMS

Push

---

# View Responsibilities

Purpose

Display UI only.

Allowed

✓ Loop

✓ Condition

✓ Components

✓ Formatting

Not Allowed

✗ Query

✗ Business Logic

---

# Test Responsibilities

Feature Test

Controller

Validation

Permission

CRUD

Unit Test

Service

Helper

Rule

Integration Test

Queue

Event

Database

Notification

---

# Module Checklist

Every Module must contain

✓ Migration

✓ Model

✓ Controller

✓ Requests

✓ Service

✓ Policy

✓ Views

✓ Routes

✓ Tests

Documentation Update

Git Commit

---

# Example Development Order

Institution

↓

Migration

↓

Model

↓

Factory

↓

Seeder

↓

Policy

↓

Requests

↓

Service

↓

Controller

↓

Routes

↓

Views

↓

Tests

↓

Documentation

↓

Git Commit

---

# Part 4

---

# Enterprise File Creation Rules

Every new feature must follow the same creation order.

Never skip steps.

Recommended order:

Business Requirement

↓

Database Design

↓

Migration

↓

Model

↓

Factory

↓

Seeder

↓

Policy

↓

Form Request

↓

Service

↓

Controller

↓

Routes

↓

Views

↓

Feature Test

↓

Unit Test

↓

Documentation

↓

Git Commit

---

# Module Development Checklist

Before a module is marked complete, verify:

☐ Migration created

☐ Model created

☐ Relationships completed

☐ Factory created

☐ Seeder created

☐ Policy implemented

☐ Store Request created

☐ Update Request created

☐ Service implemented

☐ Controller completed

☐ Routes registered

☐ Navigation added

☐ Views completed

☐ Validation tested

☐ Authorization tested

☐ CRUD tested

☐ Documentation updated

☐ Commit completed

---

# File Responsibility Matrix

| Layer | Folder | Responsibility |
|--------|--------|----------------|
| Route | routes/ | Entry point |
| Middleware | app/Http/Middleware | Request filtering |
| Controller | app/Http/Controllers | HTTP handling |
| Request | app/Http/Requests | Validation |
| Policy | app/Policies | Authorization |
| Service | app/Services | Business logic |
| Model | app/Models | Database entity |
| Observer | app/Observers | Model events |
| Event | app/Events | Business event |
| Listener | app/Listeners | Event processing |
| Notification | app/Notifications | User notifications |
| Job | app/Jobs | Background processing |
| View | resources/views | Presentation |
| Test | tests/ | Automated testing |
| Docs | docs/ | Project documentation |

---

# Allowed Dependencies

The dependency flow must always move downward.

Browser

↓

Route

↓

Middleware

↓

Controller

↓

Form Request

↓

Policy

↓

Service

↓

Model

↓

Database

Controllers may call Services.

Services may call Models.

Models must never call Controllers.

Views must never access Models directly.

---

# Forbidden Practices

Never place business logic inside Controllers.

Never write database queries inside Blade Views.

Never call DB::table() from Views.

Never bypass Form Request validation.

Never bypass Policies.

Never duplicate business logic.

Never hardcode configuration values.

Never commit `.env`.

Never commit `vendor/`.

Never commit `node_modules/`.

---

# Naming Matrix

| Item | Naming Rule | Example |
|------|-------------|---------|
| Model | PascalCase | Institution |
| Controller | PascalCase + Controller | InstitutionController |
| Service | PascalCase + Service | InstitutionService |
| Policy | PascalCase + Policy | InstitutionPolicy |
| Request | Action + Model + Request | StoreInstitutionRequest |
| Job | Verb + Noun + Job | GenerateResultJob |
| Event | Past Tense | InstitutionCreated |
| Listener | Verb + Noun | SendInstitutionEmail |
| Notification | Event + Notification | ExamPublishedNotification |
| Seeder | Model + Seeder | InstitutionSeeder |
| Factory | Model + Factory | InstitutionFactory |
| Migration | create_xxx_table | create_institutions_table |

---

# Folder Ownership Rules

Each folder has a single responsibility.

Example:

Controllers

Only HTTP requests.

Services

Only business logic.

Policies

Only authorization.

Models

Only data representation.

Observers

Only model lifecycle events.

Views

Only presentation.

---

# AI Development Rules

This project is designed to be AI-friendly.

Any AI assistant contributing to this project should follow these rules.

Always:

✔ Read PROJECT_MANIFEST.md

✔ Read SYSTEM_ARCHITECTURE.md

✔ Read PROJECT_STANDARDS.md

✔ Read PRODUCT_REQUIREMENTS.md

✔ Read ADR documents

before generating code.

AI must never introduce a new architecture without updating ADR documents.

AI must preserve Laravel conventions unless an ADR explicitly defines otherwise.

---

# Documentation Rules

Every completed feature must update:

CHANGELOG.md

PROJECT_STATUS.md

FEATURE_CATALOG.md (if applicable)

Relevant ADR (if architecture changes)

Database documentation (if schema changes)

---

# Git Rules

Commit after each completed feature.

Commit message format:

feat(institution): implement CRUD

fix(question): validation issue

docs(architecture): update folder structure

refactor(service): simplify workflow

test(exam): add feature tests

---

# Code Review Checklist

Before merging:

☐ PSR-12 compliant

☐ Laravel Pint passed

☐ No duplicate logic

☐ Validation complete

☐ Authorization complete

☐ Feature tests passed

☐ No debug statements

☐ No commented dead code

☐ Documentation updated

---

# Enterprise Folder Philosophy

The project should always remain:

Predictable

Consistent

Maintainable

Testable

Scalable

Readable

Documented

Secure

---

# End of FOLDER_STRUCTURE.md