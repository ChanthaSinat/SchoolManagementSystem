<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 tracking-tight flex items-center gap-2">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden bg-white/70 backdrop-blur-xl shadow-lg sm:rounded-3xl border border-white p-10 group">
                <!-- Background decoration -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full blur-3xl opacity-60 group-hover:scale-110 transition-transform duration-1000"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-left">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl flex items-center justify-center shrink-0 -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight mb-2">{{ __("Welcome to the Admin Dashboard") }}</h3>
                        <p class="text-lg text-gray-500 font-medium leading-relaxed max-w-2xl">{{ __("You have full access to manage the school system. Configure settings, manage users, and monitor school-wide performance metrics.") }}</p>
                        
                        <div class="mt-8 flex flex-wrap gap-4">
                            <button class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">Quick Actions</button>
                            <button class="px-6 py-2.5 bg-white border border-gray-200 hover:border-indigo-200 text-gray-700 hover:text-indigo-600 font-bold rounded-xl shadow-sm hover:shadow hover:-translate-y-0.5 transition-all">System Status</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Placeholders -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-white/60 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg></div>
                        <h4 class="font-bold text-gray-700">Total Users</h4>
                    </div>
                    <p class="text-3xl font-black text-gray-900">Manage</p>
                </div>
                
                <div class="bg-white/60 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg></div>
                        <h4 class="font-bold text-gray-700">Departments</h4>
                    </div>
                    <p class="text-3xl font-black text-gray-900">View</p>
                </div>
                
                <div class="bg-white/60 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="p-3 bg-purple-100 text-purple-600 rounded-xl"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065Z" /></svg></div>
                        <h4 class="font-bold text-gray-700">Settings</h4>
                    </div>
                    <p class="text-3xl font-black text-gray-900">Configure</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
