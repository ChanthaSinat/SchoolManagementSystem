@extends('layouts.student-app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-200 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Academic Excellence</h1>
            </div>
            <p class="text-slate-500 font-medium ml-1">Test your knowledge and achieve your academic goals.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
            <div class="bg-rose-500 text-white p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </div>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Exam Card -->
        <div class="lg:col-span-2">
            <div class="bg-white/60 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-xl shadow-slate-200/50 overflow-hidden group">
                <div class="p-10">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg uppercase tracking-wider border border-indigo-200">Available Now</span>
                            <h2 class="text-3xl font-black text-slate-900 mt-4 leading-tight">Final Assessment Session</h2>
                        </div>
                        <div class="w-20 h-20 bg-indigo-50 rounded-[2rem] flex items-center justify-center text-indigo-600 border border-indigo-100 group-hover:scale-110 transition-transform duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                    </div>
                    
                    <div class="space-y-6 mb-10">
                        <div class="flex gap-4 p-4 rounded-2xl bg-white/40 border border-white/60">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Verified Curriculum</p>
                                <p class="text-xs text-slate-500 font-medium">Questions covering Mathematics and Physics I subjects.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-4 rounded-2xl bg-white/40 border border-white/60">
                            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Time Limited</p>
                                <p class="text-xs text-slate-500 font-medium">Comprehensive evaluation of your core understanding.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('student.exams.start') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] text-lg font-black hover:bg-indigo-700 shadow-2xl shadow-indigo-600/30 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group/btn">
                            Begin Final Exam
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover/btn:translate-x-2 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar / History -->
        <div class="space-y-6">
            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-sm p-8">
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-6">Exam History</h3>
                
                <div class="space-y-4">
                    @forelse ($attempts as $attempt)
                        <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $attempt->completed_at ? $attempt->completed_at->format('M j, Y') : 'In Progress' }}</span>
                                @if($attempt->status === 'completed')
                                    <span class="text-sm font-black {{ $attempt->score >= 50 ? 'text-emerald-500' : 'text-rose-500' }}">{{ round($attempt->score) }}%</span>
                                @else
                                    <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-slate-700">Final Assessment</p>
                                @if($attempt->status === 'completed')
                                    <a href="{{ route('student.exams.results', $attempt) }}" class="text-indigo-600 text-xs font-black hover:underline underline-offset-4">View Detail</a>
                                @else
                                    <a href="{{ route('student.exams.take', $attempt) }}" class="text-amber-600 text-xs font-black hover:underline underline-offset-4">Continue</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-400">No previous attempts</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Achievement Card -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-200">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6 border border-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.45.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                </div>
                <h3 class="text-xl font-black mb-2 leading-tight">Your Academic Progress</h3>
                <p class="text-indigo-100 text-sm font-medium mb-6">Complete the final exam to unlock your updated academic profile and see where you stand.</p>
                <div class="h-2 w-full bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: 45%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
