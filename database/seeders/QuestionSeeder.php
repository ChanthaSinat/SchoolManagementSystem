<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = \App\Models\Subject::whereIn('name', ['Mathematics', 'Physics I'])->get();

        foreach ($subjects as $subject) {
            if ($subject->name === 'Mathematics') {
                $questions = [
                    [
                        'question_text' => 'What is 5 + 7?',
                        'options' => ['10', '11', '12', '13'],
                        'correct_option_index' => 2,
                        'difficulty' => 'easy',
                    ],
                    [
                        'question_text' => 'What is the square root of 64?',
                        'options' => ['6', '7', '8', '9'],
                        'correct_option_index' => 2,
                        'difficulty' => 'easy',
                    ],
                    [
                        'question_text' => 'Solve for x: 2x = 10.',
                        'options' => ['2', '3', '5', '10'],
                        'correct_option_index' => 2,
                        'difficulty' => 'easy',
                    ],
                ];
            } else {
                $questions = [
                    [
                        'question_text' => 'What is the unit of force?',
                        'options' => ['Newton', 'Joule', 'Watt', 'Volt'],
                        'correct_option_index' => 0,
                        'difficulty' => 'easy',
                    ],
                    [
                        'question_text' => 'What planet is known as the Red Planet?',
                        'options' => ['Earth', 'Mars', 'Jupiter', 'Venus'],
                        'correct_option_index' => 1,
                        'difficulty' => 'easy',
                    ],
                    [
                        'question_text' => 'Light year is a unit of?',
                        'options' => ['Time', 'Distance', 'Speed', 'Mass'],
                        'correct_option_index' => 1,
                        'difficulty' => 'easy',
                    ],
                ];
            }

            foreach ($questions as $q) {
                \App\Models\Question::create(array_merge($q, ['subject_id' => $subject->id]));
            }
        }
    }
}
