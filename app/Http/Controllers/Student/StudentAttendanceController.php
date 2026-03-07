<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\View\View;

class StudentAttendanceController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->firstOrFail();

        $attendances = Attendance::where('student_id', $student->id)
            ->where('school_class_id', $enrollment->school_class_id)
            ->orderByDesc('date')
            ->get();

        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();
        $rate = $total > 0 ? round(($present / $total) * 100) : 0;

        $byMonth = $attendances->groupBy(fn ($a) => Carbon::parse($a->date)->format('Y-m'));

        $attendanceByDate = $attendances->keyBy(fn ($a) => $a->date->format('Y-m-d'))->map(fn ($a) => $a->status)->all();

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
