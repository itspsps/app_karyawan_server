@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <style type="text/css">
        .swal2-container {
            z-index: 9999 !important;
        }

        .text-center {
            text-align: center;
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
                                                                                                                                                                                                                                                                                                <<<<<<< HEAD
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        float: right;*/
            =======float: right;
            */>>>>>>>35c7aa3d6b16d59fd98c381919fe2f71c0ddf2f9 margin-top: 5px;
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
                <div class="container card p-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ranking-tab" data-bs-toggle="tab"
                                data-bs-target="#ranking-tab-pane" type="button" role="tab"
                                aria-controls="ranking-tab-pane" aria-selected="false">Ranking</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="progres-tab" data-bs-toggle="tab"
                                data-bs-target="#progres-tab-pane" type="button" role="tab"
                                aria-controls="progres-tab-pane" aria-selected="true">Progress</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="progres-tab-pane" role="tabpanel"
                            aria-labelledby="progres-tab" tabindex="0">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="card-title m-0 me-2">DATA PROGRES RECRUITMENT</h5>
                                </div>
                            </div>
                            <table class="table" id="tabel_progres" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Ranking.</th>
                                        <th>Pelamar</th>
                                        <th>Total Koefisien</th>
                                        <th>Aksi</th>
                                        <th>Status</th>
                                        <th>Konfirmasi Pelamar</th>
                                        <th>Alasan</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="ranking-tab-pane" role="tabpanel" aria-labelledby="progres-tab"
                            tabindex="0">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="card-title m-0 me-2">DATA RANKING RECRUITMENT</h5>
                                </div>
                            </div>
                            <table class="table" id="tabel_ranking" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Pelamar</th>
                                        <th>Total Koefisien</th>
                                        <th>Esai Average</th>
                                        <th>Bobot Esai</th>
                                        <th>pilihan ganda Average</th>
                                        <th>Bobot Pilihan Ganda</th>
                                        <th>Interview Average</th>
                                        <th>Bobot Interview</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
            <!--/ Data Tables -->
        </div>
    </div>
    <div class="modal fade" id="modal_status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">STATUS CALON KARYAWAN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_update" name="id">
                    <div class="form-floating form-floating-outline py-3">

                        <select class="form-select" id="status_update" name="status">
                            <label for="bagian_recruitment">PILIH STATUS</label>
                            <option value="1b" selected>LOLOS INTERVIEW MANAGER</option>
                            <option value="2b">LOLOS LANGSUNG</option>
                            <option value="3b">TIDAK LOLOS</option>
                        </select>
                    </div>
                    <div id="lolos_manager">
                        <div class="form-floating form-floating-outline py-3">
                            <input type="date" id="tanggal_wawancara" name="tanggal_wawancara"
                                class="form-control @error('tanggal_wawancara') is-invalid @enderror" placeholder="Tanggal"
                                value="{{ old('tanggal_wawancara') }}" />
                            <label for="bagian_recruitment">TANGGAL WAWANCARA</label>
                        </div>
                        <div class="form-floating form-floating-outline py-3">
                            <select class="form-select @error('online') is-invalid @enderror" id="online_add" name="online"
                                autofocus value="{{ old('online') }}">
                                <option value="1" selected>OFFLINE</option>
                                <option value="2">ONLINE</option>
                            </select>
                            <label for="penempatan">KEPUTUSAN HRD</label>
                            @error('online')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @error('tanggal_wawancara')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div id="tempat_wawancara_form">
                            <label for="bagian_recruitment px-2"><small>TEMPAT
                                    WAWANCARA</small></label>
                            <div class="form-floating form-floating-outline mb-2">

                                <input type="text" id="tempat_wawancara" name="tempat_wawancara"
                                    class="form-control @error('tempat_wawancara') is-invalid @enderror"
                                    value="{{ old('tempat_wawancara') }}" />
                            </div>
                        </div>
                        <div id="link_wawancara_form">
                            <label for="bagian_recruitment px-2"><small>LINK
                                    WAWANCARA ONLINE</small></label>
                            <div class="form-floating form-floating-outline mb-2">

                                <input type="text" id="link_wawancara" name="link_wawancara"
                                    class="form-control @error('link_wawancara') is-invalid @enderror"
                                    value="{{ old('link_wawancara') }}" />
                            </div>
                        </div>

                        @error('link_wawancara')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <label for="bagian_recruitment"><small>WAKTU (JAM)
                                WAWANCARA</small></label>
                        <div class="form-floating form-floating-outline ">

                            <input type="time" id="waktu_wawancara" name="waktu_wawancara"
                                class="form-control @error('waktu_wawancara') is-invalid @enderror"
                                value="{{ old('waktu_wawancara') }}" />
                        </div>
                        @error('waktu_wawancara')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div id="lolos_langsung">
                        <div class="form-floating form-floating-outline py-3">
                            <input type="date" id="tanggal_diterima_update" name="tanggal_diterima"
                                class="form-control @error('tanggal_diterima') is-invalid @enderror" placeholder="Tanggal"
                                value="{{ old('tanggal_diterima') }}" />
                            <label for="bagian_recruitment">TANGGAL MASUK KERJA</label>
                        </div>
                        {{-- <label for="bagian_recruitment px-2"><small>GAJI (Rp)</small></label> --}}
                        {{-- <div class="form-floating form-floating-outline mb-2">

                            <input type="text" id="gaji_update" name="gaji"
                                class="form-control @error('gaji') is-invalid @enderror" value="{{ old('gaji') }}" />
                </div> --}}
                        <label for="bagian_recruitment px-2"><small>TEMPAT BEKERJA</small></label>
                        <div class="form-floating form-floating-outline mb-2">

                            <input type="text" id="tempat_bekerja_update" name="tempat_bekerja"
                                class="form-control @error('tempat_bekerja') is-invalid @enderror"
                                value="{{ old('tempat_bekerja') }}" />
                        </div>
                        <label for="bagian_recruitment px-2"><small>WAKTU BEKERJA</small></label>
                        <div class="form-floating form-floating-outline mb-2">

                            <input type="time" id="waktu_bekerja_update" name="WAKTU_bekerja"
                                class="form-control @error('WAKTU_bekerja') is-invalid @enderror"
                                value="{{ old('WAKTU_bekerja') }}" />
                        </div>
                        <label for="bagian_recruitment px-2"><small>NOTES</small></label>
                        <div class="form-floating form-floating-outline mb-2">
                            <textarea type="text" id="notes_langsung_update" name="notes_langsung"
                                class="form-control @error('notes_langsung') is-invalid @enderror" value="{{ old('notes_langsung') }}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_status">submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_lolos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Lolos Bekerja</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_lolos" name="id">
                    <div class="form-floating form-floating-outline py-3">
                        <input type="hidden" value="2b" name="status" id="status_lolos">
                    </div>
                    <div class="form-floating form-floating-outline py-3">
                        <input type="date" id="tanggal_diterima_lolos" name="tanggal_diterima"
                            class="form-control @error('tanggal_diterima') is-invalid @enderror" placeholder="Tanggal"
                            value="{{ old('tanggal_diterima') }}" />
                        <label for="bagian_recruitment">TANGGAL MASUK KERJA</label>
                    </div>
                    {{-- <label for="bagian_recruitment px-2"><small>GAJI (Rp)</small></label>
                    <div class="form-floating form-floating-outline mb-2">

                        <input type="text" id="gaji_lolos" name="gaji"
                            class="form-control @error('gaji') is-invalid @enderror" value="{{ old('gaji') }}" />
            </div> --}}
                    <label for="bagian_recruitment px-2"><small>TEMPAT BEKERJA</small></label>
                    <div class="form-floating form-floating-outline mb-2">

                        <input type="text" id="tempat_bekerja_lolos" name="tempat_bekerja"
                            class="form-control @error('tempat_bekerja') is-invalid @enderror"
                            value="{{ old('tempat_bekerja') }}" />
                    </div>
                    <label for="bagian_recruitment px-2"><small>WAKTU BEKERJA</small></label>
                    <div class="form-floating form-floating-outline mb-2">

                        <input type="time" id="waktu_bekerja_lolos" name="waktu_bekerja"
                            class="form-control @error('waktu_bekerja') is-invalid @enderror"
                            value="{{ old('waktu_bekerja') }}" />
                    </div>
                    <label for="bagian_recruitment px-2"><small>TEMPAT BEKERJA</small></label>
                    <div class="form-floating form-floating-outline mb-2">

                        <input type="text" id="tempat_bekerja_lolos" name="tempat_bekerja"
                            class="form-control @error('tempat_bekerja') is-invalid @enderror"
                            value="{{ old('tempat_bekerja') }}" />
                    </div>
                    <label for="bagian_recruitment px-2"><small>NOTES</small></label>
                    <div class="form-floating form-floating-outline mb-2">
                        <textarea type="text" id="notes_langsung_lolos" name="notes_langsung"
                            class="form-control @error('notes_langsung') is-invalid @enderror" value="{{ old('notes_langsung') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_lolos">submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_pindah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pemindahan Lowongan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating form-floating-outline py-3">
                        <input type="hidden" id="id_pindah" name="id">
                        <input type="hidden" value="{{ $id_recruitment }}" id="lowongan_lama_pindah"
                            name="lowongan_lama">

                        <select class="form-select" id="lowongan_baru_pindah" name="lowongan_baru">
                            <label for="bagian_recruitment">PILIH LOWONGAN</label>
                            <option value="" selected>PILIH LOWONGAN</option>
                            @foreach ($recruitment_admin as $admin)
                                <option value="{{ $admin->id }}">
                                    {{ $admin->jabatan->nama_jabatan }},
                                    {{ $admin->jabatan->bagian->nama_bagian }},
                                    {{ $admin->jabatan->bagian->divisi->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating form-floating-outline py-3">
                        <select class="form-select" id="status_pindah" name="status">
                            <label for="bagian_recruitment">PILIH STATUS</label>
                            <option value="6b" selected>LOLOS INTERVIEW MANAGER</option>
                            <option value="7b">LOLOS LANGSUNG</option>
                        </select>
                    </div>
                    <div id="pindah_manager">
                        <div class="form-floating form-floating-outline py-3">
                            <input type="date" id="tanggal_wawancara_pindah" name="tanggal_wawancara"
                                class="form-control @error('tanggal_wawancara') is-invalid @enderror"
                                placeholder="Tanggal" value="{{ old('tanggal_wawancara') }}" />
                            <label for="bagian_recruitment">TANGGAL WAWANCARA</label>
                        </div>
                        <div class="form-floating form-floating-outline py-3">
                            <select class="form-select @error('online') is-invalid @enderror" id="online_add2"
                                name="online" autofocus value="{{ old('online') }}">
                                <option value="1" selected>OFFLINE</option>
                                <option value="2">ONLINE</option>
                            </select>
                            <label for="penempatan">KEPUTUSAN HRD</label>
                            @error('online')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @error('tanggal_wawancara')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div id="tempat_wawancara_form2">
                            <label for="bagian_recruitment px-2"><small>TEMPAT
                                    WAWANCARA</small></label>
                            <div class="form-floating form-floating-outline mb-2">

                                <input type="text" id="tempat_wawancara2" name="tempat_wawancara"
                                    class="form-control @error('tempat_wawancara2') is-invalid @enderror"
                                    value="{{ old('tempat_wawancara2') }}" />
                            </div>
                        </div>
                        <div id="link_wawancara_form2">
                            <label for="bagian_recruitment px-2"><small>LINK
                                    WAWANCARA ONLINE</small></label>
                            <div class="form-floating form-floating-outline mb-2">

                                <input type="text" id="link_wawancara2" name="link_wawancara"
                                    class="form-control @error('link_wawancara') is-invalid @enderror"
                                    value="{{ old('link_wawancara') }}" />
                            </div>
                        </div>
                        @error('link_wawancara2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <label for="bagian_recruitment"><small>WAKTU (JAM)
                                WAWANCARA</small></label>
                        <div class="form-floating form-floating-outline ">

                            <input type="time" id="waktu_wawancara_pindah" name="waktu_wawancara"
                                class="form-control @error('waktu_wawancara') is-invalid @enderror"
                                value="{{ old('waktu_wawancara') }}" />
                        </div>
                        @error('waktu_wawancara')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div id="pindah_langsung">
                        <div class="form-floating form-floating-outline py-3">
                            <input type="date" id="tanggal_diterima_pindah" name="tanggal_diterima"
                                class="form-control @error('tanggal_diterima') is-invalid @enderror"
                                placeholder="Tanggal" value="{{ old('tanggal_diterima') }}" />
                            <label for="bagian_recruitment">TANGGAL MASUK KERJA</label>
                        </div>
                        {{-- <label for="bagian_recruitment px-2"><small>GAJI (Rp)</small></label>
                        <div class="form-floating form-floating-outline mb-2">
                            <input type="text" id="gaji_pindah" name="gaji"
                                class="form-control @error('gaji') is-invalid @enderror" value="{{ old('gaji') }}" />
                </div> --}}
                        <label for="bagian_recruitment px-2"><small>WAKTU BEKERJA</small></label>
                        <div class="form-floating form-floating-outline mb-2">

                            <input type="time" id="waktu_bekerja_pindah" name="waktu_bekerja"
                                class="form-control @error('waktu_bekerja') is-invalid @enderror"
                                value="{{ old('waktu_bekerja') }}" />
                        </div>
                        <label for="bagian_recruitment px-2"><small>TEMPAT BEKERJA</small></label>
                        <div class="form-floating form-floating-outline mb-2">

                            <input type="text" id="tempat_bekerja_pindah" name="tempat_bekerja"
                                class="form-control @error('tempat_bekerja') is-invalid @enderror"
                                value="{{ old('tempat_bekerja') }}" />
                        </div>
                        <label for="bagian_recruitment px-2"><small>NOTES</small></label>
                        <div class="form-floating form-floating-outline mb-2">
                            <textarea type="text" id="notes_langsung_pindah" name="notes_pindah"
                                class="form-control @error('notes_langsung') is-invalid @enderror" value="{{ old('notes_langsung') }}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_pindah">submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_integrasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Integrasi ke Data Karyawan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_integrasi" name="id">
                    <select class="form-select @error('online') is-invalid @enderror" id="pilihan_add" name="pilihan"
                        autofocus value="{{ old('online') }}">
                        <option value="1" selected>YA</option>
                        <option value="2">TIDAK</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_integrasi">submit</button>
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
        // $(document).on('keyup', '#gaji_update', function(e) {
        //     var data = $(this).val();
        //     var hasil = formatRupiah(data, "Rp. ");
        //     $(this).val(hasil);
        // });

        // function formatRupiah(angka, prefix) {
        //     var number_string = angka.replace(/[^,\d]/g, '').toString(),
        //         split = number_string.split(','),
        //         sisa = split[0].length % 3,
        //         rupiah = split[0].substr(0, sisa),
        //         ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        //     if (ribuan) {
        //         separator = sisa ? '.' : '';
        //         rupiah += separator + ribuan.join('.');
        //     }

        //     rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        //     return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        // }

        // function replace_titik(x) {
        //     return ((x.replace('.', '')).replace('.', '')).replace('.', '');
        // }
        let id = @json($id_recruitment);
        console.log(id);
        var table = $('#tabel_ranking').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('dt/data-list-ranking') }}" + '/' + id + '/' + holding,
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'total_koefisien',
                    name: 'total_koefisien'
                },
                {
                    data: 'esai_average',
                    name: 'esai_average'
                },
                {
                    data: 'bobot_esai',
                    name: 'bobot_esai'
                },
                {
                    data: 'pg_average',
                    name: 'pg_average'
                },
                {
                    data: 'bobot_pg',
                    name: 'bobot_pg'
                },
                {
                    data: 'interview_average',
                    name: 'interview_average'
                },
                {
                    data: 'bobot_interview',
                    name: 'bobot_interview'
                },

            ],
            order: [
                [2, 'desc']
            ]
        });
        $('#ranking-tab').on('shown.bs.tab', function(e) {
            table.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table2 = $('#tabel_progres').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('dt/data-list-progres') }}" + '/' + id + '/' + holding,
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'total_koefisien',
                    name: 'total_koefisien'
                },
                {
                    data: 'pilih_status',
                    name: 'pilih_status'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'feedback',
                    name: 'feedback'
                },
                {
                    data: 'alasan_lanjutan',
                    name: 'alasan_lanjutan'
                },
            ],
            order: [
                [2, 'desc']
            ]
        });
        $('#progres-tab').on('shown.bs.tab', function(e) {
            table2.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        $(document).on('click', '#btn_status_ranking', function() {
            console.log('asooy');
            var id = $(this).data('id');
            $('#id_update').val(id);
            $('#modal_status').modal('show');

        });
        $(document).on('click', '#btn_lolos', function() {
            console.log('asooy');
            var id = $(this).data('id');
            $('#id_lolos').val(id);
            $('#modal_lolos').modal('show');

        });
        $(document).on('click', '#btn_pemindahan', function() {
            console.log('asooy');
            var id = $(this).data('id');
            $('#id_pindah').val(id);
            $('#modal_pindah').modal('show');

        });
        $(document).on('click', '#btn_integrasi', function() {
            console.log('asooy44');
            var id = $(this).data('id');
            $('#id_integrasi').val(id);
            $('#modal_integrasi').modal('show');

        });
        $('#btn_save_status').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_update').val());
            formData.append('status', $('#status_update').val());
            formData.append('tempat_bekerja', $('#tempat_bekerja_update').val());
            formData.append('waktu_bekerja', $('#waktu_bekerja_update').val());
            formData.append('tanggal_diterima', $('#tanggal_diterima_update').val());
            formData.append('tanggal_wawancara', $('#tanggal_wawancara').val());
            formData.append('online', $('#online_add').val());
            formData.append('tempat_wawancara', $('#tempat_wawancara').val());
            formData.append('link_wawancara', $('#link_wawancara').val());
            formData.append('waktu_wawancara', $('#waktu_wawancara').val());
            formData.append('notes_langsung', $('#notes_langsung_update').val());
            formData.append('holding', holding);
            $.ajax({
                type: "POST",

                url: "{{ url('/dt/data-interview/ranking_update_status') }} ",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_status').modal('hide');
                        $('#tanggal_diterima_update').val('');
                        $('#notes_langsung_update').val('');
                        $('#tempat_bekerja_update').val('');
                        $('#waktu_bekerja_update').val('');
                        $('#tanggal_wawancara').val('');
                        $('#tempat_wawancara').val('');
                        $('#link_wawancara').val('');
                        $('#waktu_wawancara').val('');
                        $('#tabel_progres').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })
                        $('#modal_status').modal('hide');
                    }
                }

            });
        });
        $('#btn_save_lolos').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_lolos').val());
            formData.append('status', $('#status_lolos').val());
            formData.append('tempat_bekerja', $('#tempat_bekerja_lolos').val());
            formData.append('waktu_bekerja', $('#waktu_bekerja_lolos').val());
            formData.append('tanggal_diterima', $('#tanggal_diterima_lolos').val());
            formData.append('notes_langsung', $('#notes_langsung_lolos').val());
            formData.append('holding', holding);
            $.ajax({
                type: "POST",
                url: "{{ url('/dt/data-interview/ranking_update_status') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_lolos').modal('hide');
                        $('#tanggal_diterima_lolos').val('');
                        $('#notes_langsung_lolos').val('');
                        $('#tempat_bekerja_lolos').val('');
                        $('#waktu_bekerja_lolos').val('');
                        $('#tabel_progres').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })
                        $('#modal_lolos').modal('hide');
                    }
                }

            });
        });
        $('#btn_save_pindah').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_pindah').val());
            formData.append('lowongan_baru', $('#lowongan_baru_pindah').val());
            formData.append('lowongan_lama', $('#lowongan_lama_pindah').val());
            formData.append('status', $('#status_pindah').val());
            formData.append('tempat_bekerja', $('#tempat_bekerja_pindah').val());
            formData.append('waktu_bekerja', $('#waktu_bekerja_pindah').val());
            formData.append('tanggal_wawancara', $('#tanggal_wawancara_pindah').val());
            formData.append('online', $('#online_add2').val());
            formData.append('tempat_wawancara', $('#tempat_wawancara2').val());
            formData.append('link_wawancara', $('#link_wawancara2').val());
            formData.append('tanggal_diterima', $('#tanggal_diterima_pindah').val());
            formData.append('waktu_wawancara', $('#waktu_wawancara_pindah').val());
            formData.append('notes_pindah', $('#notes_langsung_pindah').val());
            formData.append('holding', holding);


            $.ajax({
                type: "POST",

                url: "{{ url('/dt/data-interview/ranking_update_status') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_pindah').modal('hide');
                        $('#lowongan_baru_pindah').val('');
                        $('#tanggal_wawancara_pindah').val('');
                        $('#tabel_progres').DataTable().ajax.reload();
                        $('#tempat_wawancara2').val('');
                        $('#link_wawancara2').val('');
                        $('#tempat_bekerja_pindah').val('');
                        $('#waktu_bekerja_pindah').val('');
                        $('#tanggal_diterima_pindah').val('');
                        $('#waktu_wawancara_pindah').val('');
                        $('#notes_langsung_pindah').val('');
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })
                        $('#modal_pindah').modal('hide');
                    }
                }

            });
        });
        $('#btn_save_integrasi').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_integrasi').val());
            formData.append('pilihan', $('#pilihan_add').val());
            $.ajax({
                type: "POST",
                url: "{{ url('/dt/data-interview/integrasi') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_integrasi').modal('hide');
                        $('#tabel_progres').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })
                        $('#modal_lolos').modal('hide');
                    }
                }

            });
        });
        $('#lolos_langsung').hide();
        $('#status_update').on('change', function() {
            let value = $(this).val();
            if (value == '') {
                $('#lolos_langsung').hide();
                $('#tanggal_wawancara').val('');
                $('#tempat_wawancara').val('');
                $('#waktu_wawancara').val('');

            } else if (value == '1b') {
                $('#lolos_manager').show();
                $('#lolos_langsung').hide();

            } else if (value == '2b') {
                $('#lolos_langsung').show();
                $('#lolos_manager').hide();
            } else {
                $('#lolos_manager').hide();
                $('#lolos_langsung').hide();
                $('#tanggal_wawancara').val('');
                $('#tempat_wawancara').val('');
                $('#waktu_wawancara').val('');
            }
        });
        $('#link_wawancara_form').hide();
        $('#online_add').on('change', function() {
            let value = $(this).val();
            if (value == '2') {
                $('#link_wawancara_form').show();
                $('#tempat_wawancara_form').hide();
            } else {
                $('#link_wawancara_form').hide();
                $('#tempat_wawancara_form').show();
            }
        });
        $('#pindah_langsung').hide();
        $('#status_pindah').on('change', function() {
            let value = $(this).val();
            if (value == '') {
                $('#pindah_langsung').hide();
                $('#tanggal_wawancara').val('');
                $('#tempat_wawancara').val('');
                $('#waktu_wawancara').val('');

            } else if (value == '6b') {
                $('#pindah_manager').show();
                $('#pindah_langsung').hide();

            } else if (value == '7b') {
                $('#pindah_langsung').show();
                $('#pindah_manager').hide();
            }
        });
        $('#link_wawancara_form2').hide();
        $('#online_add2').on('change', function() {
            let value = $(this).val();
            if (value == '2') {
                $('#link_wawancara_form2').show();
                $('#tempat_wawancara_form2').hide();
            } else {
                $('#link_wawancara_form2').hide();
                $('#tempat_wawancara_form2').show();
            }
        });
    </script>
    </script>
    {{-- end datatable  --}}
@endsection
