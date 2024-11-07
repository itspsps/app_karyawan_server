@extends('users.penugasan.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .kbw-signature {
        width: fit-content;
        height: 100%;
    }

    #sig canvas {
        margin-top: 5px;
        width: 100%;
        height: auto;
    }
</style>
<!-- Banner -->
<div class="head-details">
    <div class=" container">
        <div class="dz-info">
            <span class="location d-block">Form Penugasan
                @if($user->kontrak_kerja == 'CV. SUMBER PANGAN')
                CV. SUMBER PANGAN
                @elseif($user->kontrak_kerja == 'PT. SURYA PANGAN SEMESTA')
                PT. SURYA PANGAN SEMESTA
                @endif
            </span>
            {{-- @foreach ($user  as $dep) --}}
            <h5 class="title">Department of "{{ $user->nama_departemen }}"</h5>
            {{-- @endforeach --}}
        </div>
        <div class="dz-media media-65">
            <img src="assets/images/logo/logo.svg" alt="">
        </div>
    </div>
</div>

<div class="fixed-content p-0">
    <div class="container">
        <div class="main-content">
            <div class="left-content">
                <a id="btn_klik" href="{{url('/home')}}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.03033 0.46967C9.2966 0.735936 9.3208 1.1526 9.10295 1.44621L9.03033 1.53033L2.561 8L9.03033 14.4697C9.2966 14.7359 9.3208 15.1526 9.10295 15.4462L9.03033 15.5303C8.76406 15.7966 8.3474 15.8208 8.05379 15.6029L7.96967 15.5303L0.96967 8.53033C0.703403 8.26406 0.679197 7.8474 0.897052 7.55379L0.96967 7.46967L7.96967 0.46967C8.26256 0.176777 8.73744 0.176777 9.03033 0.46967Z" fill="#a19fa8" />
                    </svg>
                </a>
                <h5 class="mb-0">Back</h5>
            </div>
            <div class="mid-content">
            </div>
        </div>
    </div>
</div>
<!-- Banner End -->

<div class="container">
    @if(Session::has('penugasansukses'))
    <div class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Pengajuan Perjalanan Dinas
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif(Session::has('updatesukses'))
    <div class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Update Perjalanan Dinas
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif(Session::has('hapussukses'))
    <div class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Hapus Perjalanan Dinas
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif(Session::has('penugasangagal1'))
    <div class="alert alert-danger light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <strong>Gagal!</strong> Tanggal Pengajuan Anda Sudah Terlewat.
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <a href="javascript:void(0);" id="addForm" style="width: 80%; height: 100px; margin-left: 10%;margin-right: 10%" data-bs-toggle="modal" data-bs-target="#modal_pengajuan_penugasan">
        <div class="card bg-primary">
            <div class="card-body" style="margin-top: 0%; text-align: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="40" height="40" viewBox="0 0 24 24" id="add-file-5" data-name="Line Color" class="icon line-color">
                    <path id="secondary" d="M16,19h4m-2-2v4M8,13h6m0-4H8" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;" />
                    <path id="primary" d="M18,13V5L16,3H5A1,1,0,0,0,4,4V20a1,1,0,0,0,1,1h7" style="fill: none; stroke: rgb(255,255,255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;" />
                    <polygon id="primary-2" data-name="primary" points="16 3 16 5 18 5 16 3" style="fill: none; stroke: rgb(255,255,255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;" />
                </svg>
                <div class="card-info">
                    <h5 style="color: white;">TAMBAH PENGAJUAN PENUGASAN</h5>
                </div>
            </div>
        </div>
    </a>
    <script>
        $('button').click(function() {
            $('#myModal').modal('show');
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="modal_pengajuan_penugasan">
        <div class="modal-dialog modal-dialog-centered" role="document" aria-hidden="true">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Penugasan</h5>
                    <button class="btn-close" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form class="my-2" method="post" action="{{ url('/penugasan/tambah-penugasan-proses/') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @method('put')
                        @csrf
                        @if($getUserAtasan==NULL)
                        <div class="alert alert-danger light alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            <strong>Info!</strong>&nbsp;Karyawan Atasan Kosong.
                            <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        @else
                        @endif
                        <div class="input-group">
                            <input type="hidden" name="id_user" value="{{ $user_karyawan->id }}">
                            {{-- <input type="hidden" name="telp" value="{{ $data_user->telepon }}"> --}}
                            {{-- <input type="hidden" name="email" value="{{ $data_user->email }}"> --}}
                            {{-- <input type="hidden" name="departements" value="{{ $user->dept_id }}"> --}}
                            {{-- <input type="hidden" name="jabatan" value="{{ $user->jabatan_id }}"> --}}
                            {{-- <input type="hidden" name="divisi" value="{{ $user->divisi_id }}" id=""> --}}
                            <input type="hidden" name="id_user_atasan" value="@if($getUserAtasan==NULL)@else{{ $getUserAtasan->id }}@endif">
                            <input type="hidden" name="nik" value="{{ $user_karyawan->nik }}">
                            <input type="hidden" name="id_jabatan" value="{{ $user->jabatan_id }}">
                            <input type="hidden" name="id_departemen" value="{{ $user->dept_id }}">
                            <input type="hidden" name="id_divisi" value="{{ $user->divisi_id }}">
                            <input type="hidden" name="id_diajukan_oleh" value="{{ $user_karyawan->id }}">
                            <input type="hidden" name="id_disahkan_oleh" value="@if($getUserAtasan==NULL)@else{{ $getUserAtasan->id }}@endif">
                            <input type="hidden" name="proses_hrd" value="proses hrd">
                            <input type="hidden" name="proses_finance" value="proses finance">
                            <input type="hidden" name="tanggal_pengajuan" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="NIK" readonly>
                            <input type="text" class="form-control" name="" value="{{ $user_karyawan->nik }}" style="font-weight: bold" readonly required>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Nama" readonly>
                            <input type="text" class="form-control" name="" value="{{ $user_karyawan->name }}" style="font-weight: bold" readonly required>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Jabatan" readonly>
                            <input type="text" class="form-control" name="" value="{{ $user->nama_jabatan }}" style="font-weight: bold" readonly required>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Departemen" readonly>
                            <input type="text" class="form-control" name="" value="{{ $user->nama_departemen }}" style="font-weight: bold" readonly required>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Divisi" readonly>
                            <input type="text" class="form-control" name="" value="{{ $user->nama_divisi }}" style="font-weight: bold" readonly required>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Asal Kerja" readonly>
                            <select class="form-control" id="asal_kerja" name="asal_kerja" required>
                                <option value="">Pilih Asal Kerja...</option>
                                @foreach($master_lokasi as $lokasi)
                                <option value="{{$lokasi->lokasi_kantor}}">{{$lokasi->lokasi_kantor}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Lokasi Penugasan" readonly>
                            <select class="form-control" name="penugasan" required>
                                <option value="">Pilih Penugasan...</option>
                                <option value="Dalam Kota">Dalam Kota</option>
                                <option value="Luar Kota">Luar Kota</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Wilayah Penugasan" readonly>
                            <select class="form-control" id="wilayah_penugasan" name="wilayah_penugasan" required>
                                <option value="">Wilayah Penugasan...</option>
                                <option value="Wilayah Kantor">Wilayah Kantor</option>
                                <option value="Diluar Kantor">Diluar Kantor</option>
                            </select>
                        </div>
                        <div id="alamat_dikunjungi" class="input-group">
                            <input type="text" class="form-control" value="Lokasi Kantor" readonly>
                            <select class="form-control" id="alamat_kunjungan" name="alamat_dikunjungi" style="font-weight: bold">
                                <option selected disabled value="">-- Pilih Kantor --</option>
                                @foreach($lokasi_kantor as $lokasi)
                                <option value="{{$lokasi->lokasi_kantor}}">{{$lokasi->lokasi_kantor}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="alamat_dikunjungi1" class="input-group">
                            <input type="text" class="form-control" value="Alamat" readonly>
                            <input type="text" class="form-control" name="alamat_dikunjungi1" style="font-weight: bold">
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="PIC dikunjungi" readonly>
                            <input type="text" class="form-control" name="pic_dikunjungi" style="font-weight: bold" required>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Tanggal Kunjungan" readonly>
                            <input type="text" id="tanggal_kunjungan" name="tanggal_kunjungan" style="font-weight: bold" readonly placeholder="Tanggal" class="form-control">
                        </div>
                        <!-- <div class="input-group">
                            <input type="text" class="form-control" value="Selesai Kunjungan" readonly>
                            <input type="date" name="selesai_kunjungan" style="font-weight: bold" required placeholder="Phone number" class="form-control">
                        </div> -->
                        <div class="input-group">
                            <textarea class="form-control" name="kegiatan_penugasan" style="font-weight: bold" required placeholder="Kegiatan penugasan"></textarea>
                        </div>
                        <hr>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Transportasi" readonly>
                            <select class="form-control" name="transportasi" required>
                                <option value="">Pilih Transportasi...</option>
                                <option value="Pesawat">Pesawat</option>
                                <option value="Kereta Api">Kereta Api</option>
                                <option value="Bis">Bis</option>
                                <option value="Travel">Travel</option>
                                <option value="SPD Motor">SPD Motor</option>
                                <option value="Mobil Dinas">Mobil Dinas</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Kelas" readonly>
                            <select class="form-control" name="kelas" required>
                                <option value="">Pilih Kelas...</option>
                                <option value="Eksekutif">Eksekutif</option>
                                <option value="Bisnis">Bisnis</option>
                                <option value="Ekonomi">Ekonomi</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Budget Hotel" readonly>
                            <select class="form-control" name="budget_hotel" required>
                                <option value="">Pilih Budget...</option>
                                <option value="400.000 sd 500.000">Rp 400.000 sd 500.000</option>
                                <option value="300.000 sd 400.000">Rp 300.000 sd 400.000</option>
                                <option value="200.000 sd 300.000">Rp 200.000 sd 300.000</option>
                                <option value="Kost Harian < 200.000">Kost Harian < Rp 200.000</option>
                                <option value="0">Tidak Ada</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Makan" readonly>
                            <select class="form-control" name="makan" required>
                                <option value="">Pilih Makan...</option>
                                <option value="25.000">Rp 25.000</option>
                                <option value="15.000">Rp 15.000</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Biaya Ditanggung" readonly>
                            <select class="form-control" id="biaya_ditanggung_oleh" name="biaya_ditanggung_oleh" required>
                                <option value="">Pilih ..</option>
                            </select>
                        </div>
                        <hr>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Diajukan oleh" readonly>
                            <input type="text" class="form-control" name="diajukan_oleh" value="{{ $user_karyawan->name }}" readonly>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Diminta oleh" readonly>
                            <select name="diminta_oleh_kategori" id="diminta_oleh_kategori" class="form-control">
                                <option value="">Pilih</option>
                                <option value="Saya Sendiri">Saya Sendiri</option>
                                <option value="Atasan">Atasan</option>
                                <option value="Departemen Lain">Departemen Lain</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <select name="diminta_oleh_departemen" id="diminta_oleh_departemen" class="form-control">
                                <option value="">Pilih</option>
                                @foreach($departemen as $departemen)
                                <option value="{{$departemen->nama_departemen}}">{{$departemen->nama_departemen}}</option>
                                @endforeach
                            </select>
                            <select name="diminta_oleh" id="diminta_oleh" class="form-control">
                            </select>
                            <input type="text" class="form-control" name="diminta_oleh_saya" id="diminta_oleh_saya" value="">
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Disahkan oleh" readonly>
                            <input type="text" class="form-control" name="disahkan_oleh" value="@if($getUserAtasan==NULL)@else{{ $getUserAtasan->name }}@endif" readonly>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Diproses HRD" readonly>
                            <select name="proses_hrd" id="proses_hrd" class="form-control">
                                <option value="">Pilih</option>
                                @foreach($hrd as $hrd)
                                <option value="{{$hrd->id}}">{{$hrd->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="Diproses Finance" readonly>
                            <select name="proses_finance" id="proses_finance" class="form-control">
                                <option value="">Pilih</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<hr width="90%" style="margin-top: 0%;">
<div class="page-content bottom-content">
    <div class="container">
        <div class="row">
            <div class="col-1">
                <div class="detail-content">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 512 512" version="1.1">
                        <title>history-list</title>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="icon" fill="#000000" transform="translate(33.830111, 42.666667)">
                                <path d="M456.836556,405.333333 L456.836556,448 L350.169889,448 L350.169889,405.333333 L456.836556,405.333333 Z M328.836556,405.333333 L328.836556,448 L286.169889,448 L286.169889,405.333333 L328.836556,405.333333 Z M456.836556,341.333333 L456.836556,384 L350.169889,384 L350.169889,341.333333 L456.836556,341.333333 Z M328.836556,341.333333 L328.836556,384 L286.169889,384 L286.169889,341.333333 L328.836556,341.333333 Z M131.660221,261.176335 C154.823665,284.339779 186.823665,298.666667 222.169889,298.666667 C237.130689,298.666667 251.492003,296.099965 264.837506,291.382887 L264.838264,335.956148 C251.200633,339.466383 236.903286,341.333333 222.169889,341.333333 C175.041086,341.333333 132.37401,322.230407 101.489339,291.345231 L131.660221,261.176335 Z M456.836556,277.333333 L456.836556,320 L350.169889,320 L350.169889,277.333333 L456.836556,277.333333 Z M328.836556,277.333333 L328.836556,320 L286.169889,320 L286.169889,277.333333 L328.836556,277.333333 Z M222.169889,7.10542736e-15 C316.426487,7.10542736e-15 392.836556,76.4100694 392.836556,170.666667 C392.836556,201.752854 384.525389,230.897864 370.003903,256.000851 L317.574603,256.00279 C337.844458,233.356846 350.169889,203.451136 350.169889,170.666667 C350.169889,99.9742187 292.862337,42.6666667 222.169889,42.6666667 C151.477441,42.6666667 94.1698893,99.9742187 94.1698893,170.666667 C94.1698893,174.692297 94.3557269,178.674522 94.7192911,182.605232 L115.503223,161.830111 L145.673112,192 L72.836556,264.836556 L4.97379915e-14,192 L30.1698893,161.830111 L51.989071,183.641815 C51.6671112,179.358922 51.5032227,175.031933 51.5032227,170.666667 C51.5032227,76.4100694 127.913292,7.10542736e-15 222.169889,7.10542736e-15 Z M243.503223,64 L243.503223,159.146667 L297.903223,195.626667 L274.436556,231.04 L200.836556,182.186667 L200.836556,64 L243.503223,64 Z" id="Combined-Shape">

                                </path>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>
            <div class="col-11">
                <div class="flex-1">
                    <h4>History</h4>
                </div>
            </div>
        </div>
        <div class="title-bar" style="margin: 0;">
            <h6 class="title"> Filter&nbsp;Bulan&nbsp;
                <select class="month" style="width: max-content;border-radius: 0px; background-color:transparent; color: var(--primary); border: none;outline: none;" name="" id="month">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                &nbsp;{{$thnskrg}}
            </h6>
        </div>
        <div class="content-history">
        </div>

    </div>

</div>
@endsection
@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function() {
        $('#alamat_dikunjungi').hide();
        $('#alamat_dikunjungi1').hide();
        $('body').on("change", "#wilayah_penugasan", function() {
            var id = $(this).val();
            if (id == 'Wilayah Kantor') {
                $('#alamat_dikunjungi').show();
                $('#alamat_dikunjungi1').hide();
            } else {
                $('#alamat_dikunjungi1').show();
                $('#alamat_dikunjungi').hide();
            }
        });
        $('#diminta_oleh_departemen').hide();
        $('#diminta_oleh').hide();
        $('#diminta_oleh_saya').hide();
        $('#alamat_kunjungan').on('change', function() {
            var asal_kerja = $('#asal_kerja').val();
            var alamat_kunjungan = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{url('penugasan/get_biaya_ditanggung')}}",
                data: {
                    asal_kerja: asal_kerja,
                    alamat_kunjungan: alamat_kunjungan,

                },
                cache: false,

                success: function(msg) {

                    $('#biaya_ditanggung_oleh').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        });
        $('#diminta_oleh_kategori').on('change', function() {
            let value = $(this).val();
            let level = '{{$data_user->level_jabatan}}'
            let dept = '{{$data_user->dept_id}}'
            if (value == 'Atasan') {
                $.ajax({
                    type: 'GET',
                    url: "{{url('penugasan/get_diminta')}}",
                    data: {
                        dept: dept,
                        level: level,

                    },
                    cache: false,

                    success: function(msg) {

                        $('#diminta_oleh').html(msg);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
                $('#diminta_oleh').show();
                $('#diminta_oleh_departemen').hide();
            } else if (value == 'Departemen Lain') {
                $('#diminta_oleh').hide();
                $('#diminta_oleh_departemen').show();
            } else {
                $('#diminta_oleh').hide();
                $('#diminta_oleh_departemen').hide();
                $('#diminta_oleh_saya').show();
                $('#diminta_oleh_saya').val('{{$user_karyawan->name}}');

            }
        });
        $('#diminta_oleh_departemen').on('change', function() {
            let value = $(this).val();
            let level = '{{$data_user->level_jabatan}}';
            $('#diminta_oleh').show();
            $.ajax({
                type: 'GET',
                url: "{{url('penugasan/get_diminta_departemen')}}",
                data: {
                    value: value,
                    level: level,

                },
                cache: false,

                success: function(msg) {

                    $('#diminta_oleh').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })

        });
        $('#biaya_ditanggung_oleh').on('change', function() {
            let value = $(this).val();
            // $('#diminta_oleh').show();
            $.ajax({
                type: 'GET',
                url: "{{url('penugasan/get_finance')}}",
                data: {
                    value: value,

                },
                cache: false,

                success: function(msg) {

                    $('#proses_finance').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })

        });
        $('#alamat_kunjungan').on('change', function() {
            let id = $(this).val();
            let asal_kerja = $('#asal_kerja').val();
            let level = '{{$user->level_jabatan}}';
            let url = "{{url('penugasan/get_diminta')}}";
            $.ajax({
                type: 'GET',
                url: "{{url('penugasan/get_diminta')}}",
                data: {
                    id: id,
                    level: level,
                    asal_kerja: asal_kerja,
                },
                cache: false,

                success: function(msg) {

                    $('#diminta_oleh').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        });
    });
    $(document).ready(function() {
        var start = moment();
        $('input[id="tanggal_kunjungan"]').daterangepicker({
            drops: 'top',
            minDate: start,
            startDate: start,
            endDate: start,
            autoApply: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var today = moment().format('YYYY-MM-DD');
        var month = moment().format('MM');
        var day = moment().format('D');
        var year = moment().format('YYYY');

        // console.log(month);
        $('.month').val(month);
        load_data();

        function load_data(filter_month = '') {
            $.ajax({
                url: "{{url('/penugasan/get_filter_month')}}",
                data: {
                    filter_month: filter_month,
                },
                type: "GET",
                error: function() {
                    alert('Something is wrong');
                },
                success: function(data) {
                    // console.log(data);
                    if (data == '' || data == null) {
                        $('.content-history').append(function() {
                            var notif = "<div class='notification-content' style='background-color: white;'>";
                            var history = "<div class='notification text-center'><h6>Tidak Ada Data</h6><div class='notification-footer'>";
                            var history2 = history + "</div></div></div>";
                            return notif + history2;
                        });
                    } else {
                        count = data.length;
                        $.each(data, function(count) {
                            $('.content-history').append(function() {
                                var notif = "<div class='notification-content' style='background-color: white;'>";
                                if (data[count].status_penugasan == 0 || data[count].status_penugasan == 'NOT APPROVE') {
                                    var notif1 = "<a href='javascript:void(0);' data-bs-toggle='offcanvas' data-bs-target='#delete" + data[count].id + "' aria-controls='offcanvasBottom'>";
                                    var notif2 = "<small class='badge light badge-danger' style='float: right;padding-right:10px; box-shadow: 0px 0px 4px;'>";
                                    var notif3 = "<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none'><path d='M3 6.52381C3 6.12932 3.32671 5.80952 3.72973 5.80952H8.51787C8.52437 4.9683 8.61554 3.81504 9.45037 3.01668C10.1074 2.38839 11.0081 2 12 2C12.9919 2 13.8926 2.38839 14.5496 3.01668C15.3844 3.81504 15.4756 4.9683 15.4821 5.80952H20.2703C20.6733 5.80952 21 6.12932 21 6.52381C21 6.9183 20.6733 7.2381 20.2703 7.2381H3.72973C3.32671 7.2381 3 6.9183 3 6.52381Z' fill='#1C274C' /><path opacity='0.5' d='M11.5956 22.0001H12.4044C15.1871 22.0001 16.5785 22.0001 17.4831 21.1142C18.3878 20.2283 18.4803 18.7751 18.6654 15.8686L18.9321 11.6807C19.0326 10.1037 19.0828 9.31524 18.6289 8.81558C18.1751 8.31592 17.4087 8.31592 15.876 8.31592H8.12405C6.59127 8.31592 5.82488 8.31592 5.37105 8.81558C4.91722 9.31524 4.96744 10.1037 5.06788 11.6807L5.33459 15.8686C5.5197 18.7751 5.61225 20.2283 6.51689 21.1142C7.42153 22.0001 8.81289 22.0001 11.5956 22.0001Z' fill='#1C274C' /><path fill-rule='evenodd' clip-rule='evenodd' d='M9.42543 11.4815C9.83759 11.4381 10.2051 11.7547 10.2463 12.1885L10.7463 17.4517C10.7875 17.8855 10.4868 18.2724 10.0747 18.3158C9.66253 18.3592 9.29499 18.0426 9.25378 17.6088L8.75378 12.3456C8.71256 11.9118 9.01327 11.5249 9.42543 11.4815Z' fill='#1C274C' /><path fill-rule='evenodd' clip-rule='evenodd' d='M14.5747 11.4815C14.9868 11.5249 15.2875 11.9118 15.2463 12.3456L14.7463 17.6088C14.7051 18.0426 14.3376 18.3592 13.9254 18.3158C13.5133 18.2724 13.2126 17.8855 13.2538 17.4517L13.7538 12.1885C13.795 11.7547 14.1625 11.4381 14.5747 11.4815Z' fill='#1C274C'/></svg></small></a>";
                                    var notif4 = "<div class='offcanvas offcanvas-bottom' tabindex='-1' id='delete" + data[count].id + "' aria-labelledby='offcanvasBottomLabel'><div class='offcanvas-body text-center small'><h5 class='title'>KONFIRMASI HAPUS</h5><p>Apakah Anda Ingin Menghapus Pengajuan Cuti?</p><a id='btn_klik' href='delete_cuti/" + data[count].id + "' class='btn btn-sm btn-danger light pwa-btn'>Hapus</a>";
                                    var notif5 = notif + notif1 + notif2 + notif3 + notif4 + "<a href='javascrpit:void(0);' class='btn btn-sm light btn-primary ms-2' data-bs-dismiss='offcanvas' aria-label='Close'>Batal</a></div></div>";
                                    // return notif5;
                                } else if (data[count].status_penugasan == 5) {
                                    var notif1 = "<a href='javascript:void(0);' data-bs-toggle='offcanvas' data-bs-target='#download" + data[count].id + "' aria-controls='offcanvasBottom'><small class='badge light badge-success' style='float: right;padding-right:10px; box-shadow: 0px 0px 4px;'>";
                                    var notif2 = notif + notif1 + "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' fill='#000000' height='18' width='18' version='1.1' id='Capa_1' viewBox='0 0 48 48' xml:space='preserve'><g><g><path d='M47.987,21.938c-0.006-0.091-0.023-0.178-0.053-0.264c-0.011-0.032-0.019-0.063-0.033-0.094    c-0.048-0.104-0.109-0.202-0.193-0.285c-0.001-0.001-0.001-0.001-0.001-0.001L42,15.586V10c0-0.022-0.011-0.041-0.013-0.063    c-0.006-0.088-0.023-0.173-0.051-0.257c-0.011-0.032-0.019-0.063-0.034-0.094c-0.049-0.106-0.11-0.207-0.196-0.293l-9-9    c-0.086-0.086-0.187-0.148-0.294-0.197c-0.03-0.013-0.06-0.022-0.09-0.032c-0.086-0.03-0.174-0.047-0.264-0.053    C32.038,0.01,32.02,0,32,0H7C6.448,0,6,0.448,6,1v14.586l-5.707,5.707c0,0-0.001,0.001-0.002,0.002    c-0.084,0.084-0.144,0.182-0.192,0.285c-0.014,0.031-0.022,0.062-0.033,0.094c-0.03,0.086-0.048,0.173-0.053,0.264    C0.011,21.96,0,21.978,0,22v19c0,0.552,0.448,1,1,1h5v5c0,0.552,0.448,1,1,1h34c0.552,0,1-0.448,1-1v-5h5c0.552,0,1-0.448,1-1V22    C48,21.978,47.989,21.96,47.987,21.938z M44.586,21H42v-2.586L44.586,21z M38.586,9H33V3.414L38.586,9z M8,2h23v8    c0,0.552,0.448,1,1,1h8v5v5H8v-5V2z M6,18.414V21H3.414L6,18.414z M40,46H8v-4h32V46z M46,40H2V23h5h34h5V40z' /><path d='M18.254,26.72c-0.323-0.277-0.688-0.473-1.097-0.586c-0.408-0.113-0.805-0.17-1.19-0.17h-3.332V38h2.006v-4.828h1.428    c0.419,0,0.827-0.074,1.224-0.221c0.397-0.147,0.748-0.374,1.054-0.68c0.306-0.306,0.552-0.688,0.74-1.148    c0.187-0.459,0.281-0.994,0.281-1.606c0-0.68-0.105-1.247-0.315-1.7C18.843,27.364,18.577,26.998,18.254,26.72z M16.971,31.005    c-0.306,0.334-0.697,0.501-1.173,0.501h-1.156v-3.825h1.156c0.476,0,0.867,0.147,1.173,0.442c0.306,0.295,0.459,0.765,0.459,1.411    C17.43,30.18,17.277,30.67,16.971,31.005z' /><polygon points='30.723,38 32.78,38 32.78,32.832 35.857,32.832 35.857,31.081 32.764,31.081 32.764,27.8 36.112,27.8     36.112,25.964 30.723,25.964   ' /><path d='M24.076,25.964H21.05V38h3.009c1.553,0,2.729-0.524,3.528-1.572c0.799-1.049,1.198-2.525,1.198-4.429    c0-1.904-0.399-3.386-1.198-4.446C26.788,26.494,25.618,25.964,24.076,25.964z M26.55,33.843c-0.13,0.528-0.315,0.967-0.552,1.318    c-0.238,0.351-0.521,0.615-0.85,0.79c-0.329,0.176-0.686,0.264-1.071,0.264h-0.969v-8.466h0.969c0.385,0,0.742,0.088,1.071,0.264    c0.329,0.175,0.612,0.439,0.85,0.79c0.238,0.351,0.422,0.793,0.552,1.326s0.196,1.156,0.196,1.87    C26.746,32.702,26.68,33.316,26.55,33.843z' /></g></g></svg></small></a>";
                                    // return notif5;

                                    var offcanvas = "<div class='offcanvas offcanvas-bottom' tabindex='-1' id='download" + data[count].id + "' aria-labelledby='offcanvasBottomLabel'><div class='offcanvas-body text-center small'>";
                                    var form = "<h5 class='title'>FORM PENGAJUAN CUTI</h5>";
                                    var offcanvas1 = "<p>Apakah Anda Ingin Download Form Pengajuan Cuti?</p>";
                                    var offcanvas2 = "<a href='cetak_form_cuti/cetak/" + data[count].id + "' class='btn btn-sm btn-danger light pwa-btn'>";
                                    var notif5 = notif2 + offcanvas + form + offcanvas1 + offcanvas2 + "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' fill='#000000' height='18' width='18' version='1.1' id='Capa_1' viewBox='0 0 48 48' xml:space='preserve'><g><g><path d='M47.987,21.938c-0.006-0.091-0.023-0.178-0.053-0.264c-0.011-0.032-0.019-0.063-0.033-0.094    c-0.048-0.104-0.109-0.202-0.193-0.285c-0.001-0.001-0.001-0.001-0.001-0.001L42,15.586V10c0-0.022-0.011-0.041-0.013-0.063    c-0.006-0.088-0.023-0.173-0.051-0.257c-0.011-0.032-0.019-0.063-0.034-0.094c-0.049-0.106-0.11-0.207-0.196-0.293l-9-9    c-0.086-0.086-0.187-0.148-0.294-0.197c-0.03-0.013-0.06-0.022-0.09-0.032c-0.086-0.03-0.174-0.047-0.264-0.053    C32.038,0.01,32.02,0,32,0H7C6.448,0,6,0.448,6,1v14.586l-5.707,5.707c0,0-0.001,0.001-0.002,0.002    c-0.084,0.084-0.144,0.182-0.192,0.285c-0.014,0.031-0.022,0.062-0.033,0.094c-0.03,0.086-0.048,0.173-0.053,0.264    C0.011,21.96,0,21.978,0,22v19c0,0.552,0.448,1,1,1h5v5c0,0.552,0.448,1,1,1h34c0.552,0,1-0.448,1-1v-5h5c0.552,0,1-0.448,1-1V22    C48,21.978,47.989,21.96,47.987,21.938z M44.586,21H42v-2.586L44.586,21z M38.586,9H33V3.414L38.586,9z M8,2h23v8    c0,0.552,0.448,1,1,1h8v5v5H8v-5V2z M6,18.414V21H3.414L6,18.414z M40,46H8v-4h32V46z M46,40H2V23h5h34h5V40z' /><path d='M18.254,26.72c-0.323-0.277-0.688-0.473-1.097-0.586c-0.408-0.113-0.805-0.17-1.19-0.17h-3.332V38h2.006v-4.828h1.428    c0.419,0,0.827-0.074,1.224-0.221c0.397-0.147,0.748-0.374,1.054-0.68c0.306-0.306,0.552-0.688,0.74-1.148    c0.187-0.459,0.281-0.994,0.281-1.606c0-0.68-0.105-1.247-0.315-1.7C18.843,27.364,18.577,26.998,18.254,26.72z M16.971,31.005    c-0.306,0.334-0.697,0.501-1.173,0.501h-1.156v-3.825h1.156c0.476,0,0.867,0.147,1.173,0.442c0.306,0.295,0.459,0.765,0.459,1.411    C17.43,30.18,17.277,30.67,16.971,31.005z' /><polygon points='30.723,38 32.78,38 32.78,32.832 35.857,32.832 35.857,31.081 32.764,31.081 32.764,27.8 36.112,27.8     36.112,25.964 30.723,25.964   ' /><path d='M24.076,25.964H21.05V38h3.009c1.553,0,2.729-0.524,3.528-1.572c0.799-1.049,1.198-2.525,1.198-4.429    c0-1.904-0.399-3.386-1.198-4.446C26.788,26.494,25.618,25.964,24.076,25.964z M26.55,33.843c-0.13,0.528-0.315,0.967-0.552,1.318    c-0.238,0.351-0.521,0.615-0.85,0.79c-0.329,0.176-0.686,0.264-1.071,0.264h-0.969v-8.466h0.969c0.385,0,0.742,0.088,1.071,0.264    c0.329,0.175,0.612,0.439,0.85,0.79c0.238,0.351,0.422,0.793,0.552,1.326s0.196,1.156,0.196,1.87    C26.746,32.702,26.68,33.316,26.55,33.843z' /></g> </g></svg>&nbsp;Download</a><a href='javascrpit:void(0);' class='btn btn-sm light btn-primary ms-2' data-bs-dismiss='offcanvas' aria-label='Close'>Batal</a></div></div>";
                                } else {
                                    var notif5 = notif;
                                }
                                var history = "<a id='btn_klik' href='detail/edit/" + data[count].id + "'><div class='notification'>";

                                var history1 = "<h6>" + data[count].name + " <p>" + data[count].kegiatan_penugasan + "</p></h6>";
                                var history2 = "<div class='notification-footer'><span><svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none'><path fill-rule='evenodd' clip-rule='evenodd' d='M7 4.01833C6.46047 4.04114 6.07192 4.09237 5.72883 4.20736C4.53947 4.60599 3.60599 5.53947 3.20736 6.72883C3 7.3475 3 8.11402 3 9.64706C3 9.74287 3 9.79078 3.01296 9.82945C3.03787 9.90378 3.09622 9.96213 3.17055 9.98704C3.20922 10 3.25713 10 3.35294 10H20.6471C20.7429 10 20.7908 10 20.8294 9.98704C20.9038 9.96213 20.9621 9.90378 20.987 9.82945C21 9.79078 21 9.74287 21 9.64706C21 8.11402 21 7.3475 20.7926 6.72883C20.394 5.53947 19.4605 4.60599 18.2712 4.20736C17.9281 4.09237 17.5395 4.04114 17 4.01833L17 6.5C17 7.32843 16.3284 8 15.5 8C14.6716 8 14 7.32843 14 6.5L14 4H10L10 6.5C10 7.32843 9.32843 8 8.50001 8C7.67158 8 7 7.32843 7 6.5L7 4.01833Z' fill='#222222' /><path d='M3 11.5C3 11.2643 3 11.1464 3.07322 11.0732C3.14645 11 3.2643 11 3.5 11H20.5C20.7357 11 20.8536 11 20.9268 11.0732C21 11.1464 21 11.2643 21 11.5V12C21 15.7712 21 17.6569 19.8284 18.8284C18.6569 20 16.7712 20 13 20H11C7.22876 20 5.34315 20 4.17157 18.8284C3 17.6569 3 15.7712 3 12V11.5Z' fill='#2A4157' fill-opacity='0.24' /><path d='M8.5 2.5L8.5 6.5' stroke='#222222' stroke-linecap='round' /><path d='M15.5 2.5L15.5 6.5' stroke='#222222' stroke-linecap='round' /></svg>";
                                var history3 = new Date(data[count].tanggal_pengajuan).toLocaleDateString() + "</span>";
                                if (data[count].status_penugasan == 0) {
                                    var status = "<small class='badge light badge-danger'><i class='far fa-edit'></i> Tambahkan TTD</small>";
                                } else if (data[count].status_penugasan == 1) {
                                    var status = "<small class='badge light badge-warning'><i class='fa fa-spinner'></i> Proses TTD Diminta</small>";
                                } else if (data[count].status_penugasan == 2) {
                                    var status = "<small class='badge light badge-secondary'><i class='fa fa-spinner'></i> Proses TTD Disahkan</small>";
                                } else if (data[count].status_penugasan == 3) {
                                    var status = "<small class='badge light badge-info'><i class='fa fa-spinner'></i> Proses TTD HRD</small>";
                                } else if (data[count].status_penugasan == 4) {
                                    var status = "<small class='badge light badge-primary'><i class='fa fa-spinner'></i> Proses TTD FINANCE</small>";
                                } else if (data[count].status_penugasan == 'NOT APPROVE') {
                                    var status = "<small class='badge light badge-danger'><i class='fa fa-minus'></i> Permintaan Ditolak </small>";
                                } else if (data[count].status_penugasan == 3) {
                                    var status = "<small class='badge light badge-success'> <svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none'><path d='M8.5 12.5L10.5 14.5L15.5 9.5' stroke='#1C274C' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' /><path d='M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7' stroke='#1C274C' stroke-width='1.5' stroke-linecap='round' /></svg>Permintaan Disetujui</small>";
                                }
                                var history4 = history + history1 + history2 + history3 + status + "</div></div></a></div>";
                                return notif5 + history4;
                            });

                        });
                    }
                }
            });
        }
        $('#month').change(function() {
            filter_month = $(this).val();
            // console.log(filter_month);
            $('.content-history').empty();
            load_data(filter_month);


        })
    });
</script>
<script>
    $(document).on('click', '#btn_klik', function(e) {
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
</script>
@endsection