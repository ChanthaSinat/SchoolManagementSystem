<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\View\View;

class StudentAttendanceController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrolledClasses = $student->schoolClasses()->with(['schoolType', 'academicYear', 'semester'])->get();

        $enrollment = $enrolledClasses->isNotEmpty()
            ? (object) ['schoolClass' => $enrolledClasses->first()]
            : null;

        // Load this student's attendance records
        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();

        $rate = $total > 0
            ? (int) round((($present + $late) / $total) * 100)
            : 0;

        // Build per-date status map for calendar (absent > late > present)
        $attendanceByDate = [];
        foreach ($attendances as $a) {
            $key = $a->date->toDateString();
            $current = $attendanceByDate[$key] ?? null;

            if ($a->status === 'absent'
                || ($a->status === 'late' && $current !== 'absent')
                || ($a->status === 'present' && $current === null)
            ) {
                $attendanceByDate[$key] = $a->status;
            }
        }

        $byMonth = $attendances->groupBy(fn ($a) => $a->date->format('Y-m'));

        return view('student.attendance.index', [
            'enrollment' => $enrollment,
            'attendances' => $attendances,
            'byMonth' => $byMonth,
            'attendanceByDate' => $attendanceByDate,
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'rate' => $rate,
        ]);
    }
}
