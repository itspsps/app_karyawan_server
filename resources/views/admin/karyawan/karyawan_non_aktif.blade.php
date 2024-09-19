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
                        <h5 class="card-title m-0 me-2">DATA KARYAWAN NON AKTIF</h5>
                    </div>
                </div>
                <div class="card-body">
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
                                                <a href="{{url('karyawan/ExportKaryawan/'.$holding)}}" type="button" class="btn btn-sm btn-success"> Download Excel</a>
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
                                <table class="table" id="table_karyawan_bulanan" style="width: 100%;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Nomor&nbsp;ID</th>
                                            <th>Nama&nbsp;Karyawan</th>
                                            <th>Telepon</th>
                                            <th>Email</th>
                                            <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Kontrak&nbsp;Kerja</th>
                                            <th>Penempatan&nbsp;Kerja</th>
                                            <th>Tgl&nbsp;Mulai&nbsp;Kerja</th>
                                            <th>Tgl&nbsp;Selesai&nbsp;Kerja</th>
                                            <th>Tgl&nbsp;Non&nbsp;Aktif</th>
                                            <th>Alasan</th>
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
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telepon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>Tanggal&nbsp;Masuk</th>
                                            <th>Penempatan&nbsp;Kerja</th>
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
            url: "{{ url('database_karyawan_non_aktif') }}" + '/' + holding,
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
                data: 'kontrak_kerja',
                name: 'kontrak_kerja'
            },
            {
                data: 'penempatan_kerja',
                name: 'penempatan_kerja'
            },
            {
                data: 'tgl_mulai_kontrak',
                name: 'tgl_mulai_kontrak'
            },
            {
                data: 'tgl_selesai_kontrak',
                name: 'tgl_selesai_kontrak'
            },
            {
                data: 'tanggal_non_active',
                name: 'tanggal_non_active'
            },
            {
                data: 'alasan',
                name: 'alasan'
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
                data: 'username',
                name: 'username'
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
@endsection