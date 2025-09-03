@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style type="text/css">
    .my-swal {
        z-index: X;
    }



    .leaflet-container {
        height: 400px;
        width: 600px;
        max-width: 100%;
        max-height: 100%;
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
                        <h5 class="card-title">TAMBAH SITE</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <hr class="my-5">
                        <form method="post" action="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/site/addSite/'.$holding->holding_code) }}@else{{ url('site/addSite/'.$holding->holding_code) }}@endif" class="modal-content" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-2 p-3 gy-4">
                                <div class="col-md-6">
                                    <input type="hidden" value="{{$holding->id}}" id="id_holding" name="id_holding">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="nama_holding" name="nama_holding" value="{{$holding->holding_name}}" readonly>
                                        <label for="nama_holding">Holding</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-3 gy-4">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control @error('nama_site') is-invalid @enderror" id="nama_site" name="nama_site" value="">
                                        <label for="nama_site">Nama Site</label>
                                    </div>
                                    @error('nama_site')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row p-3 gy-4">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control @error('alamat_site') is-invalid @enderror" id="alamat_site" name="alamat_site" value="">
                                        <label for="alamat_site">Alamat Site</label>
                                    </div>
                                    @error('alamat_site')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row p-3 gy-4">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-control @error('status_site') is-invalid @enderror" id="status_site" name="status_site">
                                            <option selected disabled value="">Pilih Status</option>
                                            <option value="PUSAT">PUSAT</option>
                                            <option value="SITE">SITE</option>
                                            <option value="DEPO">DEPO</option>
                                        </select>
                                        <label for="status_site">Status Site</label>
                                    </div>
                                    @error('status_site')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-2 p-3 gy-9">
                                <div class="col mb-3">
                                    <a type="button" href="@if(Auth::user()->is_admin =='hrd'){{url('hrd/site/'.$holding->holding_code)}}@else {{url('site/'.$holding->holding_code)}} @endif" class="btn btn-outline-secondary">
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
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
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    $(document).ready(function() {
        $('#nama_site').on('keyup', function() {
            let val = $(this).val().toUpperCase();
            $(this).val(val);
        });
        $('#alamat_site').on('keyup', function() {
            let val = $(this).val().toUpperCase();
            $(this).val(val);
        });
    });
</script>

@endsection