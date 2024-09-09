@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary col-lg-12">
        <div class="mt-4 p-4">
            <form method="post" action="{{ url('/karyawan/tambah-karyawan-proses/'.$holding) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col">
                        <label for="name">Nama Karyawan</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="name">Fullname</label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" autofocus value="{{ old('fullname') }}">
                        @error('fullname')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="nik">NIK Karyawan</label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" autofocus value="{{ old('nik') }}">
                        @error('nik')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="npwp">NPWP</label>
                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" autofocus value="{{ old('npwp') }}">
                        @error('npwp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="telepon">Nomor Telefon</label>
                        <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon') }}">
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
                        <label for="foto_karyawan" class="form-label">Foto Karyawan</label>
                        <input class="form-control @error('foto_karyawan') is-invalid @enderror" type="file" id="foto_karyawan" name="foto_karyawan">
                        @error('foto_karyawan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="name">Motto</label>
                        <input type="text" class="form-control @error('motto') is-invalid @enderror" id="motto" name="motto" autofocus value="{{ old('motto') }}">
                        @error('motto')
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
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}">
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="password">Password</label>
                        <input type="password" au class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                        @error('tempat_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                        @error('tgl_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <?php $get_gender = array(
                            [
                                "gender" => "Laki-Laki"
                            ],
                            [
                                "gender" => "Perempuan"
                            ]
                        );
                        ?>
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="selectpicker form-control  @error('gender') is-invalid @enderror" data-live-search="true">
                            <option value="">Pilih Gender</option>
                            @foreach ($get_gender as $g)
                            @if(old('gender') == $g["gender"])
                            <option value="{{ $g['gender'] }}" selected>{{ $g["gender"] }}</option>
                            @else
                            <option value="{{ $g['gender'] }}">{{ $g["gender"] }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('gender')
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
                            ]
                        );
                        ?>
                        <label for="status_nikah">Status Pernikahan</label>
                        <select name="status_nikah" id="status_nikah" class="form-control selectpicker @error('status_nikah') is-invalid @enderror" data-live-search="true">
                            <option value="">Pilih Status</option>
                            @foreach ($sNikah as $s)
                            @if(old('status_nikah') == $s["status"])
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
                            ]
                        );
                        ?>
                        <label for="nama_bank">Bank</label>
                        <select name="nama_bank" id="nama_bank" onchange="bankCheck(this);" class="selectpicker form-control  @error('nama_bank') is-invalid @enderror" data-live-search="true">
                            <option value="">Pilih Bank</option>
                            @foreach ($bank as $bank)
                            @if(old('nama_bank') == $bank["bank"])
                            <option value="{{ $bank['kode_bank'] }}" selected>{{ $bank["bank"] }}</option>
                            @else
                            <option value="{{ $bank['kode_bank'] }}">{{ $bank["bank"] }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('nama_bank')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">

                        <label for="nomor_rekening">Rekening</label>
                        <input type="number" name="nomor_rekening" id="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror">
                        @error('nomor_rekening')
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
                        <input type="date" class="form-control @error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" value="{{ old('tgl_join') }}">
                        @error('tgl_join')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="departemen_id">Departemen</label>
                        <select name="departemen_id" id="id_departemen" class="form-control selectpicker @error('departemen_id') is-invalid @enderror " data-live-search="true">
                            <option value="">Pilih Departemen</option>
                            @foreach ($data_departemen as $dj)
                            @if(old('departemen_id') == $dj->id)
                            <option value="{{ $dj->id }}" selected>{{ $dj->nama_departemen }}</option>
                            @else
                            <option value="{{ $dj->id }}">{{ $dj->nama_departemen }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('departemen_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <table class="table table-bordered" id="dynamicTable">
                        <tr>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                        </tr>
                        <tr>
                            <td><select name="addmore[0][divisi_id]" id="id_divisi" class="form-control" data-live-search="true">
                                </select>
                            </td>
                            <td>
                                <select name="addmore[0][jabatan_id]" id="id_jabatan" class="form-control" data-live-search="true">
                                </select>
                            </td>
                            <td><button type="button" name="add" id="add" class="btn btn-sm btn-success">Add</button></td>
                        </tr>
                    </table>
                </div>
                <!-- <div class="form-row">
                    <div class="col">
                        <label for="divisi_id">Divisi</label>
                        <select name="divisi_id" id="id_divisi" class="form-control" data-live-search="true">
                        </select>
                    </div>
                    <div class="col">
                        <label for="jabatan_id">Jabatan</label>
                        <select name="jabatan_id" id="id_jabatan" class="form-control" data-live-search="true">
                        </select>
                        @error('jabatan_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div> -->
                <br>
                <div class="form-row">

                    <div class="col">
                        <?php $is_admin = array(
                            [
                                "is_admin" => "admin"
                            ],
                            [
                                "is_admin" => "user"
                            ]
                        );
                        ?>
                        <label for="is_admin">Level User</label>
                        <select class="selectpicker form-control @error('is_admin') is-invalid @enderror" name="is_admin" id="is_admin" data-live-search="true">
                            <option value="">Pilih Level</option>
                            @foreach ($is_admin as $a)
                            @if(old('is_admin') == $a["is_admin"])
                            <option value="{{ $a["is_admin"] }}" selected>{{ $a["is_admin"] }}</option>
                            @else
                            <option value="{{ $a["is_admin"] }}">{{ $a["is_admin"] }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('is_admin')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="kuota_cuti">Kuota Cuti</label>
                        <input type="number" class="form-control @error('kuota_cuti') is-invalid @enderror" id="kuota_cuti" name="kuota_cuti" value="{{ old('kuota_cuti') }}">
                        @error('kuota_cuti')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="kontrak_kerja">Kontrak Kerja</label>
                        <input type="text" readonly class="form-control" value="@if($holding =='sp')CV. SUMBER PANGAN @elseif($holding =='sps') PT. SURYA PANGAN SEMESTA @elseif($holding =='sip') CV. SURYA INTI PANGAN  @endif">
                        <input type="hidden" class="form-control" id="kontrak_kerja" name="kontrak_kerja" value="{{$holding}}">

                    </div>
                    <div class="col">
                        <label for="penempatan_kerja">Penempatan Kerja</label>
                        <select class="form-control @error('penempatan_kerja') is-invalid @enderror" id="penempatan_kerja" name="penempatan_kerja">
                            <option selected disabled value=""> Pilih Lokasi Penempatan</option>
                            @foreach ($data_lokasi as $a)
                            @if(old('penempatan_kerja') == $a["lokasi_kantor"])
                            <option value="{{ $a["lokasi_kantor"] }}" selected>{{ $a["lokasi_kantor"] }}</option>
                            @else
                            <option value="{{ $a["lokasi_kantor"] }}">{{ $a["lokasi_kantor"] }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('penempatan_kerja')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="cuti_dadakan">Cuti Dadakan</label>
                        <input type="number" class="form-control @error('cuti_dadakan') is-invalid @enderror" id="cuti_dadakan" name="cuti_dadakan" value="{{ old('cuti_dadakan') }}">
                        @error('cuti_dadakan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="cuti_bersama">Cuti Bersama</label>
                        <input type="number" class="form-control @error('cuti_bersama') is-invalid @enderror" id="cuti_bersama" name="cuti_bersama" value="{{ old('cuti_bersama') }}">
                        @error('cuti_bersama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="cuti_menikah">Cuti Menikah</label>
                        <input type="number" class="form-control @error('cuti_menikah') is-invalid @enderror" id="cuti_menikah" name="cuti_menikah" value="{{ old('cuti_menikah') }}">
                        @error('cuti_menikah')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="cuti_diluar_tanggungan">Cuti Diluar Tanggungan</label>
                        <input type="number" class="form-control @error('cuti_diluar_tanggungan') is-invalid @enderror" id="cuti_diluar_tanggungan" name="cuti_diluar_tanggungan" value="{{ old('cuti_diluar_tanggungan') }}">
                        @error('cuti_diluar_tanggungan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="cuti_khusus">Cuti Khusus</label>
                        <input type="number" class="form-control @error('cuti_khusus') is-invalid @enderror" id="cuti_khusus" name="cuti_khusus" value="{{ old('cuti_khusus') }}">
                        @error('cuti_khusus')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="cuti_melahirkan">Cuti Melahirkan</label>
                        <input type="number" class="form-control @error('cuti_melahirkan') is-invalid @enderror" id="cuti_melahirkan" name="cuti_melahirkan" value="{{ old('cuti_melahirkan') }}">
                        @error('cuti_melahirkan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="izin_telat">Izin Telat</label>
                        <input type="number" class="form-control @error('izin_telat') is-invalid @enderror" id="izin_telat" name="izin_telat" value="{{ old('izin_telat') }}">
                        @error('izin_telat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="izin_pulang_cepat">Izin Pulang Cepat</label>
                        <input type="number" class="form-control @error('izin_pulang_cepat') is-invalid @enderror" id="izin_pulang_cepat" name="izin_pulang_cepat" value="{{ old('izin_pulang_cepat') }}">
                        @error('izin_pulang_cepat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <label for="alamat">Alamat</label>
                <div class="form-row">
                    <div class="col">
                        <label for="provinsi">Provinsi</label>
                        <select class="form-control" id="id_provinsi" name="provinsi">
                            <option value=""> Pilih Provinsi </option>
                            @foreach($data_provinsi as $data)
                            <option value="{{$data->code}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="kabupaten">Kabupaten / Kota</label>
                        <select class="form-control" id="id_kabupaten" name="kabupaten">
                            <option value=""> Pilih Kabupaten / Kota</option>
                        </select>
                        @error('kabupaten')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="kecamatan">kecamatan</label>
                        <select class="form-control @error('kecamatan') is-invalid @enderror" id="id_kecamatan" name="kecamatan">
                            <option value=""> Pilih kecamatan</option>
                        </select>
                        @error('kecamatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="desa">Desa</label>
                        <select class="form-control @error('desa') is-invalid @enderror" id="id_desa" name="desa">
                            <option value=""> Pilih Desa</option>
                        </select>
                        @error('desa')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label for="rt">RT</label>
                        <input type="number" class="form-control @error('rt') is-invalid @enderror" id="rt" name="rt" value="{{ old('rt') }}">
                        @error('rt')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="rw">RW</label>
                        <input type="number" class="form-control @error('rw') is-invalid @enderror" id="rw" name="rw" value="{{ old('rw') }}">
                        @error('rw')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="alamat">Jalan/Dusun</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat') }}">
                        @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </form>
            <br>
        </div>
    </div>
</div>
<br>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
    function bankCheck(that) {
        if (that.value == "BBRI") {
            Swal.fire({
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
        $('#nomor_rekening').keyup(function(e) {
            if ($(this).val().length >= bankdigit) {
                $(this).val($(this).val().substr(0, bankdigit));
                document.getElementById("nomor_rekening").focus();
                Swal.fire({
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
        $('#id_provinsi').on('change', function() {
            let id_provinsi = $(this).val();
            let url = "{{url('/karyawan/get_kabupaten')}}" + "/" + id_provinsi;
            console.log(id_provinsi);
            console.log(url);
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
            let url = "{{url('/karyawan/get_kecamatan')}}" + "/" + id_kabupaten;
            console.log(id_kabupaten);
            console.log(url);
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
            let url = "{{url('/karyawan/get_desa')}}" + "/" + id_kecamatan;
            console.log(id_kecamatan);
            console.log(url);
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
@endsection