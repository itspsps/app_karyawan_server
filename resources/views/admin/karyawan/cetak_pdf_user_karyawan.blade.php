<!DOCTYPE html>
<html>

<head>
    <title>DATA USER KARYAWAN {{$holding}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}">
</head>

<body>
    <table border="0" style="margin-top: 0px;" class="kop" width="100%">
        <tr>
            @if($cek_holding=='sp')
            <td style="width:20%;"> <img src="{{ url('https://hrd.sumberpangan.store:4430/public/holding/assets/img/logosp.png') }}" width="80px" class="images"> </td>
            @elseif($cek_holding=='sps')
            <td style="width:20%;"> <img src="{{ url('https://hrd.sumberpangan.store:4430/public/holding/assets/img/logosps.png') }}" width="80px" class="images"> </td>
            @elseif($cek_holding=='sip')
            <td style="width:20%;"> <img src="{{ url('https://hrd.sumberpangan.store:4430/public/holding/assets/img/logosip.png') }}" width="80px" class="images"> </td>
            @endif
            <td style="width:40%;">
                @if($cek_holding=='sp')
                <h5 style="text-align: center;">CV.&nbsp;SUMBER&nbsp;PANGAN</h5>
                @elseif($cek_holding=='sps')
                <h5 style="text-align: center;">PT.&nbsp;SURYA&nbsp;PANGAN&nbsp;SEMESTA</h5>
                @elseif($cek_holding=='sip')
                <h5 style="text-align: center;">CV.&nbsp;SURYA&nbsp;INTI&nbsp;PANGAN</h5>
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
    <div class="text-center" style="margin-top: -2%;">
        <h6> DATA USER KARYAWAN <br>{{$holding}}</h6>
    </div>
    <table style="margin-top: 2%; font-size: 7pt;border: 1;" width="100%" class="table table-bordered" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>ID&nbsp;KARYAWAN</th>
                <th>NAMA.&nbsp;KARYAWAN</th>
                <th>USERNAME</th>
                <th>PASSWORD</th>
                <th>LEVEL</th>
                <th>STATUS</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $user)
            <tr>
                <td>{{$user->nomor_identitas_karyawan}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->username}}</td>
                <td>{{$user->password_show}}</td>
                <td>{{$user->is_admin}}</td>
                <td>{{$user->user_aktif}}</td>
                <td>@if($user->user_aktif=='AKTIF') AKTIF @elseif($user->use_aktif == 'NON AKTIF') {{$user->alasan}} @else @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>