@extends('layouts.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-outline card-primary">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if(auth()->user()->foto_karyawan == null)
                                <img class="profile-user-img img-fluid img-circle" src="{{ url('assets/img/foto_default.jpg') }}" alt="User profile picture">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ url('storage/'.auth()->user()->foto_karyawan) }}" alt="User profile picture">
                            @endif
                        </div>

                        <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>

                        <p class="text-muted text-center">{{ auth()->user()->Jabatan->nama_jabatan }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ auth()->user()->email }}</a>
                            </li>
                            <li class="list-group-item">
                            <b>Username</b> <a class="float-right">{{ auth()->user()->username }}</a>
                            </li>
                            <li class="list-group-item">
                            <b>Telepon</b> <a class="float-right">{{ auth()->user()->telepon }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-outline card-primary">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form method="post" action="{{ url('/my-profile/update/'.auth()->user()->id) }}" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="name">Nama Karyawan</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name', auth()->user()->name) }}">
                                            @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="foto_karyawan" class="form-label">Foto Karyawan</label>
                                            <input class="form-control @error('foto_karyawan') is-invalid @enderror" type="file" id="foto_karyawan" name="foto_karyawan">
                                            @error('foto_karyawan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="foto_karyawan_lama" value="{{ auth()->user()->foto_karyawan }}">
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                                            @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="telepon">Nomor Telfon</label>
                                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}">
                                            @error('telepon')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', auth()->user()->username) }}">
                                            @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="password" value="{{ auth()->user()->password }}">
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="tgl_lahir">Tanggal Lahir</label>
                                            <input type="datetime" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', auth()->user()->tgl_lahir) }}">
                                            @error('tgl_lahir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <?php $gender = array(
                                            [
                                                "gender" => "Laki-Laki"
                                            ],
                                            [
                                                "gender" => "Perempuan"
                                            ]);
                                            ?>
                                            <label for="gender">Gender</label>
                                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror selectpicker" data-live-search="true">
                                                @foreach ($gender as $g)
                                                    @if(old('gender', auth()->user()->gender) == $g["gender"])
                                                    <option value="{{ $g["gender"] }}" selected>{{ $g["gender"] }}</option>
                                                    @else
                                                    <option value="{{ $g["gender"] }}">{{ $g["gender"] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('gender')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="tgl_join">Tanggal Masuk Perusahaan</label>
                                            <input type="datetime" class="form-control @error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" value="{{ old('tgl_join', auth()->user()->tgl_join) }}">
                                            @error('tgl_join')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <?php $sNikah = array(
                                            [
                                                "status" => "Lajang"
                                            ],
                                            [
                                                "status" => "Menikah"
                                            ]);
                                            ?>
                                            <label for="status_nikah">Status Pernikahan</label>
                                            <select name="status_nikah" id="status_nikah" class="form-control @error('status_nikah') is-invalid @enderror selectpicker" data-live-search="true">
                                                @foreach ($sNikah as $s)
                                                @if(old('status_nikah', auth()->user()->status_nikah) == $s["status"])
                                                <option value="{{ $s["status"] }}" selected>{{ $s["status"] }}</option>
                                                @else
                                                <option value="{{ $s["status"] }}">{{ $s["status"] }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            @error('status_nikah')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="alamat">Alamat</label>
                                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', auth()->user()->alamat) }}">
                                            @error('alamat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="is_admin">Level User</label>
                                            <input type="text" id="is_admin" value="{{ auth()->user()->is_admin }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="col">
                                            <label for="cuti_dadakan">Sisa Cuti Dadakan</label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->cuti_dadakan }}" disabled>
                                        </div>
                                        <div class="col">
                                            <label for="cuti_bersama">Sisa Cuti Bersama</label>
                                            <input type="text" disabled value="{{ auth()->user()->cuti_bersama }}" class="form-control">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <label for="cuti_menikah">Sisa Cuti Menikah</label>
                                            <input type="text" disabled class="form-control" value="{{ auth()->user()->cuti_menikah }}">
                                        </div>
                                        <div class="col">
                                            <label for="cuti_diluar_tanggungan">Sisa Cuti Diluar Tanggungan</label>
                                            <input type="text" class="form-control" disabled value="{{ auth()->user()->cuti_diluar_tanggungan }}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <label for="cuti_khusus">Sisa Cuti Khusus</label>
                                            <input type="text" disabled class="form-control" value="{{ auth()->user()->cuti_khusus }}">
                                        </div>
                                        <div class="col">
                                            <label for="cuti_melahirkan">Sisa Cuti Melahirkan</label>
                                            <input type="text" class="form-control" disabled value="{{ auth()->user()->cuti_melahirkan }}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <label for="izin_telat">Sisa Izin Telat</label>
                                            <input type="text" disabled class="form-control" value="{{ auth()->user()->izin_telat }}">
                                        </div>
                                        <div class="col">
                                            <label for="izin_pulang_cepat">Sisa Izin Pulang Cepat</label>
                                            <input type="text" class="form-control" disabled value="{{ auth()->user()->izin_pulang_cepat }}">
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                  </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection
