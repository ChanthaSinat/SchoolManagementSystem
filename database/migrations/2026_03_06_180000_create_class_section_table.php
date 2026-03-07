<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['class_id', 'section_id']);
        });

        // Migrate existing section->class links into the pivot
        $sections = DB::table('sections')->get();
        foreach ($sections as $section) {
            DB::table('class_section')->insert([
                'class_id' => $section->class_id,
                'section_id' => $section->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_section');

        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('id')->constrained('school_classes')->cascadeOnDelete();
        });
    }
};
