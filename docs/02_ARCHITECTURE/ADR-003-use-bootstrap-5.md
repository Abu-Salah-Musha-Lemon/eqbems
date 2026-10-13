---
adr_id: ADR-003
title: Adopt Bootstrap 5 as the Primary Frontend Framework
status: Accepted
date: 2026-08-02
decision_makers:
  - Solution Architect
  - System Analyst
  - Product Owner

review_date: 2027-08-02

related_documents:
  - SYSTEM_ARCHITECTURE.md
  - PROJECT_STANDARDS.md
  - UI_STANDARDS.md
---

# ADR-003: Adopt Bootstrap 5 as the Primary Frontend Framework

---

# Status

Accepted

---

# Context

EQBEMS is an enterprise-grade web application intended for educational institutions. The application requires a consistent, responsive, accessible, and maintainable user interface that can be developed rapidly without introducing unnecessary frontend complexity.

Version 1 will primarily use Laravel Blade templates for server-side rendering, making a lightweight frontend approach preferable.

The selected frontend framework should:

- Provide responsive layouts.
- Support accessibility.
- Reduce development time.
- Integrate seamlessly with Laravel.
- Be widely adopted.
- Be easy for new developers to learn.

---

# Problem Statement

Which frontend framework should be used for Version 1?

Possible options include:

- Bootstrap 5
- Tailwind CSS
- Material UI
- Bulma
- Foundation
- Custom CSS Framework

The selected solution should balance development speed, maintainability, consistency, and long-term support.

---

# Decision

EQBEMS will use **Bootstrap 5** as the primary CSS framework.

The frontend stack for Version 1 will consist of:

- Bootstrap 5
- Laravel Blade
- Vite
- Vanilla JavaScript

JavaScript should remain lightweight. Additional frontend libraries may be introduced only when justified by business requirements.

---

# Decision Drivers

The decision is based on:

- Excellent Laravel compatibility.
- Rapid UI development.
- Responsive grid system.
- Large developer community.
- Comprehensive documentation.
- Consistent UI components.
- Reduced custom CSS requirements.
- Long-term maintainability.

---

# Alternatives Considered

## Option 1 — Bootstrap 5

### Advantages

- Mature ecosystem
- Responsive by default
- Rich UI components
- Easy onboarding
- Stable documentation
- Strong browser compatibility

### Disadvantages

- Requires customization for unique branding.
- Default appearance is common unless themed.

**Decision:** Accepted

---

## Option 2 — Tailwind CSS

### Advantages

- Highly customizable
- Utility-first approach
- Smaller production CSS after optimization

### Disadvantages

- Steeper learning curve for beginners.
- HTML becomes more utility-class heavy.
- Slower initial development for CRUD-heavy applications.

**Decision:** Rejected for Version 1

---

## Option 3 — Material UI

### Advantages

- Professional appearance
- Rich component library

### Disadvantages

- Primarily intended for React.
- Adds unnecessary complexity for a Blade application.

**Decision:** Rejected

---

## Option 4 — Bulma

### Advantages

- Simple syntax
- Lightweight

### Disadvantages

- Smaller ecosystem.
- Fewer ready-made enterprise examples.

**Decision:** Rejected

---

## Option 5 — Custom CSS Framework

### Advantages

- Complete design freedom.

### Disadvantages

- High maintenance cost.
- Slower development.
- Inconsistent UI without strict governance.

**Decision:** Rejected

---

# Frontend Technology Stack

The approved frontend stack is:

| Layer | Technology |
|--------|------------|
| Templating | Laravel Blade |
| CSS Framework | Bootstrap 5 |
| JavaScript | Vanilla JavaScript |
| Asset Bundler | Vite |
| Icons | Bootstrap Icons |
| Fonts | Google Fonts (Project Approved Fonts) |

---

# UI Design Principles

The user interface should follow these principles:

- Consistency
- Simplicity
- Accessibility
- Responsiveness
- Readability
- Performance
- Maintainability

---

# Responsive Design Standards

The application shall support:

- Desktop
- Laptop
- Tablet
- Mobile

Layouts must use Bootstrap's responsive grid system.

---

# Accessibility Requirements

The interface should:

- Use semantic HTML.
- Support keyboard navigation.
- Maintain sufficient color contrast.
- Include labels for form controls.
- Display meaningful validation messages.

---

# Styling Standards

Developers should:

- Prefer Bootstrap utility classes.
- Minimize custom CSS.
- Use reusable Blade components.
- Avoid inline styles.
- Organize custom styles into dedicated files.

---

# JavaScript Standards

- Use Vanilla JavaScript whenever possible.
- Avoid unnecessary dependencies.
- Keep scripts modular.
- Defer script loading where appropriate.

---

# Consequences

## Positive

- Faster development.
- Consistent UI.
- Lower maintenance cost.
- Easier onboarding.
- Strong Laravel integration.
- Excellent browser compatibility.

## Negative

- Custom branding requires additional styling.
- Some advanced UI components may require third-party libraries.

---

# Risks

Potential risks:

- Excessive custom CSS.
- Inconsistent component usage.
- Overuse of JavaScript plugins.

Mitigation:

- UI standards.
- Code reviews.
- Shared Blade components.
- Reusable layouts.

---

# Future Evolution

Future versions may introduce:

- Tailwind CSS (if justified)
- Alpine.js
- Livewire
- Vue.js
- React (API-based frontend only)

Such changes require a new ADR and architectural approval.

---

# Related ADRs

- ADR-001 — Use Laravel 12
- ADR-002 — Adopt Modular Monolith Architecture
- ADR-004 — Use MySQL
- ADR-005 — Use Spatie Permission

---

# References

- Bootstrap 5 Documentation
- Laravel Blade Documentation
- Laravel Vite Documentation
- Web Content Accessibility Guidelines (WCAG)

---

# Revision History

| Version | Date | Description |
|----------|------------|-------------|
| 1.0.0 | 2026-08-02 | Initial frontend framework decision recorded |

---

# Approval

Status: Accepted

Approved By:

- Solution Architect
- Product Owner
- Technical Lead

Effective Date:

2026-08-02