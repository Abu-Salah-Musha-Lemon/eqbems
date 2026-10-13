# Project Overview

**Project:** Enterprise Question Bank & Examination Management System (EQBEMS)  
**Status:** Product concept with Laravel foundation and institution module in progress.

## Purpose

EQBEMS is intended to help educational institutions structure and govern question creation, review, reuse, examination planning, secure exam generation, grading, and results reporting in one system. The current repository is an early implementation foundation; this overview describes the intended product, not a claim that every capability already exists.

## Problem

Question content and examination administration are often spread across documents, email, spreadsheets, and manual approvals. That can result in duplicate questions, inconsistent academic classification, weak traceability, access-control gaps, and manual result preparation.

## Intended users

- System administrators who configure the application.
- Organization/institution administrators who manage scoped settings and accounts.
- Academic administrators who manage academic structures.
- Question setters who create content.
- Reviewers/moderators who approve and version questions.
- Examination controllers who define blueprints and generate exams.
- Teachers/graders who enter or verify marks where permitted.
- Students/candidates who may later access exam papers or results, depending on release scope.

Role names are candidate roles from the seeder/design, not proof that the full authorization workflows are implemented.

## Product boundaries

EQBEMS is a web application. It is not currently a learning-management system, video-proctoring platform, payment gateway, or automatic AI question generator. Those capabilities require separate product decisions and security/privacy reviews.

## Success measures (to baseline during pilot)

- Time to prepare an approved exam blueprint and paper.
- Percentage of questions with complete topic/difficulty/approval metadata.
- Rate of accidental duplicate question selection.
- Time from marking completion to controlled result publication.
- Number of unauthorized access attempts denied and audited.
- Number of production incidents caused by schema/migration failures.

Do not set numerical targets until a pilot establishes a baseline.
