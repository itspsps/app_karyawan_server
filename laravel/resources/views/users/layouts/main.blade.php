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
    @include('users.layouts.css') @yield('css')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" rel="stylesheet">

</head>

<body>
    <div class="page-wraper">

        <!-- Header -->
        <header class="header transparent">
            <div class="main-bar">
                <div class="container">
                    <div class="header-content">
                        <div class="left-content">
                            <a href="javascript:void(0);" class="menu-toggler">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4" d="M16.0755 2H19.4615C20.8637 2 22 3.14585 22 4.55996V7.97452C22 9.38864 20.8637 10.5345 19.4615 10.5345H16.0755C14.6732 10.5345 13.537 9.38864 13.537 7.97452V4.55996C13.537 3.14585 14.6732 2 16.0755 2Z" fill="#a19fa8" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="#a19fa8" />
                                </svg>
                            </a>
                        </div>
                        <div class="mid-content">
                        </div>
                        <div class="right-content">
                            <!-- <a href="javascript:void(0);" class="theme-color" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">
                                <svg class="color-plate" enable-background="new 0 0 512.214 512.214" height="24" viewBox="0 0 512.214 512.214" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <g id="Color_Palette_1_">
                                        <g>
                                            <path d="m247.523 512.214c-1.552 0-3.111-.04-4.68-.12-18.018-.919-36.245-3.725-54.178-8.339-92.826-23.89-161.982-96.467-182.181-189.601-9.88-45.557-8.432-90.341 4.304-133.109 23.822-80.001 86.489-145.327 170.276-170.276 42.766-12.735 87.55-14.183 133.108-4.303 93.122 20.195 165.672 89.343 189.565 182.18 4.615 17.933 7.421 36.161 8.339 54.177 1.854 36.362-17.939 68.259-51.657 83.242-34.298 15.243-73.443 8.112-99.723-18.167-15.537-15.538-37.242-15.538-52.779 0-15.611 15.597-15.676 37.153-.007 52.811.003.002.004.004.006.006 26.278 26.278 33.41 65.42 18.168 99.721-14.337 32.263-44.159 51.778-78.561 51.778zm7.237-472.209c-57.565 0-111.211 21.694-152.127 62.61-52.797 52.797-73.594 126.81-57.058 203.062 16.995 78.361 75.644 139.417 153.059 159.341 15.342 3.948 30.9 6.347 46.245 7.129 19.745 1.012 36.427-9.444 44.651-27.953 6.736-15.161 7.675-37.622-9.898-55.194-31.279-31.26-31.212-78.199.007-109.391 31.161-31.163 78.172-31.165 109.343.006 17.572 17.573 40.033 16.634 55.194 9.898 18.509-8.225 28.959-24.917 27.953-44.652-.782-15.344-3.181-30.902-7.13-46.244-23.476-91.222-104.657-158.612-210.239-158.612z" />
                                        </g>
                                        <g>
                                            <path d="m156.197 396.178c-33.084 0-60-26.916-60-60s26.916-60 60-60 60 26.916 60 60-26.916 60-60 60zm0-80c-11.028 0-20 8.972-20 20s8.972 20 20 20 20-8.972 20-20-8.972-20-20-20z" />
                                        </g>
                                        <g>
                                            <path d="m156.197 236.179c-33.084 0-60-26.916-60-60s26.916-60 60-60 60 26.916 60 60-26.916 60-60 60zm0-80c-11.028 0-20 8.972-20 20s8.972 20 20 20 20-8.972 20-20-8.972-20-20-20z" />
                                        </g>
                                        <g>
                                            <path d="m316.197 216.179c-33.084 0-60-26.916-60-60s26.916-60 60-60 60 26.916 60 60-26.916 60-60 60zm0-80c-11.028 0-20 8.972-20 20s8.972 20 20 20 20-8.972 20-20-8.972-20-20-20z" />
                                        </g>
                                    </g>
                                </svg>
                            </a> -->
                            <a href="javascript:void(0);" class="theme-btn">
                                <svg class="dark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                </svg>
                                <svg class="light" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="5"></circle>
                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

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

        <!-- Sidebar -->
        @include('users.layouts.sidebar')
        <!-- Sidebar End -->

        <!-- Banner -->
        <div class="banner-wrapper author-notification">
            <div class="container inner-wrapper">
                <div class="dz-info">
                    <span>
                        <?php
                        date_default_timezone_set("Asia/Jakarta");
                        $time = date("H");
                        /* Set the $timezone variable to become the current timezone */
                        $timezone = date("e");
                        /* If the time is less than 1200 hours, show good morning */
                        if ($time < "12") {
                            echo "Good morning";
                        } else
                            /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
                            if ($time >= "12" && $time < "17") {
                                echo "Good afternoon";
                            } else
                                /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
                                if ($time >= "17" && $time < "19") {
                                    echo "Good evening";
                                } else
                                    /* Finally, show good night if the time is greater than or equal to 1900 hours */
                                    if ($time >= "19") {
                                        echo "Good night";
                                    }
                        ?>
                    </span>
                    <h5 class="name mb-0">{{ Auth::user()->name }}</h5>
                </div>
                <div class="dz-media media-50 rounded-circle">
                    @if(Auth::user()->foto_karyawan == '' || Auth::user()->foto_karyawan == NULL)
                    <a href="{{route('profile')}}"><img src="{{asset('admin/assets/img/avatars/1.png')}}" class="rounded-circle" alt="author-image"></a>
                    @else
                    <a href="{{route('profile')}}"><img src="{{ url('https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_karyawan/'.Auth::user()->foto_karyawan) }}" class="rounded-circle" alt="author-image"></a>
                    @endif
                </div>
            </div>
        </div>
        <!-- Banner End -->

        <!-- Page Content -->
        <div class="page-content">

            <div class="content-inner pt-0">
                <div class="container fb">
                    <!-- Search -->
                    <div class="fixed-content p-0" style=" border-radius: 10px; margin-top: 0%;box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
                        @yield('content_top')
                        @yield('alert')
                        @yield('absensi')

                    </div>

                    <!-- Dashboard Area -->
                    <div class="dashboard-area">
                        @yield('content')
                        <!-- Features -->


                    </div>
                </div>
            </div>

        </div>
        <!-- Page Content End-->

        <!-- Menubar -->
        @include('users.layouts.menubar')
        <!-- Menubar -->

        <!-- Theme Color Settings -->
        @include('users.layouts.colorsetting')
        <!-- Theme Color Settings End -->

        <!-- PWA Offcanvas -->
        <!-- PWA Offcanvas End -->

    </div>
    <!--**********************************
    Scripts
***********************************-->
    @include('users.layouts.js')
    @yield('js')
</body>

</html>