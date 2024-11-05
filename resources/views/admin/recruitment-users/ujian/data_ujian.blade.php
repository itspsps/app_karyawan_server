@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<style type="text/css">
    .my-swal {
        z-index: X;
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
                        <h5 class="card-title m-0 me-2">DATA UJIAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <hr class="my-5">
                    <hr class="my-5"> -->
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_tambah_recruitment"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <!-- <button type="button" class="btn btn-sm btn-success waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_import_inventaris"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button> -->
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
                    <table class="table" id="table_recruitment" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Mapel</th>
                                <th>Kelas</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ujian as $u)
                                <tr>
                                    <td>{{ $u->nama }}</td>
                                    <td>{{ $u->mapel->nama_mapel }}</td>
                                    <td>{{ $u->kelas->nama_kelas }}</td>
                                    <td>
                                        @if ($u->jenis == 0)
                                            <a href="{{ url('/ujian/ujian-pg-show/' . $u->kode.'/'.$holding) }}" class="btn btn-primary btn-sm">
                                                <span class="mdi mdi-eye-circle-outline"></span>
                                            </a>
                                        @endif

                                        @if ($u->jenis == 1)
                                            <a href="{{ url('/guru/ujian_essay/' . $u->kode) }}" class="btn btn-primary btn-sm">
                                                <span class="mdi mdi-eye-circle-outline"></span>
                                            </a>
                                        @endif
                                        <form action="{{ url('/ujian/ujian-pg-destroy/' . $u->kode . '/'. $holding) }}" method="post" class="d-inline" id="formHapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-hapus">
                                                <span class="mdi mdi-trash-can-outline"></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Transactions -->
        <!--/ Data Tables -->
    </div>
</div>
{!! session('pesan') !!}
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

@endsection
