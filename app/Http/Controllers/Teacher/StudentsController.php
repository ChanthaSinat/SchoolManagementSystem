<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TeacherClass;
use Illuminate\View\View;

class StudentsController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();
        $classIds = TeacherClass::where('teacher_id', $teacherId)->pluck('school_class_id');

        $enrollments = Enrollment::whereIn('school_class_id', $classIds)
            ->where('status', 'active')
            ->with(['user', 'schoolClass', 'section'])
            ->orderBy('school_class_id')
            ->orderBy('section_id')
            ->orderBy('roll_number')
            ->get();

        // One row per distinct student (with their primary class/section for display)
        $students = $enrollments->unique('student_id')->map(function ($e) use ($enrollments) {
            $classes = $enrollments->where('student_id', $e->student_id)->pluck('schoolClass.name')->unique()->join(', ');
            $sections = $enrollments->where('student_id', $e->student_id)->pluck('section.name')->unique()->join(', ');

            return (object) [
                'user' => $e->user,
                'roll_number' => $e->roll_number,
                'class_names' => $classes,
                'section_names' => $sections,
            ];
        })->values();

        return view('teacher.students.index', ['students' => $students]);
    }
}
