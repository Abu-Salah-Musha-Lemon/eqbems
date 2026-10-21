<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
        });
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->restrictOnDelete();
            $table->string('label', 80);
            $table->date('starts_on');
            $table->date('ends_on');
            $table->timestamps();
            $table->unique(['institution_id', 'label']);
        });
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->restrictOnDelete();
            $table->string('code', 60);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['institution_id', 'code']);
        });
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->unique(['subject_id', 'name']);
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->restrictOnDelete();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['institution_id', 'subject_id']);
        });
        Schema::create('question_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('version_number');
            $table->enum('type', ['mcq', 'true_false', 'short']);
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->longText('body');
            $table->json('options')->nullable();
            $table->text('answer_text');
            $table->text('explanation')->nullable();
            $table->decimal('marks', 8, 2);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected']);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_note')->nullable();
            $table->timestamps();
            $table->unique(['question_id', 'version_number']);
            $table->index(['status', 'difficulty', 'type']);
        });
        Schema::create('blueprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->restrictOnDelete();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->unsignedInteger('question_count');
            $table->unsignedInteger('duration_minutes');
            $table->string('type', 20)->nullable();
            $table->string('difficulty', 20)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->restrictOnDelete();
            $table->foreignId('blueprint_id')->constrained()->restrictOnDelete();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->unsignedInteger('duration_minutes');
            $table->decimal('total_marks', 10, 2);
            $table->enum('status', ['draft', 'issued'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_version_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('position');
            $table->decimal('marks', 8, 2);
            $table->json('content_snapshot');
            $table->text('answer_snapshot');
            $table->timestamps();
            $table->unique(['exam_id', 'position']);
            $table->unique(['exam_id', 'question_version_id']);
        });
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->restrictOnDelete();
            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();
            $table->decimal('score', 10, 2);
            $table->string('grade', 2);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['exam_id', 'student_id']);
        });
        Schema::create('result_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained()->cascadeOnDelete();
            $table->decimal('previous_score', 10, 2);
            $table->decimal('new_score', 10, 2);
            $table->string('previous_grade', 2);
            $table->string('new_grade', 2);
            $table->text('reason');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['result_revisions', 'results', 'exam_questions', 'exams', 'blueprints', 'question_versions', 'questions', 'topics', 'subjects', 'academic_years'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropConstrainedForeignId('institution_id'));
    }
};
