@extends('layouts.teacher-app')

@section('content')

<div class="flex items-end justify-between mb-8 relative">
    <div class="relative z-10">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            Teacher Dashboard
            <span class="inline-flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
            </span>
        </h1>
        <p class="text-slate-500 mt-2 font-medium text-sm sm:text-base">Welcome back! You have <span class="text-indigo-600 font-bold border-b border-indigo-200">{{ count($todaySchedule) }}</span> {{ count($todaySchedule) === 1 ? 'class' : 'classes' }} today.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6 mb-10">
    <div class="group relative bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white hover:shadow-[0_8px_30px_rgb(16,185,129,0.15)] transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 text-white p-3.5 rounded-2xl shadow-lg shadow-emerald-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18"/><path d="M3 12h18"/><path d="M3 17h18"/></svg>
            </div>
        </div>
        <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-widest relative z-10">Total Students</p>
        <p class="text-3xl font-black text-slate-800 relative z-10">{{ $totalStudents }}</p>
    </div>

    <div class="group relative bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white hover:shadow-[0_8px_30px_rgb(99,102,241,0.15)] transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 text-white p-3.5 rounded-2xl shadow-lg shadow-indigo-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
            </div>
        </div>
        <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-widest relative z-10">Lessons Today</p>
        <p class="text-3xl font-black text-slate-800 relative z-10">{{ $lessonsTodayCount }}</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8">
    <div class="xl:col-span-2 space-y-6 md:space-y-8">
        <!-- Schedule -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl opacity-60 -z-10"></div>
            <div class="px-8 py-6 border-b border-slate-100/80 bg-white/50 flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-800">Today's Schedule</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('teacher.schedule.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200/70 flex items-center gap-1.5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        Weekly View
                    </a>
                    <a href="{{ route('teacher.attendance.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition-colors bg-indigo-50 px-3 py-1.5 rounded-lg">
                        Full Attendance
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>
            <div class="p-8 space-y-5">
                @forelse ($todaySchedule as $item)
                    @php
                        $shadow = str_contains($item->color ?? '', 'blue') ? 'shadow-blue-200/50' : (str_contains($item->color ?? '', 'purple') ? 'shadow-purple-200/50' : (str_contains($item->color ?? '', 'indigo') ? 'shadow-indigo-200/50' : 'shadow-indigo-200/50'));
                        $bgClass = str_contains($item->color ?? '', 'blue') ? 'from-blue-400 to-blue-600' : (str_contains($item->color ?? '', 'purple') ? 'from-purple-400 to-purple-600' : (str_contains($item->color ?? '', 'indigo') ? 'from-indigo-400 to-indigo-600' : 'from-indigo-400 to-indigo-600'));
                    @endphp
                    <div class="group flex items-center p-5 rounded-2xl border border-slate-100 bg-white/60 hover:border-indigo-100 hover:bg-indigo-50/40 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br {{ $bgClass }} text-white p-4 rounded-2xl mr-6 shadow-lg {{ $shadow }} flex flex-col items-center justify-center min-w-[76px] transform group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1.5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="text-[11px] font-black uppercase tracking-wider">{{ $item->time_short }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $item->name }}</h3>
                            <div class="flex items-center text-sm text-slate-500 font-semibold space-x-5 mt-1.5 flex-wrap gap-y-2">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    {{ $item->students }} Students
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    {{ str_starts_with(strtolower((string) $item->room), 'room') ? $item->room : 'Room ' . $item->room }}
                                </span>
                            </div>
                            @if (!empty($item->school_class) || !empty($item->section))
                                <div class="mt-2.5 inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-[11px] font-bold text-slate-600 uppercase tracking-wider border border-slate-200/60">
                                    {{ $item->school_class ?? '' }}{{ !empty($item->school_class) && !empty($item->section) ? ' • ' : '' }}{{ $item->section ?? '' }}
                                </div>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2 text-xs font-bold">
                                @if (!empty($item->school_class_id) && !empty($item->section_id))
                                    <a href="{{ route('teacher.attendance.mark', ['class_id' => $item->school_class_id, 'section_id' => $item->section_id, 'date' => now()->toDateString()]) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-100 hover:border-emerald-200 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                        Mark Attendance
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                            <span class="text-2xl opacity-60">☀️</span>
                        </div>
                        <p class="text-lg font-bold text-slate-700">No classes scheduled for today.</p>
                        <p class="text-sm text-slate-500 mt-1">Take a break to plan ahead.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Banner removed: keep teacher dashboard focused on core features -->
    </div>

    <div class="space-y-6 md:space-y-8">
        <!-- Activity -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-7">
            <h2 class="text-xl font-black text-slate-800 mb-7">Recent Activity</h2>
            <div class="space-y-7 relative before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[3px] before:bg-gradient-to-b before:from-indigo-100 before:via-purple-100 before:to-transparent before:rounded-full">
                @forelse ($recentActivity as $sub)
                    <div class="relative pl-10 group cursor-pointer hover:-translate-y-0.5 transition-transform">
                        <div class="absolute left-0 top-0.5 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center {{ $sub->status === 'Graded' ? 'bg-emerald-500' : 'bg-amber-500' }} group-hover:scale-110 transition-transform">
                            @if($sub->status === 'Graded')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $sub->student }}</p>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Submitted <span class="text-slate-700">{{ $sub->assignment }}</span> ({{ $sub->score }})</p>
                        <div class="flex items-center justify-between mt-2.5">
                            <span class="text-[10px] px-2.5 py-1 {{ $sub->status === 'Graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }} rounded-md font-black uppercase tracking-wider">{{ $sub->status }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $sub->date }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center">
                        <p class="text-slate-500 text-sm font-medium">No recent activity.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('teacher.exams.results') }}" class="w-full mt-7 block py-3.5 text-xs font-black text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-all uppercase tracking-widest text-center border border-indigo-100 hover:shadow-sm">
                View Exam Results
            </a>
        </div>

        <!-- Smart Tips / Reminders -->
        <div class="relative group mt-2">
            <!-- Subtle Animated Background Glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-[2.5rem] blur opacity-40 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
            
            <div class="relative bg-white/80 backdrop-blur-xl rounded-[2.25rem] p-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white">
                <!-- Mesh Gradient Background (Light) -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_40%_0%,rgba(129,140,248,0.08),transparent_50%),radial-gradient(circle_at_80%_90%,rgba(139,92,246,0.05),transparent_50%)]"></div>
                
                <div class="relative z-10 space-y-8">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-[0.2em] px-2.5 py-1 rounded-full border border-indigo-100">AI Insights</span>
                            </div>
                            <h2 class="text-2xl font-black tracking-tight text-slate-800 leading-tight">Stay ahead of <br/><span class="text-indigo-600">your classes</span></h2>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex h-2.5 w-2.5 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.3)]"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Grid -->
                    <div class="space-y-4">
                        <!-- Attendance Tip -->
                        <div class="group/item relative">
                            <div class="relative p-5 rounded-2xl border {{ $attendanceMarked ? 'border-emerald-100 bg-emerald-50/50' : 'border-rose-100 bg-rose-50/50' }} flex items-start gap-4 transition-all duration-300 hover:translate-x-1">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 shadow-sm {{ $attendanceMarked ? 'bg-white text-emerald-500 border border-emerald-100' : 'bg-white text-rose-500 border border-rose-100' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm tracking-wide mb-1.5 {{ $attendanceMarked ? 'text-emerald-700' : 'text-rose-700' }}">Attendance Flow</h4>
                                    <p class="text-[12px] leading-relaxed font-semibold {{ $attendanceMarked ? 'text-slate-500' : 'text-rose-600/80' }}">
                                        {{ $attendanceMarked ? 'Brilliant! Your attendance book is fully up to date for today.' : 'Quick reminder: Some of today’s classes are still waiting for attendance updates.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Exam Results Tip -->
                        <div class="group/item relative">
                            <div class="relative p-5 rounded-2xl border border-amber-100 bg-amber-50/50 flex items-start gap-4 transition-all duration-300 hover:translate-x-1">
                                <div class="w-11 h-11 rounded-xl bg-white text-amber-500 border border-amber-100 flex items-center justify-center shrink-0 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6A2 2 0 0 0 4 4v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5Z"/><path d="M14 2v6h6"/><path d="M9 13h6"/><path d="M9 17h3"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-amber-700 tracking-wide mb-1.5">Grading Pulse</h4>
                                    <p class="text-[12px] leading-relaxed font-semibold text-slate-500">
                                        Check the latest exam attempts. A few students might need a gentle push to reach their targets.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <a href="{{ route('teacher.attendance.index') }}" class="group/btn relative flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.25em] transition-all hover:-translate-y-1 active:translate-y-0 shadow-lg shadow-indigo-200 hover:shadow-indigo-300">
                            <span>Optimize Workspace</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
