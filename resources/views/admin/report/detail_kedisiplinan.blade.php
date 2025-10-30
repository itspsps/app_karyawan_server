@extends('admin.layouts.dashboard')
@section('css')
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    .my-swal {
        z-index: 9999;
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
                    <hr class="my-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-floating form-floating-outline">
                            <div id="reportrange" style="white-space: nowrap;">
                                <button class="btn btn-outline-secondary w-100 ">
                                    <span class="fw-bold">FILTER&nbsp;DATE&nbsp;:&nbsp;</span>
                                    <span class="date_daterange"></span>
                                    <input type="date" id="start_date" name="start_date" hidden value="">
                                    <input type="date" id="end_date" name="end_date" hidden value="">
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="nav-align-top">
                        <table class="table table-hover table-striped" id="table_rekapdata_detail" style="width: 100%; font-size: small;">
                            <thead class="table-primary">
                                <tr>
                                    <th rowspan="2" class="text-center">No.</th>
                                    <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                    <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                    <th colspan="2" class="text-center">&nbsp;Shift&nbsp;</th>
                                    <th colspan="10" class="text-center">Absensi</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Izin</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Cuti</th>
                                    <th rowspan="2" class="text-center">Keterangan&nbsp;Penugasan</th>
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
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('js')
    <!-- JS -->
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>

    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    <script>
        $(document).ready(function() {
            let id = '{{$data_user->nomor_identitas_karyawan}}';
            var holding = '{{$holding->holding_code}}';
            var holding_id = '{{$holding->id}}';
            var chart_absensi_masuk;
            var data_column;

            // var start = moment().startOf('month');
            // var end = moment().endOf('month');
            var start = moment('2025-07-21');
            var end = moment('2025-08-20');
            var lstart, lend;

            function cb(start, end) {
                $('#reportrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                lstart = start.format('YYYY-MM-DD');
                lend = end.format('YYYY-MM-DD');
                $('#start_date').val(lstart);
                $('#end_date').val(lend);
                if ($.fn.DataTable.isDataTable('#table_rekapdata_detail')) {
                    $('#table_rekapdata_detail').DataTable().clear().destroy();
                }
                load_data(lstart, lend);
                // console.log(start, end);

            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

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

            function load_data(start_date = '', end_date = '') {
                // console.log(filter_month);
                var table = $('#table_rekapdata_detail').DataTable({
                    pageLength: 50,
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('report_kedisiplinan/detail_datatable') }}" + '/' + id + '/' + holding,
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                        },
                    },
                    dom: "<'row mb-2'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                            extend: 'excelHtml5',
                            className: 'btn btn-sm btn-outline-light',
                            text: '<i class="mdi mdi-file-excel text-success"></i><span class="text-success">&nbsp;Excel</span>',
                            titleAttr: 'Export ke Excel',
                            title: 'DATA ABSENSI & KEDISIPLINAN KARYAWAN',
                            messageTop: function() {
                                return 'Periode: ' + start_date + ' s/d ' + end_date;
                            },
                            exportOptions: {
                                columns: ':not(:first-child)'
                            },
                            filename: function() {
                                var d = new Date();
                                var date = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var time = d.getHours() + '-' + d.getMinutes() + '-' + d.getSeconds();
                                return 'LAPORAN_ABSENSI_&_KEDISIPLINAN_KARYAWAN_{{$holding->holding_name}}_' + date + '_' + time;
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            className: 'btn btn-outline-light btn-sm',
                            text: '<i class="mdi mdi-file-pdf-box text-danger"></i><span class="text-danger">&nbsp;PDF</span>',
                            titleAttr: 'Export ke PDF',
                            title: 'DATA ABSENSI & KEDISIPLINAN KARYAWAN',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: ':not(:first-child)'
                            },
                            filename: function() {
                                var d = new Date();
                                var date = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var time = d.getHours() + '-' + d.getMinutes() + '-' + d.getSeconds();
                                return 'LAPORAN_ABSENSI_&_KEDISIPLINAN_KARYAWAN_{{$holding->holding_name}}_' + date + '_' + time;
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-outline-light btn-sm',
                            text: '<i class="mdi mdi-printer text-info"></i><span class="text-info">&nbsp;Print</span>',
                            titleAttr: 'Cetak Data',
                            title: 'DATA ABSENSI & KEDISIPLINAN KARYAWAN'
                        },
                        {
                            extend: 'copyHtml5',
                            className: 'btn btn-outline-light btn-sm',
                            text: '<i class="mdi mdi-content-copy text-secondary"></i><span class="text-secondary">&nbsp;Copy</span>',
                            titleAttr: 'Salin ke Clipboard',
                            title: 'DATA ABSENSI & KEDISIPLINAN KARYAWAN'
                        }
                    ],
                    columns: [{
                            data: "id",

                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'nomor_identitas_karyawan',
                            name: 'nomor_identitas_karyawan'
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
                            data: 'jam_absen_masuk',
                            name: 'jam_absen_masuk'
                        },
                        {
                            data: 'keterangan_absensi',
                            name: 'keterangan_absensi'
                        },
                        {
                            data: 'lokasi_absen_masuk',
                            name: 'lokasi_absen_masuk'
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
                            data: 'jam_absen_pulang',
                            name: 'jam_absen_pulang'
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