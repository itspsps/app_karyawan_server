@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style type="text/css">
        .my-swal {
            z-index: X;
        }

        .swal2-container {
            z-index: 9999;
        }

        .nowrap {
            white-space: nowrap;
        }

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
                <div class="container card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">DATA UJIAN PELAMAR</h5>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                interview hari ini
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Keseluruhan
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-3" data-bs-toggle="tab" href="#icon-tabpanel-3" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                interview mendatang
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel" aria-labelledby="icon-tab-0">
                            <table class="table" id="table_recruitment_interview" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Tanggal Wawancara</th>
                                        <th>Waktu Wawancara</th>
                                        <th>Nama Lengkap</th>
                                        <th>Presensi Kehadiran</th>
                                        <th>Kedisiplinan</th>
                                        <th>Ujian</th>
                                        <th>Nama Bagian</th>
                                        <th>Nama Divisi</th>
                                        <th>Nama Departemen</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
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
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" name="jumlah_filter" id="jumlah_filter">
                                            <option selected value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                            <option value="">Tampilkan Semua</option>
                                        </select>
                                        <label for="jumlah_filter">Jumlah Data Tampil</label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6col-sm-12">
                                    <div class="form-floating form-floating-outline">
                                        <div id="reportrange" style="white-space: nowrap;">
                                            <button class="btn btn-outline-secondary w-100 ">
                                                <span class="fw-bold">FILTER&nbsp;DATE&nbsp;:&nbsp;</span>
                                                <span class="date_daterange"></span>
                                                <input type="date" id="start_date" name="start_date" value=""
                                                    hidden>
                                                <input type="date" id="end_date" name="end_date" value=""
                                                    hidden>
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
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="icon-tab-a" data-bs-toggle="tab"
                                        href="#icon-tabpanel-a" role="tab" aria-controls="icon-tabpanel-a"
                                        aria-selected="true">
                                        HADIR
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="icon-tab-b" data-bs-toggle="tab" href="#icon-tabpanel-b"
                                        role="tab" aria-controls="icon-tabpanel-b" aria-selected="true">
                                        TIDAK HADIR
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab-content">
                                <div class="tab-pane active show" id="icon-tabpanel-a" role="tabpanel"
                                    aria-labelledby="icon-tab-a">
                                    <div class="table-responsive py-2">
                                        <table class="table" id="table_recruitment_interview1"
                                            style="width: 100%; font-size: small;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Tanggal Wawancara</th>
                                                    <th>Waktu Wawancara</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Presensi Kehadiran</th>
                                                    <th>Kedisiplinan</th>
                                                    <th>Ujian</th>
                                                    <th>Nama Bagian</th>
                                                    <th>Nama Divisi</th>
                                                    <th>Nama Departemen</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" id="tab-content">
                                <div class="tab-pane" id="icon-tabpanel-b" role="tabpanel" aria-labelledby="icon-tab-b">
                                    <div class="table-responsive">
                                        <table class="table" id="table_recruitment_interview2"
                                            style="width: 100%; font-size: small;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Tanggal Wawancara</th>
                                                    <th>Presensi Kehadiran</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Nama jabatan</th>
                                                    <th>Nama Bagian</th>
                                                    <th>Nama Divisi</th>
                                                    <th>Nama Departemen</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-3" role="tabpanel" aria-labelledby="icon-tab-3">
                            <div class="table-responsive">
                                <table class="table" id="table_recruitment_interview3"
                                    style="width: 100%; font-size: small;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Tanggal&nbsp;Wawancara</th>
                                            <th>Presensi&nbsp;Kehadiran</th>
                                            <th>Nama&nbsp;Lengkap</th>
                                            <th>Nama&nbsp;Bagian</th>
                                            <th>Nama&nbsp;Divisi</th>
                                            <th>Nama&nbsp;Departemen</th>
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
        <!--/ Transactions -->
        <!--/ Data Tables -->
    </div>
    <div class="modal fade" id="modal_presensi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">PRESENSI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_add" name="id">
                    <select class="form-select" id="status_add" name="status">
                        <option value="1a" selected>HADIR</option>
                        <option value="2a">TIDAK HADIR</option>
                    </select>
                    <div id="terlambat_form" class="mt-3">
                        <select class="form-select" id="terlambat_add" name="terlambat">
                            <option value="1" selected>TEPAT WAKTU</option>
                            <option value="2">TERLAMBAT</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_presensi">submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var holding_id = '{{ $holding->id }}';
        var holding = '{{ $holding->holding_code }}';

        // Hadir Hari ini
        var table = $('#table_recruitment_interview').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-interview') }}" + '/' + holding,
            },
            columns: [{
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                },
                {
                    data: 'waktu_wawancara',
                    name: 'waktu_wawancara',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'presensi',
                    name: 'presensi',
                },
                {
                    data: 'terlambat',
                    name: 'terlambat',
                },
                {
                    data: 'ujian',
                    name: 'ujian',
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_departemen',
                    name: 'nama_departemen'
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
        $('#icon-tab-0').on('shown.bs.tab', function(e) {
            table.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        // end hadir hari ini interview
        // hadir keseluruhan
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

        $('#departemen_filter').change(function(e) {
            departemen_filter_dept = $(this).val() || '';
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
        var start = moment().startOf('month');
        var end = moment().endOf('month');
        // var start = moment('2025-07-21');
        // var end = moment('2025-08-21');

        cb(start, end);
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

        function cb(start, end) {
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            $('#reportrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            console.log(start, end);
        }

        var lstart, lend
        var departemen_filter = $('#departemen_filter').val() || [];
        var divisi_filter = $('#divisi_filter').val() || [];
        var bagian_filter = $('#bagian_filter').val() || [];
        var jabatan_filter = $('#jabatan_filter').val() || [];
        var jumlah_filter = $('#jumlah_filter').val();
        var start_date = lstart || [];
        var end_date = lend || [];

        load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, jumlah_filter, start_date, end_date);
        load_data2(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, jumlah_filter, start_date, end_date);

        function load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, jumlah_filter, start_date,
            end_date) {
            $('#table_recruitment_interview1').DataTable().clear().destroy();
            if ($.fn.DataTable.isDataTable('#table_recruitment_interview1')) {
                $('#table_recruitment_interview1').DataTable().clear().destroy();
            }
            var table1 = $('#table_recruitment_interview1').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                ajax: {
                    url: "{{ url('/dt/data-interview1') }}" + '/' + holding,
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                        jumlah_filter: jumlah_filter,
                    }
                },
                columns: [{
                        data: 'tanggal_wawancara',
                        name: 'tanggal_wawancara',
                    },
                    {
                        data: 'waktu_wawancara',
                        name: 'waktu_wawancara',
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },
                    {
                        data: 'presensi',
                        name: 'presensi',
                    },
                    {
                        data: 'terlambat',
                        name: 'terlambat',
                    },
                    {
                        data: 'ujian',
                        name: 'ujian',
                    },

                    {
                        data: 'nama_jabatan',
                        name: 'nama_jabatan'
                    },
                    {
                        data: 'nama_bagian',
                        name: 'nama_bagian'
                    },
                    {
                        data: 'nama_divisi',
                        name: 'nama_divisi'
                    },
                    {
                        data: 'nama_departemen',
                        name: 'nama_departemen'
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
            $('#icon-tab-1').on('shown.bs.tab', function(e) {
                table1.columns.adjust().draw().responsive.recalc();
            });
            $('#icon-tab-a').on('shown.bs.tab', function(e) {
                table1.columns.adjust().draw().responsive.recalc();
            });
        }
        $('#btn_filter').click(function(e) {
            var departemen_filter = $('#departemen_filter').val() || [];
            var divisi_filter = $('#divisi_filter').val() || [];
            var bagian_filter = $('#bagian_filter').val() || [];
            var jabatan_filter = $('#jabatan_filter').val() || [];
            var jumlah_filter = $('#jumlah_filter').val();
            var start_date = $('#start_date').val() || '';
            var end_date = $('#end_date').val() || '';

            $('#content_null').empty();

            load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, jumlah_filter, start_date,
                end_date);
            load_data2(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, jumlah_filter, start_date,
                end_date);
        });
        // end hadir keseluruhan
        // tidak hadir keseluruhan
        function load_data2(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, jumlah_filter, start_date,
            end_date) {
            $('#table_recruitment_interview2').DataTable().clear().destroy();
            if ($.fn.DataTable.isDataTable('#table_recruitment_interview2')) {
                $('#table_recruitment_interview2').DataTable().clear().destroy();
            }
            var table2 = $('#table_recruitment_interview2').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                ajax: {
                    url: "{{ url('/dt/data-interview2') }}" + '/' + holding,
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                        jumlah_filter: jumlah_filter,
                    }
                },
                columns: [{
                        data: 'tanggal_wawancara',
                        name: 'tanggal_wawancara',
                    }, {
                        data: 'presensi',
                        name: 'presensi',
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },

                    {
                        data: 'nama_jabatan',
                        name: 'nama_jabatan'
                    },
                    {
                        data: 'nama_bagian',
                        name: 'nama_bagian'
                    },
                    {
                        data: 'nama_divisi',
                        name: 'nama_divisi'
                    },
                    {
                        data: 'nama_departemen',
                        name: 'nama_departemen'
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
            $('#icon-tab-b').on('shown.bs.tab', function(e) {
                table2.columns.adjust().draw().responsive.recalc();
            });
            $('#icon-tab-1').on('shown.bs.tab', function(e) {
                table2.columns.adjust().draw().responsive.recalc();
            });
        }
        // end tidak hadir keseluruhan

        // yang akan datang
        var table3 = $('#table_recruitment_interview3').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ url('/dt/data-interview3') }}" + '/' + holding,
            },
            columns: [{
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                }, {
                    data: 'presensi',
                    name: 'presensi',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_departemen',
                    name: 'nama_departemen'
                },
            ]
        });
        $('#icon-tab-3').on('shown.bs.tab', function(e) {
            table3.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        // end yang akan datang
        $(document).on('click', '#btn_presensi', function() {
            // console.log('asooy');
            var id = $(this).data('id');
            $('#id_add').val(id);
            $('#modal_presensi').modal('show');

        });
        $(document).on('change', '#status_add', function() {
            let value = $(this).val();
            if (value == '1a') {
                $('#terlambat_form').show();
            } else if (value == '2a') {
                $('#terlambat_form').hide();
            }
        });
        $('#btn_save_presensi').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_add').val());
            formData.append('status', $('#status_add').val());
            formData.append('terlambat', $('#terlambat_add').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/dt/data-interview/presensi_recruitment_update') }}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memuat Data...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    Swal.close();
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_presensi').modal('hide');
                        $('#table_recruitment_interview').DataTable().ajax.reload();
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
    </script>
    {{-- end datatable  --}}
@endsection
