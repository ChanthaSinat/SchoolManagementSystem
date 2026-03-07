@extends('layouts.teacher-app')

@section('content')
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Grades</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">Select a class and subject to open the gradebook.</p>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm font-bold text-emerald-800">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200/80 p-4 text-sm font-bold text-red-800">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
    @forelse ($cards as $card)
        <div class="bg-white/90 backdrop-blur overflow-hidden rounded-2xl shadow-lg border border-slate-200/80 hover:shadow-xl hover:border-indigo-200/80 transition-all group">
            <div class="p-6">
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ $card->subject_name }}</p>
                <p class="mt-2.5 text-lg font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">{{ $card->class_name }}</p>
                <p class="mt-1.5 text-sm text-slate-600 font-medium">
                    {{ __('Section') }}: {{ $card->sections->isEmpty() ? __('All') : $card->sections->pluck('name')->join(', ') }}
                </p>
                <p class="mt-1 text-sm text-slate-600 font-medium">{{ $card->student_count }} {{ __('students') }}</p>
                <a href="{{ route('teacher.grades.show', [$card->school_class_id, $card->subject_id]) }}" class="mt-5 inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-[0.98]">
                    {{ __('Open Gradebook') }}
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 p-10 text-center text-slate-600 font-medium text-sm">
            {{ __('No class or subject assignments found.') }}
        </div>
    @endforelse
</div>
@endsection
