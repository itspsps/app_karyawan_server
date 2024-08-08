@extends('layouts.dashboard')
@section('isi')
<center>
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-5">
            <div class="mt-4 p-3">
                <form method="post" action="{{ url('/karyawan/edit-password-proses/'.$karyawan->id) }}">
                    @method('put')
                    @csrf
                    <div class="form-group">
                        <label for="name" class="float-left">Nama</label>
                        <input type="text" class="form-control" value="{{ $karyawan->name }}" disabled id="name">
                    </div>
                    <div class="form-group">
                        <label for="password" class="float-left">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
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