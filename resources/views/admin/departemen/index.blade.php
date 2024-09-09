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
                        <h5 class="card-title m-0 me-2">DATA MASTER DEPARTEMEN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_shift"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <button type="button" class="btn btn-sm btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_import_departemen"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button>
                    <div class="modal fade" id="modal_tambah_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/departemen/insert/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Departemen</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('nama_departemen') is-invalid @enderror" id="nama_departemen" name="nama_departemen" autofocus value="{{ old('nama_departemen') }}">
                                                <label for="nama_departemen" class="float-left">Nama Departemen</label>
                                            </div>
                                            @error('nama_departemen')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
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
                    <div class="modal fade" id="modal_import_departemen" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/departemen/ImportDepartemen/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Departemen</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="file" id="file_excel" name="file_excel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" placeholder="Masukkan File" />
                                                <label for="file_excel">File Excel</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-2">
                                        <a href="{{asset('')}}" type="button" download="" class="btn btn-sm btn-primary"> Download Format Excel</a>
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
                    <!-- modal edit -->
                    <div class="modal fade" id="modal_edit_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/departemen/update/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="hidden" name="id_departemen" id="id_departemen" value="">
                                                <input type="text" class="form-control @error('nama_departemen_update') is-invalid @enderror" id="nama_departemen_update" name="nama_departemen_update" autofocus value="{{ old('nama_departemen_update') }}">
                                                <label for="nama_departemen_update" class="float-left">Nama Departemen</label>
                                            </div>
                                            @error('nama_departemen_update')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
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
                    <!-- modal lihat karyawan -->
                    <div class="modal fade" id="modal_lihat_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class=" modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle"> Daftar Karyawan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-lg-12">
                                        <table class="table" id="table_lihat_karyawan" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama&nbsp;Karyawan</th>
                                                    <th>Divisi</th>
                                                    <th>Bagian</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal lihat divisi -->
                    <div class="modal fade" id="modal_lihat_divisi" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class=" modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle"> Daftar Divisi</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-lg-12">
                                        <table class="table" id="table_lihat_divisi" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama&nbsp;Divisi</th>
                                                    <th>Jumlah&nbsp;Karyawan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table" id="table_departemen" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama Departemen</th>
                                <th>Jumlah&nbsp;Divisi</th>
                                <th>Total&nbsp;Karyawan</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    let holding = window.location.pathname.split("/").pop();
    var table = $('#table_departemen').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('departemen-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_departemen',
                name: 'nama_departemen'
            },
            {
                data: 'jumlah_divisi',
                name: 'jumlah_divisi'
            },
            {
                data: 'jumlah_karyawan',
                name: 'jumlah_karyawan'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
<script>
    $(document).on("click", "#btn_edit_dept", function() {
        let id = $(this).data('id');
        let dept = $(this).data("dept");
        let holding = $(this).data("holding");
        // console.log(jamkeluar);
        $('#id_departemen').val(id);
        $('#nama_departemen_update').val(dept);
        $('#modal_edit_shift').modal('show');

    });
    $(document).on('click', '#btn_delete_dept', function() {
        var id = $(this).data('id');
        var usercount = $(this).data('usercount');
        let holding = $(this).data("holding");
        console.log(id);
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu tidak dapat mengembalikan data ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{ url('/departemen/delete/') }}" + '/' + id + '/' + holding,
                    type: "GET",
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data anda berhasil di hapus.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_departemen').DataTable().ajax.reload();
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Departemen Masih Terhubung Karyawan',
                                icon: 'error',
                                timer: 1500
                            })
                            $('#table_departemen').DataTable().ajax.reload();
                        } else if (data.status == 0) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Departemen Masih Terhubung Divisi',
                                icon: 'error',
                                timer: 1500
                            })
                            $('#table_departemen').DataTable().ajax.reload();
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Your data is safe :',
                    icon: 'error',
                    timer: 1500
                })
            }
        });

    });
    $(document).on('click', '#btn_lihat_divisi', function() {

        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "{{ url('departemen/divisi-datatable') }}" + '/' + id + '/' + holding;
        // console.log(url);
        var table1 = $('#table_lihat_divisi').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            retrieve: true,
            ajax: {
                url: url,
            },
            columns: [{
                    data: "id",

                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'jumlah_karyawan',
                    name: 'jumlah_karyawan'
                },
            ],
            order: [
                [1, 'asc'],
                [2, 'asc'],
            ],
        });
        $('#modal_lihat_divisi').modal('show');
        $('#modal_lihat_divisi').on('hidden.bs.modal', function(e) {
            table1.destroy();
            $('#table_departemen').DataTable().ajax.reload();
        })
    });
    $(document).on('click', '#btn_lihat_karyawan', function() {

        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "{{ url('departemen/karyawandepartemen-datatable') }}" + '/' + id + '/' + holding;
        console.log(id);
        var table1 = $('#table_lihat_karyawan').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            retrieve: true,
            ajax: {
                url: url,
            },
            columns: [{
                    data: "id",

                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
            ],
            order: [
                [1, 'asc'],
                [2, 'asc'],
            ],
        });
        $('#modal_lihat_karyawan').modal('show');
        $('#modal_lihat_karyawan').on('hidden.bs.modal', function(e) {
            table1.destroy();
            $('#table_departemen').DataTable().ajax.reload();
        })
    });
</script>
@endsection