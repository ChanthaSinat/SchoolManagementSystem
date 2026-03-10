<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalTeachers = User::where('role', 'teacher')
            ->orWhereHas('roles', fn ($q) => $q->where('name', 'teacher'))
            ->count();

        $totalStudents = User::where(function ($q) {
                $q->where('role', 'student')
                  ->orWhereHas('roles', fn ($q) => $q->where('name', 'student'));
            })
            ->where(function ($q) {
                $q->where('role', '!=', 'admin')
                  ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'));
            })
            ->count();
        $totalClasses = SchoolClass::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();

        return view('admin.dashboard', [
            'totalTeachers' => $totalTeachers,
            'totalStudents' => $totalStudents,
            'totalClasses' => $totalClasses,
            'activeEnrollments' => $activeEnrollments,
        ]);
    }
}
