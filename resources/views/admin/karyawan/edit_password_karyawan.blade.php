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
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> UBAH PASSWORD</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header"><a href="{{url('karyawan/'.$holding)}}"><i class="mdi mdi-arrow-left-bold"></i></a>&nbsp;Profil Karyawan</h4>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if($karyawan->foto_karyawan == null)
                        <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                        @else
                        <img src="https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_karyawan/{{$karyawan->foto_karyawan}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                        @endif
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
                                    @if($karyawan->kontrak_kerja=='sp') CV. SUMBER PANGAN @elseif($karyawan->kontrak_kerja=='sps') PT. SURYA PANGAN SEMESTA @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    {{$karyawan->username}}
                                </td>
                            </tr>
                            <tr>
                                <th>Password Baru</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    <form method="post" action="{{ url('/karyawan/edit-password-proses/'.$karyawan->id.'/'.$holding) }}">
                                        @method('put')
                                        @csrf
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan Password Baru">
                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <label for="password"> Password Baru</label>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light mt-2"><i class=" mdi mdi-key-outline"></i>&nbsp;Ubah</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
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
    // console.log(holding[4]);
    var table = $('#table_mapping_shift').DataTable({
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
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'nama_shift',
                name: 'nama_shift'
            },
            {
                data: 'jam_masuk',
                name: 'jam_masuk'
            },
            {
                data: 'jam_keluar',
                name: 'jam_keluar'
            },
            {
                data: 'option',
                name: 'option'
            },
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