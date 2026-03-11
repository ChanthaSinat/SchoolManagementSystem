<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();

        // Teaching Schedule from new class_schedules table
        $myTeachingSchedule = ClassSchedule::where('teacher_id', $teacherId)
            ->with(['schoolClass', 'subject'])
            ->get()
            ->groupBy('weekday');

        // Total unique students across all of this teacher's classes
        $classIds = $myTeachingSchedule->flatten()->pluck('school_class_id')->unique();
        $studentIds = \Illuminate\Support\Facades\DB::table('class_student')
            ->whereIn('school_class_id', $classIds)
            ->pluck('student_id')
            ->unique();
        $totalStudents = $studentIds->count();

        // Today’s schedule derived from class_schedules
        $today = strtolower(now()->format('l'));
        if (! in_array($today, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'], true)) {
            $today = 'monday';
        }
        $todaySchedule = ClassSchedule::where('teacher_id', $teacherId)
            ->where('weekday', $today)
            ->with(['subject', 'schoolClass'])
            ->get();

        $lessonsTodayCount = $todaySchedule->count();

        // Determine if attendance has been marked for all of today's classes
        $attendanceMarked = false;
        $classIdsToday = $todaySchedule->pluck('school_class_id')->unique();
        if ($classIdsToday->isNotEmpty()) {
            $todayDate = now()->toDateString();
            $classesWithAttendance = Attendance::where('teacher_id', $teacherId)
                ->whereDate('date', $todayDate)
                ->whereIn('school_class_id', $classIdsToday)
                ->distinct()
                ->pluck('school_class_id');

            $attendanceMarked = $classIdsToday->diff($classesWithAttendance)->isEmpty();
        }

        // Recent Activity: Final Exam submissions from students in this teacher's classes
        $recentActivity = \App\Models\ExamAttempt::whereIn('student_id', $studentIds)
            ->where('status', 'completed')
            ->with('student')
            ->latest('completed_at')
            ->take(5)
            ->get()
            ->map(function ($attempt) {
                return (object) [
                    'id' => $attempt->id,
                    'student' => $attempt->student?->first_name . ' ' . $attempt->student?->last_name,
                    'assignment' => 'Final Exam',
                    'status' => 'Graded',
                    'score' => $attempt->score . '/' . $attempt->total_questions,
                    'date' => $attempt->completed_at->diffForHumans(),
                ];
            });

        return view('teacher.dashboard', [
            'todaySchedule' => $todaySchedule,
            'myTeachingSchedule' => $myTeachingSchedule,
            'recentActivity' => $recentActivity,
            'totalStudents' => $totalStudents,
            'lessonsTodayCount' => $lessonsTodayCount,
            'attendanceMarked' => $attendanceMarked,
        ]);
    }
}
