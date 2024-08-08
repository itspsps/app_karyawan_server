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
                        <h5 class="card-title m-0 me-2">DATA ACCESS KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="modal fade" id="modal_tambah_access_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{url('/access/access_save_add/'.$holding)}}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_karyawan" id="id_karyawan" value="">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Access Karyawan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <h4 class="card-header">&nbsp;Profil</h4>
                                            <!-- Account -->
                                            <div class="card-body">
                                                <div class="d-flex align-items-start align-items-sm-center gap-4">

                                                    <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />

                                                    <table>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_name"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Departemen</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_departemen"> </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Divisi</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_divisi"> </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Jabatan</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_jabatan"></td>
                                                        <tr>
                                                            <th>Kontrak Kerja</th>
                                                            <td>&nbsp;</td>
                                                            <td>:</td>
                                                            <td id="td_kontrak_kerja"> </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Access</th>
                                                <th>Pilihan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            <tr>
                                                <td>1</td>
                                                <td>Mapping Shift Karyawan</td>
                                                <td>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="access_1" name="access_1">
                                                        <label class="form-check-label" for="access_1">
                                                            Buka Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Menu Asset</td>
                                                <td>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="access_2" name="access_2">
                                                        <label class="form-check-label" for="access_2">
                                                            Buka Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                    <table class="table" id="table_access_karyawan" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>divisi</th>
                                <th>jabatan</th>
                                <th>Access</th>
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
    var table = $('#table_access_karyawan').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('access-datatable') }}" + '/' + holding,
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
                data: 'departemen',
                name: 'departemen'
            },
            {
                data: 'divisi',
                name: 'divisi'
            },
            {
                data: 'jabatan',
                name: 'jabatan'
            },
            {
                data: 'access',
                name: 'access'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
<script>
    function bankCheck(that) {
        if (that.value == "BBRI") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_access_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BRI?',
                showConfirmButton: true
            });
            bankdigit = 15;
            // document.getElementById("ifBRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifMANDIRI").style.display = "none";
        } else if (that.value == "BBCA") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_access_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BCA?',
                showConfirmButton: true
            });
            bankdigit = 10;
            // document.getElementById("ifMANDIRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        } else if (that.value == "BOCBC") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_access_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank OCBC?',
                showConfirmButton: true
            });
            bankdigit = 12;
            // document.getElementById("ifBCA").style.display = "block";
            // document.getElementById("ifMANDIRI").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        }
    }
    $(function() {
        $('#id_departemen').on('change', function() {
            let id_departemen = $('#id_departemen').val();
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_divisi')}}",
                data: {
                    id_departemen: id_departemen
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_divisi').html(msg);
                    $('#id_divisi1').html(msg);
                    $('#id_divisi2').html(msg);
                    $('#id_divisi3').html(msg);
                    $('#id_divisi4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi').on('change', function() {
            let id_divisi = $('#id_divisi').val();
            // console.log(id_divisi);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabatan').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi1').on('change', function() {
            let id_divisi = $('#id_divisi1').val();
            // console.log(id_divisi);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabatan1').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi2').on('change', function() {
            let id_divisi = $('#id_divisi2').val();
            // console.log(id_divisi);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabatan2').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi3').on('change', function() {
            let id_divisi = $('#id_divisi3').val();
            // console.log(id_divisi);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabatan3').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi4').on('change', function() {
            let id_divisi = $('#id_divisi4').val();
            // console.log(id_divisi);
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabatan4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
    })
</script>
<script>
    $(document).on("click", "#btn_add_access_karyawan", function() {
        let id = $(this).data('id');
        // console.log(id);
        let holding = $(this).data("holding");
        $('#modal_tambah_access_karyawan').modal('show');
        let url = "{{ url('/access/add_access/')}}" + '/' + id + '/' + holding;
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
                var parsed = $.parseJSON(response);
                console.log(parsed);
                $('#td_name').text(parsed.name);
                $('#td_departemen').text(parsed.departemen.nama_departemen);
                if (parsed.jabatan1_id != null && parsed.jabatan2_id != null && parsed.jabatan3_id != null && parsed.jabatan4_id != null) {
                    $('#td_jabatan').html('1. ' + parsed.jabatan.nama_jabatan + '<br>' + '2. ' + parsed.jabatan1.nama_jabatan + '<br>' + '3. ' + parsed.jabatan2.nama_jabatan + '<br>' + '4. ' + parsed.jabatan3.nama_jabatan + '<br>' + '5. ' + parsed.jabatan4.nama_jabatan);
                } else if (parsed.jabatan1_id != null && parsed.jabatan2_id != null && parsed.jabatan3_id != null && parsed.jabatan4_id == null) {
                    $('#td_jabatan').html('1. ' + parsed.jabatan.nama_jabatan + '<br>' + '2. ' + parsed.jabatan1.nama_jabatan + '<br>' + '3. ' + parsed.jabatan2.nama_jabatan + '<br>' + '4. ' + parsed.jabatan3.nama_jabatan);
                } else if (parsed.jabatan1_id != null && parsed.jabatan2_id != null && parsed.jabatan3_id == null && parsed.jabatan4_id == null) {
                    $('#td_jabatan').html('1. ' + parsed.jabatan.nama_jabatan + '<br>' + '2. ' + parsed.jabatan1.nama_jabatan + '<br>' + '3. ' + parsed.jabatan2.nama_jabatan);
                } else if (parsed.jabatan1_id != null && parsed.jabatan2_id == null && parsed.jabatan3_id == null && parsed.jabatan4_id == null) {
                    $('#td_jabatan').html('1. ' + parsed.jabatan.nama_jabatan + '<br>' + '2. ' + parsed.jabatan1.nama_jabatan);
                } else if (parsed.jabatan1_id == null && parsed.jabatan2_id == null && parsed.jabatan3_id == null && parsed.jabatan4_id == null) {
                    $('#td_jabatan').html(parsed.jabatan.nama_jabatan);
                }
                if (parsed.divisi1_id != null && parsed.divisi2_id != null && parsed.divisi3_id != null && parsed.divisi4_id != null) {
                    $('#td_divisi').html('1. ' + parsed.divisi.nama_divisi + '<br>' + '2. ' + parsed.divisi1.nama_divisi + '<br>' + '3. ' + parsed.divisi2.nama_divisi + '<br>' + '4. ' + parsed.divisi3.nama_divisi + '<br>' + '5. ' + parsed.divisi4.nama_divisi);
                } else if (parsed.divisi1_id != null && parsed.divisi2_id != null && parsed.divisi3_id != null && parsed.divisi4_id == null) {
                    $('#td_divisi').html('1. ' + parsed.divisi.nama_divisi + '<br>' + '2. ' + parsed.divisi1.nama_divisi + '<br>' + '3. ' + parsed.divisi2.nama_divisi + '<br>' + '4. ' + parsed.divisi3.nama_divisi);
                } else if (parsed.divisi1_id != null && parsed.divisi2_id != null && parsed.divisi3_id == null && parsed.divisi4_id == null) {
                    $('#td_divisi').html('1. ' + parsed.divisi.nama_divisi + '<br>' + '2. ' + parsed.divisi1.nama_divisi + '<br>' + '3. ' + parsed.divisi2.nama_divisi);
                } else if (parsed.divisi1_id != null && parsed.divisi2_id == null && parsed.divisi3_id == null && parsed.divisi4_id == null) {
                    $('#td_divisi').html('1. ' + parsed.divisi.nama_divisi + '<br>' + '2. ' + parsed.divisi1.nama_divisi);
                } else if (parsed.divisi1_id == null && parsed.divisi2_id == null && parsed.divisi3_id == null && parsed.divisi4_id == null) {
                    $('#td_divisi').html(parsed.divisi.nama_divisi);
                }
                if (parsed.kontrak_kerja == 'SP') {
                    $('#td_kontrak_kerja').text('CV. SUMBER PANGAN');
                } else if (parsed.kontrak_kerja == 'SPS') {
                    $('#td_kontrak_kerja').text('PT. SURYA PANGAN SEMESTA');
                } else if (parsed.kontrak_kerja == 'SIP') {
                    $('#td_kontrak_kerja').text('CV. SURYA INTI PANGAN');
                }
                if (parsed.access_1 == 'on') {

                }
                $('#id_karyawan').val(parsed.id);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
    $('#foto_karyawan').change(function() {

        let reader = new FileReader();
        console.log(reader);
        reader.onload = (e) => {

            $('#template_foto_karyawan').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

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
                        $('#table_access_karyawan').DataTable().ajax.reload();
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