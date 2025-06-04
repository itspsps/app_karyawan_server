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
    <a href="javascript:void(0);" class="btn btn-primary tambah-pg"
        style="position: fixed; right: -10px; top: 50%; z-index: 9999;">Tambah Soal</a>

    <form method="post" action="{{ url('/ujian/ujian-pg-store') }}" enctype="multipart/form-data">
        @csrf
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">DATA UJIAN</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Nama Ujian / Quiz</label>
                                        <input type="text" name="nama_ujian" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Mapel Kategori</label>
                                        <select class="form-control" name="mapel" id="mapel" required>
                                            <option value="">Pilih</option>
                                            @foreach ($guru_mapel as $gm)
                                                <option value="{{ $gm->mapel->id }}">{{ $gm->mapel->nama_mapel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Kelas</label>
                                        <select class="form-control" name="kelas" id="kelas" required>
                                            <option value="">Pilih</option>
                                            @foreach ($guru_kelas as $gk)
                                                <option value="{{ $gk->kelas->id }}">{{ $gk->kelas->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Waktu Jam</label>
                                        <input type="number" name="jam" class="form-control" value="0" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Waktu Menit</label>
                                        <input type="number" name="menit" class="form-control" value="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="acak"
                                            value="1">
                                        <label class="custom-control-label" for="customCheck1">Acak Soal Siswa</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Soal Ujian</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="soal_pg">
                                <div class="isi_soal">
                                    <div class="form-group">
                                        <label for="">Soal No. 1</label>
                                        <textarea name="soal[]" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Pilihan A</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">A</span>
                                                    </div>
                                                    <input type="text" name="pg_1[]" class="form-control"
                                                        placeholder="Opsi A" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Pilihan B</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">B</span>
                                                    </div>
                                                    <input type="text" name="pg_2[]" class="form-control"
                                                        placeholder="Opsi B" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Pilihan C</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">C</span>
                                                    </div>
                                                    <input type="text" name="pg_3[]" class="form-control"
                                                        placeholder="Opsi C" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Pilihan D</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">D</span>
                                                    </div>
                                                    <input type="text" name="pg_4[]" class="form-control"
                                                        placeholder="Opsi D" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Pilihan E</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">E</span>
                                                    </div>
                                                    <input type="text" name="pg_5[]" class="form-control"
                                                        placeholder="Opsi E" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Jawaban</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <polyline points="20 6 9 17 4 12"></polyline>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="jawaban[]" class="form-control"
                                                        placeholder="Contoh : A" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Modal Tambah -->
    <div class="modal fade" id="excel_ujian" tabindex="-1" role="dialog" aria-labelledby="excel_ujianLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ url('/guru/pg_excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excel_ujianLabel">Import Soal via Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            x
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Nama Ujian / Quiz</label>
                                    <input type="text" name="e_nama_ujian" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Mapel</label>
                                    <select class="form-control" name="e_mapel" id="e_mapel" required>
                                        <option value="">Pilih</option>
                                        {{-- @foreach ($guru_mapel as $gm)
                                    <option value="{{ $gm->mapel->id }}">{{ $gm->mapel->nama_mapel }}</option>
                                @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Kelas</label>
                                    <select class="form-control" name="e_kelas" id="e_kelas" required>
                                        <option value="">Pilih</option>
                                        {{-- @foreach ($guru_kelas as $gk)
                                    <option value="{{ $gk->kelas->id }}">{{ $gk->kelas->nama_kelas }}</option>
                                @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Waktu Jam</label>
                                    <input type="number" name="e_jam" class="form-control" value="0" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Waktu Menit</label>
                                    <input type="number" name="e_menit" class="form-control" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="acak" name="e_acak"
                                        value="1">
                                    <label class="custom-control-label" for="acak">Acak Soal Siswa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">File Excel</label><br>
                                    <input type="file" name="excel" accept=".xls, .xlsx">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Template</label><br>
                                <a href="{{ url('/summernote/unduh') }}/template-pg-excel.xlsx" class="btn btn-success"
                                    target="_blank">Download Template</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" value="reset" class="btn" data-dismiss="modal"><i
                                class="flaticon-cancel-12"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
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
        var table = $('#table_recruitment').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-recruitment') }}" + '/' + holding,
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
                    data: 'desc_recruitment',
                    name: 'desc_recruitment'
                },
                {
                    data: 'pelamar',
                    name: 'pelamar'
                },
                {
                    data: 'status_recruitment',
                    name: 'status_recruitment'
                },
                {
                    data: 'created_recruitment',
                    render: function(data, type, row, meta) {
                        let dateParts = data.split('-');
                        let formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                        return '<pre style="font-size: inherit;font-family: inherit;margin: 0;">' +
                            formattedDate + '</pre>';
                    }
                },
                {
                    data: 'option',
                    name: 'option'
                },
            ]
        });
    </script>
    {{-- end datatable  --}}
    <script>
        // start add departemen
        $('#nama_dept').on('change', function() {
            let id_dept = $(this).val();
            let url = "{{ url('/bagian/get_divisi') }}" + "/" + id_dept;
            console.log(id_dept);
            console.log(url);
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
                    $('#nama_divisi').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        // end add departemen

        // start add divisi
        $('#nama_divisi').on('change', function() {
            let id_divisi = $(this).val();
            let url = "{{ url('/bagian/get_bagian') }}" + "/" + id_divisi;
            console.log(id_divisi);
            console.log(url);
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
                    $('#nama_bagian').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        // show modal syarat
        $(document).on('click', '#btn_lihat_syarat', function() {
            let id = $(this).data('id');
            let desc = $(this).data('desc'); // Mendapatkan data dengan HTML
            // desc = $('<div>').html(desc).text();
            let holding = $(this).data("holding");
            $('#show_desc_recruitment').summernote('code', desc);
            $('#show_desc_recruitment').summernote('disable');
            // let url = "{{ url('recruitment/show/') }}" + '/' + id + '/' + holding;
            $('#modal_lihat_syarat').modal('show');
        });
        // update status aktif to non aktif
        $(document).on('click', '#btn_status_aktif', function() {
            var id = $(this).data('id');
            let holding = $(this).data("holding");
            console.log(id);
            console.log(holding);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Menonaktifkan Recruitment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/update/status-recruitment/') }}" + '/' + id +
                            '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terupdate!',
                                text: 'Data anda berhasil di update.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_recruitment').DataTable().ajax.reload();
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
        // update status non aktif to aktif
        $(document).on('click', '#btn_status_naktif', function() {
            var id = $(this).data('id');
            let holding = $(this).data("holding");
            console.log(id);
            console.log(holding);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Mengaktifkan Recruitment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/update/status-recruitment/') }}" + '/' + id +
                            '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terupdate!',
                                text: 'Data anda berhasil di update.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_recruitment').DataTable().ajax.reload();
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
        // edit data
        $(document).on("click", "#btn_edit_recruitment", function() {
            let id = $(this).data('id');
            let dept = $(this).data("dept");
            let divisi = $(this).data("divisi");
            let bagian = $(this).data("bagian");
            let tanggal = $(this).data("tanggal");
            let holding = $(this).data("holding");
            console.log(dept);
            console.log(divisi);
            console.log(bagian);
            console.log(tanggal);
            // console.log(desc);
            console.log(holding);
            $('#id_recruitment').val(id);
            $('#nama_departemen_update option').filter(function() {
                // console.log($(this).val().trim());
                return $(this).val().trim() == dept
            }).prop('selected', true)
            $('#nama_divisi_update option').filter(function() {
                // console.log($(this).val().trim());
                return $(this).val().trim() == divisi
            }).prop('selected', true)
            $('#nama_bagian_update').val(bagian);
            $('#created_recruitment_update').val(tanggal);
            $('#modal_edit_recruitment').modal('show');

        });

        // delete data
        $(document).on('click', '#btn_delete_recruitment', function() {
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
                        url: "{{ url('/recruitment/delete/') }}" + '/' + id + '/' + holding,
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
                            $('#table_recruitment').DataTable().ajax.reload();
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

    <script>
        $(document).ready(function() {
            function uploadImage(e, o) {
                var a = new FormData;
                a.append("image", e), $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('summernote_upload') }}",
                    cache: !1,
                    contentType: !1,
                    processData: !1,
                    data: a,
                    type: "post",
                    success: function(e) {
                        $(o).summernote("insertImage", e)
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            }

            function deleteImage(e) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: {
                        src: e
                    },
                    type: "post",
                    url: "{{ route('summernote_delete') }}",
                    cache: !1,
                    success: function(e) {
                        console.log(e)
                    }
                })
            }
            setInterval(() => {
                $(".summernote").summernote({
                    placeholder: "Hello stand alone ui",
                    tabsize: 2,
                    height: 120,
                    toolbar: [
                        ["style", ["style"]],
                        ["font", ["bold", "underline", "clear"]],
                        ["color", ["color"]],
                        ["para", ["ul", "ol", "paragraph"]],
                        ["table", ["table"]],
                        ["insert", ["link", "picture", "video"]],
                        ["view", ["fullscreen", "help"]]
                    ],
                    callbacks: {
                        onImageUpload: function(e, o = this) {
                            uploadImage(e[0], o)
                        },
                        onMediaDelete: function(e) {
                            deleteImage(e[0].src)
                        }
                    }
                })
            }, 1e3);
            var no_soal = 2;
            $('.tambah-pg').click(function() {
                const pg = `
                <div class="isi_soal">
                <hr>
                    <div class="form-group">
                        <label for="">Soal No . ` + no_soal + `</label>
                        <textarea name="soal[]" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Pilihan A</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">A</span>
                                    </div>
                                    <input type="text" name="pg_1[]" class="form-control" placeholder="Opsi A" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Pilihan B</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">B</span>
                                    </div>
                                    <input type="text" name="pg_2[]" class="form-control" placeholder="Opsi B" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Pilihan C</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">C</span>
                                    </div>
                                    <input type="text" name="pg_3[]" class="form-control" placeholder="Opsi C" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Pilihan D</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">D</span>
                                    </div>
                                    <input type="text" name="pg_4[]" class="form-control" placeholder="Opsi D" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Pilihan E</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">E</span>
                                    </div>
                                    <input type="text" name="pg_5[]" class="form-control" placeholder="Opsi E" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Jawaban</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </span>
                                    </div>
                                    <input type="text" name="jawaban[]" class="form-control" placeholder="Contoh : A" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-danger hapus-pg">Hapus</a>
                </div>
            `;

                $('#soal_pg').append(pg);
                no_soal++;
            });
            $("#soal_pg").on("click", ".isi_soal a", function() {
                $(this).parents(".isi_soal").remove(), --no_soal
            });
        })
    </script>
@endsection
