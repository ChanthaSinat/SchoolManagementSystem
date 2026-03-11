<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSemesterRequest;
use App\Http\Requests\Admin\UpdateSemesterRequest;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function index(): View
    {
        $semesters = Semester::with('academicYear')
            ->orderByDesc('is_active')
            ->orderBy('academic_year_id')
            ->orderBy('order')
            ->get();

        return view('admin.semesters.index', compact('semesters'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('admin.semesters.create', compact('academicYears'));
    }

    public function store(StoreSemesterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = (bool) $request->boolean('is_active', true);

        Semester::create($data);

        return redirect()->route('admin.semesters.index')->with('success', __('Semester created successfully.'));
    }

    public function edit(Semester $semester): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('admin.semesters.edit', compact('semester', 'academicYears'));
    }

    public function update(UpdateSemesterRequest $request, Semester $semester): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = (bool) $request->boolean('is_active', true);

        $semester->update($data);

        return redirect()->route('admin.semesters.index')->with('success', __('Semester updated successfully.'));
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $semester->delete();

        return redirect()->route('admin.semesters.index')->with('success', __('Semester deleted successfully.'));
    }
}

