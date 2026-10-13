---
document_id: DOC-008
document_name: Risk Register
project_name: Enterprise Question Bank & Examination Management System
project_short_name: EQBEMS
version: 1.0.0
status: Draft
document_owner: Abu Salah Musha Lemon
prepared_by: System Analysis & Architecture Team
last_updated: 2026-08-02

related_documents:
  - PROJECT_MANIFEST.md
  - PRODUCT_REQUIREMENTS.md
  - BUSINESS_REQUIREMENTS.md
  - PROJECT_SCOPE.md
---

# RISK REGISTER

> Enterprise Question Bank & Examination Management System (EQBEMS)

---

# 1. Purpose

This document identifies, assesses, and tracks potential risks that may affect the successful delivery of the EQBEMS project.

Each identified risk includes its probability, impact, priority, mitigation strategy, contingency plan, owner, and current status.

---

# 2. Risk Assessment Scale

## Probability

| Level | Meaning |
|--------|---------|
| Low | Unlikely to occur |
| Medium | May occur |
| High | Likely to occur |

## Impact

| Level | Meaning |
|--------|---------|
| Low | Minor effect |
| Medium | Noticeable impact |
| High | Major impact on project |

## Risk Priority

| Priority | Description |
|----------|-------------|
| Low | Monitor |
| Medium | Mitigate |
| High | Immediate attention required |
| Critical | Highest priority |

---

# 3. Risk Register

| ID | Risk | Category | Probability | Impact | Priority | Mitigation | Contingency | Owner | Status |
|----|------|----------|-------------|--------|----------|------------|-------------|-------|--------|
| RSK-001 | Requirements change frequently | Project | High | High | Critical | Freeze baseline after approval | Submit Change Request | Product Owner | Open |
| RSK-002 | Incomplete documentation | Documentation | Medium | High | High | Documentation-first approach | Review before coding | System Analyst | Open |
| RSK-003 | Database design issues | Technical | Medium | High | High | Design review & normalization | Schema refactoring | Architect | Open |
| RSK-004 | Security vulnerabilities | Security | Medium | High | Critical | Secure coding, validation, RBAC | Security patch | Developer | Open |
| RSK-005 | Performance degradation | Performance | Medium | Medium | Medium | Optimize queries & indexing | Performance tuning | Developer | Open |
| RSK-006 | User resistance to adoption | Business | Medium | Medium | Medium | User training | Provide support | Institution Admin | Open |
| RSK-007 | Data loss | Infrastructure | Low | High | High | Automated backups | Restore from backup | System Admin | Open |
| RSK-008 | Unauthorized access | Security | Low | High | High | Strong authentication | Account lock & audit | Security Admin | Open |
| RSK-009 | Delayed development | Project | Medium | Medium | Medium | Sprint planning | Re-prioritize backlog | Project Manager | Open |
| RSK-010 | Third-party package issues | Technical | Low | Medium | Low | Use stable packages | Replace package | Developer | Open |

---

# 4. Risk Categories

## Business Risks

- Changing business requirements
- User acceptance issues
- Organizational policy changes

---

## Technical Risks

- Architecture complexity
- Database performance
- Dependency conflicts
- Framework upgrades

---

## Security Risks

- SQL Injection
- Cross-Site Scripting (XSS)
- Cross-Site Request Forgery (CSRF)
- Broken Access Control
- Weak passwords

---

## Operational Risks

- Insufficient training
- Incorrect configuration
- Human error

---

## Infrastructure Risks

- Server failure
- Disk failure
- Network outage
- Backup failure

---

# 5. Risk Response Strategy

| Strategy | Description |
|----------|-------------|
| Avoid | Eliminate the risk completely |
| Mitigate | Reduce the likelihood or impact |
| Transfer | Shift responsibility to another party |
| Accept | Acknowledge and monitor the risk |

---

# 6. Risk Monitoring

The Risk Register shall be reviewed:

- Before each major release
- At the end of every sprint
- After significant requirement changes
- Before production deployment

---

# 7. Risk Escalation Rules

A risk must be escalated if:

- It becomes Critical.
- It blocks development.
- It affects project scope.
- It threatens release deadlines.
- It introduces major security concerns.

---

# 8. Risk Review Log

| Date | Reviewer | Summary | Action |
|------|----------|---------|--------|
| 2026-08-02 | System Analysis Team | Initial Risk Register created | Approved for Draft |

---

# 9. Related Documents

- PROJECT_MANIFEST.md
- PRODUCT_REQUIREMENTS.md
- BUSINESS_REQUIREMENTS.md
- PROJECT_SCOPE.md
- SYSTEM_ARCHITECTURE.md

---

# 10. Revision History

| Version | Date | Description |
|---------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial Risk Register created |

---

# 11. Approval

Document Status: Draft

Approval Status: Pending

Baseline Version: 1.0