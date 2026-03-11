<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use Illuminate\View\View;

class StudentsController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();
        // Classes where this teacher appears in the weekly schedule
        $classIds = ClassSchedule::where('teacher_id', $teacherId)
            ->pluck('school_class_id')
            ->unique()
            ->values();

        $classes = SchoolClass::with('students')
            ->whereIn('id', $classIds)
            ->get();

        // Build a flattened list of students with the classes they appear in
        $byStudent = [];

        foreach ($classes as $class) {
            foreach ($class->students as $student) {
                // Skip admins
                if ($student->role === 'admin' || $student->hasRole('admin')) {
                    continue;
                }

                $id = $student->id;
                if (! isset($byStudent[$id])) {
                    $byStudent[$id] = (object) [
                        'user' => $student,
                        'roll_number' => $student->pivot->roll_number ?? null,
                        'class_names' => collect([$class->name]),
                    ];
                } else {
                    $byStudent[$id]->class_names->push($class->name);
                }
            }
        }

        $students = collect($byStudent)
            ->map(function ($row) {
                $row->class_names = $row->class_names->unique()->join(', ');
                return $row;
            })
            ->values();

        return view('teacher.students.index', [
            'students' => $students,
        ]);
    }
}
