<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>



<body>
 <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container" data-layout="container">
          <script>
            var isFluid = readStoredBoolean('isFluid', false);
            if (isFluid) {
              var container = document.querySelector('[data-layout]');
              container.classList.remove('container');
              container.classList.add('container-fluid');
            }
          </script>
          <div class="row flex-center min-vh-100 py-6">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">

                {{ $slot }}

            </div>
          </div>
        </div>
      </main>
      <!-- ===============================================-->
      <!--    End of Main Content-->
      <!-- ===============================================-->
    @livewireScripts

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

</body>

</html>
