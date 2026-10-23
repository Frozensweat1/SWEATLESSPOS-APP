<x-auth.layout title="Confirm password">
    <span class="icon-hero"><x-site.icon name="check" :size="26" /></span>
    <h1>Confirm your password</h1>
    <p class="sub">This is a secure area. Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf
        <x-auth.field name="password" label="Password" type="password" required autofocus autocomplete="current-password" />
        <x-auth.submit>Confirm</x-auth.submit>
    </form>
</x-auth.layout>
