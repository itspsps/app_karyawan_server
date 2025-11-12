@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style type="text/css">
        .my-swal {
            z-index: X;
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

        /* ukuran huruf untuk pilihan yang sudah dipilih (tag dalam box) */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            font-size: 0.75rem;
            /* kecilin text */
            padding: 2px 6px;
            /* biar nggak terlalu tinggi */
            line-height: 1.2;
        }

        /* icon "x" di tag */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            font-size: 0.7rem;
            margin-right: 2px;
        }

        /* tulisan placeholder / hasil render */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
            font-size: 0.8rem;
        }
    </style>
@endsection
@section('isi')
    @include('sweetalert::alert')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Transactions -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">DATA RECRUITMENT</h5>
                        </div>
                    </div>
                    <div id="collapseFilterWrapper" class="sticky-top bg-white" style="z-index: 1020;">
                        <div class="card-body">

                            <div class="row gy-4 mb-4">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="departemen_filter[]"
                                            id="departemen_filter" multiple>
                                            <option disabled value="">-Pilih Departemen-</option>
                                            @foreach ($departemen as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="departemen_filter">Departemen</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="divisi_filter[]"
                                            id="divisi_filter" multiple>
                                            <option selected disabled value="">-- Pilih Divisi --</option>
                                        </select>
                                        <label for="divisi_filter">Divisi</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="bagian_filter[]"
                                            id="bagian_filter" multiple>
                                            <option selected disabled value="">-- Pilih Bagian --</option>
                                        </select>
                                        <label for="bagian_filter">Bagian</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="jabatan_filter[]"
                                            id="jabatan_filter" multiple>
                                            <option selected disabled value="">-- Pilih Jabatan --</option>
                                        </select>
                                        <label for="jabatan_filter">Jabatan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row gy-4 align-items-end">
                                <div class="col-lg-6 col-md-6col-sm-12">
                                    <div class="form-floating form-floating-outline">
                                        <div id="reportrange" style="white-space: nowrap;">
                                            <button class="btn btn-outline-secondary w-100 ">
                                                <span class="fw-bold">FILTER&nbsp;DATE&nbsp;:&nbsp;</span>
                                                <span class="date_daterange"></span>
                                                <input type="date" id="start_date" name="start_date" value=""
                                                    hidden>
                                                <input type="date" id="end_date" name="end_date" value="" hidden>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-12 col-sm-12 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary w-100" id="btn_filter">
                                        <i class="mdi mdi-filter-outline"></i>&nbsp;Filter
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <hr class="my-5">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <hr class="my-5"> -->
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light my-3"
                            id="btn_modal_recruitment"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                        <!-- <button type="button" class="btn btn-sm btn-success waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_import_inventaris"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button> -->

                        <div class="modal fade" id="modal_lihat_syarat" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class=" modal-content">

                                    <div class="modal-body">
                                        <div class="col-lg-12">
                                            <div class="form-floating form-floating-outline">
                                                <div id="show_desc_recruitment" style="height:auto;"></div>
                                                <!-- {{-- <input class="form-control @error('show_desc_recruitment') is-invalid @enderror" id="show_desc_recruitment" name="show_desc_recruitment" autofocus value="{{ old('show_desc_recruitment') }}"> --}}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {{-- <input type="text" id="show_desc_recruitment"> --}} -->
                                                {{-- <label for="show_desc_recruitment">SYARAT KETENTUAN</label> --}}
                                            </div>
                                            @error('show_desc_recruitment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table" id="table_recruitment" style="width: 100%; font-size: small;">
                            <thead class="table-primary">
                                <tr>
                                    <!-- <th>No.</th> -->
                                    <th>Legal Number</th>
                                    <th>Status</th>
                                    <th>Pelamar</th>
                                    <th>Tanggal Awal</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Penempatan</th>
                                    <th>Departemen</th>
                                    <th>Divisi</th>
                                    <th>Bagian</th>
                                    <th>Jabatan</th>
                                    <th>Keterangan</th>
                                    <th>Deadline Recruitment</th>
                                    <th>Penggantian / Penambahan</th>
                                    <th>Surat Penambahan</th>
                                    <th>Kuota</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
            <!--/ Data Tables -->

        </div>
    </div>
    <div class="modal fade" id="modal_tambah_recruitment" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="backDropModalTitle">Tambah Recruitment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" readonly class="form-control"
                                        placeholder="Masukkan Holding Inventaris" value="{{ $holding->holding_name }}" />
                                    <input type="hidden" id="holding_recruitment_add" name="holding_recruitment"
                                        class="form-control" value="{{ $holding->id }}" />
                                    <label for="holding_recruitment">Holding Recruitment</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('penggantian_penambahan') is-invalid @enderror"
                                        id="penggantian_penambahan_add" name="penggantian_penambahan" autofocus
                                        value="{{ old('penggantian_penambahan') }}">
                                        <option value="1">Penggantian</option>
                                        <option value="2">Penambahan</option>
                                    </select>
                                    <label for="penggantian_penambahan">Penggantian / Penambahan?</label>
                                </div>
                                @error('penggantian_penambahan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div id="penambahan_form">
                            <div class="row g-2">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="file" id="surat_penambahan_add" name="surat_penambahan"
                                            class="form-control @error('surat_penambahan') is-invalid @enderror"
                                            placeholder="Tanggal" value="{{ old('surat_penambahan') }}"
                                            accept="application/pdf" />
                                        <label for="bagian_recruitment">Surat Penambahan (PDF)</label>
                                    </div>
                                    @error('surat_penambahan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" id="kuota_add" name="kuota"
                                        class="form-control @error('kuota') is-invalid @enderror" placeholder="Kuota"
                                        value="{{ old('kuota') }}" />
                                    <label for="bagian_recruitment">Kuota</label>
                                </div>
                                @error('kuota')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('penempatan') is-invalid @enderror"
                                        id="penempatan_add" name="penempatan" autofocus value="{{ old('penempatan') }}">
                                        <option selected disabled value="">Pilih Penempatan</option>
                                        @foreach ($site as $st)
                                            <option value="{{ $st->id }}">{{ $st->site_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="penempatan">Penempatan</label>
                                </div>
                                @error('penempatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>

                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('nama_dept') is-invalid @enderror"
                                        id="nama_dept_add" name="nama_dept" autofocus value="{{ old('nama_dept') }}">
                                        <option value=""> Pilih Departemen</option>
                                        @foreach ($data_dept as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama_departemen }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="nama_dept">Nama Departemen </label>
                                </div>
                                @error('nama_dept')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('nama_divisi') is-invalid @enderror"
                                        id="nama_divisi_add" name="nama_divisi">
                                        <option value=""> Pilih Divisi</option>
                                    </select>
                                    <label for="form_nama_divisi">Nama Divisi</label>
                                </div>
                                @error('nama_divisi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('nama_bagian') is-invalid @enderror"
                                        id="nama_bagian_add" name="nama_bagian">
                                        <option value=""> Pilih Bagian</option>
                                    </select>
                                    <label for="form_nama_bagian">Nama Bagian</label>
                                </div>
                                @error('nama_bagian')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('nama_jabatan') is-invalid @enderror"
                                        id="nama_jabatan_add" name="nama_jabatan">
                                        <option value=""> Pilih Jabatan</option>
                                    </select>
                                    <label for="form_nama_jabatan">Nama Jabatan</label>
                                </div>
                                @error('nama_jabatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="created_recruitment_add" name="created_recruitment"
                                        class="form-control @error('created_recruitment') is-invalid @enderror datepicker"
                                        placeholder="Masukkan Bagian" value="{{ old('created_recruitment') }}"
                                        readonly />
                                    <label for="bagian_recruitment">Tanggal Mulai</label>
                                </div>
                                @error('created_recruitment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="end_recruitment_add" name="end_recruitment"
                                        class="form-control @error('end_recruitment') is-invalid @enderror datepicker"
                                        placeholder="Masukkan Bagian" value="{{ old('end_recruitment') }}" />
                                    <label for="bagian_recruitment" readonly>Tanggal Akhir</label>
                                </div>
                                @error('end_recruitment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="deadline_recruitment_add" name="deadline_recruitment"
                                        class="form-control @error('deadline_recruitment') is-invalid @enderror datepicker"
                                        placeholder="Masukkan Bagian" value="{{ old('deadline_recruitment') }}"
                                        readonly />
                                    <label for="bagian_recruitment">Deadline</label>
                                </div>
                                @error('deadline_recruitment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="card-title m-0 me-2">SYARAT DAN KETENTUAN</h6>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control @error('desc_recruitment') is-invalid @enderror summernote" id="desc_recruitment_add"
                                        name="desc_recruitment" autofocus value="{{ old('desc_recruitment') }}" id="" cols="30"
                                        rows="10" style="height: 70%"></textarea>
                                </div>
                                @error('desc_recruitment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-secondary m-1" data-bs-dismiss="modal">
                                Tutup
                            </button>
                            <button class="btn btn-primary m-1" id="btn_add_recruitment">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_edit_recruitment" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="backDropModalTitle">Edit Recruitment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_recruitment" id="id_recruitment" value="">
                <div class="modal-body">
                    <div class="row g-2">

                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('penggantian_penambahan') is-invalid @enderror"
                                    id="penggantian_penambahan_update" name="penggantian_penambahan" autofocus
                                    value="">
                                    <option value="1">Penggantian</option>
                                    <option value="2">Penambahan</option>
                                </select>
                                <label for="penggantian_penambahan">Penggantian / Penambahan?</label>
                            </div>
                            @error('penggantian_penambahan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div id="penambahan_form_update">
                        <div class="row g-2">
                            <div class="col mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="hidden" id="old_file_update" name="old_file">
                                    <input type="file" id="surat_penambahan_update" name="surat_penambahan"
                                        class="form-control @error('surat_penambahan') is-invalid @enderror"
                                        placeholder="Tanggal" value="{{ old('surat_penambahan') }}"
                                        accept="application/pdf" />
                                    <label for="bagian_recruitment">Surat Penambahan (PDF)</label>
                                </div>
                                @error('surat_penambahan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="number" id="kuota_update" name="kuota"
                                    class="form-control @error('kuota') is-invalid @enderror" placeholder="Kuota"
                                    value="{{ old('kuota') }}" />
                                <label for="bagian_recruitment">Kuota</label>
                            </div>
                            @error('kuota')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('penempatan') is-invalid @enderror"
                                    id="penempatan_update" name="penempatan" autofocus value="{{ old('penempatan') }}">
                                    <option selected disabled value="">Pilih Penempatan</option>
                                    @foreach ($site as $st)
                                        <option value="{{ $st->id }}">{{ $st->site_name }}</option>
                                    @endforeach
                                </select>
                                <label for="penempatan">Penempatan</label>
                            </div>
                            @error('penempatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('nama_dept') is-invalid @enderror"
                                    id="nama_dept_update" name="nama_dept" autofocus
                                    value="{{ old('nama_dept_update') }}">
                                    <option value=""> Pilih Departemen</option>
                                    @foreach ($data_dept as $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="nama_dept_update">Nama Departemen</label>
                            </div>
                            @error('nama_departemen_update')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('nama_divisi_update') is-invalid @enderror"
                                    id="nama_divisi_update" name="nama_divisi_update" autofocus
                                    value="{{ old('nama_divisi_update') }}">
                                    <option value=""> Pilih Divisi</option>
                                    @foreach ($data_divisi as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama_divisi }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="nama_divisi_update">Nama Divisi</label>
                            </div>
                            @error('nama_divisi_update')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('nama_bagian_update') is-invalid @enderror"
                                    id="nama_bagian_update" name="nama_bagian_update" autofocus
                                    value="{{ old('nama_bagian_update') }}">
                                    <option value=""> Pilih Bagian</option>
                                    @foreach ($data_bagian as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama_bagian }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="nama_bagian_update">Nama Bagian</label>
                            </div>
                            @error('nama_bagian_update')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('nama_jabatan_update') is-invalid @enderror"
                                    id="nama_jabatan_update" name="nama_jabatan_update" autofocus
                                    value="{{ old('nama_jabatan_update') }}">
                                    <option value=""> Pilih Jabatan</option>
                                    @foreach ($data_jabatan as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="nama_jabatan_update">Nama Jabatan</label>
                            </div>
                            @error('nama_jabatan_update')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="created_recruitment_update" name="created_recruitment_update"
                                    class="form-control @error('created_recruitment_update') is-invalid @enderror datepicker"
                                    placeholder="Tanggal" value="{{ old('created_recruitment_update') }}" readonly />
                                <label for="bagian_recruitment">Tanggal Awal</label>
                            </div>
                            @error('created_recruitment_update')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="end_recruitment_update" name="end_recruitment_update"
                                    class="form-control @error('end_recruitment_update') is-invalid @enderror datepicker"
                                    placeholder="Tanggal" value="{{ old('end_recruitment_update') }}" readonly />
                                <label for="bagian_recruitment">Tanggal Akhir</label>
                            </div>
                            @error('end_recruitment_update')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="deadline_recruitment_update" name="deadline_recruitment_update"
                                    class="form-control @error('deadline_recruitment_update') is-invalid @enderror datepicker"
                                    placeholder="Tanggal" value="{{ old('deadline_recruitment_update') }}" readonly />
                                <label for="bagian_recruitment">Deadline</label>
                            </div>
                            @error('deadline_recruitment_update')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">SYARAT DAN KETENTUAN</h5>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control @error('desc_recruitment_update') is-invalid @enderror summernote"
                                    id="desc_recruitment_update" name="desc_recruitment_update" autofocus
                                    value="{{ old('desc_recruitment_update') }}" id="" cols="30" rows="10" style="height: 50%"></textarea>
                            </div>
                            @error('desc_recruitment_update')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-primary m-1" id="btn_update_recruitment">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#desc_recruitment_add").summernote();
            // $("#show_desc_recruitment").summernote();
            $("#desc_recruitment_update").summernote();
            $('.dropdown-toggle').dropdown();
        });
        $(".summernote").summernote({
            placeholder: "Masukkan Syarat dan Ketentuan",
            tabsize: 2,
            height: 120,
            toolbar: [
                ["style", ["style"]],
                ["font", ["bold", "underline", "clear"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["insert", ["link", "picture", "video"]],
                ["view", ["fullscreen", "help"]]
            ],
            callbacks: {
                onImageUpload: function(e, o = this) {
                    uploadImage(e[0], o)
                },
                onMediaDelete: function(e) {
                    deleteImage(e[0].src)
                }
            }
        })
    </script>
    {{-- start datatable  --}}
    <script>
        var holding_id = '{{ $holding->id }}';
        var holding = '{{ $holding->holding_code }}';
        $('#departemen_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Departemen",
            allowClear: true
        });
        $('#divisi_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Divisi",
            allowClear: true
        });
        $('#bagian_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Bagian",
            allowClear: true
        });
        $('#jabatan_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $(function() {
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0
            });
        });
        var start = moment().startOf('month');
        var end = moment().endOf('month');
        // var start = moment('2025-07-21');
        // var end = moment('2025-08-21');

        cb(start, end);
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);


        function cb(start, end) {
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            $('#reportrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            console.log(start, end);
        }

        var lstart, lend
        var departemen_filter = $('#departemen_filter').val() || [];
        var divisi_filter = $('#divisi_filter').val() || [];
        var bagian_filter = $('#bagian_filter').val() || [];
        var jabatan_filter = $('#jabatan_filter').val() || [];
        var start_date = lstart || [];
        var end_date = lend || [];

        load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);

        function load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date) {
            // $('#table_recruitment2').empty();
            $('#table_recruitment').DataTable().clear().destroy();
            if ($.fn.DataTable.isDataTable('#table_recruitment')) {
                $('#table_recruitment').DataTable().clear().destroy();
            }
            var table = $('#table_recruitment').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                ajax: {
                    url: "{{ url('/dt/data-recruitment') }}" + '/' + holding,
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                    }
                },
                columns: [{
                        data: 'legal_number',
                        name: 'legal_number'
                    },
                    {
                        data: 'status_recruitment',
                        name: 'status_recruitment'
                    },
                    {
                        data: 'pelamar',
                        name: 'pelamar'
                    },
                    {
                        data: 'created_recruitment',
                        name: 'created_recruitment'
                    },
                    {
                        data: 'end_recruitment',
                        name: 'end_recruitment'
                    },
                    {
                        data: 'penempatan',
                        name: 'penempatan'
                    },
                    {
                        data: 'nama_departemen',
                        name: 'nama_departemen'
                    },
                    {
                        data: 'nama_divisi',
                        name: 'nama_divisi'
                    },
                    {
                        data: 'nama_bagian',
                        name: 'nama_bagian'
                    },
                    {
                        data: 'nama_jabatan',
                        name: 'nama_jabatan'
                    },
                    {
                        data: 'desc_recruitment',
                        name: 'desc_recruitment',
                    },
                    {
                        data: 'deadline_recruitment',
                        name: 'deadline_recruitment',
                    },
                    {
                        data: 'penggantian_penambahan',
                        name: 'penggantian_penambahan',
                    },
                    {
                        data: 'surat_penambahan',
                        name: 'surat_penambahan',
                    },

                    {
                        data: 'kuota',
                        name: 'kuota',
                    },

                    {
                        data: 'option',
                        name: 'option'
                    },
                ],
                order: [
                    [3, 'desc']
                ]
            });
        }



        $('#departemen_filter').change(function(e) {
            departemen_filter_dept = $(this).val() || '';
            var url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/report_recruitment/get_divisi') }}@else{{ url('report_recruitment/get_divisi') }}@endif" +
                '/' + holding_id;
            // console.log(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    holding: holding_id,
                    departemen_filter: departemen_filter_dept,
                },
                cache: false,

                success: function(data_dept) {
                    // console.log(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept);
                    $('#divisi_filter').html(data_dept.select);
                    $('#bagian_filter').html('<option value="">Pilih Bagian</option>');
                    $('#jabatan_filter').html('<option value="">Pilih Jabatan</option>');
                    // refresh select2 biar dropdown kebaca data baru
                    // destroy & init ulang
                    let isOpen = $('#divisi_filter').data('select2') && $('#divisi_filter')
                        .data('select2').isOpen();

                    $('#divisi_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Divisi...",
                        allowClear: true
                    });
                    $('#bagian_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Divisi...",
                        allowClear: true
                    });
                    $('#jabatan_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Divisi...",
                        allowClear: true
                    });
                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#divisi_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#divisi_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#divisi_filter').select2('open');
                    }

                },

                error: function(data) {
                    Swal.close();
                    console.log('error:', data)
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.responseJSON.message,
                        timer: 4200,
                        showConfirmButton: false,
                    })
                },

            })
        })
        $('#divisi_filter').change(function() {
            divisi_filter = $(this).val() || '';

            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/report_recruitment/get_bagian') }}@else{{ url('report_recruitment/get_bagian') }}@endif" +
                    '/' + holding_id,
                data: {
                    holding: holding_id,
                    divisi_filter: divisi_filter,
                },
                cache: false,

                success: function(data_divisi) {
                    // console.log(data_divisi);
                    $('#bagian_filter').html(data_divisi.select);
                    $('#jabatan_filter').html('<option value="">Pilih Jabatan..</option>');
                    let isOpen = $('#bagian_filter').data('select2') && $('#bagian_filter')
                        .data('select2').isOpen();

                    $('#bagian_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Bagian...",
                        allowClear: true
                    });
                    $('#jabatan_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Bagian...",
                        allowClear: true
                    });

                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#bagian_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#bagian_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#bagian_filter').select2('open');
                    }
                },
                error: function(data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.responseJSON.message,
                        timer: 4200,
                        showConfirmButton: false,
                    })
                },

            })
        })
        $('#bagian_filter').change(function() {
            bagian_filter = $(this).val() || '';

            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "{{ url('report_recruitment/get_jabatan') }}" + '/' + holding_id,
                data: {
                    holding: holding_id,
                    bagian_filter: bagian_filter
                },
                cache: false,

                success: function(data_jabatan) {

                    $('#jabatan_filter').html(data_jabatan.select);
                    let isOpen = $('#jabatan_filter').data('select2') && $(
                        '#jabatan_filter').data('select2').isOpen();

                    $('#jabatan_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Jabatan...",
                        allowClear: true
                    });
                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#jabatan_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#jabatan_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#jabatan_filter').select2('open');
                    }
                },
                error: function(data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.responseJSON.message,
                        timer: 4200,
                        showConfirmButton: false,
                    })
                },

            })
        })

        $('#btn_filter').click(function(e) {
            var departemen_filter = $('#departemen_filter').val() || [];
            var divisi_filter = $('#divisi_filter').val() || [];
            var bagian_filter = $('#bagian_filter').val() || [];
            var jabatan_filter = $('#jabatan_filter').val() || [];
            var start_date = $('#start_date').val() || '';
            var end_date = $('#end_date').val() || '';

            // console.log(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);

            $('#content_null').empty();

            load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);
        });

        $('#penambahan_form').hide();
        $('#penggantian_penambahan_add').on('change', function() {
            let value = $(this).val();
            if (value == '2') {
                $('#penambahan_form').show();
            } else {
                $('#surat_penambahan_add').val('');
                $('#penambahan_form').hide();
            }
        });

        $('#penggantian_penambahan_update').on('change', function() {
            let value = $(this).val();
            if (value == '2') {
                $('#penambahan_form_update').show();
            } else {
                $('#penambahan_form_update').hide();
            }
        });
        $('#btn_modal_recruitment').click(function() {
            // console.log('asoy');
            $('#modal_tambah_recruitment').modal('show');
        });
        // start add departemen
        $('#nama_dept_add').on('change', function() {
            let id_dept = $(this).val();
            let url = "{{ url('/bagian/get_divisi') }}" + "/" + id_dept + "/" + holding;
            console.log(id_dept);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_divisi_add').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        // end add departemen

        // start add divisi
        $('#nama_divisi_add').on('change', function() {
            let id_divisi = $(this).val();
            let url = "{{ url('/bagian/get_bagian') }}" + "/" + id_divisi;
            console.log(id_divisi);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_bagian_add').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#nama_bagian_add').on('change', function() {
            let id_bagian = $(this).val();
            let url = "{{ url('/jabatan/get_jabatan') }}" + "/" + id_bagian;
            console.log(id_bagian);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_jabatan_add').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })

        // start update departemen
        $('#nama_dept_update').on('change', function() {
            // console.log('asooy');
            let id_dept = $(this).val();
            let url = "{{ url('/bagian/get_divisi') }}" + "/" + id_dept + "/" + holding;
            console.log(id_dept);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    console.log(response);
                    $('#nama_divisi_update').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        // end add departemen

        // start update divisi
        $('#nama_divisi_update').on('change', function() {
            let id_divisi = $(this).val();
            let url = "{{ url('/bagian/get_bagian') }}" + "/" + id_divisi;
            console.log(id_divisi);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_bagian_update').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#nama_bagian_update').on('change', function() {
            let id_bagian = $(this).val();
            let url = "{{ url('/jabatan/get_jabatan') }}" + "/" + id_bagian;
            console.log(id_bagian);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_jabatan_update').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })

        $('#btn_add_recruitment').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            //ambil data dari form
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('holding_recruitment', $('#holding_recruitment_add').val());
            formData.append('penggantian_penambahan', $('#penggantian_penambahan_add').val());
            // formData.append('surat_penambahan', $('#surat_penambahan_add').val());
            var fileInput = $('#surat_penambahan_add')[0];
            if (fileInput.files.length > 0) {
                formData.append('surat_penambahan', fileInput.files[0]);
            }
            formData.append('kuota', $('#kuota_add').val());
            formData.append('penempatan', $('#penempatan_add').val());
            formData.append('nama_dept', $('#nama_dept_add').val());
            formData.append('nama_divisi', $('#nama_divisi_add').val());
            formData.append('nama_bagian', $('#nama_bagian_add').val());
            formData.append('nama_jabatan', $('#nama_jabatan_add').val());
            formData.append('created_recruitment', $('#created_recruitment_add').val());
            formData.append('end_recruitment', $('#end_recruitment_add').val());
            formData.append('deadline_recruitment', $('#deadline_recruitment_add').val());
            formData.append('desc_recruitment', $('#desc_recruitment_add').val());

            // post
            $.ajax({
                type: "POST",

                url: "{{ url('/recruitment/create') }}" + '/' + holding,
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong!');
                    // console.log(formData);
                },
                success: function(data) {
                    console.log(data);
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_tambah_recruitment').modal('hide');
                        $('#penggantian_penambahan_add').val('');
                        $('#surat_penambahan_add').val('');
                        $('#kuota_add').val('');
                        $('#penempatan_add').val('');
                        $('#nama_dept_add').val('');
                        $('#nama_divisi_add').val('');
                        $('#nama_bagian_add').val('');
                        $('#nama_jabatan_add').val('');
                        $('#created_recruitment_add').val('');
                        $('#end_recruitment_add').val('');
                        $('#deadline_recruitment_add').val('');
                        $('#desc_recruitment_add').val('');
                        $('#table_recruitment').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        $('#modal_tambah_recruitment').modal('hide');

                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_tambah_recruitment').modal('hide');

                        $('#table_recruitment').DataTable().ajax.reload();


                    } else {
                        $('#modal_tambah_recruitment').modal('hide');
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 4500
                        })
                    }
                }
            });
        });

        // show modal syarat
        $(document).on('click', '#btn_lihat_syarat', function() {
            let id = $(this).data('id');
            let desc = $(this).data('desc');
            console.log(desc);
            let holding = $(this).data("holding");
            $('#show_desc_recruitment').html(desc);
            // $('#show_desc_recruitment').summernote('disable');
            // let url = "{{ url('recruitment/show/') }}" + '/' + id + '/' + holding;
            $('#modal_lihat_syarat').modal('show');
        });
        // update status aktif to non aktif
        $(document).on('click', '#btn_status_aktif', function() {
            var id = $(this).data('id');
            let holding = window.location.pathname.split("/").pop();
            console.log(id);
            console.log(holding);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Menonaktifkan Recruitment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/update/status-recruitment/') }}" + '/' + id +
                            '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terupdate!',
                                text: 'Data anda berhasil di update.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_recruitment').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Your data is safe :',
                        icon: 'error',
                        timer: 1500
                    })
                }
            });

        });
        // update status non aktif to aktif
        $(document).on('click', '#btn_status_naktif', function() {
            var id = $(this).data('id');
            console.log(id);
            console.log(holding);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Mengaktifkan Recruitment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/update/status-recruitment/') }}" + '/' + id +
                            '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terupdate!',
                                text: 'Data anda berhasil di update.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_recruitment').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Your data is safe :',
                        icon: 'error',
                        timer: 1500
                    })
                }
            });

        });
        $(document).on("click", "#btn_edit_recruitment", function() {
            let id = $(this).data('id');
            let penggantian_penambahan = $(this).data('penggantian_penambahan');
            let penempatan = $(this).data("penempatan");
            let kuota = $(this).data("kuota");
            let surat_penambahan = $(this).data("surat_penambahan");
            let dept = $(this).data("dept");
            let divisi = $(this).data("divisi");
            let bagian = $(this).data("bagian");
            let jabatan = $(this).data("jabatan");
            let tanggal_awal = $(this).data("tanggal_awal");
            let tanggal_akhir = $(this).data("tanggal_akhir");
            let deadline = $(this).data("deadline");
            let holding = $(this).data("holding");
            let desc = $(this).data("desc");
            $('#id_recruitment').val(id);
            $('#penggantian_penambahan_update').val(penggantian_penambahan);
            $('#kuota_update').val(kuota);
            $('#penempatan_update').val(penempatan);
            $('#old_file_update').val(surat_penambahan);
            $('#nama_dept_update option').filter(function() {
                return $(this).val().trim() == dept
            }).prop('selected', true)
            $('#nama_divisi_update option').filter(function() {
                return $(this).val().trim() == divisi
            }).prop('selected', true)
            $('#nama_bagian_update').val(bagian);
            $('#nama_jabatan_update').val(jabatan);
            $('#desc_recruitment_update').summernote('code', desc);
            $('#created_recruitment_update').val(tanggal_awal);
            $('#end_recruitment_update').val(tanggal_akhir);
            $('#deadline_recruitment_update').val(deadline);
            $('#modal_edit_recruitment').modal('show');
            // console.log(bagian);

            if (penggantian_penambahan == 2) {
                $('#penambahan_form_update').show();

            } else {
                $('#penambahan_form_update').hide();
            }

        });
        $('#btn_update_recruitment').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            //ambil data dari form
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_recruitment').val());
            formData.append('holding_recruitment', $('#holding_recruitment_add').val());
            formData.append('penggantian_penambahan', $('#penggantian_penambahan_update').val());
            formData.append('old_file', $('#old_file_update').val());
            var fileInput = $('#surat_penambahan_update')[0];
            if (fileInput.files.length > 0) {
                formData.append('surat_penambahan', fileInput.files[0]);
            }
            formData.append('kuota', $('#kuota_update').val());
            formData.append('penempatan', $('#penempatan_update').val());
            formData.append('nama_dept', $('#nama_dept_update').val());
            formData.append('nama_divisi', $('#nama_divisi_update').val());
            formData.append('nama_bagian', $('#nama_bagian_update').val());
            formData.append('nama_jabatan', $('#nama_jabatan_update').val());
            formData.append('created_recruitment', $('#created_recruitment_update').val());
            formData.append('end_recruitment', $('#end_recruitment_update').val());
            formData.append('deadline_recruitment', $('#deadline_recruitment_update').val());
            formData.append('desc_recruitment', $('#desc_recruitment_update').val());

            // post
            $.ajax({
                type: "POST",

                url: "{{ url('/recruitment/update') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong!');
                    // console.log(formData);
                },
                success: function(data) {
                    console.log(data);
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_edit_recruitment').modal('hide');
                        $('#penggantian_penambahan_update').val('');
                        $('#surat_penambahan_update').val('');
                        $('#kuota_update').val('');
                        $('#penempatan_update').val('');
                        $('#nama_dept_update').val('');
                        $('#nama_divisi_update').val('');
                        $('#nama_bagian_update').val('');
                        $('#nama_jabatan_update').val('');
                        $('#created_recruitment_update').val('');
                        $('#end_recruitment_update').val('');
                        $('#deadline_recruitment_update').val('');
                        $('#desc_recruitment_update').val('');
                        $('#table_recruitment').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        $('#modal_edit_recruitment').modal('hide');

                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_edit_recruitment').modal('hide');

                        $('#table_recruitment').DataTable().ajax.reload();


                    } else {
                        $('#modal_edit_recruitment').modal('hide');
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 4500
                        })
                    }
                }
            });
        });

        // delete data
        $(document).on('click', '#btn_delete_recruitment', function() {
            var id = $(this).data('id');
            let holding = $(this).data("holding");
            console.log(id);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Kamu tidak dapat mengembalikan data ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/delete/') }}" + '/' + id + '/' + holding,
                        type: "GET",
                        error: function(data) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terdapat data tidak dapat dihapus.',
                                icon: 'error',
                                confirmButtonText: 'Tutup'
                            });
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data anda berhasil di hapus.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_recruitment').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Your data is safe :',
                        icon: 'error',
                        timer: 1500
                    })
                }
            });

        });
    </script>
@endsection
