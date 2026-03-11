<?php

namespace App\Services;

use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\Question;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * Start a new exam attempt for a student.
     *
     * @param int $studentId
     * @return ExamAttempt
     */
    public function startExam(int $studentId): ExamAttempt
    {
        return DB::transaction(function () use ($studentId) {
            $existing = ExamAttempt::where('student_id', $studentId)
                ->where('status', 'in_progress')
                ->first();

            if ($existing) {
                return $existing;
            }

            $subjects = Subject::all();
            $questionIds = [];

            foreach ($subjects as $subject) {
                $ids = Question::where('subject_id', $subject->id)
                    ->where('difficulty', 'easy') // Default to easy for now
                    ->inRandomOrder()
                    ->limit(3)
                    ->pluck('id')
                    ->toArray();
                
                $questionIds = array_merge($questionIds, $ids);
            }

            if (empty($questionIds)) {
                throw new \Exception('No questions available for the exam.');
            }

            $attempt = ExamAttempt::create([
                'student_id' => $studentId,
                'total_questions' => count($questionIds),
                'status' => 'in_progress',
            ]);

            foreach ($questionIds as $qid) {
                ExamAttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $qid,
                ]);
            }

            return $attempt;
        });
    }

    /**
     * Store exam results and calculate score.
     *
     * @param ExamAttempt $attempt
     * @param array $answers
     * @return ExamAttempt
     */
    public function submitExam(ExamAttempt $attempt, array $answers): ExamAttempt
    {
        return DB::transaction(function () use ($attempt, $answers) {
            $correctCount = 0;
            $attempt->load('answers.question');

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

            return $attempt;
        });
    }
}
