@extends('layouts.admin-app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manage Class</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">{{ $class->name }} @if($class->section) • Section {{ $class->section }} @endif</p>
        </div>
        <a href="{{ route('admin.classes.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Back</a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Class details -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-6">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Class Details</h2>
                <form method="POST" action="{{ route('admin.classes.update', $class) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Class Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $class->name) }}" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white/70 text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 outline-none">
                        @error('name') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="year_level" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Year Level (optional)</label>
                        <input id="year_level" name="year_level" type="text" value="{{ old('year_level', $class->year_level ?? '12') }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white/70 text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 outline-none">
                        @error('year_level') <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 border-t border-slate-100 mt-4">
                        <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
                            Save Class Details
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Schedule -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Weekly Schedule</h2>
                        <p class="text-xs text-slate-500 mt-1">Assign one subject and one teacher per weekday.</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.classes.schedule.generate', $class) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-lg border border-indigo-100 hover:bg-indigo-100 transition">
                                Generate Random
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.classes.schedule.reset', $class) }}" onsubmit="return confirm('Are you sure you want to reset the schedule?')">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 text-[10px] font-black rounded-lg border border-rose-100 hover:bg-rose-100 transition">
                                Reset
                            </button>
                        </form>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.classes.schedule.store', $class) }}">
                    @csrf
                    <div class="overflow-hidden border border-slate-100 rounded-2xl">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Day</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Subject</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Teacher</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Room</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                                    @php
                                        $entry = $schedule->get($day);
                                    @endphp
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="px-6 py-4 text-sm font-black text-slate-700 capitalize">{{ $day }}</td>
                                        <td class="px-6 py-4">
                                            <select name="schedule[{{ $day }}][subject_id]" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 outline-none">
                                                <option value="">Select Subject</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}" @selected(old("schedule.{$day}.subject_id", $entry?->subject_id) == $subject->id)>
                                                        {{ $subject->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="schedule[{{ $day }}][teacher_id]" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 outline-none">
                                                <option value="">Select Teacher</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" @selected(old("schedule.{$day}.teacher_id", $entry?->teacher_id) == $teacher->id)>
                                                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="schedule[{{ $day }}][room]" value="{{ old("schedule.{$day}.room", $entry?->room) }}" placeholder="e.g. 101"
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 outline-none">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1">
                                                <input type="time" name="schedule[{{ $day }}][start_time]" value="{{ old("schedule.{$day}.start_time", $entry?->start_time?->format('H:i')) }}"
                                                    class="px-2 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-indigo-500/40 outline-none">
                                                <span class="text-slate-400">-</span>
                                                <input type="time" name="schedule[{{ $day }}][end_time]" value="{{ old("schedule.{$day}.end_time", $entry?->end_time?->format('H:i')) }}"
                                                    class="px-2 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-indigo-500/40 outline-none">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white text-sm font-black rounded-xl shadow-sm hover:bg-slate-800 transition">
                            Save Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enrolled students (simple management) -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-slate-100 shadow-sm p-6">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Enrolled Students</h2>
                <p class="text-xs text-slate-500 mb-3">Check which students belong to this class. This does not affect the schedule; it only controls enrollment.</p>

                <form method="POST" action="{{ route('admin.classes.assignments', $class) }}" class="space-y-4">
                    @csrf

                    <div class="max-h-72 overflow-y-auto custom-scrollbar border border-slate-100 rounded-2xl p-3 bg-slate-50/60">
                        @php
                            $currentStudentIds = $class->students()->pluck('users.id')->all();
                            $allStudents = \App\Models\User::where(function ($q) {
                                    $q->where('role', 'student')->orWhereHas('roles', fn ($r) => $r->where('name', 'student'));
                                })
                                ->orderBy('first_name')
                                ->orderBy('last_name')
                                ->get();
                        @endphp
                        @forelse ($allStudents as $student)
                            <label class="flex items-center gap-3 px-2 py-1.5 rounded-xl hover:bg-white cursor-pointer">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @if(in_array($student->id, $currentStudentIds)) checked @endif>
                                <span class="text-sm text-slate-700 font-medium">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                    <span class="text-[11px] text-slate-400 ml-1">{{ $student->email }}</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 px-2 py-1.5">No students found.</p>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-xl shadow-sm hover:bg-indigo-700 transition">
                            Save Enrollment
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

