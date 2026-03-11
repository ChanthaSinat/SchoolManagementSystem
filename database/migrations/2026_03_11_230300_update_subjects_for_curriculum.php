<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If the subjects table was dropped earlier, recreate a basic version first.
        if (! Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable()->unique();
                $table->timestamps();
            });
        }

        Schema::table('subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('subjects', 'description')) {
                $table->text('description')->nullable()->after('code');
            }

            if (! Schema::hasColumn('subjects', 'units')) {
                $table->decimal('units', 4, 1)->nullable()->after('description');
            }

            if (! Schema::hasColumn('subjects', 'school_type_id')) {
                $table->foreignId('school_type_id')
                    ->nullable()
                    ->after('units')
                    ->constrained('school_types')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('subjects', 'year_level')) {
                $table->string('year_level')->nullable()->after('school_type_id');
            }

            if (! Schema::hasColumn('subjects', 'semester_id')) {
                $table->foreignId('semester_id')
                    ->nullable()
                    ->after('year_level')
                    ->constrained('semesters')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'semester_id')) {
                $table->dropConstrainedForeignId('semester_id');
            }
            if (Schema::hasColumn('subjects', 'year_level')) {
                $table->dropColumn('year_level');
            }
            if (Schema::hasColumn('subjects', 'school_type_id')) {
                $table->dropConstrainedForeignId('school_type_id');
            }
            if (Schema::hasColumn('subjects', 'units')) {
                $table->dropColumn('units');
            }
            if (Schema::hasColumn('subjects', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

