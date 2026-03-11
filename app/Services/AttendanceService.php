<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Jobs\NotifyAbsentParents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceService
{
    /**
     * Store or update attendance records.
     *
     * @param array $data
     * @param int $teacherId
     * @return array
     */
    public function storeAttendance(array $data, int $teacherId): array
    {
        $classId = (int) $data['class_id'];
        $sectionId = (int) $data['section_id'];
        $date = $data['date'];
        
        $absentStudentIds = [];
        $counts = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'total' => 0,
        ];

        DB::transaction(function () use ($data, $classId, $sectionId, $date, $teacherId, &$absentStudentIds, &$counts) {
            // Remove existing records for atomicity
            Attendance::where('school_class_id', $classId)
                ->where('section_id', $sectionId)
                ->whereDate('date', $date)
                ->delete();

            foreach ($data['attendance'] as $studentId => $row) {
                $status = $row['status'] ?? 'present';
                $note = $row['note'] ?? null;

                if ($status === 'absent') {
                    $absentStudentIds[] = (int) $studentId;
                }

                Attendance::create([
                    'student_id' => $studentId,
                    'school_class_id' => $classId,
                    'section_id' => $sectionId,
                    'date' => $date,
                    'status' => $status,
                    'note' => $note,
                    'teacher_id' => $teacherId,
                ]);

                $counts[$status]++;
                $counts['total']++;
            }

            if (count($absentStudentIds) > 0) {
                NotifyAbsentParents::dispatch($absentStudentIds, $date, $classId);
            }
        });

        $this->logActivity($classId, $sectionId, $date, $counts, $teacherId);

        return $counts;
    }

    /**
     * Log activity if the activity_log table exists.
     */
    protected function logActivity(int $classId, int $sectionId, string $date, array $counts, int $teacherId): void
    {
        if (function_exists('activity') && Schema::hasTable('activity_log')) {
            $schoolClass = SchoolClass::find($classId);
            $section = Section::find($sectionId);
            
            activity()
                ->causedBy($teacherId)
                ->log("Marked attendance for {$schoolClass->name} {$section->name} on {$date} — {$counts['present']} present, {$counts['absent']} absent, {$counts['late']} late.");
        }
    }
}
