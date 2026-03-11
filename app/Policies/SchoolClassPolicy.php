<?php

namespace App\Policies;

use App\Models\SchoolClass;
use App\Models\User;

class SchoolClassPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SchoolClass $schoolClass): bool
    {
        if ($user->role === 'admin' || $user->hasRole('admin')) {
            return true;
        }

        // Teachers can view classes they are assigned to
        if ($user->role === 'teacher' || $user->hasRole('teacher')) {
            return $schoolClass->teachers()->where('teacher_id', $user->id)->exists();
        }

        // Students can view classes they are enrolled in
        if ($user->role === 'student' || $user->hasRole('student')) {
            return $schoolClass->students()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SchoolClass $schoolClass): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin');
    }
}
