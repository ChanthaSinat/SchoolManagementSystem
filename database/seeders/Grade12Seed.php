<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Models\SchoolType;
use App\Models\AcademicYear;

class Grade12Seed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1) Ensure a default school type and academic year exist
        $schoolType = SchoolType::first() ?? SchoolType::create([
            'name' => 'Default School Type',
            'code' => 'HS',
            'year_levels' => ['12'],
            'is_default' => true,
        ]);

        $activeYear = AcademicYear::first() ?? AcademicYear::create([
            'name' => now()->format('Y') . '-' . now()->addYear()->format('Y'),
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'is_active' => true,
        ]);

        // 2) Ensure a Grade 12 class exists
        $class = SchoolClass::firstOrCreate(
            ['name' => 'Grade 12'],
            [
                'year_level' => '12',
                'section' => null,
                'school_type_id' => $schoolType->id,
                'academic_year_id' => $activeYear->id,
                'semester_id' => null,
            ],
        );

        // 3) Ensure the 5 core subjects exist
        $subjectNames = ['Math', 'Physics', 'Chemistry', 'Social', 'Khmer'];
        $subjects = collect();

        foreach ($subjectNames as $name) {
            $subjects->push(
                Subject::firstOrCreate(
                    ['name' => $name],
                    [
                        'code' => strtoupper(substr($name, 0, 3)),
                        'school_type_id' => null,
                        'year_level' => '12',
                        'semester_id' => null,
                    ],
                )
            );
        }

        // 4) Pick any teachers to attach (if none exist, skip schedule)
        $teachers = User::where(function ($q) {
                $q->where('role', 'teacher')->orWhereHas('roles', fn ($r) => $r->where('name', 'teacher'));
            })
            ->get();

        if ($teachers->isEmpty()) {
            return;
        }

        // 5) Create a simple weekly schedule (one subject per weekday)
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $shuffledSubjects = $subjects->shuffle();

        // Clear any existing schedule for this class
        ClassSchedule::where('school_class_id', $class->id)->delete();

        foreach ($weekdays as $index => $weekday) {
            $subject = $shuffledSubjects[$index] ?? $subjects[$index % $subjects->count()];
            $teacher = $teachers->random();

            ClassSchedule::create([
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'weekday' => $weekday,
                'period' => 1,
            ]);
        }
    }
}
