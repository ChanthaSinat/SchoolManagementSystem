@extends('layouts.teacher-app')

@section('content')
<div class="flex items-end justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ __('Schedule') }}</h1>
        <p class="text-slate-600 mt-1.5 font-medium text-sm">{{ __('Your weekly timetable.') }}</p>
    </div>
</div>

<div class="max-w-3xl bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50/80">
            <tr>
                <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Day') }}</th>
                <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Class') }}</th>
                <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Subject') }}</th>
                <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Room') }}</th>
                <th class="px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Time') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($days as $day)
                @php
                    $entries = $schedule->get($day, collect());
                @endphp
                @if ($entries->isEmpty())
                    <tr class="{{ $day === $currentDay ? 'bg-indigo-50/60' : 'bg-white' }}">
                        <td class="px-6 py-3 text-sm font-semibold text-slate-800 capitalize">
                            {{ $day }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-500" colspan="4">
                            {{ __('No classes scheduled') }}
                        </td>
                    </tr>
                @else
                    @foreach ($entries as $index => $entry)
                        <tr class="{{ $day === $currentDay ? 'bg-indigo-50/60' : 'bg-white' }}">
                            <td class="px-6 py-3 text-sm font-semibold text-slate-800 capitalize">
                                @if ($index === 0)
                                    {{ $day }}
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-700">
                                {{ $entry->schoolClass?->name ?? __('Class') }}
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-700">
                                {{ $entry->subject?->name ?? __('Subject') }}
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-600 font-medium">
                                {{ $entry->room ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-600">
                                @if($entry->start_time && $entry->end_time)
                                    {{ $entry->start_time->format('H:i') }} - {{ $entry->end_time->format('H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
