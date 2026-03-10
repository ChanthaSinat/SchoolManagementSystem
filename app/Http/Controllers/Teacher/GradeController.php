<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeacherClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(): View
    {
        $teacherClasses = TeacherClass::where('teacher_id', auth()->id())
            ->with(['schoolClass.subjects', 'schoolClass.sections'])
            ->get();

        $pairs = [];
        foreach ($teacherClasses as $tc) {
            $class = $tc->schoolClass;
            if (! $class) {
                continue;
            }
            foreach ($class->subjects as $subject) {
                $key = "{$class->id}-{$subject->id}";
                if (isset($pairs[$key])) {
                    continue;
                }
                $studentCount = Enrollment::where('school_class_id', $class->id)
                    ->where('status', 'active')
                    ->count();
                $pairs[$key] = (object) [
                    'school_class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'class_name' => $class->name,
                    'subject_name' => $subject->name,
                    'sections' => $class->sections,
                    'student_count' => $studentCount,
                ];
            }
        }
        $cards = array_values($pairs);

        return view('teacher.grades.index', ['cards' => $cards]);
    }

    public function show(int $classId, int $subjectId): View|RedirectResponse
    {
        $schoolClass = SchoolClass::find($classId);
        $subject = Subject::find($subjectId);
        if (! $schoolClass || ! $subject) {
            return redirect()->route('teacher.grades.index')->with('error', 'Invalid class or subject.');
        }

        $students = Enrollment::where('school_class_id', $classId)
            ->where('status', 'active')
            ->with('user')
            ->orderBy('roll_number')
            ->get();

        $assessments = Grade::where('school_class_id', $classId)
            ->where('subject_id', $subjectId)
            ->distinct()
            ->pluck('assessment_name')
            ->sort()
            ->values();

        $grades = Grade::where('school_class_id', $classId)
            ->where('subject_id', $subjectId)
            ->get()
            ->groupBy('student_id')
            ->map(fn ($g) => $g->keyBy('assessment_name'));

        return view('teacher.grades.show', [
            'classId' => $classId,
            'subjectId' => $subjectId,
            'schoolClass' => $schoolClass,
            'subject' => $subject,
            'students' => $students,
            'assessments' => $assessments,
            'grades' => $grades,
        ]);
    }

    public function store(Request $request, int $classId, int $subjectId): RedirectResponse
    {
        $request->validate([
            'grades' => ['required', 'array'],
            'grades.*' => ['array'],
            'grades.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessment_names' => ['nullable', 'array'],
            'assessment_names.*' => ['nullable', 'string', 'max:255'],
        ]);

        $gradesInput = $request->input('grades', []);
        $assessmentNames = $request->input('assessment_names', []);
        $teacherId = auth()->id();

        foreach ($gradesInput as $studentId => $assessments) {
            if (! is_array($assessments)) {
                continue;
            }
            foreach ($assessments as $assessmentName => $score) {
                if ($score !== null && $score !== '') {
                    $score = (float) $score;
                    Grade::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'subject_id' => $subjectId,
                            'school_class_id' => $classId,
                            'assessment_name' => $assessmentName,
                        ],
                        [
                            'score' => $score,
                            'max_score' => 100,
                            'graded_by' => $teacherId,
                            'graded_at' => now(),
                        ]
                    );
                }
            }
        }

        foreach ($assessmentNames as $name) {
            $name = is_string($name) ? trim($name) : '';
            if ($name === '') {
                continue;
            }
            foreach ($gradesInput as $studentId => $assessments) {
                if (! is_array($assessments)) {
                    continue;
                }
                $scoreForThis = $assessments[$name] ?? null;
                if ($scoreForThis !== null && $scoreForThis !== '') {
                    continue;
                }
                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'school_class_id' => $classId,
                        'assessment_name' => $name,
                    ],
                    [
                        'score' => null,
                        'max_score' => 100,
                        'graded_by' => $teacherId,
                        'graded_at' => now(),
                    ]
                );
            }
        }

        $subject = Subject::find($subjectId);
        $schoolClass = SchoolClass::find($classId);
        if (function_exists('activity') && Schema::hasTable('activity_log')) {
            activity()
                ->causedBy(auth()->user())
                ->log("Updated gradebook for {$subject->name} {$schoolClass->name}");
        }

        return redirect()
            ->route('teacher.grades.show', [$classId, $subjectId])
            ->with('success', 'Grades saved.');
    }

    public function exportPdf(int $classId, int $subjectId)
    {
        $schoolClass = SchoolClass::with(['sections', 'academicYear'])->find($classId);
        $subject = Subject::find($subjectId);
        if (! $schoolClass || ! $subject) {
            return redirect()->route('teacher.grades.index')->with('error', 'Invalid class or subject.');
        }

        $students = Enrollment::where('school_class_id', $classId)
            ->where('status', 'active')
            ->with('user')
            ->orderBy('roll_number')
            ->get();

        $assessments = Grade::where('school_class_id', $classId)
            ->where('subject_id', $subjectId)
            ->distinct()
            ->pluck('assessment_name')
            ->sort()
            ->values();

        $grades = Grade::where('school_class_id', $classId)
            ->where('subject_id', $subjectId)
            ->get()
            ->groupBy('student_id')
            ->map(fn ($g) => $g->keyBy('assessment_name'));

        $rows = $students->map(function ($enrollment) use ($grades, $assessments) {
            $studentGrades = $grades->get($enrollment->student_id) ?? collect();
            $scores = $assessments->map(fn ($a) => $studentGrades->get($a)?->score)->all();
            $filled = array_filter($scores, fn ($v) => $v !== null && $v !== '');
            $average = count($filled) > 0 ? array_sum($filled) / count($filled) : null;
            $gradeLetter = $average === null ? '—' : ($average >= 90 ? 'A' : ($average >= 80 ? 'B' : ($average >= 70 ? 'C' : ($average >= 60 ? 'D' : 'F'))));
            return (object) [
                'enrollment' => $enrollment,
                'scores' => $studentGrades,
                'assessments' => $assessments,
                'average' => $average,
                'grade_letter' => $gradeLetter,
            ];
        });

        $rows = $rows->sortByDesc(function ($r) {
            return $r->average ?? -1;
        })->values();

        $rank = 1;
        foreach ($rows as $row) {
            $row->rank = $rank++;
        }

        $classAverage = $rows->pluck('average')->filter()->values();
        $classAvg = $classAverage->isEmpty() ? null : round($classAverage->avg(), 1);
        $passCount = $rows->filter(fn ($r) => $r->average !== null && $r->average >= 60)->count();
        $passRate = $rows->isEmpty() ? 0 : round(($passCount / $rows->count()) * 100);
        $highest = $classAverage->isEmpty() ? null : round($classAverage->max(), 1);
        $lowest = $classAverage->isEmpty() ? null : round($classAverage->min(), 1);

        $pdf = Pdf::loadView('teacher.grades.pdf', [
            'schoolClass' => $schoolClass,
            'subject' => $subject,
            'assessments' => $assessments,
            'rows' => $rows,
            'classAverage' => $classAvg,
            'passRate' => $passRate,
            'teacherName' => auth()->user()?->full_name ?? 'Teacher',
            'academicYear' => $schoolClass->academicYear,
        ]);

        $classSlug = \Str::slug($schoolClass->name);
        $subjectSlug = \Str::slug($subject->name);

        return $pdf->download("grades-{$classSlug}-{$subjectSlug}.pdf");
    }
}
