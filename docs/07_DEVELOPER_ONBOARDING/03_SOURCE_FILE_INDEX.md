# Exact source file index

Inventory of **555 original files**, before adding the documentation pack. Generated `07_DEVELOPER_ONBOARDING/` files are not included here.

A file marked **EMPTY** is physically zero bytes in the reviewed archive. Its folder/name must not be used as evidence of a working module.

**Original zero-byte files:** 17.

## `root/` — 27 files

| File | Developer purpose | Status |
|---|---|---|
| `.editorconfig` | Project configuration, documentation or supporting file | Present |
| `.env.example` | Safe starting configuration template; no live secrets | Present |
| `.gitattributes` | Project configuration, documentation or supporting file | Present |
| `.gitignore` | Files intentionally not tracked in Git | Present |
| `ADMIN_PANEL_GUIDE.md` | Project configuration, documentation or supporting file | Present |
| `BUILD_VERIFICATION.md` | Project configuration, documentation or supporting file | Present |
| `CONTRIBUTING.md` | Project configuration, documentation or supporting file | Present |
| `FACTORY_SEEDING_GUIDE.md` | Project configuration, documentation or supporting file | Present |
| `FIXES_AND_VERIFICATION.md` | Project configuration, documentation or supporting file | Present |
| `LICENSE` | Empty license placeholder; legal license text not provided | **EMPTY** |
| `PROJECT_MANIFEST.md` | Project configuration, documentation or supporting file | Present |
| `PROJECT_README_ORIGINAL.md` | Project configuration, documentation or supporting file | Present |
| `QUESTION_REVIEW_APPROVAL_FIX_GUIDE.md` | Project configuration, documentation or supporting file | Present |
| `README.md` | Existing installation and error-resolution documentation | Present |
| `SECURITY.md` | Project configuration, documentation or supporting file | Present |
| `SEEDING_GUIDE.md` | Project configuration, documentation or supporting file | Present |
| `artisan` | Laravel CLI entrypoint | Present |
| `composer.json` | PHP dependency manifest and Composer scripts | Present |
| `composer.lock` | Exact locked PHP dependency versions | Present |
| `package-lock.json` | Exact locked Node dependency versions | Present |
| `package.json` | Node packages and Vite scripts | Present |
| `phpstan.neon` | Project configuration, documentation or supporting file | **EMPTY** |
| `phpunit.xml` | Test environment configuration | Present |
| `pint.json` | Project configuration, documentation or supporting file | Present |
| `postcss.config.js` | Project configuration, documentation or supporting file | Present |
| `tailwind.config.js` | Project configuration, documentation or supporting file | Present |
| `vite.config.js` | Vite asset entrypoint configuration | Present |

## `.github/` — 1 files

| File | Developer purpose | Status |
|---|---|---|
| `.github/workflows/quality.yml` | CI / GitHub Actions configuration | Present |

## `app/` — 63 files

| File | Developer purpose | Status |
|---|---|---|
| `app/Actions/Institution/CreateInstitutionAction.php` | Proposed action class; check file size before assuming implemented | **EMPTY** |
| `app/Actions/Institution/DeleteInstitutionAction.php` | Proposed action class; check file size before assuming implemented | **EMPTY** |
| `app/Actions/Institution/UpdateInstitutionAction.php` | Proposed action class; check file size before assuming implemented | **EMPTY** |
| `app/DTO/InstitutionData.php` | Proposed data transfer object; check whether implemented | **EMPTY** |
| `app/DTO/QuestionData.php` | Proposed data transfer object; check whether implemented | **EMPTY** |
| `app/Helpers/helpers.php` | Project configuration, documentation or supporting file | Present |
| `app/Http/Controllers/AcademicYearController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/ConfirmablePasswordController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/EmailVerificationNotificationController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/EmailVerificationPromptController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/NewPasswordController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/PasswordController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/PasswordResetLinkController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/Auth/VerifyEmailController.php` | Authentication endpoint and session/account workflow | Present |
| `app/Http/Controllers/BlueprintController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/BlueprintQuestionController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/Controller.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/DashboardController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/ExamController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/InstitutionController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/OnlineExamController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/ProfileController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/QuestionController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/QuestionReviewController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/ResultController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/SubjectController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/TopicController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Controllers/UserController.php` | HTTP request handler; maps route actions to authorization, business logic and Blade views | Present |
| `app/Http/Requests/Auth/LoginRequest.php` | Laravel Form Request validation and (where implemented) authorization | Present |
| `app/Http/Requests/Institution/StoreInstitutionRequest.php` | Active institution-specific Form Request used by InstitutionController | Present |
| `app/Http/Requests/Institution/UpdateInstitutionRequest.php` | Active institution-specific Form Request used by InstitutionController | Present |
| `app/Http/Requests/ProfileUpdateRequest.php` | Laravel Form Request validation and (where implemented) authorization | Present |
| `app/Http/Requests/StoreInstitutionRequest.php` | Laravel Form Request validation and (where implemented) authorization | **EMPTY** |
| `app/Http/Requests/UpdateInstitutionRequest.php` | Laravel Form Request validation and (where implemented) authorization | **EMPTY** |
| `app/Models/AcademicYear.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Blueprint.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Dashboard.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Exam.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/ExamQuestion.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Institution.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/OnlineExamAnswer.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/OnlineExamAttempt.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Question.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/QuestionVersion.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Result.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/ResultRevision.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Subject.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/Topic.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Models/User.php` | Eloquent model; persistence, casts and relationships | Present |
| `app/Policies/InstitutionPolicy.php` | Model authorization policy (verify whether file contains actual logic) | Present |
| `app/Policies/QuestionPolicy.php` | Model authorization policy (verify whether file contains actual logic) | **EMPTY** |
| `app/Providers/AppServiceProvider.php` | Service provider / application bindings | Present |
| `app/Services/BlueprintPoolService.php` | Business/domain logic used by controllers | Present |
| `app/Services/ExamGenerationService.php` | Business/domain logic used by controllers | Present |
| `app/Services/GradeService.php` | Business/domain logic used by controllers | Present |
| `app/Services/InstitutionService.php` | Business/domain logic used by controllers | Present |
| `app/Services/QuestionService.php` | Business/domain logic used by controllers | Present |
| `app/Services/UserService.php` | Business/domain logic used by controllers | **EMPTY** |
| `app/Support/AccessScope.php` | Cross-cutting authorization/scoping helper | Present |
| `app/View/Components/AppLayout.php` | Blade component backing class | Present |
| `app/View/Components/GuestLayout.php` | Blade component backing class | Present |

## `bootstrap/` — 5 files

| File | Developer purpose | Status |
|---|---|---|
| `bootstrap/app.php` | Laravel bootstrap, middleware aliases, cached bootstrap metadata | Present |
| `bootstrap/cache/.gitignore` | Files intentionally not tracked in Git | Present |
| `bootstrap/cache/packages.php` | Laravel bootstrap, middleware aliases, cached bootstrap metadata | Present |
| `bootstrap/cache/services.php` | Laravel bootstrap, middleware aliases, cached bootstrap metadata | Present |
| `bootstrap/providers.php` | Laravel bootstrap, middleware aliases, cached bootstrap metadata | Present |

## `config/` — 14 files

| File | Developer purpose | Status |
|---|---|---|
| `config/app.php` | Laravel or package runtime configuration | Present |
| `config/auth.php` | Laravel or package runtime configuration | Present |
| `config/cache.php` | Laravel or package runtime configuration | Present |
| `config/database.php` | Laravel or package runtime configuration | Present |
| `config/features.php` | Laravel or package runtime configuration | Present |
| `config/filesystems.php` | Laravel or package runtime configuration | Present |
| `config/logging.php` | Laravel or package runtime configuration | Present |
| `config/mail.php` | Laravel or package runtime configuration | Present |
| `config/permission.php` | Laravel or package runtime configuration | Present |
| `config/queue.php` | Laravel or package runtime configuration | Present |
| `config/services.php` | Laravel or package runtime configuration | Present |
| `config/session.php` | Laravel or package runtime configuration | Present |
| `config/settings.php` | Laravel or package runtime configuration | **EMPTY** |
| `config/view.php` | Laravel or package runtime configuration | Present |

## `database/` — 24 files

| File | Developer purpose | Status |
|---|---|---|
| `database/.gitignore` | Files intentionally not tracked in Git | Present |
| `database/factories/InstitutionFactory.php` | Model test/demo fixture factory | Present |
| `database/factories/QuestionFactory.php` | Model test/demo fixture factory | Present |
| `database/factories/QuestionVersionFactory.php` | Model test/demo fixture factory | Present |
| `database/factories/SubjectFactory.php` | Model test/demo fixture factory | Present |
| `database/factories/TopicFactory.php` | Model test/demo fixture factory | Present |
| `database/factories/UserFactory.php` | Model test/demo fixture factory | Present |
| `database/migrations/0001_01_01_000000_create_users_table.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/0001_01_01_000001_create_cache_table.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/0001_01_01_000002_create_jobs_table.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/2026_07_04_190837_create_permission_tables.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/2026_07_05_142437_create_institutions_table.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/2026_10_20_000001_create_academic_and_exam_tables.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/2026_10_20_000002_create_online_exam_and_paper_format_tables.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/migrations/2026_10_20_000003_create_blueprint_questions_table.php` | Authoritative schema migration; applied by php artisan migrate | Present |
| `database/seeders/AdminUserSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/BoardFormatDemoSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/DatabaseSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/PermissionSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/QuestionSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/ReviewWorkflowDemoSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/RolePermissionSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/RoleSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |
| `database/seeders/SubjectSeeder.php` | Reference data / roles / demo data insertion (inspect environment behavior) | Present |

## `docs/` — 48 files

| File | Developer purpose | Status |
|---|---|---|
| `docs/00_START_HERE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/01_PROJECT_OVERVIEW.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/02_BUSINESS_REQUIREMENTS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/ARCHITECTURE_DECISION_RECORDS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/ASSUMPTIONS_AND_CONSTRAINTS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/FEATURE_CATALOG.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/GLOSSARY.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/IMPLEMENTATION_BACKLOG.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/PRODUCT_REQUIREMENTS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/PROJECT_SCOPE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/PROJECT_STANDARDS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/RISK_REGISTER.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/ROADMAP.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/STAKEHOLDERS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/01_FOUNDATION/VISION_AND_OBJECTIVES.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-001-use-laravel-12.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-002-modular-monolith.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-003-use-bootstrap-5.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-004-use-mysql.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-005-use-spatie-permission.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-006-service-layer.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ADR-007-form-request-validation.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/ARCHITECTURE_DECISION_RECORDS.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/FOLDER_STRUCTURE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/MODULE_DEPENDENCY_MAP.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/SECURITY_ARCHITECTURE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/02_ARCHITECTURE/SYSTEM_ARCHITECTURE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/03_DATABASE/DATABASE_DESIGN.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/03_DATABASE/DATABASE_DICTIONARY.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/03_DATABASE/ERD.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/03_DATABASE/MIGRATION_PLAN.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/04_DEVELOPMENT/CODING_GUIDELINES.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/04_DEVELOPMENT/DEVELOPMENT_WORKFLOW.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/04_DEVELOPMENT/LOCAL_SETUP.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/04_DEVELOPMENT/TESTING.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/05_OPERATIONS/BACKUP_AND_RECOVERY.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/05_OPERATIONS/DEPLOYMENT.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/05_OPERATIONS/OBSERVABILITY.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/06_MODULE_SPECS/ACADEMIC_MODULE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/06_MODULE_SPECS/AUTH_AND_RBAC.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/06_MODULE_SPECS/EXAMINATION_MODULE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/06_MODULE_SPECS/INSTITUTION_MODULE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/06_MODULE_SPECS/QUESTION_BANK_MODULE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/06_MODULE_SPECS/RESULTS_MODULE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/BLUEPRINT_QUESTION_WORKFLOW.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/BLUEPRINT_REPAIR_GUIDE.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/EXAM_PAPER_TEMPLATES.md` | Project notes/specifications; verify against runtime code | Present |
| `docs/README.md` | Existing installation and error-resolution documentation | Present |

## `public/` — 270 files

| File | Developer purpose | Status |
|---|---|---|
| `public/.htaccess` | Web-exposed static/legacy assets (not necessarily source for Vite) | Present |
| `public/css/animate.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/aos.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/bootstrap.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/button-builder.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/calendar.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/chartist.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/color-1.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/datatable-extension.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/datatables.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/date-picker.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/daterange-picker.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/dropzone.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/feather-icon.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/flag-icon.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/fontawesome.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/icofont.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/image-cropper.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/jkanban.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/jsgrid.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/mapsjs-ui.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/owlcarousel.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/page-builder.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/photoswipe.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/prism.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/range-slider.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/rating.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/responsive.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/scrollable.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/select2.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/simple-mde.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/slick-theme.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/slick.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/sticky.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/style.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/summernote.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/sweetalert2.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/themify.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/timepicker.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/todo.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/tour.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/tree.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/vector-map.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/css/whether-icon.css` | Static/legacy public asset; verify usage before modifying | Present |
| `public/favicon.ico` | Web-exposed static/legacy assets (not necessarily source for Vite) | **EMPTY** |
| `public/index.php` | Web-exposed static/legacy assets (not necessarily source for Vite) | Present |
| `public/js/animation/animate-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/aos/aos-init.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/aos/aos.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/scroll-reveal/reveal-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/scroll-reveal/scrollreveal.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/tilt/tilt-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/tilt/tilt.jquery.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/wow/wow-init.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/animation/wow/wow.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/bookmark/custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/bookmark/jquery.validate.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/bootstrap/bootstrap.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/bootstrap/popper.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/button-builder/clipboard.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/button-builder/colorpicker.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/button-builder/extend-1.0.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/button-builder/generator.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/app.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/calendars.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/chance.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/moment.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/schedules.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/tui-calendar.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/tui-code-snippet.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/tui-date-picker.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/calendar/tui-time-picker.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/apex-chart/apex-chart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/apex-chart/chart-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/apex-chart/moment.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/apex-chart/stock-prices.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/chartist/chartist-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/chartist/chartist-plugin-tooltip.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/chartist/chartist.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/chartjs/chart.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/chartjs/chart.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/excanvas.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/flot-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/jquery.flot.categories.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/jquery.flot.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/jquery.flot.pie.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/jquery.flot.stack.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/jquery.flot.symbol.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/flot-chart/jquery.flot.time.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/google/google-chart-loader.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/google/google-chart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/knob/knob-chart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/knob/knob.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/morris-chart/morris-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/morris-chart/morris.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/morris-chart/morris.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/morris-chart/prettify.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/morris-chart/raphael.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/peity-chart/peity-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/peity-chart/peity.jquery.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/sparkline/sparkline-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart/sparkline/sparkline.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/chart-widget.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/clipboard/clipboard-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/clipboard/clipboard.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/config.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/contacts/custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/countdown.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/counter/counter-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/counter/jquery.counterup.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/counter/jquery.waypoints.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/custom-card/custom-card.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/dashboard/dashboard_2.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/dashboard/default.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/buttons.bootstrap4.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/buttons.colVis.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/buttons.html5.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/buttons.print.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.autoFill.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.bootstrap4.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.buttons.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.colReorder.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.fixedHeader.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.keyTable.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.responsive.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.rowReorder.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.scroller.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/dataTables.select.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/jszip.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/pdfmake.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/responsive.bootstrap4.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatable-extension/vfs_fonts.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatables/datatable.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datatable/datatables/jquery.dataTables.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/date-picker/datepicker.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/date-picker/datepicker.en.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/date-picker/datepicker.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/date-time-picker/datetimepicker.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/date-time-picker/moment.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/daterange-picker/daterange-picker.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/daterange-picker/daterangepicker.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/datepicker/daterange-picker/moment.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/dragable/sortable-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/dragable/sortable.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/dropzone/dropzone-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/dropzone/dropzone.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/ecommerce.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/ace-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/ace.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/mode-css.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/mode-html.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/mode-javascript.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/mode-php.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/theme-monokai.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/worker-css.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/worker-html.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/worker-javascript.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ace-editor/worker-php.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ckeditor/adapters/jquery.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ckeditor/ckeditor.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ckeditor/ckeditor.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/ckeditor/styles.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/simple-mde/simplemde.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/simple-mde/simplemde.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/summernote/summernote.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/editor/summernote/summernote.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/email-app.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-builder/form-builder-1/main-built.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-builder/form-builder-1/require.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-builder/form-builder-2/beautifyhtml.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-builder/form-builder-2/form-builder-2.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-validation-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-wizard/form-wizard-three.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-wizard/form-wizard-two.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-wizard/form-wizard.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/form-wizard/jquery.backstretch.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/fullscreen.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/general-widget.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/height-equal.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/feather-icon/feather-icon-clipart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/feather-icon/feather-icon.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/feather-icon/feather.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/flag-icon-clipart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/icon-clipart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/icons-notify.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/icons/whether-icon-clipart.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/image-cropper/cropper-main.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/image-cropper/cropper.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/internationalization/language-picker.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/isotope.pkgd.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jarallax_libs/libs.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jkanban/custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jkanban/jkanban.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jquery-3.5.1.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jquery.ui.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jsgrid/griddata.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jsgrid/jsgrid.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/jsgrid/jsgrid.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/landing.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/landing_sticky.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/map-js/custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/map-js/mapsjs-core.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/map-js/mapsjs-mapevents.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/map-js/mapsjs-service.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/map-js/mapsjs-ui.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/masonry-gallery.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/modal-animated.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/modernizr.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/notify/bootstrap-notify.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/notify/index.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/notify/notify-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/owlcarousel/owl-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/owlcarousel/owl.carousel.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/page-builder/jquery.grideditor.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/page-builder/page-builder-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/photoswipe/photoswipe-ui-default.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/photoswipe/photoswipe.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/photoswipe/photoswipe.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/popover-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/print.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/prism/prism.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/product-list-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/product-tab.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/range-slider/ion.rangeSlider.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/range-slider/rangeslider-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/rating/jquery.barrating.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/rating/rating-script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/script.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/scrollable/perfect-scrollbar.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/scrollable/scrollable-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/select2/select2-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/select2/select2.full.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/sidebar-menu.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/slick-slider/slick-theme.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/slick-slider/slick.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/sticky/sticky.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/support-ticket-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/sweet-alert/app.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/sweet-alert/sweetalert.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/theme-customizer/customizer.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/time-picker/clockpicker.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/time-picker/highlight.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/time-picker/jquery-clockpicker.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/timeline/timeline-v-1/main.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/timeline/timeline-v-2/jquery.timeliny.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/timeline/timeline-v-2/timeline-v-2-custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/todo/todo.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/tooltip-init.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/touchspin/input-groups.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/touchspin/touchspin.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/touchspin/vendors.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/tour/intro-init.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/tour/intro.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/tree/jstree.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/tree/tree.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/typeahead/handlebars.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/typeahead/typeahead.bundle.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/typeahead/typeahead.custom.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/jquery-jvectormap-2.0.2.min.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-asia-mill.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-au-mill.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-in-mill.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-uk-mill-en.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-us-aea-en.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map/jquery-jvectormap-world-mill-en.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/js/vector-map/map-vector.js` | Static/legacy public asset; verify usage before modifying | Present |
| `public/robots.txt` | Web-exposed static/legacy assets (not necessarily source for Vite) | Present |

## `resources/` — 75 files

| File | Developer purpose | Status |
|---|---|---|
| `resources/css/app.css` | Editable CSS input source for frontend | Present |
| `resources/css/dashboard.css` | Editable CSS input source for frontend | **EMPTY** |
| `resources/js/app.js` | Editable JavaScript input source for frontend | Present |
| `resources/js/bootstrap.js` | Editable JavaScript input source for frontend | Present |
| `resources/js/dashboard.js` | Editable JavaScript input source for frontend | **EMPTY** |
| `resources/views/auth/confirm-password.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/auth/forgot-password.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/auth/login.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/auth/register.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/auth/reset-password.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/auth/verify-email.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/blueprints/form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/blueprints/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/blueprints/questions.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/application-logo.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/auth-session-status.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/danger-button.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/dropdown-link.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/dropdown.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/input-error.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/input-label.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/modal.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/nav-link.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/primary-button.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/responsive-nav-link.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/secondary-button.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/components/text-input.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/dashboard/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/dashboard.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/exams/answers.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/exams/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/exams/print.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/exams/settings.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/exams/show.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/institutions/_partials/form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/institutions/create.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/institutions/edit.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/institutions/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/institutions/show.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/institutions/trashed.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/layouts/admin.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/layouts/app.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/layouts/guest.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/layouts/layouts.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/layouts/navigation.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/online/attempts.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/online/grade.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/online/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/online/take.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/partials/alerts.blade.php` | Rendered Blade HTML template | **EMPTY** |
| `resources/views/partials/breadcrumb.blade.php` | Rendered Blade HTML template | **EMPTY** |
| `resources/views/partials/footer.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/partials/navbar.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/partials/sidebar.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/profile/edit.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/profile/partials/delete-user-form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/profile/partials/update-password-form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/profile/partials/update-profile-information-form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/questions/form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/questions/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/questions/review-queue.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/questions/show.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/results/form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/results/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/results/show.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/subjects/form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/subjects/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/subjects/show.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/users/_form.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/users/create.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/users/edit.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/users/index.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/users/show.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/welcome.blade.php` | Rendered Blade HTML template | Present |
| `resources/views/years/index.blade.php` | Rendered Blade HTML template | Present |

## `routes/` — 3 files

| File | Developer purpose | Status |
|---|---|---|
| `routes/auth.php` | Declared HTTP or console routes | Present |
| `routes/console.php` | Declared HTTP or console routes | Present |
| `routes/web.php` | Declared HTTP or console routes | Present |

## `scripts/` — 1 files

| File | Developer purpose | Status |
|---|---|---|
| `scripts/verify-source.sh` | Developer helper script | Present |

## `storage/` — 5 files

| File | Developer purpose | Status |
|---|---|---|
| `storage/app/.gitignore` | Files intentionally not tracked in Git | Present |
| `storage/app/private/.gitignore` | Files intentionally not tracked in Git | Present |
| `storage/app/public/.gitignore` | Files intentionally not tracked in Git | Present |
| `storage/framework/.gitignore` | Files intentionally not tracked in Git | Present |
| `storage/framework/testing/.gitignore` | Files intentionally not tracked in Git | Present |

## `tests/` — 19 files

| File | Developer purpose | Status |
|---|---|---|
| `tests/Feature/Auth/AuthenticationTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/Auth/EmailVerificationTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/Auth/PasswordConfirmationTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/Auth/PasswordResetTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/Auth/PasswordUpdateTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/Auth/RegistrationTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/BlueprintQuestionManagementTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/BlueprintRepairTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/DemoSeederTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/DomainWorkflowTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/ExamPaperTemplateTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/ExampleTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/ProfileTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/QuestionReviewQueueTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Feature/RegressionSafeguardsTest.php` | Integration/HTTP-behavior test (requires Laravel runtime) | Present |
| `tests/Pest.php` | Project configuration, documentation or supporting file | Present |
| `tests/TestCase.php` | Project configuration, documentation or supporting file | Present |
| `tests/Unit/ExampleTest.php` | Isolated unit test | Present |
| `tests/Unit/GradeServiceTest.php` | Isolated unit test | Present |

