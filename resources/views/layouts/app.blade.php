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

        <title>{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}</title>

        <!-- ===============================================-->
        <!--    Favicons-->
        <!-- ===============================================-->
        @if ($settings->favicon)
            <link rel="icon" href="{{ asset('storage/' . $settings->favicon) }}" type="image/x-icon">
        @endif
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
        <link href="{{ asset('assets/css/theme-rtl.min.css') }}" rel="stylesheet" id="style-rtl">
        <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
        <link href="{{ asset('assets/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl">
        <link href="{{ asset('assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
        @stack('styles')
        <style>
            #receipt {
                width: 80mm;
                /* Or 80mm, depending on your printer's paper width */
                font-size: 12px;
                /* Adjust font size for readability on smaller widths */
            }

            /* Optional: Remove table borders and adjust padding/margins */
            #receipt table {
                width: 100%;
                border-collapse: collapse;
            }

            #receipt th,
            #receipt td {
                padding: 5px;
                text-align: left;
            }

            /* Simplify layout for printing */
            @media print {
                body * {
                    visibility: hidden;
                }

                #receipt,
                #receipt * {
                    visibility: visible;
                }

                #receipt {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 80mm;
                    /* Match the thermal printer width */
                }
            }
        </style>
        <script>
            var isRTL = JSON.parse(localStorage.getItem('isRTL'));
            if (isRTL) {
                var linkDefault = document.getElementById('style-default');
                var userLinkDefault = document.getElementById('user-style-default');
                linkDefault.setAttribute('disabled', true);
                userLinkDefault.setAttribute('disabled', true);
                document.querySelector('html').setAttribute('dir', 'rtl');
            } else {
                var linkRTL = document.getElementById('style-rtl');
                var userLinkRTL = document.getElementById('user-style-rtl');
                linkRTL.setAttribute('disabled', true);
                userLinkRTL.setAttribute('disabled', true);
            }
        </script>
    </head>

    <body>

        <!-- ===============================================-->
        <!--    Main Content-->
        <!-- ===============================================-->
        <main class="main" id="top">
            <div class="container" data-layout="container">
                <script>
                    var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                    if (isFluid) {
                        var container = document.querySelector('[data-layout]');
                        container.classList.remove('container');
                        container.classList.add('container-fluid');
                    }
                </script>
                @include('layouts.partials.sidebar')
                <div class="content">
                    @include('layouts.partials.header')
                    {{ $slot }}
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
        {{-- sweetalert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts />
        @stack('js')
    </body>

</html>
