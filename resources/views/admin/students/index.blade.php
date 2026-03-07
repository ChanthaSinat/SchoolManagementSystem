@extends('layouts.admin-app')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Students</h1>
                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-black rounded-lg uppercase tracking-wider border border-blue-200">{{ count($students) }} Enrolled</span>
            </div>
            <p class="text-slate-500 font-medium">Manage student records and academic progress.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-white p-2.5 rounded-xl shadow-sm border border-slate-200 text-slate-600 hover:text-blue-600 transition-all hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
            </button>
            <a href="{{ route('admin.students.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                Add Student
            </a>
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

    <!-- Main Table Container -->
    <div class="bg-white/60 backdrop-blur-xl rounded-3xl border border-white shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-white/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" placeholder="Filter students..." class="w-full pl-10 pr-4 py-2 bg-slate-100/50 border border-slate-200 rounded-xl text-xs focus:ring-4 focus:ring-blue-500/10 focus:bg-white focus:border-blue-400 outline-none transition-all">
            </div>
            <div class="flex items-center gap-2">
                <div class="flex bg-slate-100 rounded-xl p-1 shrink-0">
                    <button class="px-3 py-1.5 text-[10px] font-black rounded-lg bg-white text-blue-600 shadow-sm transition-all uppercase tracking-wider">All Students</button>
                    <button class="px-3 py-1.5 text-[10px] font-black rounded-lg text-slate-500 hover:text-slate-700 transition-all uppercase tracking-wider">Active</button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-100">Student Profile</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-100">Communication</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-100 text-right">Administrative Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="group hover:bg-blue-50/30 transition-all">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-blue-50 to-blue-100 flex items-center justify-center text-blue-600 font-black text-lg border-2 border-white shadow-sm group-hover:scale-105 transition-transform">
                                            {{ strtoupper(substr($student->full_name ?: $student->name, 0, 1)) }}
                                        </div>
                                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $student->full_name ?: $student->name }}</p>
                                        <p class="text-[11px] text-slate-500 font-medium">Internal ID: #STU-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                        <div class="w-2 h-2 rounded-full bg-blue-400/20 flex items-center justify-center">
                                            <div class="w-1 h-1 bg-blue-500 rounded-full"></div>
                                        </div>
                                        {{ $student->email }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium ml-4">
                                        {{ $student->phone ?? 'Unlisted' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="p-2.5 bg-white text-blue-600 border border-slate-100 rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 shadow-sm transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Remove this student? Their enrollments and grades will be deleted.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-white text-rose-500 border border-slate-100 rounded-xl hover:bg-rose-500 hover:text-white hover:border-rose-500 shadow-sm transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M17 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-800">No Students Registered</h3>
                                    <p class="text-slate-500 text-sm max-w-xs mt-2">The student database is currently empty.</p>
                                    <a href="{{ route('admin.students.create') }}" class="mt-6 bg-blue-600 text-white px-8 py-3.5 rounded-2xl text-sm font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Enroll First Student</a>
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
