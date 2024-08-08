@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary col-lg-12">
        <div class="mt-4 p-4">
            <form method="post" action="{{ url('/reset-cuti/'.$data_cuti->id.'/'.$holding) }}">
                @method('put')
                @csrf
                <div class="form-row">
                    <div class="col">
                        <label for="cuti_dadakan">Cuti Dadakan</label>
                        <input type="number" class="form-control @error('cuti_dadakan') is-invalid @enderror" id="cuti_dadakan" name="cuti_dadakan" value="{{ old('cuti_dadakan', $data_cuti->cuti_dadakan) }}">
                        @error('cuti_dadakan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="cuti_bersama">Cuti Bersama</label>
                        <input type="number" class="form-control @error('cuti_bersama') is-invalid @enderror" id="cuti_bersama" name="cuti_bersama" value="{{ old('cuti_bersama', $data_cuti->cuti_bersama) }}">
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
                        <input type="number" class="form-control @error('cuti_menikah') is-invalid @enderror" id="cuti_menikah" name="cuti_menikah" value="{{ old('cuti_menikah', $data_cuti->cuti_menikah) }}">
                        @error('cuti_menikah')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="cuti_diluar_tanggungan">Cuti Diluar Tanggungan</label>
                        <input type="number" class="form-control @error('cuti_diluar_tanggungan') is-invalid @enderror" id="cuti_diluar_tanggungan" name="cuti_diluar_tanggungan" value="{{ old('cuti_diluar_tanggungan', $data_cuti->cuti_diluar_tanggungan) }}">
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
                        <input type="number" class="form-control @error('cuti_khusus') is-invalid @enderror" id="cuti_khusus" name="cuti_khusus" value="{{ old('cuti_khusus', $data_cuti->cuti_khusus) }}">
                        @error('cuti_khusus')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="cuti_melahirkan">Cuti Melahirkan</label>
                        <input type="number" class="form-control @error('cuti_melahirkan') is-invalid @enderror" id="cuti_melahirkan" name="cuti_melahirkan" value="{{ old('cuti_melahirkan', $data_cuti->cuti_melahirkan) }}">
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
                        <input type="number" class="form-control @error('izin_telat') is-invalid @enderror" id="izin_telat" name="izin_telat" value="{{ old('izin_telat', $data_cuti->izin_telat) }}">
                        @error('izin_telat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="izin_pulang_cepat">Izin Pulang Cepat</label>
                        <input type="number" class="form-control @error('izin_pulang_cepat') is-invalid @enderror" id="izin_pulang_cepat" name="izin_pulang_cepat" value="{{ old('izin_pulang_cepat', $data_cuti->izin_pulang_cepat) }}">
                        @error('izin_pulang_cepat')
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