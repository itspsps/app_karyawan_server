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
        /* --- ELEGANT BASE STYLES --- */
        :root {
            --primary-purple: #673ab7;
            /* Your original primary color */
            --light-purple: #c8b0f4;
            --text-dark: #343a40;
            --text-light: #f8f9fa;
            --card-bg: #ffffff;
        }

        .page-wraper {
            min-height: 100vh;
            background-color: #f0f0f0;
            /* Light background for the overall page */
        }

        /* --- ENHANCED BACKGROUND (HEADER) --- */
        .stripe_background {
            background: linear-gradient(0deg, var(--light-purple) 0%, var(--primary-purple) 100%);
            color: var(--text-light);
            padding: 80px 20px 150px 20px;
            /* More vertical padding */
            position: relative;
            min-height: 40vh;
            overflow: hidden;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            z-index: 1;
            display: flex;
            /* Use flexbox for easy centering */
            align-items: center;
            /* Center vertically */
            justify-content: center;
            /* Center horizontally */
        }

        /* Removing the old ::after for stripe_background as we're rebuilding the wave below */
        .stripe_background::after {
            display: none;
            /* Hide the previous, simpler ::after */
        }

        /* New: Wavy bottom for the header background */
        .stripe_background::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 120px;
            /* Height of the wave */
            background-color: var(--card-bg);
            /* Match content background */
            /* More complex SVG-like wave effect */
            clip-path: ellipse(150% 100% at 50% 100%);
            z-index: 2;
            /* Ensure it overlaps the gradient background */
        }

        .holding-header-content {
            /* Renamed from .holding-header for clarity */
            position: relative;
            z-index: 3;
            /* Ensure text is above the wave and other elements */
            margin-top: -50px;
            /* Adjust if needed to position title better with the wave */
            text-align: center;
            width: 100%;
            max-width: 800px;
            /* Limit width for better readability */
            padding: 20px 30px;
            border-radius: 15px;
            /* Subtle frosted glass or soft white background for the title */
            background: rgba(255, 255, 255, 0.15);
            /* Slightly transparent white */
            backdrop-filter: blur(5px);
            /* Frosted glass effect */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            /* Soft shadow for depth */
            animation: fadeIn 1s ease-out forwards;
            /* Simple animation */
        }

        .holding-title {
            font-family: 'Poppins', sans-serif;
            /* Example: Use a modern font, ensure it's loaded */
            font-weight: 700;
            font-size: 3.2rem;
            /* Larger and bolder */
            letter-spacing: 2px;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            /* Stronger, softer shadow for text */
            color: #ffffff;
            /* Pure white text for contrast */
            margin: 0;
            /* Remove default margin */
            padding: 10px 0;
            line-height: 1.2;
        }

        /* --- CARD STYLES (The core "elegance") --- */

        /* Container for the cards to position them over the background */
        .card-container-row {
            position: relative;
            margin-top: -120px;
            /* Pull the cards up further to align with the wave */
            z-index: 10;
        }

        .holding-card-elegant {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            /* Softer rounded corners */
            background-color: var(--card-bg);
            /* Subtle, multi-level box shadow for depth */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 0 5px rgba(0, 0, 0, 0.05);
            min-height: 220px;
            position: relative;
            overflow: hidden;
        }

        .holding-card-elegant a {
            color: inherit;
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .holding-card-elegant:hover {
            transform: translateY(-8px) scale(1.02);
            /* More noticeable lift and subtle growth */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            /* Stronger shadow on hover */
        }

        .card-title-elegant {
            font-weight: 600;
            color: var(--primary-purple);
            /* Highlight the title with the primary color */
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .location-cite {
            font-size: 0.9rem;
            color: #6c757d;
            font-style: normal;
            margin-top: 10px;
        }

        .location-text {
            color: var(--text-dark);
            font-size: 0.95rem;
            margin: 5px 0 0 0;
            display: flex;
            align-items: flex-start;
        }

        .location-text i {
            margin-right: 8px;
            color: var(--light-purple);
            /* Icon color matching the header */
            font-size: 1.1rem;
        }

        /* Logo/Image Overlay */
        .logo-overlay {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 60px;
            /* Smaller, modern logo placement */
            height: 60px;
            opacity: 0.8;
        }

        .logo-overlay img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .holding-card-elegant:hover .logo-overlay {
            opacity: 0.4;
            /* Slightly more visible on hover */
        }

        /* Footer Logos (simplified for all screen sizes) */
        .footer-logos {
            position: fixed;
            bottom: 10px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 5%;
            z-index: 20;
        }

        .footer-logos img {
            width: 70px;
            /* Standard size for all small logos */
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.4));
        }

        .text_version_app {
            position: fixed;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            color: #6c757d;
            z-index: 20;
        }

        .app-footer {
            width: 100%;
            padding: 20px 0;
            margin-top: 50px;
            /* Memberi jarak dari cards di atasnya */
            background-color: #f8f9fa;
            /* Warna latar belakang abu-abu muda yang bersih */
            border-top: 1px solid #e9ecef;
            /* Garis tipis di atas untuk pemisah */
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-logo-container {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .footer-logo {
            width: 60px;
            /* Ukuran logo yang lebih kecil dan konsisten */
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.2));
            transition: transform 0.3s ease;
        }

        .footer-logo:hover {
            transform: scale(1.05);
        }

        .text_version_app {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* Hidden elements for a cleaner look */
        .img_beras,
        .scaleX-n1-rtl {
            display: none !important;
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            .stripe_background {
                padding-top: 60px;
                padding-bottom: 100px;
            }

            .holding-title {
                font-size: 2.2rem;
                letter-spacing: 1px;
            }

            .holding-header-content {
                margin-top: -30px;
                padding: 15px 20px;
            }

            .card-container-row {
                margin-top: -80px;
            }

            .stripe_background::before {
                height: 80px;
            }
        }

        @media (max-width: 576px) {
            .holding-title {
                font-size: 1.8rem;
            }

            .holding-header-content {
                padding: 10px 15px;
            }
        }
    </style>

</head>

<body>
    <div class="page-wraper">
        <div class="page-content">
            @include('sweetalert::alert')
            <div class="content-body">
                <div class="stripe_background">
                    <div class="container d-flex justify-content-center"> {{-- Added d-flex and justify-content-center here --}}
                        <div class="holding-header-content"> {{-- New wrapper for the title styling --}}
                            <h1 class="holding-title">SILAHKAN PILIH HOLDING</h1>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row card-container-row g-4 justify-content-center">
                        @foreach($holding as $data)
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <a href="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/dashboard/option/'.$data->holding_code) }}@else {{ url('dashboard/option/'.$data->holding_code) }} @endif" class="text-decoration-none">
                                <div class="card holding-card-elegant">
                                    <div class="card-body">
                                        <h4 class="card-title-elegant mb-1">{{$data->holding_name}}</h4>
                                        <figcaption class="location-cite">
                                            Lokasi Holding
                                        </figcaption>
                                        @foreach($data->Site as $site)
                                        <p class="location-text">
                                            <i class="mdi mdi-map-marker-outline"></i>
                                            <span>{{$site->site_alamat}}</span>
                                        </p>
                                        @endforeach
                                    </div>
                                    <div class="logo-overlay">
                                        <img src="{{ asset('holding/assets/img/'.$data->holding_image) }}" alt="{{$data->holding_name}} Logo" />
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <footer class="app-footer">
                    <p class="text_version_app">Versi Aplikasi <span style="font-weight: 700;">V1.0.0</span></p>
                </footer>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/assets_users/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/assets_users/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('assets/assets_users/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- <script src="{{ asset('/sw.js') }}"></script> -->
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