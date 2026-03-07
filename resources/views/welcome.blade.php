<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduCore — School Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50/50 min-h-screen relative overflow-x-hidden selection:bg-indigo-500 selection:text-white flex items-center justify-center">

    <!-- Animated Background Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-200/50 blur-[100px] mix-blend-multiply animate-blob"></div>
        <div class="absolute top-[20%] -right-[10%] w-[45%] h-[45%] rounded-full bg-purple-200/50 blur-[100px] mix-blend-multiply animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[55%] h-[55%] rounded-full bg-blue-200/50 blur-[100px] mix-blend-multiply animate-blob animation-delay-4000"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[40%] h-[40%] rounded-full bg-amber-100/50 blur-[100px] mix-blend-multiply animate-blob animation-delay-6000"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-12 text-center flex flex-col items-center">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-4 mb-10 group">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl shadow-indigo-200 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                <span class="text-white font-black text-3xl font-serif leading-none">E</span>
            </div>
            <div class="text-left">
                <span class="block text-4xl font-black text-gray-900 tracking-tight leading-none group-hover:text-indigo-600 transition-colors">Edu<span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Core</span></span>
                <span class="block text-sm font-bold text-gray-500 tracking-widest uppercase mt-1">Management System</span>
            </div>
        </a>

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 backdrop-blur-md border border-white shadow-sm mb-8 animate-bounce-slight">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span class="text-sm font-bold text-gray-700 tracking-wide">Next-Generation Platform</span>
        </div>

        <!-- Headline -->
        <h1 class="text-6xl md:text-7xl font-black text-gray-900 tracking-tighter mb-6 leading-[1.1]">
            Education Reimagined <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 animate-gradient-x">for Everyone</span>
        </h1>

        <!-- Description -->
        <p class="text-xl md:text-2xl text-gray-500 font-medium max-w-3xl mx-auto mb-12 leading-relaxed">
            A unified platform connecting teachers, students, and parents — with smart attendance, live gradebooks, and personalized beautiful dashboards.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full px-4 sm:px-0">
            @auth
                <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl hover:shadow-[0_10px_30px_rgba(79,70,229,0.4)] hover:-translate-y-1 w-full sm:w-auto overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-500 skew-x-12"></div>
                    <span>Go to Dashboard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl hover:shadow-[0_10px_30px_rgba(79,70,229,0.4)] hover:-translate-y-1 w-full sm:w-auto overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-500 skew-x-12"></div>
                    <span>Sign In to Account</span>
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-gray-700 transition-all duration-300 bg-white/80 backdrop-blur-md rounded-2xl border border-white hover:border-indigo-100 hover:bg-indigo-50/50 hover:text-indigo-600 hover:shadow-lg hover:-translate-y-1 w-full sm:w-auto">
                        Create Account
                    </a>
                @endif
            @endauth
        </div>

        <!-- Decorative UI Element (Glass Card showing a preview vibe) -->
        <div class="mt-20 w-full max-w-4xl mx-auto bg-white/60 backdrop-blur-xl border border-white shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] rounded-[2rem] p-4 hidden md:block transform hover:-translate-y-2 transition-transform duration-500 text-left">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100/50 mb-4 bg-white/40 rounded-t-xl">
                <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
            </div>
            
            <div class="grid grid-cols-3 gap-6 p-4 pt-0">
                <div class="h-32 bg-indigo-50 rounded-2xl border border-indigo-100/50 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-200 rounded-full blur-xl opacity-60"></div>
                    <div class="p-4 relative z-10 flex flex-col h-full justify-between">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <div>
                            <div class="h-2 w-12 bg-indigo-200 rounded-full mb-2"></div>
                            <div class="h-4 w-24 bg-indigo-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
                
                <div class="h-32 bg-purple-50 rounded-2xl border border-purple-100/50 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-purple-200 rounded-full blur-xl opacity-60"></div>
                    <div class="p-4 relative z-10 flex flex-col h-full justify-between">
                        <div class="w-8 h-8 rounded-lg bg-purple-500 text-white flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <div class="h-2 w-16 bg-purple-200 rounded-full mb-2"></div>
                            <div class="h-4 w-20 bg-purple-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
                
                <div class="h-32 bg-amber-50 rounded-2xl border border-amber-100/50 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-200 rounded-full blur-xl opacity-60"></div>
                    <div class="p-4 relative z-10 flex flex-col h-full justify-between">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <div class="h-2 w-10 bg-amber-200 rounded-full mb-2"></div>
                            <div class="h-4 w-28 bg-amber-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>
