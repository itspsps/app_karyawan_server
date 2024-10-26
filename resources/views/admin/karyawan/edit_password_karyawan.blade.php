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
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> UBAH USER</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header"><a href="@if(Auth::user()->is_admin=='hrd'){{url('hrd/users/'.$holding)}}@else{{url('users/'.$holding)}}@endif"><i class="mdi mdi-arrow-left-bold"></i></a>&nbsp;Profil Karyawan</h4>
                <!-- Account -->
                <div class="card-body">
                    <form method="post" action="@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/users/edit-password-proses/'.$karyawan->id.'/'.$holding) }}@else{{ url('/users/edit-password-proses/'.$karyawan->id.'/'.$holding) }}@endif">
                        @csrf
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            @if($karyawan->foto_karyawan == null)
                            <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                            @else
                            <img src="https://hrd.sumberpangan.store:4430/storage/app/public/foto_karyawan/{{$karyawan->foto_karyawan}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                            @endif
                            <table>
                                <tr>
                                    <th>Nama</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>{{$karyawan->name}}</td>
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
                                        @if($karyawan->kontrak_kerja=='SP') CV. SUMBER PANGAN @elseif($karyawan->kontrak_kerja=='SPS') PT. SURYA PANGAN SEMESTA @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{$karyawan->username}}" id="username" name="username" placeholder="Username">
                                            <input type="hidden" name="username_old" id="username_old" value="{{$karyawan->username}}">
                                        </div>
                                    </td>
                                </tr>
                                <br>
                                <tr>
                                    <th>Password</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>
                                        <div class="form-floating form-floating-outline">
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" value="{{$karyawan->password_show}}" id="password" name="password" placeholder="Password">
                                                <span class="input-group-text" onclick="password_show_hide();">
                                                    <i class="mdi mdi-eye-off-outline d-none" id="hide_eye"></i>
                                                    <i class="mdi mdi-eye-outline" id="show_eye"></i>
                                                </span>
                                                @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light mt-2"><i class=" mdi mdi-key-outline"></i>&nbsp;Ubah</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
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
    let auth = '{{ Auth::user()->is_admin }}';
    if (auth == 'hrd') {
        var holding1 = holding[4];
        var holding2 = holding[5];
    } else {
        var holding1 = holding[3];
        var holding2 = holding[4];
    }
    var table = $('#table_mapping_shift').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/mapping_shift_datatable') }}@else{{ url('karyawan/mapping_shift_datatable') }}@endif" + '/' + holding1 + '/' + holding2,
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
    function password_show_hide() {
        var x = document.getElementById("password");
        var show_eye = document.getElementById("show_eye");
        var hide_eye = document.getElementById("hide_eye");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";

        }
    }
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
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/delete-shift') }}@else{{ url('/karyawan/delete-shift') }}@endif" + '/' + id + '/' + holding,
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