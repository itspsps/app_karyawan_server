@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />
<link rel="preload" href="{{asset('admin/assets/vendor/libs/apex-charts/apex-charts.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
            <div class="card" style="height: 100vh;">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card-title m-0 me-2">REPORT KEDISIPLINAN KARYAWAN</h6>
                    </div>
                </div>

                <div class="card-body p-0" style="height: calc(1000vh - 60px); overflow-y: auto;">
                    <!-- Filter selalu di atas -->
                    <div id="collapseFilterWrapper" class="sticky-top bg-white" style="z-index: 1020;">
                        <div class="card-body">

                            <div class="row gy-4 mb-4">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="departemen_filter[]" id="departemen_filter" multiple>
                                            <option disabled value="">-Pilih Departemen-</option>
                                            @foreach($departemen as $dept)
                                            <option value="{{$dept->id}}">{{$dept->nama_departemen}}</option>
                                            @endforeach
                                        </select>
                                        <label for="departemen_filter">Departemen</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="divisi_filter[]" id="divisi_filter" multiple>
                                            <option selected disabled value="">-- Pilih Divisi --</option>
                                        </select>
                                        <label for="divisi_filter">Divisi</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="bagian_filter[]" id="bagian_filter" multiple>
                                            <option selected disabled value="">-- Pilih Bagian --</option>
                                        </select>
                                        <label for="bagian_filter">Bagian</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="jabatan_filter[]" id="jabatan_filter" multiple>
                                            <option selected disabled value="">-- Pilih Jabatan --</option>
                                        </select>
                                        <label for="jabatan_filter">Jabatan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row gy-4 align-items-end">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="shift_filter" id="shift_filter">
                                            <option selected disabled value="">~~ Pilih Kategori ~~</option>
                                            <option value="NON SHIFT">NON SHIFT</option>
                                            <option value="SHIFT">SHIFT</option>
                                        </select>
                                        <label for="shift_filter">Kategori Shift</label>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6col-sm-12">
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

                                <div class="col-lg-2 col-md-12 col-sm-12 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary w-100" id="btn_filter">
                                        <i class="mdi mdi-filter-outline"></i>&nbsp;Filter
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Konten yang bisa discroll -->
                    <div class="content-scroll p-3">
                        <!-- <div class="row gy-4">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">Grafik Absensi Karyawan Kontrak Kerja {{ $holding->holding_name }}</h6>
                                        <button id="toggleBtnGrafik" class="btn btn-sm btn-outline-primary" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseGrafik"
                                            aria-expanded="false"
                                            aria-controls="collapseGrafik">
                                            Buka
                                        </button>
                                    </div>
                                    <div id="collapseGrafik" class="collapse">
                                        <div class="card-body">
                                            <div id="grafik_absensi"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Table rekap -->
                        <div class="mt-4">
                            <div id="content_null">
                                <div class="alert alert-secondary" role="alert">
                                    <div class="alert-content text-center">
                                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">

                                            <rect x="25" y="25" width="70" height="70" rx="8" ry="8" stroke="#CED4DA" stroke-width="2" />

                                            <path d="M35 35H85" stroke="#E6E8EB" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M35 45H75" stroke="#E6E8EB" stroke-width="1.5" stroke-linecap="round" />
                                            <path d="M35 55H85" stroke="#E6E8EB" stroke-width="1.5" stroke-linecap="round" />

                                            <path d="M40 70L80 70" stroke="#7E8A9A" stroke-width="2" stroke-dasharray="4 4" stroke-linecap="round" />
                                            <path d="M40 80L70 80" stroke="#7E8A9A" stroke-width="2" stroke-dasharray="4 4" stroke-linecap="round" />

                                        </svg>
                                        <h4 class="alert-heading text-center">Tidak Ada Data</h4>
                                        <p class="text-center">Filter Data Terlebih Dahulu</p>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-hover" id="table_rekapdata" style=" width: 100%; font-size: small; ">

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
    <script src="{{asset('admin/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            var holding = '{{$holding->holding_code}}';
            var holding_id = '{{$holding->id}}';
            var chart_absensi_masuk;
            var data_column;
            let table_rekapdata;

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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            $('#btn_filter').click(function(e) {
                // ambil langsung dari form
                var departemen_filter = $('#departemen_filter').val() || [];
                var departemen_filter = $('#departemen_filter').val() || [];
                var divisi_filter = $('#divisi_filter').val() || [];
                var bagian_filter = $('#bagian_filter').val() || [];
                var jabatan_filter = $('#jabatan_filter').val() || [];
                var shift_filter = $('#shift_filter').val() || [];
                var start_date = $('#start_date').val() || '';
                var end_date = $('#end_date').val() || '';
                // console.log(start_date, end_date);
                //console.log(lstart, lend, departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start, end);
                $.ajax({
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report_kedisiplinan/get_columns') }}@else{{ url('report_kedisiplinan/get_columns') }}@endif" + '/' + holding,
                    method: "get",
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                        shift_filter: shift_filter,
                    },
                    success: function(data) {
                        console.log(data);
                        datacolumn = [{
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
                                data: 'departemen',
                                name: 'departemen'
                            },
                            {
                                data: 'shift',
                                name: 'shift'
                            },
                            {
                                data: 'total_datang_lebih_awal',
                                name: 'total_datang_lebih_awal'
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
                                data: 'total_pulang_cepat',
                                name: 'total_pulang_cepat'
                            },
                            {
                                data: 'total_overtime_pulang',
                                name: 'total_overtime_pulang'
                            },
                            {
                                data: 'total_hadir',
                                name: 'total_hadir'
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
                                data: 'total_libur',
                                name: 'total_libur'
                            },
                            {
                                data: 'tidak_hadir_kerja',
                                name: 'tidak_hadir_kerja'
                            },
                            {
                                data: 'net_hadir_kerja',
                                name: 'net_hadir_kerja'
                            },
                            {
                                data: 'total_semua',
                                name: 'total_semua'
                            },
                        ]
                        const data_column = datacolumn.concat(data.datacolumn);
                        // console.log(data_column);
                        // 1. Destroy DataTable dulu kalau sudah ada
                        $('#content_null').empty();
                        $('#table_rekapdata').empty();
                        $('#table_rekapdata').append('<thead class="text-center table-primary align-middle" style="white-space: nowrap;"><tr><th rowspan="3" class="text-center">Detail</th><th rowspan="3" class="text-center">No.</th><th rowspan="3" class="text-center">ID&nbsp;Karyawan</th><th rowspan="3" class="text-center">Nama&nbsp;Karyawan</th><th rowspan="3" clas="text-center">Departemen</th><th rowspan="3" class="text-center">Shift</th><th colspan="14" class="text-center">&nbsp;ABSENSI&nbsp;</th><th class="text-center th_count_date1">Detail&nbsp;Per&nbsp;Tanggal</th></tr><tr><th colspan="4" class="text-center">Masuk</th><th colspan="2" class="text-center">Pulang</th><th rowspan="2">Total&nbsp;Hadir</th><th colspan="3" class="text-center">Keterangan</th><th rowspan="2">Libur</th><th rowspan="2">Tidak&nbsp;Hadir</th><th rowspan="2">Net&nbsp;Hadir&nbsp;Kerja</th><th rowspan="2">Total&nbsp;Hari</th><th class="text-center th_count_date2">Tanggal</th></tr><tr id="date_absensi"></tr></thead><tbody class="text-center align-middle"></tbody>');
                        if ($.fn.DataTable.isDataTable('#table_rekapdata')) {
                            $('#table_rekapdata').DataTable().clear().destroy();
                        }
                        $('#date_absensi').empty();
                        // kolom absensi summary (misalnya 8 kolom)
                        $('#date_absensi')
                            .append('<th>Datang&nbsp;Lebih&nbsp;Cepat</th>')
                            .append('<th>Tepat&nbsp;Waktu</th>')
                            .append('<th>Telat&nbsp;(-15&nbsp;Menit)</th>')
                            .append('<th>Telat&nbsp;(+15&nbsp;Menit)</th>')
                            .append('<th>Pulang&nbsp;Cepat</th>')
                            .append('<th>Overtime&nbsp;Pulang</th>')
                            .append('<th>&nbsp;Izin&nbsp;</th>')
                            .append('<th>&nbsp;Cuti&nbsp;</th>')
                            .append('<th>&nbsp;Dinas&nbsp;</th>');

                        // kolom tanggal dinamis dari backend
                        // console.log(data.data_columns_header);
                        $('.th_count_date2').attr('colspan', data.count_period);
                        $('.th_count_date1').attr('colspan', data.count_period);
                        $.each(data.data_columns_header, function(i, col) {
                            $('#date_absensi').append('<th class="text-center">' + col.header + '</th>');
                        });
                        // console.log('Header baris kedua sekarang:');
                        // console.log(data.count_period);


                        // console.log(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, shift_filter, start_date, end_date, data_column);
                        load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, shift_filter, start_date, end_date, data_column);
                    },
                    error: function(data) {
                        var errors = data.errors();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errors,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                        });
                    }
                });



            })
            $('#departemen_filter').change(function(e) {
                departemen_filter_dept = $(this).val() || '';
                // $('#table_rekapdata').DataTable().destroy();
                var url = "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report_kedisiplinan/get_divisi')}}@else{{url('report_kedisiplinan/get_divisi')}}@endif" + '/' + holding_id;
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
                        let isOpen = $('#divisi_filter').data('select2') && $('#divisi_filter').data('select2').isOpen();

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

                // $('#table_rekapdata').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report_kedisiplinan/get_bagian')}}@else{{url('report_kedisiplinan/get_bagian')}}@endif" + '/' + holding_id,
                    data: {
                        holding: holding_id,
                        divisi_filter: divisi_filter,
                    },
                    cache: false,

                    success: function(data_divisi) {
                        // console.log(data_divisi);
                        $('#bagian_filter').html(data_divisi.select);
                        $('#jabatan_filter').html('<option value="">Pilih Jabatan..</option>');
                        let isOpen = $('#bagian_filter').data('select2') && $('#bagian_filter').data('select2').isOpen();

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
                    url: "{{url('report_kedisiplinan/get_jabatan')}}" + '/' + holding_id,
                    data: {
                        holding: holding_id,
                        bagian_filter: bagian_filter
                    },
                    cache: false,

                    success: function(data_jabatan) {

                        $('#jabatan_filter').html(data_jabatan.select);
                        let isOpen = $('#jabatan_filter').data('select2') && $('#jabatan_filter').data('select2').isOpen();

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

            function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', shift_filter = '', start_date = '', end_date = '', data_column = '') {
                // console.log("Jumlah TH terakhir:", $('#table_rekapdata thead tr:last th').length);
                // console.log("Jumlah column DataTable:", data_column.length);

                // $('#table_rekapdata thead tr:last th').each(function(i, th) {
                //     console.log(i, $(th).text().trim());
                // });
                var table_rekapdata = $('#table_rekapdata').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    autoWidth: false,
                    serverSide: true,
                    deferRender: true,
                    dom: 'Blfrtip',
                    destroy: true,
                    buttons: [{

                            extend: 'excelHtml5',
                            className: 'btn btn-sm btn-success',
                            text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                            titleAttr: 'Excel',
                            title: 'DATA ABSENSI KARYAWAN ',
                            messageTop: 'Bulan : '.start_date + ' s/d ' + end_date,
                            exportOptions: {
                                columns: ':not(:first-child)',
                            },
                            filename: function() {
                                var d = new Date();
                                var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                                var n = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
                                return 'REKAP_DATA_ABSENSI_KARYAWAN_{{$holding->holding_name}}_' + l + ' ' + n;
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
                                return 'REKAP_DATA_ABSENSI_KARYAWAN_{{$holding->holding_name}}_' + l + ' ' + n;
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
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    ajax: {
                        url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/report_kedisiplinan-datatable') }}@else {{ url('report_kedisiplinan-datatable') }}@endif" + '/' + holding,
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                            departemen_filter: departemen_filter,
                            divisi_filter: divisi_filter,
                            bagian_filter: bagian_filter,
                            jabatan_filter: jabatan_filter,
                            shift_filter: shift_filter,
                        }
                    },
                    columns: data_column,
                    order: [
                        [3, 'asc']
                    ]

                });
            }
            $('#table_rekapdata').on('preXhr.dt', function(e, settings, data) {
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

            // tutup Swal setelah data datatable selesai di-load
            // setelah datatable selesai load
            $('#table_rekapdata').on('xhr.dt', function(e, settings, json, xhr) {
                // tutup swal hanya kalau sebelumnya kita buka (bukan saat search)
                if (!settings.oPreviousSearch.sSearch) {
                    Swal.close();
                }
            });
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


            function get_grafik_absensi(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', start_date = '', end_date = '') {
                // console.log(filter_month);
                $.ajax({
                    url: "@if(Auth::user()->is_admin =='hrd'){{url('hrd/report_kedisiplinan/get_grafik_absensi')}}@else{{url('report_kedisiplinan/get_grafik_absensi')}}@endif" + '/' + holding,
                    data: {
                        holding: holding,
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                        shift_filter: shift_filter,
                    },
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        var label_absensi5 = data.label_absensi;
                        var data_absensi_masuk_tepatwaktu = data.data_absensi_masuk_tepatwaktu;
                        var data_absensi_masuk_tidak_hadir = data.data_absensi_masuk_tidak_hadir;
                        var data_absensi_masuk_telat = data.data_absensi_masuk_telat;
                        var data_absensi_masuk_cuti = data.data_absensi_masuk_cuti;
                        var options = {
                            series: [{
                                name: 'Total Tepat Waktu',
                                data: data_absensi_masuk_tepatwaktu
                            }, {
                                name: 'Total Terlambat ',
                                data: data_absensi_masuk_telat
                            }, {
                                name: 'Total Tidak Hadir ',
                                data: data_absensi_masuk_tidak_hadir,
                            }, {
                                name: 'Total Cuti ',
                                data: data_absensi_masuk_cuti
                            }],
                            annotations: {
                                points: [{
                                    x: 'Bananas',
                                    seriesIndex: 0,
                                    label: {
                                        borderColor: '#775DD0',
                                        offsetY: 0,
                                        style: {
                                            color: '#fff',
                                            background: '#775DD0',
                                        },
                                        text: 'Bananas are good',
                                    }
                                }]
                            },
                            chart: {
                                height: 400,
                                type: 'line',
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '50%',
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: [3, 3, 2]
                            },
                            colors: ["#008000", "#F8C300", "#8B0000", "#1E90FF"],
                            grid: {
                                row: {
                                    colors: ['#fff', '#f2f2f2']
                                }
                            },
                            xaxis: {
                                labels: {
                                    rotate: -45
                                },
                                categories: label_absensi5,
                                tickAmount: 31
                            },
                            yaxis: {
                                title: {
                                    text: 'Jumlah Karyawan Absensi',
                                },
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'light',
                                    type: "horizontal",
                                    shadeIntensity: 0.25,
                                    gradientToColors: undefined,
                                    inverseColors: true,
                                    opacityFrom: 0.85,
                                    opacityTo: 0.85,
                                    stops: [50, 0, 100]
                                },
                            }
                        };
                        if (chart_absensi_masuk) {
                            chart_absensi_masuk.destroy();
                        }
                        var chart_absensi_masuk = new ApexCharts(document.querySelector("#grafik_absensi"), options);

                        chart_absensi_masuk.render();
                        chart_absensi_masuk.updateSeries([{
                            name: 'Total Tepat Waktu',
                            data: data_absensi_masuk_tepatwaktu
                        }, {
                            name: 'Total Terlambat ',
                            data: data_absensi_masuk_telat
                        }, {
                            name: 'Total Tidak Hadir ',
                            data: data_absensi_masuk_tidak_hadir
                        }, {
                            name: 'Total Cuti ',
                            data: data_absensi_masuk_cuti
                        }])
                    }
                });

            }
        });
        $(document).ready(function() {
            $('#collapseGrafik').on('shown.bs.collapse', function() {
                $('#toggleBtn').text('Tutup');
            });

            $('#collapseGrafik').on('hidden.bs.collapse', function() {
                $('#toggleBtn').text('Buka');
            });
            $('#collapseFilter').on('shown.bs.collapse', function() {
                $('#toggleBtn').text('Tutup');
            });

            $('#collapseFilter').on('hidden.bs.collapse', function() {
                $('#toggleBtn').text('Buka');
            });
        });
    </script>
    @endsection