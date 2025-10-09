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
                        <h5 class="card-title m-0 me-2">REPORT KEDISIPLINAN KARYAWAN</h5>
                    </div>
                </div>

                <div class="card-body p-0" style="height: calc(1000vh - 60px); overflow-y: auto;">
                    <!-- Filter selalu di atas -->
                    <div id="collapseFilterWrapper" class="sticky-top bg-white" style="z-index: 1020;">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">FILTER REPORT</h6>
                                    <button id="toggleBtnFilter" class="btn btn-sm btn-outline-primary" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseFilter"
                                        aria-expanded="false"
                                        aria-controls="collapseFilter">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                            <div id="collapseFilter" class="collapse show">
                                <div class="card-body">
                                    <!-- ISI FILTER MU -->
                                    <div class="card-body">
                                        <div class="row gy-4 text-center">
                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                <div class="form-floating form-floating-outline">
                                                    <select type="text" class="form-control" name="departemen_filter[]" id="departemen_filter" multiple style="font-size: small;">
                                                        <option disabled value="">-Pilih Departemen-</option>
                                                        @foreach($departemen as $dept)
                                                        <option value="{{$dept->id}}">{{$dept->nama_departemen}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="departemen_filter">Departemen</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                <div class="form-floating form-floating-outline">
                                                    <select type="text" class="form-control" name="divisi_filter[]" placeholder="Date Filter" id="divisi_filter" multiple>
                                                        <option selected disabled value="">--</option>
                                                    </select>
                                                    <label for="divisi_filter">Divisi</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                <div class="form-floating form-floating-outline">
                                                    <select type="text" class="form-control" name="bagian_filter[]" placeholder="Date Filter" id="bagian_filter" multiple>
                                                        <option selected disabled value="">--</option>
                                                    </select>
                                                    <label for="bagian_filter">Bagian</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                <div class="form-floating form-floating-outline">
                                                    <select type="text" class="form-control" name="jabatan_filter[]" placeholder="Date Filter" id="jabatan_filter" multiple>
                                                        <option selected disabled value="">--</option>
                                                    </select>
                                                    <label for="jabatan_filter">Jabatan</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3 gy-4">
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-floating form-floating-outline">
                                                    <select type="text" class="form-control" name="shift_filter" placeholder="Shift Filter" id="shift_filter">
                                                        <option selected disabled value="">~~ Pilih Kategori ~~</option>
                                                        <option value="NON SHIFT">NON SHIFT</option>
                                                        <option value="SHIFT">SHIFT</option>
                                                    </select>
                                                    <label for="shift_filter">Pilih Kategori Shift</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%">
                                                    <button class="btn btn-outline-secondary waves-effect">
                                                        FILTER DATE : &nbsp;
                                                        <i class="mdi mdi-calendar-filter-outline"></i>&nbsp;
                                                        <span></span> <i class="mdi mdi-menu-down"></i>
                                                        <input type="date" id="start_date" name="start_date" hidden value="">
                                                        <input type="date" id="end_date" name="end_date" hidden value="">
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konten yang bisa discroll -->
                    <div class="content-scroll p-3">
                        <div class="row gy-4">
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
                        </div>

                        <!-- Table rekap -->
                        <div class="mt-4">
                            <div class="mb-2 button-excel">
                                <button id="exportExcel" class="btn btn-sm btn-outline-success" type="button">
                                    <i class="mdi mdi-file-excel"></i>
                                    Export Excel
                                </button>
                            </div>
                            <table class="table table-bordered table-hover" id="table_rekapdata" style=" width: 100%; font-size: small;">
                                <thead class="text-center table-primary align-middle" style="white-space: nowrap;">
                                    <tr>
                                        <th rowspan="3" class="text-center">Detail</th>
                                        <th rowspan="3" class="text-center">No.</th>
                                        <th rowspan="3" class="text-center">ID&nbsp;Karyawan</th>
                                        <th rowspan="3" class="text-center">Nama&nbsp;Karyawan</th>
                                        <th rowspan="3" clas="text-center">Departemen</th>
                                        <th rowspan="3" class="text-center">Shift</th>
                                        <th colspan="14" class="text-center">&nbsp;ABSENSI&nbsp;</th>

                                        <th class="text-center th_count_date1">Detail&nbsp;Per&nbsp;Tanggal</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-center">Masuk</th>
                                        <th colspan="2" class="text-center">Pulang</th>
                                        <th rowspan="2">Total&nbsp;Hadir</th>
                                        <th colspan="3" class="text-center">Keterangan</th>
                                        <th rowspan="2">Libur</th>
                                        <th rowspan="2">Tidak&nbsp;Hadir</th>
                                        <th rowspan="2">Net&nbsp;Hadir&nbsp;Kerja</th>
                                        <th rowspan="2">Total&nbsp;Hari</th>
                                        <th class="text-center th_count_date2">Tanggal</th>
                                    </tr>
                                    <tr id="date_absensi"></tr>
                                </thead>
                                <tbody class="text-center align-middle"></tbody>
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
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var departemen_filter = [];
        var divisi_filter = [];
        var bagian_filter = [];
        var jabatan_filter = [];
        var shift_filter = [];

        var start = moment().startOf('month');
        var end = moment().endOf('month');
        // var start = moment('2025-07-21');
        // var end = moment('2025-08-21');
        var lstart, lend;

        function cb(start, end) {
            $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            console.log(start, end);
            // ambil langsung dari form
            var departemen_filter = $('#departemen_filter').val() || [];
            var divisi_filter = $('#divisi_filter').val() || [];
            var bagian_filter = $('#bagian_filter').val() || [];
            var jabatan_filter = $('#jabatan_filter').val() || [];
            var shift_filter = $('#shift_filter').val() || [];

            //console.log(lstart, lend, departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start, end);
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report_kedisiplinan/get_columns') }}@else{{ url('report_kedisiplinan/get_columns') }}@endif" + '/' + holding,
                method: "get",
                data: {
                    start_date: lstart,
                    end_date: lend,
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    shift_filter: shift_filter,
                },
                success: function(data) {
                    // console.log(data);
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


                    load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, shift_filter, lstart, lend, data_column);
                    get_grafik_absensi(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, shift_filter, lstart, lend);
                },
                error: function(data) {
                    var errors = data.errors();
                    console.log(errors);
                }
            });
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
        $('#exportExcel').click(function(e) {
            e.preventDefault();

            var holding_id = $('#holding_id').val(); // contoh ambil dari input/select
            var url = "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report_kedisiplinan/RekapAbsensiKedisiplinan')}}@else{{url('report_kedisiplinan/RekapAbsensiKedisiplinan')}}@endif" + '/' + holding;
            console.log(url);
            // Tampilkan loading (opsional)
            Swal.fire({
                title: 'Sedang menyiapkan file...',
                text: 'Harap tunggu sebentar.',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal men-download file.');
                    }
                    return response.blob();
                })
                .then(blob => {
                    Swal.close(); // tutup loading
                    // Buat link download manual
                    const link = document.createElement('a');
                    const fileURL = window.URL.createObjectURL(blob);
                    link.href = fileURL;
                    link.download = 'REKAP_ABSENSI_' + new Date().toISOString().slice(0, 10) + '.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(fileURL);
                })
                .catch(err => {
                    Swal.fire('Error', err.message, 'error');
                });
        });

        $('#departemen_filter').change(function(e) {
            departemen_filter_dept = $(this).val() || '';
            divisi_filter_dept = $('#divisi_filter').val() || '';
            bagian_filter_dept = $('#bagian_filter').val() || '';
            jabatan_filter_dept = $('#jabatan_filter').val() || '';
            shift_filter_dept = $('#shift_filter').val() || '';
            start_date_dept = $('#start_date').val();
            end_date_dept = $('#end_date').val();
            // $('#table_rekapdata').DataTable().destroy();
            var url = "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report_kedisiplinan/get_divisi')}}@else{{url('report_kedisiplinan/get_divisi')}}@endif" + '/' + holding_id;
            // console.log(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    holding: holding_id,
                    start_date: start_date_dept,
                    end_date: end_date_dept,
                    departemen_filter: departemen_filter_dept,
                    divisi_filter: divisi_filter_dept,
                    bagian_filter: bagian_filter_dept,
                    jabatan_filter: jabatan_filter_dept,
                    shift_filter: shift_filter_dept,
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
                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#divisi_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#divisi_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#divisi_filter').select2('open');
                    }
                    datacolumn_departemen = [{
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
                            data: 'total_semua',
                            name: 'total_semua'
                        },
                    ]
                    const data_column_departemen = datacolumn_departemen.concat(data_dept.datacolumn);
                    // console.log(data_column);
                    $('#table_rekapdata').DataTable().clear();
                    $('#table_rekapdata').DataTable().destroy();
                    $('#date_absensi').empty();
                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Pulang&nbsp;Cepat</th>')
                        .append('<th>Izin</th>')
                        .append('<th>Cuti</th>')
                        .append('<th>Dinas</th>')
                        .append('<th>Libur</th>');

                    console.log(data_dept.data_columns_header);
                    $.each(data_dept.data_columns_header, function(count) {
                        // console.log(count);
                        $('#date_absensi').append("<th id='th_date'>" + data_dept.data_columns_header[count].header + "</th>");
                    });

                    $('.th_count_date').attr('colspan', data_dept.count_period);
                    // console.log(msg);
                    // $('#id_divisi').html(msg);

                    // get_grafik_absensi(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept, start_date_dept, end_date_dept);
                    // load_data(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept, start_date_dept, end_date_dept, data_column_departemen);
                    cb(moment(start_date_dept), moment(end_date_dept));
                },
                // complete: function() {
                //     Swal.close();
                // },

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
            departemen_filter = $('#departemen_filter').val() || '';
            bagian_filter = $('#bagian_filter').val() || '';
            jabatan_filter = $('#jabatan_filter').val() || '';
            shift_filter = $('#shift_filter').val() || '';
            start_date_divisi = $('#start_date').val();
            end_date_divisi = $('#end_date').val();

            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report_kedisiplinan/get_bagian')}}@else{{url('report_kedisiplinan/get_bagian')}}@endif" + '/' + holding_id,
                data: {
                    holding: holding_id,
                    start_date: start_date_divisi,
                    end_date: end_date_divisi,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    divisi_filter: divisi_filter,
                    shift_filter: shift_filter,
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
                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#bagian_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#bagian_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#bagian_filter').select2('open');
                    }
                    datacolumn_divisi = [{
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
                            data: 'total_semua',
                            name: 'total_semua'
                        },
                    ];
                    const data_column_divisi = datacolumn_divisi.concat(data_divisi.datacolumn);
                    // console.log(data_column);
                    $('#table_rekapdata').DataTable().clear();
                    $('#table_rekapdata').DataTable().destroy();
                    $('#date_absensi').empty();
                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Pulang&nbsp;Cepat</th>')
                        .append('<th>Izin</th>')
                        .append('<th>Cuti</th>')
                        .append('<th>Dinas</th>')
                        .append('<th>Libur</th>');

                    $.each(data_divisi.data_columns_header, function(count) {
                        // console.log(count);
                        $('#date_absensi').append("<th id='th_date'>" + data_divisi.data_columns_header[count].header + "</th>");
                    });
                    $('.th_count_date').attr('colspan', data_divisi.count_period);

                    cb(moment(start_date_divisi), moment(end_date_divisi));
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
            // console.log(bagian_filter);
            departemen_filter = $('#departemen_filter').val() || '';
            divisi_filter = $('#divisi_filter').val() || '';
            jabatan_filter = $('#jabatan_filter').val() || '';
            shift_filter = $('#shift_filter').val() || '';
            start_date_bagian = $('#start_date').val();
            end_date_bagian = $('#end_date').val();

            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "{{url('report_kedisiplinan/get_jabatan')}}" + '/' + holding_id,
                data: {
                    holding: holding_id,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    start_date: start_date_bagian,
                    end_date: end_date_bagian,
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    shift_filter: shift_filter
                },
                cache: false,

                success: function(data_jabatan) {
                    console.log(data_jabatan);
                    datacolumn_jabatan = [{
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
                            data: 'total_semua',
                            name: 'total_semua'
                        },
                    ];
                    const data_column_jabatan = datacolumn_jabatan.concat(data_jabatan.datacolumn);
                    // console.log(data_column);
                    $('#table_rekapdata').DataTable().clear();
                    $('#table_rekapdata').DataTable().destroy();
                    $('#date_absensi').empty();
                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Pulang&nbsp;Cepat</th>')
                        .append('<th>Izin</th>')
                        .append('<th>Cuti</th>')
                        .append('<th>Dinas</th>')
                        .append('<th>Libur</th>');

                    $.each(data_jabatan.data_columns_header, function(count) {
                        // console.log(count);
                        $('#date_absensi').append("<th id='th_date'>" + data_jabatan.data_columns_header[count].header + "</th>");
                    });
                    $('.th_count_date').attr('colspan', data_jabatan.count_period);
                    // $('#id_divisi').html(data_divisi);
                    // console.log(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date_bagian, end_date_bagian);
                    $('#jabatan_filter').html(data_jabatan.select);
                    cb(moment(start_date_bagian), moment(end_date_bagian));
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
        $('#jabatan_filter').change(function() {
            jabatan_filter = $(this).val() || '';
            departemen_filter = $('#departemen_filter').val() || '';
            divisi_filter = $('#divisi_filter').val() || '';
            bagian_filter = $('#bagian_filter').val() || '';
            start_date_jabatan = $('#start_date').val();
            end_date_jabatan = $('#end_date').val();
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report_kedisiplinan/get_columns') }}@else{{ url('report_kedisiplinan/get_columns') }}@endif" + '/' + holding,
                method: "get",
                data: {
                    start_date: start_date_jabatan,
                    end_date: end_date_jabatan,
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    shift_filter: shift_filter,
                },
                cache: false,

                success: function(data) {
                    // console.log(data);
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
                            data: 'total_semua',
                            name: 'total_semua'
                        },
                    ]
                    const data_column = datacolumn.concat(data.datacolumn);
                    // console.log(data_column);

                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Pulang&nbsp;Cepat</th>')
                        .append('<th>Izin</th>')
                        .append('<th>Cuti</th>')
                        .append('<th>Dinas</th>')
                        .append('<th>Libur</th>');

                    $.each(data.data_columns_header, function(count) {
                        $('#date_absensi').append("<th id='th_date'>" + data.data_columns_header[count].header + "</th>");
                    });
                    $('.th_count_date').attr('colspan', data.count_period);
                    $('#table_rekapdata').DataTable().destroy();
                    cb(moment(start_date_jabatan), moment(end_date_jabatan));
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
                }
            });
        })
        $('#shift_filter').change(function() {
            shift_filter = $(this).val() || '';
            jabatan_filter = $('#jabatan_filter').val() || '';
            departemen_filter = $('#departemen_filter').val() || '';
            divisi_filter = $('#divisi_filter').val() || '';
            bagian_filter = $('#bagian_filter').val() || '';
            start_date_jabatan = $('#start_date').val();
            end_date_jabatan = $('#end_date').val();
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report_kedisiplinan/get_columns') }}@else{{ url('report_kedisiplinan/get_columns') }}@endif" + '/' + holding,
                method: "get",
                data: {
                    start_date: start_date_jabatan,
                    end_date: end_date_jabatan,
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    shift_filter: shift_filter,
                },
                cache: false,

                success: function(data) {
                    // console.log(data);
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
                            data: 'total_semua',
                            name: 'total_semua'
                        },
                    ]

                    const data_column = datacolumn.concat(data.datacolumn);
                    // console.log(data_column);

                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th>')
                        .append('<th>Pulang&nbsp;Cepat</th>')
                        .append('<th>Izin</th>')
                        .append('<th>Cuti</th>')
                        .append('<th>Dinas</th>')
                        .append('<th>Libur</th>');

                    $.each(data.data_columns_header, function(count) {
                        $('#date_absensi').append("<th id='th_date'>" + data.data_columns_header[count].header + "</th>");
                    });
                    $('.th_count_date').attr('colspan', data.count_period);
                    $('#table_rekapdata').DataTable().destroy();
                    cb(moment(start_date_jabatan), moment(end_date_jabatan));
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
                }
            });
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
                destroy: true,
                pageLength: 50,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                ajax: {
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/report_kedisiplinan-datatable1') }}@else {{ url('report_kedisiplinan-datatable1') }}@endif" + '/' + holding,
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