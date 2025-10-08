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
                        <h5 class="card-title m-0 me-2">DATA INTERVIEW ({{ $user_recruitment->Cv->nama_lengkap }})
                        </h5>
                        <div class="px-3">
                            <a href="{{ url('/pg/pelamar-detail_pdf/' . $user_recruitment->id) }}" type="button"
                                class="btn btn-sm btn-info" target="_blank">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Lihat CV PDF
                            </a>
                            <a href="{{ url('/pg/pelamar-nilai_pdf/' . $user_recruitment->id) }}" type="button"
                                class="btn btn-sm btn-info" target="_blank">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Lihat Nilai PDF
                            </a>
                        </div>
                        <input type="hidden" value="{{ $user_recruitment->id }}" name="recruitment_user_id"
                            id="recruitment_user_id_add">
                        <input type="hidden" value="{{ $holding->holding_code }}" name="holding" id="holding_add">

                    </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                            role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                            pilihan ganda
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1" role="tab"
                            aria-controls="icon-tabpanel-0" aria-selected="true">
                            esai
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2" role="tab"
                            aria-controls="icon-tabpanel-0" aria-selected="true">
                            interview
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="tab-content">
                    <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel" aria-labelledby="icon-tab-0">
                        <div class="table-responsive">
                            <table class="table" id="tabel_pg" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>nama&nbsp;pelamar</th>
                                        <th>nama&nbsp;kategori</th>
                                        <th>jawaban</th>
                                        <th>nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                        <div class="table-responsive">
                            <table class="table" id="tabel_esai" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>nama&nbsp;pelamar</th>
                                        <th>nama&nbsp;kategori</th>
                                        <th>jawaban</th>
                                        <th>nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-3"
                            id="btn_tambah_catatan"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah
                            Catatan</button>
                        <div class="table-responsive">
                            <table class="table" id="tabel_interview" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>penilaian</th>
                                        <th>deskripsi</th>
                                        <th>isi nilai</th>
                                        <th>nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="tabel_catatan" style="width: 100%; font-size: small;">
                                <thead class="table-primary" style="width: 100%; font-size: small;">
                                    <tr>
                                        <th>catatan</th>
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
    <!--/ Transactions -->
    <!--/ Data Tables -->
    {{-- modal update nilai --}}
    <div class="modal fade" id="modal_update_nilai" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">INPUT NILAI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="id" id="id_update">
                        <label for="">Range 1-10</label>
                        <input type="number" class="form-control" id="nilai_update" name="nilai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_nilai">Masukkan
                        Nilai</button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal update nilai end --}}
    {{-- modal update nilai --}}
    <div class="modal fade" id="modal_catatan" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">CATATAN CALON KARYAWAN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="hidden" name="recruitment_user_id" id="recruitment_user_id_update">
                            <textarea class="form-control" id="catatan_update" name="catatan"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_update_catatan">Masukkan
                        Nilai</button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal update nilai end --}}

</div>
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
{{-- start datatable  --}}

<script>
    let holding = $('#holding_add').val();
    let id = $('#recruitment_user_id_add').val();
    // console.log(id);

    var table = $('#tabel_pg').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/dt/data-get_data_pg') }}" + '/' + id + '/' + holding,
        },
        columns: [{
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'kategori',
                name: 'kategori'
            },
            {
                data: 'jawaban',
                name: 'jawaban'
            },
            {
                data: 'nilai',
                name: 'nilai'
            },
        ]
    });
    $('#icon-tab-0').on('shown.bs.tab', function(e) {
        table.columns.adjust().draw().responsive.recalc();
        // table.draw();
    });

    var table1 = $('#tabel_esai').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/dt/data-get_data_esai') }}" + '/' + id + '/' + holding,
        },
        columns: [{
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'kategori',
                name: 'kategori'
            },
            {
                data: 'jawaban',
                name: 'jawaban'
            },
            {
                data: 'nilai',
                name: 'nilai'
            },
        ]
    });
    $('#icon-tab-1').on('shown.bs.tab', function(e) {
        table1.columns.adjust().draw().responsive.recalc();
        // table.draw();
    });
    var table2 = $('#tabel_interview').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/dt/data-dt_interview_user') }}" + '/' + id + '/' + holding,
        },
        columns: [{
                data: 'parameter',
                name: 'parameter'
            },
            {
                data: 'deskripsi',
                name: 'deskripsi'
            },
            {
                data: 'isi_nilai',
                name: 'isi_nilai'
            },
            {
                data: 'nilai',
                name: 'nilai'
            },
        ]
    });

    // $('#icon-tab-2').on('shown.bs.tab', function(e) {
    //     table2.columns.adjust().draw().responsive.recalc();
    //     // table.draw();
    // });
    var table3 = $('#tabel_catatan').DataTable({
        scrollY: true,
        scrollX: true,
        processing: true,
        serverSide: true,
        paging: false, // Hilangkan pagination
        searching: false, // Hilangkan kolom pencarian
        info: false, // Hilangkan info jumlah data
        ordering: false,
        ajax: {
            url: "{{ url('/dt/dt_catatan') }}" + '/' + id,
        },
        columns: [{
            data: 'catatan',
            name: 'catatan'
        }]
    });
    $('#icon-tab-2').on('shown.bs.tab', function(e) {
        setTimeout(function() {
            table2.columns.adjust().draw().responsive.recalc();
            table3.columns.adjust().draw().responsive.recalc();
        })
    });
    $(document).on('click', '#btn_isi_nilai', function() {
        // console.log('asooy');
        var id = $(this).data('id');
        var nilai = $(this).data('nilai');
        $('#id_update').val(id);
        $('#nilai_update').val(nilai);
        $('#modal_update_nilai').modal('show');

    });
    $('#btn_update_nilai').on('click', function(e) {
        e.preventDefault();
        var formData = new FormData();

        //ambil data dari form
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('id', $('#id_update').val());
        formData.append('nilai', $('#nilai_update').val());

        // post
        $.ajax({
            type: "POST",

            url: "{{ url('/dt/interview_user_post') }}",
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
                    $('#modal_update_nilai').modal('hide');
                    $('#id_update').val('');
                    $('#nilai_update').val('');
                    $('#tabel_interview').DataTable().ajax.reload();
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
                    $('#modal_update_nilai').modal('hide');

                    $('#tabel_interview').DataTable().ajax.reload();


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
    $(document).on('click', '#btn_tambah_catatan', function() {
        let id = $('#recruitment_user_id_add').val();

        $.ajax({
            type: "GET",
            url: "{{ url('/dt/get_catatan_interview') }}" + '/' + id,
            error: function(error) {
                Swal.fire({
                    title: 'error',
                    text: error.responseJSON.message,
                    icon: 'error',
                    timer: 4500
                })

            },
            success: function(data) {
                // console.log(data);
                if (data.code == 200) {
                    $('#catatan_update').val(data.data.catatan);
                    $('#recruitment_user_id_update').val(data.data.recruitment_user_id);
                    $('#modal_catatan').modal('show');
                }
            }
        });

    });
    $('#btn_update_catatan').on('click', function(e) {
        e.preventDefault();
        var formData = new FormData();

        //ambil data dari form
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('recruitment_user_id', $('#recruitment_user_id_update').val());
        formData.append('catatan', $('#catatan_update').val());

        // post
        $.ajax({
            type: "POST",

            url: "{{ url('/dt/update_catatan') }}",
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
                    $('#modal_catatan').modal('hide');
                    $('#recruitment_user_id').val('');
                    $('#catatan').val('');
                    $('#tabel_catatan').DataTable().ajax.reload();
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
                    $('#modal_catatan').modal('hide');

                    $('#tabel_interview').DataTable().ajax.reload();


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
</script>
{{-- end datatable  --}}
@endsection