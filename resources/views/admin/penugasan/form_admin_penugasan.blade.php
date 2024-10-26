<!DOCTYPE html>
<html>

<head>
    <title>FORM PERMINTAAN PERJALANAN DINAS</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}">
</head>


<body>
    <div style="float: right; margin-top: -31px; margin-right: -10px; width:200px; height: auto;border: 1px solid black; box-sizing: border-box;">
        <h5 style="text-align: right; font-size: 11pt;">
            {{$data_penugasan->no_form_penugasan}}&nbsp;
        </h5>
    </div>
    <table border="0" style="margin-top: -5%; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;" class="kop" width="100%">
        <tr>
            <td style="width:20%;">
                @if($data_penugasan->User->kontrak_kerja=='SP')
                <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="100px" class="images">
                @elseif($data_penugasan->User->kontrak_kerja=='SPS')
                <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="100px" class="images">
                @elseif($data_penugasan->User->kontrak_kerja=='SIP')
                <img src="{{ url('public/holding/assets/img/logosip.png') }}" width="100px" class="images">
                @endif
            </td>
            <td>
                @if($data_penugasan->User->kontrak_kerja=='SP')
                <h4 style="text-align: center; font-size: 20pt;">CV&nbsp;SUMBER&nbsp;PANGAN</h4>
                @elseif($data_penugasan->User->kontrak_kerja=='SPS')
                <h4 style="text-align: center; font-size: 20pt;">PT&nbsp;SURYA&nbsp;PANGAN&nbsp;SEMESTA</h4>
                @elseif($data_penugasan->User->kontrak_kerja=='SIP')
                <h4 style="text-align: center; font-size: 20pt;">CV&nbsp;SURYA&nbsp;INTI&nbsp;PANGAN</h4>
                @endif
            </td>
            <td style="width: 40%; vertical-align: bottom; font-size:7pt; text-align: right;">
                @if($data_penugasan->User->kontrak_kerja=='SP')
                @if($data_penugasan->User->kontrak_site=='KEDIRI')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_penugasan->User->kontrak_site=='TUBAN')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - TUBAN <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
                @elseif($data_penugasan->User->kontrak_kerja=='SPS')
                @if($data_penugasan->User->kontrak_site=='KEDIRI')

                <p>Jl. Dusun Bringin No.300, Bringin, Wonosari - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_penugasan->User->kontrak_site=='NGAWI')
                <p>Jl. Raya Madiun-Ngawi KM No.13, Tambakromo - NGAWI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($data_penugasan->User->kontrak_site=='SUBUANG')
                <p>Jl. Pusaka Jaya Kebondanas - SUBANG <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @endif
                @elseif($data_penugasan->User->kontrak_kerja=='SIP')
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
    <h6 class="text-center" style="margin-top: -3%;"> FORMULIR<br>PERMINTAAN PERJALANAN DINAS</h6>
    <table style="margin-top: -1%; font-size: 10pt; border-bottom: black;">
        <tbody>
            <tr>
                <th>Tanggal&nbsp;</th>
                <td>&nbsp;:&nbsp;{{ \Carbon\Carbon::parse($data_penugasan->tanggal)->format('d-m-Y')}}&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 0%; font-size: 10pt; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="4" style="text-align: center;">DATA KARYAWAN</th>
        </thead>
        <thead style="background-color:#E6E6FA; text-align: center;">
            <th colspan="2">PERMINTAAN</th>
            <th colspan="2">MENYETUJUI</th>
        </thead>
        <tbody>
            <tr>
                <th>Nama</th>
                <td>:&nbsp;{{$data_penugasan->nama_diminta}}</td>
                <th>Nama</th>
                <td>:&nbsp;{{$data_penugasan->nama_disahkan}}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>:&nbsp;{{$departemen1->nama_departemen}}</td>
                <th>Departemen</th>
                <td>:&nbsp;{{$departemen2->nama_departemen}}</td>
            </tr>
            <tr>
                <th>Divisi</th>
                <td>:&nbsp;{{$divisi1->nama_divisi}}</td>
                <th>Divisi</th>
                <td>:&nbsp;{{$divisi2->nama_divisi}}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>:&nbsp;{{$jabatan1->nama_jabatan}}</td>
                <th>Jabatan</th>
                <td>:&nbsp;{{$jabatan2->nama_jabatan}}</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 0%; font-size: 10pt; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;" width="100%">
        <thead style="background-color:#E6E6FA;">
            <th colspan="4" style="text-align: center;">DATA KARYAWAN</th>
        </thead>
        <tbody>
            <tr>
                <th>Nama</th>
                <td>:&nbsp;{{$data_penugasan->nama_diajukan}}</td>
                <th>Lokasi Kerja</th>
                <td>:&nbsp;{{$data_penugasan->User->penempatan_kerja}}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>:&nbsp;{{$departemen->nama_departemen}}</td>
                <th>Telepon</th>
                <td>:&nbsp;{{$data_penugasan->User->telepon}}</td>
            </tr>
            <tr>
                <th>Divisi</th>
                <td>:&nbsp;{{$divisi->nama_divisi}}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>:&nbsp;{{$jabatan->nama_jabatan}}</td>
            </tr>
        </tbody>
    </table>
    <div class="row">
        <div class="col-6">
            <table style="margin-top: 0%; font-size: 10pt; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                <thead style="background-color:#E6E6FA;">
                    <th style="text-align: center;">Kegiatan</th>
                    <th style="text-align: center;">PIC Dikunjungi</th>
                    <th style="text-align: center;">Alamat</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{$data_penugasan->kegiatan_penugasan}}
                        </td>
                        <td>{{$data_penugasan->pic_dikunjungi}}</td>
                        <td>{{$data_penugasan->alamat_dikunjungi}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <table style="margin-top: 0%; font-size: 10pt; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                <thead>
                    <th colspan="2" style="text-align: left;">Transportasi:</th>
                    <th colspan="2" style="text-align: left;">Kelas:</th>
                    <th style="text-align: left;">Budget/Hotel:</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            1. Pesawat
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->transportasi =='Pesawat' ? 'checked' : ''}}>
                        </td>
                        <td>
                            Eksekutif
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->kelas =='Eksekutif' ? 'checked' : ''}}>
                        </td>
                        <td>
                            1. Rp. 400.000 s/d Rp. 500.000
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->budget_hotel =='400.000 sd 500.000' ? 'checked' : ''}}>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2. Kereta Api
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->transportasi =='Kereta Api' ? 'checked' : ''}}>
                        </td>
                        <td>
                            Bisnis
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->kelas =='Bisnis' ? 'checked' : ''}}>
                        </td>
                        <td>
                            2. Rp. 300.000 s/d Rp. 400.000
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->budget_hotel =='300.000 sd 400.000' ? 'checked' : ''}}>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3. Bis
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->transportasi =='Bis' ? 'checked' : ''}}>
                        </td>
                        <td>
                            Ekonomi
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->kelas =='Ekonomi' ? 'checked' : ''}}>
                        </td>
                        <td>
                            3. Rp. 200.000 s/d Rp. 300.000
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->budget_hotel =='200.000 sd 300.000' ? 'checked' : ''}}>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4. Travel
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->transportasi =='Travel' ? 'checked' : ''}}>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            4. Kost Harian < 200.000 </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->budget_hotel =='Kost Harian < 200.000' ? 'checked' : ''}}>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5. SPD Motor
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->transportasi =='SPD Motor' ? 'checked' : ''}}>
                        </td>
                        <td>
                            5. Tidak Ada </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->budget_hotel =='0' ? 'checked' : ''}}>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            6. Mobil Dinas
                        </td>
                        <td>
                            <input type="checkbox" value="" {{$data_penugasan->transportasi =='Mobil Dinas' ? 'checked' : ''}}>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="float: left; margin-top: 15px; margin-bottom:5%; width:100%; height: 100px;border: 1px solid #E6E6FA; box-sizing: border-box; border-radius: 7px;">
        <h6 style="text-align: left; padding-left: 3px;">
            Keterangan : <br>
            {{$data_penugasan->keterangan_cuti}}&nbsp;
        </h6>
    </div>
    <h6>Pengesahan</h6>
    <table class="table table-bordered" style="margin-top: 2%; font-size: 10pt; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;" width="100%">
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
                    <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$data_penugasan->ttd_user.'.png') }}" width="100%" alt="">
                    <p>{{$data_penugasan->User->name}}<br>(Karyawan)</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$data_penugasan->ttd_atasan.'.png') }}" width="100%" alt="">
                    <p style="margin-bottom: -10px;">{{$data_penugasan->approve_atasan}}<br>(Atasan 1)</p>
                </td>
                <td style="text-align: center;">
                    <img src="{{ url('https://hrd.sumberpangan.store:4430/public/signature/penugasan/'.$data_penugasan->ttd_atasan2.'.png') }}" width="100%" alt="">
                    <p>{{$data_penugasan->approve_atasan2}}<br>(Atasan 2)</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>