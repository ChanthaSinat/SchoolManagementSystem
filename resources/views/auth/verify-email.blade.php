<x-guest-layout title="Verify Email">
    <div class="educore-form-head">
        <h2>Verify your email</h2>
        <p>Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent you. If you didn't receive it, we can send another.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="educore-success-msg">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</div>
    @endif

    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="educore-btn educore-btn-primary">Resend Verification Email</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="educore-link-btn" style="margin:0;">Log Out</button>
        </form>
    </div>
</x-guest-layout>
