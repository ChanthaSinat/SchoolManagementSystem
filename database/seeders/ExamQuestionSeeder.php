<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamQuestionSeeder extends Seeder
{
    public function run(): void
    {
        if (Question::count() > 0) {
            return;
        }

        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            return;
        }

        $subjects = $subjects->keyBy('code');

        $data = [
            [
                'subject_code' => 'MAT',
                'question_text' => 'What is 5 + 7?',
                'options' => ['10', '11', '12', '13'],
                'correct_option_index' => 2,
            ],
            [
                'subject_code' => 'MAT',
                'question_text' => 'What is 9 × 3?',
                'options' => ['27', '21', '24', '30'],
                'correct_option_index' => 0,
            ],
            [
                'subject_code' => 'PHY',
                'question_text' => 'What force pulls objects toward the Earth?',
                'options' => ['Magnetism', 'Gravity', 'Friction', 'Electricity'],
                'correct_option_index' => 1,
            ],
            [
                'subject_code' => 'CHE',
                'question_text' => 'Water is made of which two elements?',
                'options' => ['Hydrogen and Oxygen', 'Carbon and Oxygen', 'Nitrogen and Hydrogen', 'Sodium and Chlorine'],
                'correct_option_index' => 0,
            ],
            [
                'subject_code' => 'SOC',
                'question_text' => 'Which is a basic need of humans?',
                'options' => ['Television', 'Internet', 'Food', 'Car'],
                'correct_option_index' => 2,
            ],
        ];

        foreach ($data as $item) {
            $subject = $subjects->get($item['subject_code']);

            if (! $subject) {
                continue;
            }

            Question::create([
                'subject_id' => $subject->id,
                'question_text' => $item['question_text'],
                'options' => $item['options'],
                'correct_option_index' => $item['correct_option_index'],
                'difficulty' => 'easy',
            ]);
        }
    }
}

