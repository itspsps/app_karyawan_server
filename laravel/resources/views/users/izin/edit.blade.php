@extends('users.izin.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    body {
        padding: 15px;
    }

    #note {
        position: absolute;
        left: 50px;
        top: 35px;
        padding: 0px;
        margin: 0px;
        cursor: default;
    }
</style>
<div class="container">
    @if($jam_kerja=='' || $jam_kerja==NULL)
    <div id="alert_kontrak_kerja_null" class="alert alert-danger light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <strong>User Belum Mapping Shift.</strong> Harap Hubungi HRD.
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    @if($get_izin->status_izin == '0')
    @if($get_izin->izin=='Pulang Cepat')
    <div id="alert_pulang_cepat" class="alert alert-primary light alert-lg alert-dismissible fade show">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="-4.52 0 69.472 69.472">
            <g id="Group_4" data-name="Group 4" transform="translate(-651.45 -155.8)">
                <circle id="Ellipse_4" data-name="Ellipse 4" cx="28.716" cy="28.716" r="28.716" transform="translate(652.95 157.3)" fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="3" />
                <path id="Path_11" data-name="Path 11" d="M697.51,186.016H681.667V163.846" fill="none" stroke="#814dff" stroke-miterlimit="10" stroke-width="3" />
                <circle id="Ellipse_5" data-name="Ellipse 5" cx="28.716" cy="28.716" r="28.716" transform="translate(652.95 166.34)" fill="none" stroke="#000000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" opacity="0.15" />
            </g>
        </svg>
        &nbsp;Izin Pulang Cepat Minimal Jam &nbsp;<b>{{$jam_min_plg_cpt}} WIB</b>
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    @elseif($get_izin->status_izin == '1')
    <div class="alert alert-primary light alert-lg alert-dismissible fade show">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">

            <defs>

                <style>
                    .cls-1 {
                        fill: #77acf1;
                    }

                    .cls-2 {
                        fill: #04009a;
                    }
                </style>

            </defs>

            <g data-name="20. Delivery" id="_20._Delivery">

                <path class="cls-1" d="M10,1h6a0,0,0,0,1,0,0V6.13a.87.87,0,0,1-.87.87H10.87A.87.87,0,0,1,10,6.13V1A0,0,0,0,1,10,1Z" />

                <path class="cls-2" d="M11,26H3a3,3,0,0,1-3-3V3A3,3,0,0,1,3,0H23a3,3,0,0,1,3,3v8a1,1,0,0,1-2,0V3a1,1,0,0,0-1-1H3A1,1,0,0,0,2,3V23a1,1,0,0,0,1,1h8a1,1,0,0,1,0,2Z" />

                <path class="cls-2" d="M7,22H5a1,1,0,0,1,0-2H7a1,1,0,0,1,0,2Z" />

                <path class="cls-2" d="M23,32a9,9,0,1,1,9-9A9,9,0,0,1,23,32Zm0-16a7,7,0,1,0,7,7A7,7,0,0,0,23,16Z" />

                <path class="cls-1" d="M20,27a1,1,0,0,1-.71-.29,1,1,0,0,1,0-1.42L22,22.59V19a1,1,0,0,1,2,0v4a1,1,0,0,1-.29.71l-3,3A1,1,0,0,1,20,27Z" />

            </g>

        </svg>
        <strong>&nbsp;Tunggu!&nbsp;</strong> Dalam Proses Approve {{$get_izin->approve_atasan}}
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif($get_izin->status_izin == '2')
    <div class="alert alert-success light alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polyline points="9 11 12 14 22 4"></polyline>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
        <strong>Sukses!</strong> Data Sudah Disetujui {{$get_izin->approve_atasan}}
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <form class="my-2" method="post" action="{{ url('/izin/edit-izin-proses/') }}" enctype="multipart/form-data">
        @method('post')
        @csrf
        <div class="input-group">
            <input type="hidden" name="id" value="{{$get_izin->id}}">
            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
            <input type="hidden" name="telp" value="{{ $get_izin->telp }}">
            <input type="hidden" name="email" value="{{ $get_izin->email }}">
            <input type="hidden" name="departements" value="{{ $get_izin->departements_id }}">
            <input type="hidden" name="jabatan" value="{{ $get_izin->jabatan_id }}">
            <input type="hidden" name="level_jabatan" value="@if(Auth::user()->kategori=='Karyawan Harian')@else{{ $user->level_jabatan }}@endif">
            <input type="hidden" name="divisi" value="{{ $get_izin->divisi_id }}">
            <input type="hidden" name="id_user_atasan" value="{{ $get_izin->id_approve_atasan }}">
            <input type="hidden" name="izin_old" value="{{ $get_izin->izin }}">
            <input type="hidden" name="no_form_old" value="{{ $get_izin->no_form_izin }}">
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Name" readonly>
            <input type="text" class="form-control" name="fullname" value="{{ $get_izin->fullname }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Kategori Izin" readonly>
            <select name="izin" id="izin" @if($get_izin->status_izin != '0') disabled @else @endif style="font-weight: bold" class="form-control" required>
                <option value="">--Pilih Izin--</option>
                @foreach($kategori_izin as $izin)
                <option value="{{$izin->nama_izin}}" {{ $izin->nama_izin == $get_izin->izin? 'selected' : '' }}>{{$izin->nama_izin}}</option>
                @endforeach
            </select>
        </div>
        @if($get_izin->status_izin=='0')
        <label id="label_file_sakit" class="text-info" for="file_sakit">Upload Surat Dokter</label>
        <div id="form_file_sakit" class="input-group">
            <input type="file" name="file_sakit" id="file_sakit" class="form-control" placeholder="Upload" readonly accept="image/*">
            <input type="hidden" name="foto_izin_lama" value="{{$get_izin->foto_izin}}">
        </div>
        @else
        @endif
        <div id="modal_surat" class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal_surat_dokter{{$get_izin->id}}">
                <span class="badge light badge-sm badge-info me-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none">
                        <path d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8923 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z" fill="#1C274C" />
                        <path opacity="0.5" d="M6 14.5H14" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M6 18H11.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M13 2.5V5C13 7.35702 13 8.53553 13.7322 9.26777C14.4645 10 15.643 10 18 10H22" stroke="#1C274C" stroke-width="1.5" />
                    </svg>
                    Lihat&nbsp;Surat&nbsp;Keterangan
                </span>
            </a>
        </div>
        <div class="modal fade" id="modal_surat_dokter{{$get_izin->id}}">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">SURAT KETERANGAN SAKIT </h5>
                    </div>
                    <div class="modal-body">
                        <img src="https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_izin/{{$get_izin->foto_izin}}" alt="" id="template_foto_izin">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Tanggal" readonly>
            <input type="text" name="tanggal" id="tanggal" value="{{$get_izin->tanggal}}" readonly style="font-weight: bold" required placeholder="Tanggal" class="form-control">
        </div>
        <div id="form_jam_keluar" class="input-group">
            <input type="text" class="form-control" value="Jam Keluar" readonly>
            <input type="time" name="jam_keluar" id="jam_keluar" value="{{$get_izin->jam_keluar}}" @if($get_izin->status_izin=='0') @else readonly @endif style="font-weight: bold" placeholder="Jam Keluar" class="form-control">
        </div>
        <div id="form_jam_kembali" class="input-group">
            <input type="text" class="form-control" value="Jam Kembali" readonly>
            <input type="time" name="jam_kembali" id="jam_kembali" value="{{$get_izin->jam_kembali}}" @if($get_izin->status_izin=='0') @else readonly @endif style="font-weight: bold" placeholder="Jam Kembali" class="form-control">
        </div>
        <div id="form_jam_pulang_cepat" class="input-group">
            <input type="text" class="form-control" value="Jam Keluar" readonly>
            <input type="time" name="jam_pulang_cepat" id="jam_pulang_cepat" @if($get_izin->status_izin=='0') @else readonly @endif value="@if($jam_kerja=='' || $jam_kerja==NULL) @else{{$get_izin->pulang_cepat}}@endif" min="" style="font-weight: bold" placeholder="Jam Pulang" class="form-control">
        </div>
        <div id="jam_masuk_kerja" class="input-group">
            <input type="text" class="form-control" value="Jam Masuk Kerja" readonly>
            <input type="text" id="jam_masuk" name="jam_masuk" value="@if($jam_kerja=='' || $jam_kerja==NULL)Mapping Belum Tersedia @else {{$jam_kerja->Shift->jam_masuk}} @endif" readonly style="font-weight: bold" placeholder="Jam Masuk Kerja" class="form-control">
        </div>
        <div id="jam_datang" class="input-group">
            <input type="text" class="form-control" value="Jam Datang" readonly>
            <input type="time" id="jam" name="jam" value="{{$get_izin->jam}}" readonly style="font-weight: bold" placeholder="Jam Datang" class="form-control">
        </div>
        <div id="form_terlambat" class="input-group">
            <input type="text" class="form-control" value="Terlambat" readonly>
            <input type="text" id="terlambat" name="terlambat" value="{{$get_izin->terlambat}}" readonly style="font-weight: bold" placeholder="Terlambat" class="form-control">
        </div>
        <div class="input-group">
            <textarea class="form-control" name="keterangan_izin" style="font-weight: bold" required placeholder="Description" @if($get_izin->ttd_pengajuan != '') disabled @else @endif >{{$get_izin->keterangan_izin}}</textarea>
        </div>
        @if($user->kategori=='Karyawan Bulanan')
        <div id="form_user_backup" class="input-group">
            @if($user->level_jabatan=='1')
            <input type="text" class="form-control" value="Pengganti" readonly>
            <select class="form-control" name="user_backup">
                <option selected value="-">-</option>
            </select>
            @else
            <input type="text" class="form-control" value="Pengganti" readonly>
            <select class="form-control" name="user_backup" @if($get_izin->status_izin!='0') disabled @else @endif>
                <option value="">Pilih Pengganti...</option>
                @foreach($get_user_backup as $data)
                <option value="{{$data->id}}" {{ $data->id == $get_izin->user_id_backup? 'selected' : '' }}>{{$data->name}}
                </option>
                @endforeach
            </select>
            @endif
        </div>
        @endif
        <div id="form_catatan_backup" class="input-group">
            <textarea class="form-control" name="catatan_backup" style="font-weight: bold" placeholder="Catatan Selama Tidak Masuk" @if($get_izin->ttd_pengajuan != '') disabled @else @endif >{{$get_izin->catatan_backup}}</textarea>
        </div>
        <div class="input-group">
            @if($get_izin->id_approve_atasan=='')
            @if($user->level_jabatan=='1')
            <input type="text" class="form-control" value="Diproses" readonly>
            <input type="text" class="form-control" name="approve_atasan1" value="HRD" readonly>
            <input type="hidden" class="form-control" name="approve_atasan" value="HRD" readonly>
            @else
            <input type="text" class="form-control" value="Approve By" readonly>
            <input type="text" class="form-control" name="approve_atasan" value="{{ $get_izin->approve_atasan }}" readonly>
            @endif
            @else
            <input type="text" class="form-control" value="Approve By" readonly>
            <input type="text" class="form-control" name="approve_atasan" value="{{ $get_izin->approve_atasan }}" readonly>
            @endif

        </div>
        @if($get_izin->ttd_pengajuan=='')
        <div class="input-group">
            <div id="signature-pad" style="border:solid 1px teal; width:100%;height:200px;">
                <div>
                    <div id="note" onmouseover="my_function();"></div>
                    <canvas id="the_canvas" width="auto" height="100px"></canvas>
                    <p class="text-primary" style="text-align: center">Ttd : {{ Auth::user()->fullname }} {{ date('Y-m-d') }}</p>
                    <hr>
                    <div class="text-center">
                        <input type="hidden" id="signature" name="signature">
                        <button type="button" id="clear_btn" class="btn btn-sm btn-danger btn-rounded" data-action="clear"><i class="fa fa-refresh" aria-hidden="true"> </i> &nbsp; Clear</button>
                        <button type="submit" id="save_btn" class="btn btn-sm btn-primary btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Simpan</button>
                    </div>

                </div>
            </div>
        </div>
        @else
        <div class="text-center" style="margin: 0 auto;">
            <a href="{{ url('izin/dashboard') }}" class="btn btn-sm btn-primary btn-rounded">
                <i class="fa fa-arrow-left" aria-hidden="true"> </i>
                &nbsp; Kembali
            </a>
        </div>
        @endif
    </form>
</div>

<hr width="90%" style="margin-left: 5%;margin-right: 5%">
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    var wrapper = document.getElementById("signature-pad");
    var clearButton = wrapper.querySelector("[data-action=clear]");
    var savePNGButton = wrapper.querySelector("[data-action=save-png]");
    var canvas = wrapper.querySelector("canvas");
    var el_note = document.getElementById("note");
    var signaturePad;
    signaturePad = new SignaturePad(canvas);

    clearButton.addEventListener("click", function(event) {
        // document.getElementById("note").innerHTML = "The signature should be inside box";
        signaturePad.clear();
    });
    savePNGButton.addEventListener("click", function(event) {
        if (signaturePad.isEmpty()) {
            alert("Please provide signature first.");
            event.preventDefault();
        } else {
            var canvas = document.getElementById("the_canvas");
            var dataUrl = canvas.toDataURL();
            document.getElementById("signature").value = dataUrl;
        }
    });

    function my_function() {
        document.getElementById("note").innerHTML = "";
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var foto_izin = '{{$get_izin->foto_izin}}';
        var izin = '{{$get_izin->izin}}';
        if (izin == 'Datang Terlambat') {
            $('#jam_masuk_kerja').show();
            $('#jam_datang').show();
            $('#form_terlambat').show();
            $('#modal_surat').hide();
            $('#form_jam_pulang_cepat').hide();
            $('#form_jam_keluar').hide();
            $('#form_jam_kembali').hide();
            $('#label_file_sakit').hide();
            $('#form_file_sakit').hide();
            $('#form_user_backup').hide();
            $('#form_catatan_backup').hide();
        } else if (izin == 'Sakit') {
            $('#jam_masuk_kerja').hide();
            $('#jam_datang').hide();
            $('#form_terlambat').hide();
            $('#modal_surat').show();
            $('#form_jam_pulang_cepat').hide();
            $('#form_jam_keluar').hide();
            $('#form_jam_kembali').hide();
            $('#label_file_sakit').show();
            $('#form_file_sakit').show();
            $('#form_user_backup').hide();
            $('#form_catatan_backup').hide();
            var mulai = '{{$get_izin->tanggal}}';
            var selesai = '{{$get_izin->tanggal_selesai}}';
            $('#name_form_tanggal').val('Tanggal Mulai Cuti');
            var start = moment(mulai);
            var end = moment(selesai);
            $('input[id="tanggal"]').daterangepicker({
                drops: 'up',
                minDate: start,
                startDate: start,
                endDate: end,
                autoApply: true,
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        } else if (izin == 'Pulang Cepat') {
            $('#jam_masuk_kerja').hide();
            $('#form_terlambat').hide();
            $('#jam_datang').hide();
            $('#modal_surat').hide();
            $('#form_jam_pulang_cepat').show();
            $('#form_jam_keluar').hide();
            $('#form_jam_kembali').hide();
            $('#label_file_sakit').hide();
            $('#form_file_sakit').hide();
            $('#form_user_backup').hide();
            $('#form_catatan_backup').hide();
        } else if (izin == 'Tidak Masuk (Mendadak)') {
            $('#form_user_backup').show();
            $('#form_catatan_backup').show();
            $('#modal_surat').hide();
            $('#jam_masuk_kerja').hide();
            $('#jam_datang').hide();
            $('#form_terlambat').hide();
            $('#form_jam_pulang_cepat').hide();
            $('#form_jam_keluar').hide();
            $('#form_jam_kembali').hide();
            $('#label_file_sakit').hide();
            $('#form_file_sakit').hide();
            var mulai = '{{$get_izin->tanggal}}';
            var selesai = '{{$get_izin->tanggal_selesai}}';
            var start = moment(mulai);
            var end = moment(selesai);
            $('input[id="tanggal"]').daterangepicker({
                drops: 'down',
                minDate: start,
                startDate: start,
                endDate: end,
                autoApply: true,
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        } else if (izin == 'Keluar Kantor') {
            $('#form_user_backup').hide();
            $('#label_file_sakit').hide();
            $('#form_file_sakit').hide();
            $('#modal_surat').hide();
            $('#jam_masuk_kerja').hide();
            $('#jam_datang').hide();
            $('#form_terlambat').hide();
            $('#form_jam_pulang_cepat').hide();
            $('#form_jam_keluar').show();
            $('#form_jam_kembali').show();
            $('#form_catatan_backup').hide();
        }
        $('body').on("change", "#izin", function() {
            var id = $(this).val();
            // console.log(id);
            if (id == 'Sakit') {
                $('#label_file_sakit').show();
                $('#form_file_sakit').show();
                $('#jam_masuk_kerja').hide();
                $('#jam_datang').hide();
                $('#form_terlambat').hide();
                $('#modal_surat').show();
                $('#form_jam_pulang_cepat').hide();
                $('#form_jam_keluar').hide();
                $('#form_jam_kembali').hide();
                $('#form_user_backup').hide();
                $('#form_catatan_backup').hide();
                $("#tanggal").prop('disabled', false);
                var start = moment(mulai);
                var end = moment(selesai);
                $('input[id="tanggal"]').daterangepicker({
                    drops: 'auto',
                    minDate: start,
                    startDate: start,
                    endDate: end,
                    autoApply: true,
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });
            } else if (id == 'Tidak Masuk (Mendadak)') {
                $('#label_file_sakit').hide();
                $('#form_file_sakit').hide();
                $('#jam_masuk_kerja').hide();
                $('#jam_datang').hide();
                $('#form_terlambat').hide();
                $('#modal_surat').hide();
                $('#form_jam_pulang_cepat').hide();
                $('#form_jam_keluar').hide();
                $('#form_jam_kembali').hide();
                $('#form_user_backup').show();
                $("#tanggal").prop('disabled', false);
                $('#form_catatan_backup').show();
                var start = moment().subtract(-1, 'days');
                $('input[id="tanggal"]').daterangepicker({
                    drops: 'down',
                    minDate: start,
                    startDate: start,
                    endDate: end,
                    autoApply: true,
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });
            } else if (id == 'Pulang Cepat') {
                $('#label_file_sakit').hide();
                $('#form_file_sakit').hide();
                $('#form_jam_pulang_cepat').show();
                $('#jam_masuk_kerja').hide();
                $('#jam_datang').hide();
                $('#form_terlambat').hide();
                $('#modal_surat').hide();
                $('#form_jam_keluar').hide();
                $('#form_jam_kembali').hide();
                $('#form_user_backup').hide();
                $('#form_catatan_backup').hide();
                var date = new Date();
                $('input[id="tanggal"]').val(date.toISOString().slice(0, 10));
            } else if (id == 'Datang Terlambat') {
                $('#label_file_sakit').hide();
                $('#form_file_sakit').hide();
                $('#jam_masuk_kerja').show();
                $('#jam_datang').show();
                $('#form_terlambat').show();
                $('#modal_surat').hide();
                $('#form_jam_pulang_cepat').hide();
                $('#form_jam_keluar').hide();
                $('#form_jam_kembali').hide();
                $('#form_user_backup').hide();
                $('#form_catatan_backup').hide();
                var date = new Date();
                $('input[id="tanggal"]').val(date.toISOString().slice(0, 10));
                var awal = $('#jam_masuk').val();
                var akhir = $('#jam').val();
                var time1 = awal.split(":");
                var time2 = akhir.split(":");
                var ok1 = time1[0] + time1[1];
                var ok2 = time2[0] + time2[1];
                if (ok1 < ok2) {
                    var jam = (time2[0] - time1[0]);
                    var menit = (time2[1] - time1[1]);
                    // hours = Math.floor((diff / 60));
                    // minutes = (diff % 60);
                    console.log('jam = ' + jam);
                    console.log('menit = ' + menit);
                    // console.log('MENIT = ' + minutes);
                    $('#terlambat').val(Math.abs(jam) + ' Jam, ' + Math.abs(menit) + ' Menit')
                } else {
                    var diff1 = getTimeDiff('24:00', '{time1}', 'm');
                    var diff2 = getTimeDiff('{time2}', '00:00', 'm');
                    var totalDiff = diff1 + diff2;
                    hours = Math.floor((totalDiff / 60));
                    minutes = (totalDiff % 60);
                };
                var hasil = ((akhir - awal)) / 1000;

            } else if (id == 'Keluar Kantor') {
                $('#label_file_sakit').hide();
                $('#form_file_sakit').hide();
                $('#jam_masuk_kerja').hide();
                $('#jam_datang').hide();
                $('#form_terlambat').hide();
                $('#modal_surat').hide();
                $('#form_jam_pulang_cepat').hide();
                $('#form_jam_keluar').show();
                $('#form_jam_kembali').show();
                $('#form_user_backup').hide();
                $('#form_catatan_backup').hide();
                var date = new Date();
                $('input[id="tanggal"]').val(date.toISOString().slice(0, 10));
                var awal = $('#jam_masuk').val().split(":"),
                    akhir = $('#jam').val().split(":");
                var hours1 = parseInt(awal[0], 10),
                    hours2 = parseInt(akhir[0], 10),
                    mins1 = parseInt(awal[1], 10),
                    mins2 = parseInt(akhir[1], 10);
                var hours = hours2 - hours1,
                    mins = 0;
                // get hours
                if (hours < 0) hours = 24 + hours;

                // get minutes
                if (mins2 >= mins1) {
                    mins = mins2 - mins1;
                } else {
                    mins = (mins2 + 60) - mins1;
                    hours--;
                }

                // convert to fraction of 60
                mins = (mins - 5); // -5 toleransi telat 5 menit
                console.log(mins);

                // hours += mins;
                // hours = hours.toFixed(2);
                $("#terlambat").val(hours + ' Jam, ' + mins + ' Menit');

            } else {
                $('#kategori_cuti').hide();
                $('#form_user_backup').hide();
                $('#form_catatan_backup').hide();
                $('#kuota_hari').hide();
                $('#label_file_sakit').hide();
                $('#form_file_sakit').hide();
                var date = new Date();
                $('input[id="tanggal"]').val(date.toISOString().slice(0, 10));
                $('#id_cuti').val('');
                $('#name_form_tanggal').val('Tanggal Cuti');
                var start = moment().subtract(-14, 'days');
                $('input[id="date_range_cuti"]').daterangepicker({
                    drops: 'up',
                    minDate: start,
                    startDate: start,
                    endDate: start,
                    autoApply: true,
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });
            }

        });
        $('body').on("change", "#file_sakit", function() {

            let reader = new FileReader();
            console.log(reader);
            reader.onload = (e) => {

                $('#template_foto_izin').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });
        $('body').on("click", "#btn_modal_surat", function() {

            $('#modal_surat_dokter').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("#modal_surat_dokter").modal("show");

        });
    });
</script>
@endsection