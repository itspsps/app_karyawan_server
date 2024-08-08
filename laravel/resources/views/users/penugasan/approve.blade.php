@extends('users.penugasan.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
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
    @elseif(Session::has('penugasangagal'))
    <div class="alert alert-danger light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <strong>Warning!</strong> Anda Gagal Pengajuan Perjalanan Dinas.
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <form class="my-2" method="post" action="{{ url('/penugasan/approve/diminta/ttd/'.$id_penugasan->id) }}" enctype="multipart/form-data">
        @csrf
        @if($penugasan->id_diminta_oleh==Auth::guard('web')->user()->id)
        <input type="hidden" name="status_penugasan" value="2">
        @elseif($penugasan->id_disahkan_oleh==Auth::guard('web')->user()->id)
        <input type="hidden" name="status_penugasan" value="3">
        @elseif($penugasan->id_user_hrd==Auth::guard('web')->user()->id)
        <input type="hidden" name="status_penugasan" value="4">
        @elseif($penugasan->id_user_finance==Auth::guard('web')->user()->id)
        <input type="hidden" name="status_penugasan" value="5">
        @endif
        <!-- <input type="text" id="id_diajukan_oleh" name="id_diajukan_oleh" value="{{$penugasan->id_diajukan_oleh}}">
        <input type="text" id="id_diminta_oleh" name="id_diminta_oleh" value="{{$penugasan->id_diminta_oleh}}">
        <input type="text" id="id_disahkan_oleh" name="id_disahkan_oleh" value="{{$penugasan->id_disahkan_oleh}}">
        <input type="text" id="id_user_hrd" name="id_user_hrd" value="{{$penugasan->id_user_hrd}}">
        <input type="text" id="id_user_finance" name="id_user_finance" value="{{$penugasan->id_user_finance}}"> -->
        <div class="input-group">
            <input type="text" class="form-control" value="Nama" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->fullname }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="NIK" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nik }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Jabatan" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_jabatan }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Divisi" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_departemen }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Departemen" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_divisi }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Asal Kerja" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->asal_kerja }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Penugasan" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->penugasan }}" readonly style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Tanggal Kunjungan" readonly>
            <input type="date" name="" value="{{ $penugasan->tanggal_kunjungan }}" readonly style="font-weight: bold" required placeholder="Phone number" class="form-control">
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Selesai Kunjungan" readonly>
            <input type="date" name="" value="{{ $penugasan->selesai_kunjungan}}" readonly style="font-weight: bold" required placeholder="Phone number" class="form-control">
        </div>
        <div class="input-group">
            <textarea class="form-control" name="" readonly style="font-weight: bold" required>{{ $penugasan->kegiatan_penugasan }}</textarea>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="PIC dikunjungi" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->pic_dikunjungi }}" readonly style="font-weight: bold" required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Alamat" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->alamat }}" readonly style="font-weight: bold" required>
        </div>
        <hr>
        <div class="input-group">
            <input type="text" class="form-control" value="Transportasi" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->transportasi }}" readonly style="font-weight: bold" required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Kelas" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->kelas }}" readonly style="font-weight: bold" required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Budget Hotel" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->budget_hotel }}" readonly style="font-weight: bold" required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Makan" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->makan }}" readonly style="font-weight: bold" required>
        </div>
        <hr>
        <div class="input-group">
            <input type="text" class="form-control" value="Diajukan oleh" readonly>
            <input type="text" class="form-control" name="" readonly value="{{ $penugasan->nama_user }}" readonly style="font-weight: bold">
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
            <div class="modal fade" id="modal_ttd">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">TTD : {{ $penugasan->nama_user }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_id_diajukan_oleh=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$penugasan->ttd_id_diajukan_oleh.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_id_diajukan_oleh.'.png') }}" alt=""> --}}
                        </div>
                        <p style="text-align:center;font-weight: bold">{{ $penugasan->waktu_ttd_id_diajukan_oleh }}</p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Diminta oleh" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_diminta }}" readonly style="font-weight: bold">
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if($penugasan->ttd_id_diminta_oleh !='')
            <a href="javascript:void(0);" data-bs-target="#modal_ttd1" data-bs-toggle="modal">
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
            @else
            <a href="javascript:void(0);">
                <span class="badge light badge-sm badge-danger me-1 mb-1">
                    Belum&nbsp;Tanda&nbsp;Tangan
                </span>
            </a>
            @endif
            <div class="modal fade" id="modal_ttd1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">TTD : {{ $penugasan->nama_diminta }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_id_diminta_oleh=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$penugasan->ttd_id_diminta_oleh.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_id_diminta_oleh.'.png') }}" alt=""> --}}
                            <p style="text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_id_diminta_oleh }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Disahkan oleh" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_disahkan }}" readonly style="font-weight: bold">
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($penugasan->ttd_id_disahkan_oleh != '')
            <a href="javascript:void(0);" data-bs-target="#modal_ttd2" data-bs-toggle="modal">
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
            @else
            <a href="javascript:void(0);">
                <span class="badge light badge-sm badge-danger me-1 mb-1">
                    Belum&nbsp;Tanda&nbsp;Tangan
                </span>
            </a>
            @endif
            <div class="modal fade" id="modal_ttd2">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">TTD : {{ $penugasan->nama_disahkan }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_id_disahkan_oleh=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$penugasan->ttd_id_disahkan_oleh.'.png') }}" style="width: 50%;margin-left: 25%;margin-right: 25%" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_id_disahkan_oleh.'.png') }}" alt=""> --}}
                            <p style="text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_id_disahkan_oleh }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Diproses HRD" readonly>
            <input type="text" class="form-control" name="" value="{{$penugasan->nama_hrd}}" readonly style="font-weight: bold">
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($penugasan->ttd_proses_hrd != '')
            <a href="javascript:void(0);" data-bs-target="#modal_ttd3" data-bs-toggle="modal">
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
            @else
            <a href="javascript:void(0);">
                <span class="badge light badge-sm badge-danger me-1 mb-1">
                    Belum&nbsp;Tanda&nbsp;Tangan
                </span>
            </a>
            @endif
            <div class="modal fade" id="modal_ttd3">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">TTD : {{ $penugasan->nama_hrd }}</h5>
                        </div>
                        <div class=" modal-body">
                            @if($penugasan->ttd_proses_hrd=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$penugasan->ttd_proses_hrd.'.png') }}" style="width: 50%;margin-left: 25%;margin-right: 25%" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_proses_hrd.'.png') }}" alt=""> --}}
                            <p style="text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_proses_hrd }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Diproses Finance" readonly>
            <input type="text" class="form-control" name="" value="{{$penugasan->nama_finance}}" readonly style="font-weight: bold">
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($penugasan->ttd_proses_finance != '')
            <a href="javascript:void(0);" data-bs-target="#modal_ttd4" data-bs-toggle="modal">
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
            @else
            <a href="javascript:void(0);">
                <span class="badge light badge-sm badge-danger me-1 mb-1">
                    Belum&nbsp;Tanda&nbsp;Tangan
                </span>
            </a>
            @endif
            <div class="modal fade" id="modal_ttd4">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">TTD : {{ $penugasan->nama_finance }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_proses_finance=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$penugasan->ttd_proses_finance.'.png') }}" style="width: 50%;margin-left: 25%;margin-right: 25%" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_proses_finance.'.png') }}" alt=""> --}}
                            <p style="text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_proses_finance }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <button type="submit" id="save_btn" class="btn btn-sm btn-success btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Approve</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection
@section('js')
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
@endsection