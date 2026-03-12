<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('questions')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
                $table->text('question_text');
                $table->json('options');
                $table->integer('correct_option_index');
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('exam_attempts')) {
            Schema::create('exam_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->float('score')->default(0);
                $table->integer('total_questions')->default(0);
                $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('exam_attempt_answers')) {
            Schema::create('exam_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
                $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
                $table->integer('selected_option_index')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Keep down() non-destructive to avoid data loss.
    }
};

