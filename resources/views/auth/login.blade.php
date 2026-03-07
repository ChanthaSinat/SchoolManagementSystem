<x-guest-layout title="Sign In">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Welcome back 👋</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium pb-2 border-b border-gray-100">Sign in to your EduCore account to continue.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 border border-emerald-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="email">Email Address</label>
            <input id="email" type="email" name="email" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('email') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm" placeholder="you@school.edu" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-bold text-gray-700 truncate" for="password">Password</label>
                <a href="{{ route('password.request') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('password') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm pr-12" placeholder="Enter your password" required autocomplete="current-password" />
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 focus:outline-none" onclick="var i=document.getElementById('password');i.type=i.type==='password'?'text':'password';this.innerHTML=i.type==='password'?'<svg class=\'w-5 h-5\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' /></svg>':'<svg class=\'w-5 h-5\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\' /></svg>'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                <span class="text-sm font-bold text-gray-600">Remember me</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold text-sm shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 transition-all outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Sign In
        </button>

        <div class="relative flex items-center justify-center mt-8 mb-6">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative px-4 bg-white/70 backdrop-blur-sm text-xs font-bold text-gray-400 uppercase tracking-widest">or</div>
        </div>

        <div class="text-center text-sm font-medium text-gray-600">
            Don't have an account? <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Create one</a>
        </div>
    </form>
</x-guest-layout>
