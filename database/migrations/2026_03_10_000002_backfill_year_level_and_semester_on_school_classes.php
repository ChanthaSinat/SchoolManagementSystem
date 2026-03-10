<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Simple default backfill so existing classes are usable in filters.
        // You can later adjust year_level and semester per class from the UI or tinker.
        DB::table('school_classes')
            ->whereNull('year_level')
            ->update([
                'year_level' => 'foundation',
                'semester' => 1,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('school_classes')->update([
            'year_level' => null,
            'semester' => null,
        ]);
    }
};

