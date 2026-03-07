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
        $totalUsers = User::count();
        $totalTeachers = User::role('teacher')->count();
        $totalStudents = User::role('student')->count();
        $totalClasses = SchoolClass::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalTeachers' => $totalTeachers,
            'totalStudents' => $totalStudents,
            'totalClasses' => $totalClasses,
            'activeEnrollments' => $activeEnrollments,
        ]);
    }
}
