@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<!-- Select2 CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style type="text/css">
    .select2-container,
    .swal2-container {
        z-index: 9999 !important;
    }

    /* ukuran teks di area pilihan (input select2) */
    .select2-container--bootstrap-5 .select2-selection {
        font-size: 0.875rem !important;
        /* Bootstrap small (14px) */
        min-height: calc(1.5em + 0.75rem + 4px);
        /* biar tinggi konsisten */
    }

    /* Sesuaikan Select2 agar serasi dengan form-control Bootstrap 5 */
    .select2-container--bootstrap-5 .select2-selection--single {
        /* Tinggi total elemen Select2 */
        height: calc(2.25rem + 2px);
        /* Biasanya 2.25rem (36px) + 1px border atas + 1px border bawah = 38px */
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        /* Padding standar Bootstrap 5 */
        line-height: 1.5;
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

    /* Definisikan ukuran icon MDI yang lebih besar */
    .icon-karyawan {
        /* Gunakan ukuran yang Anda inginkan, misalnya 3em atau 48px */
        font-size: 3.5em;
        /* 3.5 kali lipat ukuran font default */
        color: #6c757d;
        /* Warna default icon (abu-abu) */
    }

    /* Keterangan: Anda bisa mengganti '3.5em' dengan '48px' atau nilai yang Anda anggap pas. */

    /* (Opsional) CSS interaktivitas yang sudah kita buat sebelumnya */
    .karyawan-tile {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: block;
    }

    .karyawan-tile:hover {
        background-color: #f8f9fa;
    }

    /* Style saat kotak dipilih */
    .karyawan-tile:has(input[type="checkbox"]:checked) {
        background-color: #e6f7ff;
        border-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        box-shadow: 0 0 5px rgba(var(--bs-primary-rgb), 0.5);
    }

    /* Mengubah warna icon saat kotak dipilih (agar lebih menonjol) */
    .karyawan-tile:has(input[type="checkbox"]:checked) .icon-karyawan {
        color: var(--bs-primary);
        /* Warna icon berubah menjadi biru */
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
                        <h5 class="card-title m-0 me-2">MAPPING SHIFT KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body p-0" style="height: calc(1000vh - 60px); overflow-y: auto;">
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
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="divisi_filter[]" id="divisi_filter" multiple>
                                            <option selected disabled value="">-- Pilih Divisi --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="bagian_filter[]" id="bagian_filter" multiple>
                                            <option selected disabled value="">-- Pilih Bagian --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <select type="text" class="form-control" name="jabatan_filter[]" id="jabatan_filter" multiple>
                                            <option selected disabled value="">-- Pilih Jabatan --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row gy-4 align-items-end">
                                <div class="col-lg-6 col-md-6col-sm-6">
                                    <div class="form-floating form-floating-outline">
                                        <div id="filterrange" style="white-space: nowrap;">
                                            <button class="btn btn-outline-secondary w-100 ">
                                                <span class="fw-bold">FILTER&nbsp;DATE&nbsp;:&nbsp;</span>
                                                <span class="date_daterange"></span>
                                                <input type="date" id="start_date" name="start_date" hidden value="">
                                                <input type="date" id="end_date" name="end_date" hidden value="">
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 d-flex justify-content-end">
                                    <button type="button" class="btn btn-block w-100" id="btn_filter">
                                        <i class="mdi mdi-filter-outline text-primary"></i><span class="text-primary">&nbsp;Filter</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="content-scroll p-3">
                        <hr class="my-1">
                        <button id="btn_selected_proses" class="btn btn-xs btn-primary waves-effect waves-light" type="button">
                            <i class="menu-icon tf-icons mdi mdi-plus"></i> Tambah&nbsp;Mapping
                        </button>
                        <div class="modal fade" id="modal_tambah_shift" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable ">
                                <form method="post" id="form_tambah_shift" class=" modal-content" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="backDropModalTitle">Tambah Shift</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card mb-4" style="padding: 5px; margin: 2;">
                                            <label class="col-sm-12">Pilih Karyawan</label>
                                            <div class="col-12 mb-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="nama_karyawan" value="" placeholder="Cari Karyawan">
                                                </div>
                                            </div>
                                            <div class="row" id="list_karyawan" style="height:300px; overflow-y: scroll;">

                                            </div>
                                        </div>
                                        <div class="card mb-4" style="padding: 5px; margin: 2;">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <span id="count_karyawan" class="font-weight-bold">Karyawan Selected : 0 Karyawan<< /span>
                                                </div>
                                                <div id="jadwal_container">

                                                    <div class="list_date_jadwal row mb-3">
                                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                                            <div class="form-floating form-floating-outline">
                                                                <select class="form-control @error('shift_id') is-invalid @enderror"
                                                                    id="shift_id" name="shift_id[]">
                                                                    <option value="">-- Pilih Shift --</option>
                                                                    @foreach ($shift as $s)
                                                                    <option value="{{ $s->id }}" {{ old('shift_id') == $s->id ? 'selected' : '' }}>
                                                                        {{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('shift_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control single-date @error('tanggal') is-invalid @enderror" readonly id="tanggal" name="tanggal[]" value="">
                                                                <label for="tanggal">Tanggal</label>
                                                            </div>
                                                            @error('tanggal')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-sm-2 d-flex align-items-start">
                                                            <button type="button" class="btn btn-outline-danger btn-hapus-date">
                                                                <i class="mdi mdi-close"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-12 text-end">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn_tambah_date">
                                                            <i class="mdi mdi-plus me-1"></i> Tambah Jadwal
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                        <button type="button" id="btn_tambah_shift" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- <div id="calendar"></div> -->
                        <div class="mt-3">
                            <div id="content_null">
                                <div class="alert alert-secondary" role="alert">
                                    <div class="alert-content text-center">
                                        <svg width="120" height="120" class="text-center" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">

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
                        </div>

                        <table class="table table-bordered" id="table_mapping_shift" style="width: 100%; font-size: small;text-wrap: nowrap;">

                        </table>
                    </div>
                    <div class="modal fade" id="modalDetailTanggal" tabindex="-1" aria-labelledby="modalDetailKaryawanLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <h5 class="modal-title" id="modalDetailKaryawanLabel">Detail Profil & Shift Karyawan: <span id="namaKaryawanModal"></span></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-5 border-end">
                                            <h6 class="text-primary mb-3">Informasi Profil</h6>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">ID Karyawan:</label>
                                                <p id="idKaryawanDetail" class="form-control-static"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jabatan:</label>
                                                <p id="jabatanDetail" class="form-control-static"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Departemen:</label>
                                                <p id="departemenDetail" class="form-control-static"></p>
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <h6 class="text-primary mb-3">Detail & Edit Shift</h6>
                                            <form id="formEditShift" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <input type="text" class="form-control" id="idMappingShift" name="idMappingShift" value="" hidden>
                                                    <label for="tanggalShift" class="form-label fw-bold">Tanggal Shift Aktif</label>
                                                    <input type="text" class="form-control" id="tanggalShift" name="tanggalShift" value="" readonly>
                                                    <input type="text" class="form-control" id="tanggalMasukShift" name="tanggalMasukShift" value="" hidden>
                                                    <div class="form-text">Tanggal shift yang ditampilkan.</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="shiftSaatIni" class="form-label fw-bold">Shift Saat Ini</label>
                                                    <select class="form-select" id="shiftSaatIni" name="shiftSaatIni" required>
                                                        <option value="">Pilih Shift Baru</option>
                                                        @foreach ($shift as $a)
                                                        <option value="{{ $a->id }}"> {{ $a->nama_shift . " (" . $a->jam_masuk . " - " . $a->jam_keluar . ") " }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-text">Pilih shift baru untuk karyawan ini.</div>
                                                </div>
                                                <button type="butotn" id="btn_change_shift" class="btn btn-sm btn-success float-end">Simpan Perubahan Shift&nbsp;<i class="mdi mdi-content-save-move"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        var start_filter = moment();
        var end_filter = moment();
        $('#filterrange').daterangepicker({
            startDate: start_filter,
            endDate: end_filter,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, picker);
        $('#tanggal').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 10),
            locale: {
                format: 'YYYY-MM-DD' // 👈 Format tanggal yang diinginkan
            }
        }, function(start, end, label) {
            var years = moment().diff(start, 'years');
            alert("You are " + years + " years old!");
        });

        function picker(start, end) {
            $('#filterrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            let lstart = start.format('YYYY-MM-DD');
            let lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            // console.log(lstart, lend);
        }
        let holding = '{{ $holding->holding_code }}';
        let holding_id = '{{ $holding->id }}';

        $('#btn_filter').click(function(e) {
            // ambil langsung dari form
            var departemen_filter = $('#departemen_filter').val() || [];
            var divisi_filter = $('#divisi_filter').val() || [];
            var bagian_filter = $('#bagian_filter').val() || [];
            var jabatan_filter = $('#jabatan_filter').val() || [];
            var start_date = $('#start_date').val() || '';
            var end_date = $('#end_date').val() || '';
            // console.log(start_date, end_date);
            //console.log(lstart, lend, departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start, end);
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/mapping_shift/get_columns') }}@else{{ url('/mapping_shift/get_columns') }}@endif" + '/' + holding,
                method: "get",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                },
                success: function(data) {
                    // console.log(data);
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
                            data: 'departemen',
                            name: 'departemen'
                        },
                        {
                            data: 'jabatan',
                            name: 'jabatan'
                        },
                    ];
                    const data_column = datacolumn.concat(data.datacolumn);
                    // console.log(data_column);
                    // 1. Destroy DataTable dulu kalau sudah ada
                    $('#content_null').empty();
                    $('#table_mapping_shift').empty();
                    $('#table_mapping_shift').append('<thead><tr><th rowspan="2" class="text-center">No.</th><th colspan="4" class="text-center">Karyawan</th><th id="count_date" class="text-center">JADWAL SHIFT (' + data.start_date + ' s/d ' + data.end_date + ')</th></tr><tr id="date_absensi"></tr></thead><tbody class="table-border-bottom-0 text-center"></tbody>');
                    if ($.fn.DataTable.isDataTable('#table_mapping_shift')) {
                        $('#table_mapping_shift').DataTable().clear().destroy();
                    }
                    $('#date_absensi').empty();
                    $('#date_absensi').append('<th class="text-center">NIP</th>')
                        .append('<th class="text-center">Nama</th>')
                        .append('<th class="text-center">Departemen</th>')
                        .append('<th class="text-center">Jabatan</th>');
                    // kolom tanggal dinamis dari backend
                    // console.log(data.data_columns_header);
                    $('#count_date').attr('colspan', data.count_period);
                    $.each(data.data_columns_header, function(i, col) {
                        $('#date_absensi').append('<th class="text-center">' + col.header + '</th>');
                    });

                    load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date, data_column);
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
            // $('#table_mapping_shift').DataTable().destroy();
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
                    // console.log('error:', data)
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

            // $('#table_mapping_shift').DataTable().destroy();
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

            // $('#table_mapping_shift').DataTable().destroy();
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


        var start = moment().startOf('month');
        var end = moment().endOf('month');
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var departemen_filter = [];
        var divisi_filter = [];
        var bagian_filter = [];
        var jabatan_filter = [];

        function cb(start, end) {
            $('#filterrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            // console.log(start, end);

        }

        $('#filterrange').daterangepicker({
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

        function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', start_date = '', end_date = '', data_column = '') {

            var tab = $('#table_mapping_shift').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                autoWidth: false,
                serverSide: true,
                deferRender: true,
                pageLength: -1,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                destroy: true,
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

                ajax: {
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/mapping_shift_datatable') }}@else {{ url('karyawan/mapping_shift_datatable') }}@endif" + '/' + holding,
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                    }
                },
                columns: data_column,
                order: [
                    [2, 'asc']
                ]

            });
        }


        $(document).on("click", "#btn_selected_proses", function(e) {
            e.preventDefault();
            // ambil langsung dari form
            var departemen_filter = $('#departemen_filter').val() || [];
            var divisi_filter = $('#divisi_filter').val() || [];
            var bagian_filter = $('#bagian_filter').val() || [];
            var jabatan_filter = $('#jabatan_filter').val() || [];
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon menunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/get_karyawan_mapping') }}@else {{ url('karyawan/get_karyawan_mapping') }}@endif" + '/' + holding,
                method: "get",
                data: {
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    holding: holding,
                },
                success: function(data) {
                    // console.log(data);
                    $('#list_karyawan').empty();
                    $('#count_karyawan').text('Karyawan Selected : 0 Karyawan');
                    Swal.close();
                    $('#modal_tambah_shift').modal('show');
                    if (data.code == 200) {
                        if (data.data.length > 0) {
                            $.each(data.data, function(key, value) {
                                $('#list_karyawan').append(
                                    '<div class="col-md-3 col-sm-6 mb-3 karyawan-item" data-name="' + value.name + '">' +
                                    '<label class="karyawan-tile p-3 border rounded shadow-sm w-100 text-center">' +
                                    '<i class="mdi mdi-account-outline icon-karyawan d-block mb-0" style="font-size: 40px;"></i>' +
                                    '<input type="checkbox" class="group_select d-none" name="karyawan_id[]" value="' + value.id + '">' +
                                    '<span style="font-size: 10px;">' + value.name + '</span>' +
                                    '</label>' +
                                    '</div>');
                            });
                        } else {
                            $('#list_karyawan').append('<div class="col-12"><p class="text-center">Tidak ada karyawan sesuai filter.</p></div>');
                        }
                    } else {
                        $('#list_karyawan').append('<div class="col-12"><p class="text-center">Tidak ada karyawan sesuai filter.</p></div>');
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
                }
            })
        })
        $(document).on('click', '.karyawan-tile', function() {
            let checkbox = $(this).find('.group_select');
            checkbox.prop('checked', !checkbox.prop('checked')); // toggle
            $(this).toggleClass('selected', checkbox.prop('checked')); // optional styling
            updateCheckedCount();
        });

        // Fungsi untuk menghitung jumlah checkbox yang dicentang
        function updateCheckedCount() {
            let totalChecked = $('.group_select:checked').length;
            $('#count_karyawan').text('Karyawan Selected : ' + totalChecked + ' Karyawan'); // tampilkan ke elemen tertentu
        }
        $('#nama_karyawan').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();
            $('.karyawan-item').each(function() {
                var name = $(this).data('name').toLowerCase();
                if (name.indexOf(searchValue) > -1) {
                    // console.log($(this));
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        var templateHtml = $('#jadwal_container .list_date_jadwal:first').clone();
        var $firstRow = $('#jadwal_container .list_date_jadwal:first');
        var $templateRow = $firstRow.clone();

        $templateRow.find('select').val('');
        $templateRow.find('input').val('');

        // ===========================================
        // 2. FUNGSI TAMBAH BARIS
        // ===========================================
        $('#btn_tambah_date').on('click', function() {
            // Ambil baris terakhir
            var $lastRow = $('.list_date_jadwal').last();

            // Clone baris terakhir sebagai template
            var $newRow = $lastRow.clone();

            // Hapus instance daterangepicker lama
            $newRow.find('.daterangepicker').remove();

            // Ambil nilai shift & tanggal dari baris terakhir
            var lastShift = $lastRow.find('select[name="shift_id[]"]').val();
            var lastDateStr = $lastRow.find('input[name="tanggal[]"]').val();

            // === Set nilai awal di baris baru ===
            if (lastDateStr) {
                // Tambah 1 hari dari tanggal sebelumnya
                var nextDate = moment(lastDateStr, 'YYYY-MM-DD').add(1, 'days').format('YYYY-MM-DD');
                $newRow.find('input[name="tanggal[]"]').val(nextDate);
            } else {
                // Kosongkan jika belum ada
                $newRow.find('input[name="tanggal[]"]').val('');
            }

            // Samakan shift
            $newRow.find('select[name="shift_id[]"]').val(lastShift);

            // Tambahkan ke container
            $('#jadwal_container').append($newRow);

            // Re-init Daterangepicker untuk input baru
            $newRow.find('input.single-date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Tampilkan tombol hapus
            $newRow.find('.btn-hapus-date').show();
        });




        $('#jadwal_container').on('click', '.btn-hapus-date', function() {
            // Cek jumlah baris yang ada
            var rowCount = $('#jadwal_container .list_date_jadwal').length;

            // Hapus parent terdekat dengan class .list_date_jadwal (yaitu baris jadwal)
            if (rowCount > 1) {
                $(this).closest('.list_date_jadwal').remove();
            } else {
                // Opsional: Jika Anda tidak ingin baris terakhir dihapus (minimal harus ada 1)
                Swal.fire({
                    title: 'Minimal harus ada 1 baris jadwal.',
                    icon: 'error',
                    timer: 4500
                });
            }
        });
        $(document).on("click", "#detail_shift", function(e) {
            e.preventDefault();
            var tanggal = $(this).data('tanggal');
            var tanggal_masuk = $(this).data('tanggal_masuk');
            var shift = $(this).data('shift');
            var nip = $(this).data('nip');
            var departemen = $(this).data('departemen');
            var jabatan = $(this).data('jabatan') + ' ' + $(this).data('bagian');
            var nama = $(this).data('nama');
            var idmapping = $(this).data('idmapping');
            $('#namaKaryawanModal').text(nama);
            // console.log(tanggal, tanggal_masuk, nip);
            $('#tanggalShift').val(tanggal);
            $('#tanggalMasukShift').val(tanggal_masuk);
            $('#idKaryawanDetail').text(nip);
            $('#departemenDetail').text(departemen);
            $('#jabatanDetail').text(jabatan);
            $('#idMappingShift').val(idmapping);
            $('#shiftSaatIni').val(shift).trigger('change');

            $('#modalDetailTanggal').modal('show'); // #modalDetailTanggal

        });
        $(document).on("click", "#btn_change_shift", function(e) {
            e.preventDefault();
            var url = "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/mapping_shift/prosesEditMappingShift/'.$holding->holding_code) }}@else{{ url('/karyawan/mapping_shift/prosesEditMappingShift/'.$holding->holding_code) }}@endif";
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon menunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            $.ajax({
                url: url,
                method: "post",
                data: $("#formEditShift").serialize(),
                success: function(data) {
                    // console.log(data);
                    Swal.close();
                    $('#modalDetailTanggal').modal('hide');
                    $('#formEditShift').trigger('reset');
                    $('#table_mapping_shift').DataTable().ajax.reload();
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: data.message,
                            icon: 'success',
                            timer: 4500
                        })
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message,
                            icon: 'error',
                            timer: 4500
                        })
                    }
                },
                error: function(data) {
                    Swal.close();
                    // console.log(data);
                    $('#table_mapping_shift').DataTable().ajax.reload();
                    $('#modalDetailTanggal').modal('hide');
                    $('#formEditShift').trigger('reset');
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.responseJSON.message,
                        icon: 'error',
                        timer: 4500
                    })
                }
            })
        });
        $(document).on("click", "#btn_tambah_shift", function(e) {
            var url = "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/mapping_shift/prosesAddMappingShift/'.$holding->holding_code) }}@else{{ url('/karyawan/mapping_shift/prosesAddMappingShift/'.$holding->holding_code) }}@endif";
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon menunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            $.ajax({
                url: url,
                method: "post",
                data: $("#form_tambah_shift").serialize(),
                success: function(data) {
                    // console.log(data);
                    Swal.close();
                    if (data.code == 200) {
                        $('#modal_tambah_shift').modal('hide');
                        $('#form_tambah_shift').trigger('reset');
                        $('#shift_id').val(null).trigger('change');
                        $('#table_mapping_shift').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Sukses!',
                            text: data.message,
                            icon: 'success',
                            timer: 4500
                        })
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message,
                            icon: 'error',
                            timer: 4500
                        })
                    }
                },
                error: function(data) {
                    Swal.close();
                    // console.log(data);
                    $('#table_mapping_shift').DataTable().ajax.reload();
                    $('#modal_tambah_shift').modal('hide');
                    $('#form_tambah_shift').trigger('reset');
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.responseJSON.message,
                        icon: 'error',
                        timer: 4500
                    })
                }
            })
        })
    });
</script>
@endsection