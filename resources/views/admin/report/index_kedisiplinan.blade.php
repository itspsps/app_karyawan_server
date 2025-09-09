@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />
<link rel="preload" href="{{asset('admin/assets/vendor/libs/apex-charts/apex-charts.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" />
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
                        <h5 class="card-title m-0 me-2">REPORT KEDISIPLINAN KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="">
                    <div class="row g-3 text-center">
                        <div class="col-3">
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
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="divisi_filter" placeholder="Date Filter" id="divisi_filter">
                                    <option selected disabled value="">--</option>
                                </select>
                                <label for="divisi_filter">Divisi</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="bagian_filter" placeholder="Date Filter" id="bagian_filter">
                                    <option selected disabled value="">--</option>
                                </select>
                                <label for="bagian_filter">Bagian</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="jabatan_filter" placeholder="Date Filter" id="jabatan_filter">
                                    <option selected disabled value="">--</option>
                                </select>
                                <label for="jabatan_filter">Jabatan</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 g-3">
                        <div class="col-lg-3">
                            <input id="filter_month" class="form-control" name="filter_month" type="month" value="{{date('Y-m')}}">

                        </div>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row gy-4">
                    <div class="col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Grafik Absensi Karyawan Kontrak Kerja @if($holding->holding_category=='SPS') PT. SURYA PANGAN SEMESTA @elseif($holding->holding_category=='SP') CV. SUMBER PANGAN @else CV. SURYA INTI PANGAN @endif</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="grafik_absensi"></div>
                                <div class="mt-1 mt-md-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nav-align-top">
                    <div class="row">
                        <div class="col-2">
                        </div>
                        <div class="col-8">
                            <ul class="nav nav-pills nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a type="button" style="width: auto;" class="nav-link active" role="tab" data-bs-toggle="tab" href="#navs-pills-justified-home">
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
                            <table class="table" id="table_rekapdata" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th rowspan="2" class="text-center">Detail</th>
                                        <th rowspan="2" class="text-center">No.</th>
                                        <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                        <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                        <th colspan="3" class="text-center">&nbsp;ABSENSI&nbsp;</th>
                                        <th colspan="4" class="text-center">Keterangan</th>
                                        <th colspan="1" class="text-center">Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                        <th rowspan="2" class="text-center">Total&nbsp;Keseluruhan</th>
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
                            <table class="table" id="table_rekapdata1" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th rowspan="2" class="text-center">Detail</th>
                                        <th rowspan="2" class="text-center">No.</th>
                                        <th rowspan="2" class="text-center">ID&nbsp;Karyawan</th>
                                        <th rowspan="2" class="text-center">Nama&nbsp;Karyawan</th>
                                        <th colspan="3" class="text-center">Hadir&nbsp;Kerja</th>
                                        <th colspan="4" class="text-center">Keterangan</th>
                                        <th colspan="1" class="text-center">Tidak&nbsp;Hadir&nbsp;Kerja</th>
                                        <th rowspan="2" class="text-center">Total&nbsp;Keseluruhan</th>
                                    </tr>
                                    <tr>
                                        <th>Tepat&nbsp;Waktu</th>
                                        <th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th>
                                        <th>Telat&nbsp;Hadir&nbsp;<span>(-&nbsp;15&nbsp;Menit)</span></th>
                                        <th>Izin</th>
                                        <th>Cuti</th>
                                        <th>Dinas</th>
                                        <th>Libur</th>
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
<script>
    $(document).ready(function() {

        var holding = '{{$holding->holding_code}}';
        var chart_absensi_masuk;
        var data_column;
        let table_rekapdata;
        var filter_month = $('#filter_month').val();
        departemen_filter = $('#departemen_filter').val();
        divisi_filter = $('#divisi_filter').val();
        bagian_filter = $('#bagian_filter').val();
        jabatan_filter = $('#jabatan_filter').val();

        $(document).on("change", "#filter_month", function(e) {
            e.preventDefault();
            let filter_month1 = $(this).val();
            departemen_filter_month = $('#departemen_filter').val();
            divisi_filter_month = $('#divisi_filter').val();
            bagian_filter_month = $('#bagian_filter').val();
            jabatan_filter_month = $('#jabatan_filter').val();
            $.ajax({
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report_kedisiplinan/get_columns') }}@else{{ url('report_kedisiplinan/get_columns') }}@endif" + '/' + holding,
                method: "get",
                data: {
                    filter_month: filter_month1,
                    departemen_filter: departemen_filter_month,
                    divisi_filter: divisi_filter_month,
                    bagian_filter: bagian_filter_month,
                    jabatan_filter: jabatan_filter_month,
                },
                error: function() {
                    alert('Something is wrong');
                },
                success: function(data1) {
                    // console.log(data1);
                    datacolumn1 = [{
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
                    const data_column1 = datacolumn1.concat(data1.datacolumn);
                    // console.log(data_column);
                    $('#table_rekapdata').DataTable().clear();
                    $('#table_rekapdata').DataTable().destroy();
                    $('#date_absensi').empty();
                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th><th>Izin</th><th>Cuti</th><th>Dinas</th><th>Libur</th><th>Alfa</th>');

                    $.each(data1.data_columns_header, function(count) {
                        // console.log(count);
                        $('#date_absensi').append("<th id='th_date'>" + data1.data_columns_header[count].header + "</th>");
                    });
                    $('#th_count_date').attr('colspan', data1.count_period);
                    load_data(departemen_filter_month, divisi_filter_month, bagian_filter_month, jabatan_filter_month, filter_month1, data_column1);
                    get_grafik_absensi(departemen_filter_month, divisi_filter_month, bagian_filter_month, jabatan_filter_month, filter_month1);
                },
            });
            // console.log(filter_month);
        });

        $(document).on("change", "#departemen_filter", function(e) {
            e.preventDefault();
            departemen_filter_dept = $(this).val();
            divisi_filter_dept = $('#divisi_filter').val();
            bagian_filter_dept = $('#bagian_filter').val();
            jabatan_filter_dept = $('#jabatan_filter').val();
            filter_month_dept = $('#filter_month').val();
            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report/get_divisi')}}@else{{url('report/get_divisi')}}@endif" + holding,
                data: {
                    holding: holding,
                    filter_month: filter_month_dept,
                    departemen_filter: departemen_filter_dept,
                    divisi_filter: divisi_filter_dept,
                    bagian_filter: bagian_filter_dept,
                    jabatan_filter: jabatan_filter_dept,
                },
                cache: false,
                success: function(data_dept) {
                    console.log(data_dept);
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
                    const data_column_departemen = datacolumn_departemen.concat(data_dept.datacolumn);
                    // console.log(data_column);
                    $('#table_rekapdata').DataTable().clear();
                    $('#table_rekapdata').DataTable().destroy();
                    $('#date_absensi').empty();
                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th><th>Izin</th><th>Cuti</th><th>Dinas</th><th>Libur</th><th>Alfa</th>');

                    $.each(data_dept.data_columns_header, function(count) {
                        // console.log(count);
                        $('#date_absensi').append("<th id='th_date'>" + data_dept.data_columns_header[count].header + "</th>");
                    });
                    $('#th_count_date').attr('colspan', data_dept.count_period);
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#divisi_filter').html(data_dept.select);
                    $('#bagian_filter').html('<option value="">Pilih Bagian</option>');
                    $('#jabatan_filter').html('<option value="">Pilih Jabatan</option>');
                    get_grafik_absensi(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept, filter_month_dept);
                    load_data(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept, filter_month_dept, data_column_departemen);
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
            filter_month = $('#filter_month').val();
            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/report/get_bagian')}}@else{{url('report/get_bagian')}}@endif",
                data: {
                    holding: holding,
                    filter_month: filter_month,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
                    divisi_filter: divisi_filter,
                },
                cache: false,

                success: function(data_divisi) {
                    // console.log(data_divisi);
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
                    $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th><th>Izin</th><th>Cuti</th><th>Dinas</th><th>Libur</th><th>Alfa</th>');

                    $.each(data_divisi.data_columns_header, function(count) {
                        // console.log(count);
                        $('#date_absensi').append("<th id='th_date'>" + data_divisi.data_columns_header[count].header + "</th>");
                    });
                    $('#th_count_date').attr('colspan', data_divisi.count_period);
                    // $('#id_divisi').html(data_divisi);
                    $('#bagian_filter').html(data_divisi.select);
                    $('#jabatan_filter').html('<option value="">Pilih Jabatan..</option>');
                    get_grafik_absensi(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
                    load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month, data_column_divisi);
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
            filter_month = $('#filter_month').val();

            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "{{url('report/get_jabatan')}}",
                data: {
                    holding: holding,
                    bagian_filter: bagian_filter
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_bagian').html(msg);
                    $('#jabatan_filter').html(msg);
                    get_grafik_absensi(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
                    load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
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
            filter_month = $('#filter_month').val();
            // $('#table_rekapdata').DataTable().destroy();
            get_grafik_absensi(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
        })
        // load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);


        $.ajax({
            url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/report_kedisiplinan/get_columns') }}@else{{ url('report_kedisiplinan/get_columns') }}@endif" + '/' + holding,
            method: "get",
            data: {
                filter_month: filter_month,
                departemen_filter: departemen_filter,
                divisi_filter: divisi_filter,
                bagian_filter: bagian_filter,
                jabatan_filter: jabatan_filter,
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

                $('#date_absensi').append('<th>Tepat&nbsp;Waktu</th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;-&nbsp;15&nbsp;Menit)</span></th><th>Telat&nbsp;Hadir&nbsp;<span>(&nbsp;+&nbsp;15&nbsp;Menit)</span></th><th>Izin</th><th>Cuti</th><th>Dinas</th><th>Libur</th><th>Alfa</th>');

                $.each(data.data_columns_header, function(count) {
                    $('#date_absensi').append("<th id='th_date'>" + data.data_columns_header[count].header + "</th>");
                });
                $('#th_count_date').attr('colspan', data.count_period);
                $('#table_rekapdata').DataTable().destroy();
                load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month, data_column);
                get_grafik_absensi(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, filter_month);
            },
            error: function(data) {
                var errors = data.errors();
                console.log(errors);
            }
        });


        function load_data(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', filter_month = '', data_column = '') {
            // console.log(data_column);

            var table_rekapdata = $('#table_rekapdata').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                autoWidth: false,
                serverSide: true,
                deferRender: true,
                dom: 'Blfrtip',

                buttons: [{

                        extend: 'excelHtml5',
                        className: 'btn btn-sm btn-success',
                        text: '<i class="menu-icon tf-icons mdi mdi-file-excel"></i>Excel',
                        titleAttr: 'Excel',
                        title: 'DATA ABSENSI KARYAWAN ',
                        messageTop: 'Bulan : '.filter_month,
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
                ajax: {
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/report_kedisiplinan-datatable') }}@else {{ url('report_kedisiplinan-datatable') }}@endif" + '/' + holding,
                    data: {
                        filter_month: filter_month,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                    }
                },
                columns: data_column,
                order: [
                    [3, 'asc']
                ]

            });
        }
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


        function get_grafik_absensi(departemen_filter = '', divisi_filter = '', bagian_filter = '', jabatan_filter = '', filter_month = '') {
            // console.log(start_date);
            $.ajax({
                url: "@if(Auth::user()->is_admin =='hrd'){{url('hrd/report/get_grafik_absensi')}}@else {{url('report/get_grafik_absensi')}} @endif",
                data: {
                    holding: holding,
                    filter_month: filter_month,
                    departemen_filter: departemen_filter,
                    divisi_filter: divisi_filter,
                    bagian_filter: bagian_filter,
                    jabatan_filter: jabatan_filter,
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
                            name: 'Jumlah Karyawan Absen Masuk Tepat Waktu',
                            data: data_absensi_masuk_tepatwaktu
                        }, {
                            name: 'Jumlah Karyawan Absen Masuk Terlambat ',
                            data: data_absensi_masuk_telat
                        }, {
                            name: 'Jumlah Karyawan Tidak Hadir ',
                            data: data_absensi_masuk_tidak_hadir,
                        }, {
                            name: 'Jumlah Karyawan Cuti ',
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
                        name: 'Jumlah Karyawan Absen Masuk Tepat Waktu',
                        data: data_absensi_masuk_tepatwaktu
                    }, {
                        name: 'Jumlah Karyawan Absen Masuk Terlambat ',
                        data: data_absensi_masuk_telat
                    }, {
                        name: 'Jumlah Karyawan Tidak Hadir ',
                        data: data_absensi_masuk_tidak_hadir
                    }, {
                        name: 'Jumlah Karyawan Cuti ',
                        data: data_absensi_masuk_cuti
                    }])
                }
            });

        }
    });
</script>
@endsection