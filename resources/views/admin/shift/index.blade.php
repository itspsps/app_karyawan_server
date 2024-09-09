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
                        <h5 class="card-title m-0 me-2">DATA MASTER SHIFT</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_shift"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <div class="modal fade" id="modal_tambah_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/shift/store/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="nama_shift" name="nama_shift" class="form-control @error('nama_shift') is-invalid @enderror" placeholder="Masukkan Nama Shift" value="{{ old('nama_shift') }}" />
                                                <label for="nama_shift">Nama Shift</label>
                                            </div>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_masuk" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror" placeholder="Masukkan jam_masuk" value="{{ old('jam_masuk') }}" />
                                                <label for="jam_masuk">Jam Masuk</label>
                                            </div>
                                            @error('jam_masuk')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_keluar" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror" placeholder="Masukkan jam_keluar" value="{{ old('jam_keluar') }}" />
                                                <label for="jam_keluar">Jam Masuk</label>
                                            </div>
                                            @error('jam_keluar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                    <!-- modal edit -->
                    <div class="modal fade" id="modal_edit_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/shift/update/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <input type="hidden" name="id_shift" id="id_shift" value="">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="nama_shift_update" name="nama_shift_update" class="form-control @error('nama_shift_update') is-invalid @enderror" placeholder="Masukkan Nama Shift" value="" />
                                                <label for="nama_shift_update">Nama Shift</label>
                                            </div>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_masuk_update" name="jam_masuk_update" class="form-control @error('jam_masuk_update') is-invalid @enderror" placeholder="Masukkan jam_masuk_update" value="" />
                                                <label for="jam_masuk_update">Jam Masuk</label>
                                            </div>
                                            @error('jam_masuk_update')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_keluar_update" name="jam_keluar_update" class="form-control @error('jam_keluar_update') is-invalid @enderror" placeholder="Masukkan jam_keluar_update" value="" />
                                                <label for="jam_keluar_update">Jam Keluar</label>
                                            </div>
                                            @error('jam_keluar_update')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                    <table class="table" id="table_shift" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama&nbsp;Shift</th>
                                <th>Jam&nbsp;Masuk</th>
                                <th>Jam&nbsp;Keluar</th>
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
    var table = $('#table_shift').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('shift-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_shift',
                name: 'nama_shift'
            },
            {
                data: 'jam_masuk',
                name: 'jam_masuk'
            },
            {
                data: 'jam_keluar',
                name: 'jam_keluar'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
<script>
    $(document).on("click", "#btn_edit_shift", function() {
        let id = $(this).data('id');
        let shift = $(this).data("shift");
        let jammasuk = $(this).data("jammasuk");
        let jamkeluar = $(this).data("jamkeluar");
        let holding = $(this).data("holding");
        // console.log(jamkeluar);
        $('#id_shift').val(id);
        $('#nama_shift_update').val(shift);
        $('#jam_masuk_update').val(jammasuk);
        $('#jam_keluar_update').val(jamkeluar);
        $('#modal_edit_shift').modal('show');

    });
    $(document).on('click', '#btn_delete_shift', function() {
        var id = $(this).data('id');
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
                    url: "{{ url('/shift/delete/') }}" + '/' + id + '/' + holding,
                    type: "GET",
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Data anda berhasil di hapus.',
                            icon: 'success',
                            timer: 1500
                        })
                        $('#table_shift').DataTable().ajax.reload();
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
</script>
@endsection