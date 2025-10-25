@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />
    <link rel="preload" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
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
                            <h5 class="card-title m-0 me-2">REPORT REKRUTMEN</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <form method="post" action="{{ url('/laporan_recruitment/' . $holding) }}" class="modal-content"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-2 gy-4">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input style="font-size: small;" class="form-control" type="date"
                                            name="tanggal_awal" />
                                        <label for="tanggal_awal">Tanggal&nbsp;Awal</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input style="font-size: small;" type="date" class="form-control"
                                            name="tanggal_akhir" />
                                        <label for="tanggal_akhir">Tanggal&nbsp;Akhir</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2 gy-4 justify-content-center py-1">
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light mb-3">
                                        <i class="menu-icon tf-icons mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </form> --}}
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
                                                    <input type="date" id="start_date" name="start_date" hidden
                                                        value="">
                                                    <input type="date" id="end_date" name="end_date" hidden
                                                        value="">
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
                        <!-- Table rekap -->
                        <div class="mt-4">
                            <div id="content_null">
                                <div class="alert alert-secondary" role="alert">
                                    <div class="alert-content text-center">
                                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">

                                            <rect x="25" y="25" width="70" height="70" rx="8" ry="8"
                                                stroke="#CED4DA" stroke-width="2" />

                                            <path d="M35 35H85" stroke="#E6E8EB" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M35 45H75" stroke="#E6E8EB" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M35 55H85" stroke="#E6E8EB" stroke-width="1.5"
                                                stroke-linecap="round" />

                                            <path d="M40 70L80 70" stroke="#7E8A9A" stroke-width="2"
                                                stroke-dasharray="4 4" stroke-linecap="round" />
                                            <path d="M40 80L70 80" stroke="#7E8A9A" stroke-width="2"
                                                stroke-dasharray="4 4" stroke-linecap="round" />

                                        </svg>
                                        <h4 class="alert-heading text-center">Tidak Ada Data</h4>
                                        <p class="text-center">Filter Data Terlebih Dahulu</p>
                                    </div>
                                </div>
                            </div>
                            <div id="table_recruitment2_form">
                                <table class="table" id="table_recruitment2" style="width: 100%; font-size: small;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Posisi&nbsp;Kosong</th>
                                            <th>Penggantian&nbsp;/&nbsp;Penambahan</th>
                                            <th>Kuota</th>
                                            <th>Pelamar</th>
                                            <th>Diterima</th>
                                            <th>Kuota&nbsp;Terpenuhi</th>
                                            <th>Tanggal&nbsp;Terpenuhi</th>
                                            <th>Pelamar&nbsp;Terpilih</th>
                                            <th>Tanggal&nbsp;Mulai</th>
                                            <th>Tanggal&nbsp;Berakhir</th>
                                            <th>Deadline</th>
                                            <th>Waktu yg Dibutuhkan</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- {{-- start datatable  --}} -->
    <script>
        var holding_id = '{{ $holding->id }}';
        var holding = '{{ $holding->holding_code }}';
        $('#departemen_filter').change(function(e) {
            departemen_filter_dept = $(this).val() || '';
            // $('#table_recruitment2').DataTable().destroy();
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

            // $('#table_recruitment2').DataTable().destroy();
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
        var start = moment().startOf('month');
        var end = moment().endOf('month');
        // var start = moment('2025-07-21');
        // var end = moment('2025-08-21');
        var lstart, lend;

        $('#reportrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));

        function cb(start, end) {
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            console.log(start, end);

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
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $(document).ready(function() {
            $('#table_recruitment2_form').hide();
            $('#btn_filter').click(function(e) {
                $('#table_recruitment2_form').show();
                var departemen_filter = $('#departemen_filter').val() || [];
                var divisi_filter = $('#divisi_filter').val() || [];
                var bagian_filter = $('#bagian_filter').val() || [];
                var jabatan_filter = $('#jabatan_filter').val() || [];
                var start_date = $('#start_date').val() || '';
                var end_date = $('#end_date').val() || '';

                // console.log(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);

                $('#content_null').empty();
                // $('#table_recruitment2').empty();
                $('#table_recruitment2').DataTable().clear().destroy();
                if ($.fn.DataTable.isDataTable('#table_recruitment2')) {
                    $('#table_recruitment2').DataTable().clear().destroy();
                }
                var table = $('#table_recruitment2').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    ajax: {
                        url: "{{ url('/dt_laporan_recruitment2') }}" + '/' + holding,
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                            departemen_filter: departemen_filter,
                            divisi_filter: divisi_filter,
                            bagian_filter: bagian_filter,
                            jabatan_filter: jabatan_filter,
                        }
                    },
                    buttons: [{

                            extend: 'excelHtml5',
                            className: 'btn btn-sm btn-success',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                            titleAttr: 'Excel',
                            title: 'LAPORAN REKRUTMEN',
                            messageTop: 'Bulan : '.start_date + ' s/d ' + end_date,
                            exportOptions: {
                                columns: ':not(:first-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d
                                    .getDate();
                                var n = d.getHours() + ':' + d.getMinutes() + ':' + d
                                    .getSeconds();
                                return 'LAPORAN_REKRUTMEN_{{ $holding->holding_name }}_' +
                                    l + ' ' + n;
                            },
                        },
                        {

                            extend: 'pdf',
                            className: 'btn btn-sm btn-danger',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-pdf-box"></i>PDF',
                            titleAttr: 'PDF',
                            title: 'LAPORAN REKRUTMEN',
                            orientation: 'potrait',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: ':not(:first-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d
                                    .getDate();
                                var n = d.getHours() + ":" + d.getMinutes() + ":" + d
                                    .getSeconds();
                                return 'LAPORAN_REKRUTMEN_{{ $holding->holding_name }}_' +
                                    l + ' ' + n;
                            },
                        }, {
                            extend: 'print',
                            className: 'btn btn-sm btn-info',
                            title: 'LAPORAN REKRUTMEN',
                            text: '<i class="menu-icon tf-icons mdi mdi-printer-pos-check-outline"></i>PRINT',
                            titleAttr: 'PRINT',
                        }, {
                            extend: 'copy',
                            title: 'LAPORAN REKRUTMEN',
                            className: 'btn btn-sm btn-secondary',
                            text: '<i class="menu-icon tf-icons mdi mdi-content-copy"></i>COPY',
                            titleAttr: 'COPY',
                        }
                    ],
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'posisi_kosong',
                            name: 'posisi_kosong'
                        },
                        {
                            data: 'penggantian_penambahan',
                            name: 'penggantian_penambahan'
                        },
                        {
                            data: 'kuota',
                            name: 'kuota'
                        },

                        {
                            data: 'jumlah_pelamar',
                            name: 'jumlah_pelamar'
                        },
                        {
                            data: 'jumlah_diterima',
                            name: 'jumlah_diterima'
                        },
                        {
                            data: 'kuota_terpenuhi',
                            name: 'kuota_terpenuhi'
                        },
                        {
                            data: 'tgl_terpenuhi',
                            name: 'tgl_terpenuhi'
                        },
                        {
                            data: 'pelamar_terpilih',
                            name: 'pelamar_terpilih'
                        },

                        {
                            data: 'tanggal_mulai',
                            name: 'tanggal_mulai'
                        },
                        {
                            data: 'tanggal_akhir',
                            name: 'tanggal_akhir'
                        },
                        {
                            data: 'deadline',
                            name: 'deadline'
                        },
                        {
                            data: 'waktu_yg_dibutuhkan',
                            name: 'waktu_yg_dibutuhkan'
                        },
                    ],
                    order: [
                        [2, 'desc']
                    ]
                });
            });
        });
    </script>
@endsection
