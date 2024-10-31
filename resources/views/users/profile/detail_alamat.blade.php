@extends('users.profile.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<style>
    @media (max-width: 600px) {
        #my_camera video {
            max-width: 90%;
            max-height: 100%;
        }
    }
</style>
@endsection
@section('content')
<div class="fixed-content p-0" style=" border-radius: 10px; margin-top: 0%;box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
    <div class=" container" style="margin-top: -5%;">

        <div class="dz-banner-heading">
        </div>
        <div class="row">
            <div class="col-md-12 text-center" style="margin: 0; padding: 0;">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Edit Alamat</h5>
                    </div>
                    <div class="card-body">
                        <div class="basic-form style-1">
                            <form method="POST" action="{{url('save_detail_alamat')}}">
                                @csrf
                                <input type="hidden" name="karyawan_id" id="karyawan_id" value="{{$user_karyawan->id}}">
                                <?php
                                $kab = App\Models\Cities::where('province_code', old('provinsi', $user_karyawan->provinsi))->orderBy('name', 'ASC')->get();
                                $kec = App\Models\District::where('city_code', old('kabupaten', $user_karyawan->kabupaten))->orderBy('name', 'ASC')->get();
                                $desa = App\Models\Village::where('district_code', old('kecamatan', $user_karyawan->kecamatan))->orderBy('name', 'ASC')->get();
                                // echo $kab;
                                ?>
                                <span class="badge light badge-info">Alamat Berdasarkan KTP</span>
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map"></i>
                                    </span>
                                    <select class="form-control @error('provinsi') is-invalid @enderror" name="provinsi" id="provinsi">
                                        @foreach($data_provinsi as $data)
                                        <option value="{{$data->code}}" {{($data->code == old('provinsi',$user_karyawan->provinsi)) ? 'selected' : ''}}>{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('provinsi')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map"></i>
                                    </span>
                                    <select class="form-control @error('kabupaten') is-invalid @enderror" name="kabupaten" id="kabupaten">
                                        @foreach($kab as $data)
                                        <option value="{{$data->code}}" {{($data->code == old('kabupaten',$user_karyawan->kabupaten)) ? 'selected' : ''}}>{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kabupaten')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map"></i>
                                    </span>
                                    <select class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatan">
                                        @foreach($kec as $data)
                                        <option value="{{$data->code}}" {{($data->code == old('kecamatan',$user_karyawan->kecamatan)) ? 'selected' : ''}}>{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kecamatan')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map"></i>
                                    </span>
                                    <select class="form-control @error('desa') is-invalid @enderror" name="desa" id="desa">
                                        @foreach($desa as $data)
                                        <option value="{{$data->code}}" {{($data->code == old('desa',$user_karyawan->desa)) ? 'selected' : ''}}>{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('desa')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" class="form-control @error('rt') is-invalid @enderror" id="rt" name="rt" placeholder="RT" value="{{old('rt',$user_karyawan->rt)}}">
                                </div>
                                @error('rt')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" id="rw" name="rw" class="form-control @error('rw') is-invalid @enderror" placeholder="RW" value="{{old('rw',$user_karyawan->rw)}}">
                                </div>
                                @error('rw')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Golongan Darah" value="{{old('alamat',$user_karyawan->alamat)}}">
                                </div>
                                @error('alamat')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-2 form-input" style="text-align: left;">
                                    <span>Apakah&nbsp;Alamat&nbsp;Sama&nbsp;Dengan&nbsp;Domisili&nbsp;?</span>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">
                                        <div class="form-check check-box">
                                            <input class="form-check-input" type="radio" name="status_alamat" value="ya" @if(old('status_alamat',$user_karyawan->status_alamat)=="ya" ) checked @else @endif id="status_alamat_ya">
                                            <label class="form-check-label" for="status_alamat_ya">Ya</label>
                                            <i class="check-1 far fa-square"></i>
                                            <i class="check-2 far fa-check-square"></i>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-check check-box">
                                            <input class="form-check-input" type="radio" name="status_alamat" value="tidak" @if(old('status_alamat',$user_karyawan->status_alamat)=="tidak" ) checked @else @endif id="status_alamat_tidak">
                                            <label class="form-check-label" for="status_alamat_tidak">Tidak</label>
                                            <i class="check-1 far fa-square"></i>
                                            <i class="check-2 far fa-check-square"></i>
                                        </div>

                                        @error('status_alamat')
                                        <p class="alert alert-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div id="content_alamat_domisili">
                                    <span class="badge light badge-primary">Alamat Berdasarkan Domisili</span>
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map"></i>
                                        </span>
                                        <select class="form-control @error('provinsi_domisili') is-invalid @enderror" name="provinsi_domisili" id="provinsi_domisili">
                                            @foreach($data_provinsi as $data)
                                            <option value="{{$data->code}}" {{($data->code == old('provinsi_domisili',$user_karyawan->provinsi_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('provinsi_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map"></i>
                                        </span>
                                        <select class="form-control @error('kabupaten_domisili') is-invalid @enderror" name="kabupaten_domisili" id="kabupaten_domisili">
                                            @foreach($kab as $data)
                                            <option value="{{$data->code}}" {{($data->code == old('kabupaten_domisili',$user_karyawan->kabupaten_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('kabupaten_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map"></i>
                                        </span>
                                        <select class="form-control @error('kecamatan_domisili') is-invalid @enderror" name="kecamatan_domisili" id="kecamatan_domisili">
                                            @foreach($kec as $data)
                                            <option value="{{$data->code}}" {{($data->code == old('kecamatan_domisili',$user_karyawan->kecamatan_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('kecamatan_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map"></i>
                                        </span>
                                        <select class="form-control @error('desa_domisili') is-invalid @enderror" name="desa_domisili" id="desa_domisili">
                                            @foreach($desa as $data)
                                            <option value="{{$data->code}}" {{($data->code == old('desa_domisili',$user_karyawan->desa_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('desa_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <input type="text" class="form-control @error('rt_domisili') is-invalid @enderror" id="rt_domisili" name="rt_domisili" placeholder="RT" value="{{old('rt_domisili',$user_karyawan->rt_domisili)}}">
                                    </div>
                                    @error('rt_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <input type="text" id="rw_domisili" name="rw_domisili" class="form-control @error('rw_domisili') is-invalid @enderror" placeholder="RW" value="{{old('rw_domisili',$user_karyawan->rw_domisili)}}">
                                    </div>
                                    @error('rw_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                    <div class="mb-3 form-input">
                                        <span class="input-icon">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <input type="text" class="form-control @error('alamat_domisili') is-invalid @enderror" id="alamat_domisili" name="alamat_domisili" placeholder="Golongan Darah" value="{{old('alamat_domisili',$user_karyawan->alamat_domisili)}}">
                                    </div>
                                    @error('alamat_domisili')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="border-top">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-primary mt-1 btn-sm btn-rounded btn-block">UPDATE</button>
                                        </div>
                                        <div class="col-6">
                                            <a id="btn_klik" type="button" href="{{url('profile')}}" class="btn btn-light mt-1 btn-sm btn-rounded btn-block">KEMBALI</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvas_result_foto" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-body text-center small">
        <h5 class="title">Konfirmasi</h5>
        <p>Konfirmasi Pengambilan Foto</p>
        <form method="POST" action="{{ url('save_capture_profile') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <div id="results"></div>
                <input type="hidden" name="gallery_image" class="image-tag">
            </div>
            <br>
            <button type="submit" id="btn_klik" class="btn btn-sm btn-info light pwa-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M15 13H9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12 10L12 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M19 10H18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M2 13.3636C2 10.2994 2 8.76721 2.74902 7.6666C3.07328 7.19014 3.48995 6.78104 3.97524 6.46268C4.69555 5.99013 5.59733 5.82123 6.978 5.76086C7.63685 5.76086 8.20412 5.27068 8.33333 4.63636C8.52715 3.68489 9.37805 3 10.3663 3H13.6337C14.6219 3 15.4728 3.68489 15.6667 4.63636C15.7959 5.27068 16.3631 5.76086 17.022 5.76086C18.4027 5.82123 19.3044 5.99013 20.0248 6.46268C20.51 6.78104 20.9267 7.19014 21.251 7.6666C22 8.76721 22 10.2994 22 13.3636C22 16.4279 22 17.9601 21.251 19.0607C20.9267 19.5371 20.51 19.9462 20.0248 20.2646C18.9038 21 17.3433 21 14.2222 21H9.77778C6.65675 21 5.09624 21 3.97524 20.2646C3.48995 19.9462 3.07328 19.5371 2.74902 19.0607C2.53746 18.7498 2.38566 18.4045 2.27673 18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                &nbsp;Simpan
            </button>
            <a href="javascript:void(0);" class="btn btn-sm light btn-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M14.5197 10.6799L14.2397 10.4C13.0026 9.16288 10.9969 9.16288 9.75984 10.4C8.52276 11.637 8.52276 13.6427 9.75984 14.8798C10.9969 16.1169 13.0026 16.1169 14.2397 14.8798C14.7665 14.353 15.069 13.6868 15.1471 13M14.5197 10.6799L13 11M14.5197 10.6799V9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 13.3636C2 10.2994 2 8.76721 2.74902 7.6666C3.07328 7.19014 3.48995 6.78104 3.97524 6.46268C4.69555 5.99013 5.59733 5.82123 6.978 5.76086C7.63685 5.76086 8.20412 5.27068 8.33333 4.63636C8.52715 3.68489 9.37805 3 10.3663 3H13.6337C14.6219 3 15.4728 3.68489 15.6667 4.63636C15.7959 5.27068 16.3631 5.76086 17.022 5.76086C18.4027 5.82123 19.3044 5.99013 20.0248 6.46268C20.51 6.78104 20.9267 7.19014 21.251 7.6666C22 8.76721 22 10.2994 22 13.3636C22 16.4279 22 17.9601 21.251 19.0607C20.9267 19.5371 20.51 19.9462 20.0248 20.2646C18.9038 21 17.3433 21 14.2222 21H9.77778C6.65675 21 5.09624 21 3.97524 20.2646C3.48995 19.9462 3.07328 19.5371 2.74902 19.0607C2.53746 18.7498 2.38566 18.4045 2.27673 18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                &nbsp;Foto&nbsp;Ulang
            </a>
        </form>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

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
<script>
    var status_nomor = "{{old('status_nomor',$user_karyawan->status_nomor)}}";
    var status_alamat = "{{old('status_alamat',$user_karyawan->status_alamat)}}";
    if (status_nomor == 'ya') {
        $('#content_nomor_wa').hide();
    } else if (status_nomor == 'tidak') {
        $('#content_nomor_wa').show();
    } else {
        $('#content_nomor_wa').hide();
    }
    if (status_alamat == 'ya') {
        $('#content_alamat_domisili').hide();
    } else if (status_alamat == 'tidak') {
        $('#content_alamat_domisili').show();
    } else {
        $('#content_alamat_domisili').hide();
    }
    $(document).on("click", "#status_nomor", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').hide();
        } else {
            $('#content_nomor_wa').show();
        }
    });
    $(document).on("click", "#status_alamat_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').hide();

        }
    });
    $(document).on("click", "#status_alamat_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').show();
        }
    });
</script>
@endsection