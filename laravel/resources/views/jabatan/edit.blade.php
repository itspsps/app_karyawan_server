@extends('layouts.dashboard')
@section('isi')
<center>
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-8">
            <div class="p-4">
                <form method="post" action="{{ url('/jabatan/update/'.$data_jabatan->id.'/'.$holding) }}">
                    @method('put')
                    @csrf
                    <div class="form-group">
                        <label for="nama_divisi" class="float-left">Nama Divisi</label>
                        <select class="form-control @error('nama_divisi') is-invalid @enderror" id="nama_divisi" name="nama_divisi" autofocus value="{{ old('nama_divisi') }}">
                            <option value=""> Pilih Divisi</option>
                            @foreach($get_divisi as $s)
                            <option value="{{$s->id}}" {{$s->id == $data_jabatan->divisi_id  ? 'selected' : ''}}>{{$s->nama_divisi}}</option>
                            @endforeach
                        </select>
                        @error('nama_divisi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_jabatan" class="float-left">Nama Jabatan</label>
                        <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror" id="nama_jabatan" name="nama_jabatan" autofocus value="{{ old('nama_jabatan', $data_jabatan->nama_jabatan) }}">
                        @error('nama_jabatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="level_jabatan" class="float-left">Level Jabatan</label>
                        <select class="form-control @error('level_jabatan') is-invalid @enderror" id="level_jabatan" name="level_jabatan" autofocus value="{{ old('level_jabatan') }}">
                            <option value=""> Pilih Level</option>
                            @foreach($get_level as $data)
                            <option value="{{$data->id}}" {{$data->id == $data_jabatan->level_id  ? 'selected' : ''}}>{{$data->level_jabatan}}</option>
                            @endforeach
                        </select>
                        @error('level_jabatan')
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