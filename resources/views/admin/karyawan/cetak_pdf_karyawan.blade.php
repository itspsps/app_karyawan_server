<!DOCTYPE html>
<html>

<head>
    <title>DATA KARYAWAN {{$holding}}</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}"> -->
</head>

<body>
    <table border="0" style="margin-top: 3px;" class="kop" width="100%">
        <tr>
            @if($cek_holding=='sp')
            <td style="width:20%;"> <img src="{{ asset('holding/assets/img/logosp.png') }}" width="80px" class="images"> </td>
            @elseif($cek_holding=='sps')
            <td style="width:20%;"> <img src="{{ asset('holding/assets/img/logosps.png') }}" width="80px" class="images"> </td>
            @elseif($cek_holding=='sip')
            <td style="width:20%;"> <img src="{{ asset('holding/assets/img/logosip.png') }}" width="80px" class="images"> </td>
            @endif
            <td style="width:40%;">
                @if($cek_holding=='sp')
                <h3 style="text-align: center;">CV. SUMBER PANGAN</h3>
                @elseif($cek_holding=='sps')
                <h3 style="text-align: center;">PT. SURYA PANGAN SEMESTA</h3>
                @elseif($cek_holding=='sip')
                <h3 style="text-align: center;">CV. SURYA INTI PANGAN</h3>
                @endif
            </td>
            <td style="width: 40%; vertical-align: bottom; font-size:7pt; text-align: right;">
                @if($cek_holding=='sp')
                <p>Jl. Raya Sambirobyong No.88 Kayen Kidul - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($cek_holding=='sps')
                <p>Jl. Dusun Bringin No.300, Bringin, Wonosari - KEDIRI <br>
                    Telp: 0354-548466, 0354-546859, Fax: 0354548465 <br>
                    Website:
                    <a href="www.beraskediri.com">
                        www.beraskediri.com
                    </a>
                </p>
                @elseif($cek_holding=='sip')
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
    <h5 class="text-center" style="margin-top: -3%;"> DATA KARYAWAN <br>{{$holding}}</h5>
    <table style="margin-top: 2%; font-size: 5pt;" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>NAMA&nbsp;LENGKAP</th>
                <th>TELEPON</th>
                <th>TTL</th>
                <th>KELAMIN</th>
                <th>TGL&nbsp;GABUNG</th>
                <th>STATUS</th>
                <th>ALAMAT&nbsp;</th>
                <th>KATEGORI</th>
                <th>LAMA&nbsp;KONTRAK</th>
                <th>TGL&nbsp;KONTRAK</th>
                <th>PENEMPATAN&nbsp;KERJA</th>
                <th>BANK</th>
                <th>NO.&nbsp;REKENING</th>
                <th>DEPARTEMEN</th>
                <th>DIVISI</th>
                <th>BAGIAN</th>
                <th>JABATAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $user)
            <tr>
                <td>{{$user->nomor_identitas_karyawan}}</td>
                <td>{{$user->nik}}</td>
                <td>{{$user->npwp}}</td>
                <td>{{$user->fullname}}</td>
                <td>{{$user->telepon}}</td>
                <td>{{$user->tempat_lahir}}, {{$user->tgl_lahir}}</td>
                <td>{{$user->gender}}</td>
                <td>{{$user->tgl_join}}</td>
                <td>{{$user->status_nikah}}</td>
                <td>{{$user->alamat}}</td>
                <td>{{$user->kategori}}</td>
                <td>{{$user->lama_kontrak_kerja}}</td>
                <td>{{$user->tgl_mulai_kontrak}} - {{$user->tgl_selesai_kontrak}}</td>
                <td>{{$user->penempatan_kerja}}</td>
                <td>{{$user->nama_bank}}</td>
                <td>{{$user->nomor_rekening}}</td>
                <td>@if($user->Departemen==null)@else{{$user->Departemen->nama_departemen}}@endif</td>
                <td>@if($user->Divisi==null)@else{{$user->Divisi->nama_divisi}}@endif</td>
                <td>@if($user->Bagian==null)@else{{$user->Bagian->nama_bagian}}@endif</td>
                <td>@if($user->Jabatan==null)@else{{$user->Jabatan->nama_jabatan}}@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>