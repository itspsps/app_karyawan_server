<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Cities;
use App\Models\City;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\User;
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

class UsersImport implements ToModel, WithStartRow, WithCalculatedFormulas, SkipsOnError
{

    use Importable, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        //     dd($row);
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
            $npwp = NULL;
        } else {
            $npwp = $row[3];
        }
        if ($row[4] == NULL || $row[4] == 0) {
            $fullname = NULL;
        } else {
            $fullname = $row[4];
        }
        if ($row[5] == NULL || $row[5] == 0) {
            $motto = NULL;
        } else {
            $motto = $row[5];
        }
        if ($row[6] == NULL || $row[6] == 0) {
            $email = NULL;
        } else {
            $email = $row[6];
        }
        if ($row[7] == NULL || $row[7] == 0) {
            $telepon = NULL;
        } else {
            $telepon = $row[7];
        }
        if ($row[8] == NULL || $row[8] == 0) {
            $username = NULL;
        } else {
            $username = $row[8];
        }
        if ($row[9] == NULL || $row[9] == 0) {
            $tempat_lahir = NULL;
        } else {
            $tempat_lahir = $row[9];
        }
        if ($row[10] == NULL || $row[10] == 0) {
            $tgl_lahir = NULL;
        } else {
            $tgl_lahir = is_numeric($row[10]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10]))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d');
        }
        // dd($tgl_lahir);
        // KELAMIN
        if ($row[11] == NULL || $row[11] == 0) {
            // dd($row);
            $kelamin = NULL;
        } else if ($row[11] == 'Laki-Laki') {
            $kelamin = $row[11];
        } else if ($row[11] == 'laki-laki') {
            $kelamin = 'Laki-Laki';
        } else if ($row[11] == 'laki laki') {
            $kelamin = 'Laki-Laki';
        } else if ($row[11] == 'L') {
            $kelamin = 'Laki-Laki';
        } else if ($row[11] == 'Perempuan') {
            $kelamin = $row[11];
        } else if ($row[11] == 'P') {
            $kelamin = 'Perempuan';
        } else if ($row[11] == 'perempuan') {
            $kelamin = 'Perempuan';
        }

        // TANGGAL JOIN
        if ($row[12] == NULL || $row[12] == 0) {
            $tgl_join = NULL;
        } else {
            $tgl_join = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[12]))->format('Y-m-d');
            // dd($tgl_mulai);
        }

        // STATUS NIKAH
        if ($row[13] == NULL || $row[13] == 0) {
            // dd($row);
            $status_nikah = NULL;
        } else if ($row[13] == 'LAJANG') {
            $status_nikah = 'Lajang';
        } else if ($row[13] == 'lajang') {
            $status_nikah = 'Lajang';
        } else if ($row[13] == 'Lajang') {
            $status_nikah = 'Lajang';
        } else if ($row[13] == 'MENIKAH') {
            $status_nikah = 'Menikah';
        } else if ($row[13] == 'menikah') {
            $status_nikah = 'Menikah';
        } else if ($row[13] == 'Menikah') {
            $status_nikah = 'Menikah';
        }

        if ($row[14] == NULL || $row[14] == '0') {
            $provinsi = NULL;
        } else {
            $provinsi = Province::whereLike('name', $row[14])->value('code');
        }

        if ($row[15] == NULL || $row[15] == '0') {
            $kabupaten = NULL;
        } else {
            if (strpos($row[15], 'KAB') !== false) {
                // dd('ok');
                $get_kabupaten = str_replace(array('KAB'), 'KABUPATEN', $row[15]);
                $kabupaten = Cities::where('province_code', $provinsi)->whereLike('name', $get_kabupaten)->value('code');
            } else {
                // dd('ok1');
                $kabupaten = Cities::where('province_code', $provinsi)->whereLike('name', $row[15])->value('code');
            }
        }
        if ($row[16] == NULL || $row[16] == '0') {
            $kecamatan = NULL;
        } else {
            if (strpos($row[16], 'KEC') !== false || strpos($row[16], 'KECAMATAN') !== false) {
                // dd('ok');
                $get_kecamatan = str_replace(array('KEC ', 'KECAMATAN '), '', $row[16]);
                $kecamatan = District::where('city_code', $kabupaten)->whereLike('name', $get_kecamatan)->value('code');
                // dd($get_kecamatan);
            } else {
                // dd('ok1');
                $kecamatan = District::where('city_code', $kabupaten)->whereLike('name', $row[16])->value('code');
            }
        }
        // dd($kecamatan);
        if ($row[17] == NULL || $row[17] == '0') {
            $desa = NULL;
        } else {
            if (strpos($row[17], 'KEL ') !== false || strpos($row[17], 'DS ') !== false) {
                $get_desa = str_replace(array('KEL ', 'DS '), '', $row[17]);
                // dd($get_desa);
                $desa = Village::where('district_code', $kecamatan)->whereLike('name', $get_desa)->value('code');
            } else {
                // dd('ok1');
                $desa = Village::where('district_code', $kecamatan)->whereLike('name', $row[17])->value('code');
            }
        }
        // dd($desa);
        if ($row[18] == NULL) {
            $rt = NULL;
        } else {
            $rt = $row[18];
        }
        if ($row[19] == NULL) {
            $rw = NULL;
        } else {
            $rw = $row[19];
        }
        $detail_alamat = Province::where('code', $provinsi)->value('name') . ', ' . Cities::where('code', $kabupaten)->value('name') . ', ' . District::where('code', $kecamatan)->value('name') . ', ' . Village::where('code', $desa)->value('name') . ', RT: ' . $rt . ', RW: ' . $rw;
        if ($row[20] == NULL || $row[20] == '0') {
            $alamat = NULL;
        } else {
            $alamat = $row[20];
        }
        $kuota_cuti_tahunan =  $row[21];
        // dd($kuota_cuti_tahunan);
        if ($row[22] == NULL || $row[22] == '0') {
            $kategori = NULL;
        } else {
            $kategori = $row[22];
        }
        if ($row[23] == NULL || $row[23] == '0') {
            $lama_kontrak_kerja = NULL;
        } else {
            $lama_kontrak_kerja = $row[23];
        }

        if ($row[24] == NULL || $row[24] == '0') {
            $tgl_mulai = NULL;
        } else {
            $tgl_mulai = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[24]))->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[25] == NULL || $row[25] == '0') {
            // dd($row);
            $tgl_selesai = NULL;
        } else {
            $tgl_selesai = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[25]))->format('Y-m-d');
        }
        if ($row[26] == NULL || $row[26] == '0') {
            $kontrak_kerja = NULL;
        } else {
            $kontrak_kerja = $row[26];
        }
        if ($row[27] == NULL || $row[27] == '0') {
            $penempatan_kerja = NULL;
        } else {
            $penempatan_kerja = $row[27];
        }
        if ($row[28] == NULL || $row[28] == '0') {
            $site_job = NULL;
        } else {
            $site_job = $row[28];
        }

        if ($row[29] == NULL || $row[29] == '0') {
            $nama_bank = NULL;
        } else if ($row[29] == 'OCBC') {
            $nama_bank = 'BOCBC';
        } else if ($row[29] == 'BRI') {
            $nama_bank = 'BBRI';
        } else if ($row[29] == 'BCA') {
            $nama_bank = 'BBCA';
        } else {
            $nama_bank = $row[29];
        }

        if ($row[30] == NULL || $row[30] == '0') {
            $nomor_rekening = NULL;
        } else {
            $nomor_rekening = $row[30];
        }
        if ($row[31] == NULL || $row[31] == '0') {
            $kategori_jabatan = NULL;
        } else if ($row[31] == 'SP') {
            $kategori_jabatan = 'sp';
        } else if ($row[31] == 'SPS') {
            $kategori_jabatan = 'sps';
        } else if ($row[31] == 'SIP') {
            $kategori_jabatan = 'sip';
        } else {
            $kategori_jabatan = $row[31];
        }
        if ($row[27] == NULL || $row[27] == '0') {
            $penempatan_kerja = NULL;
        } else {
            $penempatan_kerja = $row[27];
        }
        if ($row[52] == NULL || $row[52] == '0') {
            $bpjs_ketenagakerjaan = NULL;
        } else if ($row[52] == 'FALSE') {
            $bpjs_ketenagakerjaan = 'off';
        } else if ($row[52] == 'TRUE') {
            $bpjs_ketenagakerjaan = 'on';
        } else {
            $bpjs_ketenagakerjaan = $row[52];
        }
        if ($row[53] == NULL || $row[53] == '0') {
            $no_bpjs_ketenagakerjaan = NULL;
        } else {
            $no_bpjs_ketenagakerjaan = $row[53];
        }
        if ($row[54] == NULL || $row[54] == '0') {
            $bpjs_pensiun = NULL;
        } else if ($row[54] == 'FALSE') {
            $bpjs_pensiun = 'off';
        } else if ($row[54] == 'TRUE') {
            $bpjs_pensiun = 'on';
        } else {
            $bpjs_pensiun = $row[54];
        }
        if ($row[55] == NULL || $row[55] == '0') {
            $bpjs_kesehatan = NULL;
        } else if ($row[55] == 'FALSE') {
            $bpjs_kesehatan = 'off';
        } else if ($row[55] == 'TRUE') {
            $bpjs_kesehatan = 'on';
        } else {
            $bpjs_kesehatan = $row[55];
        }
        if ($row[56] == NULL || $row[56] == '0') {
            $no_bpjs_kesehatan = NULL;
        } else {
            $no_bpjs_kesehatan = $row[56];
        }
        if ($row[57] == NULL || $row[57] == '0') {
            $kelas_bpjs = NULL;
        } else {
            $kelas_bpjs = $row[57];
        }
        if ($row[58] == NULL || $row[58] == '0') {
            $ptkp = NULL;
        } else {
            $ptkp = $row[58];
        }
        try {
            return new User([
                "nomor_identitas_karyawan"                      => $nomor_identitas_karyawan,
                "name"                                          => $name,
                "nik"                                           => $nik,
                "npwp"                                          => $npwp,
                "fullname"                                      => $fullname,
                "motto"                                         => $motto,
                "email"                                         => $email,
                "telepon"                                       => $telepon,
                "username"                                      => $username,
                "password"                                      => Hash::make('user123'),
                "tempat_lahir"                                  => $tempat_lahir,
                "tgl_lahir"                                     => $tgl_lahir,
                "gender"                                        => $kelamin,
                "tgl_join"                                      => $tgl_join,
                "status_nikah"                                  => $status_nikah,
                "provinsi"                                      => $provinsi,
                "kabupaten"                                     => $kabupaten,
                "kecamatan"                                     => $kecamatan,
                "desa"                                          => $desa,
                "rt"                                            => $rt,
                "rw"                                            => $rw,
                "detail_alamat"                                 => $detail_alamat,
                "alamat"                                        => $alamat,
                "kuota_cuti_tahunan"                            => $kuota_cuti_tahunan,
                "is_admin"                                      => 'user',
                "kategori"                                      => $kategori,
                "lama_kontrak_kerja"                            => $lama_kontrak_kerja,
                "tgl_mulai_kontrak"                             => $tgl_mulai,
                "tgl_selesai_kontrak"                           => $tgl_selesai,
                "kontrak_kerja"                                 => $kontrak_kerja,
                "penempatan_kerja"                              => $penempatan_kerja,
                "site_job"                                      => $site_job,
                "nama_bank"                                     => $nama_bank,
                "nomor_rekening"                                => $nomor_rekening,
                "kategori_jabatan"                              => $kategori_jabatan,
                "bpjs_ketenagakerjaan"                          => $bpjs_ketenagakerjaan,
                "no_bpjs_ketenagakerjaan"                       => $no_bpjs_ketenagakerjaan,
                "bpjs_pensiun"                                  => $bpjs_pensiun,
                "bpjs_kesehatan"                                => $bpjs_kesehatan,
                "no_bpjs_kesehatan"                             => $no_bpjs_kesehatan,
                "kelas_bpjs"                                    => $kelas_bpjs,
                "ptkp"                                          => $ptkp,
                "status_aktif"                                  => 'AKTIF',
                // "dept_id" => Departemen::where('nama_departemen', $row[37])->value('id'),
                // "divisi_id" => Divisi::where('id', $divisi)->value('id'),
                // "bagian_id" => Bagian::where('nama_bagian', $row[39])->value('id'),
                // "jabatan_id" => Jabatan::where('nama_jabatan', $row[40])->where('divisi_id', $divisi)->value('id'),
                // "divisi1_id" => Divisi::where('nama_divisi', $row[41])->value('id'),
                // "jabatan1_id" => Jabatan::where('nama_jabatan', $row[42])->value('id'),
                // "divisi2_id" => Divisi::where('nama_divisi', $row[43])->value('id'),
                // "jabat2an_id" => Jabatan::where('nama_jabatan', $row[44])->value('id'),
                // "divisi3_id" => Divisi::where('nama_divisi', $row[45])->value('id'),
                // "jabatan3_id" => Jabatan::where('nama_jabatan', $row[46])->value('id'),
                // "divisi4_id" => Divisi::where('nama_divisi', $row[47])->value('id'),
                // "jabatan4_id" => Jabatan::where('nama_jabatan', $row[48])->value('id')
            ]);
        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage(), 10000);
        }
    }
}
