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
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('vendors/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{ asset('vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
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

    @livewireStyles
</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        @if (Route::get(''))
            @include('layouts.partials.guest-header')
        @endif

        {{ $slot }}
    </main>

    @include('layouts.partials.footer')

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
    <script src="{{ asset('vendors/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/typed.js/typed.js') }}"></script>

    @stack('js')

    @livewireScripts
</body>

</html>
