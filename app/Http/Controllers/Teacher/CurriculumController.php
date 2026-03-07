<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherClass;
use Illuminate\View\View;

class CurriculumController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)
            ->with(['schoolClass.subjects', 'schoolClass.sections'])
            ->get();

        $items = $teacherClasses->map(function ($tc) {
            $class = $tc->schoolClass;
            if (! $class) {
                return null;
            }

            return (object) [
                'class_id' => $class->id,
                'class_name' => $class->name,
                'subjects' => $class->subjects,
                'sections' => $class->sections,
            ];
        })->filter()->unique('class_id')->values();

        return view('teacher.curriculum.index', ['items' => $items]);
    }
}
