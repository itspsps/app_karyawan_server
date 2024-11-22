<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>HRD-APP | ADMIN</title>

    <meta name="description" content="" />

    <link rel="apple-touch-icon" href="{{ asset('holding/assets/img/logosp.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <!-- Favicons Icon -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('admin/assets/vendor/fonts/materialdesignicons.css')}}" />


    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/assets_users/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets_users/vendor/swiper/swiper-bundle.min.css') }}">

    <style>
        .stripe_background {
            background: rgb(103, 58, 183);
            background: linear-gradient(0deg, rgba(103, 58, 183, 1) 6%, rgba(200, 176, 244, 1) 100%);
            /* background: var(--primary); */
            color: #FFF;
            padding: 2em;
            position: relative;
            min-height: 300px;
            overflow: hidden;
        }

        .stripe_background::after {
            content: '';
            position: absolute;
            bottom: 10%;
            left: -40%;
            right: 5%;
            border-radius: 20%;
            height: 100%;
            background: #FFF;
            transform: skew(-20deg, 5deg);
            /* transform: rotate(-60deg); */
        }

        @media screen and (min-width: 1801px) {
            .logo_sps {
                margin-right: 6%;
                margin-bottom: 1%;
                width: 95px;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .fixed-content {
                margin-bottom: 10%;
                margin-top: 10%;
                /* margin: 8%; */
            }

            .logo_sp {
                width: 110px;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 6%;
                margin-bottom: 1%;
                bottom: 0;
                z-index: 1;
                width: 95px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }


        @media screen and (min-width: 1500px) and (max-width: 1800px) {
            .logo_sps {
                margin-right: 5%;
                margin-bottom: 1%;
                width: 90px;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: 5px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .logo_sp {
                width: 100px;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .fixed-content {
                margin-bottom: 10%;
                margin-top: 10%;
                /* margin: 8%; */
            }


            .logo_sip {
                margin-left: 6%;
                margin-bottom: 1%;
                bottom: 0;
                z-index: 1;
                width: 90px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 1300px) and (max-width: 1500px) {
            .logo_sps {
                margin-right: 7%;
                margin-bottom: 2%;
                width: 90px;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: 3px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .logo_sp {
                width: 100px;
                margin-bottom: 3%;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .fixed-content {
                /* margin-bottom: 15%; */
                margin-top: 5%;
                /* margin: 8%; */
            }

            .logo_sip {
                margin-left: 7%;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                width: 90px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 1100px) and (max-width: 1300px) {
            .logo_sps {
                margin-right: 8%;
                margin-bottom: 1%;
                width: 90px;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -1px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .fixed-content {
                /* margin-bottom: 15%; */
                margin-top: 5%;
                /* margin: 8%; */
            }

            .logo_sp {
                width: 100px;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 8%;
                margin-bottom: 1%;
                bottom: 0;
                z-index: 1;
                width: 90px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }



        @media screen and (min-width: 991px) and (max-width: 1100px) {
            .logo_sps {
                margin-right: 8%;
                margin-bottom: 2%;
                width: 90px;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: 0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .fixed-content {
                /* margin-bottom: 25%; */
                margin-top: 5%;
                /* margin: 8%; */
            }

            .logo_sp {
                width: 100px;
                margin-bottom: 3%;
                bottom: 0;
                position: fixed;
                z-index: 1;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 9%;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                width: 90px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 920px) and (max-width: 990px) {
            .logo_sps {
                margin-right: 8%;
                margin-bottom: 2%;
                width: 90px;
                bottom: 0;
                position: fixed;
                z-index: 1;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .logo_sp {
                width: 100px;
                z-index: 1;
                margin-bottom: 3%;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .fixed-content {
                /* margin-bottom: 25%; */
                margin-top: 15%;
                /* margin: 8%; */
            }

            .logo_sip {
                margin-left: 9%;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                width: 90px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 760px) and (max-width: 920px) {
            .logo_sps {
                margin-right: 9%;
                margin-bottom: 2%;
                width: 90px;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .fixed-content {
                margin-bottom: 20%;
                margin-top: 20%;
                /* margin: 8%; */
            }

            .logo_sp {
                width: 100px;
                margin-bottom: 3%;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 10%;
                margin-bottom: 2%;
                bottom: 0;
                z-index: 1;
                width: 90px;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

        }

        @media screen and (min-width: 700px) and (max-width: 760px) {

            .logo_sps {
                margin-right: 12%;
                margin-bottom: 2%;
                width: 95px;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .fixed-content {
                margin-bottom: 25%;
                margin-top: 25%;
                /* margin: 8%; */
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .logo_sp {
                width: 100px;
                margin-bottom: 3%;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 13%;
                width: 95px;
                margin-bottom: 2%;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 570px) and (max-width: 700px) {
            .logo_sps {
                margin-right: 16%;
                margin-bottom: 2%;
                z-index: 1;
                width: 95px;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .fixed-content {
                margin-bottom: 25%;
                margin-top: 25%;
                /* margin: 8%; */
            }

            .logo_sp {
                width: 100px;
                margin-bottom: 3%;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 17%;
                margin-bottom: 2%;
                z-index: 1;
                width: 95px;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 400px) and (max-width: 570px) {
            .logo_sps {
                margin-right: 20%;
                margin-bottom: 3%;
                z-index: 1;
                width: 90px;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .fixed-content {
                margin-bottom: 25%;
                margin-top: 25%;
                /* margin: 8%; */
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .logo_sp {
                width: 95px;
                margin-bottom: 4%;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 21%;
                margin-bottom: 3%;
                z-index: 1;
                width: 90px;
                bottom: 0%;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        @media screen and (min-width: 200px) and (max-width: 400px) {
            .logo_sps {
                margin-right: 23%;
                margin-bottom: 5%;
                z-index: 1;
                width: 90px;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                display: block
            }

            .text_version_app {
                /* margin-bottom: 20px; */
                bottom: -0px;
                text-align: center;
                position: fixed;
                z-index: 1;

            }

            .fixed-content {
                margin-bottom: 25%;
                margin-top: 25%;
                /* margin: 8%; */
            }

            .logo_sp {
                width: 95px;
                margin-bottom: 7%;
                bottom: 0;
                z-index: 1;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }

            .logo_sip {
                margin-left: 24%;
                margin-bottom: 5%;
                width: 90px;
                z-index: 1;
                bottom: 0;
                position: fixed;
                filter: drop-shadow(2px 1px 1px #222);
                display: block
            }
        }

        .img_beras {
            bottom: -5%;
            position: fixed;
            filter: drop-shadow(2px 5px 5px #222);
            left: -4%;
            transform: perspective(400px) rotate3d(1, -180, 0, calc(var(--i, 1) * 8deg));
        }

        .img_beras:hover {
            --i: -1;
        }

        .logo {
            width: 150px;
            /* height: 30px; */
        }

        .location {

            position: relative;
            z-index: 1;
        }

        @media screen and (max-width: 1190px) {
            .logo {
                width: 130px;
            }

            .img_beras {
                bottom: -5%;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                left: -5%;
                transform: perspective(400px) rotate3d(1, -1, 0, calc(var(--i, 1) * 8deg));
            }

            .img_beras:hover {
                --i: -1;
            }
        }

        @media screen and (max-width: 990px) {
            .logo {
                width: 100px;
            }

            .img_beras {
                bottom: -4%;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                width: 300px;
                left: -4%;
                transform: perspective(400px) rotate3d(1, -1, 0, calc(var(--i, 1) * 8deg));
            }

            .img_beras:hover {
                --i: -1;
            }
        }

        @media screen and (max-width: 760px) {
            .logo {
                width: 150px;
            }

            .img_beras {
                bottom: -5%;
                position: fixed;
                filter: drop-shadow(2px 2px 5px #222);
                left: -5%;
                display: none;
                transform: perspective(400px) rotate3d(1, -1, 0, calc(var(--i, 1) * 8deg));
            }

            .img_beras:hover {
                --i: -1;
            }
        }

        .fixed-content:hover {
            zoom: 1.2;
        }
    </style>
</head>

<body>
    <div class="page-wraper">
        <div class="page-content">

            <div class="content-body">
                <div class="stripe_background">
                    <div class="col-12" style="margin-top: 10%; height: 77vh !important; justify-content: center;">
                        <h1 class="text-center" style="position: relative; z-index: 1 !important;">Silahkan Pilih Holding</h1>
                        <div class="row" style="margin:0px; padding: 0;">
                            <!-- Congratulations card -->
                            <div class="col-md-4 col-lg-4">
                                <a href="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/dashboard/holding/sp') }}@else {{ url('dashboard/holding/sp') }} @endif">
                                    <div class="fixed-content" style="border-radius: 10px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
                                        <div class="card" style="height: 200px;">
                                            <div class="card-body">
                                                <figure>
                                                    <blockquote class="blockquote">
                                                        <h4 class="card-title mb-1">CV. SUMBER PANGAN</h4>
                                                    </blockquote>
                                                    <figcaption class="blockquote-footer" style="margin: 0;">
                                                        Lokasi <cite title="Source Title">Pabrik</cite>
                                                        <p style="margin: 0;"><i class="mdi mdi-google-maps"></i>Kabupaten Kediri, Jawa Timur</p>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <img src="{{asset('admin/assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background" data-app-light-img="icons/misc/triangle-light.png" data-app-dark-img="icons/misc/triangle-dark.png" />
                                            <img src="{{ asset('holding/assets/img/logosp.png') }}" class="logo scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 pb-2" alt="view sales" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <a href="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/dashboard/holding/sps') }} @else {{ url('dashboard/holding/sps') }} @endif">
                                    <div class="fixed-content" style="border-radius: 10px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
                                        <div class="card" style="height: 200px; display: block;">
                                            <div class="card-body">
                                                <figure>
                                                    <blockquote class="blockquote">
                                                        <h4 class="card-title mb-1">PT. SURYA PANGAN SEMESTA</h4>
                                                    </blockquote>
                                                    <figcaption class="location blockquote-footer" style="margin: 0;">
                                                        Lokasi <cite title="Source Title">Pabrik</cite>
                                                        <p style="margin: 0;"><i class="mdi mdi-google-maps"></i>Kabupaten Kediri, Jawa Timur</p>
                                                        <p style="margin: 0;"><i class="mdi mdi-google-maps"></i>Kabupaten Ngawi, Jawa Timur</p>
                                                        <p style="margin: 0;"><i class="mdi mdi-google-maps"></i>Kabupaten Subang, Jawa Barat</p>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <img src="{{asset('admin/assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background" data-app-light-img="icons/misc/triangle-light.png" data-app-dark-img="icons/misc/triangle-dark.png" />
                                            <img src="{{ asset('holding/assets/img/logosps.png') }}" class="logo scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 pb-2" width="150" alt="view sales" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <a href="@if(Auth::user()->is_admin =='hrd') {{ url('hrd/dashboard/holding/sip') }} @else {{ url('dashboard/holding/sip') }} @endif">
                                    <div class="fixed-content" style="border-radius: 10px; box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
                                        <div class="card" style="height: 200px; display: block;">
                                            <div class="card-body">
                                                <figure>
                                                    <blockquote class="blockquote">
                                                        <h4 class="card-title mb-1">CV. SURYA INTI PANGAN</h4>
                                                    </blockquote>
                                                    <figcaption class="blockquote-footer" style="margin: 0;">
                                                        Lokasi <cite title="Source Title">Pabrik</cite>
                                                        <p><i class="mdi mdi-google-maps"></i>Makasar, Sulawesi Utara</p>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <img src="{{asset('admin/assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background" data-app-light-img="icons/misc/triangle-light.png" data-app-dark-img="icons/misc/triangle-dark.png" />
                                            <img src="{{ asset('holding/assets/img/logosipbaru.png') }}" class="logo scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 pb-2" width="200" alt="view sales" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <img src="{{ asset('holding/assets/img/produk_beras.png') }}" width="350" class="img_beras" alt="auth-tree" />
            </div>
        </div>
        <!--**********************************
    Scripts
***********************************-->
        <script src="{{ asset('assets/assets_users/js/jquery.js') }}"></script>
        <script src="{{ asset('assets/assets_users/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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

            $("document").ready(function() {
                $('#password_show').change(function() {
                    var x = document.getElementById("dz-password");
                    var ok = $(this).is(':checked');
                    if (ok == true) {
                        x.type = "text";
                    } else {
                        x.type = "password";

                    }
                });
            });
        </script>
</body>

</html>