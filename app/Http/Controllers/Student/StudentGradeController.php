<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class StudentGradeController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['schoolClass.academicYear', 'section'])
            ->firstOrFail();

        $grades = Grade::where('student_id', $student->id)
            ->where('school_class_id', $enrollment->school_class_id)
            ->with(['subject', 'gradedByUser'])
            ->orderBy('subject_id')
            ->orderBy('assessment_name')
            ->get()
            ->groupBy('subject_id');

        $subjectStats = $grades->map(function (Collection $group) {
            $withScores = $group->filter(fn ($g) => $g->score !== null && $g->max_score > 0);
            $average = $withScores->isEmpty()
                ? null
                : $withScores->avg(fn ($g) => ((float) $g->score / (float) $g->max_score) * 100);
            $letter = $average === null ? '—' : ($average >= 90 ? 'A' : ($average >= 80 ? 'B' : ($average >= 70 ? 'C' : ($average >= 60 ? 'D' : 'F'))));
            $status = $average === null ? null : ($average >= 60 ? 'pass' : 'fail');

            return (object) [
                'grades' => $group,
                'average' => $average,
                'letter' => $letter,
                'status' => $status,
                'subject' => $group->first()?->subject,
                'teacher' => $group->first()?->gradedByUser,
            ];
        });

        $overallAvg = $subjectStats->pluck('average')->filter()->values();
        $overallAverage = $overallAvg->isEmpty() ? null : $overallAvg->avg();
        $overallLetter = $overallAverage === null ? '—' : ($overallAverage >= 90 ? 'A' : ($overallAverage >= 80 ? 'B' : ($overallAverage >= 70 ? 'C' : ($overallAverage >= 60 ? 'D' : 'F'))));
        $passingCount = $subjectStats->filter(fn ($s) => $s->status === 'pass')->count();
        $failingCount = $subjectStats->filter(fn ($s) => $s->status === 'fail')->count();

        $assessmentLabels = Grade::where('student_id', $student->id)
            ->where('school_class_id', $enrollment->school_class_id)
            ->distinct()
            ->orderBy('assessment_name')
            ->pluck('assessment_name')
            ->values()
            ->all();

        $chartColors = [
            '#4f46e5', '#059669', '#d97706', '#dc2626', '#0284c7',
            '#7c3aed', '#0d9488', '#ea580c',
        ];
        $chartDatasets = [];
        $idx = 0;
        foreach ($subjectStats as $stat) {
            $subjectName = $stat->subject?->name ?? 'Subject';
            $byAssessment = $stat->grades->keyBy('assessment_name');
            $data = array_map(function ($label) use ($byAssessment) {
                $g = $byAssessment->get($label);
                if (! $g || $g->score === null || $g->max_score <= 0) {
                    return null;
                }

                return round(((float) $g->score / (float) $g->max_score) * 100, 1);
            }, $assessmentLabels);
            $chartDatasets[] = [
                'label' => $subjectName,
                'data' => $data,
                'borderColor' => $chartColors[$idx % count($chartColors)],
                'backgroundColor' => $chartColors[$idx % count($chartColors)] . '20',
                'tension' => 0.4,
                'fill' => false,
            ];
            $idx++;
        }
        $chartData = [
            'labels' => $assessmentLabels,
            'datasets' => $chartDatasets,
        ];

        return view('student.grades.index', [
            'enrollment' => $enrollment,
            'grades' => $grades,
            'subjectStats' => $subjectStats,
            'overallAverage' => $overallAverage,
            'overallLetter' => $overallLetter,
            'passingCount' => $passingCount,
            'failingCount' => $failingCount,
            'chartData' => $chartData,
        ]);
    }
}
