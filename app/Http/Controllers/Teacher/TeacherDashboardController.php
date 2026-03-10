<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\TeacherClass;
use App\Models\Timetable;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();
        $teacherClassIds = TeacherClass::where('teacher_id', $teacherId)->pluck('school_class_id');

        // Average grade (teacher's graded entries)
        $grades = Grade::where('graded_by', $teacherId)->get();
        $gradesWithScore = $grades->filter(fn ($g) => $g->score !== null && $g->max_score > 0);
        $averageGrade = $gradesWithScore->isEmpty()
            ? 0
            : round($gradesWithScore->avg(fn ($g) => ((float) $g->score / (float) $g->max_score) * 100), 0);

        // Attendance rate (last 30 days, teacher's records)
        $recentAttendance = Attendance::where('teacher_id', $teacherId)
            ->where('date', '>=', now()->subDays(30))
            ->get();
        $attendanceTotal = $recentAttendance->count();
        $attendancePresent = $recentAttendance->where('status', 'present')->count();
        $attendanceRate = $attendanceTotal > 0 ? round(($attendancePresent / $attendanceTotal) * 100, 0) : 0;

        // Pre-compute student counts per (class, section) to avoid N+1 queries
        $studentCounts = Enrollment::whereIn('school_class_id', $teacherClassIds)
            ->where('status', 'active')
            ->selectRaw('school_class_id, section_id, COUNT(*) as total')
            ->groupBy('school_class_id', 'section_id')
            ->get()
            ->keyBy(fn ($row) => $row->school_class_id . ':' . $row->section_id);

        // Today's schedule
        $todaySchedule = Timetable::where('user_id', $teacherId)
            ->where('day_of_week', strtolower(now()->format('l')))
            ->with(['subject', 'schoolClass', 'section'])
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) use ($studentCounts) {
                $key = $slot->school_class_id . ':' . $slot->section_id;
                $studentCount = $studentCounts[$key]->total ?? 0;

                return (object) [
                    'id' => $slot->id,
                    'name' => $slot->subject?->name ?? 'Subject',
                    'code' => $slot->subject?->code ?? '',
                    'students' => $studentCount,
                    'time' => $slot->start_time . ' — ' . $slot->end_time,
                    'time_short' => $slot->start_time,
                    'room' => $slot->room ?? '—',
                    'school_class' => $slot->schoolClass?->name,
                    'section' => $slot->section?->name,
                    'school_class_id' => $slot->school_class_id,
                    'section_id' => $slot->section_id,
                    'subject_id' => $slot->subject_id,
                    'color' => ['bg-blue-500', 'bg-purple-500', 'bg-indigo-500', 'bg-teal-500'][$slot->subject_id % 4] ?? 'bg-indigo-500',
                ];
            });

        // Recent activity (recent grades)
        $recentGrades = Grade::where('graded_by', $teacherId)
            ->with(['student', 'subject'])
            ->latest('graded_at')
            ->take(5)
            ->get()
            ->map(function ($g) {
                return (object) [
                    'id' => $g->id,
                    'student' => $g->student?->full_name ?? 'Student',
                    'assignment' => $g->assessment_name,
                    'subject' => $g->subject?->name ?? '—',
                    'status' => $g->score !== null ? 'Graded' : 'Pending',
                    'score' => $g->score !== null ? (string) $g->score . '/' . $g->max_score : '—',
                    'date' => $g->graded_at?->diffForHumans() ?? '—',
                ];
            });

        $stats = [
            (object) [
                'label' => 'Avg Grade',
                'value' => $averageGrade . '%',
                'color' => 'text-green-600',
                'bg' => 'bg-green-100',
                'trend_text' => '+0.8%',
                'trend_class' => 'text-green-500 bg-green-50',
            ],
            (object) [
                'label' => 'Attendance',
                'value' => $attendanceRate . '%',
                'color' => 'text-purple-600',
                'bg' => 'bg-purple-100',
                'trend_text' => 'Stable',
                'trend_class' => 'text-slate-400 bg-slate-50',
            ],
            (object) [
                'label' => 'Pending Tasks',
                'value' => '12',
                'color' => 'text-orange-600',
                'bg' => 'bg-orange-100',
                'trend_text' => '-2',
                'trend_class' => 'text-red-500 bg-red-50',
            ],
        ];

        return view('teacher.dashboard', [
            'stats' => $stats,
            'todaySchedule' => $todaySchedule,
            'recentActivity' => $recentGrades,
        ]);
    }
}
