<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduStream - Student Dashboard</title>
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
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 flex flex-col p-6 shrink-0 shadow-xl relative z-20">
            <div class="flex items-center mb-10 px-2">
                <div class="bg-indigo-500 p-2.5 rounded-xl mr-3 shadow-lg shadow-indigo-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">EduStream</span>
            </div>

            <nav class="flex-1 space-y-1.5">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 px-4">Student Menu</p>
                
                <a href="{{ route('student.dashboard') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('student.dashboard') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-700/50 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('student.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('student.timetable') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('student.timetable') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-700/50 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('student.timetable') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    My Schedule
                </a>

                <a href="{{ route('student.attendance') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('student.attendance') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-700/50 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('student.attendance') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    My Attendance
                </a>

                <a href="{{ route('student.exams.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('student.exams.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-700/50 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('student.exams.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    Final Exams
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-700">
                <div class="p-4 bg-slate-700/40 rounded-2xl border border-slate-600/50 shadow-inner">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 border-2 border-slate-600 flex items-center justify-center text-white font-bold mr-3 shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-black text-indigo-300 uppercase tracking-wider">Student Account</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full py-2.5 bg-slate-600/50 hover:bg-rose-500/10 border border-slate-500/50 rounded-xl text-xs font-bold text-slate-300 hover:text-rose-400 hover:border-rose-500/30 transition-all group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-2 group-hover:rotate-12 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-100">
            <!-- Header -->
            <header class="h-20 bg-white/90 backdrop-blur-sm border-b border-slate-200/80 flex items-center justify-between px-8 shrink-0 z-10 shadow-sm">
                <div class="relative w-full max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" placeholder="Search grades, schedule, lessons..." class="w-full pl-11 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-indigo-200 outline-none transition-all">
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center bg-indigo-50 text-indigo-700 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-100 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Settings
                    </a>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto main-content-bg p-8 custom-scrollbar">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
