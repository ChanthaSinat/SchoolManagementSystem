<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\Question;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExamController extends Controller
{
    public function index(): View
    {
        $attempts = ExamAttempt::where('student_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('student.exams.index', compact('attempts'));
    }

    public function start(): RedirectResponse
    {
        // Check if there's an in-progress attempt
        $existing = ExamAttempt::where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            return redirect()->route('student.exams.take', $existing);
        }

        return DB::transaction(function () {
            $subjects = Subject::all();
            $questionIds = [];

            foreach ($subjects as $subject) {
                $ids = Question::where('subject_id', $subject->id)
                    ->where('difficulty', 'easy')
                    ->inRandomOrder()
                    ->limit(3)
                    ->pluck('id')
                    ->toArray();
                
                $questionIds = array_merge($questionIds, $ids);
            }

            if (empty($questionIds)) {
                return redirect()->route('student.exams.index')->with('error', 'No questions available for the exam.');
            }

            $attempt = ExamAttempt::create([
                'student_id' => auth()->id(),
                'total_questions' => count($questionIds),
                'status' => 'in_progress',
            ]);

            foreach ($questionIds as $qid) {
                ExamAttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $qid,
                ]);
            }

            return redirect()->route('student.exams.take', $attempt);
        });
    }

    public function take(ExamAttempt $attempt): View|RedirectResponse
    {
        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.exams.results', $attempt);
        }

        $attempt->load('answers.question.subject');

        return view('student.exams.take', compact('attempt'));
    }

    public function store(Request $request, ExamAttempt $attempt): RedirectResponse
    {
        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.exams.results', $attempt);
        }

        $answers = $request->input('answers', []);
        $correctCount = 0;

        $attempt->load('answers.question');

        DB::transaction(function () use ($attempt, $answers, &$correctCount) {
            foreach ($attempt->answers as $attemptAnswer) {
                $question = $attemptAnswer->question;
                $selectedIndex = $answers[$attemptAnswer->id] ?? null;
                
                $isCorrect = false;
                if ($selectedIndex !== null && (int)$selectedIndex === $question->correct_option_index) {
                    $isCorrect = true;
                    $correctCount++;
                }

                $attemptAnswer->update([
                    'selected_option_index' => $selectedIndex,
                    'is_correct' => $isCorrect,
                ]);
            }

            $score = ($attempt->total_questions > 0) ? ($correctCount / $attempt->total_questions) * 100 : 0;

            $attempt->update([
                'score' => $score,
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]);
        });

        return redirect()->route('student.exams.results', $attempt)->with('success', 'Exam completed successfully!');
    }

    public function results(ExamAttempt $attempt): View
    {
        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

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
