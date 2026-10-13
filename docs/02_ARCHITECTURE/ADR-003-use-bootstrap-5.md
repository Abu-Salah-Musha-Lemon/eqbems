# ADR-003: Use Bootstrap 5 for New Screens

- **Status:** Accepted as forward UI standard; migration incomplete
- **Scope:** Application UI styling for newly implemented feature screens

## Context

The repository includes Bootstrap 5 packages, Breeze/Tailwind assets, and legacy theme assets. Without a clear forward choice, new features can introduce further styling drift.

## Decision

Use Bootstrap 5 as the forward styling standard for new EQBEMS feature screens. Keep existing Breeze/Tailwind screens working during transition, then migrate or isolate them deliberately. Do not mix frameworks arbitrarily within a new feature.

## Consequences and work

- Confirm `resources/css/app.css`, Vite plugins, Blade layouts, and Bootstrap JS/CSS imports are consistent.
- Do not rely on legacy theme assets as the new baseline; review their actual version/license before reuse.
- Complete a UI migration plan before removing Tailwind dependencies.
- Use semantic markup, accessible form labels/errors, focus styles, and responsive layout.

## Implementation status

`package.json` declares Bootstrap 5.3.x and Tailwind/Vite dependencies, and existing Breeze views remain Tailwind-oriented. This ADR is a forward standard, not evidence of a unified frontend today.
