<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\View\View;

class CurriculumController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();

        // Derive curriculum from weekly schedule: classes and subjects this teacher teaches
        $schedule = ClassSchedule::where('teacher_id', $teacherId)
            ->with(['schoolClass', 'subject'])
            ->get();

        $items = $schedule->groupBy('school_class_id')->map(function ($group) {
            $first = $group->first();
            $class = $first->schoolClass;

            if (! $class) {
                return null;
            }

            $subjects = $group->pluck('subject')->filter()->unique('id')->values();

            return (object) [
                'class_id' => $class->id,
                'class_name' => $class->name,
                'subjects' => $subjects,
            ];
        })->filter()->values();

        return view('teacher.curriculum.index', compact('items'));
    }
}
