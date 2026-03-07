<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $classes = SchoolClass::with('academicYear')->latest()->paginate(15);

        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        return view('classes.create', compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolClassRequest $request): RedirectResponse
    {
        SchoolClass::create($request->validated());

        return redirect()->route('classes.index')->with('success', __('Class created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $class): View
    {
        $class->load(['academicYear', 'sections', 'subjects']);

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        return view('classes.edit', compact('class', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolClassRequest $request, SchoolClass $class): RedirectResponse
    {
        $class->update($request->validated());

        return redirect()->route('classes.index')->with('success', __('Class updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('classes.index')->with('success', __('Class deleted successfully.'));
    }
}
