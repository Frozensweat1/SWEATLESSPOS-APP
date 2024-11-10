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
                    <h5>Reset Password</h5>
                </div>
            </div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                </div>
                <div class="mb-3">
                    <x-input id="password" class="form-control" type="password" name="password" required
                        autocomplete="new-password" />
                </div>
                <div class="mb-3">
                    <x-input id="password_confirmation" class="form-control" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                        name="submit">{{ __('Reset Password') }}</button>
                </div>
            </form>

        </div>
    </x-authentication-card>
</x-guest-layout>
