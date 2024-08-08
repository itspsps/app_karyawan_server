@extends('users.cuti.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
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
    @if($get_cuti->status_cuti == '1')
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
        <strong>&nbsp;Tunggu!&nbsp;</strong> Dalam Proses Approve {{$get_cuti->approve_atasan}}
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif($get_cuti->status_cuti == '2')
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
        <strong>&nbsp;Tunggu!&nbsp;</strong> Dalam Proses Approve {{$get_cuti->approve_atasan2}}
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif($get_cuti->status_cuti == '3')
    <div class="alert alert-success light alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polyline points="9 11 12 14 22 4"></polyline>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
        <strong>Sukses!</strong> Data Pengajuan Cuti Anda Sudah Disetujui
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @elseif($get_cuti->status_cuti == 'NOT APPROVE')
    <div class="alert alert-danger light alert-dismissible fade show">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="20" height="20" viewBox="0 0 512 512" xml:space="preserve">
            <path style="fill:#CCCCCC;" d="M255.832,32.021c123.697,0.096,223.907,100.45,223.811,224.147s-100.45,223.907-224.147,223.811  C131.863,479.883,31.685,379.633,31.685,256C31.869,132.311,132.143,32.117,255.832,32.021 M255.832,0  C114.443,0.096-0.096,114.779,0,256.168S114.779,512.096,256.168,512C397.485,511.904,512,397.317,512,256  C511.952,114.571,397.261-0.048,255.832,0z" />
            <g>

                <rect x="227.863" y="113.103" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0971 255.9227)" style="fill:#E21B1B;" width="56.028" height="285.857" />

                <rect x="112.943" y="227.962" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -106.0594 255.9024)" style="fill:#E21B1B;" width="285.857" height="56.028" />
            </g>
        </svg>
        <strong>Info!</strong>&nbsp;Data Pengajuan Anda Di Tolak
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="alert alert-danger light alert-dismissible fade show">
        <span class="text-center">
            {{$get_cuti->catatan}}
        </span>
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <form class="my-2" method="post" action="{{ url('/cuti/edit-cuti-proses/') }}" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="hidden" name="id" value="{{$get_cuti->id}}">
            <input type="hidden" name="id_user" value="{{Auth::user()->id }}">
            <input type="hidden" name="telp" value="{{$get_cuti->telp}}">
            <input type="hidden" name="email" value="{{$get_cuti->email}}">
            <input type="hidden" name="departements" value="{{$get_cuti->departements_id}}">
            <input type="hidden" name="jabatan" value="{{ $get_cuti->jabatan_id}}">
            <input type="hidden" name="level_jabatan" value="{{$user->level_jabatan}}">
            <input type="hidden" name="divisi" value="{{$get_cuti->divisi_id}}">
            <input type="hidden" name="id_user_atasan" value="{{$get_cuti->id_user_atasan}}">
            <input type="hidden" name="id_user_atasan2" value="{{$get_cuti->id_user_atasan2}}">
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Nama" readonly>
            <input type="text" class="form-control" name="fullname" value="{{ Auth::user()->fullname }}" style="font-weight: bold" readonly required>
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($get_cuti->ttd_user != '')
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
                            <h5 class="modal-title">TTD : {{ $user->name }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($user->name=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$get_cuti->ttd_user.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            <p style="text-align: center;font-weight: bold">{{ \Carbon\Carbon::parse($get_cuti->waktu_ttd_user)->isoFormat('D MMMM Y HH:m')}} WIB</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Kuota Cuti Tahunan" readonly>
            <input type="text" class="form-control" name="kuota_cuti" value="{{ $user->kuota_cuti_tahunan }} Hari" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Kategori Cuti" readonly>
            @if ($get_cuti->status_cuti == 0)
            <select class="form-control" id="cuti" name="cuti" required>
                <option value="">Pilih Cuti...</option>
                <option value="Cuti Tahunan" {{($get_cuti->nama_cuti=='Cuti Tahunan') ? 'selected' : ''}}>Cuti Tahunan</option>
                <option value="Diluar Cuti Tahunan" {{($get_cuti->nama_cuti=='Diluar Cuti Tahunan') ? 'selected' : ''}}>Diluar Cuti Tahunan</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$get_cuti->nama_cuti}}" readonly>
            @endif
        </div>
        <div id="kategori_cuti" class="input-group">
            <input type="text" class="form-control" value="Pilihan Cuti" readonly>
            @if ($get_cuti->status_cuti == 0)
            <select class="form-control" id="id_cuti" name="kategori_cuti">
                <option value="">Pilih Cuti...</option>
                @foreach($get_kategori_cuti as $data)
                <option value="{{$data->id}}" {{($data->id == $get_cuti->kategori_cuti_id) ? 'selected' : ''}}>{{$data->nama_cuti}}
                </option>
                @endforeach
            </select>
            @else
            <select class="form-control" id="id_cuti" name="kategori_cuti" disabled>
                <option value="">Pilih Cuti...</option>
                @foreach($get_kategori_cuti as $data)
                <option value="{{$data->id}}" {{($data->id == $get_cuti->kategori_cuti_id) ? 'selected' : ''}}>{{$data->nama_cuti}}
                </option>
                @endforeach
            </select>
            @endif
        </div>
        <div id="kuota_hari" class="input-group">
            <input type="text" class="form-control" value="Jumlah Hari" readonly>
            <input type="text" class="form-control" name="jumlah_cuti" id="jumlah_cuti" value="{{$get_cuti->total_cuti}} Hari" readonly>
        </div>
        <div class="input-group">
            @if($user->level_jabatan=='1')
            <input type="text" class="form-control" value="Pengganti" readonly>
            @if ($get_cuti->status_cuti == 0)
            <select class="form-control" name="user_backup" required>
                <option selected value="-">-</option>
            </select>
            @else
            <select class="form-control" name="user_backup" disabled>
                @foreach($get_user_backup as $data)
                <option value="{{$data->id}}" {{($data->id == $get_cuti->user_id_backup) ? 'selected' : ''}}>{{$data->fullname}}
                    @endforeach
            </select>
            @endif
            @else
            <input type="text" class="form-control" value="Pengganti" readonly>
            @if ($get_cuti->status_cuti == 0)
            <select class="form-control" name="user_backup" required>
                <option value="">Pilih Pengganti...</option>
                @foreach($get_user_backup as $data)
                <option value="{{$data->id}}" {{($data->id == $get_cuti->user_id_backup) ? 'selected' : ''}}>{{$data->fullname}}
                </option>
                @endforeach
            </select>
            @else
            <select class="form-control" name="user_backup" disabled>
                <option value="">Pilih Pengganti...</option>
                @foreach($get_user_backup as $data)
                <option value="{{$data->id}}" {{($data->id == $get_cuti->user_id_backup) ? 'selected' : ''}}>{{$data->fullname}}
                </option>
                @endforeach
            </select> @endif
            @endif
        </div>
        <div class="input-group">
            @if ($get_cuti->status_cuti == 0)
            <input type="text" class="form-control" id="name_form_tanggal" value="Tanggal Mulai" readonly>
            <input type="text" id="date_range_cuti" name="tanggal_cuti" value="{{$get_cuti->tanggal_mulai}}-{{$get_cuti->tanggal_selesai}}" style="font-weight: bold" required placeholder="Tanggal Cuti" class="form-control">
            @else
            <input type="text" class="form-control" value="Tanggal Mulai" readonly>
            <input type="text" value="{{ \Carbon\Carbon::parse($get_cuti->tanggal_mulai)->format('d/m/Y')}}" style="font-weight: bold" readonly placeholder="Tanggal Cuti" class="form-control">
            @endif
        </div>
        @if ($get_cuti->status_cuti != 0)
        <div class="input-group">
            <input type="text" class="form-control" value="Tanggal Selesai" readonly>
            <input type="text" value="{{ \Carbon\Carbon::parse($get_cuti->tanggal_selesai)->format('d/m/Y')}}" style="font-weight: bold" readonly placeholder="Tanggal Cuti" class="form-control">
        </div>
        @endif
        <div class="input-group">
            @if ($get_cuti->status_cuti == 0)
            <textarea class="form-control" name="keterangan_cuti" style="font-weight: bold" required placeholder="Keterangan">{{$get_cuti->keterangan_cuti}}</textarea>
            @else
            <textarea class="form-control" name="keterangan_cuti" style="font-weight: bold" disabled placeholder="Keterangan">{{$get_cuti->keterangan_cuti}}</textarea>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Approve By 1" readonly>
            <input type="text" class="form-control" name="approve_atasan" value="{{ $get_cuti->approve_atasan }}" readonly>
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($get_cuti->ttd_atasan != '')
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
                            <h5 class="modal-title">TTD : {{ $get_cuti->approve_atasan }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($get_cuti->ttd_atasan=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$get_cuti->ttd_atasan.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            <p style="text-align: center;font-weight: bold">{{ $get_cuti->waktu_approve }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Approve By 2" readonly>
            <input type="text" class="form-control" name="approve_atasan2" value="{{ $get_cuti->approve_atasan2 }}" readonly>
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($get_cuti->ttd_atasan2 != '')
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
                            <h5 class="modal-title">TTD : {{ $get_cuti->approve_atasan2 }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($get_cuti->ttd_atasan2=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$get_cuti->ttd_atasan2.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            <p style="text-align: center;font-weight: bold">{{ \Carbon\Carbon::parse($get_cuti->waktu_approve2)->format('d/m/Y H:i')}}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($get_cuti->ttd_user=='')
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
                        <button type="submit" id="save_btn" class="btn btn-sm btn-primary btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Update</button>
                    </div>

                </div>
            </div>
        </div>
        @else
        <div class="text-center" style="margin: 0 auto;">
            <a href="{{ url('cuti/dashboard') }}" class="btn btn-sm btn-primary btn-rounded">
                <i class="fa fa-arrow-left" aria-hidden="true"> </i>
                &nbsp; Kembali
            </a>
        </div>
        @endif
    </form>
</div>
@endsection
@section('js')
{{-- <link rel="stylesheet" href="{{ asset('assets_ttd/libs/css/bootstrap.v3.3.6.css') }}"> --}}
<script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
    $('#id_cuti').on('change', function() {
        let id_cuti = $(this).val();
        // console.log(id_cuti);
        $.ajax({
            type: 'GET',
            url: "{{url('get_cuti')}}",
            data: {
                id_cuti: id_cuti
            },
            cache: false,

            success: function(msg) {
                $('#jumlah_cuti').val(msg + ' Hari');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var cuti = '{{$get_cuti->nama_cuti}}';
        // console.log(cuti);
        if (cuti == 'Cuti Tahunan') {
            $('#kategori_cuti').hide();
            $('#kuota_hari').hide();
            var mulai = '{{$get_cuti->tanggal_mulai}}';
            var selesai = '{{$get_cuti->tanggal_selesai}}';
            $('#name_form_tanggal').val('Tanggal Mulai Cuti');
            var start = moment(mulai);
            var end = moment(selesai);
            $('input[id="date_range_cuti"]').daterangepicker({
                drops: 'auto',
                minDate: start,
                startDate: start,
                endDate: end,
                autoApply: true,
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        } else {
            var mulai = '{{$get_cuti->tanggal_mulai}}';
            var start = moment(mulai);
            $('input[id="date_range_cuti"]').daterangepicker({
                drops: 'auto',
                startDate: start,
                minDate: start,
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: false,
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
            $('#kategori_cuti').show();
            $('#name_form_tanggal').val('Tanggal Mulai Cuti');
        }
        $('body').on("change", "#cuti", function() {
            var id = $(this).val();
            // console.log(id);
            if (id == 'Diluar Cuti Tahunan') {
                $('#kategori_cuti').show();
                $('#name_form_tanggal').val('Tanggal Mulai Cuti');
                $('#id_cuti').on('change', function() {
                    let id_cuti = $(this).val();
                    // console.log(id_cuti);
                    $.ajax({
                        type: 'GET',
                        url: "{{url('get_cuti')}}",
                        data: {
                            id_cuti: id_cuti
                        },
                        cache: false,

                        success: function(msg) {
                            $('#jumlah_cuti').val(msg + ' Hari');
                            $('#name_form_tanggal').val('Tanggal Mulai Cuti');
                            $('#kuota_hari').show();
                            var start = moment();
                            $('input[id="date_range_cuti"]').daterangepicker({
                                drops: 'auto',
                                startDate: start,
                                minDate: start,
                                singleDatePicker: true,
                                showDropdowns: true,
                                autoApply: false,
                            }, function(start, end, label) {
                                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                            });
                        },
                        error: function(data) {
                            console.log('error:', data)
                        },

                    })
                })

            } else {
                $('#kategori_cuti').hide();
                $('#kuota_hari').hide();
                $('#id_cuti').val('');
                $('#name_form_tanggal').val('Tanggal Cuti');
                var start = moment().subtract(-14, 'days');
                $('input[id="date_range_cuti"]').daterangepicker({
                    drops: 'auto',
                    minDate: start,
                    startDate: start,
                    endDate: start,
                    autoApply: true,
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });
            }
        });
    });
</script>
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