@extends('layouts.student-app')

@section('content')
<div class="max-w-4xl mx-auto pb-32">
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('student.exams.index') }}" onclick="return confirm('Wait! Your progress in this session will be lost if you leave. Are you sure?')" class="w-12 h-12 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-100 hover:bg-rose-50 transition-all active:scale-90 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Final Assessment</h1>
                <p class="text-slate-500 font-medium">Academic Year 2026 • Term II</p>
            </div>
        </div>
        <div class="flex flex-col items-end">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Session Protocol</span>
            <div class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                LIVE SESSION
            </div>
        </div>
    </div>

    <form 
        action="{{ route('student.exams.store', $attempt) }}" 
        method="POST" 
        id="exam-form"
        x-data="{ 
            showConfirmModal: false,
            totalQuestions: {{ $attempt->answers->count() }},
            answeredCount: 0,
            updateAnsweredCount() {
                this.answeredCount = document.querySelectorAll('input[type=radio]:checked').length;
            },
            get progressPercentage() {
                return (this.answeredCount / this.totalQuestions) * 100;
            }
        }"
        x-init="updateAnsweredCount()"
        @answer-changed.stop="updateAnsweredCount()"
    >
        @csrf
        <div class="space-y-8">
            @php $currentSubject = null; @endphp
            @foreach ($attempt->answers as $index => $attemptAnswer)
                @php $question = $attemptAnswer->question; @endphp
                
                @if ($currentSubject !== $question->subject->name)
                    @php $currentSubject = $question->subject->name; @endphp
                    <div class="pt-4 flex items-center gap-4">
                        <div class="h-px flex-1 bg-slate-200"></div>
                        <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] bg-slate-100 px-4">{{ $currentSubject }}</h2>
                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>
                @endif

                <div class="bg-white/60 backdrop-blur-xl rounded-[2.5rem] border border-white shadow-xl shadow-slate-200/40 overflow-hidden animate-in fade-in slide-in-from-bottom-8 duration-700 transition-all hover:bg-white" style="animation-delay: {{ $index * 50 }}ms">
                    <div class="p-8 sm:p-10">
                        <div class="flex items-start gap-6">
                            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-900 font-black text-lg shrink-0 border border-slate-200 shadow-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 space-y-6">
                                <h3 class="text-xl font-bold text-slate-800 leading-snug">{{ $question->question_text }}</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($question->options as $optIndex => $option)
                                        <label class="px-6 py-5 bg-white border-2 border-slate-50 rounded-2xl text-slate-600 font-bold hover:bg-indigo-50 hover:border-indigo-100 transition-all flex items-center gap-4 cursor-pointer group">
                                            <input
                                                type="radio"
                                                name="answers[{{ $attemptAnswer->id }}]"
                                                value="{{ $optIndex }}"
                                                class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                                required
                                                @change="$dispatch('answer-changed')"
                                            >
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 text-xs flex items-center justify-center font-black group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                                {{ chr(65 + $optIndex) }}
                                            </div>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Submit Bar -->
        <div class="fixed bottom-0 left-64 right-0 z-30 px-8 py-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] rounded-[2rem] p-4 flex items-center justify-between gap-6">
                    <!-- Progress Section -->
                    <div class="flex-1 px-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Assessment Progress</span>
                            <span class="text-xs font-bold text-indigo-600" x-text="answeredCount + '/' + totalQuestions + ' Answered'"></span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200/50">
                            <div 
                                class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500 ease-out rounded-full shadow-[0_0_10px_rgba(99,102,241,0.3)]"
                                :style="`width: ${progressPercentage}%`"
                            ></div>
                        </div>
                    </div>

                    <!-- Action Section -->
                    <div class="flex items-center gap-3">
                        <button 
                            type="button" 
                            @click="showConfirmModal = true"
                            class="group relative px-8 py-4 bg-slate-900 overflow-hidden rounded-2xl font-black text-xs text-white uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 shadow-xl shadow-slate-900/20"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center gap-2">
                                Finish Assessment
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div 
            x-show="showConfirmModal" 
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6"
            x-cloak
        >
            <!-- Backdrop -->
            <div 
                x-show="showConfirmModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="showConfirmModal = false"
                class="absolute inset-0 bg-slate-950/40 backdrop-blur-md"
            ></div>

            <!-- Modal Content -->
            <div 
                x-show="showConfirmModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative bg-white rounded-[2.5rem] shadow-2xl border border-white max-w-lg w-full overflow-hidden"
            >
                <div class="p-8 sm:p-10 text-center">
                    <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-3">Ready to submit?</h3>
                    <p class="text-slate-500 font-medium mb-8 leading-relaxed">
                        You have answered <span class="text-indigo-600 font-bold" x-text="answeredCount"></span> out of <span class="font-bold text-slate-700" x-text="totalQuestions"></span> questions. Once submitted, you cannot change your answers.
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <button 
                            type="button" 
                            @click="showConfirmModal = false"
                            class="px-6 py-4 rounded-2xl border-2 border-slate-100 text-slate-400 font-black text-xs uppercase tracking-widest hover:bg-slate-50 hover:text-slate-600 transition-all active:scale-95"
                        >
                            Not yet
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-4 bg-indigo-600 rounded-2xl text-white font-black text-xs uppercase tracking-widest hover:bg-indigo-500 shadow-lg shadow-indigo-200 transition-all active:scale-95"
                        >
                            Yes, submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
