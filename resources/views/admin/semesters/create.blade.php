@extends('layouts.admin-app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Add Semester / Term</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Attach the term to an academic year.</p>
        </div>
        <a href="{{ route('admin.semesters.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Back</a>
    </div>

    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.semesters.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="academic_year_id" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Academic Year</label>
                <select id="academic_year_id" name="academic_year_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none">
                    <option value="">Select academic year</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected(old('academic_year_id') == $year->id)>{{ $year->name }}</option>
                    @endforeach
                </select>
                @error('academic_year_id') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. 1st Semester 2024–2025" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="code" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Code (optional)</label>
                    <input id="code" name="code" type="text" value="{{ old('code') }}" placeholder="e.g. 2024-1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('code') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="order" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Order</label>
                    <input id="order" name="order" type="number" min="1" value="{{ old('order') }}" placeholder="1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('order') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-2 mt-6 md:mt-8">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-slate-700">Set as active</label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
                    Save Semester
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

