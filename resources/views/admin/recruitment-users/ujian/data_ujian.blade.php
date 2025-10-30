@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <style type="text/css">
        .my-swal {
            z-index: X;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
@endsection
@section('isi')
    @include('sweetalert::alert')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Transactions -->
            <div class="col-lg-12">
                <div class="container card">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                Pilihan Ganda
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Esai
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                interview
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-3" data-bs-toggle="tab" href="#icon-tabpanel-3" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Kategori Ujian
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-4" data-bs-toggle="tab" href="#icon-tabpanel-4" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Pembobotan
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-5" data-bs-toggle="tab" href="#icon-tabpanel-5" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Referensi
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel" aria-labelledby="icon-tab-0">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">SOAL UJIAN PILIHAN GANDA</h5>
                            </div>
                            <a href="{{ url('/pg-data-ujian/ujian_pg/' . $holding->holding_code) }}" type="button"
                                class="btn btn-sm btn-primary waves-effect waves-light my-3"><i
                                    class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</a>
                            <table class="table" id="table_ujian" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Direktur</th>
                                        <th>Head</th>
                                        <th>Manager / Regional Sales Manager</th>
                                        <th>Junior Sales Manager / Area Sales Manager</th>
                                        <th>Supervisor</th>
                                        <th>Koordinator</th>
                                        <th>Admin, Operator, Drafter, Staff, Sales, Sopir</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">SOAL UJIAN ESAI</h5>
                            </div>
                            <a href="{{ url('/pg-data-ujian/ujian_pg_esai/' . $holding->holding_code) }}" type="button"
                                class="btn btn-sm btn-primary waves-effect waves-light my-3"><i
                                    class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</a>
                            <table class="table" id="table_esai" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Direktur</th>
                                        <th>Head</th>
                                        <th>Manager / Regional Sales Manager</th>
                                        <th>Junior Sales Manager / Area Sales Manager</th>
                                        <th>Supervisor</th>
                                        <th>Koordinator</th>
                                        <th>Admin, Operator, Drafter, Staff, Sales, Sopir</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">PENILAIAN INTERVIEW</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light my-3"
                                id="btn_modal_interview"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                            <table class="table" id="tabel_interview" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Soal</th>
                                        <th>Deskripsi</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-3" role="tabpanel" aria-labelledby="icon-tab-3">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">KATEGORI UJIAN</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light my-3"
                                id="btn_modal_kategori"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                            <table class="table" id="tabel_ujian_kategori" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-4" role="tabpanel" aria-labelledby="icon-tab-4">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">PEMBOBOTAN</h5>
                            </div>
                            <table class="table" id="tabel_pembobotan" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-start">esai</th>
                                        <th class="text-start">pilihan ganda</th>
                                        <th class="text-start">interview</th>
                                        <th class="text-start">interview user</th>
                                        <th class="text-start">option</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-5" role="tabpanel" aria-labelledby="icon-tab-4">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">REFERENSI PELAMAR</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light my-3"
                                id="btn_modal_referensi"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                            <table class="table" id="tabel_referensi" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-start">No.</th>
                                        <th class="text-start">Asal Lowongan</th>
                                        <th class="text-start">TEMPAT / Link</th>
                                        <th class="text-start">Option</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_tambah_recruitment" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-sm">
            <form class="modal-content" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="backDropModalTitle">Tambah Ujian</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-2">
                            <div class="modal-body text-center">
                                <a href="{{ url('/pg-data-ujian/ujian_pg/' . $holding) }}" class="btn btn-primary">Pilihan
                                    Ganda</a>
                                <a href="{{ url('/pg-data-ujian/ujian_essay/' . $holding) }}"
                                    class="btn btn-primary ml-2">Essay</a>
                            </div>
                        </div>
                    </div>
                    <button type="button" style="float: right" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- modal tambah kategori --}}
    <div class="modal fade" id="modal_tambah_kategori" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">TAMBAH KATEGORI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="nama_kategori_add" name="nama_kategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_kategori">Masukkan
                        Kategori</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal tambah kategori --}}
    {{-- modal update kategori --}}
    <div class="modal fade" id="modal_update_kategori" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">UPDATE KATEGORI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="nama_kategori_update" name="nama_kategori"
                                required>
                            <input type="hidden" class="form-control" id="id_update" name="id">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_kategori">Masukkan
                        Kategori</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal update kategori --}}
    {{-- modal update Pembobotan --}}
    <div class="modal fade" id="modal_update_pembobotan" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">UPDATE PEMBOBOTAN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">Penilaian total harus Pas 100</p>
                    <form method="post" enctype="multipart/form-data">
                        <div id="pembobotan_perhitungan">
                            <div class="mb-3">
                                <label>PILIHAN GANDA</label>
                                <input type="number" class="form-control" id="esai_update" name="esai" required>
                                <input type="hidden" class="form-control" id="pembobotan_id_update"
                                    name="pembobotan_id">
                            </div>
                            <div class="mb-3">
                                <label>ESAI</label>
                                <input type="number" class="form-control" id="pilihan_ganda_update"
                                    name="pilihan_ganda" required>
                            </div>
                            <div class="mb-3">
                                <label>INTERVIEW</label>
                                <input type="number" class="form-control" id="interview_update" name="interview"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label>INTERVIEW USER</label>
                                <input type="number" class="form-control" id="interview_user_update"
                                    name="interview_user" required>
                            </div>
                            <div class="mb-3">
                                <label>TOTAL</label>
                                <input type="number" class="form-control" id="total_update" name="total" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_pembobotan">Masukkan
                        pembobotan</button>
                    <p class="text-danger" id="alert-samadengan">*Penilaian kurang atau lebih dari 100</p>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal update pembobotan --}}
    {{-- modal tambah interview --}}
    <div class="modal fade" id="modal_tambah_interview" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">TAMBAH SOAL INTERVIEW</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>PARAMETER</label>
                            <textarea type="text" class="form-control" id="parameter_add" name="parameter" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>PENJELASAN</label>
                            <textarea type="text" class="form-control" id="deskripsi_add" name="deskripsi" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_interview">Masukkan
                        Parameter</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal tambah interview --}}
    {{-- modal update interview --}}
    <div class="modal fade" id="modal_update_interview" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">TAMBAH SOAL INTERVIEW</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>PARAMETER</label>
                            <input type="hidden" id="id_update" name="id">
                            <textarea type="text" class="form-control" id="parameter_update" name="parameter" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>PENJELASAN</label>
                            <textarea type="text" class="form-control" id="deskripsi_update" name="deskripsi" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_interview">Masukkan
                        Parameter</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal update interview --}}
    {{-- modal referensi Pelamar --}}
    <div class="modal fade" id="modal_referensi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">TAMBAH SOAL INTERVIEW</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>ASAL LOWONGAN</label>
                            {{-- <input type="hidden" id="id_update" name="id"> --}}
                            <input type="text" class="form-control" id="alamat_add" name="alamat" required />
                        </div>
                        <div class="mb-3">
                            <label>TEMPAT / LINK</label>
                            <textarea type="text" class="form-control" id="tempat_link_add" name="tempat_link"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_add_referensi">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal referensi Pelamar --}}
    {{-- modal edit referensi Pelamar --}}
    <div class="modal fade" id="modal_update_referensi" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">TAMBAH SOAL INTERVIEW</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>ASAL LOWONGAN</label>
                            <input type="hidden" id="id_referensi" name="id">
                            <input type="text" class="form-control" id="alamat_update" name="alamat" required />
                        </div>
                        <div class="mb-3">
                            <label>TEMPAT / LINK</label>
                            <textarea type="text" class="form-control" id="tempat_link_update" name="tempat_link"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_referensi">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal edit referensi Pelamar --}}


    {!! session('pesan') !!}
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $("input[type=text]").keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        let holding = window.location.pathname.split("/").pop();
        var table = $('#table_ujian').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt-data-list-ujian') }}" + '/' + holding,
            },
            columns: [{
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'nol',
                    name: 'nol'
                },
                {
                    data: 'satu',
                    name: 'satu'
                },
                {
                    data: 'dua',
                    name: 'dua'
                },
                {
                    data: 'tiga',
                    name: 'tiga'
                },
                {
                    data: 'empat',
                    name: 'empat'
                },
                {
                    data: 'lima',
                    name: 'lima'
                },
                {
                    data: 'enam',
                    name: 'enam'
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-0').on('shown.bs.tab', function(e) {
            table.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table1 = $('#table_esai').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt-data-list-esai') }}" + '/' + holding,
            },
            columns: [{
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'nol',
                    name: 'nol'
                },
                {
                    data: 'satu',
                    name: 'satu'
                },
                {
                    data: 'dua',
                    name: 'dua'
                },
                {
                    data: 'tiga',
                    name: 'tiga'
                },
                {
                    data: 'empat',
                    name: 'empat'
                },
                {
                    data: 'lima',
                    name: 'lima'
                },
                {
                    data: 'enam',
                    name: 'enam'
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-1').on('shown.bs.tab', function(e) {
            table1.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table2 = $('#tabel_interview').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt-data-list-interview_admin') }}" + '/' + holding,
            },
            columns: [{
                    data: 'parameter',
                    name: 'parameter',
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-2').on('shown.bs.tab', function(e) {
            table2.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });

        var table3 = $('#tabel_ujian_kategori').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt-data-list-ujian_kategori') }}" + '/' + holding,
            },
            columns: [{
                    data: 'nama_kategori',
                    name: 'nama_kategori',
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-3').on('shown.bs.tab', function(e) {
            table3.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table4 = $('#tabel_pembobotan').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/pg-data-dt_pembobotan') }}"
            },
            columns: [{
                    data: 'esai',
                    name: 'esai',
                },
                {
                    data: 'pilihan_ganda',
                    name: 'pilihan_ganda',
                },
                {
                    data: 'interview',
                    name: 'interview',
                },
                {
                    data: 'interview_user',
                    name: 'interview_user'
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });

        $('#icon-tab-4').on('shown.bs.tab', function(e) {
            table4.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table5 = $('#tabel_referensi').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt_referensi') }}"
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                },
                {
                    data: 'tempat_link',
                    name: 'tempat_link',
                },
                {
                    data: 'option',
                    name: 'option',
                },
            ]
        });

        $('#icon-tab-5').on('shown.bs.tab', function(e) {
            table5.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        $('#btn_modal_kategori').click(function() {
            console.log('asoy');
            $('#modal_tambah_kategori').modal('show');
        });
        $(document).on('click', '#btn_edit_ujian_kategori', function() {
            var id = $(this).data('id');
            $('#id_update').val(id);
            $('#modal_update_kategori').modal('show');

        });
        $('#btn_save_kategori').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            //ambil data dari form
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('nama_kategori', $('#nama_kategori_add').val());

            // post
            $.ajax({
                type: "POST",

                url: "{{ url('/ujian_kategori_post') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong!');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_tambah_kategori').modal('hide');
                        $('#nama_kategori_add').val('');
                        $('#tabel_ujian_kategori').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_tambah_kategori').modal('hide');

                        $('#tabel_ujian_kategori').DataTable().ajax.reload();


                    } else {
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
        $(document).on('click', '#btn_delete_ujian_kategori', function() {
            // $('#modal_delete_riwayat').modal('show');
            var id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi',
                icon: 'warning',
                text: "Apakah benar-benar ingin menghapus data ini?",
                showCancelButton: true,
                inputValue: 0,
                confirmButtonText: 'Yes',
            }).then(function(result) {
                if (result.value) {
                    // console.log(id);
                    Swal.fire({
                        title: 'Harap Tuggu Sebentar!',
                        html: 'Proses Menghapus Data...', // add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                            $.ajax({
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    id: id,
                                },
                                url: "{{ url('/delete_ujian_kategori') }}",
                                type: "POST",
                                dataType: 'json',
                                success: function(data) {
                                    if (data.code == 200) {
                                        $('#tabel_ujian_kategori').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'success',
                                            text: 'Data Berhasil dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    } else {
                                        $('#tabel_ujian_kategori').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'error',
                                            text: 'Data gagal dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    }
                                },
                                error: function(data) {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Data Gagal dihapus',
                                        icon: 'error',
                                        timer: 1500
                                    })
                                }
                            });
                        },
                    });

                } else {
                    Swal.fire({
                        title: 'Gagal !',
                        text: 'Data gagal dihapus',
                        icon: 'warning',
                        timer: 1500
                    })
                }

            });
        });
        $(document).on('click', '#btn_edit_ujian_kategori', function() {
            var id = $(this).data('id');
            var nama_kategori = $(this).data('nama_kategori');
            console.log(nama_kategori);
            $('#id_update').val(id);
            $('#nama_kategori_update').val(nama_kategori);
            $('#modal_update_kategori').modal('show');

        });
        $('#btn_update_kategori').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('nama_kategori', $('#nama_kategori_update').val());
            formData.append('id', $('#id_update').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/ujian_kategori_update') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#nama_kategori_update').val('');
                        $('#modal_update_kategori').modal('hide');
                        $('#tabel_ujian_kategori').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_update_kategori').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
        // pembobotan
        // console.log(esai);
        $('#alert-samadengan').hide();
        $('#btn_update_pembobotan').prop('disabled', true);
        $('#pembobotan_perhitungan').on('keyup', function() {
            var esai = $('#esai_update').val();
            var pilihan_ganda = $('#pilihan_ganda_update').val();
            var interview = $('#interview_update').val();
            var interview_user = $('#interview_user_update').val();

            var total = parseInt(esai) + parseInt(pilihan_ganda) + parseInt(interview) + parseInt(interview_user);
            $('#total_update').val(total);
            if (total == '100') {
                $('#btn_update_pembobotan').prop('disabled', false);
            } else {
                $('#alert-samadengan').show();
                $('#btn_update_pembobotan').prop('disabled', true);

            }

        });
        $(document).on('click', '#btn_modal_pembobotan', function() {
            var pembobotan_id = $(this).data('pembobotan_id');
            var esai = $(this).data('esai');
            var pilihan_ganda = $(this).data('pilihan_ganda');
            var interview = $(this).data('interview');
            var interview_user = $(this).data('interview_user');
            // console.log(pembobotan_id);
            $('#pembobotan_id_update').val(pembobotan_id);
            $('#esai_update').val(esai);
            $('#pilihan_ganda_update').val(pilihan_ganda);
            $('#interview_user_update').val(interview_user);
            $('#interview_update').val(interview);
            $('#modal_update_pembobotan').modal('show');

        });
        $('#btn_update_pembobotan').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('pembobotan_id', $('#pembobotan_id_update').val());
            formData.append('esai', $('#esai_update').val());
            formData.append('pilihan_ganda', $('#pilihan_ganda_update').val());
            formData.append('interview', $('#interview_update').val());
            formData.append('interview_user', $('#interview_user_update').val());
            formData.append('id', $('#id_update').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/pg-data-pembobotan_post') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#esai_update').val('');
                        $('#pilihan_ganda_update').val('');
                        $('#interview_update').val('');
                        $('#interview_user_update').val('');
                        $('#modal_update_pembobotan').modal('hide');
                        $('#tabel_pembobotan').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_update_pembobotan').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
        // pembobotan end
        // interview
        $('#btn_modal_interview').click(function() {
            // console.log('asoy');
            $('#modal_tambah_interview').modal('show');
        });
        $('#btn_save_interview').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            //ambil data dari form
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('parameter', $('#parameter_add').val());
            formData.append('deskripsi', $('#deskripsi_add').val());

            // post
            $.ajax({
                type: "POST",

                url: "{{ url('/interview_admin_post') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong!');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_tambah_interview').modal('hide');
                        $('#parameter_add').val('');
                        $('#deskripsi_add').val('');
                        $('#tabel_interview').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        $('#modal_tambah_interview').modal('hide');

                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_tambah_interview').modal('hide');

                        $('#tabel_interview').DataTable().ajax.reload();


                    } else {
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
        $(document).on('click', '#btn_delete_interview', function() {
            // $('#modal_delete_riwayat').modal('show');
            var id = $(this).data('id');
            // console.log(id);
            Swal.fire({
                title: 'Konfirmasi',
                icon: 'warning',
                text: "Apakah benar-benar ingin menghapus data ini?",
                showCancelButton: true,
                inputValue: 0,
                confirmButtonText: 'Yes',
            }).then(function(result) {
                if (result.value) {
                    // console.log(id);
                    Swal.fire({
                        title: 'Harap Tuggu Sebentar!',
                        html: 'Proses Menghapus Data...', // add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                            $.ajax({
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    id: id,
                                },
                                url: "{{ url('/interview_admin_delete') }}",
                                type: "POST",
                                dataType: 'json',
                                success: function(data) {
                                    if (data.code == 200) {
                                        $('#tabel_interview').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'success',
                                            text: 'Data Berhasil dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    } else {
                                        $('#tabel_interview').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'error',
                                            text: 'Data gagal dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    }
                                },
                                error: function(data) {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Data Gagal dihapus',
                                        icon: 'error',
                                        timer: 1500
                                    })
                                }
                            });
                        },
                    });

                } else {
                    Swal.fire({
                        title: 'Gagal !',
                        text: 'Data gagal dihapus',
                        icon: 'warning',
                        timer: 1500
                    })
                }

            });
        });
        $(document).on('click', '#btn_update_interview', function() {
            var id = $(this).data('id');
            var parameter = $(this).data('parameter');
            var deskripsi = $(this).data('deskripsi');
            $('#id_update').val(id);
            $('#parameter_update').val(parameter);
            $('#deskripsi_update').val(deskripsi);
            $('#modal_update_interview').modal('show');

        });
        $('#btn_update_interview').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('parameter', $('#parameter_update').val());
            formData.append('deskripsi', $('#deskripsi_update').val());
            formData.append('id', $('#id_update').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/interview_admin_update') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#parameter_update').val('');
                        $('#deskripsi_update').val('');
                        $('#modal_update_interview').modal('hide');
                        $('#tabel_interview').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_update_interview').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
        // interview end
        // Referensi
        $('#btn_modal_referensi').click(function() {
            console.log('asoy');
            $('#modal_referensi').modal('show');
        });
        $(document).on('click', '#btn_edit_referensi', function() {
            var id_referensi = $(this).data('id_referensi');
            var alamat = $(this).data('alamat');
            var tempat_link = $(this).data('tempat_link');
            $('#id_referensi').val(id_referensi);
            $('#alamat_update').val(alamat);
            $('#tempat_link_update').val(tempat_link);
            $('#modal_update_referensi').modal('show');

        });
        $('#btn_add_referensi').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('alamat', $('#alamat_add').val());
            formData.append('tempat_link', $('#tempat_link_add').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/referensi_add') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#alamat_add').val('');
                        $('#tempat_link_add').val('');
                        $('#modal_referensi').modal('hide');
                        $('#tabel_referensi').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_referensi').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
        $('#btn_update_referensi').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('alamat', $('#alamat_update').val());
            formData.append('tempat_link', $('#tempat_link_update').val());
            formData.append('id_referensi', $('#id_referensi').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/referensi_update') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_update_referensi').modal('hide');
                        $('#tabel_referensi').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_update_referensi').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
        $(document).on('click', '#btn_delete_referensi', function() {
            // $('#modal_delete_riwayat').modal('show');
            var id = $(this).data('id_referensi');
            // console.log(id);
            Swal.fire({
                title: 'Konfirmasi',
                icon: 'warning',
                text: "Apakah benar-benar ingin menghapus data ini?",
                showCancelButton: true,
                inputValue: 0,
                confirmButtonText: 'Yes',
            }).then(function(result) {
                if (result.value) {
                    // console.log(id);
                    Swal.fire({
                        title: 'Harap Tuggu Sebentar!',
                        html: 'Proses Menghapus Data...', // add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                            $.ajax({
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    id: id,
                                },
                                url: "{{ url('/delete_referensi') }}",
                                type: "POST",
                                dataType: 'json',
                                success: function(data) {
                                    if (data.code == 200) {
                                        $('#tabel_referensi').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'success',
                                            text: 'Data Berhasil dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    } else {
                                        $('#tabel_referensi').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'error',
                                            text: 'Data gagal dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    }
                                },
                                error: function(data) {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Data Gagal dihapus',
                                        icon: 'error',
                                        timer: 1500
                                    })
                                }
                            });
                        },
                    });

                } else {
                    Swal.fire({
                        title: 'Gagal !',
                        text: 'Data gagal dihapus',
                        icon: 'warning',
                        timer: 1500
                    })
                }

            });
        });
        // Referensi End
    </script>
@endsection
