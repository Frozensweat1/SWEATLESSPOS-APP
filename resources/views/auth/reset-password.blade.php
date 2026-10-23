<x-auth.layout title="Reset password">
    <h1>Choose a new password</h1>
    <p class="sub">Use a strong password you don't use elsewhere.</p>

    <form method="POST" action="{{ route('password.update') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-auth.field name="email" label="Email" type="email" :value="old('email', $request->email)" required autofocus autocomplete="username" inputmode="email" />
        <x-auth.field name="password" label="New password" type="password" required autocomplete="new-password" />
        <x-auth.field name="password_confirmation" label="Confirm new password" type="password" required autocomplete="new-password" />
        <x-auth.submit>Reset password</x-auth.submit>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}"><x-site.icon name="arrow-left" :size="14" /> Back to log in</a></p>
</x-auth.layout>
