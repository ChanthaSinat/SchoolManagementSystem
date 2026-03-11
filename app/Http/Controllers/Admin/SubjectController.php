<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminSubjectRequest;
use App\Http\Requests\Admin\UpdateAdminSubjectRequest;
use App\Models\SchoolType;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::with(['schoolType', 'semester.academicYear'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        $schoolTypes = SchoolType::orderBy('name')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('is_active')->orderBy('name')->get();

        return view('admin.subjects.create', compact('schoolTypes', 'semesters'));
    }

    public function store(StoreAdminSubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());

        return redirect()->route('admin.subjects.index')->with('success', __('Subject created successfully.'));
    }

    public function edit(Subject $subject): View
    {
        $schoolTypes = SchoolType::orderBy('name')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('is_active')->orderBy('name')->get();

        return view('admin.subjects.edit', compact('subject', 'schoolTypes', 'semesters'));
    }

    public function update(UpdateAdminSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        return redirect()->route('admin.subjects.index')->with('success', __('Subject updated successfully.'));
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', __('Subject deleted successfully.'));
    }
}

