<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>HRD-APP | ADMIN</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    @if($holding=='sp')
    <link rel="icon" type="image/x-icon" href="{{asset('holding/assets/img/logosp.png')}}" />
    @elseif($holding=='sps')
    <link rel="icon" type="image/x-icon" href="{{asset('holding/assets/img/logosps.png')}}" />
    @else
    <link rel="icon" type="image/x-icon" href="{{asset('holding/assets/img/logosip.png')}}" />
    @endif
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

    <link rel="preload" href="{{asset('admin/assets/vendor/fonts/materialdesignicons.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="preload" href="{{asset('admin/assets/vendor/libs/node-waves/node-waves.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />

    <!-- Core CSS -->
    <link rel="preload" href="{{asset('admin/assets/vendor/css/core.css')}}" class="template-customizer-core-css" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="preload" href="{{asset('admin/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="preload" href="{{asset('admin/assets/css/demo.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />

    <!-- Vendors CSS -->
    <link rel="preload" href="{{asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="preload" href="{{asset('admin/assets/vendor/libs/apex-charts/apex-charts.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    @yield('css')
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{asset('admin/assets/vendor/js/helpers.js')}}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('admin/assets/js/config.js')}}"></script>
    <style>
        .menu_bg_color {
            background-color: rgba(58, 53, 65, 0.08);
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('admin.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('admin.layouts.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('isi')

                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                                <div class="text-body mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with <span class="text-danger">IT SP & SPS
                                        <a href="mailto:it.dev.sps@gmail.com" target="_blank" class="footer-link fw-medium">it.dev.sps@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('admin/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/node-waves/node-waves.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('admin/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('admin/assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('admin/assets/js/dashboards-analytics.js')}}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @yield('js')


</body>

</html>