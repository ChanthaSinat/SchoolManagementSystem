<x-guest-layout title="Reset Password">
    <a href="{{ route('login') }}" class="educore-back-btn">← Back to Sign In</a>

    <div class="educore-form-head">
        <h2>Set New Password</h2>
        <p>Enter your new password below.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="educore-field">
            <label class="educore-label" for="email">Email Address</label>
            <input id="email" type="email" name="email" class="educore-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="you@school.edu" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="educore-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="educore-field educore-pw-wrap">
            <label class="educore-label" for="password">New Password</label>
            <input id="password" type="password" name="password" class="educore-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="Min. 8 characters" required autocomplete="new-password" />
            <span class="educore-field-icon" onclick="var i=document.getElementById('password');i.type=i.type==='password'?'text':'password';this.textContent=i.type==='password'?'👁':'🙈'">👁</span>
            @error('password')
                <p class="educore-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="educore-field educore-pw-wrap">
            <label class="educore-label" for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="educore-input" placeholder="Repeat your new password" required autocomplete="new-password" />
        </div>

        <button type="submit" class="educore-btn educore-btn-primary">Reset Password</button>
    </form>
</x-guest-layout>
