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
            <div class="card container">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DATA INTERVIEW</h5>
                    </div>
                </div>
                <table class="table" id="table_recruitment_interview" style="width: 100%;">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Email</th>
                            <th>Kehadiran</th>
                            <th>Ujian</th>
                            <th>Detail&nbsp;CV</th>
                            <th>Penilaian</th>
                            <th>Departemen</th>
                            <th>Divisi</th>
                            <th>Bagian</th>
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
<div class="modal fade" id="modal_warning" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-scrollable">
        <div class=" modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="backDropModalTitle">Kategori Ujian</h6>
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
                                        <span class="badge rounded-pill bg-danger">
                                            User belum absen kehadiran
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_ujian" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class=" modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="backDropModalTitle">Kategori Ujian</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form method="post" action="{{ url('ujian/kategori-ujian/'.$holding) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="hidden" name="id_recruitmentuser" id="show_recruitmentuserid">
                                            <input type="hidden" name="id_userscareer" id="show_userscareerid">
                                            <div class="form-check">
                                                <input class="form-check-input" name="sd-6" disabled type="checkbox" value="6-SD" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    6-SD
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="smp-1" disabled type="checkbox" value="SPM-1" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SMP-1
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="smp-2" disabled type="checkbox" value="SMP-2" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SMP-2
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="smp-3" disabled type="checkbox" value="SMP-2" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SMP-3
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" name="sma-1" disabled type="checkbox" value="SMA-1" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SMA-1
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="sma-2" disabled type="checkbox" value="SMA-2" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SMA-2
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="sma-3" disabled type="checkbox" value="SMA-3" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SMA-3
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="sbmptn" disabled type="checkbox" value="SBMPTN" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    SBMPTN
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <!-- <div class="form-check">
                                                <input class="form-check-input" name="cpns" type="checkbox" value="CPNS" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    CPNS
                                                </label>
                                            </div> -->
                                            <div class="form-check">
                                                <input class="form-check-input" name="kelas" disabled type="checkbox" value="TOEFL" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    TOEFL
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="kelas" checked type="checkbox" value="11" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">
                                                    PSIKOTES
                                                </label>
                                            </div>
                                        </div>
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
<div class="modal fade" id="modal_prosesujian" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-scrollable">
        <div class=" modal-content">
            <div class="modal-header">
                {{-- <h6 class="modal-title" id="backDropModalTitle">Absensi Kehadiran Interview</h6> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form enctype="multipart/form-data">
                        @csrf
                        <h4>Proses Pengerjaan Ujian</h4>
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
                                            <span id="show_namasdmi"></span>&nbsp;<br>(<span id="show_tahunsdmi"></span>)
                                        </div>
                                        <p style="font-weight: bold">SMP/MTS</p>
                                        <div class="mb-3">
                                            <i class="mdi mdi-book-education-outline"></i>
                                            <span id="show_namasmpmts"></span>&nbsp;<br>(<span id="show_tahunsmpmts"></span>)
                                        </div>
                                        <p style="font-weight: bold">SMA/MA/SMK</p>
                                        <div class=" mb-3">
                                            <i class="mdi mdi-book-education-outline"></i>
                                            <span id="show_namasmamasmk"></span>&nbsp;<br>(<span id="show_tahunsmamasm"></span>)
                                        </div>
                                        <p style="font-weight: bold">UNIVERSITAS</p>
                                        <div class=" mb-3">
                                            <i class="mdi mdi-book-education-outline"></i>
                                            <span id="show_namauniversitas"></span>&nbsp;<br>(<span id="show_tahununiversitas"></span>)
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
<div class="modal fade" id="modal_penilaian" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class=" modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="backDropModalTitle"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="location.reload();"></button>
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
                                            <span id="show_namasdmi3"></span>&nbsp;<br>(<span id="show_tahunsdmi3"></span>)
                                        </div>
                                        <p style="font-weight: bold">SMP/MTS</p>
                                        <div class="mb-3">
                                            <i class="mdi mdi-book-education-outline"></i>
                                            <span id="show_namasmpmts3"></span>&nbsp;<br>(<span id="show_tahunsmpmts3"></span>)
                                        </div>
                                        <p style="font-weight: bold">SMA/MA/SMK</p>
                                        <div class=" mb-3">
                                            <i class="mdi mdi-book-education-outline"></i>
                                            <span id="show_namasmamasmk3"></span>&nbsp;<br>(<span id="show_tahunsmamasm3"></span>)
                                        </div>
                                        <p style="font-weight: bold">S1/S2/S3</p>
                                        <div class=" mb-3">
                                            <i class="mdi mdi-book-education-outline"></i>
                                            <span id="show_namauniversitas3"></span>&nbsp;<br>(<span id="show_tahununiversitas3"></span>)
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
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="row g-2">
                                                        <div class="col mb-2">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" disabled name="" id="show_nama_nilai_ujian_analogi_verbal_antonim" class="form-control" />
                                                                <label for="holding_recruitment">ANALOGI VERBAL (ANTONIM)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row g-2">
                                                        <div class="col mb-2">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" disabled name="" id="show_nama_nilai_ujian_analogi_verbal_sinonim" class="form-control" />
                                                                <label for="holding_recruitment">ANALOGI VERBAL (SINONIM)</label>
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
                                                                <input type="text" disabled name="" id="show_nama_nilai_ujian_penalaran" class="form-control" />
                                                                <label for="holding_recruitment">PENALARAN ATAU LOGIKA</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row g-2">
                                                        <div class="col mb-2">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" disabled name="" id="show_nama_nilai_ujian_aritmatika" class="form-control" />
                                                                <label for="holding_recruitment">ARITMATIKA</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" disabled name="" id="show_nama_nilai_total_psikotes" class="form-control" />
                                                        <label for="holding_recruitment">TOTAL NILAI PSIKOTES</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <br> -->
                                            <!-- <div class="row g-2">
                                                <div class="col mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <textarea type="text" disabled id="show_catatanujian" name="" class="form-control" style="height: 200px;"></textarea>
                                                        <label for="holding_recruitment">CATATAN</label>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="tab-pane fade" id="tabs-dua" role="tabpanel">
                                            <form method="post" action="{{ url('/nilai-interview-hrd/update/'.$holding) }}" enctype="multipart/form-data">
                                                @csrf
                                                <h6 style="font-weight: bold">PENILAIAN INTERVIEW</h6>
                                                <br>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="hidden" id="show_recruitmentinterviewid1" name="recruitment_interview_id1" class="form-control" />
                                                                    <input id="myRange1" required min="1" max="16" step="1.5" type="range" name="nilai_leadership" class="form-range" value="0" />
                                                                    <button type="button" style="width: 100%;" class="btn btn-outline-primary waves-effect">
                                                                        Nilai Leadership:
                                                                        <span class="badge ms-1" id="show_nilai_leadership"></span>
                                                                    </button>
                                                                </div>
                                                                <br>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catatan_leadership" name="catatan_leadership" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">Catatan Leadership</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input id="myRange2" require min="1" max="16" step="1.5" type="range" name="nilai_planning" class="form-range" value="0" />
                                                                    <button type="button" style="width: 100%;" class="btn btn-outline-primary waves-effect">
                                                                        Nilai Planning:
                                                                        <span class="badge ms-1" id="show_nilai_planning"></span>
                                                                    </button>
                                                                </div>
                                                                <br>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catatan_planning" name="catatan_planning" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">Catatan Planning</label>
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
                                                                    <input id="myRange3" require min="1" max="16" step="1.5" type="range" name="nilai_problemsolving" class="form-range" value="0" />
                                                                    <button type="button" style="width: 100%;" class="btn btn-outline-primary waves-effect">
                                                                        Nilai Problem Solving:
                                                                        <span class="badge ms-1" id="show_nilai_problemsolving"></span>
                                                                    </button>
                                                                </div>
                                                                <br>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catatan_problem_solving" name="catatan_problem_solving" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">Catatan Problem Solving</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input id="myRange4" require min="1" max="16" step="1.5" type="range" name="nilai_quallity" class="form-range" value="0" />
                                                                    <button type="button" style="width: 100%;" class="btn btn-outline-primary waves-effect">
                                                                        Nilai Quality:
                                                                        <span class="badge ms-1" id="show_nilai_quallity"></span>
                                                                    </button>
                                                                </div>
                                                                <br>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catatan_quality" name="catatan_quality" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">Catatan Quality</label>
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
                                                                    <input id="myRange5" require min="1" max="16" step="1.5" type="range" name="nilai_creativity" class="form-range" value="0" />
                                                                    <button type="button" style="width: 100%;" class="btn btn-outline-primary waves-effect">
                                                                        Nilai Creativity:
                                                                        <span class="badge ms-1" id="show_nilai_creativity"></span>
                                                                    </button>
                                                                </div>
                                                                <br>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catatan_creativity" name="catatan_creativity" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">Catatan Creativity</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input id="myRange6" require min="1" max="16" step="1.5" type="range" name="nilai_teamwork" class="form-range" value="0" />
                                                                    <button type="button" style="width: 100%;" class="btn btn-outline-primary waves-effect">
                                                                        Nilai Teamwork:
                                                                        <span class="badge ms-1" id="show_nilai_teamwork"></span>
                                                                    </button>
                                                                </div>
                                                                <br>
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea type="text" required id="show_catatan_teamwork" name="catatan_teamwork" class="form-control" style="height: 100px;"></textarea>
                                                                    <label for="holding_recruitment">Catatan Teamwork</label>
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
                                                                    <input id="myRange7" min="1" max="100" step="1.5" type="range" name="nilai_creativity" class="form-range" value="0" />
                                                                    <button type="button" class="btn btn-outline-primary waves-effect d-flex justify-content-between align-items-center" style="width: 100%;">
                                                                        <span>Total Nilai Interview:</span>
                                                                        <span class="badge ms-1" id="show_total_nilai_interview_hrd"></span>
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-primary waves-effect d-flex justify-content-between align-items-center" style="width: 100%;">
                                                                        <span>Nilai Kehadiran:</span>
                                                                        <span class="badge ms-1" id="show_nilai_kehadiran"></span>
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-primary waves-effect d-flex justify-content-between align-items-center" style="width: 100%;">
                                                                        <span>Total Nilai:</span>
                                                                        <span class="badge ms-1" id="show_total_nilai_hrd"></span>
                                                                    </button>
                                                                    <!-- Nilai Kehadiran:<span class="badge ms-1" id="">4</span> -->
                                                                    <!-- <h4>Nilai Kehadiran: <span id="" style="color: #00a651;">4</span></h4> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="row g-2">
                                                            <div class="col mb-2">
                                                                <div class="form-floating form-floating-outline">
                                                                    <select id="select_status_interview_manager" name="status_interview_manager" require class="form-select form-select-lg">
                                                                        <option>--Pilih--</option>
                                                                        <option value="1">Ya</option>
                                                                        <option value="2">Tidak</option>
                                                                    </select>
                                                                    <label for="holding_recruitment">Interview Manager</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="modal-footer" id="modalFooter">
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
                                                            <input type="hidden" id="show_recruitmentinterviewid2" name="recruitment_interview_id2" class="form-control" />
                                                            <input type="number" required step="0.1" id="show_nilaiinterviewmanager" name="nilai_interview_manager" class="form-control" />
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
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="location.reload();">
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}


<script>
    function updateSliderValue(sliderId, outputId) {
        const slider = document.getElementById(sliderId);
        const output = document.getElementById(outputId);
        output.innerText = slider.valueAsNumber;
        slider.addEventListener('input', () => {
            output.innerText = slider.valueAsNumber;
        });
    }
    updateSliderValue('myRange1', 'show_nilai_leadership');
    updateSliderValue('myRange2', 'show_nilai_planning');
    updateSliderValue('myRange3', 'show_nilai_problemsolving');
    updateSliderValue('myRange4', 'show_nilai_quallity');
    updateSliderValue('myRange5', 'show_nilai_creativity');
    updateSliderValue('myRange6', 'show_nilai_teamwork');
    updateSliderValue('myRange7', 'show_total_nilai_interview_hrd');
</script>
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
            url: "{{ url('dt/data-list-interview') }}" + '/' + id + '/' + holding,
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
                data: 'status_kehadiran',
                name: 'status_kehadiran'
            },
            {
                data: 'ujian',
                name: 'ujian'
            },
            {
                data: 'detail_cv',
                name: 'detail_cv'
            },
            {
                data: 'penilaian',
                name: 'penilaian'
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
        $('#show_gender').val(nama_gender.toUpperCase());
        $('#show_statusnikah').val(nama_statusnikah.toUpperCase());
        $('#show_departemen').text(nama_departemen);
        $('#show_divisi').text(nama_divisi);
        $('#show_bagian').text(nama_bagian);
        $('#show_email').text(nama_email.toUpperCase());
        $('#show_nohp').text(nama_nohp);
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
        $('#show_imgktp').attr('src', 'http://127.0.0.1:8000/images/' + nama_imgktp);
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
        let nama_tahununiversitas = $(this).data('tahun_universitas');

        let nama_imgktp = $(this).data('img_ktp');
        let nama_imgkk = $(this).data('img_kk');
        let nama_imgijazah = $(this).data('img_ijazah');
        let nama_nilai_ujian_analogi_verbal_antonim = $(this).data('nilai_ujian_analogi_verbal_antonim');
        let nama_nilai_ujian_analogi_verbal_sinonim = $(this).data('nilai_ujian_analogi_verbal_sinonim');
        let nama_nilai_ujian_penalaran = $(this).data('nilai_ujian_nilai_penalaran');
        let nama_nilai_ujian_aritmatika = $(this).data('nilai_ujian_nilai_aritmatika');
        let nama_nilai_total_psikotes = $(this).data('nilai_total_psikotes');
        let nama_nilai_kehadiran = $(this).data('nilai_kehadiran');
        let nama_nilai_leadership = $(this).data('nilai_leadership');
        let nama_catatan_leadership = $(this).data('catatan_leadership');
        let nama_nilai_planning = $(this).data('nilai_planning');
        let nama_catatan_planning = $(this).data('catatan_planning');
        let nama_nilai_problemsolving = $(this).data('nilai_problemsolving');
        let nama_catatan_problem_solving = $(this).data('catatan_problem_solving');
        let nama_nilai_quallity = $(this).data('nilai_quallity');
        let nama_catatan_quality = $(this).data('catatan_quality');
        let nama_nilai_creativity = $(this).data('nilai_creativity');
        let nama_catatan_creativity = $(this).data('catatan_creativity');
        let nama_nilai_teamwork = $(this).data('nilai_teamwork');
        let nama_catatan_teamwork = $(this).data('catatan_teamwork');
        let nama_total_nilai_interview_hrd = $(this).data('total_nilai_interview_hrd');
        let nama_nilaiinterviewmanager = $(this).data('nilai_interview_manager');
        let nama_catataninterviewmanager = $(this).data('catatan_interview_manager');
        let nama_pp = $(this).data('img_pp');
        let nama_status_interview = $(this).data('status_interview');
        let nama_statusinterviewmanager = $(this).data('status_interview_manager');
        let holding = $(this).data("holding");
        console.log('a');
        console.log(nama_statusinterviewmanager);
        console.log('b');
        $('#show_recruitmentinterviewid1').val(recruitment_interview_id);
        $('#show_recruitmentinterviewid2').val(recruitment_interview_id);
        $('#show_nama_pelamar3').text(nama_pelamar);

        $('#show_email3').text(nama_email.toUpperCase());
        $('#show_nohp3').text(nama_nohp);
        $('#show_alamatktp3').text(nama_alamatktp.toUpperCase());
        $('#show_namasdmi3').text(nama_sdmi.toUpperCase());
        $('#show_tahunsdmi3').text(nama_tahunsdmi);
        $('#show_namasmpmts3').text(nama_smpmts.toUpperCase());
        $('#show_tahunsmpmts3').text(nama_tahunsmpmts);
        $('#show_namasmamasmk3').text(nama_smamasmk.toUpperCase());
        $('#show_tahunsmamasm3').text(nama_tahunsmamasmk);
        $('#show_namauniversitas3').text(nama_universitas.toUpperCase());
        $('#show_tahununiversitas3').text(nama_tahununiversitas);
        $('#show_nama_nilai_ujian_analogi_verbal_antonim').val(nama_nilai_ujian_analogi_verbal_antonim);
        $('#show_nama_nilai_ujian_analogi_verbal_sinonim').val(nama_nilai_ujian_analogi_verbal_sinonim);
        $('#show_nama_nilai_ujian_penalaran').val(nama_nilai_ujian_penalaran);
        $('#show_nama_nilai_ujian_aritmatika').val(nama_nilai_ujian_aritmatika);
        $('#show_nama_nilai_total_psikotes').val(nama_nilai_total_psikotes);
        $('#show_nilaiinterviewmanager').val(nama_nilaiinterviewmanager);
        $('#show_catataninterviewmanager').text(nama_catataninterviewmanager);
        // console.log(nama_status_interview);
        if (nama_status_interview === 4 || nama_status_interview === 0) {
            $('#show_nilai_leadership').val(nama_nilai_leadership).prop('disabled', true);
            $('#show_catatan_leadership').val(nama_catatan_leadership).prop('disabled', true);
            $('#show_nilai_planning').text(nama_nilai_planning).prop('disabled', true);
            $('#show_catatan_planning').val(nama_catatan_planning).prop('disabled', true);
            $('#show_nilai_problemsolving').text(nama_catatan_problem_solving).prop('disabled', true);
            $('#show_catatan_problem_solving').val(nama_catatan_problem_solving).prop('disabled', true);
            $('#show_nilai_quallity').text(nama_nilai_quallity).prop('disabled', true);
            $('#show_catatan_quality').val(nama_catatan_quality).prop('disabled', true);
            $('#show_nilai_creativity').text(nama_nilai_creativity).prop('disabled', true);
            $('#show_catatan_creativity').val(nama_catatan_creativity).prop('disabled', true);
            $('#show_nilai_teamwork').text(nama_nilai_teamwork).prop('disabled', true);
            $('#show_catatan_teamwork').val(nama_catatan_teamwork).prop('disabled', true);
            $('#myRange7').val(nama_total_nilai_interview_hrd).prop('disabled', true);
            $('#show_total_nilai_interview_hrd').text(nama_total_nilai_interview_hrd);
            $('#myRange7').on('input', function() {
                $('#show_total_nilai_interview_hrd').text($(this).val());
            });
            $('#show_nilai_kehadiran').text(nama_nilai_kehadiran);
            $('#show_total_nilai_hrd').text(nama_nilai_kehadiran + nama_total_nilai_interview_hrd);

            $('input[name="status_interview_manager"]').prop('disabled', true).prop('checked', false);
            $('button[type="submit"]').prop('disabled', true);
        } else if (nama_status_interview === 3) {
            if (nama_nilai_leadership != 0) {
                $('#myRange1').val(nama_nilai_leadership).prop('disabled', true);
                $('#show_nilai_leadership').text(nama_nilai_leadership);
                $('#myRange1').on('input', function() {
                    $('#show_nilai_leadership').text($(this).val());
                });
                $('#show_catatan_leadership').val(nama_catatan_leadership).prop('disabled', true);
            } else {
                $('#myRange1').val(nama_nilai_leadership).prop('disabled', false);
                $('#show_nilai_leadership').text(nama_nilai_leadership);
                $('#myRange1').on('input', function() {
                    $('#show_nilai_leadership').text($(this).val());
                });
                $('#show_catatan_leadership').val(nama_catatan_leadership).prop('disabled', false);
            }
            if (nama_nilai_planning != 0) {
                $('#myRange2').val(nama_nilai_planning).prop('disabled', true);
                $('#show_nilai_planning').text(nama_nilai_planning);
                $('#myRange2').on('input', function() {
                    $('#show_nilai_planning').text($(this).val());
                });
                $('#show_catatan_planning').val(nama_catatan_planning).prop('disabled', true);
            } else {
                $('#myRange2').val(nama_nilai_planning).prop('disabled', false);
                $('#show_nilai_planning').text(nama_nilai_planning);
                $('#myRange2').on('input', function() {
                    $('#show_nilai_planning').text($(this).val());
                });
                $('#show_catatan_planning').val(nama_catatan_planning).prop('disabled', false);
            }
            if (nama_nilai_problemsolving != 0) {
                $('#myRange3').val(nama_nilai_problemsolving).prop('disabled', true);
                $('#show_nilai_problemsolving').text(nama_nilai_problemsolving);
                $('#myRange3').on('input', function() {
                    $('#show_nilai_problemsolving').text($(this).val());
                });
                $('#show_catatan_problem_solving').val(nama_catatan_problem_solving).prop('disabled', true);
            } else {
                $('#myRange3').val(nama_nilai_problemsolving).prop('disabled', false);
                $('#show_nilai_problemsolving').text(nama_nilai_problemsolving);
                $('#myRange3').on('input', function() {
                    $('#show_nilai_problemsolving').text($(this).val());
                });
                $('#show_catatan_problem_solving').val(nama_catatan_problem_solving).prop('disabled', false);
            }
            if (nama_nilai_quallity != 0) {
                $('#myRange4').val(nama_nilai_quallity).prop('disabled', true);
                $('#show_nilai_quallity').text(nama_nilai_quallity);
                $('#myRange4').on('input', function() {
                    $('#show_nilai_quallity').text($(this).val());
                });
                $('#show_catatan_quality').val(nama_catatan_quality).prop('disabled', true);
            } else {
                $('#myRange4').val(nama_nilai_quallity).prop('disabled', false);
                $('#show_nilai_quallity').text(nama_nilai_quallity);
                $('#myRange4').on('input', function() {
                    $('#show_nilai_quallity').text($(this).val());
                });
                $('#show_catatan_quality').val(nama_catatan_quality).prop('disabled', false);
            }
            if (nama_nilai_creativity != 0) {
                $('#myRange5').val(nama_nilai_creativity).prop('disabled', true);
                $('#show_nilai_creativity').text(nama_nilai_creativity);
                $('#myRange5').on('input', function() {
                    $('#show_nilai_creativity').text($(this).val());
                });
                $('#show_catatan_creativity').val(nama_catatan_creativity).prop('disabled', true);
            } else {
                $('#myRange5').val(nama_nilai_creativity).prop('disabled', false);
                $('#show_nilai_creativity').text(nama_nilai_creativity);
                $('#myRange5').on('input', function() {
                    $('#show_nilai_creativity').text($(this).val());
                });
                $('#show_catatan_creativity').val(nama_catatan_creativity).prop('disabled', false);
            }
            if (nama_nilai_teamwork != 0) {
                $('#myRange6').val(nama_nilai_teamwork).prop('disabled', true);
                $('#show_nilai_teamwork').text(nama_nilai_teamwork);
                $('#myRange6').on('input', function() {
                    $('#show_nilai_teamwork').text($(this).val());
                });
                $('#show_catatan_teamwork').val(nama_catatan_teamwork).prop('disabled', true);
            } else {
                $('#myRange6').val(nama_nilai_teamwork).prop('disabled', false);
                $('#show_nilai_teamwork').text(nama_nilai_teamwork);
                $('#myRange6').on('input', function() {
                    $('#show_nilai_teamwork').text($(this).val());
                });
                $('#show_catatan_teamwork').val(nama_catatan_teamwork).prop('disabled', false);
            }
            if (nama_nilai_teamwork != 0 && nama_nilai_creativity != 0 && nama_nilai_quallity !== 0 && nama_nilai_problemsolving != 0 && nama_nilai_planning != 0 && nama_nilai_leadership != 0) {
                $('#myRange7').val(nama_total_nilai_interview_hrd).prop('disabled', true);
                $('#show_total_nilai_interview_hrd').text(nama_total_nilai_interview_hrd);
                $('#myRange7').on('input', function() {
                    $('#show_total_nilai_interview_hrd').text($(this).val());
                });

                $('#show_nilai_kehadiran').text(nama_nilai_kehadiran);
                $('#show_total_nilai_hrd').text(nama_nilai_kehadiran + nama_total_nilai_interview_hrd);
                $('#modalFooter').hide();
            } else {
                $('#myRange7').val(nama_total_nilai_interview_hrd).prop('disabled', true);
                $('#show_total_nilai_interview_hrd').text(nama_total_nilai_interview_hrd);
                $('#myRange7').on('input', function() {
                    $('#show_total_nilai_interview_hrd').text($(this).val());
                });
                $('#show_nilai_kehadiran').text(nama_nilai_kehadiran);
                $('#show_total_nilai_hrd').text(nama_nilai_kehadiran + nama_total_nilai_interview_hrd);
                $('#modalFooter').show();
            }

            // $('button[type="submit"]').prop('disabled', false);
            $('input[name="status_interview_manager"]').prop('disabled', false);
            if (nama_statusinterviewmanager != 0) {
                if (nama_statusinterviewmanager == 1) {
                    $('#select_status_interview_manager').val('1').prop('disabled', true);
                } else if (nama_statusinterviewmanager == 2) {
                    $('#select_status_interview_manager').val('2').prop('disabled', true);
                } else {
                    $('#select_status_interview_manager').val('--Pilih--').prop('disabled', true);
                }
            } else {
                $('#select_status_interview_manager').val('--Pilih--').prop('disabled', false);
            }
        }
        if (nama_pp == "") {
            $('#show_imgpp3').attr('src', 'http://127.0.0.1:8000/admin/assets/img/avatars/1.png');
        } else {
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
    $(document).on('click', '#btn_ujian', function() {
        let recruitment_user_id = $(this).data('id_recruitment_user');
        let users_career_id = $(this).data('id_users_career');
        let users_auth_id = $(this).data('id_users_auth');
        $('#show_recruitmentuserid').val(recruitment_user_id);
        $('#show_userscareerid').val(users_career_id);
        $('#modal_ujian').modal('show');

    });
    $(document).on('click', '#btn_prosesujian', function() {
        $('#modal_prosesujian').modal('show');
    });
    $(document).on('click', '#btn_warning', function() {
        $('#modal_warning').modal('show');
    });
</script>
@endsection