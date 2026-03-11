<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $teacherId = auth()->id();

        // Classes this teacher teaches, based on the weekly schedule
        $classIds = ClassSchedule::where('teacher_id', $teacherId)
            ->pluck('school_class_id')
            ->unique()
            ->values();

        $classes = \App\Models\SchoolClass::whereIn('id', $classIds)->get();

        // Recent attendance history (last 14 days) for these classes, if table exists
        $history = collect();
        if ($classIds->isNotEmpty() && Schema::hasTable('attendances')) {
            $fourteenDaysAgo = now()->subDays(14)->toDateString();
            $records = Attendance::where('teacher_id', $teacherId)
                ->whereIn('school_class_id', $classIds)
                ->where('date', '>=', $fourteenDaysAgo)
                ->with(['schoolClass', 'section'])
                ->orderByDesc('date')
                ->get();

            $history = $records->groupBy(fn ($r) => "{$r->date->toDateString()}-{$r->school_class_id}-{$r->section_id}")
                ->map(function ($group) {
                    $first = $group->first();
                    $present = $group->where('status', 'present')->count();
                    $absent = $group->where('status', 'absent')->count();
                    $late = $group->where('status', 'late')->count();
                    $total = $group->count();
                    $rate = $total > 0 ? round((($present + $late) / $total) * 100) : 0;

                    return (object) [
                        'date' => $first->date,
                        'school_class_id' => $first->school_class_id,
                        'section_id' => $first->section_id,
                        'class_name' => $first->schoolClass?->name,
                        'section_name' => $first->section?->name ?? 'A',
                        'present' => $present,
                        'absent' => $absent,
                        'late' => $late,
                        'rate' => $rate,
                    ];
                })
                ->values()
                ->sortByDesc('date')
                ->values();
        }

        return view('teacher.attendance.index', compact('classes', 'history'));
    }

    public function history(): RedirectResponse
    {
        return redirect()->route('teacher.attendance.index');
    }

    public function showMarkForm(Request $request): View|RedirectResponse
    {
        $classId = (int) $request->query('class_id');
        $date = $request->query('date');

        if (! $classId || ! $date) {
            return redirect()->route('teacher.attendance.index')
                ->with('error', 'Please select class and date.');
        }

        $schoolClass = SchoolClass::find($classId);
        if (! $schoolClass) {
            return redirect()->route('teacher.attendance.index')
                ->with('error', 'Invalid class.');
        }

        // Single default section for simplified Grade 12 setup
        $section = Section::firstOrCreate(['name' => 'A']);
        $sectionId = $section->id;

        // Students enrolled in this class via class_student
        $students = $schoolClass->students()
            ->wherePivot('status', 'active')
            ->get()
            ->filter(function ($student) {
                // Exclude admins
                return ! ($student->role === 'admin' || $student->hasRole('admin'));
            })
            ->map(function ($student) {
                return (object) [
                    'student_id' => $student->id,
                    'user' => $student,
                    'roll_number' => $student->pivot->roll_number,
                ];
            })
            ->values();

        $existing = collect();
        if (Schema::hasTable('attendances')) {
            $existing = Attendance::where('school_class_id', $classId)
                ->where('section_id', $sectionId)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');
        }

        return view('teacher.attendance.mark', [
            'students' => $students,
            'existing' => $existing,
            'classId' => $classId,
            'sectionId' => $sectionId,
            'date' => $date,
            'schoolClass' => $schoolClass,
            'section' => $section,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('attendances')) {
            return redirect()
                ->route('teacher.attendance.index')
                ->with('error', 'Attendance table is missing. Please run php artisan migrate to enable attendance tracking.');
        }

        $data = $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', 'in:present,absent,late'],
            'attendance.*.note' => ['nullable', 'string', 'max:500'],
        ]);

        $classId = (int) $data['class_id'];
        $date = $data['date'];

        $schoolClass = SchoolClass::findOrFail($classId);
        $teacherId = auth()->id();

        // Single default section
        $section = Section::firstOrCreate(['name' => 'A']);
        $sectionId = $section->id;

        $counts = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'total' => 0,
        ];

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $classId, $sectionId, $date, $teacherId, &$counts) {
            Attendance::where('school_class_id', $classId)
                ->where('section_id', $sectionId)
                ->whereDate('date', $date)
                ->delete();

            foreach ($data['attendance'] as $studentId => $row) {
                $status = $row['status'] ?? 'present';
                $note = $row['note'] ?? null;

                Attendance::create([
                    'student_id' => $studentId,
                    'school_class_id' => $classId,
                    'section_id' => $sectionId,
                    'date' => $date,
                    'status' => $status,
                    'note' => $note,
                    'teacher_id' => $teacherId,
                ]);

                $counts[$status]++;
                $counts['total']++;
            }
        });

        return redirect()->route('teacher.attendance.index')
            ->with('success', "Attendance saved for {$counts['total']} students.");
    }
}
