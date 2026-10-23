<x-auth.layout title="Create account">
    <h1>Create your account</h1>
    <p class="sub">It only takes a minute to get started.</p>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <x-auth.field name="name" label="Full name" :value="old('name')" required autofocus autocomplete="name" />
        <x-auth.field name="email" label="Email" type="email" :value="old('email')" required autocomplete="username" inputmode="email" />
        <x-auth.field name="password" label="Password" type="password" required autocomplete="new-password" />
        <x-auth.field name="password_confirmation" label="Confirm password" type="password" required autocomplete="new-password" />

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div>
                <label class="check">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span>{!! __('I agree to the :terms_of_service and :privacy_policy', [
                        'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '">' . __('Terms of Service') . '</a>',
                        'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '">' . __('Privacy Policy') . '</a>',
                    ]) !!}</span>
                </label>
                @error('terms')<div class="error" role="alert">{{ $message }}</div>@enderror
            </div>
        @endif

        <x-auth.submit>Create account</x-auth.submit>
    </form>

    <p class="auth-foot">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
</x-auth.layout>
