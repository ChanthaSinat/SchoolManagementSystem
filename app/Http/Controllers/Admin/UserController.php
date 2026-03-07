<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function teachers(): View
    {
        $teachers = User::role('teacher')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.teachers.index', ['teachers' => $teachers]);
    }

    public function students(): View
    {
        $students = User::role('student')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.students.index', ['students' => $students]);
    }

    public function editTeacher(User $user): View|RedirectResponse
    {
        if (! $user->hasRole('teacher')) {
            abort(404);
        }

        return view('admin.users.edit', [
            'user' => $user,
            'roleLabel' => __('Teacher'),
            'listRoute' => 'admin.teachers.index',
            'listLabel' => __('Teachers'),
        ]);
    }

    public function editStudent(User $user): View|RedirectResponse
    {
        if (! $user->hasRole('student')) {
            abort(404);
        }

        return view('admin.users.edit', [
            'user' => $user,
            'roleLabel' => __('Student'),
            'listRoute' => 'admin.students.index',
            'listLabel' => __('Students'),
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

        $route = $user->hasRole('teacher') ? 'admin.teachers.index' : 'admin.students.index';

        return redirect()->route($route)->with('success', __('User updated successfully.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', __('You cannot remove yourself.'));
        }

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', __('You cannot remove an admin.'));
        }

        if (! $user->hasRole('teacher') && ! $user->hasRole('student')) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        $wasTeacher = $user->hasRole('teacher');
        $user->syncRoles([]);
        $user->delete();

        $route = $wasTeacher ? 'admin.teachers.index' : 'admin.students.index';

        return redirect()->route($route)->with('success', __('User removed successfully.'));
    }
}
