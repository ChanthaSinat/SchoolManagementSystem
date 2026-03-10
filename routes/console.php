<?php

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherClass;
use App\Models\Timetable;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('schedule:random-teacher {teacherId}', function (int $teacherId) {
    $this->info("Generating random schedule for teacher ID {$teacherId}...");

    $teacherClasses = TeacherClass::with(['schoolClass.sections', 'schoolClass.subjects'])
        ->where('teacher_id', $teacherId)
        ->get();

    if ($teacherClasses->isEmpty()) {
        $this->error('No classes found for this teacher.');

        return;
    }

    // Clear existing timetable entries for this teacher to avoid duplicates
    $deleted = Timetable::where('user_id', $teacherId)->delete();
    if ($deleted > 0) {
        $this->line("Cleared {$deleted} existing timetable entries for this teacher.");
    }

    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    $timeSlots = [
        ['start' => '09:00', 'end' => '10:30'],
        ['start' => '10:45', 'end' => '12:15'],
        ['start' => '13:30', 'end' => '15:00'],
        ['start' => '15:15', 'end' => '16:45'],
    ];

    $createdCount = 0;

    foreach ($teacherClasses as $teacherClass) {
        /** @var SchoolClass $schoolClass */
        $schoolClass = $teacherClass->schoolClass;
        if (! $schoolClass) {
            continue;
        }

        $sections = $schoolClass->sections;
        $subjects = $schoolClass->subjects;

        if ($sections->isEmpty() || $subjects->isEmpty()) {
            $this->warn("Skipping class {$schoolClass->id} ({$schoolClass->name}) because it has no sections or subjects.");
            continue;
        }

        // Each class gets 2–4 random lessons across the week
        $lessonCount = rand(2, 4);
        $usedSlots = [];

        for ($i = 0; $i < $lessonCount; $i++) {
            $day = $days[array_rand($days)];
            $slotIndex = array_rand($timeSlots);

            // Avoid exact duplicates for (day, slot) within this class
            $key = $day.'#'.$slotIndex;
            if (isset($usedSlots[$key])) {
                continue;
            }
            $usedSlots[$key] = true;

            $slot = $timeSlots[$slotIndex];
            $section = $sections->random();
            $subject = $subjects->random();

            Timetable::create([
                'school_class_id' => $schoolClass->id,
                'section_id' => $section->id,
                'subject_id' => $subject->id,
                'user_id' => $teacherId,
                'day_of_week' => $day,
                'start_time' => $slot['start'],
                'end_time' => $slot['end'],
                'room' => 'B-'.rand(1, 20),
            ]);

            $createdCount++;
        }
    }

    $this->info("Created {$createdCount} timetable entries for teacher ID {$teacherId}.");
})->purpose('Generate a random weekly timetable for a specific teacher');

Artisan::command('school:demo-setup', function () {
    $this->info('Setting up demo academic data (classes, sections, subjects, assignments)...');

    // Ensure there is at least one teacher and one student
    $teacher = User::where(function ($q) {
        $q->where('role', 'teacher')->orWhereHas('roles', fn ($r) => $r->where('name', 'teacher'));
    })->first();

    $student = User::where(function ($q) {
        $q->where('role', 'student')->orWhereHas('roles', fn ($r) => $r->where('name', 'student'));
    })->first();

    if (! $teacher || ! $student) {
        $this->error('You need at least one teacher and one student user created via the admin panel.');
        return;
    }

    // Academic year
    $year = AcademicYear::firstOrCreate(
        ['name' => now()->year.'/'.(now()->year + 1)],
        [
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'is_active' => true,
        ]
    );

    // Sections
    $sectionA = Section::firstOrCreate(['name' => 'A']);
    $sectionB = Section::firstOrCreate(['name' => 'B']);

    // Subjects
    $math = Subject::firstOrCreate(['code' => 'MATH101'], ['name' => 'Mathematics']);
    $physics = Subject::firstOrCreate(['code' => 'PHYS101'], ['name' => 'Physics I']);

    // Class
    $class = SchoolClass::firstOrCreate(
        ['name' => 'Grade 10'],
        [
            'academic_year_id' => $year->id,
        ]
    );

    // Attach sections and subjects to class
    $class->sections()->syncWithoutDetaching([$sectionA->id, $sectionB->id]);
    $class->subjects()->syncWithoutDetaching([$math->id, $physics->id]);

    // Assign teacher to class
    TeacherClass::firstOrCreate([
        'teacher_id' => $teacher->id,
        'school_class_id' => $class->id,
    ]);

    // Enroll student into class/section A
    Enrollment::updateOrCreate(
        [
            'student_id' => $student->id,
            'school_class_id' => $class->id,
            'section_id' => $sectionA->id,
        ],
        [
            'status' => 'active',
            'roll_number' => 1,
        ]
    );

    // Generate a random timetable for this teacher
    $this->call('schedule:random-teacher', ['teacherId' => $teacher->id]);

    $this->info('Demo setup complete. Teacher and student are now linked to Grade 10 with a timetable.');
})->purpose('Create demo class, sections, subjects, and link one teacher & student');

