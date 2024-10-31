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
                        <h5 class="title">Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="basic-form style-1">
                            <form method="POST" action="{{url('save_detail_profile')}}">
                                @csrf
                                <input type="hidden" name="karyawan_id" id="karyawan_id" value="{{$user_karyawan->id}}">
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-id-card"></i>
                                    </span>
                                    <input type="number" class="form-control @error('nik') is-invalid @enderror" name="nik" id="nik" placeholder="NIK" value="{{old('nik',$user_karyawan->nik)}}">
                                </div>
                                @error('nik')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" style="text-transform:uppercase" name="name" id="name" placeholder="Nama Lengkap" value="{{old('name',$user_karyawan->name)}}">
                                </div>
                                @error('name')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-at"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{old('email',$user_karyawan->email)}}">
                                </div>
                                @error('email')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <input type="number" class="form-control @error('telepon') is-invalid @enderror" placeholder="Telepon" id="telepon" name="telepon" value="{{old('telepon',$user_karyawan->telepon)}}">
                                </div>
                                @error('telepon')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="col-8">
                                    <div class="form-check check-box">
                                        <label class="form-check-label" for="status_nomor">Terhubung&nbsp;WhatsApps&nbsp;?</label>
                                        <input class="form-check-input @error('status_nomor') is-invalid @enderror" type="checkbox" value="ya" @if(old('status_nomor',$user_karyawan->status_nomor)=="ya") checked @else @endif name="status_nomor" id="status_nomor">
                                        <i class="check-1 far fa-square"></i>
                                        <i class="check-2 far fa-check-square"></i>
                                    </div>
                                    @error('status_nomor')
                                    <p class="alert alert-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div id="content_nomor_wa" class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                    <input type="number" class="form-control @error('nomor_wa') is-invalid @enderror" placeholder="Nomor Wa" id="nomor_wa" name="nomor_wa" value="{{old('nomor_wa',$user_karyawan->nomor_wa)}}">
                                </div>
                                @error('nomor_wa')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-building"></i>
                                    </span>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" value="{{old('tempat_lahir',$user_karyawan->tempat_lahir)}}">
                                </div>
                                @error('tempat_lahir')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa-solid fa-calendar"></i>
                                    </span>
                                    <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" placeholder="Tanggal Lahir" value="{{old('tgl_lahir',$user_karyawan->tgl_lahir)}}">
                                </div>
                                @error('tgl_lahir')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-tint"></i>
                                    </span>
                                    <input type="text" class="form-control @error('golongan_darah') is-invalid @enderror" id="golongan_darah" name="golongan_darah" placeholder="Golongan Darah" value="{{old('golongan_darah',$user_karyawan->golongan_darah)}}">
                                </div>
                                @error('golongan_darah')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-star"></i>
                                    </span>
                                    <select type="text" class="form-control @error('agama') is-invalid @enderror" id="agama" name="agama" placeholder="Agama">
                                        <option @if(old('agama',$user_karyawan->agama)=='') selected @else @endif disabled value=""> ~Pilih Agama~ </option>
                                        <option @if(old('agama',$user_karyawan->agama)=='ISLAM') selected @else @endif value="ISLAM">ISLAM</option>
                                        <option @if(old('agama',$user_karyawan->agama)=='KRISTEN PROTESTAN') selected @else @endif value="KRISTEN PROTESTAN">KRISTEN PROTESTAN</option>
                                        <option @if(old('agama',$user_karyawan->agama)=='KRISTEN KATOLIK') selected @else @endif value="KRISTEN KATOLIK">KRISTEN KATOLIK</option>
                                        <option @if(old('agama',$user_karyawan->agama)=='HINDU') selected @else @endif value="HINDU">HINDU</option>
                                        <option @if(old('agama',$user_karyawan->agama)=='BUDDHA') selected @else @endif value="BUDDHA">BUDDHA</option>
                                        <option @if(old('agama',$user_karyawan->agama)=='KHONGHUCU') selected @else @endif value="KHONGHUCU">KHONGHUCU</option>
                                    </select>
                                </div>
                                @error('agama')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-genderless"></i>
                                    </span>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option @if(old('gender',$user_karyawan->gender) == '') selected @else @endif value=""> ~ Pilih Kelamin ~</option>
                                        <option @if(old('gender',$user_karyawan->gender) == 'Laki-Laki') selected @else @endif value="Laki-Laki">Laki-Laki</option>
                                        <option @if(old('gender',$user_karyawan->gender) == 'Perempuan') selected @else @endif value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                @error('gender')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-tag"></i>
                                    </span>
                                    <select class="form-control @error('status_nikah') is-invalid @enderror" id="status_nikah" name="status_nikah">
                                        <option @if(old('status_nikah',$user_karyawan->status_nikah) == '') selected @else @endif value=""> ~ Pilih Status ~</option>
                                        <option @if(old('status_nikah',$user_karyawan->status_nikah) == 'Lajang') selected @else @endif value="Lajang">Lajang</option>
                                        <option @if(old('status_nikah',$user_karyawan->status_nikah) == 'Menikah') selected @else @endif value="Menikah">Menikah</option>
                                    </select>
                                </div>
                                @error('status_nikah')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </span>
                                    <select class="form-control @error('strata_pendidikan') is-invalid @enderror" id="strata_pendidikan" name="strata_pendidikan" placeholder="Tingkat Pendidikan">
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='' ) selected @else @endif disabled value=""> ~Pilih Tingkatan Pendidikan~ </option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='SEKOLAH DASAR (SD)' ) selected @else @endif value="SEKOLAH DASAR (SD)">SEKOLAH DASAR (SD)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='SEKOLAH MENENGAH PERTAMA (SMP)' ) selected @else @endif value="SEKOLAH MENENGAH PERTAMA (SMP)">SEKOLAH MENENGAH PERTAMA (SMP)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='SEKOLAH MENENGAH AKHIR (SMA)' ) selected @else @endif value="SEKOLAH MENENGAH AKHIR (SMA)">SEKOLAH MENENGAH AKHIR (SMA)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='SEKOLAH MENENGAH KEJURUAN (SMK)' ) selected @else @endif value="SEKOLAH MENENGAH KEJURUAN (SMK)">SEKOLAH MENENGAH KEJURUAN (SMK)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='DIPLOMA I (D1)' ) selected @else @endif value="DIPLOMA I (D1)">DIPLOMA I (D1)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='DIPLOMA II (D2)' ) selected @else @endif value="DIPLOMA II (D2)">DIPLOMA II (D2)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='DIPLOMA III (D3)' ) selected @else @endif value="DIPLOMA III (D3)">DIPLOMA III (D3)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='DIPLOMA IV (D4)' ) selected @else @endif value="DIPLOMA IV (D4)">DIPLOMA IV (D4)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='SARJANA (S1)' ) selected @else @endif value="SARJANA (S1)">SARJANA (S1)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='MAGISTER (S2)' ) selected @else @endif value="MAGISTER (S2)">MAGISTER (S2)</option>
                                        <option @if(old('strata_pendidikan',$user_karyawan->strata_pendidikan)=='DOKTOR (S3)' ) selected @else @endif value="DOKTOR (S3)">DOKTOR (S3)</option>
                                    </select>
                                </div>
                                @error('strata_pendidikan')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </span>
                                    <input type="text" class="form-control @error('instansi_pendidikan') is-invalid @enderror" id="instansi_pendidikan" name="instansi_pendidikan" placeholder="Instansi Pendidikan" value="{{old('instansi_pendidikan',$user_karyawan->instansi_pendidikan)}}">
                                </div>
                                @error('instansi_pendidikan')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
                                <div class="mb-3 form-input">
                                    <span class="input-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </span>
                                    <input type="text" class="form-control @error('jurusan_akademik') is-invalid @enderror" id="jurusan_akademik" name="jurusan_akademik" placeholder="Jurusan Akademik" value="{{old('jurusan_akademik',$user_karyawan->jurusan_akademik)}}">
                                </div>
                                @error('jurusan_akademik')
                                <p class="alert alert-danger">{{$message}}</p>
                                @enderror
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
    if (status_nomor == 'ya') {
        $('#content_nomor_wa').hide();
    } else if (status_nomor == 'tidak') {
        $('#content_nomor_wa').show();
    } else {
        $('#content_nomor_wa').hide();
    }
    $(document).on("click", "#status_nomor", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').hide();
        } else {
            $('#content_nomor_wa').show();
        }
    });
</script>
@endsection