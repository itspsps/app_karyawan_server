<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="theme-color" content="#2196f3">
    <meta name="author" content="DexignZone" />
    <meta name="keywords" content="" />
    <meta name="robots" content="" />
    <meta name="description" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:title" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:description" content="Jobie - Job Portal Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:image" content="https://jobie.dexignzone.com/mobile-app/xhtml/social-image.png" />
    <meta name="format-detection" content="telephone=no">

    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png" />

    <!-- Title -->
    <title>APPS KARYAWAN</title>

    <!-- PWA Version -->
    {{-- <link rel="manifest" href="{{ asset('assets/assets_users/manifest.json') }}"> --}}

    <!-- Stylesheets -->
    @include('users.izin.layout.css') @yield('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Include Bootstrap DateTimePicker CDN -->
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>
    @include('sweetalert::alert')
    <div class="page-wraper">

        <!-- Preloader -->
        <div id="preloader">
            <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status">
            </div>
            <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status">
            </div>
            <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status">
            </div>
        </div>
        <!-- Preloader end-->

        <!-- Page Content -->
        <div class="page-content bottom-content">

            <!-- Banner -->
            <div class="head-details">
                <div class=" container">
                    <div class="dz-info">
                        <span class="location d-block text-left">Form Izin&nbsp;Pulang Cepat
                        </span>
                        @if(auth()->user()->kategori=='Karyawan Bulanan')
                        <h6 class="title">@if($user->kontrak_kerja == 'SP')
                            CV. SUMBER PANGAN
                            @elseif($user->kontrak_kerja == 'SPS')
                            PT. SURYA PANGAN SEMESTA
                            @elseif($user->kontrak_kerja == 'SIP')
                            CV. SURYA INTI PANGAN
                            @endif</h6>
                        @elseif(auth()->user()->kategori=='Karyawan Harian')
                        <h5 class="title">{{auth()->user()->penempatan_kerja}}
                        </h5>
                        @endif
                    </div>
                    <div class="dz-media media-65">
                        <img src="assets/images/logo/logo.svg" alt="">
                    </div>
                </div>
            </div>

            <div class="fixed-content p-0">

            </div>
            <!-- Banner End -->


            <!-- Footer End -->
            @yield('content')


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
            <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
            <style>
                body {
                    padding: 15px;
                }

                #note {
                    position: absolute;
                    left: 50px;
                    top: 35px;
                    padding: 0px;
                    margin: 0px;
                    cursor: default;
                }
            </style>
            <div class="container">
                @if($jam_kerja=='' || $jam_kerja==NULL)
                <div id="alert_kontrak_kerja_null" class="alert alert-danger light alert-lg alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <strong>User Belum Mapping Shift.</strong> Harap Hubungi HRD.
                    <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @endif
                <form class="my-2" method="post" action="{{ url('/home/pulang_cepat_proses/') }}" enctype=" multipart/form-data">
                    <div id="alert_atasankosong" class="alert mt-4 alert-primary light alert-lg alert-dismissible fade show">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="20" width="20" version="1.1" id="_x32_" viewBox="0 0 512 512" xml:space="preserve">
                            <style type="text/css">
                                .st0 {
                                    fill: #000000;
                                }
                            </style>
                            <g>
                                <polygon class="st0" points="131.549,358.824 88.822,358.824 88.822,316.089 64.362,316.089 64.362,383.285 131.549,383.285  " />
                                <polygon class="st0" points="380.451,0 380.451,24.461 423.178,24.461 423.178,67.196 447.638,67.196 447.638,0  " />
                                <polygon class="st0" points="88.822,24.453 131.549,24.461 131.549,0 64.362,0 64.362,67.196 88.822,67.196  " />
                                <polygon class="st0" points="423.178,358.824 380.451,358.824 380.451,383.285 447.638,383.285 447.638,316.089 423.178,316.089     " />
                                <path class="st0" d="M255.996,316.67c69.052,0,125.028-55.969,125.028-125.028c0-69.051-55.976-125.028-125.028-125.028   c-69.051,0-125.028,55.977-125.028,125.028C130.968,260.701,186.945,316.67,255.996,316.67z" />
                                <path class="st0" d="M255.996,341.378c-62.044,0-114.78,55.833-127.194,96.163C115.64,480.308,131.9,512,175.335,512h161.322   c43.435,0,59.695-31.692,46.533-74.458C370.776,397.211,318.04,341.378,255.996,341.378z" />
                            </g>
                        </svg>
                        &nbsp;Anda Absensi Kurang dari Ketentuan Jam Pulang. Silahkan Mengisi Form Pulang Cepat
                        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @method('post')
                    @csrf
                    <div class="input-group">
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="telp" value="{{ $user->telepon }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="departements" value="{{ $user->dept_id }}">
                        <input type="hidden" name="jabatan" value="{{ $user->jabatan_id }}">
                        <input type="hidden" name="level_jabatan" value="@if(Auth::user()->kategori=='Karyawan Harian')@else{{ $user->level_jabatan }}@endif">
                        <input type="hidden" name="divisi" value="{{ $user->divisi_id }}">
                        <input type="hidden" name="id_mapping" value="{{ $jam_kerja->id}}">
                        <input type="hidden" name="menit_telat" value="{{ $pulang_cepat}}">
                        <input type="hidden" name="id_user_atasan" value="@if($getUserAtasan=='') @else{{ $getUserAtasan->id}}@endif">
                        <input type="hidden" name="foto_jam_pulang" value="{{ $foto_jam_pulang}}">
                        <input type="hidden" name="total_jam_kerja" value="{{ $total_jam_kerja}}">
                        <input type="hidden" name="lat_pulang" value="{{ $lat_pulang}}">
                        <input type="hidden" name="long_pulang" value="{{ $long_pulang}}">
                        <input type="hidden" name="jarak_pulang" value="{{ $jarak_pulang}}">
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" value="Name" readonly>
                        <input type="text" class="form-control" name="fullname" value="{{ $user->name }}" style="font-weight: bold" readonly required>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" value="Kategori Izin" readonly>
                        <input type="text" name="izin" class="form-control" value="Pulang Cepat" readonly>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" value="Tanggal" readonly>
                        <input type="date" name="tanggal" id="tanggal" value="{{date('Y-m-d')}}" readonly style="font-weight: bold" required placeholder="Tanggal" class="form-control">
                    </div>
                    <div id="jam_masuk_kerja" class="input-group">
                        <input type="text" class="form-control" value="Jam Pulang Kerja" readonly>
                        <input type="text" id="jam_pulang" name="jam_pulang" value="@if($jam_kerja=='' || $jam_kerja==NULL)Mapping Belum Tersedia @else {{$jam_kerja->Shift->jam_keluar}} @endif" readonly style="font-weight: bold" placeholder="Jam Pulang Kerja" class="form-control">
                    </div>
                    <div id="form_jam_pulang_cepat" class="input-group">
                        <input type="text" class="form-control" value="Jam Keluar" readonly>
                        <input type="time" readonly name="jam_pulang_cepat" id="jam_pulang_cepat" value="{{date('H:i')}}" style="font-weight: bold" placeholder="Jam Pulang" class="form-control">
                    </div>
                    <div class="input-group">
                        <textarea class="form-control" name="keterangan_izin" style="font-weight: bold" required placeholder="Keterangan"></textarea>
                    </div>
                    <div class="input-group">
                        @if($getUserAtasan==NULL)
                        @if($user->level_jabatan=='1')
                        <input type="text" class="form-control" value="Diproses" readonly>
                        <input type="text" class="form-control" name="approve_atasan1" value="HRD" readonly>
                        <input type="hidden" class="form-control" name="approve_atasan" value="" readonly>
                        @else
                        <input type="text" class="form-control" value="Approve By" readonly>
                        <input type="text" class="form-control" name="approve_atasan" value="" readonly>
                        @endif
                        @else
                        <input type="text" class="form-control" value="Approve By" readonly>
                        <input type="text" class="form-control" name="approve_atasan" value="{{ $getUserAtasan->name }}" readonly>
                        @endif
                    </div>
                    <div class="input-group">
                        <div id="signature-pad" style="border:solid 1px teal; width:100%;height:200px;">
                            <div>
                                <div id="note" onmouseover="my_function();"></div>
                                <canvas id="the_canvas" width="auto" height="100px"></canvas>
                                <p class="text-primary" style="text-align: center">Ttd : {{ Auth::user()->fullname }} {{ date('Y-m-d') }}</p>
                                <hr>
                                <div class="text-center">
                                    <input type="hidden" id="signature" name="signature">
                                    <button type="button" id="clear_btn" class="btn btn-sm btn-danger btn-rounded" data-action="clear"><i class="fa fa-refresh" aria-hidden="true"> </i> &nbsp; Clear</button>
                                    <button type="submit" id="save_btn" class="btn btn-sm btn-primary btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Simpan</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <hr width="90%" style="margin-left: 5%;margin-right: 5%">
        </div>
    </div>
    @include('users.izin.layout.menubar')
    @include('users.izin.layout.js')
    <script type="text/javascript" src="{{ asset('assets_ttd/assets/signature.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var wrapper = document.getElementById("signature-pad");
        var clearButton = wrapper.querySelector("[data-action=clear]");
        var savePNGButton = wrapper.querySelector("[data-action=save-png]");
        var canvas = wrapper.querySelector("canvas");
        var el_note = document.getElementById("note");
        var signaturePad;
        signaturePad = new SignaturePad(canvas);

        clearButton.addEventListener("click", function(event) {
            // document.getElementById("note").innerHTML = "The signature should be inside box";
            signaturePad.clear();
        });
        savePNGButton.addEventListener("click", function(event) {
            if (signaturePad.isEmpty()) {
                alert("Please provide signature first.");
                event.preventDefault();
            } else {
                var canvas = document.getElementById("the_canvas");
                var dataUrl = canvas.toDataURL();
                document.getElementById("signature").value = dataUrl;
            }
        });

        function my_function() {
            document.getElementById("note").innerHTML = "";
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var awal = $('#jam_masuk').val().split(":"),
                akhir = $('#jam').val().split(":");
            var hours1 = parseInt(awal[0], 10),
                hours2 = parseInt(akhir[0], 10),
                mins1 = parseInt(awal[1], 10),
                mins2 = parseInt(akhir[1], 10);
            var hours = hours2 - hours1,
                mins = 0;
            // get hours
            if (hours < 0) hours = 24 + hours;

            // get minutes
            if (mins2 >= mins1) {
                mins = mins2 - mins1;
            } else {
                mins = (mins2 + 60) - mins1;
                hours--;
            }

            // convert to fraction of 60
            mins = (mins - 5); // -5 toleransi telat 5 menit
            console.log(mins);

            // hours += mins;
            // hours = hours.toFixed(2);
            $("#terlambat").val(hours + ' Jam, ' + mins + ' Menit');

            // var time1 = awal.split(":");
            // var time2 = akhir.split(":");
            // var ok1 = time1[0] + time1[1];
            // var ok2 = time2[0] + time2[1];
            // console.log(ok1, ok2);
            // if (ok1 < ok2) {
            //     var ya = ok2 - ok1;
            //     var hasil = ya / 60;
            //     console.log(hasil);
            //     var jam = (time2[0] - time1[0]);
            //     var menit = (time2[1] - time1[1]);
            //     // hours = Math.floor((diff / 60));
            //     // minutes = (diff % 60);
            //     console.log('jam = ' + jam);
            //     console.log('menit = ' + menit);
            //     // console.log('MENIT = ' + minutes);
            //     $('#terlambat').val(Math.abs(jam) + ' Jam, ' + Math.abs(menit) + ' Menit')
            // } else {
            //     var diff1 = getTimeDiff('24:00', '{time1}', 'm');
            //     var diff2 = getTimeDiff('{time2}', '00:00', 'm');
            //     var totalDiff = diff1 + diff2;
            //     hours = Math.floor((totalDiff / 60));
            //     minutes = (totalDiff % 60);
            // };
            // var hasil = ((akhir - awal)) / 1000;
        });
    </script>
</body>

</html>
@section('js')
@endsection