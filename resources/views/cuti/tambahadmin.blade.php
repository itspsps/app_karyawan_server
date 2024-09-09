@extends('layouts.dashboard')
@section('isi')

    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-12">
            <div class="mt-4 p-3">
                <form method="post" action="{{ url('/data-cuti/proses-tambah') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <label for="user_id_ajax">Nama Karyawan</label>
                            <select id="user_id_ajax" name="user_id" class="form-control selectpicker" id="">
                                <option value="">Pilih Karyawan</option>
                                @foreach ($data_user as $du)
                                    @if(old('user_id') == $du->id)
                                        <option value="{{ $du->id }}" selected>{{ $du->name }}</option>
                                    @else
                                        <option value="{{ $du->id }}">{{ $du->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="nama_cuti_ajax">Nama Cuti</label>
                            <select name="nama_cuti" id="nama_cuti_ajax" class="form-control">
                                <option value="">Pilih Cuti</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="datetime" class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="datetime" class="form-control @error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="tanggal">
                    </div>
                    <br>
                    <div class="form-row">
                        <div class="col">
                            <label for="foto_cuti">Unggah Foto</label>
                            <input type="file" name="foto_cuti" id="foto_cuti" class="form-control @error('foto_cuti') is-invalid @enderror">
                            @error('foto_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="alasan_cuti">Alasan Cuti</label>
                            <input type="text" class="form-control @error('alasan_cuti') is-invalid @enderror" id="alasan_cuti" name="alasan_cuti" value="{{ old('alasan_cuti') }}">
                            @error('alasan_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="status_cuti">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
            </div>
        </div>
    </div>
    <br>

@endsection
