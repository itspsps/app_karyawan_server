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
                        <h5 class="card-title m-0 me-2">{{ $table->Cv->nama_lengkap }} =
                            {{ $table->Jabatan->nama_jabatan }}, {{ $table->Jabatan->Bagian->nama_bagian }},
                            {{ $table->Jabatan->Bagian->Divisi->nama_divisi }},
                            {{ $table->Jabatan->Bagian->Divisi->Departemen->nama_departemen }}
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="table_riwayat" style="width: 100%; font-size: small;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Riwayat&nbsp;Waktu</th>
                                <th>Riwayat&nbsp;Status</th>
                                <th>Riwayat&nbsp;Feedback</th>
                                <th>Waktu&nbsp;Feedback</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                    <div class="row g-3 my-5">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-primary rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Total waktu recruitment</div>
                                    <h5 class="mb-0">{{$total_day}}&nbsp;Hari</h5>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- {{-- start datatable  --}} -->
<script>
    let holding = window.location.pathname.split("/").pop();
    let id = "{{$id}}";
    var table = $('#table_riwayat').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/dt_riwayat_recruitment') }}" + '/' + id + '/' + holding,
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
                data: 'waktu',
                name: 'waktu'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'feedback',
                name: 'feedback'
            },
            {
                data: 'waktu_feedback',
                name: 'waktu_feedback'
            }
        ],
        // order: [
        //     [2, 'desc']
        // ]
    });
</script>
@endsection