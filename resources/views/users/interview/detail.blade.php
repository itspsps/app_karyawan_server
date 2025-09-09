@extends('users.interview.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<style>
    #note {
        position: absolute;
        left: 50px;
        top: 35px;
        padding: 0px;
        margin: 0px;
        cursor: default;
    }
</style>
@endsection
@section('content')
<div class="head-details">
    <div class="container">
        <div class="dz-info col-12">
            <span class="location d-block text-left">List Pelamar&nbsp;
            </span>
            <h6 class="title">Department of "{{ $table->Jabatan->Bagian->Divisi->Departemen->nama_departemen }}"</h6>
        </div>
        <div class="dz-media media-65">
            <img src="assets/images/logo/logo.svg" alt="">
        </div>
    </div>
</div>

<div class="fixed-content p-0">
    <div class="container">
        <div class="main-content">
            <div class="left-content">
                <a id="btn_klik" href="{{url('interview/dashboard')}}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.03033 0.46967C9.2966 0.735936 9.3208 1.1526 9.10295 1.44621L9.03033 1.53033L2.561 8L9.03033 14.4697C9.2966 14.7359 9.3208 15.1526 9.10295 15.4462L9.03033 15.5303C8.76406 15.7966 8.3474 15.8208 8.05379 15.6029L7.96967 15.5303L0.96967 8.53033C0.703403 8.26406 0.679197 7.8474 0.897052 7.55379L0.96967 7.46967L7.96967 0.46967C8.26256 0.176777 8.73744 0.176777 9.03033 0.46967Z" fill="#a19fa8" />
                    </svg>
                </a>
                <h5 class="mb-0">Back</h5>
            </div>
            <div class="mid-content">
            </div>
        </div>
    </div>
</div>
<div class="container">
    <dl class="flex">
        <dt class="font-semibold text-primary">Nama</dt>
        <dd class="ml-2">: {{ $table->Cv->nama_lengkap }}</dd>
        <dt class="font-semibold text-primary">CV</dt>
        <dd class="ml-2"><a type="button" class="btn btn-sm btn-primary" href="{{ url('interview/pdfUserKaryawan/' . $table->id) }}" target="_blank">Download CV</a></dd>
        <dt class="font-semibold text-primary">Catatan HRD</dt>
        <dd class="ml-2">: {{ $table->DataInterview->catatan }}</dd>
    </dl>
    <form id="form_approve" class="my-2" method="post" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="hidden" id="id" name="id" value="{{ $table->id }}">
            <input type="hidden" id="status" name="status" value="">
        </div>
        <div class="input-group">
            <textarea class="form-control" placeholder="Catatan" id="catatan" name="catatan" style="font-weight: bold"></textarea>
        </div>
        <br>
        <div class="input-group">
            <div class="text-center">
                <button type="button" id="approve_btn" class="btn btn-sm btn-success btn-rounded" data-action="save-png"><i class="fa fa-save" aria-hidden="true"> </i> &nbsp; Diterima</button>
                <button type="button" id="not_approve_btn" class="btn btn-sm btn-warning btn-rounded" data-action="not_save-png"><i class="fa fa-times" aria-hidden="true"> </i> &nbsp; Tidak Diterima</button>
            </div>
        </div>
    </form>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
    $(function() {
        $(document).on('click', '#approve_btn', function(e) {
            e.preventDefault();
            var approve = 'approve';
            var id = $('#id').val();
            var catatan = $('#catatan').val();
            var status = '4b';
            console.log(id, catatan, status);
            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    catatan: catatan,
                    status: status
                },
                url: "{{ url('/interview/approve/proses') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    console.log(data);
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
                    var url = "{{ url('/interview/dashboard') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                },
                error: function(data) {
                    console.log('error:', data)
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
                    var url = "{{ url('/interview/dashboard') }}"; //the url I want to redirect to
                    $(location).attr('href', url);
                }
            });
        });
        $(document).on('click', '#not_approve_btn', function(e) {
            e.preventDefault();
            // console.log('ok');
            var approve = 'not_approve';
            var id = $('#id').val();
            var catatan = $('#catatan').val();
            var status = '5b';
            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    catatan: catatan,
                    status: status
                },
                url: "{{ url('/interview/approve/proses') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
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
                    console.log(data);
                    var url = "{{ url('/interview/dashboard') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                },
                error: function(data) {
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
                    var url = "{{ url('/interview/dashboard') }}"; //the url I want to redirect to
                    $(location).attr('href', url);

                }
            });
        });
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
</script>
@endsection