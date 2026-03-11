<?php

namespace App\Policies;

use App\Models\SchoolClass;
use App\Models\User;
use App\Models\TeacherClass;

class AttendancePolicy
{
    /**
     * Determine whether the user can mark attendance for the class.
     */
    public function mark(User $user, SchoolClass $schoolClass): bool
    {
        if ($user->role === 'admin' || $user->hasRole('admin')) {
            return true;
        }

        if ($user->role === 'teacher' || $user->hasRole('teacher')) {
            return TeacherClass::where('teacher_id', $user->id)
                ->where('school_class_id', $schoolClass->id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view attendance for the class.
     */
    public function view(User $user, SchoolClass $schoolClass): bool
    {
        return $this->mark($user, $schoolClass);
    }
}
