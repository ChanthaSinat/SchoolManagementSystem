@extends('layouts.student-app')

@section('content')
<div class="max-w-5xl mx-auto space-y-12 animate-in fade-in zoom-in duration-1000">
    <!-- Header/Success State -->
    <div class="text-center p-12 bg-white/60 backdrop-blur-xl rounded-[3.5rem] border border-white shadow-2xl shadow-slate-200/50">
        <div class="w-24 h-24 mx-auto mb-8 relative">
            <div class="absolute inset-x-0 bottom-0 top-2 bg-emerald-400/20 blur-2xl rounded-full translate-y-4"></div>
            <div class="w-20 h-20 mx-auto bg-emerald-600 rounded-[1.75rem] shadow-[0_20px_40px_-10px_rgba(5,150,105,0.4)] flex items-center justify-center text-white relative z-10 transition-transform hover:scale-110 duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight mb-4">Exam Completed!</h1>
        <p class="text-slate-500 text-lg font-medium max-w-2xl mx-auto leading-relaxed">Great job! You've successfully finished your final assessment session. Here is your academic performance breakdown.</p>
        
        <div class="mt-12 flex flex-wrap justify-center gap-0 overflow-hidden rounded-[2.5rem] border border-slate-100 shadow-sm bg-white">
            <!-- Final Score -->
            <div class="px-10 py-10 flex flex-col items-center border-r border-slate-50 min-w-[200px]">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Your Final Score</p>
                <p class="text-7xl font-black text-slate-900 leading-none">
                    {{ round($attempt->score) }}<span class="text-indigo-600 text-4xl">%</span>
                </p>
            </div>

            <!-- Correct Answers -->
            <div class="px-10 py-10 flex flex-col items-center border-r border-slate-50 min-w-[200px]">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Correct Answers</p>
                @php $correctCount = $attempt->answers->where('is_correct', true)->count(); @endphp
                <p class="text-7xl font-black text-slate-900 leading-none">
                    {{ $correctCount }}<span class="text-slate-200 text-4xl">/{{ $attempt->total_questions }}</span>
                </p>
            </div>

            <!-- Academic Standing -->
            <div class="px-10 py-10 flex flex-col items-center min-w-[240px] justify-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Academic Standing</p>
                <div class="px-8 py-4 bg-indigo-50/80 text-indigo-700 rounded-2xl text-xl font-black shadow-sm border border-indigo-100/50">
                    {{ $attempt->score >= 80 ? 'Excellent' : ($attempt->score >= 50 ? 'Satisfactory' : 'Developing') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Question Breakdown List -->
    <div class="space-y-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Detailed Performance Breakdown</h2>
        
        @foreach ($attempt->answers as $index => $answer)
            @php $question = $answer->question; @endphp
            <div class="bg-white/40 backdrop-blur-md rounded-[2.5rem] border border-white shadow-sm overflow-hidden p-8 sm:p-10 transition-all hover:bg-white group">
                <div class="flex items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-black text-lg shrink-0 shadow-lg {{ $answer->is_correct ? 'bg-emerald-500 shadow-emerald-100' : 'bg-rose-500 shadow-rose-100' }}">
                        {{ $answer->is_correct ? '✓' : '✗' }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-black rounded-lg uppercase tracking-wider border border-slate-200">{{ $question->subject->name }}</span>
                            <span class="text-xs font-bold text-slate-400">Question {{ $index + 1 }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 leading-snug mb-6">{{ $question->question_text }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($question->options as $optIndex => $option)
                                @php
                                    $isSelected = $answer->selected_option_index !== null && (int)$answer->selected_option_index === $optIndex;
                                    $isCorrect = (int)$question->correct_option_index === $optIndex;
                                    
                                    $styles = 'bg-white border-slate-100 text-slate-400';
                                    if ($isSelected && $isCorrect) $styles = 'bg-emerald-50 border-emerald-500 text-emerald-700';
                                    elseif ($isSelected && !$isCorrect) $styles = 'bg-rose-50 border-rose-500 text-rose-700';
                                    elseif ($isCorrect) $styles = 'bg-emerald-50 border-emerald-500/30 text-emerald-600';
                                @endphp
                                <div class="px-6 py-4 border-2 rounded-2xl text-sm font-bold flex items-center gap-4 {{ $styles }}">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs border border-current opacity-60">
                                        {{ chr(65 + $optIndex) }}
                                    </div>
                                    {{ $option }}
                                    @if($isCorrect) <span class="ml-auto text-[10px] font-black uppercase tracking-widest">Correct Answer</span> @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Final Action -->
    <div class="flex flex-col items-center gap-6 pb-20">
        <div class="h-px w-24 bg-slate-200"></div>
        <a href="{{ route('student.exams.index') }}" class="group relative px-12 py-6 bg-slate-900 overflow-hidden rounded-[2rem] font-black text-sm text-white uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 shadow-2xl shadow-slate-900/20 flex items-center gap-4">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Return to Dashboard
            </span>
        </a>
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Session ID: {{ strtoupper(substr($attempt->id, 0, 8)) }} • Protocol Secure</p>
    </div>
</div>
@endsection
