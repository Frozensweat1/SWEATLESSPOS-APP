<x-auth.layout title="Verify email">
    <span class="icon-hero"><x-site.icon name="mail" :size="26" /></span>
    <h1>Verify your email</h1>
    <p class="sub">Before continuing, please click the link we just emailed to you. If you didn't receive it, we'll gladly send another.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert--ok" role="status" style="margin-bottom:1rem">
            A new verification link has been sent to the email address in your profile.
        </div>
    @endif

    <form method="POST" action="{{ Route::has('verification.send') ? route('verification.send') : '#' }}" class="auth-form">
        @csrf
        <x-auth.submit>Resend verification email</x-auth.submit>
    </form>

    <div class="auth-row" style="justify-content:center;gap:1.25rem;margin-top:1.25rem">
        <a href="{{ route('user.profile') }}">Edit profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="link-btn">Log out</button>
        </form>
    </div>
</x-auth.layout>
