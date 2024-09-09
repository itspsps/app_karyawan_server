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
                        <h5 class="card-title m-0 me-2">DATA MASTER JABATAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_jabatan"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <div class="modal fade" id="modal_tambah_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/jabatan/insert/'.$holding) }}" class=" modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Jabatan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_divisi') is-invalid @enderror" id="nama_divisi" name="nama_divisi" autofocus value="{{ old('nama_divisi') }}">
                                                    <option value=""> Pilih Divisi</option>
                                                    @foreach($data_divisi as $s)
                                                    <option value="{{$s->id}}">{{$s->nama_divisi}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="nama_divisi" class="float-left">Nama Divisi</label>
                                            </div>
                                            @error('nama_divisi')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_bagian') is-invalid @enderror" id="nama_bagian" name="nama_bagian" autofocus value="{{ old('nama_bagian') }}">
                                                    <option value=""> Pilih Bagian</option>
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
                                    <br>
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
                            <form method="post" action="{{ url('/jabatan/update/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Jabatan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select class="form-control @error('nama_divisi_update') is-invalid @enderror" id="nama_divisi_update" name="nama_divisi_update" autofocus value="{{ old('nama_divisi_update') }}">
                                                    <option value=""> Pilih Divisi</option>
                                                    @foreach($data_divisi as $s)
                                                    <option value="{{$s->id}}">{{$s->nama_divisi}}</option>
                                                    @endforeach
                                                </select>
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
                    <table class="table" id="table_jabatan" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Divisi</th>
                                <th>Bagian</th>
                                <th>Nama&nbsp;Jabatan</th>
                                <th>Level</th>
                                <th>Jabatan&nbsp;Atasan</th>
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
    var table = $('#table_jabatan').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('jabatan-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_divisi',
                name: 'nama_divisi'
            },
            {
                data: 'nama_bagian',
                name: 'nama_bagian'
            },
            {
                data: 'nama_jabatan',
                name: 'nama_jabatan'
            },
            {
                data: 'level_jabatan',
                name: 'level_jabatan'
            },
            {
                data: 'jabatan_atasan',
                name: 'jabatan_atasan'
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
    $('#nama_divisi').on('change', function() {
        let id_divisi = $(this).val();
        let url = "{{url('/jabatan/get_bagian')}}" + "/" + id_divisi;
        // console.log(id_divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_divisi: id_divisi
            // },
            success: function(response) {
                // console.log(response);
                $('#nama_bagian').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#nama_jabatan').on('change', function() {
        let id = $(this).val();
        if (id == 'DIREKTUR UTAMA') {
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
    })
    $('#nama_jabatan_update').on('change', function() {
        let id = $(this).val();
        if (id == 'DIREKTUR UTAMA') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'HEAD') {
            $('#level_jabatan_update').val('1');
        } else if (id == 'NATIONAL SALES MANAGER') {
            $('#level_jabatan').val('1');
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
        let url = "{{url('atasan/get_jabatan')}}" + "/" + holding;
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
        let level = $(this).data("level");
        $level = $(this).data("level");
        let jabatan = $(this).data("jabatan");
        let atasan = $(this).data("atasan");
        let holding = $(this).data("holding");
        console.log($level);
        $('#id_jabatan').val(id);
        $('#nama_divisi_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == divisi
        }).prop('selected', true)
        $('#nama_bagian_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == bagian
        }).prop('selected', true)
        let url = "{{url('atasan/edit/get_jabatan')}}" + "/" + holding;
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
                    url: "{{ url('/jabatan/delete/') }}" + '/' + id + '/' + holding,
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
                        $('#table_jabatan').DataTable().ajax.reload();
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