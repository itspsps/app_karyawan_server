<!DOCTYPE html>
<html>

<head>
    <title>FORM PERMINTAAN CUTI</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}">
</head>


<body>
    <div style="float: right; margin-top: -30px; margin-right: -10px; width:200px; height: auto;border: 1px solid black; box-sizing: border-box;">
        <h5 style="text-align: right; font-size: 11pt;">
            {{$data_cuti->no_form_cuti}}&nbsp;
        </h5>
    </div>
    <table border="0" style="margin-top: 1px;" class="kop" width="100%">
        <tr>
            <td style="width:20%;">
                @if($data_cuti->User->kontrak_kerja=='SP')
                <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="100px" class="images">
                @elseif($data_cuti->User->kontrak_kerja=='SPS')
                <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="100px" class="images">
                @elseif($data_cuti->User->kontrak_kerja=='SIP')
                <img src="{{ url('public/holding/assets/img/logosip.png') }}" width="100px" class="images">
                @endif
            </td>
            <td>
                <h4 style="text-align: center; font-size: 16pt;">PT&nbsp;SURYA&nbsp;PANGAN&nbsp;SEMESTA</h4>
            </td>
            <td style="width: 40%; vertical-align: bottom; font-size:7pt; text-align: right;">
                @if($data_cuti->User->kontrak_kerja=='SP')
                @if($data_cuti->User->kontrak_site=='KEDIRI')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_cuti->User->kontrak_site=='TUBAN')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - TUBAN <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
                @elseif($data_cuti->User->kontrak_kerja=='SPS')
                @if($data_cuti->User->kontrak_site=='KEDIRI')

                <p>Jl. Dusun Bringin No.300, Bringin, Wonosari - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_cuti->User->kontrak_site=='NGAWI')
                <p>Jl. Raya Madiun-Ngawi KM No.13, Tambakromo - NGAWI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_cuti->User->kontrak_site=='SUBUANG')
                <p>Jl. Pusaka Jaya Kebondanas - SUBANG <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
                @elseif($data_cuti->User->kontrak_kerja=='SIP')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
            </td>
        </tr>
    </table>
    <hr style="margin-top: -5px; border: 1px solid black;">
    <h5 class="text-center" style="margin-top: -3%;"> FORMULIR<br>PERMINTAAN CUTI</h5>
    <table style="margin-top: 10px; font-size: 10pt; border-bottom: black;">
        <tbody>
            <tr>
                <th>Tanggal&nbsp;</th>
                <td>&nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_cuti->tanggal)->format('d-m-Y')}}&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 0%; font-size: 10pt;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="2" style="text-align: center;">DATA KARYAWAN</th>
        </thead>
        <tbody>
            <tr>
                <th>Nomor Induk Karyawan</th>
                <td>:&nbsp;{{$data_cuti->User->nomor_identitas_karyawan}}</td>
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
    <table style="margin-top: 5%; font-size: 10pt;" width="100%">
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
        <h6 style="text-align: left; padding-left: 3px;">
            Keterangan : <br>
            {{$data_cuti->keterangan_cuti}}&nbsp;
        </h6>
    </div>
    <h6>Pengesahan</h6>
    <table class="table table-bordered" style="margin-top: 2%; font-size: 10pt;" width="100%">
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
                    <p>{{$data_cuti->User->name}}<br>(Karyawan)</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_cuti->ttd_atasan.'.png') }}" width="100%" alt="">
                    <p style="margin-bottom: -10px;">{{$data_cuti->approve_atasan}}<br>(Atasan 1)</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_cuti->ttd_atasan2.'.png') }}" width="100%" alt="">
                    <p>{{$data_cuti->approve_atasan2}}<br>(Atasan 2)</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>