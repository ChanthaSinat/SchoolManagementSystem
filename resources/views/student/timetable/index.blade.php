@extends('layouts.student-app')

@section('content')
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=playfair-display:400,600,700&display=swap" rel="stylesheet" />

<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ __('My Schedule') }}</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">{{ __('Your weekly timetable.') }}</p>
    </div>
    <button type="button" onclick="window.print()" class="print-hide inline-flex items-center px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 bg-white hover:bg-slate-50">
        {{ __('Print') }}
    </button>
</div>

@if (! $enrollment)
    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-5 flex items-center gap-4">
        <div class="bg-amber-100 text-amber-600 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <p class="font-bold text-amber-900 text-sm">{{ __('Enrollment Required') }}</p>
            <p class="text-amber-800/80 text-xs mt-0.5">{{ __('Contact administration to be assigned to a class and see your schedule.') }}</p>
        </div>
    </div>
@endif

<div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Day') }}</th>
                    <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Subject') }}</th>
                    <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Teacher') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($days as $day)
                    @php
                        $entry = $schedule->get($day);
                    @endphp
                    <tr class="{{ $day === $currentDay ? 'bg-amber-50/60' : 'bg-white' }}">
                        <td class="px-6 py-3 text-sm font-semibold text-slate-800 capitalize">
                            {{ $day }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-700">
                            {{ $entry?->subject?->name ?? __('No subject') }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-700">
                            @if ($entry?->teacher)
                                {{ $entry->teacher->full_name ?? ($entry->teacher->first_name . ' ' . $entry->teacher->last_name) }}
                            @else
                                <span class="text-slate-400">{{ __('Unassigned') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
