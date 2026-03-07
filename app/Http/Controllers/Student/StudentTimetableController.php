<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Timetable;
use Illuminate\View\View;

class StudentTimetableController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['schoolClass', 'section'])
            ->first();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $currentDay = strtolower(now()->format('l'));
        if (! in_array($currentDay, $days, true)) {
            $currentDay = 'monday';
        }
        $currentTime = now()->format('H:i');

        if (! $enrollment) {
            return view('student.timetable.index', [
                'enrollment' => null,
                'timetable' => collect(),
                'days' => $days,
                'currentDay' => $currentDay,
                'currentTime' => $currentTime,
            ]);
        }

        $timetable = Timetable::where('school_class_id', $enrollment->school_class_id)
            ->where('section_id', $enrollment->section_id)
            ->with(['subject', 'user'])
            ->get()
            ->groupBy('day_of_week');

        return view('student.timetable.index', [
            'enrollment' => $enrollment,
            'timetable' => $timetable,
            'days' => $days,
            'currentDay' => $currentDay,
            'currentTime' => $currentTime,
        ]);
    }
}
