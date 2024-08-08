@extends('layouts.dashboard')
@section('isi')
 @if($shift_karyawan->count() > 0)
        @foreach ($shift_karyawan as $sk)
            <?php $skid = $sk->id ?>
            <?php $sktanggal = $sk->tanggal ?>
            <?php $sknamas = $sk->Shift->nama_shift  ?>
            <?php $skjamas = $sk->Shift->jam_masuk ?>
            <?php $skjamkel = $sk->Shift->jam_keluar ?>
            <?php $skjamab = $sk->jam_absen ?>
            <?php $skjampul = $sk->jam_pulang ?>
            <?php $skstatus = $sk->status_absen ?>

        @endforeach
    @else
        <?php $skid = "-" ?>
        <?php $sktanggal = "-" ?>
        <?php $sknamas = "-"  ?>
        <?php $skjamas = "-" ?>
        <?php $skjamkel = "-" ?>
        <?php $skjamab = "-" ?>
        <?php $skjampul = "-" ?>
        <?php $skstatus = "-" ?>
    @endif
    <div class="container-fluid">
        <div class="d-flex justify-content-center">
            <form action="{{ url('/my-location') }}" method="get">
                @csrf
                <input type="hidden" name="lat" id="lat2">
                <input type="hidden" name="long" id="long2">
                <button type="submit" class="btn btn-success">Lihat Lokasi Saya</button>
            </form>
        </div>

        @if($shift_karyawan->count() == 0)
        <br>
        <div class="card col-lg-12">
        <div class="mt-5">
            <div class="mb-5">
                <center>
                    <h2>Hubungi Admin Untuk Mapping Shift Anda</h2>
                </center>
            </div>
        </div>
        </div>
        <div class="card col-lg-12">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-tahun-tab" data-toggle="pill" href="#pills-tahun" role="tab" aria-controls="pills-tahun" aria-selected="true">TAHUN {{$date_now}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-kemarin-tab" data-toggle="pill" href="#pills-kemarin" role="tab" aria-controls="pills-kemarin" aria-selected="false">BULAN KEMARIN (<b>{{\Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');}}</b>)</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-sekarang-tab" data-toggle="pill" href="#pills-sekarang" role="tab" aria-controls="pills-sekarang" aria-selected="false">BULAN INI (<b>{{\Carbon\Carbon::now()->isoFormat('MMMM');}}</b>)</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-tahun" role="tabpanel" aria-labelledby="pills-tahun-tab">
 <div class="card col-lg-12">
    <div class="mb-5">
        <div id="grafik_year"></div>
    </div>
</div>
    </div>
  <div class="tab-pane fade" id="pills-kemarin" role="tabpanel" aria-labelledby="pills-kemarin-tab">
      <div class="card col-lg-12">
    <div class="mb-5">
        <div id="grafik_month_yesterday"></div>
    </div>
</div>
  </div>
  <div class="tab-pane fade" id="pills-sekarang" role="tabpanel" aria-labelledby="pills-sekarang-tab">
      <div class="card col-lg-12">
    <div class="mb-5">
        <div id="grafik_month_now"></div>
    </div>
</div>
  </div>
</div>
        </div>
        @elseif($skstatus == "Libur")
        <br>
        <div class="card col-lg-12">
        <div class="mt-5">
            <div class="mb-5">
                <center>
                    <h2>Hari Ini Anda Libur</h2>
                </center>
            </div>
        </div>
        </div>
        @elseif($skstatus == "Cuti")
        <br>
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
            @if($skjamab == null)
            <br>
                <div class="card col-lg-12">
                    <div class="mt-4">
                        <form method="post" action="{{ url('/absen/masuk/'.$skid) }}">
                            @method('put')
                            @csrf
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col">
                                    <center>
                                        <h2>Absen Masuk 1: </h2>
                                        <div class="webcam" id="results"></div>
                                    </center>
                                </div>
                                <div class="col">
                                    <input type="hidden" name="jam_absen" value="{{ date('H:i') }}">
                                    <input type="hidden" name="foto_jam_absen" class="image-tag">
                                    <input type="hidden" name="lat_absen" id="lat">
                                    <input type="hidden" name="long_absen" id="long">
                                    <input type="text" name="telat">
                                    <input type="text" name="jarak_masuk">
                                    <input type="text" name="status_absen">
                                </div>
                            </div>
                            <br>
                            <center>
                                <button type="submit" class="btn btn-primary" value="Ambil Foto" onClick="take_snapshot()">Masuk</button>
                            </center>
                            </form>
                            <br>
                    </div>
                </div>
                <br><br>

                <script type="text/javascript" src="{{ asset('public/webcamjs/webcam.min.js') }}"></script>
                <script language="JavaScript">
                Webcam.set({
                    width: 240,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 50
                });
                Webcam.attach( '.webcam' );
                </script>
                <script language="JavaScript">
                function take_snapshot() {
                    // take snapshot and get image data
                    Webcam.snap( function(data_uri) {
                            $(".image-tag").val(data_uri);
                    // display results in page
                    document.getElementById('results').innerHTML =
                        '<img src="'+data_uri+'"/>';
                    } );
                }
                </script>

            @elseif($skjampul == null)
            <br>
            <div class="card col-lg-12">
                <div class="mt-4">
                    <form method="post" action="{{ url('/absen/pulang/'.$skid) }}">
                        @method('put')
                        @csrf
                        <div class="form">
                            <div class="col"></div>
                            <div class="col">
                                <center>
                                    <span class="imgtgl font-weight-bold mr-2 mt-0" id='tanggal'>
                                            &nbsp;
                                        </span>
                                        <span class="imgjam font-weight-bold mr-3 mt-0" id='jam'>
                                            &nbsp;
                                        </span>
                                    <h2>Absen Pulang: </h2>
                                    <div class="webcam" id="results"></div>
                                </center>
                            </div>
                            <div class="col">
                                <input type="hidden" name="jam_pulang" value="{{ date('H:i') }}">
                                <input type="hidden" name="foto_jam_pulang" class="image-tag">
                                <input type="hidden" name="lat_pulang" id="lat">
                                <input type="hidden" name="long_pulang" id="long">
                                <input type="hidden" name="pulang_cepat">
                                <input type="hidden" name="jarak_pulang">
                            </div>
                        </div>
                        <br>
                        <center>
                            <button type="submit" class="btn btn-primary" value="Ambil Foto" onClick="take_snapshot()">Pulang</button>
                        </center>
                    </form>
                    <br>
                </div>
            </div>
            <br><br>

            <script type="text/javascript" src="{{ url('public/webcamjs/webcam.min.js') }}"></script>
            <script language="JavaScript">
            Webcam.set({
                width: 240,
                height: 320,
                image_format: 'jpeg',
                jpeg_quality: 50
            });
            Webcam.attach( '.webcam' );
            </script>
            <script language="JavaScript">
            function take_snapshot() {
                // take snapshot and get image data
                Webcam.snap( function(data_uri) {
                        $(".image-tag").val(data_uri);
                // display results in page
                document.getElementById('results').innerHTML =
                    '<img src="'+data_uri+'"/>';
                } );
            }
            </script>

            @else
            <br>
            <div class="card col-lg-12">
                <div class="mt-5">
                <div class="mb-5">
                    <center>
                        <h2>Anda Sudah Selesai Absen</h2>
                    </center>
                </div>
                </div>
            </div>

            @endif
        @endif


    </div>
    <br>
      <div class="card col-lg-12">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-tahun-tab" data-toggle="pill" href="#pills-tahun" role="tab" aria-controls="pills-tahun" aria-selected="true">TAHUN {{$date_now}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-kemarin-tab" data-toggle="pill" href="#pills-kemarin" role="tab" aria-controls="pills-kemarin" aria-selected="false">BULAN KEMARIN (<b>{{\Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');}}</b>)</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-sekarang-tab" data-toggle="pill" href="#pills-sekarang" role="tab" aria-controls="pills-sekarang" aria-selected="false">BULAN INI (<b>{{\Carbon\Carbon::now()->isoFormat('MMMM');}}</b>)</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-tahun" role="tabpanel" aria-labelledby="pills-tahun-tab">
 <div class="card col-lg-12">
    <div class="mb-5">
        <div id="grafik_year"></div>
    </div>
</div>
    </div>
  <div class="tab-pane fade" id="pills-kemarin" role="tabpanel" aria-labelledby="pills-kemarin-tab">
      <div class="card col-lg-12">
    <div class="mb-5">
        <div id="grafik_month_yesterday"></div>
    </div>
</div>
  </div>
  <div class="tab-pane fade" id="pills-sekarang" role="tabpanel" aria-labelledby="pills-sekarang-tab">
      <div class="card col-lg-12">
    <div class="mb-5">
        <div id="grafik_month_now"></div>
    </div>
</div>
  </div>
</div>
        </div>

    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form action="{{ url('/my-absen') }}">
                    <span>Filter Rentang Tanggal</span><br><br>
                    <div class="form-row">
                        <div class="col-3">
                            <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                        </div>
                        <div class="col-3">
                            <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                        </div>
                        <div>
                            <button type="submit" id="search" class="form-control btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table id="tableprint" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Karyawan</th>
                            <th>Shift</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Telat</th>
                            <th>Lokasi Masuk</th>
                            <th>Foto Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Pulang Cepat</th>
                            <th>Lokasi Pulang</th>
                            <th>Foto Pulang</th>
                            <th>Status Absen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_absen as $da)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $da->User->name }}</td>
                        <td>{{ $da->Shift->nama_shift }} ({{ $da->Shift->jam_masuk }} - {{ $da->Shift->jam_keluar }})</td>
                        <td>{{ $da->tanggal }}</td>
                        <td>
                             @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @else
                                {{ $da->jam_absen }}
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->status_absen == 'Izin Telat')
                                <span class="badge badge-warning">Izin Telat</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @else
                            <?php
                                $telat = $da->telat;
                                $jam   = floor($telat / (60 * 60));
                                $menit = $telat - ( $jam * (60 * 60) );
                                $menit2 = floor( $menit / 60 );
                                $detik = $telat % 60;
                            ?>
                                @if($jam <= 0 && $menit2 <= 0)
                                    <span class="badge badge-success">Tepat Waktu</span>
                                @else
                                    <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @else
                                @php
                                    $jarak_masuk = explode(".", $da->jarak_masuk);
                                @endphp
                                <a href="{{ url('/maps/'.$da->lat_absen.'/'.$da->long_absen) }}" class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                                <span class="badge badge-warning">{{ $jarak_masuk[0] }} Meter</span>
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @else
                                <img src="{{ url('storage/' . $da->foto_jam_absen) }}" style="width: 60px">
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                                <span class="badge badge-warning">Belum Pulang</span>
                            @else
                                {{ $da->jam_pulang }}
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->status_absen == 'Izin Pulang Cepat')
                                <span class="badge badge-warning">Izin Pulang Cepat</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                                <span class="badge badge-warning">Belum Pulang</span>
                            @else
                                <?php
                                    $pulang_cepat = $da->pulang_cepat;

                                    $jam   = floor($pulang_cepat / (60 * 60));
                                    $menit = $pulang_cepat - ( $jam * (60 * 60) );
                                    $menit2 = floor( $menit / 60 );
                                    $detik = $pulang_cepat % 60;
                                ?>
                                 @if($jam <= 0 && $menit2 <= 0)
                                    <span class="badge badge-success">Tidak Pulang Cepat</span>
                                 @else
                                    <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                 @endif
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                                <span class="badge badge-warning">Belum Pulang</span>
                            @else
                                @php
                                    $jarak_pulang = explode(".", $da->jarak_pulang);
                                @endphp
                                <a href="{{ url('/maps/'.$da->lat_pulang.'/'.$da->long_pulang) }}" class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                                <span class="badge badge-warning">{{ $jarak_pulang[0] }} Meter</span>
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                                <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                                <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                                <span class="badge badge-warning">Belum Pulang</span>
                            @else
                                <img src="{{ url('public/storage/' . $da->foto_jam_pulang) }}" style="width: 60px">
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                                <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti' || $da->status_absen == 'Izin Telat' || $da->status_absen == 'Izin Pulang Cepat')
                                <span class="badge badge-warning">{{ $da->status_absen }}</span>
                            @elseif($da->status_absen == 'Masuk')
                                <span class="badge badge-success">{{ $da->status_absen }}</span>
                            @else
                                <span class="badge badge-danger">{{ $da->status_absen }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    var tertib = {{json_encode($masuk)}};
    var telat = {{json_encode($telat)}};
    var tidakmasuk = {{json_encode($tidak_masuk)}};
    var telat_now = {{json_encode($telat_now)}};
    var telat_yesterday = {{json_encode($telat_yesterday)}};
    var lembur_now = {{json_encode($lembur_now)}};
    var lembur_yesterday = {{json_encode($lembur_yesterday)}};
    // console.log(tertib);
        Highcharts.chart('grafik_year', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'GRAFIK ABSENSI, TAHUN {{$date_now}}'
        },
        subtitle: {
            text: 'CV. SUMBER PANGAN'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']
        },
        yAxis: {
            title: {
                text: 'Total Hari'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'MASUK',
            data: tertib
        }, {
            name: 'TELAT',
            data: telat
        }, {
            name: 'TIDAK MASUK',
            data: tidakmasuk
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 200
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
    Highcharts.chart('grafik_month_yesterday', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'GRAFIK ABSENSI, BULAN {{$month_yesterday1}} {{$date_now}}'
        },
        subtitle: {
            text: 'CV. SUMBER PANGAN'
        },
        xAxis: {
            categories: {!!json_encode($data_telat_yesterday)!!}
        },
        yAxis: {
            title: {
                text: 'Total Menit'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'TELAT(Menit)',
            data: telat_yesterday
        }, {
            name: 'LEMBUR(Menit)',
            data: lembur_yesterday
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 200
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
    Highcharts.chart('grafik_month_now', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'GRAFIK ABSENSI, BULAN {{$month_now1}} {{$date_now}}'
        },
        subtitle: {
            text: 'CV. SUMBER PANGAN'
        },
        xAxis: {
            categories: {!!json_encode($data_telat_now)!!}
        },
        yAxis: {
            title: {
                text: 'Total Menit'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'TELAT(Menit)',
            data: telat_now
        }, {
            name: 'LEMBUR(Menit)',
            data: lembur_now
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 200
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>
@endsection
