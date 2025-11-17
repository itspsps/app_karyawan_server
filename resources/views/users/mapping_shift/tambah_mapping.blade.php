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
            Mapping 0 Karyawan
        </button>
        <button id="btn_lihat_jadwal" type="button" class="btn_lihat_jadwal btn btn-sm btn-secondary">
            <i class="fa-solid fa-calendar"></i>&nbsp;
            Lihat Jadwal
        </button>
    </div>
</div>


<div class="page-content">
    <div class="container fb">
        <div class="text-center mt-1">
            <h5>Pilih Karyawan</h5>
        </div>


        <div class="row">
            @foreach($user_shift as $user)
            <div class="col-12 mb-3"> {{-- col-12 = lebar penuh --}}
                <input type="checkbox" id="user_{{$user->id}}" class="user-checkbox d-none" name="selected_users[]" value="{{$user->id}}">
                <label for="user_{{$user->id}}" class="w-100"> {{-- label 100% width --}}
                    <div class="user-card w-100 p-1 d-flex align-items-center">
                        <div class="me-3">
                            @if($user->foto_karyawan=='')
                            <img src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" class="user-image" alt="/">
                            @else
                            <img src="https://hrd.sumberpangan.store:4430/storage/app/public/foto_karyawan/{{$user->foto_karyawan}}" class="user-image" alt="/">
                            @endif
                        </div>
                        <div class="user-info flex-grow-1">
                            <strong>{{$user->name}}</strong><br>
                            <small>
                                {{$user->Jabatan->nama_jabatan ?? '-'}} ({{$user->Bagian->nama_bagian ?? '-'}})
                            </small><br>
                            <span class="badge bg-success mt-1" style="padding: 3px !important;"><i class="fa fa-check"></i> Aktif</span>
                        </div>
                    </div>
                </label>
            </div>
            @endforeach
            <!-- Tombol bawah -->
            <div class="modal fade" id="modal_add_mapping_shift">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header py-2 px-3">
                            <h6 class="modal-title"><small> Mapping Shift</small></h6>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form class="my-2" method="post" id="form_add_mapping_shift" enctype="multipart/form-data">
                            <div class="modal-body px-4 py-3">
                                @csrf
                                <input type="hidden" name="id_user" value="{{$user_karyawan->id}}">

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
                                                <div class="mb-3 form-input">
                                                    <span class="input-icon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <select class="form-control" name="shift" required>
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
                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                <div class="mb-3 form-input">
                                                    <span class="input-icon">
                                                        <i class="fa-solid fa-calendar-check"></i>
                                                    </span>
                                                    <select class="form-control" name="approve_hrd" id="approve_hrd" required>
                                                        <option value="">Pilih Approval HRD</option>
                                                        foreach($hrd as $hrd)
                                                        <option value="{{$hrd->id}}">{{$hrd->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button" id="btnAddMappingShift" class="btn btn-sm btn-primary btn-sm px-1">
                                    <i class="fa fa-save me-1"></i> Simpan
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm btn-sm px-1" data-bs-dismiss="modal">
                                    <i class="fa fa-times me-1"></i> Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function() {
        Swal.close();
        // let table = new DataTable('#table_mapping_shift');
        $("#btn_lihat_jadwal").on('click', function() {
            $.ajax({
                url: "{{url('mapping_shift/dashboard')}}",
                method: "GET",
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
                success: function(data) {
                    Swal.close();
                    window.location.href = "{{url('mapping_shift/dashboard')}}";
                },
                error: function() {
                    Swal.close();
                }
            })

        });
        $(".btnSelected").on('click', function() {
            let count = document.querySelectorAll('.user-checkbox:checked').length;
            if (count == 0) {
                // console.log('no selected');
                $('#alert_selectednull').empty();
                $('#alert_selectednull').append('<div class="alert alert-danger light alert-lg alert-dismissible fade show">' +
                    '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">' +
                    '<circle cx="12" cy="12" r="10"></circle>' +
                    '<path d="M8 14s1.5 2 4 2 4-2 4-2"></path>' +
                    '<line x1="9" y1="9" x2="9.01" y2="9"></line>' +
                    '<line x1="15" y1="9" x2="15.01" y2="9"></line>' +
                    '</svg>' +
                    '<strong>Info!</strong>&nbsp;Anda Belum Memilih Karyawan' +
                    '<button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>');
                setTimeout(function() {
                    $('#alert_selectednull').fadeOut('slow');
                }, 5000); // 3 detik
            } else {
                var selectedUsers = [];
                document.querySelectorAll('.user-checkbox:checked').forEach(function(checkbox) {
                    selectedUsers.push(checkbox.value);
                });
                // console.log('selected users:', selectedUsers);
                var id = $(this).data('id');
                var shift = $(this).data('shift');
                var tanggal = $(this).data('tanggal');
                $('#container_selected_users').empty(); // kosongkan dulu container
                $('#karyawan_list').empty(); // kosongkan dulu container
                $('#tanggal_update').val(tanggal);

                $.ajax({
                    url: "{{url('mapping_shift/getKaryawanMappingShift')}}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        user: selectedUsers,
                    },
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
                        response.data.forEach(function(userId, index) {
                            $('#container_selected_users').append(`<input type="text" class="form-control mb-2" name="users[]" value="${userId.id}" hidden>`);
                            $('#karyawan_list').append(`<li>${userId.name}</li>`)
                        });
                        $('#form_add_mapping_shift').trigger("reset");
                        $("#modal_add_mapping_shift").modal("show");
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        $('#form_add_mapping_shift').trigger("reset");
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                        })
                    }
                })
            }

        });

        var start = moment().add(1, 'days');
        $('#tanggal').daterangepicker({
            drops: 'auto',
            opens: 'center',
            minDate: start,
            startDate: start,
            endDate: start,
            autoApply: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        }, function(start, end, label) {
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
        $('#btnAddMappingShift').on('click', function() {
            var formData = $('#form_add_mapping_shift').serialize();
            $.ajax({
                url: "{{url('mapping_shift/addMappingShift')}}",
                type: 'POST',
                dataType: 'json',
                data: formData,
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
                    // console.log(response);
                    Swal.close();
                    if (response.code == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 4500

                        })
                        $('#modal_add_mapping_shift').modal('hide');
                        window.location.href = "{{url('mapping_shift/dashboard')}}";
                    } else {
                        if (typeof response.message === 'object') {
                            var errorText = '<ul>';
                            Object.values(response.message).forEach(msgArray => {
                                msgArray.forEach(msg => {
                                    errorText += `<li>${msg}</li>`;
                                });
                            });
                            errorText += '</ul>';
                        } else {
                            var errorText = response.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorText,
                            timer: 4500
                        })
                    }
                    $('#modal_add_mapping_shift').modal('hide');
                },
                error: function(xhr, status, error) {
                    // console.log(xhr);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                }
            })
        });

    });
</script>
<script>
    $(document).ready(function() {
        // Select all checkboxes with the class 'user-checkbox'
        const $checkboxes = $('.user-checkbox');
        // Select the button by its ID
        const $btnSelected = $('#btnSelected');

        // Function to update the button's text/HTML based on checked count
        function updateSelectedCount() {
            // Count the number of checked checkboxes
            const count = $checkboxes.filter(':checked').length;

            // Construct the HTML string including the icon and the count.
            // We use .html() to render the <i> tag as an actual icon.
            $btnSelected.html(`<i class="fa fa-plus"></i>&nbsp;Mapping ${count} Karyawan`);
        }

        // Attach the 'change' event listener to all checkboxes
        $checkboxes.on('change', updateSelectedCount);

        // Optional: Call the function once on load to set the initial state
        updateSelectedCount();
    });
</script>

@endsection