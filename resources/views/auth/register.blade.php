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
                    <h5>Register</h5>
                </div>

                <div class="col-auto fs--1 text-600">
                    <span class="mb-0 undefined">Have an account?</span>
                    <span><a href="{{ route('login') }}">Login</a></span>
                </div>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <x-input id="name" class="form-control" type="name" name="name" :value="old('name')"
                        placeholder="Name" autofocus autocomplete="name" />
                </div>
                <div class="mb-3">
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                        placeholder="Email" autofocus autocomplete="username" />
                </div>
                <div class="row gx-2">
                    <div class="mb-3 col-sm-6">
                        <x-input id="password" class="form-control" type="password" name="password"
                            placeholder="Password" autocomplete="new-password" />
                    </div>
                    <div class="mb-3 col-sm-6">
                        <x-input id="password_confirmation" class="form-control" type="password"
                            name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password" />
                    </div>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="form-check">
                        <x-checkbox class="form-check-input" type="checkbox" id="basic-register-checkbox" />
                        <x-label for="terms" class="form-label" for="basic-register-checkbox">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' =>
                                    '<a target="_blank" href="' .
                                    route('terms.show') .
                                    '" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' .
                                    __('Terms of Service') .
                                    '</a>',
                                'privacy_policy' =>
                                    '<a target="_blank" href="' .
                                    route('policy.show') .
                                    '" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' .
                                    __('Privacy Policy') .
                                    '</a>',
                            ]) !!}
                        </x-label>
                    </div>
                @endif

                <div class="mb-3">
                    <x-button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">
                        {{ _('Register') }}
                    </x-button>
                </div>

            </form>
        </div>
    </x-authentication-card>

</x-guest-layout>
