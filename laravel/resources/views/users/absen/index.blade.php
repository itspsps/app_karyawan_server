@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<style>
    body {}

    canvas {
        position: absolute;
    }
</style>
@endsection
@section('content')

<!-- Features -->
<div id="alert_karyawan_tidaksesuai" class="alert alert-danger light alert-lg alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>&nbsp;Face tidak Sesuai.</strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
<div id="alert_karyawan_unknown" class="alert alert-danger light alert-lg alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>&nbsp;Face Tidak Diketahui.</strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@if(Session::has('karyawan_tidaksesuai'))
<div id="alert_karyawan_tidaksesuai" class="alert alert-danger light alert-lg alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>&nbsp;Face tidak Sesuai.</strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@elseif(Session::has('karyawan_kosong'))
<div id="alert_karyawan_unknown" class="alert alert-danger light alert-lg alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>&nbsp;Face Tidak Diketahui.</strong>
    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@elseif(Session::has('jam_kerja_kurang'))
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
                        <!-- <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{url('home/my-location')}}" id="btn_klik" type="button" class="btn btn-sm btn-secondary" style="height:10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFFFFF" version="1.1" id="Capa_1" width="18" height="18" class="svg-main-icon" viewBox="0 0 395.71 395.71" xml:space="preserve">
                                            <g>
                                                <path d="M197.849,0C122.131,0,60.531,61.609,60.531,137.329c0,72.887,124.591,243.177,129.896,250.388l4.951,6.738   c0.579,0.792,1.501,1.255,2.471,1.255c0.985,0,1.901-0.463,2.486-1.255l4.948-6.738c5.308-7.211,129.896-177.501,129.896-250.388   C335.179,61.609,273.569,0,197.849,0z M197.849,88.138c27.13,0,49.191,22.062,49.191,49.191c0,27.115-22.062,49.191-49.191,49.191   c-27.114,0-49.191-22.076-49.191-49.191C148.658,110.2,170.734,88.138,197.849,88.138z" />
                                            </g>
                                        </svg>
                                        &nbsp;Lokasi&nbsp;Saya
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a id="btn_klik" href="{{url('/absen/data-absensi')}}" class="btn btn-sm btn-secondary" style="height:10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 10C16.4183 10 20 8.20914 20 6C20 3.79086 16.4183 2 12 2C7.58172 2 4 3.79086 4 6C4 8.20914 7.58172 10 12 10Z" fill="#1C274C" />
                                            <path opacity="0.5" d="M4 12V18C4 20.2091 7.58172 22 12 22C16.4183 22 20 20.2091 20 18V12C20 14.2091 16.4183 16 12 16C7.58172 16 4 14.2091 4 12Z" fill="#1C274C" />
                                            <path opacity="0.7" d="M4 6V12C4 14.2091 7.58172 16 12 16C16.4183 16 20 14.2091 20 12V6C20 8.20914 16.4183 10 12 10C7.58172 10 4 8.20914 4 6Z" fill="#1C274C" />
                                        </svg>
                                        &nbsp;Data&nbsp;Absensi
                                    </a>
                                </div>
                            </div>
                        </div> -->
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
                            <form id="form" action="absenMasuk" method="post">
                                <!-- @method('put')-->
                                @csrf
                                <div class="text-center">
                                    <h2 style="color: white">Absen Masuk: </h2>
                                    <canvas id="canvas"></canvas>
                                    <video id="video" class="webcam" width="300" height="400" autoplay muted>

                                    </video>
                                    <!-- <div class="webcam" id="results"></div> -->
                                    <input type="hidden" name="jam_absen" value="{{ date('H:i:s') }}">
                                    <input type="hidden" name="foto_jam_absen" class="image-tag">
                                    <input type="hidden" id="shift_karyawan" name="shift_karyawan" value="{{$shift_karyawan->id}}">
                                    <input type="hidden" name="lat_absen" id="lat">
                                    <input type="hidden" name="long_absen" id="long">
                                    <input type="hidden" name="name" id="name">
                                    <input type="hidden" name="karyawan_id" id="karyawan_id">
                                    <input type="hidden" name="telat">
                                    <input type="hidden" name="jarak_masuk">
                                    <input type="hidden" name="status_absen">
                                    <input type="hidden" name="keterangan_absensi">
                                    <button id="btn_submit" hidden style="background-color: white; display: none;" type="submit" class="btn btn-lokasisaya" value="Ambil Foto">Masuk</button>
                                </div>
                            </form>
                            <script type="text/javascript" src="{{ asset('webcamjs/webcam.min.js') }}"></script>
                            <script language="JavaScript">
                                Webcam.set({
                                    width: 300,
                                    height: 400,
                                    image_format: 'jpeg',
                                    jpeg_quality: 50
                                });
                                Webcam.attach('.webcam');
                            </script>
                            @elseif($shift_karyawan->jam_absen != NULL && $shift_karyawan->jam_pulang == NULL)
                            <form id="form" method="post" action="absenPulang">
                                <!-- @method('put') -->
                                @csrf
                                <div class="text-center">
                                    <h2 style="color: white">Absen Pulang: </h2>
                                    <canvas id="canvas"></canvas>
                                    <video id="video" class="webcam" width="300" height="400" autoplay muted></video>
                                    <!-- <div class="webcam" id="results"></div> -->
                                    <input type="hidden" name="jam_pulang" value="{{ date('H:i') }}">
                                    <input type="hidden" name="foto_jam_pulang" class="image-tag">
                                    <input type="hidden" name="lat_pulang" id="lat2" value="">
                                    <input type="hidden" name="long_pulang" id="long2" value="">
                                    <input type="hidden" id="shift_karyawan" name="shift_karyawan" value="{{$shift_karyawan->id}}">
                                    <input type="hidden" name="name" id="name">
                                    <input type="hidden" name="karyawan_id" id="karyawan_id">
                                    <input type="hidden" name="pulang_cepat">
                                    <input type="hidden" name="jarak_pulang">
                                    <input type="hidden" name="keterangan_absensi">
                                    <input type="hidden" name="jam_masuk" value="{{$shift_karyawan->jam_absen}}">

                                    <button id="btn_submit" type="submit" hidden class="btn btn-lokasisaya" style="background-color: white; display: none;" value="Ambil Foto" onClick="take_snapshot()">Pulang</button>

                                </div>
                            </form>
                            <script type="text/javascript" src="{{ asset('webcamjs/webcam.min.js') }}"></script>
                            <script language="JavaScript">
                                Webcam.set({
                                    width: 300,
                                    height: 400,
                                    image_format: 'jpeg',
                                    jpeg_quality: 50
                                });
                                Webcam.attach('.webcam');
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
                        <!--  -->
                        <!--  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($faceid==NULL)
<div class="offcanvas offcanvas-bottom pwa-offcanvas">
    <div class="container">
        <div class="offcanvas-body small text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 48 48" fill="none">
                <rect width="48" height="48" fill="white" fill-opacity="0.01" />
                <path d="M34 3.99976H44V13.9998M44 33.9998V43.9998H34M14 43.9998H4V33.9998M4 13.9998V3.99976H14" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M24 39.9998C31.732 39.9998 38 32.8363 38 23.9998C38 15.1632 31.732 7.99976 24 7.99976C16.268 7.99976 10 15.1632 10 23.9998C10 32.8363 16.268 39.9998 24 39.9998Z" stroke="#000000" stroke-width="4" />
                <path d="M6 24.0081L42 23.9998" stroke="#000000" stroke-width="4" stroke-linecap="round" />
                <path d="M20.0697 32.1057C21.3375 33.0429 22.6476 33.5115 24 33.5115C25.3523 33.5115 26.6983 33.0429 28.0381 32.1057" stroke="#000000" stroke-width="4" stroke-linecap="round" />
            </svg>
            <h5 class="title">FACE ID BELUM TERDAFTAR</h5>
            <p class="text">SILAHKAN DAFTAR DAHULU</p>
            <a href="{{route('create_face_id')}}" style="margin-top: -5%;" class="btn btn-sm btn-primary">DAFTAR FACE</a>
        </div>
    </div>
</div>
<div class="offcanvas-backdrop pwa-backdrop"></div>
@endif
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script defer src="{{ asset('assets/assets_users/js/face-api.js/face-api.min.js') }}"></script>
<script defer src="{{ asset('assets/assets_users/js/absensi.js')}}" onload="onLoadData('{{ $face }}', '{{ $karyawan }}', '{{ $angka }}')"></script>
<script defer src="{{ asset('assets/assets_users/js/submitFormAbsensi.js')}}" onload="onLoadDataAbsensi('{{ $absensi }}','{{$jumlah_absensi}}')"></script>
<!-- <script defer type="module">
        // onLoadData('{{$face}}', '{{$karyawan}}', '{{$angka}}');
        onLoadData(<?php echo json_encode($face) ?>,<?php echo json_encode($karyawan) ?>,<?php echo json_encode($angka) ?>);
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        //masukan data db ke js dan di parsing 
        var dataFaceJson
        var dataKaryawanJson
        var labelHasil
        var nomorTable

        function onLoadData(face, karyawan, angka) {
            // console.log(face);
            dataFaceJson = face;
            // console.log(dataFaceJson);
            dataKaryawanJson = karyawan;
            nomorTable = angka;
        }


        navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

        const startVideo = () => {
            navigator.getUserMedia({
                    video: {}
                },
                stream => video.srcObject = stream,
                err => console.error(err)
            )
        }
        import * as faceapi from '/public/assets/assets_users/js/face-api.js/face-api.min.js';
        // var faceapi = require('public/assets/assets_users/js/face-api.min.js');
        Promise.all([
            console.log(faceapi),
            await faceapi.nets.tinyFaceDetector.loadFromUri('/public/assets/assets_users/js/face-api.js/models'),
            // await faceapi.nets.tinyFaceDetector.loadFromUri('/public/assets/assets_users/js/face-api.js/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/assets/assets_users/js/face-api.js/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/assets/assets_users/js/face-api.js/models'),
            faceapi.nets.ssdMobilenetv1.loadFromUri('/assets/assets_users/js/face-api.js/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('/assets/assets_users/js/face-api.js/models'),
        ]).then(startVideo);


        video.addEventListener('play', () => {

            // console.log(canvas);
            // document.getElementById('container').appendChild(canvas)
            const displaySize = {
                width: video.width,
                height: video.height
            }
            faceapi.matchDimensions(canvas, displaySize)


            // membuat data sesuai format dari faceapi
            const labeledFaceDescriptors = []
            // console.log(dataFaceJson);
            // console.log(JSON.parse(dataFaceJson[0].face_id));
            // console.log(dataFaceJson[0].id);
            // console.log(dataKaryawanJson.find(value => value.id));
            for (let i = 0; i < dataFaceJson.length; i++) {
                const data = dataKaryawanJson.find(value => value.id === dataFaceJson[i].id)
                // console.log(data);  

                // rubah dari array biasa menjadi float32Array
                const array1 = JSON.parse(dataFaceJson[i].face_id)
                // console.log(array1);
                const float1 = Float32Array.from(array1)
                // console.log(data.name);
                // console.log(float1);

                // memasukan data yang sesuai format ke array labeledFaceDescriptors
                labeledFaceDescriptors.push(new faceapi.LabeledFaceDescriptors(
                    data.name, [float1]))

            }

            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5)
            // me load gambar
            setInterval(async () => {
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceExpressions()
                    .withFaceDescriptors()
                const resizedDetections = faceapi.resizeResults(detections, displaySize)
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)

                //menambkan kan kotak pada muka sebagai tanda pendeteksian wajah berhasil
                faceapi.draw.drawDetections(canvas, resizedDetections)
                // digunakan untuk menampilkan faceLandmark
                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
                //digunakan untuk menampilkan expresi wajah
                // faceapi.draw.drawFaceExpressions(canvas, resizedDetections)

                const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
                results.forEach((result, i) => {
                    const box = resizedDetections[i].detection.box
                    const drawBox = new faceapi.draw.DrawBox(box, {
                        label: result.toString()
                    })
                    drawBox.draw(canvas)
                    labelHasil = drawBox.options.label

                })
            }, 100)

        })
    // })
</script>
<script defer>
    $(document).ready(function() {
        onLoadDataAbsensi(<?php echo json_encode($absensi) ?> , <?php echo json_encode($jumlah_absensi) ?>);
        const form = document.getElementById('form')
        const name = document.getElementById('name')
        const karyawan_id = document.getElementById('karyawan_id')
        const button = document.getElementById('btn_submit')
        const shift_karyawan = $('#shift_karyawan').val();

        var alert_karyawan_tidaksesuai = document.getElementById('alert_karyawan_tidaksesuai')
        var alert_karyawan_unknown = document.getElementById('alert_karyawan_unknown')
        $('#alert_karyawan_tidaksesuai').hide();
        $('#alert_karyawan_unknown').hide();
        var absensi = []
        var jumlahAbsensi
        // untuk menyimpan variable array yang pertama di ambil
        var allFirstMatches = []
        // onload array absensi
        function onLoadDataAbsensi(value , jmlAbsensi) {
            absensi = value
            jumlahAbsensi = jmlAbsensi
        }

        function take_snapshot() {
            // take snapshot and get image data
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                // display results in page
                video.innerHTML =
                    '<img src="' + data_uri + '"/>';
            });
        }

        //membuat kondisi jika hasil pengenalan tidak sama dengan unknown
        var labelHasil
        setInterval(() => {
            if (labelHasil !== undefined) {
                if (labelHasil.split(" ")[0] !== "unknown") {
                    const arrayLabel = labelHasil.split(" ")
                    arrayLabel.pop()
                    // nama label yang dikenali
                    const labelName = arrayLabel.join(" ")
                    submitButton = () => {
                        // memasukan data pengenalan ke form
                        const karyawan = dataKaryawanJson.find(value => value.name === labelName)
                        name.value = labelName
                        karyawan_id.value = karyawan.id
                        // untuk mensubmit form
                        take_snapshot()
                        button.click()
                    }
                    submitButton();
                } else {
                    $('#alert_karyawan_unkwon').show();
                    console.log('unknwon');
                    // console.log('ok');
                    setTimeout(function() {
                        // console.log('ok1');
                        $("#alert_karyawan_unkwon").hide();
                    }, 2000); // 7 secs
                }
            } else {
                $('#alert_karyawan_tidaksesuai').show();
                console.log('tidak sesuai');
                // console.log('ok');
                setTimeout(function() {
                    // console.log('ok1');
                    $("#alert_karyawan_tidaksesuai").hide();
                }, 2000); // 7 secs
            }
        }, 3000) // jarak tiap submit 3 detik satuan ms
    });
</script> -->
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
        $('#lat_location').val(position.coords.latitude);
        $('#long_location').val(position.coords.longitude);
        $('#btn_submit').show();
    }
</script>
<script>
    $(document).on('click', '#btn_klik', function(e) {
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
    });
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
    $(document).on('click', '#btn_submit', function(e) {
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
    });
</script>
@endsection