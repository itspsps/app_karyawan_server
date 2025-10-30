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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('isi')
    @include('sweetalert::alert')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="container card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">DATA RANKING</h5>
                        </div>
                    </div>
                    <table class="table" id="table_recruitment_ranking" style="width: 100%; font-size: small;">
                        <thead class="table-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Departemen</th>
                                <th>Divisi</th>
                                <th>Bagian</th>
                                <th>Ranking</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        let holding = window.location.pathname.split("/").pop();
        var table = $('#table_recruitment_ranking').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/dt/data-ranking') }}" + '/' + holding,
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at',
                    className: 'nowrap'
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
                    data: 'pelamar',
                    name: 'pelamar'
                },
            ]
        });
    </script>

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
                            $('#table_interview').DataTable().ajax.reload();
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
                            $('#table_interview').DataTable().ajax.reload();
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
                            $('#table_interview').DataTable().ajax.reload();
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
