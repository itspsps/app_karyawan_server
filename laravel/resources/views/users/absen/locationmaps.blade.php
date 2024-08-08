@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
@php
$lat_kantor = $lokasi_kantor->lat_kantor;
$long_kantor = $lokasi_kantor->long_kantor;
$radius = $lokasi_kantor->radius;
@endphp
<!-- Features -->
<div class="">
    <div class="row m-b20 g-3">
        <div class="col-12">
            <a href="">
                <div class="card card-bx card-content bg-primary">
                    <div class="card-body">
                        <div class="info">
                            <span class="" style="color: white">{{ $lat }}, {{ $long }}</span>
                            {{-- <div id="map" style="width:100%;height:600px;"></div>
                            <script>
                                var map = L.map('map').setView([{{ $lat }}, {{ $long }}], 18);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                            }).addTo(map);
                            var marker = L.marker([{{ $lat }}, {{ $long }}]).addTo(map);
                            var circle = L.circle([{{ $lat_kantor }}, {{ $long_kantor }}], {
                            color: 'red',
                            fillColor: '#f03',
                            fillOpacity: 0.5,
                            radius: {{ $radius }}
                            }).addTo(map);

                            marker.bindPopup("<b>Lokasi Saya</b>").openPopup();
                            circle.bindPopup("<b>Radius RSGPI</b>");
                            </script> --}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection