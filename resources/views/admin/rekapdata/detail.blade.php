@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />
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

                            <!-- <div class="col-6">
                                <button class="btn btn-sm btn-success waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="menu-icon tf-icons mdi mdi-file-excel"></i> Excel
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_import_absensi" href="">Import Excel</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_export_absensi" href="#">Eksport Excel</a></li>
                                </ul>
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-printer"></i>cetak</button>
                            </div> -->
                        </div>
                    </form>
                    <hr class="my-5">
                    <div class="nav-align-top">
                        <table class="table" id="table_rekapdata_detail" style="width: 100%; font-size: small;">
                            <thead class="table-primary">
                                <tr>
                                    <th rowspan="2" class="text-center">No.</th>
                                    <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                    <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                    <th colspan="2" class="text-center">&nbsp;Shift&nbsp;</th>
                                    <th colspan="10" class="text-center">Absensi</th>
                                    <th colspan="2" class="text-center">Foto&nbsp;Absen</th>
                                    <th rowspan="2" class="text-center">Total&nbsp;Jam&nbsp;Kerja</th>
                                    <th rowspan="2" class="text-center">Status&nbsp;Absen</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Absen</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Izin</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Cuti</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Penugasan</th>
                                    <th rowspan="2" class="text-center">File&nbsp;Form</th>
                                </tr>
                                <tr>
                                    <th>Nama&nbsp;Shift</th>
                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jam&nbsp;Kerja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th>Tanggal&nbsp;Masuk</th>
                                    <th>Jam&nbsp;Masuk</th>
                                    <th>Keterangan&nbsp;Absen&nbsp;Masuk</th>
                                    <th>Lokasi&nbsp;Absen</th>
                                    <th>Terlambat</th>
                                    <th>Tanggal&nbsp;Pulang</th>
                                    <th>Jam&nbsp;Pulang</th>
                                    <th>Keterangan&nbsp;Absen&nbsp;Pulang</th>
                                    <th>Lokasi&nbsp;Absen</th>
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
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
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

            function newexportaction(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                dt.one('preXhr', function(e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = 2147483647;
                    dt.one('preDraw', function(e, settings) {
                        // Call the original action function
                        if (button[0].className.indexOf('buttons-copy') >= 0) {
                            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-print') >= 0) {
                            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                        }
                        dt.one('preXhr', function(e, s, data) {
                            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                            // Set the property to what it was before exporting.
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                        });
                        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                        setTimeout(dt.ajax.reload, 0);
                        // Prevent rendering of the full data to the DOM
                        return false;
                    });
                });
                // Requery the server with the new one-time export settings
                dt.ajax.reload();
            }

            function load_data(filter_month = '') {
                // console.log(filter_month);
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
                    dom: 'Blfrtip',
                    buttons: [{

                            extend: 'excelHtml5',
                            className: 'btn btn-sm btn-success',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                            titleAttr: 'Excel',
                            title: 'DATA ABSENSI KARYAWAN {{$data_user->name}}',
                            messageTop: 'Bulan : '.filter_month,
                            action: newexportaction,
                            exportOptions: {
                                columns: ':not(:first-child)',
                                columns: ':not(:last-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var n = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
                                return 'REKAP_DATA_ABSENSI_KARYAWAN_{{$data_user->name}}_' + l + ' ' + n;
                            },
                        },
                        {

                            extend: 'pdf',
                            className: 'btn btn-sm btn-danger',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-pdf-box"></i>PDF',
                            titleAttr: 'PDF',
                            title: 'DATA ABSENSI KARYAWAN {{$data_user->name}}',
                            orientation: 'potrait',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: ':not(:first-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var n = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                                return 'REKAP_DATA_ABSENSI_KARYAWAN_{{$data_user->name}}_' + l + ' ' + n;
                            },
                        }, {
                            extend: 'print',
                            className: 'btn btn-sm btn-info',
                            title: 'DATA ABSENSI KARYAWAN {{$data_user->name}}',
                            text: '<i class="menu-icon tf-icons mdi mdi-printer-pos-check-outline"></i>PRINT',
                            titleAttr: 'PRINT',
                        }, {
                            extend: 'copy',
                            title: 'DATA ABSENSI KARYAWAN {{$data_user->name}}',
                            className: 'btn btn-sm btn-secondary',
                            text: '<i class="menu-icon tf-icons mdi mdi-content-copy"></i>COPY',
                            titleAttr: 'COPY',
                        }
                    ],
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
                            data: 'keterangan_absensi',
                            name: 'keterangan_absensi'
                        },
                        {
                            data: 'lokasi_absen',
                            name: 'lokasi_absen'
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
                            data: 'keterangan_absensi_pulang',
                            name: 'keterangan_absensi_pulang'
                        },
                        {
                            data: 'lokasi_absen_pulang',
                            name: 'lokasi_absen_pulang'
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
                            data: 'kelengkapan_absensi',
                            name: 'kelengkapan_absensi'
                        },
                        {
                            data: 'keterangan_izin',
                            name: 'keterangan_izin'
                        },
                        {
                            data: 'keterangan_cuti',
                            name: 'keterangan_cuti'
                        },
                        {
                            data: 'keterangan_dinas',
                            name: 'keterangan_dinas'
                        },
                        {
                            data: 'file_form',
                            name: 'file_form'
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