<!DOCTYPE html>
<html>

<head>
    <title>FORM PERMINTAAN CUTI</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="{{asset('public/kpi/bower_components/font-awesome/css/font-awesome.min.css')}}"> -->
</head>


<body>
    <div style="float: right; margin-top: -30px; margin-right: -10px; width:250px; height: auto;border: 1px solid black; box-sizing: border-box;">
        <h5 style="text-align: right;">
            {{$data_cuti->no_form_cuti}}&nbsp;
        </h5>
    </div>
    <table border="0" style="margin-top: 3px;" class="kop" width="100%">
        <tr>
            @if($data_cuti->User->kontrak_kerja=='SP')
            <td style="width:25%;"> <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="100px" class="images"> </td>
            @elseif($data_cuti->User->kontrak_kerja=='SPS')
            <td style="width:25%;"> <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="100px" class="images"> </td>
            @else
            <td style="width:25%;"> <img src="{{ url('public/holding/assets/img/logosip.png') }}" width="100px" class="images"> </td>
            @endif
            <td>
                <h4 style="text-align: center;">FORMULIR<br>PERMINTAAN CUTI</h4>
            </td>
            <td style="width: 40px;">
            </td>
            <td>
            </td>
        </tr>
    </table>

</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

</html>