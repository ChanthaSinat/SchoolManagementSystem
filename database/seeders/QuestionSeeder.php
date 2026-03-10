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
        $subject = \App\Models\Subject::where('name', 'Web Programming')->first();

        if (! $subject) {
            return;
        }

        $questions = [
            [
                'question_text' => 'In Laravel, which command creates a new controller named UserController?',
                'options' => [
                    'php artisan make:controller UserController',
                    'php artisan new:controller UserController',
                    'php artisan create:controller UserController',
                    'php artisan controller:make UserController',
                ],
                'correct_option_index' => 0,
                'difficulty' => 'easy',
            ],
            [
                'question_text' => 'In a Blade view, how do you safely echo a variable called $name?',
                'options' => [
                    '{{ name }}',
                    '@name',
                    '{{ $name }}',
                    '<?= $name ?>',
                ],
                'correct_option_index' => 2,
                'difficulty' => 'easy',
            ],
            [
                'question_text' => 'Which HTTP verb is typically used in a form to submit NEW data to a Laravel route?',
                'options' => [
                    'GET',
                    'POST',
                    'PUT',
                    'DELETE',
                ],
                'correct_option_index' => 1,
                'difficulty' => 'easy',
            ],
        ];

        foreach ($questions as $q) {
            \App\Models\Question::firstOrCreate(
                [
                    'subject_id' => $subject->id,
                    'question_text' => $q['question_text'],
                ],
                $q + ['subject_id' => $subject->id]
            );
        }
    }
}
