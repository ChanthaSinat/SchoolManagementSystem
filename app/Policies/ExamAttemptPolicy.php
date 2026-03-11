<?php

namespace App\Policies;

use App\Models\ExamAttempt;
use App\Models\User;

class ExamAttemptPolicy
{
    /**
     * Determine whether the user can view the exam attempt results.
     */
    public function view(User $user, ExamAttempt $examAttempt): bool
    {
        if ($user->id === $examAttempt->student_id) {
            return true;
        }

        if ($user->role === 'admin' || $user->hasRole('admin')) {
            return true;
        }

        if ($user->role === 'teacher' || $user->hasRole('teacher')) {
            // Further refinement could check if the teacher teaches this student's class
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can take the exam attempt.
     */
    public function take(User $user, ExamAttempt $examAttempt): bool
    {
        return $user->id === $examAttempt->student_id && $examAttempt->status === 'in_progress';
    }
}
