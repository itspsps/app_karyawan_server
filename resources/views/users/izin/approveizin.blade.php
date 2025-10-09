@extends('users.izin.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
<style>
    #note {
        position: absolute;
        left: 50px;
        top: 35px;
        padding: 0px;
        margin: 0px;
        cursor: default;
    }
</style>
@endsection
@section('content')
<div class="head-details">
    <div class="container">
        <div class="dz-info col-12">
            <span class="location d-block text-left">Form Izin&nbsp;
            </span>
            @if($user_karyawan->kategori=='Karyawan Bulanan')
            <h6 class="title">@if($user->kontrak_kerja == 'SP')
                CV. SUMBER PANGAN
                @elseif($user->kontrak_kerja == 'SPS')
                PT. SURYA PANGAN SEMESTA
                @elseif($user->kontrak_kerja == 'SIP')
                CV. SURYA INTI PANGAN
                @endif</h6>
            {{-- @foreach ($user  as $dep) --}}
            <h6 class="title">Department of "{{ $user->nama_departemen }}"</h6>
            {{-- @endforeach --}}
            @elseif($user_karyawan->kategori=='Karyawan Harian')
            <h6 class="title">{{$user_karyawan->penempatan_kerja}}
            </h6>
            @endif
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
                <a id="btn_klik" href="{{url('home')}}" class="btn-back">
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
<div class="container">
    <form id="form_approve" class="my-2" method="post" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
            <input type="hidden" id="id_user" name="id_user" value="{{ $data->user_id }}">
            <input type="hidden" name="telp" value="{{ $user->telepon }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="departements" value="{{ $user->dept_id }}">
            <input type="hidden" name="jabatan" value="{{ $user->jabatan_id }}">
            <input type="hidden" name="divisi" value="{{ $user->divisi_id }}" id="">
            <input type="hidden" name="status_izin" value="2" id="status_izin">
            {{-- <input type="hidden" name="id_user_atasan" value="{{ $getUserAtasan->id }}"> --}}
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Nama Pemohon" readonly>
            <input type="text" class="form-control" name="fullname" value="{{ $data->fullname }}" style="font-weight: bold" readonly required>
        </div>
        <div class="modal fade" id="modal_ttd">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">TTD : {{ $data->fullname }}</h5>
                    </div>
                    <div class="modal-body">
                        @if($data->ttd_pengajuan=='')
                        <h6 class="text-center">kosong</h6>
                        @else
                        <img src="{{ asset('signature/izin/'.$data->ttd_pengajuan.'.png') }}" style="width: 100%; font-size: small;" alt="">
                        @endif
                        <p style="text-align: center;font-weight: bold">{{ \Carbon\Carbon::parse($data->waktu_ttd_pengajuan)->isoFormat('D MMMM Y HH:mm')}} WIB</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            <a href="javascript:void(0);" data-bs-target="#modal_ttd" data-bs-toggle="modal">
                <span class="badge light badge-sm badge-info me-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="15" viewBox="0 0 28 28" version="1.1">
                        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                        <title>ic_fluent_signature_28_filled</title>
                        <desc>Created with Sketch.</desc>
                        <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="ic_fluent_signature_28_filled" fill="#212121" fill-rule="nonzero">
                                <path d="M16.4798956,21.0019578 L16.75,21 C17.9702352,21 18.6112441,21.5058032 19.4020627,22.7041662 L19.7958278,23.3124409 C20.1028266,23.766938 20.2944374,23.9573247 20.535784,24.0567929 C20.9684873,24.2351266 21.3271008,24.1474446 22.6440782,23.5133213 L23.0473273,23.3170319 C23.8709982,22.9126711 24.4330286,22.6811606 25.0680983,22.5223931 C25.4699445,22.4219316 25.8771453,22.6662521 25.9776069,23.0680983 C26.0780684,23.4699445 25.8337479,23.8771453 25.4319017,23.9776069 C25.0371606,24.0762922 24.6589465,24.2178819 24.1641364,24.4458997 L23.0054899,25.0032673 C21.4376302,25.7436944 20.9059009,25.8317321 19.964216,25.4436275 C19.3391237,25.1860028 18.9836765,24.813298 18.4635639,24.0180227 L18.2688903,23.7140849 C17.6669841,22.7656437 17.3640608,22.5 16.75,22.5 L16.5912946,22.5037584 C16.1581568,22.5299816 15.8777212,22.7284469 14.009281,24.1150241 C12.2670395,25.4079488 10.9383359,26.0254984 9.24864243,26.0254984 C7.18872869,26.0254984 5.24773367,25.647067 3.43145875,24.8905363 L6.31377803,24.2241784 C7.25769404,24.4250762 8.23567143,24.5254984 9.24864243,24.5254984 C10.5393035,24.5254984 11.609129,24.0282691 13.1153796,22.9104743 L14.275444,22.0545488 C15.5468065,21.1304903 15.8296113,21.016032 16.4798956,21.0019578 L16.4798956,21.0019578 Z M22.7770988,3.22208979 C24.4507223,4.8957133 24.4507566,7.60916079 22.7771889,9.28281324 L21.741655,10.3184475 C22.8936263,11.7199657 22.8521526,13.2053774 21.7811031,14.279556 L18.7800727,17.2805874 L18.7800727,17.2805874 C18.4870374,17.5733384 18.0121637,17.573108 17.7194126,17.2800727 C17.4266616,16.9870374 17.426892,16.5121637 17.7199273,16.2194126 L20.7188969,13.220444 C21.2039571,12.7339668 21.2600021,12.1299983 20.678941,11.3818945 L10.0845437,21.9761011 C9.78635459,22.2743053 9.41036117,22.482705 8.99944703,22.5775313 L2.91864463,23.9807934 C2.37859061,24.1054212 1.89457875,23.6214094 2.0192066,23.0813554 L3.42247794,17.0005129 C3.51729557,16.5896365 3.72566589,16.2136736 4.0238276,15.9154968 L16.7165019,3.22217992 C18.3900415,1.54855555 21.1034349,1.54851059 22.7770988,3.22208979 Z" id="🎨-Color">

                                </path>
                            </g>
                        </g>
                    </svg>
                    Lihat&nbsp;Tanda&nbsp;Tangan
                </span>
            </a>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Kategori Izin" readonly>
            <input type="text" class="form-control" id="izin" name="izin" style="font-weight: bold" value="{{ $data->izin }}" readonly>
        </div>
        @if($data->izin=='Sakit')
        <div id="modal_surat" class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal_surat_dokter{{$data->id}}">
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
        @endif
        <div class="modal fade" id="modal_surat_dokter{{$data->id}}">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">SURAT KETERANGAN SAKIT </h5>
                    </div>
                    <div class="modal-body">
                        <img src="{{url('../storage/app/public/foto_izin/',$data->foto_izin)}}" alt="" id="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Tanggal" readonly>
            <input type="date" name="tanggal" id="tanggal" value="{{$data->tanggal}}" readonly style="font-weight: bold" required placeholder="Tanggal" class="form-control">
        </div>
        <div id="form_jam_pulang_cepat" class="input-group">
            <input type="text" class="form-control" value="Jam Keluar" readonly>
            <input type="time" name="jam_pulang_cepat" id="jam_pulang_cepat" @if($data->status_izin=='0') @else readonly @endif value="@if($jam_kerja=='') @else{{ \Carbon\Carbon::parse($jam_kerja->Shift->jam_keluar)->addHour(-3)->format('H:i')}}@endif" min="" style="font-weight: bold" placeholder="Jam Pulang" class="form-control">
        </div>
        <div id="form_tgl_selesai" class="input-group">
            <input type="text" class="form-control" value="Tanggal Selesai" readonly>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{$data->tanggal_selesai}}" readonly style="font-weight: bold" placeholder="Tanggal" class="form-control">
        </div>
        <div id="jam_masuk_kerja" class="input-group">
            <input type="text" class="form-control" value="Jam Masuk Kerja" readonly>
            <input type="time" id="jam_masuk" name="jam_masuk" value="{{$data->jam_masuk_kerja}}" readonly style="font-weight: bold" placeholder="Jam Masuk Kerja" class="form-control">
        </div>
        <div id="form_jam_kembali" class="input-group">
            <input type="text" class="form-control" value="Jam Kembali" readonly>
            <input type="time" id="jam_kembali" name="jam_kembali" value="{{$data->jam_kembali}}" readonly style="font-weight: bold" placeholder="Jam Kembali" class="form-control">
        </div>
        <div id="jam_datang" class="input-group">
            <input type="text" class="form-control" value="Jam Datang" readonly>
            <input type="time" id="jam" name="jam" value="{{$data->jam}}" readonly style="font-weight: bold" placeholder="Jam Datang" class="form-control">
        </div>
        <div id="form_terlambat" class="input-group">
            <input type="text" class="form-control" value="Terlambat" readonly>
            <input type="text" id="terlambat" name="terlambat" value="{{$data->terlambat}}" readonly style="font-weight: bold" placeholder="Terlambat" class="form-control">
        </div>
        <div class="input-group">
            <textarea class="form-control" placeholder="Alasan Izin" name="keterangan_izin" readonly style="font-weight: bold" required>{{ $data->keterangan_izin }}</textarea>
        </div>
        <div id="form_user_backup" class="input-group">
            <input type="text" class="form-control" value="Pengganti" readonly>
            <input type="text" class="form-control" name="" id="" value="{{$data->user_name_backup}}" readonly>
            </select>
        </div>
        <div class="input-group">
            <textarea class="form-control" placeholder="Catatan" id="catatan" name="catatan" style="font-weight: bold"></textarea>
        </div>
        <div class="input-group">
            <div id="signature-pad" style="border:solid 1px teal; width:100%;height:200px;">
                <div>
                    <div id="note" onmouseover="my_function();"></div>
                    <canvas id="the_canvas" width="auto" height="100px"></canvas>
                    <p class="text-primary" style="text-align: center">Ttd : {{ $user_karyawan->name }} {{ date('Y-m-d') }}</p>
                    <hr>
                    <div class="text-center">
                        <input type="hidden" id="signature" name="signature">
                        <button type="button" id="clear_btn" class="btn btn-sm btn-danger btn-rounded" data-action="clear"><i class="fa fa-refresh" aria-hidden="true"> </i> &nbsp; Clear</button>
                        <button type="button" id="not_approve_btn" class="btn btn-sm btn-warning btn-rounded" data-action="not_save-png"><i class="fa fa-times" aria-hidden="true"> </i> &nbsp; Not Approve</button>
                        <button type="button" id="approve_btn" class="btn btn-sm btn-success btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Approve</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
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
        var izin = '{{$data->izin}}';
        var foto_izin = '{{$data->foto_izin}}';
        if (izin == 'Datang Terlambat') {
            $('#form_jam_kembali').hide();
            $('#jam_masuk_kerja').show();
            $('#jam_datang').show();
            $('#form_terlambat').show();
            $('#form_jam_pulang_cepat').hide();
            $('#form_user_backup').hide();
            $('#form_tgl_selesai').hide();
        } else if (izin == 'Sakit') {
            if (foto_izin == 'TIDAK ADA') {
                $('#jam_masuk_kerja').hide();
                $('#jam_datang').hide();
                $('#form_terlambat').hide();
                $('#form_jam_kembali').hide();
                $('#form_jam_pulang_cepat').hide();
                $('#form_user_backup').hide();
                $('#form_tgl_selesai').show();
            } else {
                $('#jam_masuk_kerja').hide();
                $('#jam_datang').hide();
                $('#form_terlambat').hide();
                $('#form_jam_kembali').hide();
                $('#form_jam_pulang_cepat').hide();
                $('#form_user_backup').hide();
                $('#form_tgl_selesai').hide();
            }
        } else if (izin == 'Pulang Cepat') {
            $('#form_jam_kembali').hide();
            $('#form_jam_pulang_cepat').show();
            $('#jam_masuk_kerja').hide();
            $('#form_terlambat').hide();
            $('#jam_datang').hide();
            $('#form_user_backup').hide();
            $('#form_tgl_selesai').hide();
        } else if (izin == 'Tidak Masuk (Mendadak)') {
            $('#form_user_backup').show();
            $('#form_jam_kembali').hide();
            $('#form_tgl_selesai').show();
            $('#jam_masuk_kerja').hide();
            $('#jam_datang').hide();
            $('#form_terlambat').hide();
            $('#form_jam_pulang_cepat').hide();
        } else if (izin == 'Keluar Kantor') {
            $('#form_user_backup').hide();
            $('#jam_masuk_kerja').hide();
            $('#jam_datang').hide();
            $('#form_terlambat').hide();
            $('#form_jam_pulang_cepat').show();
            $('#form_jam_kembali').show();
            $('#form_tgl_selesai').show();

        }
    });
</script>
<script type="text/javascript">
    $(function() {
        $(document).on('click', '#approve_btn', function(e) {
            // console.log('ok');
            e.preventDefault();
            var canvas = document.getElementById("the_canvas");
            var dataUrl = canvas.toDataURL();
            var approve = 'approve';
            var id = $('#id').val();
            var id_user = $('#id_user').val();
            var status_izin = $('#status_izin').val();
            var signature = dataUrl;
            var catatan = $('#catatan').val();
            var izin = $('#izin').val();
            var tanggal = $('#tanggal').val();
            var tanggal_selesai = $('#tanggal_selesai').val();
            if (signaturePad.isEmpty()) {
                swal.fire("Please provide signature first.");
                event.preventDefault();
            } else {
                $.ajax({
                    data: {
                        "_token": "{{ csrf_token() }}",
                        approve: approve,
                        id: id,
                        id_user: id_user,
                        status_izin: status_izin,
                        signature: signature,
                        izin: izin,
                        catatan: catatan,
                        tanggal: tanggal,
                        tanggal_selesai: tanggal_selesai,
                    },
                    url: "{{ url('/izin/approve/proses') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
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
                        console.log(data);
                        var url = "{{ url('/home') }}"; //the url I want to redirect to
                        $(location).attr('href', url);

                    },
                    error: function(data) {
                        console.log('error:', data)
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
                        var url = "{{ url('/home') }}"; //the url I want to redirect to
                        $(location).attr('href', url);
                    }
                });
            }
        });
        $(document).on('click', '#not_approve_btn', function(e) {
            e.preventDefault();
            // console.log('ok');
            var approve = 'not_approve';
            var canvas = document.getElementById("the_canvas");
            var dataUrl = canvas.toDataURL();
            var id = $('#id').val();
            var id_user = $('#id_user').val();
            var status_izin = $('#status_izin').val();
            var signature = dataUrl;
            var izin = $('#izin').val();
            var catatan = $('#catatan').val();
            var tanggal = $('#tanggal').val();
            var tanggal_selesai = $('#tanggal_selesai').val();
            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}",
                    approve: approve,
                    id: id,
                    id_user: id_user,
                    status_izin: status_izin,
                    izin: izin,
                    signature: signature,
                    catatan: catatan,
                    tanggal: tanggal,
                    tanggal_selesai: tanggal_selesai,
                },
                url: "{{ url('/izin/approve/proses') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
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
                    console.log(data);
                    var url = "{{ url('/home') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                },
                error: function(data) {
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
                    var url = "{{ url('/home') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                }
            });
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
    window.onbeforeunload = function() {
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
    };
</script>
@endsection