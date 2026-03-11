<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('class_schedules')) {
            return;
        }

        Schema::table('class_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('class_schedules', 'weekday')) {
                $table->string('weekday', 16)->default('monday')->after('teacher_id');
            }

            if (! Schema::hasColumn('class_schedules', 'period')) {
                $table->unsignedTinyInteger('period')->default(1)->after('weekday');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('class_schedules')) {
            return;
        }

        Schema::table('class_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('class_schedules', 'period')) {
                $table->dropColumn('period');
            }

            if (Schema::hasColumn('class_schedules', 'weekday')) {
                $table->dropColumn('weekday');
            }
        });
    }
};

