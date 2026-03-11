@extends('layouts.student-app')

@php
    // Used for schedule card theming
    $subjectColors = [
        'from-indigo-500 to-purple-500',
        'from-emerald-400 to-teal-500',
        'from-amber-400 to-orange-500',
        'from-rose-400 to-red-500',
        'from-sky-400 to-blue-500',
        'from-violet-500 to-fuchsia-500',
    ];
    $currentTime = now()->format('H:i');
@endphp

@section('content')
@if (session('info'))
    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200/80 p-4 text-sm font-bold text-amber-800">{{ session('info') }}</div>
@endif
@if (session('success'))
    <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm font-bold text-emerald-800">{{ session('success') }}</div>
@endif
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative">
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                Hey, {{ explode(' ', auth()->user()->first_name ?: auth()->user()->name)[0] }}!
                <span class="inline-flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </span>
            </h1>
            <p class="text-slate-500 mt-2 font-medium">
                @if ($todaySchedule->isEmpty())
                    Looks like a relaxing day ahead. No classes scheduled!
                @else
                    You have <span class="text-indigo-600 font-bold border-b-2 border-indigo-100">{{ count($todaySchedule) }}</span> {{ count($todaySchedule) === 1 ? 'class' : 'classes' }} to attend today.
                @endif
            </p>
        </div>
        <div class="hidden md:flex bg-white/60 backdrop-blur-md p-1.5 rounded-2xl shadow-sm border border-white">
            <div class="px-5 py-2.5 text-sm font-bold bg-white text-indigo-900 rounded-xl shadow-sm">Semester 2</div>
        </div>
    </div>

    @if (! $enrollment)
        <div class="bg-amber-50 border border-amber-200 p-5 rounded-2xl flex items-center gap-4 animate-pulse">
            <div class="bg-amber-100 text-amber-600 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <p class="font-bold text-amber-900 text-sm">Enrollment Required</p>
                <p class="text-amber-800/80 text-xs mt-0.5">Contact administration to be assigned to a class and start your learning journey.</p>
            </div>
        </div>
    @else
        <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl flex items-center gap-4">
            <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="font-bold text-emerald-900 text-sm">Enrolled</p>
                <p class="text-emerald-800/80 text-xs mt-0.5">
                    @if (isset($enrolledClasses) && $enrolledClasses->isNotEmpty())
                        {{ $enrolledClasses->pluck('name')->join(', ') }}
                    @else
                        {{ $enrollment->schoolClass->name ?? '—' }}
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="group bg-white/60 backdrop-blur-xl p-6 rounded-3xl border border-white shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 hover:-translate-y-1 overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 relative z-10">Attendance</p>
            <div class="flex items-end justify-between relative z-10">
                <h3 class="text-3xl font-black text-slate-800">{{ $attendanceRate }}%</h3>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-emerald-100 flex items-center justify-center text-[10px] font-bold text-emerald-600">P</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-emerald-100 flex items-center justify-center text-[10px] font-bold text-emerald-600">P</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-rose-100 flex items-center justify-center text-[10px] font-bold text-rose-600 text-xs">A</div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Center Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Today's Schedule -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white shadow-sm overflow-hidden relative">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-white/50">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        Today's Classes
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-200 px-2 py-0.5 rounded-lg">{{ now()->format('D, M d') }}</span>
                    </h2>
                    <a href="{{ route('student.timetable') }}" class="text-xs font-black text-indigo-600 uppercase tracking-[0.15em] hover:text-indigo-800 transition-colors">Full Schedule</a>
                </div>
                
                <div class="p-8">
                    @if ($todaySchedule->isEmpty())
                        <div class="py-12 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                <span class="text-3xl">☕</span>
                            </div>
                            <p class="text-lg font-black text-slate-800">No classes today</p>
                            <p class="text-slate-500 text-sm mt-1 max-w-xs">Use this time to catch up on assignments or review past lessons.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach ($todaySchedule as $index => $slot)
                                    @php
                                        $isNow = $currentTime >= '08:00' && $currentTime < '17:00'; // Simplified for demo
                                        $colorIndex = ($slot?->subject_id ?? $index) % count($subjectColors);
                                        $themeGradient = $subjectColors[$colorIndex];
                                        $teacherName = $slot->teacher ? ($slot->teacher->first_name . ' ' . $slot->teacher->last_name) : 'TBA';
                                    @endphp
                                <div class="group relative bg-white border {{ $isNow ? 'border-indigo-400 ring-4 ring-indigo-500/5' : 'border-slate-100' }} rounded-2xl p-5 hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 transition-all duration-300">
                                    @if ($isNow)
                                        <div class="absolute -top-3 left-4 flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> IN SESSION
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="bg-gradient-to-br {{ $themeGradient }} p-3 rounded-xl text-white shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                {{ $slot->room ? 'Room ' . $slot->room : '—' }}
                                            </p>
                                            <p class="text-xs font-black text-slate-800">
                                                @if($slot->start_time && $slot->end_time)
                                                    {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                                                @else
                                                    —
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <h3 class="text-lg font-black text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight mb-2">{{ $slot->subject->name ?? 'Subject' }}</h3>
                                    
                                    <div class="flex items-center gap-3 pt-3 border-t border-slate-50 mt-4">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                                            {{ substr($teacherName, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Instructor</p>
                                            <p class="text-xs font-bold text-slate-700 leading-none">{{ $teacherName }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Learning Banner -->
            <div class="group bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl shadow-indigo-100">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(79,70,229,0.15),transparent_50%)]"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-1">
                        <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-lg text-[10px] font-black uppercase tracking-[0.2em] mb-4 inline-block border border-white/10">Academic Milestone</span>
                        <h2 class="text-3xl font-black mb-3 leading-tight">Begin your Final Exam</h2>
                        <p class="text-slate-400 text-sm max-w-md mb-6 leading-relaxed font-medium">Test your knowledge across all subjects in this final assessment session.</p>
                        <a href="{{ route('student.exams.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-600/20 active:scale-95">Start Assessment</a>
                    </div>
                    <div class="hidden md:block w-32 h-32 opacity-20 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Sidebar -->
        <div class="space-y-8">
            <!-- Attendance Preview -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white shadow-sm overflow-hidden">
                <div class="p-8 pb-4">
                    <h2 class="text-lg font-black text-slate-800 mb-6">Attendance</h2>
                    <div class="flex gap-3 mb-8">
                        <div class="flex-1 bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4">
                            <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest mb-1">Present</p>
                            <p class="text-xl font-black text-emerald-800">{{ $monthPresent }}</p>
                        </div>
                        <div class="flex-1 bg-rose-50/50 border border-rose-100 rounded-2xl p-4">
                            <p class="text-[9px] font-black text-rose-700 uppercase tracking-widest mb-1">Absent</p>
                            <p class="text-xl font-black text-rose-800">{{ $monthAbsent }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Latest Status</h3>
                    <div class="space-y-4">
                        @foreach ($recentAttendance->take(3) as $a)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $a->date->format('M j') }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $a->date->format('l') }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $a->status === 'present' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($a->status === 'absent' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                                    {{ $a->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('student.attendance') }}" class="w-full border-t border-slate-100 flex items-center justify-center py-5 bg-white/50 hover:bg-white text-emerald-600 text-[10px] font-black uppercase tracking-[0.15em] transition-all">
                    Monthly Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
