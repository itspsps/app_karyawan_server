@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<!-- Categorie -->
<div class="categorie-section">
    <div class="title-bar" style="margin-top: 10%;">
        <h5 class="dz-title">Record Data Absensi
            <select class="month" style="width: max-content;border-radius: 0px; background-color:transparent; color: var(--primary); border: none;outline: none;" name="" id="month">
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">Juli</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            &nbsp;{{$thnskrg}}
        </h5>
    </div>
    <div class="card">
        <table id="table_absensi" class="table table-striped table-hover" style="width: 100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal&nbsp;Masuk</th>
                    <th scope="col">Jam&nbsp;Masuk</th>
                    <th scope="col">Tanggal&nbsp;Pulang</th>
                    <th scope="col">Jam&nbsp;Pulang</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!-- Categorie End -->
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        load_data();

        function load_data(filter_month = '') {
            console.log(filter_month);
            var table1 = $('#table_absensi').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                "bPaginate": false,
                searching: false,
                ajax: {
                    url: "{{ route('get_table_absensi') }}",
                    data: {
                        filter_month: filter_month,
                    }
                },
                columns: [{
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'tanggal_masuk',
                        name: 'tanggal_masuk'
                    },
                    {
                        data: 'jam_absen',
                        name: 'jam_absen'
                    },
                    {
                        data: 'tanggal_pulang',
                        name: 'tanggal_pulang'
                    },
                    {
                        data: 'jam_pulang',
                        name: 'jam_pulang'
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });
        }

        function load_absensi(filter_month = '') {
            $.ajax({
                url: "{{route('get_count_absensi_home')}}",
                data: {
                    filter_month: filter_month,
                },
                type: "GET",
                error: function() {
                    alert('Something is wrong');
                },
                success: function(data) {
                    $('#count_absen_hadir').html(data);
                    console.log(data)
                }
            });
        }
        $('#month').change(function() {
            filter_month = $(this).val();
            console.log(filter_month);
            $('#table_absensi').DataTable().destroy();
            load_data(filter_month);
            load_absensi(filter_month);


        })
    });
</script>
@endsection