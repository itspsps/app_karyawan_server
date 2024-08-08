@extends('layouts.dashboard')
@section('isi')
<center>
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-4">
            <div class="p-4">
                <form method="post" action="{{ url('/departemen/insert/'.$holding) }}">
                    @csrf
                    <div class="form-group">
                        <label for="nama_departemen" class="float-left">Nama Departemen</label>
                        <input type="text" class="form-control @error('nama_departemen') is-invalid @enderror" id="nama_departemen" name="nama_departemen" autofocus value="{{ old('nama_departemen') }}">
                        @error('nama_departemen')
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