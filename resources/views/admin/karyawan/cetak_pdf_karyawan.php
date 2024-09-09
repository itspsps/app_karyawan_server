<!DOCTYPE html>
<html>

<head>
    <title>DATA KARYAWAN {{$holding}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}">
</head>

<body>
    <table border="0" style="margin-top: 3px;" class="kop" width="100%">
        <tr>
            @if($cek_holding=='sp')
            <td style="width:20%;"> <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="80px" class="images"> </td>
            @elseif($cek_holding=='sps')
            <td style="width:20%;"> <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="80px" class="images"> </td>
            @elseif($cek_holding=='sip')
            <td style="width:20%;"> <img src="{{ url('public/holding/assets/img/logosip.png') }}" width="80px" class="images"> </td>
            @endif
            <td style="width:40%;">
                @if($cek_holding=='sp')
                <h4 style="text-align: center;">CV. SUMBER PANGAN</h4>
                @elseif($cek_holding=='sps')
                <h4 style="text-align: center;">PT. SURYA PANGAN SEMESTA</h4>
                @elseif($cek_holding=='sip')
                <h4 style="text-align: center;">CV. SURYA INTI PANGAN</h4>
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
    <table style="margin-top: 2%;" width="100%">
        <thead>
            <tr>
                <th>ID KARYAWAN</th>
                <th>NAMA</th>
                <th>NIK</th>
                <th>NPWP</th>
                <th>NAMA LENGKAP</th>
                <th>MOTTO</th>
                <th>EMAIL</th>
                <th>TELEPON</th>
                <th>USERNAME</th>
                <th>TEMPAT LAHIR</th>
                <th>TANGGAL LAHIR</th>
                <th>KELAMIN</th>
                <th>TANGGAL BERGABUNG</th>
                <th>STATUS PERNIKAHAAN</th>
                <th>PROVINSI</th>
                <th>KABUPATEN/KOTA</th>
                <th>KECAMATAN</th>
                <th>DESA</th>
                <th>RT</th>
                <th>RW</th>
                <th>KETERANGAN ALAMAT</th>
                <th>SALDO CUTI</th>
                <th>KATEGORI KARYAWAN</th>
                <th>LAMA KONTRAK</th>
                <th>TANGGAL MULAI KONTRAK</th>
                <th>TANGGAL SELESAI KONTRAK</th>
                <th>KONTRAK KERJA</th>
                <th>PENEMPATAN KERJA</th>
                <th>SITE JOB</th>
                <th>BANK</th>
                <th>NOMOR REKENING</th>
                <th>DEPARTEMEN</th>
                <th>DIVISI</th>
                <th>BAGIAN</th>
                <th>JABATAN</th>
                <th>DIVISI 2</th>
                <th>BAGIAN 2</th>
                <th>JABATAN 2</th>
                <th>DIVISI 3</th>
                <th>DIVISI 3</th>
                <th>JABATAN 3</th>
                <th>DIVISI 4</th>
                <th>DIVISI 4</th>
                <th>BAGIAN 4</th>
                <th>BAGIAN 5</th>
                <th>JABATAN 5</th>
                <th>JABATAN 5</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $user)
            <tr>
                <td>{{$user->nomor_identitas_karyawan}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->nik}}</td>
                <td>{{$user->npwp}}</td>
                <td>{{$user->fullname}}</td>
                <td>{{$user->motto}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->telepon}}</td>
                <td>{{$user->username}}</td>
                <td>{{$user->tempat_lahir}}</td>
                <td>{{$user->tgl_lahir}}</td>
                <td>{{$user->gender}}</td>
                <td>{{$user->tgl_join}}</td>
                <td>{{$user->status_nikah}}</td>
                <td>{{$user->nama_provinsi}}</td>
                <td>{{$user->nama_kabupaten}}</td>
                <td>{{$user->nama_kecamatan}}</td>
                <td>{{$user->nama_desa}}</td>
                <td>{{$user->rt}}</td>
                <td>{{$user->rw}}</td>
                <td>{{$user->alamat}}</td>
                <td>{{$user->kuota_cuti_tahunan}}</td>
                <td>{{$user->kategori}}</td>
                <td>{{$user->lama_kontrak_kerja}}</td>
                <td>{{$user->tgl_mulai_kontrak}}</td>
                <td>{{$user->tgl_selesai_kontrak}}</td>
                <td>{{$user->kontrak_kerja}}</td>
                <td>{{$user->penempatan_kerja}}</td>
                <td>{{$user->site_job}}</td>
                <td>{{$user->nama_bank}}</td>
                <td>{{$user->nomor_rekening}}</td>
                <td>{{$user->nama_departemen}}</td>
                <td>{{$user->nama_divisi}}</td>
                <td>{{$user->nama_bagian}}</td>
                <td>{{$user->nama_jabatan}}</td>
                <td>{{$user->nama_divisi1}}</td>
                <td>{{$user->nama_bagian1}}</td>
                <td>{{$user->nama_jabatan1}}</td>
                <td>{{$user->nama_divisi2}}</td>
                <td>{{$user->nama_bagian2}}</td>
                <td>{{$user->nama_jabatan2}}</td>
                <td>{{$user->nama_divisi3}}</td>
                <td>{{$user->nama_bagian3}}</td>
                <td>{{$user->nama_jabatan3}}</td>
                <td>{{$user->nama_divisi4}}</td>
                <td>{{$user->nama_bagian4}}</td>
                <td>{{$user->nama_jabatan4}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</html>