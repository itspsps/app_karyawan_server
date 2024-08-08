@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
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
                        <h5 class="card-title m-0 me-2">DATA MASTER JABATAN</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-lg-8">
                        <div class="nav-item d-flex align-items-center">
                            <i class="mdi mdi-magnify mdi-24px lh-0"></i>
                            <input type="text" id="search-jabatan" class="search-jabatan form-control border-0 shadow-none bg-body" placeholder="Search..." aria-label="Search..." />
                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal_import_jabatan"><i class="menu-icon tf-icons mdi mdi-file-excel"></i>Import</button>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_import_jabatan" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <form method="post" action="{{ url('/jabatan/ImportJabatan/'.$holding) }}" class="modal-content" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Import Jabatan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mt-2">
                                        <div class="col mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="file" id="file_excel" name="file_excel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" placeholder="Masukkan File" />
                                                <label for="file_excel">File Excel</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-2">
                                        <a href="{{asset('')}}" type="button" download="" class="btn btn-sm btn-primary"> Download Format Excel</a>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="5">
                    <div class="containerItems row">
                        @foreach($data_divisi as $divisi)
                        <div class="col-md-6 col-lg-4" data-search="{{$divisi->nama_divisi}}" style="">
                            <div class="card text-center mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <p class="card-text">Departemen : </p>@if($divisi->Departemen==NULL)@else {{$divisi->Departemen->nama_departemen}} @endif
                                    </h6>
                                    <p class="card-text">({{$divisi->nama_divisi}})</p>
                                    <a href="{{url('detail_jabatan',$divisi->id).'/'.$holding}}" class="btn btn-primary btn-sm waves-effect waves-light">Lihat Jabatan ({{count($divisi->Jabatan)}})</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="{{asset('search/e-search.js')}}"></script>
<script type="text/javascript">
    $('#search-jabatan').search(function() {

    });
</script>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>
<script>
    $('#nama_divisi').on('change', function() {
        let id_divisi = $(this).val();
        let url = "{{url('/jabatan/get_bagian')}}" + "/" + id_divisi;
        // console.log(id_divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_divisi: id_divisi
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
    $('#nama_jabatan').on('change', function() {
        let id = $(this).val();
        if (id == 'DIREKTUR UTAMA') {
            $('#level_jabatan').val('0');
        } else if (id == 'DIREKTUR KEUANGAN') {
            $('#level_jabatan').val('0');
        } else if (id == 'DIREKTUR OPERASIONAL') {
            $('#level_jabatan').val('0');
        } else if (id == 'DIREKTUR SALES') {
            $('#level_jabatan').val('0');
        } else if (id == 'HEAD') {
            $('#level_jabatan').val('1');
        } else if (id == 'NATIONAL SALES MANAGER') {
            $('#level_jabatan').val('1');
        } else if (id == 'MANAGER') {
            $('#level_jabatan').val('2');
        } else if (id == 'REGIONAL SALES MANAGER') {
            $('#level_jabatan').val('2');
        } else if (id == 'JUNIOR MANAGER') {
            $('#level_jabatan').val('3');
        } else if (id == 'AREA SALES MANAGER') {
            $('#level_jabatan').val('3');
        } else if (id == 'SUPERVISOR') {
            $('#level_jabatan').val('4');
        } else if (id == 'KOORDINATOR') {
            $('#level_jabatan').val('5');
        } else if (id == 'ASM') {
            $('#level_jabatan').val('5');
        } else if (id == 'ADMIN') {
            $('#level_jabatan').val('6');
        } else if (id == 'SALES') {
            $('#level_jabatan').val('6');
        } else if (id == 'STAFF') {
            $('#level_jabatan').val('6');
        } else if (id == 'OPERATOR') {
            $('#level_jabatan').val('6');
        } else if (id == 'SOPIR') {
            $('#level_jabatan').val('6');
        } else if (id == 'KERNET') {
            $('#level_jabatan').val('6');
        } else {
            $('#level_jabatan').val('7');
        }
        let divisi = $('#nama_divisi').val();
        let level = $('#level_jabatan').val();
        let holding = '{{$holding}}';
        let url = "{{url('atasan/get_jabatan')}}" + "/" + holding;
        console.log(divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                holding: holding,
                level: level,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#nama_jabatan_atasan').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#nama_jabatan_update').on('change', function() {
        let id = $(this).val();
        if (id == 'DIREKTUR UTAMA') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'DIREKTUR KEUANGAN') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'DIREKTUR OPERASIONAL') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'DIREKTUR SALES') {
            $('#level_jabatan_update').val('0');
        } else if (id == 'HEAD') {
            $('#level_jabatan_update').val('1');
        } else if (id == 'NATIONAL SALES MANAGER') {
            $('#level_jabatan_update').val('1');
        } else if (id == 'MANAGER') {
            $('#level_jabatan_update').val('2');
        } else if (id == 'REGIONAL SALES MANAGER') {
            $('#level_jabatan_update').val('2');
        } else if (id == 'AREA SALES MANAGER') {
            $('#level_jabatan_update').val('3');
        } else if (id == 'JUNIOR MANAGER') {
            $('#level_jabatan_update').val('3');
        } else if (id == 'SUPERVISOR') {
            $('#level_jabatan_update').val('4');
        } else if (id == 'KOORDINATOR') {
            $('#level_jabatan_update').val('5');
        } else if (id == 'ASM') {
            $('#level_jabatan_update').val('5');
        } else if (id == 'ADMIN') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'SALES') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'STAFF') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'OPERATOR') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'SOPIR') {
            $('#level_jabatan_update').val('6');
        } else if (id == 'KERNET') {
            $('#level_jabatan_update').val('6');
        } else {
            $('#level_jabatan_update').val('7');
        }

        let divisi = $('#nama_divisi_update').val();
        let level = $('#level_jabatan_update').val();
        let holding = '{{$holding}}';
        let url = "{{url('atasan/get_jabatan')}}" + "/" + holding;
        console.log(divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                holding: holding,
                level: level,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#nama_jabatan_atasan_update').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $(document).on("click", "#btn_edit_jabatan", function() {
        let id = $(this).data('id');
        let bagian = $(this).data("bagian");
        let divisi = $(this).data("divisi");
        let level = $(this).data("level");
        $level = $(this).data("level");
        let jabatan = $(this).data("jabatan");
        let atasan = $(this).data("atasan");
        let holding = $(this).data("holding");
        console.log($level);
        $('#id_jabatan').val(id);
        $('#nama_divisi_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == divisi
        }).prop('selected', true)
        $('#nama_bagian_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == bagian
        }).prop('selected', true)
        let url = "{{url('atasan/edit/get_jabatan')}}" + "/" + holding;
        console.log(divisi);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                atasan: atasan,
                holding: holding,
                level: level,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#nama_jabatan_atasan_update').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        $('#level_jabatan_update').val(level);
        $('#nama_jabatan_update').val(jabatan);
        $('#nama_jabatan_atasan_update').val(atasan);
        $('#modal_edit_jabatan').modal('show');

    });
    $(document).on('click', '#btn_delete_jabatan', function() {
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
                    url: "{{ url('/jabatan/delete/') }}" + '/' + id + '/' + holding,
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
                        $('#table_jabatan').DataTable().ajax.reload();
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