@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<style type="text/css">
    .my-swal {
        z-index: X;
    }

    .swal2-container {
        z-index: 9999 !important;

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
                        <h5 class="card-title m-0 me-2">DATA MASTER FINGER</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_finger"><i class="menu-icon tf-icons mdi mdi-plus"></i> Tambah Mesin Finger</button>
                    <div class="modal fade" id="modal_tambah_finger" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form id="form_add_fingermachine" method="post" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Mesin Finger</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="nama_mesin" name="nama_mesin" class="form-control @error('nama_mesin') is-invalid @enderror" placeholder="Masukkan Nama Mesin" value="{{ old('nama_mesin') }}" />
                                                <label for="nama_mesin">Nama Mesin</label>
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
                                                <input type="text" id="ip_mesin" name="ip_mesin" class="form-control @error('ip_mesin') is-invalid @enderror" placeholder="Masukkan IP Mesin" value="{{ old('ip_mesin') }}" />
                                                <label for="ip_mesin">IP Mesin</label>
                                            </div>
                                            @error('ip_mesin')
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
                                                <input type="number" id="port_mesin" name="port_mesin" class="form-control @error('port_mesin') is-invalid @enderror" placeholder="Masukkan port_mesin" value="{{ old('port_mesin') }}" />
                                                <label for="port_mesin">Jam Kerja</label>
                                            </div>
                                            @error('port_mesin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" id="btn_save_add_finger" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- modal edit -->
                    <div class="modal fade" id="modal_edit_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/finger/update/'.$holding->id) }}@else {{ url('/finger/update/'.$holding->id) }} @endif" class="modal-content" enctype="multipart/form-data">
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
                                                <input type="time" id="jam_masuk_update" name="jam_masuk_update" class="form-control @error('jam_masuk_update') is-invalid @enderror" placeholder="Masukkan Jam Masuk" value="" />
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
                                                <input type="time" id="jam_kerja_update" name="jam_kerja_update" class="form-control @error('jam_kerja_update') is-invalid @enderror" placeholder="Masukkan Jam Kerja" value="{{ old('jam_kerja_update') }}" />
                                                <label for="jam_kerja_update">Jam Kerja</label>
                                            </div>
                                            @error('jam_kerja_update')
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
                    <table class="table" id="table_finger" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama&nbsp;Finger</th>
                                <th>IP&nbsp;Mesin</th>
                                <th>Port&nbsp;Mesin</th>
                                <th>Status</th>
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
    var table = $('#table_finger').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/finger-datatable') }}@else {{ url('finger-datatable') }}@endif" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'Name',
                name: 'Name'
            },
            {
                data: 'Ip',
                name: 'Ip'
            },
            {
                data: 'Port',
                name: 'Port'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });

    $(document).on("click", "#btn_save_add_finger", function() {
        var nama_mesin = $('#nama_mesin').val();
        var ip_mesin = $('ip_mesin').val();
        var port_mesin = $('port_mesin').val();
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu tidak dapat mengembalikan data ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/finger/store/'.$holding->holding_code) }}@else {{ url('/finger/store/'.$holding->holding_code) }} @endif",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: data.message,
                            icon: 'success',
                            timer: 4500
                        });
                        $('#form_add_fingermachine')[0].reset();

                        // reload datatable tanpa refresh page
                        table.ajax.reload(null, false);
                        $('#modal_tambah_finger').modal('hide');
                    },
                    error: function(err) {
                        if (err.status === 422) {
                            let errors = err.responseJSON.errors;
                            let message = "";
                            Object.keys(errors).forEach(function(key) {
                                message += errors[key][0] + "\n";
                            });
                            alert("Validasi gagal:\n" + message);
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Your data is safe :',
                    icon: 'error',
                    timer: 3500
                })
            }
        });
    });
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
        console.log(id, holding);
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
                    url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/shift/delete/') }} @else {{ url('/shift/delete/') }}@endif" + '/' + id + '/' + holding,
                    type: "GET",
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        if (data.code === 200) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success',
                                timer: 4500
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                timer: 4500
                            });
                        }
                        $('#table_finger').DataTable().ajax.reload();
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