@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')

<!-- Features -->
@if(Session::has('jam_kerja_kurang'))
<div class="alert alert-danger light alert-lg alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>&nbsp;Jam Kerja Anda Kurang dari 6 Jam.</strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
<div class="alert alert-warning light alert-lg alert-dismissible fade show">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" version="1.1" id="Layer_1" width="20" height="20" viewBox="0 0 256 240" enable-background="new 0 0 256 240" xml:space="preserve">
        <path d="M108.327,28.998c11.331,0,20.516,9.185,20.516,20.516s-9.185,20.516-20.516,20.516S87.81,60.845,87.81,49.514  S96.996,28.998,108.327,28.998z M33.069,153.324l-0.189-0.189l0,0c-0.851-0.567-1.607-1.04-2.269-1.796l2.269,26L4.327,217.804  c-4.065,5.862-2.647,13.804,3.025,17.869c5.862,4.065,13.804,2.647,17.869-3.025l30.822-44.436c1.607-2.269,2.647-5.295,2.269-8.32  l-1.04-12.574L33.069,153.324z M117.876,237.942h122.341l-22.88-71.381c0,0-12.953,3.215-38.574,30.443  c-0.567,0.567-1.229,1.229-1.796,1.796l-61.832-35.738c1.04-1.985,1.229-4.255,0.851-6.713L94.05,75.041  c-1.796-7.942-8.887-13.993-17.207-13.993H35.905c-3.025,0-6.051,1.607-7.753,4.255L7.731,100.757  c-2.647,4.255-1.04,9.738,3.215,12.196l22.88,13.142l-3.025,5.105c-3.687,6.051-1.607,13.804,4.633,17.207L86.77,178v46.894  c0,7.091,5.673,12.764,12.764,12.764s12.764-5.673,12.764-12.764v-53.512l56.821,32.902c-11.724,6.713-25.149,7.942-34.698,13.614  C122.697,224.895,117.876,237.942,117.876,237.942z M38.174,118.626l-15.033-8.698l0,0l17.869-30.822h20.044L38.174,118.626z   M71.171,137.724l14.182-24.771l10.589,38.952L71.171,137.724z M203.459,2c-27.879,0-50.541,22.621-50.541,50.459  c0,27.879,22.662,50.541,50.541,50.541S254,80.338,254,52.459C253.918,24.621,231.257,2,203.459,2z M203.459,93.014  c-22.377,0-40.555-18.178-40.555-40.555s18.178-40.555,40.555-40.555s40.555,18.178,40.555,40.555  C243.77,74.836,225.591,93.014,203.459,93.014z M228.648,42.596c-0.937-1.549-2.894-2.609-5.136-1.467l-17.893,7.989V27.963  c0-1.141-0.408-2.16-1.141-2.935l-0.041-0.041c-0.734-0.652-1.59-0.937-2.609-0.937c-1.956,0-3.994,1.467-3.994,3.913v27.675  c0,1.508,0.856,2.935,2.201,3.668c0.693,0.326,1.304,0.489,1.875,0.489c0.611,0,1.304-0.204,1.956-0.489l23.11-11.046  c1.019-0.53,1.753-1.386,2.079-2.405C229.3,44.797,229.178,43.615,228.648,42.596L228.648,42.596z" />
    </svg>
    <strong>&nbsp;Jam Kerja Kurang dari 6 jam Dianggap Tidak Masuk.</strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@elseif(Session::has('cameraoff'))
<div class="alert alert-danger light alert-lg alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>&nbsp;Camera Error. Harap Ulangi </strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@endif
<div class="">
    <div class="row m-b20 g-3">
        <div class="col-12">
            <div class="card card-bx card-content bg-primary">
                <div class="card-body">
                    <div class="info">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <a type="button" href="{{ url('/home/my-location') }}" class="btn btn-sm btn-secondary" style="height:10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFFFFF" version="1.1" id="Capa_1" width="18" height="18" class="svg-main-icon" viewBox="0 0 395.71 395.71" xml:space="preserve">
                                            <g>
                                                <path d="M197.849,0C122.131,0,60.531,61.609,60.531,137.329c0,72.887,124.591,243.177,129.896,250.388l4.951,6.738   c0.579,0.792,1.501,1.255,2.471,1.255c0.985,0,1.901-0.463,2.486-1.255l4.948-6.738c5.308-7.211,129.896-177.501,129.896-250.388   C335.179,61.609,273.569,0,197.849,0z M197.849,88.138c27.13,0,49.191,22.062,49.191,49.191c0,27.115-22.062,49.191-49.191,49.191   c-27.114,0-49.191-22.076-49.191-49.191C148.658,110.2,170.734,88.138,197.849,88.138z" />
                                            </g>
                                        </svg>
                                        &nbsp;Lokasi&nbsp;Saya
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{url('/absen/data-absensi')}}" class="btn btn-sm btn-secondary" style="height:10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 10C16.4183 10 20 8.20914 20 6C20 3.79086 16.4183 2 12 2C7.58172 2 4 3.79086 4 6C4 8.20914 7.58172 10 12 10Z" fill="#1C274C" />
                                            <path opacity="0.5" d="M4 12V18C4 20.2091 7.58172 22 12 22C16.4183 22 20 20.2091 20 18V12C20 14.2091 16.4183 16 12 16C7.58172 16 4 14.2091 4 12Z" fill="#1C274C" />
                                            <path opacity="0.7" d="M4 6V12C4 14.2091 7.58172 16 12 16C16.4183 16 20 14.2091 20 12V6C20 8.20914 16.4183 10 12 10C7.58172 10 4 8.20914 4 6Z" fill="#1C274C" />
                                        </svg>
                                        &nbsp;Data&nbsp;Absensi
                                    </a>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            @if($shift_karyawan == NULL)
                            <div class="card col-lg-12">
                                <div class="mt-5">
                                    <div class="mb-5">
                                        <center>
                                            <h2>Hubungi Admin Untuk Mapping Shift Anda</h2>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            @elseif($shift_karyawan->status_absen == "LIBUR")
                            <div class="card col-lg-12">
                                <div class="mt-5">
                                    <div class="mb-5">
                                        <center>
                                            <h2>Hari Ini Anda Libur</h2>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            @elseif($shift_karyawan->status_absen == "CUTI")
                            <div class="card col-lg-12">
                                <div class="mt-5">
                                    <div class="mb-5">
                                        <center>
                                            <h2>Hari Ini Anda Cuti</h2>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            @else
                            @if($shift_karyawan->jam_absen == NULL && $shift_karyawan->jam_pulang== NULL)
                            <form method="post" action="{{ url('/home/absen/masuk/'.$shift_karyawan->id) }}">
                                @method('put')
                                @csrf
                                <div class="form">
                                    <center>
                                        <h2 style="color: white">Absen Masuk: </h2>
                                        <div class="webcam" id="results"></div>
                                    </center>
                                    <input type="hidden" name="jam_absen" value="{{ date('H:i:s') }}">
                                    <input type="hidden" name="foto_jam_absen" class="image-tag">
                                    <input type="hidden" name="lat_absen" id="lat">
                                    <input type="hidden" name="long_absen" id="long">
                                    <input type="hidden" name="telat">
                                    <input type="hidden" name="jarak_masuk">
                                    <input type="hidden" name="status_absen">
                                    <input type="hidden" name="keterangan_absensi">
                                    <center>
                                        <button id="btn_submit" style="background-color: white; display: none;" type="submit" class="btn btn-lokasisaya" value="Ambil Foto" onClick="take_snapshot()">Masuk</button>
                                    </center>
                                </div>
                            </form>
                            <script type="text/javascript" src="{{ asset('webcamjs/webcam.min.js') }}"></script>
                            <script language="JavaScript">
                                Webcam.set({
                                    width: 240,
                                    height: 320,
                                    image_format: 'jpeg',
                                    jpeg_quality: 50
                                });
                                Webcam.attach('.webcam');
                            </script>
                            <script language="JavaScript">
                                function take_snapshot() {
                                    // take snapshot and get image data
                                    Webcam.snap(function(data_uri) {
                                        $(".image-tag").val(data_uri);
                                        // display results in page
                                        document.getElementById('results').innerHTML =
                                            '<img src="' + data_uri + '"/>';
                                    });
                                }
                            </script>
                            @elseif($shift_karyawan->jam_absen != NULL && $shift_karyawan->jam_pulang == NULL)
                            <form method="post" action="{{ url('/home/absen/pulang/'.$shift_karyawan->id) }}">
                                @method('put')
                                @csrf
                                <div class="form">
                                    <center>
                                        <h2 style="color: white">Absen Pulang: </h2>
                                        <div class="webcam" id="results"></div>
                                    </center>
                                    <input type="hidden" name="jam_pulang" value="{{ date('H:i') }}">
                                    <input type="hidden" name="foto_jam_pulang" class="image-tag">
                                    <input type="hidden" name="lat_pulang" id="lat2" value="">
                                    <input type="hidden" name="long_pulang" id="long2" value="">
                                    <input type="hidden" name="pulang_cepat">
                                    <input type="hidden" name="jarak_pulang">
                                    <input type="hidden" name="keterangan_absensi">
                                    <input type="hidden" name="jam_masuk" value="{{$shift_karyawan->jam_absen}}">
                                    <center>
                                        <button id="btn_submit" type="submit" class="btn btn-lokasisaya" style="background-color: white; display: none;" value="Ambil Foto" onClick="take_snapshot()">Pulang</button>
                                    </center>
                                </div>
                            </form>
                            <script type="text/javascript" src="{{ asset('webcamjs/webcam.min.js') }}"></script>
                            <script language="JavaScript">
                                Webcam.set({
                                    width: 240,
                                    height: 320,
                                    image_format: 'jpeg',
                                    jpeg_quality: 50
                                });
                                Webcam.attach('.webcam');
                            </script>
                            <script language="JavaScript">
                                function take_snapshot() {
                                    // take snapshot and get image data
                                    Webcam.snap(function(data_uri) {
                                        $(".image-tag").val(data_uri);
                                        // display results in page
                                        document.getElementById('results').innerHTML =
                                            '<img src="' + data_uri + '"/>';
                                    });
                                }
                            </script>
                            @else
                            <div class="card col-lg-12">
                                <div class="mt-5">
                                    <div class="mb-5 text-center">
                                        <h2>Anda Sudah Selesai Absen</h2>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
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
        $('#lat').val(position.coords.latitude);
        $('#lat2').val(position.coords.latitude);
        $('#long').val(position.coords.longitude);
        $('#long2').val(position.coords.longitude);
        $('#long2').val(position.coords.longitude);
        $('#btn_submit').show();
    }
</script>
@endsection