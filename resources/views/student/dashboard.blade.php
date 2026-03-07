@php
    $letterColors = ['A' => 'from-green-500 to-emerald-600', 'B' => 'from-emerald-400 to-green-500', 'C' => 'from-amber-400 to-orange-500', 'D' => 'from-orange-500 to-red-500', 'F' => 'from-red-500 to-rose-600'];
    $subjectColors = ['from-indigo-500 to-purple-500', 'from-emerald-400 to-teal-500', 'from-amber-400 to-orange-500', 'from-rose-400 to-red-500', 'from-sky-400 to-blue-500', 'from-violet-500 to-fuchsia-500'];
    $subjectBorderColors = ['border-indigo-500', 'border-emerald-500', 'border-amber-500', 'border-rose-500', 'border-sky-500', 'border-violet-500'];
    $subjectTextColors = ['text-indigo-600', 'text-emerald-600', 'text-amber-600', 'text-rose-600', 'text-sky-600', 'text-violet-600'];
    $currentTime = now()->format('H:i');
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 tracking-tight">{{ __('Student Dashboard') }}</h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- TOP: 2 stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="group relative bg-white/70 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden p-6">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider relative z-10">{{ __('Overall Average') }}</p>
                    <div class="mt-2 flex items-baseline gap-3 relative z-10">
                        <p class="text-4xl font-black text-gray-900 tracking-tight">
                            {{ $overallAvg }}%
                        </p>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold text-white bg-gradient-to-r {{ $letterColors[$overallLetter] ?? 'from-gray-400 to-gray-500' }} shadow-md">
                            Grade {{ $overallLetter }}
                        </span>
                    </div>
                </div>
                
                @php
                    $rateColor = $attendanceRate >= 90 ? 'text-emerald-500' : ($attendanceRate >= 75 ? 'text-amber-500' : 'text-rose-500');
                    $rateBg = $attendanceRate >= 90 ? 'from-emerald-100 to-teal-100' : ($attendanceRate >= 75 ? 'from-amber-100 to-orange-100' : 'from-rose-100 to-red-100');
                @endphp
                <div class="group relative bg-white/70 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden p-6">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br {{ $rateBg }} rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider relative z-10">{{ __('Attendance Rate') }}</p>
                    <p class="mt-2 text-4xl font-black {{ $rateColor }} tracking-tight relative z-10 drop-shadow-sm">{{ $attendanceRate }}%</p>
                </div>
            </div>

            {{-- MIDDLE: Today's Schedule --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white shadow-lg p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50 -z-10"></div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('Today') }} <span class="text-gray-400 font-medium">—</span> <span class="text-indigo-600">{{ now()->format('l, F j') }}</span>
                </h3>
                
                @if ($todaySchedule->isEmpty())
                    <div class="py-16 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <span class="text-2xl">🎉</span>
                        </div>
                        <p class="text-xl font-semibold text-gray-800">{{ __("No classes today") }}</p>
                        <p class="text-gray-500 mt-1">{{ __("Enjoy your free time!") }}</p>
                    </div>
                @else
                    <div class="flex flex-wrap gap-5">
                        @foreach ($todaySchedule as $slot)
                            @php
                                $isNow = $currentTime >= $slot->start_time && $currentTime < $slot->end_time;
                                $colorIndex = ($slot->subject_id ?? 0) % count($subjectColors);
                                $themeGradient = $subjectColors[$colorIndex];
                                $themeBorder = $subjectBorderColors[$colorIndex];
                                $teacherName = $slot->user ? ($slot->user->full_name ?? trim(($slot->user->first_name ?? '') . ' ' . ($slot->user->last_name ?? ''))) : '';
                            @endphp
                            <div class="group flex min-w-[240px] max-w-sm flex-1 rounded-xl bg-white shadow-sm hover:shadow-lg transition-all duration-300 {{ $isNow ? 'ring-2 ring-amber-400 ring-offset-2 scale-[1.02]' : 'border border-gray-100 hover:-translate-y-1' }} overflow-hidden">
                                <div class="w-2 bg-gradient-to-b {{ $themeGradient }}" aria-hidden="true"></div>
                                <div class="flex-1 p-5 relative">
                                    @if ($isNow)
                                        <div class="absolute top-4 right-4 flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 shadow-[0_0_10px_rgba(251,191,36,0.5)]">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> {{ __('NOW') }}
                                        </div>
                                    @endif
                                    
                                    <p class="text-sm font-bold text-gray-500 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $slot->start_time }} — {{ $slot->end_time }}
                                    </p>
                                    <p class="mt-2 text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $slot->subject->name ?? __('Subject') }}</p>
                                    
                                    <div class="mt-3 pt-3 border-t border-gray-50 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                            {{ substr($teacherName ?: 'T', 0, 1) }}
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">{{ $teacherName ? 'Mr/Ms ' . $teacherName : '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- BOTTOM: two columns --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                {{-- LEFT: Recent Grades --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white shadow-lg p-6 relative flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">{{ __('Recent Grades') }}</h3>
                        <a href="{{ route('student.grades') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">{{ __('View All') }}</a>
                    </div>
                    
                    @if ($recentGrades->isEmpty())
                        <div class="flex-1 flex flex-col items-center justify-center py-8">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">{{ __('No grades yet.') }}</p>
                        </div>
                    @else
                        <ul class="space-y-4 flex-1">
                            @foreach ($recentGrades as $g)
                                @php
                                    $pct = ($g->max_score > 0 && $g->score !== null) ? round(((float)$g->score / (float)$g->max_score) * 100, 1) : null;
                                    $letter = $pct === null ? '—' : ($pct >= 90 ? 'A' : ($pct >= 80 ? 'B' : ($pct >= 70 ? 'C' : ($pct >= 60 ? 'D' : 'F'))));
                                    $colorIdx = ($g->subject_id ?? 0) % count($subjectColors);
                                    $themeGradient = $subjectColors[$colorIdx];
                                @endphp
                                <li class="group flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-all cursor-pointer">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $themeGradient }} p-0.5 shadow-sm group-hover:shadow-md transition-shadow">
                                            <div class="w-full h-full bg-white rounded-[6px] flex items-center justify-center">
                                                <span class="text-xs font-black {{ $subjectTextColors[$colorIdx] }}">{{ substr($g->assessment_name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $g->assessment_name }}</p>
                                            <p class="text-xs font-medium text-gray-500 mt-0.5">{{ $g->subject->name ?? 'Subject' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-right">
                                            <p class="text-lg font-black text-gray-900">{{ $g->score !== null ? $g->score : '—' }}<span class="text-sm font-medium text-gray-400">/{{ $g->max_score }}</span></p>
                                        </div>
                                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-bold text-white shadow-sm bg-gradient-to-br {{ $letterColors[$letter] ?? 'from-gray-400 to-gray-500' }}">{{ $letter }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- RIGHT: Attendance This Month --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white shadow-lg p-6 relative flex flex-col overflow-hidden">
                    <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-emerald-50 rounded-full blur-3xl opacity-60 -z-10"></div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ __('Attendance') }}</h3>
                        <a href="{{ route('student.attendance') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors">{{ __('View Full') }}</a>
                    </div>
                    
                    <div class="flex gap-4 mb-6">
                        <div class="flex-1 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-3 border border-emerald-200/50">
                            <p class="text-xs font-bold text-emerald-800 uppercase mb-1">{{ __('Present') }}</p>
                            <p class="text-2xl font-black text-emerald-600">{{ $monthPresent }}</p>
                        </div>
                        <div class="flex-1 bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl p-3 border border-rose-200/50">
                            <p class="text-xs font-bold text-rose-800 uppercase mb-1">{{ __('Absent') }}</p>
                            <p class="text-2xl font-black text-rose-600">{{ $monthAbsent }}</p>
                        </div>
                    </div>
                    
                    @if ($recentAttendance->isEmpty())
                        <div class="flex-1 flex flex-col items-center justify-center py-4">
                            <p class="text-gray-500 font-medium">{{ __('No attendance records this month.') }}</p>
                        </div>
                    @else
                        <div class="flex-1 overflow-hidden rounded-xl border border-gray-100">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr class="text-left text-gray-500 tracking-wider">
                                        <th class="py-3 px-4 font-bold uppercase text-xs">{{ __('Date') }}</th>
                                        <th class="py-3 px-4 font-bold uppercase text-xs">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($recentAttendance as $a)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="py-3 px-4 text-gray-900 font-medium">
                                                {{ $a->date->format('M j, Y') }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($a->status === 'present')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ __('Present') }}
                                                    </span>
                                                @elseif ($a->status === 'absent')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> {{ __('Absent') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ __('Late') }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
