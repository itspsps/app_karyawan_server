@extends('users.izin.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css"> --}}

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>

<link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">

<style>
    .kbw-signature {
        width: 100%;
        height: 200px;
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }
</style>

<div class="container">
    <!-- Modal -->
    <div class="container">
        <form id="form_approve" class="my-2" method="post" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                <input type="hidden" id="status_cuti" name="status_cuti" value="{{ $data->status_cuti }}">
                <input type="hidden" id="user_id_atasan1" name="user_id_atasan1" value="{{ $data->id_user_atasan }}">
                <input type="hidden" id="user_id_atasan2" name="user_id_atasan2" value="{{ $data->id_user_atasan2 }}">
            </div>
            <div class="input-group">
                <input type="text" class="form-control" value="Nama" readonly>
                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $data->name }}" style="font-weight: bold" readonly required>
            </div>
            <div class="modal fade" id="modal_ttd">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">TTD : {{ $data->name }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($data->ttd_user=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data->ttd_user.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            <p style="text-align: center;font-weight: bold">{{ \Carbon\Carbon::parse($data->waktu_ttd_user)->isoFormat('D MMMM Y HH:m')}} WIB</p>
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
            @if($data->nama_cuti=='Diluar Cuti Tahunan')
            <div class="input-group">
                <input type="text" class="form-control" value="Kategori Cuti" readonly>
                <input type="text" class="form-control" style="font-weight: bold" value="{{ $data->nama_cuti }}" readonly>
            </div>
            <div class="input-group">
                <input type="text" class="form-control" value="Pilihan Cuti" readonly>
                <input type="text" class="form-control" style="font-weight: bold" value="{{ $data->KategoriCuti->nama_cuti }}" readonly>
            </div>
            @else
            <div class="input-group">
                <input type="text" class="form-control" value="Kategori Cuti" readonly>
                <input type="text" class="form-control" style="font-weight: bold" value="{{ $data->nama_cuti }}" readonly>
            </div>
            @endif
            <div class="input-group">
                <input type="text" class="form-control" value="Nama Pengganti" readonly>
                @if($get_id_backup=='')
                <input type="text" class="form-control" style="font-weight: bold" value="-" readonly>
                @else
                <input type="text" class="form-control" style="font-weight: bold" value="{{ $get_id_backup->name }}" readonly>
                @endif
            </div>
            <div class="input-group">
                <input type="text" name="jam" value="Tanggal Mulai" readonly required placeholder="Phone number" class="form-control">
                <input type="text" name="tanggal" value="{{ \Carbon\Carbon::parse($data->tanggal_mulai)->isoFormat('D MMMM Y')}}" readonly style="font-weight: bold" required placeholder="Phone number" class="form-control">
            </div>
            <div class="input-group">
                <input type="text" name="jam" value="Tanggal Selesai" readonly required placeholder="Phone number" class="form-control">
                <input type="text" name="tanggal" value="{{ \Carbon\Carbon::parse($data->tanggal_selesai)->isoFormat('D MMMM Y')}}" readonly style="font-weight: bold" required placeholder="Phone number" class="form-control">
            </div>
            <div class="input-group">
                <input type="text" name="jam" value="Total Pengajuan" readonly required placeholder="Phone number" class="form-control">
                @if($data->nama_cuti=='Diluar Cuti Tahunan')
                <input type="text" name="tanggal" value="{{ $data->total_cuti }} Hari" readonly style="font-weight: bold" required placeholder="Phone number" class="form-control">
                @else
                <input type="text" name="tanggal" value="{{ $data->total_cuti }} Dari @if($data->kuota_cuti_tahunan==0 || $data->kuota_cuti_tahunan=='')0 @else{{ $data->kuota_cuti_tahunan }}@endif Kuota Cuti" readonly style="font-weight: bold" required placeholder="Phone number" class="form-control">
                @endif
            </div>
            <div class="input-group">
                <textarea class="form-control" placeholder="Catatan" name="keterangan_izin" readonly style="font-weight: bold" required>{{ $data->keterangan_cuti }}</textarea>
            </div>
            <div class="input-group">
                <input class="form-control" placeholder="Catatan Atasan" id="catatan" name="catatan" required style="font-weight: bold" />
            </div>
            <div class="input-group">
                <div id="signature-pad" style="border:solid 1px teal; width:100%;height:200px;">
                    <div>
                        <div id="note" onmouseover="my_function();"></div>
                        <canvas id="the_canvas" width="auto" height="100px"></canvas>
                        <p class="text-primary" style="text-align: center">Ttd : {{ Auth::user()->name }} {{ date('d-m-Y') }}</p>
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
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
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
    $(function() {
        $(document).on('click', '#approve_btn', function(e) {
            e.preventDefault();
            var canvas = document.getElementById("the_canvas");
            var dataUrl = canvas.toDataURL();
            var approve = 'approve';
            var id = $('#id').val();
            var status_cuti = $('#status_cuti').val();
            var user_id_atasan1 = $('#user_id_atasan1').val();
            var user_id_atasan2 = $('#user_id_atasan2').val();
            var signature = dataUrl;
            var catatan = $('#catatan').val();
            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}",
                    approve: approve,
                    id: id,
                    status_cuti: status_cuti,
                    user_id_atasan1: user_id_atasan1,
                    user_id_atasan2: user_id_atasan2,
                    signature: signature,
                    catatan: catatan,
                },
                url: "{{ url('/cuti/approve/proses') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var url = "{{ url('/home') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                },
                error: function(data) {
                    var url = "{{ url('/home') }}"; //the url I want to redirect to
                    $(location).attr('href', url);
                }
            });
        });
        $(document).on('click', '#not_approve_btn', function(e) {
            e.preventDefault();
            var approve = 'not_approve';
            var canvas = document.getElementById("the_canvas");
            var dataUrl = canvas.toDataURL();
            var id = $('#id').val();
            var status_cuti = $('#status_cuti').val();
            var user_id_atasan1 = $('#user_id_atasan1').val();
            var user_id_atasan2 = $('#user_id_atasan2').val();
            var signature = dataUrl;
            var catatan = $('#catatan').val();
            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}",
                    approve: approve,
                    id: id,
                    status_cuti: status_cuti,
                    user_id_atasan1: user_id_atasan1,
                    user_id_atasan2: user_id_atasan2,
                    signature: signature,
                    catatan: catatan,
                },
                url: "{{ url('/cuti/approve/proses') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    var url = "{{ url('/home') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                },
                error: function(data) {
                    var url = "{{ url('/home') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                }
            });
        });
    });
</script>
@endsection