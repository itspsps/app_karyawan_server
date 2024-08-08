@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <center>
                <a class="btn btn-primary" href="{{ url('/data-cuti/tambah') }}">+ Tambah Cuti Karyawan</a>
            </center>
        </div>
        <div class="card-body p-3">
                <table class="table table-striped" id="tableprint" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Karyawan</th>
                            <th>Nama Cuti</th>
                            <th>Tanggal</th>
                            <th>Alasan Cuti</th>
                            <th>Foto Cuti</th>
                            <th>Status Cuti</th>
                            <th>Catatan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_cuti as $dc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dc->User->name }}</td>
                            <td>{{ $dc->nama_cuti }}</td>
                            <td>{{ $dc->tanggal}}</td>
                            <td>{{ $dc->alasan_cuti}}</td>
                            <td>
                                <img src="{{ url('storage/'.$dc->foto_cuti) }}" style="width: 200px" alt="">
                            </td>
                            <td>
                                @if($dc->status_cuti == "Diterima")
                                    <span class="badge badge-success">{{ $dc->status_cuti }}</span>
                                @elseif($dc->status_cuti == "Ditolak")
                                    <span class="badge badge-danger">{{ $dc->status_cuti }}</span>
                                @else
                                    <span class="badge badge-warning">{{ $dc->status_cuti }}</span>
                                @endif
                            </td>
                            <td>{{ $dc->catatan}}</td>
                            <td>
                                @if($dc->status_cuti == "Diterima")
                                    <span class="badge badge-success">Sudah Approve</span>
                                @else
                                    <a href="{{ url('/data-cuti/edit/'.$dc->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-exclamation-triangle"></i></a>
                                @endif

                                @if($dc->status_cuti == "Diterima")
                                    <span class="badge badge-success">Sudah Approve</span>
                                @else
                                    <form action="{{ url('/data-cuti/delete/'.$dc->id) }}" method="post" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="btn btn-danger btn-sm" onClick="return confirm('Are You Sure')"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>
<br>
@endsection
