<x-guest-layout title="Recover Access">
    <a href="{{ route('login') }}" class="educore-back-btn">← Back to Sign In</a>

    <div class="educore-form-head">
        <h2>Recover Access</h2>
        <p>Enter your email and we'll send you a password reset link.</p>
    </div>

    @if (session('status'))
        <div class="educore-success-msg">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="educore-error" style="margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="educore-field">
            <label class="educore-label" for="email">Email Address</label>
            <input id="email" type="email" name="email" class="educore-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="you@school.edu" value="{{ old('email') }}" required autofocus />
            @error('email')
                <p class="educore-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="educore-btn educore-btn-primary">Email Password Reset Link</button>

        <div class="educore-link-row">
            Remember your password? <a href="{{ route('login') }}" class="educore-link-btn">Sign in</a>
        </div>
    </form>
</x-guest-layout>
