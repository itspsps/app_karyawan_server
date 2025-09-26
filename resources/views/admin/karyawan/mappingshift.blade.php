@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/assets_users/css/daterangepicker.css') }}" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style type="text/css">
    .my-swal {
        z-index: X;
    }
</style>
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">KARYAWAN /</span> MAPPING SHIFT KARYAWAN</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <a href="@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/mapping_shift/'.$holding->holding_code)}}@else{{url('karyawan/mapping_shift/'.$holding->holding_code)}}@endif" class="btn btn-sm btn-secondary">
                        <i class="mdi mdi-arrow-left"></i>
                    </a>
                    <a href="@if(Auth::user()->is_admin=='hrd'){{url('hrd/karyawan/detail/'.$karyawan->id.'/'.$holding)}}@else{{url('karyawan/detail/'.$karyawan->id.'/'.$holding)}}@endif" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-account-arrow-left"></i>
                        &nbsp;Profil
                    </a>
                </div>
                </ul>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if($karyawan->foto_karyawan == null)
                        <img src="{{asset('admin/assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                        @else
                        <img src="https://hrd.sumberpangan.store:4430/storage/app/public/foto_karyawan/{{$karyawan->foto_karyawan}}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="template_foto_karyawan" />
                        @endif
                        @if($karyawan->kategori=='Karyawan Bulanan')
                        <table>
                            <tr>
                                <th>Nama</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>{{$karyawan->name}}</td>
                            </tr>
                            <tr>
                                <th>Divisi</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    @if(count($divisi_karyawan)>1)
                                    @foreach($divisi_karyawan as $dv)
                                    {{$no++;}}. {{$dv->nama_divisi}} <br>
                                    @break
                                    @endforeach
                                    @else
                                    {{$karyawan->Divisi->nama_divisi}} <br>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    @if(count($jabatan_karyawan)>1)
                                    @foreach($jabatan_karyawan as $jb)
                                    {{$no1++;}}. {{$jb->nama_jabatan}} <br>
                                    @endforeach
                                    @else
                                    {{$karyawan->Jabatan->nama_jabatan}} <br>
                                    @endif
                                </td>
                            <tr>
                                <th>Kontrak Kerja</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    {{$karyawan->KontrakKerja->holding_name}}
                                </td>
                            </tr>
                            <tr>
                                <th>Penempatan Kerja</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    {{$karyawan->penempatan_kerja}}
                                </td>
                            </tr>
                        </table>
                        @else
                        <table>
                            <tr>
                                <th>Nama</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>{{$karyawan->name}}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    Karyawan Harian
                                </td>
                            <tr>
                                <th>Penempatan Kerja</th>
                                <td>&nbsp;</td>
                                <td>:</td>
                                <td>
                                    {{$karyawan->penempatan_kerja}}
                                </td>
                            </tr>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header">Mapping Shift</h4>
                <!-- Account -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-8">
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%">
                                <button class="btn btn-outline-secondary waves-effect">
                                    FILTER DATE : &nbsp;
                                    <i class="mdi mdi-calendar-filter-outline"></i>&nbsp;
                                    <span></span> <i class="mdi mdi-menu-down"></i>
                                    <input type="date" id="start_date" name="start_date" hidden value="">
                                    <input type="date" id="end_date" name="end_date" hidden value="">
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <!-- modal edit -->
                    <div class="modal fade" id="modal_edit_shift" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable ">
                            <form method="post" action="@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/proses-edit-shift/'.$holding) }}@else{{ url('karyawan/proses-edit-shift/'.$holding) }}@endif" class=" modal-content" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title" id="backDropModalTitle">Edit Shift</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <input type="hidden" name="id_shift" id="id_shift" value="">
                                        <input type="hidden" name="user_id" id="user_id" value="">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-control selectpicker @error('shift_id_update') is-invalid @enderror" id="shift_id_update" name="shift_id_update" data-live-search="true">
                                                @foreach ($shift as $s)
                                                <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @endforeach
                                            </select>
                                            <label for="shift_id_update">Shift</label>
                                        </div>
                                        @error('shift_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="date" class="form-control @error('tanggal_update') is-invalid @enderror" id="tanggal_update" name="tanggal_update" value="{{ old('tanggal_update') }}">
                                            <label for="tanggal_update">Tanggal</label>
                                        </div>
                                        @error('tanggal_update')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <br>
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control @error('keterangan_update') is-invalid @enderror" id="keterangan_update" name="keterangan_update" value="{{ old('keterangan_update') }}">
                                            <label for="keterangan_update">Keterangan</label>
                                        </div>
                                        @error('keterangan_update')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table" id="table_mapping_shift" style="width:100%;">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Shift&nbsp;Karyawan</th>
                                <th>Tanggal&nbsp;Masuk</th>
                                <th>Jam&nbsp;Masuk</th>
                                <th>Tanggal&nbsp;Pulang</th>
                                <th>Jam&nbsp;Keluar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/assets_users/js/daterangepicker.js') }}"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(doucment).ready(function() {

        var start = moment().startOf('month');
        var end = moment().endOf('month');
        var lstart, lend;
        var start_date = document.getElementById("start_date");
        var end_date = document.getElementById("end_date");

        function cb(start, end) {
            $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            lstart = moment($('#reportrange').data('daterangepicker').startDate).format('YYYY-MM-DD');
            lend = moment($('#reportrange').data('daterangepicker').endDate).format('YYYY-MM-DD');
            start_date.value = lstart;
            end_date.value = lend;
            // console.log(lstart, lend)
            load_data(lstart, lend);
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });
</script>
<script type="text/javascript">
    let holding = '{{$holding->holding_code}}';
    let id = '{{$karyawan->id}}';
    let auth = '{{ Auth::user()->is_admin }}';
    end_date = $('#end_date').val();
    start_date = $('#start_date').val();

    function load_data(start_date = '', end_date = '') {
        $('#table_mapping_shift').DataTable().destroy();
        console.log(start_date, end_date);
        var table = $('#table_mapping_shift').DataTable({
            pageLength: 50,
            "scrollY": true,
            "scrollX": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "@if(Auth::user()->is_admin=='hrd'){{ url('hrd/karyawan/mapping_shift_detail_datatable') }}@else{{ url('karyawan/mapping_shift_detail_datatable') }}@endif" + '/' + id + '/' + holding,
                data: {
                    start_date: start_date,
                    end_date: end_date,
                }
            },
            columns: [{
                    data: "id",

                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_shift',
                    name: 'nama_shift'
                },
                {
                    data: 'tanggal_masuk',
                    name: 'tanggal_masuk'
                },
                {
                    data: 'jam_masuk',
                    name: 'jam_masuk'
                },
                {
                    data: 'tanggal_pulang',
                    name: 'tanggal_pulang'
                },
                {
                    data: 'jam_keluar',
                    name: 'jam_keluar'
                },
                {
                    data: 'option',
                    name: 'option'
                },

            ],
            order: [
                [2, 'ASC'],
                [0, 'ASC']
            ]
        });
    }
</script>
<script>
    $(document).on("click", "#btn_edit_mapping_shift", function() {
        let id = $(this).data('id');
        let user_id = $(this).data('userid');
        let tanggal = $(this).data("tanggal");
        let tgl = new Date(tanggal);
        let date_now = new Date();
        let shift = $(this).data("shift");
        let holding = $(this).data("holding");
        let keterangan = $(this).data("keterangan");
        // console.log(tgl.getTime(), date_now.getTime());
        $('#id_shift').val(id);
        $('#tanggal_update').val(tanggal);
        $('#keterangan_update').val(keterangan);
        $('#user_id').val(user_id);
        $('#shift_id_update option').filter(function() {
            // console.log($(this).val().trim());
            return $(this).val().trim() == shift
        }).prop('selected', true)
        if (tgl.getTime() <= date_now.getTime()) {
            Swal.fire({
                title: 'Info!',
                text: 'Tidak Bisa Di Ubah Ketika Melebihi Tanggal Sekarang',
                icon: 'warning',
                timer: 3000
            })
        } else {
            $('#modal_edit_shift').modal('show');
        }
    });
    $(document).on('click', '#btn_delete_mapping_shift', function() {
        var id = $(this).data('id');
        let holding = $(this).data("holding");
        // console.log(id);
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu tidak dapat mengembalikan data ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "@if(Auth::user()->is_admin=='hrd'){{ url('/hrd/karyawan/delete-shift') }}@else{{ url('karyawan/delete-shift') }}@endif" + '/' + id + '/' + holding,
                    type: "GET",
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Data anda berhasil di hapus.',
                            icon: 'success',
                            timer: 1500
                        })
                        $('#table_mapping_shift').DataTable().ajax.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Your data is safe :',
                    icon: 'error',
                    timer: 1500
                })
            }
        });

    });
</script>

@endsection