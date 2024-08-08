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
            {{$data_izin->no_form_izin}}&nbsp;
        </h5>
    </div>
    <table border="0" style="margin-top: 3px;" class="kop" width="100%">
        <tr>
            @if($data_izin->User->kontrak_kerja=='SP')
            <td style="width:20%;"> <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="80px" class="images"> </td>
            @elseif($data_izin->User->kontrak_kerja=='SPS')
            <td style="width:20%;"> <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="80px" class="images"> </td>
            @elseif($data_izin->User->kontrak_kerja=='SIP')
            <td style="width:20%;"> <img src="{{ url('public/holding/assets/img/logosip.png') }}" width="80px" class="images"> </td>
            @endif
            <td style="width:40%;">
                @if($data_izin->User->kontrak_kerja=='SP')
                <h4 style="text-align: center;">CV. SUMBER PANGAN</h4>
                @elseif($data_izin->User->kontrak_kerja=='SPS')
                <h4 style="text-align: center;">PT. SURYA PANGAN SEMESTA</h4>
                @elseif($data_izin->User->kontrak_kerja=='SIP')
                <h4 style="text-align: center;">CV. SURYA INTI PANGAN</h4>
                @endif
            </td>
            <td style="width: 40%; vertical-align: bottom; font-size:7pt; text-align: right;">
                @if($data_izin->User->kontrak_kerja=='SP')
                @if($data_izin->User->kontrak_site=='KEDIRI')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_izin->User->kontrak_site=='TUBAN')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - TUBAN <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
                @elseif($data_izin->User->kontrak_kerja=='SPS')
                @if($data_izin->User->kontrak_site=='KEDIRI')

                <p>Jl. Dusun Bringin No.300, Bringin, Wonosari - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_izin->User->kontrak_site=='NGAWI')
                <p>Jl. Raya Madiun-Ngawi KM No.13, Tambakromo - NGAWI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_izin->User->kontrak_site=='SUBUANG')
                <p>Jl. Pusaka Jaya Kebondanas - SUBANG <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
                @elseif($data_izin->User->kontrak_kerja=='SIP')
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
    <h5 class="text-center" style="margin-top: -3%;"> FORMULIR PERMOHONAN<br>IJIN TIDAK MASUK</h5>
    <table style="margin-top: 2%;" width="100%">
        <tbody>
            <tr>
                <th>NIK</th>
                <td>:&nbsp;{{$data_izin->nomor_identitas_karyawan}}</td>
                <th>Divisi</th>
                <td>:&nbsp;@foreach($divisi as $divisi){{$divisi->nama_divisi}} @endforeach</td>
            </tr>
            <tr style="width: 50%;">
                <th>Nama</th>
                <td>:&nbsp;{{$data_izin->User->fullname}}</td>
                <th>Jabatan</th>
                <td>:&nbsp;@foreach($jabatan as $jabatan){{$jabatan->nama_jabatan}} @endforeach</td>
            </tr>
            <tr>
                <th>Kelamin</th>
                <td>:&nbsp;{{$data_izin->User->gender}}</td>
                <th>Lokasi Kerja</th>
                <td>:&nbsp;{{$data_izin->User->penempatan_kerja}}</td>

        </tbody>
    </table>
    <table border="1" style="margin-top: 1%;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <tr>
                <th colspan="3" style="text-align: center;">TANGGAL</th>
                <th colspan="1" style="text-align: center;">HARI</th>
            </tr>
            <tr>
                <th colspan="1" style="text-align: center;">PENGAJUAN</th>
                <th colspan="1" style="text-align: center;">DILAKSANAKAN</th>
                <th colspan="1" style="text-align: center;">DISETUJUI</th>
                <th colspan="1" style="text-align: center;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center;">
                <td>{{ \Carbon\Carbon::parse($data_izin->waktu_ttd_pengajuan)->isoFormat('D MMMM YYYY')}}</td>
                <td>{{ \Carbon\Carbon::parse($data_izin->tanggal)->isoFormat('D MMMM YYYY')}}&nbsp;s/d&nbsp;{{ \Carbon\Carbon::parse($data_izin->tanggal_selesai)->isoFormat('D MMMM YYYY')}}</td>
                <td>{{ \Carbon\Carbon::parse($data_izin->waktu_ttd_pengajuan)->isoFormat('D MMMM YYYY')}}</td>
                <td>{{$data_interval}}</td>
            </tr>
        </tbody>
    </table>
    <div style="float: left; margin-top: 15px; width:100%; height: 100px;border: 1px solid #E6E6FA; box-sizing: border-box; border-radius: 7px;">
        <h5 style="text-align: left; padding-left: 3px;">
            Alasan Tidak Masuk : <br>
            {{$data_izin->keterangan_izin}}&nbsp;
        </h5>
    </div>
    <table style="margin-top: 20%;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="2" style="text-align: center;">SERAH TERIMA TUGAS SELAMA TIDAK MASUK</th>
        </thead>
        <tbody>
            <tr>
                <th>Kepada</th>
                <td>:&nbsp;{{$data_izin->user_name_backup}}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>:&nbsp;{{ \Carbon\Carbon::parse($data_izin->waktu_ttd_pengajuan)->isoFormat('D MMMM YYYY')}}</td>
            </tr>
            <tr>
                <th>Telepon </th>
                <td>:&nbsp;{{$user_backup->telepon}}</td>
            </tr>
        </tbody>
    </table>
    <div style="float: left; margin-top: 5%; margin-bottom: 10%; width:100%; height: 100px;border: 1px solid #E6E6FA; box-sizing: border-box; border-radius: 7px;">
        <h5 style="text-align: left; padding-left: 3px;">
            Catatan Selama Meninggalkan Lokasi Kerja : <br>
            {{$data_izin->catatan_backup}}&nbsp;
        </h5>
    </div>
    <h5></h5>
    <table class="table table-bordered" style="margin-top:25%;" width="100%">
        <thead>
            <tr style="text-align: center;">
                <th>Pemohon</th>
                <th>Atasan Langsung</th>
                <th>Menyetujui</th>
            </tr>
        </thead>
        <tbody>
            <tr style="font-weight: bold;">
                <td>
                    <img style="text-align: center;" src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_izin->ttd_pengajuan.'.png') }}" width="100%" alt="">
                </td>
                <td>
                    <img style="text-align: center;" src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_izin->ttd_atasan.'.png') }}" width="100%" alt="">
                </td>
                <td>
                    <img style="text-align: center;" src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_izin->ttd_atasan.'.png') }}" width="100%" alt="">
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>
                    <p style="text-align: left; font-size: 12px; margin-bottom: -2%;">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{Auth::user()->name}}</p>
                    <p style="text-align: left; font-size: 12px;">Tanggal &nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_izin->waktu_ttd_pengajuan)->isoFormat('D MMMM YYYY')}}</p>
                </th>
                <th>
                    <p style="text-align: left; font-size: 12px; margin-bottom: -2%;">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{$data_izin->approve_atasan}}</p>
                    <p style="text-align: left; font-size: 12px;">Tanggal &nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_izin->waktu_approve)->isoFormat('D MMMM YYYY')}}</p>
                </th>
                <th>
                    <p style="text-align: left; font-size: 12px; margin-bottom: -2%;">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{$data_izin->approve_atasan}}</p>
                    <p style="text-align: left; font-size: 12px;">Tanggal &nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_izin->waktu_approve)->isoFormat('D MMMM YYYY')}}</p>

                </th>
            </tr>
        </tfoot>
    </table>
</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>