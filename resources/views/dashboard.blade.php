<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden bg-white/70 backdrop-blur-xl shadow-lg sm:rounded-3xl border border-white p-10 group hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full blur-3xl opacity-60 group-hover:scale-110 transition-transform duration-1000"></div>
                
                <div class="relative z-10 text-center py-8">
                    <div class="mx-auto w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-6 shadow-sm border border-green-200">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight mb-2">{{ __("You're logged in!") }}</h3>
                    <p class="text-gray-500 font-medium">{{ __("Welcome to your dashboard.") }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
