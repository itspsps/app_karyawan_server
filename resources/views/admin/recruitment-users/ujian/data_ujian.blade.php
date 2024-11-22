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
                        <h5 class="card-title m-0 me-2">DATA SOAL UJIAN</h5>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_tambah_recruitment"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>

                </div>

                <table class="table" id="table_ujian" style="width: 100%;">
                    <thead class="table-primary">
                        <tr>
                            <th>Namaa</th>
                            <th>Mapel</th>
                            <th>Kelas</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_tambah_recruitment" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-sm">
        <form class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="backDropModalTitle">Tambah Ujian</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col mb-2">
                        <div class="modal-body text-center">
                            <a href="{{ url('/pg-data-ujian/ujian_pg/'.$holding) }}" class="btn btn-primary">Pilihan Ganda</a>
                            <a href="{{ url('/pg-data-ujian/ujian_essay/'.$holding) }}" class="btn btn-primary ml-2">Essay</a>
                        </div>
                    </div>
                </div>
                <button type="button" style="float: right" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </form>
    </div>
</div>
{!! session('pesan') !!}
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


<script>
    let holding = window.location.pathname.split("/").pop();
    var table = $('#table_ujian').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/dt-data-list-ujian') }}" + '/' + holding,
        },
        columns: [{
                data: 'nama',
                name: 'nama',
            },
            {
                data: 'nama_mapel',
                name: 'nama_mapel'
            },
            {
                data: 'nama_kelas',
                name: 'nama_kelas'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
@endsection