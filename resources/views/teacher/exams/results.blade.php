@extends('layouts.teacher-app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-200 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Exam Results Overview</h1>
            </div>
            <p class="text-slate-500 font-medium ml-1">Monitor all student performances for the Final Assessment session.</p>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-8 bg-white/80 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm flex flex-col items-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Total Submissions</p>
            <p class="text-5xl font-black text-slate-900">{{ $attempts->count() }}</p>
        </div>
        <div class="p-8 bg-white/80 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm flex flex-col items-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Average Score</p>
            @php $avg = $attempts->avg('score') ?? 0; @endphp
            <p class="text-5xl font-black text-indigo-600">{{ round($avg) }}%</p>
        </div>
        <div class="p-8 bg-white/80 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm flex flex-col items-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Passing Rate</p>
            @php $passed = $attempts->where('score', '>=', 50)->count(); $rate = $attempts->count() > 0 ? ($passed / $attempts->count()) * 100 : 0; @endphp
            <p class="text-5xl font-black text-emerald-500">{{ round($rate) }}%</p>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Student Profile</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Score</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Correct Answers</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Completed At</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($attempts as $attempt)
                        @php $user = $attempt->student; @endphp
                        <tr class="group hover:bg-indigo-50/30 transition-all">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black text-sm group-hover:scale-105 transition-all duration-300">
                                        {{ strtoupper(mb_substr($user->first_name ?? '?', 0, 1) . mb_substr($user->last_name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-xl font-black {{ $attempt->score >= 80 ? 'text-emerald-500' : ($attempt->score >= 50 ? 'text-indigo-500' : 'text-rose-500') }}">
                                    {{ round($attempt->score) }}%
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php $correct = $attempt->answers->where('is_correct', true)->count(); @endphp
                                <span class="text-sm font-black text-slate-700">{{ $correct }}</span>
                                <span class="text-xs text-slate-400 font-bold">/ {{ $attempt->total_questions }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <p class="text-sm font-bold text-slate-900">{{ $attempt->completed_at ? $attempt->completed_at->format('M j, Y') : 'N/A' }}</p>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">{{ $attempt->completed_at ? $attempt->completed_at->format('H:i') : '' }}</p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="inline-flex px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $attempt->score >= 50 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    {{ $attempt->score >= 50 ? 'Passed' : 'Failed' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-6 border border-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800 tracking-tight">No Submissions Yet</h3>
                                    <p class="text-slate-500 text-sm max-w-xs mt-2 font-medium">Results will appear here once students complete their assessments.</p>
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
