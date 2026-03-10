@extends('layouts.admin-app')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Admin Dashboard</h1>
            <p class="text-slate-500 font-medium mt-1">Welcome back, {{ auth()->user()->name }}. Here's what's happening today.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-sm font-bold text-slate-700">System Live</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Teachers -->
        <div class="group bg-white/60 backdrop-blur-xl p-6 rounded-3xl border border-white shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-3 rounded-2xl shadow-lg shadow-emerald-200 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="bg-emerald-50 text-emerald-700 text-[10px] font-black px-2 py-1 rounded-lg border border-emerald-100 uppercase tracking-wider">Active</div>
            </div>
            <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1 relative z-10">Total Teachers</p>
            <h3 class="text-3xl font-black text-slate-800 relative z-10">{{ $totalTeachers }}</h3>
        </div>

        <!-- Students -->
        <div class="group bg-white/60 backdrop-blur-xl p-6 rounded-3xl border border-white shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-3 rounded-2xl shadow-lg shadow-blue-200 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M17 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="bg-blue-50 text-blue-700 text-[10px] font-black px-2 py-1 rounded-lg border border-blue-100 uppercase tracking-wider">Enrolled</div>
            </div>
            <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1 relative z-10">Total Students</p>
            <h3 class="text-3xl font-black text-slate-800 relative z-10">{{ $totalStudents }}</h3>
        </div>

        <!-- Classes -->
        <div class="group bg-white/60 backdrop-blur-xl p-6 rounded-3xl border border-white shadow-sm hover:shadow-xl hover:shadow-violet-500/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-violet-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="bg-gradient-to-br from-violet-500 to-violet-600 text-white p-3 rounded-2xl shadow-lg shadow-violet-200 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
                </div>
                <div class="bg-violet-50 text-violet-700 text-[10px] font-black px-2 py-1 rounded-lg border border-violet-100 uppercase tracking-wider">Standard</div>
            </div>
            <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1 relative z-10">Classes</p>
            <h3 class="text-3xl font-black text-slate-800 relative z-10">{{ $totalClasses }}</h3>
        </div>

        <!-- Active Context -->
        <div class="group bg-white/60 backdrop-blur-xl p-6 rounded-3xl border border-white shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-3 rounded-2xl shadow-lg shadow-indigo-200 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="bg-indigo-50 text-indigo-700 text-[10px] font-black px-2 py-1 rounded-lg border border-indigo-100 uppercase tracking-wider">Operational</div>
            </div>
            <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1 relative z-10">Active Slots</p>
            <h3 class="text-3xl font-black text-slate-800 relative z-10">{{ $activeEnrollments }}</h3>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Overview & Quick Actions -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-xl font-black text-slate-800">System Overview</h2>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-lg uppercase tracking-wider">Last 30 Days</span>
                </div>
                <div class="p-8">
                    <div class="flex items-center gap-6 mb-8">
                        <div class="flex-1 p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Active Enrollments</p>
                            <div class="flex items-end gap-3">
                                <span class="text-2xl font-black text-slate-800">{{ $activeEnrollments }}</span>
                                <span class="text-emerald-600 text-xs font-bold flex items-center gap-1 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    12%
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Completion Rate</p>
                            <div class="flex items-end gap-3">
                                <span class="text-2xl font-black text-slate-800">94.2%</span>
                                <span class="text-emerald-600 text-xs font-bold flex items-center gap-1 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    2.4%
                                </span>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Quick Management</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('admin.teachers.index') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200 rounded-2xl hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-500/5 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="bg-indigo-50 text-indigo-600 p-2.5 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Manage Teachers</p>
                                    <p class="text-[11px] text-slate-500 font-medium">Add or edit teacher profiles</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200 rounded-2xl hover:border-blue-300 hover:shadow-lg hover:shadow-blue-500/5 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M17 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Manage Students</p>
                                    <p class="text-[11px] text-slate-500 font-medium">Student records & enrollments</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- System Health -->
        <div class="space-y-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white shadow-sm p-8">
                <h2 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    System Health
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </h2>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-emerald-50 text-emerald-600 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6" y1="6" y2="6"/><line x1="6" x2="6" y1="18" y2="18"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Server API</p>
                                <p class="text-[10px] text-slate-500 font-medium">99.9% Uptime</p>
                            </div>
                        </div>
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-emerald-50 text-emerald-600 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Database</p>
                                <p class="text-[10px] text-slate-500 font-medium">Optimized</p>
                            </div>
                        </div>
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-50 text-blue-600 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7"/><path d="M7 21h10"/><path d="M12 13v8"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Media Server</p>
                                <p class="text-[10px] text-slate-500 font-medium">84% Load</p>
                            </div>
                        </div>
                        <div class="w-12 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full w-[84%]"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
