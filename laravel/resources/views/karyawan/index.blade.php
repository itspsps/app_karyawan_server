@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <center>
                <a href="{{ url('/karyawan/tambah-karyawan/'.$holding) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data Karyawan</a>
            </center>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="tableprint" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_user as $du)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td> {{ $du->name }}</td>
                        <td>{{ $du->email }}</td>
                        <td>{{ $du->username }}</td>
                        <td>
                            <a href="{{ url('/karyawan/detail/'.$du->id.'/'.$holding) }}" class="btn btn-sm btn-info"><i class="fa fa-solid fa-eye"></i></a>
                            <a href="{{ url('/karyawan/edit-password/'.$du->id.'/'.$holding) }}" class="btn btn-sm btn-warning"><i class="fa fa-solid fa-key"></i></a>
                            <form action="{{ url('/karyawan/delete/'.$du->id.'/'.$holding) }}" method="post" class="d-inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-circle" onClick="return confirm('Are You Sure')"><i class="fa fa-solid fa-trash"></i></button>
                            </form>
                            <a href="{{ url('/karyawan/shift/'.$du->id.'/'.$holding) }}" class="btn btn-sm btn-success"><i class="fa fa-solid fa-clock"></i></a>
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