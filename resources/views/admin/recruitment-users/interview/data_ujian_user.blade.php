@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <style type="text/css">
        .my-swal {
            z-index: X;
        }

        .nowrap {
            white-space: nowrap;
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
                            <h5 class="card-title m-0 me-2">DATA INTERVIEW ({{ $user_recruitment->Cv->nama_lengkap }})</h5>
                        </div>
                        <input type="hidden" value="{{ $user_recruitment->id }}" name="recruitment_user_id"
                            id="recruitment_user_id_add">
                        <input type="hidden" value="{{ $holding }}" name="holding" id="holding_add">

                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                pilihan ganda
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                esai
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                interview
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel" aria-labelledby="icon-tab-0">
                            <div class="table-responsive">
                                <table class="table" id="tabel_pg" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>nama&nbsp;pelamar</th>
                                            <th>nama&nbsp;kategori</th>
                                            <th>jawaban</th>
                                            <th>nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                            <div class="table-responsive">
                                <table class="table" id="tabel_esai" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>nama&nbsp;pelamar</th>
                                            <th>nama&nbsp;kategori</th>
                                            <th>jawaban</th>
                                            <th>nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                            <div class="table-responsive">
                                <table class="table" id="tabel_interview" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>nama&nbsp;pelamar</th>
                                            <th>nama&nbsp;kategori</th>
                                            <th>jawaban</th>
                                            <th>nilai</th>
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
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#desc_recruitment").summernote();
            // $("#show_desc_recruitment").summernote();
            $("#desc_recruitment_update").summernote();
            $('.dropdown-toggle').dropdown();
        });
    </script>
    {{-- start datatable  --}}

    <script>
        let holding = $('#holding_add').val();
        let id = $('#recruitment_user_id_add').val();
        console.log(id);

        var table = $('#tabel_pg').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-get_data_pg') }}" + '/' + id + '/' + holding,
            },
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'jawaban',
                    name: 'jawaban'
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                },
            ]
        });
        $('#icon-tab-0').on('shown.bs.tab', function(e) {
            table.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });

        var table1 = $('#tabel_esai').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-get_data_esai') }}" + '/' + id + '/' + holding,
            },
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'jawaban',
                    name: 'jawaban'
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                },
            ]
        });
        $('#icon-tab-1').on('shown.bs.tab', function(e) {
            table1.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table2 = $('#tabel_interview').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-get_data_esai') }}" + '/' + id + '/' + holding,
            },
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'jawaban',
                    name: 'jawaban'
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                },
            ]
        });
        $('#icon-tab-2').on('shown.bs.tab', function(e) {
            table2.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
    </script>
    {{-- end datatable  --}}
@endsection
