<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\View\View;

class StudentTimetableController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrolledClasses = $student->schoolClasses()->with(['schoolType', 'academicYear', 'semester', 'schedules.subject', 'schedules.teacher'])->get();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $currentDay = strtolower(now()->format('l'));
        if (! in_array($currentDay, $days, true)) {
            $currentDay = 'monday';
        }

        $primaryClass = $enrolledClasses->first();
        $enrollment = $primaryClass ? (object) ['schoolClass' => $primaryClass] : null;

        $schedule = $primaryClass
            ? $primaryClass->schedules->keyBy('weekday')
            : collect();

        return view('student.timetable.index', [
            'enrollment' => $enrollment,
            'enrolledClasses' => $enrolledClasses,
            'schedule' => $schedule,
            'days' => $days,
            'currentDay' => $currentDay,
        ]);
    }
}
