<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentGradeController;
use App\Http\Controllers\Student\StudentTimetableController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role.dashboard'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/teachers', [AdminUserController::class, 'teachers'])->name('teachers.index');
    Route::get('/students', [AdminUserController::class, 'students'])->name('students.index');
    Route::get('/teachers/{user}/edit', [AdminUserController::class, 'editTeacher'])->name('teachers.edit');
    Route::get('/students/{user}/edit', [AdminUserController::class, 'editStudent'])->name('students.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/mark', [AttendanceController::class, 'showMarkForm'])->name('attendance.mark');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{classId}/{subjectId}', [GradeController::class, 'show'])->name('grades.show');
    Route::post('/grades/{classId}/{subjectId}', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{classId}/{subjectId}/pdf', [GradeController::class, 'exportPdf'])->name('grades.pdf');
});

Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/timetable', [StudentTimetableController::class, 'index'])->name('timetable');
    Route::get('/grades', [StudentGradeController::class, 'index'])->name('grades');
    Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
