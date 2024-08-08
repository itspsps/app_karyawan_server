@extends('users.cuti.layout.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<style>
    .modal-backdrop.show:nth-of-type(even) {
        z-index: 1051 !important;
    }
</style>
@endsection
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>

<link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" rel="stylesheet"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="container">
    @if(Session::has('mappingshiftsuccess'))
    <div id="alert_addmappingsuccess" class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Menyimpan Data
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    @elseif(Session::has('mappingshiftupdatesuccess'))
    <div id="alert_statusmappingeditsuccess" class="alert alert-success light alert-lg alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <strong>Success!</strong> Anda Berhasil Mengedit Data Mapping
        <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    <form class="my-2">
        <div class="input-group">
            <input type="text" value="" readonly style="font-weight: bold" placeholder="Search" class="form-control">
        </div>
    </form>
</div>
</div>

<hr width="90%" style="margin-top: -15%;">

<div class="page-content">
    <div class="container fb">
        <div class="row">
            @foreach($user_shift as $user)
            <div class="col-xl-4">
                <a href="" data-bs-toggle="modal" data-bs-target="#modal_{{$user->id}}">
                    <div class="swiper-slide">
                        <div class="card job-post">
                            <div class="card-body">
                                <div class="media media-80">
                                    @if($user->foto_karyawan=='')
                                    <img src="{{ asset('assets/assets_users/images/users/user_icon.jpg') }}" alt="/">
                                    @else
                                    <img src="https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_karyawan/{{$user->foto_karyawan}}" alt="/">
                                    @endif
                                </div>
                                <div class="card-info">
                                    <?php
                                    $iduser = "{$user->id}";
                                    $data_mapping_start = App\Models\MappingShift::with('Shift')->with('User')->where('user_id', $iduser)->orderBy('tanggal', 'ASC')->first();
                                    if ($data_mapping_start == '') {
                                        $mapping_start = NULL;
                                    } else {
                                        if ($data_mapping_start >= date('Y-m-d')) {
                                            $mapping_start = date('Y-m-d');
                                        } else {
                                            $mapping_start = App\Models\MappingShift::with('Shift')->with('User')->where('user_id', $iduser)->orderBy('tanggal', 'ASC')->value('tanggal');
                                        }
                                    }
                                    $data_mapping_end = App\Models\MappingShift::with('Shift')->with('User')->where('user_id', $iduser)->orderBy('tanggal', 'DESC')->first();
                                    if ($data_mapping_end == '') {
                                        $mapping_end = NULL;
                                    } else {
                                        $mapping_end = App\Models\MappingShift::with('Shift')->with('User')->where('user_id', $iduser)->orderBy('tanggal', 'DESC')->value('tanggal');
                                    }
                                    $count_data_mapping = App\Models\MappingShift::with('Shift')->with('User')->where('user_id', $iduser)->whereBetween('tanggal', [$mapping_start, $mapping_end])->count();
                                    $koordinator = App\Models\MappingShift::with('Shift')->with('Koordinator')->where('user_id', $iduser)->whereBetween('tanggal', [$mapping_start, $mapping_end])->first();
                                    // print_r($koordinator);
                                    ?>
                                    <!-- <p>{{$koordinator}}</p> -->
                                    <h6 class="title">{{$user->name}}</h6>
                                    <span class="">Koordinator: @if($koordinator==NULL)- @else {{$koordinator->Koordinator->name}} @endif</span>
                                    <div class="d-flex align-items-center">
                                        @if($count_data_mapping <='0' || $data_mapping_end->tanggal <= date('d-m-Y') ) <small class="badge badge-danger"><i class="fa fa-spinner"></i>&nbsp;Belum Mapping</small>
                                                @else
                                                <small class="badge badge-success"><i class="fa fa-check"></i>&nbsp;@if($data_mapping_start->tanggal >= date('d-m-Y')){{date('d-m-Y')}}@else{{ \Carbon\Carbon::parse($data_mapping_start->tanggal)->format('d-m-Y')}}@endif - {{ \Carbon\Carbon::parse($data_mapping_end->tanggal)->format('d-m-Y')}}</small>
                                                @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- Modal -->
                <div class="modal fade" id="modal_edit_shift">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Mapping</h5>
                                <button class="btn-close" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <form class="my-2" method="post" action="{{ url('/mapping_shift/prosesEditMappingShift') }}" enctype="multipart/form-data">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" id="id_mapping" name="id_mapping" value="">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Shift" readonly>
                                        <select class="form-control" id="shift_update" name="shift_update" style="font-weight: bold" required>
                                            <option value="">Pilih Shift</option>
                                            @foreach($shift as $data)
                                            <option value="{{$data->id}}">{{$data->nama_shift}} ({{$data->jam_masuk}} - {{$data->jam_keluar}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Tanggal" readonly>
                                        <input type="date" class="form-control" name="tanggal_update" id="tanggal_update" value="" style="font-weight: bold" required>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal_{{$user->id}}">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Form Mapping ({{$user->name}})</h5>
                                <button class="btn-close" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <form class="my-2" method="post" action="{{ url('/mapping_shift/prosesAddMappingShift') }}" enctype="multipart/form-data">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{$user->id}}">
                                    <input type="hidden" name="id_atasan" value="{{Auth::user()->id}}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Shift" readonly>
                                        <select class="form-control" name="shift" style="font-weight: bold" required>
                                            <option value="">Pilih Shift</option>
                                            @foreach($shift as $data)
                                            <option value="{{$data->id}}">{{$data->nama_shift}} ({{$data->jam_masuk}} - {{$data->jam_keluar}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <?php
                                        $koordinator = App\Models\User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                                            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                                            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                                            ->where('users.dept_id', auth()->user()->dept_id)
                                            ->where('level_jabatans.level_jabatan', 5)
                                            ->select('users.*')
                                            ->get();
                                        ?>
                                        <input type="text" class="form-control" value="Koordinator" readonly>
                                        <select class="form-control" name="koordinator" style="font-weight: bold" required>
                                            <option value="">Pilih Koordinator</option>
                                            @foreach($koordinator as $s)
                                            <option value="{{$s->id}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Lokasi Bekerja" readonly>
                                        <input type="text" class="form-control" name="lokasi_bekerja" value="" required>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Tanggal Mulai" readonly>
                                        <input type="date" class="form-control" name="tanggl_mulai" value="" style="font-weight: bold" required>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Tanggal Akhir" readonly>
                                        <input type="date" class="form-control" name="tanggal_akhir" value="" style="font-weight: bold" required>
                                    </div>
                                    <h6 class="mt-3">Tabel Mapping Shift</h6>
                                    <div class="table-responsive">
                                        <table class="table" id="table_mapping_shift" style="width:100%;">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Shift</th>
                                                    <th>Jam&nbsp;Masuk</th>
                                                    <th>Jam&nbsp;Keluar</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $iduser = "{$user->id}";
                                                $data_mapping = App\Models\MappingShift::with('Shift')->with('User')->where('user_id', $iduser)->get();
                                                $no = 1;
                                                ?>
                                                @foreach($data_mapping as $data)
                                                <tr>
                                                    <td>{{$no++;}}</td>
                                                    <td>{{$data->tanggal}}</td>
                                                    <td>{{$data->Shift->nama_shift}}</td>
                                                    <td>{{$data->Shift->jam_masuk}}</td>
                                                    <td>{{$data->Shift->jam_keluar}}</td>
                                                    <td>
                                                        @if($data->tanggal >= date('Y-m-d')) <a id="btn_modal_shift" type="button" data-id="{{$data->id}}" data-shift="{{$data->shift_id}}" data-userid="{{$data->user_id}}" data-tanggal="{{$data->tanggal}}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                                        <a id="btn_delete_mapping_shift" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                                        @else
                                                        <a id="btn_delete_mapping_shift" data-id="' . $row->id . '" data-holding="' . $holding . '" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
    // let table = new DataTable('#table_mapping_shift');
    $('#table_mapping_shift').dataTable({
        searching: false,
        responsive: true,
        "bPaginate": false,
        // paging: false,
    });
    $('body').on("click", "#btn_modal_shift", function() {

        $('#modal_edit_shift').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        var id = $(this).data('id');
        var shift = $(this).data('shift');
        var tanggal = $(this).data('tanggal');
        $('#id_mapping').val(id);
        $('#shift_update').val(shift);
        $('#tanggal_update').val(tanggal);
        $("#modal_edit_shift").modal("show");

    });
</script>
@endsection