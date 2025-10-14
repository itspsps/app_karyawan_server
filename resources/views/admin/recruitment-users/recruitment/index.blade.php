@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <style type="text/css">
        .my-swal {
            z-index: X;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
                    <div class="card-body">
                        <!-- <hr class="my-5">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <hr class="my-5"> -->
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light my-3"
                            id="btn_modal_recruitment"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                        <!-- <button type="button" class="btn btn-sm btn-success waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_import_inventaris"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button> -->

                        <div class="modal fade" id="modal_lihat_syarat" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class=" modal-content">

                                    <div class="modal-header">
                                        <h4 class="modal-title" id="backDropModalTitle"> Syarat Ketentuan</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-lg-12">
                                            <div class="form-floating form-floating-outline">
                                                <textarea class="form-control @error('show_desc_recruitment') is-invalid @enderror" id="desc_recruitment"
                                                    name="show_desc_recruitment" autofocus value="{{ old('show_desc_recruitment') }}" cols="30" rows="20"
                                                    style="height: auto" disabled></textarea>
                                                <!-- {{-- <input class="form-control @error('show_desc_recruitment') is-invalid @enderror" id="show_desc_recruitment" name="show_desc_recruitment" autofocus value="{{ old('show_desc_recruitment') }}"> --}}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{-- <input type="text" id="show_desc_recruitment"> --}} -->
                                                <label for="show_desc_recruitment">SYARAT KETENTUAN</label>
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
                                <select class="form-control @error('penempatan') is-invalid @enderror" id="penempatan_add"
                                    name="penempatan" autofocus value="{{ old('penempatan') }}">
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
                                <select class="form-control @error('nama_dept') is-invalid @enderror" id="nama_dept_add"
                                    name="nama_dept" autofocus value="{{ old('nama_dept') }}">
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
                                <input type="date" id="created_recruitment_add" name="created_recruitment"
                                    class="form-control @error('created_recruitment') is-invalid @enderror"
                                    placeholder="Masukkan Bagian" value="{{ old('created_recruitment') }}" />
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
                                <input type="date" id="end_recruitment_add" name="end_recruitment"
                                    class="form-control @error('end_recruitment') is-invalid @enderror"
                                    placeholder="Masukkan Bagian" value="{{ old('end_recruitment') }}" />
                                <label for="bagian_recruitment">Tanggal Akhir</label>
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
                                <input type="date" id="deadline_recruitment_add" name="deadline_recruitment"
                                    class="form-control @error('deadline_recruitment') is-invalid @enderror"
                                    placeholder="Masukkan Bagian" value="{{ old('deadline_recruitment') }}" />
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
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control @error('desc_recruitment') is-invalid @enderror" id="desc_recruitment_add"
                                    name="desc_recruitment" autofocus value="{{ old('desc_recruitment') }}" id="" cols="30"
                                    rows="10" style="height: 70%"></textarea>
                                <label for="desc_recruitment">Syarat Ketentuan</label>
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
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_edit_recruitment" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
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
                                    <input type="text" id="surat_penambahan_update">
                                    <input type="file" id="surat_penambahan_baru" name="surat_penambahan"
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
                                <input type="date" id="created_recruitment_update" name="created_recruitment_update"
                                    class="form-control @error('created_recruitment_update') is-invalid @enderror"
                                    placeholder="Tanggal" value="{{ old('created_recruitment_update') }}" />
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
                                <input type="date" id="end_recruitment_update" name="end_recruitment_update"
                                    class="form-control @error('end_recruitment_update') is-invalid @enderror"
                                    placeholder="Tanggal" value="{{ old('end_recruitment_update') }}" />
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
                                <input type="date" id="deadline_recruitment_update" name="deadline_recruitment_update"
                                    class="form-control @error('deadline_recruitment_update') is-invalid @enderror"
                                    placeholder="Tanggal" value="{{ old('deadline_recruitment_update') }}" />
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
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control @error('desc_recruitment_update') is-invalid @enderror" id="desc_recruitment_update"
                                    name="desc_recruitment_update" autofocus value="{{ old('desc_recruitment_update') }}" id=""
                                    cols="30" rows="10" style="height: 50%"></textarea>
                                <label for="desc_recruitment_update">Syarat Ketentuan</label>
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
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script> --}}
    {{-- <script>
    $(document).ready(function() {
        $("#desc_recruitment").summernote();
        // $("#show_desc_recruitment").summernote();
        $("#desc_recruitment_update").summernote();
        $('.dropdown-toggle').dropdown();
    });
</script> --}}
    {{-- start datatable  --}}
    <script>
        let holding = window.location.pathname.split("/").pop();
        var table = $('#table_recruitment').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-recruitment') }}" + '/' + holding,
            },
            columns: [
                // {
                //     data: 'created_at',
                //     render: function(data, type, row, meta) {
                //         let dateParts = data.split('-');
                //         let formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                //         return '<pre style="font-size: inherit;font-family: inherit;margin: 0;">' +
                //             formattedDate + '</pre>';
                //     }
                // },
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
                [2, 'desc']
            ]
        });
    </script>
    {{-- end datatable  --}}
    <script>
        $('#penambahan_form').hide();
        $('#penggantian_penambahan_add').on('change', function() {
            let value = $(this).val();
            if (value == '2') {
                $('#penambahan_form').show();
            } else {
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
            let holding = window.location.pathname.split("/").pop();
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
            let holding = window.location.pathname.split("/").pop();
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
            formData.append('surat_penambahan', $('#surat_penambahan_add').val());
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

                url: "{{ url('/recruitment/create') }}",
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
            $('#show_desc_recruitment').val(desc);
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
            let holding = window.location.pathname.split("/").pop();
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
            $('#surat_penambahan_update').val(surat_penambahan);
            $('#nama_dept_update option').filter(function() {
                return $(this).val().trim() == dept
            }).prop('selected', true)
            $('#nama_divisi_update option').filter(function() {
                return $(this).val().trim() == divisi
            }).prop('selected', true)
            $('#nama_bagian_update').val(bagian);
            $('#nama_jabatan_update').val(jabatan);
            $('#desc_recruitment_update').val(desc);
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
            formData.append('surat_penambahan', $('#surat_penambahan_baru').val());
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
                        $('#surat_penambahan_baru').val('');
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
