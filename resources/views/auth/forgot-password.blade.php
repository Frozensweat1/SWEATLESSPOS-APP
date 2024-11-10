<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="card-body p-4 p-sm-5">

            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="alert alert-success" role="alert">{{ $value }}</div>
            @endsession

            <div class="row flex-between-center mb-2">
                <div class="col-auto">
                    <h5>Forgot Password</h5>
                </div>
                <p> {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>

        </div>
    </x-authentication-card>
</x-guest-layout>
