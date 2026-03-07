<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\TeacherClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function createTeacher(): View
    {
        return view('admin.users.create', [
            'roleLabel' => __('Teacher'),
            'listRoute' => 'admin.teachers.index',
            'listLabel' => __('Teachers'),
            'role' => 'teacher',
        ]);
    }

    public function createStudent(): View
    {
        return view('admin.users.create', [
            'roleLabel' => __('Student'),
            'listRoute' => 'admin.students.index',
            'listLabel' => __('Students'),
            'role' => 'student',
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $role = $request->input('role');
        $user = User::create([
            'name' => trim($request->first_name . ' ' . $request->last_name),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);
        $user->assignRole($role);

        $route = $role === 'teacher' ? 'admin.teachers.index' : 'admin.students.index';

        return redirect()->route($route)->with('success', __('User created successfully.'));
    }

    public function teachers(): View
    {
        $teachers = User::where(function ($q) {
            $q->where('role', 'teacher')->orWhereHas('roles', fn ($q) => $q->where('name', 'teacher'));
        })->orderBy('first_name')->orderBy('last_name')->get();

        return view('admin.teachers.index', ['teachers' => $teachers]);
    }

    public function students(): View
    {
        $students = User::where(function ($q) {
            $q->where('role', 'student')->orWhereHas('roles', fn ($q) => $q->where('name', 'student'));
        })->orderBy('first_name')->orderBy('last_name')->get();

        return view('admin.students.index', ['students' => $students]);
    }

    public function editTeacher(User $user): View|RedirectResponse
    {
        if (! $user->hasRole('teacher') && $user->role !== 'teacher') {
            abort(404);
        }

        $schoolClasses = SchoolClass::with('sections')->orderBy('name')->get();
        $teacherClassIds = $user->teacherClasses()->pluck('school_class_id')->all();

        return view('admin.users.edit', [
            'user' => $user,
            'roleLabel' => __('Teacher'),
            'listRoute' => 'admin.teachers.index',
            'listLabel' => __('Teachers'),
            'schoolClasses' => $schoolClasses,
            'teacherClassIds' => $teacherClassIds,
            'enrollment' => null,
        ]);
    }

    public function editStudent(User $user): View|RedirectResponse
    {
        if (! $user->hasRole('student') && $user->role !== 'student') {
            abort(404);
        }

        $schoolClasses = SchoolClass::with('sections')->orderBy('name')->get();
        $enrollment = Enrollment::where('student_id', $user->id)->where('status', 'active')->with(['schoolClass', 'section'])->first();

        return view('admin.users.edit', [
            'user' => $user,
            'roleLabel' => __('Student'),
            'listRoute' => 'admin.students.index',
            'listLabel' => __('Students'),
            'schoolClasses' => $schoolClasses,
            'teacherClassIds' => [],
            'enrollment' => $enrollment,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->only(['first_name', 'last_name', 'email', 'phone']));
        $user->name = trim($user->first_name . ' ' . $user->last_name);

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'teacher' || $user->hasRole('teacher')) {
            $classIds = $request->input('class_ids', []);
            $classIds = is_array($classIds) ? array_filter(array_map('intval', $classIds)) : [];
            TeacherClass::where('teacher_id', $user->id)->delete();
            foreach ($classIds as $schoolClassId) {
                TeacherClass::firstOrCreate(
                    ['teacher_id' => $user->id, 'school_class_id' => $schoolClassId],
                    ['teacher_id' => $user->id, 'school_class_id' => $schoolClassId]
                );
            }
        }

        if ($user->role === 'student' || $user->hasRole('student')) {
            $schoolClassId = $request->input('school_class_id') ? (int) $request->input('school_class_id') : null;
            $sectionId = $request->input('section_id') ? (int) $request->input('section_id') : null;

            Enrollment::where('student_id', $user->id)->update(['status' => 'inactive']);

            if ($schoolClassId && $sectionId) {
                Enrollment::updateOrCreate(
                    [
                        'student_id' => $user->id,
                        'school_class_id' => $schoolClassId,
                        'section_id' => $sectionId,
                    ],
                    [
                        'status' => 'active',
                        'roll_number' => $request->input('roll_number') ? (int) $request->input('roll_number') : null,
                        'guardian_phone' => $request->input('guardian_phone'),
                    ]
                );
            }
        }

        $route = ($user->role === 'teacher' || $user->hasRole('teacher')) ? 'admin.teachers.index' : 'admin.students.index';

        return redirect()->route($route)->with('success', __('User updated successfully.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', __('You cannot remove yourself.'));
        }

        if ($user->role === 'admin' || $user->hasRole('admin')) {
            return redirect()->back()->with('error', __('You cannot remove an admin.'));
        }

        if ($user->role !== 'teacher' && $user->role !== 'student' && ! $user->hasRole('teacher') && ! $user->hasRole('student')) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        $wasTeacher = $user->role === 'teacher' || $user->hasRole('teacher');
        $user->syncRoles([]);
        $user->delete();

        $route = $wasTeacher ? 'admin.teachers.index' : 'admin.students.index';

        return redirect()->route($route)->with('success', __('User removed successfully.'));
    }
}
