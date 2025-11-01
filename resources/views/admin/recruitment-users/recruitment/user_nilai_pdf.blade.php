<!DOCTYPE html>
<html>

<head>
    <title>Nilai Pelamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 0px solid rgba(95, 94, 94, 0.522);
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }

        th,
        td {
            padding: 10px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }

        th {
            background-color: #767776;
            color: white;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }
    </style>
</head>

<body>
    <h2>HASIL TES DAN WAWANCARA</h2>
    <div class="table table-striped">
        <table border="1" class="table" id="table_pelamar3" style="width: 100%; font-size: small;">
            <thead class="table-primary">
                <tr>
                    <th class="fw-bold">JENIS TES</th>
                    <th></th>
                    <th class="text-center">NILAI</th>
                    <th class="text-center">BOBOT NILAI (%)</th>
                    <th class="text-center">KOEFISIEN NILAI</th>
                </tr>

            </thead>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td class="fw-bold"><small>PILIHAN GANDA</small></td>
                    <td>:</td>
                    <td class="text-center">{{ $pg_total }}</td>
                    <td class="text-center">{{ $pembobotan->pilihan_ganda }}%</td>
                    <td class="text-center">{{ $koefisien_pg }}</td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>ESAI</small></td>
                    <td>:</td>
                    <td class="text-center">{{ $esai_total }}</td>
                    <td class="text-center">{{ $pembobotan->esai }}%</td>
                    <td class="text-center">{{ $koefisien_esai }}</td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>WAWANCARA</small></td>
                    <td>:</td>
                    <td class="text-center">{{ $hasil_interview }}</td>
                    <td class="text-center">{{ $pembobotan->interview }}%</td>
                    <td class="text-center">{{ $koefisien_interview }}</td>
                </tr>
            </tbody>
            <thead class="table-primary">
                <tr>
                    <th class="fw-bold">TOTAL</th>
                    <th></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center">{{ $koefisien_total }}</th>
                </tr>

            </thead>
        </table>
    </div>
</body>

</html>
