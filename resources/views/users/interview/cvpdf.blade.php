<!DOCTYPE html>
<html>

<head>
    <title>CV Cetak PDF</title>
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
    <h2>DETAIL CV</h2>
    <img src="{{ url_karir() . '/storage/file_pp/' . $data_cv->AuthLogin->recruitmentCV->file_pp }}"
        style="max-height: 400px; max-width: 340px;">
    <div class="fw-bold py-3">Email : {{ $data_cv->AuthLogin->email }}</div>
    <div class="table table-striped">
        <table border="1" class="table" id="table_pelamar3" style="width: 100%;">
            <tbody class="table-primary">
                <tr>
                    <th class="fw-bold">PROFIL</th>
                    <th></th>
                    <th></th>
                </tr>

            </tbody>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td class="fw-bold"><small>NAMA LENGKAP</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>TEMPAT, TANGGAL LAHIR</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->tempat_lahir }},
                        {{ $data_cv->AuthLogin->recruitmentCV->tanggal_lahir }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>NIK</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->nik }}</td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>AGAMA</small></td>
                    <td>:</td>
                    <td>
                        @if ($data_cv->AuthLogin->recruitmentCV->agama == '1')
                        ISLAM
                        @elseif ($data_cv->AuthLogin->recruitmentCV->agama == '2')
                        KRISTEN PROTESTAN
                        @elseif ($data_cv->AuthLogin->recruitmentCV->agama == '3')
                        KRISTEN KATOLIK
                        @elseif ($data_cv->AuthLogin->recruitmentCV->agama == '4')
                        HINDU
                        @elseif ($data_cv->AuthLogin->recruitmentCV->agama == '5')
                        BUDHA
                        @elseif ($data_cv->AuthLogin->recruitmentCV->agama == '6')
                        KONGHUCHU
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>JENIS KELAMIN</small></td>
                    <td>:</td>
                    <td>
                        @if ($data_cv->AuthLogin->recruitmentCV->jenis_kelamin == '1')
                        LAKI - LAKI
                        @else
                        PEREMPUAN
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>STATUS PERNIKAHAN</small></td>
                    <td>:</td>
                    <td>
                        @if ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'lajang')
                        LAJANG
                        @elseif ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'menikah')
                        MENIKAH
                        @elseif ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'cerai_hidup')
                        CERAI HIDUP
                        @elseif ($data_cv->AuthLogin->recruitmentCV->status_pernikahan == 'cerai_mati')
                        CERAI MATI
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>JUMLAH ANAK</small></td>
                    <td>:</td>
                    <td>
                        {{ $data_cv->AuthLogin->recruitmentCV->jumlah_anak }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>HOBI</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->hobi }}</td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>KTP</small></td>
                    <td>:</td>
                    <td>
                        <img src="{{ url_karir() . '/storage/ktp/' . $data_cv->AuthLogin->recruitmentCV->ktp }}"
                            style="max-height: 250px; max-width: 350px;">
                    </td>
                    </td>
                </tr>
            </tbody>
            <tbody class="table-primary">
                <tr>
                    <th class="fw-bold">ALAMAT</th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td class="fw-bold"><small>ALAMAT SESUAI KTP</small></td>
                    <td>:</td>
                    <td>
                        {{ $data_cv->AuthLogin->recruitmentCV->nama_jalan_ktp }},
                        RT {{ $data_cv->AuthLogin->recruitmentCV->rt_ktp }},
                        RW {{ $data_cv->AuthLogin->recruitmentCV->rw_ktp }},
                        {{ $data_cv->AuthLogin->recruitmentCV->desaKTP->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->kecamatanKTP->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->kabupatenKTP->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->provinsiKTP->name }},
                        KODE POS :
                        {{ $data_cv->AuthLogin->recruitmentCV->kode_pos_ktp }}

                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>ALAMAT SAAT INI</small></td>
                    <td>:</td>
                    <td>
                        @if ($data_cv->AuthLogin->recruitmentCV->alamat_sekarang == 'sama')
                        {{ $data_cv->AuthLogin->recruitmentCV->nama_jalan_ktp }},
                        RT {{ $data_cv->AuthLogin->recruitmentCV->rt_ktp }},
                        RW {{ $data_cv->AuthLogin->recruitmentCV->rw_ktp }},
                        {{ $data_cv->AuthLogin->recruitmentCV->desaKTP->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->kecamatanKTP->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->kabupatenKTP->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->provinsiKTP->name }},
                        KODE POS :
                        {{ $data_cv->AuthLogin->recruitmentCV->kode_pos_ktp }}
                        @else
                        {{ $data_cv->AuthLogin->recruitmentCV->nama_jalan_now }},
                        RT {{ $data_cv->AuthLogin->recruitmentCV->rt_now }},
                        RW {{ $data_cv->AuthLogin->recruitmentCV->rw_now }},
                        {{ $data_cv->AuthLogin->recruitmentCV->desaNOW->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->kecamatanNOW->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->kabupatenNOW->name }},
                        {{ $data_cv->AuthLogin->recruitmentCV->provinsiNOW->name }},
                        KODE POS :
                        {{ $data_cv->AuthLogin->recruitmentCV->kode_pos_now }}
                        @endif
                    </td>
                </tr>
            </tbody>
            <tbody class="table-primary">
                <tr>
                    <th class="fw-bold">KONTAK</th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td class="fw-bold"><small>NOMOR WHATSAPP</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->nomor_whatsapp }}</td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>LAMA NOMOR INI DIGUNAKAN</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->lama_nomor_whatsapp }}
                        TAHUN
                        @if ($data_cv->AuthLogin->recruitmentCV->lama_nomor_bulan == null)
                        -
                        @else
                        {{ $data_cv->AuthLogin->recruitmentCV->lama_nomor_bulan }}
                        BULAN
                        @endif

                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>KONTAK DARURAT</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->nomor_whatsapp_darurat }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold"><small>PEMILIK KONTAK DARURAT</small></td>
                    <td>:</td>
                    <td>{{ $data_cv->AuthLogin->recruitmentCV->pemilik_nomor_whatsapp }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <h2>RIWAYAT KESEHATAN</h2>

    <table class="table" id="table_pelamar3" style="width: 100%;">
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">PERSETUJUAN</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="fw-bold"><small>PERSETUJUAN MENGISI DATA DENGAN
                        JUJUR</small>
                </td>
                <td>:</td>
                <td>
                    @if ($kesehatan->persetujuan_kesehatan == 'on')
                    SETUJU
                    @elseif ($kesehatan->persetujuan_kesehatan == null)
                    TIDAK SETUJU
                    @endif
                </td>
            </tr>
        </tbody>
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT KESEHATAN</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody class="table-border-bottom-0">
            <tr>
                <td class="fw-bold"><small>PEROKOK</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->perokok == '1')
                    YA
                    @elseif ($kesehatan->perokok == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>PENGKONSUMSI ALKOHOL</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->alkohol == '1')
                    YA
                    @elseif ($kesehatan->alkohol == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>PUNYA PHOBIA</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->phobia == '1')
                    YA
                    @elseif ($kesehatan->phobia == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>JENIS PHOBIA</small></td>
                <td>:</td>
                <td>
                    {{ $kesehatan->sebutkan_phobia }}
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>PUNYA KETERBATASAN FISIK</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->keterbatasan_fisik == '1')
                    YA
                    @elseif ($kesehatan->keterbatasan_fisik == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>KATEGORI KETERBATASAN FISIK</small></td>
                <td>:</td>
                <td>
                    {{ $kesehatan->sebutkan_keterbatasan_fisik }}
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>SEDANG DALAM PENGOBATAN RUTIN</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->pengobatan_rutin == '1')
                    YA
                    @elseif ($kesehatan->pengobatan_rutin == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
        </tbody>
        @if ($kesehatan->pengobatan_rutin == '1')
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT PENGOBATAN RUTIN</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            @php
            $n = 1;
            @endphp
            @foreach ($kesehatan_pengobatan as $rr)
            <tr>
                <td class="fw-bold"><small>{{ $n++ }}. JENIS
                        OBAT</small>
                </td>
                <td>:</td>
                <td>{{ $rr->jenis_obat }}</td>
                <td class="fw-bold"><small>ALASAN</small></td>
                <td>:</td>
                <td>{{ $rr->alasan_obat }}</td>
            </tr>
            @endforeach
        </tbody>
        @endif

        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">PERNAH ATAU SEDANG MENDERITA PENYAKIT KRONIS</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="fw-bold"><small>ASMA</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->asma == 'on')
                    YA
                    @elseif ($kesehatan->asma == null)
                    TIDAK
                    @endif
                </td>
                <td class="fw-bold"><small>DIABETES</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->diabetes == 'on')
                    YA
                    @elseif ($kesehatan->diabetes == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>HIPERTENSI</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->hipertensi == 'on')
                    YA
                    @elseif ($kesehatan->hipertensi == null)
                    TIDAK
                    @endif
                </td>
                <td class="fw-bold"><small>JANTUNG</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->jantung == 'on')
                    YA
                    @elseif ($kesehatan->jantung == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>TBC</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->jantung == 'on')
                    YA
                    @elseif ($kesehatan->jantung == null)
                    TIDAK
                    @endif
                </td>
                <td class="fw-bold"><small>HEPATITIS</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->hepatitis == 'on')
                    YA
                    @elseif ($kesehatan->hepatitis == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>EPILEPSI</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->gangguan_mental == 'on')
                    YA
                    @elseif ($kesehatan->gangguan_mental == null)
                    TIDAK
                    @endif
                </td>
                <td class="fw-bold"><small>GANGGUAN MENTAL</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->gangguan_mental == 'on')
                    YA
                    @elseif ($kesehatan->gangguan_mental == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>GANGGUAN PENGELIHATAN</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->gangguan_pengelihatan == 'on')
                    YA
                    @elseif ($kesehatan->gangguan_pengelihatan == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>GANGGUAN LAINNYA</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->gangguan_lainnya == 'on')
                    YA
                    @elseif ($kesehatan->gangguan_lainnya == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>SEBUTKAN GANGGUAN</small></td>
                <td>:</td>
                <td>
                    {{ $kesehatan->sebutkan_gangguan }}
                </td>
            </tr>
        </tbody>
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT PERAWATAN</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="fw-bold"><small>PERNAH DIRAWAT DI RS</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->pernah_dirawat_rs == '1')
                    YA
                    @elseif ($kesehatan->pernah_dirawat_rs == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
        </tbody>
        @if ($kesehatan->pernah_dirawat_rs == '1')
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT DIRAWAT DI RS</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            @php
            $k = 1;
            @endphp
            @foreach ($kesehatan_rs as $rr)
            <tr>
                <td class="fw-bold"><small>{{ $k++ }}. TAHUN</small>
                </td>
                <td>:</td>
                <td>{{ $rr->tahun_rs }}</td>
                <td class="fw-bold"><small>PENYEBAB</small></td>
                <td>:</td>
                <td>{{ $rr->penyebab_rs }}</td>
            </tr>
            @endforeach
        </tbody>
        @endif

        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">KECELAKAAN SERIUS</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="fw-bold"><small>PERNAH KECELAKAAN SERIUS</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->kecelakaan_serius == '1')
                    YA
                    @elseif ($kesehatan->kecelakaan_serius == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
        </tbody>
        @if ($kesehatan->kecelakaan_serius == '1')
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT KECELAKAAN SERIUS</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            @php
            $l = 1;
            @endphp
            @foreach ($kesehatan_kecelakaan as $rr)
            <tr>
                <td class="fw-bold"><small>{{ $l++ }}. TAHUN</small>
                </td>
                <td>:</td>
                <td>{{ $rr->tahun_kecelakaan }}</td>
                <td class="fw-bold"><small>PENYEBAB</small></td>
                <td>:</td>
                <td>{{ $rr->penyebab_kecelakaan }}</td>
            </tr>
            @endforeach
        </tbody>
        @endif

        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT VAKSINASI</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="fw-bold"><small>COVID</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->covid == 'on')
                    YA
                    @elseif ($kesehatan->covid == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>TETANUS</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->tetanus == 'on')
                    YA
                    @elseif ($kesehatan->tetanus == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>VAKSIN LAINNYA</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->vaksin_lainnya == 'on')
                    YA
                    @elseif ($kesehatan->vaksin_lainnya == null)
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>SEBUTKAN</small></td>
                <td>:</td>
                <td>
                    {{ $kesehatan->sebutkan_vaksin_lainnya }}
                </td>
            </tr>
        </tbody>
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">KEMAMPUAN BEKERJA</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="fw-bold"><small>MAMPU BEKERJA DALAM SHIFT</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->mampu_shift == '1')
                    YA
                    @elseif ($kesehatan->mampu_shift == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>PERNAH MENJALANI PEMERIKSAAN KERJA
                        SEBELUMNYA</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->pemeriksaan_kerja_sebelumnya == '1')
                    YA
                    @elseif ($kesehatan->pemeriksaan_kerja_sebelumnya == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>DINYATAKAN LAYAK BEKERJA</small></td>
                <td>:</td>
                <td>
                    @if ($kesehatan->pemeriksaan_sebelumnya_hasil == '1')
                    YA
                    @elseif ($kesehatan->pemeriksaan_sebelumnya_hasil == '2')
                    TIDAK
                    @endif
                </td>
            </tr>
        </tbody>

    </table>
    <h2>RIWAYAT PENDIDIKAN</h2>

    <table class="table" id="table_pelamar3" style="width: 100%;">
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">DOKUMEN PENDIDIKAN</th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
        <tbody class="table-border-bottom-0">
            <tr>
                <td class="fw-bold"><small>IJAZAH TERAKHIR</small></td>
                <td>:</td>
                <td><a href="{{ url_karir() . '/storage/ijazah/' . $data_cv->AuthLogin->recruitmentCV->ijazah }}"
                        target="_blank">
                        LIHAT
                    </a>
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>IPK</small></td>
                <td>:</td>
                <td>{{ $data_cv->AuthLogin->recruitmentCV->ipk }}
                </td>
            </tr>
            <tr>
                <td class="fw-bold"><small>TRANSKRIP NILAI</small></td>
                <td>:</td>
                <td><a href="{{ url_karir() . '/storage/transkrip_nilai/' . $data_cv->AuthLogin->recruitmentCV->transkrip_nilai }}"
                        target="_blank">
                        LIHAT
                    </a>
                </td>
            </tr>
        </tbody>
        @php
        $i = 1;
        @endphp
        @foreach ($pendidikan as $pp)
        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">RIWAYAT PENDIDIKAN ({{ $i++ }})</th>
                <th class="fw-bold"></th>
                <th class="fw-bold"></th>
            </tr>

        </tbody>
        <tbody class="table-border-bottom-0">
            <tr>
                <td class="fw-bold"><small>NAMA INSTITUSI</small></td>
                <td>:</td>
                <td>{{ $pp->institusi }}</td>
            </tr>
            <tr>
                <td class="fw-bold"><small>JURUSAN</small></td>
                <td>:</td>
                <td>{{ $pp->jurusan }}</td>
            </tr>
            <tr>
                <td class="fw-bold"><small>JENJANG</small></td>
                <td>:</td>
                <td>{{ $pp->jenjang }}</td>
            </tr>
            <tr>
                <td class="fw-bold"><small>PERIODE</small></td>
                <td>:</td>
                <td>{{ $pp->tanggal_masuk }} - {{ $pp->tanggal_keluar }}</td>
            </tr>
        </tbody>
        @endforeach
    </table>
    <h2>KEAHLIAN</h2>
    <table class="table" id="table_pelamar3" style="width: 100%;">

        <tbody class="table-primary">
            <tr>
                <th class="fw-bold">No</th>
                <th class="fw-bold">KEAHLIAN</th>
                <th class="fw-bold">DOKUMEN KEAHLIAN</th>
            </tr>

        </tbody>
        <tbody class="table-border-bottom-0">
            @if ($keahlian_count == 0)
            <tr>
                <td colspan="3" class="fw-bold" style="text-align: center">
                    <small>PELAMAR
                        TIDAK MEMASUKKAN
                        KEAHLIAN</small>
                </td>
            </tr>
            @else
            @php
            $k = 1;
            @endphp
            @foreach ($keahlian as $kk)
            <tr>
                <td class="fw-bold"><small>{{ $k++ }}</small></td>
                <td class="fw-bold"><small>{{ $kk->keahlian }}</small></td>
                <td class="fw-bold">
                    @if ($kk->file_keahlian != null)
                    <a href="{{ url_karir() . '/storage/file_keahlian/' . $kk->file_keahlian }}"
                        target="_blank">
                        LIHAT
                    </a>
                    @endif
                </td>

            </tr>
            @endforeach
            @endif
        </tbody>
    </table>


</body>

</html>