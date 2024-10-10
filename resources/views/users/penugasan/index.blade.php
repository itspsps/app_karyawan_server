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
    <form class="my-2">
        <div class="input-group">
            <input type="date" value="{{ date('Y-m-d') }}" readonly style="font-weight: bold" placeholder="Phone number" class="form-control">
            <input type="time" value="{{ date('H:i:s') }}" readonly style="font-weight: bold" placeholder="Phone number" class="form-control">
        </div>
    </form>
    <button id="addForm" class="btn btn-sm btn-primary btn-rounded" style="width: 30%;margin-left: 35%;margin-right: 35%" data-bs-toggle="modal" data-bs-target="#modal_pengajuan_cuti">
        <i class="fa fa-plus" aria-hidden="true"> </i>
        &nbsp; Add
    </button>
    <script>
        $('button').click(function() {
            $('#myModal').modal('show');
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="modal_pengajuan_cuti">
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
                        <div class="input-group">
                            <input type="hidden" name="id_user" value="{{ $user_karyawan->id }}">
                            {{-- <input type="hidden" name="telp" value="{{ $data_user->telepon }}"> --}}
                            {{-- <input type="hidden" name="email" value="{{ $data_user->email }}"> --}}
                            {{-- <input type="hidden" name="departements" value="{{ $user->dept_id }}"> --}}
                            {{-- <input type="hidden" name="jabatan" value="{{ $user->jabatan_id }}"> --}}
                            {{-- <input type="hidden" name="divisi" value="{{ $user->divisi_id }}" id=""> --}}
                            <input type="hidden" name="id_user_atasan" value="{{ $getUserAtasan->id }}">
                            <input type="hidden" name="nik" value="{{ $user_karyawan->nik }}">
                            <input type="hidden" name="id_jabatan" value="{{ $user->jabatan_id }}">
                            <input type="hidden" name="id_departemen" value="{{ $user->dept_id }}">
                            <input type="hidden" name="id_divisi" value="{{ $user->divisi_id }}">
                            <input type="hidden" name="id_diajukan_oleh" value="{{ $user_karyawan->id }}">
                            <input type="hidden" name="id_disahkan_oleh" value="{{ $getUserAtasan->id }}">
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
                            <input type="text" class="form-control" name="" value="{{ $user_karyawan->fullname }}" style="font-weight: bold" readonly required>
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
                            <input type="text" class="form-control" name="disahkan_oleh" value="{{ $getUserAtasan->name }}" readonly>
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
        <div class="detail-content">
            <div class="flex-1">
                <h4>Riwayat Penugasan</h4>
            </div>
        </div>
        @foreach ($record_data as $record_data)
        <div class="notification-content" style="background-color: white">
            @if ($record_data->status_penugasan == 0 || $record_data->status_penugasan=='NOT APPROVE')
            <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#delete{{$record_data->id}}" aria-controls="offcanvasBottom">
                <small class="badge light badge-danger" style="float: right;padding-right:10px; box-shadow: 0px 0px 4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M3 6.52381C3 6.12932 3.32671 5.80952 3.72973 5.80952H8.51787C8.52437 4.9683 8.61554 3.81504 9.45037 3.01668C10.1074 2.38839 11.0081 2 12 2C12.9919 2 13.8926 2.38839 14.5496 3.01668C15.3844 3.81504 15.4756 4.9683 15.4821 5.80952H20.2703C20.6733 5.80952 21 6.12932 21 6.52381C21 6.9183 20.6733 7.2381 20.2703 7.2381H3.72973C3.32671 7.2381 3 6.9183 3 6.52381Z" fill="#1C274C" />
                        <path opacity="0.5" d="M11.5956 22.0001H12.4044C15.1871 22.0001 16.5785 22.0001 17.4831 21.1142C18.3878 20.2283 18.4803 18.7751 18.6654 15.8686L18.9321 11.6807C19.0326 10.1037 19.0828 9.31524 18.6289 8.81558C18.1751 8.31592 17.4087 8.31592 15.876 8.31592H8.12405C6.59127 8.31592 5.82488 8.31592 5.37105 8.81558C4.91722 9.31524 4.96744 10.1037 5.06788 11.6807L5.33459 15.8686C5.5197 18.7751 5.61225 20.2283 6.51689 21.1142C7.42153 22.0001 8.81289 22.0001 11.5956 22.0001Z" fill="#1C274C" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.42543 11.4815C9.83759 11.4381 10.2051 11.7547 10.2463 12.1885L10.7463 17.4517C10.7875 17.8855 10.4868 18.2724 10.0747 18.3158C9.66253 18.3592 9.29499 18.0426 9.25378 17.6088L8.75378 12.3456C8.71256 11.9118 9.01327 11.5249 9.42543 11.4815Z" fill="#1C274C" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5747 11.4815C14.9868 11.5249 15.2875 11.9118 15.2463 12.3456L14.7463 17.6088C14.7051 18.0426 14.3376 18.3592 13.9254 18.3158C13.5133 18.2724 13.2126 17.8855 13.2538 17.4517L13.7538 12.1885C13.795 11.7547 14.1625 11.4381 14.5747 11.4815Z" fill="#1C274C" />
                    </svg>
                </small>
            </a>
            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="delete{{$record_data->id}}" aria-labelledby="offcanvasBottomLabel">
                <div class="offcanvas-body text-center small">
                    <h5 class="title">KONFIRMASI HAPUS</h5>
                    <p>Apakah Anda Ingin Menghapus Pengajuan Perjalanan Dinas?</p>
                    <a id="btn_klik" href="{{url('/penugasan/delete_penugasan/'.$record_data->id)}}" class="btn btn-sm btn-danger light pwa-btn">Hapus</a>
                    <a href="javascrpit:void(0);" class="btn btn-sm light btn-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">Batal</a>
                </div>
            </div>
            @elseif($record_data->status_penugasan=='5')
            <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#download{{$record_data->id}}" aria-controls="offcanvasBottom">
                <small class="badge light badge-success" style="float: right;padding-right:10px; box-shadow: 0px 0px 4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="18" width="18" version="1.1" id="Capa_1" viewBox="0 0 48 48" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M47.987,21.938c-0.006-0.091-0.023-0.178-0.053-0.264c-0.011-0.032-0.019-0.063-0.033-0.094    c-0.048-0.104-0.109-0.202-0.193-0.285c-0.001-0.001-0.001-0.001-0.001-0.001L42,15.586V10c0-0.022-0.011-0.041-0.013-0.063    c-0.006-0.088-0.023-0.173-0.051-0.257c-0.011-0.032-0.019-0.063-0.034-0.094c-0.049-0.106-0.11-0.207-0.196-0.293l-9-9    c-0.086-0.086-0.187-0.148-0.294-0.197c-0.03-0.013-0.06-0.022-0.09-0.032c-0.086-0.03-0.174-0.047-0.264-0.053    C32.038,0.01,32.02,0,32,0H7C6.448,0,6,0.448,6,1v14.586l-5.707,5.707c0,0-0.001,0.001-0.002,0.002    c-0.084,0.084-0.144,0.182-0.192,0.285c-0.014,0.031-0.022,0.062-0.033,0.094c-0.03,0.086-0.048,0.173-0.053,0.264    C0.011,21.96,0,21.978,0,22v19c0,0.552,0.448,1,1,1h5v5c0,0.552,0.448,1,1,1h34c0.552,0,1-0.448,1-1v-5h5c0.552,0,1-0.448,1-1V22    C48,21.978,47.989,21.96,47.987,21.938z M44.586,21H42v-2.586L44.586,21z M38.586,9H33V3.414L38.586,9z M8,2h23v8    c0,0.552,0.448,1,1,1h8v5v5H8v-5V2z M6,18.414V21H3.414L6,18.414z M40,46H8v-4h32V46z M46,40H2V23h5h34h5V40z" />
                                <path d="M18.254,26.72c-0.323-0.277-0.688-0.473-1.097-0.586c-0.408-0.113-0.805-0.17-1.19-0.17h-3.332V38h2.006v-4.828h1.428    c0.419,0,0.827-0.074,1.224-0.221c0.397-0.147,0.748-0.374,1.054-0.68c0.306-0.306,0.552-0.688,0.74-1.148    c0.187-0.459,0.281-0.994,0.281-1.606c0-0.68-0.105-1.247-0.315-1.7C18.843,27.364,18.577,26.998,18.254,26.72z M16.971,31.005    c-0.306,0.334-0.697,0.501-1.173,0.501h-1.156v-3.825h1.156c0.476,0,0.867,0.147,1.173,0.442c0.306,0.295,0.459,0.765,0.459,1.411    C17.43,30.18,17.277,30.67,16.971,31.005z" />
                                <polygon points="30.723,38 32.78,38 32.78,32.832 35.857,32.832 35.857,31.081 32.764,31.081 32.764,27.8 36.112,27.8     36.112,25.964 30.723,25.964   " />
                                <path d="M24.076,25.964H21.05V38h3.009c1.553,0,2.729-0.524,3.528-1.572c0.799-1.049,1.198-2.525,1.198-4.429    c0-1.904-0.399-3.386-1.198-4.446C26.788,26.494,25.618,25.964,24.076,25.964z M26.55,33.843c-0.13,0.528-0.315,0.967-0.552,1.318    c-0.238,0.351-0.521,0.615-0.85,0.79c-0.329,0.176-0.686,0.264-1.071,0.264h-0.969v-8.466h0.969c0.385,0,0.742,0.088,1.071,0.264    c0.329,0.175,0.612,0.439,0.85,0.79c0.238,0.351,0.422,0.793,0.552,1.326s0.196,1.156,0.196,1.87    C26.746,32.702,26.68,33.316,26.55,33.843z" />
                            </g>
                        </g>
                    </svg>
                </small>
            </a>
            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="download{{$record_data->id}}" aria-labelledby="offcanvasBottomLabel">
                <div class="offcanvas-body text-center small">
                    <h5 class="title">FORM PENGAJUAN PERJALANAN DINAS</h5>
                    <p>Apakah Anda Ingin Download Form Pengajuan Perjalanan Dinas ?</p>
                    <a id="btn_klik" href="{{url('/penugasan/cetak_form_penugasan/'.$record_data->id)}}" class="btn btn-sm btn-danger light pwa-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="18" width="18" version="1.1" id="Capa_1" viewBox="0 0 48 48" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M47.987,21.938c-0.006-0.091-0.023-0.178-0.053-0.264c-0.011-0.032-0.019-0.063-0.033-0.094    c-0.048-0.104-0.109-0.202-0.193-0.285c-0.001-0.001-0.001-0.001-0.001-0.001L42,15.586V10c0-0.022-0.011-0.041-0.013-0.063    c-0.006-0.088-0.023-0.173-0.051-0.257c-0.011-0.032-0.019-0.063-0.034-0.094c-0.049-0.106-0.11-0.207-0.196-0.293l-9-9    c-0.086-0.086-0.187-0.148-0.294-0.197c-0.03-0.013-0.06-0.022-0.09-0.032c-0.086-0.03-0.174-0.047-0.264-0.053    C32.038,0.01,32.02,0,32,0H7C6.448,0,6,0.448,6,1v14.586l-5.707,5.707c0,0-0.001,0.001-0.002,0.002    c-0.084,0.084-0.144,0.182-0.192,0.285c-0.014,0.031-0.022,0.062-0.033,0.094c-0.03,0.086-0.048,0.173-0.053,0.264    C0.011,21.96,0,21.978,0,22v19c0,0.552,0.448,1,1,1h5v5c0,0.552,0.448,1,1,1h34c0.552,0,1-0.448,1-1v-5h5c0.552,0,1-0.448,1-1V22    C48,21.978,47.989,21.96,47.987,21.938z M44.586,21H42v-2.586L44.586,21z M38.586,9H33V3.414L38.586,9z M8,2h23v8    c0,0.552,0.448,1,1,1h8v5v5H8v-5V2z M6,18.414V21H3.414L6,18.414z M40,46H8v-4h32V46z M46,40H2V23h5h34h5V40z" />
                                    <path d="M18.254,26.72c-0.323-0.277-0.688-0.473-1.097-0.586c-0.408-0.113-0.805-0.17-1.19-0.17h-3.332V38h2.006v-4.828h1.428    c0.419,0,0.827-0.074,1.224-0.221c0.397-0.147,0.748-0.374,1.054-0.68c0.306-0.306,0.552-0.688,0.74-1.148    c0.187-0.459,0.281-0.994,0.281-1.606c0-0.68-0.105-1.247-0.315-1.7C18.843,27.364,18.577,26.998,18.254,26.72z M16.971,31.005    c-0.306,0.334-0.697,0.501-1.173,0.501h-1.156v-3.825h1.156c0.476,0,0.867,0.147,1.173,0.442c0.306,0.295,0.459,0.765,0.459,1.411    C17.43,30.18,17.277,30.67,16.971,31.005z" />
                                    <polygon points="30.723,38 32.78,38 32.78,32.832 35.857,32.832 35.857,31.081 32.764,31.081 32.764,27.8 36.112,27.8     36.112,25.964 30.723,25.964   " />
                                    <path d="M24.076,25.964H21.05V38h3.009c1.553,0,2.729-0.524,3.528-1.572c0.799-1.049,1.198-2.525,1.198-4.429    c0-1.904-0.399-3.386-1.198-4.446C26.788,26.494,25.618,25.964,24.076,25.964z M26.55,33.843c-0.13,0.528-0.315,0.967-0.552,1.318    c-0.238,0.351-0.521,0.615-0.85,0.79c-0.329,0.176-0.686,0.264-1.071,0.264h-0.969v-8.466h0.969c0.385,0,0.742,0.088,1.071,0.264    c0.329,0.175,0.612,0.439,0.85,0.79c0.238,0.351,0.422,0.793,0.552,1.326s0.196,1.156,0.196,1.87    C26.746,32.702,26.68,33.316,26.55,33.843z" />
                                </g>
                            </g>
                        </svg>
                        &nbsp;Download
                    </a>
                    <a href="javascrpit:void(0);" class="btn btn-sm light btn-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">Batal</a>
                </div>
            </div>
            @endif
            <a id="btn_klik" href="{{ url('penugasan/detail/edit/'.$record_data->id) }}">
                <div class="notification">
                    <h6>{{ $record_data->fullname }}</h6>
                    <p>{{ $record_data->kegiatan_penugasan}}</p>
                    <div class="notification-footer">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 4.01833C6.46047 4.04114 6.07192 4.09237 5.72883 4.20736C4.53947 4.60599 3.60599 5.53947 3.20736 6.72883C3 7.3475 3 8.11402 3 9.64706C3 9.74287 3 9.79078 3.01296 9.82945C3.03787 9.90378 3.09622 9.96213 3.17055 9.98704C3.20922 10 3.25713 10 3.35294 10H20.6471C20.7429 10 20.7908 10 20.8294 9.98704C20.9038 9.96213 20.9621 9.90378 20.987 9.82945C21 9.79078 21 9.74287 21 9.64706C21 8.11402 21 7.3475 20.7926 6.72883C20.394 5.53947 19.4605 4.60599 18.2712 4.20736C17.9281 4.09237 17.5395 4.04114 17 4.01833L17 6.5C17 7.32843 16.3284 8 15.5 8C14.6716 8 14 7.32843 14 6.5L14 4H10L10 6.5C10 7.32843 9.32843 8 8.50001 8C7.67158 8 7 7.32843 7 6.5L7 4.01833Z" fill="#222222" />
                                <path d="M3 11.5C3 11.2643 3 11.1464 3.07322 11.0732C3.14645 11 3.2643 11 3.5 11H20.5C20.7357 11 20.8536 11 20.9268 11.0732C21 11.1464 21 11.2643 21 11.5V12C21 15.7712 21 17.6569 19.8284 18.8284C18.6569 20 16.7712 20 13 20H11C7.22876 20 5.34315 20 4.17157 18.8284C3 17.6569 3 15.7712 3 12V11.5Z" fill="#2A4157" fill-opacity="0.24" />
                                <path d="M8.5 2.5L8.5 6.5" stroke="#222222" stroke-linecap="round" />
                                <path d="M15.5 2.5L15.5 6.5" stroke="#222222" stroke-linecap="round" />
                            </svg>
                            {{ \Carbon\Carbon::parse($record_data->tanggal_pengajuan)->format('d-m-Y')}}
                        </span>
                        @if ($record_data->status_penugasan == 0 )
                        <small class="badge light badge-danger"><i class="fa fa-pencil"> </i> Tambahkan TTD</small>
                        @elseif($record_data->status_penugasan == 1)
                        <small class="badge light badge-warning"><i class="fa fa-pencil"> </i> Proses TTD Diminta</small>
                        @elseif($record_data->status_penugasan == 2)
                        <small class="badge light badge-secondary"><i class="fa fa-pencil"> </i> Proses TTD Disahkan</small>
                        @elseif($record_data->status_penugasan == 3)
                        <small class="badge light badge-info"><i class="fa fa-pencil"> </i> Proses TTD HRD</small>
                        @elseif($record_data->status_penugasan == 4)
                        <small class="badge light badge-primary"><i class="fa fa-pencil"> </i> Proses TTD FINANCE</small>
                        @elseif($record_data->status_penugasan == 5)
                        <small class="badge light badge-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none">
                                <path d="M8.5 12.5L10.5 14.5L15.5 9.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Penugasan Telah Disetujui
                        </small>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @endforeach

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
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
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