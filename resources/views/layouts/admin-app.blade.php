<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduStream - Admin Dashboard</title>
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
        <aside class="w-64 bg-slate-900 flex flex-col p-6 shrink-0 shadow-2xl relative z-20">
            <div class="flex items-center mb-10 px-2">
                <div class="bg-indigo-600 p-2.5 rounded-xl mr-3 shadow-lg shadow-indigo-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">EduStream</span>
            </div>

            <nav class="flex-1 space-y-1.5">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 px-4">Main Menu</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.teachers.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('admin.teachers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.teachers.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Teachers
                </a>

                <a href="{{ route('admin.students.index') }}" class="flex items-center w-full px-4 py-3.5 rounded-xl {{ request()->routeIs('admin.students.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} font-bold text-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M17 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Students
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-800">
                <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 border-2 border-slate-700 flex items-center justify-center text-white font-bold mr-3 shadow-inner">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-wider">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full py-2.5 bg-slate-700/50 hover:bg-rose-500/10 border border-slate-600/50 rounded-xl text-xs font-bold text-slate-300 hover:text-rose-400 hover:border-rose-500/30 transition-all group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mr-2 group-hover:rotate-12 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-50">
            <!-- Header -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 flex items-center justify-between px-8 shrink-0 z-10">
                <div class="relative w-full max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 shrink-0 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" placeholder="Global search..." class="w-full pl-11 pr-4 py-2.5 bg-slate-100/50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-400 outline-none transition-all duration-300">
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-slate-100 rounded-xl p-1">
                        <button class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        </button>
                        <button class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 active:scale-95 transition-all">
                        Support
                    </button>
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
