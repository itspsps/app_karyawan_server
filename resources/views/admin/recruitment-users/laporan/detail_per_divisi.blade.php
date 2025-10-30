@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" />
    <link rel="preload" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style type="text/css">
        .my-swal {
            z-index: X;
        }

        /* ukuran teks di area pilihan (input select2) */
        .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.875rem !important;
            /* Bootstrap small (14px) */
            min-height: calc(1.5em + 0.75rem + 2px);
            /* biar tinggi konsisten */
        }

        /* ukuran teks di dropdown list */
        .select2-container--bootstrap-5 .select2-results__option {
            font-size: 0.875rem !important;
        }

        /* Fokus warna primary */
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25) !important;
        }

        /* Background dan teks saat option terpilih */
        .select2-container--bootstrap-5 .select2-results__option--selected {
            background-color: var(--bs-primary) !important;
            color: #fff !important;
        }

        /* Hover option */
        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
            color: var(--bs-primary) !important;
        }

        /* ukuran huruf untuk pilihan yang sudah dipilih (tag dalam box) */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            font-size: 0.75rem;
            /* kecilin text */
            padding: 2px 6px;
            /* biar nggak terlalu tinggi */
            line-height: 1.2;
        }

        /* icon "x" di tag */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            font-size: 0.7rem;
            margin-right: 2px;
        }

        /* tulisan placeholder / hasil render */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
            font-size: 0.8rem;
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
                            <h5 class="card-title m-0 me-2">
                                {{ $nama_divisi->nama_divisi }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($divisi as $div)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">
                                    {{ $div->Cv->nama_lengkap }}
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table" id="table_riwayat" style="width: 100%; font-size: small;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Riwayat&nbsp;Status</th>
                                        <th>Riwayat&nbsp;Waktu</th>
                                        <th>Rentang</th>
                                        <th>Waktu&nbsp;Feedback</th>
                                        <th>Riwayat&nbsp;Feedback</th>
                                    </tr>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($div->recruitmentUserRecord as $per)
                                    <tbody class="table-border-bottom-0">
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            @if ($per->status_user == '0')
                                                <span class="badge bg-label-primary">Review HRD</span>
                                            @elseif ($per->status_user == '1')
                                                <span class="badge bg-label-warning">Panggilan Wawancara</span>
                                            @elseif ($per->status_user == '2')
                                                <span class="badge bg-label-info">Lamaran Diterima</span>
                                            @elseif ($per->status_user == '3')
                                                <span class="badge bg-label-danger">Ditolak</span>
                                            @elseif ($per->status == '1a')
                                                <span class="badge bg-label-success">Hadir Interview</span>
                                            @elseif ($per->status == '2a')
                                                <span class="badge bg-label-danger">Tidak Hadir Interview</span>
                                            @elseif ($per->status == '1b')
                                                <span class="badge bg-label-warning">Interview Manager</span>
                                            @elseif ($per->status == '2b')
                                                <span class="badge bg-label-success">Diterima Bekerja</span>
                                            @elseif ($per->status == '3b')
                                                <span class="badge bg-label-danger">Tidak Lolos</span>
                                            @elseif ($per->status == '4b')
                                                <span class="badge bg-label-warning">Lolos Interviw
                                                    Manager</span>
                                            @elseif ($per->status == '5b')
                                                <span class="badge bg-label-danger">Ditolak Manager</span>
                                            @elseif ($per->status == '6b')
                                                <span class="badge bg-label-warning">Penawaran Posisi Lain</span>
                                            @elseif ($per->status == '7b')
                                                <span class="badge bg-label-success">Lolos Posisi Lain</span>
                                            @elseif ($per->status == '8b')
                                                <span class="badge bg-label-info">Ditetapkan Sebagai
                                                    Karyawan</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $per->created_at }}</td>
                                        <td>

                                        </td>
                                        <td>
                                            @if ($per->feedback == null)
                                                -
                                            @elseif ($per->feedback == '1')
                                                <span class="badge bg-label-warning">Menyanggupi</span>
                                            @elseif ($per->feedback == '1b')
                                                <span class="badge bg-label-warning">Menyanggupi</span>
                                            @elseif ($per->feedback == '2b')
                                                <span class="badge bg-label-success">Menerima</span>
                                            @elseif ($per->feedback == '3')
                                                <span class="badge bg-label-danger">Tidak Hadir</span>
                                            @elseif ($per->feedback == '3b')
                                                <span class="badge bg-label-danger">Tidak Hadir</span>
                                            @endif
                                        </td>
                                        <td>{{ $per->updated_at }}</td>
                                    </tbody>
                                @endforeach
                                @php
                                    $record = $div->recruitmentUserRecord;
                                    if ($record != null) {
                                        $awal = $record->sortBy('created_at')->first();
                                        $akhir = $record->sortByDesc('created_at')->first();
                                        $tgl_awal = \Carbon\Carbon::parse($awal->created_at) ?? 0;
                                        $tgl_akhir = \Carbon\Carbon::parse($akhir->created_at) ?? 0;
                                        $total = $tgl_awal->diffInDays($tgl_akhir);

                                        // $hasil->push(['total_hari' => $total]);
                                    } else {
                                        $total = 0;
                                    }
                                @endphp
                                <tbody class="table-primary">
                                    <tr>
                                        <td></td>
                                        <td>Total Hari</td>
                                        <td>{{ $total }} Hari</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row g-3 my-5">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-primary rounded shadow">
                                                <i class="mdi mdi-account-tie mdi-24px"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">
                                RATA-RATA WAKTU : {{ $rata_rata }} Hari
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- {{-- start datatable  --}} -->
@endsection
