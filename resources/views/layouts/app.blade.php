<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="icon" href="{{ $settings->FaviconUrl ?? asset('assets/img/logo.png') }}" type="image/x-icon">
    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="{{ url('/manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/icon-192.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <script>window.SITE_PWA = {!! json_encode(['sw' => asset('sw.js'), 'scope' => rtrim(url('/'), '/') . '/'], JSON_UNESCAPED_SLASHES) !!};</script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('vendors/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('vendors/overlayscrollbars/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/theme-rtl.min.css') }}" rel="stylesheet" id="style-rtl" disabled>
    <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl" disabled>
    <link href="{{ asset('assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">

    @stack('styles')

    <script>
        function readStoredBoolean(key, fallback) {
            var raw = localStorage.getItem(key);
            if (raw === null) return fallback;
            try {
                return JSON.parse(raw);
            } catch (e) {
                return fallback;
            }
        }

        var isRTL = readStoredBoolean('isRTL', false);
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');

        if (isRTL) {
            linkRTL.removeAttribute('disabled');
            userLinkRTL.removeAttribute('disabled');
            linkDefault.setAttribute('disabled', 'disabled');
            userLinkDefault.setAttribute('disabled', 'disabled');
            document.documentElement.setAttribute('dir', 'rtl');
        } else {
            linkRTL.setAttribute('disabled', 'disabled');
            userLinkRTL.setAttribute('disabled', 'disabled');
            linkDefault.removeAttribute('disabled');
            userLinkDefault.removeAttribute('disabled');
            document.documentElement.setAttribute('dir', 'ltr');
        }
    </script>
</head>

<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid" data-layout="container">
            <script>
                var isFluid = readStoredBoolean('isFluid', false);
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.add('container-fluid');
                }
            </script>
            @include('layouts.partials.sidebar')
            <div class="content">
                @include('layouts.partials.header')
                <div class="px-2 px-sm-3 px-md-4 py-3">
                    {{ $slot }}
                </div>
                @include('layouts.partials.footer')
            </div>

        </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{ asset('vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @auth
        <script>window.POS_QUEUE = {!! json_encode([
            'userId' => auth()->id(),
            'catalog' => route('pos.offline.catalog', [], false),
            'token' => route('pos.offline.token', [], false),
            'sync' => route('pos.offline.sync', [], false),
        ], JSON_UNESCAPED_SLASHES) !!};</script>
        <script src="{{ asset('assets/js/pos-queue.js') }}"></script>
    @endauth
    <script src="{{ asset('assets/js/app-pwa.js') }}"></script>
    @stack('js')
</body>

</html>
