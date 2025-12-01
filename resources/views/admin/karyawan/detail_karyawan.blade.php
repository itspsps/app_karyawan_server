@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .swal2-container {
            z-index: 9999;
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
                                    <button id="pendidikan_nav" type="button" class="nav-link" role="tab"
                                        data-bs-toggle="tab" data-bs-target="#nav_pendidikan" aria-controls="nav_pendidikan"
                                        aria-selected="false">
                                        <i class="tf-icons mdi mdi-account-school-outline me-1"></i>
                                        PENDIDIKAN
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
                                {{-- <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#nav_dokumen" aria-controls="nav_dokumen"
                                            aria-selected="false">
                                            <i class="tf-icons mdi mdi-file-document-multiple-outline me-1"></i>
                                            DOKUMEN
                                        </button>
                                    </li> --}}
                                <li class="nav-item">
                                    <button id="keahlian_nav" type="button" class="nav-link" role="tab"
                                        data-bs-toggle="tab" data-bs-target="#nav_dokumen" aria-controls="nav_dokumen"
                                        aria-selected="false">
                                        <i class="mdi mdi-hammer-screwdriver me-1"></i>
                                        KEAHLIAN
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button id="pengalaman_nav" type="button" class="nav-link" role="tab"
                                        data-bs-toggle="tab" data-bs-target="#nav_riwayat" aria-controls="nav_riwayat"
                                        aria-selected="false">
                                        <i class="mdi mdi-briefcase-outline me-1"></i>
                                        PENGALAMAN
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#nav_kesehatan" aria-controls="nav_kesehatan"
                                        aria-selected="false">
                                        <i class="mdi mdi-medication-outline me-1"></i>
                                        KESEHATAN
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="nav_profile" role="tabpanel">
                                    <div class="col-md-3">
                                        <span class="mdi mdi-account-badge badge bg-label-secondary">&nbsp;Foto
                                            Profil</span>
                                    </div>
                                    <hr class="m-0">
                                    <div class="row mt-2 mb-4 gy-4">
                                        <div class="col-md-4">
                                            @if ($karyawan->foto_karyawan)
                                                <img src="{{ asset('storage/foto_karyawan/' . $karyawan->foto_karyawan) }}"
                                                    id="template_foto_karyawan" max-height="200" height="200"
                                                    class="rounded" alt="">
                                            @else
                                                <img src="{{ asset('storage/foto_karyawan/default_profil.jpg') }}"
                                                    id="template_foto_karyawan" max-height="200" height="200"
                                                    class="rounded" alt="">
                                            @endif
                                            <br>
                                            <input type="file" name="foto_karyawan" id="foto_karyawan" hidden
                                                value="" accept="image/png, image/jpeg">
                                            <input type="hidden" name="foto_karyawan_old" id="foto_karyawan_old"
                                                value="{{ $karyawan->foto_karyawan }}">
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
                                                    class="form-control @error('nik') is-invalid @enderror" type="number"
                                                    id="nik" name="nik"
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
                                            <h6>Apakah Nomor Telepon Terhubung WhatsApp ?</h6>
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
                                                    id="tgl_lahir" value="{{ old('tgl_lahir', $karyawan->tgl_lahir) }}"
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
                                                            <option value="{{ $g['gender_id'] }}">
                                                                {{ $g['gender_name'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label for="gender">Jenis Kelamin</label>
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
                                                <select style="font-size: small;" name="status_nikah" id="status_nikah"
                                                    class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($sNikah as $s)
                                                        @if (old('status_nikah', $karyawan->status_nikah) == $s['status_id'])
                                                            <option value="{{ $s['status_id'] }}" selected>
                                                                {{ $s['status_name'] }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $s['status_id'] }}">
                                                                {{ $s['status_name'] }}
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
                                                <input type="number" class="form-control" id="jumlah_anak"
                                                    name="jumlah_anak"
                                                    value="{{ old('jumlah_anak', $karyawan->jumlah_anak) }}">
                                                <label for="jumlah_anak">Jumlah Anak</label>
                                            </div>
                                            @error('jumlah_anak')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-md-6">
                                                <h5>KTP</h5>
                                                <div class="form-floating form-floating-outline">
                                                    <input type="file" hidden id="ktp" name="ktp"
                                                        value="{{ old('ktp', $karyawan->ktp) }}">
                                                    <img src="{{ $karyawan->ktp == null ? asset('images/KTP.jpg') : asset('storage/ktp/' . $karyawan->ktp) }}"
                                                        class="img-fluid" alt="" width="323" height="204">
                                                    <br>
                                                    <button type="button" id="btn_upload_ktp"
                                                        class="btn btn-sm bottom-0">
                                                        @if ($karyawan->ktp == null)
                                                            <i class="mdi mdi-upload text-primary"></i> <span
                                                                class="text-primary">Upload</span>
                                                        @else
                                                            <i class="mdi mdi-pencil text-primary"></i> <span
                                                                class="text-primary">Ganti</span>
                                                        @endif
                                                    </button>
                                                </div>
                                                @error('ktp')
                                                    <p class="alert alert-danger">{{ $message }}</p>
                                                @enderror
                                            </div> --}}
                                        <div class="col-md-6">
                                            <h5>KTP</h5>
                                            @if ($karyawan->foto_karyawan)
                                                <img src="{{ asset('storage/ktp/' . $karyawan->ktp) }}" id="template_ktp"
                                                    max-height="200" height="200" class="rounded" alt="">
                                            @else
                                                <img src="{{ asset('storage/ktp/default_ktp.jpg') }}" id="template_ktp"
                                                    max-height="200" height="200" class="rounded" alt="">
                                            @endif
                                            <br>
                                            <input type="file" name="ktp" id="ktp" hidden value=""
                                                accept="image/png, image/jpeg">
                                            <input type="hidden" name="ktp_old" id="ktp_old"
                                                value="{{ $karyawan->ktp }}">
                                            <div id="group-button-ktp"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="mdi mdi-card-account-details-outline badge bg-label-info">
                                                KTP</span>
                                        </div>
                                        <hr class="m-0">
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('provinsi') is-invalid @enderror"
                                                    id="id_provinsi" name="provinsi">
                                                    <option value="">Pilih Provinsi </option>
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
                                            <span class="mdi mdi-map-marker-check-outline badge bg-label-danger">ALAMAT
                                                DOMISILI
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
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_profil">Simpan
                                            Data</button>
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
                                                            <input type="text" name="nama_instansi" id="nama_instansi"
                                                                class="form-control" value="">
                                                            <label for="nama_instansi">Nama Instansi</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="jurusan" id="jurusan"
                                                                class="form-control" value="">
                                                            <label for="jurusan">Jurusan</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_masuk" id="tahun_masuk"
                                                                class="form-control">
                                                                <option value="">--Pilih Tahun Masuk--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_masuk">Tahun Masuk</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_lulus" id="tahun_lulus"
                                                                class="form-control">
                                                                <option value="">--Pilih Tahun Lulus--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_lulus">Tahun Lulus</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_pendidikan"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="mdi mdi-content-save"></i> Simpan</button>
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
                                                        <input type="hidden" name="id_pendidikan" id="id_pendidikan"
                                                            value="">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="jenjang_update" id="jenjang_update"
                                                                class="form-control">
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
                                                            <input type="text" name="nama_instansi_update"
                                                                id="nama_instansi_update" class="form-control"
                                                                value="">
                                                            <label for="nama_instansi_update">Nama Instansi</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="jurusan_update"
                                                                id="jurusan_update" class="form-control" value="">
                                                            <label for="jurusan_update">Jurusan</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_masuk_update" id="tahun_masuk_update"
                                                                class="form-control">
                                                                <option value="">--Pilih Tahun Masuk--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_masuk_update">Tahun Masuk</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <select name="tahun_lulus_update" id="tahun_lulus_update"
                                                                class="form-control">
                                                                <option value="">--Pilih Tahun Lulus--</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                            <label for="tahun_lulus_update">Tahun Lulus</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_edit_pendidikan"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="mdi mdi-content-save"></i> Simpan</button>
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
                                <div class="tab-pane fade" id="nav_pendidikan" role="tabpanel">
                                    <div class="row gy-4">
                                        <div class="col-md-3 mt-5">
                                            <span
                                                class="mdi mdi-account-school-outline badge bg-label-primary">&nbsp;RIWAYAT
                                                PENDIDIKAN</span>
                                        </div>
                                        <div class="row mt-2 gy-4">
                                            <div class="d-flex justify-content-center py-2">
                                                <button class="btn btn-primary" type="button"
                                                    id="btn_tambah_pendidikan">Tambah
                                                    Riwayat
                                                    Pendidikan</button>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <p class="" id="pesan_disabled_pendidikan">*Silakan
                                                    edit atau hapus data untuk
                                                    menambahkan
                                                    data lain
                                                    (max : 3)
                                                </p>
                                            </div>
                                            <table class="table table-bordered" id="table_pendidikan"
                                                style="width:100%;">
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
                                        <hr class="m-0 mb-3">
                                        <div class="row mt-2 gy-4">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" name="ipk" id="ipk"
                                                        class="form-control" value="{{ old('ipk', $karyawan->ipk) }}">
                                                    <label for="ipk">IPK</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="file" hidden name="file_ijazah" id="file_ijazah"
                                                        class="form-control" accept=".pdf" value="">
                                                    @if ($karyawan->ijazah == null)
                                                        <button type="button" id="btn_upload_ijazah"
                                                            class="btn btn-sm"><i class="mdi mdi-upload text-primary"></i>
                                                            <span class="text-primary">Upload</span></button>
                                                        <label for="file_ijazah">FILE IJAZAH</label>
                                                    @else
                                                        <h5 for="file_ijazah">File Ijazah</h5>
                                                        <input type="hidden" id="file_ijazah_old" name="file_ijazah_old"
                                                            class="form-control" value="{{ $karyawan->ijazah }}">
                                                        <div class="group-button-ijazah">
                                                            <a href="{{ asset('storage/ijazah/' . $karyawan->ijazah) }}"
                                                                target="_blank" type="button" id="btn_lihat_ijazah"
                                                                class="btn btn-sm bottom-0"><i
                                                                    class="mdi mdi-eye text-primary"></i> <span
                                                                    class="text-primary">&nbsp;Lihat File</span></a>
                                                            <button id="btn_change_ijazah" type="button"
                                                                class="btn btn-sm bottom-0"><i
                                                                    class="mdi mdi-pencil text-primary"></i> <span
                                                                    class="text-primary">Ganti</span></button>
                                                            <button type="button" id="btn_delete_file_ijazah"
                                                                class="btn btn-sm bottom-0"><i
                                                                    class="mdi mdi-delete text-primary"></i> <span
                                                                    class="text-primary">Hapus</span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="file" hidden name="file_transkrip_nilai"
                                                        id="file_transkrip_nilai" class="form-control" accept=".pdf"
                                                        value="">
                                                    @if ($karyawan->transkrip_nilai == null)
                                                        <button type="button" id="btn_upload_transkrip_nilai"
                                                            class="btn btn-sm"><i class="mdi mdi-upload text-primary"></i>
                                                            <span class="text-primary">Upload</span></button>
                                                        <label for="file_transkrip_nilai">FILE TRANSKRIP NILAI</label>
                                                    @else
                                                        <h5 for="transkrip_nilai">File Transkrip Nilai</h5>
                                                        <input type="hidden" id="transkrip_nilai_old"
                                                            name="transkrip_nilai_old" class="form-control"
                                                            value="{{ $karyawan->transkrip_nilai }}">
                                                        <div class="group-button-transkrip_nilai">
                                                            <a href="{{ asset('storage/transkrip_nilai/' . $karyawan->transkrip_nilai) }}"
                                                                target="_blank" type="button"
                                                                id="btn_lihat_transkrip_nilai"
                                                                class="btn btn-sm bottom-0"><i
                                                                    class="mdi mdi-eye text-primary"></i> <span
                                                                    class="text-primary">&nbsp;Lihat File</span></a>
                                                            <button id="btn_change_transkrip_nilai" type="button"
                                                                class="btn btn-sm bottom-0"><i
                                                                    class="mdi mdi-pencil text-primary"></i> <span
                                                                    class="text-primary">&nbsp;Ganti</span></button>
                                                            <button type="button" id="btn_delete_file_transkrip_nilai"
                                                                class="btn btn-sm bottom-0"><i
                                                                    class="mdi mdi-delete text-primary"></i> <span
                                                                    class="text-primary">&nbsp;Hapus</span></button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_doc_pend">Simpan
                                            Data Pendidikan</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_info_hr" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-3">

                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="kategori" id="kategori"
                                                    class="form-control @error('kategori') is-invalid @enderror">
                                                    <option value=""> Pilih Kategori</option>
                                                    <option value="Karyawan Bulanan"
                                                        {{ $karyawan->kategori == 'Karyawan Bulanan' ? 'selected' : '' }}>
                                                        Karyawan Bulanan</option>
                                                    <option value="Karyawan Harian"
                                                        {{ $karyawan->kategori == 'Karyawan Harian' ? 'selected' : '' }}>
                                                        Karyawan Harian</option>
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
                                                    <option value="Non Shift"
                                                        {{ $karyawan->shift == 'Non Shift' ? 'selected' : '' }}> Non
                                                        Shift</option>
                                                    <option value="Shift"
                                                        {{ $karyawan->shift == 'Shift' ? 'selected' : '' }}> Shift
                                                    </option>
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
                                                    readonly value="{{ $karyawan->KontrakKerja->holding_name }}">
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
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_hr">Simpan
                                            INFO HR</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_jabatan" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;"
                                                    class="form-control @error('penempatan_kerja') is-invalid @enderror"
                                                    id="penempatan_kerja" name="penempatan_kerja[]" multiple disabled>
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
                                            if ($karyawan->PenempatanKerja != null) {
                                                $data_departemen = App\Models\Departemen::where('holding', $karyawan->PenempatanKerja->site_holding_category)->orderBy('nama_departemen', 'ASC')->get();
                                                // print_r($data_departemen);
                                            } else {
                                                $data_departemen = App\Models\Departemen::where('holding', $holding->id)->orderBy('nama_departemen', 'ASC')->get();
                                            }
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
                                                                                <optgroup label='Daftar Departemen '>
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
                                                                                <optgroup label='Daftar Divisi '>
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
                                                                                <optgroup label='Daftar Bagian '>
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
                                                                                <optgroup label='Daftar Jabatan '>
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
                                                                            <label for=" id_jabatan1">Jabatan
                                                                                2</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;"
                                                                                name="departemen2_id"
                                                                                id="id_departemen2"
                                                                                class="form-control">
                                                                                <option value=""> Pilih
                                                                                    Departemen</option>
                                                                                <optgroup label='Daftar Departemen '>
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
                                                                                <optgroup label='Daftar Divisi '>
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
                                                                                <optgroup label='Daftar Bagian '>
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
                                                                                <optgroup label='Daftar Jabatan '>
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
                                                                            <label for=" id_jabatan2">Jabatan
                                                                                3</label>
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
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_jabatan">Simpan
                                            Data Jabatan</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_bank" role="tabpanel">
                                    <div class="row mt-2 mb-2 gy-4">
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

                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_bank">Simpan
                                            Data Bank</button>
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
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_pajak">Simpan
                                            Data Pajak</button>
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
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-primary" type="button" id="btn_update_bpjs">Simpan
                                            Data BPJS</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_dokumen" role="tabpanel">
                                    {{-- <div class="col-md-6">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <img src="{{ asset('admin/assets/img/avatars/cv.png') }}"
                                                    alt="user-avatar" class="d-block w-px-120 h-px-120 rounded"
                                                    id="template_foto_karyawan1" />

                                                <div class="button-wrapper">
                                                    <label for="file_cv" class="btn btn-danger me-2 mb-3"
                                                        tabindex="0">
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
                                        </div> --}}
                                    <div class="col-md-3 mt-3">
                                        <span
                                            class="mdi mdi-account-school-outline badge bg-label-success">&nbsp;KEAHLIAN</span>
                                    </div>
                                    <hr class="m-0 mb-3">
                                    <div class="row mt-2 gy-4">
                                        <div class="d-flex justify-content-center py-2">
                                            <button class="btn btn-primary" id="btn_tambah_keahlian"
                                                type="button">Tambah
                                                Keahlian</button>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <p class="" id="pesan_disabled_keahlian">
                                                *Silakan
                                                edit atau hapus data untuk
                                                menambahkan
                                                data lain
                                                (max : 5)
                                            </p>
                                        </div>
                                        <div class="col-lg-12">
                                            <table class="table table-bordered" id="table_keahlian"
                                                style="width: 100%;">
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
                                                            <input type="text" name="nama_keahlian"
                                                                id="nama_keahlian" class="form-control"
                                                                value="">
                                                            <label for="nama_keahlian">Nama Keahlian</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="file" name="file_keahlian"
                                                                id="file_keahlian" class="form-control" hidden
                                                                value="" accept=".pdf">
                                                            <button type="button" id="btn_upload_keahlian"
                                                                class="btn btn-sm btn-secondary"><i
                                                                    class="mdi mdi-upload"></i> Upload</button>
                                                            <p class="text-primary">format: PDF</p>
                                                            <div class="group-button-keahlian align-items-center">
                                                            </div>
                                                            <label for="file_keahlian">File</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_keahlian"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="mdi mdi-content-save"></i> Simpan</button>
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
                                                        <input type="hidden" name="id_keahlian" id="id_keahlian"
                                                            value="">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="nama_keahlian_update"
                                                                id="nama_keahlian_update" class="form-control"
                                                                value="">
                                                            <label for="nama_keahlian_update">Nama Keahlian</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="file_keahlian_update">File Keahlian</label>
                                                        <input type="file" name="file_keahlian_update"
                                                            id="file_keahlian_update" hidden accept=".pdf"
                                                            class="form-control" value="">
                                                        <input type="hidden" name="file_keahlian_old_update"
                                                            id="file_keahlian_old_update" value="">
                                                        <div class="group-update-keahlian align-items-center">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" id="btn_simpan_edit_keahlian"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="mdi mdi-content-save"></i> Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_riwayat" role="tabpanel">
                                    <div class="d-flex justify-content-center py-2">
                                        <button class="btn btn-info" id="btn_tambah_riwayat" type="button">Tambah
                                            Riwayat
                                            Pekerjaan</button>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <p class="" id="pesan_disabled">*Silakan edit
                                            atau
                                            hapus data untuk menambahkan data lain
                                            (max : 3)
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <p class="text-danger">*Kosongkan apabila belum punya
                                            pengalaman kerja</p>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <table class="table table-bordered" id="tabel_riwayat" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Aksi</th>
                                                    <th>Nama Perusahaan</th>
                                                    <th>Alamat Perusahaan</th>
                                                    <th>Posisi</th>
                                                    <th>Gaji Terakhir</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Tanggal Keluar</th>
                                                    <th>Alasan Keluar</th>
                                                    <th>Surat Keterangan</th>
                                                    <th>Kontak Referensi</th>
                                                    <th>Jabatan Referensi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_tambah_riwayat" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Riwayat
                                                    Pekerjaan</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data">
                                                    <input type="hidden" value="{{ $karyawan->id }}"
                                                        id="id_karyawan_add" name="id_karyawan">
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Nama
                                                            Perusahaan</label>
                                                        <input type="text" class="form-control"
                                                            id="nama_perusahaan_add" name="nama_perusahaan">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Alamat
                                                            Perusahaan</label>
                                                        <input type="text" class="form-control"
                                                            id="alamat_perusahaan_add" name="alamat_perusahaan">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name"
                                                            class="col-form-label">Posisi</label>
                                                        <input type="text" class="form-control" id="posisi_add"
                                                            name="posisi">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Gaji
                                                            Terakhir</label>
                                                        <input type="text" class="form-control" id="gaji_add"
                                                            name="gaji_terakhir">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Tanggal
                                                            Masuk</label>
                                                        <input type="date" class="form-control"
                                                            id="tanggal_masuk_add" name="tanggal_masuk">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Tanggal
                                                            Keluar</label>
                                                        <input type="date" class="form-control"
                                                            id="tanggal_keluar_add" name="tanggal_keluar">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Alasan
                                                            Keluar</label>
                                                        <input type="text" class="form-control"
                                                            id="alasan_keluar_add" name="alasan_keluar">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Surat
                                                            Keterangan Pernah Bekerja
                                                            *PDF
                                                            (Opsional), Max: 5 MB</label>
                                                        <input type="file" accept="application/pdf"
                                                            class="form-control" id="surat_keterangan_add"
                                                            name="surat_keterangan">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Kontak
                                                            Referensi Pengalaman Kerja
                                                            (Opsional)</label>
                                                        <input type="text" class="form-control"
                                                            id="nomor_referensi_add" name="nomor_referensi">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Jabatan
                                                            Referensi
                                                            (Opsional)</label>
                                                        <input type="text" class="form-control"
                                                            id="jabatan_referensi_add" name="jabatan_referensi">
                                                    </div>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="btn_save_riwayat">Masukkan
                                                        Riwayat</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_edit_riwayat" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Riwayat
                                                    Pekerjaan</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <input type="hidden" value="" id="id_riwayat_update"
                                                        name="id_riwayat">
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Nama
                                                            Perusahaan</label>
                                                        <input type="text" class="form-control"
                                                            id="nama_perusahaan_update" name="nama_perusahaan">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Alamat
                                                            Perusahaan</label>
                                                        <input type="text" class="form-control"
                                                            id="alamat_perusahaan_update" name="alamat_perusahaan">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name"
                                                            class="col-form-label">Posisi</label>
                                                        <input type="text" class="form-control" id="posisi_update"
                                                            name="posisi">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Gaji
                                                            Terakhir</label>
                                                        <input type="text" class="form-control" id="gaji_update"
                                                            name="gaji">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Tanggal
                                                            Masuk</label>
                                                        <input type="date" class="form-control"
                                                            id="tanggal_masuk_update" name="tanggal_masuk">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Tanggal
                                                            Keluar</label>
                                                        <input type="date" class="form-control"
                                                            id="tanggal_keluar_update" name="tanggal_keluar">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Alasan
                                                            Keluar</label>
                                                        <input type="text" class="form-control"
                                                            id="alasan_keluar_update" name="alasan_keluar">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Surat
                                                            Keterangan Pernah Bekerja
                                                            *PDF
                                                            (Opsional), Max: 5 MB</label>
                                                        <input type="file" class="form-control"
                                                            id="surat_keterangan_update" name="surat_keterangan"
                                                            accept="application/pdf">
                                                        <input type="hidden" class="form-control"
                                                            id="old_file_update" name="old_file">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Kontak
                                                            Referensi Pengalaman Kerja
                                                            (Opsional)</label>
                                                        <input type="text" class="form-control"
                                                            id="nomor_referensi_update" name="nomor_referensi">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="recipient-name" class="col-form-label">Jabatan
                                                            Referensi
                                                            (Opsional)</label>
                                                        <input type="text" class="form-control"
                                                            id="jabatan_referensi_update" name="jabatan_referensi">
                                                    </div>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="btn_update_riwayat">Masukkan
                                                        Riwayat</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_kesehatan" role="tabpanel">
                                    <div class="p-3">
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah anda
                                                    perokok?</label>
                                                <select class="form-select" name="perokok" id="perokok_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->perokok) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->perokok) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                                <input type="hidden" value="{{ $karyawan->id }}"
                                                    id="id_user_kesehatan_add" name="id_karyawan">
                                            </div>
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah Anda
                                                    mengonsumsi
                                                    alkohol?</label>
                                                <select class="form-select" name="alkohol" id="alkohol_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->alkohol) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->alkohol) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah Anda memiliki
                                                    alergi?</label>
                                                <select class="form-select" name="alergi" id="alergi_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->alergi) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->alergi) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2" id="">
                                                <div id="sebutkan_alergi">
                                                    <label class="text-muted" for="">Jika Ya,
                                                        sebutkan:</label>
                                                    <input type="text" name="sebutkan_alergi"
                                                        id="sebutkan_alergi_add" value="" class="form-control"
                                                        placeholder="Contoh :  alergi kulit, alergi debu, alergi obat">
                                                </div>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah anda memiliki
                                                    Phobia ?</label>
                                                <select class="form-select" name="phobia" id="phobia_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->phobia) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->phobia) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                                <div id="sebutkan_phobia">
                                                    <label class="text-muted" for="">Jika Ya,
                                                        sebutkan:</label>
                                                    <input type="text" name="sebutkan_phobia"
                                                        id="sebutkan_phobia_add"
                                                        placeholder="Contoh : ketinggian, laba-laba, petir"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah anda memiliki
                                                    Keterbatasan Fisik ?</label>
                                                <select class="form-select" name="keterbatasan_fisik"
                                                    id="keterbatasan_fisik_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->keterbatasan_fisik) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->keterbatasan_fisik) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                                <div id="sebutkan_keterbatasan_fisik">
                                                    <label class="text-muted" for="">Jika Ya,
                                                        sebutkan:</label>
                                                    <input type="text" name="sebutkan_keterbatasan_fisik"
                                                        id="sebutkan_keterbatasan_fisik_add" value=""
                                                        placeholder="Contoh : kehilangan anggota tubuh (amputasi), cerebral palsy"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah Anda sedang
                                                    dalam
                                                    pengobatan rutin?</label>
                                                <select class="form-select" name="pengobatan_rutin"
                                                    id="pengobatan_rutin_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pengobatan_rutin) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pengobatan_rutin) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div id="sebutkan_pengobatan_rutin">
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <label class="text-muted" for="">Sebutkan
                                                        Jenis
                                                        Obat</label>
                                                    <input type="hidden" value="{{ $karyawan->id }}"
                                                        id="id_user_pengobatan_add" name="id_karyawan">
                                                    <input type="text" name="jenis_obat" id="jenis_obat_add"
                                                        value="" class="form-control">
                                                </div>
                                                <div class="col-lg p-2">
                                                    <label class="text-muted" for="">Sebutkan
                                                        Alasan</label>
                                                    <input type="text" name="alasan_obat" id="alasan_obat_add"
                                                        value="" class="form-control">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <button class="btn btn-primary" type="button"
                                                        id="tambah_pengobatan">Tambah
                                                        Pengobatan</button>
                                                </div>
                                                <div class="col-lg p-2">
                                                    <div class="col-lg p-2">
                                                    </div>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table py-2">
                                                    <table id="tabel_pengobatan" class="text-left text-nowrap display"
                                                        style="width: 100%;">
                                                        <thead class="">
                                                            <tr>
                                                                <th>Jenis Obat</th>
                                                                <th>Alasan</th>
                                                                <th>Hapus</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            Apakah Anda pernah atau sedang menderita penyakit kronis berikut
                                            ini?
                                            (Centang jika pernah/sedang)
                                        </div>
                                        <div class="p-3">
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="asma" id="asma_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->asma) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Asma
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="diabetes" id="diabetes_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->diabetes) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Diabetes
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="hipertensi" id="hipertensi_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->hipertensi) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Hipertensi (Tekanan Darah Tinggi)
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="jantung" id="jantung_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->jantung) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Jantung
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="tbc" id="tbc_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->tbc) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        TBC
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="hepatitis" id="hepatitis_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->hepatitis) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Hepatitis
                                                    </label>
                                                </div>

                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="epilepsi" id="epilepsi_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->epilepsi) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Epilepsi
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="gangguan_mental" id="gangguan_mental_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->gangguan_mental) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Gangguan Mental (depresi, bipolar, dll)
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="gangguan_pengelihatan" id="gangguan_pengelihatan_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->gangguan_pengelihatan) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Gangguan penglihatan/pendengaran
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="gangguan_lainnya" id="gangguan_lainnya_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->gangguan_lainnya) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Lainnya
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div id="gangguan_sebutkan">
                                                <div class="row">
                                                    <div class="col-lg p-2">
                                                        <label class="text-muted" for="">Sebutkan</label>
                                                        <input type="text" name="sebutkan_gangguan"
                                                            id="sebutkan_gangguan_add" value=""
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-lg p-2">

                                                    </div>
                                                    <div class="col-lg p-2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Pernahkah Anda
                                                    dirawat di
                                                    rumah sakit?</label>
                                                <select class="form-select" name="pernah_dirawat_rs"
                                                    id="pernah_dirawat_rs_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pernah_dirawat_rs) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pernah_dirawat_rs) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div id="pernah_dirawat_sebutkan">
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <label class="text-muted" for="">Tahun</label>
                                                    <input type="hidden" value="{{ $karyawan->id }}"
                                                        id="id_user_rs_add" name="id_karyawan">
                                                    <input type="text" name="tahun_rs" id="tahun_rs_add"
                                                        value="" class="form-control" maxlength="4">
                                                </div>
                                                <div class="col-lg p-2">
                                                    <label class="text-muted" for="">Penyebab</label>
                                                    <input type="text" name="penyebab_rs" id="penyebab_rs_add"
                                                        value="" class="form-control">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <button class="btn btn-primary" type="button"
                                                        id="tambah_rs">Tambah</button>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table py-2">
                                                    <table id="tabel_rs" class="text-left text-nowrap display"
                                                        style="width: 100%;">
                                                        <thead class="">
                                                            <tr>
                                                                <th>Tahun</th>
                                                                <th>Penyebab</th>
                                                                <th>Hapus</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Pernahkah Anda
                                                    mengalami
                                                    kecelakaan serius?
                                                </label>
                                                <select class="form-select" name="kecelakaan_serius"
                                                    id="kecelakaan_serius_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->kecelakaan_serius) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->kecelakaan_serius) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div id="kecelakaan_serius_sebutkan">
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <label class="text-muted" for="">Tahun</label>
                                                    <input type="hidden" value="{{ $karyawan->id }}"
                                                        id="id_user_kecelakaan_add" name="id_karyawan">
                                                    <input type="text" name="tahun_kecelakaan"
                                                        id="tahun_kecelakaan_add" value="" class="form-control"
                                                        maxlength="4">
                                                </div>
                                                <div class="col-lg p-2">
                                                    <label class="text-muted" for="">Penyebab</label>
                                                    <input type="text" name="penyebab_kecelakaan"
                                                        id="penyebab_kecelakaan_add" value=""
                                                        class="form-control">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <button class="btn btn-primary" type="button"
                                                        id="tambah_kecelakaan">Tambah</button>
                                                    <div class="col-lg p-2">
                                                    </div>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="table py-2">
                                                    <table id="tabel_kecelakaan" class="text-left text-nowrap display"
                                                        style="width: 100%;">
                                                        <thead class="">
                                                            <tr>
                                                                <th>Tahun</th>
                                                                <th>Penyebab</th>
                                                                <th>Hapus</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah Anda mampu
                                                    bekerja
                                                    dalam shift malam/lembur?</label>
                                                <select class="form-select" name="mampu_shift" id="mampu_shift_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->mampu_shift) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->mampu_shift) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg p-2">
                                                <label class="text-muted" for="">Apakah Anda pernah
                                                    menjalani pemeriksaan kesehatan kerja sebelumnya?</label>
                                                <select class="form-select" name="pemeriksaan_kerja_sebelumnya"
                                                    id="pemeriksaan_kerja_sebelumnya_add">
                                                    <option value="2"
                                                        {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pemeriksaan_kerja_sebelumnya) ? 'selected' : '' }}>
                                                        Tidak</option>
                                                    <option value="1"
                                                        {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pemeriksaan_kerja_sebelumnya) ? 'selected' : '' }}>
                                                        Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-lg p-2">
                                                <div id="pemeriksaan_sebelumnya_hasil">
                                                    <label class="text-muted" for="">Jika Ya, apakah
                                                        dinyatakan layak bekerja? </label>
                                                    <select class="form-select" name="pemeriksaan_sebelumnya_hasil"
                                                        id="pemeriksaan_sebelumnya_hasil_add">
                                                        <option value="2"
                                                            {{ '2' == old('2', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pemeriksaan_sebelumnya_hasil) ? 'selected' : '' }}>
                                                            Tidak</option>
                                                        <option value="1"
                                                            {{ '1' == old('1', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->pemeriksaan_sebelumnya_hasil) ? 'selected' : '' }}>
                                                            Ya</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg p-2">
                                            </div>
                                        </div>
                                        <div class="row mt-2">

                                            <div class="row">
                                                Riwayat Vaksinasi (Opsional)
                                            </div>
                                        </div>
                                        <div class="row p-3">
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="covid" id="covid_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->covid) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        COVID-19 (dosis lengkap)
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="hepatitis" id="hepatitis_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->hepatitis) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Hepatitis B
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="tetanus" id="tetanus_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->tetanus) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Tetanus
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg p-2">
                                                    <input class="form-check-input border" type="checkbox"
                                                        name="vaksin_lainnya" id="vaksin_lainnya_add"
                                                        {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->vaksin_lainnya) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                                        Lainnya
                                                    </label>
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                                <div class="col-lg p-2">
                                                </div>
                                            </div>
                                            <div id="sebutkan_vaksin_lainnya">
                                                <div class="row">
                                                    <div class="col-lg p-2">
                                                        <label class="text-muted" for="">Sebutkan</label>
                                                        <input type="text" name="sebutkan_vaksin_lainnya"
                                                            id="sebutkan_vaksin_lainnya_add" class="form-control">
                                                    </div>
                                                    <div class="col-lg p-2">
                                                    </div>
                                                    <div class="col-lg p-2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            "Saya menyatakan bahwa semua informasi yang saya berikan adalah
                                            benar
                                            dan dapat dipertanggungjawabkan. Saya bersedia menjalani tes
                                            kesehatan
                                            jika dibutuhkan oleh perusahaan."
                                        </div>
                                        <div class="row px-3">
                                            <div class="col-lg p-2">
                                                <input class="form-check-input border" type="checkbox"
                                                    name="persetujuan_kesehatan" id="persetujuan_kesehatan_add"
                                                    {{ 'on' == old('on', $karyawan->karyawanKesehatan == null ? '' : $karyawan->karyawanKesehatan->persetujuan_kesehatan) ? 'checked' : '' }}>
                                                <label class="form-check-label text-dark" for="flexCheckDefault">
                                                    Setuju
                                                </label>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-primary" type="button"
                                                id="btn_update_kesehatan">Simpan
                                                Data</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body pt-2 mt-1">

                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('admin.karyawan.detail_karyawan_js')
@endsection
