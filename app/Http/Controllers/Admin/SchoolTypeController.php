<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolTypeRequest;
use App\Http\Requests\Admin\UpdateSchoolTypeRequest;
use App\Models\SchoolType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolTypeController extends Controller
{
    public function index(): View
    {
        $schoolTypes = SchoolType::orderBy('name')->get();

        return view('admin.school-types.index', compact('schoolTypes'));
    }

    public function create(): View
    {
        return view('admin.school-types.create');
    }

    public function store(StoreSchoolTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['year_levels'] = $request->input('year_levels', []);
        $data['is_default'] = (bool) $request->boolean('is_default');

        if ($data['is_default']) {
            SchoolType::query()->update(['is_default' => false]);
        }

        SchoolType::create($data);

        return redirect()->route('admin.school-types.index')->with('success', __('School type created successfully.'));
    }

    public function edit(SchoolType $schoolType): View
    {
        return view('admin.school-types.edit', compact('schoolType'));
    }

    public function update(UpdateSchoolTypeRequest $request, SchoolType $schoolType): RedirectResponse
    {
        $data = $request->validated();
        $data['year_levels'] = $request->input('year_levels', []);
        $data['is_default'] = (bool) $request->boolean('is_default');

        if ($data['is_default']) {
            SchoolType::query()->whereKeyNot($schoolType->id)->update(['is_default' => false]);
        }

        $schoolType->update($data);

        return redirect()->route('admin.school-types.index')->with('success', __('School type updated successfully.'));
    }

    public function destroy(SchoolType $schoolType): RedirectResponse
    {
        $schoolType->delete();

        return redirect()->route('admin.school-types.index')->with('success', __('School type deleted successfully.'));
    }
}

