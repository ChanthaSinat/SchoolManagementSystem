@extends('layouts.admin-app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Edit Subject</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">{{ $subject->name }}</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Back</a>
    </div>

    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $subject->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="code" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Code (optional)</label>
                    <input id="code" name="code" type="text" value="{{ old('code', $subject->code) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white/60 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 outline-none text-sm">
                    @error('code') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description and units removed for simplicity --}}

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

