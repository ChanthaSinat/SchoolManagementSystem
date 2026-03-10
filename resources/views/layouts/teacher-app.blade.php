<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduStream - Teacher Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        .main-content-bg { background: linear-gradient(160deg, #f8fafc 0%, #f1f5f9 40%, #e2e8f0 100%); }
    </style>
</head>
<body class="text-slate-900 overflow-hidden">
    <div class="h-screen flex overflow-hidden">
        <!-- Sidebar - dark for contrast and modern look -->
        <aside class="w-64 bg-slate-800 flex flex-col p-6 shrink-0 shadow-xl">
            <div class="flex items-center mb-10 px-2">
                <div class="bg-indigo-500 p-2.5 rounded-xl mr-3 shadow-lg shadow-indigo-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">EduStream</span>
            </div>

            <nav class="flex-1 space-y-1.5">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-700/80 hover:text-white' }} font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    Overview
                </a>
                <a href="{{ route('teacher.students.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('teacher.students.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-700/80 hover:text-white' }} font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    My Students
                </a>
                <a href="{{ route('teacher.curriculum.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('teacher.curriculum.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-700/80 hover:text-white' }} font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
                    Curriculum
                </a>
                <a href="{{ route('teacher.schedule.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('teacher.schedule.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-700/80 hover:text-white' }} font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    Schedule
                </a>
                <a href="{{ route('teacher.attendance.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('teacher.attendance.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-700/80 hover:text-white' }} font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                    Mark Attendance
                </a>
                <a href="{{ route('teacher.exams.results') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('teacher.exams.results') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-700/80 hover:text-white' }} font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Exam Results
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-600/80">
                <div class="p-4 bg-slate-700/50 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500/80 border-2 border-slate-600 flex items-center justify-center text-white font-bold mr-3">
                            {{ strtoupper(mb_substr(auth()->user()->first_name ?? 'T', 0, 1) . mb_substr(auth()->user()->last_name ?? '', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate text-white">{{ auth()->user()->full_name ?? auth()->user()->name }}</p>
                            <p class="text-[10px] font-bold text-indigo-300 uppercase">Teacher</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full py-2.5 bg-slate-600/80 hover:bg-red-500/20 border border-slate-500/50 rounded-xl text-xs font-bold text-slate-200 hover:text-red-300 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
            <!-- Header (simplified for teachers) -->
            <header class="h-16 bg-white/90 backdrop-blur-sm border-b border-slate-200/80 flex items-center px-6 md:px-8 shrink-0 z-10 shadow-sm">
                <h1 class="text-sm md:text-base font-bold text-slate-700">
                    Teacher Workspace
                </h1>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto main-content-bg p-6 md:p-8 lg:p-10 custom-scrollbar">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
