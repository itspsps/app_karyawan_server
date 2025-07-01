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
                    <div class="widget-content widget-content-area bg-white p-5">
                        <h3>Detail CV</h3>
                        <div class="row ">
                            <div class="col-4 py-3">
                                <div></div>
                                <img src="{{ url_karir() . '/storage/file_pp/' . $data_cv->AuthLogin->recruitmentCV->file_pp }}"
                                    style="max-height: 400px; max-width: 340px;">
                                <div class="fw-bold py-3">Email : {{ $data_cv->AuthLogin->email }}</div>
                            </div>
                            <div class="col-lg">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab"
                                            href="#icon-tabpanel-0" role="tab" aria-controls="icon-tabpanel-0"
                                            aria-selected="true">
                                            Data Diri
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1"
                                            role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">

                                            Pendidikan
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2"
                                            role="tab" aria-controls="icon-tabpanel-1" aria-selected="false">

                                            Riwayat Kerja
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="icon-tab-3" data-bs-toggle="tab" href="#icon-tabpanel-3"
                                            role="tab" aria-controls="icon-tabpanel-2" aria-selected="false">
                                            Keahlian
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="icon-tab-4" data-bs-toggle="tab" href="#icon-tabpanel-4"
                                            role="tab" aria-controls="icon-tabpanel-3" aria-selected="false">
                                            Keputusan HRD
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="tab-content">
                                    <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel"
                                        aria-labelledby="icon-tab-0">
                                        <div class="table table-striped">
                                            <table class="table" id="table_pelamar3" style="width: 100%;">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="fw-bold">profil</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>

                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    <tr>
                                                        <td class="fw-bold"><small>NAMA LENGKAP</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->nama_lengkap }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>TEMPAT, TANGGAL LAHIR</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->tempat_lahir }},
                                                            {{ $data_cv->AuthLogin->recruitmentCV->tanggal_lahir }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>NIK</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->nik }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>AGAMA</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->agama }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>JENIS KELAMIN</small></td>
                                                        <td>:</td>
                                                        <td>
                                                            @if ($data_cv->AuthLogin->recruitmentCV->jenis_kelamin == 'Laki-laki')
                                                                LAKI - LAKI
                                                            @else
                                                                PEREMPUAN
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>STATUS PERNIKAHAN</small></td>
                                                        <td>:</td>
                                                        <td>
                                                            @if ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'lajang')
                                                                LAJANG
                                                            @elseif ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'menikah')
                                                                MENIKAH
                                                            @elseif ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'cerai_hidup')
                                                                CERAI HIDUP
                                                            @elseif ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'cerai_mati')
                                                                CERAI MATI
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>JUMLAH ANAK</small></td>
                                                        <td>:</td>
                                                        <td>
                                                            {{ $data_cv->AuthLogin->recruitmentCV->jumlah_anak }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>HOBI</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->hobi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>KTP</small></td>
                                                        <td>:</td>
                                                        <td><a href="{{ url_karir() . '/storage/ktp/' . $data_cv->AuthLogin->recruitmentCV->ktp }}"
                                                                type="button" class="btn btn-sm btn-info" target="_blank">
                                                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                LIHAT KTP
                                                            </a></td>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="fw-bold">ALAMAT</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    <tr>
                                                        <td class="fw-bold"><small>ALAMAT SESUAI KTP</small></td>
                                                        <td>:</td>
                                                        <td>
                                                            {{ $data_cv->AuthLogin->recruitmentCV->nama_jalan_ktp }},
                                                            RT {{ $data_cv->AuthLogin->recruitmentCV->rt_ktp }},
                                                            RW {{ $data_cv->AuthLogin->recruitmentCV->rw_ktp }},
                                                            {{ $data_cv->AuthLogin->recruitmentCV->desaKTP->name }},
                                                            {{ $data_cv->AuthLogin->recruitmentCV->kecamatanKTP->name }},
                                                            {{ $data_cv->AuthLogin->recruitmentCV->kabupatenKTP->name }},
                                                            {{ $data_cv->AuthLogin->recruitmentCV->provinsiKTP->name }},
                                                            KODE POS :
                                                            {{ $data_cv->AuthLogin->recruitmentCV->kode_pos_ktp }}

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>ALAMAT SAAT INI</small></td>
                                                        <td>:</td>
                                                        <td>
                                                            @if ($data_cv->AuthLogin->recruitmentCV->alamat_sekarang == 'sama')
                                                                {{ $data_cv->AuthLogin->recruitmentCV->nama_jalan_ktp }},
                                                                RT {{ $data_cv->AuthLogin->recruitmentCV->rt_ktp }},
                                                                RW {{ $data_cv->AuthLogin->recruitmentCV->rw_ktp }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->desaKTP->name }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->kecamatanKTP->name }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->kabupatenKTP->name }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->provinsiKTP->name }},
                                                                KODE POS :
                                                                {{ $data_cv->AuthLogin->recruitmentCV->kode_pos_ktp }}
                                                            @else
                                                                {{ $data_cv->AuthLogin->recruitmentCV->nama_jalan_now }},
                                                                RT {{ $data_cv->AuthLogin->recruitmentCV->rt_now }},
                                                                RW {{ $data_cv->AuthLogin->recruitmentCV->rw_now }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->desaNOW->name }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->kecamatanNOW->name }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->kabupatenNOW->name }},
                                                                {{ $data_cv->AuthLogin->recruitmentCV->provinsiNOW->name }},
                                                                KODE POS :
                                                                {{ $data_cv->AuthLogin->recruitmentCV->kode_pos_now }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="fw-bold">KONTAK</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    <tr>
                                                        <td class="fw-bold"><small>NOMOR WHATSAPP</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->nomor_whatsapp }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>LAMA NOMOR INI DIGUNAKAN</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->lama_nomor_whatsapp }}
                                                            TAHUN
                                                            @if ($data_cv->AuthLogin->recruitmentCV->lama_nomor_bulan == null)
                                                                -
                                                            @else
                                                                {{ $data_cv->AuthLogin->recruitmentCV->lama_nomor_bulan }}
                                                                BULAN
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>KONTAK DARURAT</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->nomor_whatsapp_darurat }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>PEMILIK KONTAK DARURAT</small></td>
                                                        <td>:</td>
                                                        <td>{{ $data_cv->AuthLogin->recruitmentCV->pemilik_nomor_whatsapp }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel"
                                        aria-labelledby="icon-tab-1">
                                        <table class="table" id="table_pelamar3" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th class="fw-bold">dokumen pendidikan</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <tr>
                                                    <td class="fw-bold"><small>IJAZAH TERAKHIR</small></td>
                                                    <td>:</td>
                                                    <td><a href="{{ url_karir() . '/storage/ijazah/' . $data_cv->AuthLogin->recruitmentCV->ijazah }}"
                                                            type="button" class="btn btn-sm btn-info" target="_blank">
                                                            <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                            LIHAT IJAZAH
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold"><small>TRANSKRIP NILAI</small></td>
                                                    <td>:</td>
                                                    <td><a href="{{ url_karir() . '/storage/transkrip_nilai/' . $data_cv->AuthLogin->recruitmentCV->transkrip_nilai }}"
                                                            type="button" class="btn btn-sm btn-primary"
                                                            target="_blank">
                                                            <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                            LIHAT TRANSKRIP
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($pendidikan as $pp)
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="fw-bold">riwayat pendidikan ({{ $i++ }})</th>
                                                        <th class="fw-bold"></th>
                                                        <th class="fw-bold"></th>
                                                    </tr>

                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    <tr>
                                                        <td class="fw-bold"><small>NAMA INSTITUSI</small></td>
                                                        <td>:</td>
                                                        <td>{{ $pp->institusi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>JURUSAN</small></td>
                                                        <td>:</td>
                                                        <td>{{ $pp->jurusan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>JENJANG</small></td>
                                                        <td>:</td>
                                                        <td>{{ $pp->jenjang }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold"><small>PERIODE</small></td>
                                                        <td>:</td>
                                                        <td>{{ $pp->tanggal_masuk }} - {{ $pp->tanggal_keluar }}</td>
                                                    </tr>
                                                </tbody>
                                            @endforeach
                                        </table>

                                    </div>
                                    <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel"
                                        aria-labelledby="icon-tab-2">
                                        <table class="table" id="table_pelamar3" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th class="fw-bold">BACKGROUND CHECK</th>
                                                    <th class="fw-bold"></th>
                                                    <th class="fw-bold"></th>
                                                </tr>

                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <tr>
                                                    <td class="fw-bold"><small>BERSEDIA DILAKUKAN BACKGROUND
                                                            CHECK?</small>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        @if ($data_cv->AuthLogin->recruitmentCV->persetujuan == '1')
                                                            SETUJU
                                                        @else
                                                            TIDAK SETUJU
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @if ($pekerjaan_count == 0)
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="fw-bold">RIWAYAT PEKERJAAN
                                                        </th>
                                                        <th class="fw-bold"></th>
                                                        <th class="fw-bold"></th>
                                                    </tr>

                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    <tr>
                                                        <td colspan="3" class="fw-bold" style="text-align: center">
                                                            <small>PELAMAR
                                                                TIDAK MEMASUKKAN
                                                                RIWAYAT PEKERJAAN</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            @else
                                                @php
                                                    $j = 1;
                                                @endphp
                                                @foreach ($pekerjaan as $rr)
                                                    <thead class="table-primary">
                                                        <tr>
                                                            <th class="fw-bold">RIWAYAT PEKERJAAN ({{ $j++ }})
                                                            </th>
                                                            <th class="fw-bold"></th>
                                                            <th class="fw-bold"></th>
                                                        </tr>

                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                        <tr>
                                                            <td class="fw-bold"><small>PERUSAHAAN</small></td>
                                                            <td>:</td>
                                                            <td>{{ $rr->nama_perusahaan }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>ALAMAT PERUSAHAAN</small></td>
                                                            <td>:</td>
                                                            <td>{{ $rr->alamat_perusahaan }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>POSISI</small></td>
                                                            <td>:</td>
                                                            <td>{{ $rr->posisi }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>GAJI TERAKHIR</small></td>
                                                            <td>:</td>
                                                            <td>{{ rupiah($rr->gaji) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>TANGGAL MASUK</small></td>
                                                            <td>:</td>
                                                            <td>{{ Carbon\Carbon::parse($rr->tanggal_masuk)->format('d M Y') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>TANGGAL KELUAR</small></td>
                                                            <td>:</td>
                                                            <td>{{ Carbon\Carbon::parse($rr->tanggal_keluar)->format('d M Y') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>SURAT KETERANGAN</small></td>
                                                            <td>:</td>
                                                            <td>
                                                                @if ($rr->surat_keterangan == null)
                                                                    -
                                                                @else
                                                                    <a href="{{ url_karir() . '/storage/surat_keterangan/' . $rr->surat_keterangan }}"
                                                                        type="button" class="btn btn-sm btn-info"
                                                                        target="_blank">
                                                                        <i
                                                                            class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                        LIHAT SURAT KETERANGAN
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>KONTAK REFERENSI</small></td>
                                                            <td>:</td>
                                                            <td>{{ $rr->nomor_referensi }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"><small>JABATAN REFERENSI</small></td>
                                                            <td>:</td>
                                                            <td>{{ $rr->jabatan_referensi }}</td>
                                                        </tr>
                                                    </tbody>
                                                @endforeach
                                            @endif

                                        </table>

                                    </div>
                                    <div class="tab-pane" id="icon-tabpanel-3" role="tabpanel"
                                        aria-labelledby="icon-tab-3">
                                        <table class="table" id="table_pelamar3" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th class="fw-bold">No</th>
                                                    <th class="fw-bold">KEAHLIAN</th>
                                                    <th class="fw-bold">DOKUMEN KEAHLIAN</th>
                                                </tr>

                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @if ($keahlian_count == 0)
                                                    <tr>
                                                        <td colspan="3" class="fw-bold" style="text-align: center">
                                                            <small>PELAMAR
                                                                TIDAK MEMASUKKAN
                                                                KEAHLIAN</small>
                                                        </td>
                                                    </tr>
                                                @else
                                                    @php
                                                        $k = 1;
                                                    @endphp
                                                    @foreach ($keahlian as $kk)
                                                        <tr>
                                                            <td class="fw-bold"><small>{{ $k++ }}</small></td>
                                                            <td class="fw-bold"><small>{{ $kk->keahlian }}</small></td>
                                                            <td class="fw-bold">
                                                                <a href="{{ url_karir() . '/storage/file_keahlian/' . $kk->file_keahlian }}"
                                                                    type="button" class="btn btn-sm btn-info"
                                                                    target="_blank">
                                                                    <i
                                                                        class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                                                    LIHAT DOKUMEN KEAHLIAN
                                                                </a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="icon-tabpanel-4" role="tabpanel"
                                        aria-labelledby="icon-tab-4">
                                        <form method="post" action="{{ url('/pg/pelamar-detail-ubah/' . $holding) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-floating form-floating-outline py-3">
                                                <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status" name="status" autofocus value="{{ old('status') }}">
                                                    <option value="" selected>PILIH STATUS</option>
                                                    <option value="1">KANDIDAT</option>
                                                    <option value="2">DAFTAR TUNGGU</option>
                                                    <option value="3">DITOLAK</option>
                                                </select>
                                                <label for="penempatan">KEPUTUSAN HRD</label>
                                                @error('status')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-floating form-floating-outline py-3">
                                                <input type="date" id="tanggal_wawancara" name="tanggal_wawancara"
                                                    class="form-control @error('tanggal_wawancara') is-invalid @enderror"
                                                    placeholder="Tanggal" value="{{ old('tanggal_wawancara') }}" />
                                                <label for="bagian_recruitment">TANGGAL WAWANCARA</label>
                                            </div>
                                            @error('tanggal_wawancara')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label for="bagian_recruitment px-2"><small>TEMPAT WAWANCARA</small></label>
                                            <div class="form-floating form-floating-outline mb-2">

                                                <input type="text" id="tempat_wawancara" name="tempat_wawancara"
                                                    class="form-control @error('tempat_wawancara') is-invalid @enderror"
                                                    {{-- placeholder="TEMPAT WAWANCARA" --}} value="{{ old('tempat_wawancara') }}" />
                                            </div>
                                            @error('tempat_wawancara')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label for="bagian_recruitment"><small>WAKTU (JAM)
                                                    WAWANCARA</small></label>
                                            <div class="form-floating form-floating-outline ">

                                                <input type="text" id="waktu_wawancara" name="waktu_wawancara"
                                                    class="form-control @error('waktu_wawancara') is-invalid @enderror"
                                                    {{-- placeholder="TEMPAT WAWANCARA" --}} value="{{ old('waktu_wawancara') }}" />
                                            </div>
                                            @error('waktu_wawancara')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <input type="hidden" name="recruitment_user_id"
                                                value="{{ $data_cv->id }}">
                                            <div class="py-4">
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    Ubah&nbsp;Status
                                                </button>
                                            </div>

                                        </form>
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
@endsection
