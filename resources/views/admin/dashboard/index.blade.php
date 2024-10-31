@extends('admin.layouts.dashboard')
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Karyawan</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                <a class="dropdown-item" href="javascript:void(0);">Update</a>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3"><span class="fw-medium">Total Karyawan </span> Kontrak Kerja @if($holding=='sps') PT. SURYA PANGAN SEMESTA @elseif($holding=='sp') CV. SUMBER PANGAN @else CV. SURYA INTI PANGAN @endif</p>
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
                                    <h5 class="mb-0">{{$karyawan_laki}}&nbsp;Orang</h5>
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
                                    <h5 class="mb-0">{{$karyawan_perempuan}}&nbsp;Orang</h5>
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
                                    <h5 class="mb-0">{{$karyawan_office}}&nbsp;Orang</h5>
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
                                    <h5 class="mb-0">{{$karyawan_shift}}&nbsp;Orang</h5>
                                </div>
                            </div>
                        </div>
                        @if($count_karyawan_habis_kontrak > 0)
                        <div class="alert alert-warning" role="alert">
                            <i class="mdi mdi-account-alert-outline "></i>
                            <span>Karyawan Masa Tenggang Kontrak @if($holding=='sps') PT. SURYA PANGAN SEMESTA @elseif($holding=='sp') CV. SUMBER PANGAN @else CV. SURYA INTI PANGAN @endif </span><span>Total : {{$count_karyawan_habis_kontrak}} Orang
                                <a href="{{url('karyawan/karyawan_masa_tenggang_kontrak/'.$holding)}}">&nbsp;Lihat&nbsp;Semua&nbsp;. .</a>
                            </span>

                        </div>
                        <div class="modal fade" id="modal_perbarui_kontrak" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <form id="form_update_kontrak" method="post" action="{{ url('karyawan/update_kontrak_proses') }}" class="modal-content" enctype="multipart/form-data">
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
                                <a href="{{url('karyawan/karyawan_masa_tenggang_kontrak/'.$holding)}}"><span class="badge bg-label-success">Lihat&nbsp;Semua&nbsp;<i class="mdi mdi-chevron-double-right"></i></span></a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row gy-4">
            <div class="col-xl-6 col-md-6">
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
            <div class="col-xl-6 col-md-6">
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

        'use strict';
        let labels = '{{$labels}}';
        let data = '{{$data}}';
        var labels1 = labels.replaceAll('&quot;', '"');
        var labels2 = labels1.replaceAll('&amp;', '&');
        var labels3 = labels2.replaceAll('[', '');
        var labels4 = labels3.replaceAll(']', '');
        var labels5 = labels3.replaceAll(',', ', ');
        var labels6 = JSON.parse("[" + labels5);
        // Data
        var data1 = data.replaceAll('[', '');
        var data2 = data1.replaceAll(']', '');
        var data3 = JSON.parse("[" + data2 + "]");

        // Count 
        var get = '{{$jumlah_user}}';
        var count = JSON.parse(get);
        // console.log(count);
        (function() {
            let cardColor, labelColor, borderColor, chartBgColor, bodyColor;

            cardColor = config.colors.cardColor;
            labelColor = config.colors.textMuted;
            borderColor = config.colors.borderColor;
            chartBgColor = config.colors.chartBgColor;
            bodyColor = config.colors.bodyColor;

            // Weekly Overview Line Chart
            // --------------------------------------------------------------------
            const weeklyOverviewChartEl = document.querySelector('#grafik_dept'),
                weeklyOverviewChartConfig = {
                    chart: {
                        type: 'bar',
                        height: 300,
                        offsetY: -9,
                        offsetX: -16,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: true
                        }
                    },
                    series: [{
                        name: 'Jumlah Karyawan',
                        data: data3,
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
                        categories: labels6,
                        tickPlacement: 'on',
                        labels: {
                            style: {
                                fontSize: '6pt',
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
                        max: count,
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
        })();
    </script>
    <script>
        // jabatan
        let labels_jabatan = '{{$labels_jabatan}}';
        var labels_jabatan1 = labels_jabatan.replaceAll('&quot;', '"');
        var labels_jabatan2 = labels_jabatan1.replaceAll('&amp;', '&');
        var labels_jabatan3 = labels_jabatan2.replaceAll('[', '');
        var labels_jabatan4 = labels_jabatan3.replaceAll(']', '');
        var labels_jabatan5 = labels_jabatan3.replaceAll(',', ', ');

        // jabatan 1
        let labels1_jabatan = '{{$labels_jabatan1}}';
        var labels1_jabatan1 = labels1_jabatan.replaceAll('&quot;', '"');
        var labels1_jabatan2 = labels1_jabatan1.replaceAll('&amp;', '&');
        var labels1_jabatan3 = labels1_jabatan2.replaceAll('[', '');
        var labels1_jabatan4 = labels1_jabatan3.replaceAll(']', '');
        var labels1_jabatan5 = labels1_jabatan4.replaceAll(',', ', ');

        // jabatan 2
        let labels2_jabatan = '{{$labels_jabatan2}}';
        var labels2_jabatan1 = labels2_jabatan.replaceAll('&quot;', '"');
        var labels2_jabatan2 = labels2_jabatan1.replaceAll('&amp;', '&');
        var labels2_jabatan3 = labels2_jabatan2.replaceAll('[', '');
        var labels2_jabatan4 = labels2_jabatan3.replaceAll(']', '');
        var labels2_jabatan5 = labels2_jabatan4.replaceAll(',', ', ');

        // jabatan 3
        let labels3_jabatan = '{{$labels_jabatan3}}';
        var labels3_jabatan1 = labels3_jabatan.replaceAll('&quot;', '"');
        var labels3_jabatan2 = labels3_jabatan1.replaceAll('&amp;', '&');
        var labels3_jabatan3 = labels3_jabatan2.replaceAll('[', '');
        var labels3_jabatan4 = labels3_jabatan3.replaceAll(']', '');
        var labels3_jabatan5 = labels3_jabatan4.replaceAll(',', ', ');

        // jabatan 4
        let labels4_jabatan = '{{$labels_jabatan4}}';
        var labels4_jabatan1 = labels4_jabatan.replaceAll('&quot;', '"');
        var labels4_jabatan2 = labels4_jabatan1.replaceAll('&amp;', '&');
        var labels4_jabatan3 = labels4_jabatan2.replaceAll('[', '');
        var labels4_jabatan4 = labels4_jabatan3.replaceAll(']', '');
        var labels4_jabatan5 = labels4_jabatan4.replaceAll(',', ', ');



        let data_karyawan_jabatan = '{{$data_karyawan_jabatan}}';
        let data_karyawan1_jabatan = '{{$data_karyawan_jabatan1}}';
        let data_karyawan2_jabatan = '{{$data_karyawan_jabatan2}}';
        let data_karyawan3_jabatan = '{{$data_karyawan_jabatan3}}';
        let data_karyawan4_jabatan = '{{$data_karyawan_jabatan4}}';
        if (labels1_jabatan5 == '') {
            $koma1 = '';
        } else {
            $koma1 = ', ';
        }
        if (labels2_jabatan5 == '') {
            $koma2 = '';
        } else {
            $koma2 = ', ';
        }
        if (labels3_jabatan5 == '') {
            $koma3 = '';
        } else {
            $koma3 = ', ';
        }
        if (labels4_jabatan5 == '') {
            $koma4 = '';
        } else {
            $koma4 = ', ';
        }
        // console.log("[" + labels1_jabatan5 + $koma1 + labels2_jabatan5 + $koma2 + labels3_jabatan5 + $koma3 + labels4_jabatan5 + $koma4 + labels_jabatan5);
        var labels_jabatan_all = JSON.parse("[" + labels1_jabatan5 + $koma1 + labels2_jabatan5 + $koma2 + labels3_jabatan5 + $koma3 + labels4_jabatan5 + $koma4 + labels_jabatan5);
        var data_karyawan_jabatan_all = JSON.parse("[" + data_karyawan1_jabatan + $koma1 + data_karyawan2_jabatan + $koma2 + data_karyawan3_jabatan + $koma3 + data_karyawan4_jabatan + $koma4 + data_karyawan_jabatan + "]");

        var options_jabatan = {
            series: data_karyawan_jabatan_all,
            chart: {
                width: 600,
                type: 'pie',
                toolbar: {
                    show: true
                }
            },
            labels: labels_jabatan_all,
            legend: {
                position: 'bottom'
            },
            responsive: [{
                    breakpoint: 2000,
                    options: {
                        chart: {
                            width: 600
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
    </script>
    <script>
        let labels_gender = '{{$labels_gender}}';
        let data_karyawan_gender = '{{$data_karyawan_gender}}';
        var labels_gender1 = labels_gender.replaceAll('&quot;', '"');
        var labels_gender2 = labels_gender1.replaceAll('&amp;', '&');
        var labels_gender3 = labels_gender2.replaceAll('[', '');
        var labels_gender4 = labels_gender3.replaceAll(']', '');
        var labels_gender5 = labels_gender3.replaceAll(',', ', ');
        var labels_gender6 = JSON.parse("[" + labels_gender5);
        var data_karyawan_gender1 = JSON.parse(data_karyawan_gender);
        // console.log(labels_gender6);
        var options_gender = {
            series: data_karyawan_gender1,
            chart: {
                width: 300,
                type: 'pie',
                toolbar: {
                    show: true
                }
            },
            labels: labels_gender6,
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
    </script>
    <script>
        let labels_kontrak = '{{$labels_kontrak}}';
        let data_karyawan_kontrak = '{{$data_karyawan_kontrak}}';
        var labels_kontrak1 = labels_kontrak.replaceAll('&quot;', '"');
        var labels_kontrak2 = labels_kontrak1.replaceAll('&amp;', '&');
        var labels_kontrak3 = labels_kontrak2.replaceAll('[', '');
        var labels_kontrak4 = labels_kontrak3.replaceAll(']', '');
        var labels_kontrak5 = labels_kontrak3.replaceAll(',', ', ');
        var labels_kontrak6 = JSON.parse("[" + labels_kontrak5);
        var data_karyawan_kontrak1 = JSON.parse(data_karyawan_kontrak);
        // console.log(labels_kontrak6);
        var options_kontrak = {
            series: data_karyawan_kontrak1,
            chart: {
                width: 300,
                type: 'pie',
                toolbar: {
                    show: true
                }
            },
            labels: labels_kontrak6,
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
    </script>
    <script>
        let labels_status = '{{$labels_status}}';
        let data_karyawan_status = '{{$data_karyawan_status}}';
        var labels_status1 = labels_status.replaceAll('&quot;', '"');
        var labels_status2 = labels_status1.replaceAll('&amp;', '&');
        var labels_status3 = labels_status2.replaceAll('[', '');
        var labels_status4 = labels_status3.replaceAll(']', '');
        var labels_status5 = labels_status3.replaceAll(',', ', ');
        var labels_status6 = JSON.parse("[" + labels_status5);
        var data_karyawan_status1 = JSON.parse(data_karyawan_status);
        // console.log(labels_status6);
        var options_status = {
            series: data_karyawan_status1,
            chart: {
                width: 300,
                type: 'pie',
                toolbar: {
                    show: true
                }
            },
            labels: labels_status6,
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
    </script>
    @endsection