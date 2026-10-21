<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('question_versions', function (Blueprint $table) {
            // Creative/CQ questions remain in the existing 'short' assessment category.
            $table->string('format', 20)->default('standard');
            $table->longText('stimulus')->nullable();
            $table->json('creative_parts')->nullable();
        });
        Schema::table('blueprints', fn (Blueprint $table) => $table->string('format', 20)->nullable());
        Schema::table('exams', function (Blueprint $table) {
            $table->string('exam_label')->nullable();
            $table->string('class_label')->nullable();
            $table->string('paper_code', 60)->nullable();
            $table->text('instructions')->nullable();
            $table->string('print_layout', 24)->default('standard');
            $table->boolean('online_enabled')->default(false);
            $table->timestamp('online_start_at')->nullable();
            $table->timestamp('online_end_at')->nullable();
        });
        Schema::create('online_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->restrictOnDelete();
            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();
            $table->enum('status', ['in_progress','submitted','graded'])->default('in_progress');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->decimal('awarded_marks', 10, 2)->nullable();
            $table->timestamps();
            $table->unique(['exam_id','student_id']);
        });
        Schema::create('online_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_exam_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_question_id')->constrained()->restrictOnDelete();
            $table->json('response')->nullable();
            $table->decimal('awarded_marks', 8, 2)->nullable();
            $table->json('awarded_parts')->nullable();
            $table->timestamps();
            $table->unique(['online_exam_attempt_id','exam_question_id'], 'online_answer_unique');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('online_exam_answers');
        Schema::dropIfExists('online_exam_attempts');
        Schema::table('exams', fn (Blueprint $table) => $table->dropColumn([
            'exam_label','class_label','paper_code','instructions','print_layout','online_enabled','online_start_at','online_end_at',
        ]));
        Schema::table('blueprints', fn (Blueprint $table) => $table->dropColumn('format'));
        Schema::table('question_versions', fn (Blueprint $table) => $table->dropColumn(['format','stimulus','creative_parts']));
    }
};
