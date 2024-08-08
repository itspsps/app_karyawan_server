@extends('layouts.dashboard')
@section('isi')
<center>
    <div class="container-fluid">
        <div class="card card-outline card-primary col-lg-4">
            <div class="p-4">
                <form method="post" action="{{ url('/karyawan/proses-edit-shift/'.$shift_karyawan->id.'/'.$holding) }}">
                    @method('put')
                    @csrf
                    <div class="form-group">
                        <label for="shift_id" class="float-left">Shift</label>
                        <select class="form-control selectpicker @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id" data-live-search="true">
                            @foreach ($shift as $s)
                            @if(old('shift_id', $shift_karyawan->shift_id) == $s->id)
                            <option value="{{ $s->id }}" selected>{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                            @else
                            <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('shift_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal" class="float-left">Tanggal</label>
                        <input type="datetime" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $shift_karyawan->tanggal) }}">
                        @error('tanggal')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <input type="hidden" name="user_id" value="{{ $shift_karyawan->user_id }}">
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </form>
                <br>
            </div>
        </div>
    </div>
</center>
<br>
@endsection