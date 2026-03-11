@extends('layouts.admin-app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Add Class / Section</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Create a new class for an academic year and semester.</p>
        </div>
        <a href="{{ route('admin.classes.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Back</a>
    </div>

    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Class Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', 'Grade 12') }}" required placeholder="e.g. Grade 12" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="year_level" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Year Level (optional)</label>
                    <input id="year_level" name="year_level" type="text" value="{{ old('year_level', '12') }}" placeholder="e.g. 12" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('year_level') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
                    Save Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

