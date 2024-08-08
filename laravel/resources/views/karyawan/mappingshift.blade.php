@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="p-4">
                    <form method="post" action="{{ url('/karyawan/shift/proses-tambah-shift/'.$holding) }}">
                        @csrf
                        <div class="form-group">
                            <label for="shift_id" class="float-left">Shift</label>
                            <select class="form-control selectpicker @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id" data-live-search="true">
                                <option value="">Pilih Shift</option>
                                @foreach ($shift as $s)
                                @if(old('shift_id') == $s->id)
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
                            <label for="tanggal_mulai" class="float-left">Tanggal Mulai</label>
                            <input type="datetime" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_akhir" class="float-left">Tanggal Akhir</label>
                            <input type="datetime" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <input type="hidden" name="tanggal">
                            <input type="hidden" name="status_absen">
                            <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <center>
                        <h3>{{ $karyawan->name }}</h3>
                    </center>
                </div>
                <div class="card-body">
                    <table id="tableprint" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Shift Karyawan</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shift_karyawan as $sk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sk->tanggal}}</td>
                                <td>{{ $sk->Shift->nama_shift}}</td>
                                <td>{{ $sk->Shift->jam_masuk}}</td>
                                <td>{{ $sk->Shift->jam_keluar}}</td>
                                <td>
                                    <a href="{{ url('/karyawan/edit-shift/'.$sk->id.'/'.$holding) }}" class="btn btn-sm btn-warning"><i class="fa fa-solid fa-edit"></i></a>
                                    <form action="{{ url('/karyawan/delete-shift/'.$sk->id.'/'.$holding) }}" method="post" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                                        <button class="btn btn-danger btn-sm btn-circle" onClick="return confirm('Are You Sure')"><i class="fa fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
@endsection