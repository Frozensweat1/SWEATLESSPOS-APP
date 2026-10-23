<x-auth.layout title="Two-factor authentication">
    <div id="tf">
        <span class="icon-hero"><x-site.icon name="check" :size="26" /></span>
        <h1>Two-factor check</h1>
        <p class="sub" data-show="code">Enter the code from your authenticator app.</p>
        <p class="sub" data-show="recovery" hidden>Enter one of your emergency recovery codes.</p>

        <form method="POST" action="{{ route('two-factor.login') }}" class="auth-form">
            @csrf
            <div data-show="code">
                <x-auth.field name="code" label="Authentication code" id="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autofocus autocomplete="one-time-code" class="input otp" />
            </div>
            <div data-show="recovery" hidden>
                <x-auth.field name="recovery_code" label="Recovery code" id="recovery_code" autocomplete="one-time-code" disabled />
            </div>

            <x-auth.submit>Verify and log in</x-auth.submit>

            <div class="auth-row" style="justify-content:center">
                <button type="button" class="link-btn" data-switch="recovery" data-show="code">Use a recovery code</button>
                <button type="button" class="link-btn" data-switch="code" data-show="recovery" hidden>Use an authentication code</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (function () {
                var root = document.getElementById('tf');
                root.addEventListener('click', function (e) {
                    var b = e.target.closest('[data-switch]');
                    if (!b) return;
                    var mode = b.getAttribute('data-switch');
                    root.querySelectorAll('[data-show]').forEach(function (el) {
                        el.hidden = el.getAttribute('data-show') !== mode;
                    });
                    root.querySelector('#code').disabled = mode !== 'code';
                    root.querySelector('#recovery_code').disabled = mode !== 'recovery';
                    root.querySelector(mode === 'code' ? '#code' : '#recovery_code').focus();
                });
            })();
        </script>
    @endpush
</x-auth.layout>
