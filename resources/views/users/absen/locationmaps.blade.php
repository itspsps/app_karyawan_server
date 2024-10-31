@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
<style>
    #lokasi {
        height: 300px;
    }
</style>
@endsection
@section('content')
<div class="fixed-content p-0" style=" border-radius: 10px; margin-top: 0%;box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
    <div class=" container" style="margin-top: -5%;">
        <div class="card" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="row">

                    <h5 class="dz-title">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="18px" width="18px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve">
                            <circle style="fill:#006775;" cx="256" cy="255.977" r="255.977" />
                            <path style="fill:#055661;" d="M506.211,310.479C481.241,425.705,378.75,512,256.052,512c-17.299,0-34.197-1.705-50.543-5.014  L91.086,392.562l74.812-29.884l102.54-26.825l58.766-204.38L506.211,310.479z" />
                            <path style="fill:#FEFEFE;" d="M146.693,305.215c-3.911,1.655-8.724,7.822-12.034,13.137l-42.269,67.44  c-3.309,5.265-1.755,8.774,2.106,7.12l71.352-30.236c1.304,1.805,55.758,46.532,57.613,45.83l94.969-34.548  c2.005,0.501,87.749,30.085,92.963,31.639c6.569,2.005,10.229-0.803,10.128-7.822l-1.003-69.447  c-0.1-7.02,1.003-13.388-5.415-15.695c-9.627-3.46-64.834-26.725-67.19-25.873c-14.441,5.164-79.626,28.631-79.626,28.631  c-10.179-7.572-54.003-36.855-55.858-38.208c-0.702-0.501-3.009-0.301-6.318,0.802c-7.472,2.508-51.797,23.968-59.369,27.227h-0.05  V305.215z" />
                            <g>
                                <path style="fill:#D9DADA;" d="M318.479,374.009c3.259,0.953,87.749,30.085,92.863,31.639l0.301,0.1l0.301,0.1l0.301,0.05   l0.301,0.05l0.301,0.05l0.301,0.05l0,0l0.301,0.05l0,0l0.251,0.05l0.251,0.05l0.251,0.05h0.251l0,0h0.251h0.251l0,0h0.251h0.251   h0.251h0.251l0.251-0.05l0.251-0.05l0,0l0.2-0.05l0.2-0.05l0,0l0.2-0.05l0,0l0.2-0.05l0.2-0.05l0,0l0.2-0.05l0,0l0.2-0.1l0.2-0.1   l0.2-0.1l0,0l0.2-0.1l0.15-0.1l0,0l0.15-0.1l0.15-0.1l0.15-0.15l0.15-0.15l0.15-0.15l0.15-0.15l0.15-0.15l0,0l0.15-0.15l0.15-0.15   l0.15-0.15l0,0l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.251l0.05-0.251l0.05-0.251l0.05-0.251   l0.05-0.251l0.05-0.251l0,0l0.05-0.251l0,0l0.05-0.251l0.05-0.301l0.05-0.301l0.05-0.301l0,0v-0.301v-0.301v-0.301v-0.301v-0.301   l-1.003-69.447c-0.1-7.02,1.003-13.388-5.415-15.695c-9.627-3.46-64.834-26.725-67.19-25.873l-29.383,87.247L318.479,374.009z" />
                                <path style="fill:#D9DADA;" d="M165.848,362.677c1.304,1.755,53.451,44.676,57.412,45.83h0.2l44.827-93.113   c-10.179-7.572-54.003-36.855-55.858-38.208l-46.532,85.442L165.848,362.677z" />
                            </g>
                            <path style="fill:#00CC96;" d="M268.388,335.851h3.059c0.953-4.061,5.164-11.382,7.12-15.193l23.015-47.033  c10.529-21.01,20.358-41.769,30.787-62.577c11.984-24.018,14.14-47.534-0.05-72.004c-11.182-19.355-34.548-35.551-57.814-35.551  c-17.349,0-29.333,1.655-43.674,11.132c-9.677,6.418-16.647,13.438-22.915,23.768c-14.692,24.369-12.686,48.087-0.401,72.656  C209.32,214.608,267.284,331.038,268.388,335.851z" />
                            <path style="fill:#07B587;" d="M269.19,335.851h2.256c0.953-4.061,5.164-11.382,7.12-15.193l23.015-47.033  c10.529-21.01,20.358-41.769,30.787-62.577c11.984-24.018,14.14-47.534-0.05-72.004c-11.182-19.355-34.548-35.551-57.814-35.551  c-1.805,0-3.61,0-5.315,0.05C269.19,192.696,269.19,182.617,269.19,335.851z" />
                            <circle style="fill:#E1E5E6;" cx="269.889" cy="171.235" r="43.227" />
                            <path style="fill:#CCCCCC;" d="M269.19,128.063v86.395h0.702c23.868,0,43.223-19.355,43.223-43.223s-19.355-43.223-43.223-43.223  h-0.702V128.063z" />
                            <path style="fill:#E84F4F;" d="M269.892,155.491c8.674,0,15.745,7.02,15.745,15.745c0,8.674-7.02,15.745-15.745,15.745  c-8.674,0-15.745-7.02-15.745-15.745C254.147,162.56,261.167,155.491,269.892,155.491z" />
                            <path style="fill:#C94545;" d="M269.19,155.541v31.439h0.702c8.674,0,15.745-7.02,15.745-15.745c0-8.674-7.02-15.745-15.745-15.745  h-0.702V155.541z" />
                        </svg>
                        Lokasi Saya
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                <div id="lokasi"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
<script>
    window.onbeforeunload = function() {
        Swal.fire({
            allowOutsideClick: false,
            background: 'transparent',
            html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
            showCancelButton: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                // Swal.showLoading()
            },
        });
    };
    getLocation();

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        //   x.innerHTML = "Latitude: " + position.coords.latitude +
        //   "<br>Longitude: " + position.coords.longitude;
        var lat_saya = position.coords.latitude;
        var long_saya = position.coords.longitude;
        var nama_saya = '{{$user_karyawan->name}}';
        // console.log(lat_saya, long_saya);
        // console.log(lokasi_kantor);

        var map = L.map('lokasi').setView([lat_saya, long_saya], 16);
        var latlngs_sumberpangankediri = [
            [-7.757852, 112.093890],
            [-7.756964, 112.094195],
            [-7.757866, 112.096507],
            [-7.758657, 112.095336]
        ];
        var latlngs_sumberpangantuban = [
            [-6.991758822037412, 112.12048943252134],
            [-6.992285922956118, 112.12087444394012],
            [-6.991649636772762, 112.12126324857486],
            [-6.9918209446766015, 112.12162739730593],
            [-6.99158186659566, 112.12182464453525],
            [-6.991630811724543, 112.12207689339583],
            [-6.988976733872493, 112.12301030070874],
            [-6.988841110863623, 112.1225521606721],
            [-6.988496578083082, 112.12262012506571],
            [-6.988366830934185, 112.12224502050286],
            [-6.988087592439392, 112.12137545996293],
            [-6.98793810105542, 112.1214266105829],
            [-6.987859124455924, 112.12116801578183],
            [-6.988502219235255, 112.1209008958774],
            [-6.988694019261298, 112.12132146764182],
            [-6.989663035162432, 112.12098199978163],
            [-6.9897194468028525, 112.12109850952719],
            [-6.990145354468302, 112.12087117343832],
            [-6.989959196198711, 112.12060689523501],
            [-6.990190483734605, 112.12045628507613],
            [-6.990653058462982, 112.12096779127609]
        ];
        var latlngs_suryapangansemestakediri = [
            [-7.811016, 112.079884],
            [-7.810885, 112.080821],
            [-7.808513, 112.081619],
            [-7.808415, 112.081323],
            [-7.810010, 112.080548]
        ];
        var latlngs_suryapangansemestangawi = [
            [-7.503894, 111.429050],
            [-7.503781, 111.425848],
            [-7.505655, 111.425796],
            [-7.505756, 111.428451],
            [-7.504889, 111.428478],
            [-7.504698, 111.429090]
        ];
        var latlngs_suryapangansemestasubang = [
            [-6.295363, 107.906800],
            [-6.293911, 107.906937],
            [-6.293955, 107.907789],
            [-6.295743, 107.907667]
        ];
        // DEPO SIDOARJO
        var latlngs_deposidoarjo = [
            [-7.361735, 112.784873],
            [-7.361757, 112.785147],
            [-7.362231, 112.785102],
            [-7.362195, 112.784741]
        ];

        // DEPO SAMARINDA
        var latlngs_deposamarinda = [
            [-0.46124004439708466, 117.1890440835615],
            [-0.4612392469974343, 117.18918363302389],
            [-0.46134587505367874, 117.18918108680002],
            [-0.4613312150395592, 117.18903673736563]
        ];

        // DEPO DENPASAR
        var latlngs_depodenpasar = [
            [-8.652895481207116, 115.20293696056507],
            [-8.652912717125513, 115.2030294967747],
            [-8.652755926596885, 115.20305008509402],
            [-8.652733064463064, 115.2029671528421]
        ];

        // DEPO MALANG
        var latlngs_depomalang = [
            [-7.967760845267797, 112.65873922458452],
            [-7.967798033683292, 112.65879957428648],
            [-7.967823932756354, 112.65878616324159],
            [-7.967790064737394, 112.65872983685311]
        ];
        // DEPO PALANGKARAYA
        var latlngs_depopalangkaraya = [
            [-2.1739101807413506, 113.864207945572],
            [-2.1737446735313326, 113.86422269772137],
            [-2.173735292555323, 113.86412814985499],
            [-2.1739061603235093, 113.86411876212357]
        ];
        // DEPO SEMARANG
        var latlngs_deposemarang = [
            [-7.003762008571239, 110.4547271253569],
            [-7.003741376561739, 110.4546278836248],
            [-7.003783306128471, 110.45461983699788],
            [-7.003805934781971, 110.4547117026553]
        ];
        // DEPO BANDUNG
        var latlngs_depobandung = [
            [-6.887528841438018, 107.60032030611694],
            [-6.887538161422427, 107.60048257975994],
            [-6.887629364117361, 107.60047855644646],
            [-6.887622041273895, 107.60032164722143]
        ];

        // DEPO SPS CIPINANG (JAKARTA)
        var latlngs_depocipinang1 = [
            [-6.21311187156196, 106.88544203302257],
            [-6.2120446956529545, 106.88543065337363],
            [-6.212025840935464, 106.88472511513999],
            [-6.213168435595694, 106.88476684051939]
        ];
        var latlngs_depocipinang2 = [
            [-6.211847347299506, 106.8808114012799],
            [-6.211852680220818, 106.88181991185459],
            [-6.212351308125068, 106.88182795848152],
            [-6.212327310001449, 106.88079799023502]
        ];
        var latlngs_deposmarinda = [
            [-0.46124004439708466, 117.1890440835615],
            [-0.4612392469974343, 117.18918363302389],
            [-0.46134587505367874, 117.18918108680002],
            [-0.4613312150395592, 117.18903673736563]
        ];

        var polygon = L.polygon(latlngs_sumberpangankediri, {
            color: 'purple'
        }).addTo(map);
        var polygon2 = L.polygon(latlngs_sumberpangantuban, {
            color: 'purple'
        }).addTo(map);
        var polygon3 = L.polygon(latlngs_suryapangansemestakediri, {
            color: 'purple'
        }).addTo(map);
        var polygon4 = L.polygon(latlngs_suryapangansemestangawi, {
            color: 'purple'
        }).addTo(map);
        var polygon5 = L.polygon(latlngs_suryapangansemestasubang, {
            color: 'purple'
        }).addTo(map);
        var polygon6 = L.polygon(latlngs_deposidoarjo, {
            color: 'red'
        }).addTo(map);
        var polygon7 = L.polygon(latlngs_deposamarinda, {
            color: 'red'
        }).addTo(map);
        var polygon8 = L.polygon(latlngs_depodenpasar, {
            color: 'red'
        }).addTo(map);
        var polygon9 = L.polygon(latlngs_depomalang, {
            color: 'red'
        }).addTo(map);
        var polygon10 = L.polygon(latlngs_depopalangkaraya, {
            color: 'red'
        }).addTo(map);
        var polygon11 = L.polygon(latlngs_deposemarang, {
            color: 'red'
        }).addTo(map);
        var polygon12 = L.polygon(latlngs_depobandung, {
            color: 'red'
        }).addTo(map);
        var polygon13 = L.polygon(latlngs_depocipinang1, {
            color: 'red'
        }).addTo(map);
        var polygon14 = L.polygon(latlngs_depocipinang2, {
            color: 'red'
        }).addTo(map);
        var polygon15 = L.polygon(latlngs_deposmarinda, {
            color: 'red'
        }).addTo(map);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: ''
        }).addTo(map);
        const popupContent =
            '<p style="font-size:6pt;">' +
            nama_saya +
            '</p>';
        var marker = L.marker([lat_saya, long_saya]).addTo(map)
            .bindPopup(popupContent).openPopup();
    }
</script>
@endsection