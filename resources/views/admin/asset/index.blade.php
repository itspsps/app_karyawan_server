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
                        <h5 class="card-title m-0 me-2">DATA ASSET</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-primary rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Laki- Laki</div>
                                    <h5 class="mb-0">{{$karyawan_laki}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-success rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Perempuan</div>
                                    <h5 class="mb-0">{{$karyawan_perempuan}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-warning rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Office</div>
                                    <h5 class="mb-0">{{$karyawan_office}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-info rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Shift</div>
                                    <h5 class="mb-0">{{$karyawan_shift}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_tambah_inventaris"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <div class="modal fade" id="modal_tambah_inventaris" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/inventaris/tambah-inventaris-proses/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Inventaris</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="kode_inventaris" name="kode_inventaris" class="form-control @error('kode_inventaris') is-invalid @enderror" placeholder="Masukkan Kode Asset" value="{{ old('kode_inventaris') }}" />
                                                <label for="kode_inventaris">Kode Inventaris</label>
                                            </div>
                                            @error('kode_inventaris')
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
                                                <input type="text" id="nama_inventaris" name="nama_inventaris" class="form-control @error('nama_inventaris') is-invalid @enderror" placeholder="Masukkan NAMA" value="{{ old('nama_inventaris') }}" />
                                                <label for="nama_inventaris">NAMA</label>
                                            </div>
                                            @error('nama_inventaris')
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
                                                <select id="kategori_invntaris" name="kategori_invntaris" class="form-control @error('kategori_invntaris') is-invalid @enderror" placeholder="Masukkan NAMA">
                                                    <option value=""> Pilih Kategori</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                                <label for="kategori_invntaris">KATEGORI</label>
                                            </div>
                                            @error('kategori_invntaris')
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
                                                <input type="text" id="type_inventaris" name="type_inventaris" class="form-control @error('type_inventaris') is-invalid @enderror" placeholder="Masukkan Jumlah" value="{{ old('type_inventaris') }}" />
                                                <label for="type_inventaris">TYPE</label>
                                            </div>
                                            @error('type_inventaris')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="{{inventaris('admin/inventariss/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_inventaris" />

                                            <div class="button-wrapper">
                                                <label for="foto_inventaris" class="btn btn-primary me-2 mb-3" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload Foto</span>
                                                    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                                    <input type="file" name="foto_inventaris" id="foto_inventaris" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                                </label>

                                                <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                            </div>
                                        </div>
                                        <br>
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
                    <div class="modal fade" id="modal_edit_inventaris" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/inventaris/edit-inventaris-proses/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Asset</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <input type="text" name="id_inventaris" id="id_inventaris" value="">
                                            <div class="form-floating form-floating-outline">
                                                <select id="kategori_invntaris_update" name="kategori_invntaris_update" class="form-control @error('kategori_invntaris_update') is-invalid @enderror" placeholder="Masukkan NAMA">
                                                    <option value=""> Pilih Kategori</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                                <label for="kategori_invntaris_update">KATEGORI</label>
                                            </div>
                                            @error('kategori_invntaris_update')
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
                                                <input type="text" id="nama_inventaris_update" name="nama_inventaris_update" class="form-control @error('nama_inventaris_update') is-invalid @enderror" placeholder="Masukkan NAMA" value="{{ old('nama_inventaris_update') }}" />
                                                <label for="nama_inventaris_update">NAMA</label>
                                            </div>
                                            @error('nama_inventaris_update')
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
                                                <input type="text" id="type_inventaris_update" name="type_inventaris_update" class="form-control @error('type_inventaris_update') is-invalid @enderror" placeholder="Masukkan Jumlah" value="{{ old('type_inventaris_update') }}" />
                                                <label for="type_inventaris_update">JUMLAH</label>
                                            </div>
                                            @error('type_inventaris_update')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="{{inventaris('admin/inventariss/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_inventaris" />

                                            <div class="button-wrapper">
                                                <label for="foto_inventaris_update" class="btn btn-primary me-2 mb-3" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload Foto</span>
                                                    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                                    <input type="file" name="foto_inventaris_update" id="foto_inventaris_update" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                                </label>

                                                <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                            </div>
                                        </div>
                                        <br>
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
                    <table class="table" id="table_inventaris" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Kategori</th>
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
    var table = $('#table_inventaris').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('inventaris-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id_inventaris",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_inventaris',
                name: 'nama_inventaris'
            },
            {
                data: 'type_inventaris',
                name: 'type_inventaris'
            },
            {
                data: 'kategori_invntaris',
                name: 'kategori_invntaris'
            },
            {
                data: 'foto_inventaris',
                name: 'foto_inventaris'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
<script>
    $(document).on("click", "#btndetail_karyawan", function() {
        let id = $(this).data('id');
        // console.log(id);
        let holding = $(this).data("holding");
        let url = "{{ url('/karyawan/detail/')}}" + '/' + id + '/' + holding;
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_kecamatan: id_kecamatan
            // },
            success: function(response) {
                // console.log(response);
                window.location.assign(url);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
    $('#foto_inventaris').change(function() {

        let reader = new FileReader();
        console.log(reader);
        reader.onload = (e) => {

            $('#template_foto_inventaris').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
    $(document).on("click", "#btn_mapping_shift", function() {
        // console.log('ok');
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "{{ url('/karyawan/shift/')}}" + '/' + id + '/' + holding;
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_kecamatan: id_kecamatan
            // },
            success: function(response) {
                // console.log(response);
                window.location.assign(url);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
    $(document).on("click", "#btn_edit", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        $('#id_inventaris').val(id);
        $('#modal_edit_inventaris'), modal('show');
    });
    $(document).on('click', '#btn_delete_karyawan', function() {
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
                    url: "{{ url('/karyawan/delete/') }}" + '/' + id + '/' + holding,
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
                        $('#table_inventaris').DataTable().ajax.reload();
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