<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotifyAbsentParents implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $absentStudentIds,
        public string $date,
        public int $classId
    ) {}

    public function handle(): void
    {
        foreach ($this->absentStudentIds as $studentId) {
            $student = User::find($studentId);
            if (! $student) {
                continue;
            }

            $enrollment = $student->enrollments()
                ->where('school_class_id', $this->classId)
                ->with(['schoolClass', 'section'])
                ->first();

            $className = $enrollment?->schoolClass?->name ?? 'N/A';
            $sectionName = $enrollment?->section?->name ?? 'N/A';
            $guardianPhone = $enrollment?->guardian_phone ?? 'no phone';

            Log::info("ABSENT ALERT: {$student->first_name} {$student->last_name} | Class: {$className} | Section: {$sectionName} | Date: {$this->date} | Guardian: {$guardianPhone}");
            // SMS integration stubbed for now
        }
    }
}
