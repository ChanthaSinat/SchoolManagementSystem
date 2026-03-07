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
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Schedule') }}</h2>
            <button type="button" onclick="window.print()" class="print-hide inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                {{ __('Print') }}
            </button>
        </div>
    </x-slot>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,600,700&display=swap" rel="stylesheet" />

    <div class="py-8 timetable-page">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="day-tabs print-hide flex gap-1 mb-6 border-b border-gray-200 pb-2">
                @foreach ($days as $day)
                    @php
                        $label = ucfirst(substr($day, 0, 3));
                        $isToday = $day === $currentDay;
                    @endphp
                    <button type="button" class="day-tab px-4 py-2 rounded-t text-sm font-medium transition {{ $isToday ? 'bg-amber-400 text-amber-900' : 'text-gray-600 hover:bg-gray-100' }}" data-day="{{ $day }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @foreach ($days as $day)
                @php
                    $slots = $timetable->get($day, collect())->sortBy('start_time');
                @endphp
                <div class="day-panel {{ $day !== $currentDay ? 'hidden' : '' }} print:!block print:mb-8" id="day-{{ $day }}" data-day="{{ $day }}">
                    @if ($slots->isEmpty())
                        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>{{ __('No classes scheduled') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($slots as $slot)
                                @php
                                    $subjectId = $slot->subject_id ?? 0;
                                    $colorKey = $subjectColorKeys[$subjectId % count($subjectColorKeys)];
                                    $barColor = $subjectColors[$colorKey] ?? 'bg-indigo-500';
                                    $start = $slot->start_time;
                                    $end = $slot->end_time;
                                    $isNow = $day === $currentDay && $currentTime >= $start && $currentTime < $end;
                                    $teacher = $slot->user;
                                    $teacherName = $teacher ? ($teacher->full_name ?? trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? ''))) : '';
                                    $teacherLabel = $teacherName ? 'Mr/Ms ' . $teacherName : '';
                                @endphp
                                <div class="timetable-card bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm flex {{ $isNow ? 'border-l-4 border-l-amber-500' : '' }}">
                                    <div class="w-1.5 flex-shrink-0 {{ $barColor }}" aria-hidden="true"></div>
                                    <div class="flex-1 p-4 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $start }} — {{ $end }}</p>
                                                <h3 class="mt-1 text-xl font-semibold text-gray-900" style="font-family: 'Playfair Display', serif;">{{ $slot->subject->name ?? __('Subject') }}</h3>
                                                @if ($teacherLabel)
                                                    <p class="mt-1 text-sm text-gray-500">{{ $teacherLabel }}</p>
                                                @endif
                                                @if (!empty($slot->room))
                                                    <p class="text-sm text-gray-500">{{ __('Room') }}: {{ $slot->room }}</p>
                                                @endif
                                            </div>
                                            @if ($isNow)
                                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-amber-100 text-amber-800 animate-pulse">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ __('NOW') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <style>
        @media print {
            nav, .navigation, .print-hide, .day-tabs, button.print-hide, header.bg-white { display: none !important; }
            .day-panel { display: block !important; page-break-inside: avoid; }
            .timetable-page { padding-top: 0 !important; }
            .day-panel:not(:first-child) { margin-top: 1.5rem; }
            #day-monday::before { content: "Monday"; display: block; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; }
            #day-tuesday::before { content: "Tuesday"; display: block; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; }
            #day-wednesday::before { content: "Wednesday"; display: block; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; }
            #day-thursday::before { content: "Thursday"; display: block; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; }
            #day-friday::before { content: "Friday"; display: block; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; }
        }
    </style>

    <script>
        (function() {
            var currentDay = @json($currentDay);
            var tabs = document.querySelectorAll('.day-tab');
            var panels = document.querySelectorAll('.day-panel');
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var day = tab.getAttribute('data-day');
                    tabs.forEach(function(t) {
                        t.classList.remove('bg-amber-400', 'text-amber-900');
                        t.classList.add('text-gray-600');
                    });
                    tab.classList.add('bg-amber-400', 'text-amber-900');
                    tab.classList.remove('text-gray-600');
                    panels.forEach(function(p) {
                        p.classList.add('hidden');
                        if (p.getAttribute('data-day') === day) p.classList.remove('hidden');
                    });
                });
            });
        })();
    </script>
</x-app-layout>
