@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />

<style type="text/css">
    .my-swal {
        z-index: X;
    }



    .leaflet-container {
        height: 400px;
        width: 600px;
        max-width: 100%;
        max-height: 100%;
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
                        <h5 class="card-title">TAMBAH LOKASI</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <hr class="my-5">
                        <form method="post" action="{{ url('/lokasi-kantor/add/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-2 p-3 gy-4">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select name="lokasi_kantor" id="lokasi_kantor" class="form-control  @error('lokasi_kantor') is-invalid @enderror">
                                            <option value="">Pilih Lokasi</option>
                                            @foreach ($lokasi_kantor as $g)
                                            @if(old('lokasi_kantor') == $g["lokasi_kantor"])
                                            <option value="{{ $g['lokasi_kantor'] }}" selected>{{ $g["lokasi_kantor"] }}</option>
                                            @else
                                            <option value="{{ $g['lokasi_kantor'] }}">{{ $g["lokasi_kantor"] }}</option>
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
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control @error('nama_titik') is-invalid @enderror" id="nama_titik" name="nama_titik" value="">
                                        <label for="nama_titik">Nama Titik</label>
                                    </div>
                                    @error('nama_titik')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-2 p-3 gy-4">
                                <input type="hidden" class="form-control" id="lat_titik" name="lat_titik" value="">
                                <input type="hidden" class="form-control" id="long_titik" name="long_titik" value="">
                                <!-- <div class="col mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <label for="lat_titik">Latitude Kantor</label>
                                    </div>
                                    @error('lat_titik')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <label for="long_titik">Longitude Kantor</label>
                                    </div>
                                    @error('long_titik')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div> -->
                                <!-- <button type="button" id="btn_lokasi_saya" class="btn btn-icon btn-outline-success waves-effect" title="Lokasi Saya">
                                    <span class="tf-icons mdi mdi-map-marker"></span>
                                </button>
                                <button id="btn_refresh_lokasi" type="button" id="btn_lokasi_saya" class="btn btn-icon btn-outline-primary waves-effect" title="Refresh">
                                    <span class="tf-icons mdi mdi-refresh"></span>
                                </button> -->
                                <!-- <br>
                                <br> -->
                                <div class="col-md-3">
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
                                <div class="col mb-6">
                                    <div id="tambah_lokasi"></div>
                                </div>
                            </div>
                            <div class="row mt-2 p-3 gy-9">
                                <div class="col mb-3">
                                    <a type="button" href="{{url('lokasi-kantor/'.$holding)}}" class="btn btn-outline-secondary">
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
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
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>


<script>
    let holding = window.location.pathname.split("/").pop();

    $(function() {
        $(document).on('change', '#lokasi_kantor', function() {
            let value = $(this).val();
            let holding = '{{$holding}}';
            // console.log(holding);
            $.ajax({
                type: 'GET',
                url: "{{url('lokasi_kantor/get_lokasi')}}",
                data: {
                    holding: holding,
                    value: value
                },
                cache: false,

                success: function(response) {
                    var result = JSON.parse(response);
                    console.log(result);
                    if (result != null) {
                        $('#lat_titik').val(result.lat_kantor);
                        $('#long_titik').val(result.long_kantor);
                        $('#tambah_lokasi').show();
                        maps_lokasi();
                    } else {
                        $('#tambah_lokasi').hide();
                    }
                },
                error: function(data) {
                    console.log('error:', data)
                },

            });
        });
        var typingTimer; //timer identifier
        var doneTypingInterval = 2000;
        $(document).on('keyup', '#radius', function() {
            let value = $(this).val();
            let holding = '{{$holding}}';
            console.log(value);
            clearTimeout(typingTimer);
            typingTimer = setTimeout(maps_lokasi, doneTypingInterval);

        });
    })
</script>
<script>
    var map = null;

    function maps_lokasi() {
        if (map) {
            map.off();
            map.remove();
        }
        var long_titik = $("#long_titik").val();
        var lat_titik = $("#lat_titik").val()
        var lokasi_kantor = $("#lokasi_kantor").val()
        // console.log(lokasi_kantor);
        map = L.map('tambah_lokasi').fitWorld().setView([lat_titik, long_titik], 17);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 25,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);


        var popup = L.popup()
            .setLatLng([lat_titik, long_titik])
            .setContent(lokasi_kantor)
            .openOn(map);

        if (lokasi_kantor == 'CV. SUMBER PANGAN - KEDIRI') {
            var latlngs = [
                [-7.757852, 112.093890],
                [-7.756964, 112.094195],
                [-7.757866, 112.096507],
                [-7.758657, 112.095336]
            ];
        } else {
            var latlngs = [
                [-6.991185, 112.120763],
                [-6.989174, 112.121394],
                [-6.989563, 112.122751],
                [-6.991437, 112.122061]
            ];

        }
        var polygon = L.polygon(latlngs, {
            color: 'red'
        }).addTo(map);

        // console.log(circle);

        // console.log(marker);
        // function onMapClick(e) {
        // }
        // map.on('click', onMapClick);
        var marker = null;
        var circle = null;
        map.on('click', function(e) {
            popup
                .setLatLng(e.latlng)
                .setContent('You clicked the map at ' + e.latlng.toString())
                .openOn(map);
            let latitude = e.latlng.lat.toString().substring(0, 15);
            let longitude = e.latlng.lng.toString().substring(0, 15);
            document.querySelector("#long_titik").value = longitude;
            document.querySelector("#lat_titik").value = latitude;
            // map.removeLayer(circle1);
            // map.removeLayer(circle);
            console.log(circle);
            if (marker !== null) {
                map.removeLayer(marker);
            }
            marker = L.marker([latitude, longitude]).addTo(map)
                .bindPopup(e.latlng.toString()).openPopup();
            if (circle !== null) {
                map.removeLayer(circle);
            }
            var radius = $("#radius").val()
            circle = L.circle([latitude, longitude], {
                color: 'purple',
                fillColor: 'purple',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
            // map.removeLayer(L.circle([latitude, longitude]));
        });
    }
</script>
@endsection