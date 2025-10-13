@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <style type="text/css">
        .my-swal {
            z-index: X;
        }

        .table-tbody {
            white-space: nowrap;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
                            <h5 class="card-title m-0 me-2">REPORT RECRUITMENT</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="table_recruitment" style="width: 100%; font-size: small;">
                            <thead class="table-primary">
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Nama Lengkap</th>
                                    <th>Posisi yang Dilamar</th>
                                    <th>CV</th>
                                    <th>Riwayat&nbsp;Detail</th>
                                    <th>Tanggal&nbsp;Berakhir</th>
                                    <th>Perkembangan&nbsp;Terakhir</th>
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
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- {{-- start datatable  --}} -->
    <script>
        let holding = window.location.pathname.split("/").pop();
        var table = $('#table_recruitment').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt_laporan_recruitment') }}" + '/' + holding,
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
                    data: 'tanggal_mulai',
                    name: 'tanggal_mulai',
                    // class: 'table-tbody'
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'posisi_yang_dilamar',
                    name: 'posisi_yang_dilamar'
                },
                {
                    data: 'cv',
                    name: 'cv'
                },
                {
                    data: 'status_detail',
                    name: 'status_detail'
                },
                {
                    data: 'tanggal_berakhir',
                    name: 'tanggal_berakhir',
                    // class: 'table-tbody'
                },
                {
                    data: 'perkembangan_terakhir',
                    name: 'perkembangan_terakhir'
                },
            ],
            order: [
                [2, 'desc']
            ]
        });
    </script>
@endsection
