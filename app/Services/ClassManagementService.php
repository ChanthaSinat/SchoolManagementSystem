<?php

namespace App\Services;

use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class ClassManagementService
{
    public function __construct(
        protected ScheduleGeneratorService $scheduleGenerator
    ) {}

    /**
     * Update student assignments for a class (simple enrollment only).
     *
     * @param SchoolClass $class
     * @param array $data
     * @return void
     */
    public function updateAssignments(SchoolClass $class, array $data): void
    {
        DB::transaction(function () use ($class, $data) {
            // Sync students
            $studentSync = [];
            foreach ($data['student_ids'] ?? [] as $studentId) {
                $studentSync[(int) $studentId] = ['status' => 'active'];
            }
            $class->students()->sync($studentSync);
        });
    }
}
