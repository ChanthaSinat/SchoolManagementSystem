<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrolledClasses = $student->schoolClasses()->with(['schoolType', 'academicYear', 'semester'])->get();
        $classId = $enrolledClasses->first()?->id;
        
        $currentSchedule = collect();
        $todaySchedule = collect();
        if ($classId) {
            $currentSchedule = ClassSchedule::where('school_class_id', $classId)
                ->with(['subject', 'teacher'])
                ->get()
                ->keyBy('weekday');

            $today = strtolower(now()->format('l'));
            if (! in_array($today, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'], true)) {
                $today = 'monday'; // Default to Monday if viewed on weekend or for demo
            }
            $todaySchedule = ClassSchedule::where('school_class_id', $classId)
                ->where('weekday', $today)
                ->with(['subject', 'teacher'])
                ->get();
        }

        // Attendance stats
        $attendances = Attendance::where('student_id', $student->id)->get();
        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        
        $attendanceRate = $total > 0 ? round((($present + $late) / $total) * 100) : 0;
        $recentAttendance = $attendances->sortByDesc('date')->take(5);

        // Month stats
        $monthStart = now()->startOfMonth();
        $monthAttendances = $attendances->where('date', '>=', $monthStart);
        $monthPresent = $monthAttendances->where('status', 'present')->count();
        $monthAbsent = $monthAttendances->where('status', 'absent')->count();

        // Pass a simple "enrollment" object so the view can show "enrolled"
        $enrollment = $enrolledClasses->isNotEmpty()
            ? (object) ['schoolClass' => $enrolledClasses->first()]
            : null;

        return view('student.dashboard', [
            'enrollment' => $enrollment,
            'enrolledClasses' => $enrolledClasses,
            'currentSchedule' => $currentSchedule,
            'attendanceRate' => $attendanceRate,
            'todaySchedule' => $todaySchedule,
            'monthPresent' => $monthPresent,
            'monthAbsent' => $monthAbsent,
            'recentAttendance' => $recentAttendance,
        ]);
    }
}
