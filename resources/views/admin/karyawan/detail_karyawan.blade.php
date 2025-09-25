@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .swal2-container {
        z-index: 9999 !important;
    }

    /* ukuran teks di area pilihan (input select2) */
    .select2-container--bootstrap-5 .select2-selection {
        font-size: 0.875rem !important;
        /* Bootstrap small (14px) */
        min-height: calc(1.5em + 0.75rem + 2px);
        /* biar tinggi konsisten */
    }

    /* ukuran teks di dropdown list */
    .select2-container--bootstrap-5 .select2-results__option {
        font-size: 0.875rem !important;
    }

    /* Fokus warna primary */
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: var(--bs-primary) !important;
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25) !important;
    }

    /* Background dan teks saat option terpilih */
    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: var(--bs-primary) !important;
        color: #fff !important;
    }

    /* Hover option */
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        color: var(--bs-primary) !important;
    }
</style>
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> DETAIL KARYAWAN</h4>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i
                            class="mdi mdi-account-outline mdi-20px me-1"></i>{{ $karyawan->name }}&nbsp;<b>[{{ $karyawan->nomor_identitas_karyawan }}]</b></a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-info"
                        href="@if (Auth::user()->is_admin == 'hrd') {{ url('/hrd/karyawan/shift/' . $karyawan->id . '/' . $holding) }}@else{{ url('/karyawan/shift/' . $karyawan->id . '/' . $holding) }} @endif"><i
                            class="mdi mdi-clock-outline mdi-20px me-1"></i>Mapping Jadwal&nbsp;</a>
                </li>
            </ul>
            <div class="card mb-4">
                <h4 class="card-header">Detail Profil</h4>
                <!-- Account -->
                <form method="post"
                    action="@if (Auth::user()->is_admin == 'hrd') {{ url('/hrd/karyawan/proses-edit/' . $karyawan->id . '/' . $holding) }}@else{{ url('/karyawan/proses-edit/' . $karyawan->id . '/' . $holding) }} @endif"
                    enctype="multipart/form-data">
                    @csrf
                    <input style="font-size: small;" type="hidden" value="{{ $karyawan->id }}" name="id_karyawan"
                        id="id_karyawan">
                    <div class="card-body">
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_profile" aria-controls="nav_profile" aria-selected="true">
                                        <i class="tf-icons mdi mdi-account-outline me-1"></i>
                                        PROFILE
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_alamat" aria-controls="nav_alamat" aria-selected="false">
                                        <i class="tf-icons mdi mdi-home-city me-1"></i>
                                        ALAMAT
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_info_hr" aria-controls="nav_info_hr" aria-selected="false">
                                        <i class="tf-icons mdi mdi-account-cog-outline me-1"></i>
                                        INFO HR
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_jabatan" aria-controls="nav_jabatan" aria-selected="false">
                                        <i class="tf-icons mdi mdi-medal-outline me-1"></i>
                                        JABATAN
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_bank" aria-controls="nav_bank" aria-selected="false">
                                        <i class="tf-icons mdi mdi-bank-circle me-1"></i>
                                        BANK
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_pajak" aria-controls="nav_pajak" aria-selected="false">
                                        <i class="tf-icons mdi mdi-percent-outline me-1"></i>
                                        PAJAK
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_bpjs" aria-controls="nav_bpjs" aria-selected="false">
                                        <i class="tf-icons mdi mdi-card-account-details-outline me-1"></i>
                                        BPJS
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_dokumen" aria-controls="nav_dokumen"
                                        aria-selected="false">
                                        <i class="tf-icons mdi mdi-file-document-multiple-outline me-1"></i>
                                        DOKUMEN
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="nav_profile" role="tabpanel">

                                    <div class="col-md-3">
                                        <span class="mdi mdi-account-badge badge bg-label-secondary">&nbsp;Foto Profil</span>
                                    </div>
                                    <hr class="m-0">
                                    <div class="row mt-2 mb-4 gy-4">
                                        <div class="col-md-4">
                                            @if ($karyawan->foto_karyawan)
                                            <img src="{{ asset('storage/foto_karyawan/' . $karyawan->foto_karyawan) }}" id="template_foto_karyawan" max-width="200" max-height="200" width="200"
                                                height="200" class="rounded" alt="">
                                            @else
                                            <img src="{{ asset('storage/foto_karyawan/default_profil.jpg') }}" id="template_foto_karyawan" max-width="200" max-height="200" width="200"
                                                height="200" class="rounded" alt="">
                                            @endif
                                            <br>
                                            <input type="file" name="foto_karyawan" id="foto_karyawan" hidden value="" accept="image/png, image/jpeg">
                                            <input type="hidden" name="foto_karyawan_old" id="foto_karyawan_old" value="{{$karyawan->foto_karyawan}}">
                                            <div id="group-button-foto"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="mdi mdi-account-tie badge bg-label-info">&nbsp;Biodata Diri</span>
                                    </div>
                                    <hr class="m-0">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('nik') is-invalid @enderror"
                                                    type="number" id="nik" name="nik"
                                                    value="{{ old('nik', $karyawan->nik) }}" />
                                                <label for="nik">NIK</label>
                                            </div>
                                            @error('nik')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name"
                                                    value="{{ old('name', $karyawan->name) }}">
                                                <label for="name">Nama&nbsp;Lengkap</label>
                                            </div>
                                            @error('name')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" name="email"
                                                    value="{{ old('email', $karyawan->email) }}">
                                                <label for="email">E-mail</label>
                                            </div>
                                            @error('email')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text"
                                                    class="form-control @error('telepon') is-invalid @enderror"
                                                    id="telepon" name="telepon"
                                                    value="{{ old('telepon', $karyawan->telepon) }}">
                                                <label for="telepon">Telepon</label>
                                            </div>
                                            @error('telepon')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <h6>Apakah Nomor Telepon Terhubung WhatsApps ?</h6>
                                            <div class="btn-group" role="group"
                                                aria-label="Basic radio toggle button group">
                                                <input type="radio"
                                                    class="btn-check @error('status_nomor') is-invalid @enderror"
                                                    name="status_nomor" value=""
                                                    @if (old('status_nomor', $karyawan->status_nomor) == '') checked @else @endif>
                                                <input type="radio"
                                                    class="btn-check @error('status_nomor') is-invalid @enderror"
                                                    name="status_nomor" id="btn_status_no_ya" value="ya"
                                                    @if (old('status_nomor', $karyawan->status_nomor) == 'ya') checked @else @endif>
                                                <label class="btn btn-sm btn-outline-success waves-effect"
                                                    for="btn_status_no_ya">Ya</label>
                                                <input type="radio"
                                                    class="btn-check @error('status_nomor') is-invalid @enderror"
                                                    name="status_nomor" id="btn_status_no_tidak" value="tidak"
                                                    @if (old('status_nomor', $karyawan->status_nomor) == 'tidak') checked @else @endif>
                                                <label class="btn btn-sm btn-outline-primary waves-effect"
                                                    for="btn_status_no_tidak">Tidak</label>
                                                @error('status_nomor')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div id="content_nomor_wa" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('nomor_wa') is-invalid @enderror"
                                                    type="number" name="nomor_wa" id="nomor_wa"
                                                    value="{{ old('nomor_wa', $karyawan->nomor_wa) }}" />
                                                <label for="nomor_wa">Nomor WA</label>
                                            </div>
                                            @error('nomor_wa')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text"
                                                    class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                    id="tempat_lahir" name="tempat_lahir"
                                                    value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}">
                                                <label for="tempat_lahir">Tempat Lahir</label>
                                            </div>
                                            @error('tempat_lahir')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="date"
                                                    id="tgl_lahir"
                                                    value="{{ old('tgl_lahir', $karyawan->tgl_lahir) }}"
                                                    name="tgl_lahir" placeholder="Tanggal Lahir" />
                                                <label for="tgl_lahir">Tanggal Lahir</label>
                                            </div>
                                            @error('tgl_lahir')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control" id="agama"
                                                    name="agama">
                                                    <option @if (old('agama', $karyawan->agama) == '') selected @else @endif
                                                        disabled value=""> ~Pilih Agama~ </option>
                                                    <option @if (old('agama', $karyawan->agama) == '1') selected @else @endif
                                                        value="1">ISLAM</option>
                                                    <option @if (old('agama', $karyawan->agama) == '2') selected @else @endif
                                                        value="2">KRISTEN PROTESTAN</option>
                                                    <option @if (old('agama', $karyawan->agama) == '3') selected @else @endif
                                                        value="3">KRISTEN KATOLIK</option>
                                                    <option @if (old('agama', $karyawan->agama) == '4') selected @else @endif
                                                        value="4">HINDU</option>
                                                    <option @if (old('agama', $karyawan->agama) == '5') selected @else @endif
                                                        value="5">BUDDHA</option>
                                                    <option @if (old('agama', $karyawan->agama) == '6') selected @else @endif
                                                        value="6">KHONGHUCU</option>
                                                </select>
                                                <label for="agama">Agama</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $gender = [
                                                    [
                                                        'gender_id' => '1',
                                                        'gender_name' => 'LAKI LAKI',
                                                    ],
                                                    [
                                                        'gender_id' => '2',
                                                        'gender_name' => 'PEREMPUAN',
                                                    ],
                                                ];
                                                ?>
                                                <select style="font-size: small;" name="gender" id="gender"
                                                    class="form-control @error('gender') is-invalid @enderror">
                                                    @foreach ($gender as $g)
                                                    @if (old('gender', $karyawan->gender) == $g['gender_id'])
                                                    <option value="{{ $g['gender_id'] }}" selected>
                                                        {{ $g['gender_name'] }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $g['gender_id'] }}">{{ $g['gender_name'] }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="gender">Kelamin</label>
                                            </div>
                                            @error('gender')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $sNikah = [
                                                    [
                                                        'status_id' => '1',
                                                        'status_name' => 'Belum Kawin',
                                                    ],
                                                    [
                                                        'status_id' => '2',
                                                        'status_name' => 'Sudah Kawin',
                                                    ],
                                                    [
                                                        'status_id' => '3',
                                                        'status_name' => 'Cerai Hidup',
                                                    ],
                                                    [
                                                        'status_id' => '4',
                                                        'status_name' => 'Cerai Mati',
                                                    ],
                                                ];
                                                ?>
                                                <select style="font-size: small;" name="status_nikah"
                                                    id="status_nikah" class="form-control selectpicker"
                                                    data-live-search="true">
                                                    @foreach ($sNikah as $s)
                                                    @if (old('status_nikah', $karyawan->status_nikah) == $s['status_id'])
                                                    <option value="{{ $s['status_id'] }}" selected>
                                                        {{ $s['status_name'] }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $s['status_id'] }}">{{ $s['status_name'] }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="status_nikah">Status Nikah</label>
                                            </div>
                                            @error('status_nikah')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input type="number" class="form-control" id="jumlah_anak" name="jumlah_anak" value="{{ old('jumlah_anak', $karyawan->jumlah_anak) }}">
                                                <label for="jumlah_anak">Jumlah Anak</label>
                                            </div>
                                            @error('jumlah_anak')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <h5>KTP</h5>
                                            <div class="form-floating form-floating-outline">
                                                <input type="file" hidden id="ktp" name="ktp" value="{{ old('ktp', $karyawan->ktp) }}">
                                                <img src="{{$karyawan->ktp == NULL ? asset('images/KTP.jpg'):asset('storage/ktp/' . $karyawan->ktp) }}" class="img-fluid" alt="" width="323" height="204">
                                                <br>
                                                <button type="button" id="btn_upload_ktp" class="btn btn-sm bottom-0">@if($karyawan->ktp == NULL) <i class="mdi mdi-upload text-primary"></i> <span class="text-primary">Upload</span> @else <i class="mdi mdi-pencil text-primary"></i> <span class="text-primary">Ganti</span> @endif</button>
                                                @if ($karyawan->ktp != NULL)
                                                <button type="button" id="btn_hapus_ktp" class="btn btn-sm bottom-0"><i class="mdi mdi-download text-primary"></i> <span class="text-primary">Download</span></button>
                                                @endif
                                            </div>
                                            @error('ktp')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-md-3 mt-5">
                                        <span
                                            class="mdi mdi-account-school-outline badge bg-label-primary">&nbsp;RIWAYAT PENDIDIKAN</span>
                                    </div>
                                    <hr class="m-0 mb-3">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" name="ipk" id="ipk" class="form-control" value="{{ old('ipk', $karyawan->ipk) }}">
                                                <label for="ipk">IPK</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="file" hidden name="file_ijazah" id="file_ijazah" class="form-control" accept=".pdf" value="">
                                                @if($karyawan->ijazah == NULL)
                                                <button type="button" id="btn_upload_ijazah" class="btn btn-sm"><i class="mdi mdi-upload text-primary"></i> <span class="text-primary">Upload</span></button>
                                                <label for="file_ijazah">FILE IJAZAH</label>
                                                @else
                                                <h5 for="file_ijazah">File Ijazah</h5>
                                                <input type="hidden" id="file_ijazah_old" name="file_ijazah_old" class="form-control" value="{{ $karyawan->ijazah }}">
                                                <div class="group-button-ijazah">
                                                    <a href="{{ asset('storage/ijazah/' . $karyawan->ijazah) }}" target="_blank" type="button" id="btn_lihat_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-eye text-primary"></i> <span class="text-primary">&nbsp;Lihat File</span></a>
                                                    <button id="btn_change_ijazah" type="button" class="btn btn-sm bottom-0"><i class="mdi mdi-pencil text-primary"></i> <span class="text-primary">Ganti</span></button>
                                                    <button type="button" id="btn_delete_file_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-delete text-primary"></i> <span class="text-primary">Hapus</span></button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="file" hidden name="file_transkrip_nilai" id="file_transkrip_nilai" class="form-control" accept=".pdf" value="">
                                                @if($karyawan->transkrip_nilai == NULL)
                                                <button type="button" id="btn_upload_transkrip_nilai" class="btn btn-sm"><i class="mdi mdi-upload text-primary"></i> <span class="text-primary">Upload</span></button>
                                                <label for="file_transkrip_nilai">FILE TRANSKRIP NILAI</label>
                                                @else
                                                <h5 for="transkrip_nilai">File Transkrip Nilai</h5>
                                                <input type="hidden" id="transkrip_nilai_old" name="transkrip_nilai_old" class="form-control" value="{{ $karyawan->transkrip_nilai }}">
                                                <div class="group-button-transkrip_nilai">
                                                    <a href="{{ asset('storage/transkrip_nilai/' . $karyawan->transkrip_nilai) }}" target="_blank" type="button" id="btn_lihat_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-eye text-primary"></i> <span class="text-primary">&nbsp;Lihat File</span></a>
                                                    <button id="btn_change_transkrip_nilai" type="button" class="btn btn-sm bottom-0"><i class="mdi mdi-pencil text-primary"></i> <span class="text-primary">&nbsp;Ganti</span></button>
                                                    <button type="button" id="btn_delete_file_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-delete text-primary"></i> <span class="text-primary">&nbsp;Hapus</span></button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <button type="button" id="btn_add_pendidikan" class="btn btn-sm btn-primary"><i class="mdi mdi-plus"></i></button>
                                        </div>
                                        <table class="table table-bordered" id="table_pendidikan" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Aksi</th>
                                                    <th>No</th>
                                                    <th>Nama&nbsp;Instansi</th>
                                                    <th>Jenjang</th>
                                                    <th>jurusan</th>
                                                    <th>Tahun&nbsp;Masuk</th>
                                                    <th>Tahun&nbsp;Lulus</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <span
                                            class="mdi mdi-account-school-outline badge bg-label-success">&nbsp;KEAHLIAN</span>
                                    </div>
                                    <hr class="m-0 mb-3">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <button type="button" id="btn_add_keahlian" class="btn btn-sm btn-primary"><i class="mdi mdi-plus"></i></button>
                                        </div>
                                        <table class="table table-bordered" id="table_keahlian" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Aksi</th>
                                                    <th>No</th>
                                                    <th>Nama&nbsp;Keahlian</th>
                                                    <th>File</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_add_pendidikan" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Pendidikan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="form_add_pendidikan">
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="jenjang" id="jenjang" class="form-control">
                                                                <option value="">--Pilih Jenjang--</option>
                                                                <option value="SMA">SMA</option>
                                                                <option value="SMK">SMK</option>
                                                                <option value="D1">D1</option>
                                                                <option value="D2">D2</option>
                                                                <option value="D3">D3</option>
                                                                <option value="S1">S1</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                            </select>
                                                            <label for="jenjang">Jenjang</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="nama_instansi" id="nama_instansi" class="form-control" value="">
                                                            <label for="nama_instansi">Nama Instansi</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="jurusan" id="jurusan" class="form-control" value="">
                                                            <label for="jurusan">Jurusan</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_masuk" id="tahun_masuk" class="form-control">
                                                                <option value="">--Pilih Tahun Masuk--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_masuk">Tahun Masuk</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_lulus" id="tahun_lulus" class="form-control">
                                                                <option value="">--Pilih Tahun Lulus--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_lulus">Tahun Lulus</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_pendidikan" class="btn btn-sm btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_edit_pendidikan" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Pendidikan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="form_edit_pendidikan">
                                                    <div class="col-md-12 mb-3">
                                                        <input type="hidden" name="id_pendidikan" id="id_pendidikan" value="">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="jenjang_update" id="jenjang_update" class="form-control">
                                                                <option value="">--Pilih Jenjang--</option>
                                                                <option value="SMA">SMA</option>
                                                                <option value="SMK">SMK</option>
                                                                <option value="D1">D1</option>
                                                                <option value="D2">D2</option>
                                                                <option value="D3">D3</option>
                                                                <option value="S1">S1</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                            </select>
                                                            <label for="jenjang_update">Jenjang</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="nama_instansi_update" id="nama_instansi_update" class="form-control" value="">
                                                            <label for="nama_instansi_update">Nama Instansi</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="jurusan_update" id="jurusan_update" class="form-control" value="">
                                                            <label for="jurusan_update">Jurusan</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_masuk_update" id="tahun_masuk_update" class="form-control">
                                                                <option value="">--Pilih Tahun Masuk--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_masuk_update">Tahun Masuk</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_lulus_update" id="tahun_lulus_update" class="form-control">
                                                                <option value="">--Pilih Tahun Lulus--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_lulus_update">Tahun Lulus</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_edit_pendidikan" class="btn btn-sm btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_add_keahlian" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tambah Keahlian</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="form_add_keahlian" enctype="multipart/form-data">
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="nama_keahlian" id="nama_keahlian" class="form-control" value="">
                                                            <label for="nama_keahlian">Nama Keahlian</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="file" name="file_keahlian" id="file_keahlian" class="form-control" hidden value="" accept=".pdf">
                                                            <button type="button" id="btn_upload_keahlian" class="btn btn-sm btn-secondary"><i class="mdi mdi-upload"></i> Upload</button>
                                                            <p class="text-primary">format: PDF</p>
                                                            <div class="group-button-keahlian align-items-center">
                                                            </div>
                                                            <label for="file_keahlian">File</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_keahlian" class="btn btn-sm btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_edit_keahlian" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Keahlian</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="form_edit_keahlian">
                                                    <div class="col-md-12 mb-3">
                                                        <input type="hidden" name="id_keahlian" id="id_keahlian" value="">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="nama_keahlian_update" id="nama_keahlian_update" class="form-control" value="">
                                                            <label for="nama_keahlian_update">Nama Keahlian</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="file_keahlian_update">File Keahlian</label>
                                                        <input type="file" name="file_keahlian_update" id="file_keahlian_update" hidden accept=".pdf" class="form-control" value="">
                                                        <input type="hidden" name="file_keahlian_old_update" id="file_keahlian_old_update" value="">
                                                        <div class="group-update-keahlian align-items-center">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_edit_keahlian" class="btn btn-sm btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_cv" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Lampiran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($karyawan->file_cv == '')
                                                <iframe id="lihat_file_cv" src=""
                                                    style=" height: 500px; width: 100%;"></iframe>
                                                @else
                                                <iframe id="lihat_file_cv"
                                                    src="{{ url('https://hrd.sumberpangan.store:4430/storage/app/public/file_cv/' . $karyawan->file_cv) }}"
                                                    style=" height: 500px; width: 100%;"></iframe>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
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
                                                <select style="font-size: small;"
                                                    class="form-control @error('provinsi') is-invalid @enderror"
                                                    id="id_provinsi" name="provinsi">
                                                    <option value=""> Pilih Provinsi </option>
                                                    @foreach ($data_provinsi as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('provinsi', $karyawan->provinsi) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_provinsi">Provinsi</label>
                                            </div>
                                            @error('provinsi')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $kab = App\Models\Cities::where('province_code', old('provinsi', $karyawan->provinsi))
                                                ->orderBy('name', 'ASC')
                                                ->get();
                                            $kec = App\Models\District::where('city_code', old('kabupaten', $karyawan->kabupaten))
                                                ->orderBy('name', 'ASC')
                                                ->get();
                                            $desa = App\Models\Village::where('district_code', old('kecamatan', $karyawan->kecamatan))
                                                ->orderBy('name', 'ASC')
                                                ->get();
                                            // echo $kab;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('kabupaten') is-invalid @enderror"
                                                    id="id_kabupaten" name="kabupaten">
                                                    <option value=""> Pilih Kabupaten / Kota</option>
                                                    @foreach ($kab as $kabupaten)
                                                    <option value="{{ $kabupaten->code }}"
                                                        {{ $kabupaten->code == old('kabupaten', $karyawan->kabupaten) ? 'selected' : '' }}>
                                                        {{ $kabupaten->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kabupaten">Kabupaten</label>
                                            </div>
                                            @error('kabupaten')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('kecamatan') is-invalid @enderror"
                                                    id="id_kecamatan" name="kecamatan">
                                                    <option value=""> Pilih kecamatan</option>
                                                    @foreach ($kec as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('kecamatan', $karyawan->kecamatan) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kecamatan">kecamatan</label>
                                            </div>
                                            @error('kecamatan')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('desa') is-invalid @enderror"
                                                    id="id_desa" name="desa">
                                                    <option value=""> Pilih Desa</option>
                                                    @foreach ($desa as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('desa', $karyawan->desa) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_desa">Desa</label>
                                            </div>
                                            @error('desa')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rt"
                                                    name="rt" class="form-control @error('rt') is-invalid @enderror"
                                                    placeholder="Masukkan RT" value="{{ old('rt', $karyawan->rt) }}" />
                                                <label for="rt">RT</label>
                                            </div>
                                            @error('rt')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rw"
                                                    name="rw" class="form-control @error('rw') is-invalid @enderror"
                                                    placeholder="Masukkan RW" value="{{ old('rw', $karyawan->rw) }}" />
                                                <label for="rw">RW</label>
                                            </div>
                                            @error('rw')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" id="alamat"
                                                    name="alamat"
                                                    class="form-control @error('alamat') is-invalid @enderror"
                                                    placeholder="Masukkan Alamat"
                                                    value="{{ old('alamat', $karyawan->detail_alamat) }}" />
                                                <label for="alamat">Keterangan Alamat(Jalan / Dusun)</label>
                                            </div>
                                            @error('alamat')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <h6>Apakah Alamat KTP Sama Dengan Alamat Domisili ?</h6>
                                            <div class="btn-group" role="group"
                                                aria-label="Basic radio toggle button group">
                                                <input style="font-size: small;" type="radio"
                                                    class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror"
                                                    name="pilihan_alamat_domisili" value="" checked>
                                                <input style="font-size: small;" type="radio"
                                                    class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror"
                                                    name="pilihan_alamat_domisili" id="btnradio_ya" value="ya"
                                                    @if (old('pilihan_alamat_domisili', $karyawan->status_alamat) == 'ya') checked @else @endif>
                                                <label class="btn btn-sm btn-outline-success waves-effect"
                                                    for="btnradio_ya">Ya</label>
                                                <input style="font-size: small;" type="radio"
                                                    class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror"
                                                    name="pilihan_alamat_domisili" id="btnradio_tidak" value="tidak"
                                                    @if (old('pilihan_alamat_domisili', $karyawan->status_alamat) == 'tidak') checked @else @endif>
                                                <label class="btn btn-sm btn-outline-primary waves-effect"
                                                    for="btnradio_tidak">Tidak</label>
                                                @error('pilihan_alamat_domisili')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div id="content_alamat_domisili" class="row mt-2 gy-4">
                                        <div class="col-md-3">
                                            <span class="badge bg-label-danger">Alamat Berdasarkan Domisili
                                                Sekarang</span>
                                        </div>
                                        <hr class="m-0">
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('provinsi_domisili') is-invalid @enderror"
                                                    id="id_provinsi_domisili" name="provinsi_domisili"
                                                    style="font-size: small;">
                                                    <option value=""> Pilih Provinsi </option>
                                                    @foreach ($data_provinsi as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('provinsi_domisili', $karyawan->provinsi_domisili) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_provinsi_domisili">Provinsi</label>
                                            </div>
                                            @error('provinsi_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $kab_domisili = App\Models\Cities::Where('province_code', old('provinsi_domisili', $karyawan->provinsi_domisili))
                                                ->orderBy('name', 'ASC')
                                                ->get();
                                            $kec_domisili = App\Models\District::Where('city_code', old('kabupaten_domisili', $karyawan->kabupaten_domisili))
                                                ->orderBy('name', 'ASC')
                                                ->get();
                                            $desa_domisili = App\Models\Village::Where('district_code', old('kecamatan_domisili', $karyawan->kecamatan_domisili))
                                                ->orderBy('name', 'ASC')
                                                ->get();
                                            // echo $kab;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('kabupaten_domisili') is-invalid @enderror"
                                                    id="id_kabupaten_domisili" name="kabupaten_domisili"
                                                    style="font-size: small;">
                                                    <option value=""> Pilih Kabupaten / Kota</option>
                                                    @foreach ($kab_domisili as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('kabupaten_domisili', $karyawan->kabupaten_domisili) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kabupaten_domisili">Kabupaten</label>
                                            </div>
                                            @error('kabupaten_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('kecamatan_domisili') is-invalid @enderror"
                                                    id="id_kecamatan_domisili" name="kecamatan_domisili"
                                                    style="font-size: small;">
                                                    <option value=""> Pilih Kecamatan</option>
                                                    @foreach ($kec_domisili as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('kecamatan_domisili', $karyawan->kecamatan_domisili) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kecamatan_domisili">kecamatan_domisili</label>
                                            </div>
                                            @error('kecamatan_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('desa_domisili') is-invalid @enderror"
                                                    id="id_desa_domisili" name="desa_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Desa</option>
                                                    @foreach ($desa_domisili as $data)
                                                    <option value="{{ $data->code }}"
                                                        {{ $data->code == old('desa_domisili', $karyawan->desa_domisili) ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label for="id_desa_domisili">Desa</label>
                                            </div>
                                            @error('desa_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" style="font-size: small;" type="number"
                                                    id="rt_domisili" name="rt_domisili"
                                                    class="form-control @error('rt_domisili') is-invalid @enderror"
                                                    placeholder="Masukkan RT"
                                                    value="{{ old('rt_domisili', $karyawan->rt_domisili) }}" />
                                                <label for="rt_domisili">RT</label>
                                            </div>
                                            @error('rt_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" style="font-size: small;" type="number"
                                                    id="rw_domisili" name="rw_domisili"
                                                    class="form-control @error('rw_domisili') is-invalid @enderror"
                                                    placeholder="Masukkan RW"
                                                    value="{{ old('rw_domisili', $karyawan->rw_domisili) }}" />
                                                <label for="rw_domisili">RW</label>
                                            </div>
                                            @error('rw_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" style="font-size: small;" type="text"
                                                    id="alamat_domisili" name="alamat_domisili"
                                                    class="form-control @error('alamat_domisili') is-invalid @enderror"
                                                    placeholder="Masukkan Alamat"
                                                    value="{{ old('alamat_domisili', $karyawan->alamat_domisili) }}" />
                                                <label for="alamat_domisili">Keterangan Alamat(Jalan / Dusun)</label>
                                            </div>
                                            @error('alamat_domisili')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_info_hr" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-3">

                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="kategori" id="kategori"
                                                    class="form-control @error('kategori') is-invalid @enderror">
                                                    <option value=""> Pilih Kategori</option>
                                                    <option value="Karyawan Bulanan" {{ $karyawan->kategori == 'Karyawan Bulanan' ? 'selected' : '' }}> Karyawan Bulanan</option>
                                                    <option value="Karyawan Harian" {{ $karyawan->kategori == 'Karyawan Harian' ? 'selected' : '' }}> Karyawan Harian</option>
                                                </select>
                                                <label for="kategori">Kategori Karyawan</label>
                                            </div>
                                            @error('kategori')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="shift" id="shift"
                                                    class="form-control @error('shift') is-invalid @enderror">
                                                    <option value="">~~ Pilih Shift ~~</option>
                                                    <option value="Non Shift" {{ $karyawan->shift == 'Non Shift' ? 'selected' : '' }}> Non Shift</option>
                                                    <option value="Shift" {{ $karyawan->shift == 'Shift' ? 'selected' : '' }}> Shift</option>
                                                </select>
                                                <label for="shift">Shift</label>
                                            </div>
                                            @error('shift')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_kontrak" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control"
                                                    readonly
                                                    value="{{ $karyawan->KontrakKerja->holding_name }}">
                                                <input style="font-size: small;" type="hidden" class="form-control"
                                                    id="kontrak_kerja" name="kontrak_kerja"
                                                    value="{{ $karyawan->kontrak_kerja }}">
                                                <label for="kontrak_kerja">Kontrak Kerja</label>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" disabled type="date"
                                                    class="form-control @error('tgl_join') is-invalid @enderror"
                                                    id="tgl_join" name="tgl_join"
                                                    value="{{ old('tgl_join', $karyawan->tgl_join) }}">
                                                <label for="tgl_join">Tanggal Join Perusahaan</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('tgl_join')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_lama_kontrak" class="col-md-3">
                                            <?php $lama_kontrak_kerja = [
                                                [
                                                    'lama_kontrak_kerja' => '3 bulan',
                                                ],
                                                [
                                                    'lama_kontrak_kerja' => '6 bulan',
                                                ],
                                                [
                                                    'lama_kontrak_kerja' => '1 tahun',
                                                ],
                                                [
                                                    'lama_kontrak_kerja' => '2 bahun',
                                                ],
                                                [
                                                    'lama_kontrak_kerja' => 'tetap',
                                                ],
                                            ];
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="lama_kontrak_kerja"
                                                    id="lama_kontrak_kerja" disabled
                                                    class="form-control selectpicker @error('lama_kontrak_kerja') is-invalid @enderror"
                                                    data-live-search="true">
                                                    <option value="">Pilih Kontrak</option>
                                                    @foreach ($lama_kontrak_kerja as $a)
                                                    @if (old('lama_kontrak_kerja', $karyawan->lama_kontrak_kerja) == $a['lama_kontrak_kerja'])
                                                    <option value="{{ $a['lama_kontrak_kerja'] }}" selected>
                                                        {{ $a['lama_kontrak_kerja'] }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $a['lama_kontrak_kerja'] }}">
                                                        {{ $a['lama_kontrak_kerja'] }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="lama_kontrak_kerja">Lama Kontrak</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('lama_kontrak_kerja')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_tgl_mulai_kontrak" class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" disabled type="date" readonly
                                                    class="form-control @error('tgl_mulai_kontrak') is-invalid @enderror"
                                                    id="tgl_mulai_kontrak" name="tgl_mulai_kontrak"
                                                    value="{{ old('tgl_mulai_kontrak', $karyawan->tgl_mulai_kontrak) }}" />
                                                <label for="tgl_mulai_kontrak">Tanggal Mulai Kontrak</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('tgl_mulai_kontrak')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_tgl_selesai_kontrak" class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" disabled type="date" readonly
                                                    class="form-control @error('tgl_selesai_kontrak') is-invalid @enderror"
                                                    id="tgl_selesai_kontrak" name="tgl_selesai_kontrak"
                                                    value="{{ old('tgl_selesai_kontrak', $karyawan->tgl_selesai_kontrak) }}" />
                                                <label for=" tgl_selesai_kontrak">Tanggal Selesai Kontrak</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('tgl_selesai_kontrak')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_kuota_cuti" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="kuota_cuti"
                                                    name="kuota_cuti"
                                                    class="form-control @error('kuota_cuti') is-invalid @enderror"
                                                    placeholder="Masukkan Cuti Tahunan"
                                                    value="{{ old('kuota_cuti', $karyawan->kuota_cuti_tahunan) }}" />
                                                <label for="kuota_cuti">Kuota Cuti Tahunan</label>
                                            </div>
                                            @error('kuota_cuti')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav_jabatan" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div id="form_site" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('approval_site') is-invalid @enderror"
                                                    id="approval_site" name="approval_site[]" multiple>
                                                    <option disabled value=""> Pilih Approval Site</option>
                                                    @foreach ($data_lokasi as $a)
                                                    @if (old('approval_site', $karyawan->approval_site) == $a['id'])
                                                    <option value="{{ $a['id'] }}" selected>
                                                        {{ $a['site_name'] }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $a['id'] }}">
                                                        {{ $a['site_name'] }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="approval_site">Approval Site</label>
                                            </div>
                                            <p class="text-info">Untuk Kebutuhan Approval</p>
                                            @error('approval_site')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('penempatan_kerja') is-invalid @enderror"
                                                    id="penempatan_kerja" name="penempatan_kerja[]" multiple>
                                                    <option disabled value=""> Pilih Lokasi Penempatan
                                                    </option>
                                                    @foreach ($data_lokasi1 as $a)
                                                    @if (old('penempatan_kerja', $karyawan->penempatan_kerja) == $a['id'])
                                                    <option value="{{ $a['id'] }}" selected>
                                                        {{ $a['site_name'] }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $a['id'] }}">
                                                        {{ $a['site_name'] }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="penempatan_kerja">Penempatan Kerja</label>
                                            </div>
                                            <p class="text-info">Untuk Kebutuhan Struktural</p>
                                            @error('penempatan_kerja')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div id="form_departemen" class="col-md-3">
                                            <?php
                                            $data_departemen = App\Models\Departemen::where('holding', $karyawan->PenempatanKerja->site_holding_category)->orderBy('nama_departemen', 'ASC')->get();
                                            // print_r($data_departemen);
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="departemen_id" id="id_departemen"
                                                    class="form-control @error('departemen_id') is-invalid @enderror">
                                                    <option value=""> Pilih Departemen</option>
                                                    <optgroup label='Daftar Departemen '>
                                                        @foreach ($data_departemen as $dj)
                                                        @if (old('departemen_id', $karyawan->dept_id) == $dj->id)
                                                        <option value="{{ $dj->id }}" selected>
                                                            {{ $dj->nama_departemen }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $dj->id }}">
                                                            {{ $dj->nama_departemen }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_departemen">Departemen</label>
                                            </div>
                                            @error('departemen_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_divisi" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = $karyawan->kategori_jabatan;
                                            }
                                            $data_divisi = App\Models\Divisi::Where('dept_id', old('departemen_id', $karyawan->dept_id))
                                                ->orderBy('nama_divisi', 'ASC')
                                                ->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="divisi_id" id="id_divisi"
                                                    class="form-control @error('divisi_id') is-invalid @enderror">
                                                    <option selected disabled value="">Pilih Divisi</option>
                                                    <optgroup label='Daftar Divisi'>
                                                        @foreach ($data_divisi as $divisi)
                                                        @if (old('divisi_id', $karyawan->divisi_id) == $divisi['id'])
                                                        <option value="{{ $divisi->id }}" selected>
                                                            {{ $divisi->nama_divisi }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $divisi->id }}">
                                                            {{ $divisi->nama_divisi }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_divisi">Divisi</label>
                                            </div>
                                            @error('divisi_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_bagian" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = $karyawan->kategori_jabatan;
                                            }
                                            $data_bagian = App\Models\Bagian::Where('divisi_id', old('divisi_id', $karyawan->divisi_id))
                                                ->orderBy('nama_bagian', 'ASC')
                                                ->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="bagian_id" id="id_bagian"
                                                    class="form-control @error('bagian_id') is-invalid @enderror">
                                                    <option selected disabled value="">Pilih Bagian</option>
                                                    <optgroup label='Daftar Bagian'>
                                                        @foreach ($data_bagian as $bagian)
                                                        @if (old('bagian_id', $karyawan->bagian_id) == $bagian['id'])
                                                        <option value="{{ $bagian->id }}" selected>
                                                            {{ $bagian->nama_bagian }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $bagian->id }}">
                                                            {{ $bagian->nama_bagian }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_bagian">Bagian</label>
                                            </div>
                                            @error('bagian_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_jabatan" class="col-md-3">
                                            <?php
                                            // Bagian
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = $karyawan->kategori_jabatan;
                                            }
                                            $data_bagian = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi_id))
                                                ->orderBy('nama_bagian', 'ASC')
                                                ->get();
                                            $data_bagian1 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi1_id))
                                                ->orderBy('nama_bagian', 'ASC')
                                                ->get();
                                            $data_bagian2 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi2_id))
                                                ->orderBy('nama_bagian', 'ASC')
                                                ->get();
                                            $data_bagian3 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi3_id))
                                                ->orderBy('nama_bagian', 'ASC')
                                                ->get();
                                            $data_bagian4 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi4_id))
                                                ->orderBy('nama_bagian', 'ASC')
                                                ->get();
                                            // Jabatan
                                            $data_jabatan = App\Models\Jabatan::Where('bagian_id', old('bagian_id', $karyawan->bagian_id))
                                                ->where(old('disivi_id', $karyawan->disivi_id))
                                                ->orderBy('nama_jabatan', 'ASC')
                                                ->get();
                                            $data_jabatan1 = App\Models\Jabatan::Where('bagian_id', old('bagian1_id', $karyawan->bagian1_id))
                                                ->where(old('disivi1_id', $karyawan->disivi1_id))
                                                ->orderBy('nama_jabatan', 'ASC')
                                                ->get();
                                            $data_jabatan2 = App\Models\Jabatan::Where('bagian_id', old('bagian2_id', $karyawan->bagian2_id))
                                                ->where(old('disivi2_id', $karyawan->disivi2_id))
                                                ->orderBy('nama_jabatan', 'ASC')
                                                ->get();
                                            $data_jabatan3 = App\Models\Jabatan::Where('bagian_id', old('bagian3_id', $karyawan->bagian3_id))
                                                ->where(old('disivi3_id', $karyawan->disivi3_id))
                                                ->orderBy('nama_jabatan', 'ASC')
                                                ->get();
                                            $data_jabatan4 = App\Models\Jabatan::Where('bagian_id', old('bagian4_id', $karyawan->bagian4_id))
                                                ->where(old('disivi4_id', $karyawan->disivi4_id))
                                                ->orderBy('nama_jabatan', 'ASC')
                                                ->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="jabatan_id" id="id_jabatan"
                                                    class="form-control @error('jabatan_id') is-invalid @enderror">
                                                    <option value="">Pilih Jabatan</option>
                                                    <optgroup label='Daftar Jabatan'>
                                                        @foreach ($data_jabatan as $jabatan)
                                                        <option value="{{ $jabatan->id }}"
                                                            {{ $jabatan->id == $karyawan->jabatan_id ? 'selected' : '' }}>
                                                            {{ $jabatan->nama_jabatan }}
                                                        </option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_jabatan">Jabatan</label>
                                            </div>
                                            @error('jabatan_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="form_jabatan_more" class="row g-2 mt-2">
                                            <div class="col mb-2">
                                                <div class="accordion mt-3" id="accordionExample">
                                                    <div
                                                        class="accordion-item @if ($karyawan->jabatan1_id != '') active @endif">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button type="button" class="accordion-button"
                                                                data-bs-toggle="collapse" data-bs-target="#accordionOne"
                                                                aria-expanded="true" aria-controls="accordionOne">
                                                                Jika Karyawan Memiliki Lebih Dari 1 Jabatan
                                                            </button>
                                                        </h2>

                                                        <div id="accordionOne"
                                                            class="accordion-collapse collapse @if ($karyawan->jabatan1_id != '') show @endif"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="departemen1_id" id="id_departemen1"
                                                                                class="form-control">
                                                                                <option value=""> Pilih
                                                                                    Departemen</option>
                                                                                <?php
                                                                                if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                    $kategori_jabatan = $holding;
                                                                                } else {
                                                                                    $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                }
                                                                                print_r($kategori_jabatan);
                                                                                $departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                                                                ?>
                                                                                <optgroup
                                                                                    label='Daftar Departemen '>
                                                                                    @foreach ($departemen as $departemen)
                                                                                    @if (old('departemen1_id', $karyawan->dept1_id) == $departemen->id)
                                                                                    <option
                                                                                        value="{{ $departemen->id }}"
                                                                                        selected>
                                                                                        {{ $departemen->nama_departemen }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $departemen->id }}">
                                                                                        {{ $departemen->nama_departemen }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_departemen1">Departemen
                                                                                2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="divisi1_id" id="id_divisi1"
                                                                                class="form-control">
                                                                                <option value=""> Pilih Divisi
                                                                                </option>
                                                                                <optgroup
                                                                                    label='Daftar Divisi '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $divisi = App\Models\Divisi::where('dept_id', old('departemen1_id', $karyawan->dept1_id))
                                                                                        ->orderBy('nama_divisi', 'ASC')
                                                                                        ->get();
                                                                                    ?>
                                                                                    @foreach ($divisi as $divisi)
                                                                                    @if (old('divisi1_id', $karyawan->divisi1_id) == $divisi->id)
                                                                                    <option
                                                                                        value="{{ $divisi->id }}"
                                                                                        selected>
                                                                                        {{ $divisi->nama_divisi }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $divisi->id }}">
                                                                                        {{ $divisi->nama_divisi }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_divisi1">Divisi 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="bagian1_id" id="id_bagian1"
                                                                                class="form-control @error('bagian1_id') is-invalid @enderror">
                                                                                <option value=""> Pilih Bagian
                                                                                </option>
                                                                                <optgroup
                                                                                    label='Daftar Bagian '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $bagian = App\Models\Bagian::where('divisi_id', old('divisi1_id', $karyawan->divisi1_id))
                                                                                        ->orderBy('nama_bagian', 'ASC')
                                                                                        ->get();
                                                                                    ?>
                                                                                    @foreach ($bagian as $bagian)
                                                                                    @if (old('bagian1_id', $karyawan->bagian1_id) == $bagian->id)
                                                                                    <option
                                                                                        value="{{ $bagian->id }}"
                                                                                        selected>
                                                                                        {{ $bagian->nama_bagian }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $bagian->id }}">
                                                                                        {{ $bagian->nama_bagian }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for="id_bagian1">Bagian 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="jabatan1_id" id="id_jabatan1"
                                                                                class="form-control">
                                                                                <option value=""> Pilih Jabatan
                                                                                </option>
                                                                                <optgroup
                                                                                    label='Daftar Jabatan '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $jabatan = App\Models\Jabatan::where('bagian_id', old('bagian1_id', $karyawan->bagian1_id))
                                                                                        ->orderBy('nama_jabatan', 'ASC')
                                                                                        ->get();
                                                                                    ?>
                                                                                    @foreach ($jabatan as $jabatan)
                                                                                    @if (old('jabatan1_id', $karyawan->jabatan1_id) == $jabatan->id)
                                                                                    <option
                                                                                        value="{{ $jabatan->id }}"
                                                                                        selected>
                                                                                        {{ $jabatan->nama_jabatan }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $jabatan->id }}">
                                                                                        {{ $jabatan->nama_jabatan }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_jabatan1">Jabatan 2</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="departemen2_id" id="id_departemen2"
                                                                                class="form-control">
                                                                                <option value=""> Pilih
                                                                                    Departemen</option>
                                                                                <optgroup
                                                                                    label='Daftar Departemen '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach ($departemen as $departemen)
                                                                                    @if (old('departemen2_id', $karyawan->dept2_id) == $departemen->id)
                                                                                    <option
                                                                                        value="{{ $departemen->id }}"
                                                                                        selected>
                                                                                        {{ $departemen->nama_departemen }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $departemen->id }}">
                                                                                        {{ $departemen->nama_departemen }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_departemen2">Departemen
                                                                                3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="divisi2_id" id="id_divisi2"
                                                                                class="form-control">
                                                                                <option value=""> Pilih Divisi
                                                                                </option>
                                                                                <optgroup
                                                                                    label='Daftar Divisi '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $divisi = App\Models\Divisi::where('dept_id', old('departemen2_id', $karyawan->dept_id))
                                                                                        ->orderBy('nama_divisi', 'ASC')
                                                                                        ->get();
                                                                                    ?>
                                                                                    @foreach ($divisi as $divisi)
                                                                                    @if (old('divisi2_id', $karyawan->divisi2_id) == $divisi->id)
                                                                                    <option
                                                                                        value="{{ $divisi->id }}"
                                                                                        selected>
                                                                                        {{ $divisi->nama_divisi }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $divisi->id }}">
                                                                                        {{ $divisi->nama_divisi }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_divisi2">Divisi 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="bagian2_id" id="id_bagian2"
                                                                                class="form-control">
                                                                                <option value=""> Pilih Bagian
                                                                                </option>
                                                                                <optgroup
                                                                                    label='Daftar Bagian '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $bagian = App\Models\Bagian::where('divisi_id', old('divisi2_id', $karyawan->divisi2_id))
                                                                                        ->orderBy('nama_bagian', 'ASC')
                                                                                        ->get();
                                                                                    ?>
                                                                                    @foreach ($bagian as $bagian)
                                                                                    @if (old('bagian2_id', $karyawan->bagian2_id) == $bagian->id)
                                                                                    <option
                                                                                        value="{{ $bagian->id }}"
                                                                                        selected>
                                                                                        {{ $bagian->nama_bagian }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $bagian->id }}">
                                                                                        {{ $bagian->nama_bagian }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for="id_bagian2">Bagian 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="jabatan2_id" id="id_jabatan2"
                                                                                class="form-control">
                                                                                <option value=""> Pilih Jabatan
                                                                                </option>
                                                                                <optgroup
                                                                                    label='Daftar Jabatan '>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $jabatan = App\Models\Jabatan::where('bagian_id', old('bagian2_id', $karyawan->bagian2_id))
                                                                                        ->orderBy('nama_jabatan', 'ASC')
                                                                                        ->get();
                                                                                    ?>
                                                                                    @foreach ($jabatan as $jabatan)
                                                                                    @if (old('jabatan2_id', $karyawan->jabatan2_id) == $jabatan->id)
                                                                                    <option
                                                                                        value="{{ $jabatan->id }}"
                                                                                        selected>
                                                                                        {{ $jabatan->nama_jabatan }}
                                                                                    </option>
                                                                                    @else
                                                                                    <option
                                                                                        value="{{ $jabatan->id }}">
                                                                                        {{ $jabatan->nama_jabatan }}
                                                                                    </option>
                                                                                    <!-- <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_jabatan2">Jabatan 3</label>
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
                                                <?php $bank = [
                                                    [
                                                        'kode_bank' => 'BBRI',
                                                        'bank' => 'BANK RAKYAT INDONESIA (BRI)',
                                                    ],
                                                    [
                                                        'kode_bank' => 'BBCA',
                                                        'bank' => 'BANK CENTRAL ASIA (BCA)',
                                                    ],
                                                    [
                                                        'kode_bank' => 'BOCBC',
                                                        'bank' => 'BANK OCBC',
                                                    ],
                                                    [
                                                        'kode_bank' => 'BMANDIRI',
                                                        'bank' => 'BANK MANDIRI',
                                                    ],
                                                ];
                                                ?>
                                                <select style="font-size: small;" name="nama_bank" id="nama_bank"
                                                    onchange="bankCheck(this);"
                                                    class="form-control  @error('nama_bank') is-invalid @enderror">
                                                    <option value="">Pilih Bank</option>
                                                    @foreach ($bank as $bank)
                                                    @if (old('nama_bank', $karyawan->nama_bank) == $bank['kode_bank'])
                                                    <option value="{{ $bank['kode_bank'] }}" selected>
                                                        {{ $bank['bank'] }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $bank['kode_bank'] }}">
                                                        {{ $bank['bank'] }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="nama_bank">Nama Bank</label>
                                            </div>
                                            @error('nama_bank')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text"
                                                    class="form-control  @error('nama_pemilik_rekening') is-invalid @enderror"
                                                    id="nama_pemilik_rekening" name="nama_pemilik_rekening"
                                                    value="{{ old('nama_pemilik_rekening', $karyawan->nama_pemilik_rekening) }}"
                                                    placeholder="Nama Pemilik Rekening" />
                                                <label for="nama_pemilik_rekening">Nama Pemilik Rekening</label>
                                            </div>
                                            @error('nama_pemilik_rekening')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number"
                                                    class="form-control  @error('nomor_rekening') is-invalid @enderror"
                                                    id="nomor_rekening" name="nomor_rekening"
                                                    value="{{ old('nomor_rekening', $karyawan->nomor_rekening) }}"
                                                    placeholder="Nomor Rekening" />
                                                <label for="nomor_rekening">Nomor Rekening</label>
                                            </div>
                                            @error('nomor_rekening')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_pajak" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('ptkp') is-invalid @enderror"
                                                    id="ptkp" name="ptkp">
                                                    <option @if (old('ptkp', $karyawan->ptkp) == '') selected @else @endif
                                                        value="">Pilih PKTP</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'TK/0') selected @else @endif
                                                        value="TK/0">TK/0</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'TK/1') selected @else @endif
                                                        value="TK/1">TK/1</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'TK/2') selected @else @endif
                                                        value="TK/2">TK/2</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'TK/3') selected @else @endif
                                                        value="TK/3">TK/3</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/0') selected @else @endif
                                                        value="K/0">K/0</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/1') selected @else @endif
                                                        value="K/1">K/1</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/2') selected @else @endif
                                                        value="K/2">K/2</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/I/0') selected @else @endif
                                                        value="K/I/0">K/I/0</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/I/1') selected @else @endif
                                                        value="K/I/1">K/I/1</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/I/2') selected @else @endif
                                                        value="K/I/2">K/I/2</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/I/3') selected @else @endif
                                                        value="K/I/3">K/I/3</option>
                                                    <option @if (old('ptkp', $karyawan->ptkp) == 'K/3') selected @else @endif
                                                        value="K/3">K/3</option>
                                                </select>
                                                <label for="ptkp">PTKP</label>
                                            </div>
                                            @error('ptkp')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="status_npwp">Punya NPWP</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="status_npwp_ya" name="status_npwp"
                                                            class="form-check-input" value="on"
                                                            @if (old('status_npwp', $karyawan->status_npwp) == 'on') checked @else @endif>
                                                        <label class="form-check-label" for="status_npwp_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="status_npwp_tidak" name="status_npwp"
                                                            class="form-check-input" value="off"
                                                            @if (old('status_npwp', $karyawan->status_npwp) == 'off') checked @else @endif>
                                                        <label class="form-check-label"
                                                            for="status_npwp_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_npwp" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('nama_pemilik_npwp') is-invalid @enderror"
                                                    type="text" placeholder="Nama Pemilik NPWP"
                                                    id="nama_pemilik_npwp" name="nama_pemilik_npwp"
                                                    value="{{ old('nama_pemilik_npwp', $karyawan->nama_pemilik_npwp) }}" />
                                                <label for="nama_pemilik_npwp">Nama Pemilik NPWP</label>
                                            </div>
                                            @error('nama_pemilik_npwp')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('npwp') is-invalid @enderror"
                                                    type="number" id="npwp" name="npwp"
                                                    value="{{ old('npwp', $karyawan->npwp) }}" />
                                                <label for="npwp">NPWP</label>
                                            </div>
                                            @error('npwp')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_bpjs" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_ketenagakerjaan">BPJS
                                                Ketenagakerjaan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="bpjs_ketenagakerjaan_ya" name="bpjs_ketenagakerjaan"
                                                            class="form-check-input" value="on"
                                                            @if (old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) == 'on') checked @else @endif>
                                                        <label class="form-check-label"
                                                            for="bpjs_ketenagakerjaan_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="bpjs_ketenagakerjaan_tidak" name="bpjs_ketenagakerjaan"
                                                            class="form-check-input" value="off"
                                                            @if (old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) == 'off') checked @else @endif>
                                                        <label class="form-check-label"
                                                            for="bpjs_ketenagakerjaan_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_bpjs_ketenagakerjaan" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('nama_pemilik_bpjs_ketenagakerjaan') is-invalid @enderror"
                                                    placeholder="Nama Pemilik BPJS Ketenagakerjaan" type="text"
                                                    id="nama_pemilik_bpjs_ketenagakerjaan"
                                                    name="nama_pemilik_bpjs_ketenagakerjaan"
                                                    value="{{ old('nama_pemilik_bpjs_ketenagakerjaan', $karyawan->nama_pemilik_bpjs_ketenagakerjaan) }}"
                                                    autofocus />
                                                <label for="nama_pemilik_bpjs_ketenagakerjaan">Nama Pemilik BPJS
                                                    Ketenagakerjaan</label>
                                            </div>
                                            @error('nama_pemilik_bpjs_ketenagakerjaan')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('no_bpjs_ketenagakerjaan') is-invalid @enderror"
                                                    type="number" placeholder="No. BPJS Ketenagakerjaan"
                                                    id="no_bpjs_ketenagakerjaan" name="no_bpjs_ketenagakerjaan"
                                                    value="{{ old('no_bpjs_ketenagakerjaan', $karyawan->no_bpjs_ketenagakerjaan) }}"
                                                    autofocus />
                                                <label for="no_bpjs_ketenagakerjaan">No. BPJS Ketenagakerjaan</label>
                                            </div>
                                            @error('no_bpjs_ketenagakerjaan')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_pensiun_ya">BPJS
                                                Pensiun</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="bpjs_pensiun_ya" name="bpjs_pensiun"
                                                            class="form-check-input" value="on"
                                                            @if (old('bpjs_pensiun', $karyawan->bpjs_pensiun) == 'on') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_pensiun_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="bpjs_pensiun_tidak" name="bpjs_pensiun"
                                                            class="form-check-input" value="off"
                                                            @if (old('bpjs_pensiun', $karyawan->bpjs_pensiun) == 'off') checked @else @endif>
                                                        <label class="form-check-label"
                                                            for="bpjs_pensiun_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_kesehatan">BPJS
                                                Kesehatan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="bpjs_kesehatan_ya" name="bpjs_kesehatan"
                                                            class="form-check-input" value="on"
                                                            @if (old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) == 'on') checked @else @endif>
                                                        <label class="form-check-label"
                                                            for="bpjs_kesehatan_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio"
                                                            id="bpjs_kesehatan_tidak" name="bpjs_kesehatan"
                                                            class="form-check-input" value="off"
                                                            @if (old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) == 'off') checked @else @endif>
                                                        <label class="form-check-label"
                                                            for="bpjs_kesehatan_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_bpjs_kesehatan" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('nama_pemilik_bpjs_kesehatan') is-invalid @enderror"
                                                    type="text" id="nama_pemilik_bpjs_kesehatan"
                                                    placeholder="Nama Pemilik BPJS Kesehatan"
                                                    name="nama_pemilik_bpjs_kesehatan"
                                                    value="{{ old('nama_pemilik_bpjs_kesehatan', $karyawan->nama_pemilik_bpjs_kesehatan) }}"
                                                    autofocus />
                                                <label for="nama_pemilik_bpjs_kesehatan">Nama Pemilik BPJS
                                                    Kesehatan</label>
                                            </div>
                                            @error('nama_pemilik_bpjs_kesehatan')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"
                                                    class="form-control @error('no_bpjs_kesehatan') is-invalid @enderror"
                                                    type="number" id="no_bpjs_kesehatan" name="no_bpjs_kesehatan"
                                                    value="{{ old('no_bpjs_kesehatan', $karyawan->no_bpjs_kesehatan) }}"
                                                    autofocus />
                                                <label for="no_bpjs_kesehatan">No. BPJS Kesehatan</label>
                                            </div>
                                            @error('no_bpjs_kesehatan')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="row_kelas_bpjs" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('kelas_bpjs') is-invalid @enderror"
                                                    id="kelas_bpjs" name="kelas_bpjs"
                                                    value="{{ old('kelas_bpjs', $karyawan->kelas_bpjs) }}">
                                                    <option @if (old('kelas_bpjs', $karyawan->kelas_bpjs) == '') selected @else @endif
                                                        value="">Pilih Kelas BPJS</option>
                                                    <option @if (old('kelas_bpjs', $karyawan->kelas_bpjs) == 'Kelas 1') selected @else @endif
                                                        value="Kelas 1">Kelas 1</option>
                                                    <option @if (old('kelas_bpjs', $karyawan->kelas_bpjs) == 'Kelas 2') selected @else @endif
                                                        value="Kelas 2">Kelas 2</option>
                                                    <option @if (old('kelas_bpjs', $karyawan->kelas_bpjs) == 'Kelas 3') selected @else @endif
                                                        value="Kelas 3">Kelas 3</option>
                                                </select>
                                                <label for="kelas_bpjs">Kelas BPJS</label>
                                            </div>
                                            @error('kelas_bpjs')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_dokumen" role="tabpanel">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="{{ asset('admin/assets/img/avatars/cv.png') }}"
                                                alt="user-avatar" class="d-block w-px-120 h-px-120 rounded"
                                                id="template_foto_karyawan1" />

                                            <div class="button-wrapper">
                                                <label for="file_cv" class="btn btn-danger me-2 mb-3" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload File CV</span>
                                                    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                                    <input style="font-size: small;" type="hidden"
                                                        name="file_cv_lama" value="{{ $karyawan->file_cv }}">
                                                    <input style="font-size: small;" type="file" name="file_cv"
                                                        id="file_cv" class="account-file-input" hidden
                                                        accept=".doc, .docx,.pdf" />
                                                </label>
                                                <button type="button" id="btn_modal_lihat" data-bs-toggle="modal"
                                                    data-bs-target="#modal_cv"
                                                    class="btn_modal_lihat btn btn-info me-2 mb-3">Lihat</button>

                                                <div class="text-muted small">Allowed PDF, DOC or DOCX. Max size of 5
                                                    MB</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                    <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('/hrd/karyawan/' . $holding) }}@else{{ url('/karyawan/' . $holding) }} @endif"
                                        type="button" class="btn btn-outline-secondary">Kembali</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // simpan posisi sebelum reload
        window.addEventListener("beforeunload", function() {
            localStorage.setItem("scrollPos", window.scrollY);
        });

        // balikin posisi setelah reload
        window.addEventListener("load", function() {
            let scrollPos = localStorage.getItem("scrollPos");
            if (scrollPos) {
                window.scrollTo(0, scrollPos);
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // simpan tab terakhir yang dibuka
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tab) {
                tab.addEventListener("shown.bs.tab", function(e) {
                    localStorage.setItem("activeTab", e.target.getAttribute("data-bs-target"));
                });
            });

            // balikin tab terakhir setelah reload
            let activeTab = localStorage.getItem("activeTab");
            if (activeTab) {
                let tabTriggerEl = document.querySelector(`button[data-bs-target="${activeTab}"]`);
                if (tabTriggerEl) {
                    new bootstrap.Tab(tabTriggerEl).show();

                    // kalau di tab restore ada input, baru kasih fokus di sini
                    setTimeout(() => {
                        let input = document.querySelector(`${activeTab} input`);
                        if (input) input.focus();
                    }, 300);
                }
            }
        });
        $('#id_provinsi').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Provinsi",
            allowClear: true
        });

        $('#id_kabupaten').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kabupaten",
            allowClear: true
        });
        $('#id_kecamatan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kecamatan",
            allowClear: true
        });
        $('#id_desa').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Desa",
            allowClear: true
        });
        $('#id_provinsi_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Provinsi Domisili",
            allowClear: true
        });
        $('#id_kabupaten_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kabupaten Domisili",
            allowClear: true
        });
        $('#id_kecamatan_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Kecamatan Domisili",
            allowClear: true
        });
        $('#id_desa_domisili').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Desa Domisili",
            allowClear: true
        });
        $('#penempatan_kerja').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Penempatan Kerja",
            allowClear: true
        });
        $('#approval_site').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Approval Job",
            allowClear: true
        });
        $('#id_departemen').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Departemen",
            allowClear: true
        });
        $('#id_divisi').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Divisi",
            allowClear: true
        });
        $('#id_jabatan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $('#id_bagian').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Bagian",
            allowClear: true
        });




        // BUTTON FOTO
        var foto_karyawan = '{{ $karyawan->foto_karyawan }}';
        if (foto_karyawan == null) {
            $('#group-button-foto').empty();
            $('#group-button-foto').append('<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>');
        } else {
            $('#group-button-foto').empty();
            $('#group-button-foto').append('<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>');

        }
        $(document).on('click', '#btn_edit_foto', function() {
            $('#foto_karyawan').click();
        });
        $('#foto_karyawan').change(function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#template_foto_karyawan').attr('src', e.target.result);
            };
            // $('#group-button-foto').empty();
            $('#group-button-foto')
                .empty()
                .append('<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>')
                .append('<button type="button" id="btn_reset_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-reload text-danger"></i><span class="text-danger">&nbsp;Reset</span></button>');
            reader.readAsDataURL(file);
        });
        $(document).on('click', '#btn_reset_foto', function() {
            $('#foto_karyawan').val('');
            $('#template_foto_karyawan').attr('src', '{{asset("storage/foto_karyawan/default_profil.jpg")}}');
            $('#group-button-foto')
                .empty()
                .append('<button type="button" id="btn_edit_foto" class="btn btn-sm me-2 mb-3"><i class="mdi mdi-pencil-outline text-primary"></i><span class="text-primary">&nbsp;Edit Foto</span></button>');
        });
        // BUTTON PENDIDIKAN
        $('#btn_add_pendidikan').click(function() {
            $(this).find(':focus').blur(); // lepas focus dari elemen apapun di modal
            $('#nama_instansi').val('');
            $('#jurusan').val('');
            $('#jenjang').val('');
            $('#tahun_masuk').val('');
            $('#tahun_keluar').val('');
            $('#modal_add_pendidikan').modal('show');
        });
        $('#btn_add_keahlian').click(function() {
            $(this).find(':focus').blur(); // lepas focus dari elemen apapun di modal
            $('#nama_keahlian').val('');
            $('#file_keahlian').val('');
            $('.group-button-keahlian').empty();
            $('#modal_add_keahlian').modal('show');
        });
        var table = $('#table_pendidikan').DataTable({
            pageLength: 50,
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "@if (Auth::user()->is_admin == 'hrd') {{ url('hrd/karyawan/pendidikan/' . $karyawan->id) }}@else{{ url('karyawan/pendidikan/' . $karyawan->id) }}@endif",
            },
            columns: [{
                    data: "aksi",
                    name: "aksi"
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'institusi',
                    name: 'institusi'
                },
                {
                    data: 'jenjang',
                    name: 'jenjang'
                },
                {
                    data: 'jurusan',
                    name: 'jurusan'
                },
                {
                    data: 'tanggal_masuk',
                    name: 'tanggal_masuk'
                },
                {
                    data: 'tanggal_keluar',
                    name: 'tanggal_keluar'
                }
            ],
            order: [
                [2, 'asc']
            ]
        });
        var table1 = $('#table_keahlian').DataTable({
            pageLength: 50,
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "@if (Auth::user()->is_admin == 'hrd') {{ url('hrd/karyawan/keahlian/' . $karyawan->id) }}@else{{ url('karyawan/keahlian/' . $karyawan->id) }}@endif",
            },
            columns: [{
                    data: "aksi",
                    name: "aksi"
                },
                {
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'keahlian',
                    name: 'keahlian'
                },
                {
                    data: 'file',
                    name: 'file'
                }
            ],
            order: [
                [2, 'asc']
            ]
        });
        $('#btn_simpan_pendidikan').click(function() {

            var id_karyawan = $('#id_karyawan').val();
            var jenjang = $('#jenjang').val();
            var jurusan = $('#jurusan').val();
            var tahun_masuk = $('#tahun_masuk').val();
            var tahun_lulus = $('#tahun_lulus').val();
            var nama_instansi = $('#nama_instansi').val();
            // console.log(id_karyawan, jenjang, jurusan, tahun_masuk, tahun_lulus, nama_instansi);
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/AddPendidikan') }}@else{{ url('karyawan/AddPendidikan') }}@endif",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id_karyawan: id_karyawan,
                    jenjang: jenjang,
                    jurusan: jurusan,
                    tahun_masuk: tahun_masuk,
                    tahun_lulus: tahun_lulus,
                    nama_instansi: nama_instansi
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.code == 200) {
                        $('#modal_add_pendidikan').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                        })
                    }
                    $('#form_add_pendidikan').trigger('reset');
                    table.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_add_pendidikan').trigger('reset');
                    $('#modal_add_pendidikan').modal('hide');
                    let errors = '';
                    $.each(data.responseJSON.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                    });

                }
            });

        });
        $(document).on('click', '.btn_edit_pendidikan', function() {
            console.log($(this).data());
            var id_pendidikan = $(this).data('id_pendidikan');
            var jenjang = $(this).data('jenjang');
            var nama_instansi = $(this).data('nama_instansi');
            var jurusan = $(this).data('jurusan');
            var tahun_masuk = $(this).data('tahun_masuk');
            var tahun_lulus = $(this).data('tahun_lulus');
            $('#id_pendidikan').val(id_pendidikan);
            $('#jenjang_update').val(jenjang);
            $('#nama_instansi_update').val(nama_instansi);
            $('#jurusan_update').val(jurusan);
            $('#tahun_masuk_update').val(tahun_masuk);
            $('#tahun_lulus_update').val(tahun_lulus);
            $('#modal_edit_pendidikan').modal('show');
        });
        $('#btn_simpan_edit_pendidikan').click(function() {
            var id_pendidikan = $('#id_pendidikan').val();
            var id_karyawan = $('#id_karyawan').val();
            var jenjang = $('#jenjang_update').val();
            var jurusan = $('#jurusan_update').val();
            var tahun_masuk = $('#tahun_masuk_update').val();
            var tahun_lulus = $('#tahun_lulus_update').val();
            var nama_instansi = $('#nama_instansi_update').val();
            // console.log(id_karyawan, jenjang, jurusan, tahun_masuk, tahun_lulus, nama_instansi);
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/UpdatePendidikan') }}@else{{ url('karyawan/UpdatePendidikan') }}@endif",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id_pendidikan: id_pendidikan,
                    id_karyawan: id_karyawan,
                    jenjang: jenjang,
                    jurusan: jurusan,
                    tahun_masuk: tahun_masuk,
                    tahun_lulus: tahun_lulus,
                    nama_instansi: nama_instansi
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    $('#form_add_pendidikan').trigger('reset');
                    $('#modal_edit_pendidikan').modal('hide');
                    if (response.code == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 4000,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                            timer: 4000,
                        })
                    }
                    table.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_edit_pendidikan').trigger('reset');
                    $('#modal_edit_pendidikan').modal('hide');
                    let errors = '';
                    $.each(data.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                        timer: 4000,
                    });

                }
            });

        });
        $(document).on('click', '#btn_delete_pendidikan', function() {
            let id = $(this).data('id'); // ambil id dari tombol

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data pendidikan akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) { // v9 pakai result.value, bukan result.isConfirmed
                    $.ajax({
                        url: "{{ url('karyawan/DeletePendidikan') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_pendidikan: id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon tunggu...',
                                text: 'Sedang menghapus data',
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                table.ajax.reload(); // reload datatable
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server'
                            });
                        }
                    });
                }
            });
        });
        $('#btn_upload_keahlian').click(function() {
            $('#file_keahlian').click();
        });
        $('#file_keahlian').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-button-keahlian').empty();
            $('.group-button-keahlian').append('<a href="' + fileURL + '" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-eye"></i></a>')
                .append('<button type="button" id="btn_reset_keahlian" class="btn btn-sm btn-danger"><i class="mdi mdi-refresh"></i></button>');
        });
        $(document).on('click', '#btn_reset_keahlian', function() {
            // console.log('reset');
            $('#file_keahlian').val('');
            $('.group-button-keahlian').empty();
        });
        $(document).on('click', '#btn_reset_keahlian_update', function() {
            // console.log('reset');
            $('#file_keahlian_update').val('');
            $('.group-update-keahlian').empty();
            $('.group-update-keahlian').append('<button type="button" id="btn_upload_keahlian_update" class="btn btn-sm btn-secondary"><i class="mdi mdi-upload"></i> Upload</button><p class="text-primary">format: PDF</p>');

        });
        $(document).on('click', '#btn_upload_keahlian_update', function() {
            // console.log('upload');
            $('#file_keahlian_update').click();
        });
        $(document).on('click', '.btn_edit_keahlian', function() {
            console.log($(this).data());
            var id_keahlian = $(this).data('id_keahlian');
            var keahlian = $(this).data('keahlian');
            var file_keahlian = $(this).data('file_keahlian');
            var file_url = $(this).data('file_url');
            $('#id_keahlian').val(id_keahlian);
            $('#nama_keahlian_update').val(keahlian);
            console.log(file_keahlian.length);
            if (file_keahlian.length != 0) {
                console.log('ya');
                $('#file_keahlian_old_update').val(file_keahlian);
                $('.group-update-keahlian').empty();
                $('.group-update-keahlian').append('<a href="' + file_url + '" target="_blank" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>')
                    .append('<button type="button" id="btn_reset_keahlian_update" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>');
            } else {
                console.log('ok');
                $('.group-update-keahlian').empty();
                $('.group-update-keahlian').append('<button type="button" id="btn_upload_keahlian_update" class="btn btn-sm btn-secondary"><i class="mdi mdi-upload"></i> Upload</button>');

            }
            $('#modal_edit_keahlian').modal('show');
        });
        $('#file_keahlian_update').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-update-keahlian').empty();
            $('.group-update-keahlian').append('<a href="' + fileURL + '" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-eye"></i></a>')
                .append('<button type="button" id="btn_reset_keahlian" class="btn btn-sm btn-danger"><i class="mdi mdi-refresh"></i></button>');
        });
        $('#btn_simpan_keahlian').click(function() {
            var id_karyawan = $('#id_karyawan').val();
            var nama_keahlian = $('#nama_keahlian').val();
            var file_keahlian = $('#file_keahlian')[0].files[0];

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id_karyawan', id_karyawan);
            formData.append('nama_keahlian', nama_keahlian);
            if (file_keahlian) {
                formData.append('file_keahlian', file_keahlian);
            } else {
                formData.append('file_keahlian', null);
            }
            // console.log(id_karyawan, nama_keahlian, file_keahlian);
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/AddKeahlian') }}@else{{ url('karyawan/AddKeahlian') }}@endif",
                type: 'POST',
                data: formData,
                processData: false, // WAJIB
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.code == 200) {
                        $('#modal_add_keahlian').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                        })
                    }

                    $('#form_add_keahlian').trigger('reset');

                    table1.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_add_keahlian').trigger('reset');
                    $('#modal_add_keahlian').modal('hide');
                    let errors = '';
                    $.each(data.responseJSON.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                    });

                }
            });
        });
        $('#btn_simpan_edit_keahlian').click(function() {
            var id_karyawan = $('#id_karyawan').val();
            var id_keahlian = $('#id_keahlian').val();
            var nama_keahlian = $('#nama_keahlian_update').val();
            var file_keahlian = $('#file_keahlian_update')[0].files[0];
            var file_keahlian_old = $('#file_keahlian_old_update').val();

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id_karyawan', id_karyawan);
            formData.append('id_keahlian', id_keahlian);
            formData.append('nama_keahlian', nama_keahlian);
            formData.append('file_keahlian_old', file_keahlian_old);
            console.log(file_keahlian);
            if (file_keahlian) {
                formData.append('file_keahlian', file_keahlian);
            } else {
                formData.append('file_keahlian', null);
            }
            // console.log(id_karyawan, nama_keahlian, file_keahlian);
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/UpdateKeahlian') }}@else{{ url('karyawan/UpdateKeahlian') }}@endif",
                type: 'POST',
                data: formData,
                processData: false, // WAJIB
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon tunggu...',
                        text: 'Sedang memproses data',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    if (response.code == 200) {
                        $('#modal_edit_keahlian').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                    } else {
                        let errors = '';
                        $.each(response.message, function(key, value) {
                            errors += value.join('<br>') + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errors,
                        })
                    }

                    $('#form_edit_keahlian').trigger('reset');

                    table1.ajax.reload();
                },
                error: function(data) {
                    Swal.close();
                    console.log(data);
                    $('#form_edit_keahlian').trigger('reset');
                    $('#modal_edit_keahlian').modal('hide');
                    let errors = '';
                    $.each(data.responseJSON.message, function(key, value) {
                        errors += value.join('<br>') + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errors,
                    });

                }
            });
        });
        $(document).on('click', '#btn_delete_keahlian', function() {
            let id = $(this).data('id'); // ambil id dari tombol

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data keahlian akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) { // v9 pakai result.value, bukan result.isConfirmed
                    $.ajax({
                        url: "{{ url('karyawan/DeleteKeahlian') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_keahlian: id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon tunggu...',
                                text: 'Sedang menghapus data',
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                table1.ajax.reload(); // reload datatable
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server'
                            });
                        }
                    });
                }
            });
        });

        // IJAZAH
        var file_ijazah = '{{ $karyawan->ijazah }}';
        var url_ijazah = "{{ asset('storage/ijazah') }}";
        $(document).on('click', '#btn_change_ijazah', function() {
            $('#file_ijazah').click();
            console.log('change ijazah');
        });
        $(document).on('click', '#btn_upload_ijazah', function() {
            $('#file_ijazah').click();
            // console.log('upload ijazah');
        });
        $('#file_ijazah').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-button-ijazah').empty();
            $('.group-button-ijazah').append('<a href="' + fileURL + '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>')
                .append('<button type="button" id="btn_reset_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-refresh"></i><span class="text-primary">&nbsp;Reset</span></button>');
        });
        $(document).on('click', '#btn_reset_ijazah', function() {
            $('#file_ijazah').val('');
            if (file_ijazah == '') {
                $('.group-button-ijazah').empty();
                $('.group-button-ijazah').append('<button type="button" id="btn_upload_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Ganti</span></button>');
            } else {
                $('.group-button-ijazah').empty();
                $('.group-button-ijazah').append('<a href="' + url_ijazah + '/' + file_ijazah + '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>')
                    .append('<button type="button" id="btn_change_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-pencil"></i><span class="text-primary">&nbsp;Ganti</span></button>')
                    .append('<button type="button" id="btn_delete_file_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-delete"></i><span class="text-primary">&nbsp;Hapus</span></button>');
            }
        });
        $(document).on('click', '#btn_delete_file_ijazah', function() {
            $('#file_ijazah').val('');
            $('.group-button-ijazah').empty();
            $('.group-button-ijazah').append('<button type="button" id="btn_upload_ijazah" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Upload</span></button>');
        });

        // TRANSKRIP NILAI
        var file_transkrip_nilai = '{{ $karyawan->transkrip_nilai }}';
        var url_transkrip_nilai = "{{ asset('storage/transkrip_nilai') }}";
        $(document).on('click', '#btn_change_transkrip_nilai', function() {
            $('#file_transkrip_nilai').click();
            // console.log('change transkrip nilai');
        });
        $(document).on('click', '#btn_upload_transkrip_nilai', function() {
            $('#file_transkrip_nilai').click();
            // console.log('upload transkrip nilai');
        });
        $('#file_transkrip_nilai').change(function() {
            var file = this.files[0];
            if (!file) return;
            var fileURL = URL.createObjectURL(file);
            // $(this).val(file);
            $('.group-button-transkrip_nilai').empty();
            $('.group-button-transkrip_nilai').append('<a href="' + fileURL + '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>')
                .append('<button type="button" id="btn_reset_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-refresh"></i><span class="text-primary">&nbsp;Reset</span></button>');
        });
        $(document).on('click', '#btn_reset_transkrip_nilai', function() {
            $('#file_transkrip_nilai').val('');
            if (file_transkrip_nilai == '') {
                $('.group-button-transkrip_nilai').empty();
                $('.group-button-transkrip_nilai').append('<button type="button" id="btn_upload_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Ganti</span></button>');
            } else {
                $('.group-button-transkrip_nilai').empty();
                $('.group-button-transkrip_nilai').append('<a href="' + url_transkrip_nilai + '/' + file_transkrip_nilai + '" target="_blank" class="btn btn-sm bottom-0"><i class="mdi mdi-eye"></i><span class="text-primary">&nbsp;Lihat File</span></a>')
                    .append('<button type="button" id="btn_change_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-pencil"></i><span class="text-primary">&nbsp;Ganti</span></button>')
                    .append('<button type="button" id="btn_delete_file_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-delete"></i><span class="text-primary">&nbsp;Hapus</span></button>');
            }
        });
        $(document).on('click', '#btn_delete_file_transkrip_nilai', function() {
            $('#file_transkrip_nilai').val('');
            console.log('File transkrip nilai dihapus');
            $('.group-button-transkrip_nilai').empty();
            $('.group-button-transkrip_nilai').append('<button type="button" id="btn_upload_transkrip_nilai" class="btn btn-sm bottom-0"><i class="mdi mdi-upload"></i><span class="text-primary">&nbsp;Upload</span></button>');
        });
    });


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
        var kategori = '{{ $karyawan->kategori }}';
        if (kategori == 'Karyawan Harian') {
            $('#form_departemen').hide();
            $('#form_divisi').hide();
            $('#form_jabatan_more').hide();
            $('#form_jabatan').hide();
            $('#form_lama_kotrak').hide();
            $('#form_bagian').hide();
            $('#form_kontrak').hide();
            $('#form_tgl_kontrak_kerja').hide();
            // $('#form_level').hide();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_site').hide();
            $('#form_lama_kontrak').hide();
        } else {

            $('#form_departemen').show();
            $('#form_divisi').show();
            $('#form_jabatan_more').show();
            $('#form_jabatan').show();
            $('#form_lama_kotrak').show();
            $('#form_bagian').show();
            $('#form_kontrak').show();
            $('#form_tgl_kontrak_kerja').show();
            // $('#form_level').show();
            $('#form_lama_kontrak').show();
            $('#form_tgl_mulai_kontrak').show();
            $('#form_tgl_selesai_kontrak').show();
            $('#form_site').show();
        }
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
                // $('#form_level').hide();
                $('#form_tgl_mulai_kontrak').hide();
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').hide();
                $('#form_lama_kontrak').hide();
                $('#form_kuota_cuti').hide();
            } else if (id == 'Karyawan Bulanan') {
                let lama = $('#lama_kontrak_kerja').val();
                // console.log(lama);
                if (lama == 'tetap') {
                    $('#form_tgl_mulai_kontrak').show();
                    $('#form_kuota_cuti').show();
                } else if (lama == '') {
                    $('#form_tgl_mulai_kontrak').hide();
                    $('#form_tgl_selesai_kontrak').hide();
                    $('#form_kuota_cuti').hide();
                } else {
                    $('#form_tgl_mulai_kontrak').show();
                    $('#form_tgl_selesai_kontrak').show();
                    $('#form_kuota_cuti').show();
                }
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
                // $('#form_tgl_mulai_kontrak').hide();
                // $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').show();
                // $('#form_kuota_cuti').hide();
            }
        });
        $('#btn_upload_ktp').on('click', function() {
            $('#ktp').click();
        });
        $('#ktp').on('change', function() {
            let file = $(this).val();
            if (file) {
                $('#btn_upload_ktp').hide();
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

    });
    $(function() {

        $('#atasan').on('change', function() {
            let id = $('#id_jabatan').val();
            let divisi = $('#id_divisi').val();
            let id_karyawan = $('#id_karyawan').val();
            let holding = '{{ $holding }}';
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/atasan2/get_jabatan') }}@else{{ url('karyawan/atasan2/get_jabatan') }}@endif" +
                "/" + holding;
            // console.log(divisi);
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
                    title: 'Nomor Rekening harus ' + bankdigit +
                        ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });


        $('#id_provinsi').on('change', function() {
            let id_provinsi = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kabupaten') }}@else{{ url('/karyawan/get_kabupaten') }}@endif" +
                "/" + id_provinsi;
            // console.log(id_provinsi);
            // console.log(url);
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
                    $('#id_kecamatan').html('<option value="">Pilih Kecamatan</option>');
                    $('#id_desa').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten').on('change', function() {
            let id_kabupaten = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kecamatan') }}@else{{ url('/karyawan/get_kecamatan') }}@endif" +
                "/" + id_kabupaten;
            // console.log(id_kabupaten);
            // console.log(url);
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
                    $('#id_desa').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan').on('change', function() {
            let id_kecamatan = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_desa') }}@else{{ url('/karyawan/get_desa') }}@endif" +
                "/" + id_kecamatan;
            // console.log(id_kecamatan);
            // console.log(url);
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
            let id_provinsi = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kabupaten') }}@else{{ url('/karyawan/get_kabupaten') }}@endif" +
                "/" + id_provinsi;
            // console.log(id_provinsi);
            // console.log(url);
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
                    $('#id_kecamatan_domisili').html('<option value="">Pilih Kecamatan</option>');
                    $('#id_desa_domisili').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten_domisili').on('change', function() {
            let id_kabupaten = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_kecamatan') }}@else{{ url('/karyawan/get_kecamatan') }}@endif" +
                "/" + id_kabupaten;
            // console.log(id_kabupaten);
            // console.log(url);
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
                    $('#id_kecamatan_domisili').html(response);
                    $('#id_desa_domisili').html('<option value="">Pilih Desa</option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan_domisili').on('change', function() {
            let id_kecamatan = $(this).val();
            let url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/get_desa') }}@else{{ url('/karyawan/get_desa') }}@endif" +
                "/" + id_kecamatan;
            // console.log(id_kecamatan);
            // console.log(url);
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
    var status_nomor = "{{ old('status_nomor', $karyawan->status_nomor) }}";
    var status_bpjs_ketenagakerjaan = "{{ old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) }}";
    var status_bpjs_kesehatan = "{{ old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) }}";
    var pilih_domisili_alamat = "{{ old('pilihan_alamat_domisili', $karyawan->status_alamat) }}";
    var status_npwp = "{{ old('status_npwp', $karyawan->status_npwp) }}";
    // console.log(status_bpjs_ketenagakerjaan);
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
    $(document).on("click", "#status_npwp_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_npwp').show();
        } else {
            $('#row_npwp').hide();

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
    var file_cv = '{{ $karyawan->file_cv }}';
    if (file_cv == '') {
        // console.log('ok');
        $('#btn_modal_lihat').hide();
    } else {
        // console.log('ok1');
        $('#btn_modal_lihat').show();
    }
    $('#file_cv').change(function() {


        let reader = new FileReader();
        // console.log(reader);
        reader.onload = (e) => {
            $('#lihat_file_cv').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
    $('#row_kategori_jabatan').hide();
    if ($('#site_job').val() == 'ALL SITES (SP, SPS, SIP)') {
        $('#row_kategori_jabatan').show();
    }
    $(document).on("change", "#site_job", function() {
        var id = $(this).val();
        if (id == 'ALL SITES (SP, SPS, SIP)') {
            $('#row_kategori_jabatan').show();
            var holding = $('#kategori_jabatan').val();
            // console.log(holding);
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
            var holding = '{{ $holding }}';
        }
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
            data: {
                holding: holding,
            },
            cache: false,

            success: function(msg) {
                console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_departemen').html(msg);
                $('#id_departemen1').html(msg);
                $('#id_departemen2').html(msg);
                $('#id_departemen3').html(msg);
                $('#id_departemen4').html(msg);
                $('#id_divisi').html('<option value=""></option>');
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
                $('#id_divisi1').html('<option value=""></option>');
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
                $('#id_divisi2').html('<option value=""></option>');
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');
                $('#id_divisi3').html('<option value=""></option>');
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');
                $('#id_divisi4').html('<option value=""></option>');
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        // console.log($(this).val());
    });
    $(document).on("click", "#kategori_jabatan_sp", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sp') {
            $('#kategori_jabatan').val(holding);
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
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
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
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
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sip", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sip') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
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
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $('#id_departemen').on('change', function() {
        let id_departemen = $('#id_departemen').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_divisi') }}@else{{ url('karyawan/get_divisi') }}@endif" + '/' + id_departemen,
            cache: false,

            success: function(msg) {
                $('#id_divisi').html(msg);
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen1').on('change', function() {
        let id_departemen = $('#id_departemen1').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_divisi') }}@else{{ url('karyawan/get_divisi') }}@endif" + '/' + id_departemen,
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
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_divisi') }}@else{{ url('karyawan/get_divisi') }}@endif" + '/' + id_departemen,
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
    $('#id_divisi').on('change', function() {
        let id_divisi = $('#id_divisi').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_bagian') }}@else{{ url('karyawan/get_bagian') }}@endif" + '/' + id_divisi,
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
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_bagian') }}@else{{ url('karyawan/get_bagian') }}@endif" + '/' + id_divisi,
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
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_bagian') }}@else{{ url('karyawan/get_bagian') }}@endif" + '/' + id_divisi,
            cache: false,

            success: function(msg) {
                $('#id_bagian2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian').on('change', function() {
        let id_bagian = $('#id_bagian').val();
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_jabatan') }}@else{{ url('karyawan/get_jabatan') }}@endif" + '/' + id_bagian,
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
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_jabatan') }}@else{{ url('karyawan/get_jabatan') }}@endif" + '/' + id_bagian,
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
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_jabatan') }}@else{{ url('karyawan/get_jabatan') }}@endif" + '/' + id_bagian,
            cache: false,

            success: function(msg) {
                $('#id_jabatan2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_jabatan').on('change', function() {
        let id = $(this).val();
        let id_karyawan = $('#id_karyawan').val();
        let divisi = $('#id_divisi').val();
        let holding = '{{ $holding }}';
        let url =
            "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/atasan/get_jabatan') }}@else{{ url('karyawan/atasan/get_jabatan') }}@endif" +
            "/" + holding + "/" + divisi;
        // console.log(divisi);
        // console.log(holding);
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
                $('#atasan').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
<script>
    $('#row_bpjs_ketenagakerjaan').hide();
    $('#row_bpjs_kesehatan').hide();
    $('#row_kelas_bpjs').show();
    var status_nomor = "{{ old('status_nomor', $karyawan->status_nomor) }}";
    var status_bpjs_ketenagakerjaan = "{{ old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) }}";
    var status_bpjs_kesehatan = "{{ old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) }}";
    var pilih_domisili_alamat = "{{ old('pilihan_alamat_domisili', $karyawan->status_alamat) }}";
    var status_npwp = "{{ old('status_npwp', $karyawan->status_npwp) }}";
    // console.log(status_bpjs_ketenagakerjaan);
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
    $(document).on("click", "#status_npwp_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_npwp').show();
        } else {
            $('#row_npwp').hide();

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
            console.log('ok');
            $('#id_provinsi_domisili option:selected').prop('selected', false);
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
    var file_cv = '{{ $karyawan->file_cv }}';
    if (file_cv == '') {
        // console.log('ok');
        $('#btn_modal_lihat').hide();
    } else {
        // console.log('ok1');
        $('#btn_modal_lihat').show();
    }
    $('#file_cv').change(function() {


        let reader = new FileReader();
        // console.log(reader);
        reader.onload = (e) => {
            $('#lihat_file_cv').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
    $('#row_kategori_jabatan').hide();
    if ($('#site_job').val() == 'ALL SITES (SP, SPS, SIP)') {
        $('#row_kategori_jabatan').show();
    }
    $(document).on("change", "#site_job", function() {
        var id = $(this).val();
        if (id == 'ALL SITES (SP, SPS, SIP)') {
            $('#row_kategori_jabatan').show();
            var holding = $('#kategori_jabatan').val();
            // console.log(holding);
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
            var holding = '{{ $holding }}';
        }
        $.ajax({
            type: 'GET',
            url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
            data: {
                holding: holding,
            },
            cache: false,

            success: function(msg) {
                console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_departemen').html(msg);
                $('#id_departemen1').html(msg);
                $('#id_departemen2').html(msg);
                $('#id_departemen3').html(msg);
                $('#id_departemen4').html(msg);
                $('#id_divisi').html('<option value=""></option>');
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
                $('#id_divisi1').html('<option value=""></option>');
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
                $('#id_divisi2').html('<option value=""></option>');
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');
                $('#id_divisi3').html('<option value=""></option>');
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');
                $('#id_divisi4').html('<option value=""></option>');
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        // console.log($(this).val());
    });
    $(document).on("click", "#kategori_jabatan_sp", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sp') {
            $('#kategori_jabatan').val(holding);
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
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
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
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
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sip", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sip') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/karyawan/get_departemen') }}@else{{ url('karyawan/get_departemen') }}@endif",
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
                    $('#id_divisi').html('<option value=""></option>');
                    $('#id_bagian').html('<option value=""></option>');
                    $('#id_jabatan').html('<option value=""></option>');
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
</script>
<script>
    $(document).on("click", "#btndetail_karyawan", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        // console.log(id);
        let url =
            "@if (Auth::user()->is_admin == 'hrd'){{ url('/hrd/karyawan/detail/') }}@else{{ url('/karyawan/detail/') }}@endif" +
            '/' + id + '/' + holding;
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
                window.location.assign(url);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
@endsection