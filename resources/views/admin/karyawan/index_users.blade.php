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
            <div class="modal fade" id="modal_non_aktif_karyawan" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <form method="post" action="{{ url('karyawan/non_aktif_proses') }}" class="modal-content" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="backDropModalTitle">Form Non Aktif Karyawan</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mt-2">
                                <div class="col-md-12">
                                    <div class="card mb-4">
                                        <!-- Account -->
                                        <div class="card-body">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <input type="hidden" name="id_nonactive" id="id_nonactive" value="">
                                                <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                                                <table>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_nama"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Divisi</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_divisi"></td>
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
                                                        <td id="td_kontrak_kerja"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Penempatan Kerja</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_penempatan_kerja"> </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tgl Mulai Kontrak</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_mulai_kontrak"> </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tgl Selesai Kontrak</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_selesai_kontrak"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="date_now" name="date_now" readonly value="{{date('Y-m-d')}}" class="form-control @error('date_now') is-invalid @enderror" placeholder="Tanggal" />
                                        <label for="date_now">Tanggal Non Aktif</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea rows="10" id="alasan_non_aktif" name="alasan_non_aktif" class="form-control @error('alasan_non_aktif') is-invalid @enderror" placeholder="Alasan"></textarea>
                                        <label for="alasan_non_aktif">Alasan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success">
                                Save
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="modal_non_aktif_karyawan1" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <form method="post" action="{{ url('/karyawan/non_aktif_proses/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="backDropModalTitle">Form Non Aktif Karyawan</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mt-2">
                                <div class="col-md-12">
                                    <div class="card mb-4">
                                        <h4 class="card-header"><a href="{{url('karyawan/'.$holding)}}"><i class="mdi mdi-arrow-left-bold"></i></a>&nbsp;Profil</h4>
                                        <!-- Account -->
                                        <div class="card-body">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />

                                                <table>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_nama"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Jabatan</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td>Karyawan Harian</td>
                                                    <tr>
                                                        <th>Penempatan Kerja</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_penempatan_kerja"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="alasan_non_aktif" name="alasan_non_aktif" class="form-control @error('alasan_non_aktif') is-invalid @enderror" placeholder="Alasan" />
                                        <label for="alasan_non_aktif">Alasan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DATA USER</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <div class="nav-align-top">
                        <div class="row">
                            <div class="col-6">
                                <ul class="nav nav-pills nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a type=" button" style="width: auto;" class="nav-link active" role="tab" data-bs-toggle="tab" href="#navs-pills-justified-home">
                                            <i class="tf-icons mdi mdi-account-tie me-1"></i><span class="d-none d-sm-block">Karyawan Bulanan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a type="button" style="width: auto;" class="nav-link" role="tab" data-bs-toggle="tab" href="#navs-pills-justified-profile">
                                            <i class="tf-icons mdi mdi-account me-1"></i><span class="d-none d-sm-block">Karyawan Harian</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                                <table class="table" id="table_karyawan_bulanan" style="width: 100%; font-size: smaller;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Nomor&nbsp;ID</th>
                                            <th>Nama&nbsp;Karyawan</th>
                                            <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>username</th>
                                            <th>Akses</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                                <table class="table" id="table_karyawan_harian" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor&nbsp;ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama&nbsp;Karyawan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Akses&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
    var table = $('#table_karyawan_bulanan').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('users_bulanan-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nomor_identitas_karyawan',
                name: 'nomor_identitas_karyawan'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'nama_divisi',
                name: 'nama_divisi'
            },
            {
                data: 'nama_jabatan',
                name: 'nama_jabatan'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'akses',
                name: 'akses'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'option',
                name: 'option'
            },
        ],
        order: [
            [2, 'asc']
        ]
    });
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        table.columns.adjust().draw().responsive.recalc();
        // table.draw();
    })
    var table1 = $('#table_karyawan_harian').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('users_harian-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nomor_identitas_karyawan',
                name: 'nomor_identitas_karyawan'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'akses',
                name: 'akses'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'option',
                name: 'option'
            },
        ],
        order: [
            [2, 'asc']
        ]
    });
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        table1.columns.adjust().draw().responsive.recalc();
        // table.draw();
    })
</script>
<script>
    $(document).on("click", "#btn_edit_password", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        // console.log(holding);
        let url = "{{ url('/karyawan/edit-password/')}}" + '/' + id + '/' + holding;
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
    $(document).on('click', '#btn_non_aktif_karyawan', function() {
        var id = $(this).data('id');
        var holding = $(this).data("holding");
        var nama = $(this).data('nama');
        var divisi = $(this).data('divisi');
        var jabatan = $(this).data('jabatan');
        var bagian = $(this).data('bagian');
        var foto = $(this).data('foto');
        var tgl_mulai_kontrak = $(this).data('tgl_mulai_kontrak');
        var tgl_selesai_kontrak = $(this).data('tgl_selesai_kontrak');
        var kontrak_kerja = $(this).data('kontrak_kerja');
        var penempatan_kerja = $(this).data('penempatan_kerja');
        if (foto == '' | foto == null) {
            $('#template_foto_karyawan').attr('src', "{{asset('admin/assets/img/avatars/1.png')}}");
        } else {
            $('#template_foto_karyawan').attr('src', "{{url('storage/app/public/foto_karyawan/')}}" + foto);
        }
        $('#td_nama').html(nama);
        $('#td_divisi').html(divisi);
        $('#td_jabatan').html(jabatan);
        $('#td_bagian').html(bagian);
        $('#td_jabatan').html(jabatan);
        $('#td_mulai_kontrak').html(tgl_mulai_kontrak);
        $('#td_selesai_kontrak').html(tgl_selesai_kontrak);
        $('#td_kontrak_kerja').html(kontrak_kerja);
        $('#td_penempatan_kerja').html(penempatan_kerja);
        $('#id_nonactive').val(id);
        $('#modal_non_aktif_karyawan').modal('show');
    });
</script>
@endsection