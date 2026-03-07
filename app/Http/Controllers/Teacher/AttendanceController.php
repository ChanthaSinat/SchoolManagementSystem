<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyAbsentParents;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\TeacherClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $teacherClasses = TeacherClass::where('teacher_id', auth()->id())
            ->with(['schoolClass.sections'])
            ->get();

        $classes = $teacherClasses->map(fn ($tc) => $tc->schoolClass)->filter()->unique('id')->values();

        $fourteenDaysAgo = now()->subDays(14)->toDateString();
        $records = Attendance::where('teacher_id', auth()->id())
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
                    'section_name' => $first->section?->name,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'rate' => $rate,
                ];
            })
            ->values()
            ->sortByDesc('date')
            ->values();

        return view('teacher.attendance.index', [
            'classes' => $classes,
            'history' => $history,
        ]);
    }

    public function history(): RedirectResponse
    {
        return redirect()->route('teacher.attendance.index');
    }

    public function showMarkForm(Request $request): View|RedirectResponse
    {
        $classId = $request->query('class_id');
        $sectionId = $request->query('section_id');
        $date = $request->query('date');

        if (! $classId || ! $sectionId || ! $date) {
            return redirect()->route('teacher.attendance.index')
                ->with('error', 'Please select class, section, and date.');
        }

        $schoolClass = SchoolClass::find($classId);
        $section = Section::find($sectionId);

        if (! $schoolClass || ! $section) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Invalid class or section.');
        }

        $students = Enrollment::where('school_class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 'active')
            ->with('user')
            ->orderBy('roll_number')
            ->get();

        $existing = Attendance::where('school_class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

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
        $validated = $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', 'in:present,absent,late'],
            'attendance.*.note' => ['nullable', 'string', 'max:500'],
        ]);

        $classId = (int) $validated['class_id'];
        $sectionId = (int) $validated['section_id'];
        $date = $validated['date'];
        $teacherId = auth()->id();

        $absentStudentIds = [];

        foreach ($validated['attendance'] as $studentId => $row) {
            $status = $row['status'] ?? 'present';
            $note = $row['note'] ?? null;

            if ($status === 'absent') {
                $absentStudentIds[] = (int) $studentId;
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'school_class_id' => $classId,
                    'section_id' => $sectionId,
                    'date' => $date,
                ],
                [
                    'status' => $status,
                    'note' => $note,
                    'section_id' => $sectionId,
                    'teacher_id' => $teacherId,
                ]
            );
        }

        if (count($absentStudentIds) > 0) {
            NotifyAbsentParents::dispatch($absentStudentIds, $date, $classId);
        }

        $schoolClass = SchoolClass::with('section')->find($classId);
        $section = Section::find($sectionId);
        $presentCount = collect($validated['attendance'])->where('status', 'present')->count();
        $absentCount = collect($validated['attendance'])->where('status', 'absent')->count();
        $lateCount = collect($validated['attendance'])->where('status', 'late')->count();

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log("Marked attendance for {$schoolClass->name} {$section->name} on {$date} — {$presentCount} present, {$absentCount} absent, {$lateCount} late.");
        }

        $total = count($validated['attendance']);
        session()->flash('success', "Attendance saved for {$total} students.");

        return redirect()->route('teacher.attendance.index');
    }
}
