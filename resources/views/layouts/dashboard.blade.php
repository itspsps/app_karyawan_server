<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    {{-- logo --}}
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/fullcalendar/main.css') }}">

    <link rel="shorcut icon" href="{{ url('public/assets/img/avatar-1.png') }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="{{ url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/adminlte/dist/css/adminlte.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/summernote/summernote-bs4.min.css') }}">

    {{-- select picker --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- timepicker --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css') }}">

    {{-- maps leaflet js --}}
    <link rel="stylesheet" href="{{ url('https://unpkg.com/leaflet@1.8.0/dist/leaflet.css') }}" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="{{ url('https://unpkg.com/leaflet@1.8.0/dist/leaflet.js') }}" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <style type="text/css">
        body {
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            line-height: 1.4;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            height: 100%;
            /* background: #ffffff; */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .content-wrapper {
            /* padding: 0;
                margin: 0; */
            background: url('dist/img/bgWave.svg') center bottom / 100% no-repeat fixed, url('/dist/img/topWave.svg') center top / 100% no-repeat fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .imgtgl {
            background: url("dist/img/buttonBlue.svg") 0% 0% / cover rgba(71, 188, 188, 0.07);
            border-radius: 6px;
            font-size: 10px;
            color: rgb(71, 188, 188);
        }

        .imgjam {
            background-color: rgb(255, 250, 242);
            background-image: url("dist/img/buttonOrange.svg");
            background-size: cover;
            border-radius: 6px;
            font-size: 10px;
            color: rgb(254, 153, 0);
        }
    </style>


</head>
<!-- <body class="hold-transition sidebar-mini layout-fixed"> -->

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            @if($holding=='sp')
            <img src="{{ asset('assets/img/logosp.png') }}" alt="SP" height="60" width="60">
            @elseif($holding=='sps')
            <img src="{{ asset('holding/assets/img/logosps.png') }}" alt="SP" height="60" width="60">
            @elseif($holding=='sip')
            <img src="{{ asset('holding/assets/img/logosip.png') }}" alt="SP" height="60" width="60">
            @endif
        </div>

        @include('partials.topbar')

        @include('partials.sidebar')


        <!-- Content Wrapper. Contains page content -->
        <!-- <div class="content-wrapper" style="background-image: url({{ url('assets/img/absenbg.jpg') }})"> -->
        <div class="content-wrapper content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <!-- <center class="mb-2">
            <h2 class="fw-bold" style="color: rgb(255, 41, 4)">{{ $title }}</h2>
        </center> -->
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                @yield('isi')
            </section>

        </div>
        <!-- /.content-wrapper -->

        @include('partials.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    @include('partials.script')
    @yield('js')
    @include('sweetalert::alert')

</body>

</html>