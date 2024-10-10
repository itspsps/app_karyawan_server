@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> TAMBAH KARYAWAN</h4>

    <div class="row">
        <div class="col-md-12">

            <div class="card mb-4">
                <h4 class="card-header">Tambah Karyawan</h4>
                <!-- Account -->
                <form method="post" action="{{ url('/karyawan/tambah-karyawan-proses/'.$holding) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#nav_profile" aria-controls="nav_profile" aria-selected="true">
                                        <i class="tf-icons mdi mdi-account-outline me-1"></i>
                                        PROFILE
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_alamat" aria-controls="nav_alamat" aria-selected="false">
                                        <i class="tf-icons mdi mdi-home-city me-1"></i>
                                        ALAMAT
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_info_hr" aria-controls="nav_info_hr" aria-selected="false">
                                        <i class="tf-icons mdi mdi-account-cog-outline me-1"></i>
                                        INFO HR
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_jabatan" aria-controls="nav_jabatan" aria-selected="false">
                                        <i class="tf-icons mdi mdi-medal-outline me-1"></i>
                                        JABATAN
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_bank" aria-controls="nav_bank" aria-selected="false">
                                        <i class="tf-icons mdi mdi-bank-circle me-1"></i>
                                        BANK
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_pajak" aria-controls="nav_pajak" aria-selected="false">
                                        <i class="tf-icons mdi mdi-percent-outline me-1"></i>
                                        PAJAK
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_bpjs" aria-controls="nav_bpjs" aria-selected="false">
                                        <i class="tf-icons mdi mdi-card-account-details-outline me-1"></i>
                                        BPJS
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="nav_profile" role="tabpanel">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                                        <div class="button-wrapper">
                                            <label for="foto_karyawan" class="btn btn-primary me-2 mb-3" tabindex="0">
                                                <span class="d-none d-sm-block">Upload Foto</span>
                                                <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                                <input style="font-size: small;" type="file" name="foto_karyawan" id="foto_karyawan" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                            </label>

                                            <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nik') is-invalid @enderror" type="number" id="nik" name="nik" value="{{old('nik')}}" autofocus />
                                                <label for="nik">NIK</label>
                                            </div>
                                            @error('nik')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                                <label for="name">Nama Lengkap</label>
                                            </div>
                                            @error('name')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                                <label for="email">E-mail</label>
                                            </div>
                                            @error('email')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon') }}">
                                                <label for="telepon">Telepon</label>
                                            </div>
                                            @error('telepon')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <h6>Apakah Nomor Telepon Terhubung WhatsApps ?</h6>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check @error('status_nomor') is-invalid @enderror" name="status_nomor" value="" checked>
                                                <input type="radio" class="btn-check @error('status_nomor') is-invalid @enderror" name="status_nomor" id="btn_status_no_ya" value="ya" @if(old('status_nomor')=="ya" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-success waves-effect" for="btn_status_no_ya">Ya</label>
                                                <input type="radio" class="btn-check @error('status_nomor') is-invalid @enderror" name="status_nomor" id="btn_status_no_tidak" value="tidak" @if(old('status_nomor')=="tidak" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-primary waves-effect" for="btn_status_no_tidak">Tidak</label>
                                                @error('status_nomor')
                                                <p class="alert alert-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div id="content_nomor_wa" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nomor_wa') is-invalid @enderror" type="number" name="nomor_wa" id="nomor_wa" value="{{ old('nomor_wa')}}" />
                                                <label for="nomor_wa">Nomor WA</label>
                                            </div>
                                            @error('nomor_wa')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                                                <label for="tempat_lahir">Tempat Lahir</label>
                                            </div>
                                            @error('tempat_lahir')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="date" id="tgl_lahir" value="{{old('tgl_lahir')}}" name="tgl_lahir" placeholder="Tanggal Lahir" />
                                                <label for="tgl_lahir">Tanggal Lahir</label>
                                            </div>
                                            @error('tgl_lahir')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="text" id="golongan_darah" value="{{old('golongan_darah')}}" name="golongan_darah" placeholder="Golongan Darah" />
                                                <label for="golongan_darah">Golongan Darah</label>
                                            </div>
                                            @error('golongan_darah')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}">

                                                <label for="username">Username</label>
                                            </div>
                                            @error('username')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div> -->

                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control" id="agama" name="agama" value="{{old('agama') }}">
                                                    <option @if(old('agama')=='' ) selected @else @endif disabled value=""> ~Pilih Agama~ </option>
                                                    <option @if(old('agama')=='ISLAM' ) selected @else @endif value="ISLAM">ISLAM</option>
                                                    <option @if(old('agama')=='KRISTEN PROTESTAN' ) selected @else @endif value="KRISTEN PROTESTAN">KRISTEN PROTESTAN</option>
                                                    <option @if(old('agama')=='KRISTEN KATOLIK' ) selected @else @endif value="KRISTEN KATOLIK">KRISTEN KATOLIK</option>
                                                    <option @if(old('agama')=='HINDU' ) selected @else @endif value="HINDU">HINDU</option>
                                                    <option @if(old('agama')=='BUDDHA' ) selected @else @endif value="BUDDHA">BUDDHA</option>
                                                    <option @if(old('agama')=='KHONGHUCU' ) selected @else @endif value="KHONGHUCU">KHONGHUCU</option>
                                                </select>
                                                <label for="agama">Agama</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $gender = array(
                                                    [
                                                        "gender" => "Laki-Laki"
                                                    ],
                                                    [
                                                        "gender" => "Perempuan"
                                                    ]
                                                );
                                                ?>
                                                <select style="font-size: small;" name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                                    <option selected disabled value="">Pilih Jenis Kelamin</option>
                                                    @foreach ($gender as $g)
                                                    @if(old('gender') == $g["gender"])
                                                    <option value="{{ $g["gender"] }}" selected>{{ $g["gender"] }}</option>
                                                    @else
                                                    <option value="{{ $g["gender"] }}">{{ $g["gender"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="gender">Kelamin</label>
                                            </div>
                                            @error('gender')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $sNikah = array(
                                                    [
                                                        "status" => "Lajang"
                                                    ],
                                                    [
                                                        "status" => "Menikah"
                                                    ]
                                                );
                                                ?>
                                                <select style="font-size: small;" name="status_nikah" id="status_nikah" class="form-control selectpicker" data-live-search="true">
                                                    <option selected disabled value="">Pilih Status Nikah</option>
                                                    @foreach ($sNikah as $s)
                                                    @if(old('status_nikah') == $s["status"])
                                                    <option value="{{ $s["status"] }}" selected>{{ $s["status"] }}</option>
                                                    @else
                                                    <option value="{{ $s["status"] }}">{{ $s["status"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="status_nikah">Status Nikah</label>
                                            </div>
                                            @error('status_nikah')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">

                                        <div class="col-md-12">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <img src="{{asset('admin/assets/img/avatars/cv.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />

                                                <div class="button-wrapper">
                                                    <label for="file_cv" class="btn btn-danger me-2 mb-3" tabindex="0">
                                                        <span class="d-none d-sm-block">Upload File CV</span>
                                                        <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                                        <input style="font-size: small;" type="file" name="file_cv" id="file_cv" class="account-file-input @error('file_cv') is-invalid @enderror" hidden accept=".doc, .docx,.pdf" />
                                                    </label>
                                                    <button type="button" id="btn_modal_lihat" data-bs-toggle="modal" data-bs-target="#modal_cv" class="btn_modal_lihat btn btn-info me-2 mb-3">Lihat</button>

                                                    <div class="text-muted small">Allowed PDF, DOC or DOCX. Max size of 5 MB</div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_cv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Lampiran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <iframe id="lihat_file_cv" src="" style=" height: 500px; width: 100%;"></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_info_hr" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <?php $kategori = array(
                                                [
                                                    "kategori" => "Karyawan Bulanan"
                                                ],
                                                [
                                                    "kategori" => "Karyawan Harian"
                                                ]
                                            );
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="kategori" id="kategori" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($kategori as $a)
                                                    @if(old('kategori') == $a["kategori"])
                                                    <option value="{{ $a["kategori"] }}" selected>{{ $a["kategori"] }}</option>
                                                    @else
                                                    <option value="{{ $a["kategori"] }}">{{ $a["kategori"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="kategori">Kategori Karyawan</label>
                                            </div>
                                            @error('kategori')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="date" id="tgl_join" value="{{old('tgl_join')}}" name="tgl_join" placeholder="Tanggal Join Perusahan" />
                                                <label for="tgl_join">Tanggal Join Perusahaan</label>
                                            </div>
                                            @error('tgl_join')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_kontrak" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control" readonly value="@if($holding =='sp')CV. SUMBER PANGAN @elseif($holding =='sps') PT. SURYA PANGAN SEMESTA @elseif($holding =='sip') CV. SURYA INTI PANGAN  @endif">
                                                <input style="font-size: small;" type="hidden" class="form-control" id="kontrak_kerja" name="kontrak_kerja" value="@if($holding =='sp') SP @elseif($holding =='sps') SPS @elseif($holding =='sip') SIP @endif">
                                                <label for="kontrak_kerja">Kontrak Kerja</label>
                                            </div>

                                        </div>
                                        <div id="form_lama_kontrak" class="col-md-6">
                                            <?php $lama_kontrak_kerja = array(
                                                [
                                                    "lama_kontrak_kerja" => "3 bulan"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "6 bulan"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "1 tahun"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "2 bahun"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "tetap"
                                                ]
                                            );
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="lama_kontrak_kerja" id="lama_kontrak_kerja" class="form-control selectpicker @error('lama_kontrak_kerja') is-invalid @enderror" data-live-search="true">
                                                    <option value="">Pilih Kontrak</option>
                                                    @foreach ($lama_kontrak_kerja as $a)
                                                    @if(old('lama_kontrak_kerja') == $a["lama_kontrak_kerja"])
                                                    <option value="{{ $a["lama_kontrak_kerja"] }}" selected>{{ $a["lama_kontrak_kerja"] }}</option>
                                                    @else
                                                    <option value="{{ $a["lama_kontrak_kerja"] }}">{{ $a["lama_kontrak_kerja"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="lama_kontrak_kerja">Lama Kontrak</label>
                                            </div>
                                            @error('lama_kontrak_kerja')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_tgl_mulai_kontrak" class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="date" class="form-control @error('tgl_mulai_kontrak') is-invalid @enderror" id="tgl_mulai_kontrak" name="tgl_mulai_kontrak" value="{{old('tgl_mulai_kontrak') }}" />
                                                <label for="tgl_mulai_kontrak">Tanggal Mulai Kontrak</label>
                                            </div>
                                            @error('tgl_mulai_kontrak')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_tgl_selesai_kontrak" class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="date" class="form-control @error('tgl_selesai_kontrak') is-invalid @enderror" id="tgl_selesai_kontrak" name="tgl_selesai_kontrak" value="{{old('tgl_selesai_kontrak') }}" />
                                                <label for=" tgl_selesai_kontrak">Tanggal Selesai Kontrak</label>
                                            </div>
                                            @error('tgl_selesai_kontrak')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_kuota_cuti" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="kuota_cuti" name="kuota_cuti" class="form-control @error('kuota_cuti') is-invalid @enderror" placeholder="Masukkan Cuti Tahunan" value="{{ old('kuota_cuti') }}" />
                                                <label for="kuota_cuti">Kuota Cuti Tahunan</label>
                                            </div>
                                            @error('kuota_cuti')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_alamat" role="tabpanel">
                                    <div class="row gy-4">
                                        <div class="col-md-3">
                                            <span class="badge bg-label-info">Alamat Berdasarkan KTP</span>
                                        </div>
                                        <hr class="m-0">
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('provinsi') is-invalid @enderror" id="id_provinsi" name="provinsi" style="font-size: small;">
                                                    <option value=""> Pilih Provinsi </option>
                                                    @foreach($data_provinsi as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('provinsi')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_provinsi">Provinsi</label>
                                            </div>
                                            @error('provinsi')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $kab = App\Models\Cities::Where('province_code', old('provinsi'))->orderBy('name', 'ASC')->get();
                                            $kec = App\Models\District::Where('city_code', old('kabupaten'))->orderBy('name', 'ASC')->get();
                                            $desa = App\Models\Village::Where('district_code', old('kecamatan'))->orderBy('name', 'ASC')->get();
                                            // echo $kab;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('kabupaten') is-invalid @enderror" id="id_kabupaten" name="kabupaten" style="font-size: small;">
                                                    <option value=""> Pilih Kabupaten / Kota</option>
                                                    @foreach($kab as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kabupaten')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kabupaten">Kabupaten</label>
                                            </div>
                                            @error('kabupaten')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('kecamatan') is-invalid @enderror" id="id_kecamatan" name="kecamatan" style="font-size: small;">
                                                    <option value=""> Pilih kecamatan</option>
                                                    @foreach($kec as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kecamatan')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kecamatan">kecamatan</label>
                                            </div>
                                            @error('kecamatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('desa') is-invalid @enderror" id="id_desa" name="desa" style="font-size: small;">
                                                    <option value=""> Pilih Desa</option>
                                                    @foreach($desa as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('desa')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_desa">Desa</label>
                                            </div>
                                            @error('desa')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rt" name="rt" class="form-control @error('rt') is-invalid @enderror" placeholder="Masukkan RT" value="{{ old('rt') }}" />
                                                <label for="rt">RT</label>
                                            </div>
                                            @error('rt')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rw" name="rw" class="form-control @error('rw') is-invalid @enderror" placeholder="Masukkan RW" value="{{ old('rw') }}" />
                                                <label for="rw">RW</label>
                                            </div>
                                            @error('rw')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" value="{{ old('alamat') }}" />
                                                <label for="alamat">Keterangan Alamat(Jalan / Dusun)</label>
                                            </div>
                                            @error('alamat')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <h6>Apakah Alamat KTP Sama Dengan Alamat Domisili ?</h6>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror" name="pilihan_alamat_domisili" value="" checked>
                                                <input type="radio" class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror" name="pilihan_alamat_domisili" id="btnradio_ya" value="ya" @if(old('pilihan_alamat_domisili')=="ya" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-success waves-effect" for="btnradio_ya">Ya</label>
                                                <input type="radio" class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror" name="pilihan_alamat_domisili" id="btnradio_tidak" value="tidak" @if(old('pilihan_alamat_domisili')=="tidak" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-primary waves-effect" for="btnradio_tidak">Tidak</label>
                                                @error('pilihan_alamat_domisili')
                                                <p class="alert alert-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div id="content_alamat_domisili" class="row mt-2 gy-4">
                                        <div class="col-md-3">
                                            <span class="badge bg-label-danger">Alamat Berdasarkan Domisili Sekarang</span>
                                        </div>
                                        <hr class="m-0">
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('provinsi_domisili') is-invalid @enderror" id="id_provinsi_domisili" name="provinsi_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Provinsi </option>
                                                    @foreach($data_provinsi as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('provinsi_domisili')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_provinsi_domisili">Provinsi</label>
                                            </div>
                                            @error('provinsi_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $kab_domisili = App\Models\Cities::Where('province_code', old('provinsi_domisili'))->orderBy('name', 'ASC')->get();
                                            $kec_domisili = App\Models\District::Where('city_code', old('kabupaten_domisili'))->orderBy('name', 'ASC')->get();
                                            $desa_domisili = App\Models\Village::Where('district_code', old('kecamatan_domisili'))->orderBy('name', 'ASC')->get();
                                            // echo $kab;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('kabupaten_domisili') is-invalid @enderror" id="id_kabupaten_domisili" name="kabupaten_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Kabupaten / Kota</option>
                                                    @foreach($kab_domisili as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kabupaten_domisili')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kabupaten_domisili">Kabupaten</label>
                                            </div>
                                            @error('kabupaten_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('kecamatan_domisili') is-invalid @enderror" id="id_kecamatan_domisili" name="kecamatan_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Kecamatan</option>
                                                    @foreach($kec_domisili as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kecamatan_domisili')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kecamatan_domisili">kecamatan_domisili</label>
                                            </div>
                                            @error('kecamatan_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('desa_domisili') is-invalid @enderror" id="id_desa_domisili" name="desa_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Desa</option>
                                                    @foreach($desa_domisili as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('desa_domisili')) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_desa_domisili">Desa</label>
                                            </div>
                                            @error('desa_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rt_domisili" name="rt_domisili" class="form-control @error('rt_domisili') is-invalid @enderror" placeholder="Masukkan RT" value="{{ old('rt_domisili') }}" />
                                                <label for="rt_domisili">RT</label>
                                            </div>
                                            @error('rt_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rw_domisili" name="rw_domisili" class="form-control @error('rw_domisili') is-invalid @enderror" placeholder="Masukkan RW" value="{{ old('rw_domisili') }}" />
                                                <label for="rw_domisili">RW</label>
                                            </div>
                                            @error('rw_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" id="alamat_domisili" name="alamat_domisili" class="form-control @error('alamat_domisili') is-invalid @enderror" placeholder="Masukkan Alamat" value="{{ old('alamat_domisili') }}" />
                                                <label for="alamat_domisili">Keterangan Alamat(Jalan / Dusun)</label>
                                            </div>
                                            @error('alamat_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>

                                </div>
                                <div class="tab-pane fade" id="nav_jabatan" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div id="form_site" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('site_job') is-invalid @enderror" id="site_job" name="site_job">
                                                    <option selected disabled value=""> Pilih Site Job</option>
                                                    @foreach ($data_lokasi as $a)
                                                    @if(old('site_job') == $a["lokasi_kantor"])
                                                    <option value="{{ $a["lokasi_kantor"] }}" selected>{{ $a["lokasi_kantor"] }}</option>
                                                    @else
                                                    <option value="{{ $a["lokasi_kantor"] }}">{{ $a["lokasi_kantor"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="site_job">Site yang Dipegang</label>
                                            </div>
                                            <p class="text-info">Untuk Kebutuhan Approval</p>
                                            @error('site_job')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('penempatan_kerja') is-invalid @enderror" id="penempatan_kerja" name="penempatan_kerja">
                                                    <option selected disabled value=""> Pilih Lokasi Penempatan</option>
                                                    @foreach ($data_lokasi as $a)
                                                    @if(old('penempatan_kerja') == $a["lokasi_kantor"])
                                                    <option value="{{ $a["lokasi_kantor"] }}" selected>{{ $a["lokasi_kantor"] }}</option>
                                                    @else
                                                    <option value="{{ $a["lokasi_kantor"] }}">{{ $a["lokasi_kantor"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="penempatan_kerja">Penempatan Kerja</label>
                                            </div>
                                            <p class="text-info">Untuk Kebutuhan Absensi</p>
                                            @error('penempatan_kerja')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <!-- <div id="form_level" class="col-md-6">
                                            <?php $is_admin = array(
                                                [
                                                    "is_admin" => "admin"
                                                ],
                                                [
                                                    "is_admin" => "user"
                                                ]
                                            );
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="is_admin" id="is_admin" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($is_admin as $a)
                                                    @if(old('is_admin') == $a["is_admin"])
                                                    <option value="{{ $a["is_admin"] }}" selected>{{ $a["is_admin"] }}</option>
                                                    @else
                                                    <option value="{{ $a["is_admin"] }}">{{ $a["is_admin"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="is_admin">Level User</label>
                                            </div>
                                            @error('is_admin')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div> -->

                                        <div id="row_kategori_jabatan" style="margin-top: -1%; margin-left: 2%;" class="col mb-6">
                                            <label class="form-check-label" for="kategori_jabatan">Pilih Kategori Jabatan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4">
                                                    <div class="col-lg-3 form-check">
                                                        <input style="font-size: small;" type="radio" id="kategori_jabatan_sp" name="kategori_jabatan" class="form-check-input" value="sp" @if(old('kategori_jabatan')=='sp' ) checked @else @endif>
                                                        <label class="form-check-label" for="kategori_jabatan_sp">CV. SUMBER PANGAN</label>
                                                    </div>
                                                    <div class="col-lg-6 form-check">
                                                        <input style="font-size: small;" type="radio" id="kategori_jabatan_sps" name="kategori_jabatan" class="form-check-input" value="sps" @if(old('kategori_jabatan')=='sps' ) checked @else @endif>
                                                        <label class="form-check-label" for="kategori_jabatan_sps">PT. SURYA PANGAN SEMESTA</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input style="font-size: small;" type="hidden" id="kategori_jabatan" value="{{$holding}}">
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div id="form_departemen" class="col-md-3">

                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="departemen_id" id="id_departemen" class="form-control @error('departemen_id') is-invalid @enderror">
                                                    <option value=""> Pilih Departemen</option>
                                                    @if($holding == 'sp')
                                                    @php
                                                    $ok = 'CV. SUMBER PANGAN';
                                                    @endphp
                                                    @elseif ($holding == 'sps')
                                                    @php
                                                    $ok = 'PT. SURYA PANGAN SEMESTA';
                                                    @endphp
                                                    @else
                                                    @php
                                                    $ok = 'CV. SURYA INTI PANGAN';
                                                    @endphp
                                                    @endif
                                                    <optgroup label='Daftar Departemen {{$ok}}'>
                                                        @foreach ($data_departemen as $dj)
                                                        <option value="{{$dj->id}}" {{($dj->id == old('departemen_id')) ? 'selected' : ''}}>{{$dj->nama_departemen}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_departemen">Departemen</label>
                                            </div>
                                            @error('departemen_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_divisi" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan') == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = old('kategori_jabatan');
                                            }
                                            // print_r($kategori_jabatan);
                                            // exit;
                                            $data_divisi = App\Models\Divisi::Where('dept_id', old('departemen_id'))->where('holding', $kategori_jabatan)->orderBy('nama_divisi', 'ASC')->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="divisi_id" id="id_divisi" class="form-control @error('divisi_id') is-invalid @enderror">
                                                    <option value=""> Pilih Divisi</option>
                                                    @foreach ($data_divisi as $data)
                                                    <option value="{{$data->id}}" {{($data->id == old('divisi_id')) ? 'selected' : ''}}>{{$data->nama_divisi}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_divisi">Divisi</label>
                                            </div>
                                            @error('divisi_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_bagian" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan') == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = old('kategori_jabatan');
                                            }
                                            // print_r($kategori_jabatan);
                                            // exit;
                                            $data_bagian = App\Models\Bagian::Where('divisi_id', old('divisi_id'))->where('holding', $kategori_jabatan)->orderBy('nama_bagian', 'ASC')->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="bagian_id" id="id_bagian" class="form-control @error('bagian_id') is-invalid @enderror">
                                                    <option value=""> Pilih Bagian</option>
                                                    @foreach ($data_bagian as $data)
                                                    <option value="{{$data->id}}" {{($data->id == old('bagian_id')) ? 'selected' : ''}}>{{$data->nama_bagian}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_bagian">Bagian</label>
                                            </div>
                                            @error('bagian_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_jabatan" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan') == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = old('kategori_jabatan');
                                            }
                                            // exit;
                                            $data_jabatan = App\Models\Jabatan::where('bagian_id', old('bagian_id'))->where('divisi_id', old('divisi_id'))->where('holding', $kategori_jabatan)->orderBy('nama_jabatan', 'ASC')->get();
                                            // print_r(old('bagian_id'));
                                            // print_r(old('divisi_id'));
                                            // print_r(old('jabatan_id'));
                                            ?>

                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="jabatan_id" id="id_jabatan" class="form-control @error('jabatan_id') is-invalid @enderror">
                                                    <option value=""> Pilih Jabatan</option>
                                                    @foreach ($data_jabatan as $data)
                                                    <option value="{{$data->id}}" {{($data->id == old('jabatan_id')) ? 'selected' : ''}}>{{$data->nama_jabatan}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_jabatan">Jabatan</label>
                                            </div>
                                            @error('jabatan_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_jabatan_more" class="row g-2 mt-2">
                                            <div class="col mb-2">
                                                <div class="accordion mt-3" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                                                Jika Karyawan Memiliki Lebih Dari 1 Jabatan
                                                            </button>
                                                        </h2>

                                                        <div id="accordionOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen1_id" id="id_departemen1" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                @if($holding == 'sp')
                                                                                @php
                                                                                $ok = 'CV. SUMBER PANGAN';
                                                                                @endphp
                                                                                @elseif ($holding == 'sps')
                                                                                @php
                                                                                $ok = 'PT. SURYA PANGAN SEMESTA';
                                                                                @endphp
                                                                                @else
                                                                                @php
                                                                                $ok = 'CV. SURYA INTI PANGAN';
                                                                                @endphp
                                                                                @endif
                                                                                <optgroup label='Daftar Departemen {{$ok}}'>
                                                                                    @foreach ($data_departemen as $dj)
                                                                                    <option value="{{$dj->id}}" {{($dj->id == old('departemen_id')) ? 'selected' : ''}}>{{$dj->nama_departemen}}</option>
                                                                                    @endforeach
                                                                                </optgroup>

                                                                            </select>
                                                                            <label for=" id_departemen1">Departemen 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi1_id" id="id_divisi1" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>


                                                                            </select>
                                                                            <label for=" id_divisi1">Divisi 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian1_id" id="id_bagian1" class="form-control @error('bagian1_id') is-invalid @enderror">
                                                                                <option value=""> Pilih Bagian</option>


                                                                            </select>
                                                                            <label for="id_bagian1">Bagian 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan1_id" id="id_jabatan1" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>


                                                                            </select>
                                                                            <label for=" id_jabatan1">Jabatan 2</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen2_id" id="id_departemen2" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                @if($holding == 'sp')
                                                                                @php
                                                                                $ok = 'CV. SUMBER PANGAN';
                                                                                @endphp
                                                                                @elseif ($holding == 'sps')
                                                                                @php
                                                                                $ok = 'PT. SURYA PANGAN SEMESTA';
                                                                                @endphp
                                                                                @else
                                                                                @php
                                                                                $ok = 'CV. SURYA INTI PANGAN';
                                                                                @endphp
                                                                                @endif
                                                                                <optgroup label='Daftar Departemen {{$ok}}'>
                                                                                    @foreach ($data_departemen as $dj)
                                                                                    <option value="{{$dj->id}}" {{($dj->id == old('departemen_id')) ? 'selected' : ''}}>{{$dj->nama_departemen}}</option>
                                                                                    @endforeach
                                                                                </optgroup>

                                                                            </select>
                                                                            <label for=" id_departemen2">Departemen 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi2_id" id="id_divisi2" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>


                                                                            </select>
                                                                            <label for=" id_divisi2">Divisi 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian2_id" id="id_bagian2" class="form-control">
                                                                                <option value=""> Pilih Bagian</option>


                                                                            </select>
                                                                            <label for="id_bagian2">Bagian 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan2_id" id="id_jabatan2" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>


                                                                            </select>
                                                                            <label for=" id_jabatan2">Jabatan 3</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen3_id" id="id_departemen3" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                @if($holding == 'sp')
                                                                                @php
                                                                                $ok = 'CV. SUMBER PANGAN';
                                                                                @endphp
                                                                                @elseif ($holding == 'sps')
                                                                                @php
                                                                                $ok = 'PT. SURYA PANGAN SEMESTA';
                                                                                @endphp
                                                                                @else
                                                                                @php
                                                                                $ok = 'CV. SURYA INTI PANGAN';
                                                                                @endphp
                                                                                @endif
                                                                                <optgroup label='Daftar Departemen {{$ok}}'>
                                                                                    @foreach ($data_departemen as $dj)
                                                                                    <option value="{{$dj->id}}" {{($dj->id == old('departemen_id')) ? 'selected' : ''}}>{{$dj->nama_departemen}}</option>
                                                                                    @endforeach
                                                                                </optgroup>

                                                                            </select>
                                                                            <label for=" id_departemen3">Departemen 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi3_id" id="id_divisi3" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>


                                                                            </select>
                                                                            <label for=" id_divisi3">Divisi 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian3_id" id="id_bagian3" class="form-control">
                                                                                <option value=""> Pilih Bagian</option>


                                                                            </select>
                                                                            <label for="id_bagian3">Bagian 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan3_id" id="id_jabatan3" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>


                                                                            </select>
                                                                            <label for=" id_jabatan3">Jabatan 4</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen4_id" id="id_departemen4" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                @if($holding == 'sp')
                                                                                @php
                                                                                $ok = 'CV. SUMBER PANGAN';
                                                                                @endphp
                                                                                @elseif ($holding == 'sps')
                                                                                @php
                                                                                $ok = 'PT. SURYA PANGAN SEMESTA';
                                                                                @endphp
                                                                                @else
                                                                                @php
                                                                                $ok = 'CV. SURYA INTI PANGAN';
                                                                                @endphp
                                                                                @endif
                                                                                <optgroup label='Daftar Departemen {{$ok}}'>
                                                                                    @foreach ($data_departemen as $dj)
                                                                                    <option value="{{$dj->id}}" {{($dj->id == old('departemen_id')) ? 'selected' : ''}}>{{$dj->nama_departemen}}</option>
                                                                                    @endforeach
                                                                                </optgroup>

                                                                            </select>
                                                                            <label for=" id_departemen4">Departemen 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi4_id" id="id_divisi4" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>


                                                                            </select>
                                                                            <label for=" id_divisi4">Divisi 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian4_id" id="id_bagian4" class="form-control">
                                                                                <option value=""> Pilih Bagian</option>

                                                                            </select>
                                                                            <label for="id_bagian4">Bagian 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan4_id" id="id_jabatan4" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>


                                                                            </select>
                                                                            <label for=" id_jabatan4">Jabatan 5</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="nav_bank" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $bank = array(
                                                    [
                                                        "kode_bank" => "BBRI",
                                                        "bank" => "BANK RAKYAT INDONESIA (BRI)"
                                                    ],
                                                    [
                                                        "kode_bank" => "BBCA",
                                                        "bank" => "BANK CENTRAL ASIA (BCA)"
                                                    ],
                                                    [
                                                        "kode_bank" => "BOCBC",
                                                        "bank" => "BANK OCBC"
                                                    ],
                                                    [
                                                        "kode_bank" => "BMANDIRI",
                                                        "bank" => "BANK MANDIRI"
                                                    ]
                                                );
                                                ?>
                                                <select style="font-size: small;" name="nama_bank" id="nama_bank" onchange="bankCheck(this);" class="form-control  @error('nama_bank') is-invalid @enderror">
                                                    <option value="">Pilih Bank</option>
                                                    @foreach ($bank as $bank)
                                                    @if(old('nama_bank') == $bank['kode_bank']) <option value="{{ $bank['kode_bank'] }}" selected>{{ $bank['bank'] }}</option>
                                                    @else
                                                    <option value="{{ $bank['kode_bank'] }}">{{ $bank['bank'] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="nama_bank">Nama Bank</label>
                                            </div>
                                            @error('nama_bank')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control  @error('nama_pemilik_rekening') is-invalid @enderror" id="nama_pemilik_rekening" name="nama_pemilik_rekening" value="{{old('nama_pemilik_rekening') }}" placeholder="Nama Pemilik Rekening" />
                                                <label for="nama_pemilik_rekening">Nama Pemilik Rekening</label>
                                            </div>
                                            @error('nama_pemilik_rekening')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="row mt-2 gy-4">
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input style="font-size: small;" type="number" class="form-control  @error('nomor_rekening') is-invalid @enderror" id="nomor_rekening" name="nomor_rekening" value="{{old('nomor_rekening') }}" placeholder="Nomor Rekening" />
                                                    <label for="nomor_rekening">Nomor Rekening</label>
                                                </div>
                                                @error('nomor_rekening')
                                                <p class="alert alert-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_pajak" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('ptkp') is-invalid @enderror" id="ptkp" name="ptkp">
                                                    <option @if(old('ptkp')=="" ) selected @else @endif value="">Pilih PKTP</option>
                                                    <option @if(old('ptkp')=="TK/0" ) selected @else @endif value="TK/0">TK/0</option>
                                                    <option @if(old('ptkp')=="TK/1" ) selected @else @endif value="TK/1">TK/1</option>
                                                    <option @if(old('ptkp')=="TK/2" ) selected @else @endif value="TK/2">TK/2</option>
                                                    <option @if(old('ptkp')=="TK/3" ) selected @else @endif value="TK/3">TK/3</option>
                                                    <option @if(old('ptkp')=="K/0" ) selected @else @endif value="K/0">K/0</option>
                                                    <option @if(old('ptkp')=="K/1" ) selected @else @endif value="K/1">K/1</option>
                                                    <option @if(old('ptkp')=="K/2" ) selected @else @endif value="K/2">K/2</option>
                                                    <option @if(old('ptkp')=="K/I/0" ) selected @else @endif value="K/I/0">K/I/0</option>
                                                    <option @if(old('ptkp')=="K/I/1" ) selected @else @endif value="K/I/1">K/I/1</option>
                                                    <option @if(old('ptkp')=="K/I/2" ) selected @else @endif value="K/I/2">K/I/2</option>
                                                    <option @if(old('ptkp')=="K/I/3" ) selected @else @endif value="K/I/3">K/I/3</option>
                                                    <option @if(old('ptkp')=="K/3" ) selected @else @endif value="K/3">K/3</option>
                                                </select>
                                                <label for="ptkp">PKPT</label>
                                            </div>
                                            @error('ptkp')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="status_npwp">Punya NPWP</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="status_npwp_ya" name="status_npwp" class="form-check-input" value="on" @if(old('status_npwp')=='on' ) checked @else @endif>
                                                        <label class="form-check-label" for="status_npwp_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="status_npwp_tidak" name="status_npwp" class="form-check-input" value="off" @if(old('status_npwp')=='off' ) checked @else @endif>
                                                        <label class="form-check-label" for="status_npwp_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_npwp" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nama_pemilik_npwp') is-invalid @enderror" type="text" placeholder="Nama Pemilik NPWP" id="nama_pemilik_npwp" name="nama_pemilik_npwp" value="{{old('nama_pemilik_npwp')}}" />
                                                <label for="nama_pemilik_npwp">Nama Pemilik NPWP</label>
                                            </div>
                                            @error('nama_pemilik_npwp')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('npwp') is-invalid @enderror" type="number" id="npwp" name="npwp" value="{{old('npwp')}}" />
                                                <label for="npwp">NPWP</label>
                                            </div>
                                            @error('npwp')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_bpjs" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_ketenagakerjaan_ya" name="bpjs_ketenagakerjaan" class="form-check-input" value="on" @if(old('bpjs_ketenagakerjaan')=='on' ) checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_ketenagakerjaan_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_ketenagakerjaan_tidak" name="bpjs_ketenagakerjaan" class="form-check-input" value="off" @if(old('bpjs_ketenagakerjaan')=='off' ) checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_ketenagakerjaan_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_bpjs_ketenagakerjaan" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nama_pemilik_bpjs_ketenagakerjaan') is-invalid @enderror" type="text" placeholder="Nama Pemilik BPJS Ketenagakerjaan" id="nama_pemilik_bpjs_ketenagakerjaan" name="nama_pemilik_bpjs_ketenagakerjaan" value="{{old('nama_pemilik_bpjs_ketenagakerjaan')}}" autofocus />
                                                <label for="nama_pemilik_bpjs_ketenagakerjaan">Nama Pemilik BPJS Ketenagakerjaan</label>
                                            </div>
                                            @error('nama_pemilik_bpjs_ketenagakerjaan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('no_bpjs_ketenagakerjaan') is-invalid @enderror" type="number" placeholder="No. BPJS Ketenagakerjaan" id="no_bpjs_ketenagakerjaan" name="no_bpjs_ketenagakerjaan" value="{{old('no_bpjs_ketenagakerjaan')}}" autofocus />
                                                <label for="no_bpjs_ketenagakerjaan">No. BPJS Ketenagakerjaan</label>
                                            </div>
                                            @error('no_bpjs_ketenagakerjaan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_pensiun_ya">BPJS Pensiun</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_pensiun_ya" name="bpjs_pensiun" class="form-check-input" value="on" @if(old('bpjs_pensiun')=='on' ) checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_pensiun_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_pensiun_tidak" name="bpjs_pensiun" class="form-check-input" value="off" @if(old('bpjs_pensiun')=='off' ) checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_pensiun_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_kesehatan">BPJS Kesehatan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_kesehatan_ya" name="bpjs_kesehatan" class="form-check-input" value="on" @if(old('bpjs_kesehatan')=='on' ) checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_kesehatan_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_kesehatan_tidak" name="bpjs_kesehatan" class="form-check-input" value="off" @if(old('bpjs_kesehatan')=='off' ) checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_kesehatan_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_bpjs_kesehatan" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nama_pemilik_bpjs_kesehatan') is-invalid @enderror" type="text" id="nama_pemilik_bpjs_kesehatan" name="nama_pemilik_bpjs_kesehatan" value="{{old('nama_pemilik_bpjs_kesehatan')}}" autofocus />
                                                <label for="nama_pemilik_bpjs_kesehatan">Nama Pemilik BPJS Kesehatan</label>
                                            </div>
                                            @error('nama_pemilik_bpjs_kesehatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('no_bpjs_kesehatan') is-invalid @enderror" type="number" id="no_bpjs_kesehatan" name="no_bpjs_kesehatan" value="{{old('no_bpjs_kesehatan')}}" autofocus />
                                                <label for="no_bpjs_kesehatan">No. BPJS Kesehatan</label>
                                            </div>
                                            @error('no_bpjs_kesehatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="row_kelas_bpjs" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('kelas_bpjs') is-invalid @enderror" id="kelas_bpjs" name="kelas_bpjs" value="{{old('kelas_bpjs')}}">
                                                    <option value="">Pilih Kelas BPJS</option>
                                                    <option value="Kelas 1">Kelas 1</option>
                                                    <option value="Kelas 2">Kelas 2</option>
                                                    <option value="Kelas 3">Kelas 3</option>
                                                </select>
                                                <label for="kelas_bpjs">Kelas BPJS</label>
                                            </div>
                                            @error('kelas_bpjs')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                    <a href="{{url('/karyawan/'.$holding)}}" type="button" class="btn btn-outline-secondary">Kembali</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body pt-2 mt-1">

                    </div>
                    <!-- /Account -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    function bankCheck(that) {
        if (that.value == "BBRI") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BRI?',
                showConfirmButton: true
            });
            bankdigit = 15;
            // document.getElementById("ifBRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifMANDIRI").style.display = "none";
        } else if (that.value == "BBCA") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BCA?',
                showConfirmButton: true
            });
            bankdigit = 10;
            // document.getElementById("ifMANDIRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        } else if (that.value == "BOCBC") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank OCBC?',
                showConfirmButton: true
            });
            bankdigit = 12;
            // document.getElementById("ifBCA").style.display = "block";
            // document.getElementById("ifMANDIRI").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        }
    }
    $(function() {
        if ($('#nama_bank').val() == "BBRI") {

            bankdigit = 15;
            // document.getElementById("ifBRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifMANDIRI").style.display = "none";
        } else if ($('#nama_bank').val() == "BBCA") {

            bankdigit = 10;
            // document.getElementById("ifMANDIRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        } else if ($('#nama_bank').val() == "BOCBC") {

            bankdigit = 12;
            // document.getElementById("ifBCA").style.display = "block";
            // document.getElementById("ifMANDIRI").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        }
    });

    $(function() {
        $('#form_departemen').hide();
        $('#form_divisi').hide();
        $('#form_jabatan_more').hide();
        $('#form_jabatan').hide();
        $('#form_lama_kotrak').hide();
        $('#form_bagian').hide();
        $('#form_kontrak').hide();
        $('#form_tgl_kontrak_kerja').hide();
        $('#form_level').hide();
        $('#form_lama_kontrak').hide();
        $('#form_tgl_mulai_kontrak').hide();
        $('#form_tgl_selesai_kontrak').hide();
        $('#form_site').hide();
        $('#form_kuota_cuti').hide();
        $('#kategori').on('change', function() {
            var id = $(this).val();
            if (id == 'Karyawan Harian') {
                $('#form_departemen').hide();
                $('#form_divisi').hide();
                $('#form_jabatan_more').hide();
                $('#form_jabatan').hide();
                $('#form_lama_kotrak').hide();
                $('#form_bagian').hide();
                $('#form_kontrak').hide();
                $('#form_tgl_kontrak_kerja').hide();
                $('#form_level').hide();
                $('#form_tgl_mulai_kontrak').hide();
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').hide();
                $('#form_lama_kontrak').hide();
                $('#form_kuota_cuti').hide();
            } else if (id == 'Karyawan Bulanan') {
                $('#form_departemen').show();
                $('#form_divisi').show();
                $('#form_jabatan_more').show();
                $('#form_jabatan').show();
                $('#form_lama_kotrak').show();
                $('#form_bagian').show();
                $('#form_kontrak').show();
                $('#form_tgl_kontrak_kerja').show();
                $('#form_level').show();
                $('#form_lama_kontrak').show();
                $('#form_tgl_mulai_kontrak').hide();
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').show();
                $('#form_kuota_cuti').hide();
            }
        });
        $('#lama_kontrak_kerja').on('change', function() {
            let iya = $(this).val();
            if (iya == 'tetap') {
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_tgl_mulai_kontrak').show();
                $('#form_kuota_cuti').show();
            } else {
                $('#form_tgl_selesai_kontrak').show();
                $('#form_tgl_mulai_kontrak').show();
                $('#form_kuota_cuti').show();
            }
        })
        if ($('#kategori').val() == 'Karyawan Harian') {
            $('#form_departemen').hide();
            $('#form_divisi').hide();
            $('#form_jabatan_more').hide();
            $('#form_jabatan').hide();
            $('#form_lama_kotrak').hide();
            $('#form_bagian').hide();
            $('#form_kontrak').hide();
            $('#form_tgl_kontrak_kerja').hide();
            $('#form_level').hide();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_site').hide();
            $('#form_lama_kontrak').hide();
            $('#form_kuota_cuti').hide();
        } else if ($('#kategori').val() == 'Karyawan Bulanan') {
            $('#form_departemen').show();
            $('#form_divisi').show();
            $('#form_jabatan_more').show();
            $('#form_jabatan').show();
            $('#form_lama_kotrak').show();
            $('#form_bagian').show();
            $('#form_kontrak').show();
            $('#form_tgl_kontrak_kerja').show();
            $('#form_level').show();
            $('#form_lama_kontrak').show();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_site').show();
            $('#form_kuota_cuti').hide();
        }
        if ($('#lama_kontrak_kerja').val() == 'tetap') {
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_tgl_mulai_kontrak').show();
            $('#form_kuota_cuti').show();
        } else if ($('#lama_kontrak_kerja').val() == '') {
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_kuota_cuti').hide();
        } else if ($('#kategori').val() == '') {
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_kuota_cuti').hide();
        } else {
            $('#form_tgl_selesai_kontrak').show();
            $('#form_tgl_mulai_kontrak').show();
            $('#form_kuota_cuti').show();
        }
    });
    $(function() {

        $('#atasan').on('change', function() {
            let id = $('#id_jabatan').val();
            let divisi = $('#id_divisi').val();
            let id_karyawan = $('#id_karyawan').val();
            let holding = '{{$holding}}';
            let url = "{{url('karyawan/atasan2/get_jabatan')}}" + "/" + holding;
            console.log(divisi);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: true,
                data: {
                    id: id,
                    id_karyawan: id_karyawan,
                    holding: holding,
                    id_divisi: divisi
                },
                success: function(response) {
                    // console.log(response);
                    $('#atasan2').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        });

    })
    $(function() {
        $('#nik').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("nik").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NIK harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#npwp').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("npwp").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NPWP harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#nomor_rekening').keyup(function(e) {
            if ($(this).val().length >= bankdigit) {
                $(this).val($(this).val().substr(0, bankdigit));
                document.getElementById("nomor_rekening").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });

        $('#foto_karyawan').change(function() {

            let reader = new FileReader();
            console.log(reader);
            reader.onload = (e) => {

                $('#template_foto_karyawan').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });
        $('#id_provinsi').on('change', function() {
            let id_provinsi = $(this).val();
            let url = "{{url('/karyawan/get_kabupaten')}}" + "/" + id_provinsi;
            console.log(id_provinsi);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_provinsi: id_provinsi
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kabupaten').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten').on('change', function() {
            let id_kabupaten = $(this).val();
            let url = "{{url('/karyawan/get_kecamatan')}}" + "/" + id_kabupaten;
            console.log(id_kabupaten);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kabupaten: id_kabupaten
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kecamatan').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan').on('change', function() {
            let id_kecamatan = $(this).val();
            let url = "{{url('/karyawan/get_desa')}}" + "/" + id_kecamatan;
            console.log(id_kecamatan);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kecamatan: id_kecamatan
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_desa').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_provinsi_domisili').on('change', function() {
            let id_provinsi_domisili = $(this).val();
            let url = "{{url('/karyawan/get_kabupaten')}}" + "/" + id_provinsi_domisili;
            console.log(id_provinsi_domisili);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_provinsi: id_provinsi
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kabupaten_domisili').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten_domisili').on('change', function() {
            let id_kabupaten_domisili = $(this).val();
            let url = "{{url('/karyawan/get_kecamatan')}}" + "/" + id_kabupaten_domisili;
            console.log(id_kabupaten_domisili);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kabupaten_domisili: id_kabupaten_domisili
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kecamatan_domisili').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan_domisili').on('change', function() {
            let id_kecamatan_domisili = $(this).val();
            let url = "{{url('/karyawan/get_desa')}}" + "/" + id_kecamatan_domisili;
            console.log(id_kecamatan_domisili);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kecamatan_domisili: id_kecamatan_domisili
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_desa_domisili').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })

    });
</script>
<script>
    $('#row_bpjs_ketenagakerjaan').hide();
    $('#row_bpjs_kesehatan').hide();
    $('#row_kelas_bpjs').show();
    var status_nomor = "{{old('status_nomor')}}";
    var status_bpjs_ketenagakerjaan = "{{old('bpjs_ketenagakerjaan')}}";
    var pilih_domisili_alamat = "{{old('pilihan_alamat_domisili')}}";
    var status_bpjs_kesehatan = "{{old('bpjs_kesehatan')}}";
    var status_npwp = "{{old('status_npwp')}}";
    // console.log(pilih_domisili_alamat);
    if (status_nomor == 'ya') {
        $('#content_nomor_wa').hide();
    } else if (status_nomor == 'tidak') {
        $('#content_nomor_wa').show();
    } else {
        $('#content_nomor_wa').hide();
    }
    if (pilih_domisili_alamat == 'ya') {
        $('#content_alamat_domisili').hide();
    } else if (pilih_domisili_alamat == 'tidak') {
        $('#content_alamat_domisili').show();
    } else {
        $('#content_alamat_domisili').hide();
    }
    if (status_bpjs_ketenagakerjaan == 'on') {
        $('#row_bpjs_ketenagakerjaan').show();
    } else if (status_bpjs_ketenagakerjaan == 'off') {
        $('#row_bpjs_ketenagakerjaan').hide();
    } else {
        $('#row_bpjs_ketenagakerjaan').hide();

    }
    if (status_bpjs_kesehatan == 'on') {
        $('#row_bpjs_kesehatan').show();
        $('#row_kelas_bpjs').show();
    } else if (status_bpjs_kesehatan == 'off') {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();
    } else {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();

    }
    if (status_npwp == 'on') {
        $('#row_npwp').show();
    } else if (status_npwp == 'off') {
        $('#row_npwp').hide();
    } else {
        $('#row_npwp').hide();
    }
    $(document).on("click", "#btn_status_no_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').hide();

        }
    });
    $(document).on("click", "#btn_status_no_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').show();
        }
    });
    $(document).on("click", "#btnradio_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').hide();

        }
    });
    $(document).on("click", "#btnradio_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').show();
        }
    });
    $(document).on("click", "#status_npwp_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_npwp').show();
        } else {
            $('#row_npwp').hide();

        }
    });
    $(document).on("click", "#status_npwp_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_npwp').hide();
        } else {
            $('#row_npwp').show();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_ketenagakerjaan').show();
        } else {
            $('#row_bpjs_ketenagakerjaan').hide();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_ketenagakerjaan').hide();
        } else {
            $('#row_bpjs_ketenagakerjaan').show();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();
        } else {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();
        } else {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();

        }
    });

    $('#file_cv').change(function() {

        var thisdata = $(this).val();
        var text = thisdata.substring(thisdata.lastIndexOf("\\") + 1, thisdata.length);
        let reader = new FileReader();
        reader.onload = (e) => {
            // console.log(e)
            $('#lihat_file_cv').attr('src', e.target.result);
        }
        $('#btn_modal_lihat').html('LIHAT (' + text + ')');
        // console.log($(this).val());

        reader.readAsDataURL(this.files[0]);

    });
    $('#row_kategori_jabatan').hide();
    if ($('#penempatan_kerja').val() == 'ALL SITES (SP, SPS, SIP)') {
        $('#row_kategori_jabatan').show();
    }
    $(document).on("change", "#penempatan_kerja", function() {
        var id = $(this).val();
        if (id == 'ALL SITES (SP, SPS, SIP)') {
            $('#row_kategori_jabatan').show();
        } else if (id == 'ALL SITES (SP)') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'ALL SITES (SPS)') {
            $('#row_kategori_jabatan').hide();
            $('#kategori_jabatan').val('sps');
            var holding = 'sps';
            $('#row_kategori_jabatan').hide();
        } else if (id == 'ALL SITES (SIP)') {
            $('#kategori_jabatan').val('sip');
            var holding = 'sip';
        } else if (id == 'CV. SUMBER PANGAN - KEDIRI') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'CV. SUMBER PANGAN - TUBAN') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - KEDIRI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - NGAWI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - SUBANG') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else {
            $('#row_kategori_jabatan').hide();
            var holding = '{{$holding}}';
        }
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_departemen')}}",
            data: {
                holding: holding,
            },
            cache: false,

            success: function(msg) {
                // console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_departemen').html(msg);
                $('#id_departemen1').html(msg);
                $('#id_departemen2').html(msg);
                $('#id_departemen3').html(msg);
                $('#id_departemen4').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        console.log($(this).val());
    });
    $(document).on("click", "#kategori_jabatan_sp", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sp') {
            $('#kategori_jabatan').val(holding);
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_departemen')}}",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi1').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi2').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi3').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi4').html('<option value="">Pilih Divisi</option>');
                    $('#id_bagian').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian1').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian2').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian3').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian4').html('<option value="">Pilih Bagian</option>');
                    $('#id_jabatan').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan1').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan2').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan3').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan4').html('<option value="">Pilih Jabatan</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sps", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sps') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_departemen')}}",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi1').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi2').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi3').html('<option value="">Pilih Divisi</option>');
                    $('#id_divisi4').html('<option value="">Pilih Divisi</option>');
                    $('#id_bagian').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian1').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian2').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian3').html('<option value="">Pilih Bagian</option>');
                    $('#id_bagian4').html('<option value="">Pilih Bagian</option>');
                    $('#id_jabatan').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan1').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan2').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan3').html('<option value="">Pilih Jabatan</option>');
                    $('#id_jabatan4').html('<option value="">Pilih Jabatan</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $('#id_departemen').on('change', function() {
        let id_departemen = $('#id_departemen').val();
        let holding = $('#kategori_jabatan').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_divisi')}}",
            data: {
                holding: holding,
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {
                // console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_divisi').html(msg);
                $('#id_bagian').html('<option value="">Pilih Bagian</option>');
                $('#id_jabatan').html('<option value="">Pilih Jabatan</option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen1').on('change', function() {
        let id_departemen = $('#id_departemen1').val();
        let holding = $('#kategori_jabatan').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_divisi')}}",
            data: {
                holding: holding,
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {

                $('#id_divisi1').html(msg);
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen2').on('change', function() {
        let id_departemen = $('#id_departemen2').val();
        let holding = $('#kategori_jabatan').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_divisi')}}",
            data: {
                holding: holding,
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {

                $('#id_divisi2').html(msg);
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');

            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen3').on('change', function() {
        let id_departemen = $('#id_departemen3').val();
        let holding = $('#kategori_jabatan').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_divisi')}}",
            data: {
                holding: holding,
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {
                $('#id_divisi3').html(msg);
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');

            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen4').on('change', function() {
        let id_departemen = $('#id_departemen4').val();
        let holding = $('#kategori_jabatan').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_divisi')}}",
            data: {
                holding: holding,
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {
                $('#id_divisi4').html(msg);
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi').on('change', function() {
        let id_divisi = $('#id_divisi').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_bagian')}}",
            data: {
                holding: holding,
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi1').on('change', function() {
        let id_divisi = $('#id_divisi1').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_bagian')}}",
            data: {
                holding: holding,
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian1').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi2').on('change', function() {
        let id_divisi = $('#id_divisi2').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_bagian')}}",
            data: {
                holding: holding,
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi3').on('change', function() {
        let id_divisi = $('#id_divisi3').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_bagian')}}",
            data: {
                holding: holding,
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian3').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi4').on('change', function() {
        let id_divisi = $('#id_divisi4').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_bagian')}}",
            data: {
                holding: holding,
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian4').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian').on('change', function() {
        let id_bagian = $('#id_bagian').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_jabatan')}}",
            data: {
                holding: holding,
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian1').on('change', function() {
        let id_bagian = $('#id_bagian1').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_jabatan')}}",
            data: {
                holding: holding,
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan1').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian2').on('change', function() {
        let id_bagian = $('#id_bagian2').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_jabatan')}}",
            data: {
                holding: holding,
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian3').on('change', function() {
        let id_bagian = $('#id_bagian3').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_jabatan')}}",
            data: {
                holding: holding,
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan3').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian4').on('change', function() {
        let id_bagian = $('#id_bagian4').val();
        let holding = $('#kategori_jabatan').val();
        $.ajax({
            type: 'GET',
            url: "{{url('karyawan/get_jabatan')}}",
            data: {
                holding: holding,
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan4').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
</script>
@endsection