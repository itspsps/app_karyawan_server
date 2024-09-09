@extends('layouts.dashboard')
@section('isi')
<center>
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-4">
            <div class="p-4">
                <form method="post" action="{{ url('/shift/store/'.$holding) }}">
                    @csrf
                    <div class="form-group">
                        <label for="nama_shift" class="float-left">Nama Shift</label>
                        <input type="text" class="form-control @error('nama_shift') is-invalid @enderror" id="nama_shift" name="nama_shift" autofocus value="{{ old('nama_shift') }}">
                        @error('nama_shift')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jam_masuk" class="float-left">Jam Masuk</label>
                        <input type="datetime-local" class="form-control @error('jam_masuk') is-invalid @enderror" id="jam_masuk" name="jam_masuk" autofocus value="{{ old('jam_masuk') }}">
                        @error('jam_masuk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group" class="float-left">
                        <label for="jam_keluar">Jam Keluar</label>
                        <input type="datetime-local" class="form-control @error('jam_keluar') is-invalid @enderror" id="jam_keluar" name="jam_keluar" autofocus value="{{ old('jam_keluar') }}">
                        @error('jam_keluar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </form>
                <br>
            </div>
        </div>
    </div>
</center>
<br>
@endsection