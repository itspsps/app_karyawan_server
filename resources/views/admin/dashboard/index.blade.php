@extends('admin.layouts.dashboard')
@section('isi')
<div class="container-xxl flex-grow-1 container-p-y">
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
                    </div>
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