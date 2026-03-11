<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentTimetableController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\CurriculumController;
use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\Teacher\StudentsController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
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
    Route::get('/teachers/create', [AdminUserController::class, 'createTeacher'])->name('teachers.create');
    Route::get('/students/create', [AdminUserController::class, 'createStudent'])->name('students.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/teachers/{user}/edit', [AdminUserController::class, 'editTeacher'])->name('teachers.edit');
    Route::get('/students/{user}/edit', [AdminUserController::class, 'editStudent'])->name('students.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Admin tools
    Route::post('/teachers/{user}/generate-schedule', [AdminUserController::class, 'generateSchedule'])
        ->name('teachers.generate-schedule');

    // Simple class management (no curriculum complexity)
    Route::resource('classes', AdminClassController::class)
        ->except('show')
        ->names('classes');

    Route::post('classes/{class}/assignments', [AdminClassController::class, 'updateAssignments'])
        ->name('classes.assignments');

    Route::post('classes/{class}/schedule', [AdminClassController::class, 'storeSchedule'])->name('classes.schedule.store');
    Route::post('classes/{class}/schedule/generate', [AdminClassController::class, 'generateRandomSchedule'])->name('classes.schedule.generate');
    Route::post('classes/{class}/schedule/reset', [AdminClassController::class, 'resetSchedule'])->name('classes.schedule.reset');
});

Route::middleware(['auth', 'verified', 'role.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/mark', [AttendanceController::class, 'showMarkForm'])->name('attendance.mark');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
    Route::get('/curriculum', [CurriculumController::class, 'index'])->name('curriculum.index');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    
    // Exam Results for Teacher
    Route::get('/exams/results', [StudentExamController::class, 'teacherResults'])->name('exams.results');
});

Route::middleware(['auth', 'verified', 'role.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/timetable', [StudentTimetableController::class, 'index'])->name('timetable');
    Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance');

    // Final Exam Routes
    Route::get('/exams', [StudentExamController::class, 'index'])->name('exams.index');
    Route::post('/exams/start', [StudentExamController::class, 'start'])->name('exams.start');
    Route::get('/exams/{attempt}/take', [StudentExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{attempt}/store', [StudentExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{attempt}/results', [StudentExamController::class, 'results'])->name('exams.results');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
