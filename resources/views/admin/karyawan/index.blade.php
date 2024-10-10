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
                        <h5 class="card-title m-0 me-2">DATA KARYAWAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <a type="button" href="{{url('karyawan/tambah-karyawan/'.$holding)}}" class="btn btn-xs btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</a>

                    <button class="btn btn-xs btn-success waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="menu-icon tf-icons mdi mdi-file-excel"></i> Excel
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_import_karyawan" href="">Import Add Excel</a></li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_import_update_karyawan" href="">Import Update Excel</a></li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_export_karyawan" href="#">Export Excel</a></li>
                    </ul>
                    <a type="button" href="{{url('karyawan/pdfKaryawan/'.$holding)}}" class="btn btn-xs btn-danger waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-file-pdf-box"></i>PDF</a>

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
                                    <div class="small mb-1">Karyawan Bulanan</div>
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
                                    <div class="small mb-1">Karyawan Harian</div>
                                    <h5 class="mb-0">{{$karyawan_shift}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5">
                    <div class="modal fade" id="modal_import_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/karyawan/ImportKaryawan/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Add Karyawan</h4>
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
                                        <a href="{{asset('admin/template_import/TEMPLATE IMPORT TAMBAH KARYAWAN BULANAN SP_SPS.xlsx')}}" type="button" download="" class="btn btn-xs btn-primary"> Download Format Excel</a>
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
                    <div class="modal fade" id="modal_import_update_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/karyawan/ImportUpdateKaryawan/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Update Karyawan</h4>
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
                                        <a href="{{asset('admin/template_import/TEMPLATE IMPORT UPDATE KARYAWAN BULANAN SP_SPS.xlsx')}}" type="button" download="" class="btn btn-xs btn-primary"> Download Format Excel</a>
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
                    <div class="modal fade" id="modal_export_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/karyawan/ImportKaryawan/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Export Excel Karyawan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <h6>Download File Excel Data Karyawan</h6>
                                                <a href="{{url('karyawan/ExportKaryawan/'.$holding)}}" type="button" class="btn btn-xs btn-success"> Download Excel</a>
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
                                    <button type="submit" class="btn btn-xs btn-success">
                                        Save
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary" data-bs-dismiss="modal">
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
                                            <th>Telepon</th>
                                            <th>Email</th>
                                            <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Penempatan&nbsp;Kerja</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                                <table class="table" id="table_karyawan_harian" style="width: 100%; font-size: smaller;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor&nbsp;ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telepon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal&nbsp;Masuk&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penempatan&nbsp;Kerja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Opsi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
            url: "{{ url('karyawan_bulanan-datatable') }}" + '/' + holding,
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
                data: 'telepon',
                name: 'telepon'
            },
            {
                data: 'email',
                name: 'email'
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
                data: 'penempatan_kerja',
                name: 'penempatan_kerja'
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
            url: "{{ url('karyawan_harian-datatable') }}" + '/' + holding,
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
                data: 'telepon',
                name: 'telepon'
            },
            {
                data: 'detail_alamat',
                name: 'detail_alamat'
            },
            {
                data: 'tgl_join',
                name: 'tgl_join'
            },
            {
                data: 'penempatan_kerja',
                name: 'penempatan_kerja'
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
    function bankCheck(that) {
        if (that.value == "BBRI") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
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
                target: document.getElementById('modal_tambah_karyawan'),
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
                target: document.getElementById('modal_tambah_karyawan'),
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
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_divisi')}}",
                data: {
                    holding: holding,
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
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_bagian')}}",
                data: {
                    holding: holding,
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_bagian').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi1').on('change', function() {
            let id_divisi = $('#id_divisi1').val();
            // console.log(id_divisi);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_bagian')}}",
                data: {
                    holding: holding,
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_bagian1').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi2').on('change', function() {
            let id_divisi = $('#id_divisi2').val();
            // console.log(id_divisi);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_bagian')}}",
                data: {
                    holding: holding,
                    id_divisi: id_divisi,
                },
                cache: false,

                success: function(msg) {
                    $('#id_bagian2').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi3').on('change', function() {
            let id_divisi = $('#id_divisi3').val();
            // console.log(id_divisi);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_bagian')}}",
                data: {
                    holding: holding,
                    id_divisi: id_divisi,
                },
                cache: false,

                success: function(msg) {
                    $('#id_bagian3').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_divisi4').on('change', function() {
            let id_divisi = $('#id_divisi4').val();
            // console.log(id_divisi);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_bagian')}}",
                data: {
                    holding: holding,
                    id_divisi: id_divisi
                },
                cache: false,

                success: function(msg) {
                    $('#id_bagian4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_bagian').on('change', function() {
            let id_bagian = $('#id_bagian').val();
            // console.log(id_bagian);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    holding: holding,
                    id_bagian: id_bagian
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
        $('#id_bagian1').on('change', function() {
            let id_bagian = $('#id_bagian1').val();
            // console.log(id_bagian);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    holding: holding,
                    id_bagian: id_bagian
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
        $('#id_bagian2').on('change', function() {
            let id_bagian = $('#id_bagian2').val();
            // console.log(id_bagian);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    holding: holding,
                    id_bagian: id_bagian
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
        $('#id_bagian3').on('change', function() {
            let id_bagian = $('#id_bagian3').val();
            // console.log(id_bagian);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    holding: holding,
                    id_bagian: id_bagian
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabata3').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_bagian4').on('change', function() {
            let id_bagian = $('#id_bagian4').val();
            // console.log(id_bagian);
            let holding = '{{$holding}}';
            $.ajax({
                type: 'GET',
                url: "{{url('karyawan/get_jabatan')}}",
                data: {
                    holding: holding,
                    id_bagian: id_bagian
                },
                cache: false,

                success: function(msg) {
                    $('#id_jabata4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
    })
    $(function() {
        $('#nik').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("nik").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NIK harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#npwp').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("npwp").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NPWP harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#nomor_rekening').keyup(function(e) {
            if ($(this).val().length >= bankdigit) {
                $(this).val($(this).val().substr(0, bankdigit));
                document.getElementById("nomor_rekening").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#id_provinsi').on('change', function() {
            let id_provinsi = $(this).val();
            let url = "{{url('/karyawan/get_kabupaten')}}" + "/" + id_provinsi;
            console.log(id_provinsi);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_provinsi: id_provinsi
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kabupaten').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten').on('change', function() {
            let id_kabupaten = $(this).val();
            let url = "{{url('/karyawan/get_kecamatan')}}" + "/" + id_kabupaten;
            console.log(id_kabupaten);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kabupaten: id_kabupaten
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kecamatan').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan').on('change', function() {
            let id_kecamatan = $(this).val();
            let url = "{{url('/karyawan/get_desa')}}" + "/" + id_kecamatan;
            console.log(id_kecamatan);
            console.log(url);
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
                    $('#id_desa').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
    });
</script>
<script>
    $(document).on("click", "#btndetail_karyawan", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        console.log(holding);
        let url = "{{ url('/karyawan/detail/')}}" + '/' + id + '/' + holding;
        Swal.fire({
            allowOutsideClick: false,
            background: 'transparent',
            html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
            showCancelButton: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                // Swal.showLoading()
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
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error',
                            text: 'Error : ' + data.responseJSON.message,
                            showConfirmButton: true,
                        });
                        // console.log('error:', data)
                    },

                })
            },
            onAfterClose() {
                Swal.close()
            }
        });
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
            success: function(response) {
                console.log(response);
                window.location.assign(url);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
@endsection