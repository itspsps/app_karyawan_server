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
                        <h5 class="card-title m-0 me-2">DATA MASTER HOLDING</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" id="btn_add_holding" class="btn btn-sm btn-primary waves-effect waves-light"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>

                    <!-- modal add Holding -->
                    <div class="modal fade" id="modal_tambah_holding" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/holding/store/'.$holding) }}@else {{ url('/holding/store/'.$holding) }} @endif" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Holding</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="nama_holding" name="nama_holding" class="form-control @error('nama_holding') is-invalid @enderror" placeholder="Masukkan Nama Holding" value="{{ old('nama_holding') }}" />
                                                <label for="nama_holding">Nama Holding</label>
                                            </div>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_masuk" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror" placeholder="Masukkan jam_masuk" value="{{ old('jam_masuk') }}" />
                                                <label for="jam_masuk">Jam Masuk</label>
                                            </div>
                                            @error('jam_masuk')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_kerja" name="jam_kerja" class="form-control @error('jam_kerja') is-invalid @enderror" placeholder="Masukkan jam_kerja" value="{{ old('jam_kerja') }}" />
                                                <label for="jam_kerja">Jam Kerja</label>
                                            </div>
                                            @error('jam_kerja')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="time" id="jam_keluar" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror" placeholder="Masukkan jam_keluar" value="{{ old('jam_keluar') }}" />
                                                <label for="jam_keluar">Jam Pulang</label>
                                            </div>
                                            @error('jam_keluar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
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
                    <!-- modal edit Holding-->
                    <div class="modal fade" id="modal_edit_holding" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/holding/update/'.$holding->holding_code) }}@else {{ url('/holding/update/'.$holding->holding_code) }} @endif" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Holding</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <input type="hidden" name="id_holding" id="id_holding" value="">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="nama_holding_update" name="nama_holding_update" class="form-control @error('nama_holding_update') is-invalid @enderror" placeholder="Masukkan Nama Holding" value="" />
                                                <label for="nama_holding_update">Nama Holding</label>
                                            </div>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="kategori_holding_update" name="kategori_holding_update" class="form-control @error('kategori_holding_update') is-invalid @enderror" placeholder="Masukkan Jam Masuk" value="" />
                                                <label for="kategori_holding_update">Kategori Holding</label>
                                            </div>
                                            @error('kategori_holding_update')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
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
                    <table class="table" id="table_holding" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Nama&nbsp;Holding</th>
                                <th>Kategori&nbsp;Holding</th>
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
    var table = $('#table_holding').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/holding-datatable') }}@else {{ url('holding-datatable') }}@endif" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'holding_name',
                name: 'holding_name'
            },
            {
                data: 'holding_category',
                name: 'holding_category'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
<script>
    $(document).on("click", "#btn_edit_holding", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        let kategori = $(this).data("category");
        // console.log(jamkeluar);
        $('#id_holding').val(id);
        $('#nama_holding_update').val(holding);
        $('#kategori_holding_update').val(kategori);
        $('#modal_edit_holding').modal('show');

    });
    $(document).on('click', '#btn_delete_holding', function() {
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
                    url: "@if(Auth::user()->is_admin =='hrd'){{ url('hrd/holding/delete/') }} @else {{ url('/holding/delete/') }}@endif" + '/' + id + '/' + holding,
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
                        $('#table_holding').DataTable().ajax.reload();
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