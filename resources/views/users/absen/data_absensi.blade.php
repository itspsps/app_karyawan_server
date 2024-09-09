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
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="18px" width="18px" version="1.1" id="Capa_1" viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <path style="fill:#424A60;" d="M24,35v-0.375V34.25v-8.625V25.25h0.034C24.013,25.374,24,25.499,24,25.625   c0-2.437,3.862-4.552,9.534-5.625H3.608C1.616,20,0,21.615,0,23.608v11.783C0,37.385,1.616,39,3.608,39H24V35z" />
                            </g>
                            <g>
                                <path style="fill:#556080;" d="M24.034,53H24v-9v-0.375V43.25V39H3.608C1.616,39,0,40.615,0,42.608v11.783   C0,56.385,1.616,58,3.608,58h28.718C27.601,56.931,24.378,55.103,24.034,53z" />
                            </g>
                            <path style="fill:#556080;" d="M54.392,20H3.608C1.616,20,0,18.384,0,16.392V4.608C0,2.616,1.616,1,3.608,1h50.783  C56.384,1,58,2.616,58,4.608v11.783C58,18.384,56.384,20,54.392,20z" />
                            <circle style="fill:#7383BF;" cx="9.5" cy="10.5" r="3.5" />
                            <circle style="fill:#7383BF;" cx="49" cy="9" r="1" />
                            <circle style="fill:#7383BF;" cx="45" cy="9" r="1" />
                            <circle style="fill:#7383BF;" cx="51" cy="12" r="1" />
                            <circle style="fill:#7383BF;" cx="47" cy="12" r="1" />
                            <circle style="fill:#7383BF;" cx="41" cy="9" r="1" />
                            <circle style="fill:#7383BF;" cx="43" cy="12" r="1" />
                            <circle style="fill:#7383BF;" cx="37" cy="9" r="1" />
                            <circle style="fill:#7383BF;" cx="39" cy="12" r="1" />
                            <circle style="fill:#7383BF;" cx="33" cy="9" r="1" />
                            <circle style="fill:#7383BF;" cx="35" cy="12" r="1" />
                            <circle style="fill:#7383BF;" cx="9.5" cy="29.5" r="3.5" />
                            <circle style="fill:#7383BF;" cx="9.5" cy="48.5" r="3.5" />
                            <g>
                                <path style="fill:#1A9172;" d="M42,48.75c-9.941,0-18-2.854-18-6.375V53h0.034c0.548,3.346,8.381,6,17.966,6s17.418-2.654,17.966-6   H60V42.375C60,45.896,51.941,48.75,42,48.75z" />
                                <path style="fill:#1A9172;" d="M24,42v0.375c0-0.126,0.013-0.251,0.034-0.375H24z" />
                                <path style="fill:#1A9172;" d="M59.966,42C59.987,42.124,60,42.249,60,42.375V42H59.966z" />
                            </g>
                            <g>
                                <path style="fill:#25AE88;" d="M42,38c-9.941,0-18-2.854-18-6.375V42.75h0.034c0.548,3.346,8.381,6,17.966,6s17.418-2.654,17.966-6   H60V31.625C60,35.146,51.941,38,42,38z" />
                                <path style="fill:#25AE88;" d="M24,31.25v0.375c0-0.126,0.013-0.251,0.034-0.375H24z" />
                                <path style="fill:#25AE88;" d="M59.966,31.25C59.987,31.374,60,31.499,60,31.625V31.25H59.966z" />
                            </g>
                            <ellipse style="fill:#88C057;" cx="42" cy="21.375" rx="18" ry="6.375" />
                            <g>
                                <path style="fill:#61B872;" d="M42,27.75c-9.941,0-18-2.854-18-6.375V32h0.034c0.548,3.346,8.381,6,17.966,6s17.418-2.654,17.966-6   H60V21.375C60,24.896,51.941,27.75,42,27.75z" />
                                <path style="fill:#61B872;" d="M24,21v0.375c0-0.126,0.013-0.251,0.034-0.375H24z" />
                                <path style="fill:#61B872;" d="M59.966,21C59.987,21.124,60,21.249,60,21.375V21H59.966z" />
                            </g>
                        </svg>
                        Record Data Absensi
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="title-bar">
                    <div class="input-group">
                        <h5>
                            <label for="month">Filter :</label>
                            <select class="month" style="width: max-content;border-radius: 0px; background-color:transparent; color: var(--primary); border: none;outline: none;" name="" id="month">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                            &nbsp;{{$thnskrg}}
                        </h5>
                    </div>
                </div>
                <div class="card" style="margin-top: -10%;">
                    <table id="table_absensi" class="table table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal&nbsp;Masuk</th>
                                <th scope="col">Jam&nbsp;Masuk</th>
                                <th scope="col">Tanggal&nbsp;Pulang</th>
                                <th scope="col">Jam&nbsp;Pulang</th>
                                <th scope="col">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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