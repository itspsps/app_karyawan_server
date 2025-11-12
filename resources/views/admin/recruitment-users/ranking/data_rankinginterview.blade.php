@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style type="text/css">
        .my-swal {
            z-index: X;
        }

        .nowrap {
            white-space: nowrap;
        }

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
            <div class="col-lg-12">
                <div class="container card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">DATA RANKING</h5>
                        </div>
                    </div>
                    <div class="row gy-4 mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="departemen_filter[]"
                                    id="departemen_filter" multiple>
                                    <option disabled value="">-Pilih Departemen-</option>
                                    @foreach ($departemen as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="departemen_filter">Departemen</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="divisi_filter[]" id="divisi_filter"
                                    multiple>
                                    <option selected disabled value="">-- Pilih Divisi --</option>
                                </select>
                                <label for="divisi_filter">Divisi</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="bagian_filter[]" id="bagian_filter"
                                    multiple>
                                    <option selected disabled value="">-- Pilih Bagian --</option>
                                </select>
                                <label for="bagian_filter">Bagian</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-floating form-floating-outline">
                                <select type="text" class="form-control" name="jabatan_filter[]" id="jabatan_filter"
                                    multiple>
                                    <option selected disabled value="">-- Pilih Jabatan --</option>
                                </select>
                                <label for="jabatan_filter">Jabatan</label>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4 align-items-end">
                        <div class="col-lg-6 col-md-6col-sm-12">
                            <div class="form-floating form-floating-outline">
                                <div id="reportrange" style="white-space: nowrap;">
                                    <button class="btn btn-outline-secondary w-100 ">
                                        <span class="fw-bold">FILTER&nbsp;DATE&nbsp;:&nbsp;</span>
                                        <span class="date_daterange"></span>
                                        <input type="date" id="start_date" name="start_date" value="" hidden>
                                        <input type="date" id="end_date" name="end_date" value="" hidden>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary w-100" id="btn_filter">
                                <i class="mdi mdi-filter-outline"></i>&nbsp;Filter
                            </button>
                        </div>
                    </div>
                    <table class="table" id="table_recruitment_ranking" style="width: 100%; font-size: small;">
                        <thead class="table-primary">
                            <tr>
                                <th>Legal Number</th>
                                <th>Tanggal</th>
                                <th>Ranking</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Divisi</th>
                                <th>Bagian</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var holding_id = '{{ $holding->id }}';
        var holding = '{{ $holding->holding_code }}';
        $('#departemen_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Departemen",
            allowClear: true
        });
        $('#divisi_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Divisi",
            allowClear: true
        });
        $('#bagian_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Bagian",
            allowClear: true
        });
        $('#jabatan_filter').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $('#departemen_filter').change(function(e) {
            departemen_filter_dept = $(this).val() || '';
            var url =
                "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/report_recruitment/get_divisi') }}@else{{ url('report_recruitment/get_divisi') }}@endif" +
                '/' + holding_id;
            // console.log(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    holding: holding_id,
                    departemen_filter: departemen_filter_dept,
                },
                cache: false,

                success: function(data_dept) {
                    // console.log(departemen_filter_dept, divisi_filter_dept, bagian_filter_dept, jabatan_filter_dept);
                    $('#divisi_filter').html(data_dept.select);
                    $('#bagian_filter').html('<option value="">Pilih Bagian</option>');
                    $('#jabatan_filter').html('<option value="">Pilih Jabatan</option>');
                    // refresh select2 biar dropdown kebaca data baru
                    // destroy & init ulang
                    let isOpen = $('#divisi_filter').data('select2') && $('#divisi_filter')
                        .data('select2').isOpen();

                    $('#divisi_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Divisi...",
                        allowClear: true
                    });
                    $('#bagian_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Divisi...",
                        allowClear: true
                    });
                    $('#jabatan_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Divisi...",
                        allowClear: true
                    });
                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#divisi_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#divisi_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#divisi_filter').select2('open');
                    }

                },

                error: function(data) {
                    Swal.close();
                    console.log('error:', data)
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.responseJSON.message,
                        timer: 4200,
                        showConfirmButton: false,
                    })
                },

            })
        })
        $('#divisi_filter').change(function() {
            divisi_filter = $(this).val() || '';

            $.ajax({
                type: 'GET',
                url: "@if (Auth::user()->is_admin == 'hrd'){{ url('hrd/report_recruitment/get_bagian') }}@else{{ url('report_recruitment/get_bagian') }}@endif" +
                    '/' + holding_id,
                data: {
                    holding: holding_id,
                    divisi_filter: divisi_filter,
                },
                cache: false,

                success: function(data_divisi) {
                    // console.log(data_divisi);
                    $('#bagian_filter').html(data_divisi.select);
                    $('#jabatan_filter').html('<option value="">Pilih Jabatan..</option>');
                    let isOpen = $('#bagian_filter').data('select2') && $('#bagian_filter')
                        .data('select2').isOpen();

                    $('#bagian_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Bagian...",
                        allowClear: true
                    });
                    $('#jabatan_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Bagian...",
                        allowClear: true
                    });

                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#bagian_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#bagian_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#bagian_filter').select2('open');
                    }
                },
                error: function(data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.responseJSON.message,
                        timer: 4200,
                        showConfirmButton: false,
                    })
                },

            })
        })
        $('#bagian_filter').change(function() {
            bagian_filter = $(this).val() || '';

            // $('#table_rekapdata').DataTable().destroy();
            $.ajax({
                type: 'GET',
                url: "{{ url('report_recruitment/get_jabatan') }}" + '/' + holding_id,
                data: {
                    holding: holding_id,
                    bagian_filter: bagian_filter
                },
                cache: false,

                success: function(data_jabatan) {

                    $('#jabatan_filter').html(data_jabatan.select);
                    let isOpen = $('#jabatan_filter').data('select2') && $(
                        '#jabatan_filter').data('select2').isOpen();

                    $('#jabatan_filter').select2('destroy').select2({
                        theme: "bootstrap-5",
                        placeholder: "Pilih Jabatan...",
                        allowClear: true
                    });
                    // langsung pilih opsi pertama kalau ada
                    let firstOpt = $('#jabatan_filter option:eq(0)').val();
                    if (firstOpt) {
                        $('#jabatan_filter').val(firstOpt).trigger('change');
                    }
                    if (isOpen) {
                        $('#jabatan_filter').select2('open');
                    }
                },
                error: function(data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.responseJSON.message,
                        timer: 4200,
                        showConfirmButton: false,
                    })
                },

            })
        })

        var start = moment().startOf('month');
        var end = moment().endOf('month');
        // var start = moment('2025-07-21');
        // var end = moment('2025-08-21');

        cb(start, end);
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);

        function cb(start, end) {
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            $('#reportrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            console.log(start, end);
        }

        var lstart, lend
        var departemen_filter = $('#departemen_filter').val() || [];
        var divisi_filter = $('#divisi_filter').val() || [];
        var bagian_filter = $('#bagian_filter').val() || [];
        var jabatan_filter = $('#jabatan_filter').val() || [];
        var start_date = lstart || [];
        var end_date = lend || [];

        load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);


        function load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date) {
            // $('#table_recruitment2').empty();
            $('#table_recruitment_ranking').DataTable().clear().destroy();
            if ($.fn.DataTable.isDataTable('#table_recruitment_ranking')) {
                $('#table_recruitment_ranking').DataTable().clear().destroy();
            }
            var table = $('#table_recruitment_ranking').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                ajax: {
                    url: "{{ url('/dt/data-ranking') }}" + '/' + holding,
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        departemen_filter: departemen_filter,
                        divisi_filter: divisi_filter,
                        bagian_filter: bagian_filter,
                        jabatan_filter: jabatan_filter,
                    }
                },
                columns: [{
                        data: 'legal_number',
                        name: 'legal_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'nowrap'
                    },
                    {
                        data: 'pelamar',
                        name: 'pelamar'
                    },
                    {
                        data: 'nama_jabatan',
                        name: 'nama_jabatan'
                    },
                    {
                        data: 'nama_departemen',
                        name: 'nama_departemen'
                    },
                    {
                        data: 'nama_divisi',
                        name: 'nama_divisi'
                    },
                    {
                        data: 'nama_bagian',
                        name: 'nama_bagian'
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });
        }
        $('#btn_filter').click(function(e) {
            var departemen_filter = $('#departemen_filter').val() || [];
            var divisi_filter = $('#divisi_filter').val() || [];
            var bagian_filter = $('#bagian_filter').val() || [];
            var jabatan_filter = $('#jabatan_filter').val() || [];
            var start_date = $('#start_date').val() || '';
            var end_date = $('#end_date').val() || '';

            // console.log(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);

            $('#content_null').empty();

            load_data(departemen_filter, divisi_filter, bagian_filter, jabatan_filter, start_date, end_date);
        });
        // start add departemen
        $('#nama_dept').on('change', function() {
            let id_dept = $(this).val();
            let url = "{{ url('/bagian/get_divisi') }}" + "/" + id_dept;
            console.log(id_dept);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_divisi').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        // end add departemen

        // start add divisi
        $('#nama_divisi').on('change', function() {
            let id_divisi = $(this).val();
            let url = "{{ url('/bagian/get_bagian') }}" + "/" + id_divisi;
            console.log(id_divisi);
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_dept: id_dept
                // },
                success: function(response) {
                    // console.log(response);
                    $('#nama_bagian').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        // show modal syarat
        $(document).on('click', '#btn_lihat_syarat', function() {
            let id = $(this).data('id');
            let desc = $(this).data('desc'); // Mendapatkan data dengan HTML
            // desc = $('<div>').html(desc).text();
            let holding = $(this).data("holding");
            $('#show_desc_recruitment').summernote('code', desc);
            $('#show_desc_recruitment').summernote('disable');
            // let url = "{{ url('recruitment/show/') }}" + '/' + id + '/' + holding;
            $('#modal_lihat_syarat').modal('show');
        });
        // update status aktif to non aktif
        $(document).on('click', '#btn_status_aktif', function() {
            var id = $(this).data('id');
            let holding = $(this).data("holding");
            console.log(id);
            console.log(holding);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Menonaktifkan Recruitment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/update/status-recruitment/') }}" + '/' + id +
                            '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terupdate!',
                                text: 'Data anda berhasil di update.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_interview').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Your data is safe :',
                        icon: 'error',
                        timer: 1500
                    })
                }
            });

        });
        // update status non aktif to aktif
        $(document).on('click', '#btn_status_naktif', function() {
            var id = $(this).data('id');
            let holding = $(this).data("holding");
            console.log(id);
            console.log(holding);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Mengaktifkan Recruitment",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/update/status-recruitment/') }}" + '/' + id +
                            '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terupdate!',
                                text: 'Data anda berhasil di update.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_interview').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Your data is safe :',
                        icon: 'error',
                        timer: 1500
                    })
                }
            });

        });
        // edit data
        $(document).on("click", "#btn_edit_recruitment", function() {
            let id = $(this).data('id');
            let dept = $(this).data("dept");
            let divisi = $(this).data("divisi");
            let bagian = $(this).data("bagian");
            let tanggal = $(this).data("tanggal");
            let holding = $(this).data("holding");
            console.log(dept);
            console.log(divisi);
            console.log(bagian);
            console.log(tanggal);
            // console.log(desc);
            console.log(holding);
            $('#id_recruitment').val(id);
            $('#nama_departemen_update option').filter(function() {
                // console.log($(this).val().trim());
                return $(this).val().trim() == dept
            }).prop('selected', true)
            $('#nama_divisi_update option').filter(function() {
                // console.log($(this).val().trim());
                return $(this).val().trim() == divisi
            }).prop('selected', true)
            $('#nama_bagian_update').val(bagian);
            $('#created_recruitment_update').val(tanggal);
            $('#modal_edit_recruitment').modal('show');

        });

        // delete data
        $(document).on('click', '#btn_delete_recruitment', function() {
            var id = $(this).data('id');
            let holding = $(this).data("holding");
            console.log(id);
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Kamu tidak dapat mengembalikan data ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/recruitment/delete/') }}" + '/' + id + '/' + holding,
                        type: "GET",
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data anda berhasil di hapus.',
                                icon: 'success',
                                timer: 1500
                            })
                            $('#table_interview').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'Your data is safe :',
                        icon: 'error',
                        timer: 1500
                    })
                }
            });

        });
    </script>
@endsection
