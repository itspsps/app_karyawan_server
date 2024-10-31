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
                    <table class="table" id="table_mapping_shift" style="width: 100%; font-size: small;">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">ID&nbsp;Karyawan</th>
                                <th class="text-center">Nama&nbsp;Karyawan</th>
                                <th class="text-center">&nbsp;Jabatan&nbsp;</th>
                                <th class="text-center">Mapping&nbsp;Shift</th>
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
        $('#table_mapping_shift').hide();
        let holding = window.location.pathname.split("/").pop();
        $(document).ready(function() {
            $('#departemen_filter').change(function() {
                departemen_filter = $(this).val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                filter_month = $('#date_filter').val();
                $('#table_mapping_shift').show();
                $('#table_mapping_shift').DataTable().destroy();
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
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
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
                // console.log(filter_month);
                departemen_filter = $('#departemen_filter').val();
                divisi_filter = $('#divisi_filter').val();
                bagian_filter = $('#bagian_filter').val();
                jabatan_filter = $('#jabatan_filter').val();
                $('#table_mapping_shift').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            })
            // load_data();

            function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', filter_month = '') {
                filter_month = $('#date_filter').val();
                // console.log(filter_month);
                var table = $('#table_mapping_shift').DataTable({
                    "scrollY": true,
                    "scrollX": true,
                    processing: true,
                    autoWidth: false,
                    serverSide: true,
                    deferRender: true,
                    pageLength: 50,
                    ajax: {
                        url: "{{ url('karyawan/mapping_shift_datatable') }}" + '/' + holding,
                        data: {
                            filter_month: filter_month,
                            departemen_filter: departemen_filter,
                            divisi_filter: divisi_filter,
                            bagian_filter: bagian_filter,
                            jabatan_filter: jabatan_filter,
                        }
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
                            data: 'fullname',
                            name: 'fullname'
                        },
                        {
                            data: 'jabatan',
                            name: 'jabatan'
                        },
                        {
                            data: 'mapping_shift',
                            name: 'mapping_shift'
                        },

                    ],
                    order: [2, 'ASC'],

                });
            }

        });
    </script>
    @endsection