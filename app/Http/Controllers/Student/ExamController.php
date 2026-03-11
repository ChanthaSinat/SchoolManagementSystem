<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\Question;
use App\Services\ExamService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class ExamController extends Controller
{
    public function __construct(
        protected ExamService $examService
    ) {}

    public function index(): View
    {
        $attempts = ExamAttempt::where('student_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('student.exams.index', compact('attempts'));
    }

    public function start(): RedirectResponse
    {
        try {
            $attempt = $this->examService->startExam(auth()->id());
            return redirect()->route('student.exams.take', $attempt);
        } catch (Exception $e) {
            return redirect()->route('student.exams.index')->with('error', $e->getMessage());
        }
    }

    public function take(ExamAttempt $attempt): View|RedirectResponse
    {
        $this->authorize('take', $attempt);

        if ($attempt->status === 'completed') {
            return redirect()->route('student.exams.results', $attempt);
        }

        $attempt->load('answers.question.subject');

        return view('student.exams.take', compact('attempt'));
    }

    public function store(Request $request, ExamAttempt $attempt): RedirectResponse
    {
        $this->authorize('take', $attempt);

        if ($attempt->status === 'completed') {
            return redirect()->route('student.exams.results', $attempt);
        }

        $this->examService->submitExam($attempt, $request->input('answers', []));

        return redirect()->route('student.exams.results', $attempt)->with('success', 'Exam completed successfully!');
    }

    public function results(ExamAttempt $attempt): View
    {
        $this->authorize('view', $attempt);

        if ($attempt->status !== 'completed') {
            return redirect()->route('student.exams.take', $attempt);
        }

        $attempt->load('answers.question.subject');

        return view('student.exams.results', compact('attempt'));
    }

    public function teacherResults(): View
    {
        $attempts = ExamAttempt::with('student')
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->get();

        return view('teacher.exams.results', compact('attempts'));
    }
}
