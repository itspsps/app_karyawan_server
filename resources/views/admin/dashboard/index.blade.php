@extends('admin.layouts.dashboard')
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('dashboard/option/'.$holding->holding_code) }}">DASHBOARD</a>
                            </li>
                            <li class="breadcrumb-item active">HRD
                            </li>
                        </ol>
                    </nav>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Karyawan</h5>

                    </div>
                    <p class="mt-3"><span class="fw-medium">Total Karyawan </span> Kontrak Kerja @if($holding=='') @else {{$holding->holding_name}} @endif</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-primary rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Laki- Laki</div>
                                    <h5 class="mb-0" id="karyawan_lakilaki"></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-success rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Perempuan</div>
                                    <h5 class="mb-0" id="karyawan_perempuan"></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-warning rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Bulanan</div>
                                    <h5 class="mb-0" id="karyawan_office"></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-info rounded shadow">
                                        <i class="mdi mdi-account-tie mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="small mb-1">Karyawan Harian</div>
                                    <h5 class="mb-0" id="karyawan_shift"></h5>
                                </div>
                            </div>
                        </div>
                        <!-- @if($count_karyawan_habis_kontrak > 0) -->
                        <div class="alert alert-warning" role="alert">
                            <i class="mdi mdi-account-alert-outline "></i>
                            <span>Karyawan Masa Tenggang Kontrak @if($holding=='') @else {{$holding->holding_name}} @endif </span><span>Total : {{$count_karyawan_habis_kontrak}} Orang
                                <a href=" @if(Auth::user()->is_admin =='hrd'){{url('hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code)}}@else{{url('karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code)}}@endif">&nbsp;Lihat&nbsp;Semua&nbsp;. .</a>
                            </span>

                        </div>
                        <div class="modal fade" id="modal_perbarui_kontrak" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <form id="form_update_kontrak" method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/karyawan/update_kontrak_proses') }}@else {{ url('karyawan/update_kontrak_proses') }} @endif" class="modal-content" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="backDropModalTitle">Form Pembaruan Kontrak Karyawan</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-2 mt-2">
                                            <div class="col-md-12">
                                                <div class="card mb-4">
                                                    <!-- Account -->
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                            <input type="hidden" name="id_karyawan" id="id_karyawan" value="">
                                                            <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                                                            <table>
                                                                <tr>
                                                                    <th>Nama</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_nama"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Divisi</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_divisi"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jabatan</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_jabatan"></td>
                                                                <tr>
                                                                    <th>Kontrak Kerja</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_kontrak_kerja"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Penempatan Kerja</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_penempatan_kerja"> </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tgl Mulai Kontrak</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_mulai_kontrak"> </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tgl Selesai Kontrak</th>
                                                                    <td>&nbsp;</td>
                                                                    <td>:</td>
                                                                    <td id="td_selesai_kontrak"></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="date" id="tgl_mulai_kontrak_baru" name="tgl_mulai_kontrak_baru" readonly value="{{date('Y-m-d')}}" class="form-control @error('tgl_mulai_kontrak_baru') is-invalid @enderror" placeholder="Tanggal" />
                                                    <label for="tgl_mulai_kontrak_baru">Tanggal Mulai Kontrak Baru</label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="date" id="tgl_selesai_kontrak_baru" name="tgl_selesai_kontrak_baru" value="" class="form-control @error('tgl_selesai_kontrak_baru') is-invalid @enderror" placeholder="Tanggal" />
                                                    <label for="tgl_selesai_kontrak_baru">Tanggal Selesai Kontrak Baru</label>
                                                </div>
                                            </div>
                                            <br>
                                            <br>

                                            <div class="col-md-12">
                                                <div class="form-floating form-floating-outline">
                                                    <select id="lama_kontrak_baru" name="lama_kontrak_baru" class="form-control @error('lama_kontrak_baru') is-invalid @enderror" placeholder="Lama Kontrak">
                                                        <option value="">- Select Lama Kontrak -</option>
                                                        <option value="3 bulan">3 Bulan</option>
                                                        <option value="6 bulan">6 Bulan</option>
                                                        <option value="1 tahun">1 Tahun</option>
                                                        <option value="2 tahun">2 Tahun</option>
                                                        <option value="tetap">Tetap</option>
                                                    </select>
                                                    <label for="lama_kontrak_baru">Lama Kontrak Baru</label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <hr class="m-0">
                                                <small class="text-light fw-medium">Upload File Pendukung</small>
                                                <br>
                                                <br>
                                                <div class="form-floating form-floating-outline">
                                                    <input type="file" id="file_kontrak_kerja" accept="application/pdf" name="file_kontrak_kerja" class="form-control @error('file_kontrak_kerja') is-invalid @enderror" placeholder="File" />
                                                    <label for="file_kontrak_kerja">File Kontrak Kerja</label>
                                                </div>
                                                <small class="text-info fw-medium">*Format PDF</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mt--10">
                            <div class="d-flex align-items-center" style="overflow-x:auto;">
                                <table class="table table-responsive" id="table_karyawan_tenggang" style="width: 100%; font-size: smaller;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Nomor&nbsp;ID</th>
                                            <th>Nama&nbsp;Karyawan</th>
                                            <th>Telepon</th>
                                            <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Tanggal&nbsp;Kontrak</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @php
                                        $no=1;
                                        @endphp
                                        @foreach($karyawan_habis_kontrak as $karyawan_habis_kontrak)
                                        <tr>
                                            <td>{{$no++;}}</td>
                                            <td>{{$karyawan_habis_kontrak->nomor_identitas_karyawan}}</td>
                                            <td>{{$karyawan_habis_kontrak->name}}</td>
                                            <td>{{$karyawan_habis_kontrak->telepon}}</td>
                                            <td>@if($karyawan_habis_kontrak->Divisi==NULL)-@else{{$karyawan_habis_kontrak->Divisi->nama_divisi}}@endif</td>
                                            <td>@if($karyawan_habis_kontrak->Jabatan==NULL)-@else{{$karyawan_habis_kontrak->Jabatan->nama_jabatan}}@endif</td>
                                            <td>{{ \Carbon\Carbon::parse($karyawan_habis_kontrak->tgl_mulai_kontrak)->isoFormat('DD MMMM YYYY') }}&nbsp;-&nbsp;{{\Carbon\Carbon::parse($karyawan_habis_kontrak->tgl_selesai_kontrak)->isoFormat('DD MMMM YYYY')}}</td>
                                            <?php
                                            $date1 = new DateTime();
                                            $date2 = new DateTime($karyawan_habis_kontrak->tgl_selesai_kontrak);
                                            $interval = $date1->diff($date2);
                                            // print_r($interval->d);
                                            ?>
                                            <td>@if($karyawan_habis_kontrak->tgl_selesai_kontrak <= $date_now1) <span class="badge bg-label-danger"><i class="mdi mdi-close-octagon-outline"></i> Melebihi Masa Kontrak {{$interval->format('%a')}} Hari </span>@else <span class="badge bg-label-warning"><i class="mdi mdi-alert-octagon-outline"></i> Kontrak Kurang {{$interval->format('%a')}} Hari </span> @endif</td>
                                            <td><button id="btn_perbarui_kontrak" data-id="{{$karyawan_habis_kontrak->id}}" data-nama="{{$karyawan_habis_kontrak->name}}" data-divisi="@if($karyawan_habis_kontrak->Divisi==NULL)-@else{{$karyawan_habis_kontrak->Divisi->nama_divisi}}@endif" data-jabatan="@if($karyawan_habis_kontrak->Jabatan==NULL)-@else{{$karyawan_habis_kontrak->Jabatan->nama_jabatan}}@endif" data-foto="{{$karyawan_habis_kontrak->foto_karyawan}}" data-tgl_mulai_kontrak="{{$karyawan_habis_kontrak->tgl_mulai_kontrak}}" data-tgl_selesai_kontrak="{{$karyawan_habis_kontrak->tgl_selesai_kontrak}}" data-penempatan_kerja="{{$karyawan_habis_kontrak->penempatan_kerja}}" data-kontrak_kerja="@if($karyawan_habis_kontrak->kontrak_kerja=='SPS')PT. SURYA PANGAN SEMESTA @elseif($karyawan_habis_kontrak->kontrak_kerja=='SP') CV. SUMBER PANGAN @else CV. SURYA INTI PANGAN @endif" type="button" class="btn btn-xs btn-info waves-effect waves-light"><i class="mdi mdi-update"></i>&nbsp;Perbarui</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right; margin-right: 2px; margin-top: 2px;" class="float-right">
                                <a href="@if(Auth::user()->is_admin =='hrd'){{url('hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code)}}@else {{url('karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code)}} @endif"><span class="badge bg-label-success">Lihat&nbsp;Semua&nbsp;<i class="mdi mdi-chevron-double-right"></i></span></a>
                            </div>
                        </div>
                        <!-- @endif -->
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row gy-4">
            <div class="col-xl-7 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Grafik Karyawan per Departemen</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_dept"></div>
                        <div class="mt-1 mt-md-3">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-5">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Presentase Jabatan Karyawan</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_jabatan"></div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row gy-4">
            <div class="col-xl-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Presentase Gender Karyawan</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_gender"></div>
                        <div class="mt-1 mt-md-3">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Presentase Kontrak Karyawan</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_kontrak"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Presentase Status Nikah Karyawan</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_status"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Grafik Absensi Karyawan Kontrak Kerja <div class="btn-group" role="group">
                                    <select name="change_holding" id="change_holding" style="width: max-content;border-radius: 0px; background-color:transparent; color:#9370DB; border: none;outline: none;">
                                        @foreach($holdingAll as $data)
                                        <option @if($data->holding_code== $holding->holding_code ) selected @else @endif value="{{$data->holding_category}}">{{$data->holding_name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- <span class="mdi mdi-menu-down"></span> -->
                            </h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_absensi"></div>
                        <div class="mt-1 mt-md-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">Grafik Lokasi Karyawan Kontrak Kerja <div class="btn-group" role="group">
                                    <select name="change_holding" id="change_holding" style="width: max-content;border-radius: 0px; background-color:transparent; color:#9370DB; border: none;outline: none;">
                                        @foreach($holdingAll as $data)
                                        <option @if($holding==$data->holding_code ) selected @else @endif value="{{$data->holding_code}}">{{$data->holding_name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- <span class="mdi mdi-menu-down"></span> -->
                            </h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafik_absensi"></div>
                        <div class="mt-1 mt-md-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $(document).on('click', '#btn_perbarui_kontrak', function() {
            var id = $(this).data('id');
            var holding = $(this).data("holding");
            var nama = $(this).data('nama');
            var divisi = $(this).data('divisi');
            var jabatan = $(this).data('jabatan');
            var bagian = $(this).data('bagian');
            var foto = $(this).data('foto');
            var tgl_mulai_kontrak = $(this).data('tgl_mulai_kontrak');
            var tgl_selesai_kontrak = $(this).data('tgl_selesai_kontrak');
            var kontrak_kerja = $(this).data('kontrak_kerja');
            var penempatan_kerja = $(this).data('penempatan_kerja');
            if (foto == '' | foto == null) {
                $('#template_foto_karyawan').attr('src', "{{asset('admin/assets/img/avatars/1.png')}}");
            } else {
                $('#template_foto_karyawan').attr('src', "{{url('storage/app/public/foto_karyawan/')}}" + foto);
            }
            $('#td_nama').html(nama);
            $('#td_divisi').html(divisi);
            $('#td_jabatan').html(jabatan);
            $('#td_bagian').html(bagian);
            $('#td_jabatan').html(jabatan);
            $('#td_mulai_kontrak').html(tgl_mulai_kontrak);
            $('#td_selesai_kontrak').html(tgl_selesai_kontrak);
            $('#td_kontrak_kerja').html(kontrak_kerja);
            $('#td_penempatan_kerja').html(penempatan_kerja);
            $('#id_karyawan').val(id);
            $('#modal_perbarui_kontrak').modal('show');
        });
    </script>
    <script>
        /**
         * Dashboard Analytics
         */
        $(document).ready(function() {
            var holding = '{{$holding->holding_code}}';
            load_graph_Dashboard_All(holding);
            get_grafik_absensi(holding);

            function load_graph_Dashboard_All(holding = '') {
                // console.log(holding)
                $.ajax({
                    url: "{{ url('/graph_Dashboard_All') }}" + '/' + holding,
                    type: "GET",
                    error: function(error) {
                        // console.log(error);
                        Swal.fire({
                            title: 'Error',
                            text: error.responseJSON.message + ' ' + error.responseJSON.file + ' ' + error.responseJSON.line,
                            icon: 'error',
                            timer: 60000,
                            showConfirmButton: false,
                        })
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#karyawan_lakilaki').html(response.karyawan_laki + ' Orang');
                        $('#karyawan_perempuan').html(response.karyawan_perempuan + ' Orang');
                        $('#karyawan_office').html(response.karyawan_office + ' Orang');
                        $('#karyawan_shift').html(response.karyawan_shift + ' Orang');
                        let cardColor, labelColor, borderColor, chartBgColor, bodyColor;


                        cardColor = config.colors.cardColor;
                        labelColor = config.colors.textMuted;
                        borderColor = config.colors.borderColor;
                        chartBgColor = config.colors.chartBgColor;
                        bodyColor = config.colors.bodyColor;

                        // Chart Nama Departemen
                        // --------------------------------------------------------------------
                        const weeklyOverviewChartEl = document.querySelector('#grafik_dept'),
                            weeklyOverviewChartConfig = {
                                chart: {
                                    type: 'bar',
                                    height: 300,
                                    width: "100%",
                                    offsetY: -9,
                                    offsetX: -16,
                                    parentHeightOffset: 0,
                                    toolbar: {
                                        show: true
                                    },
                                    animations: {
                                        initialAnimation: {
                                            enabled: false
                                        }
                                    }
                                },
                                series: [{
                                    name: 'Jumlah Karyawan',
                                    data: response.jumlah_karyawan_departemen,
                                }],
                                colors: [chartBgColor],
                                plotOptions: {
                                    bar: {
                                        borderRadius: 8,
                                        columnWidth: '30%',
                                        endingShape: 'rounded',
                                        startingShape: 'rounded',
                                        colors: {
                                            ranges: [{
                                                    from: 10,
                                                    to: 20,
                                                    color: config.colors.info
                                                },
                                                {
                                                    from: 0,
                                                    to: 10,
                                                    color: config.colors.primary
                                                },
                                                {
                                                    from: 20,
                                                    to: 30,
                                                    color: config.colors.secondary
                                                },
                                                {
                                                    from: 30,
                                                    to: 40,
                                                    color: config.colors.warning
                                                },
                                                {
                                                    from: 40,
                                                    to: 50,
                                                    color: config.colors.danger
                                                },
                                                {
                                                    from: 100,
                                                    to: 200,
                                                    color: config.colors.success
                                                }
                                            ]
                                        }
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                legend: {
                                    show: false
                                },
                                grid: {
                                    strokeDashArray: 8,
                                    borderColor,
                                    padding: {
                                        bottom: 0
                                    }
                                },
                                xaxis: {
                                    categories: response.nama_departemen,
                                    // tickPlacement: 'on',
                                    labels: {
                                        style: {
                                            fontSize: '5pt',
                                        },
                                        show: true
                                    },
                                    axisBorder: {
                                        show: true
                                    },
                                    axisTicks: {
                                        show: true
                                    }
                                },
                                yaxis: {
                                    min: 0,
                                    max: response.jumlah_user,
                                    show: true,
                                    tickAmount: 5,
                                    labels: {
                                        formatter: function(val) {
                                            return parseInt(val) + ' Orang';
                                        },
                                        style: {
                                            fontSize: '0.75rem',
                                            fontFamily: 'Inter',
                                            colors: labelColor
                                        }
                                    }
                                },
                                states: {
                                    hover: {
                                        filter: {
                                            type: 'none'
                                        }
                                    },
                                    active: {
                                        filter: {
                                            type: 'none'
                                        }
                                    }
                                },
                                responsive: [{
                                        breakpoint: 2000,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '50%'
                                                }
                                            }
                                        }
                                    }, {
                                        breakpoint: 1500,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '40%'
                                                }
                                            }
                                        }
                                    },
                                    {
                                        breakpoint: 1200,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '30%'
                                                }
                                            }
                                        }
                                    },
                                    {
                                        breakpoint: 815,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 5
                                                }
                                            }
                                        }
                                    },
                                    {
                                        breakpoint: 768,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 10,
                                                    columnWidth: '20%'
                                                }
                                            }
                                        }
                                    },
                                    {
                                        breakpoint: 568,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 8,
                                                    columnWidth: '30%'
                                                }
                                            }
                                        }
                                    },
                                    {
                                        breakpoint: 410,
                                        options: {
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '50%'
                                                }
                                            }
                                        }
                                    }
                                ]
                            };
                        if (typeof weeklyOverviewChartEl !== undefined && weeklyOverviewChartEl !== null) {
                            const weeklyOverviewChart = new ApexCharts(weeklyOverviewChartEl, weeklyOverviewChartConfig);
                            weeklyOverviewChart.render();
                        }
                        // -------------------------------------------------------------------
                        // End Chart Nama Departemen
                        // Chart Nama Jabatan
                        // --------------------------------------------------------------------

                        var options_jabatan = {
                            series: response.data_karyawan_jabatan_all,
                            chart: {
                                width: 600,
                                type: 'pie',
                                toolbar: {
                                    show: true
                                }
                            },
                            labels: response.labels_jabatan_all,
                            legend: {
                                position: 'bottom'
                            },
                            responsive: [{
                                    breakpoint: 2000,
                                    options: {
                                        chart: {
                                            width: 520,
                                        },
                                        legend: {
                                            position: 'right'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1600,
                                    options: {
                                        chart: {
                                            width: 405
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1500,
                                    options: {
                                        chart: {
                                            width: 450,
                                            height: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1300,
                                    options: {
                                        chart: {
                                            width: 400,
                                            height: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1100,
                                    options: {
                                        chart: {
                                            width: 280
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            ]
                        };

                        var chart = new ApexCharts(document.querySelector("#grafik_jabatan"), options_jabatan);
                        chart.render();
                        // --------------------------------------------------------------------
                        // End Chart Nama Jabatan
                        //  Chart Nama Gender
                        // --------------------------------------------------------------------
                        var options_gender = {
                            series: response.data_karyawan_gender,
                            chart: {
                                width: 300,
                                type: 'pie',
                                toolbar: {
                                    show: true
                                }
                            },
                            labels: response.labels_gender,
                            legend: {
                                position: 'bottom'
                            },
                            responsive: [{
                                    breakpoint: 2000,
                                    options: {
                                        chart: {
                                            width: 400
                                        },
                                        legend: {
                                            position: 'right'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1600,
                                    options: {
                                        chart: {
                                            width: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1500,
                                    options: {
                                        chart: {
                                            width: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1300,
                                    options: {
                                        chart: {
                                            width: 280
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            ]
                        };

                        var chart = new ApexCharts(document.querySelector("#grafik_gender"), options_gender);
                        chart.render();
                        // --------------------------------------------------------------------
                        // End Chart Gender
                        // Chart Kontrak
                        // --------------------------------------------------------------------
                        var options_kontrak = {
                            series: response.data_karyawan_kontrak,
                            chart: {
                                width: 300,
                                type: 'pie',
                                toolbar: {
                                    show: true
                                }
                            },
                            labels: response.labels_kontrak,
                            legend: {
                                position: 'bottom'
                            },
                            responsive: [{
                                    breakpoint: 2000,
                                    options: {
                                        chart: {
                                            width: 400
                                        },
                                        legend: {
                                            position: 'right'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1600,
                                    options: {
                                        chart: {
                                            width: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1500,
                                    options: {
                                        chart: {
                                            width: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1300,
                                    options: {
                                        chart: {
                                            width: 280
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            ]
                        };

                        var chart = new ApexCharts(document.querySelector("#grafik_kontrak"), options_kontrak);
                        chart.render();
                        // --------------------------------------------------------------------
                        // End Chart Kontrak
                        //  Chart Status Pernikahan
                        // --------------------------------------------------------------------
                        var options_status = {
                            series: response.data_karyawan_status,
                            chart: {
                                width: 300,
                                type: 'pie',
                                toolbar: {
                                    show: true
                                }
                            },
                            labels: response.labels_status,
                            legend: {
                                position: 'bottom'
                            },
                            responsive: [{
                                    breakpoint: 2000,
                                    options: {
                                        chart: {
                                            width: 400
                                        },
                                        legend: {
                                            position: 'right'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1600,
                                    options: {
                                        chart: {
                                            width: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1500,
                                    options: {
                                        chart: {
                                            width: 350
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                },
                                {
                                    breakpoint: 1300,
                                    options: {
                                        chart: {
                                            width: 280
                                        },
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            ]
                        };

                        var chart = new ApexCharts(document.querySelector("#grafik_status"), options_status);
                        chart.render();
                        // --------------------------------------------------------------------
                        // End Chart Status Pernikahan
                    }
                });
            }


            function get_grafik_absensi(get_holding = '') {
                var get_holding = '{{$holding->holding_code}}';
                var url = "@if(Auth::user()->is_admin =='hrd'){{url('hrd/get_grafik_absensi_karyawan')}}@else {{url('get_grafik_absensi_karyawan')}}@endif" + "/" + get_holding;
                console.log(url);
                // console.log(get_holding);
                $.ajax({
                    url: url,
                    data: {
                        get_holding: get_holding,
                    },
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        var label_absensi = data.label_absensi;
                        var data_absensi_masuk = data.data_absensi_masuk;
                        var data_absensi_pulang = data.data_absensi_pulang;
                        var options = {
                            series: [{
                                name: 'Jumlah Karyawan Absen Masuk ',
                                data: data_absensi_masuk
                            }, {
                                name: 'Jumlah Karyawan Absen Pulang ',
                                data: data_absensi_pulang
                            }],
                            annotations: {
                                points: [{
                                    x: 'Bananas',
                                    seriesIndex: 0,
                                    label: {
                                        borderColor: '#775DD0',
                                        offsetY: 0,
                                        style: {
                                            color: '#fff',
                                            background: '#775DD0',
                                        },
                                        text: 'Bananas are good',
                                    }
                                }]
                            },
                            chart: {
                                height: 350,
                                type: 'line',
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '50%',
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: [4, 4]
                            },
                            grid: {
                                row: {
                                    colors: ['#fff', '#f2f2f2']
                                }
                            },
                            xaxis: {
                                labels: {
                                    rotate: -45
                                },
                                categories: label_absensi,
                                tickAmount: 31
                            },
                            yaxis: {
                                title: {
                                    text: 'Jumlah Karyawan Absensi',
                                },
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'light',
                                    type: "horizontal",
                                    shadeIntensity: 0.25,
                                    gradientToColors: undefined,
                                    inverseColors: true,
                                    opacityFrom: 0.85,
                                    opacityTo: 0.85,
                                    stops: [50, 0, 100]
                                },
                            }
                        };
                        var chart = new ApexCharts(document.querySelector("#grafik_absensi"), options);
                        chart.render();
                        chart.updateSeries([{
                            name: 'Jumlah Karyawan Absen Masuk ',
                            data: data_absensi_masuk
                        }, {
                            name: 'Jumlah Karyawan Absen Pulang ',
                            data: data_absensi_pulang
                        }])
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: error.responseJSON.message + ' ' + error.responseJSON.file + ' ' + error.responseJSON.line,
                            icon: 'error',
                            timer: 60000,
                            showConfirmButton: false,
                        })
                    },
                });
            }
            $('#change_holding').change(function() {
                get_holding = $(this).val();
                get_grafik_absensi(get_holding);


            })
        });
    </script>
    @endsection