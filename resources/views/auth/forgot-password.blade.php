<x-guest-layout title="Recover Access">
    <div class="space-y-6">
        <a href="{{ route('login') }}"
           class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <span class="mr-1">←</span>
            <span>Back to Sign In</span>
        </a>

        <div class="space-y-2">
            <h2 class="text-2xl font-semibold text-gray-900">Recover Access</h2>
            <p class="text-sm text-gray-600">
                Enter your email and we'll send you a password reset link.
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700" for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="block w-full rounded-xl border border-gray-200 bg-white/80 px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 {{ $errors->has('email') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/40' : '' }}"
                    placeholder="you@school.edu"
                    value="{{ old('email') }}"
                    required
                    autofocus
                />
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                Email Password Reset Link
            </button>

            <p class="text-center text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700">
                    Sign in
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
