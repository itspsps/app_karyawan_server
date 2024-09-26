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
    <meta name="description" content="HRD-APPS - SUMBER PANGAN -SURYA PANGAN SEMESTA" />
    <meta property="og:title" content="HRD-APPS - SUMBER PANGAN -SURYA PANGAN SEMESTA" />
    <meta property="og:description" content="HRD-APPS - SUMBER PANGAN -SURYA PANGAN SEMESTA" />
    <meta property="og:image" content="{{ asset('holding/assets/img/logosp.png') }}" />
    <meta name="format-detection" content="telephone=no">
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('holding/assets/img/logosp.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('holding/assets/img/logosp.png') }}" />

    <!-- Title -->
    <title>HRD-APPS</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/assets_users/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets_users/vendor/swiper/swiper-bundle.min.css') }}">


    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Racing+Sans+One&display=swap" rel="stylesheet">

</head>

<body>
    <div class="page-wraper">



        <!-- Page Content -->
        <div class="page-content">
            @if(Session::has('login_error'))
            <div class="offcanvas offcanvas-bottom show text-center">
                <div class="container">
                    <div class="offcanvas-body small">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">

                            <defs>

                                <style>
                                    .cls-1 {
                                        fill: #669df6;
                                    }

                                    .cls-1,
                                    .cls-2 {
                                        fill-rule: evenodd;
                                    }

                                    .cls-2 {
                                        fill: #4285f4;
                                    }
                                </style>

                            </defs>

                            <title>Icon_24px_ErrorReporting_Color</title>

                            <g data-name="Product Icons">

                                <g>

                                    <polygon id="Fill-1" class="cls-1" points="7 2 2 7 2 17 7 22 12 22 9.5 19.14 8.25 19.14 4.86 15.75 4.86 8.25 8.25 4.86 9.5 4.86 12 2 7 2" />

                                    <polygon id="Fill-2" class="cls-1" points="14.5 2 12 4.86 15.75 4.86 19.14 8.25 19.14 15.75 15.75 19.14 12 19.14 14.5 22 17 22 22 17 22 7 17 2 14.5 2" />

                                    <polygon id="Fill-3" class="cls-2" points="12 17 9.5 14.5 12 12 9.5 9.5 12 7 9.5 7 7 9.5 7 14.5 9.5 17 12 17" />

                                    <polygon id="Fill-4" class="cls-2" points="14.5 7 12 9.5 14.5 12 12 14.5 14.5 17 17 14.5 17 9.5 14.5 7" />

                                </g>

                            </g>

                        </svg>
                        <h5 class="title">ANDA GAGAL LOGIN</h5>
                        <p class="pwa-text">Pastikan Username dan Password Sesuai</p>
                    </div>
                </div>
            </div>
            @elseif(Session::has('user_nonaktif'))
            <div class="offcanvas offcanvas-bottom show text-center">
                <div class="container">
                    <div class="offcanvas-body small">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">

                            <defs>

                                <style>
                                    .cls-1 {
                                        fill: #669df6;
                                    }

                                    .cls-1,
                                    .cls-2 {
                                        fill-rule: evenodd;
                                    }

                                    .cls-2 {
                                        fill: #4285f4;
                                    }
                                </style>

                            </defs>

                            <title>Icon_24px_ErrorReporting_Color</title>

                            <g data-name="Product Icons">

                                <g>

                                    <polygon id="Fill-1" class="cls-1" points="7 2 2 7 2 17 7 22 12 22 9.5 19.14 8.25 19.14 4.86 15.75 4.86 8.25 8.25 4.86 9.5 4.86 12 2 7 2" />

                                    <polygon id="Fill-2" class="cls-1" points="14.5 2 12 4.86 15.75 4.86 19.14 8.25 19.14 15.75 15.75 19.14 12 19.14 14.5 22 17 22 22 17 22 7 17 2 14.5 2" />

                                    <polygon id="Fill-3" class="cls-2" points="12 17 9.5 14.5 12 12 9.5 9.5 12 7 9.5 7 7 9.5 7 14.5 9.5 17 12 17" />

                                    <polygon id="Fill-4" class="cls-2" points="14.5 7 12 9.5 14.5 12 12 14.5 14.5 17 17 14.5 17 9.5 14.5 7" />

                                </g>

                            </g>

                        </svg>
                        <h5 class="title">ANDA GAGAL LOGIN</h5>
                        <p class="pwa-text">User Non Aktif</p>
                    </div>
                </div>
            </div>
            @elseif(Session::has('logout_success'))
            <div class="offcanvas offcanvas-bottom show text-center">
                <div class="container">
                    <div class="offcanvas-body small">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="50" width="50" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve">
                            <path style="fill:#4FC0E8;" d="M0,256.006C0,397.402,114.606,512.004,255.996,512C397.394,512.004,512,397.402,512,256.006  C512.009,114.61,397.394,0,255.996,0C114.606,0,0,114.614,0,256.006z" />
                            <path style="fill:#3DAED9;" d="M512,256.005c0.002-35.04-7.054-68.427-19.794-98.842c-0.179-0.24-0.329-0.5-0.572-0.7  c-0.16-0.129-0.356-0.163-0.524-0.276c-0.345-0.47-66.748-66.801-66.915-66.915c-0.345-0.468-0.638-0.972-1.11-1.357  c-3.027-2.465-7.474-2.03-9.949,1.002l-96.48,117.941c-0.302-0.329-35.189-35.297-36.305-35.851  c-18.686-9.28-38.774-13.988-59.705-13.988c-74.068,0-134.325,60.26-134.325,134.329c0,37.451,15.438,71.333,40.243,95.718  c0.54,0.549,124.558,124.557,124.815,124.815c1.541,0.029,3.069,0.117,4.617,0.117C397.394,512.004,512,397.401,512,256.005z" />
                            <g>
                                <path style="fill:#F4F6F9;" d="M423.086,87.917c-3.027-2.465-7.474-2.03-9.949,1.002L209.89,337.374l-61.997-62.318   c-2.755-2.769-7.235-2.789-9.997-0.028c-2.769,2.755-2.783,7.229-0.028,9.997l67.523,67.869c1.329,1.34,3.135,2.085,5.012,2.085   c0.114,0,0.228,0,0.346-0.006c1.996-0.097,3.859-1.036,5.126-2.589L424.08,97.867C426.553,94.849,426.107,90.388,423.086,87.917z" />
                                <path style="fill:#F4F6F9;" d="M343.124,256.111c-3.808,0.87-6.187,4.66-5.313,8.471c2.01,8.768,3.027,17.772,3.027,26.768   c0,66.274-53.919,120.189-120.192,120.189c-66.27,0-120.186-53.915-120.186-120.189s53.915-120.189,120.186-120.189   c18.727,0,36.7,4.211,53.414,12.511c3.51,1.747,7.74,0.318,9.476-3.189c1.737-3.493,0.31-7.74-3.186-9.473   c-18.686-9.279-38.774-13.988-59.705-13.988c-74.068,0-134.325,60.26-134.325,134.329s60.257,134.329,134.325,134.329   c74.073,0,134.332-60.26,134.332-134.329c0-10.052-1.139-20.126-3.387-29.93C350.723,257.616,346.915,255.248,343.124,256.111z" />
                            </g>
                        </svg>
                        <h5 class="title">ANDA BERHASIL LOGOUT</h5>
                    </div>
                </div>
            </div>
            @endif
            <!-- Banner -->
            <div class="banner-wrapper shape-1">
                <div class="container inner-wrapper">
                    <h2 class="dz-title">SIGN IN</h2>
                    <p class="mb-0">PLEASE SIGN IN TO APP HRD</p>
                </div>
            </div>
            <!-- Banner End -->
            <div class="container">

                <div class="account-area">
                    <form method="POST" action="{{ url('/login-proses') }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username" autofocus placeholder="Username">
                        </div>
                        @error('username')
                        <p class="alert alert-danger">{{$message}}</p>
                        @enderror
                        <div class="input-group">
                            <input type="password" name="password" id="dz-password" class="form-control be-0 @error('password') is-invalid @enderror" placeholder="***********">
                            <span class="input-group-text" onclick="password_show_hide();">
                                <i class="fa fa-eye-slash d-none" id="hide_eye"></i>
                                <i class="fa fa-eye" id="show_eye"></i>
                            </span>
                        </div>
                        @error('password')
                        <p class="alert alert-danger">{{$message}}</p>
                        @enderror
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                            <label class="form-check-label" for="flexCheckDefault">
                                Remember Me
                            </label>
                        </div>
                        <a href="forgot-password.html" class="btn-link d-block text-center">Forgot your password?</a>
                        <div class="input-group">
                            <button id="btn_login" class="btn mt-2 btn-primary w-100 btn-rounded" type="submit">Login</button>
                        </div>
                    </form>
                    <div class="text-center p-tb20">
                        <span class="saprate">Or sign in with</span>
                    </div>
                    <div class="social-btn-group text-center">
                        <a href="https://www.google.com/" target="_blank" class="social-btn"><img src="{{ asset('assets/assets_users/images/social/google.png') }}" alt="socila-image"></a>
                        <a href="https://www.facebook.com/" target="_blank" class="social-btn ms-3"><img src="{{ asset('assets/assets_users/images/social/facebook.png') }}" alt="social-image"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Content End -->

        <!-- Footer -->
        <!-- <footer class="footer fixed">
            <div class="container">
                <a href="register.html" class="btn btn-primary light btn-rounded text-primary d-block">Create account</a>
            </div>
        </footer> -->
    </div>
    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('assets/assets_users/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/assets_users/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/assets_users/js/settings.js') }}"></script>
    <script src="{{ asset('assets/assets_users/js/custom.js') }}"></script> -->
    <script src="{{asset('assets/assets_users/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>
    <script>
        $("document").ready(function() {
            // console.log('ok');
            setTimeout(function() {
                // console.log('ok1');
                $("#alert_logout_success").remove();
            }, 7000); // 7 secs

        });
        $("document").ready(function() {
            // console.log('ok');
            setTimeout(function() {
                // console.log('ok1');
                $("#alert_login_error").remove();
            }, 7000); // 7 secs

        });
        $(document).on('click', '#btn_login', function(e) {
            Swal.fire({
                allowOutsideClick: false,
                background: 'transparent',
                html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
                showCancelButton: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    // Swal.showLoading()
                },
            });
        });
        window.onbeforeunload = function() {
            Swal.fire({
                allowOutsideClick: false,
                background: 'transparent',
                html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
                showCancelButton: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    // Swal.showLoading()
                },
            });
        };

        function password_show_hide() {
            var x = document.getElementById("dz-password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            hide_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                show_eye.style.display = "none";
                hide_eye.style.display = "block";
            } else {
                x.type = "password";
                show_eye.style.display = "block";
                hide_eye.style.display = "none";

            }
        }
    </script>
</body>

</html>