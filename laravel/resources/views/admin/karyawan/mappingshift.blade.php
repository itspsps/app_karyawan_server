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
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> MAPPING SHIFT KARYAWAN</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header"><a href="{{url('karyawan/'.$holding)}}"><i class="mdi mdi-arrow-left-bold"></i></a>&nbsp;Profil</h4>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if($karyawan->foto_karyawan == null)
                        <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                        @else
                        <img src="https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_karyawan/{{$karyawan->foto_karyawan}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                        @endif
                        @if($karyawan->kategori=='Karyawan Bulanan')
                        <table>
                            <tr>
                                <th>Nama</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>{{$karyawan->fullname}}</td>
                            </tr>
                            <tr>
                                <th>Divisi</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    @if(count($divisi_karyawan)>1)
                                    @foreach($divisi_karyawan as $dv)
                                    {{$no++;}}. {{$dv->nama_divisi}} <br>
                                    @break
                                    @endforeach
                                    @else
                                    {{$karyawan->Divisi->nama_divisi}} <br>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    @if(count($jabatan_karyawan)>1)
                                    @foreach($jabatan_karyawan as $jb)
                                    {{$no1++;}}. {{$jb->nama_jabatan}} <br>
                                    @endforeach
                                    @else
                                    {{$karyawan->Jabatan->nama_jabatan}} <br>
                                    @endif
                                </td>
                            <tr>
                                <th>Kontrak Kerja</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    @if($karyawan->kontrak_kerja=='SP') CV. SUMBER PANGAN @elseif($karyawan->kontrak_kerja=='SPS') PT. SURYA PANGAN SEMESTA @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Penempatan Kerja</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    {{$karyawan->penempatan_kerja}}
                                </td>
                            </tr>
                        </table>
                        @else
                        <table>
                            <tr>
                                <th>Nama</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>{{$karyawan->fullname}}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    Karyawan Harian
                                </td>
                            <tr>
                                <th>Penempatan Kerja</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    {{$karyawan->penempatan_kerja}}
                                </td>
                            </tr>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header">Mapping Shift</h4>
                <!-- Account -->
                <div class="card-body">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal_tambah_shift"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah&nbsp;Shift</button>
                    <div class="modal fade" id="modal_tambah_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable ">
                            <form method="post" action="{{ url('/karyawan/shift/proses-tambah-shift/'.$holding) }}" class=" modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-control @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id">
                                                <option value="">-- Pilih Shift --</option>
                                                @foreach ($shift as $s)
                                                @if(old('shift_id') == $s->id)
                                                <option value=" {{ $s->id }}" selected>{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @else
                                                <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <label for="shift_id">Shift</label>
                                        </div>
                                        @error('nik')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                            <label for="tanggal_mulai">Tanggal Mulai</label>
                                        </div>
                                        @error('tanggal_mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                                            <label for="tanggal_akhir">Tanggal Akhir</label>
                                        </div>
                                        @error('tanggal_akhir')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <input type="hidden" name="tanggal">
                                        <input type="hidden" name="status_absen">
                                        <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
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
                    <!-- modal edit -->
                    <div class="modal fade" id="modal_edit_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable ">
                            <form method="post" action="{{ url('/karyawan/proses-edit-shift/'.$holding) }}" class=" modal-content" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <input type="hidden" name="id_shift" id="id_shift" value="">
                                        <input type="hidden" name="user_id" id="user_id" value="">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-control selectpicker @error('shift_id_update') is-invalid @enderror" id="shift_id_update" name="shift_id_update" data-live-search="true">
                                                @foreach ($shift as $s)
                                                <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @endforeach
                                            </select>
                                            <label for="shift_id_update">Shift</label>
                                        </div>
                                        @error('shift_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control @error('tanggal_update') is-invalid @enderror" id="tanggal_update" name="tanggal_update" value="{{ old('tanggal_update') }}">
                                            <label for="tanggal_update">Tanggal</label>
                                        </div>
                                        @error('tanggal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
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
                    <table class="table" id="table_mapping_shift" style="width:100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Shift&nbsp;Karyawan</th>
                                <th>Tanggal&nbsp;Masuk</th>
                                <th>Jam&nbsp;Masuk</th>
                                <th>Tanggal&nbsp;Pulang</th>
                                <th>Jam&nbsp;Keluar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
    let holding = window.location.pathname.split("/");
    console.log(holding[3]);
    console.log(holding[4]);
    var table = $('#table_mapping_shift').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('karyawan/mapping_shift_datatable') }}" + '/' + holding[3] + '/' + holding[4],
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_shift',
                name: 'nama_shift'
            },
            {
                data: 'tanggal_masuk',
                name: 'tanggal_masuk'
            },
            {
                data: 'jam_masuk',
                name: 'jam_masuk'
            },
            {
                data: 'tanggal_pulang',
                name: 'tanggal_pulang'
            },
            {
                data: 'jam_keluar',
                name: 'jam_keluar'
            },
            {
                data: 'option',
                name: 'option'
            },

        ],
        order: [
            [2, 'DESC'],
            [0, 'DESC']
        ]
    });
</script>
<script>
    $(document).on("click", "#btn_edit_mapping_shift", function() {
        let id = $(this).data('id');
        let user_id = $(this).data('userid');
        let tanggal = $(this).data("tanggal");
        let shift = $(this).data("shift");
        let holding = $(this).data("holding");
        console.log(tanggal);
        $('#id_shift').val(id);
        $('#tanggal_update').val(tanggal);
        $('#user_id').val(user_id);
        $('#shift_id_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == shift
        }).prop('selected', true)
    });
    $(document).on('click', '#btn_delete_mapping_shift', function() {
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
                    url: "{{ url('/karyawan/delete-shift') }}" + '/' + id + '/' + holding,
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
                        $('#table_mapping_shift').DataTable().ajax.reload();
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