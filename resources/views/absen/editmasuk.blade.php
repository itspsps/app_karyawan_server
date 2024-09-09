@extends('layouts.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-12">
            <div class="mt-4">
                <form method="post" action="{{ url('/data-absen/'.$data_absen->id.'/proses-edit-masuk') }}" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <label for="jam_absen">Jam Absen</label>
                            <input type="datetime-local" class="form-control @error('jam_absen') is-invalid @enderror" id="jam_absen" name="jam_absen" value="{{ old('jam_absen', $data_absen->jam_absen) }}">
                            @error('jam_absen')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="foto_jam_absen">Foto Absen Masuk</label>
                            <input type="file" class="form-control @error('foto_jam_absen') is-invalid @enderror" name="foto_jam_absen" id="foto_jam_absen">
                            @error('foto_jam_absen')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <input type="hidden" name="foto_jam_absen_lama" value="{{ $data_absen->foto_jam_absen }}">
                        </div>
                        <input type="hidden" name="lat_absen" value="{{ $lokasi_kantor->lat_kantor }}">
                        <input type="hidden" name="long_absen" value="{{ $lokasi_kantor->long_kantor }}">
                        <input type="hidden" name="telat">
                        <input type="hidden" name="jarak_masuk">
                        <input type="hidden" name="status_absen" value="Masuk">
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
