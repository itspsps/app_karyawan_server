@extends('layouts.dashboard')
@section('isi')
<center>
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-8">
            <div class="p-4">
                <form method="post" action="{{ url('/divisi/insert/'.$holding) }}">
                    @csrf
                    <div class="form-group">
                        <label for="nama_departemen" class="float-left">Nama Departemen</label>
                        <select class="form-control @error('nama_departemen') is-invalid @enderror" id="nama_departemen" name="nama_departemen" autofocus value="{{ old('nama_departemen') }}">
                            <option value=""> Pilih Departemen</option>
                            @foreach($data_departemen as $data)
                            <option value="{{$data->id}}">{{$data->nama_departemen}}</option>
                            @endforeach
                        </select>
                        @error('nama_divisi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_divisi" class="float-left">Nama Divisi</label>
                        <input type="text" class="form-control @error('nama_divisi') is-invalid @enderror" id="nama_divisi" name="nama_divisi" autofocus value="{{ old('nama_divisi') }}">
                        @error('nama_divisi')
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