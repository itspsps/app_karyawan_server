@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="row">
        <div class="card card-outline card-primary col-lg-12">
            <div class="p-4">
                <button type="button" data-toggle="modal" data-target="#modal_tambah" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> Tambah Lokasi
                </button>
                <table id="tableprint" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Lokasi Kantor</th>
                            <th>Lat</th>
                            <th>Long</th>
                            <th>Radius Absensi</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_lokasi as $lokasi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td> {{ $lokasi->lokasi_kantor }}</td>
                            <td>{{ $lokasi->lat_kantor }}</td>
                            <td>{{ $lokasi->long_kantor }}</td>
                            <td>{{ $lokasi->radius }}</td>
                            <td>
                                <button id="btn_edit" class="btn btn-sm btn-info" data-toggle="modal" data-id="{{$lokasi->id}}" data-lokasi="{{$lokasi->lokasi_kantor}}" data-lat="{{$lokasi->lat_kantor}}" data-long="{{$lokasi->long_kantor}}" data-radius="{{$lokasi->radius}}" data-target="#modal_edit"><i class="fa fa-solid fa-edit"></i>
                                </button>
                                <button id="btn_delete" data-id="{{$lokasi->id}}" data-holding="{{$holding}}" class="btn btn-danger btn-sm btn-circle"><i class="fa fa-solid fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="modal_tambah" tabindex="-1" aria-labelledby="modal_tambahLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_tambahLabel">Tambah Lokasi Kantor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ url('/lokasi-kantor/add/'.$holding) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="col">
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
                                        <!-- {{$holding}} -->
                                        <label for="lokasi_kantor">Lokasi Kantor</label>
                                        <select name="lokasi_kantor" id="lokasi_kantor" class="selectpicker form-control  @error('lokasi_kantor') is-invalid @enderror" data-live-search="true">
                                            <option value="">Pilih Lokasi</option>
                                            @foreach ($lokasi_kantor as $g)
                                            @if(old('lokasi_kantor') == $g["lokasi"])
                                            <option value="{{ $g['lokasi'] }}" selected>{{ $g["lokasi"] }}</option>
                                            @else
                                            <option value="{{ $g['lokasi'] }}">{{ $g["lokasi"] }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('lokasi_kantor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="lat_kantor">Latitude Kantor</label>
                                        <input type="text" class="form-control @error('lat_kantor') is-invalid @enderror" id="lat_kantor" name="lat_kantor" autofocus value="{{ old('lat_kantor') }}">
                                        @error('lat_kantor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="long_kantor">Longitude Kantor</label>
                                        <input type="text" class="form-control @error('long_kantor') is-invalid @enderror" id="long_kantor" name="long_kantor" autofocus value="{{ old('long_kantor') }}">
                                        @error('long_kantor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <button id="btn_lokasi_saya" type="button" class="btn btn-sm btn-success"><i class="fa fa-map-marker-alt"></i>Lokasi Saya</button>
                                    <button id="btn_refresh_lokasi" type="button" class="btn btn-sm btn-info" title="Refresh"><i class="fa fa-retweet"></i></button>
                                    <div class="form-group">
                                        <label for="radius" class="float-left">Radius</label>
                                        <input type="text" class="form-control @error('radius') is-invalid @enderror" id="radius" name="radius" autofocus value="{{ old('radius') }}">
                                        @error('radius')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary float-right">save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="modal_editLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_editLabel">Edit Lokasi Kantor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ url('/lokasi-kantor/edit/'.$holding) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="col">
                                        <input type="hidden" name="id_lokasi" id="id_lokasi">
                                        <input type="hidden" name="kategori_kantor" id="kategori_kantor" value="{{$holding}}">
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
                                        <label for="lokasi_kantor">Lokasi Kantor</label>
                                        <select name="lokasi_kantor" id="lokasi_kantor_update" class="selectpicker form-control  @error('lokasi_kantor') is-invalid @enderror" data-live-search="true">
                                            @foreach ($lokasi_kantor as $g)
                                            <option value="{{ $g['lokasi'] }}">{{ $g["lokasi"] }}</option>
                                            @endforeach
                                        </select>
                                        @error('lokasi_kantor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="lat_kantor">Latitude Kantor</label>
                                        <input type="text" class="form-control @error('lat_kantor') is-invalid @enderror" id="lat_kantor_update" name="lat_kantor" autofocus value="{{ old('lat_kantor') }}">
                                        @error('lat_kantor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="long_kantor">Longitude Kantor</label>
                                        <input type="text" class="form-control @error('long_kantor') is-invalid @enderror" id="long_kantor_update" name="long_kantor" autofocus value="{{ old('long_kantor') }}">
                                        @error('long_kantor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <button id="btn_lokasi_saya" type="button" class="btn btn-sm btn-success"><i class="fa fa-map-marker-alt"></i>Lokasi Saya</button>
                                    <button id="btn_refresh_lokasi" type="button" class="btn btn-sm btn-info" title="Refresh"><i class="fa fa-retweet"></i></button>
                                    <div class="form-group">
                                        <label for="radius" class="float-left">Radius</label>
                                        <input type="text" class="form-control @error('radius') is-invalid @enderror" id="radius_update" name="radius" autofocus value="{{ old('radius') }}">
                                        @error('radius')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary float-right">save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<br>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
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
    $(document).on('click', '#btn_delete', function() {
        var cek = $(this).data('id');
        var holding = $(this).data('holding');
        // console.log(holding);
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu tidak dapat mengembalikan data ini",
            type: 'warning',
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