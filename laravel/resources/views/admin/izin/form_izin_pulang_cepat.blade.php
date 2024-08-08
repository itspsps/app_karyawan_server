<!DOCTYPE html>
<html>

<head>
    <title>FORM SURAT IZIN KELUAR/PULANG CEPAT</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}">
</head>


<body style="margin: 0px;">
    <table border="0" style="margin-top: -45px;" class="kop" width="100%">
        <tr>
            @if($data_izin->User->kontrak_kerja=='SP')
            <td style="width:10%;"> <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="80px" class="images"> </td>
            @elseif($data_izin->User->kontrak_kerja=='SPS')
            <td style="width:10%;"> <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="80px" class="images"> </td>
            @elseif($data_izin->User->kontrak_kerja=='SIP')
            <td style="width:10%;"> <img src="{{ url('public/holding/assets/img/logosip.png') }}" width="80px" class="images"> </td>
            @endif
            <td style="width:60%;">
                @if($data_izin->User->kontrak_kerja=='SP')
                <h4 style="text-align: center;">CV. SUMBER PANGAN</h4>
                @elseif($data_izin->User->kontrak_kerja=='SPS')
                <h4 style="text-align: center;">PT. SURYA PANGAN SEMESTA</h4>
                @elseif($data_izin->User->kontrak_kerja=='SIP')
                <h4 style="text-align: center;">CV. SURYA INTI PANGAN</h4>
                @endif
            </td>
            <td style="width: 30%; vertical-align: bottom; font-size:6pt; text-align: right;">
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
    <div style="margin-top: -10px; text-align: center;">
        <h6>SURAT IZIN <br> PULANG CEPAT</h6>
    </div>
    <table style="margin-top: 10px; border-bottom: black; font-size: 10pt;" width="100%">
        <tbody style="margin-top: 10%;">
            <td style="width:10%;">No&nbsp;</td>
            <td style="width:90%;">&nbsp;:&nbsp;<b>{{$data_izin->no_form_izin}}&nbsp;</b></td>
            </tr>
            <tr>
                <td>Tanggal&nbsp;</td>
                <td>&nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_izin->tanggal)->format('d-m-Y')}}&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 0%;font-size: 10pt;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="2" style="text-align: center;">DATA KARYAWAN</th>
        </thead>
        <tbody>
            <tr>
                <td style="width:30%;">Nomor Induk Karyawan</td>
                <td style="width:70%;">:&nbsp;{{$data_izin->User->nomor_identitas_karyawan}}</td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>:&nbsp;{{$data_izin->User->fullname}}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>:&nbsp;{{$departemen->nama_departemen}}</td>
            </tr>
            <tr>
                <td>Divisi</td>
                <td>:&nbsp;@foreach($divisi as $divisi){{$divisi->nama_divisi}} @endforeach</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:&nbsp;@foreach($jabatan as $jabatan){{$jabatan->nama_jabatan}} @endforeach</td>
            </tr>
        </tbody>
    </table>
    <table border="1" style="margin-top: 2%; font-size: 10pt;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th style="text-align: center;">Jam&nbsp;Keluar</th>
            <th style="text-align: center;">Jam Pulang</th>
            <th style="text-align: center;">Tujuan&nbsp;/&nbsp;Alasan</th>
        </thead>
        <tbody>
            <tr style="text-align: center;">
                <td>@if($data_izin->pulang_cepat=='') - @else{{\Carbon\Carbon::parse($data_izin->pulang_cepat)->format('H:i')}} WIB @endif</td>
                <td>@if($jam_kerja->Shift->jam_keluar=='') - @else{{\Carbon\Carbon::parse($jam_kerja->Shift->jam_keluar)->format('H:i')}} WIB @endif</td>
                <td>@if($data_izin->keterangan_izin=='') - @else{{$data_izin->keterangan_izin}}@endif</td>
            </tr>
        </tbody>
    </table>
    <table style="bottom: 20%; position: absolute; font-size: 10pt;" width="100%">
        <thead>
            <tr style="text-align: center;">
                <td>Pemohon</td>
                <td>Mengetahui</td>
                <td>Menyutujui</th>
            </tr>
        </thead>
        <tbody>
            <tr style="font-weight: bold;">
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_izin->ttd_pengajuan.'.png') }}" width="100%" alt="">
                    <p>({{$data_izin->fullname}})</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_izin->ttd_atasan.'.png') }}" width="100%" alt="">
                    <p>({{$data_izin->approve_atasan}})</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://karyawan.sumberpangan.store/laravel/public/signature/'.$data_izin->ttd_atasan.'.png') }}" width="100%" alt="">
                    <p>({{$data_izin->approve_atasan}})</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>