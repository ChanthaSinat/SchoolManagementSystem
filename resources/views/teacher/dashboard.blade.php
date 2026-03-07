@extends('layouts.teacher-app')

@section('content')
@php
    $statMap = collect($stats)->keyBy('label');
    $total = $statMap->get('Total Students');
    $avg = $statMap->get('Avg Grade');
    $att = $statMap->get('Attendance');
    $pending = $statMap->get('Pending Tasks');
@endphp

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
    <div class="hidden sm:flex bg-slate-100/80 backdrop-blur-md p-1.5 rounded-xl shadow-[inset_0_2px_4px_rgba(0,0,0,0.06)] border border-slate-200/50">
        <button type="button" class="px-5 py-2.5 text-sm font-bold bg-white text-indigo-900 rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.08)] transition-all transform hover:scale-[1.02]">Today</button>
        <button type="button" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">This Week</button>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6 mb-10">
    <div class="group relative bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white hover:shadow-[0_8px_30px_rgb(59,130,246,0.15)] transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-3.5 rounded-2xl shadow-lg shadow-blue-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="{{ $total->trend_class ?? 'text-emerald-700 bg-emerald-100 border border-emerald-200' }} text-[11px] font-black px-2.5 py-1 rounded-full shadow-sm">{{ $total->trend_text ?? '+2.4%' }}</span>
        </div>
        <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-widest relative z-10">Total Students</p>
        <p class="text-3xl font-black text-slate-800 relative z-10">{{ $total->value ?? '0' }}</p>
    </div>

    <div class="group relative bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white hover:shadow-[0_8px_30px_rgb(16,185,129,0.15)] transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 text-white p-3.5 rounded-2xl shadow-lg shadow-emerald-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <span class="{{ $avg->trend_class ?? 'text-emerald-700 bg-emerald-100 border border-emerald-200' }} text-[11px] font-black px-2.5 py-1 rounded-full shadow-sm">{{ $avg->trend_text ?? '+0.8%' }}</span>
        </div>
        <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-widest relative z-10">Avg Grade</p>
        <p class="text-3xl font-black text-slate-800 relative z-10">{{ $avg->value ?? '0%' }}</p>
    </div>

    <div class="group relative bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white hover:shadow-[0_8px_30px_rgb(139,92,246,0.15)] transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-violet-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="bg-gradient-to-br from-violet-400 to-violet-600 text-white p-3.5 rounded-2xl shadow-lg shadow-violet-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <span class="{{ $att->trend_class ?? 'text-slate-600 bg-slate-100 border border-slate-200' }} text-[11px] font-black px-2.5 py-1 rounded-full shadow-sm">{{ $att->trend_text ?? 'Stable' }}</span>
        </div>
        <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-widest relative z-10">Attendance</p>
        <p class="text-3xl font-black text-slate-800 relative z-10">{{ $att->value ?? '0%' }}</p>
    </div>

    <div class="group relative bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white hover:shadow-[0_8px_30px_rgb(245,158,11,0.15)] transition-all duration-300 hover:-translate-y-1 overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="bg-gradient-to-br from-amber-400 to-amber-500 text-white p-3.5 rounded-2xl shadow-lg shadow-amber-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            </div>
            <span class="{{ $pending->trend_class ?? 'text-rose-700 bg-rose-100 border border-rose-200' }} text-[11px] font-black px-2.5 py-1 rounded-full shadow-sm">{{ $pending->trend_text ?? '-2' }}</span>
        </div>
        <p class="text-xs text-slate-500 font-bold mb-1 uppercase tracking-widest relative z-10">Pending Tasks</p>
        <p class="text-3xl font-black text-slate-800 relative z-10">{{ $pending->value ?? '0' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8">
    <div class="xl:col-span-2 space-y-6 md:space-y-8">
        <!-- Schedule -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl opacity-60 -z-10"></div>
            <div class="px-8 py-6 border-b border-slate-100/80 bg-white/50 flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-800">Today's Schedule</h2>
                <a href="{{ route('teacher.attendance.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition-colors bg-indigo-50 px-3 py-1.5 rounded-lg">Full Calendar <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
            </div>
            <div class="p-8 space-y-5">
                @forelse ($todaySchedule as $item)
                    @php
                        $shadow = str_contains($item->color ?? '', 'blue') ? 'shadow-blue-200/50' : (str_contains($item->color ?? '', 'purple') ? 'shadow-purple-200/50' : (str_contains($item->color ?? '', 'indigo') ? 'shadow-indigo-200/50' : 'shadow-indigo-200/50'));
                        $bgClass = str_contains($item->color ?? '', 'blue') ? 'from-blue-400 to-blue-600' : (str_contains($item->color ?? '', 'purple') ? 'from-purple-400 to-purple-600' : (str_contains($item->color ?? '', 'indigo') ? 'from-indigo-400 to-indigo-600' : 'from-indigo-400 to-indigo-600'));
                    @endphp
                    <div class="group flex items-center p-5 rounded-2xl border border-slate-100 bg-white/60 hover:border-indigo-100 hover:bg-indigo-50/40 hover:shadow-md transition-all duration-300 cursor-pointer hover:-translate-y-0.5">
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

        <!-- Banner -->
        <div class="group bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-800 rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-[0_10px_40px_rgba(79,70,229,0.3)] hover:shadow-[0_15px_50px_rgba(79,70,229,0.4)] transition-all duration-500">
            <!-- Animated background elements -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white rounded-full blur-3xl opacity-10 group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-purple-400 rounded-full blur-3xl opacity-20 group-hover:scale-150 transition-transform duration-1000"></div>
            
            <div class="relative z-10">
                <span class="px-3.5 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-[11px] font-black uppercase tracking-widest mb-5 inline-block border border-white/30 shadow-sm">Pro Feature</span>
                <h2 class="text-2xl md:text-3xl font-black mb-4 tracking-tight text-white drop-shadow-sm">Automated Performance Reports</h2>
                <p class="text-indigo-100 text-sm md:text-base mb-7 max-w-md leading-relaxed font-medium">Use AI to analyze student test scores and generate personalized feedback instantly.</p>
                <a href="{{ route('teacher.grades.index') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-white text-indigo-700 rounded-xl font-bold text-sm hover:shadow-xl hover:bg-slate-50 transition-all duration-300 transform group-hover:-translate-y-1">
                    Generate Now
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
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
                        <p class="text-xs text-slate-500 mt-1 font-medium">Submitted <span class="text-slate-700">{{ $sub->assignment }}</span></p>
                        <span class="text-[10px] px-2.5 py-1 {{ $sub->status === 'Graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }} rounded-md font-black uppercase mt-2.5 inline-block tracking-wider">{{ $sub->status }}</span>
                    </div>
                @empty
                    <div class="py-4 text-center">
                        <p class="text-slate-500 text-sm font-medium">No recent activity.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('teacher.grades.index') }}" class="w-full mt-7 block py-3.5 text-xs font-black text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-all uppercase tracking-widest text-center border border-indigo-100 hover:shadow-sm">
                View History
            </a>
        </div>

        <!-- Deadlines -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-7">
            <h2 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2">
                Upcoming Deadlines
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                </span>
            </h2>
            <div class="space-y-4">
                <div class="group p-5 bg-gradient-to-br from-amber-50/80 to-amber-100/50 rounded-2xl border border-amber-200/60 flex items-start gap-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="bg-amber-100 p-3.5 rounded-xl shadow-sm border border-amber-200 shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-amber-900 text-sm">Grading Deadline</h4>
                        <p class="text-[13px] text-amber-800/80 font-medium mt-1.5 leading-relaxed">Keep gradebooks updated for quarterly reporting.</p>
                    </div>
                </div>
                <div class="group p-5 bg-gradient-to-br from-blue-50/80 to-blue-100/50 rounded-2xl border border-blue-200/60 flex items-start gap-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="bg-blue-100 p-3.5 rounded-xl shadow-sm border border-blue-200 shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065Z"/><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-blue-900 text-sm">Attendance</h4>
                        <p class="text-[13px] text-blue-800/80 font-medium mt-1.5 leading-relaxed">Remember to mark attendance for all classes today.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
