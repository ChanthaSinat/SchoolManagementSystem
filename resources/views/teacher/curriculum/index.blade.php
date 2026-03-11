@extends('layouts.teacher-app')

@section('content')
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ __('Curriculum') }}</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">{{ __('Classes and subjects you teach.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
    @forelse ($items as $item)
        <div class="bg-white/90 backdrop-blur overflow-hidden rounded-2xl shadow-lg border border-slate-200/80 hover:shadow-xl hover:border-indigo-200/80 transition-all group">
            <div class="p-6">
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ __('Class') }}</p>
                <p class="mt-2.5 text-lg font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">{{ $item->class_name }}</p>
                <p class="mt-2 text-sm font-semibold text-slate-700">{{ __('Subjects') }}:</p>
                <ul class="mt-1 text-sm text-slate-600 space-y-0.5">
                    @forelse ($item->subjects as $subject)
                        <li>{{ $subject->name }}</li>
                    @empty
                        <li class="text-slate-500">{{ __('None assigned') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 p-10 text-center text-slate-600 font-medium text-sm">
            {{ __('No class assignments found.') }}
        </div>
    @endforelse
</div>
@endsection
