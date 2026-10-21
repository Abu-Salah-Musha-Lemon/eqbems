<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\BlueprintController;
use App\Http\Controllers\BlueprintQuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\OnlineExamController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionReviewController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('institutions-archive', [InstitutionController::class, 'trashed'])->name('institutions.trashed');
    Route::post('institutions-archive/{uuid}/restore', [InstitutionController::class, 'restore'])->name('institutions.restore');
    Route::resource('institutions', InstitutionController::class);
    Route::resource('subjects', SubjectController::class);
    Route::post('subjects/{subject}/topics', [TopicController::class,'store'])->name('topics.store');
    Route::delete('subjects/{subject}/topics/{topic}', [TopicController::class,'destroy'])->name('topics.destroy');
    Route::get('academic-years', [AcademicYearController::class,'index'])->name('academic-years.index');
    Route::post('academic-years', [AcademicYearController::class,'store'])->name('academic-years.store');
    Route::delete('academic-years/{academicYear}', [AcademicYearController::class,'destroy'])->name('academic-years.destroy');

    // This static route must precede the questions/{question} resource show route.
    Route::get('questions/review-queue', [QuestionReviewController::class, 'index'])->name('questions.review-queue');
    Route::resource('questions', QuestionController::class)->except(['destroy']);
    Route::post('questions/{question}/submit', [QuestionController::class,'submit'])->name('questions.submit');
    Route::post('questions/{question}/review', [QuestionController::class,'review'])->name('questions.review');
    Route::post('questions/{question}/revise', [QuestionController::class,'revise'])->name('questions.revise');

    Route::get('blueprints/{blueprint}/questions', [BlueprintQuestionController::class, 'index'])->name('blueprints.questions.index');
    Route::post('blueprints/{blueprint}/questions/{question}', [BlueprintQuestionController::class, 'attach'])->name('blueprints.questions.attach');
    Route::delete('blueprints/{blueprint}/questions/{question}', [BlueprintQuestionController::class, 'detach'])->name('blueprints.questions.detach');
    Route::get('blueprints/availability', [BlueprintController::class, 'availability'])->name('blueprints.availability');
    Route::resource('blueprints', BlueprintController::class)->only(['index','create','store','edit','update','destroy']);
    Route::get('exams', [ExamController::class,'index'])->name('exams.index');
    Route::get('exams/{exam}', [ExamController::class,'show'])->name('exams.show');
    Route::get('exams/{exam}/print', [ExamController::class,'printPaper'])->name('exams.print');
    Route::get('exams/{exam}/settings', [ExamController::class,'editPaper'])->name('exams.settings');
    Route::put('exams/{exam}/settings', [ExamController::class,'updatePaper'])->name('exams.settings.update');
    Route::get('online-exams', [OnlineExamController::class,'index'])->name('online-exams.index');
    Route::post('online-exams/{exam}/start', [OnlineExamController::class,'start'])->name('online-exams.start');
    Route::get('online-exams/{exam}/take', [OnlineExamController::class,'take'])->name('online-exams.take');
    Route::post('online-exams/{exam}/save', [OnlineExamController::class,'save'])->name('online-exams.save');
    Route::post('online-exams/{exam}/submit', [OnlineExamController::class,'submit'])->name('online-exams.submit');
    Route::get('online-attempts', [OnlineExamController::class,'attempts'])->name('online-attempts.index');
    Route::get('online-attempts/{attempt}/grade', [OnlineExamController::class,'grade'])->name('online-attempts.grade');
    Route::post('online-attempts/{attempt}/grade', [OnlineExamController::class,'storeGrade'])->name('online-attempts.grade.store');
    Route::post('blueprints/{blueprint}/generate', [ExamController::class,'generate'])->name('exams.generate');
    Route::post('exams/{exam}/issue', [ExamController::class,'issue'])->name('exams.issue');
    Route::get('exams/{exam}/answer-key', [ExamController::class,'answers'])->name('exams.answers');

    Route::resource('results', ResultController::class)->only(['index','create','store','show','update']);
    Route::post('results/{result}/publish', [ResultController::class,'publish'])->name('results.publish');

    Route::middleware('role:Super Admin')->resource('users', UserController::class);
});

require __DIR__.'/auth.php';
