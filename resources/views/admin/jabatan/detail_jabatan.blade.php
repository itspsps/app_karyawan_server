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
                        <h5 class="card-title m-0 me-2"><a href="@if(Auth::user()->is_admin =='hrd'){{url('hrd/jabatan/'.$holding->holding_code)}}@else{{url('jabatan/'.$holding->holding_code)}}@endif"><i class="mdi mdi-arrow-left-bold"></i></a>&nbsp;DAFTAR JABATAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <table>
                            <tr>
                                <th>Departemen</th>
                                <td>&nbsp;</td>
                                <th>:</th>
                                @if($divisi==NULL)
                                <th></th>
                                @else
                                <th>{{$divisi->Departemen->nama_departemen}}</th>
                                @endif
                            </tr>
                            <tr>
                                <th>Divisi</th>
                                <th>&nbsp;</th>
                                <th>:</th>
                                @if($divisi==NULL)
                                <th></th>
                                @else
                                <th>{{$divisi->nama_divisi}}</th>
                                @endif
                            </tr>
                        </table>
                    </div>
                    <hr class="my-5">
                    <button type="button" id="btn_add_jabatan" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_jabatan"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>

                    <div class="modal fade" id="modal_karyawan_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class=" modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle"> Daftar Karyawan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-lg-12">
                                        <table class="table" id="table_karyawan_jabatan" style="width: 100%; font-size: small;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama&nbsp;Karyawan</th>
                                                    <th>Jabatan</th>
                                                    <th>Bagian</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
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
                    <div class="modal fade" id="modal_bawahan_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class=" modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle"> Jabatan Bawahan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-lg-12">
                                        <table class="table" id="table_bawahan_jabatan" style="width: 100%; font-size: small;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama&nbsp;Jabatan</th>
                                                    <th>Bagian</th>
                                                    <th>Jabatan&nbsp;Atasan</th>
                                                    <th>Jumlah&nbsp;Karyawan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
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
                    <div class="modal fade" id="modal_tambah_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form id="form_add_jabatan" method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('/hrd/jabatan/insert/'.$holding->holding_code) }}@else{{ url('/jabatan/insert/'.$holding->holding_code) }}@endif" class=" modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Jabatan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <input type="hidden" id="nama_departemen" name="nama_departemen" value="{{$divisi->Departemen->id}}">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control" name="nama_divisi" id="nama_divisi" value="{{$divisi->nama_divisi}}" readonly>
                                                <label for="nama_divisi" class="float-left">Nama Divisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_bagian') is-invalid @enderror" id="nama_bagian" name="nama_bagian" autofocus value="{{ old('nama_bagian') }}">
                                                    <option value=""> Pilih Bagian</option>
                                                    @foreach($data_bagian as $bagian)
                                                    <option value="{{$bagian->id}}">{{$bagian->nama_bagian}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_bagian" class="float-left">Nama Bagian</label>
                                            </div>
                                            @error('nama_bagian')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <?php

                                            use App\Models\Jabatan;
                                            use Illuminate\Database\Eloquent\Model;
                                            use Illuminate\Support\Facades\App;

                                            $get_jabatan = array(
                                                [
                                                    "nama" => "DIREKTUR UTAMA"
                                                ],
                                                [
                                                    "nama" => "DIREKTUR KEUANGAN"
                                                ],
                                                [
                                                    "nama" => "DIREKTUR OPERASIONAL"
                                                ],
                                                [
                                                    "nama" => "DIREKTUR SALES DAN MARKETING"
                                                ],
                                                [
                                                    "nama" => "HEAD"
                                                ],
                                                [
                                                    "nama" => "NATIONAL SALES MANAGER"
                                                ],
                                                [
                                                    "nama" => "MANAGER"
                                                ],
                                                [
                                                    "nama" => "REGIONAL SALES MANAGER"
                                                ],
                                                [
                                                    "nama" => "AREA SALES MANAGER"
                                                ],
                                                [
                                                    "nama" => "JUNIOR MANAGER"
                                                ],
                                                [
                                                    "nama" => "SUPERVISOR"
                                                ],
                                                [
                                                    "nama" => "KOORDINATOR"
                                                ],
                                                [
                                                    "nama" => "OPERATOR"
                                                ],
                                                [
                                                    "nama" => "STAFF"
                                                ],
                                                [
                                                    "nama" => "ADMIN"
                                                ],
                                                [
                                                    "nama" => "SALES"
                                                ],
                                                [
                                                    "nama" => "SOPIR"
                                                ],
                                                [
                                                    "nama" => "KERNET"
                                                ],
                                            );
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_jabatan') is-invalid @enderror" id="nama_jabatan" name="nama_jabatan">
                                                    <option selected disabled value="">Nama Jabatan</option>
                                                    @foreach($get_jabatan as $jabatan)
                                                    <option value="{{$jabatan['nama']}}"> {{$jabatan['nama']}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_jabatan" class="float-left">Nama Jabatan</label>
                                            </div>
                                            @error('nama_jabatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="lintas_departemen" name="lintas_departemen">
                                                <label class="form-check-label" for="lintas_departemen">Lintas Departemen</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col mb-2">
                                        <div class="form-floating form-floating-outline">

                                            <select class="form-control @error('nama_jabatan_atasan') is-invalid @enderror" id="nama_jabatan_atasan" name="nama_jabatan_atasan" autofocus value="{{ old('nama_jabatan_atasan') }}">
                                                <option value=""> Pilih Jabatan Atasan</option>
                                            </select>
                                            <label for="nama_jabatan_atasan" class="float-left">Nama Jabatan Atasan</label>
                                        </div>
                                        @error('nama_jabatan_atasan')
                                        <p class="alert alert-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <input type="hidden" class="form-control @error('level_jabatan') is-invalid @enderror" id="level_jabatan" name="level_jabatan" readonly value="{{ old('level_jabatan') }}">
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
                    <div class="modal fade" id="modal_edit_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('/hrd/jabatan/update/'.$holding->holding_code) }}@else{{ url('/jabatan/update/'.$holding->holding_code) }}@endif" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Jabatan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="nama_departemen_update" name="nama_departemen_update" value="">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input class="form-control @error('nama_divisi_update') is-invalid @enderror" readonly id="nama_divisi_update" name="nama_divisi_update" value="{{ old('nama_divisi_update') }}">
                                                <label for="nama_divisi_update" class="float-left">Nama Divisi</label>
                                            </div>
                                            @error('nama_divisi_update')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_bagian_update') is-invalid @enderror" id="nama_bagian_update" name="nama_bagian_update" autofocus value="{{ old('nama_bagian_update') }}">
                                                    <option value=""> Pilih Bagian</option>
                                                    @foreach($data_bagian as $s)
                                                    <option value="{{$s->id}}">{{$s->nama_bagian}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_bagian_update" class="float-left">Nama Bagian</label>
                                            </div>
                                            @error('nama_bagian_update')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <?php $get_jabatan = array(
                                            [
                                                "nama" => "DIREKTUR UTAMA"
                                            ],
                                            [
                                                "nama" => "DIREKTUR KEUANGAN"
                                            ],
                                            [
                                                "nama" => "DIREKTUR OPERASIONAL"
                                            ],
                                            [
                                                "nama" => "DIREKTUR SALES DAN MARKETING"
                                            ],
                                            [
                                                "nama" => "HEAD"
                                            ],
                                            [
                                                "nama" => "NATIONAL SALES MANAGER"
                                            ],
                                            [
                                                "nama" => "MANAGER"
                                            ],
                                            [
                                                "nama" => "REGIONAL SALES MANAGER"
                                            ],
                                            [
                                                "nama" => "JUNIOR MANAGER"
                                            ],
                                            [
                                                "nama" => "AREA SALES MANAGER"
                                            ],
                                            [
                                                "nama" => "SUPERVISOR"
                                            ],
                                            [
                                                "nama" => "KOORDINATOR"
                                            ],
                                            [
                                                "nama" => "OPERATOR"
                                            ],
                                            [
                                                "nama" => "STAFF"
                                            ],
                                            [
                                                "nama" => "ADMIN"
                                            ],
                                            [
                                                "nama" => "ASM"
                                            ],
                                            [
                                                "nama" => "SALES"
                                            ],
                                            [
                                                "nama" => "SOPIR"
                                            ],
                                            [
                                                "nama" => "KERNET"
                                            ],
                                        );
                                        ?>
                                        <div class="col mb-2">
                                            <input type="hidden" name="id_jabatan" id="id_jabatan" value="">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_jabatan_update') is-invalid @enderror" id="nama_jabatan_update" name="nama_jabatan_update" autofocus value="{{ old('nama_jabatan_update') }}">
                                                    <option value=""> Pilih Jabatan</option>
                                                    @foreach($get_jabatan as $s)
                                                    <option value="{{$s['nama']}}">{{$s['nama']}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_jabatan_update" class="float-left">Nama Jabatan</label>
                                            </div>
                                            @error('nama_jabatan_update')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="lintas_departemen_update" name="lintas_departemen_update">
                                                <label class="form-check-label" for="lintas_departemen_update">Lintas Departemen</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col mb-2">
                                        <div class="form-floating form-floating-outline">

                                            <select class="form-control @error('nama_jabatan_atasan_update') is-invalid @enderror" id="nama_jabatan_atasan_update" name="nama_jabatan_atasan_update" autofocus value="{{ old('nama_jabatan_atasan_update') }}">
                                                <option value=""> Pilih Jabatan Atasan</option>
                                            </select>
                                            <label for="nama_jabatan_atasan_update" class="float-left">Nama Jabatan Atasan</label>
                                        </div>
                                        @error('nama_jabatan_atasan_update')
                                        <p class="alert alert-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" class="form-control @error('level_jabatan_update') is-invalid @enderror" id="level_jabatan_update" name="level_jabatan_update" readonly value="{{ old('level_jabatan_update') }}">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table" id="table_jabatan" style="width: 100%; font-size: small;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama&nbsp;Jabatan</th>
                                <th>Bagian</th>
                                <th>Jabatan&nbsp;Atasan</th>
                                <th>Jumlah&nbsp;Bawahan</th>
                                <th>Jumlah&nbsp;Karyawan</th>
                                <th>Lintas&nbsp;Departemen</th>
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
    let holding = '{{$holding->holding_code}}';
    var id = '{{$divisi->id}}';
    console.log(id);
    var table = $('#table_jabatan').DataTable({
        pageLength: 50,
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/jabatan-datatable') }}@else{{ url('jabatan-datatable') }}@endif" + '/' + id + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_jabatan',
                name: 'nama_jabatan'
            },
            {
                data: 'nama_bagian',
                name: 'nama_bagian'
            },
            {
                data: 'jabatan_atasan',
                name: 'jabatan_atasan'
            },
            {
                data: 'jumlah_bawahan',
                name: 'jumlah_bawahan'
            },
            {
                data: 'jumlah_karyawan',
                name: 'jumlah_karyawan'
            },
            {
                data: 'lintas_departemen',
                name: 'lintas_departemen'
            },
            {
                data: 'option',
                name: 'option'
            },
        ],
        order: [
            [1, 'asc'],
            [2, 'asc'],
            [4, 'asc'],
        ],
    });
</script>
<script>
    $('#nama_jabatan').on('change', function() {
        let id = $(this).val();
        if (id == 'DIREKTUR UTAMA') {
            $('#level_jabatan').val('0');
        } else if (id == 'DIREKTUR KEUANGAN') {
            $('#level_jabatan').val('0');
        } else if (id == 'DIREKTUR OPERASIONAL') {
            $('#level_jabatan').val('0');
        } else if (id == 'DIREKTUR SALES DAN MARKETING') {
            $('#level_jabatan').val('0');
        } else if (id == 'HEAD') {
            $('#level_jabatan').val('1');
        } else if (id == 'NATIONAL SALES MANAGER') {
            $('#level_jabatan').val('1');
        } else if (id == 'MANAGER') {
            $('#level_jabatan').val('2');
        } else if (id == 'REGIONAL SALES MANAGER') {
            $('#level_jabatan').val('2');
        } else if (id == 'JUNIOR MANAGER') {
            $('#level_jabatan').val('3');
        } else if (id == 'AREA SALES MANAGER') {
            $('#level_jabatan').val('3');
        } else if (id == 'SUPERVISOR') {
            $('#level_jabatan').val('4');
        } else if (id == 'KOORDINATOR') {
            $('#level_jabatan').val('5');
        } else if (id == 'ASM') {
            $('#level_jabatan').val('5');
        } else if (id == 'ADMIN') {
            $('#level_jabatan').val('6');
        } else if (id == 'SALES') {
            $('#level_jabatan').val('6');
        } else if (id == 'STAFF') {
            $('#level_jabatan').val('6');
        } else if (id == 'OPERATOR') {
            $('#level_jabatan').val('6');
        } else if (id == 'SOPIR') {
            $('#level_jabatan').val('6');
        } else if (id == 'KERNET') {
            $('#level_jabatan').val('6');
        } else {
            $('#level_jabatan').val('7');
        }
        let divisi = $('#nama_divisi').val();
        let level = $('#level_jabatan').val();
        let holding = '{{$holding}}';
        let url = "@if(Auth::user()->is_admin =='hrd'){{url('hrd/atasan/get_jabatan')}}@else{{url('atasan/get_jabatan')}}@endif" + "/" + holding;
        console.log(divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                holding: holding,
                level: level,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#nama_jabatan_atasan').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#nama_jabatan_update').on('change', function() {
        let id = $(this).val();
        if (id == 'DIREKTUR UTAMA') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'DIREKTUR KEUANGAN') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'DIREKTUR OPERASIONAL') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'DIREKTUR SALES DAN MARKETING') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'HEAD') {
            $('#level_jabatan_update').val('1');
        } else if (id == 'NATIONAL SALES MANAGER') {
            $('#level_jabatan_update').val('1');
        } else if (id == 'MANAGER') {
            $('#level_jabatan_update').val('2');
        } else if (id == 'REGIONAL SALES MANAGER') {
            $('#level_jabatan_update').val('2');
        } else if (id == 'AREA SALES MANAGER') {
            $('#level_jabatan_update').val('3');
        } else if (id == 'JUNIOR MANAGER') {
            $('#level_jabatan_update').val('3');
        } else if (id == 'SUPERVISOR') {
            $('#level_jabatan_update').val('4');
        } else if (id == 'KOORDINATOR') {
            $('#level_jabatan_update').val('5');
        } else if (id == 'ASM') {
            $('#level_jabatan_update').val('5');
        } else if (id == 'ADMIN') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'SALES') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'STAFF') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'OPERATOR') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'SOPIR') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'KERNET') {
            $('#level_jabatan_update').val('6');
        } else {
            $('#level_jabatan_update').val('7');
        }

        let divisi = $('#nama_divisi_update').val();
        let level = $('#level_jabatan_update').val();
        let holding = '{{$holding}}';
        let url = "@if(Auth::user()->is_admin =='hrd'){{url('hrd/atasan/edit/get_jabatan')}}@else{{url('atasan/edit/get_jabatan')}}@endif" + "/" + holding;
        console.log(divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                holding: holding,
                level: level,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#nama_jabatan_atasan_update').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $(document).on("click", "#btn_edit_jabatan", function() {
        let id = $(this).data('id');
        let bagian = $(this).data("bagian");
        let divisi = $(this).data("divisi");
        let departemen = $(this).data("departemen");
        let level = $(this).data("level");
        $level = $(this).data("level");
        let jabatan = $(this).data("jabatan");
        let lintas = $(this).data("lintas");
        let atasan = $(this).data("atasan");
        let holding = $(this).data("holding");
        // console.log(bagian);
        $('#id_jabatan').val(id);
        $('#nama_departemen_update').val(departemen);
        $('#nama_divisi_update').val(divisi);
        $('#nama_bagian_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == bagian
        }).prop('selected', true)
        if (lintas == 'on') {
            $('#lintas_departemen_update').prop('checked', true)
        } else {
            $('#lintas_departemen_update').prop('checked', false)
        }
        let url = "@if(Auth::user()->is_admin =='hrd'){{url('hrd/atasan/edit/get_jabatan')}}@else{{url('atasan/edit/get_jabatan')}}@endif" + "/" + holding;
        console.log(divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                atasan: atasan,
                bagian: bagian,
                holding: holding,
                level: level,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#nama_jabatan_atasan_update').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        $('#level_jabatan_update').val(level);
        $('#nama_jabatan_update').val(jabatan);
        $('#nama_jabatan_atasan_update').val(atasan);
        $('#modal_edit_jabatan').modal('show');

    });
    $(document).on('click', '#btn_add_jabatan', function() {
        $('#form_add_jabatan').trigger('reset');
        $('#modal_tambah_jabatan').modal('show');
    });
    $(document).on('click', '#btn_lihat_bawahan', function() {

        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/bawahanjabatan-datatable') }}@else{{ url('bawahanjabatan-datatable') }}@endif" + '/' + id + '/' + holding;
        // console.log(url);
        var table1 = $('#table_bawahan_jabatan').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            retrieve: true,
            ajax: {
                url: url,
            },
            columns: [{
                    data: "id",

                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
                {
                    data: 'jabatan_atasan',
                    name: 'jabatan_atasan'
                },
                {
                    data: 'jumlah_karyawan',
                    name: 'jumlah_karyawan'
                },
            ],
            order: [
                [1, 'asc'],
                [2, 'asc'],
                [4, 'asc'],
            ],
        });
        $('#modal_bawahan_jabatan').modal('show');
        $('#modal_bawahan_jabatan').on('hidden.bs.modal', function(e) {
            table1.destroy();
            $('#table_jabatan').DataTable().ajax.reload();
        })
    });
    $(document).on('click', '#btn_lihat_karyawan', function() {

        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let url = "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/karyawanjabatan-datatable') }}@else{{ url('karyawanjabatan-datatable') }}@endif" + '/' + id + '/' + holding;
        console.log(id);
        var table1 = $('#table_karyawan_jabatan').DataTable({
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            retrieve: true,
            ajax: {
                url: url,
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
                    data: 'nama_jabatan',
                    name: 'nama_jabatan'
                },
                {
                    data: 'nama_bagian',
                    name: 'nama_bagian'
                },
            ],
            order: [
                [1, 'asc'],
                [2, 'asc'],
                [3, 'asc'],
            ],
        });
        $('#modal_karyawan_jabatan').modal('show');
        $('#modal_karyawan_jabatan').on('hidden.bs.modal', function(e) {
            table1.destroy();
            $('#table_jabatan').DataTable().ajax.reload();
        })
    });
    $(document).on('click', '#btn_delete_jabatan', function() {
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
                    url: "@if(Auth::user()->is_admin =='hrd'){{ url('/hrd/jabatan/delete/') }}@else{{ url('/jabatan/delete/') }}@endif" + '/' + id + '/' + holding,
                    type: "GET",
                    data: {
                        holding: holding,
                    },
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        console.log(data.status);
                        if (data.status == 1) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data anda berhasil di hapus.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_jabatan').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Jumlah Karyawan Tidak 0',
                                icon: 'error',
                                timer: 1500
                            })
                            $('#table_jabatan').DataTable().ajax.reload();
                        }
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