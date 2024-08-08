<!DOCTYPE html>
<html>

<head>
    <title>FORM PERMINTAAN CUTI</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}">
</head>


<body>
    <div style="float: right; margin-top: -30px; margin-right: -10px; width:250px; height: auto;border: 1px solid black; box-sizing: border-box;">
        <h5 style="text-align: right;">
            {{$data_cuti->no_form_cuti}}&nbsp;
        </h5>
    </div>
    <table border="0" style="margin-top: 3px;" class="kop" width="100%">
        <tr>
            <td style="width:25%;"> <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="100px" class="images"> </td>
            <td>
                <h4 style="text-align: center;">FORMULIR<br>PERMINTAAN CUTI</h4>
            </td>
            <td style="width: 40px;">
            </td>
            <td>
            </td>
        </tr>
    </table>
    <table style="margin-top: 10px; border-bottom: black;">
        <tbody>
            <tr>
                <td>Tanggal&nbsp;</td>
                <td>&nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_cuti->tanggal)->format('d-m-Y')}}&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 0%;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="2" style="text-align: center;">DATA KARYAWAN</th>
        </thead>
        <tbody>
            <tr>
                <th>Nomor Induk Karyawan</th>
                <td>:&nbsp;{{$data_cuti->nomor_identitas_karyawan}}</td>
            </tr>
            <tr>
                <th>Nama Karyawan</th>
                <td>:&nbsp;{{$data_cuti->User->fullname}}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>:&nbsp;{{$departemen->nama_departemen}}</td>
            </tr>
            <tr>
                <th>Divisi</th>
                <td>:&nbsp;@foreach($divisi as $divisi){{$divisi->nama_divisi}} @endforeach</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>:&nbsp;@foreach($jabatan as $jabatan){{$jabatan->nama_jabatan}} @endforeach</td>
            </tr>
            <tr>
                <th>Lokasi Kerja</th>
                <td>:&nbsp;{{$data_cuti->User->penempatan_kerja}}</td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td>:&nbsp;{{$data_cuti->User->telepon}}</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 5%;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="1" style="text-align: center;">JENIS CUTI</th>
            <th colspan="2" style="text-align: center;">PERIODE CUTI</th>
        </thead>
        <tbody>
            <tr>
                <th>
                    <ul>
                        <li>
                            {{$data_cuti->nama_cuti}}
                        </li>
                    </ul>
                </th>
                <th>Tanggal Awal Cuti</th>
                <td>:&nbsp;{{ \Carbon\Carbon::parse($data_cuti->tanggal_mulai)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">@if($data_cuti->nama_cuti=='Diluar Cuti Tahunan')<i>({{$data_cuti->KategoriCuti->nama_cuti}})</i> @else @endif</td>
                <th>Tanggal Terakhir Cuti</th>
                <td>:&nbsp;{{ \Carbon\Carbon::parse($data_cuti->tanggal_selesai)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td></td>
                <th>Total Hari</th>
                <td>:&nbsp;{{$data_cuti->total_cuti}} Hari</td>
            </tr>
            <tr>
                <td></td>
                <th>Tanggal Kembali ke Kantor</th>
                <td>:&nbsp;{{ \Carbon\Carbon::parse($data_cuti->tanggal_selesai)->addDays(1)->format('d-m-Y')}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th style="background-color:#F2F3F4 ;">Nama Pengganti<i>(Jika Ada)</i></th>
                <td style="background-color:#F2F3F4 ;">:&nbsp;{{$pengganti->name}}</td>
            </tr>
        </tfoot>
    </table>
    <div style="float: left; margin-top: 15px; margin-bottom:5%; width:100%; height: 100px;border: 1px solid #E6E6FA; box-sizing: border-box; border-radius: 7px;">
        <h5 style="text-align: left; padding-left: 3px;">
            Keterangan : <br>
            {{$data_cuti->keterangan_cuti}}&nbsp;
        </h5>
    </div>
    <h5>Pengesahan</h5>
    <table class="table table-bordered" style="margin-top: 2%;" width="100%">
        <thead>
            <tr style="text-align: center;">
                <th>Diajukan Oleh :</th>
                <th>Disahkan Oleh - 1 :</th>
                <th>Disahkan Oleh - 2 :</th>
            </tr>
        </thead>
        <tbody>
            <tr style="font-weight: bold;">
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_cuti->ttd_user.'.png') }}" width="100%" alt="">
                    <p>{{Auth::user()->name}}</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_cuti->ttd_atasan.'.png') }}" width="100%" alt="">
                    <p style="margin-bottom: -10px;">{{$data_cuti->approve_atasan}}</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_cuti->ttd_atasan2.'.png') }}" width="100%" alt="">
                    <p>{{$data_cuti->approve_atasan2}}</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>