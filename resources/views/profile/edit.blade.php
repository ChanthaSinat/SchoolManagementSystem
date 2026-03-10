@php
    $layout = 'layouts.app';
    if (auth()->user()->isStudent()) $layout = 'layouts.student-app';
    elseif (auth()->user()->isTeacher()) $layout = 'layouts.teacher-app';
    elseif (auth()->user()->isAdmin()) $layout = 'layouts.admin-app';
@endphp

@extends($layout)

@section('content')
<div class="max-w-5xl mx-auto space-y-12 animate-in fade-in duration-700">
    <!-- Profile Page Header -->
    @include('profile.partials.profile-header')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sidebar Navigation (Optional/Secondary) -->
        <div class="lg:col-span-1 space-y-4">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest px-2">Account Control</h3>
            <div class="bg-white/40 backdrop-blur-xl rounded-[2rem] border border-white/60 p-4 space-y-1 shadow-sm">
                <button class="w-full flex items-center gap-3 px-4 py-3 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    General Info
                </button>
                <button class="w-full flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-white/60 rounded-xl font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Password & Security
                </button>
                <button class="w-full flex items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-50 rounded-xl font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 6 3 18h12l3-18H3z"/><path d="M19 6V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v1h14z"/></svg>
                    Danger Zone
                </button>
            </div>
        </div>

        <!-- Main Form Area -->
        <div class="lg:col-span-2 space-y-12 pb-20">
            @include('profile.partials.update-profile-information-form')
            
            <div class="h-px bg-slate-200 w-full opacity-50"></div>
            
            @include('profile.partials.update-password-form')
            
            <div class="h-px bg-slate-200 w-full opacity-50"></div>
            
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
