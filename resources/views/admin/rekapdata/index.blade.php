@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                        <h5 class="card-title m-0 me-2">REKAP DATA ABSENSI KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="">
                    <form action="{{ url('/rekap-data/'.$holding) }}">
                        <div class="row g-3 text-center">
                            <div class="col-2">
                                <div class="form-floating form-floating-outline">
                                    <select type="text" class="form-control" name="departemen_filter" id="departemen_filter">
                                        <option selected disabled value="">--</option>
                                        @foreach($departemen as $dept)
                                        <option value="{{$dept->id}}">{{$dept->nama_departemen}}</option>
                                        @endforeach
                                    </select>
                                    <label for="departemen_filter">Departemen</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating form-floating-outline">
                                    <select type="text" class="form-control" name="divisi_filter" placeholder="Date Filter" id="divisi_filter">
                                        <option selected disabled value="">--</option>
                                    </select>
                                    <label for="divisi_filter">Divisi</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating form-floating-outline">
                                    <select type="text" class="form-control" name="bagian_filter" placeholder="Date Filter" id="bagian_filter">
                                        <option selected disabled value="">--</option>
                                    </select>
                                    <label for="bagian_filter">Bagian</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating form-floating-outline">
                                    <select type="text" class="form-control" name="jabatan_filter" placeholder="Date Filter" id="jabatan_filter">
                                        <option selected disabled value="">--</option>
                                    </select>
                                    <label for="jabatan_filter">Jabatan</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="month" class="form-control" name="date_filter" placeholder="Filter By Month:" id="date_filter" value="{{ date('Y-m') }}">
                                    <label for="date_filter">Date Range Filter</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal fade" id="modal_import_absensi" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/rekapdata/ImportAbsensi/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Data Absensi</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="file" id="file_excel" name="file_excel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" placeholder="Masukkan File" />
                                                <label for="file_excel">File Excel</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-2">
                                        <a href="" type="button" download="" class="btn btn-sm btn-primary"> Download Format Excel</a>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_export_absensi" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="get" action="{{url('rekap-data/ExportAbsensi/'.$holding)}}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Export Excel Absensi</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <h6>Download File Excel Data Absensi</h6>
                                                <button type="submit" class="btn btn-sm btn-success"> Download Excel</button>
                                            </div>
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
                    <div class="modal fade" id="modal_detail" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <form method="post" action="{{ url('/rekapdata/ImportAbsensi/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Detail Absensi</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Transactions -->
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 id="title_detail" class="card-title m-0 me-2">DATA ABSENSI KARYAWAN </h5>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <table class="table" id="table_rekapdata2" style="width: 100%;">
                                                    <thead class="table-primary">
                                                        <tr>
                                                            <th rowspan="2" class="text-center">Detail</th>
                                                            <th rowspan="2" class="text-center">No.</th>
                                                            <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                                            <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                                            <th colspan="2" class="text-center">Hadir&nbsp;Kerja</th>
                                                            <th colspan="3" class="text-center">Keterangan</th>
                                                            <th colspan="1" class="text-center">Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                                            <th rowspan="2" class="text-center">Total&nbsp;Keseluruhan</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Tepat&nbsp;Waktu</th>
                                                            <th>Telat&nbsp;Hadir</th>
                                                            <th>Izin</th>
                                                            <th>Cuti</th>
                                                            <th>Dinas</th>
                                                            <th>Alfa</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                    </tbody>
                                                </table>
                                            </div>
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
                    <hr class="my-5">
                    <div class="nav-align-top">
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-8">
                                <ul class="nav nav-pills nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a type=" button" style="width: auto;" class="nav-link active" role="tab" data-bs-toggle="tab" href="#navs-pills-justified-home">
                                            <i class="tf-icons mdi mdi-account-tie me-1"></i><span class="d-none d-sm-block">Karyawan Bulanan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a type="button" style="width: auto;" class="nav-link" role="tab" data-bs-toggle="tab" href="#navs-pills-justified-profile">
                                            <i class="tf-icons mdi mdi-account me-1"></i><span class="d-none d-sm-block">Karyawan Harian</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-2">
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                                <table class="table" id="table_rekapdata" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th rowspan="2" class="text-center">Detail</th>
                                            <th rowspan="2" class="text-center">No.</th>
                                            <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                            <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                            <th colspan="3" class="text-center">Hadir&nbsp;Kerja</th>
                                            <th colspan="3" class="text-center">Keterangan</th>
                                            <th colspan="1" class="text-center">Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                            <th rowspan="2" class="text-center">Total&nbsp;Keseluruhan</th>
                                        </tr>
                                        <tr>
                                            <th>Tepat&nbsp;Waktu</th>
                                            <th>Telat&nbsp;Hadir&nbsp;<span>(>&nbsp;15&nbsp;Menit)</span></th>
                                            <th>Telat&nbsp;Hadir&nbsp;<span>(<&nbsp;15&nbsp;Menit)< /span>
                                            </th>
                                            <th>Izin</th>
                                            <th>Cuti</th>
                                            <th>Dinas</th>
                                            <th>Alfa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                                <table class="table" id="table_rekapdata1" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th rowspan="2" class="text-center">No.</th>
                                            <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                            <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                            <th colspan="3" class="text-center">Hadir&nbsp;Kerja</th>
                                            <th colspan="3" class="text-center">Keterangan</th>
                                            <th colspan="1" class="text-center">Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                            <th rowspan="2" class="text-center">Total&nbsp;Keseluruhan</th>
                                        </tr>
                                        <tr>
                                            <th>Tepat&nbsp;Waktu</th>
                                            <th>Telat&nbsp;Hadir(>15 Menit)</th>
                                            <th>Telat&nbsp;Hadir('<'15 Menit)</th>
                                            <th>Izin</th>
                                            <th>Cuti</th>
                                            <th>Dinas</th>
                                            <th>Alfa</th>
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
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script>
        let holding = window.location.pathname.split("/").pop();
        $(document).ready(function() {
            $('#departemen_filter').change(function() {
                departemen_filter = $(this).val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();
                $('#table_rekapdata').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "{{url('rekapdata/get_divisi')}}",
                    data: {
                        holding: holding,
                        departemen_filter: departemen_filter
                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_divisi').html(msg);
                        $('#divisi_filter').html(msg);
                        $('#bagian_filter').html('<option value="">Pilih Bagian</option>');
                        $('#jabatan_filter').html('<option value="">Pilih Jabatan</option>');
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            $('#divisi_filter').change(function() {
                divisi_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();
                $('#table_rekapdata').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "{{url('rekapdata/get_bagian')}}",
                    data: {
                        holding: holding,
                        divisi_filter: divisi_filter
                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_divisi').html(msg);
                        $('#bagian_filter').html(msg);
                        $('#jabatan_filter').html('<option value="">Pilih Jabatan..</option>');
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            $('#bagian_filter').change(function() {
                bagian_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();

                $('#table_rekapdata').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "{{url('rekapdata/get_jabatan')}}",
                    data: {
                        holding: holding,
                        bagian_filter: bagian_filter
                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_bagian').html(msg);
                        $('#jabatan_filter').html(msg);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            $('#jabatan_filter').change(function() {
                jabatan_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                filter_month = $('#date_filter').val();
                $('#table_rekapdata').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            $('#date_filter').change(function() {
                filter_month = $(this).val();
                // console.log(filter_month);
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                $('#table_rekapdata').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
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

            function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', filter_month = '') {
                filter_month = $('#date_filter').val();
                // console.log(filter_month);
                var table = $('#table_rekapdata').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    autoWidth: false,
                    serverSide: true,
                    deferRender: true,
                    dom: 'Blfrtip',
                    buttons: [{

                            extend: 'excelHtml5',
                            className: 'btn btn-sm btn-success',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                            titleAttr: 'Excel',
                            title: 'DATA ABSENSI KARYAWAN ',
                            messageTop: 'Bulan : '.filter_month,
                            action: newexportaction,
                            exportOptions: {
                                columns: ':not(:first-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var n = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
                                return 'REKAP_DATA_ABSENSI_KARYAWAN_{{$holding}}_' + l + ' ' + n;
                            },
                        },
                        {

                            extend: 'pdf',
                            className: 'btn btn-sm btn-danger',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-pdf-box"></i>PDF',
                            titleAttr: 'PDF',
                            title: 'DATA ABSENSI KARYAWAN',
                            orientation: 'potrait',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: ':not(:first-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var n = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                                return 'REKAP_DATA_ABSENSI_KARYAWAN_{{$holding}}_' + l + ' ' + n;
                            },
                        }, {
                            extend: 'print',
                            className: 'btn btn-sm btn-info',
                            title: 'DATA ABSENSI KARYAWAN',
                            text: '<i class="menu-icon tf-icons mdi mdi-printer-pos-check-outline"></i>PRINT',
                            titleAttr: 'PRINT',
                        }, {
                            extend: 'copy',
                            title: 'DATA ABSENSI KARYAWAN',
                            className: 'btn btn-sm btn-secondary',
                            text: '<i class="menu-icon tf-icons mdi mdi-content-copy"></i>COPY',
                            titleAttr: 'COPY',
                        }
                    ],
                    pageLength: 50,
                    ajax: {
                        url: "{{ url('rekapdata-datatable') }}" + '/' + holding,
                        data: {
                            filter_month: filter_month,
                            departemen_filter: departemen_filter,
                            divisi_filter: divisi_filter,
                            bagian_filter: bagian_filter,
                            jabatan_filter: jabatan_filter,
                        }
                    },
                    columns: [{
                            data: 'btn_detail',
                            name: 'btn_detail'
                        },
                        {
                            data: 'no',
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }

                        },
                        {
                            data: 'nomor_identitas_karyawan',
                            name: 'nomor_identitas_karyawan'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'total_hadir_tepat_waktu',
                            name: 'total_hadir_tepat_waktu'
                        },
                        {
                            data: 'total_hadir_telat_hadir',
                            name: 'total_hadir_telat_hadir'
                        },
                        {
                            data: 'total_hadir_telat_hadir1',
                            name: 'total_hadir_telat_hadir1'
                        },
                        {
                            data: 'total_izin_true',
                            name: 'total_izin_true'
                        },
                        {
                            data: 'total_cuti_true',
                            name: 'total_cuti_true'
                        },
                        {
                            data: 'total_dinas_true',
                            name: 'total_dinas_true'
                        },
                        {
                            data: 'tidak_hadir_kerja',
                            name: 'tidak_hadir_kerja'
                        },
                        {
                            data: 'total_semua',
                            name: 'total_semua'
                        },

                    ],
                    order: [3, 'ASC'],

                });
            }
            var table1 = $('#table_rekapdata1').DataTable({
                "scrollY": true,
                "scrollX": true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                deferRender: true,
                ajax: {
                    url: "{{ url('rekapdata-datatable_harian') }}" + '/' + holding,
                },
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'total_hadir_tepat_waktu',
                        name: 'total_hadir_tepat_waktu'
                    },
                    {
                        data: 'total_hadir_telat_hadir',
                        name: 'total_hadir_telat_hadir'
                    },
                    {
                        data: 'total_izin_true',
                        name: 'total_izin_true'
                    },
                    {
                        data: 'total_cuti_true',
                        name: 'total_cuti_true'
                    },
                    {
                        data: 'total_dinas_true',
                        name: 'total_dinas_true'
                    },
                    {
                        data: 'tidak_hadir_kerja',
                        name: 'tidak_hadir_kerja'
                    },
                    {
                        data: 'total_semua',
                        name: 'total_semua'
                    },

                ],
                order: [
                    [2, 'asc']
                ]
            });
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                table1.columns.adjust().draw().responsive.recalc();
                // table.draw();
            })
        });
    </script>
    <script>
        // console.log(now);
        // $('input[id="date_filter"]').daterangepicker({
        //     drops: 'auto',
        //     autoUpdateInput: true,
        //     locale: {
        //         cancelLabel: 'Clear'
        //     },
        //     autoApply: false,
        // }, function(start, end, label) {
        //     console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        // });
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
                            $('#table_rekapdata').DataTable().ajax.reload();
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