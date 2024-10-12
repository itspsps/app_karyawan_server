@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<style type="text/css">
    .my-swal {
        z-index: X;
    }

    #card-profile::backdrop {
        background-color: red;

    }

    .myFont {
        font-size: 4pt !important;
    }


    .img_resign {
        position: absolute;
        top: 50%;
        left: 80%;
        transform: translate(-50%, -50%);
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="modal fade" id="modal_non_aktif_user" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <form method="post" action="{{ url('users/non_aktif_proses') }}" class="modal-content" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="backDropModalTitle">Form Non Aktif User</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mt-2">
                                <div class="col-md-12" style="position: relative;">
                                    <div class="card mb-4" id="card-profile">
                                        <img id="icon_resign" style="visibility: hidden;" src="{{asset('admin/assets/img/resign.png')}}" alt="" class="img_resign d-block w-px-150 h-px-120 rounded" />
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
                                        <textarea rows="10" id="alasan_non_aktif" name="alasan_non_aktif" class="form-control @error('alasan_non_aktif') is-invalid @enderror" placeholder="Alasan"></textarea>
                                        <label for="alasan_non_aktif">Alasan User Non Aktif</label>
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
            <div class="modal fade" id="modal_aktif_user" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <form method="post" action="{{ url('users/aktif_proses') }}" class="modal-content" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="backDropModalTitle">Form Aktif User</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mt-2">
                                <div class="col-md-12" style="position: relative;">
                                    <div class="card mb-4" id="card-profile">
                                        <img id="icon_resign" style="visibility: hidden;" src="{{asset('admin/assets/img/resign.png')}}" alt="" class="img_resign d-block w-px-150 h-px-120 rounded" />
                                        <!-- Account -->
                                        <div class="card-body">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <input type="hidden" name="id_active" id="id_active" value="">
                                                <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                                                <table>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_nama_active"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Divisi</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_divisi_active"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Jabatan</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_jabatan_active"></td>
                                                    <tr>
                                                        <th>Kontrak Kerja</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_kontrak_kerja_active"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Penempatan Kerja</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_penempatan_kerja_active"> </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tgl Mulai Kontrak</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_mulai_kontrak_active"> </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tgl Selesai Kontrak</th>
                                                        <td>&nbsp;</td>
                                                        <td>:</td>
                                                        <td id="td_selesai_kontrak_active"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea rows="10" id="alasan_aktif" name="alasan_aktif" class="form-control @error('alasan_aktif') is-invalid @enderror" placeholder="Alasan"></textarea>
                                        <label for="alasan_aktif">Alasan User Aktif</label>
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
            <div class="modal fade" id="modal_non_aktif_user1" data-bs-backdrop="static" tabindex="-1">
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
                    <a type="button" href="javascript:void(0)" id="btn_tambah_users" class="btn btn-xs btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</a>

                    <button class="btn btn-xs btn-success waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="menu-icon tf-icons mdi mdi-file-excel"></i> Excel
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_import_user_karyawan" href="">Import Add Excel</a></li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_import_update_user_karyawan" href="">Import Update Excel</a></li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal_export_user_karyawan" href="#">Export Excel</a></li>
                    </ul>
                    <a type="button" href="{{url('users/pdfKaryawan/'.$holding)}}" class="btn btn-xs btn-danger waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-file-pdf-box"></i>PDF</a>
                    <div class="modal fade" id="modal_tambah_users" role="dialog" data-bs-backdrop="static" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/users/prosesTambahUser/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah User</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="height:400px">
                                    <div class="row g-2 mt-2">
                                        <div class="col-md-12 mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select id="nama_karyawan" name="nama_karyawan" class="form-control" data-placeholder="Pilih Karyawan" style="font-size: small;">

                                                    <option value="">Pilih Karyawan</option>

                                                    @foreach($karyawan as $karyawanid)
                                                    @if(old('nama_karyawan') == $karyawanid->id)
                                                    <option selected style="font-size: small;" value="{{$karyawanid->id}}">{{$karyawanid->name}}</option>
                                                    @else
                                                    <option style="font-size: small;" value="{{$karyawanid->id}}">{{$karyawanid->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="nama_karyawan">Karyawan</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="{{old('username')}}" />
                                                <label for="username">Username</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="form-password-toggle">
                                                <label class="form-label" for="password">Password</label>
                                                <div class="input-group input-group-merge">
                                                    <input
                                                        type="password" value="{{old('password')}}"
                                                        class="form-control"
                                                        id="password" name="password"
                                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                        aria-describedby="password" />
                                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select id="level" name="level" class="form-control" placeholder="Level Access">
                                                    <option @if(old('level')=='' ) selected else @endif disabled value=""> ~Pilih Access~ </option>
                                                    <option @if(old('level')=='user' )selected else @endif value="user">Karyawan</option>
                                                    <option @if(old('level')=='admin' )selected else @endif value="admin">HRD</option>
                                                </select>
                                                <label for="level">Level Access</label>
                                            </div>
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
                    <div class="modal fade" id="modal_import_user_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/users/ImportUser/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Add User</h4>
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
                    <div class="modal fade" id="modal_import_update_user_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
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
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_export_user_karyawan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Export Excel User Karyawan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <h6>Download File Excel Data User Karyawan</h6>
                                                <a href="{{url('users/ExportUser/'.$holding)}}" type="button" class="btn btn-xs btn-success"> Download Excel</a>
                                            </div>
                                        </div>
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
                    <hr class="my-5">
                    <div class="nav-align-top">
                        <div class="row">
                            <div class="col-6">
                                <ul class="nav nav-pills nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a type="button" style="width: auto;" class="nav-link active" role="tab" data-bs-toggle="tab" href="#navs-pills-justified-home">
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
                                            <th>Username</th>
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
                                <table class="table" id="table_karyawan_harian" style="width: 100%; font-size: smaller;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor&nbsp;ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama&nbsp;Karyawan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Akses&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                data: 'user_aktif',
                name: 'user_aktif'
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
                data: 'user_aktif',
                name: 'user_aktif'
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
    $('#nama_karyawan').select2({
        theme: "bootstrap-5",
        dropdownParent: $('#modal_tambah_users .modal-content'),
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        dropdownCssClass: "myFont"
    });
    $(document).on("click", "#btn_edit_password", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        // console.log(holding);
        let url = "{{ url('/users/edit-password/')}}" + '/' + id + '/' + holding;
        Swal.fire({
            allowOutsideClick: false,
            background: 'transparent',
            html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
            showCancelButton: false,
            showConfirmButton: false,
            onBeforeOpen: () => {

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
        var status_aktif = $(this).data('status_aktif');
        console.log(status_aktif);
        if (status_aktif == 'NON AKTIF') {
            $('.img_resign').show();
        } else {
            $('.img_resign').hide();
        }
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
        $('#modal_non_aktif_user').modal('show');
    });
    $(document).on('click', '#btn_tambah_users', function() {
        $('#modal_tambah_users').modal('show');
    });
    $(document).on('click', '#btn_aktif_karyawan', function() {

        var status_aktif = $(this).data('status_aktif');
        if (status_aktif == 'NON AKTIF') {
            Swal.fire({
                icon: 'warning',
                title: 'Info',
                text: 'Karyawan Non Aktif',
                showConfirmButton: true,
            });
            $('.img_resign').show();
        } else {
            $('.img_resign').hide();
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
            console.log(id);

            if (foto == '' | foto == null) {
                $('#template_foto_karyawan').attr('src', "{{asset('admin/assets/img/avatars/1.png')}}");
            } else {
                $('#template_foto_karyawan').attr('src', "{{url('storage/app/public/foto_karyawan/')}}" + foto);
            }
            $('#td_nama_active').html(nama);
            $('#td_divisi_active').html(divisi);
            $('#td_jabatan_active').html(jabatan);
            $('#td_bagian_active').html(bagian);
            $('#td_jabatan_active').html(jabatan);
            $('#td_mulai_kontrak_active').html(tgl_mulai_kontrak);
            $('#td_selesai_kontrak_active').html(tgl_selesai_kontrak);
            $('#td_kontrak_kerja_active').html(kontrak_kerja);
            $('#td_penempatan_kerja_active').html(penempatan_kerja);
            $('#id_active').val(id);
            $('#modal_aktif_user').modal('show');
        }
    });
</script>
@endsection