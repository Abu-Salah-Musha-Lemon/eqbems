<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blueprint_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blueprint_id')->constrained()->cascadeOnDelete();
            // A selection refers to the question, not a specific draft/approved version.
            // Generation always resolves the newest APPROVED version.
            $table->foreignId('question_id')->constrained()->restrictOnDelete();
            $table->timestamps();
            $table->unique(['blueprint_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blueprint_questions');
    }
};
