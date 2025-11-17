@extends('users.mapping_shift.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<style>
    @media (max-width: 768px) {
        .daterangepicker {
            position: fixed !important;
            top: 10% !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 90% !important;
            margin: 0 auto !important;
            z-index: 9999 !important;
        }

        .daterangepicker .drp-calendar {
            float: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .daterangepicker .drp-buttons {
            text-align: center !important;
        }
    }

    .modal-backdrop.show:nth-of-type(even) {
        z-index: 1051 !important;
    }

    .user-card {
        width: 100%;
        border: 2px solid transparent;
        background-color: #ffffffff;
        border-radius: 10px;
        transition: 0.3s;
        cursor: pointer;
    }

    .user-card:hover {
        border-color: #fffefeff;
    }

    .user-checkbox:checked+label .user-card {
        border-color: #673ab7ff;
        background-color: #e7e8ffff;
        box-shadow: 0 0 10px rgba(97, 13, 253, 0.42);
    }

    .user-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-info {
        font-size: 13px;
    }

    /* Kartu modal */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }



    .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .input-group {
        margin-bottom: 10px;
    }

    .input-group .form-control[readonly] {
        background-color: #f1f1f1;
        /* font-weight: 600;
        width: 150px; */
        border-right: none;
        text-align: center;
    }

    .input-group select,
    .input-group input:not([readonly]) {
        border-left: none;
        font-weight: 600;
    }

    .table th {
        background-color: #0d6efd !important;
        color: white !important;
        text-align: center;
    }

    .table td {
        vertical-align: middle;
        text-align: center;
    }
</style>
@endsection
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>

<link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<div class="container">
    @if(Session::has('mappingshiftsuccess'))
    <div id="alert_addmappingsuccess" class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Menyimpan Data
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    @elseif(Session::has('mappingshiftupdatesuccess'))
    <div id="alert_statusmappingeditsuccess" class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Mengedit Data Mapping
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <div id="alert_selectednull">

    </div>
    <div class="mb-2">
        <button id="btnSelected" type="button" class="btnSelected btn btn-sm btn-primary">
            <i class="fa-solid fa-add"></i>&nbsp;
            Tambah Jadwal
        </button>
    </div>
</div>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-outline-primary btn-sm" id="prevWeek">&lt; Prev</button>
        <h5 class="mb-0" id="weekLabel">Weekly Shift Overview</h5>
        <button class="btn btn-outline-primary btn-sm" id="nextWeek">Next &gt;</button>
    </div>

    <div class="mb-4">
        <div class="d-flex flex-nowrap overflow-auto gap-2 pb-2" id="dateContainer"></div>
    </div>

    <div class="card">
        <div class="card-body" id="shiftList">
            <p class="text-center text-muted my-3">Pilih tanggal untuk melihat jadwal shift.</p>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_mapping_shift">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 px-3">
                <h6 class="modal-title"><small> Edit & Hapus Mapping Shift</small></h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form class="my-2" method="post" id="form_edit_mapping_shift" enctype="multipart/form-data">
                <div class="modal-body px-4 py-3">
                    <input type="hidden" name="id_mapping" id="id_mapping" value="">

                    <!-- Form input -->
                    <div class="basic-form style-1">
                        <div class="col-12">
                            <h6>Karyawan Terpilih :</h6>
                            <div id="container_selected_users">
                            </div>
                            <ul id="karyawan_list">

                            </ul>
                            <div class="row mt-3">
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <span id="txt_karyawan"></span>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <select class="form-control" name="shift" id="shift" required>
                                            <option value="">Pilih Shift</option>
                                            @foreach($shift as $data)
                                            <option value="{{$data->id}}">{{$data->nama_shift}} ({{$data->jam_masuk}} - {{$data->jam_keluar}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" name="tanggal" id="tanggal" readonly placeholder="Tanggal" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" id="btnUpdateMappingShift" class="btn btn-sm btn-warning btn-sm px-1">
                        <i class="fa fa-save me-1"></i> Update
                    </button>
                    <button type="button" id="btn_deletemapping" class="btn btn-danger btn-sm btn-sm px-1" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

</script>
<script type="text/javascript">
    $(document).ready(function() {
        Swal.close();
        const dateContainer = $("#dateContainer")[0];
        const shiftList = $("#shiftList")[0];
        const weekLabel = $("#weekLabel")[0];
        let currentStart = new Date();
        currentStart.setDate(currentStart.getDate() - currentStart.getDay() + 1);

        let shiftData = {};

        loadWeek();

        $("#prevWeek").on("click", () => changeWeek(-1));
        $("#nextWeek").on("click", () => changeWeek(1));

        function changeWeek(direction) {
            currentStart.setDate(currentStart.getDate() + (direction * 7));
            loadWeek();
        }

        function loadWeek() {
            const start = formatISO(currentStart);
            const end = formatISO(new Date(currentStart.getTime() + 6 * 86400000));

            weekLabel.innerText = `Minggu ${formatLabel(currentStart)} - ${formatLabel(new Date(currentStart.getTime() + 6 * 86400000))}`;

            Swal.fire({
                allowOutsideClick: false,
                background: 'transparent',
                html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
                showCancelButton: false,
                showConfirmButton: false,
            });

            $.getJSON(`/mapping_shift/get_shiftData?start=${start}&end=${end}`)
                .done(function(data) {
                    shiftData = data;
                    renderWeek();
                    Swal.close();
                })
                .fail(function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat!',
                        text: 'Terjadi kesalahan saat mengambil data shift.'
                    });
                });
        }

        function renderWeek() {
            dateContainer.innerHTML = "";
            const startDate = new Date(currentStart);
            let firstButton = null;

            for (let i = 0; i < 7; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                const dateStr = formatISO(date);

                const btn = document.createElement("button");
                btn.className = "btn btn-sm border text-center flex-shrink-0";
                btn.style.minWidth = "90px";
                btn.innerHTML = `
                <small class="fw-semibold">${date.toLocaleDateString('id-ID', { weekday: 'short' })}</small>
                <small class="text-muted">&nbsp;${date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })}</small>`;
                btn.dataset.date = dateStr;
                btn.addEventListener("click", () => selectDate(btn));
                dateContainer.appendChild(btn);

                if (i === 0) firstButton = btn;
            }

            if (firstButton) selectDate(firstButton);
        }

        function selectDate(btn) {
            $("#dateContainer button").removeClass("btn-primary text-white");
            btn.classList.add("btn-primary", "text-white");

            const date = btn.dataset.date;
            const shifts = shiftData[date] || [];
            renderShifts(date, shifts);
        }

        function renderShifts(date, shifts) {
            let html = `<h6 class="mb-3 text-center">Shift tanggal ${formatLabel(date)}</h6>`;
            if (shifts.length === 0) {
                html += `<p class="text-center text-muted">Tidak ada jadwal shift di tanggal ini.</p>`;
            } else {
                html += '<ul class="list-group">';
                shifts.forEach(s => {
                    html += `
                    <a href="javascript:void(0)" class="btn_editmapping" 
                        data-id="${s.id_mapping}" 
                        data-karyawan="${s.name}" 
                        data-shift="${s.shift_id}" 
                        data-jam_shift="${s.jam_shift}" 
                        data-tanggal="${date}">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${s.name}</span>
                            <div class="form-check form-switch flex-shrink-0 me-2">
                                <span class="badge text-dark">${s.shift}</span>
                                <span class="badge text-dark">${s.jam_shift}</span>
                            </div>
                        </li>
                    </a>`;
                });
                html += '</ul>';
            }
            shiftList.innerHTML = html;
        }

        function formatISO(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        }

        function formatLabel(date) {
            const d = new Date(date);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short'
            });
        }

        // let table = new DataTable('#table_mapping_shift');
        $(".btnSelected").on('click', function() {
            $.ajax({
                url: "{{url('mapping_shift/tambah_mapping')}}",
                type: 'GET',
                beforeSend: function() {
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
                },
                success: function(response) {
                    Swal.close();
                    window.location.href = "{{url('mapping_shift/tambah_mapping')}}";
                },
                error: function(xhr, status, error) {
                    Swal.close();

                }
            })
        })
        $(document).on('click', ".btn_editmapping", function() {
            Swal.fire({
                allowOutsideClick: false,
                background: 'transparent',
                html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
                showCancelButton: false,
                showConfirmButton: false,

            });
            $('#modal_edit_mapping_shift').modal('show');
            Swal.close();
            var id_mapping = $(this).data('id');
            var karyawan = $(this).data('karyawan');
            var shift = $(this).data('shift');
            var jam_shift = $(this).data('jam_shift');
            var tanggal = $(this).data('tanggal');
            $('#id_mapping').val(id_mapping);
            $('#txt_karyawan').html(karyawan);
            $('#shift').val(shift);
            $('#tanggal').val(tanggal);
            console.log(id_mapping, karyawan, shift, jam_shift, tanggal);


        })
        $('#btnUpdateMappingShift').on('click', function() {
            var id_mapping = $('#id_mapping').val();
            var shift = $('#shift').val();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan diubah",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-primary)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal',

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{url('mapping_shift/update_mapping_shift')}}",
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'id_mapping': id_mapping,
                            'shift': shift,
                        },
                        beforeSend: function() {
                            Swal.fire({
                                allowOutsideClick: false,
                                background: 'transparent',
                                html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
                                showCancelButton: false,
                                showConfirmButton: false,

                            });
                        },
                        success: function(response) {
                            // console.log(response);
                            Swal.close();
                            if (response.code == '500') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                });
                                $('#modal_edit_mapping_shift').modal('hide');
                            }
                            loadWeek();
                            // window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                            })
                        }
                    })
                } else {
                    Swal.close();
                    $('#modal_edit_mapping_shift').modal('hide');
                }
            })



        })
        $('#btn_deletemapping').on('click', function() {
            var id_mapping = $('#id_mapping').val();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-primary)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{url('mapping_shift/delete_mapping_shift')}}",
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'id_mapping': id_mapping,
                        },
                        beforeSend: function() {
                            Swal.fire({
                                allowOutsideClick: false,
                                background: 'transparent',
                                html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
                                showCancelButton: false,
                                showConfirmButton: false,

                            });
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.code == '500') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                });
                            }
                            $('modal_edit_mapping_shift').modal('hide');
                            loadWeek();

                            // window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                timer: 5200,
                            })
                        }
                    })
                }
            })
        })
        window.onbeforeunload = function() {
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
        };
    });
</script>

@endsection