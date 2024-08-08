@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <center>
                <a href="{{ url('/divisi/create/'.$holding) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data Divisi</a>
            </center>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="tableprint" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Departemen</th>
                        <th>Nama Divisi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_divisi as $dj)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dj->Departemen->nama_departemen}}</td>
                        <td>{{ $dj->nama_divisi }}</td>
                        <td>
                            <a href="{{ url('/divisi/edit/'.$dj->id.'/'.$holding) }}" class="btn btn-sm btn-warning"><i class="fa fa-solid fa-edit"></i></a>
                            <form action="{{ url('/divisi/delete/'.$dj->id.'/'.$holding) }}" method="post" class="d-inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-circle" onClick="return confirm('Are You Sure')"><i class="fa fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<br>
@endsection