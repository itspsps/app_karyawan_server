@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<!-- Select2 CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">MAPPING SHIFT KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="">
                    <div class="row g-3 text-center">
                        <div class="col-lg-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="departemen_filter[]" id="departemen_filter" multiple>
                                    <option disabled value="">--</option>
                                    @foreach($departemen as $dept)
                                    <option value="{{$dept->id}}">{{$dept->nama_departemen}}</option>
                                    @endforeach
                                </select>
                                <label for="departemen_filter">Departemen</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="divisi_filter[]" placeholder="Date Filter" id="divisi_filter" multiple>
                                    <option disabled value="">--</option>
                                </select>
                                <label for="divisi_filter">Divisi</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="bagian_filter[]" placeholder="Date Filter" id="bagian_filter" multiple>
                                    <option disabled value="">--</option>
                                </select>
                                <label for="bagian_filter">Bagian</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="jabatan_filter[]" placeholder="Date Filter" id="jabatan_filter" multiple>
                                    <option disabled value="">--</option>
                                </select>
                                <label for="jabatan_filter">Jabatan</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 g-3">
                        <div class="col-lg-8">
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%">
                                <button type="button" class="btn btn-outline-secondary waves-effect">
                                    FILTER DATE : &nbsp;
                                    <i class="mdi mdi-calendar-filter-outline"></i>&nbsp;
                                    <span></span> <i class="mdi mdi-menu-down"></i>
                                    <input type="date" id="start_date" name="start_date" hidden value="">
                                    <input type="date" id="end_date" name="end_date" hidden value="">
                                </button>
                            </div>

                        </div>
                    </div>
                    <hr class="my-5">
                    <div id="btn_selected_karyawan">
                        <button id="btn_selected_proses" class="btn btn-xs btn-primary waves-effect waves-light" type="button">
                            <i class="menu-icon tf-icons mdi mdi-plus"></i> Tambah&nbsp;Mapping&nbsp;(&nbsp;<span id="count_checked">0</span>&nbsp;Selected)
                        </button>
                    </div>
                    <div class="modal fade" id="modal_tambah_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable ">
                            <form method="post" action="@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/mapping_shift/prosesAddMappingShift/'.$holding->holding_code) }}@else{{ url('/karyawan/mapping_shift/prosesAddMappingShift/'.$holding->holding_code) }}@endif" class=" modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <div class="card mb-4" style="padding: 5px; margin: 2;">
                                            <dl id="getplan" class="dl-horizontal row">
                                                <label class="col-sm-12">Nama Karyawan</label>
                                                <dd class="col-sm-5" style="font-weight: bold;">Nomor&nbsp;ID</dd>
                                                <dd class="col-sm-7" style="font-weight: bold;">Nama</dd>
                                            </dl>
                                            <dl id="data_karyawan" class="dl-horizontal row">
                                            </dl>
                                        </div>
                                        <input type='hidden' name="id_karyawan" id="id_karyawan" value="" />
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-control select2 @error('shift_id') is-invalid @enderror"
                                                id="shift_id" name="shift_id">
                                                <option value="">-- Pilih Shift --</option>
                                                @foreach ($shift as $s)
                                                <option value="{{ $s->id }}" {{ old('shift_id') == $s->id ? 'selected' : '' }}>
                                                    {{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="shift_id">Shift</label>
                                        </div>

                                        @error('shift_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                            <label for="tanggal_mulai">Tanggal Mulai</label>
                                        </div>
                                        @error('tanggal_mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                                            <label for="tanggal_akhir">Tanggal Akhir</label>
                                        </div>
                                        @error('tanggal_akhir')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-control @error('libur') is-invalid @enderror" id="libur" name="libur" value="{{ old('libur') }}">
                                                <option value="">-- Pilih Hari Libur --</option>
                                                <option value="0">Minggu</option>
                                                <option value="1">Senin</option>
                                                <option value="2">Selasa</option>
                                                <option value="3">Rabu</option>
                                                <option value="4">Kamis</option>
                                                <option value="5">Jumat</option>
                                                <option value="6">Sabtu</option>
                                            </select>
                                            <label for="libur">Set Libur</label>
                                        </div>
                                        @error('libur')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <input type="hidden" name="tanggal">
                                        <input type="hidden" name="status_absen">
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
                    <table class="table" id="table_mapping_shift" style="width: 100%; font-size: small;">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">ID&nbsp;Karyawan</th>
                                <th class="text-center">Nama&nbsp;Karyawan</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center" width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mapping&nbsp;Jadwal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center" width="30%">
                                    <input class="form-check-input" type="checkbox" value="" id="select_karyawan_all">
                                    <label class="form-check-label" for="select_karyawan_all">&nbsp;&nbsp;Select&nbsp;All&nbsp;&nbsp;</label>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
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
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // $('#table_mapping_shift').hide();
        $('#btn_selected_karyawan').hide();
        let holding = '{{ $holding->holding_code }}';
        let holding_id = '{{ $holding->id }}';
        $(document).ready(function() {

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
        });

        $(document).ready(function() {
            $('#departemen_filter').change(function() {
                departemen_filter_dept = $(this).val();
                divisi_filter_dept = $('#divisi_filter').val();
                bagian_filter_dept = $('#bagian_filter').val();
                jabatan_filter_dept = $('#jabatan_filter').val();
                start_date = $('#start_date').val();
                end_date = $('#end_date').val();


                $('#btn_selected_karyawan').hide();
                // $('#table_mapping_shift').DataTable().destroy();

                $.ajax({
                    type: 'GET',
                    url: "@if(Auth::user()->is_admin =='hrd'){{url('hrd/mapping_shift/get_divisi')}}@else {{url('mapping_shift/get_divisi')}}@endif",
                    data: {
                        holding: holding_id,
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter_dept,
                        divisi_filter: divisi_filter_dept,
                        bagian_filter: bagian_filter_dept,
                        jabatan_filter: jabatan_filter_dept,

                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_divisi').html(msg);
                        $('#divisi_filter').html(msg);
                        $('#bagian_filter').html('<option selected value="">Pilih Bagian</option>');
                        $('#jabatan_filter').html('<option selected value="">Pilih Jabatan</option>');
                        load_data(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept, start_date, end_date);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
            })
            $('#divisi_filter').change(function() {
                divisi_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();
                $('#btn_selected_karyawan').hide();
                // $('#table_mapping_shift').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "@if(Auth::user()->is_admin =='hrd'){{url('hrd/mapping_shift/get_bagian')}}@else {{url('mapping_shift/get_bagian')}}@endif",
                    data: {
                        holding: holding_id,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        filter_month: filter_month,
                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_divisi').html(msg);
                        $('#bagian_filter').html(msg);
                        $('#jabatan_filter').html('<option selected value="">Pilih Jabatan..</option>');
                        load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
            })
            $('#bagian_filter').change(function() {
                bagian_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();
                $('#btn_selected_karyawan').hide();
                $.ajax({
                    type: 'GET',
                    url: "@if(Auth::user()->is_admin =='hrd'){{url('hrd/mapping_shift/get_jabatan')}}@else {{url('mapping_shift/get_jabatan')}}@endif",
                    data: {
                        holding: holding_id,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        filter_month: filter_month,
                        bagian_filter: bagian_filter
                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_bagian').html(msg);
                        $('#jabatan_filter').html(msg);
                        load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
                        // $('#table_mapping_shift').DataTable().destroy();
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
            })
            $('#jabatan_filter').change(function() {
                jabatan_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                filter_month = $('#date_filter').val();
                $('#btn_selected_karyawan').hide();
                // $('#table_mapping_shift').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            var start = moment().startOf('month');
            var end = moment().endOf('month');
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var departemen_filter = [];
            var divisi_filter = [];
            var bagian_filter = [];
            var jabatan_filter = [];

            function cb(start, end) {
                $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                lstart = start.format('YYYY-MM-DD');
                lend = end.format('YYYY-MM-DD');
                $('#start_date').val(lstart);
                $('#end_date').val(lend);
                // console.log(lstart, lend);
                // ambil langsung dari form
                var departemen_filter = $('#departemen_filter').val() || [];
                var divisi_filter = $('#divisi_filter').val() || [];
                var bagian_filter = $('#bagian_filter').val() || [];
                var jabatan_filter = $('#jabatan_filter').val() || [];


                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, lstart, lend);

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

            function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', start_date = '', end_date = '') {
                // console.log(departemen_filter);
                $('#table_mapping_shift').DataTable().destroy();
                var table = $('#table_mapping_shift').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    autoWidth: false,
                    serverSide: true,
                    deferRender: true,
                    pageLength: 50,
                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/karyawan/mapping_shift_datatable') }}@else {{ url('karyawan/mapping_shift_datatable') }}@endif" + '/' + holding,
                        type: 'get',
                        data: {
                            departemen_filter: departemen_filter,
                            divisi_filter: divisi_filter,
                            bagian_filter: bagian_filter,
                            jabatan_filter: jabatan_filter,
                            start_date: start_date,
                            end_date: end_date,
                        },
                    },
                    columns: [{
                            data: 'id',
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
                            data: 'jabatan',
                            name: 'jabatan'
                        },
                        {
                            data: 'mapping_shift',
                            name: 'mapping_shift'
                        },
                        {
                            data: 'select',
                            name: 'select',
                            className: 'text-center',
                            orderable: false
                        },

                    ],
                    order: [2, 'ASC'],

                });
            }
            if ($(".group_select:checked").length > 0) {
                $('#btn_selected_karyawan').show();
                $('#count_checked').html($(".group_select:checked").length);
            } else {
                $('#btn_selected_karyawan').hide();
                $('#count_checked').html($(".group_select:checked").length);
            }
            $(document).on("click", ".group_select", function(e) {
                // console.log($(this).is(':checked'));
                if ($(this).is(':checked') == true) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
                if ($(".group_select:checked").length > 0) {
                    $('#btn_selected_karyawan').show();
                    $('#count_checked').html($(".group_select:checked").length);
                } else {
                    $('#btn_selected_karyawan').hide();
                    $('#count_checked').html($(".group_select:checked").length);
                }
            })
            $(document).on("click", "#select_karyawan_all", function(e) {
                var all = $(this).is(':checked');
                console.log($(".group_select:checked").length);
                if ($(this).is(':checked') == true) {
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
                $(".group_select").each(function(all) {
                    // console.log(all);
                    if ($(this).is(':checked') == true && $('#select_karyawan_all').is(':checked') == false) {
                        $(this).prop("checked", false);
                    } else if ($(this).is(':checked') == false && $('#select_karyawan_all').is(':checked') == true) {
                        $(this).prop("checked", true);
                    }
                });
                if ($(".group_select:checked").length > 0) {
                    $('#btn_selected_karyawan').show();
                    $('#count_checked').html($(".group_select:checked").length);
                } else {
                    $('#btn_selected_karyawan').hide();
                    $('#count_checked').html($(".group_select:checked").length);
                }
            })
            $(document).on("click", "#btn_selected_proses", function(e) {
                var value = [];
                $('.group_select:checked').each(function() {
                    value.push($(this).val());
                });
                console.log(holding);
                var count = value.length;
                $.ajax({
                    url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/karyawan/get_karyawan_selected')}}@else{{ url('karyawan/get_karyawan_selected')}}@endif" + "/" + holding,
                    method: "get",
                    data: {
                        value: value
                    },
                    success: function(data) {
                        console.log(data, count);
                        $.each(data, function(count) {
                            $('#data_karyawan').append("<dd id=" + 'no_id' + " class=" + 'col-sm-4 col-xs-12' + ">" + data[count].nomor_identitas_karyawan + "</dd><dd id=" + 'name' + "  class=" + 'col-sm-8 col-xs-12' + ">" + data[count].name + "</dd>");
                        });
                        $('#id_karyawan').val(value);
                        // Swal.fire({
                        //     title: 'Sukses!',
                        //     text: 'Anda berhasil Kirim Data',
                        //     icon: 'success',
                        //     timer: 1500
                        // })
                        // $('#datatable').DataTable().ajax.reload();
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
                $('#modal_tambah_shift').on('hidden.bs.modal', function(e) {
                    $('#data_karyawan').empty();
                })
                $('#modal_tambah_shift').modal('show');
            })

        });
    </script>
    @endsection