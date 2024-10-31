@extends('users.penugasan.layout.main')
@section('title')
APPS | KARYAWAN - SP
@endsection
@section('content')

{{-- <link rel="stylesheet" href="{{ asset('assets_ttd/libs/css/bootstrap.v3.3.6.css') }}"> --}}
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

    </div>
</div>

<div class="fixed-content p-0">
    <div class="container">
        <div class="main-content">
            <div class="left-content">
                <a id="btn_klik" href="{{url('/penugasan/dashboard')}}" class="btn-back">
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
    @if (Session::has('penugasansukses'))
    <div class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Pengajuan Perdin
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
        <strong>Warning!</strong> Anda Gagal Pengajuan Perdin.
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <form class="my-2" method="post" action="{{ url('/penugasan/detail/update/'.$id_penugasan) }}" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
            {{-- <input type="hidden" name="telp" value="{{ $data_user->telepon }}"> --}}
            {{-- <input type="hidden" name="email" value="{{ $data_user->email }}"> --}}
            {{-- <input type="hidden" name="departements" value="{{ $user->dept_id }}"> --}}
            {{-- <input type="hidden" name="jabatan" value="{{ $user->jabatan_id }}"> --}}
            {{-- <input type="hidden" name="divisi" value="{{ $user->divisi_id }}" id=""> --}}
            <input type="hidden" name="status_penugasan" id="" value="1">
            <input type="hidden" name="id_user_atasan" value="{{ $penugasan->id_user_atasan }}">
            <input type="hidden" name="id_user_atasan2" value="{{ $penugasan->id_user_atasan2 }}">
            <input type="hidden" name="nik" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_jabatan" value="{{ $penugasan->id_jabatan }}">
            <input type="hidden" name="id_departemen" value="{{ $penugasan->id_departemen }}">
            <input type="hidden" name="id_divisi" value="{{ $penugasan->id_divisi }}">
            <input type="hidden" name="id_diajukan_oleh" value="{{ Auth::user()->id }}">
            <input type="hidden" name="id_diminta_oleh" value="{{ $penugasan->id_diminta_oleh }}">
            <input type="hidden" name="id_disahkan_oleh" value="{{ $penugasan->id_disahkan_oleh }}">
            <input type="hidden" name="proses_hrd" value="proses hrd">
            <input type="hidden" name="proses_finance" value="proses finance">
            <input type="hidden" name="tanggal_pengajuan" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="jam_pengajuan" value="{{ date('h:i:s') }}">
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Nama" readonly>
            <input type="text" class="form-control" name="" value="{{ Auth::user()->fullname }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="NIK" readonly>
            <input type="text" class="form-control" name="" value="{{ Auth::user()->nik }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Jabatan" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_jabatan }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Depaartemen" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_departemen }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Divisi" readonly>
            <input type="text" class="form-control" name="" value="{{ $penugasan->nama_divisi }}" style="font-weight: bold" readonly required>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Asal Kerja" readonly>
            <select class="form-control" id="asal_kerja" name="asal_kerja" @if($penugasan->status_penugasan >= '0') disabled @endif required>
                <option value="">Pilih Asal Kerja...</option>
                @foreach($master_lokasi as $lokasi)
                <option value="{{$lokasi->lokasi_kantor}}" @if($lokasi->lokasi_kantor==$penugasan->asal_kerja)selected @endif>{{$lokasi->lokasi_kantor}}</option>
                @endforeach
            </select>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Penugasan" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" name="penugasan" required>
                <option value="">Pilih Penugasan...</option>
                <option value="Dalam Kota" {{$penugasan->penugasan == 'Dalam Kota' ? 'selected' : ''}}>Dalam Kota</option>
                <option value="Luar Kota" {{$penugasan->penugasan == 'Luar Kota' ? 'selected' : ''}}>Luar Kota</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->penugasan}}" readonly>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Wilayah Penugasan" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" id="wilayah_penugasan" name="wilayah_penugasan" required>
                <option value="">Wilayah Penugasan...</option>
                <option value="Wilayah Kantor" {{$penugasan->wilayah_penugasan == 'Wilayah Kantor' ? 'selected' : ''}}>Wilayah Kantor</option>
                <option value="Diluar Kantor" {{$penugasan->wilayah_penugasan == 'Diluar Kantor' ? 'selected' : ''}}>Diluar Kantor</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->wilayah_penugasan}}" readonly>
            @endif
        </div>
        <input type="hidden" class="form-control" name="" id="wilayah" value="{{$penugasan->wilayah_penugasan}}">
        <div id="form_alamat_dikunjungi" class="input-group">
            <input type="text" class="form-control" value="Lokasi Kantor" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" id="alamat_dikunjungi" name="alamat_dikunjungi" style="font-weight: bold">
                <option selected disabled value="">-- Pilih Kantor --</option>
                @foreach($master_lokasi as $lokasi)
                <option value="{{$lokasi->lokasi_kantor}}" {{$penugasan->alamat_dikunjungi == $lokasi->lokasi_kantor ? 'selected' : ''}}>{{$lokasi->lokasi_kantor}}</option>
                @endforeach
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->alamat_dikunjungi}}" readonly>
            @endif
        </div>
        <div id="form_alamat_dikunjungi1" class="input-group">
            <input type="text" class="form-control" value="Alamat" readonly>
            @if ($penugasan->status_penugasan == 0)
            <input type="text" class="form-control" name="alamat_dikunjungi1" style="font-weight: bold" value="{{$penugasan->alamat_dikunjungi}}">
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->alamat_dikunjungi}}" readonly>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="PIC dikunjungi" readonly>
            @if ($penugasan->status_penugasan == 0)
            <input type="text" class="form-control" name="pic_dikunjungi" value="{{ $penugasan->pic_dikunjungi }}" style="font-weight: bold" required>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->pic_dikunjungi}}" readonly>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Tanggal Kunjungan" readonly>
            @if ($penugasan->status_penugasan == 0)
            <input type="text" id="tanggal_kunjungan" name="tanggal_kunjungan" value="" style="font-weight: bold" required readonly placeholder="Phone number" class="form-control">
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->tanggal_kunjungan}}-{{$penugasan->selesai_kunjungan}}" readonly>
            @endif
        </div>
        <!-- <div class="input-group">
            <input type="text" class="form-control" value="Selesai Kunjungan" readonly>
            @if ($penugasan->status_penugasan == 0)
            <input type="date" name="selesai_kunjungan" value="{{ $penugasan->selesai_kunjungan }}" style="font-weight: bold" required placeholder="Phone number" class="form-control">
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->selesai_kunjungan}}" readonly>
            @endif
        </div> -->
        <div class="input-group">
            @if ($penugasan->status_penugasan == 0)
            <textarea class="form-control" name="kegiatan_penugasan" style="font-weight: bold" required>{{ $penugasan->kegiatan_penugasan }}</textarea>
            @else
            <textarea class="form-control" style="font-weight: bold" readonly required>{{ $penugasan->kegiatan_penugasan }}</textarea>
            @endif
        </div>
        <hr>
        <div class="input-group">
            <input type="text" class="form-control" value="Transportasi" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" name="transportasi" required>
                <option value="">Pilih Transportasi...</option>
                <option value="Pesawat" {{ $penugasan->transportasi == 'Pesawat' ? 'selected' : '' }}>Pesawat</option>
                <option value="Kereta Api" {{ $penugasan->transportasi == 'Kereta Api' ? 'selected' : '' }}>Kereta Api</option>
                <option value="Bis" {{ $penugasan->transportasi == 'Bis' ? 'selected' : '' }}>Bis</option>
                <option value="Travel" {{ $penugasan->transportasi == 'Travel' ? 'selected' : '' }}>Travel</option>
                <option value="SPD Motor" {{ $penugasan->transportasi == 'SPD Motor' ? 'selected' : '' }}>SPD Motor</option>
                <option value="Mobil Dinas" {{ $penugasan->transportasi == 'Mobil Dinas' ? 'selected' : '' }}>Mobil Dinas</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->transportasi}}" readonly>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Kelas" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" name="kelas" required>
                <option value="">Pilih Kelas...</option>
                <option value="Eksekutif" {{ $penugasan->kelas == 'Eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                <option value="Bisnis" {{ $penugasan->kelas == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                <option value="Ekonomi" {{ $penugasan->kelas == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->kelas}}" readonly>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Budget Hotel" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" name="budget_hotel" required>
                <option value="">Pilih Budget...</option>
                <option value="400.000 sd 500.000" {{ $penugasan->budget_hotel == '400.000 sd 500.000' ? 'selected' : '' }}>Rp 400.000 sd 500.000</option>
                <option value="300.000 sd 400.000" {{ $penugasan->budget_hotel == '300.000 sd 400.000' ? 'selected' : '' }}>Rp 300.000 sd 400.000</option>
                <option value="200.000 sd 300.000" {{ $penugasan->budget_hotel == '200.000 sd 300.000' ? 'selected' : '' }}>Rp 200.000 sd 300.000</option>
                <option value="Kost Harian < Rp 200.000" {{ $penugasan->budget_hotel == 'Kost Harian < Rp 200.000' ? 'selected' : '' }}>Kost Harian < Rp 200.000</option>
                <option value="0" {{ $penugasan->budget_hotel == '0' ? 'selected' : '' }}>Tidak Ada</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->budget_hotel}}" readonly>
            @endif
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Makan" readonly>
            @if ($penugasan->status_penugasan == 0)
            <select class="form-control" name="makan" required>
                <option value="">Pilih Makan...</option>
                <option value="25.000" {{ $penugasan->makan == '25.000' ? 'selected' : '' }}>Rp 25.000</option>
                <option value="15.000" {{ $penugasan->makan == '15.000' ? 'selected' : '' }}>Rp 15.000</option>
            </select>
            @else
            <input type="text" class="form-control" name="" id="" value="{{$penugasan->makan}}" readonly>
            @endif
        </div>
        <hr>
        <div class="input-group">
            <input type="text" class="form-control" value="Biaya Ditanggung" readonly>
            <select class="form-control" id="biaya_ditanggung_oleh" name="biaya_ditanggung_oleh" required>
                <option value="">Pilih ..</option>
                <option value="{{$penugasan->asal_kerja}}" {{ $penugasan->asal_kerja == $penugasan->biaya_ditanggung ? 'selected' : '' }}>{{$penugasan->asal_kerja}}</option>
                <option value="{{$penugasan->alamat_dikunjungi}}" {{ $penugasan->alamat_dikunjungi == $penugasan->biaya_ditanggung ? 'selected' : '' }}>{{$penugasan->alamat_dikunjungi}}</option>
            </select>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Diajukan oleh" readonly>
            <input type="text" class="form-control" name="" readonly value="{{ $penugasan->nama_user }}" readonly style="font-weight: bold">
        </div>
        <div class="text-center" style="margin-top: -4%;margin-bottom: 2%;">
            @if ($penugasan->ttd_id_diajukan_oleh != '')
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
                            <h5 class="modal-title">TTD : {{ $penugasan->nama_user }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_id_diajukan_oleh=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$penugasan->ttd_id_diajukan_oleh.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_proses_finance.'.png') }}" alt=""> --}}
                            <p style="text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_id_diajukan_oleh }}</p>
                        </div>
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
            @if ($penugasan->ttd_id_diminta_oleh != '')
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
                            <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$penugasan->ttd_id_diminta_oleh.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_id_diminta_oleh.'.png') }}" alt=""> --}}
                        </div>
                        <p style="margin-top: 10%; text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_id_diminta_oleh }}</p>
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
                            <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$penugasan->ttd_id_disahkan_oleh.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_id_disahkan_oleh.'.png') }}" alt=""> --}}
                        </div>
                        <p style="margin-top: 10%; text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_id_disahkan_oleh }}</p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Diproses HRD" readonly>
            <select name="proses_hrd" id="proses_hrd" class="form-control">
                <option value="">Pilih</option>
                @foreach($hrd as $hrd)
                <option value="{{$hrd->id}}" {{$penugasan->id_user_hrd == $hrd->id ? 'selected' : ''}}>{{$hrd->name}}</option>
                @endforeach
            </select>
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
                            <h5 class="modal-title">TTD : {{ $hrd->name }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_proses_hrd=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$penugasan->ttd_proses_hrd.'.png') }}" style="width: 100%;" alt="">
                            @endif
                            {{-- <img width="100%" src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/"> --}}
                            {{-- <img src="{{ url('signature/'.$penugasan->ttd_proses_hrd.'.png') }}" alt=""> --}}
                        </div>
                        <p style="margin-top: 10%; text-align: center;font-weight: bold">{{ $penugasan->waktu_ttd_proses_hrd }}</p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" value="Diproses Finance" readonly>
            <select name="proses_finance" id="proses_finance" class="form-control">
                <option value="">Pilih</option>
                @foreach($finance as $finance)
                <option value="{{$finance->id}}" {{$penugasan->id_user_finance == $finance->id ? 'selected' : ''}}>{{$finance->name}}</option>
                @endforeach
            </select>
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
                            <h5 class="modal-title">TTD : {{ $finance->name }}</h5>
                        </div>
                        <div class="modal-body">
                            @if($penugasan->ttd_proses_finance=='')
                            <h6 class="text-center">kosong</h6>
                            @else
                            <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$penugasan->ttd_proses_finance.'.png') }}" style="width: 50%;margin-left: 25%;margin-right: 25%" alt="">
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
        @if($penugasan->status_penugasan=='0')
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
                        <button type="submit" id="save_btn" class="btn btn-sm btn-primary btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Update</button>
                    </div>

                </div>
            </div>
        </div>
        @else
        <a id="btn_klik" href="{{url('penugasan/dashboard')}}" class="btn-sm btn btn-primary btn-rounded" style="width: 30%;margin-left: 35%;margin-right: 35%">
            &nbsp; Kembali
        </a>
        @endif
    </form>
</div>
</div>

@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
    $(document).ready(function() {
        wilayah = $('#wilayah').val();
        if (wilayah == 'Wilayah Kantor') {
            $('#form_alamat_dikunjungi').show();
            $('#form_alamat_dikunjungi1').hide();
        } else {
            $('#form_alamat_dikunjungi1').show();
            $('#form_alamat_dikunjungi').hide();
        }
        $('body').on("change", "#wilayah_penugasan", function() {
            var id = $(this).val();
            if (id == 'Wilayah Kantor') {
                $('#form_alamat_dikunjungi').show();
                $('#form_alamat_dikunjungi1').hide();
            } else {
                $('#form_alamat_dikunjungi1').show();
                $('#form_alamat_dikunjungi').hide();
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
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
            // alert("Please provide signature first.");
            Swal.fire({
                title: 'Info',
                text: 'Silahkan Masukkan TTD',
                icon: 'info',
                timer: 1500
            })
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
<script>
    $(document).ready(function() {
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
        $('#alamat_dikunjungi').on('change', function() {
            var asal_kerja = $('#asal_kerja').val();
            var alamat_kunjungan = $(this).val();
            // $('#diminta_oleh').show();
            console.log(alamat_kunjungan);
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
    });
    $(document).ready(function() {
        var mulai = '{{$penugasan->tanggal_kunjungan}}';
        var selesai = '{{$penugasan->selesai_kunjungan}}';
        var start = moment(mulai);
        var end = moment(selesai);
        var now = moment();
        $('input[id="tanggal_kunjungan"]').daterangepicker({
            drops: 'top',
            minDate: now,
            startDate: start,
            endDate: end,
            autoApply: false,
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });
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