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
                        <h5 class="card-title m-0 me-2">REPORT ABSENSI KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="">
                    <div class="row mt-3 g-3">
                        <div class="col-lg-3">
                            <input type="month" name="filter_month" id="filter_month" class="form-control" value="{{date('Y-m')}}">
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
                                <table class="table" id="table_report" style="width: 100%; font-size: smaller;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th rowspan="2" class="text-center">No.</th>
                                            <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                            <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                            <th rowspan="2" class="text-center">Jumlah&nbsp;Hadir&nbsp;Kerja</th>
                                            <th rowspan="2" class="text-center">Jumlah&nbsp;Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                            <th rowspan="2" class="text-center">Jumlah&nbsp;Cuti</th>
                                            <th rowspan="2" class="text-center">Jumlah&nbsp;Izin&nbsp;Sakit</th>
                                            <th rowspan="2" class="text-center">Jumlah&nbsp;Izin&nbsp;Lainnya</th>
                                            <th rowspan="2" class="text-center">Jumlah&nbsp;Libur</th>
                                            <th rowspan="2" class="text-center">&nbsp;Total&nbsp;</th>
                                            <th id="th_count_date" class="text-center">&nbsp;Tanggal&nbsp;</th>
                                        </tr>
                                        <tr id="date_absensi">
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
                                            <th colspan="3" class="text-center">Jumlah&nbsp;Hadir&nbsp;Kerja</th>
                                            <th colspan="1" class="text-center">Jumlah&nbsp;Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                            <th colspan="3" class="text-center">Jumlah&nbsp;Libur</th>
                                            <th rowspan="2" class="text-center">&nbsp;Total&nbsp;</th>
                                        </tr>
                                        <tr>
                                            <th>Tepat&nbsp;Waktu</th>
                                            <th>Telat&nbsp;Hadir(-15 Menit)</th>
                                            <th>Telat&nbsp;Hadir(+15 Menit)</th>
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
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script>
        let holding = window.location.pathname.split("/").pop();
        $(document).ready(function() {
            // console.log(colspan);

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
            var filter_month = $('#filter_month').val();
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report/get_columns') }}@else{{ url('report/get_columns') }}@endif",
                method: "get",
                data: {
                    filter_month: filter_month,
                },
                success: function(data) {
                    datacolumn = [{
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
                            data: 'total_hadir_kerja',
                            name: 'total_hadir_kerja'
                        },
                        {
                            data: 'total_tidak_hadir_kerja',
                            name: 'total_tidak_hadir_kerja',
                            className: 'red-cell'
                        },
                        {
                            data: 'total_cuti',
                            name: 'total_cuti'
                        },
                        {
                            data: 'total_izin_sakit',
                            name: 'total_izin_sakit'
                        },
                        {
                            data: 'total_izin_lainnya',
                            name: 'total_izin_lainnya'
                        },
                        {
                            data: 'total_libur',
                            name: 'total_libur'
                        },
                        {
                            data: 'total_semua',
                            name: 'total_semua'
                        },
                    ];
                    // console.log(data);
                    const data_column = datacolumn.concat(data.datacolumn);
                    $.each(data.data_columns_header, function(count) {
                        $('#date_absensi').append("<th id='th_date'>" + data.data_columns_header[count].header + "</th>");
                    });
                    $('#th_count_date').attr('colspan', data.count_period);
                    load_data(filter_month, data_column, data.count_period);
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            });
            $(document).on("change", "#filter_month", function(e) {
                $('#date_absensi').empty();
                let filter_month1 = $(this).val();
                $.ajax({
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report/get_columns') }}@else{{ url('report/get_columns') }}@endif",
                    type: "GET",
                    data: {
                        filter_month: filter_month1,
                    },
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data1) {
                        console.log(data1);
                        datacolumn1 = [{
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
                                data: 'total_hadir_kerja',
                                name: 'total_hadir_kerja'
                            },
                            {
                                data: 'total_tidak_hadir_kerja',
                                name: 'total_tidak_hadir_kerja'
                            },
                            {
                                data: 'total_cuti',
                                name: 'total_cuti'
                            },
                            {
                                data: 'total_izin_sakit',
                                name: 'total_izin_sakit'
                            },
                            {
                                data: 'total_izin_lainnya',
                                name: 'total_izin_lainnya'
                            },
                            {
                                data: 'total_libur',
                                name: 'total_libur'
                            },
                            {
                                data: 'total_semua',
                                name: 'total_semua'
                            },
                        ];
                        const data_column1 = datacolumn1.concat(data1.datacolumn);

                        $.each(data1.data_columns_header, function(count) {
                            // console.log(data.data_columns_header[count].header);
                            $('#date_absensi').append("<th id='th_date'>" + data1.data_columns_header[count].header + "</th>");
                        });
                        $('#th_count_date').attr('colspan', data1.count_period);
                        // console.log(filter_month, data_column, data.count_period);
                        // load_data(filter_month1, data_column1, data1.count_period);
                        // $('#table_report').DataTable().ajax.reload();
                    }
                });
                // console.log(filter_month);
            });


            // console.log(datacolumn);
            var xlsLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK',
                'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV',
                'AW', 'AX', 'AY', 'AZ',
                'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK',
                'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV',
                'BW', 'BX', 'BY', 'BZ'
            ];

            function load_data(filter_month = '', datacolumn = '', colspan1 = '') {
                // console.log(colspan1);
                url = "{{ url('report-datatable') }}" + '/' + holding;
                // console.log(filter_month);
                // console.log(datacolumn);
                $('#table_report').DataTable().destroy();
                var table_report = $('#table_report').DataTable({
                    scrollY: '600px',
                    scrollCollapse: true,
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    dom: 'Blfrtip',
                    buttons: [{

                            extend: 'excelHtml5',
                            className: 'btn btn-xs btn-success waves-effect waves-light',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                            titleAttr: 'Excel',
                            title: 'DATA ABSENSI KARYAWAN ',
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
                            // customize: function(xlsx) {
                            //     var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            //     // var mergeCell = $('mergeCell', sheet);
                            //     // var row = $('row c', sheet); // First row
                            //     var mergeCells = [{
                            //             ref: 'A1:C1'
                            //         }, // Merge cells A1, B1, and C1
                            //         {
                            //             ref: 'D1:E1'
                            //         } // Merge cells D1 and E1
                            //     ];

                            //     // Add merge cells to the XML
                            //     var mergeCellsNode = sheet.getElementsByTagName('mergeCells')[0];
                            //     mergeCells.forEach(function(mergeCell) {
                            //         var mergeCellNode = sheet.createElement('mergeCell');
                            //         mergeCellNode.setAttribute('ref', mergeCell.ref);
                            //         mergeCellsNode.appendChild(sheet.createTextNode('\n'));
                            //         mergeCellsNode.appendChild(sheet.createTextNode('  '));
                            //         mergeCellsNode.appendChild(mergeCellNode);
                            //     });
                            //     // mergeCell.attr('ref', 'A2:A3');
                            //     // mergeCell.attr('ref', 'B2:B3');
                            //     // mergeCell.attr('ref', 'C2:C3');
                            //     // mergeCell.attr('ref', 'B:D3');
                            //     // mergeCell.attr('ref', 'E2:E3');
                            //     // mergeCell.attr('ref', 'F2:F3');
                            //     // mergeCell.attr('ref', 'G2:G3');
                            //     // mergeCell.attr('ref', 'H2:H3');
                            //     // mergeCell.attr('ref', 'I2:I3');
                            //     // mergeCell.attr('ref', 'I2:I3');
                            //     // <mergeCell ref=\"A2:A3\"/>
                            // },
                        },
                        {

                            extend: 'pdf',
                            className: 'btn btn-sm btn-danger',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-pdf-box"></i>PDF',
                            titleAttr: 'PDF',
                            title: 'DATA ABSENSI KARYAWAN',
                            orientation: 'landscape',
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
                        url: url,
                        data: {
                            filter_month: filter_month,
                        },
                    },
                    columns: datacolumn,

                    // order: [3, 'ASC'],
                });
            }

        });
    </script>

    @endsection