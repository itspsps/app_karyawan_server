@extends('layouts.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-12">
            <div class="mt-4">
                <form method="post" action="{{ url('/data-absen/'.$data_absen->id.'/proses-edit-pulang') }}" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <label for="jam_pulang">Jam Pulang</label>
                            <input type="datetime-local" class="form-control @error('jam_pulang') is-invalid @enderror" name="jam_pulang" id="jam_pulang" value="{{ old('jam_pulang', $data_absen->jam_pulang) }}">
                            @error('jam_pulang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="foto_jam_pulang">Foto Absen Pulang</label>
                            <input type="file" class="form-control @error('foto_jam_pulang') is-invalid @enderror" id="foto_jam_pulang" name="foto_jam_pulang">
                            @error('foto_jam_pulang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <input type="hidden" name="foto_jam_pulang_lama" value="{{ $data_absen->foto_jam_pulang }}">
                        </div>
                        <input type="hidden" name="lat_pulang" value="{{ $lokasi_kantor->lat_kantor }}">
                        <input type="hidden" name="long_pulang" value="{{ $lokasi_kantor->long_kantor }}">
                        <input type="hidden" name="pulang_cepat">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                  <br>
            </div>
        </div>
    </div>
    <br>
@endsection
