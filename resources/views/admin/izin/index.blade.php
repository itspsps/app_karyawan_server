@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    .my-swal {
        z-index: X;
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
                        <h5 class="card-title m-0 me-2">REKAP FORM IZIN KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <form action="{{ url('/rekap-data/'.$holding) }}">
                        <div class="row g-3 text-center">
                            <div class="col-2">
                            </div>
                            <div class="col-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="date_filter" placeholder="Date Filter" id="date_filter" readonly>
                                    <label for="date_filter">Date Range Filter</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <!-- <button type="submit" id="search" class="btn btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-filter"></i></button> -->

                                <a href="javascript:void(0);" class="btn btn-sm btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_export_izin" type="button">
                                    <i class="menu-icon tf-icons mdi mdi-file-excel"></i> Excel
                                </a>
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-printer"></i>cetak</button>
                            </div>
                            <div class="col-2">
                            </div>
                        </div>
                    </form>
                    <div class="modal fade" id="modal_export_izin" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog modal-lg">
                            <form method="post" action="{{ url('/rekapdata/ImportAbsensi/'.$holding) }}" class="modal-content" style="height: 300px;" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Ekspor Excel Data Izin</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col-12 mb-2">
                                            <button class="btn btn-sm btn-success waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="menu-icon tf-icons mdi mdi-file-excel"></i> Ketegori Izin
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{url('izin/ExportIzin/Datang Terlambat/'.$holding)}}">TERLAMBAT</a></li>
                                                <li><a class="dropdown-item" href="{{url('izin/ExportIzin/Pulang Cepat/'.$holding)}}">PULANG CEPAT</a></li>
                                                <li><a class="dropdown-item" href="{{url('izin/ExportIzin/Keluar Kantor/'.$holding)}}">KELUAR KANTOR</a></li>
                                                <li><a class="dropdown-item" href="{{url('izin/ExportIzin/Sakit/'.$holding)}}">SAKIT</a></li>
                                                <li><a class="dropdown-item" href="{{url('izin/ExportIzin/Tidak Masuk/'.$holding)}}">TIDAK MASUK</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_lihat_ttd_pengajuan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="backDropModalTitle">TTD Pengajuan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="text-center">
                                            <img id="ttd_pengajuan" src="" width="200" height="200" alt="">
                                            <h6 id="nama_pengajuan"></h6>
                                            <h6 id="tgl_pengajuan"></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_lihat_ttd_atasan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="backDropModalTitle">TTD Atasan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <div class="text-center">
                                            <img id="ttd_atasan" src="" width="200" height="200" alt="">
                                            <h6 id="nama_atasan"></h6>
                                            <h6 id="tgl_atasan"></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">
                    <div class="nav-align-top">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav nav-pills nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a type="button" style="width: auto;" class="btn btn-sm nav-link active" role="tab" data-bs-toggle="tab" href="#navs_terlambat">
                                            <i class="tf-icons mdi mdi-clipboard-text-clock me-1"></i><span class="d-none d-sm-block">TERLAMBAT</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" style="width: min-content; margin-left: -3%;">
                                        <a type="button" style="width: auto;" class="btn btn-sm nav-link" role="tab" data-bs-toggle="tab" href="#navs_pulangcepat">
                                            <i class="tf-icons mdi mdi-clipboard-text-clock me-1"></i><span class="d-none d-sm-block">PULANG CEPAT</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" style="width: min-content; margin-left: -3%;">
                                        <a type="button" style="width: auto;" class="btn btn-sm nav-link" role="tab" data-bs-toggle="tab" href="#navs_keluarkantor">
                                            <i class="tf-icons mdi mdi-clipboard-text-clock me-1"></i><span class="d-none d-sm-block">KELUAR KANTOR</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" style="width: min-content; margin-left: -3%;">
                                        <a type="button" style="width: auto;" class="btn btn-sm nav-link" role="tab" data-bs-toggle="tab" href="#navs_sakit">
                                            <i class="tf-icons mdi mdi-emoticon-sick-outline me-1"></i><span class="d-none d-sm-block">SAKIT</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" style="width: min-content; margin-left: -3%;">
                                        <a type="button" style="width: auto;" class="btn btn-sm nav-link" role="tab" data-bs-toggle="tab" href="#navs_tidak_masuk">
                                            <i class="tf-icons mdi mdi-calendar-range me-1"></i><span class="d-none d-sm-block">TIDAK MASUK</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs_terlambat" role="tabpanel">
                                <table class="table" id="table_izin_terlambat" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">No&nbsp;Form</th>
                                            <th class="text-center">Nama&nbsp;Karyawan</th>
                                            <th class="text-center">Departemen</th>
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Jam&nbsp;Masuk&nbsp;Kerja</th>
                                            <th class="text-center">Jam&nbsp;Absen</th>
                                            <th class="text-center">Total&nbsp;Terlambat</th>
                                            <th class="text-center">Keterangan&nbsp;Izin</th>
                                            <th class="text-center">TTD&nbsp;Pengajuan</th>
                                            <th class="text-center">Nama&nbsp;Approve</th>
                                            <th class="text-center">TTD&nbsp;Approve</th>
                                            <th class="text-center">Catatan&nbsp;Approve</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs_pulangcepat" role="tabpanel">
                                <table class="table" id="table_izin_pulangcepat" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">No&nbsp;Form</th>
                                            <th class="text-center">Nama&nbsp;Karyawan</th>
                                            <th class="text-center">Departemen</th>
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Jam&nbsp;Pulang&nbsp;Cepat</th>
                                            <th class="text-center">Keterangan&nbsp;Izin</th>
                                            <th class="text-center">TTD&nbsp;Pengajuan</th>
                                            <th class="text-center">Nama&nbsp;Approve</th>
                                            <th class="text-center">TTD&nbsp;Approve</th>
                                            <th class="text-center">Catatan&nbsp;Approve</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs_keluarkantor" role="tabpanel">
                                <table class="table" id="table_izin_keluarkantor" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">No&nbsp;Form</th>
                                            <th class="text-center">Nama&nbsp;Karyawan</th>
                                            <th class="text-center">Departemen</th>
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Jam&nbsp;Keluar</th>
                                            <th class="text-center">Jam&nbsp;Kembali</th>
                                            <th class="text-center">Keterangan&nbsp;Izin</th>
                                            <th class="text-center">TTD&nbsp;Pengajuan</th>
                                            <th class="text-center">Nama&nbsp;Approve</th>
                                            <th class="text-center">TTD&nbsp;Approve</th>
                                            <th class="text-center">Catatan&nbsp;Approve</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs_sakit" role="tabpanel">
                                <table class="table" id="table_izin_sakit" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">Foto&nbsp;Surat&nbsp;Dokter</th>
                                            <th class="text-center">Nama&nbsp;Karyawan</th>
                                            <th class="text-center">Departemen</th>
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Keterangan&nbsp;Izin</th>
                                            <th class="text-center">TTD&nbsp;Pengajuan</th>
                                            <th class="text-center">Nama&nbsp;Approve</th>
                                            <th class="text-center">TTD&nbsp;Approve</th>
                                            <th class="text-center">Catatan&nbsp;Approve</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs_tidak_masuk" role="tabpanel">
                                <table class="table" id="table_izin_tidak_masuk" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">No&nbsp;Form</th>
                                            <th class="text-center">Nama&nbsp;Karyawan</th>
                                            <th class="text-center">Departemen</th>
                                            <th class="text-center">Divisi</th>
                                            <th class="text-center">Jabatan</th>
                                            <th class="text-center">Tanggal&nbsp;Mulai</th>
                                            <th class="text-center">Tanggal&nbsp;Selasai</th>
                                            <th class="text-center">Nama&nbsp;Pengganti</th>
                                            <th class="text-center">Catatan</th>
                                            <th class="text-center">Keterangan&nbsp;Izin</th>
                                            <th class="text-center">TTD&nbsp;Pengajuan</th>
                                            <th class="text-center">Nama&nbsp;Approve</th>
                                            <th class="text-center">TTD&nbsp;Approve</th>
                                            <th class="text-center">Catatan&nbsp;Approve</th>
                                            <th class="text-center">Status</th>
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
    </div>
    @endsection
    @section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        let holding = window.location.pathname.split("/").pop();
        $(document).ready(function() {
            $('#date_filter').change(function() {
                filter_month = $(this).val();
                $('#table_izin_terlambat').DataTable().destroy();
                $('#table_izin_pulangcepat').DataTable().destroy();
                $('#table_izin_sakit').DataTable().destroy();
                $('#table_izin_keluarkantor').DataTable().destroy();
                $('#table_izin_tidak_masuk').DataTable().destroy();
                load_data(filter_month);
            })
            load_data();

            function load_data(filter_month = '') {
                // console.log(filter_month);
                var table = $('#table_izin_terlambat').DataTable({
                    pageLength: 50,
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    autoWidth: false,
                    responsive: true,
                    serverSide: true,
                    aaSorting: [
                        [6, 'asc'],
                        [8, 'asc'],
                    ],
                    ajax: {
                        url: "{{ url('izin/datatable-terlambat') }}" + '/' + holding,
                        data: {
                            filter_month: filter_month,
                        }
                    },
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'no_form_izin',
                            name: 'no_form_izin'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
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
                            data: 'nama_jabatan',
                            name: 'nama_jabatan'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'jam_masuk_kerja',
                            name: 'jam_masuk_kerja'
                        },
                        {
                            data: 'jam',
                            name: 'jam'
                        },
                        {
                            data: 'terlambat',
                            name: 'terlambat'
                        },
                        {
                            data: 'keterangan_izin',
                            name: 'keterangan_izin'
                        },
                        {
                            data: 'btn_lihat_ttd_pengajuan',
                            name: 'btn_lihat_ttd_pengajuan'
                        },
                        {
                            data: 'approve_atasan',
                            name: 'approve_atasan'
                        },
                        {
                            data: 'btn_lihat_ttd_atasan',
                            name: 'btn_lihat_ttd_atasan'
                        },
                        {
                            data: 'catatan',
                            name: 'catatan'
                        },
                        {
                            data: 'status_izin',
                            name: 'status_izin'
                        },

                    ],

                });
                var table1 = $('#table_izin_pulangcepat').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    autoWidth: false,
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [6, 'asc'],
                        [7, 'asc'],
                    ],
                    ajax: {
                        url: "{{ url('izin/datatable-pulangcepat') }}" + '/' + holding,
                    },
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'no_form_izin',
                            name: 'no_form_izin'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
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
                            data: 'nama_jabatan',
                            name: 'nama_jabatan'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'pulang_cepat',
                            name: 'pulang_cepat'
                        },
                        {
                            data: 'keterangan_izin',
                            name: 'keterangan_izin'
                        },
                        {
                            data: 'btn_lihat_ttd_pengajuan',
                            name: 'btn_lihat_ttd_pengajuan'
                        },
                        {
                            data: 'approve_atasan',
                            name: 'approve_atasan'
                        },
                        {
                            data: 'btn_lihat_ttd_atasan',
                            name: 'btn_lihat_ttd_atasan'
                        },
                        {
                            data: 'catatan',
                            name: 'catatan'
                        },
                        {
                            data: 'status_izin',
                            name: 'status_izin'
                        },

                    ],

                });
                var table2 = $('#table_izin_sakit').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    autoWidth: false,
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [6, 'asc'],
                    ],
                    ajax: {
                        url: "{{ url('izin/datatable-sakit') }}" + '/' + holding,
                    },
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'foto_izin',
                            name: 'foto_izin'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
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
                            data: 'nama_jabatan',
                            name: 'nama_jabatan'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'keterangan_izin',
                            name: 'keterangan_izin'
                        },
                        {
                            data: 'btn_lihat_ttd_pengajuan',
                            name: 'btn_lihat_ttd_pengajuan'
                        },
                        {
                            data: 'approve_atasan',
                            name: 'approve_atasan'
                        },
                        {
                            data: 'btn_lihat_ttd_atasan',
                            name: 'btn_lihat_ttd_atasan'
                        },
                        {
                            data: 'catatan',
                            name: 'catatan'
                        },
                        {
                            data: 'status_izin',
                            name: 'status_izin'
                        },

                    ],

                });
                var table3 = $('#table_izin_tidak_masuk').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    autoWidth: false,
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [6, 'asc'],
                    ],
                    ajax: {
                        url: "{{ url('izin/datatable-tidak_masuk') }}" + '/' + holding,
                    },
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'no_form_izin',
                            name: 'no_form_izin'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
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
                            data: 'nama_jabatan',
                            name: 'nama_jabatan'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'tanggal_selesai',
                            name: 'tanggal_selesai'
                        },
                        {
                            data: 'user_name_backup',
                            name: 'user_name_backup'
                        },
                        {
                            data: 'catatan_backup',
                            name: 'catatan_backup'
                        },
                        {
                            data: 'keterangan_izin',
                            name: 'keterangan_izin'
                        },
                        {
                            data: 'btn_lihat_ttd_pengajuan',
                            name: 'btn_lihat_ttd_pengajuan'
                        },
                        {
                            data: 'approve_atasan',
                            name: 'approve_atasan'
                        },
                        {
                            data: 'btn_lihat_ttd_atasan',
                            name: 'btn_lihat_ttd_atasan'
                        },
                        {
                            data: 'catatan',
                            name: 'catatan'
                        },
                        {
                            data: 'status_izin',
                            name: 'status_izin'
                        },

                    ],
                    order: [
                        [2, 'ASC'],
                        [1, 'ASC'],
                    ]
                });
                var table4 = $('#table_izin_keluarkantor').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    autoWidth: false,
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [6, 'asc'],
                    ],
                    ajax: {
                        url: "{{ url('izin/datatable-keluar_kantor') }}" + '/' + holding,
                    },
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'no_form_izin',
                            name: 'no_form_izin'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
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
                            data: 'nama_jabatan',
                            name: 'nama_jabatan'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'jam_keluar',
                            name: 'jam_keluar'
                        },
                        {
                            data: 'jam_kembali',
                            name: 'jam_kembali'
                        },
                        {
                            data: 'keterangan_izin',
                            name: 'keterangan_izin'
                        },
                        {
                            data: 'btn_lihat_ttd_pengajuan',
                            name: 'btn_lihat_ttd_pengajuan'
                        },
                        {
                            data: 'approve_atasan',
                            name: 'approve_atasan'
                        },
                        {
                            data: 'btn_lihat_ttd_atasan',
                            name: 'btn_lihat_ttd_atasan'
                        },
                        {
                            data: 'catatan',
                            name: 'catatan'
                        },
                        {
                            data: 'status_izin',
                            name: 'status_izin'
                        },

                    ],

                });
                document.querySelectorAll('a[data-bs-toggle="tab"]').forEach((el) => {
                    el.addEventListener('shown.bs.tab', () => {
                        DataTable.tables({
                            visible: true,
                            api: true
                        }).columns.adjust();
                    });
                });
            }

        });
    </script>
    <script>
        // console.log(now);
        $('input[id="date_filter"]').daterangepicker({
            drops: 'auto',
            autoUpdateInput: true,
            locale: {
                cancelLabel: 'Clear'
            },
            autoApply: false,
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
        $(document).on("click", "#btn_lihat_ttd_pengajuan", function() {
            $('#modal_lihat_ttd_pengajuan').modal('show');
            let id = $(this).data("id");
            let tgl = $(this).data("tgl");
            let nama = $(this).data("nama");
            let ttd = $(this).data("ttd");
            $('#ttd_pengajuan').attr('src', 'https://hrd.sumberpangan.store:4430/public/signature/izin/' + ttd + '.png');
            $('#nama_pengajuan').html(nama);
            $('#tgl_pengajuan').html(tgl);

        });
        $(document).on("click", "#btn_lihat_ttd_atasan", function() {
            $('#modal_lihat_ttd_atasan').modal('show');
            let id = $(this).data("id");
            let tgl = $(this).data("tgl");
            let nama = $(this).data("nama");
            let ttd = $(this).data("ttd");
            $('#ttd_atasan').attr('src', 'https://hrd.sumberpangan.store:4430/public/signature/izin/' + ttd + '.png');
            $('#nama_atasan').html(nama);
            $('#tgl_atasan').html(tgl);

        });
        $(document).on("click", "#btn_izin_0", function() {
            Swal.fire({
                title: 'Infomasi!',
                text: 'Data Izin Masih Dalam Status Pengajuan.',
                icon: 'info',
                timer: 2500
            })
        });
        $(document).on("click", "#btn_izin_1", function() {
            Swal.fire({
                title: 'Infomasi!',
                text: 'Data Izin Masih Dalam Status Pengajuan Approve.',
                icon: 'info',
                timer: 2500
            })
        });
        $(document).on("click", "#btn_izin_not_approve", function() {
            Swal.fire({
                title: 'Infomasi!',
                text: 'Data Izin Masih Dalam Status Not Approve.',
                icon: 'error',
                timer: 2500
            })
        });
        $(document).on("click", "#btn_edit_shift", function() {
            let id = $(this).data('id');
            let shift = $(this).data("shift");
            let jammasuk = $(this).data("jammasuk");
            let jamkeluar = $(this).data("jamkeluar");
            let holding = $(this).data("holding");
            // console.log(jamkeluar);
            $('#id_shift').val(id);
            $('#nama_shift_update').val(shift);
            $('#jam_masuk_update').val(jammasuk);
            $('#jam_keluar_update').val(jamkeluar);
            $('#modal_edit_shift').modal('show');

        });
        $(document).on('click', '#btn_delete_shift', function() {
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
                        url: "{{ url('/shift/delete/') }}" + '/' + id + '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data anda berhasil di hapus.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_izin_terlambat').DataTable().ajax.reload();
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