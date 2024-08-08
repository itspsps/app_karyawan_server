@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<style type="text/css">
    .my-swal {
        z-index: X;
    }
</style>
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DATA ACTIVITY LOG</h5>
                    </div>
                </div>
                <div class="card-body">
                    <hr class="my-5">
                    <form class="" method="post" action="javascript:void(0);" method="POST" class="form-inline mb-3">
                        <div class="row">
                            <div class="col-lg-3 text-center">
                            </div>
                            <div class="col-lg-3 text-center">
                                <div class="form-group mr-2">
                                    <div class="form-floating form-floating-outline">
                                        <select name="activity" id="activity" class="form-control">
                                            <option value="">All</option>
                                            <option value="create">Create
                                            </option>
                                            <option value="tambah">Tambah
                                            </option>
                                            <option value="update">Update
                                            </option>
                                            <option value="delete">Delete
                                            </option>
                                        </select>
                                        <label for="activity" class="mr-2">Activity:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <button type="button" id="btn_filter" name="btn_filter" class="btn btn-primary">Search</button>
                            </div>
                    </form>
                </div>
                <table class="table" id="table_activity" style="width: 100%;">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Nama&nbsp;User</th>
                            <th>Aktivitas</th>
                            <th>Deskripsi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Transactions -->
    <!--/ Data Tables -->
</div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).ready(function() {
        let holding = window.location.pathname.split("/").pop();
        load_data();

        function load_data(activity = '') {
            var table = $('#table_activity').DataTable({
                "scrollY": true,
                "scrollX": true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('activity-datatable') }}" + '/' + holding,
                    data: {
                        activity: activity,
                    }
                },
                columns: [{
                        data: "id",

                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'activity',
                        name: 'activity'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                ]
            });
        }
        $('#btn_filter').click(function() {
            var activity = $('#activity').val();
            console.log(activity);
            if (activity != '') {
                $('#table_activity').DataTable().destroy();
                // table.ajax.reload(from_date, to_date);
                load_data(activity);
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Sukses filter data',
                    icon: 'success',
                    timer: 1500
                });
            } else {
                Swal.fire({
                    title: 'Infoo!!',
                    text: 'Mohon Isikan data',
                    icon: 'warning',
                    timer: 1500
                });
            }

        });
    });
</script>
@endsection