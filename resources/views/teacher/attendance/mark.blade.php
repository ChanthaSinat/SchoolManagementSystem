@extends('layouts.teacher-app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-32">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('teacher.attendance.index') }}" class="p-2 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Mark Attendance</h1>
            </div>
            <p class="text-slate-500 font-medium ml-12">{{ $schoolClass->name }} • Section {{ $section->name }} • {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($existing->isNotEmpty())
                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-black rounded-full border border-amber-100 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Editing Existing
                </span>
            @endif
            <button type="button" id="mark-all-present" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl text-sm font-black hover:bg-emerald-700 shadow-lg shadow-emerald-200 active:scale-95 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                Mark All Present
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white/60 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-xl shadow-slate-200/50 overflow-hidden">
        <form action="{{ route('teacher.attendance.store') }}" method="POST" id="attendance-form">
            @csrf
            <input type="hidden" name="class_id" value="{{ $classId }}">
            <input type="hidden" name="section_id" value="{{ $sectionId }}">
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Student Profile</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status Toggle</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Optional Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php
                            $colors = ['bg-indigo-500','bg-emerald-500','bg-amber-500','bg-rose-500','bg-sky-500','bg-violet-500','bg-teal-500','bg-fuchsia-500'];
                        @endphp
                        @foreach ($students as $enrollment)
                            @php
                                $sid = $enrollment->student_id;
                                $user = $enrollment->user;
                                $existingRecord = $existing->get($sid);
                                $status = $existingRecord ? $existingRecord->status : 'present';
                                $note = $existingRecord ? ($existingRecord->note ?? '') : '';

                                // Compute display name and initials with safe fallbacks
                                $displayName = $user
                                    ? (trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->full_name ?? $user->name ?? 'Student'))
                                    : 'Student';

                                $nameForInitials = $user ? ($user->full_name ?? $user->name ?? $displayName) : $displayName;
                                $nameForInitials = trim($nameForInitials);
                                $parts = preg_split('/\s+/', $nameForInitials);
                                $firstInitial = isset($parts[0][0]) ? mb_substr($parts[0], 0, 1) : 'S';
                                $secondInitial = isset($parts[1][0]) ? mb_substr($parts[1], 0, 1) : '';
                                $initials = mb_strtoupper($firstInitial . $secondInitial);

                                $colorIndex = ord(mb_strtoupper(mb_substr($initials, 0, 1))) % count($colors);
                                $bgColor = $colors[$colorIndex];
                            @endphp
                            <tr class="attendance-row group hover:bg-slate-50/50 transition-colors" data-student-id="{{ $sid }}">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl {{ $bgColor }} overflow-hidden flex items-center justify-center text-white font-black text-sm shadow-inner group-hover:scale-105 transition-transform duration-300">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 leading-tight">{{ $displayName }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-2 status-buttons">
                                        <button type="button" class="status-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all border-2 present-btn {{ $status === 'present' ? 'bg-emerald-50 text-emerald-600 border-emerald-500/20' : 'bg-white text-slate-400 border-slate-100 hover:border-emerald-200' }}" data-status="present">
                                            Present
                                        </button>
                                        <button type="button" class="status-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all border-2 absent-btn {{ $status === 'absent' ? 'bg-rose-50 text-rose-600 border-rose-500/20' : 'bg-white text-slate-400 border-slate-100 hover:border-rose-200' }}" data-status="absent">
                                            Absent
                                        </button>
                                        <button type="button" class="status-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all border-2 late-btn {{ $status === 'late' ? 'bg-amber-50 text-amber-600 border-amber-500/20' : 'bg-white text-slate-400 border-slate-100 hover:border-amber-200' }}" data-status="late">
                                            Late
                                        </button>
                                    </div>
                                    <input type="hidden" name="attendance[{{ $sid }}][status]" value="{{ $status }}" class="status-input">
                                </td>
                                <td class="px-8 py-5">
                                    <div class="relative max-w-xs">
                                        <input type="text" name="attendance[{{ $sid }}][note]" value="{{ old("attendance.{$sid}.note", $note) }}" placeholder="Optional note..." 
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/5 outline-none transition-all">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Floating Summary Bar -->
    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2 p-2 bg-slate-900/90 backdrop-blur-xl border border-white/10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] min-w-[320px] transition-all duration-500 group">
        <div id="summary-bar" class="flex-1 px-6 flex items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Present</span>
                    <span id="count-present" class="text-lg font-black text-emerald-400 leading-none">0</span>
                </div>
                <div class="flex flex-col border-l border-white/10 pl-6">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Absent</span>
                    <span id="count-absent" class="text-lg font-black text-rose-400 leading-none">0</span>
                </div>
                <div class="flex flex-col border-l border-white/10 pl-6">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Late</span>
                    <span id="count-late" class="text-lg font-black text-amber-400 leading-none">0</span>
                </div>
            </div>
        </div>
        <button onclick="document.getElementById('attendance-form').submit()" class="px-6 py-4 bg-indigo-500 hover:bg-indigo-400 text-white rounded-2xl text-sm font-black transition-all active:scale-95 flex items-center gap-3">
            Submit Records
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateSummary() {
            var present = 0, absent = 0, late = 0;
            document.querySelectorAll('.status-input').forEach(function(input) {
                var v = (input.value || '').trim();
                if (v === 'present') present++;
                else if (v === 'absent') absent++;
                else if (v === 'late') late++;
            });
            document.getElementById('count-present').textContent = present;
            document.getElementById('count-absent').textContent = absent;
            document.getElementById('count-late').textContent = late;
        }

        document.querySelectorAll('.attendance-row').forEach(function(row) {
            var statusInput = row.querySelector('.status-input');
            var btns = row.querySelectorAll('.status-btn');
            
            btns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var status = btn.getAttribute('data-status');
                    statusInput.value = status;
                    
                    btns.forEach(function(b) {
                        var s = b.getAttribute('data-status');
                        // Remove all active styles
                        b.classList.remove('bg-emerald-50', 'text-emerald-600', 'border-emerald-500/20', 'bg-rose-50', 'text-rose-600', 'border-rose-500/20', 'bg-amber-50', 'text-amber-600', 'border-amber-500/20');
                        b.classList.add('bg-white', 'text-slate-400', 'border-slate-100');
                        
                        if (s === status) {
                            b.classList.remove('bg-white', 'text-slate-400', 'border-slate-100');
                            if (s === 'present') b.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-500/20');
                            else if (s === 'absent') b.classList.add('bg-rose-50', 'text-rose-600', 'border-rose-500/20');
                            else if (s === 'late') b.classList.add('bg-amber-50', 'text-amber-600', 'border-amber-500/20');
                        }
                    });
                    updateSummary();
                });
            });
        });

        document.getElementById('mark-all-present').addEventListener('click', function() {
            document.querySelectorAll('.attendance-row').forEach(function(row) {
                var statusInput = row.querySelector('.status-input');
                var presentBtn = row.querySelector('.present-btn');
                if (statusInput && presentBtn) {
                    statusInput.value = 'present';
                    row.querySelectorAll('.status-btn').forEach(function(b) {
                        b.classList.remove('bg-emerald-50', 'text-emerald-600', 'border-emerald-500/20', 'bg-rose-50', 'text-rose-600', 'border-rose-500/20', 'bg-amber-50', 'text-amber-600', 'border-amber-500/20');
                        b.classList.add('bg-white', 'text-slate-400', 'border-slate-100');
                    });
                    presentBtn.classList.remove('bg-white', 'text-slate-400', 'border-slate-100');
                    presentBtn.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-500/20');
                }
            });
            updateSummary();
        });

        updateSummary();
    });
</script>
@endpush
@endsection
