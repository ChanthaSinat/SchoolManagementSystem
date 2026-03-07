<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // teacher
            $table->string('day_of_week', 20); // monday, tuesday, ...
            $table->string('start_time', 5);   // 08:00
            $table->string('end_time', 5);     // 09:00
            $table->string('room')->nullable();
            $table->timestamps();

            $table->index(['school_class_id', 'section_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
