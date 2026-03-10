<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Timetable;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['schoolClass', 'section'])
            ->first();

        // No active enrollment: show empty dashboard
        if (! $enrollment) {
            return view('student.dashboard', [
                'enrollment' => null,
                'attendanceRate' => 0,
                'todaySchedule' => collect(),
                'monthPresent' => 0,
                'monthAbsent' => 0,
                'recentAttendance' => collect(),
            ]);
        }

        // Attendance
        $totalDays = Attendance::where('student_id', $student->id)->count();
        $presentDays = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        // Today's schedule
        $todaySchedule = Timetable::where('school_class_id', $enrollment->school_class_id)
            ->where('section_id', $enrollment->section_id)
            ->where('day_of_week', strtolower(now()->format('l')))
            ->with(['subject', 'user'])
            ->orderBy('start_time')
            ->get();

        // Attendance this month
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $attendanceThisMonth = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();
        $monthPresent = $attendanceThisMonth->where('status', 'present')->count();
        $monthAbsent = $attendanceThisMonth->where('status', 'absent')->count();
        $recentAttendance = Attendance::where('student_id', $student->id)
            ->orderByDesc('date')
            ->take(5)
            ->get();

        return view('student.dashboard', [
            'enrollment' => $enrollment,
            'attendanceRate' => $attendanceRate,
            'todaySchedule' => $todaySchedule,
            'monthPresent' => $monthPresent,
            'monthAbsent' => $monthAbsent,
            'recentAttendance' => $recentAttendance,
        ]);
    }
}
