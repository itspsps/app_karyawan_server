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
                        <h5 class="card-title">DATA MASTER LOKASI</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_tambah_lokasi"><i class="menu-icon tf-icons mdi mdi-plus"></i>Tambah</button>
                    <div class="modal fade" id="modal_tambah_lokasi" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/lokasi-kantor/add/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Tambah Lokasi</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <?php
                                            $holding = request()->segment(count(request()->segments()));
                                            if ($holding == 'sp') {
                                                $lokasi_kantor = array(
                                                    [
                                                        "lokasi" => "CV. SUMBER PANGAN - KEDIRI"
                                                    ],
                                                    [
                                                        "lokasi" => "CV. SUMBER PANGAN - TUBAN"
                                                    ],
                                                );
                                            } else if ($holding == 'sps') {
                                                $lokasi_kantor = array(
                                                    [
                                                        "lokasi" => "PT. SURYA PANGAN SEMESTA - KEDIRI"
                                                    ],
                                                    [
                                                        "lokasi" => "PT. SURYA PANGAN SEMESTA - NGAWI"
                                                    ],
                                                    [
                                                        "lokasi" => "PT. SURYA PANGAN SEMESTA - SUBANG"
                                                    ],
                                                    [
                                                        "lokasi" => "DEPO SPS SIDOARJO"
                                                    ]
                                                );
                                            } else {
                                                $lokasi_kantor = array(
                                                    [
                                                        "lokasi" => "CV. SURYA INTI PANGAN - MAKASAR"
                                                    ]
                                                );
                                            }
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select name="lokasi_kantor" id="lokasi_kantor" class="form-control  @error('lokasi_kantor') is-invalid @enderror">
                                                    <option value="">Pilih Lokasi</option>
                                                    @foreach ($lokasi_kantor as $g)
                                                    @if(old('lokasi_kantor') == $g["lokasi"])
                                                    <option value="{{ $g['lokasi'] }}" selected>{{ $g["lokasi"] }}</option>
                                                    @else
                                                    <option value="{{ $g['lokasi'] }}">{{ $g["lokasi"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="lokasi_kantor">Lokasi Kantor</label>
                                            </div>
                                            @error('lokasi_kantor')
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
                                                <input type="text" class="form-control @error('lat_kantor') is-invalid @enderror" id="lat_kantor" name="lat_kantor" value="{{ old('lat_kantor') }}">
                                                <label for="lat_kantor">Latitude Kantor</label>
                                            </div>
                                            @error('lat_kantor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('long_kantor') is-invalid @enderror" id="long_kantor" name="long_kantor" value="{{ old('long_kantor') }}">
                                                <label for="long_kantor">Longitude Kantor</label>
                                            </div>
                                            @error('long_kantor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="button" id="btn_lokasi_saya" class="btn btn-icon btn-outline-success waves-effect" title="Lokasi Saya">
                                        <span class="tf-icons mdi mdi-map-marker"></span>
                                    </button>
                                    <button id="btn_refresh_lokasi" type="button" id="btn_lokasi_saya" class="btn btn-icon btn-outline-primary waves-effect" title="Refresh">
                                        <span class="tf-icons mdi mdi-refresh"></span>
                                    </button>
                                    <br>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('radius') is-invalid @enderror" id="radius" name="radius" value="{{ old('radius') }}">
                                                <label for="radius" class="float-left">Radius</label>
                                            </div>
                                            @error('radius')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
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
                    <!-- modal edit -->
                    <div class="modal fade" id="modal_edit_lokasi" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form method="post" action="{{ url('/lokasi-kantor/edit/'.$holding) }}"" class=" modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Lokasi</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_lokasi" id="id_lokasi">
                                    <input type="hidden" name="kategori_kantor_update" id="kategori_kantor_update" value="{{$holding}}">
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <?php
                                            $holding = request()->segment(count(request()->segments()));
                                            if ($holding == 'sp') {
                                                $lokasi_kantor = array(
                                                    [
                                                        "lokasi" => "CV. SUMBER PANGAN - KEDIRI"
                                                    ],
                                                    [
                                                        "lokasi" => "CV. SUMBER PANGAN - TUBAN"
                                                    ],
                                                );
                                            } else if ($holding == 'sps') {
                                                $lokasi_kantor = array(
                                                    [
                                                        "lokasi" => "PT. SURYA PANGAN SEMESTA - KEDIRI"
                                                    ],
                                                    [
                                                        "lokasi" => "PT. SURYA PANGAN SEMESTA - NGAWI"
                                                    ],
                                                    [
                                                        "lokasi" => "PT. SURYA PANGAN SEMESTA - SUBANG"
                                                    ]
                                                );
                                            } else {
                                                $lokasi_kantor = array(
                                                    [
                                                        "lokasi" => "CV. SURYA INTI PANGAN - MAKASAR"
                                                    ]
                                                );
                                            }
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select name="lokasi_kantor_update" id="lokasi_kantor_update" class="form-control  @error('lokasi_kantor_update') is-invalid @enderror">
                                                    <option value="">Pilih Lokasi</option>
                                                    @foreach ($lokasi_kantor as $g)
                                                    <option value="{{ $g['lokasi'] }}">{{ $g["lokasi"] }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="lokasi_kantor_update">Lokasi Kantor</label>
                                            </div>
                                            @error('lokasi_kantor_update')
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
                                                <input type="text" class="form-control @error('lat_kantor_update') is-invalid @enderror" id="lat_kantor_update" name="lat_kantor_update" value="{{ old('lat_kantor_update') }}">
                                                <label for="lat_kantor_update">Latitude Kantor</label>
                                            </div>
                                            @error('lat_kantor_update')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('long_kantor_update') is-invalid @enderror" id="long_kantor_update" name="long_kantor_update" value="">
                                                <label for="long_kantor_update">Longitude Kantor</label>
                                            </div>
                                            @error('long_kantor_update')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="button" id="btn_lokasi_saya" class="btn btn-icon btn-outline-success waves-effect" title="Lokasi Saya">
                                        <span class="tf-icons mdi mdi-map-marker"></span>
                                    </button>
                                    <button id="btn_refresh_lokasi" type="button" id="btn_lokasi_saya" class="btn btn-icon btn-outline-primary waves-effect" title="Refresh">
                                        <span class="tf-icons mdi mdi-refresh"></span>
                                    </button>
                                    <br>
                                    <br>
                                    <div class="row g-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control @error('radius_update') is-invalid @enderror" id="radius_update" name="radius_update" value="">
                                                <label for="radius_update" class="float-left">Radius</label>
                                            </div>
                                            @error('radius_update')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
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
                    <table class="table" id="table_lokasi" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Lokasi&nbsp;Kantor</th>
                                <th>Lat&nbsp;Kantor</th>
                                <th>Long&nbsp;Kantor</th>
                                <th>Radius</th>
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
    var table = $('#table_lokasi').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('lokasi-datatable') }}" + '/' + holding,
        },
        columns: [{
                data: "id",

                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'lokasi_kantor',
                name: 'lokasi_kantor'
            },
            {
                data: 'lat_kantor',
                name: 'lat_kantor'
            },
            {
                data: 'long_kantor',
                name: 'long_kantor'
            },
            {
                data: 'radius',
                name: 'radius'
            },
            {
                data: 'option',
                name: 'option'
            },
        ]
    });
</script>
<script>
    $(document).on("click", "#btn_edit_lokasi", function() {
        let id = $(this).data('id');
        let lokasi = $(this).data("lokasi");
        let lat = $(this).data("lat");
        let long = $(this).data("long");
        let radius = $(this).data("radius");
        let holding = $(this).data("holding");
        // console.log(long);
        $('#id_lokasi').val(id);
        $('#lat_kantor_update').val(lat);
        $('#long_kantor_update').val(long);
        $('#radius_update').val(radius);
        $('#lokasi_kantor_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == lokasi
        }).prop('selected', true)
        $('#modal_edit_lokasi').modal('show');

    });
</script>
<script>
    $(document).on('click', '#btn_lokasi_saya', function() {
        getLocation();

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            $('#lat_kantor').val(position.coords.latitude);
            $('#long_kantor').val(position.coords.longitude);
            $('#lat_kantor_update').val(position.coords.latitude);
            $('#long_kantor_update').val(position.coords.longitude);
        }
    })
    $(document).on('click', '#btn_refresh_lokasi', function() {
        $('#lat_kantor').val('');
        $('#long_kantor').val('');
        $('#lat_kantor_update').val('');
        $('#long_kantor_update').val('');
    })
    $(document).on('click', '#btn_edit', function() {
        var id = $(this).data('id');
        var lokasi = $(this).data('lokasi');
        var lat = $(this).data('lat');
        var long = $(this).data('long');
        var radius = $(this).data('radius');
        $('#id_lokasi').val(id);
        $('#lokasi_kantor_update').val(lokasi);
        $('#lat_kantor_update').val(lat);
        $('#long_kantor_update').val(long);
        $('#radius_update').val(radius);
    });
    $(document).on('click', '#btn_delete_lokasi', function() {
        var cek = $(this).data('id');
        var holding = $(this).data('holding');
        // console.log(holding);
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
                    url: "{{url('lokasi-kantor/delete/')}}/" + cek + "/" + holding,
                    type: "GET",
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Data anda berhasil di hapus.',
                            type: 'success',
                            timer: 1500
                        })
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire("Cancelled", "Your data is safe :)", "error");
            }
        });

    });
</script>
@endsection