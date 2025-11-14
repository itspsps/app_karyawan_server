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
                        <div id="table_divisi_form">
                            <table class="table" id="table_divisi_print" style="width: 100%; font-size: small;">
                                <thead class="table-primary" hidden>
                                    <tr>
                                        <th>No.</th>
                                        <th>Divisi</th>
                                        <th>Jumlah&nbsp;Pelamar</th>
                                        <th>Rata-rata&nbsp;Waktu</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" hidden>
                                </tbody>
                            </table>
                            <table class="table" id="table_divisi" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Divisi</th>
                                        <th>Jumlah&nbsp;Pelamar</th>
                                        <th>Rata-rata&nbsp;Waktu</th>
                                        <th>Detail</th>
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
        $('#table_divisi_print').on('preXhr.dt', function(e, settings, data) {
            if (data.search && data.search.value) {
                // kalau ada search value, biarkan loading default datatable
                return;
            }
            Swal.fire({
                title: 'Memuat Data...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
        $('#table_divisi_print').on('xhr.dt', function(e, settings, json, xhr) {
            // tutup swal hanya kalau sebelumnya kita buka (bukan saat search)
            if (!settings.oPreviousSearch.sSearch) {
                Swal.close();
            }
        });
        $('#table_divisi').on('preXhr.dt', function(e, settings, data) {
            if (data.search && data.search.value) {
                // kalau ada search value, biarkan loading default datatable
                return;
            }
            Swal.fire({
                title: 'Memuat Data...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
        $('#table_divisi').on('xhr.dt', function(e, settings, json, xhr) {
            // tutup swal hanya kalau sebelumnya kita buka (bukan saat search)
            if (!settings.oPreviousSearch.sSearch) {
                Swal.close();
            }
        });
        if (!$.fn.dataTable.isDataTable('#table_divisi_print')) {

            var table2 = $('#table_divisi_print').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                info: false,
                dom: 'Bfrtip',
                ajax: {
                    url: "{{ url('/dt_per_divisi_print') }}" + '/' + holding,
                },
                buttons: [{

                    extend: 'excelHtml5',
                    className: 'btn btn-sm btn-success',
                    text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                    titleAttr: 'Excel',
                    title: 'RATA-RATA LOWONGAN KERJA PER DIVISI',
                    // messageTop: 'Bulan : '.start_date + ' s/d ' + end_date,
                    exportOptions: {
                        columns: ':not(:first-child)',
                    },
                    filename: function() {
                        var d = new Date();
                        var l = d.getFullYear() + '-' + (d.getMonth() + 1) +
                            '-' + d
                            .getDate();
                        var n = d.getHours() + ':' + d.getMinutes() + ':' + d
                            .getSeconds();
                        return 'RATA_RATA_LOWONGAN_KERJA_PER_DIVISI_{{ $holding->holding_name }}_' +
                            l + ' ' + n;
                    },

                }, ],
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'divisi',
                        name: 'divisi',
                    },
                    {
                        data: 'jumlah_pelamar',
                        name: 'jumlah_pelamar'
                    },
                    {
                        data: 'rata_rata',
                        name: 'rata_rata',
                        className: 'text-end'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

        }
        var table = $('#table_divisi').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt_per_divisi') }}" + '/' + holding,
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
                    data: 'divisi',
                    name: 'divisi',
                },
                {
                    data: 'jumlah_pelamar',
                    name: 'jumlah_pelamar'
                },
                {
                    data: 'rata_rata',
                    name: 'rata_rata',
                    className: 'text-end'
                },
                {
                    data: 'detail',
                    name: 'detail'
                },
            ],
            order: [
                [1, 'asc']
            ]
        });
    </script>
@endsection
