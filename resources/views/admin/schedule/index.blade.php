@php
    $subjectColors = [
        'indigo' => 'bg-indigo-500',
        'emerald' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
        'rose' => 'bg-rose-500',
        'sky' => 'bg-sky-500',
        'violet' => 'bg-violet-500',
        'teal' => 'bg-teal-500',
        'orange' => 'bg-orange-500',
    ];
    $subjectColorKeys = array_keys($subjectColors);
@endphp
@extends('layouts.admin-app')

@section('content')
<div class="space-y-8">
    <!-- Header and class selector -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Class Schedule</h1>
            <p class="text-slate-500 font-medium mt-1">View weekly timetable for any class (year &amp; semester).</p>
        </div>
        <form method="GET" action="{{ route('admin.schedule.index') }}" class="flex items-center gap-3">
            <div>
                <label for="class_id" class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Select Class</label>
                <select id="class_id" name="class_id" onchange="this.form.submit()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 outline-none min-w-[220px]">
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" {{ $class->id === $selectedClassId ? 'selected' : '' }}>
                            {{ $class->name }} @if($class->academicYear) — {{ $class->academicYear->name }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @if (! $selectedClass)
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 p-10 text-center text-slate-600 font-medium text-sm">
            No classes found. Please create a class first.
        </div>
    @else
        <!-- Day tabs -->
        <div class="day-tabs flex gap-1 mb-6 border-b border-slate-200 pb-2 overflow-x-auto">
            @foreach ($days as $day)
                @php
                    $label = ucfirst(substr($day, 0, 3));
                    $isToday = $day === $currentDay;
                @endphp
                <button type="button" class="day-tab px-4 py-2 rounded-t text-sm font-medium transition whitespace-nowrap {{ $isToday ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-200' : 'text-slate-600 hover:bg-slate-200/80' }}" data-day="{{ $day }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <!-- Timetable panels -->
        @foreach ($days as $day)
            @php
                $slots = $timetable->get($day, collect())->sortBy('start_time');
            @endphp
            <div class="day-panel {{ $day !== $currentDay ? 'hidden' : '' }}" id="day-{{ $day }}" data-day="{{ $day }}">
                @if ($slots->isEmpty())
                    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 p-10 text-center text-slate-600 font-medium text-sm">
                        No classes scheduled for this day.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($slots as $slot)
                            @php
                                $subjectId = $slot->subject_id ?? 0;
                                $colorKey = $subjectColorKeys[$subjectId % count($subjectColorKeys)];
                                $barColor = $subjectColors[$colorKey] ?? 'bg-indigo-500';
                                $teacherName = $slot->user?->full_name ?? $slot->user?->name ?? 'Teacher';
                            @endphp
                            <div class="timetable-card bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden flex">
                                <div class="w-1.5 flex-shrink-0 {{ $barColor }}" aria-hidden="true"></div>
                                <div class="flex-1 p-5 min-w-0">
                                    <p class="text-sm font-bold text-slate-600">{{ $slot->start_time }} — {{ $slot->end_time }}</p>
                                    <h3 class="mt-1.5 text-lg font-bold text-slate-800">{{ $slot->subject->name ?? 'Subject' }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $selectedClass->name }}
                                        @if($slot->section)
                                            · {{ $slot->section->name }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-slate-500 mt-0.5">Teacher: {{ $teacherName }}</p>
                                    @if (!empty($slot->room))
                                        <p class="text-sm text-slate-500 mt-0.5">Room: {{ $slot->room }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <script>
    (function() {
        var tabs = document.querySelectorAll('.day-tab');
        var panels = document.querySelectorAll('.day-panel');
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var day = tab.getAttribute('data-day');
                tabs.forEach(function(t) {
                    t.classList.remove('bg-indigo-500', 'text-white', 'shadow-lg', 'shadow-indigo-200');
                    t.classList.add('text-slate-600');
                });
                tab.classList.add('bg-indigo-500', 'text-white', 'shadow-lg', 'shadow-indigo-200');
                tab.classList.remove('text-slate-600');
                panels.forEach(function(p) {
                    p.classList.add('hidden');
                    if (p.getAttribute('data-day') === day) p.classList.remove('hidden');
                });
            });
        });
    })();
    </script>
</div>
@endsection

