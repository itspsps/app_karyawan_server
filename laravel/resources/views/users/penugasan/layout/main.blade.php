<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="theme-color" content="#2196f3">
    <meta name="author" content="DexignZone" />
    <meta name="keywords" content="" />
    <meta name="robots" content="" />
    <meta name="description" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:title" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:description" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:image" content="https://jobie.dexignzone.com/mobile-app/xhtml/social-image.png" />
    <meta name="format-detection" content="telephone=no">

    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png" />

    <!-- Title -->
    <title>APPS KARYAWAN</title>

    <!-- PWA Version -->
    {{-- <link rel="manifest" href="{{ asset('assets/assets_users/manifest.json') }}"> --}}

    <!-- Stylesheets -->
    @include('users.penugasan.layout.css') @yield('css')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>
    <div class="page-wraper">

        <!-- Preloader -->
        <div id="preloader">
            <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status">
            </div>
            <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status">
            </div>
            <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status">
            </div>
        </div>
        <!-- Preloader end-->

        <!-- Page Content -->
        <div class="page-content bottom-content">




            <!-- Footer End -->
            @yield('content')

        </div>
    </div>
    @include('users.penugasan.layout.menubar')
    @include('users.penugasan.layout.colorsetting')
    @include('users.penugasan.layout.js') @yield('js')
</body>

</html>