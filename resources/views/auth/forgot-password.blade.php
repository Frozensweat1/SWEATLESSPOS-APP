<x-auth.layout title="Forgot password">
    <span class="icon-hero"><x-site.icon name="mail" :size="26" /></span>
    <h1>Forgot your password?</h1>
    <p class="sub">Enter your email and we'll send you a link to choose a new one.</p>

    @session('status')
        <div class="alert alert--ok" role="status" style="margin-bottom:1rem">{{ $value }}</div>
    @endsession

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf
        <x-auth.field name="email" label="Email" type="email" :value="old('email')" required autofocus autocomplete="username" inputmode="email" />
        <x-auth.submit>Email reset link</x-auth.submit>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}"><x-site.icon name="arrow-left" :size="14" /> Back to log in</a></p>
</x-auth.layout>
