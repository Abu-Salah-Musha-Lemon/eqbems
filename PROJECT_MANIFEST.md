# PROJECT MANIFEST

> **Project Name**
>
> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# Project Information

| Item | Value |
|------|-------|
| Project Name | Enterprise Question Bank & Examination Management System |
| Short Name | EQBEMS |
| Version | 0.1.0 (Development) |
| Project Type | Enterprise Web Application |
| Architecture | Modular Monolith |
| Framework | Laravel 12 |
| Frontend | Bootstrap 5 |
| Database | MySQL |
| PHP Version | 8.3+ |
| Authentication | Laravel Breeze |
| Authorization | Spatie Permission |
| Development Status | Active |

---

# Project Vision

Develop a modern, scalable, secure, and maintainable Question Bank &
Examination Management System that can be used by schools,
colleges, universities, training institutes, and certification
organizations.

The system must support multi-level academic structures,
role-based access control, centralized question banks,
blueprint generation, examination management,
result processing, and reporting.

---

# Project Goals

The primary goals are:

- Build an Enterprise-level Laravel application.
- Follow Clean Architecture principles.
- Maintain long-term scalability.
- Ensure maintainability.
- Keep documentation synchronized with development.
- Avoid technical debt.
- Follow coding standards.
- Build reusable modules.

---

# Current Development Stage

Current Phase

Development Foundation

Current Sprint

Sprint-02

Current Module

Institution Module

Current Status

Planning + Core Development

---

# Project Progress

| Module | Status |
|---------|---------|
| Authentication | ✅ Completed |
| Dashboard | ✅ Completed |
| RBAC | ✅ Completed |
| Institution | 🚧 In Progress |
| Academic | ⏳ Planned |
| Question Bank | ⏳ Planned |
| Examination | ⏳ Planned |
| Results | ⏳ Planned |
| Reports | ⏳ Planned |
| Settings | ⏳ Planned |

---

# Core Architecture

The project follows the following architecture.

Controller

↓

Form Request

↓

Service Layer

↓

Model

↓

Database

Authorization

↓

Policy

Audit

↓

Observer

Notifications

↓

Events

Background Tasks

↓

Queue

---

# Development Rules

Every module MUST contain

✔ Migration

✔ Model

✔ Observer

✔ Policy

✔ Form Request

✔ Service

✔ Controller

✔ Views

✔ Seeder

✔ Factory

✔ Feature Test

No module is considered complete without these components.

---

# Folder Responsibility

app/Models

Business Entities

app/Services

Business Logic

app/Policies

Authorization

app/Observers

Audit Logic

app/Http/Requests

Validation

resources/views

User Interface

routes/

Application Routing

database/

Schema & Seeders

tests/

Automated Tests

---

# Technology Stack

Backend

Laravel 12

PHP 8.3+

Frontend

Bootstrap 5

JavaScript

Blade

Database

MySQL

Authentication

Laravel Breeze

Authorization

Spatie Permission

Development

Composer

Vite

Git

GitHub

---

# Coding Standard

PSR-12

Strict Type Hinting

UUID Route Binding

Soft Delete

Service Layer

Form Requests

Policies

Observers

No Business Logic in Controllers

No Validation in Controllers

No Authorization in Controllers

---

# Documentation Rule

Before implementing a feature

↓

Documentation must be updated.

After implementation

↓

Documentation must be updated again.

Documentation is considered part of the source code.

---

# Development Workflow

Requirement

↓

Documentation

↓

Architecture

↓

Database

↓

Backend

↓

Frontend

↓

Testing

↓

Documentation Update

↓

Git Commit

---

# Git Convention

Branches

main

develop

feature/*

Commit Types

feat:

fix:

docs:

refactor:

style:

test:

chore:

---

# Module Development Order

Authentication

↓

Dashboard

↓

RBAC

↓

Institution

↓

Academic Year

↓

Faculty

↓

Department

↓

Program

↓

Semester

↓

Course

↓

Subject

↓

Question Bank

↓

Blueprint

↓

Exam

↓

Result

↓

Reports

↓

Settings

---

# Planned Major Features

Institution Management

Academic Management

Faculty Management

Department Management

Program Management

Semester Management

Course Management

Subject Management

Question Bank

Question Approval Workflow

Blueprint Generator

Exam Management

Result Processing

Reports

User Management

Role Management

Permission Management

Activity Log

Notification System

Backup Management

System Settings

---

# Future Features

AI Question Generator

Question Import

Excel Import

PDF Generator

REST API

Mobile Application

CBT Examination

OMR Processing

SMS Gateway

Email Notification

Multi-language

Cloud Storage

LMS Integration

---

# Current Priority

Finish Institution Module completely.

After completion

↓

Academic Structure Module

---

# Important Rule

The source of truth for this project is:

PROJECT_MANIFEST.md

All documentation inside

/docs/

must always remain synchronized
with the implementation.

If implementation differs from documentation,

documentation must be updated immediately.

---

Last Updated

Version 0.1.0

Status

Active Development



