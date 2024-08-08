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
                        <h5 class="card-title m-0 me-2">DATA MASTER BAGIAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_bagian"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <button type="button" class="btn btn-sm btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_import_bagian"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button>
                    <div class="modal fade" id="modal_tambah_bagian" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/bagian/insert/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Bagian</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_dept') is-invalid @enderror" id="nama_dept" name="nama_dept" autofocus value="{{ old('nama_dept') }}">
                                                    <option value=""> Pilih Departemen</option>
                                                    @foreach($data_dept as $data)
                                                    <option value="{{$data->id}}">{{$data->nama_departemen}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_dept">Nama Departemen</label>
                                            </div>
                                            @error('nama_dept')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_divisi') is-invalid @enderror" id="form_nama_divisi" name="nama_divisi">
                                                    <option value=""> Pilih Divisi</option>
                                                </select>
                                                <label for="form_nama_divisi">Nama Divisi</label>
                                            </div>
                                            @error('nama_divisi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('nama_bagian') is-invalid @enderror" id="nama_bagian" name="nama_bagian" autofocus value="{{ old('nama_bagian') }}">
                                                <label for="nama_bagian" class="float-left">Nama Bagian</label>
                                            </div>
                                            @error('nama_bagian')
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
                    <div class="modal fade" id="modal_import_bagian" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/bagian/ImportBagian/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Bagian</h4>
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
                    <div class="modal fade" id="modal_edit_bagian" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/bagian/update/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Bagian</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_departemen_update') is-invalid @enderror" id="nama_departemen_update" name="nama_departemen_update" autofocus value="{{ old('nama_departemen_update') }}">
                                                    <option value=""> Pilih Departemen</option>
                                                    @foreach($data_dept as $data)
                                                    <option value="{{$data->id}}">{{$data->nama_departemen}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_departemen_update">Nama Departemen</label>
                                            </div>
                                            @error('nama_departemen_update')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <input type="hidden" name="id_bagian" id="id_bagian" value="">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_divisi_update') is-invalid @enderror" id="nama_divisi_update" name="nama_divisi_update" autofocus value="{{ old('nama_divisi_update') }}">
                                                    <option value=""> Pilih Divisi</option>
                                                    @foreach($data_divisi as $data)
                                                    <option value="{{$data->id}}">{{$data->nama_divisi}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_divisi_update">Nama Divisi</label>
                                            </div>
                                            @error('nama_divisi_update')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('nama_bagian_update') is-invalid @enderror" id="nama_bagian_update" name="nama_bagian_update" autofocus value="{{ old('nama_bagian_update') }}">
                                                <label for="nama_bagian_update" class="float-left">Nama Bagian</label>
                                            </div>
                                            @error('nama_bagian_update')
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
                                                    <th>Jabatan</th>
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
                    <div class="modal fade" id="modal_lihat_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class=" modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle"> Daftar Jabatan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-lg-12">
                                        <table class="table" id="table_lihat_jabatan" style="width: 100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama&nbsp;Jabatan</th>
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
                    <table class="table" id="table_bagian" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Departemen</th>
                                <th>Divisi</th>
                                <th>Nama Bagian</th>
                                <th>Jumlah&nbsp;Jabatan</th>
                                <th>Jumlah&nbsp;Karyawan</th>
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
    var table = $('#table_bagian').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('bagian-datatable') }}" + '/' + holding,
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
                data: 'nama_divisi',
                name: 'nama_divisi'
            },
            {
                data: 'nama_bagian',
                name: 'nama_bagian'
            },
            {
                data: 'jumlah_jabatan',
                name: 'jumlah_jabatan'
            },
            {
                data: 'jumlah_karyawan',
                name: 'jumlah_karyawan'
            },
            {
                data: 'option',
                name: 'option'
            },
        ],
        order: [
            [1, 'asc']
        ]
    });
</script>
<script>
    $('#nama_dept').on('change', function() {
        let id_dept = $(this).val();
        let url = "{{url('/bagian/get_divisi')}}" + "/" + id_dept;
        // console.log(id_dept);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_dept: id_dept
            // },
            success: function(response) {
                // console.log(response);
                $('#form_nama_divisi').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#nama_departemen_update').on('change', function() {
        let id_dept = $(this).val();
        let url = "{{url('/bagian/get_divisi')}}" + "/" + id_dept;
        // console.log(id_dept);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_dept: id_dept
            // },
            success: function(response) {
                // console.log(response);
                $('#nama_divisi_update').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $(document).on("click", "#btn_edit_bagian", function() {
        let id = $(this).data('id');
        let dept = $(this).data("dept");
        let divisi = $(this).data("divisi");
        let bagian = $(this).data("bagian");
        let holding = $(this).data("holding");
        // console.log(divisi);
        $('#id_bagian').val(id);
        $('#nama_departemen_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == dept
        }).prop('selected', true)
        $('#nama_divisi_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == divisi
        }).prop('selected', true)
        $('#nama_bagian_update').val(bagian);
        $('#modal_edit_bagian').modal('show');

    });
    $(document).on('click', '#btn_delete_bagian', function() {
        var id = $(this).data('id');
        let divisi = $(this).data("divisi");
        let holding = $(this).data("holding");
        // console.log(id);
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
                    url: "{{ url('/bagian/delete/') }}" + '/' + id + '/' + holding,
                    type: "GET",
                    data: {
                        id: id,
                        divisi: divisi,
                        holding: holding,
                    },
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        console.log(data.status);
                        if (data.status == 1) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data anda berhasil di hapus.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_bagian').DataTable().ajax.reload();
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Bagian Masih Terhubung Karyawan',
                                icon: 'error',
                                timer: 1500
                            })
                            $('#table_bagian').DataTable().ajax.reload();
                        } else if (data.status == 0) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Bagian Masih Terhubung Jabatan',
                                icon: 'error',
                                timer: 1500
                            })
                            $('#table_bagian').DataTable().ajax.reload();
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
    $(document).on('click', '#btn_lihat_jabatan', function() {

        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "{{ url('jabatan/jabatan-datatable') }}" + '/' + id + '/' + holding;
        // console.log(url);
        var table1 = $('#table_lihat_jabatan').DataTable({
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
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
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
        $('#modal_lihat_jabatan').modal('show');
        $('#modal_lihat_jabatan').on('hidden.bs.modal', function(e) {
            table1.destroy();
            $('#table_bagian').DataTable().ajax.reload();
        })
    });
    $(document).on('click', '#btn_lihat_karyawan', function() {

        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "{{ url('jabatan/karyawanjabatan-datatable') }}" + '/' + id + '/' + holding;
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
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
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
            $('#table_bagian').DataTable().ajax.reload();
        })
    });
</script>
@endsection