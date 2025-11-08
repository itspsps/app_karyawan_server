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
                            <h5 class="card-title m-0 me-2">DATA Ujian pelamar</h5>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="icon-tab-0" data-bs-toggle="tab" href="#icon-tabpanel-0"
                                role="tab" aria-controls="icon-tabpanel-0" aria-selected="true">
                                interview hari ini
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-1" data-bs-toggle="tab" href="#icon-tabpanel-1" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                hadir Keseluruhan
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-2" data-bs-toggle="tab" href="#icon-tabpanel-2" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                tidak hadir Keseluruhan
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="icon-tab-3" data-bs-toggle="tab" href="#icon-tabpanel-3" role="tab"
                                aria-controls="icon-tabpanel-0" aria-selected="true">
                                interview mendatang
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tab-content">
                        <div class="tab-pane active show" id="icon-tabpanel-0" role="tabpanel" aria-labelledby="icon-tab-0">
                            <table class="table" id="table_recruitment_interview" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Tanggal&nbsp;Wawancara</th>
                                        <th>Waktu&nbsp;Wawancara</th>
                                        <th>Nama&nbsp;Lengkap</th>
                                        <th>Presensi&nbsp;Kehadiran</th>
                                        <th>Kedisiplinan</th>
                                        <th>Ujian</th>
                                        <th>Nama&nbsp;Bagian</th>
                                        <th>Nama&nbsp;Divisi</th>
                                        <th>Nama&nbsp;Departemen</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-1" role="tabpanel" aria-labelledby="icon-tab-1">
                            <div class="table-responsive">
                                <table class="table" id="table_recruitment_interview1"
                                    style="width: 100%; font-size: small;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Tanggal&nbsp;Wawancara</th>
                                            <th>Waktu&nbsp;Wawancara</th>
                                            <th>Nama&nbsp;Lengkap</th>
                                            <th>Presensi&nbsp;Kehadiran</th>
                                            <th>Kedisiplinan</th>
                                            <th>Ujian</th>
                                            <th>Nama&nbsp;Bagian</th>
                                            <th>Nama&nbsp;Divisi</th>
                                            <th>Nama&nbsp;Departemen</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-2" role="tabpanel" aria-labelledby="icon-tab-2">
                            <div class="table-responsive">
                                <table class="table" id="table_recruitment_interview2"
                                    style="width: 100%; font-size: small;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Tanggal&nbsp;Wawancara</th>
                                            <th>Presensi&nbsp;Kehadiran</th>
                                            <th>Nama&nbsp;Lengkap</th>
                                            <th>Nama&nbsp;Bagian</th>
                                            <th>Nama&nbsp;Divisi</th>
                                            <th>Nama&nbsp;Departemen</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="icon-tabpanel-3" role="tabpanel" aria-labelledby="icon-tab-3">
                            <div class="table-responsive">
                                <table class="table" id="table_recruitment_interview3"
                                    style="width: 100%; font-size: small;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Tanggal&nbsp;Wawancara</th>
                                            <th>Presensi&nbsp;Kehadiran</th>
                                            <th>Nama&nbsp;Lengkap</th>
                                            <th>Nama&nbsp;Bagian</th>
                                            <th>Nama&nbsp;Divisi</th>
                                            <th>Nama&nbsp;Departemen</th>
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
    </div>
    <div class="modal fade" id="modal_presensi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">PRESENSI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_add" name="id">
                    <select class="form-select" id="status_add" name="status">
                        <option value="1a" selected>HADIR</option>
                        <option value="2a">TIDAK HADIR</option>
                    </select>
                    <div id="terlambat_form" class="mt-3">
                        <select class="form-select" id="terlambat_add" name="terlambat">
                            <option value="1" selected>TEPAT WAKTU</option>
                            <option value="2">TERLAMBAT</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn_save_presensi">submit</button>
                </div>
            </div>
        </div>
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
        let holding = window.location.pathname.split("/").pop();
        var table = $('#table_recruitment_interview').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-interview') }}" + '/' + holding,
            },
            columns: [{
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                },
                {
                    data: 'waktu_wawancara',
                    name: 'waktu_wawancara',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'presensi',
                    name: 'presensi',
                },
                {
                    data: 'terlambat',
                    name: 'terlambat',
                },
                {
                    data: 'ujian',
                    name: 'ujian',
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_departemen',
                    name: 'nama_departemen'
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
        $('#icon-tab-0').on('shown.bs.tab', function(e) {
            table.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table1 = $('#table_recruitment_interview1').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ url('/dt/data-interview1') }}" + '/' + holding,
            },
            columns: [{
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                },
                {
                    data: 'waktu_wawancara',
                    name: 'waktu_wawancara',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'presensi',
                    name: 'presensi',
                },
                {
                    data: 'terlambat',
                    name: 'terlambat',
                },
                {
                    data: 'ujian',
                    name: 'ujian',
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_departemen',
                    name: 'nama_departemen'
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
        $('#icon-tab-1').on('shown.bs.tab', function(e) {
            table1.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table2 = $('#table_recruitment_interview2').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ url('/dt/data-interview2') }}" + '/' + holding,
            },
            columns: [{
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                }, {
                    data: 'presensi',
                    name: 'presensi',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_departemen',
                    name: 'nama_departemen'
                },
            ]
        });
        $('#icon-tab-2').on('shown.bs.tab', function(e) {
            table2.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        var table3 = $('#table_recruitment_interview3').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ url('/dt/data-interview3') }}" + '/' + holding,
            },
            columns: [{
                    data: 'tanggal_wawancara',
                    name: 'tanggal_wawancara',
                }, {
                    data: 'presensi',
                    name: 'presensi',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'nama_divisi',
                    name: 'nama_divisi'
                },
                {
                    data: 'nama_departemen',
                    name: 'nama_departemen'
                },
            ]
        });
        $('#icon-tab-3').on('shown.bs.tab', function(e) {
            table3.columns.adjust().draw().responsive.recalc();
            // table.draw();
        });
        $(document).on('click', '#btn_presensi', function() {
            // console.log('asooy');
            var id = $(this).data('id');
            $('#id_add').val(id);
            $('#modal_presensi').modal('show');

        });
        $(document).on('change', '#status_add', function() {
            let value = $(this).val();
            if (value == '1a') {
                $('#terlambat_form').show();
            } else if (value == '2a') {
                $('#terlambat_form').hide();
            }
        });
        $('#btn_save_presensi').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', $('#id_add').val());
            formData.append('status', $('#status_add').val());
            formData.append('terlambat', $('#terlambat_add').val());
            $.ajax({
                type: "POST",

                url: "{{ url('/dt/data-interview/presensi_recruitment_update') }}",
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
                        $('#modal_presensi').modal('hide');
                        $('#table_recruitment_interview').DataTable().ajax.reload();
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
    {{-- end datatable  --}}
@endsection
