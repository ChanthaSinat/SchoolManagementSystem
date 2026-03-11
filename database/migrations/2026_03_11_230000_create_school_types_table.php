<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Elementary, High School, College, Custom
            $table->string('code')->unique(); // internal key like elementary, high_school, college, custom
            $table->json('year_levels')->nullable(); // array of labels: ["Grade 1", "Grade 2", ...]
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_types');
    }
};

