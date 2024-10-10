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
        <div class="modal fade" id="modal_perbarui_kontrak" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <form id="form_update_kontrak" method="post" action="{{ url('karyawan/update_kontrak_proses') }}" class="modal-content" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="backDropModalTitle">Form Pembaruan Kontrak Karyawan</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 mt-2">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <!-- Account -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <input type="hidden" name="id_karyawan" id="id_karyawan" value="">
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
                                    <input type="date" id="tgl_mulai_kontrak_baru" name="tgl_mulai_kontrak_baru" readonly value="{{date('Y-m-d')}}" class="form-control @error('tgl_mulai_kontrak_baru') is-invalid @enderror" placeholder="Tanggal" />
                                    <label for="tgl_mulai_kontrak_baru">Tanggal Mulai Kontrak Baru</label>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-12" style="margin-top:2% ;">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" id="tgl_selesai_kontrak_baru" name="tgl_selesai_kontrak_baru" value="" class="form-control @error('tgl_selesai_kontrak_baru') is-invalid @enderror" placeholder="Tanggal" />
                                    <label for="tgl_selesai_kontrak_baru">Tanggal Selesai Kontrak Baru</label>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-12" style="margin-top:2% ;">
                                <div class="form-floating form-floating-outline">
                                    <select id="lama_kontrak_baru" name="lama_kontrak_baru" class="form-control @error('lama_kontrak_baru') is-invalid @enderror" placeholder="Lama Kontrak">
                                        <option value="">- Select Lama Kontrak -</option>
                                        <option value="3 bulan">3 Bulan</option>
                                        <option value="6 bulan">6 Bulan</option>
                                        <option value="1 tahun">1 Tahun</option>
                                        <option value="2 tahun">2 Tahun</option>
                                        <option value="tetap">Tetap</option>
                                    </select>
                                    <label for="lama_kontrak_baru">Lama Kontrak Baru</label>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <hr class="m-0">
                                <small class="text-light fw-medium">Upload File Pendukung</small>
                                <br>
                                <br>
                                <div class="form-floating form-floating-outline">
                                    <input type="file" id="file_kontrak_kerja" accept="application/pdf" name="file_kontrak_kerja" class="form-control @error('file_kontrak_kerja') is-invalid @enderror" placeholder="File" />
                                    <label for="file_kontrak_kerja">File Kontrak Kerja</label>
                                </div>
                                <small class="text-info fw-medium">*Format PDF</small>
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DATA KARYAWAN MASA TENGGANG KONTRAK</h5>
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
                                    <div class="small mb-1">Karyawan Kontrak Hampir Habis</div>
                                    <h5 class="mb-0">{{$karyawan_lebih_kontrak}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-danger rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Kontrak Melebihi Batas</div>
                                    <h5 class="mb-0">{{$karyawan_akan_habis_kontrak}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5">
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
                    <table class="table" id="table_karyawan_masa_tenggang_kontrak" style="width: 100%; font-size: smaller;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nomor&nbsp;ID</th>
                                <th>Nama&nbsp;Karyawan</th>
                                <th>Telepon</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Tanggal&nbsp;Kontrak</th>
                                <th>Status</th>
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
    var table = $('#table_karyawan_masa_tenggang_kontrak').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('karyawan/database_karyawan_masa_tenggang_kontrak') }}" + '/' + holding,
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
                data: 'nama_divisi',
                name: 'nama_divisi'
            },
            {
                data: 'nama_jabatan',
                name: 'nama_jabatan'
            },
            {
                data: 'tgl_kontrak',
                name: 'tgl_kontrak'
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
</script>
<script>
    $(document).on('click', '#btn_perbarui_kontrak', function() {
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
        $('#id_karyawan').val(id);
        $('#modal_perbarui_kontrak').modal('show');
    });
</script>
@endsection