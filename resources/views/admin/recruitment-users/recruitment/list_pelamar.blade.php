@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css" rel="stylesheet" />

    <style type="text/css">
        .my-swal {
            z-index: X;
        }
    </style>
    <style type="text/css">
        .left-profile-card .user-profile {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: auto;
            margin-bottom: 20px;
        }

        .left-profile-card h3 {
            font-size: 18px;
            margin-bottom: 0;
            font-weight: 700;
        }

        .left-profile-card p {
            margin-bottom: 5px;
        }

        .left-profile-card .progress-bar {
            /* background-color: var(--main-color); */
        }

        .personal-info {
            margin-bottom: 30px;
        }

        .personal-info .personal-list {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .personal-list li {
            margin-bottom: 5px;
        }

        .personal-info h3 {
            margin-bottom: 10px;
        }

        .personal-info p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .personal-info i {
            font-size: 15px;
            color: var(--main-color);
            ;
            margin-right: 15px;
            width: 18px;
        }

        .skill {
            margin-bottom: 30px;
        }

        .skill h3 {
            margin-bottom: 15px;
        }

        .skill p {
            margin-bottom: 5px;
        }


        .right-profile-card .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff;
            /* background-color: var(--main-color); */
        }

        .right-profile-card .nav>li {
            float: left;
            margin-right: 10px;
        }

        .right-profile-card .nav-pills .nav-link {
            border-radius: 26px;
        }

        .right-profile-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .right-profile-card h4 {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .right-profile-card i {
            font-size: 15px;
            margin-right: 10px;
        }

        .right-profile-card .work-container {
            /* border-bottom: 1px solid #eee; */
            margin-bottom: 20px;
            padding: 10px;
        }


        /*timeline*/

        .img-circle {
            border-radius: 50%;
        }

        .timeline-centered {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-centered:before,
        .timeline-centered:after {
            content: " ";
            display: table;
        }

        .timeline-centered:after {
            clear: both;
        }

        .timeline-centered:before,
        .timeline-centered:after {
            content: " ";
            display: table;
        }

        .timeline-centered:after {
            clear: both;
        }

        .timeline-centered:before {
            content: '';
            position: absolute;
            display: block;
            width: 4px;
            /* background: #f5f5f6; */
            /*left: 50%;*/
            top: 20px;
            bottom: 20px;
            margin-left: 30px;
        }

        .timeline-centered .timeline-entry {
            position: relative;
            /*width: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        float: right;*/
            margin-top: 5px;
            margin-left: 30px;
            margin-bottom: 10px;
            clear: both;
        }

        .timeline-centered .timeline-entry:before,
        .timeline-centered .timeline-entry:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry:after {
            clear: both;
        }

        .timeline-centered .timeline-entry:before,
        .timeline-centered .timeline-entry:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry:after {
            clear: both;
        }

        .timeline-centered .timeline-entry.begin {
            margin-bottom: 0;
        }

        .timeline-centered .timeline-entry.left-aligned {
            float: left;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner {
            margin-left: 0;
            margin-right: -18px;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-time {
            left: auto;
            right: -100px;
            text-align: left;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-icon {
            float: right;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label {
            margin-left: 0;
            margin-right: 70px;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label:after {
            left: auto;
            right: 0;
            margin-left: 0;
            margin-right: -9px;
            -moz-transform: rotate(180deg);
            -o-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .timeline-centered .timeline-entry .timeline-entry-inner {
            position: relative;
            margin-left: -20px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner:before,
        .timeline-centered .timeline-entry .timeline-entry-inner:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner:after {
            clear: both;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner:before,
        .timeline-centered .timeline-entry .timeline-entry-inner:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner:after {
            clear: both;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time {
            position: absolute;
            left: -100px;
            text-align: right;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time>span {
            display: block;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time>span:first-child {
            font-size: 15px;
            font-weight: bold;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time>span:last-child {
            font-size: 12px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
            /* background: #fff; */
            color: #737881;
            display: block;
            width: 40px;
            height: 40px;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            text-align: center;
            -moz-box-shadow: 0 0 0 5px #f5f5f6;
            -webkit-box-shadow: 0 0 0 5px #f5f5f6;
            box-shadow: 0 0 0 5px #f5f5f6;
            line-height: 40px;
            font-size: 15px;
            float: left;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-primary {
            background-color: #303641;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-secondary {
            background-color: #ee4749;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-success {
            background-color: #00a651;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-info {
            background-color: #21a9e1;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-warning {
            background-color: #fad839;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-danger {
            background-color: #cc2424;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label {
            position: relative;
            background: #f5f5f6;
            padding: 1em;
            margin-left: 60px;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label:after {
            content: '';
            display: block;
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 9px 9px 9px 0;
            border-color: transparent #f5f5f6 transparent transparent;
            left: 0;
            top: 10px;
            margin-left: -9px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p {
            color: #737881;
            font-size: 12px;
            margin: 0;
            line-height: 1.428571429;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p+p {
            margin-top: 15px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 a {
            color: #303641;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 span {
            -webkit-opacity: .6;
            -moz-opacity: .6;
            opacity: .6;
            -ms-filter: alpha(opacity=60);
            filter: alpha(opacity=60);
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/@icon/entypo@1.0.3/entypo.css" rel="stylesheet">
@endsection
@section('isi')
    @include('sweetalert::alert')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Transactions -->
            <div class="col-lg-12">

                <div class="skills layout-spacing ">
                    <div class="widget-content widget-content-area bg-white p-3">
                        <h3 class="">Data Pelamar</h3>
                        <br>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                    role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                    Belum Dilihat
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1"
                                    role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">

                                    Kandidat
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="icon-tab-3" data-bs-toggle="tab" href="#icon-tabpanel-3"
                                    role="tab" aria-controls="icon-tabpanel-2" aria-selected="false">

                                    Daftar Tunggu
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2"
                                    role="tab" aria-controls="icon-tabpanel-1" aria-selected="false">
                                    Ditolak
                                </a>
                            </li>

                        </ul>
                        <div class="tab-content pt-5" id="tab-content">
                            <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel"
                                aria-labelledby="icon-tab-0">
                                <div>
                                    <div class="table table-striped py-3">
                                        <table class="table" id="table_pelamar0" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Pelamar</th>
                                                    <th>Nomor Whatsapp</th>
                                                    <th>Status</th>
                                                    <th>Lihat CV</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($user_meta as $user)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $user->AuthLogin->recruitmentCV->nama_lengkap }}</td>
                                                        <td>{{ $user->AuthLogin->nomor_whatsapp }}</td>
                                                        <td class="bg-info text-white">
                                                            @if ($user->status == '0')
                                                                Belum Dilihat
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ url('/pg/pelamar-detail/' . $user->id . '/' . $holding . '') }}"
                                                                type="button" class="btn btn-sm btn-info">
                                                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                Detail&nbsp;CV
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                                <div>
                                    <div class="table table-striped py-3">
                                        <table class="table" id="table_pelamar2" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Pelamar</th>
                                                    <th>Nomor Whatsapp</th>
                                                    <th>Tanggal Wawancara</th>
                                                    <th>Tempat Wawancara</th>
                                                    <th>Waktu Wawancara</th>
                                                    <th>Konfirmasi Wawancara</th>
                                                    <th>Status</th>
                                                    <th>Lihat CV</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($user_kandidat as $user)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $user->AuthLogin->recruitmentCV->nama_lengkap }}</td>
                                                        <td>{{ $user->AuthLogin->nomor_whatsapp }}</td>
                                                        <td>{{ $user->tanggal_wawancara }}</td>
                                                        <td>{{ $user->tempat_wawancara }}</td>
                                                        <td>{{ $user->waktu_wawancara }}</td>
                                                        <td>
                                                            @if ($user->feedback != '1')
                                                                <div class="bg-warning text-white p-1">Menunggu Konfirmasi
                                                                </div>
                                                            @elseif ($user->feedback == '1')
                                                                <div class="bg-info text-white p-1">Bersedia Wawancara</div>
                                                            @endif
                                                        </td>
                                                        <td class="bg-success text-white">
                                                            @if ($user->status == '1')
                                                                Kandidat
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ url('/pg/pelamar-detail/' . $user->id . '/' . $holding . '') }}"
                                                                type="button" class="btn btn-sm btn-info">
                                                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                Detail&nbsp;CV
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="icon-tabpanel-3" role="tabpanel" aria-labelledby="icon-tab-3">
                                <div>
                                    <div class="table table-striped py-3">
                                        <table class="table" id="table_pelamar3" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Pelamar</th>
                                                    <th>Nomor Whatsapp</th>
                                                    <th>Status</th>
                                                    <th>Rubah Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($user_wait as $user)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $user->AuthLogin->recruitmentCV->nama_lengkap }}</td>
                                                        <td>{{ $user->AuthLogin->nomor_whatsapp }}</td>
                                                        <td class="bg-secondary text-white">
                                                            @if ($user->status == '2')
                                                                Daftar Tunggu
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ url('/pg/pelamar-detail/' . $user->id . '/' . $holding . '') }}"
                                                                type="button" class="btn btn-sm btn-info">
                                                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                Detail&nbsp;CV
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                                <div>
                                    <div class="table table-striped py-3">
                                        <table class="table" id="table_pelamar1" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Pelamar</th>
                                                    <th>Nomor Whatsapp</th>
                                                    <th>Alasan</th>
                                                    <th>Status</th>
                                                    <th>Ubah Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($user_reject as $user)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $user->AuthLogin->recruitmentCV->nama_lengkap }}</td>
                                                        <td>{{ $user->AuthLogin->nomor_whatsapp }}</td>
                                                        <td>{{ $user->alasan }}</td>
                                                        <td class="bg-danger text-white">
                                                            @if ($user->status == '3')
                                                                Ditolak
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ url('/pg/pelamar-detail/' . $user->id . '/' . $holding . '') }}"
                                                                type="button" class="btn btn-sm btn-info">
                                                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                Ubah&nbsp;Status
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
            <!--/ Data Tables -->
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $('#table_pelamar0').DataTable();
        $('#table_pelamar1').DataTable();
        $('#table_pelamar2').DataTable();
        $('#table_pelamar3').DataTable();
    </script>
@endsection
