<x-auth.layout title="Log in">
    <h1>Welcome back</h1>
    <p class="sub">Log in to continue to your dashboard.</p>

    @session('status')
        <div class="alert alert--ok" role="status" style="margin-bottom:1rem">{{ $value }}</div>
    @endsession

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <x-auth.field name="email" label="Email" type="email" :value="old('email')" required autofocus autocomplete="username" inputmode="email" />
        <x-auth.field name="password" label="Password" type="password" required autocomplete="current-password" />

        <div class="auth-row">
            <label class="check"><input type="checkbox" name="remember" id="remember_me"> Remember me</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <x-auth.submit>Log in</x-auth.submit>
    </form>

    @if (Route::has('register'))
        <p class="auth-foot">New here? <a href="{{ route('register') }}">Create an account</a></p>
    @endif
</x-auth.layout>
