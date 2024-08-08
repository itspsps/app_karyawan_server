@extends('layouts.dashboard')
@section('isi')
    @php
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;
        $radius = $lokasi_kantor->radius;
    @endphp
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
               <h1 class="h3 mb-2 text-gray-800">{{ $lat }}, {{ $long }}</h1>
           </div>
            <div class="card-body">
               <div id="map" style="width:100%;height:600px;"></div>
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
               </script>

            </div>
        </div>
        <br><br>
    </div>
@endsection
