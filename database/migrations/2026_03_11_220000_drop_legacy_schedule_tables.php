<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop child tables first to satisfy foreign key constraints.
        if (Schema::hasTable('timetables')) {
            Schema::drop('timetables');
        }

        if (Schema::hasTable('attendances')) {
            Schema::drop('attendances');
        }

        if (Schema::hasTable('enrollments')) {
            Schema::drop('enrollments');
        }

        if (Schema::hasTable('grades')) {
            Schema::drop('grades');
        }

        if (Schema::hasTable('teacher_class')) {
            Schema::drop('teacher_class');
        }

        if (Schema::hasTable('class_subject')) {
            Schema::drop('class_subject');
        }

        if (Schema::hasTable('class_section')) {
            Schema::drop('class_section');
        }

        if (Schema::hasTable('subjects')) {
            Schema::drop('subjects');
        }

        if (Schema::hasTable('school_classes')) {
            Schema::drop('school_classes');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration performs a destructive cleanup of the old schedule-related tables.
        // Re-creating the entire legacy structure is out of scope, so the down() method is intentionally empty.
    }
};

