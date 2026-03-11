@extends('layouts.admin-app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Edit School Type</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">{{ $schoolType->name }}</p>
        </div>
        <a href="{{ route('admin.school-types.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Back</a>
    </div>

    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.school-types.update', $schoolType) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $schoolType->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="code" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Code</label>
                    <input id="code" name="code" type="text" value="{{ old('code', $schoolType->code) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('code') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Year Levels</label>
                <p class="text-[11px] text-slate-500 mb-2">Update the labels for this school type.</p>
                <div id="year-levels-container" class="space-y-2">
                    @php $levels = old('year_levels', $schoolType->year_levels ?? []); @endphp
                    @forelse ($levels as $level)
                        <input type="text" name="year_levels[]" value="{{ $level }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none">
                    @empty
                        <input type="text" name="year_levels[]" placeholder="e.g. Grade 1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none">
                    @endforelse
                </div>
                <button type="button" onclick="addYearLevelInput()" class="mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800">+ Add another level</button>
                @error('year_levels.*') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input id="is_default" name="is_default" type="checkbox" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('is_default', $schoolType->is_default) ? 'checked' : '' }}>
                <label for="is_default" class="text-sm font-medium text-slate-700">Set as default school type</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addYearLevelInput() {
        const container = document.getElementById('year-levels-container');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'year_levels[]';
        input.placeholder = 'e.g. Grade 2';
        input.className = 'w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none';
        container.appendChild(input);
    }
</script>
@endsection

