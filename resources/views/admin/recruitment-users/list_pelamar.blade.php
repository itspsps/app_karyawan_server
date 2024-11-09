@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
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
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DATA PELAMAR</h5>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <hr class="my-5">
                    <hr class="my-5"> -->
                    <!-- <button type="button" class="btn btn-sm btn-success waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_import_inventaris"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button> -->
                    <div class="modal fade" id="modal_lihat_cv" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class=" modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle"></h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-4 ">
                                                <div class="card left-profile-card">
                                                    <div class="card-body">
                                                        <div class="text-center">
                                                            <img src="" id="show_imgpp" alt class="user-profile">
                                                            <h6 id="show_nama_pelamar1" style="font-weight: bold"></h6>
                                                            {{-- <p>World of Internet</p> --}}
                                                            <div class="d-flex align-items-center justify-content-center mb-3">
                                                                <i class="fas fa-star text-info"></i>
                                                                <i class="fas fa-star text-info"></i>
                                                                <i class="fas fa-star text-info"></i>
                                                                <i class="fas fa-star text-info"></i>
                                                                <i class="fas fa-star text-info"></i>
                                                            </div>
                                                        </div>
                                                        <div class="personal-info">
                                                            <h5 style="font-weight: bold">Personal Information</h5>
                                                            <ul class="personal-list">
                                                                <li>
                                                                    <i class="mdi mdi-card-account-mail"></i>
                                                                    <span id="show_email"></span>
                                                                </li>
                                                                <li>
                                                                    <i class="mdi mdi-phone-classic "></i>
                                                                    <span id="show_nohp"></span>
                                                                </li>
                                                                <li>
                                                                    <i class="mdi mdi-google-maps"></i>
                                                                    <span id="show_alamatktp1"></span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="skill">
                                                            <h3>Riwayat Pendidikan</h3>
                                                            <p style="font-weight: bold">SD/MI</p>
                                                            <div class=" mb-3">
                                                                <i class="mdi mdi-book-education-outline"></i>
                                                                <span id="show_namasdmi"></span>&nbsp;(<span id="show_tahunsdmi"></span>)
                                                            </div>
                                                            <p style="font-weight: bold">SMP/MTS</p>
                                                            <div class="mb-3">
                                                                <i class="mdi mdi-book-education-outline"></i>
                                                                <span id="show_namasmpmts"></span>&nbsp;(<span id="show_tahunsmpmts"></span>)
                                                            </div>
                                                            <p style="font-weight: bold">SMA/MA/SMK</p>
                                                            <div class=" mb-3">
                                                                <i class="mdi mdi-book-education-outline"></i>
                                                                <span id="show_namasmamasmk"></span>&nbsp;(<span id="show_tahunsmamasm"></span>)
                                                            </div>
                                                            <p style="font-weight: bold">S1/S2/S3</p>
                                                            <div class=" mb-3">
                                                                <i class="mdi mdi-book-education-outline"></i>
                                                                <span id="show_namauniversitas"></span>&nbsp;(<span id="show_tahununiversitas"></span>)
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="card right-profile-card">
                                                    <div class="card-header alert-primary">
                                                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="tabs-profil-tab" data-toggle="pill" href="#tabs-profil" role="tab" aria-selected="true">Profil</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="tabs-keterampilan-tab" data-toggle="pill" href="#tabs-keterampilan" role="tab" aria-selected="false">Keterampilan</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="tabs-pengalaman-tab" data-toggle="pill" href="#tabs-pengalaman" role="tab" aria-selected="false">Pengalaman</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="tabs-prestasi-tab" data-toggle="pill" href="#tabs-prestasi" role="tab" aria-selected="false">Prestasi</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="tabs-dokumen-tab" data-toggle="pill" href="#tabs-dokumen" role="tab" aria-selected="false">Dokumen</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="tab-pane fade show active" id="tabs-profil" role="tabpanel" aria-labelledby="tabs-profil-tab">
                                                                <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <input type="text" id="show_nama_pelamar2" readonly name="" class="form-control" />
                                                                            <label for="holding_recruitment">NAMA LENGKAP</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <input type="text" id="show_tempatlahir" readonly name="" class="form-control" />
                                                                            <label for="holding_recruitment">TEMPAT LAHIR</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <input type="text" id="show_tanggallahir" readonly name="" class="form-control" />
                                                                            <label for="holding_recruitment">TANGGAL LAHIR</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <!-- <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <textarea type="text" id="show_alamatktp2" readonly name="" class="form-control"></textarea>
                                                                            <label for="holding_recruitment">ALAMAT</label>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <br>-->
                                                                <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <input type="text" id="show_gender" readonly name="" class="form-control" />
                                                                            <label for="holding_recruitment">JENIS KELAMIN</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <input type="text" id="show_statusnikah" readonly name="" class="form-control" />
                                                                            <label for="holding_recruitment">STATUS PERNIKAHAN</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row g-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <input type="text" id="show_nik" readonly name="" class="form-control" />
                                                                            <label for="holding_recruitment">NIK</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="tabs-keterampilan" role="tabpanel">
                                                                <div class="work-container">
                                                                    <h3 id="show_judulketerampilan1"></h3>
                                                                    <p id="show_ketketerampilan1"></p>
                                                                </div>
                                                                <div class="work-container">
                                                                    <h3 id="show_judulketerampilan2"></h3>
                                                                    <p id="show_ketketerampilan2"></p>
                                                                </div>
                                                                <div class="work-container">
                                                                    <h3 id="show_judulketerampilan3"></h3>
                                                                    <p id="show_ketketerampilan3"></p>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="tabs-pengalaman" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="timeline-centered">
                                                                        <div class="timeline-entry">
                                                                            <div class="timeline-entry-inner">
                                                                                <div class="timeline-icon bg-success">
                                                                                    <span class="mdi mdi-check-decagram"></span>
                                                                                </div>
                                                                                <div class="timeline-label">
                                                                                    <h2>
                                                                                        <a href="#" id="show_lokasipengalaman1"></a>
                                                                                        (<span id="show_tahunpengalaman1"></span>)
                                                                                    </h2>
                                                                                    <p id="show_judulpengalaman1"></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="timeline-entry">
                                                                            <div class="timeline-entry-inner">
                                                                                <div class="timeline-icon bg-success">
                                                                                    <span class="mdi mdi-check-decagram"></span>
                                                                                </div>
                                                                                <div class="timeline-label">
                                                                                    <h2>
                                                                                        <a href="#" id="show_lokasipengalaman2"></a>
                                                                                        (<span id="show_tahunpengalaman2"></span>)
                                                                                    </h2>
                                                                                    <p id="show_judulpengalaman2"></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="timeline-entry">
                                                                            <div class="timeline-entry-inner">
                                                                                <div class="timeline-icon bg-success">
                                                                                    <span class="mdi mdi-check-decagram"></span>
                                                                                </div>
                                                                                <div class="timeline-label">
                                                                                    <h2>
                                                                                        <a href="#" id="show_lokasipengalaman3"></a>
                                                                                        (<span id="show_tahunpengalaman3"></span>)
                                                                                    </h2>
                                                                                    <p id="show_judulpengalaman3"></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="tabs-prestasi" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="timeline-centered">
                                                                        <div class="timeline-entry">
                                                                            <div class="timeline-entry-inner">
                                                                                <div class="timeline-icon bg-success">
                                                                                    <span class="mdi mdi-check-decagram"></span>
                                                                                </div>
                                                                                <div class="timeline-label">
                                                                                    <h2>
                                                                                        <a href="#" id="show_prestasi1"></a>
                                                                                    </h2>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="timeline-entry">
                                                                            <div class="timeline-entry-inner">
                                                                                <div class="timeline-icon bg-success">
                                                                                    <span class="mdi mdi-check-decagram"></span>
                                                                                </div>
                                                                                <div class="timeline-label">
                                                                                    <h2>
                                                                                        <a href="#" id="show_prestasi2"></a>
                                                                                    </h2>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="timeline-entry">
                                                                            <div class="timeline-entry-inner">
                                                                                <div class="timeline-icon bg-success">
                                                                                    <span class="mdi mdi-check-decagram"></span>
                                                                                </div>
                                                                                <div class="timeline-label">
                                                                                    <h2>
                                                                                        <a href="#" id="show_prestasi3"></a>
                                                                                    </h2>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="tabs-dokumen" role="tabpanel">
                                                                <div class="work-container">
                                                                    <h3 id="">KTP</h3>
                                                                    <img width="100%" height="100%" src="" id="show_imgktp" alt="">
                                                                </div>
                                                                <div class="work-container">
                                                                    <h3 id="">KK</h3>
                                                                    <img width="100%" height="100%" src="" id="show_imgkk" alt="">
                                                                </div>
                                                                <div class="work-container">
                                                                    <h3 id="">IJAZAH</h3>
                                                                    <img width="100%" height="100%" src="" id="show_imgijazah" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="post" id="users-form">
                        @csrf
                        <table class="table" id="table_listpelamar" style="width: 100%;">
                            <thead class="table-primary">
                                <tr>
                                    <th>No.</th>
                                    <th>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Detail&nbsp;CV</th>
                                    <th>Select</th>
                                    <th>Status&nbsp;Recruitment</th>
                                    <th>Departemen</th>
                                    <th>Divisi</th>
                                    <th>Bagian</th>
                                    {{-- <th>Status</th> --}}
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            </tbody>
                        </table>
                        <button type="button" id="submit-btny" class="btn btn-sm btn-primary">
                            <span class="mdi mdi-file-check"></span>&nbsp;
                            (Y) Lolos Administrasi
                        </button>
                        <button type="button" id="submit-btnt" class="btn btn-sm btn-danger">
                            <span class="mdi mdi-file-document-remove"></span>&nbsp;
                            (T) Lolos Administrasi
                        </button>
                    </form>
                    <div class="modal fade" id="confirmModalY" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="POST" action="{{ url('/recruitment/lolos-administrasi/'.$holding) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel">Jadwal Interview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="input-users-list-y">
                                            <!-- Input fields akan ditambahkan di sini -->
                                        </div>
                                        <div class="row">
                                            <div class="form-floating form-floating-outline">
                                                <input type="date" id="tanggal_interview" name="tanggal_interview" class="form-control @error('tanggal_interview') is-invalid @enderror" placeholder="Masukkan Bagian" value="{{ old('tanggal_interview') }}" />
                                                <label for="tanggal_interview">Tanggal</label>
                                            </div>
                                            @error('tanggal_interview')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_interview" name="jam_interview" class="form-control @error('jam_interview') is-invalid @enderror" placeholder="Masukkan Bagian" value="{{ old('jam_interview') }}" />
                                                <label for="tanggal_interview">Jam</label>
                                            </div>
                                            @error('jam_interview')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('lokasi_interview') is-invalid @enderror" id="lokasi_interview" name="lokasi_interview" autofocus value="{{ old('lokasi_interview') }}">
                                                    <option value=""> Pilih Lokasi</option>
                                                    <option value="CV. Sumber Pangan (Kediri)">CV. Sumber Pangan (KEDIRI)</option>
                                                    <option value="CV. Surya Inti Pangan (Makasar)">CV. Surya Inti Pangan (MAKASAR)</option>
                                                    <option value="CV. Surya Pangan Semesta (Kediri)">CV. Surya Pangan Semesta (KEDIRI)</option>
                                                    <option value="CV. Surya Pangan Semesta (Ngawi)">CV. Surya Pangan Semesta (NGAWI)</option>
                                                    <option value="CV. Surya Pangan Semesta (Subang)">CV. Surya Pangan Semesta (SUBANG)</option>
                                                </select>
                                                <label for="lokasi_interview">Lokasi</label>
                                            </div>
                                            @error('lokasi_interview')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <br>
                                        {{-- <div class="row">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="judul_interview" name="judul_interview" class="form-control @error('judul_interview') is-invalid @enderror" placeholder="Masukkan Bagian" value="{{ old('judul_interview') }}" />
                                        <label for="bagian_recruitment">Judul Pesan</label>
                                    </div>
                                    @error('tanggal_interview')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="keteraangan_interview" name="keteraangan_interview" class="form-control @error('keteraangan_interview') is-invalid @enderror" placeholder="Masukkan Bagian" value="{{ old('keteraangan_interview') }}" />
                                        <label for="bagian_recruitment">Keterangan Pesan</label>
                                    </div>
                                    @error('keteraangan_interview')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" id="confirm-submit" class="btn btn-primary">Konfirmasi</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="confirmModalT" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <form method="POST" action="{{ url('/recruitment/tidak-lolos-administrasi/'.$holding) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Administrasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="input-users-list-t">
                                    <!-- Input fields akan ditambahkan di sini -->
                                </div>
                                <div class="row">
                                    <p>Konfirmasi tidak lolos data administrasi</p>
                                </div>
                                <br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" id="confirm-submit" class="btn btn-primary">Konfirmasi</button>
                            </div>
                        </div>
                    </form>
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
    $(document).ready(function() {
        $("#desc_recruitment").summernote();
        // $("#show_desc_recruitment").summernote();
        $("#desc_recruitment_update").summernote();
        $('.dropdown-toggle').dropdown();
    });
</script>
{{-- start datatable  --}}
<script>
    let holding = window.location.pathname.split("/").pop();
    let id = @json($id_recruitment);
    console.log('id');
    var table = $('#table_listpelamar').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('dt/data-list-pelamar/') }}" + '/' + id + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'email',
                name: 'email',
                render: function(data, type, row, meta) {
                    return '<strong style="white-space: nowrap;">' + data + '</strong>';
                }
            },
            {
                data: 'detail_cv',
                name: 'detail_cv'
            },
            {
                data: 'select',
                name: 'select'
            },
            //{
            //    data: 'select', 
            //    name: 'select',
            //    render: function(data, type, row) {
            //        return '<input type="checkbox" name="selected_users[]" value="' + row.id + '">';
            //    },
            //    orderable: false,
            //    searchable: false
            //},
            {
                data: 'status_recruitment',
                name: 'status_recruitment'
            },
            {
                data: 'nama_departemen',
                name: 'nama_departemen',
                render: function(data, type, row, meta) {
                    return '<span style="white-space: nowrap;">' + data + '</span>';
                }
            },
            {
                data: 'nama_divisi',
                name: 'nama_divisi',
                render: function(data, type, row, meta) {
                    return '<span style="white-space: nowrap;">' + data + '</span>';
                }
            },
            {
                data: 'nama_bagian',
                name: 'nama_bagian',
                render: function(data, type, row, meta) {
                    return '<span style="white-space: nowrap;">' + data + '</span>';
                }
            },
        ]
    });

    $('#select-all').on('click', function() {
        var rows = $('#table_listpelamar').DataTable().rows({
            'search': 'applied'
        }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $('#submit-btny').on('click', function(e) {
        e.preventDefault();
        var selectedUsers = [];
        $('input[name="selected_users[]"]:checked').each(function() {
            selectedUsers.push($(this).val());
        });
        if (selectedUsers.length > 0) {
            $('#input-users-list-y').empty();
            selectedUsers.forEach(function(userId) {
                $('#input-users-list-y').append(
                    '<div class="form-group">' +
                    '<input type="hidden" class="form-control" name="users[' + userId + ']" id="user_' + userId + '" value="' + userId + '">' +
                    '</div>'
                );
            });
            $('#confirmModalY').modal('show');
        } else {
            alert('Please select at least one user.');
        }
    });

    $('#submit-btnt').on('click', function(e) {
        e.preventDefault();

        // Ambil semua checkbox yang dipilih
        var selectedUsers = [];
        $('input[name="selected_users[]"]:checked').each(function() {
            selectedUsers.push($(this).val());
        });

        // Tampilkan modal jika ada checkbox yang dipilih
        if (selectedUsers.length > 0) {
            // Kosongkan input sebelumnya dari modal
            $('#input-users-list-t').empty();

            // Tambahkan input text untuk setiap ID user yang dipilih
            selectedUsers.forEach(function(userId) {
                $('#input-users-list-t').append(
                    '<div class="form-group">' +
                    '<input type="hidden" class="form-control" name="users[' + userId + ']" id="user_' + userId + '" value="' + userId + '">' +
                    '</div>'
                );
            });

            // Tampilkan modal
            $('#confirmModalT').modal('show');
        } else {
            alert('Please select at least one user.');
        }
    });

    $('#confirm-submit').on('click', function() {
        $('#users-form').submit();
    });
</script>
{{-- end datatable  --}}
<script>
    function formatNomor(nohp) {
        // Konversi ke string dan tambahkan kode negara
        let nohpStr = `+62 ${nohp.toString()}`;
        // Masukkan tanda '-' pada posisi yang diinginkan
        return `${nohpStr.slice(0, 6)}-${nohpStr.slice(6, 10)}-${nohpStr.slice(10)}`;
    }
    // show modal syarat
    $(document).on('click', '#btn_lihat_cv', function() {
        let id = $(this).data('id');
        let nama_pelamar = $(this).data('nama_pelamar');
        let nama_tempatlahir = $(this).data('tempat_lahir');
        let nama_tanggallahir = $(this).data('tanggal_lahir');
        let nama_gender = $(this).data('gender');
        let nama_statusnikah = $(this).data('status_nikah');
        let nama_nik = $(this).data('nik');
        let nama_departemen = $(this).data('departemen');
        let nama_divisi = $(this).data('divisi');
        let nama_bagian = $(this).data('bagian');
        let nama_email = $(this).data('email');
        let nama_nohp = $(this).data('no_hp');
        let nama_alamatktp = $(this).data('alamat_ktp');
        let nama_sdmi = $(this).data('nama_sdmi');
        let nama_tahunsdmi = $(this).data('tahun_sdmi');
        let nama_smpmts = $(this).data('nama_smpmts');
        let nama_tahunsmpmts = $(this).data('tahun_smpmts');
        let nama_smamasmk = $(this).data('nama_smamasmk');
        let nama_tahunsmamasmk = $(this).data('tahun_smamasmk');
        let nama_universitas = $(this).data('nama_universitas');
        let nama_tahununiversitas = $(this).data('tahun_universitas');
        let nama_judulketerampilan1 = $(this).data('judul_keterampilan1');
        let nama_ketketerampilan1 = $(this).data('ket_keterampilan1');
        let nama_judulketerampilan2 = $(this).data('judul_keterampilan2');
        let nama_ketketerampilan2 = $(this).data('ket_keterampilan2');
        let nama_judulketerampilan3 = $(this).data('judul_keterampilan3');
        let nama_ketketerampilan3 = $(this).data('ket_keterampilan3');
        let nama_judulpengalaman1 = $(this).data('judul_pengalaman1');
        let nama_lokasipengalaman1 = $(this).data('lokasi_pengalaman1');
        let nama_tahunpengalaman1 = $(this).data('tahun_pengalaman1');
        let nama_judulpengalaman2 = $(this).data('judul_pengalaman2');
        let nama_lokasipengalaman2 = $(this).data('lokasi_pengalaman2');
        let nama_tahunpengalaman2 = $(this).data('tahun_pengalaman2');
        let nama_judulpengalaman3 = $(this).data('judul_pengalaman3');
        let nama_lokasipengalaman3 = $(this).data('lokasi_pengalaman3');
        let nama_tahunpengalaman3 = $(this).data('tahun_pengalaman3');
        let nama_prestasi1 = $(this).data('prestasi1');
        let nama_prestasi2 = $(this).data('prestasi2');
        let nama_prestasi3 = $(this).data('prestasi3');
        let nama_imgktp = $(this).data('img_ktp');
        let nama_imgkk = $(this).data('img_kk');
        let nama_imgijazah = $(this).data('img_ijazah');
        let nama_pp = $(this).data('img_pp');
        let holding = $(this).data("holding");
        $('#show_nama_pelamar1').val(nama_pelamar);
        $('#show_nama_pelamar2').val(nama_pelamar);
        $('#show_tempatlahir').val(nama_tempatlahir);
        $('#show_tanggallahir').val(nama_tanggallahir);
        $('#show_gender').val(nama_gender.toUpperCase());
        $('#show_statusnikah').val(nama_statusnikah.toUpperCase());
        $('#show_departemen').val(nama_departemen);
        $('#show_divisi').val(nama_divisi);
        $('#show_bagian').val(nama_bagian);
        $('#show_email').text(nama_email.toUpperCase());
        $('#show_nohp').text(formatNomor(nama_nohp));
        $('#show_nik').val(nama_nik);
        $('#show_alamatktp1').text(nama_alamatktp);
        $('#show_alamatktp2').val(nama_alamatktp);
        $('#show_namasdmi').text(nama_sdmi.toUpperCase());
        $('#show_tahunsdmi').text(nama_tahunsdmi);
        $('#show_namasmpmts').text(nama_smpmts.toUpperCase());
        $('#show_tahunsmpmts').text(nama_tahunsmpmts);
        $('#show_namasmamasmk').text(nama_smamasmk.toUpperCase());
        $('#show_tahunsmamasm').text(nama_tahunsmamasmk);
        $('#show_namauniversitas').text(nama_universitas.toUpperCase());
        $('#show_tahununiversitas').text(nama_tahununiversitas);
        $('#show_judulketerampilan1').text(nama_judulketerampilan1);
        $('#show_ketketerampilan1').text(nama_ketketerampilan1);
        $('#show_judulketerampilan2').text(nama_judulketerampilan2);
        $('#show_ketketerampilan2').text(nama_ketketerampilan2);
        $('#show_judulketerampilan3').text(nama_judulketerampilan3);
        $('#show_ketketerampilan3').text(nama_ketketerampilan3);
        $('#show_judulpengalaman1').text(nama_judulpengalaman1);
        $('#show_lokasipengalaman1').text(nama_lokasipengalaman1);
        $('#show_tahunpengalaman1').text(nama_tahunpengalaman1);
        $('#show_judulpengalaman2').text(nama_judulpengalaman2);
        $('#show_lokasipengalaman2').text(nama_lokasipengalaman2);
        $('#show_tahunpengalaman2').text(nama_tahunpengalaman2);
        $('#show_judulpengalaman3').text(nama_judulpengalaman3);
        $('#show_lokasipengalaman3').text(nama_lokasipengalaman3);
        $('#show_tahunpengalaman3').text(nama_tahunpengalaman3);
        $('#show_prestasi1').text(nama_prestasi1);
        $('#show_prestasi2').text(nama_prestasi2);
        $('#show_prestasi3').text(nama_prestasi3);
        $('#show_imgktp').attr('src', 'http://192.168.2.2:8000/public/assets/img/dokumen_cv/ijazah/' + nama_imgktp);
        $('#show_imgkk').attr('src', 'http://127.0.0.1:8000/images/' + nama_imgkk);
        $('#show_imgijazah').attr('src', 'http://127.0.0.1:8000/images/' + nama_imgijazah);
        if (nama_pp == "") {
            $('#show_imgpp').attr('src', 'http://127.0.0.1:8000/admin/assets/img/avatars/1.png');
        } else {
            $('#show_imgpp').attr('src', 'http://127.0.0.1:8000/images/' + nama_pp);
        }

        // let url = "{{ url('recruitment/show/') }}" + '/' + id + '/' + holding;
        $('#modal_lihat_cv').modal('show');
    });
    // update status aktif to non aktif

    // update status non aktif to aktif
</script>
@endsection