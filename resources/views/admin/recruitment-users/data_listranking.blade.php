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
                        <h5 class="card-title m-0 me-2">DATA RANKING</h5>
                    </div>
                </div>
                <table class="table" id="table_recruitment_interview" style="width: 100%;">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Pelamar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th>Status</th>
                            <th>Detail&nbsp;CV</th>
                            <th>Nilai Ujian</th>
                            {{-- <th>Catatan&nbsp;Ujian</th> --}}
                            <th>Nilai Relevansi</th>
                            <th>Nilai Motivasi</th>
                            <th>Nilai&nbsp;Kemampuan Problem solving</th>
                            <th>Nilai&nbsp;Sikap keterampilan</th>
                            <th>Nilai&nbsp;Individual Competency</th>
                            <th>Catatan HRD</th>
                            <th>Nilai Manager</th>
                            <th>Catatn&nbsp;Manager</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Transactions -->
        <!--/ Data Tables -->
    </div>
</div>
<div class="modal fade" id="modal_kehadiran" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-scrollable">
        <div class=" modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="backDropModalTitle">Absensi Kehadiran Interview</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form method="post" action="{{ url('absensi/kehadrian-interview/'.$holding) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <div class="form-check mt-3">
                                        <input type="hidden" id="show_recruitmentuserid3" name="show_recruitmentuserid3">
                                        <input type="hidden" id="show_recruitmentinterviewid3" name="show_recruitmentinterviewid3">
                                        <input name="status_interview" class="form-check-input" type="radio" value="3" id="defaultRadio2">
                                        <label class="form-check-label" for="defaultRadio1"> HADIR </label>
                                    </div>
                                    <div class="form-check">
                                        <input name="status_interview" class="form-check-input" type="radio" value="4" id="defaultRadio1">
                                        <label class="form-check-label" for="defaultRadio2"> TIDAK </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="location.reload();" class="btn btn-sm btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
                                    <p>SMP/MTS</p>
                                    <div class="mb-3">
                                        <i class="mdi mdi-book-education-outline"></i>
                                        <span id="show_namasmpmts"></span>&nbsp;(<span id="show_tahunsmpmts"></span>)
                                    </div>
                                    <p>SMA/MA/SMK</p>
                                    <div class=" mb-3">
                                        <i class="mdi mdi-book-education-outline"></i>
                                        <span id="show_namasmamasmk"></span>&nbsp;(<span id="show_tahunsmamasm"></span>)
                                    </div>
                                    <p>UNIVERSITAS</p>
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
                                                        <input type="text" id="show_nama_pelamar2" readonly name="" class="form-control"/>
                                                        <label for="holding_recruitment">NAMA LENGKAP</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="show_tempatlahir" readonly name="" class="form-control"/>
                                                        <label for="holding_recruitment">TEMPAT LAHIR</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="show_tanggallahir" readonly name="" class="form-control"/>
                                                        <label for="holding_recruitment">TANGGAL LAHIR</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <textarea type="text" id="show_alamatktp2" readonly name="" class="form-control"></textarea>
                                                        <label for="holding_recruitment">ALAMAT</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="show_gender" readonly name="" class="form-control"/>
                                                        <label for="holding_recruitment">JENIS KELAMIN</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="show_statusnikah" readonly name="" class="form-control"/>
                                                        <label for="holding_recruitment">STATUS PERNIKAHAN</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="show_nik" readonly name="" class="form-control"/>
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
<div class="modal fade" id="modal_penilaian" data-bs-backdrop="static" tabindex="-1">
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
                                        <img src="" id="show_imgpp3" alt class="user-profile">
                                            <h6 id="show_nama_pelamar3" style="font-weight: bold"></h6>
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
                                                <span id="show_email3"></span>
                                            </li>
                                        <li>
                                            <i class="mdi mdi-phone-classic "></i>
                                            <span id="show_nohp3"></span>
                                        </li>
                                        <li>
                                            <i class="mdi mdi-google-maps"></i>
                                            <span id="show_alamatktp3"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="skill">
                                    <h3>Riwayat Pendidikan</h3>
                                    <p style="font-weight: bold">SD/MI</p>
                                    <div class=" mb-3">
                                        <i class="mdi mdi-book-education-outline"></i>
                                        <span id="show_namasdmi3"></span>&nbsp;(<span id="show_tahunsdmi3"></span>)
                                    </div>
                                    <p style="font-weight: bold">SMP/MTS</p>
                                    <div class="mb-3">
                                        <i class="mdi mdi-book-education-outline"></i>
                                        <span id="show_namasmpmts3"></span>&nbsp;(<span id="show_tahunsmpmts3"></span>)
                                    </div>
                                    <p style="font-weight: bold">SMA/MA/SMK</p>
                                    <div class=" mb-3">
                                        <i class="mdi mdi-book-education-outline"></i>
                                        <span id="show_namasmamasmk3"></span>&nbsp;(<span id="show_tahunsmamasm3"></span>)
                                    </div>
                                    <p style="font-weight: bold">S1/S2/S3</p>
                                    <div class=" mb-3">
                                        <i class="mdi mdi-book-education-outline"></i>
                                        <span id="show_namauniversitas3"></span>&nbsp;(<span id="show_tahununiversitas3"></span>)
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
                                            <a class="nav-link active" id="tabs-satu-tab" data-toggle="pill" href="#tabs-satu" role="tab" aria-selected="true">Psikotes</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tabs-dua-tab" data-toggle="pill" href="#tabs-dua" role="tab" aria-selected="false">Interview HRD</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tabs-tiga-tab" data-toggle="pill" href="#tabs-tiga" role="tab" aria-selected="false">Interview Manager</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="tabs-satu" role="tabpanel" aria-labelledby="tabs-profil-tab">
                                            <h6 style="font-weight: bold">PENILAIAN PSIKOTES</h6>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" disabled name="" id="show_nilaiujian" class="form-control"/>
                                                        <label for="holding_recruitment">NILAI</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <textarea type="text" disabled id="show_catatanujian" name="" class="form-control" style="height: 200px;"></textarea>
                                                        <label for="holding_recruitment">CATATAN</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-dua" role="tabpanel">
                                            <form method="post" action="{{ url('/nilai-interview-hrd/update/'.$holding) }}" enctype="multipart/form-data">
                                                @csrf
                                                <h6 style="font-weight: bold">PENILAIAN INTERVIEW</h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="hidden" id="show_recruitmentinterviewid1" name="recruitment_interview_id1" class="form-control"/>
                                                                    <input type="number" required step="0.1" id="show_nilaiinterviewhrd1" name="nilai_interview_hrd1" class="form-control"/>
                                                                    <label for="holding_recruitment">Kualifikasi dan relevansi</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="number" required step="0.1" id="show_nilaiinterviewhrd2" name="nilai_interview_hrd2" class="form-control"/>
                                                                    <label for="holding_recruitment">Etika kerja dan motivasi</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="number" required step="0.1" id="show_nilaiinterviewhrd3" name="nilai_interview_hrd3" class="form-control"/>
                                                                    <label for="holding_recruitment">Kemampuan problem solving</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="number" required step="0.1" id="show_nilaiinterviewhrd4" name="nilai_interview_hrd4" class="form-control"/>
                                                                    <label for="holding_recruitment">Sikap dan keterampilan</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="number" required step="0.1" id="show_nilaiinterviewhrd5" name="nilai_interview_hrd5" class="form-control"/>
                                                                    <label for="holding_recruitment">Individual competency</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <div class="form-check">
                                                                        <input name="status_interview_manager" class="form-check-input" type="radio" value="YA" id="ya_interview_manager">
                                                                        <label class="form-check-label" for="ya_interview_manager"> (Y) Interview Manager </label>
                                                                      </div>
                                                                      <div class="form-check">
                                                                        <input name="status_interview_manager" class="form-check-input" type="radio" value="TIDAK" id="tdk_interview_manager">
                                                                        <label class="form-check-label" for="tdk_interview_manager"> (T) Interview Manager </label>
                                                                      </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catataninterviewhrd" name="catatan_interview_hrd" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">CATATAN HRD</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" onclick="location.reload();" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-tiga" role="tabpanel">
                                            <form method="post" action="{{ url('/nilai-interview-manager/update/'.$holding) }}" enctype="multipart/form-data">
                                                @csrf
                                                <h6 style="font-weight: bold">PENILAIAN MANAGER</h6>
                                                <div class="row g-2">
                                                    <div class="col mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="hidden" id="show_recruitmentinterviewid2" name="recruitment_interview_id2" class="form-control"/>
                                                            <input type="number" required step="0.1" id="show_nilaiinterviewmanager" name="nilai_interview_manager" class="form-control"/>
                                                            <label for="holding_recruitment">NILAI</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row g-2">
                                                    <div class="col mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <textarea type="text" required id="show_catataninterviewmanager" name="catatan_interview_manager" class="form-control" style="height: 200px;"></textarea>
                                                            <label for="holding_recruitment">CATATAN MANAGER</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button onclick="location.reload();" type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                                                </div>
                                            </form>
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

@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>
    let holding = window.location.pathname.split("/").pop();
    let id = @json($id_recruitment);
    console.log(id);
    var table = $('#table_recruitment_interview').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('dt/data-list-ranking') }}" + '/' + id + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_pelamar',
                name: 'nama_pelamar',
                render: function(data, type, row, meta) {
                    return '<strong style="white-space: nowrap;">' + data + '</strong>';
                }
            },
            {
                data: 'status_kehadiran',
                name: 'status_kehadiran'
            },
            {
                data: 'detail_cv',
                name: 'detail_cv'
            },
            {
                data: 'nilai_ujian',
                name: 'nilai_ujian'
            },
            // {
            //     data: 'catatan_ujian',
            //     name: 'catatan_ujian'
            // },
            {
                data: 'nilai_interview_hrd1',
                name: 'nilai_interview_hrd1'
            },
            {
                data: 'nilai_interview_hrd2',
                name: 'nilai_interview_hrd2'
            },
            {
                data: 'nilai_interview_hrd3',
                name: 'nilai_interview_hrd3'
            },
            {
                data: 'nilai_interview_hrd4',
                name: 'nilai_interview_hrd4'
            },
            {
                data: 'nilai_interview_hrd5',
                name: 'nilai_interview_hrd5'
            },
            {
                data: 'catatan_interview_hrd',
                name: 'catatan_interview_hrd'
            },
            {
                data: 'nilai_interview_manager',
                name: 'nilai_interview_manager'
            },
            {
                data: 'catatan_interview_manager',
                name: 'catatan_interview_manager'
            },

        ]
    });
</script>
{{-- end datatable  --}}

<script>
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
        let nama_alamatktp = $(this).data('alamatktp');
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
        $('#show_nama_pelamar1').text(nama_pelamar);
        $('#show_nama_pelamar2').val(nama_pelamar);
        $('#show_tempatlahir').val(nama_tempatlahir);
        $('#show_tanggallahir').val(nama_tanggallahir);
        $('#show_gender').val(nama_gender);
        $('#show_statusnikah').val(nama_statusnikah);
        $('#show_departemen').text(nama_departemen);
        $('#show_divisi').text(nama_divisi);
        $('#show_bagian').text(nama_bagian);
        $('#show_email').text(nama_email);
        $('#show_nohp').text(nama_nohp);
        $('#show_nik').val(nama_nik);
        $('#show_alamatktp1').text(nama_alamatktp);
        $('#show_alamatktp2').val(nama_alamatktp);
        $('#show_namasdmi').text(nama_sdmi);
        $('#show_tahunsdmi').text(nama_tahunsdmi);
        $('#show_namasmpmts').text(nama_smpmts);
        $('#show_tahunsmpmts').text(nama_tahunsmpmts);
        $('#show_namasmamasmk').text(nama_smamasmk);
        $('#show_tahunsmamasm').text(nama_tahunsmamasmk);
        $('#show_namauniversitas').text(nama_universitas);
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
        $('#show_imgktp').attr('src', 'http://127.0.0.1:8000/images/' + nama_imgktp);
        $('#show_imgkk').attr('src', 'http://127.0.0.1:8000/images/' + nama_imgkk);
        $('#show_imgijazah').attr('src', 'http://127.0.0.1:8000/images/' + nama_imgijazah);
        if (nama_pp == "") {
            $('#show_imgpp').attr('src', 'http://127.0.0.1:8000/admin/assets/img/avatars/1.png');
        }else{
            $('#show_imgpp').attr('src', 'http://127.0.0.1:8000/images/' + nama_pp);
        }

        // let url = "{{ url('recruitment/show/') }}" + '/' + id + '/' + holding;
        $('#modal_lihat_cv').modal('show');
    });
    $(document).on('click', '#btn_penilaian', function() {
        let recruitment_user_id = $(this).data('recruitment_user_id');
        let recruitment_interview_id = $(this).data('recruitment_interview_id');
        let nama_pelamar = $(this).data('nama_pelamar');
        let nama_email = $(this).data('email');
        let nama_nohp = $(this).data('no_hp');
        let nama_alamatktp = $(this).data('alamatktp');
        let nama_sdmi = $(this).data('nama_sdmi');
        let nama_tahunsdmi = $(this).data('tahun_sdmi');
        let nama_smpmts = $(this).data('nama_smpmts');
        let nama_tahunsmpmts = $(this).data('tahun_smpmts');
        let nama_smamasmk = $(this).data('nama_smamasmk');
        let nama_tahunsmamasmk = $(this).data('tahun_smamasmk');
        let nama_universitas = $(this).data('nama_universitas');
        let nama_tahununiversitas = $(this).data('tahun_universitas');3
        let nama_imgktp = $(this).data('img_ktp');
        let nama_imgkk = $(this).data('img_kk');
        let nama_imgijazah = $(this).data('img_ijazah');
        let nama_nilaiujian = $(this).data('nilai_ujian');
        let nama_catatanujian = $(this).data('catatan_ujian');
        let nama_nilaiinterviewhrd1 = $(this).data('nilai_interview_hrd1');
        let nama_nilaiinterviewhrd2 = $(this).data('nilai_interview_hrd2');
        let nama_nilaiinterviewhrd3 = $(this).data('nilai_interview_hrd3');
        let nama_nilaiinterviewhrd4 = $(this).data('nilai_interview_hrd4');
        let nama_nilaiinterviewhrd5 = $(this).data('nilai_interview_hrd5');
        let nama_catataninterviewhrd = $(this).data('catatan_interview_hrd');
        let nama_nilaiinterviewmanager = $(this).data('nilai_interview_manager');
        let nama_catataninterviewmanager = $(this).data('catatan_interview_manager');
        let nama_pp = $(this).data('img_pp');
        let nama_status_interview = $(this).data('status_interview');
        let nama_statusinterviewmanager = $(this).data('status_interview_manager');
        let holding = $(this).data("holding");

        $('#show_recruitmentinterviewid1').val(recruitment_interview_id);
        $('#show_recruitmentinterviewid2').val(recruitment_interview_id);
        $('#show_nama_pelamar3').text(nama_pelamar);3
        $('#show_email3').text(nama_email);
        $('#show_nohp3').text(nama_nohp);3
        $('#show_alamatktp3').text(nama_alamatktp);
        $('#show_namasdmi3').text(nama_sdmi);
        $('#show_tahunsdmi3').text(nama_tahunsdmi);
        $('#show_namasmpmts3').text(nama_smpmts);
        $('#show_tahunsmpmts3').text(nama_tahunsmpmts);
        $('#show_namasmamasmk3').text(nama_smamasmk);
        $('#show_tahunsmamasm3').text(nama_tahunsmamasmk);
        $('#show_namauniversitas3').text(nama_universitas);
        $('#show_tahununiversitas3').text(nama_tahununiversitas);
        $('#show_nilaiujian').val(nama_nilaiujian);
        $('#show_catatanujian').text(nama_catatanujian);
        $('#show_nilaiinterviewmanager').val(nama_nilaiinterviewmanager);
        $('#show_catataninterviewmanager').text(nama_catataninterviewmanager);
        // console.log(nama_status_interview);
        if (nama_status_interview === 4) {
            $('#show_nilaiinterviewhrd1').val(nama_nilaiinterviewhrd1).prop('disabled', true);
            $('#show_nilaiinterviewhrd2').val(nama_nilaiinterviewhrd2).prop('disabled', true);
            $('#show_nilaiinterviewhrd3').val(nama_nilaiinterviewhrd3).prop('disabled', true);
            $('#show_nilaiinterviewhrd4').val(nama_nilaiinterviewhrd4).prop('disabled', true);
            $('#show_nilaiinterviewhrd5').val(nama_nilaiinterviewhrd5).prop('disabled', true);
            $('#show_catataninterviewhrd').val(nama_catataninterviewhrd).prop('disabled', true);
            $('#show_nilaiinterviewmanager').val(nama_nilaiinterviewmanager).prop('disabled', true);
            $('#show_catataninterviewmanager').val(nama_catataninterviewmanager).prop('disabled', true);
            $('input[name="status_interview_manager"]').prop('disabled', true).prop('checked', false);
            $('button[type="submit"]').prop('disabled', true);
        } else if (nama_status_interview === 3) {
            $('#show_nilaiinterviewhrd1').val(nama_nilaiinterviewhrd1).prop('disabled', false);
            $('#show_nilaiinterviewhrd2').val(nama_nilaiinterviewhrd2).prop('disabled', false);
            $('#show_nilaiinterviewhrd3').val(nama_nilaiinterviewhrd3).prop('disabled', false);
            $('#show_nilaiinterviewhrd4').val(nama_nilaiinterviewhrd4).prop('disabled', false);
            $('#show_nilaiinterviewhrd5').val(nama_nilaiinterviewhrd5).prop('disabled', false);
            $('#show_catataninterviewhrd').val(nama_catataninterviewhrd).prop('disabled', false);
            $('#show_nilaiinterviewmanager').val(nama_nilaiinterviewmanager).prop('disabled', false);
            $('#show_catataninterviewmanager').val(nama_catataninterviewmanager).prop('disabled', false);
            $('button[type="submit"]').prop('disabled', false);
            $('input[name="status_interview_manager"]').prop('disabled', false);
            if (nama_statusinterviewmanager === 'YA') {
                $('#ya_interview_manager').prop('checked', true);
            } else {
                $('#tdk_interview_manager').prop('checked', true);
            }
        }
        if (nama_pp == "") {
            $('#show_imgpp3').attr('src', 'http://127.0.0.1:8000/admin/assets/img/avatars/1.png');
        }else{
            $('#show_imgpp3').attr('src', 'http://127.0.0.1:8000/images/' + nama_pp);
        }
        $('#modal_penilaian').modal('show');
    });
    $(document).on('click', '#btn_kehadiran', function() {
        let recruitment_user_id = $(this).data('recruitment_user_id');
        let recruitment_interview_id = $(this).data('recruitment_interview_id');
        $('#show_recruitmentuserid3').val(recruitment_user_id);
        $('#show_recruitmentinterviewid3').val(recruitment_interview_id);
        $('#modal_kehadiran').modal('show');
    });
</script>
@endsection
