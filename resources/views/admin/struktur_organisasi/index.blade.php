@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link href="https://cdn.syncfusion.com/ej2/material.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/orgchart/4.0.1/css/jquery.orgchart.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/orgchart/4.0.1/css/jquery.orgchart.min.css"> -->
<style type="text/css">
    .my-swal {
        z-index: X;
    }
</style>
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">STRUKTUR ORGANISASI</h5>
                        <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link waves-effect waves-light @if($holding=='sp') active @endif" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="true">
                                    <i class="tf-icons mdi mdi-family-tree me-1"></i> SP
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link waves-effect waves-light @if($holding=='sps') active @endif" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile" aria-selected="false" tabindex="-1">
                                    <i class="tf-icons mdi mdi-family-tree me-1"></i> SPS
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link waves-effect waves-light @if($holding=='sip') active @endif" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-messages" aria-controls="navs-pills-top-messages" aria-selected="false" tabindex="-1">
                                    <i class="tf-icons mdi mdi-family-tree me-1"></i> SIP
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade @if($holding=='sp') active show @endif" id="navs-pills-top-home" role="tabpanel">

                        <div class="card-body">

                            <div id="chartDiv1" class="chartDiv" style="max-width: 100%;  height: 800px"></div>
                            <div id="keterangan_karyawan_sp" style="min-height:10px;text-align: left"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($holding=='sps') active show @endif" id="navs-pills-top-profile" role="tabpanel">

                        <div class="card-body">

                            <div id="chartDiv2" class="chartDiv2" style="max-width: 100%; height: 800px"></div>
                            <div id="keterangan_karyawan_sps" style="min-height:10px;text-align: left"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($holding=='sip') active show @endif" id="navs-pills-top-messages" role="tabpanel">

                        <div class="card-body">

                            <div id="chartDiv3" class="chartDiv3" style="max-width: 100%; height: 800px"></div>
                            <div id="keterangan_karyawan_sip" style="min-height:10px;text-align: left"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Transactions -->
    <!--/ Data Tables -->
</div>
</div>
@endsection
@section('js')
<script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js" type="text/javascript"></script>
<script src="https://code.jscharting.com/latest/jscharting.js"></script>
<script type="text/javascript" src="https://code.jscharting.com/latest/modules/types.js"></script>
<script type="text/javascript" src="https://code.jscharting.com/latest/modules/toolbar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    var selectedPoint;
    var highlightColor = '#5C6BC0',
        mutedHighlightColor = '#9FA8DA',
        mutedFill = '#f3f4fa',
        selectedFill = '#E8EAF6',
        normalFill = 'white',
        highlightDirection = 'Down';
    var points = <?php echo json_encode($user) ?>;

    // console.log(points);
    var chart = JSC.chart('chartDiv1', {
        debug: true,
        type: 'organizational',
        defaultTooltip_enabled: true,

        /* These options will apply to all annotations including point nodes. */
        defaultAnnotation: {
            padding: [5, 10],
            margin: 6
        },
        annotations: [{
            position: 'bottom',
            label_text: 'STRUKTUR ORGANISASI CV. SUMBER PANGAN'
        }],

        defaultSeries: {
            color: normalFill,
            /* Point selection is disabled because it is managed manually with point click events. */
            pointSelection: false
        },


        defaultPoint: {
            focusGlow: true,
            connectorLine: {
                color: '#e0e0e0',
                radius: [10, 3]
            },
            label: {
                text: '%photo%name<br><span style="color:#9E9E9E">%role</span>',
                style_color: 'black'
            },
            outline: {
                color: '#e0e0e0',
                width: 1
            },
            annotation: {
                syncHeight_with: 'level'
            },
            states: {
                mute: {
                    opacity: 0.8,
                    outline: {
                        color: mutedHighlightColor,
                        opacity: 0.9,
                        width: 2
                    }
                },
                select: {
                    enabled: true,
                    outline: {
                        color: highlightColor,
                        width: 2
                    },
                    color: selectedFill
                },
                hover: {
                    outline: {
                        color: mutedHighlightColor,
                        width: 2
                    },
                    color: mutedFill
                }
            },
            events: {
                click: pointClick,
                mouseOver: pointMouseOver,
                mouseOut: pointMouseOut
            }
        },
        series: [{
            points: points
        }]
    });

    /** 
     * Event Handlers 
     */

    function pointClick() {
        var point = this,
            chart = point.chart;
        // console.log(point.userOptions.attributes.role, chart);
        resetStyles(chart);
        if (point.id === selectedPoint) {
            selectedPoint = undefined;
            return;
        }
        switch (highlightDirection) {
            case 'Up':
                highlightUp(point.id);
                break;
            case 'Down':
                highlightDown(point.id);
                break;
            case 'Both':
                highlightUp(point.id);
                highlightDown(point.id);
        }
        chart
            .series()
            .points([point.id, 'up'])
            .options({
                muted: true
            });

        function highlightUp(id) {
            chart.connectors([id, 'up'], {

                width: 2
            });
            chart
                .series()
                .points([id, 'up'])
                .options({
                    selected: true,
                    muted: false
                });
        }

        // Use the muted state to highlight points down the tree. 
        function highlightDown(id) {
            chart.connectors([id, 'down'], {

                width: 2
            });
            chart
                .series()
                .points([id, 'down'])
                .options({
                    selected: false,
                    muted: true
                });
        }
        updateInfo(point);
        selectedPoint = point.id;
        styleSelectedPoint(chart);
    }

    function reset(c) {
        c.connectors();
        c.series()
            .points()
            .options({
                selected: false
            });
        var el = document.getElementById('keterangan_karyawan_sp');
        el.innerHTML = '';
    }

    function updateInfo(point) {
        var chart = point.chart,
            pathSelection = [
                point.id,
                highlightDirection
            ],
            paths = chart.connectors(pathSelection, {});
        var html =
            ' Daftar Karyawan : <br/>';

        var pathList =
            '<li>' +
            point.userOptions.user +
            '</li>';
        console.log(point.userOptions.user);
        html += '<ul>' + pathList + '</ul>';

        var el = document.getElementById('keterangan_karyawan_sp');
        el.innerHTML = html;

        function idToPointName(id) {
            var point = chart.series().points(id);
            var user = chart.series().points(user);
            console.log(point.x, point.id);
            return point.x || point.name;
        }
    }

    function pointMouseOver() {
        var point = this,
            chart = point.chart;
        chart.connectors([point.id, 'up'], {
            color: mutedHighlightColor,
            width: 2
        });
    }

    function pointMouseOut() {
        var point = this,
            chart = point.chart;
        // Reset point and line styling. 
        resetStyles(chart);
        // Style clicked points 
        styleSelectedPoint(chart);
        return false;
    }

    function styleSelectedPoint(chart) {
        if (selectedPoint) {
            chart.connectors([selectedPoint, 'up'], {
                color: highlightColor,
                width: 2
            });
            chart
                .series()
                .points([selectedPoint, 'up'])
                .options({
                    selected: true,
                    muted: false
                });
        }
    }

    function resetStyles(chart) {
        chart.connectors();
        chart
            .series()
            .points()
            .options({
                selected: false,
                muted: false
            });
    }

    function getImgText(name) {
        return (
            '<img width=50 height=50 style="border-radius:50%;" align=center margin_bottom=4 margin_top=4 src=' +
            name +
            '><br>'
        );
    }
</script>
<script>
    var selectedPoint1;
    var highlightColor1 = '#5C6BC0',
        mutedHighlightColor1 = '#9FA8DA',
        mutedFill1 = '#f3f4fa',
        selectedFill1 = '#E8EAF6',
        normalFill1 = 'white',
        highlightDirection1 = 'Down';
    var points1 = <?php echo json_encode($user1) ?>;

    // console.log(points);
    var chart1 = JSC.chart('chartDiv2', {
        debug: true,
        type: 'organizational',
        defaultTooltip_enabled: true,

        /* These options will apply to all annotations including point nodes. */
        defaultAnnotation: {
            padding: [5, 10],
            margin: 6
        },
        annotations: [{
            position: 'bottom',
            label_text: 'STRUKTUR ORGANISASI PT. SURYA PANGAN SEMESTA'
        }],

        defaultSeries: {
            color: normalFill,
            /* Point selection is disabled because it is managed manually with point click events. */
            pointSelection: false
        },


        defaultPoint: {
            focusGlow: true,
            connectorLine: {
                color: '#e0e0e0',
                radius: [10, 3]
            },
            label: {
                text: '%photo%name<br><span style="color:#9E9E9E">%role</span>',
                style_color: 'black'
            },
            outline: {
                color: '#e0e0e0',
                width: 1
            },
            annotation: {
                syncHeight_with: 'level'
            },
            states: {
                mute: {
                    opacity: 0.8,
                    outline: {
                        color: mutedHighlightColor1,
                        opacity: 0.9,
                        width: 2
                    }
                },
                select: {
                    enabled: true,
                    outline: {
                        color: highlightColor1,
                        width: 2
                    },
                    color: selectedFill1
                },
                hover: {
                    outline: {
                        color: mutedHighlightColor1,
                        width: 2
                    },
                    color: mutedFill1
                }
            },
            events: {
                click: pointClick1,
                mouseOver: pointMouseOver1,
                mouseOut: pointMouseOut1
            }
        },
        series: [{
            points: points1
        }]
    });

    /** 
     * Event Handlers 
     */

    function pointClick1() {
        var point = this,
            chart = point.chart;
        // console.log(point.userOptions.attributes.role, chart);
        resetStyles1(chart);
        if (point.id === selectedPoint1) {
            selectedPoint1 = undefined;
            return;
        }
        switch (highlightDirection1) {
            case 'Up':
                highlightUp(point.id);
                break;
            case 'Down':
                highlightDown1(point.id);
                break;
            case 'Both':
                highlightUp1(point.id);
                highlightDown1(point.id);
        }
        chart
            .series()
            .points([point.id, 'up'])
            .options({
                muted: true
            });

        function highlightUp1(id) {
            chart.connectors([id, 'up'], {

                width: 2
            });
            chart
                .series()
                .points([id, 'up'])
                .options({
                    selected: true,
                    muted: false
                });
        }

        // Use the muted state to highlight points down the tree. 
        function highlightDown1(id) {
            chart.connectors([id, 'down'], {

                width: 2
            });
            chart
                .series()
                .points([id, 'down'])
                .options({
                    selected: false,
                    muted: true
                });
        }
        updateInfo1(point);
        selectedPoint1 = point.id;
        styleSelectedPoint1(chart);
    }

    function reset(c) {
        c.connectors();
        c.series()
            .points()
            .options({
                selected: false
            });
        var el1 = document.getElementById('keterangan_karyawan_sps');
        el1.innerHTML = '';
    }

    function updateInfo1(point) {
        var chart = point.chart,
            pathSelection = [
                point.id,
                highlightDirection
            ],
            paths = chart.connectors(pathSelection, {});
        var html =
            ' Daftar Karyawan : <br/>';

        var pathList =
            '<li>' +
            point.userOptions.user +
            '</li>';
        console.log(point.userOptions.user);
        html += '<ul>' + pathList + '</ul>';

        var el = document.getElementById('keterangan_karyawan_sps');
        el.innerHTML = html;

        function idToPointName1(id) {
            var point = chart.series().points(id);
            var user = chart.series().points(user);
            console.log(point.x, point.id);
            return point.x || point.name;
        }
    }

    function pointMouseOver1() {
        var point = this,
            chart = point.chart;
        chart.connectors([point.id, 'up'], {
            color: mutedHighlightColor,
            width: 2
        });
    }

    function pointMouseOut1() {
        var point = this,
            chart = point.chart;
        // Reset point and line styling. 
        resetStyles1(chart);
        // Style clicked points 
        styleSelectedPoint1(chart);
        return false;
    }

    function styleSelectedPoint1(chart) {
        if (selectedPoint1) {
            chart.connectors([selectedPoint1, 'up'], {
                color: highlightColor1,
                width: 2
            });
            chart
                .series()
                .points([selectedPoint1, 'up'])
                .options({
                    selected: true,
                    muted: false
                });
        }
    }

    function resetStyles1(chart) {
        chart.connectors();
        chart
            .series()
            .points()
            .options({
                selected: false,
                muted: false
            });
    }

    function getImgText1(name) {
        return (
            '<img width=50 height=50 style="border-radius:50%;" align=center margin_bottom=4 margin_top=4 src=' +
            name +
            '><br>'
        );
    }
</script>

<script>
    var selectedPoint2;
    var highlightColor2 = '#5C6BC0',
        mutedHighlightColor2 = '#9FA8DA',
        mutedFill2 = '#f3f4fa',
        selectedFill2 = '#E8EAF6',
        normalFill2 = 'white',
        highlightDirection2 = 'Down';
    var points2 = <?php echo json_encode($user2) ?>;


    console.log(points2);
    var chart2 = JSC.chart('chartDiv3', {
        debug: true,
        type: 'organizational',
        defaultTooltip_enabled: true,

        /* These options will apply to all annotations including point nodes. */
        defaultAnnotation: {
            padding: [5, 10],
            margin: 6
        },
        annotations: [{
            position: 'bottom',
            label_text: 'STRUKTUR ORGANISASI CV. SURYA INTI PANGAN'
        }],

        defaultSeries: {
            color: normalFill,
            /* Point selection is disabled because it is managed manually with point click events. */
            pointSelection: false
        },
        defaultPoint: {
            focusGlow: false,
            connectorLine: {
                color: '#e0e0e0',
                radius: [10, 3]
            },
            label: {
                text: '%photo%name<br><span style="color:#9E9E9E">%role</span>',
                style_color: 'black'
            },
            outline: {
                color: '#e0e0e0',
                width: 1
            },
            annotation: {
                syncHeight_with: 'level'
            },
            states: {
                mute: {
                    opacity: 0.8,
                    outline: {
                        color: mutedHighlightColor2,
                        opacity: 0.9,
                        width: 2
                    }
                },
                select: {
                    enabled: true,
                    outline: {
                        color: highlightColor2,
                        width: 2
                    },
                    color: selectedFill2
                },
                hover: {
                    outline: {
                        color: mutedHighlightColor2,
                        width: 2
                    },
                    color: mutedFill2
                }
            },
            events: {
                click: pointClick2,
                mouseOver: pointMouseOver2,
                mouseOut: pointMouseOut2
            }
        },
        series: [{
            points: points2
        }]
    });

    /** 
     * Event Handlers 
     */

    function pointClick2() {
        var point = this,
            chart = point.chart;
        // console.log(point.userOptions.attributes.role, chart);
        resetStyles2(chart);
        if (point.id === selectedPoint2) {
            selectedPoint2 = undefined;
            return;
        }
        switch (highlightDirection1) {
            case 'Up':
                highlightUp(point.id);
                break;
            case 'Down':
                highlightDown2(point.id);
                break;
            case 'Both':
                highlightUp2(point.id);
                highlightDown2(point.id);
        }
        chart
            .series()
            .points([point.id, 'up'])
            .options({
                muted: true
            });

        function highlightUp2(id) {
            chart.connectors([id, 'up'], {

                width: 2
            });
            chart
                .series()
                .points([id, 'up'])
                .options({
                    selected: true,
                    muted: false
                });
        }

        // Use the muted state to highlight points down the tree. 
        function highlightDown2(id) {
            chart.connectors([id, 'down'], {

                width: 2
            });
            chart
                .series()
                .points([id, 'down'])
                .options({
                    selected: false,
                    muted: true
                });
        }
        updateInfo2(point);
        selectedPoint2 = point.id;
        styleSelectedPoint2(chart);
    }

    function reset(c) {
        c.connectors();
        c.series()
            .points()
            .options({
                selected: false
            });
        var el2 = document.getElementById('keterangan_karyawan_sip');
        el2.innerHTML = '';
    }

    function updateInfo2(point) {
        var chart = point.chart,
            pathSelection = [
                point.id,
                highlightDirection
            ],
            paths = chart.connectors(pathSelection, {});
        var html =
            ' Daftar Karyawan : <br/>';

        var pathList =
            '<li>' +
            point.userOptions.user +
            '</li>';
        console.log(point.userOptions.user);
        html += '<ul>' + pathList + '</ul>';

        var el = document.getElementById('keterangan_karyawan_sip');
        el.innerHTML = html;

        function idToPointName2(id) {
            var point = chart.series().points(id);
            var user = chart.series().points(user);
            console.log(point.x, point.id);
            return point.x || point.name;
        }
    }

    function pointMouseOver2() {
        var point = this,
            chart = point.chart;
        chart.connectors([point.id, 'up'], {
            color: mutedHighlightColor,
            width: 2
        });
    }

    function pointMouseOut2() {
        var point = this,
            chart = point.chart;
        // Reset point and line styling. 
        resetStyles2(chart);
        // Style clicked points 
        styleSelectedPoint2(chart);
        return false;
    }

    function styleSelectedPoint2(chart) {
        if (selectedPoint2) {
            chart.connectors([selectedPoint2, 'up'], {
                color: highlightColor2,
                width: 2
            });
            chart
                .series()
                .points([selectedPoint2, 'up'])
                .options({
                    selected: true,
                    muted: false
                });
        }
    }

    function resetStyles2(chart) {
        chart.connectors();
        chart
            .series()
            .points()
            .options({
                selected: false,
                muted: false
            });
    }

    function getImgText2(name) {
        return (
            '<img width=50 height=50 style="border-radius:50%;" align=center margin_bottom=4 margin_top=4 src=' +
            name +
            '><br>'
        );
    }
</script>


@endsection