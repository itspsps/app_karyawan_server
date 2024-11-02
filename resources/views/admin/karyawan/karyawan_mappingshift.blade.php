@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
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
                        <h5 class="card-title m-0 me-2">MAPPING SHIFT KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="">
                    <form action="{{ url('/karyawan/mapping_shift/'.$holding) }}">
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
                    <hr class="my-5">
                    <div id="btn_selected_karyawan">
                        <button id="btn_selected_proses" class="btn btn-xs btn-primary waves-effect waves-light" type="button">
                            <i class="menu-icon tf-icons mdi mdi-plus"></i> Tambah&nbsp;Mapping&nbsp;(&nbsp;<span id="count_checked">0</span>&nbsp;Selected)
                        </button>
                    </div>
                    <div class="modal fade" id="modal_tambah_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable ">
                            <form method="post" action="@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/mapping_shift/prosesAddMappingShift/'.$holding) }}@else{{ url('/karyawan/mapping_shift/prosesAddMappingShift/'.$holding) }}@endif" class=" modal-content" enctype="multipart/form-data">
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
                                            <select class="form-control @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id">
                                                <option value="">-- Pilih Shift --</option>
                                                @foreach ($shift as $s)
                                                @if(old('shift_id') == $s->id)
                                                <option value=" {{ $s->id }}" selected>{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @else
                                                <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <label for="shift_id">Shift</label>
                                        </div>
                                        @error('nik')
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
                                <th class="text-center" style="text-align: ;" width="30%">
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        // $('#table_mapping_shift').hide();
        $('#btn_selected_karyawan').hide();
        let holding = window.location.pathname.split("/").pop();
        $(document).ready(function() {
            $('#departemen_filter').change(function() {
                departemen_filter = $(this).val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                // filter_month = $('#date_filter').val();
                $('#table_mapping_shift').show();
                // $('#table_mapping_shift').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "{{url('mapping_shift/get_divisi')}}",
                    data: {
                        holding: holding,
                        departemen_filter: departemen_filter
                    },
                    cache: false,

                    success: function(msg) {
                        // console.log(msg);
                        // $('#id_divisi').html(msg);
                        $('#table_mapping_shift').show();
                        $('#divisi_filter').html(msg);
                        $('#bagian_filter').html('<option value="">Pilih Bagian</option>');
                        $('#jabatan_filter').html('<option value="">Pilih Jabatan</option>');
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },

                })
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter);
            })
            $('#divisi_filter').change(function() {
                divisi_filter = $(this).val();
                departemen_filter = $('#departemen_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();
                $('#table_mapping_shift').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "{{url('mapping_shift/get_bagian')}}",
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

                $('#table_mapping_shift').DataTable().destroy();
                $.ajax({
                    type: 'GET',
                    url: "{{url('mapping_shift/get_jabatan')}}",
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
                $('#table_mapping_shift').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            $('#date_filter').change(function() {
                filter_month = $(this).val();
                console.log(filter_month);
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                $('#table_mapping_shift').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            // load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter);
            load_data();

            function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', filter_month = '') {
                console.log(departemen_filter);
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
                        url: "{{ url('mapping_shift_datatable') }}" + '/' + holding,
                        type: 'get',
                        data: {
                            departemen_filter: departemen_filter,
                            divisi_filter: divisi_filter,
                            bagian_filter: bagian_filter,
                            jabatan_filter: jabatan_filter,
                            filter_month: filter_month,
                        },
                    },
                    columns: [{
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
                console.log(value);
                var count = value.length;
                $.ajax({
                    url: "{{ url('karyawan/get_karyawan_selected')}}",
                    method: "get",
                    data: {
                        value: value
                    },
                    success: function(data) {
                        console.log(count);
                        $.each(data, function(count) {
                            $('#data_karyawan').append("<dd id=" + 'no_id' + " class=" + 'col-sm-4 col-xs-12' + ">" + data[count].nomor_identitas_karyawan + "</dd><dd id=" + 'name' + "  class=" + 'col-sm-8 col-xs-12' + ">" + data[count].name + "</dd>");
                        });
                        console.log(data);
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