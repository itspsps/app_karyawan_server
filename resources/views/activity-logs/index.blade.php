@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <!-- <center>
                <a href="{{ url('/dokumen/tambah') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                    Dokumen</a>
            </center> -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <h1>Activity Logs</h1>

            <form class="" method="post" action="javascript:void(0);" method="POST" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="activity" class="mr-2">Activity:</label>
                    <select name="activity" id="activity" class="form-control">
                        <option value="">All</option>
                        <option value="create">Create
                        </option>
                        <option value="update">Update
                        </option>
                        <option value="delete">Delete
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ ucfirst($log->activity) }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $logs->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<br>
@endsection