@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<!-- Categorie -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvas_logout" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-body text-center small">
        <h5 class="title">KONFIRMASI LOGOUT APP</h5>
        <p>Apakah Anda Ingin Keluar Dari Aplikasi Ini ?</p>
        <a id="btn_klik" href="{{url('/logout')}}" class="btn btn-sm btn-danger light pwa-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M5.46967 12.5303C5.17678 12.2374 5.17678 11.7626 5.46967 11.4697L7.46967 9.46967C7.76257 9.17678 8.23744 9.17678 8.53033 9.46967C8.82323 9.76256 8.82323 10.2374 8.53033 10.5303L7.81066 11.25L15 11.25C15.4142 11.25 15.75 11.5858 15.75 12C15.75 12.4142 15.4142 12.75 15 12.75L7.81066 12.75L8.53033 13.4697C8.82323 13.7626 8.82323 14.2374 8.53033 14.5303C8.23744 14.8232 7.76257 14.8232 7.46967 14.5303L5.46967 12.5303Z" fill="#1C274C" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9453 1.25H15.0551C16.4227 1.24998 17.525 1.24996 18.392 1.36652C19.2921 1.48754 20.05 1.74643 20.6519 2.34835C21.2538 2.95027 21.5127 3.70814 21.6337 4.60825C21.7503 5.47522 21.7502 6.57754 21.7502 7.94513V16.0549C21.7502 17.4225 21.7503 18.5248 21.6337 19.3918C21.5127 20.2919 21.2538 21.0497 20.6519 21.6517C20.05 22.2536 19.2921 22.5125 18.392 22.6335C17.525 22.75 16.4227 22.75 15.0551 22.75H13.9453C12.5778 22.75 11.4754 22.75 10.6085 22.6335C9.70836 22.5125 8.95048 22.2536 8.34857 21.6517C7.94963 21.2527 7.70068 20.7844 7.54305 20.2498C6.59168 20.2486 5.79906 20.2381 5.15689 20.1518C4.39294 20.0491 3.7306 19.8268 3.20191 19.2981C2.67321 18.7694 2.45093 18.1071 2.34822 17.3431C2.24996 16.6123 2.24998 15.6865 2.25 14.5537V9.44631C2.24998 8.31349 2.24996 7.38774 2.34822 6.65689C2.45093 5.89294 2.67321 5.2306 3.20191 4.7019C3.7306 4.17321 4.39294 3.95093 5.15689 3.84822C5.79906 3.76188 6.59168 3.75142 7.54305 3.75017C7.70068 3.21562 7.94963 2.74729 8.34857 2.34835C8.95048 1.74643 9.70836 1.48754 10.6085 1.36652C11.4754 1.24996 12.5778 1.24998 13.9453 1.25ZM7.25197 17.0042C7.25555 17.6487 7.2662 18.2293 7.30285 18.7491C6.46836 18.7459 5.848 18.7312 5.35676 18.6652C4.75914 18.5848 4.46611 18.441 4.26257 18.2374C4.05903 18.0339 3.91519 17.7409 3.83484 17.1432C3.7516 16.5241 3.75 15.6997 3.75 14.5V9.5C3.75 8.30029 3.7516 7.47595 3.83484 6.85676C3.91519 6.25914 4.05903 5.9661 4.26257 5.76256C4.46611 5.55902 4.75914 5.41519 5.35676 5.33484C5.848 5.2688 6.46836 5.25415 7.30285 5.25091C7.2662 5.77073 7.25555 6.35129 7.25197 6.99583C7.24966 7.41003 7.58357 7.74768 7.99778 7.74999C8.41199 7.7523 8.74964 7.41838 8.75194 7.00418C8.75803 5.91068 8.78643 5.1356 8.89448 4.54735C8.9986 3.98054 9.16577 3.65246 9.40923 3.40901C9.68599 3.13225 10.0746 2.9518 10.8083 2.85315C11.5637 2.75159 12.5648 2.75 14.0002 2.75H15.0002C16.4356 2.75 17.4367 2.75159 18.1921 2.85315C18.9259 2.9518 19.3144 3.13225 19.5912 3.40901C19.868 3.68577 20.0484 4.07435 20.1471 4.80812C20.2486 5.56347 20.2502 6.56459 20.2502 8V16C20.2502 17.4354 20.2486 18.4365 20.1471 19.1919C20.0484 19.9257 19.868 20.3142 19.5912 20.591C19.3144 20.8678 18.9259 21.0482 18.1921 21.1469C17.4367 21.2484 16.4356 21.25 15.0002 21.25H14.0002C12.5648 21.25 11.5637 21.2484 10.8083 21.1469C10.0746 21.0482 9.68599 20.8678 9.40923 20.591C9.16577 20.3475 8.9986 20.0195 8.89448 19.4527C8.78643 18.8644 8.75803 18.0893 8.75194 16.9958C8.74964 16.5816 8.41199 16.2477 7.99778 16.25C7.58357 16.2523 7.24966 16.59 7.25197 17.0042Z" fill="#1C274C" />
            </svg>
            &nbsp;Logout
        </a>
        <a href="javascrpit:void(0);" class="btn btn-sm light btn-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">Batal</a>
    </div>
</div>
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
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                ],
                order: [
                    [0, 'asc']
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