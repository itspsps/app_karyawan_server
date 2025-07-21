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
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                Pilihan Ganda
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Esai
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                Kategori Ujian
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel" aria-labelledby="icon-tab-0">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">SOAL UJIAN PILIHAN GANDA</h5>
                            </div>
                            <a href="{{ url('/pg-data-ujian/ujian_pg/' . $holding) }}" type="button"
                                class="btn btn-sm btn-primary waves-effect waves-light my-3"><i
                                    class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</a>
                            <table class="table" id="table_ujian" style="width: 100%;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Direktur</th>
                                        <th>Head</th>
                                        <th>Manager / Regional Sales Manager</th>
                                        <th>Junior Sales Manager / Area Sales Manager</th>
                                        <th>Supervisor</th>
                                        <th>Koordinator</th>
                                        <th>Admin, Operator, Drafter, Staff, Sales, Sopir</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">SOAL UJIAN ESAI</h5>
                            </div>
                            <a href="{{ url('/pg-data-ujian/ujian_pg_esai/' . $holding) }}" type="button"
                                class="btn btn-sm btn-primary waves-effect waves-light my-3"><i
                                    class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</a>
                            <table class="table" id="table_esai" style="width: 100%;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Direktur</th>
                                        <th>Head</th>
                                        <th>Manager / Regional Sales Manager</th>
                                        <th>Junior Sales Manager / Area Sales Manager</th>
                                        <th>Supervisor</th>
                                        <th>Koordinator</th>
                                        <th>Admin, Operator, Drafter, Staff, Sales, Sopir</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                            <div class="d-">
                                <h5 class="card-title m-0 me-2">KATEGORI UJIAN</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light my-3"
                                id="btn_modal_kategori"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                            <table class="table" id="tabel_ujian_kategori" style="width: 100%;">
                                <thead class="table-primary">
                                    <tr>
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
                                <a href="{{ url('/pg-data-ujian/ujian_pg/' . $holding) }}" class="btn btn-primary">Pilihan
                                    Ganda</a>
                                <a href="{{ url('/pg-data-ujian/ujian_essay/' . $holding) }}"
                                    class="btn btn-primary ml-2">Essay</a>
                            </div>
                        </div>
                    </div>
                    <button type="button" style="float: right" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- modal tambah kategori --}}
    <div class="modal fade" id="modal_tambah_kategori" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">TAMBAH KATEGORI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="nama_kategori_add" name="nama_kategori"
                                required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_kategori">Masukkan
                        Kategori</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal tambah kategori --}}
    {{-- modal update kategori --}}
    <div class="modal fade" id="modal_update_kategori" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">UPDATE KATEGORI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="nama_kategori_update" name="nama_kategori"
                                required>
                            <input type="hidden" class="form-control" id="id_update" name="id">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_kategori">Masukkan
                        Kategori</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal update kategori --}}
    {!! session('pesan') !!}
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


    <script>
        $("input[type=text]").keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        let holding = window.location.pathname.split("/").pop();
        var table1 = $('#table_esai').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt-data-list-esai') }}" + '/' + holding,
            },
            columns: [{
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'nol',
                    name: 'nol'
                },
                {
                    data: 'satu',
                    name: 'satu'
                },
                {
                    data: 'dua',
                    name: 'dua'
                },
                {
                    data: 'tiga',
                    name: 'tiga'
                },
                {
                    data: 'empat',
                    name: 'empat'
                },
                {
                    data: 'lima',
                    name: 'lima'
                },
                {
                    data: 'enam',
                    name: 'enam'
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-1').on('shown.bs.tab', function(e) {
            table1.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
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
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'nol',
                    name: 'nol'
                },
                {
                    data: 'satu',
                    name: 'satu'
                },
                {
                    data: 'dua',
                    name: 'dua'
                },
                {
                    data: 'tiga',
                    name: 'tiga'
                },
                {
                    data: 'empat',
                    name: 'empat'
                },
                {
                    data: 'lima',
                    name: 'lima'
                },
                {
                    data: 'enam',
                    name: 'enam'
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-0').on('shown.bs.tab', function(e) {
            table.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table2 = $('#tabel_ujian_kategori').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt-data-list-ujian_kategori') }}" + '/' + holding,
            },
            columns: [{
                    data: 'nama_kategori',
                    name: 'nama_kategori',
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
        $('#icon-tab-2').on('shown.bs.tab', function(e) {
            table2.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        $('#btn_modal_kategori').click(function() {
            $('#modal_tambah_kategori').modal('show');
        });
        $(document).on('click', '#btn_edit_ujian_kategori', function() {
            var id = $(this).data('id');
            $('#id_update').val(id);
            $('#modal_update_kategori').modal('show');

        });
        $('#btn_save_kategori').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            //ambil data dari form
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('nama_kategori', $('#nama_kategori_add').val());

            // post
            $.ajax({
                type: "POST",

                url: "{{ url('/ujian_kategori_post') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong!');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#modal_tambah_kategori').modal('hide');
                        $('#nama_kategori_add').val('');
                        $('#tabel_ujian_kategori').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_tambah_kategori').modal('hide');

                        $('#tabel_ujian_kategori').DataTable().ajax.reload();


                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 4500
                        })

                    }
                }
            });
        });
        $(document).on('click', '#btn_delete_ujian_kategori', function() {
            // $('#modal_delete_riwayat').modal('show');
            var id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi',
                icon: 'warning',
                text: "Apakah benar-benar ingin menghapus data ini?",
                showCancelButton: true,
                inputValue: 0,
                confirmButtonText: 'Yes',
            }).then(function(result) {
                if (result.value) {
                    // console.log(id);
                    Swal.fire({
                        title: 'Harap Tuggu Sebentar!',
                        html: 'Proses Menghapus Data...', // add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                            $.ajax({
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    id: id,
                                },
                                url: "{{ url('/delete_ujian_kategori') }}",
                                type: "POST",
                                dataType: 'json',
                                success: function(data) {
                                    if (data.code == 200) {
                                        $('#tabel_ujian_kategori').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'success',
                                            text: 'Data Berhasil dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    } else {
                                        $('#tabel_ujian_kategori').DataTable().ajax
                                            .reload();
                                        Swal.fire({
                                            title: 'error',
                                            text: 'Data gagal dihapus',
                                            icon: 'success',
                                            timer: 1500
                                        })
                                    }
                                },
                                error: function(data) {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Data Gagal dihapus',
                                        icon: 'error',
                                        timer: 1500
                                    })
                                }
                            });
                        },
                    });

                } else {
                    Swal.fire({
                        title: 'Gagal !',
                        text: 'Data gagal dihapus',
                        icon: 'warning',
                        timer: 1500
                    })
                }

            });
        });
        $(document).on('click', '#btn_edit_ujian_kategori', function() {
            var id = $(this).data('id');
            var nama_kategori = $(this).data('nama_kategori');
            console.log(nama_kategori);
            $('#id_update').val(id);
            $('#nama_kategori_update').val(nama_kategori);
            $('#modal_update_kategori').modal('show');

        });
        $('#btn_update_kategori').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('nama_kategori', $('#nama_kategori_update').val());
            formData.append('id', $('#id_update').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/ujian_kategori_update') }}",
                data: formData,
                contentType: false,
                processData: false,
                error: function() {
                    alert('Something is wrong');
                    // console.log(formData);
                },
                success: function(data) {
                    if (data.code == 200) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: data.message,
                            icon: 'success',
                            timer: 5000
                        })
                        //mengosongkan modal dan menyembunyikannya
                        $('#nama_kategori_update').val('');
                        $('#modal_update_kategori').modal('hide');
                        $('#tabel_ujian_kategori').DataTable().ajax.reload();
                    } else if (data.code == 400) {
                        let errors = data.errors;
                        // console.log(errors);
                        let errorMessages = '';

                        Object.keys(errors).forEach(function(key) {
                            errors[key].forEach(function(message) {
                                errorMessages += `• ${message}\n`;
                            });
                        });
                        Swal.fire({
                            // title: data.message,
                            text: errorMessages,
                            icon: 'warning',
                            timer: 4500
                        })
                        $('#modal_update_kategori').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: data.error,
                            icon: 'error',
                            timer: 10000
                        })

                    }
                }

            });
        });
    </script>
@endsection
