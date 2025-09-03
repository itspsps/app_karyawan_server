@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<!-- Categorie -->
<div class="fixed-content p-0" style=" border-radius: 10px; margin-top: 0%;box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
    <div class=" container" style="margin-top: -5%;">
        <div class="card" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="row">

                    <h5 class="dz-title">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" height="18px" width="18px" viewBox="0 0 512 512" xml:space="preserve">
                            <rect x="77.913" y="50.352" style="fill:#365558;" width="178.087" height="453.698" />
                            <rect x="256" y="50.352" style="fill:#687F82;" width="178.087" height="453.698" />
                            <polygon style="fill:#E6EAEA;" points="111.834,84.273 111.834,474.369 319.602,474.369 400.166,393.805 400.166,84.273 " />
                            <g>
                                <rect x="111.834" y="84.273" style="fill:#CDD4D5;" width="144.166" height="390.095" />
                                <polygon style="fill:#CDD4D5;" points="319.602,393.805 319.602,474.369 400.166,393.805  " />
                            </g>
                            <path style="fill:#FF8C29;" d="M306.165,50.352C302.127,26.289,281.211,7.95,256,7.95c-25.211,0-46.126,18.339-50.165,42.402  h-34.638v63.602h169.607V50.352H306.165z" />
                            <path style="fill:#F0353D;" d="M205.835,50.352h-34.638v63.602H256V7.95C230.789,7.95,209.874,26.289,205.835,50.352z" />
                            <circle style="fill:#4ACFD9;" cx="256" cy="253.88" r="93.284" />
                            <path style="fill:#0295AA;" d="M162.716,253.88c0,51.165,41.194,92.701,92.224,93.27V160.61  C203.911,161.179,162.716,202.715,162.716,253.88z" />
                            <path style="fill:#FFFFFF;" d="M224.199,299.992c-2.034,0-4.07-0.776-5.621-2.328l-25.441-25.441c-3.105-3.106-3.105-8.139,0-11.244  c3.105-3.104,8.139-3.104,11.243,0l19.82,19.82l68.581-68.581c3.105-3.104,8.139-3.104,11.243,0c3.105,3.106,3.105,8.139,0,11.244  l-74.203,74.203C228.268,299.216,226.233,299.992,224.199,299.992z" />
                            <path d="M434.087,42.402h-93.284h-28.318C305.306,17.472,282.542,0,256,0s-49.305,17.472-56.485,42.402h-28.318H77.913  c-4.391,0-7.95,3.559-7.95,7.95V504.05c0,4.392,3.56,7.95,7.95,7.95h356.174c4.391,0,7.95-3.559,7.95-7.95V50.352  C442.037,45.96,438.478,42.402,434.087,42.402z M392.215,385.855h-72.613c-4.391,0-7.95,3.559-7.95,7.95v72.613H119.785V92.224  h43.462v21.731c0,4.392,3.56,7.95,7.95,7.95h169.607c4.391,0,7.95-3.559,7.95-7.95V92.224h43.462V385.855z M380.971,401.756  l-53.419,53.419v-53.419H380.971z M256,15.901c21.045,0,38.846,15.042,42.324,35.767c0.007,0.045,0.02,0.086,0.028,0.129  c0.024,0.13,0.053,0.26,0.084,0.388c0.028,0.118,0.057,0.235,0.09,0.351c0.035,0.123,0.073,0.243,0.113,0.364  c0.041,0.122,0.086,0.243,0.133,0.361c0.042,0.107,0.085,0.213,0.131,0.318c0.058,0.13,0.121,0.258,0.186,0.385  c0.046,0.089,0.09,0.178,0.139,0.265c0.078,0.14,0.162,0.275,0.248,0.408c0.046,0.071,0.088,0.142,0.136,0.212  c0.103,0.151,0.213,0.294,0.325,0.436c0.038,0.049,0.074,0.101,0.114,0.148c0.156,0.187,0.318,0.368,0.49,0.538  c0.001,0.001,0.002,0.002,0.003,0.003c0.172,0.171,0.351,0.333,0.536,0.488c0.071,0.059,0.147,0.112,0.221,0.17  c0.116,0.09,0.231,0.18,0.352,0.263c0.094,0.066,0.193,0.125,0.29,0.186c0.106,0.067,0.212,0.134,0.321,0.195  c0.105,0.059,0.212,0.113,0.32,0.167c0.11,0.056,0.22,0.11,0.334,0.161c0.108,0.049,0.217,0.093,0.328,0.137  c0.122,0.049,0.245,0.093,0.37,0.136c0.105,0.035,0.209,0.07,0.316,0.102c0.144,0.042,0.289,0.078,0.436,0.112  c0.092,0.021,0.182,0.045,0.276,0.063c0.185,0.036,0.374,0.063,0.563,0.086c0.058,0.007,0.117,0.018,0.175,0.023  c0.255,0.025,0.513,0.04,0.774,0.04c0.004,0,0.008-0.001,0.013-0.001h26.685v25.971v21.732H179.147V84.273V58.302h26.688  c0.038,0,0.075-0.005,0.113-0.005c0.174-0.002,0.347-0.013,0.518-0.027c0.092-0.007,0.184-0.014,0.276-0.023  c0.172-0.019,0.34-0.047,0.509-0.077c0.092-0.017,0.184-0.032,0.276-0.051c0.159-0.035,0.315-0.077,0.471-0.121  c0.098-0.028,0.195-0.053,0.29-0.085c0.145-0.047,0.286-0.102,0.427-0.156c0.101-0.039,0.202-0.077,0.301-0.12  c0.134-0.058,0.262-0.124,0.391-0.189c0.1-0.05,0.2-0.099,0.297-0.154c0.127-0.071,0.25-0.148,0.373-0.227  c0.091-0.057,0.183-0.113,0.272-0.175c0.125-0.086,0.244-0.179,0.364-0.272c0.079-0.061,0.16-0.122,0.237-0.187  c0.122-0.102,0.237-0.211,0.353-0.321c0.068-0.065,0.137-0.127,0.202-0.194c0.114-0.117,0.222-0.239,0.329-0.363  c0.059-0.069,0.122-0.137,0.179-0.208c0.101-0.124,0.194-0.253,0.287-0.384c0.057-0.08,0.116-0.158,0.17-0.24  c0.082-0.124,0.157-0.252,0.232-0.382c0.056-0.098,0.114-0.194,0.166-0.294c0.061-0.118,0.118-0.239,0.174-0.36  c0.055-0.12,0.111-0.239,0.161-0.361c0.043-0.108,0.082-0.218,0.121-0.329c0.05-0.142,0.1-0.285,0.141-0.43  c0.03-0.102,0.054-0.206,0.079-0.31c0.038-0.156,0.074-0.312,0.104-0.472c0.007-0.04,0.019-0.08,0.026-0.12  C217.155,30.943,234.954,15.901,256,15.901z M426.137,496.099H85.863V58.302h77.383v18.021h-51.412c-4.391,0-7.95,3.559-7.95,7.95  v390.095c0,4.392,3.56,7.95,7.95,7.95h207.768c0.262,0,0.524-0.014,0.784-0.039c0.12-0.012,0.235-0.034,0.354-0.051  c0.138-0.02,0.278-0.036,0.414-0.064c0.136-0.026,0.266-0.064,0.399-0.098c0.119-0.03,0.239-0.056,0.355-0.091  c0.129-0.039,0.254-0.088,0.382-0.134c0.118-0.042,0.236-0.082,0.353-0.129c0.119-0.049,0.233-0.107,0.349-0.162  c0.119-0.056,0.24-0.109,0.356-0.172c0.111-0.059,0.216-0.127,0.324-0.192c0.115-0.069,0.231-0.134,0.343-0.209  c0.118-0.078,0.228-0.165,0.34-0.25c0.095-0.071,0.192-0.137,0.284-0.212c0.19-0.156,0.371-0.32,0.545-0.493  c0.012-0.013,0.025-0.022,0.037-0.034l80.563-80.563c0.011-0.011,0.019-0.022,0.03-0.033c0.174-0.176,0.34-0.358,0.497-0.549  c0.075-0.092,0.141-0.189,0.212-0.284c0.085-0.113,0.173-0.224,0.251-0.341c0.075-0.113,0.141-0.23,0.211-0.347  c0.064-0.106,0.13-0.211,0.189-0.32c0.064-0.12,0.119-0.243,0.176-0.365c0.053-0.113,0.109-0.224,0.158-0.339  c0.05-0.12,0.089-0.242,0.133-0.363c0.045-0.123,0.092-0.246,0.13-0.372c0.037-0.122,0.064-0.245,0.094-0.368  c0.032-0.128,0.068-0.255,0.094-0.386c0.029-0.144,0.046-0.288,0.066-0.434c0.016-0.112,0.037-0.223,0.049-0.336  c0.025-0.262,0.04-0.526,0.039-0.789V84.273c0-4.392-3.56-7.95-7.95-7.95h-51.411V58.302h77.383V496.099z" />
                            <path d="M256,152.646c-55.821,0-101.234,45.413-101.234,101.234S200.179,355.114,256,355.114s101.234-45.413,101.234-101.234  S311.821,152.646,256,152.646z M256,339.213c-47.053,0-85.333-38.28-85.333-85.333s38.28-85.333,85.333-85.333  s85.333,38.28,85.333,85.333S303.053,339.213,256,339.213z" />
                        </svg>
                        Daftar Interview
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card" style="margin-top: 0%;">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-izin-tab" data-bs-toggle="tab" data-bs-target="#nav-izin" type="button" role="tab" aria-controls="nav-izin" aria-selected="true">Hari Ini</button>
                        <button class="nav-link" id="nav-cuti-tab" data-bs-toggle="tab" data-bs-target="#nav-cuti" type="button" role="tab" aria-controls="nav-cuti" aria-selected="false">History</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent" style="margin-top: 2%;">
                    <div class="tab-pane fade show active" id="nav-izin" role="tabpanel" aria-labelledby="nav-izin-tab">
                        @if ($table->count() > 0)
                        @foreach ($table as $table)
                        <a id="btn_klik" href="{{ url('/interview/detail/'.$table->id) }}">
                            <div class="swiper-slide">
                                <div class="card job-post" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); margin-bottom: 4%;">
                                    <div class="card-body" style="padding: 6px;">
                                        <div class="media media-70">
                                            @if($table->User != '')
                                            @if($table->User->foto_karyawan != '')
                                            <img src="{{asset('../storage/app/public/foto_karyawan/'.$table->User->foto_karyawan)}}" alt="/">
                                            @else
                                            <img src="{{ asset('admin/assets/img/avatars/1.png') }}" alt="/">
                                            @endif
                                            @else
                                            <img src="{{ asset('admin/assets/img/avatars/1.png') }}" alt="/">
                                            @endif
                                        </div>
                                        <div class="card-info">
                                            <h6 class="title" style="font-size: 9pt;">{{ $table->Cv->nama_lengkap }}</h6>
                                            <span class="location">{{ $table->Jabatan->nama_jabatan }}({{ $table->Jabatan->Bagian->Divisi->Departemen->nama_departemen }})</span>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" viewBox="0 0 460 460" xml:space="preserve">
                                                    <g id="XMLID_1011_">
                                                        <path id="XMLID_1012_" style="fill:#354A67;" d="M230,0c127.03,0,230,102.97,230,230S357.03,460,230,460l-60-230L230,0z" />
                                                        <path id="XMLID_1013_" style="fill:#466289;" d="M230,460C102.97,460,0,357.03,0,230S102.97,0,230,0V460z" />
                                                        <path id="XMLID_1014_" style="fill:#BEC8D6;" d="M230,420l-20-200l210,10C420,334.77,334.77,420,230,420z" />
                                                        <path id="XMLID_1015_" style="fill:#DAE0E7;" d="M230,40c104.77,0,190,85.23,190,190H210L230,40z" />
                                                        <path id="XMLID_1016_" style="fill:#DAE0E7;" d="M230,230v190c-104.77,0-190-85.23-190-190l95-30L230,230z" />
                                                        <path id="XMLID_1017_" style="fill:#FFFFFF;" d="M230,40v190H40C40,125.23,125.23,40,230,40z" />

                                                        <rect id="XMLID_1018_" x="142.496" y="89.424" transform="matrix(-0.866 0.5 -0.5 -0.866 346.103 116.1065)" style="fill:#DAE0E7;" width="29.999" height="29.999" />

                                                        <rect id="XMLID_1019_" x="89.423" y="142.503" transform="matrix(-0.5 0.866 -0.866 -0.5 293.0349 145.8244)" style="fill:#DAE0E7;" width="29.999" height="29.999" />

                                                        <rect id="XMLID_1020_" x="89.419" y="287.503" transform="matrix(0.5 0.866 -0.866 0.5 314.1816 60.8195)" style="fill:#BEC8D6;" width="29.999" height="29.999" />

                                                        <rect id="XMLID_1021_" x="142.505" y="340.583" transform="matrix(0.866 0.5 -0.5 0.866 198.8977 -31.1126)" style="fill:#BEC8D6;" width="29.999" height="29.999" />

                                                        <rect id="XMLID_1022_" x="287.508" y="340.583" transform="matrix(0.866 -0.5 0.5 0.866 -137.2649 198.8984)" style="fill:#A3B1C4;" width="29.999" height="29.999" />

                                                        <rect id="XMLID_1023_" x="340.582" y="287.492" transform="matrix(0.5 -0.866 0.866 0.5 -84.1765 459.1824)" style="fill:#A3B1C4;" width="29.999" height="29.999" />
                                                        <polygon id="XMLID_1024_" style="fill:#354A67;" points="333.241,106.256 230.711,208.787 230.711,251.213 354.454,127.47  " />
                                                        <polygon id="XMLID_1025_" style="fill:#466289;" points="181.213,159.289 160,180.502 230.711,251.213 230.711,208.787  " />
                                                        <rect id="XMLID_1026_" x="230" y="360" style="fill:#A3B1C4;" width="15" height="30" />
                                                        <rect id="XMLID_1027_" x="230" y="70" style="fill:#BEC8D6;" width="15" height="30" />
                                                        <rect id="XMLID_1028_" x="215" y="360" style="fill:#BEC8D6;" width="15" height="30" />
                                                        <rect id="XMLID_1029_" x="215" y="70" style="fill:#DAE0E7;" width="15" height="30" />
                                                        <rect id="XMLID_1030_" x="360" y="230" style="fill:#A3B1C4;" width="30" height="15" />
                                                        <rect id="XMLID_1031_" x="360" y="215" style="fill:#BEC8D6;" width="30" height="15" />
                                                        <rect id="XMLID_1032_" x="70" y="230" style="fill:#BEC8D6;" width="30" height="15" />
                                                        <rect id="XMLID_1033_" x="70" y="215" style="fill:#DAE0E7;" width="30" height="15" />
                                                    </g>
                                                </svg>
                                                <span style="font-size: 9pt;" class="ms-2 price-item">{{\Carbon\Carbon::parse($table->tanggal_interview_manager)->format('d-m-Y')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                        @else
                        <div class="text-center">
                            <h5>Tidak Ada Data</h5>
                        </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-cuti" role="tabpanel" aria-labelledby="nav-cuti-tab">
                        @if ($table->count() > 0)

                        @else
                        <div class="text-center">
                            <h5>Tidak Ada Data</h5>
                        </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-penugasan" role="tabpanel" aria-labelledby="nav-penugasan-tab">
                        @if($table->count() > 0)

                        @else
                        <div class="text-center">
                            <h5>Tidak Ada Data</h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Categorie End -->
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
    $(document).ready(function() {
        load_data();

        function load_data(filter_month = '') {
            console.log(filter_month);
            var table1 = $('#table_absensi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                "bPaginate": false,
                searching: false,
                ajax: {
                    url: "{{ route('get_table_absensi') }}",
                    data: {
                        filter_month: filter_month,
                    }
                },
                columns: [{
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'tanggal_masuk',
                        name: 'tanggal_masuk'
                    },
                    {
                        data: 'jam_absen',
                        name: 'jam_absen'
                    },
                    {
                        data: 'tanggal_pulang',
                        name: 'tanggal_pulang'
                    },
                    {
                        data: 'jam_pulang',
                        name: 'jam_pulang'
                    },
                    {
                        data: 'status_absen',
                        name: 'status_absen'
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });
        }

        function load_absensi(filter_month = '') {
            $.ajax({
                url: "{{route('get_count_absensi_home')}}",
                data: {
                    filter_month: filter_month,
                },
                type: "GET",
                error: function() {
                    alert('Something is wrong');
                },
                success: function(data) {
                    $('#count_absen_hadir').html(data);
                    console.log(data)
                }
            });
        }
        $('#month').change(function() {
            filter_month = $(this).val();
            console.log(filter_month);
            $('#table_absensi').DataTable().destroy();
            load_data(filter_month);
            load_absensi(filter_month);


        })
    });
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
</script>
@endsection