<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
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
    @include('users.izin.layout.css') @yield('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Include Bootstrap DateTimePicker CDN -->
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>
    @include('sweetalert::alert')
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

            <!-- Banner -->
            <div class="head-details">
                <div class="container">
                    <div class="dz-info col-12">
                        <span class="location d-block text-left">Form Izin&nbsp;
                        </span>
                        @if(auth()->user()->kategori=='Karyawan Bulanan')
                        <h6 class="title">@if($user->kontrak_kerja == 'SP')
                            CV. SUMBER PANGAN
                            @elseif($user->kontrak_kerja == 'SPS')
                            PT. SURYA PANGAN SEMESTA
                            @elseif($user->kontrak_kerja == 'SIP')
                            CV. SURYA INTI PANGAN
                            @endif</h6>
                        {{-- @foreach ($user  as $dep) --}}
                        <h6 class="title">Department of "{{ $user->nama_departemen }}"</h6>
                        {{-- @endforeach --}}
                        @elseif(auth()->user()->kategori=='Karyawan Harian')
                        <h6 class="title">{{auth()->user()->penempatan_kerja}}
                        </h6>
                        @endif
                    </div>
                    <div class="dz-media media-65">
                        <img src="assets/images/logo/logo.svg" alt="">
                    </div>
                </div>
            </div>

            <div class="fixed-content p-0">
                <div class="container">
                    <div class="main-content">
                        <div class="left-content">
                            <a href="javascript:void(0);" class="back-btn">
                                <svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.03033 0.46967C9.2966 0.735936 9.3208 1.1526 9.10295 1.44621L9.03033 1.53033L2.561 8L9.03033 14.4697C9.2966 14.7359 9.3208 15.1526 9.10295 15.4462L9.03033 15.5303C8.76406 15.7966 8.3474 15.8208 8.05379 15.6029L7.96967 15.5303L0.96967 8.53033C0.703403 8.26406 0.679197 7.8474 0.897052 7.55379L0.96967 7.46967L7.96967 0.46967C8.26256 0.176777 8.73744 0.176777 9.03033 0.46967Z" fill="#a19fa8" />
                                </svg>
                            </a>
                            <h5 class="mb-0">Back</h5>
                        </div>
                        <div class="mid-content">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Banner End -->


            <!-- Footer End -->
            @yield('content')

        </div>
    </div>
    @include('users.izin.layout.menubar')
    @include('users.izin.layout.js') @yield('js')
</body>

</html>