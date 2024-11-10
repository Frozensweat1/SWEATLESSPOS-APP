<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        <div class="card-body p-4 p-sm-5">
            <x-validation-errors class="mb-4" /> @session('status')
                <div class="alert alert-success" role="alert">{{ $value }}</div>
            @endsession
            <div class="row flex-between-center mb-2">
                <div class="col-auto">
                    <h5>Log in</h5>
                </div>
                <div class="col-auto fs--1 text-600">
                    <span class="mb-0 undefined">or</span>
                    <span>
                        <a href="{{ route('register') }}">Create an account</a>
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                </div>
                <div class="mb-3">
                    <x-input id="password" class="form-control" type="password" name="password" required
                        autocomplete="current-password" />
                </div>
                <div class="row flex-between-center">
                    <div class="col-auto">
                        <div class="form-check mb-0">
                            <x-checkbox id="remember_me" name="remember" />
                            <label class="form-check-label mb-0" for="basic-checkbox">{{ __('Remember me') }}</label>
                        </div>
                    </div>
                    <div class="col-auto">
                        @if (Route::has('password.request'))
                            <a class="fs--1" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                        name="submit">{{ __('Log in') }}</button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
