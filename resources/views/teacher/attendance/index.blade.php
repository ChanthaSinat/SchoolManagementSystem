@extends('layouts.teacher-app')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Attendance Management</h1>
                <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg uppercase tracking-wider border border-indigo-200">Daily Records</span>
            </div>
            <p class="text-slate-500 font-medium">Track and monitor student presence across your assigned classes.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Date</p>
                    <p class="text-sm font-bold text-slate-700">{{ now()->format('D, M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="bg-emerald-500 text-white p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="bg-rose-500 text-white p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </div>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- New Attendance Session -->
    <div class="bg-white/60 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="p-8 sm:p-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">New Attendance Session</h3>
                    <p class="text-slate-500 text-sm font-medium">Select your class and date to begin marking.</p>
                </div>
            </div>

            <form action="{{ route('teacher.attendance.mark') }}" method="GET" id="attendance-form">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div class="space-y-2">
                        <label for="class_id" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">{{ __('Class') }}</label>
                        <select name="class_id" id="class_id" required
                            class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                            <option value="">{{ __('Select Class') }}</option>
                            @foreach ($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="date" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">{{ __('Date') }}</label>
                        <div class="relative">
                            <input type="date" name="date" id="date" required
                                max="{{ date('Y-m-d') }}"
                                value="{{ request('date', date('Y-m-d')) }}"
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-8 py-4 bg-indigo-600 text-white rounded-2xl text-sm font-black hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 active:scale-95 transition-all flex items-center justify-center gap-2 group">
                            {{ __('Mark Attendance') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- History Container -->
    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-white/50">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Recent Sessions</h3>
                <p class="text-slate-500 text-sm font-medium">History from the last 14 days.</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase border border-emerald-100">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Complete
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Session Date</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Class & Section</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status Breakdown</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Success Rate</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($history as $h)
                        <tr class="group hover:bg-indigo-50/30 transition-all">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $h->date->format('M j, Y') }}</span>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">{{ $h->date->format('l') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs group-hover:bg-white group-hover:text-indigo-600 group-hover:shadow-sm transition-all border border-transparent group-hover:border-indigo-100">
                                        {{ substr($h->class_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $h->class_name }}</p>
                                        <p class="text-[11px] font-medium text-slate-500">Section {{ $h->section_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-4">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-black text-emerald-600">{{ $h->present }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Present</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-black text-rose-500">{{ $h->absent }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Absent</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-black text-amber-500">{{ $h->late }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Late</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $h->rate }}%"></div>
                                    </div>
                                    <span class="text-sm font-black text-slate-700">{{ $h->rate }}%</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('teacher.attendance.mark', ['class_id' => $h->school_class_id, 'section_id' => $h->section_id, 'date' => $h->date->toDateString()]) }}" class="inline-flex p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-sm transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-6 border border-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800 tracking-tight">No Attendance Data</h3>
                                    <p class="text-slate-500 text-sm max-w-xs mt-2 font-medium">Attendance tracking is currently disabled for this simplified Grade 12 setup.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
