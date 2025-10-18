@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css" rel="stylesheet" />

    <style type="text/css">
        .my-swal {
            z-index: X;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/@icon/entypo@1.0.3/entypo.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
@endsection
@section('isi')
    @include('sweetalert::alert')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Transactions -->
            <div class="card-body">
                <div class="col-lg-12">

                    <div class="skills layout-spacing ">
                        <div class="widget-content widget-content-area bg-white p-3">
                            <h3 class="">Data Pelamar</h3>
                            <input type="hidden" value="{{ $recruitment_admin_id }}" id="recruitment_admin_id">
                            <br>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                        role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                        Belum Dilihat
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1"
                                        role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                        Kandidat
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2"
                                        role="tab" aria-controls="icon-tabpanel-2" aria-selected="false">

                                        Daftar Tunggu
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="icon-tab-3" data-bs-toggle="tab" href="#icon-tabpanel-3"
                                        role="tab" aria-controls="icon-tabpanel-3" aria-selected="false">
                                        Ditolak
                                    </a>
                                </li>

                            </ul>
                            <div class="tab-content pt-5" id="tab-content">
                                <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel"
                                    aria-labelledby="icon-tab-0">
                                    <div>
                                        <div class="table-responsive py-3" style="width: 100%; font-size: small;">
                                            <table class="table table-striped" id="table_pelamar0"
                                                style="width: 100%; font-size: small;">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Pelamar</th>
                                                        <th>Nomor Whatsapp</th>
                                                        <th>Status</th>
                                                        <th>Lihat CV</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                                    <div>
                                        <div class="table table-striped py-3">
                                            <table class="table" id="table_pelamar1"
                                                style="width: 100%; font-size: small;">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Pelamar</th>
                                                        <th>Nomor Whatsapp</th>
                                                        <th>Tanggal Wawancara</th>
                                                        <th>Tempat Wawancara</th>
                                                        <th>Waktu Wawancara</th>
                                                        <th>Konfirmasi Wawancara</th>
                                                        <th>Status</th>
                                                        <th>Lihat CV</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                                    <div>
                                        <div class="table table-striped py-3">
                                            <table class="table nowrap" id="table_pelamar2"
                                                style="width: 100%; font-size: small;">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Pelamar</th>
                                                        <th>Nomor Whatsapp</th>
                                                        <th>Status</th>
                                                        <th>Rubah Status</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="icon-tabpanel-3" role="tabpanel" aria-labelledby="icon-tab-3">
                                    <div>
                                        <div class="table table-striped py-3">
                                            <table class="table" id="table_pelamar3"
                                                style="width: 100%; font-size: small;">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Pelamar</th>
                                                        <th>Nomor Whatsapp</th>
                                                        <th>Alasan</th>
                                                        <th>Status</th>
                                                        <th>Ubah Status</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
            <!--/ Data Tables -->
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        let holding = window.location.pathname.split("/").pop();
        let id = $('#recruitment_admin_id').val();
        var table = $('#table_pelamar0').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/pg/data-list-user_meta') }}" + '/' + id + '/' + holding,
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
                    data: 'pelamar',
                    name: 'pelamar',
                },
                {
                    data: 'no_wa',
                    name: 'no_wa'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'lihat_cv',
                    name: 'lihat_cv'
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
        var table1 = $('#table_pelamar1').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/pg/data-list-user_kandidat') }}" + '/' + id + '/' + holding,
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
                    data: 'pelamar',
                    name: 'pelamar',
                },
                {
                    data: 'no_wa',
                    name: 'no_wa'
                },
                {
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                },
                {
                    data: 'tempat_wawancara',
                    name: 'tempat_wawancara',
                },

                {
                    data: 'waktu_wawancara',
                    name: 'waktu_wawancara',
                },

                {
                    data: 'feedback',
                    name: 'feedback'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'lihat_cv',
                    name: 'lihat_cv'
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
        $('#icon-tab-1').on('shown.bs.tab', function(e) {
            table1.columns.adjust().draw().responsive.recalc();
        });
        var table = $('#table_pelamar2').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/pg/data-list-user_wait') }}" + '/' + id + '/' + holding,
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
                    data: 'pelamar',
                    name: 'pelamar',
                },
                {
                    data: 'no_wa',
                    name: 'no_wa'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'lihat_cv',
                    name: 'lihat_cv'
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
        $('#icon-tab-2').on('shown.bs.tab', function() {
            table.columns.adjust().draw(false).responsive.recalc();
        });
        var table3 = $('#table_pelamar3').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/pg/data-list-user_reject') }}" + '/' + id + '/' + holding,
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
                    data: 'pelamar',
                    name: 'pelamar',
                },
                {
                    data: 'no_wa',
                    name: 'no_wa'
                },
                {
                    data: 'alasan',
                    name: 'alasan'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'lihat_cv',
                    name: 'lihat_cv'
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
        $('#icon-tab-3').on('shown.bs.tab', function() {
            table3.columns.adjust().draw(false).responsive.recalc();
        });
    </script>
@endsection
