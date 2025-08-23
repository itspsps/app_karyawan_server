<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Cities;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\Province;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KaryawanImport implements ToModel, WithStartRow, WithCalculatedFormulas
{

    use Importable, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        // dd($row);
        // dd(Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[12]))->format('Y-m-d'));
        $holding = request()->segment(count(request()->segments()));


        // if ($row[0] == null) {
        //     break;
        // }
        if ($row[0] == NULL || $row[0] == 0) {
            $nomor_identitas_karyawan = NULL;
        } else {
            $nomor_identitas_karyawan = $row[0];
        }
        if ($row[1] == NULL || $row[1] == 0) {
            $name = NULL;
        } else {
            $name = $row[1];
        }
        if ($row[2] == NULL || $row[2] == 0) {
            $nik = NULL;
        } else {
            $nik = $row[2];
        }
        if ($row[3] == NULL || $row[3] == 0) {
            $agama = NULL;
        } else {
            $agama = $row[3];
        }
        if ($row[4] == NULL || $row[4] == 0) {
            $golongan_darah = NULL;
        } else {
            $golongan_darah = $row[4];
        }
        if ($row[5] == NULL || $row[5] == 0) {
            $email = NULL;
        } else {
            $email = $row[5];
        }
        if ($row[6] == NULL || $row[6] == 0) {
            $telepon = NULL;
        } else {
            $telepon = $row[6];
        }
        if ($row[7] == NULL || $row[7] == 0) {
            $status_nomor = 'tidak';
            $nomor_wa = NULL;
        } else {
            $status_nomor = 'ya';
            $nomor_wa = $row[7];
        }

        if ($row[8] == NULL || $row[8] == 0) {
            $tempat_lahir = NULL;
        } else {
            $tempat_lahir = $row[8];
        }
        // dd($row[9]);
        if ($row[9] == NULL || $row[9] == 0) {
            $tgl_lahir = NULL;
        } else {
            $tgl_lahir = Carbon::parse($row[9])->format('Y-m-d');
            // $tgl_lahir = is_numeric($row[9]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d');
        }
        // dd($tgl_lahir);
        // KELAMIN
        if ($row[10] == NULL || $row[10] == 0) {
            // dd($row);
            $kelamin = NULL;
        } else if ($row[10] == "1") {
            $kelamin = $row[10];
        } else if ($row[10] == "1") {
            $kelamin = "1";
        } else if ($row[10] == 'laki laki') {
            $kelamin = "1";
        } else if ($row[10] == 'L') {
            $kelamin = "1";
        } else if ($row[10] == "2") {
            $kelamin = $row[10];
        } else if ($row[10] == 'P') {
            $kelamin = "2";
        } else if ($row[10] == "2") {
            $kelamin = "2";
        }


        // STATUS NIKAH
        if ($row[11] == NULL || $row[11] == 0) {
            // dd($row);
            $status_nikah = NULL;
        } else if ($row[11] == 'LAJANG') {
            $status_nikah = 'Lajang';
        } else if ($row[11] == 'lajang') {
            $status_nikah = 'Lajang';
        } else if ($row[11] == 'Lajang') {
            $status_nikah = 'Lajang';
        } else if ($row[11] == 'MENIKAH') {
            $status_nikah = 'Menikah';
        } else if ($row[11] == 'menikah') {
            $status_nikah = 'Menikah';
        } else if ($row[11] == 'Menikah') {
            $status_nikah = 'Menikah';
        }

        if ($row[12] == NULL || $row[12] == '0') {
            $strata_pendidikan = NULL;
        } else {
            $strata_pendidikan = $row[12];
        }
        if ($row[13] == NULL || $row[13] == '0') {
            $instansi_pendidikan = NULL;
        } else {
            $instansi_pendidikan = $row[13];
        }
        if ($row[14] == NULL || $row[14] == '0') {
            $jurusan_akademik = NULL;
        } else {
            $jurusan_akademik = $row[14];
        }
        if ($row[15] == NULL || $row[15] == '0') {
            $provinsi = NULL;
        } else {
            $get_provinsi = Province::where('name', $row[15])->value('code');
            if ($get_provinsi == NULL) {
                $provinsi = NULL;
            } else {
                $provinsi = $get_provinsi;
            }
        }

        if ($row[16] == NULL || $row[16] == '0') {
            $kabupaten = NULL;
        } else {
            if (strpos($row[16], 'KAB. ') !== false) {
                $get_kabupaten = str_replace(array('KAB. '), 'KABUPATEN ', $row[16]);
                $kabupaten = Cities::where('province_code', $provinsi)->where('name', $get_kabupaten)->value('code');
                // dd('ok');
            } else if (strpos($row[16], 'KOTA') !== false) {
                // dd('ok1');
                // dd('ok');
                $get_kabupaten = str_replace(array('KOTA'), '', $row[16]);
                $kabupaten = Cities::where('province_code', $provinsi)->where('name', $get_kabupaten)->value('code');
            } else {
                // dd('ok2');
                // dd('ok1');
                $get_kabupaten = Cities::where('province_code', $provinsi)->where('name', $row[16])->value('code');
                // dd($get_kabupaten);
                if ($get_kabupaten == NULL) {
                    $kabupaten = NULL;
                } else {
                    $kabupaten = $get_kabupaten;
                }
            }
        }
        // dd($kabupaten);
        if ($row[17] == NULL || $row[17] == '0') {
            $kecamatan = NULL;
        } else {
            if (strpos($row[17], 'KEC. ') !== false || strpos($row[17], 'KECAMATAN ') !== false) {
                // dd('ok');
                $get_kecamatan = str_replace(array('KEC. ', 'KECAMATAN '), '', $row[17]);
                $kecamatan = District::where('city_code', $kabupaten)->where('name', $get_kecamatan)->value('code');
                // dd($get_kecamatan);
            } else {
                // dd('ok1');
                $get_kecamatan = District::where('city_code', $kabupaten)->where('name', $row[17])->value('code');
                if ($get_kecamatan == NULL) {
                    $kecamatan = NULL;
                } else {
                    $kecamatan = $get_kecamatan;
                }
            }
        }
        // dd($kecamatan);
        if ($row[18] == NULL || $row[18] == '0') {
            $desa = NULL;
        } else {
            if (strpos($row[18], 'KEL. ') !== false || strpos($row[18], 'DS. ') !== false) {
                $get_desa = str_replace(array('KEL. ', 'DS. '), '', $row[18]);
                // dd($get_desa);
                $desa = Village::where('district_code', $kecamatan)->where('name', $get_desa)->value('code');
            } else {
                // dd('ok1');
                $get_desa = Village::where('district_code', $kecamatan)->where('name', $row[18])->value('code');
                if ($get_desa == NULL) {

                    $desa = NULL;
                } else {
                    $desa = $get_desa;
                }
            }
        }
        // dd($desa);
        if ($row[19] == NULL) {
            $rt = NULL;
        } else {
            $rt = $row[19];
        }
        if ($row[20] == NULL) {
            $rw = NULL;
        } else {
            $rw = $row[20];
        }
        $detail_alamat = Province::where('code', $provinsi)->value('name') . ', ' . Cities::where('code', $kabupaten)->value('name') . ', ' . District::where('code', $kecamatan)->value('name') . ', ' . Village::where('code', $desa)->value('name') . ', RT: ' . $rt . ', RW: ' . $rw;
        if ($row[21] == NULL || $row[21] == '0') {
            $alamat = NULL;
        } else {
            $alamat = $row[21];
        }
        // alamat Domisili
        if ($row[22] == NULL || $row[22] == '0') {
            $status_alamat = 'ya';
            $provinsi_domisili = NULL;
        } else {
            $get_provinsi_domisili = Province::where('name', $row[22])->value('code');
            if ($get_provinsi_domisili == NULL) {
                $status_alamat = 'ya';
                $provinsi_domisili = NULL;
            } else {
                $status_alamat = 'tidak';
                $provinsi_domisili = $get_provinsi_domisili;
            }
        }

        if ($row[23] == NULL || $row[23] == '0') {
            $kabupaten_domisili = NULL;
        } else {
            if (strpos($row[23], 'KAB. ') !== false) {
                $get_kabupaten_domisili = str_replace(array('KAB. '), 'KABUPATEN ', $row[23]);
                $kabupaten_domisili = Cities::where('province_code', $provinsi_domisili)->where('name', $get_kabupaten_domisili)->value('code');
                // dd('ok');
            } else if (strpos($row[23], 'KOTA') !== false) {
                // dd('ok1');
                // dd('ok');
                $get_kabupaten_domisili = str_replace(array('KOTA'), '', $row[23]);
                $kabupaten_domisili = Cities::where('province_code', $provinsi_domisili)->where('name', $get_kabupaten_domisili)->value('code');
            } else {
                // dd('ok2');
                // dd('ok1');
                $get_kabupaten_domisili = Cities::where('province_code', $provinsi_domisili)->where('name', $row[23])->value('code');
                // dd($get_kabupaten_domisili);
                if ($get_kabupaten_domisili == NULL) {
                    $kabupaten_domisili = NULL;
                } else {
                    $kabupaten_domisili = $get_kabupaten_domisili;
                }
            }
        }
        // dd($kabupaten);
        if ($row[24] == NULL || $row[24] == '0') {
            $kecamatan_domisili = NULL;
        } else {
            if (strpos($row[24], 'KEC. ') !== false || strpos($row[24], 'KECAMATAN ') !== false) {
                // dd('ok');
                $get_kecamatan_domisili = str_replace(array('KEC. ', 'KECAMATAN '), '', $row[24]);
                $kecamatan_domisili = District::where('city_code', $kabupaten_domisili)->where('name', $get_kecamatan_domisili)->value('code');
                // dd($get_kecamatan_domisili);
            } else {
                // dd('ok1');
                $get_kecamatan_domisili = District::where('city_code', $kabupaten_domisili)->where('name', $row[24])->value('code');
                if ($get_kecamatan_domisili == NULL) {
                    $kecamatan_domisili = NULL;
                } else {
                    $kecamatan_domisili = $get_kecamatan_domisili;
                }
            }
        }
        // dd($kecamatan);
        if ($row[25] == NULL || $row[25] == '0') {
            $desa_domisili = NULL;
        } else {
            if (strpos($row[25], 'KEL. ') !== false || strpos($row[25], 'DS. ') !== false) {
                $get_desa_domisili = str_replace(array('KEL. ', 'DS. '), '', $row[25]);
                // dd($get_desa_domisili);
                $desa_domisili = Village::where('district_code', $kecamatan_domisili)->where('name', $get_desa_domisili)->value('code');
            } else {
                // dd('ok1');
                $get_desa_domisili = Village::where('district_code', $kecamatan_domisili)->where('name', $row[25])->value('code');
                if ($get_desa_domisili == NULL) {

                    $desa_domisili = NULL;
                } else {
                    $desa_domisili = $get_desa_domisili;
                }
            }
        }
        // dd($desa);
        if ($row[26] == NULL) {
            $rt_domisili = NULL;
        } else {
            $rt_domisili = $row[26];
        }
        if ($row[27] == NULL) {
            $rw_domisili = NULL;
        } else {
            $rw_domisili = $row[27];
        }
        $detail_alamat_domisili = Province::where('code', $provinsi_domisili)->value('name') . ', ' . Cities::where('code', $kabupaten_domisili)->value('name') . ', ' . District::where('code', $kecamatan_domisili)->value('name') . ', ' . Village::where('code', $desa_domisili)->value('name') . ', RT: ' . $rt_domisili . ', RW: ' . $rw_domisili;
        if ($row[28] == NULL || $row[28] == '0') {
            $alamat_domisili = NULL;
        } else {
            $alamat_domisili = $row[28];
        }
        $kuota_cuti_tahunan =  $row[29];
        // dd($kuota_cuti_tahunan);
        if ($row[30] == NULL || $row[30] == '0') {
            $kategori = NULL;
        } else {
            $kategori = $row[30];
        }
        // TANGGAL JOIN
        if ($row[31] == NULL || $row[31] == 0) {
            $tgl_join = NULL;
        } else {
            // $tgl_join = Carbon::parse($row[31])->format('Y-m-d');
            $tgl_join = is_numeric($row[31]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[31]))->format('Y-m-d') : Carbon::createFromFormat('Y-m-d', $row[31])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[32] == NULL || $row[32] == '0') {
            $lama_kontrak_kerja = NULL;
        } else {
            $lama_kontrak_kerja = $row[32];
        }

        if ($row[33] == NULL || $row[33] == '0') {
            $tgl_mulai = NULL;
        } else {
            // $tgl_mulai = Carbon::parse($row[33])->format('Y-m-d');
            $tgl_mulai = is_numeric($row[33]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[33]))->format('Y-m-d') : Carbon::createFromFormat('Y-m-d', $row[33])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[34] == NULL || $row[34] == '0') {
            // dd($row);
            $tgl_selesai = NULL;
        } else {
            // $tgl_selesai = Carbon::parse($row[34])->format('Y-m-d');
            $tgl_selesai = is_numeric($row[34]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[34]))->format('Y-m-d') : Carbon::createFromFormat('Y-m-d', $row[34])->format('Y-m-d');
        }
        if ($row[35] == NULL || $row[35] == '0') {
            $kontrak_kerja = NULL;
        } else {
            $kontrak_kerja = $row[35];
        }
        if ($row[36] == NULL || $row[36] == '0') {
            $penempatan_kerja = NULL;
        } else {
            $penempatan_kerja = $row[36];
        }
        if ($row[37] == NULL || $row[37] == '0') {
            $site_job = NULL;
        } else {
            $site_job = $row[37];
        }

        if ($row[38] == NULL || $row[38] == '0') {
            $nama_bank = NULL;
        } else if ($row[38] == 'BANK OCBC') {
            $nama_bank = 'BOCBC';
        } else if ($row[38] == 'OCBC') {
            $nama_bank = 'BOCBC';
        } else if ($row[38] == 'BANK BRI') {
            $nama_bank = 'BBRI';
        } else if ($row[38] == 'BRI') {
            $nama_bank = 'BBRI';
        } else if ($row[38] == 'BANK BCA') {
            $nama_bank = 'BBCA';
        } else if ($row[38] == 'BCA') {
            $nama_bank = 'BBCA';
        } else if ($row[38] == 'MANDIRI') {
            $nama_bank = 'BMANDIRI';
        } else if ($row[38] == 'BANK MANDIRI') {
            $nama_bank = 'BMANDIRI';
        } else {
            $nama_bank = $row[38];
        }

        if ($row[39] == NULL || $row[39] == '0') {
            $nama_pemilik_rekening = NULL;
        } else {
            $nama_pemilik_rekening = $row[39];
        }
        if ($row[40] == NULL || $row[40] == '0') {
            $nomor_rekening = NULL;
        } else {
            $nomor_rekening = $row[40];
        }
        if ($row[41] == NULL || $row[41] == '0') {
            $kategori_jabatan = NULL;
        } else if ($row[41] == 'SP') {
            $kategori_jabatan = 'sp';
        } else if ($row[41] == 'SPS') {
            $kategori_jabatan = 'sps';
        } else if ($row[41] == 'SIP') {
            $kategori_jabatan = 'sip';
        } else {
            $kategori_jabatan = $row[41];
        }
        if ($kategori_jabatan == null) {
            $get_jabatan = $kontrak_kerja;
        } else {
            $get_jabatan = $kategori_jabatan;
        }

        $departemen = Departemen::where('nama_departemen', $row[42])->where('holding', $get_jabatan)->value('id');
        $divisi = Divisi::where('nama_divisi', $row[43])->where('dept_id', $departemen)->value('id');
        $bagian = Bagian::where('nama_bagian', $row[44])->where('divisi_id', $divisi)->value('id');
        $jabatan = Jabatan::where('nama_jabatan', $row[45])->where('divisi_id', $divisi)->where('bagian_id', $bagian)->value('id');
        // dd($get_jabatan);

        $departemen1 = Departemen::where('nama_departemen', $row[46])->value('id');
        $divisi1 = Divisi::where('nama_divisi', $row[47])->where('dept_id', $departemen1)->value('id');
        $bagian1 = Bagian::where('nama_bagian', $row[48])->where('divisi_id', $divisi1)->value('id');
        $jabatan1 = Jabatan::where('nama_jabatan', $row[49])->where('divisi_id', $divisi1)->where('bagian_id', $bagian1)->value('id');

        $departemen2 = Departemen::where('nama_departemen', $row[50])->value('id');
        $divisi2 = Divisi::where('nama_divisi', $row[51])->where('dept_id', $departemen2)->value('id');
        $bagian2 = Bagian::where('nama_bagian', $row[52])->where('divisi_id', $divisi2)->value('id');
        $jabatan2 = Jabatan::where('nama_jabatan', $row[53])->where('divisi_id', $divisi2)->where('bagian_id', $bagian2)->value('id');

        $departemen3 = Departemen::where('nama_departemen', $row[54])->value('id');
        $divisi3 = Divisi::where('nama_divisi', $row[55])->where('dept_id', $departemen3)->value('id');
        $bagian3 = Bagian::where('nama_bagian', $row[56])->where('divisi_id', $divisi3)->value('id');
        $jabatan3 = Jabatan::where('nama_jabatan', $row[57])->where('divisi_id', $divisi3)->where('bagian_id', $bagian3)->value('id');

        $departemen4 = Departemen::where('nama_departemen', $row[58])->value('id');
        $divisi4 = Divisi::where('nama_divisi', $row[59])->where('dept_id', $departemen4)->value('id');
        $bagian4 = Bagian::where('nama_bagian', $row[60])->where('divisi_id', $divisi4)->value('id');
        $jabatan4 = Jabatan::where('nama_jabatan', $row[61])->where('divisi_id', $divisi4)->where('bagian_id', $bagian4)->value('id');

        if ($row[62] == NULL || $row[62] == '0') {
            $ptkp = NULL;
        } else {
            $ptkp = $row[62];
        }
        if ($row[63] == NULL || $row[63] == 0) {
            $status_npwp = 'off';
            $nama_pemilik_npwp = NULL;
        } else {
            $status_npwp = 'on';
            $nama_pemilik_npwp = $row[63];
        }
        if ($row[64] == NULL || $row[64] == 0) {
            $status_npwp = 'off';
            $npwp = NULL;
        } else {
            $status_npwp = 'on';
            $npwp = $row[64];
        }
        if ($row[65] == NULL || $row[65] == '0') {
            $bpjs_ketenagakerjaan = 'off';
            $nama_pemilik_bpjs_ketenagakerjaan = NULL;
        } else {
            $bpjs_ketenagakerjaan = 'on';
            $nama_pemilik_bpjs_ketenagakerjaan = $row[65];
        }
        if ($row[66] == NULL || $row[66] == '0') {
            $bpjs_ketenagakerjaan = 'off';
            $no_bpjs_ketenagakerjaan = NULL;
        } else {
            $bpjs_ketenagakerjaan = 'on';
            $no_bpjs_ketenagakerjaan = $row[66];
        }
        if ($row[67] == NULL || $row[67] == '0') {
            $bpjs_pensiun = 'off';
        } else if ($row[67] == 'FALSE') {
            $bpjs_pensiun = 'off';
        } else if ($row[67] == 'TRUE') {
            $bpjs_pensiun = 'on';
        } else if ($row[67] == 'ya') {
            $bpjs_pensiun = 'on';
        } else if ($row[67] == 'tidak') {
            $bpjs_pensiun = 'off';
        } else {
            $bpjs_pensiun = $row[67];
        }
        if ($row[68] == NULL || $row[68] == '0') {
            $bpjs_kesehatan = 'off';
            $nama_pemilik_bpjs_kesehatan = NULL;
        } else {
            $bpjs_kesehatan = 'on';
            $nama_pemilik_bpjs_kesehatan = $row[68];
        }
        if ($row[69] == NULL || $row[69] == '0') {
            $bpjs_kesehatan = 'off';
            $no_bpjs_kesehatan = NULL;
        } else {
            $bpjs_kesehatan = 'on';
            $no_bpjs_kesehatan = $row[69];
        }
        if ($row[70] == NULL || $row[70] == '0') {
            $kelas_bpjs = NULL;
        } else {
            $kelas_bpjs = $row[70];
        }
        if ($provinsi == $provinsi_domisili && $kabupaten == $kabupaten_domisili && $kecamatan == $kecamatan_domisili && $desa == $desa_domisili) {
            $status_alamat = 'ya';
        } else {
            $status_alamat = 'tidak';
        }

        try {
            // dd($row[65]);
            return new Karyawan([
                "id"                                            => $row[72],
                "face_id"                                       => $row[70],
                "status_aktif"                                  => $row[71],
                "nomor_identitas_karyawan"                      => $nomor_identitas_karyawan,
                "name"                                          => $name,
                "nik"                                           => $nik,
                "agama"                                         => $agama,
                "golongan_darah"                                => $golongan_darah,
                "email"                                         => $email,
                "telepon"                                       => $telepon,
                "status_nomor"                                  => $status_nomor,
                "nomor_wa"                                      => $nomor_wa,
                "tempat_lahir"                                  => $tempat_lahir,
                "tgl_lahir"                                     => $tgl_lahir,
                // 10
                "gender"                                        => $kelamin,
                "status_nikah"                                  => $status_nikah,
                "status_alamat"                                 => $status_alamat,
                "provinsi_domisili"                             => $provinsi_domisili,
                "kabupaten_domisili"                            => $kabupaten_domisili,
                "kecamatan_domisili"                            => $kecamatan_domisili,
                "desa_domisili"                                 => $desa_domisili,
                "rt_domisili"                                   => $rt_domisili,
                "rw_domisili"                                   => $rw_domisili,
                "detail_alamat_domisili"                        => $detail_alamat_domisili,
                "alamat_domisili"                               => $alamat_domisili,
                "provinsi"                                      => $provinsi,
                "kabupaten"                                     => $kabupaten,
                "kecamatan"                                     => $kecamatan,
                "desa"                                          => $desa,
                "rt"                                            => $rt,
                "rw"                                            => $rw,
                "detail_alamat"                                 => $detail_alamat,
                "alamat"                                        => $alamat,
                // 20
                "kuota_cuti_tahunan"                            => $kuota_cuti_tahunan,
                "kategori"                                      => $kategori,
                "tgl_join"                                      => $tgl_join,
                "lama_kontrak_kerja"                            => $lama_kontrak_kerja,
                "tgl_mulai_kontrak"                             => $tgl_mulai,
                "tgl_selesai_kontrak"                           => $tgl_selesai,
                "kontrak_kerja"                                 => $kontrak_kerja,
                "penempatan_kerja"                              => $penempatan_kerja,
                "site_job"                                      => $site_job,
                "nama_bank"                                     => $nama_bank,
                "nama_pemilik_rekening"                         => $nama_pemilik_rekening,
                "nomor_rekening"                                => $nomor_rekening,
                // 30
                "kategori_jabatan"                              => $kategori_jabatan,
                "dept_id"                                       => $departemen,
                // 40
                "divisi_id"                                     => $divisi,
                "bagian_id"                                     => $bagian,
                "jabatan_id"                                    => $jabatan,
                "dept1_id"                                      => $departemen1,
                "divisi1_id"                                    => $divisi1,
                "bagian1_id"                                    => $bagian1,
                "jabatan1_id"                                   => $jabatan1,
                "dept2_id"                                      => $departemen2,
                "divisi2_id"                                    => $divisi2,
                "bagian2_id"                                    => $bagian2,
                "jabatan2_id"                                   => $jabatan2,
                "dept3_id"                                      => $departemen3,
                "divisi3_id"                                    => $divisi3,
                "bagian3_id"                                    => $bagian3,
                "jabatan3_id"                                   => $jabatan3,
                "dept4_id"                                      => $departemen4,
                "divisi4_id"                                    => $divisi4,
                "bagian4_id"                                    => $bagian4,
                "jabatan4_id"                                   => $jabatan4,
                "ptkp"                                          => $ptkp,
                "status_npwp"                                   => $status_npwp,
                "npwp"                                          => $npwp,
                "nama_pemilik_npwp"                             => $nama_pemilik_npwp,
                "bpjs_ketenagakerjaan"                          => $bpjs_ketenagakerjaan,
                "nama_pemilik_bpjs_ketenagakerjaan"             => $nama_pemilik_bpjs_ketenagakerjaan,
                "no_bpjs_ketenagakerjaan"                       => $no_bpjs_ketenagakerjaan,
                "bpjs_pensiun"                                  => $bpjs_pensiun,
                "bpjs_kesehatan"                                => $bpjs_kesehatan,
                "nama_pemilik_bpjs_kesehatan"                   => $nama_pemilik_bpjs_kesehatan,
                "no_bpjs_kesehatan"                             => $no_bpjs_kesehatan,
                "kelas_bpjs"                                    => $kelas_bpjs
            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage(), 1500);
        }
    }
}
