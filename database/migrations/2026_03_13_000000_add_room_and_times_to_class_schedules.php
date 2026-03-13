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
        if (! Schema::hasTable('class_schedules')) {
            return;
        }

        Schema::table('class_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('class_schedules', 'room')) {
                $table->string('room', 50)->nullable()->after('period');
            }

            if (! Schema::hasColumn('class_schedules', 'start_time')) {
                $table->time('start_time')->nullable()->after('room');
            }

            if (! Schema::hasColumn('class_schedules', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('class_schedules')) {
            return;
        }

        Schema::table('class_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('class_schedules', 'end_time')) {
                $table->dropColumn('end_time');
            }

            if (Schema::hasColumn('class_schedules', 'start_time')) {
                $table->dropColumn('start_time');
            }

            if (Schema::hasColumn('class_schedules', 'room')) {
                $table->dropColumn('room');
            }
        });
    }
};

