# EQBEMS — Routes, Roles, Permissions and Authorization

**Authoritative route definitions:** `routes/web.php`, `routes/auth.php`. Most application routes run inside `Route::middleware('auth')`; User CRUD additionally uses `role:Super Admin`. Controller-level permission and institution checks provide additional protection. Generate the complete runtime route list after Composer installation with `php artisan route:list`.

## HTTP URL map

| Category | HTTP method and URL | Controller/action | Route name |
|---|---|---|---|
| Landing | GET `/` | `Route::view('welcome')` | none |
| Dashboard | GET `/dashboard` | `DashboardController@index` | `dashboard` |
| Profile | GET `/profile`, PATCH `/profile`, DELETE `/profile` | `ProfileController@edit/update/destroy` | `profile.edit/update/destroy` |
| Institutions | `Route::resource('institutions')` | InstitutionController RESTful CRUD | `institutions.*` |
| Institution archive | GET `/institutions-archive` | `InstitutionController@trashed` | `institutions.trashed` |
| Restore archive | POST `/institutions-archive/{uuid}/restore` | `InstitutionController@restore` | `institutions.restore` |
| Subjects | `Route::resource('subjects')` | SubjectController RESTful CRUD | `subjects.*` |
| Topics | POST `/subjects/{subject}/topics` | `TopicController@store` | `topics.store` |
| Topics | DELETE `/subjects/{subject}/topics/{topic}` | `TopicController@destroy` | `topics.destroy` |
| Academic years | GET `/academic-years`; POST `/academic-years` | AcademicYearController index/store | `academic-years.index/store` |
| Academic years | DELETE `/academic-years/{academicYear}` | `AcademicYearController@destroy` | `academic-years.destroy` |
| Review queue | GET `/questions/review-queue` | `QuestionReviewController@index` | `questions.review-queue` |
| Questions | `Route::resource('questions')->except(['destroy'])` | QuestionController RESTful actions except destroy | `questions.*` |
| Question submission | POST `/questions/{question}/submit` | `QuestionController@submit` | `questions.submit` |
| Question review | POST `/questions/{question}/review` | `QuestionController@review` | `questions.review` |
| New revision | POST `/questions/{question}/revise` | `QuestionController@revise` | `questions.revise` |
| Blueprints | resource index/create/store/edit/update/destroy | `BlueprintController` | `blueprints.*` |
| Question pool preview | GET `/blueprints/availability` | `BlueprintController@availability` | `blueprints.availability` |
| Blueprint questions | GET `/blueprints/{blueprint}/questions` | `BlueprintQuestionController@index` | `blueprints.questions.index` |
| Pin a question | POST `/blueprints/{blueprint}/questions/{question}` | `BlueprintQuestionController@attach` | `blueprints.questions.attach` |
| Unpin a question | DELETE `/blueprints/{blueprint}/questions/{question}` | `BlueprintQuestionController@detach` | `blueprints.questions.detach` |
| Generate exam | POST `/blueprints/{blueprint}/generate` | `ExamController@generate` | `exams.generate` |
| Exam list | GET `/exams` | `ExamController@index` | `exams.index` |
| Exam detail | GET `/exams/{exam}` | `ExamController@show` | `exams.show` |
| Printable exam | GET `/exams/{exam}/print` | `ExamController@printPaper` | `exams.print` |
| Exam settings | GET `/exams/{exam}/settings`; PUT same path | `ExamController@editPaper/updatePaper` | `exams.settings`, `exams.settings.update` |
| Issue paper | POST `/exams/{exam}/issue` | `ExamController@issue` | `exams.issue` |
| Answer key | GET `/exams/{exam}/answer-key` | `ExamController@answers` | `exams.answers` |
| Student online exam list | GET `/online-exams` | `OnlineExamController@index` | `online-exams.index` |
| Start / take | POST `/online-exams/{exam}/start`; GET `/online-exams/{exam}/take` | OnlineExamController start/take | `online-exams.start/take` |
| Save / submit answers | POST `/online-exams/{exam}/save`; POST `/online-exams/{exam}/submit` | OnlineExamController save/submit | `online-exams.save/submit` |
| Staff grading queue | GET `/online-attempts` | `OnlineExamController@attempts` | `online-attempts.index` |
| Manual grading | GET / POST `/online-attempts/{attempt}/grade` | OnlineExamController grade/storeGrade | `online-attempts.grade`, `online-attempts.grade.store` |
| Results | resource index/create/store/show/update | `ResultController` | `results.index/create/store/show/update` |
| Publish result | POST `/results/{result}/publish` | `ResultController@publish` | `results.publish` |
| Users | `Route::resource('users')` behind role Super Admin | UserController | `users.*` |
| Auth | `/login`, `/logout`, `/register`, password reset, verify email | `Auth/*` controllers | `routes/auth.php` |

**Resource conventions:** `GET index`, `GET create`, `POST store`, `GET show`, `GET edit`, `PUT/PATCH update`, `DELETE destroy`. Some resources restrict actions as stated above. Names can be verified using `php artisan route:list --path=questions` etc.

**Route ordering detail:** `/questions/review-queue` is registered **before** `questions/{question}` (otherwise route model binding could interpret `review-queue` as an ID).

## Permissions (declared by PermissionSeeder)

`dashboard.view`  
`user.view`, `user.create`, `user.edit`, `user.delete`  
`institution.view`, `institution.create`, `institution.update`, `institution.delete`, `institution.restore`  
`academic.view`, `academic.manage`  
`question.view`, `question.create`, `question.review`  
`blueprint.view`, `blueprint.manage`  
`exam.view`, `exam.generate`, `exam.issue`, `exam.answers`  
`result.view`, `result.mark`, `result.publish`

## Role-permission mapping (exact seeded intent)

| Role | Scope | Notable permissions |
|---|---|---|
| Super Admin | All institutions | All permissions in PermissionSeeder; UserController resource also requires this role |
| Organization Admin | Their own institution (current AccessScope behavior) | academic/manage; question/view/create/review; blueprint/view/manage; exam/view/generate; result/view; institution/view |
| Institution Admin | Their own institution | Same seeded permissions as Organization Admin |
| Teacher | Their institution | question/view/create/review; blueprint/view; exam/view; result/view/mark |
| Question Setter | Their institution | academic/view; question/view/create |
| Moderator | Their institution | academic/view; question/view/review; blueprint/view |
| Exam Controller | Their institution | blueprint/manage; exam/generate/issue/answers; question/review; result/mark/publish and other read rights |
| Student | Their institution | dashboard.view; online-exam endpoints and own published result controlled by explicit controller checks |

**Do not assume `Organization Admin` currently has global organization-wide scope.** The present `AccessScope` uses only `Super Admin` as global and `institution_id` for everyone else; organization entities are not modeled as a full management module in this source.

## Authorization pattern

```php
abort_unless(
    $request->user()->can('exam.generate')
        && AccessScope::allows($request->user(), $blueprint->institution_id),
    403
);
```

This protects **both capability** and **tenant ownership**. For institutions, `InstitutionPolicy` is used via `$this->authorize(...)`. For actions operating on a student result, the controller also ensures ownership and published status. Never rely solely on hiding buttons in Blade: controller permissions and tenant checks are the security boundary.

## Authentication and initial admin

- Public self-registration is off by default via `config/features.php` / `.env` `ALLOW_PUBLIC_REGISTRATION=false`.
- Login is provided by Breeze-style auth controllers.
- Users can be created/assigned roles through the Super Admin-only `users` resource.
- Seeder only creates an initial Super Admin when **both** `ADMIN_EMAIL` and `ADMIN_PASSWORD` are set (password must be at least 12 characters). There is **no fixed default password**.
- `AdminUserSeeder` should be treated as initial bootstrap only; clear bootstrap credentials after use.

## Tests a security-sensitive pull request should contain

- Unauthenticated request is redirected/rejected as expected.
- User without capability gets HTTP 403.
- User with capability but wrong institution gets HTTP 403.
- A question author cannot approve their own question.
- Student cannot request another student's result or access paper answer snapshots.
- Non-student cannot access online exam attempt routes as a student.
- Last Super Admin cannot be deleted through profile or admin UserController.
