@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
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
                        <h5 class="card-title m-0 me-2">DATA ABSENSI KARYAWAN ({{$data_user->name}})</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <form action="{{ url('/rekap-data/'.$holding) }}">
                        <div class="row g-3 text-center">
                            <div class="col-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="month" class="form-control" name="month_filter" placeholder="Filter By Month:" id="month_filter" value="{{ date('Y-m') }}">
                                    <label for="month_filter">Filter By Month:</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <button class="btn btn-sm btn-success waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="menu-icon tf-icons mdi mdi-file-excel"></i> Excel
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_import_absensi" href="">Import Excel</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_export_absensi" href="#">Eksport Excel</a></li>
                                </ul>
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-printer"></i>cetak</button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-5">
                    <div class="nav-align-top">
                        <table class="table" id="table_rekapdata_detail" style="width: 100%;">
                            <thead class="table-primary">
                                <tr>
                                    <th rowspan="2" class="text-center">No.</th>
                                    <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                    <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                    <th colspan="2" class="text-center">&nbsp;Shift&nbsp;</th>
                                    <th colspan="6" class="text-center">Absensi</th>
                                    <th colspan="2" class="text-center">Foto&nbsp;Absen</th>
                                    <th rowspan="2" class="text-center">Total&nbsp;Jam&nbsp;Kerja</th>
                                    <th rowspan="2" class="text-center">Status&nbsp;Absen</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Absen</th>
                                    <th rowspan="2" class="text-center">Kelengkapan&nbsp;Absensi</th>
                                </tr>
                                <tr>
                                    <th>Nama&nbsp;Shift</th>
                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jam&nbsp;Kerja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Tanggal&nbsp;Masuk</th>
                                    <th>Jam&nbsp;Masuk</th>
                                    <th>Telat</th>
                                    <th>Tanggal&nbsp;Pulang</th>
                                    <th>Jam&nbsp;Pulang</th>
                                    <th>Pulang&nbsp;Cepat</th>
                                    <th>Absen&nbsp;Masuk</th>
                                    <th>Absen&nbsp;Pulang</th>
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
    @endsection
    @section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        let id = '{{$data_user->id}}';
        console.log(id);
        let holding = window.location.pathname.split("/").pop();
        $(document).ready(function() {
            $('#month_filter').change(function() {
                filter_month = $(this).val();
                $('#table_rekapdata_detail').DataTable().destroy();
                load_data(filter_month);


            })
            load_data();

            function load_data(filter_month = '') {
                console.log(filter_month);
                var table = $('#table_rekapdata_detail').DataTable({
                    pageLength: 50,
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('rekapdata-detail_datatable') }}" + '/' + id + '/' + holding,
                        data: {
                            filter_month: filter_month,
                        },
                    },
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'nik_karyawan',
                            name: 'nik_karyawan'
                        },
                        {
                            data: 'nama_karyawan',
                            name: 'nama_karyawan'
                        },
                        {
                            data: 'nama_shift',
                            name: 'nama_shift'
                        },
                        {
                            data: 'jam_kerja',
                            name: 'jam_kerja'
                        },
                        {
                            data: 'tanggal_masuk',
                            name: 'tanggal_masuk'
                        },
                        {
                            data: 'jam_absen',
                            name: 'jam_absen'
                        },
                        {
                            data: 'telat',
                            name: 'telat'
                        },
                        {
                            data: 'tanggal_pulang',
                            name: 'tanggal_pulang'
                        },
                        {
                            data: 'jam_pulang',
                            name: 'jam_pulang'
                        },
                        {
                            data: 'pulang_cepat',
                            name: 'pulang_cepat'
                        },
                        {
                            data: 'foto_jam_absen',
                            name: 'foto_jam_absen'
                        },
                        {
                            data: 'foto_jam_pulang',
                            name: 'foto_jam_pulang'
                        },
                        {
                            data: 'total_jam_kerja',
                            name: 'total_jam_kerja'
                        },
                        {
                            data: 'status_absen',
                            name: 'status_absen'
                        },
                        {
                            data: 'keterangan_absensi',
                            name: 'keterangan_absensi'
                        },
                        {
                            data: 'kelengkapan_absensi',
                            name: 'kelengkapan_absensi'
                        },

                    ],
                    order: [
                        [2, 'asc']
                    ]
                });
            }
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                table.columns.adjust().draw().responsive.recalc();
                // table.draw();
            })
        });
    </script>
    <script>
    </script>
    @endsection