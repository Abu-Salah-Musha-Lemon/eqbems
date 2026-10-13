# Changelog

All notable project changes should be recorded here. The project has no verified tagged release yet, so entries describe repository milestones rather than a formal semantic-version release.

## [Unreleased]

### Documentation
- Added a canonical start-here guide and documentation index.
- Reconciled project status with the checked-in routes, controllers, models, migrations, seeders, and package manifests.
- Added setup, testing, architecture, database, security, operations, and module planning documents.
- Explicitly separated implemented database schema from proposed target schema.

### Known work not yet completed
- Add Spatie `HasRoles` to the user model and reconcile permission names.
- Wire and test institution service injection and pagination.
- Complete institution views and remove duplicate request classes.
- Reconcile dashboard routes/views and implement real dashboard data.
- Add feature tests for application-specific workflows.
- Implement academic, question-bank, exam, and result domain migrations/features.

## Release policy

For future releases, use headings such as `## [0.1.0] - YYYY-MM-DD`, group changes under Added, Changed, Fixed, Security, Deprecated, and Removed, and link to a repository tag where available. Do not describe a feature as released unless it is included in a tagged build and tested.
