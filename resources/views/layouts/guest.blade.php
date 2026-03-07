<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50/50 min-h-screen relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        
        <!-- Animated Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-200/40 blur-[100px] mix-blend-multiply animate-blob"></div>
            <div class="absolute top-[20%] -right-[10%] w-[45%] h-[45%] rounded-full bg-purple-200/40 blur-[100px] mix-blend-multiply animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[55%] h-[55%] rounded-full bg-blue-200/40 blur-[100px] mix-blend-multiply animate-blob animation-delay-4000"></div>
            <div class="absolute bottom-[10%] -right-[5%] w-[40%] h-[40%] rounded-full bg-amber-100/40 blur-[100px] mix-blend-multiply animate-blob animation-delay-6000"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 pb-12 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg flex items-center justify-center -rotate-3 hover:rotate-0 transition-transform duration-300">
                        <span class="text-white font-black text-2xl font-serif">E</span>
                    </div>
                    <span class="text-3xl font-black text-gray-900 tracking-tight">Edu<span class="text-indigo-600">Core</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-white overflow-hidden sm:rounded-3xl relative">
                <!-- Inner decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-100/50 to-purple-100/50 rounded-bl-full -z-10"></div>
                
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500 font-medium">
                &copy; {{ date('Y') }} EduCore Systems. All rights reserved.
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
