<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <title>Dashboard admin TransacExtrac</title>
    <meta charset="utf-8">
    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('assetsv2/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assetsv2/css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assetsv2/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assetsv2/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assetsv2/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assetsv2/images/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assetsv2/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assetsv2/css/custom.css') }}">
</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">

                <div id="preload" class="preload-container">
                    <div class="preloading">
                        <span></span>
                    </div>
                </div>

                @include('layouts.superadminv2.inc.sidebar')
                <div class="section-content-right">

                    @include('layouts.superadminv2.inc.navbar')
                    <div class="main-content">


                        {{-- @include('layouts.gestionnairev2.inc.sidebar') --}}

                        @yield('content')
                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 SurfsideMedia</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assetsv2/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assetsv2/js/bootstrap-select.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="{{ asset('assetsv2/js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('assetsv2/js/main.js') }}"></script>
</body>

</html>
