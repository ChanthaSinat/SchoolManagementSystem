<x-guest-layout title="Confirm Password">
    <div class="educore-form-head">
        <h2>Confirm password</h2>
        <p>This is a secure area. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="educore-field educore-pw-wrap">
            <label class="educore-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="educore-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="Your password" required autocomplete="current-password" />
            <span class="educore-field-icon" onclick="var i=document.getElementById('password');i.type=i.type==='password'?'text':'password';this.textContent=i.type==='password'?'👁':'🙈'">👁</span>
            @error('password')
                <p class="educore-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="educore-btn educore-btn-primary">Confirm</button>
    </form>
</x-guest-layout>
