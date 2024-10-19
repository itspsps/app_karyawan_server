@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> DETAIL KARYAWAN</h4>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{$karyawan->name}}&nbsp;<b>[{{$karyawan->nomor_identitas_karyawan}}]</b></a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-info" href="@if(Auth::user()->is_admin=='hrd'){{url('/hrd/karyawan/shift/'.$karyawan->id.'/'.$holding)}}@else{{url('/karyawan/shift/'.$karyawan->id.'/'.$holding)}}@endif"><i class="mdi mdi-clock-outline mdi-20px me-1"></i>Mapping Jadwal&nbsp;</a>
                </li>
            </ul>
            <div class="card mb-4">
                <h4 class="card-header">Detail Profil</h4>
                <!-- Account -->
                <form method="post" action="@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/proses-edit/'.$karyawan->id.'/'.$holding) }}@else{{ url('/karyawan/proses-edit/'.$karyawan->id.'/'.$holding) }}@endif" enctype="multipart/form-data">
                    @csrf
                    <input style="font-size: small;" type="hidden" value="{{$karyawan->id}}" name="id_karyawan" id="id_karyawan">
                    <div class="card-body">
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#nav_profile" aria-controls="nav_profile" aria-selected="true">
                                        <i class="tf-icons mdi mdi-account-outline me-1"></i>
                                        PROFILE
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_alamat" aria-controls="nav_alamat" aria-selected="false">
                                        <i class="tf-icons mdi mdi-home-city me-1"></i>
                                        ALAMAT
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_info_hr" aria-controls="nav_info_hr" aria-selected="false">
                                        <i class="tf-icons mdi mdi-account-cog-outline me-1"></i>
                                        INFO HR
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_jabatan" aria-controls="nav_jabatan" aria-selected="false">
                                        <i class="tf-icons mdi mdi-medal-outline me-1"></i>
                                        JABATAN
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_bank" aria-controls="nav_bank" aria-selected="false">
                                        <i class="tf-icons mdi mdi-bank-circle me-1"></i>
                                        BANK
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_pajak" aria-controls="nav_pajak" aria-selected="false">
                                        <i class="tf-icons mdi mdi-percent-outline me-1"></i>
                                        PAJAK
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_bpjs" aria-controls="nav_bpjs" aria-selected="false">
                                        <i class="tf-icons mdi mdi-card-account-details-outline me-1"></i>
                                        BPJS
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav_dokumen" aria-controls="nav_dokumen" aria-selected="false">
                                        <i class="tf-icons mdi mdi-file-document-multiple-outline me-1"></i>
                                        DOKUMEN
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="nav_profile" role="tabpanel">
                                    <input style="font-size: small;" type="file" name="foto_karyawan" id="foto_karyawan" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                    <div class="col-md-3">
                                        <span class="mdi mdi-account-tie badge bg-label-info">&nbsp;Biodata Diri</span>
                                    </div>
                                    <hr class="m-0">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nik') is-invalid @enderror" type="number" id="nik" name="nik" value="{{old('nik', $karyawan->nik)}}" autofocus />
                                                <label for="nik">NIK</label>
                                            </div>
                                            @error('nik')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $karyawan->name) }}">
                                                <label for="name">Nama&nbsp;Lengkap</label>
                                            </div>
                                            @error('name')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('fullname') is-invalid @enderror" type="text" name="fullname" id="fullname" value="{{ old('fullname', $karyawan->fullname)}}" />
                                                <label for="fullname">Fullname</label>
                                            </div>
                                            @error('fullname')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $karyawan->email) }}">
                                                <label for="email">E-mail</label>
                                            </div>
                                            @error('email')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $karyawan->telepon) }}">
                                                <label for="telepon">Telepon</label>
                                            </div>
                                            @error('telepon')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <h6>Apakah Nomor Telepon Terhubung WhatsApps ?</h6>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check @error('status_nomor') is-invalid @enderror" name="status_nomor" value="" @if(old('status_nomor',$karyawan->status_nomor)=="") checked @else @endif>
                                                <input type="radio" class="btn-check @error('status_nomor') is-invalid @enderror" name="status_nomor" id="btn_status_no_ya" value="ya" @if(old('status_nomor',$karyawan->status_nomor)=="ya" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-success waves-effect" for="btn_status_no_ya">Ya</label>
                                                <input type="radio" class="btn-check @error('status_nomor') is-invalid @enderror" name="status_nomor" id="btn_status_no_tidak" value="tidak" @if(old('status_nomor',$karyawan->status_nomor)=="tidak" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-primary waves-effect" for="btn_status_no_tidak">Tidak</label>
                                                @error('status_nomor')
                                                <p class="alert alert-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div id="content_nomor_wa" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nomor_wa') is-invalid @enderror" type="number" name="nomor_wa" id="nomor_wa" value="{{ old('nomor_wa',$karyawan->nomor_wa)}}" />
                                                <label for="nomor_wa">Nomor WA</label>
                                            </div>
                                            @error('nomor_wa')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir',$karyawan->tempat_lahir) }}">
                                                <label for="tempat_lahir">Tempat Lahir</label>
                                            </div>
                                            @error('tempat_lahir')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="date" id="tgl_lahir" value="{{old('tgl_lahir',$karyawan->tgl_lahir)}}" name="tgl_lahir" placeholder="Tanggal Lahir" />
                                                <label for="tgl_lahir">Tanggal Lahir</label>
                                            </div>
                                            @error('tgl_lahir')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="text" id="golongan_darah" value="{{old('golongan_darah',$karyawan->golongan_darah)}}" name="golongan_darah" placeholder="Golongan Darah" />
                                                <label for="golongan_darah">Golongan Darah</label>
                                            </div>
                                            @error('golongan_darah')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;"type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $karyawan->username) }}">
                                                <input style="font-size: small;"type="hidden" name="password" value="{{ $karyawan->password }}">
                                                <label for="username">Username</label>
                                            </div>
                                            @error('username')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control" id="agama" name="agama">
                                                    <option @if(old('agama',$karyawan->agama)=='') selected @else @endif disabled value=""> ~Pilih Agama~ </option>
                                                    <option @if(old('agama',$karyawan->agama)=='ISLAM') selected @else @endif value="ISLAM">ISLAM</option>
                                                    <option @if(old('agama',$karyawan->agama)=='KRISTEN PROTESTAN') selected @else @endif value="KRISTEN PROTESTAN">KRISTEN PROTESTAN</option>
                                                    <option @if(old('agama',$karyawan->agama)=='KRISTEN KATOLIK') selected @else @endif value="KRISTEN KATOLIK">KRISTEN KATOLIK</option>
                                                    <option @if(old('agama',$karyawan->agama)=='HINDU') selected @else @endif value="HINDU">HINDU</option>
                                                    <option @if(old('agama',$karyawan->agama)=='BUDDHA') selected @else @endif value="BUDDHA">BUDDHA</option>
                                                    <option @if(old('agama',$karyawan->agama)=='KHONGHUCU') selected @else @endif value="KHONGHUCU">KHONGHUCU</option>
                                                </select>
                                                <label for="agama">Agama</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $gender = array(
                                                    [
                                                        "gender" => "Laki-Laki"
                                                    ],
                                                    [
                                                        "gender" => "Perempuan"
                                                    ]
                                                );
                                                ?>
                                                <select style="font-size: small;" name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                                    @foreach ($gender as $g)
                                                    @if(old('gender', $karyawan->gender) == $g["gender"])
                                                    <option value="{{ $g["gender"] }}" selected>{{ $g["gender"] }}</option>
                                                    @else
                                                    <option value="{{ $g["gender"] }}">{{ $g["gender"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="gender">Kelamin</label>
                                            </div>
                                            @error('gender')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $sNikah = array(
                                                    [
                                                        "status" => "Lajang"
                                                    ],
                                                    [
                                                        "status" => "Menikah"
                                                    ]
                                                );
                                                ?>
                                                <select style="font-size: small;" name="status_nikah" id="status_nikah" class="form-control selectpicker" data-live-search="true">
                                                    @foreach ($sNikah as $s)
                                                    @if(old('status_nikah', $karyawan->status_nikah) == $s["status"])
                                                    <option value="{{ $s["status"] }}" selected>{{ $s["status"] }}</option>
                                                    @else
                                                    <option value="{{ $s["status"] }}">{{ $s["status"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="status_nikah">Status Nikah</label>
                                            </div>
                                            @error('status_nikah')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <span class="mdi mdi-account-school-outline badge bg-label-info">&nbsp;Pendidikan</span>
                                    </div>
                                    <hr class="m-0 mb-3">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control" id="strata_pendidikan" name="strata_pendidikan" value="{{old('strata_pendidikan') }}">
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='' ) selected @else @endif disabled value=""> ~Pilih Tingkatan Pendidikan~ </option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='SEKOLAH DASAR (SD)' ) selected @else @endif value="SEKOLAH DASAR (SD)">SEKOLAH DASAR (SD)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='SEKOLAH MENENGAH PERTAMA (SMP)' ) selected @else @endif value="SEKOLAH MENENGAH PERTAMA (SMP)">SEKOLAH MENENGAH PERTAMA (SMP)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='SEKOLAH MENENGAH AKHIR (SMA)' ) selected @else @endif value="SEKOLAH MENENGAH AKHIR (SMA)">SEKOLAH MENENGAH AKHIR (SMA)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='SEKOLAH MENENGAH KEJURUAN (SMK)' ) selected @else @endif value="SEKOLAH MENENGAH KEJURUAN (SMK)">SEKOLAH MENENGAH KEJURUAN (SMK)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='DIPLOMA I (D1)' ) selected @else @endif value="DIPLOMA I (D1)">DIPLOMA I (D1)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='DIPLOMA II (D2)' ) selected @else @endif value="DIPLOMA II (D2)">DIPLOMA II (D2)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='DIPLOMA III (D3)' ) selected @else @endif value="DIPLOMA III (D3)">DIPLOMA III (D3)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='DIPLOMA IV (D4)' ) selected @else @endif value="DIPLOMA IV (D4)">DIPLOMA IV (D4)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='SARJANA (S1)' ) selected @else @endif value="SARJANA (S1)">SARJANA (S1)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='MAGISTER (S2)' ) selected @else @endif value="MAGISTER (S2)">MAGISTER (S2)</option>
                                                    <option @if(old('strata_pendidikan',$karyawan->strata_pendidikan)=='DOKTOR (S3)' ) selected @else @endif value="DOKTOR (S3)">DOKTOR (S3)</option>
                                                </select>
                                                <label for="strata_pendidikan">Tingkat Pendidikan</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="text" id="instansi_pendidikan" value="{{old('instansi_pendidikan',$karyawan->instansi_pendidikan)}}" name="instansi_pendidikan" placeholder="Instansi Pendidikan" />
                                                <label for="instansi_pendidikan">Instansi Pendidikan</label>
                                            </div>
                                            @error('instansi_pendidikan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control" type="text" id="jurusan_akademik" value="{{old('jurusan_akademik',$karyawan->jurusan_akademik)}}" name="jurusan_akademik" placeholder="Jurusan Akademik" />
                                                <label for="jurusan_akademik">Jurusan Akademik</label>
                                            </div>
                                            @error('jurusan_akademik')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_cv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Lampiran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($karyawan->file_cv=='')
                                                <iframe id="lihat_file_cv" src="" style=" height: 500px; width: 100%;"></iframe>
                                                @else
                                                <iframe id="lihat_file_cv" src="{{url('https://karyawan.sumberpangan.store/laravel/storage/app/public/file_cv/'.$karyawan->file_cv)}}" style=" height: 500px; width: 100%;"></iframe>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_info_hr" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <?php $kategori = array(
                                                [
                                                    "kategori" => "Karyawan Bulanan"
                                                ],
                                                [
                                                    "kategori" => "Karyawan Harian"
                                                ]
                                            );
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="kategori" id="kategori" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($kategori as $a)
                                                    @if(old('kategori', $karyawan->kategori) == $a["kategori"])
                                                    <option value="{{ $a["kategori"] }}" selected>{{ $a["kategori"] }}</option>
                                                    @else
                                                    <option value="{{ $a["kategori"] }}">{{ $a["kategori"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="kategori">Kategori Karyawan</label>
                                            </div>
                                            @error('kategori')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_kontrak" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control" readonly value="@if($karyawan->kontrak_kerja =='SP')CV. SUMBER PANGAN @elseif($karyawan->kontrak_kerja =='SPS') PT. SURYA PANGAN SEMESTA @elseif($karyawan->kontrak_kerja =='SIP') CV. SURYA INTI PANGAN  @endif">
                                                <input style="font-size: small;" type="hidden" class="form-control" id="kontrak_kerja" name="kontrak_kerja" value="{{$karyawan->kontrak_kerja}}">
                                                <label for="kontrak_kerja">Kontrak Kerja</label>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" disabled type="date" class="form-control @error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" value="{{ old('tgl_join', $karyawan->tgl_join) }}">
                                                <label for="tgl_join">Tanggal Join Perusahaan</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('tgl_join')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_lama_kontrak" class="col-md-3">
                                            <?php $lama_kontrak_kerja = array(
                                                [
                                                    "lama_kontrak_kerja" => "3 bulan"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "6 bulan"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "1 tahun"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "2 bahun"
                                                ],
                                                [
                                                    "lama_kontrak_kerja" => "tetap"
                                                ]
                                            );
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="lama_kontrak_kerja" id="lama_kontrak_kerja" disabled class="form-control selectpicker @error('lama_kontrak_kerja') is-invalid @enderror" data-live-search="true">
                                                    <option value="">Pilih Kontrak</option>
                                                    @foreach ($lama_kontrak_kerja as $a)
                                                    @if(old('lama_kontrak_kerja', $karyawan->lama_kontrak_kerja) == $a["lama_kontrak_kerja"])
                                                    <option value="{{ $a["lama_kontrak_kerja"] }}" selected>{{ $a["lama_kontrak_kerja"] }}</option>
                                                    @else
                                                    <option value="{{ $a["lama_kontrak_kerja"] }}">{{ $a["lama_kontrak_kerja"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="lama_kontrak_kerja">Lama Kontrak</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('lama_kontrak_kerja')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_tgl_mulai_kontrak" class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" disabled type="date" readonly class="form-control @error('tgl_mulai_kontrak') is-invalid @enderror" id="tgl_mulai_kontrak" name="tgl_mulai_kontrak" value="{{old('tgl_mulai_kontrak', $karyawan->tgl_mulai_kontrak) }}" />
                                                <label for="tgl_mulai_kontrak">Tanggal Mulai Kontrak</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('tgl_mulai_kontrak')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_tgl_selesai_kontrak" class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" disabled type="date" readonly class="form-control @error('tgl_selesai_kontrak') is-invalid @enderror" id="tgl_selesai_kontrak" name="tgl_selesai_kontrak" value="{{old('tgl_selesai_kontrak', $karyawan->tgl_selesai_kontrak) }}" />
                                                <label for=" tgl_selesai_kontrak">Tanggal Selesai Kontrak</label>
                                                <span class="badge bg-label-danger">Tidak Dapat Di Ubah</span>
                                            </div>
                                            @error('tgl_selesai_kontrak')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_kuota_cuti" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="kuota_cuti" name="kuota_cuti" class="form-control @error('kuota_cuti') is-invalid @enderror" placeholder="Masukkan Cuti Tahunan" value="{{ old('kuota_cuti',$karyawan->kuota_cuti_tahunan) }}" />
                                                <label for="kuota_cuti">Kuota Cuti Tahunan</label>
                                            </div>
                                            @error('kuota_cuti')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_alamat" role="tabpanel">
                                    <div class="row gy-4">
                                        <div class="col-md-3">
                                            <span class="badge bg-label-info">Alamat Berdasarkan KTP</span>
                                        </div>
                                        <hr class="m-0">
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('provinsi') is-invalid @enderror" id="id_provinsi" name="provinsi">
                                                    <option value=""> Pilih Provinsi </option>
                                                    @foreach($data_provinsi as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('provinsi',$karyawan->provinsi)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_provinsi">Provinsi</label>
                                            </div>
                                            @error('provinsi')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $kab = App\Models\Cities::where('province_code', old('provinsi', $karyawan->provinsi))->orderBy('name', 'ASC')->get();
                                            $kec = App\Models\District::where('city_code', old('kabupaten', $karyawan->kabupaten))->orderBy('name', 'ASC')->get();
                                            $desa = App\Models\Village::where('district_code', old('kecamatan', $karyawan->kecamatan))->orderBy('name', 'ASC')->get();
                                            // echo $kab;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('kabupaten') is-invalid @enderror" id="id_kabupaten" name="kabupaten">
                                                    <option value=""> Pilih Kabupaten / Kota</option>
                                                    @foreach ($kab as $kabupaten)
                                                    <option value="{{$kabupaten->code}}" {{($kabupaten->code == old('kabupaten',$karyawan->kabupaten)) ? 'selected' : ''}}>{{$kabupaten->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kabupaten">Kabupaten</label>
                                            </div>
                                            @error('kabupaten')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('kecamatan') is-invalid @enderror" id="id_kecamatan" name="kecamatan">
                                                    <option value=""> Pilih kecamatan</option>
                                                    @foreach($kec as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kecamatan',$karyawan->kecamatan)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kecamatan">kecamatan</label>
                                            </div>
                                            @error('kecamatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('desa') is-invalid @enderror" id="id_desa" name="desa">
                                                    <option value=""> Pilih Desa</option>
                                                    @foreach ($desa as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('desa',$karyawan->desa)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_desa">Desa</label>
                                            </div>
                                            @error('desa')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rt" name="rt" class="form-control @error('rt') is-invalid @enderror" placeholder="Masukkan RT" value="{{ old('rt', $karyawan->rt) }}" />
                                                <label for="rt">RT</label>
                                            </div>
                                            @error('rt')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" id="rw" name="rw" class="form-control @error('rw') is-invalid @enderror" placeholder="Masukkan RW" value="{{ old('rw',$karyawan->rw) }}" />
                                                <label for="rw">RW</label>
                                            </div>
                                            @error('rw')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" value="{{ old('alamat',$karyawan->alamat) }}" />
                                                <label for="alamat">Keterangan Alamat(Jalan / Dusun)</label>
                                            </div>
                                            @error('alamat')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <h6>Apakah Alamat KTP Sama Dengan Alamat Domisili ?</h6>
                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                <input style="font-size: small;" type="radio" class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror" name="pilihan_alamat_domisili" value="" checked>
                                                <input style="font-size: small;" type="radio" class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror" name="pilihan_alamat_domisili" id="btnradio_ya" value="ya" @if(old('pilihan_alamat_domisili',$karyawan->status_alamat)=="ya" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-success waves-effect" for="btnradio_ya">Ya</label>
                                                <input style="font-size: small;" type="radio" class="btn-check @error('pilihan_alamat_domisili') is-invalid @enderror" name="pilihan_alamat_domisili" id="btnradio_tidak" value="tidak" @if(old('pilihan_alamat_domisili',$karyawan->status_alamat)=="tidak" ) checked @else @endif>
                                                <label class="btn btn-sm btn-outline-primary waves-effect" for="btnradio_tidak">Tidak</label>
                                                @error('pilihan_alamat_domisili')
                                                <p class="alert alert-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div id="content_alamat_domisili" class="row mt-2 gy-4">
                                        <div class="col-md-3">
                                            <span class="badge bg-label-danger">Alamat Berdasarkan Domisili Sekarang</span>
                                        </div>
                                        <hr class="m-0">
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('provinsi_domisili') is-invalid @enderror" id="id_provinsi_domisili" name="provinsi_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Provinsi </option>
                                                    @foreach($data_provinsi as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('provinsi_domisili',$karyawan->provinsi_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_provinsi_domisili">Provinsi</label>
                                            </div>
                                            @error('provinsi_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            $kab_domisili = App\Models\Cities::Where('province_code', old('provinsi_domisili', $karyawan->provinsi_domisili))->orderBy('name', 'ASC')->get();
                                            $kec_domisili = App\Models\District::Where('city_code', old('kabupaten_domisili', $karyawan->kabupaten_domisili))->orderBy('name', 'ASC')->get();
                                            $desa_domisili = App\Models\Village::Where('district_code', old('kecamatan_domisili', $karyawan->kecamatan_domisili))->orderBy('name', 'ASC')->get();
                                            // echo $kab;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('kabupaten_domisili') is-invalid @enderror" id="id_kabupaten_domisili" name="kabupaten_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Kabupaten / Kota</option>
                                                    @foreach($kab_domisili as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kabupaten_domisili',$karyawan->kabupaten_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kabupaten_domisili">Kabupaten</label>
                                            </div>
                                            @error('kabupaten_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('kecamatan_domisili') is-invalid @enderror" id="id_kecamatan_domisili" name="kecamatan_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Kecamatan</option>
                                                    @foreach($kec_domisili as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('kecamatan_domisili',$karyawan->kecamatan_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_kecamatan_domisili">kecamatan_domisili</label>
                                            </div>
                                            @error('kecamatan_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('desa_domisili') is-invalid @enderror" id="id_desa_domisili" name="desa_domisili" style="font-size: small;">
                                                    <option value=""> Pilih Desa</option>
                                                    @foreach($desa_domisili as $data)
                                                    <option value="{{$data->code}}" {{($data->code == old('desa_domisili',$karyawan->desa_domisili)) ? 'selected' : ''}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_desa_domisili">Desa</label>
                                            </div>
                                            @error('desa_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" style="font-size: small;" type="number" id="rt_domisili" name="rt_domisili" class="form-control @error('rt_domisili') is-invalid @enderror" placeholder="Masukkan RT" value="{{ old('rt_domisili',$karyawan->rt_domisili) }}" />
                                                <label for="rt_domisili">RT</label>
                                            </div>
                                            @error('rt_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" style="font-size: small;" type="number" id="rw_domisili" name="rw_domisili" class="form-control @error('rw_domisili') is-invalid @enderror" placeholder="Masukkan RW" value="{{ old('rw_domisili',$karyawan->rw_domisili) }}" />
                                                <label for="rw_domisili">RW</label>
                                            </div>
                                            @error('rw_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" style="font-size: small;" type="text" id="alamat_domisili" name="alamat_domisili" class="form-control @error('alamat_domisili') is-invalid @enderror" placeholder="Masukkan Alamat" value="{{ old('alamat_domisili',$karyawan->alamat_domisili) }}" />
                                                <label for="alamat_domisili">Keterangan Alamat(Jalan / Dusun)</label>
                                            </div>
                                            @error('alamat_domisili')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_jabatan" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div id="form_site" class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('site_job') is-invalid @enderror" id="site_job" name="site_job">
                                                    <option selected disabled value=""> Pilih Site Job</option>
                                                    @foreach ($data_lokasi as $a)
                                                    @if(old('site_job',$karyawan->site_job) == $a["lokasi_kantor"])
                                                    <option value="{{ $a["lokasi_kantor"] }}" selected>{{ $a["lokasi_kantor"] }}</option>
                                                    @else
                                                    <option value="{{ $a["lokasi_kantor"] }}">{{ $a["lokasi_kantor"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="site_job">Site yang Dipegang</label>
                                            </div>
                                            <p class="text-info">Untuk Kebutuhan Approval</p>
                                            @error('site_job')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('penempatan_kerja') is-invalid @enderror" id="penempatan_kerja" name="penempatan_kerja">
                                                    <option selected disabled value=""> Pilih Lokasi Penempatan</option>
                                                    @foreach ($data_lokasi1 as $a)
                                                    @if(old('penempatan_kerja',$karyawan->penempatan_kerja) == $a["lokasi_kantor"])
                                                    <option value="{{ $a["lokasi_kantor"] }}" selected>{{ $a["lokasi_kantor"] }}</option>
                                                    @else
                                                    <option value="{{ $a["lokasi_kantor"] }}">{{ $a["lokasi_kantor"] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="penempatan_kerja">Penempatan Kerja</label>
                                            </div>
                                            <p class="text-info">Untuk Kebutuhan Absensi</p>
                                            @error('penempatan_kerja')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>


                                        <div id="row_kategori_jabatan" style="margin-top: -1%; margin-left: 2%;" class="col mb-6">
                                            <label class="form-check-label mb-2" for="kategori_jabatan">Pilih Kategori Jabatan (Untuk All Site)</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4">
                                                    <div class="col-lg-4 form-check">
                                                        <input style="font-size: small;" type="radio" id="kategori_jabatan_sp" name="kategori_jabatan" class="form-check-input" value="sp" @if(old('kategori_jabatan', $karyawan->kategori_jabatan)=='sp') checked @else @endif>
                                                        <label class="form-check-label" for="kategori_jabatan_sp">CV. SUMBER PANGAN</label>
                                                    </div>
                                                    <div class="col-lg-4 form-check">
                                                        <input style="font-size: small;" type="radio" id="kategori_jabatan_sps" name="kategori_jabatan" class="form-check-input" value="sps" @if(old('kategori_jabatan', $karyawan->kategori_jabatan)=='sps') checked @else @endif>
                                                        <label class="form-check-label" for="kategori_jabatan_sps">PT. SURYA PANGAN SEMESTA</label>
                                                    </div>
                                                    <div class="col-lg-4 form-check">
                                                        <input style="font-size: small;" type="radio" id="kategori_jabatan_sip" name="kategori_jabatan" class="form-check-input" value="sip" @if(old('kategori_jabatan', $karyawan->kategori_jabatan)=='sip') checked @else @endif>
                                                        <label class="form-check-label" for="kategori_jabatan_sip">CV. SURYA INTI PANGAN</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input style="font-size: small;" type="hidden" name="kategori_jabatan" id="kategori_jabatan" value="{{old('kategori_jabatan',$karyawan->kategori_jabatan)}}">
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div id="form_departemen" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == NULL) {
                                                // echo 'ok';
                                                $get_kategori_jabatan = App\Models\Lokasi::where('lokasi_kantor', old('site_job', $karyawan->site_job))->value('kategori_kantor');
                                                if (old('kategori_jabatan', $get_kategori_jabatan) == 'sp' || old('kategori_jabatan', $get_kategori_jabatan) == 'all sp') {
                                                    $kategori_jabatan = 'sp';
                                                    $holding_jabatan = 'CV. SUMBER PANGAN';
                                                } else if (old('kategori_jabatan', $get_kategori_jabatan) == 'sps' || old('kategori_jabatan', $get_kategori_jabatan) == 'all sps') {
                                                    $kategori_jabatan = 'sps';
                                                    $holding_jabatan = 'PT. SURYA PANGAN SEMESTA';
                                                } else if (old('kategori_jabatan', $get_kategori_jabatan) == 'sip' || old('kategori_jabatan', $get_kategori_jabatan) == 'all sip') {
                                                    $kategori_jabatan = 'sip';
                                                    $holding_jabatan = 'CV. SURYA INTI PANGAN';
                                                } else if (old('kategori_jabatan', $get_kategori_jabatan) == 'all') {
                                                    $kategori_jabatan = $holding;
                                                    $holding_jabatan = $holding;
                                                } else {
                                                    $kategori_jabatan = $holding;
                                                    $holding_jabatan = NULL;
                                                }
                                                // echo $kategori_jabatan;
                                            } else {
                                                // echo 'ok2';
                                                $kategori_jabatan = old('kategori_jabatan', $karyawan->kategori_jabatan);
                                                if (old('kategori_jabatan', $karyawan->kategori_jabatan) == 'sp') {
                                                    $holding_jabatan = 'CV. SUMBER PANGAN';
                                                } else if (old('kategori_jabatan', $karyawan->kategori_jabatan) == 'sps') {
                                                    $holding_jabatan = 'PT. SURYA PANGAN SEMESTA';
                                                } else if (old('kategori_jabatan', $kategori_jabatan) == 'sip') {
                                                    $holding_jabatan = 'CV. SURYA INTI PANGAN';
                                                } else {
                                                    $holding_jabatan = NULL;
                                                }
                                                // print_r($kategori_jabatan);
                                            }
                                            $data_departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                            // print_r($data_departemen);
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="departemen_id" id="id_departemen" class="form-control @error('departemen_id') is-invalid @enderror">
                                                    <option value=""> Pilih Departemen</option>
                                                    <optgroup label='Daftar Departemen {{$holding_jabatan}}'>
                                                        @foreach ($data_departemen as $dj)
                                                        @if(old('departemen_id',$karyawan->dept_id) == $dj->id)
                                                        <option value="{{ $dj->id }}" selected>{{ $dj->nama_departemen }}</option>
                                                        @else
                                                        <option value="{{ $dj->id }}">{{ $dj->nama_departemen }}</option>
                                                        @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_departemen">Departemen</label>
                                            </div>
                                            @error('departemen_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_divisi" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = $karyawan->kategori_jabatan;
                                            }
                                            $data_divisi = App\Models\Divisi::Where('dept_id', old('departemen_id', $karyawan->dept_id))->orderBy('nama_divisi', 'ASC')->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="divisi_id" id="id_divisi" class="form-control @error('divisi_id') is-invalid @enderror">
                                                    <option selected disabled value="">Pilih Divisi</option>
                                                    <optgroup label='Daftar Divisi {{$holding_jabatan}}'>
                                                        @foreach ($data_divisi as $divisi)
                                                        @if(old('divisi_id', $karyawan->divisi_id) == $divisi['id'])
                                                        <option value="{{$divisi->id}}" selected>{{$divisi->nama_divisi}}</option>
                                                        @else
                                                        <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option>
                                                        @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_divisi">Divisi</label>
                                            </div>
                                            @error('divisi_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_bagian" class="col-md-3">
                                            <?php
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = $karyawan->kategori_jabatan;
                                            }
                                            $data_bagian = App\Models\Bagian::Where('divisi_id', old('divisi_id', $karyawan->divisi_id))->orderBy('nama_bagian', 'ASC')->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="bagian_id" id="id_bagian" class="form-control @error('bagian_id') is-invalid @enderror">
                                                    <option selected disabled value="">Pilih Bagian</option>
                                                    <optgroup label='Daftar Bagian {{$holding_jabatan}}'>
                                                        @foreach ($data_bagian as $bagian)
                                                        @if(old('bagian_id', $karyawan->bagian_id) == $bagian['id'])
                                                        <option value="{{$bagian->id}}" selected>{{$bagian->nama_bagian}}</option>
                                                        @else
                                                        <option value="{{$bagian->id}}">{{$bagian->nama_bagian}}</option>
                                                        @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_bagian">Bagian</label>
                                            </div>
                                            @error('bagian_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_jabatan" class="col-md-3">
                                            <?php
                                            // Bagian
                                            if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                $kategori_jabatan = $holding;
                                            } else {
                                                $kategori_jabatan = $karyawan->kategori_jabatan;
                                            }
                                            $data_bagian = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi_id))->orderBy('nama_bagian', 'ASC')->get();
                                            $data_bagian1 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi1_id))->orderBy('nama_bagian', 'ASC')->get();
                                            $data_bagian2 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi2_id))->orderBy('nama_bagian', 'ASC')->get();
                                            $data_bagian3 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi3_id))->orderBy('nama_bagian', 'ASC')->get();
                                            $data_bagian4 = App\Models\Bagian::Where('divisi_id', old('bagian_id', $karyawan->divisi4_id))->orderBy('nama_bagian', 'ASC')->get();
                                            // Jabatan
                                            $data_jabatan = App\Models\Jabatan::Where('bagian_id', old('bagian_id', $karyawan->bagian_id))->where(old('disivi_id', $karyawan->disivi_id))->orderBy('nama_jabatan', 'ASC')->get();
                                            $data_jabatan1 = App\Models\Jabatan::Where('bagian_id', old('bagian1_id', $karyawan->bagian1_id))->where(old('disivi1_id', $karyawan->disivi1_id))->orderBy('nama_jabatan', 'ASC')->get();
                                            $data_jabatan2 = App\Models\Jabatan::Where('bagian_id', old('bagian2_id', $karyawan->bagian2_id))->where(old('disivi2_id', $karyawan->disivi2_id))->orderBy('nama_jabatan', 'ASC')->get();
                                            $data_jabatan3 = App\Models\Jabatan::Where('bagian_id', old('bagian3_id', $karyawan->bagian3_id))->where(old('disivi3_id', $karyawan->disivi3_id))->orderBy('nama_jabatan', 'ASC')->get();
                                            $data_jabatan4 = App\Models\Jabatan::Where('bagian_id', old('bagian4_id', $karyawan->bagian4_id))->where(old('disivi4_id', $karyawan->disivi4_id))->orderBy('nama_jabatan', 'ASC')->get();
                                            // echo $kec;
                                            ?>
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" name="jabatan_id" id="id_jabatan" class="form-control @error('jabatan_id') is-invalid @enderror">
                                                    <option value="">Pilih Jabatan</option>
                                                    <optgroup label='Daftar Jabatan {{$holding_jabatan}}'>
                                                        @foreach ($data_jabatan as $jabatan)
                                                        <option value="{{$jabatan->id}}" {{($jabatan->id == $karyawan->jabatan_id) ? 'selected' : ''}}>{{$jabatan->nama_jabatan}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <label for="id_jabatan">Jabatan</label>
                                            </div>
                                            @error('jabatan_id')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div id="form_jabatan_more" class="row g-2 mt-2">
                                            <div class="col mb-2">
                                                <div class="accordion mt-3" id="accordionExample">
                                                    <div class="accordion-item @if($karyawan->jabatan1_id!='') active @endif">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                                                Jika Karyawan Memiliki Lebih Dari 1 Jabatan
                                                            </button>
                                                        </h2>

                                                        <div id="accordionOne" class="accordion-collapse collapse @if($karyawan->jabatan1_id!='') show @endif" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen1_id" id="id_departemen1" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                <?php
                                                                                if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                    $kategori_jabatan = $holding;
                                                                                } else {
                                                                                    $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                }
                                                                                $departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                                                                ?>
                                                                                <optgroup label='Daftar Departemen {{$holding_jabatan}}'>
                                                                                    @foreach($departemen as $departemen)
                                                                                    @if(old('departemen1_id',$karyawan->dept1_id) == $departemen->id)
                                                                                    <option value="{{ $departemen->id }}" selected>{{ $departemen->nama_departemen }}</option>
                                                                                    @else
                                                                                    <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                                                                                    <!-- <option value="{{$departemen->id}}">{{$departemen->nama_departemen}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_departemen1">Departemen 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi1_id" id="id_divisi1" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>
                                                                                <optgroup label='Daftar Divisi {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $divisi = App\Models\Divisi::where('dept_id', old('departemen1_id', $karyawan->dept1_id))->orderBy('nama_divisi', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($divisi as $divisi)
                                                                                    @if(old('divisi1_id',$karyawan->divisi1_id) == $divisi->id)
                                                                                    <option value="{{ $divisi->id }}" selected>{{ $divisi->nama_divisi }}</option>
                                                                                    @else
                                                                                    <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_divisi1">Divisi 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian1_id" id="id_bagian1" class="form-control @error('bagian1_id') is-invalid @enderror">
                                                                                <option value=""> Pilih Bagian</option>
                                                                                <optgroup label='Daftar Bagian {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $bagian = App\Models\Bagian::where('divisi_id', old('divisi1_id', $karyawan->divisi1_id))->orderBy('nama_bagian', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($bagian as $bagian)
                                                                                    @if(old('bagian1_id',$karyawan->bagian1_id) == $bagian->id)
                                                                                    <option value="{{ $bagian->id }}" selected>{{ $bagian->nama_bagian }}</option>
                                                                                    @else
                                                                                    <option value="{{ $bagian->id }}">{{ $bagian->nama_bagian }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for="id_bagian1">Bagian 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan1_id" id="id_jabatan1" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>
                                                                                <optgroup label='Daftar Jabatan {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $jabatan = App\Models\Jabatan::where('bagian_id', old('bagian1_id', $karyawan->bagian1_id))->orderBy('nama_jabatan', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($jabatan as $jabatan)
                                                                                    @if(old('jabatan1_id',$karyawan->jabatan1_id) == $jabatan->id)
                                                                                    <option value="{{ $jabatan->id }}" selected>{{ $jabatan->nama_jabatan }}</option>
                                                                                    @else
                                                                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_jabatan1">Jabatan 2</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen2_id" id="id_departemen2" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                <optgroup label='Daftar Departemen {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($departemen as $departemen)
                                                                                    @if(old('departemen2_id',$karyawan->dept2_id) == $departemen->id)
                                                                                    <option value="{{ $departemen->id }}" selected>{{ $departemen->nama_departemen }}</option>
                                                                                    @else
                                                                                    <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                                                                                    <!-- <option value="{{$departemen->id}}">{{$departemen->nama_departemen}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_departemen2">Departemen 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi2_id" id="id_divisi2" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>
                                                                                <optgroup label='Daftar Divisi {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $divisi = App\Models\Divisi::where('dept_id', old('departemen2_id', $karyawan->dept_id))->orderBy('nama_divisi', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($divisi as $divisi)
                                                                                    @if(old('divisi2_id',$karyawan->divisi2_id) == $divisi->id)
                                                                                    <option value="{{ $divisi->id }}" selected>{{ $divisi->nama_divisi }}</option>
                                                                                    @else
                                                                                    <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_divisi2">Divisi 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian2_id" id="id_bagian2" class="form-control">
                                                                                <option value=""> Pilih Bagian</option>
                                                                                <optgroup label='Daftar Bagian {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $bagian = App\Models\Bagian::where('divisi_id', old('divisi2_id', $karyawan->divisi2_id))->orderBy('nama_bagian', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($bagian as $bagian)
                                                                                    @if(old('bagian2_id',$karyawan->bagian2_id) == $bagian->id)
                                                                                    <option value="{{ $bagian->id }}" selected>{{ $bagian->nama_bagian }}</option>
                                                                                    @else
                                                                                    <option value="{{ $bagian->id }}">{{ $bagian->nama_bagian }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for="id_bagian2">Bagian 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan2_id" id="id_jabatan2" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>
                                                                                <optgroup label='Daftar Jabatan {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $jabatan = App\Models\Jabatan::where('bagian_id', old('bagian2_id', $karyawan->bagian2_id))->orderBy('nama_jabatan', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($jabatan as $jabatan)
                                                                                    @if(old('jabatan2_id',$karyawan->jabatan2_id) == $jabatan->id)
                                                                                    <option value="{{ $jabatan->id }}" selected>{{ $jabatan->nama_jabatan }}</option>
                                                                                    @else
                                                                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_jabatan2">Jabatan 3</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen3_id" id="id_departemen3" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                <optgroup label='Daftar Departemen {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($departemen as $departemen)
                                                                                    @if(old('departemen3_id',$karyawan->dept3_id) == $departemen->id)
                                                                                    <option value="{{ $departemen->id }}" selected>{{ $departemen->nama_departemen }}</option>
                                                                                    @else
                                                                                    <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                                                                                    <!-- <option value="{{$departemen->id}}">{{$departemen->nama_departemen}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_departemen3">Departemen 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi3_id" id="id_divisi3" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>
                                                                                <optgroup label='Daftar Divisi {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $divisi = App\Models\Divisi::where('dept_id', old('departemen3_id', $karyawan->dept_id))->orderBy('nama_divisi', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($divisi as $divisi)
                                                                                    @if(old('divisi3_id',$karyawan->divisi3_id) == $divisi->id)
                                                                                    <option value="{{ $divisi->id }}" selected>{{ $divisi->nama_divisi }}</option>
                                                                                    @else
                                                                                    <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_divisi3">Divisi 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian3_id" id="id_bagian3" class="form-control">
                                                                                <option value=""> Pilih Bagian</option>
                                                                                <optgroup label='Daftar Bagian {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $bagian = App\Models\Bagian::where('divisi_id', old('divisi3_id', $karyawan->divisi3_id))->orderBy('nama_bagian', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($bagian as $bagian)
                                                                                    @if(old('bagian3_id',$karyawan->bagian3_id) == $bagian->id)
                                                                                    <option value="{{ $bagian->id }}" selected>{{ $bagian->nama_bagian }}</option>
                                                                                    @else
                                                                                    <option value="{{ $bagian->id }}">{{ $bagian->nama_bagian }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for="id_bagian3">Bagian 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan3_id" id="id_jabatan3" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>
                                                                                <optgroup label='Daftar Jabatan {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $jabatan = App\Models\Jabatan::where('bagian_id', old('bagian3_id', $karyawan->bagian3_id))->orderBy('nama_jabatan', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($jabatan as $jabatan)
                                                                                    @if(old('jabatan3_id',$karyawan->jabatan3_id) == $jabatan->id)
                                                                                    <option value="{{ $jabatan->id }}" selected>{{ $jabatan->nama_jabatan }}</option>
                                                                                    @else
                                                                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_jabatan3">Jabatan 4</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-2 mt-2">
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="departemen4_id" id="id_departemen4" class="form-control">
                                                                                <option value=""> Pilih Departemen</option>
                                                                                <optgroup label='Daftar Departemen {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $departemen = App\Models\Departemen::where('holding', $kategori_jabatan)->orderBy('nama_departemen', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($departemen as $departemen)
                                                                                    @if(old('departemen4_id',$karyawan->dept4_id) == $departemen->id)
                                                                                    <option value="{{ $departemen->id }}" selected>{{ $departemen->nama_departemen }}</option>
                                                                                    @else
                                                                                    <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                                                                                    <!-- <option value="{{$departemen->id}}">{{$departemen->nama_departemen}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_departemen4">Departemen 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="divisi4_id" id="id_divisi4" class="form-control">
                                                                                <option value=""> Pilih Divisi</option>
                                                                                <optgroup label='Daftar Divisi {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $divisi = App\Models\Divisi::where('dept_id', old('departemen4_id', $karyawan->dept_id))->orderBy('nama_divisi', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($divisi as $divisi)
                                                                                    @if(old('divisi4_id',$karyawan->divisi4_id) == $divisi->id)
                                                                                    <option value="{{ $divisi->id }}" selected>{{ $divisi->nama_divisi }}</option>
                                                                                    @else
                                                                                    <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_divisi4">Divisi 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="bagian4_id" id="id_bagian4" class="form-control">
                                                                                <option value=""> Pilih Bagian</option>
                                                                                <optgroup label='Daftar bagian {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $bagian = App\Models\Bagian::where('divisi_id', old('divisi4_id', $karyawan->divisi4_id))->orderBy('nama_bagian', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($bagian as $bagian)
                                                                                    @if(old('bagian4_id') == $bagian->id)
                                                                                    <option value="{{ $bagian->id }}" selected>{{ $bagian->nama_bagian }}</option>
                                                                                    @else
                                                                                    <option value="{{ $bagian->id }}">{{ $bagian->nama_bagian }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for="id_bagian4">Bagian 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col mb-2">
                                                                        <div class="form-floating form-floating-outline">
                                                                            <select style="font-size: small;" name="jabatan4_id" id="id_jabatan4" class="form-control">
                                                                                <option value=""> Pilih Jabatan</option>
                                                                                <optgroup label='Daftar Jabatan {{$holding_jabatan}}'>
                                                                                    <?php
                                                                                    if (old('kategori_jabatan', $karyawan->kategori_jabatan) == '') {
                                                                                        $kategori_jabatan = $holding;
                                                                                    } else {
                                                                                        $kategori_jabatan = $karyawan->kategori_jabatan;
                                                                                    }
                                                                                    $jabatan = App\Models\Jabatan::where('bagian_id', old('bagian4_id', $karyawan->bagian4_id))->orderBy('nama_jabatan', 'ASC')->get();
                                                                                    ?>
                                                                                    @foreach($jabatan as $jabatan)
                                                                                    @if(old('jabatan4_id',$karyawan->jabatan4_id) == $jabatan->id)
                                                                                    <option value="{{ $jabatan->id }}" selected>{{ $jabatan->nama_jabatan }}</option>
                                                                                    @else
                                                                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                                                                    <!-- <option value="{{$divisi->id}}">{{$divisi->nama_divisi}}</option> -->
                                                                                    @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                            <label for=" id_jabatan4">Jabatan 5</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="nav_bank" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <?php $bank = array(
                                                    [
                                                        "kode_bank" => "BBRI",
                                                        "bank" => "BANK RAKYAT INDONESIA (BRI)"
                                                    ],
                                                    [
                                                        "kode_bank" => "BBCA",
                                                        "bank" => "BANK CENTRAL ASIA (BCA)"
                                                    ],
                                                    [
                                                        "kode_bank" => "BOCBC",
                                                        "bank" => "BANK OCBC"
                                                    ],
                                                    [
                                                        "kode_bank" => "BMANDIRI",
                                                        "bank" => "BANK MANDIRI"
                                                    ]
                                                );
                                                ?>
                                                <select style="font-size: small;" name="nama_bank" id="nama_bank" onchange="bankCheck(this);" class="form-control  @error('nama_bank') is-invalid @enderror">
                                                    <option value="">Pilih Bank</option>
                                                    @foreach ($bank as $bank)
                                                    @if(old('nama_bank', $karyawan->nama_bank) == $bank['kode_bank']) <option value="{{ $bank['kode_bank'] }}" selected>{{ $bank['bank'] }}</option>
                                                    @else
                                                    <option value="{{ $bank['kode_bank'] }}">{{ $bank['bank'] }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="nama_bank">Nama Bank</label>
                                            </div>
                                            @error('nama_bank')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="text" class="form-control  @error('nama_pemilik_rekening') is-invalid @enderror" id="nama_pemilik_rekening" name="nama_pemilik_rekening" value="{{old('nama_pemilik_rekening',$karyawan->nama_pemilik_rekening) }}" placeholder="Nama Pemilik Rekening" />
                                                <label for="nama_pemilik_rekening">Nama Pemilik Rekening</label>
                                            </div>
                                            @error('nama_pemilik_rekening')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" type="number" class="form-control  @error('nomor_rekening') is-invalid @enderror" id="nomor_rekening" name="nomor_rekening" value="{{old('nomor_rekening', $karyawan->nomor_rekening) }}" placeholder="Nomor Rekening" />
                                                <label for="nomor_rekening">Nomor Rekening</label>
                                            </div>
                                            @error('nomor_rekening')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_pajak" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('ptkp') is-invalid @enderror" id="ptkp" name="ptkp">
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="" ) selected @else @endif value="">Pilih PKTP</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="TK/0" ) selected @else @endif value="TK/0">TK/0</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="TK/1" ) selected @else @endif value="TK/1">TK/1</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="TK/2" ) selected @else @endif value="TK/2">TK/2</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="TK/3" ) selected @else @endif value="TK/3">TK/3</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/0" ) selected @else @endif value="K/0">K/0</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/1" ) selected @else @endif value="K/1">K/1</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/2" ) selected @else @endif value="K/2">K/2</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/I/0" ) selected @else @endif value="K/I/0">K/I/0</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/I/1" ) selected @else @endif value="K/I/1">K/I/1</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/I/2" ) selected @else @endif value="K/I/2">K/I/2</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/I/3" ) selected @else @endif value="K/I/3">K/I/3</option>
                                                    <option @if(old('ptkp',$karyawan->ptkp)=="K/3" ) selected @else @endif value="K/3">K/3</option>
                                                </select>
                                                <label for="ptkp">PTKP</label>
                                            </div>
                                            @error('ptkp')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="status_npwp">Punya NPWP</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="status_npwp_ya" name="status_npwp" class="form-check-input" value="on" @if(old('status_npwp', $karyawan->status_npwp)=='on') checked @else @endif>
                                                        <label class="form-check-label" for="status_npwp_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="status_npwp_tidak" name="status_npwp" class="form-check-input" value="off" @if(old('status_npwp', $karyawan->status_npwp)=='off') checked @else @endif>
                                                        <label class="form-check-label" for="status_npwp_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_npwp" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nama_pemilik_npwp') is-invalid @enderror" type="text" placeholder="Nama Pemilik NPWP" id="nama_pemilik_npwp" name="nama_pemilik_npwp" value="{{old('nama_pemilik_npwp',$karyawan->nama_pemilik_npwp)}}" />
                                                <label for="nama_pemilik_npwp">Nama Pemilik NPWP</label>
                                            </div>
                                            @error('nama_pemilik_npwp')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('npwp') is-invalid @enderror" type="number" id="npwp" name="npwp" value="{{old('npwp', $karyawan->npwp)}}" />
                                                <label for="npwp">NPWP</label>
                                            </div>
                                            @error('npwp')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_bpjs" role="tabpanel">
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_ketenagakerjaan_ya" name="bpjs_ketenagakerjaan" class="form-check-input" value="on" @if(old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan)=='on') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_ketenagakerjaan_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_ketenagakerjaan_tidak" name="bpjs_ketenagakerjaan" class="form-check-input" value="off" @if(old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan)=='off') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_ketenagakerjaan_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_bpjs_ketenagakerjaan" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nama_pemilik_bpjs_ketenagakerjaan') is-invalid @enderror" placeholder="Nama Pemilik BPJS Ketenagakerjaan" type="text" id="nama_pemilik_bpjs_ketenagakerjaan" name="nama_pemilik_bpjs_ketenagakerjaan" value="{{old('nama_pemilik_bpjs_ketenagakerjaan',$karyawan->nama_pemilik_bpjs_ketenagakerjaan)}}" autofocus />
                                                <label for="nama_pemilik_bpjs_ketenagakerjaan">Nama Pemilik BPJS Ketenagakerjaan</label>
                                            </div>
                                            @error('nama_pemilik_bpjs_ketenagakerjaan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('no_bpjs_ketenagakerjaan') is-invalid @enderror" type="number" placeholder="No. BPJS Ketenagakerjaan" id="no_bpjs_ketenagakerjaan" name="no_bpjs_ketenagakerjaan" value="{{old('no_bpjs_ketenagakerjaan', $karyawan->no_bpjs_ketenagakerjaan)}}" autofocus />
                                                <label for="no_bpjs_ketenagakerjaan">No. BPJS Ketenagakerjaan</label>
                                            </div>
                                            @error('no_bpjs_ketenagakerjaan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_pensiun_ya">BPJS Pensiun</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_pensiun_ya" name="bpjs_pensiun" class="form-check-input" value="on" @if(old('bpjs_pensiun', $karyawan->bpjs_pensiun)=='on') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_pensiun_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_pensiun_tidak" name="bpjs_pensiun" class="form-check-input" value="off" @if(old('bpjs_pensiun', $karyawan->bpjs_pensiun)=='off') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_pensiun_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-4">
                                        <div class="col mb-12">
                                            <label class="form-check-label" for="bpjs_kesehatan">BPJS Kesehatan</label>
                                            <div class="form-floating form-floating-outline">
                                                <div class="row gy-4" style="margin-left: 2%;">
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_kesehatan_ya" name="bpjs_kesehatan" class="form-check-input" value="on" @if(old('bpjs_kesehatan', $karyawan->bpjs_kesehatan)=='on') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_kesehatan_ya">Ya</label>
                                                    </div>
                                                    <div class="col-lg-2 form-check">
                                                        <input style="font-size: small;" type="radio" id="bpjs_kesehatan_tidak" name="bpjs_kesehatan" class="form-check-input" value="off" @if(old('bpjs_kesehatan', $karyawan->bpjs_kesehatan)=='off') checked @else @endif>
                                                        <label class="form-check-label" for="bpjs_kesehatan_tidak">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="row_bpjs_kesehatan" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('nama_pemilik_bpjs_kesehatan') is-invalid @enderror" type="text" id="nama_pemilik_bpjs_kesehatan" placeholder="Nama Pemilik BPJS Kesehatan" name="nama_pemilik_bpjs_kesehatan" value="{{old('nama_pemilik_bpjs_kesehatan',$karyawan->nama_pemilik_bpjs_kesehatan)}}" autofocus />
                                                <label for="nama_pemilik_bpjs_kesehatan">Nama Pemilik BPJS Kesehatan</label>
                                            </div>
                                            @error('nama_pemilik_bpjs_kesehatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input style="font-size: small;" class="form-control @error('no_bpjs_kesehatan') is-invalid @enderror" type="number" id="no_bpjs_kesehatan" name="no_bpjs_kesehatan" value="{{old('no_bpjs_kesehatan', $karyawan->no_bpjs_kesehatan)}}" autofocus />
                                                <label for="no_bpjs_kesehatan">No. BPJS Kesehatan</label>
                                            </div>
                                            @error('no_bpjs_kesehatan')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="row_kelas_bpjs" class="row mt-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <select style="font-size: small;" class="form-control @error('kelas_bpjs') is-invalid @enderror" id="kelas_bpjs" name="kelas_bpjs" value="{{old('kelas_bpjs', $karyawan->kelas_bpjs)}}">
                                                    <option @if(old('kelas_bpjs',$karyawan->kelas_bpjs)=='') selected @else @endif value="">Pilih Kelas BPJS</option>
                                                    <option @if(old('kelas_bpjs',$karyawan->kelas_bpjs)=='Kelas 1') selected @else @endif value="Kelas 1">Kelas 1</option>
                                                    <option @if(old('kelas_bpjs',$karyawan->kelas_bpjs)=='Kelas 2') selected @else @endif value="Kelas 2">Kelas 2</option>
                                                    <option @if(old('kelas_bpjs',$karyawan->kelas_bpjs)=='Kelas 3') selected @else @endif value="Kelas 3">Kelas 3</option>
                                                </select>
                                                <label for="kelas_bpjs">Kelas BPJS</label>
                                            </div>
                                            @error('kelas_bpjs')
                                            <p class="alert alert-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav_dokumen" role="tabpanel">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="{{asset('admin/assets/img/avatars/cv.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />

                                            <div class="button-wrapper">
                                                <label for="file_cv" class="btn btn-danger me-2 mb-3" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload File CV</span>
                                                    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                                    <input style="font-size: small;" type="hidden" name="file_cv_lama" value="{{ $karyawan->file_cv }}">
                                                    <input style="font-size: small;" type="file" name="file_cv" id="file_cv" class="account-file-input" hidden accept=".doc, .docx,.pdf" />
                                                </label>
                                                <button type="button" id="btn_modal_lihat" data-bs-toggle="modal" data-bs-target="#modal_cv" class="btn_modal_lihat btn btn-info me-2 mb-3">Lihat</button>

                                                <div class="text-muted small">Allowed PDF, DOC or DOCX. Max size of 5 MB</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                    <a href="@if(Auth::user()->is_admin=='hrd'){{url('/hrd/karyawan/'.$holding)}}@else{{url('/karyawan/'.$holding)}}@endif" type="button" class="btn btn-outline-secondary">Kembali</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body pt-2 mt-1">

                    </div>
                    <!-- /Account -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    function bankCheck(that) {
        if (that.value == "BBRI") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BRI?',
                showConfirmButton: true
            });
            bankdigit = 15;
            // document.getElementById("ifBRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifMANDIRI").style.display = "none";
        } else if (that.value == "BBCA") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank BCA?',
                showConfirmButton: true
            });
            bankdigit = 10;
            // document.getElementById("ifMANDIRI").style.display = "block";
            // document.getElementById("ifBCA").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        } else if (that.value == "BOCBC") {
            Swal.fire({
                customClass: {
                    container: 'my-swal'
                },
                target: document.getElementById('modal_tambah_karyawan'),
                position: 'top',
                icon: 'warning',
                title: 'Apakah Benar Bank OCBC?',
                showConfirmButton: true
            });
            bankdigit = 12;
            // document.getElementById("ifBCA").style.display = "block";
            // document.getElementById("ifMANDIRI").style.display = "none";
            // document.getElementById("ifBRI").style.display = "none";
        }
    }
    $(function() {
        var kategori = '{{$karyawan->kategori}}';
        if (kategori == 'Karyawan Harian') {
            $('#form_departemen').hide();
            $('#form_divisi').hide();
            $('#form_jabatan_more').hide();
            $('#form_jabatan').hide();
            $('#form_lama_kotrak').hide();
            $('#form_bagian').hide();
            $('#form_kontrak').hide();
            $('#form_tgl_kontrak_kerja').hide();
            // $('#form_level').hide();
            $('#form_tgl_mulai_kontrak').hide();
            $('#form_tgl_selesai_kontrak').hide();
            $('#form_site').hide();
            $('#form_lama_kontrak').hide();
        } else {

            $('#form_departemen').show();
            $('#form_divisi').show();
            $('#form_jabatan_more').show();
            $('#form_jabatan').show();
            $('#form_lama_kotrak').show();
            $('#form_bagian').show();
            $('#form_kontrak').show();
            $('#form_tgl_kontrak_kerja').show();
            // $('#form_level').show();
            $('#form_lama_kontrak').show();
            $('#form_tgl_mulai_kontrak').show();
            $('#form_tgl_selesai_kontrak').show();
            $('#form_site').show();
        }
        $('#kategori').on('change', function() {
            var id = $(this).val();
            if (id == 'Karyawan Harian') {
                $('#form_departemen').hide();
                $('#form_divisi').hide();
                $('#form_jabatan_more').hide();
                $('#form_jabatan').hide();
                $('#form_lama_kotrak').hide();
                $('#form_bagian').hide();
                $('#form_kontrak').hide();
                $('#form_tgl_kontrak_kerja').hide();
                // $('#form_level').hide();
                $('#form_tgl_mulai_kontrak').hide();
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').hide();
                $('#form_lama_kontrak').hide();
                $('#form_kuota_cuti').hide();
            } else if (id == 'Karyawan Bulanan') {
                let lama = $('#lama_kontrak_kerja').val();
                // console.log(lama);
                if (lama == 'tetap') {
                    $('#form_tgl_mulai_kontrak').show();
                    $('#form_kuota_cuti').show();
                } else if (lama == '') {
                    $('#form_tgl_mulai_kontrak').hide();
                    $('#form_tgl_selesai_kontrak').hide();
                    $('#form_kuota_cuti').hide();
                } else {
                    $('#form_tgl_mulai_kontrak').show();
                    $('#form_tgl_selesai_kontrak').show();
                    $('#form_kuota_cuti').show();
                }
                $('#form_departemen').show();
                $('#form_divisi').show();
                $('#form_jabatan_more').show();
                $('#form_jabatan').show();
                $('#form_lama_kotrak').show();
                $('#form_bagian').show();
                $('#form_kontrak').show();
                $('#form_tgl_kontrak_kerja').show();
                $('#form_level').show();
                $('#form_lama_kontrak').show();
                // $('#form_tgl_mulai_kontrak').hide();
                // $('#form_tgl_selesai_kontrak').hide();
                $('#form_site').show();
                // $('#form_kuota_cuti').hide();
            }
        });
        $('#lama_kontrak_kerja').on('change', function() {
            let iya = $(this).val();
            if (iya == 'tetap') {
                $('#form_tgl_selesai_kontrak').hide();
                $('#form_tgl_mulai_kontrak').show();
                $('#form_kuota_cuti').show();
            } else {
                $('#form_tgl_selesai_kontrak').show();
                $('#form_tgl_mulai_kontrak').show();
                $('#form_kuota_cuti').show();
            }
        })

    });
    $(function() {

        $('#atasan').on('change', function() {
            let id = $('#id_jabatan').val();
            let divisi = $('#id_divisi').val();
            let id_karyawan = $('#id_karyawan').val();
            let holding = '{{$holding}}';
            let url = "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/atasan2/get_jabatan')}}@else{{url('karyawan/atasan2/get_jabatan')}}@endif" + "/" + holding;
            // console.log(divisi);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: true,
                data: {
                    id: id,
                    id_karyawan: id_karyawan,
                    holding: holding,
                    id_divisi: divisi
                },
                success: function(response) {
                    // console.log(response);
                    $('#atasan2').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        });

    })
    $(function() {
        $('#nik').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("nik").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NIK harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#npwp').keyup(function(e) {
            if ($(this).val().length >= 16) {
                $(this).val($(this).val().substr(0, 16));
                document.getElementById("npwp").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor NPWP harus ' + 16 + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });
        $('#nomor_rekening').keyup(function(e) {
            if ($(this).val().length >= bankdigit) {
                $(this).val($(this).val().substr(0, bankdigit));
                document.getElementById("nomor_rekening").focus();
                Swal.fire({
                    customClass: {
                        container: 'my-swal'
                    },
                    target: document.getElementById('modal_tambah_karyawan'),
                    position: 'top',
                    icon: 'warning',
                    title: 'Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // if (length !== bankdigit) {
                //     document.getElementById('nomor_rekening').value;
                //     alert('Nomor Rekening harus ' + bankdigit + ' karakter. Mohon cek kembali!');
                //     document.getElementById('nomor_rekening').focus();
            }
        });

        $('#foto_karyawan').change(function() {

            let reader = new FileReader();
            // console.log(reader);
            reader.onload = (e) => {

                $('#template_foto_karyawan').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });
        $('#id_provinsi').on('change', function() {
            let id_provinsi = $(this).val();
            let url = "@if(Auth::user()->is_admin=='hrd'){{url('/hrd/karyawan/get_kabupaten')}}@else{{url('/karyawan/get_kabupaten')}}@endif" + "/" + id_provinsi;
            // console.log(id_provinsi);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_provinsi: id_provinsi
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kabupaten').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kabupaten').on('change', function() {
            let id_kabupaten = $(this).val();
            let url = "@if(Auth::user()->is_admin=='hrd'){{url('/hrd/karyawan/get_kecamatan')}}@else{{url('/karyawan/get_kecamatan')}}@endif" + "/" + id_kabupaten;
            // console.log(id_kabupaten);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kabupaten: id_kabupaten
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_kecamatan').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
        $('#id_kecamatan').on('change', function() {
            let id_kecamatan = $(this).val();
            let url = "@if(Auth::user()->is_admin=='hrd'){{url('/hrd/karyawan/get_desa')}}@else{{url('/karyawan/get_desa')}}@endif" + "/" + id_kecamatan;
            // console.log(id_kecamatan);
            // console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                // data: {
                //     id_kecamatan: id_kecamatan
                // },
                success: function(response) {
                    // console.log(response);
                    $('#id_desa').html(response);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        })
    });
</script>
<script>
    $('#row_bpjs_ketenagakerjaan').hide();
    $('#row_bpjs_kesehatan').hide();
    $('#row_kelas_bpjs').show();
    var status_nomor = "{{old('status_nomor',$karyawan->status_nomor)}}";
    var status_bpjs_ketenagakerjaan = "{{old('bpjs_ketenagakerjaan',$karyawan->bpjs_ketenagakerjaan)}}";
    var status_bpjs_kesehatan = "{{old('bpjs_kesehatan',$karyawan->bpjs_kesehatan)}}";
    var pilih_domisili_alamat = "{{old('pilihan_alamat_domisili',$karyawan->status_alamat)}}";
    var status_npwp = "{{old('status_npwp',$karyawan->status_npwp)}}";
    // console.log(status_bpjs_ketenagakerjaan);
    if (status_nomor == 'ya') {
        $('#content_nomor_wa').hide();
    } else if (status_nomor == 'tidak') {
        $('#content_nomor_wa').show();
    } else {
        $('#content_nomor_wa').hide();
    }
    if (pilih_domisili_alamat == 'ya') {
        $('#content_alamat_domisili').hide();
    } else if (pilih_domisili_alamat == 'tidak') {
        $('#content_alamat_domisili').show();
    } else {
        $('#content_alamat_domisili').hide();
    }
    if (status_bpjs_ketenagakerjaan == 'on') {
        $('#row_bpjs_ketenagakerjaan').show();
    } else if (status_bpjs_ketenagakerjaan == 'off') {
        $('#row_bpjs_ketenagakerjaan').hide();
    } else {
        $('#row_bpjs_ketenagakerjaan').hide();

    }
    if (status_bpjs_kesehatan == 'on') {
        $('#row_bpjs_kesehatan').show();
        $('#row_kelas_bpjs').show();
    } else if (status_bpjs_kesehatan == 'off') {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();
    } else {
        $('#row_kelas_bpjs').hide();
        $('#row_bpjs_kesehatan').hide();

    }

    if (status_npwp == 'on') {
        $('#row_npwp').show();
    } else if (status_npwp == 'off') {
        $('#row_npwp').hide();
    } else {
        $('#row_npwp').hide();
    }
    $(document).on("click", "#btn_status_no_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').hide();

        }
    });
    $(document).on("click", "#btn_status_no_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_nomor_wa').show();
        }
    });
    $(document).on("click", "#status_npwp_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_npwp').show();
        } else {
            $('#row_npwp').hide();

        }
    });
    $(document).on("click", "#btnradio_ya", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').hide();

        }
    });
    $(document).on("click", "#btnradio_tidak", function() {
        var isChecked = $(this).is(':checked')
        if (isChecked) {
            $('#content_alamat_domisili').show();
        }
    });
    $(document).on("click", "#status_npwp_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_npwp').hide();
        } else {
            $('#row_npwp').show();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_ketenagakerjaan').show();
        } else {
            $('#row_bpjs_ketenagakerjaan').hide();

        }
    });
    $(document).on("click", "#bpjs_ketenagakerjaan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_ketenagakerjaan').hide();
        } else {
            $('#row_bpjs_ketenagakerjaan').show();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_ya", function() {
        var id = $(this).val();
        if (id == 'on') {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();
        } else {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();

        }
    });
    $(document).on("click", "#bpjs_kesehatan_tidak", function() {
        var id = $(this).val();
        if (id == 'off') {
            $('#row_bpjs_kesehatan').hide();
            $('#row_kelas_bpjs').hide();
        } else {
            $('#row_bpjs_kesehatan').show();
            $('#row_kelas_bpjs').show();

        }
    });
    var file_cv = '{{$karyawan->file_cv}}';
    if (file_cv == '') {
        // console.log('ok');
        $('#btn_modal_lihat').hide();
    } else {
        // console.log('ok1');
        $('#btn_modal_lihat').show();
    }
    $('#file_cv').change(function() {


        let reader = new FileReader();
        // console.log(reader);
        reader.onload = (e) => {
            $('#lihat_file_cv').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });
    $('#row_kategori_jabatan').hide();
    if ($('#site_job').val() == 'ALL SITES (SP, SPS, SIP)') {
        $('#row_kategori_jabatan').show();
    }
    $(document).on("change", "#site_job", function() {
        var id = $(this).val();
        if (id == 'ALL SITES (SP, SPS, SIP)') {
            $('#row_kategori_jabatan').show();
            var holding = $('#kategori_jabatan').val();
            // console.log(holding);
        } else if (id == 'ALL SITES (SP)') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'ALL SITES (SPS)') {
            $('#row_kategori_jabatan').hide();
            $('#kategori_jabatan').val('sps');
            var holding = 'sps';
            $('#row_kategori_jabatan').hide();
        } else if (id == 'ALL SITES (SIP)') {
            $('#kategori_jabatan').val('sip');
            var holding = 'sip';
        } else if (id == 'CV. SUMBER PANGAN - KEDIRI') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'CV. SUMBER PANGAN - TUBAN') {
            $('#kategori_jabatan').val('sp');
            $('#row_kategori_jabatan').hide();
            var holding = 'sp';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - KEDIRI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - NGAWI') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else if (id == 'PT. SURYA PANGAN SEMESTA - SUBANG') {
            $('#kategori_jabatan').val('sps');
            $('#row_kategori_jabatan').hide();
            var holding = 'sps';
        } else {
            $('#row_kategori_jabatan').hide();
            var holding = '{{$holding}}';
        }
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_departemen')}}@else{{url('karyawan/get_departemen')}}@endif",
            data: {
                holding: holding,
            },
            cache: false,

            success: function(msg) {
                // console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_departemen').html(msg);
                $('#id_departemen1').html(msg);
                $('#id_departemen2').html(msg);
                $('#id_departemen3').html(msg);
                $('#id_departemen4').html(msg);
                $('#id_divisi').html('<option value=""></option>');
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
                $('#id_divisi1').html('<option value=""></option>');
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
                $('#id_divisi2').html('<option value=""></option>');
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');
                $('#id_divisi3').html('<option value=""></option>');
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');
                $('#id_divisi4').html('<option value=""></option>');
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
        // console.log($(this).val());
    });
    $(document).on("click", "#kategori_jabatan_sp", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sp') {
            $('#kategori_jabatan').val(holding);
            // console.log(id_departemen);
            $.ajax({
                type: 'GET',
                url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_departemen')}}@else{{url('karyawan/get_departemen')}}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sps", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sps') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_departemen')}}@else{{url('karyawan/get_departemen')}}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $(document).on("click", "#kategori_jabatan_sip", function() {
        var holding = $(this).val();
        // console.log(holding);
        if (holding == 'sip') {
            $('#kategori_jabatan').val(holding);
            $.ajax({
                type: 'GET',
                url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_departemen')}}@else{{url('karyawan/get_departemen')}}@endif",
                data: {
                    holding: holding,
                },
                cache: false,

                success: function(msg) {
                    // console.log(msg);
                    // $('#id_divisi').html(msg);
                    $('#id_departemen').html(msg);
                    $('#id_departemen1').html(msg);
                    $('#id_departemen2').html(msg);
                    $('#id_departemen3').html(msg);
                    $('#id_departemen4').html(msg);
                },
                error: function(data) {
                    console.log('error:', data)
                },

            })
        }
    });
    $('#id_departemen').on('change', function() {
        let id_departemen = $('#id_departemen').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_divisi')}}@else{{url('karyawan/get_divisi')}}@endif",
            data: {
                // holding: holding,
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {
                // console.log(msg);
                // $('#id_divisi').html(msg);
                $('#id_divisi').html(msg);
                $('#id_bagian').html('<option value=""></option>');
                $('#id_jabatan').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen1').on('change', function() {
        let id_departemen = $('#id_departemen1').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_divisi')}}@else{{url('karyawan/get_divisi')}}@endif",
            data: {
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {

                $('#id_divisi1').html(msg);
                $('#id_bagian1').html('<option value=""></option>');
                $('#id_jabatan1').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen2').on('change', function() {
        let id_departemen = $('#id_departemen2').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_divisi')}}@else{{url('karyawan/get_divisi')}}@endif",
            data: {
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {

                $('#id_divisi2').html(msg);
                $('#id_bagian2').html('<option value=""></option>');
                $('#id_jabatan2').html('<option value=""></option>');

            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen3').on('change', function() {
        let id_departemen = $('#id_departemen3').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_divisi')}}@else{{url('karyawan/get_divisi')}}@endif",
            data: {
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {
                $('#id_divisi3').html(msg);
                $('#id_bagian3').html('<option value=""></option>');
                $('#id_jabatan3').html('<option value=""></option>');

            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_departemen4').on('change', function() {
        let id_departemen = $('#id_departemen4').val();
        // console.log(holding);
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_divisi')}}@else{{url('karyawan/get_divisi')}}@endif",
            data: {
                id_departemen: id_departemen
            },
            cache: false,

            success: function(msg) {
                $('#id_divisi4').html(msg);
                $('#id_bagian4').html('<option value=""></option>');
                $('#id_jabatan4').html('<option value=""></option>');
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi').on('change', function() {
        let id_divisi = $('#id_divisi').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_bagian')}}@else{{url('karyawan/get_bagian')}}@endif",
            data: {
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi1').on('change', function() {
        let id_divisi = $('#id_divisi1').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_bagian')}}@else{{url('karyawan/get_bagian')}}@endif",
            data: {
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian1').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi2').on('change', function() {
        let id_divisi = $('#id_divisi2').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_bagian')}}@else{{url('karyawan/get_bagian')}}@endif",
            data: {
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi3').on('change', function() {
        let id_divisi = $('#id_divisi3').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_bagian')}}@else{{url('karyawan/get_bagian')}}@endif",
            data: {
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian3').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_divisi4').on('change', function() {
        let id_divisi = $('#id_divisi4').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_bagian')}}@else{{url('karyawan/get_bagian')}}@endif",
            data: {
                id_divisi: id_divisi
            },
            cache: false,

            success: function(msg) {
                $('#id_bagian4').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian').on('change', function() {
        let id_bagian = $('#id_bagian').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_jabatan')}}@else{{url('karyawan/get_jabatan')}}@endif",
            data: {
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian1').on('change', function() {
        let id_bagian = $('#id_bagian1').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_jabatan')}}@else{{url('karyawan/get_jabatan')}}@endif",
            data: {
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan1').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian2').on('change', function() {
        let id_bagian = $('#id_bagian2').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_jabatan')}}@else{{url('karyawan/get_jabatan')}}@endif",
            data: {
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan2').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian3').on('change', function() {
        let id_bagian = $('#id_bagian3').val();
        $.ajax({
            type: 'GET',
            url: "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_jabatan')}}@else{{url('karyawan/get_jabatan')}}@endif",
            data: {
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan3').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_bagian4').on('change', function() {
        let id_bagian = $('#id_bagian4').val();
        let url = "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/get_jabatan')}}@else{{url('karyawan/get_jabatan')}}@endif";
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                id_bagian: id_bagian
            },
            cache: false,

            success: function(msg) {
                $('#id_jabatan4').html(msg);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    })
    $('#id_jabatan').on('change', function() {
        let id = $(this).val();
        let id_karyawan = $('#id_karyawan').val();
        let divisi = $('#id_divisi').val();
        let holding = '{{$holding}}';
        let url = "@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/atasan/get_jabatan')}}@else{{url('karyawan/atasan/get_jabatan')}}@endif" + "/" + holding;
        // console.log(divisi);
        // console.log(holding);
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: true,
            data: {
                id: id,
                id_karyawan: id_karyawan,
                holding: holding,
                id_divisi: divisi
            },
            success: function(response) {
                // console.log(response);
                $('#atasan').html(response);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
<script>
    $(document).on("click", "#btndetail_karyawan", function() {
        let id = $(this).data('id');
        let holding = $(this).data("holding");
        // console.log(id);
        let url = "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/detail/')}}@else{{ url('/karyawan/detail/')}}@endif" + '/' + id + '/' + holding;
        $.ajax({
            url: url,
            method: 'GET',
            contentType: false,
            cache: false,
            processData: false,
            // data: {
            //     id_kecamatan: id_kecamatan
            // },
            success: function(response) {
                // console.log(response);
                window.location.assign(url);
            },
            error: function(data) {
                console.log('error:', data)
            },

        })
    });
</script>
@endsection