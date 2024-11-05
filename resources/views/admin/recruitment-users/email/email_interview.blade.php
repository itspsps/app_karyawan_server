<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Invitation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Interview Invitation</h3>
            </div>
            <div class="card-body">
                <p>Kepada {{ $user->nama_depan }} {{ $user->nama_tengah }} {{ $user->nama_belakang }},</p>

                <p>Kami dengan senang hati mengundang Anda untuk wawancara di posisi
                    <strong> {{ $user->Bagian->nama_bagian }} </strong>
                    pada perusahaan
                    <strong>
                        @if($user->holding == 'sp')
                            CV. Sumber Pangan
                        @elseif($user->holding == 'sip')
                            CV. Surya Inti Pangan
                        @elseif($user->holding == 'sps')
                            PT. Surya Pangan Semesta
                        @else
                            -
                        @endif
                    </strong>.
                </p>

                <p><strong>Detail Interview:</strong></p>
                <ul>
                    <li><strong>Tanggal:</strong> {{ $tanggal_interview }} </li>
                    <li><strong>jam:</strong> {{ $jam_interview }}</li>
                    <li><strong>Lokasi:</strong>{{ $lokasi_interview }}</li>
                </ul>
                <p>Harap konfirmasi ketersediaan Anda dengan
                    <a href="{{ url('konfirmasi-interview/'.$user->email.'/tidak-datang') }}" class="btn rounded-pill btn-info waves-effect waves-light">Tidak Datang</a>
                </p>
                <p>Harap konfirmasi ketersediaan Anda dengan
                    <a href="{{ url('konfirmasi-interview/'.$user->email.'/datang') }}" class="btn rounded-pill btn-primary waves-effect waves-light">Datang</a>
                </p>
                <p>Kami berharap dapat bertemu dengan Anda!!</p>
                <p>Salam,</p>
                <p><strong>HRD</strong></p>
            </div>
            <div class="card-footer text-center">
                <small>&copy; {{ date('Y') }}
                    @if($user->holding == 'sp')
                        CV. Sumber Pangan
                    @elseif($user->holding == 'sip')
                        CV. Surya Inti Pangan
                    @elseif($user->holding == 'sps')
                        PT. Surya Pangan Semesta
                    @else
                        -
                    @endif
                    . All rights reserved.</small>
            </div>
        </div>
    </div>
</body>
</html>
