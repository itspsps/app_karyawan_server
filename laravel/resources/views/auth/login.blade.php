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
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('public/holding/assets/img/logosp.png') }}" />

    <!-- Title -->
    <title>APPS KARYAWAN</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/assets_users/css/style.css') }}">
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
            @else
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
                            <input type="password" name="password" id="dz-password" id="password" class="form-control be-0 @error('password') is-invalid @enderror" placeholder="***********">
                            <span class="input-group-text show-pass">
                                <i class="fa fa-eye-slash"></i>
                                <i class="fa fa-eye"></i>
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
        <footer class="footer fixed">
            <div class="container">
                <a href="register.html" class="btn btn-primary light btn-rounded text-primary d-block">Create account</a>
            </div>
        </footer>
        <!-- Footer End -->

        <!-- Theme Color Settings -->
        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom">
            <div class="offcanvas-body small">
                <ul class="theme-color-settings">
                    <li>
                        <input class="filled-in" id="primary_color_8" name="theme_color" type="radio" value="color-primary" />
                        <label for="primary_color_8"></label>
                        <span>Default</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_2" name="theme_color" type="radio" value="color-green" />
                        <label for="primary_color_2"></label>
                        <span>Green</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_3" name="theme_color" type="radio" value="color-blue" />
                        <label for="primary_color_3"></label>
                        <span>Blue</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_4" name="theme_color" type="radio" value="color-pink" />
                        <label for="primary_color_4"></label>
                        <span>Pink</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_5" name="theme_color" type="radio" value="color-yellow" />
                        <label for="primary_color_5"></label>
                        <span>Yellow</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_6" name="theme_color" type="radio" value="color-orange" />
                        <label for="primary_color_6"></label>
                        <span>Orange</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_7" name="theme_color" type="radio" value="color-purple" />
                        <label for="primary_color_7"></label>
                        <span>Purple</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_1" name="theme_color" type="radio" value="color-red" />
                        <label for="primary_color_1"></label>
                        <span>Red</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_9" name="theme_color" type="radio" value="color-lightblue" />
                        <label for="primary_color_9"></label>
                        <span>Lightblue</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_10" name="theme_color" type="radio" value="color-teal" />
                        <label for="primary_color_10"></label>
                        <span>Teal</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_11" name="theme_color" type="radio" value="color-lime" />
                        <label for="primary_color_11"></label>
                        <span>Lime</span>
                    </li>
                    <li>
                        <input class="filled-in" id="primary_color_12" name="theme_color" type="radio" value="color-deeporange" />
                        <label for="primary_color_12"></label>
                        <span>Deeporange</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Theme Color Settings End -->
    </div>
    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('assets/assets_users/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/assets_users/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/assets_users/js/settings.js') }}"></script>
    <script src="{{ asset('assets/assets_users/js/custom.js') }}"></script>
    <script src="{{asset('assets/assets_users/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
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
    </script>
</body>

</html>