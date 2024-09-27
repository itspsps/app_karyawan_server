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

class KaryawanImport implements ToModel, WithStartRow, WithCalculatedFormulas, SkipsOnError
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
        if ($row[0] == 'NULL' || $row[0] == 0) {
            $nomor_identitas_karyawan = NULL;
        } else {
            $nomor_identitas_karyawan = $row[0];
        }
        if ($row[1] == 'NULL' || $row[1] == 0) {
            $name = NULL;
        } else {
            $name = $row[1];
        }
        if ($row[2] == 'NULL' || $row[2] == 0) {
            $nik = NULL;
        } else {
            $nik = $row[2];
        }
        if ($row[3] == 'NULL' || $row[3] == 0) {
            $npwp = NULL;
        } else {
            $npwp = $row[3];
        }
        if ($row[4] == 'NULL' || $row[4] == 0) {
            $fullname = NULL;
        } else {
            $fullname = $row[4];
        }
        if ($row[5] == 'NULL' || $row[5] == 0) {
            $motto = NULL;
        } else {
            $motto = $row[5];
        }
        if ($row[6] == 'NULL' || $row[6] == 0) {
            $email = NULL;
        } else {
            $email = $row[6];
        }
        if ($row[7] == 'NULL' || $row[7] == 0) {
            $telepon = NULL;
        } else {
            $telepon = $row[7];
        }

        if ($row[8] == 'NULL' || $row[8] == 0) {
            $tempat_lahir = NULL;
        } else {
            $tempat_lahir = $row[8];
        }
        if ($row[9] == 'NULL' || $row[9] == 0) {
            $tgl_lahir = NULL;
        } else {
            $tgl_lahir = is_numeric($row[9]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d');
        }
        // dd($tgl_lahir);
        // KELAMIN
        if ($row[10] == 'NULL' || $row[10] == 0) {
            // dd($row);
            $kelamin = NULL;
        } else if ($row[10] == 'Laki-Laki') {
            $kelamin = $row[10];
        } else if ($row[10] == 'laki-laki') {
            $kelamin = 'Laki-Laki';
        } else if ($row[10] == 'laki laki') {
            $kelamin = 'Laki-Laki';
        } else if ($row[10] == 'L') {
            $kelamin = 'Laki-Laki';
        } else if ($row[10] == 'Perempuan') {
            $kelamin = $row[10];
        } else if ($row[10] == 'P') {
            $kelamin = 'Perempuan';
        } else if ($row[10] == 'perempuan') {
            $kelamin = 'Perempuan';
        }

        // TANGGAL JOIN
        if ($row[11] == 'NULL' || $row[11] == 0) {
            $tgl_join = NULL;
        } else {
            $tgl_join = is_numeric($row[11]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[11]))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $row[11])->format('Y-m-d');
            // dd($tgl_mulai);
        }

        // STATUS NIKAH
        if ($row[12] == 'NULL' || $row[12] == 0) {
            // dd($row);
            $status_nikah = NULL;
        } else if ($row[12] == 'LAJANG') {
            $status_nikah = 'Lajang';
        } else if ($row[12] == 'lajang') {
            $status_nikah = 'Lajang';
        } else if ($row[12] == 'Lajang') {
            $status_nikah = 'Lajang';
        } else if ($row[12] == 'MENIKAH') {
            $status_nikah = 'Menikah';
        } else if ($row[12] == 'menikah') {
            $status_nikah = 'Menikah';
        } else if ($row[12] == 'Menikah') {
            $status_nikah = 'Menikah';
        }

        if ($row[13] == 'NULL' || $row[13] == '0') {
            $provinsi = NULL;
        } else {
            $get_provinsi = Province::whereLike('name', $row[13])->value('code');
            if ($get_provinsi == NULL) {
                $provinsi = Province::where('code', $row[13])->value('code');
            } else {
                $provinsi = $row[13];
            }
        }

        if ($row[14] == 'NULL' || $row[14] == '0') {
            $kabupaten = NULL;
        } else {
            if (strpos($row[14], 'KAB') !== false) {
                $get_kabupaten = str_replace(array('KAB'), 'KABUPATEN', $row[14]);
                $kabupaten = Cities::where('province_code', $provinsi)->whereLike('name', $get_kabupaten)->value('code');
            } else if (strpos($row[14], 'KOTA') !== false) {
                // dd('ok');
                $get_kabupaten = str_replace(array('KOTA'), '', $row[14]);
                $kabupaten = Cities::where('province_code', $provinsi)->whereLike('name', $get_kabupaten)->value('code');
            } else {
                // dd('ok1');
                $get_kabupaten = Cities::where('province_code', $provinsi)->whereLike('name', $row[14])->value('code');
                if ($get_kabupaten == NULL) {
                    $kabupaten = Cities::where('province_code', $provinsi)->where('code', $row[14])->value('code');
                } else {
                    $kabupaten = $row[14];
                }
            }
        }
        if ($row[15] == 'NULL' || $row[15] == '0') {
            $kecamatan = NULL;
        } else {
            if (strpos($row[15], 'KEC') !== false || strpos($row[15], 'KECAMATAN') !== false) {
                // dd('ok');
                $get_kecamatan = str_replace(array('KEC ', 'KECAMATAN '), '', $row[15]);
                $kecamatan = District::where('city_code', $kabupaten)->whereLike('name', $get_kecamatan)->value('code');
                // dd($get_kecamatan);
            } else {
                // dd('ok1');
                $get_kecamatan = District::where('city_code', $kabupaten)->whereLike('name', $row[15])->value('code');
                if ($get_kecamatan == NULL) {
                    $kecamatan = District::where('city_code', $kabupaten)->where('code', $row[15])->value('code');
                } else {
                    $kecamatan = $row[15];
                }
            }
        }
        // dd($kecamatan);
        if ($row[16] == 'NULL' || $row[16] == '0') {
            $desa = NULL;
        } else {
            if (strpos($row[16], 'KEL ') !== false || strpos($row[16], 'DS ') !== false) {
                $get_desa = str_replace(array('KEL ', 'DS '), '', $row[16]);
                // dd($get_desa);
                $desa = Village::where('district_code', $kecamatan)->whereLike('name', $get_desa)->value('code');
            } else {
                // dd('ok1');
                $get_desa = Village::where('district_code', $kecamatan)->whereLike('name', $row[16])->value('code');
                if ($get_desa == NULL) {

                    $desa = Village::where('district_code', $kecamatan)->where('code', $row[16])->value('code');
                } else {
                    $desa = $row[16];
                }
            }
        }
        // dd($desa);
        if ($row[17] == 'NULL') {
            $rt = NULL;
        } else {
            $rt = $row[17];
        }
        if ($row[18] == 'NULL') {
            $rw = NULL;
        } else {
            $rw = $row[18];
        }
        $detail_alamat = Province::where('code', $provinsi)->value('name') . ', ' . Cities::where('code', $kabupaten)->value('name') . ', ' . District::where('code', $kecamatan)->value('name') . ', ' . Village::where('code', $desa)->value('name') . ', RT: ' . $rt . ', RW: ' . $rw;
        if ($row[19] == 'NULL' || $row[19] == '0') {
            $alamat = NULL;
        } else {
            $alamat = $row[19];
        }
        $kuota_cuti_tahunan =  $row[20];
        // dd($kuota_cuti_tahunan);
        if ($row[21] == 'NULL' || $row[21] == '0') {
            $kategori = NULL;
        } else {
            $kategori = $row[21];
        }
        if ($row[22] == 'NULL' || $row[22] == '0') {
            $lama_kontrak_kerja = NULL;
        } else {
            $lama_kontrak_kerja = $row[22];
        }

        if ($row[23] == 'NULL' || $row[23] == '0') {
            $tgl_mulai = NULL;
        } else {
            $tgl_mulai = is_numeric($row[23]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[23]))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $row[23])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[24] == 'NULL' || $row[24] == '0') {
            // dd($row);
            $tgl_selesai = NULL;
        } else {
            $tgl_selesai = is_numeric($row[24]) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[24]))->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $row[24])->format('Y-m-d');
        }
        if ($row[25] == 'NULL' || $row[25] == '0') {
            $kontrak_kerja = NULL;
        } else {
            $kontrak_kerja = $row[25];
        }
        if ($row[26] == 'NULL' || $row[26] == '0') {
            $penempatan_kerja = NULL;
        } else {
            $penempatan_kerja = $row[26];
        }
        if ($row[27] == 'NULL' || $row[27] == '0') {
            $site_job = NULL;
        } else {
            $site_job = $row[27];
        }

        if ($row[28] == 'NULL' || $row[28] == '0') {
            $nama_bank = NULL;
        } else if ($row[28] == 'OCBC') {
            $nama_bank = 'BOCBC';
        } else if ($row[28] == 'BRI') {
            $nama_bank = 'BBRI';
        } else if ($row[28] == 'BCA') {
            $nama_bank = 'BBCA';
        } else if ($row[28] == 'MANDIRI') {
            $nama_bank = 'BMANDIRI';
        } else {
            $nama_bank = $row[28];
        }

        if ($row[29] == 'NULL' || $row[29] == '0') {
            $nomor_rekening = NULL;
        } else {
            $nomor_rekening = $row[29];
        }
        if ($row[30] == 'NULL' || $row[30] == '0') {
            $kategori_jabatan = NULL;
        } else if ($row[30] == 'SP') {
            $kategori_jabatan = 'sp';
        } else if ($row[30] == 'SPS') {
            $kategori_jabatan = 'sps';
        } else if ($row[30] == 'SIP') {
            $kategori_jabatan = 'sip';
        } else {
            $kategori_jabatan = $row[30];
        }
        if ($kategori_jabatan == 'NULL') {
            $departemen = NULL;
            $divisi = NULL;
            $bagian = NULL;
            $jabatan = NULL;
            $departemen1 = NULL;
            $divisi1 = NULL;
            $bagian1 = NULL;
            $jabatan1 = NULL;
            $departemen2 = NULL;
            $divisi2 = NULL;
            $bagian2 = NULL;
            $jabatan2 = NULL;
            $departemen3 = NULL;
            $divisi3 = NULL;
            $bagian3 = NULL;
            $jabatan3 = NULL;
            $departemen4 = NULL;
            $divisi4 = NULL;
            $bagian4 = NULL;
            $jabatan4 = NULL;
        } else {
            $departemen = Departemen::where('nama_departemen', $row[31])->where('holding', $kategori_jabatan)->value('id');
            $divisi = Divisi::where('nama_divisi', $row[32])->where('dept_id', $departemen)->where('holding', $kategori_jabatan)->value('id');
            $bagian = Bagian::where('nama_bagian', $row[33])->where('divisi_id', $divisi)->where('holding', $kategori_jabatan)->value('id');
            $jabatan = Jabatan::where('nama_jabatan', $row[34])->where('divisi_id', $divisi)->where('bagian_id', $bagian)->where('holding', $kategori_jabatan)->value('id');

            $departemen1 = Departemen::where('nama_departemen', $row[35])->where('holding', $kategori_jabatan)->value('id');
            $divisi1 = Divisi::where('nama_divisi', $row[36])->where('dept_id', $departemen1)->where('holding', $kategori_jabatan)->value('id');
            $bagian1 = Bagian::where('nama_bagian', $row[37])->where('divisi_id', $divisi1)->where('holding', $kategori_jabatan)->value('id');
            $jabatan1 = Jabatan::where('nama_jabatan', $row[38])->where('divisi_id', $divisi1)->where('bagian_id', $bagian1)->where('holding', $kategori_jabatan)->value('id');

            $departemen2 = Departemen::where('nama_departemen', $row[39])->where('holding', $kategori_jabatan)->value('id');
            $divisi2 = Divisi::where('nama_divisi', $row[40])->where('dept_id', $departemen2)->where('holding', $kategori_jabatan)->value('id');
            $bagian2 = Bagian::where('nama_bagian', $row[41])->where('divisi_id', $divisi2)->where('holding', $kategori_jabatan)->value('id');
            $jabatan2 = Jabatan::where('nama_jabatan', $row[42])->where('divisi_id', $divisi2)->where('bagian_id', $bagian2)->where('holding', $kategori_jabatan)->value('id');

            $departemen3 = Departemen::where('nama_departemen', $row[43])->where('holding', $kategori_jabatan)->value('id');
            $divisi3 = Divisi::where('nama_divisi', $row[44])->where('dept_id', $departemen3)->where('holding', $kategori_jabatan)->value('id');
            $bagian3 = Bagian::where('nama_bagian', $row[45])->where('divisi_id', $divisi3)->where('holding', $kategori_jabatan)->value('id');
            $jabatan3 = Jabatan::where('nama_jabatan', $row[46])->where('divisi_id', $divisi3)->where('bagian_id', $bagian3)->where('holding', $kategori_jabatan)->value('id');

            $departemen4 = Departemen::where('nama_departemen', $row[47])->where('holding', $kategori_jabatan)->value('id');
            $divisi4 = Divisi::where('nama_divisi', $row[48])->where('dept_id', $departemen4)->where('holding', $kategori_jabatan)->value('id');
            $bagian4 = Bagian::where('nama_bagian', $row[49])->where('divisi_id', $divisi4)->where('holding', $kategori_jabatan)->value('id');
            $jabatan4 = Jabatan::where('nama_jabatan', $row[50])->where('divisi_id', $divisi4)->where('bagian_id', $bagian4)->where('holding', $kategori_jabatan)->value('id');
        }
        if ($row[51] == 'NULL' || $row[51] == '0') {
            $bpjs_ketenagakerjaan = 'off';
        } else if ($row[51] == 'FALSE') {
            $bpjs_ketenagakerjaan = 'off';
        } else if ($row[51] == 'TRUE') {
            $bpjs_ketenagakerjaan = 'on';
        } else {
            $bpjs_ketenagakerjaan = $row[51];
        }
        if ($row[52] == 'NULL' || $row[52] == '0') {
            $no_bpjs_ketenagakerjaan = NULL;
        } else {
            $no_bpjs_ketenagakerjaan = $row[52];
        }
        if ($row[53] == 'NULL' || $row[53] == '0') {
            $bpjs_pensiun = 'off';
        } else if ($row[53] == 'FALSE') {
            $bpjs_pensiun = 'off';
        } else if ($row[53] == 'TRUE') {
            $bpjs_pensiun = 'on';
        } else {
            $bpjs_pensiun = $row[53];
        }
        if ($row[54] == 'NULL' || $row[54] == '0') {
            $bpjs_kesehatan = 'off';
        } else if ($row[54] == 'FALSE') {
            $bpjs_kesehatan = 'off';
        } else if ($row[54] == 'TRUE') {
            $bpjs_kesehatan = 'on';
        } else {
            $bpjs_kesehatan = $row[54];
        }
        if ($row[55] == 'NULL' || $row[55] == '0') {
            $no_bpjs_kesehatan = NULL;
        } else {
            $no_bpjs_kesehatan = $row[55];
        }
        if ($row[56] == 'NULL' || $row[56] == '0') {
            $kelas_bpjs = NULL;
        } else {
            $kelas_bpjs = $row[56];
        }
        if ($row[58] == 'NULL' || $row[58] == '0') {
            $ptkp = NULL;
        } else {
            $ptkp = $row[58];
        }

        try {
            return new Karyawan([
                "nomor_identitas_karyawan"                      => $nomor_identitas_karyawan,
                "name"                                          => $name,
                "nik"                                           => $nik,
                "npwp"                                          => $npwp,
                "fullname"                                      => $fullname,
                "motto"                                         => $motto,
                "email"                                         => $email,
                "telepon"                                       => $telepon,
                "tempat_lahir"                                  => $tempat_lahir,
                "tgl_lahir"                                     => $tgl_lahir,
                // 10
                "gender"                                        => $kelamin,
                "tgl_join"                                      => $tgl_join,
                "status_nikah"                                  => $status_nikah,
                "provinsi"                                      => $provinsi,
                "kabupaten"                                     => $kabupaten,
                "kecamatan"                                     => $kecamatan,
                "desa"                                          => $desa,
                "rt"                                            => $rt,
                "rw"                                            => $rw,
                "alamat"                                        => $alamat,
                // 20
                "kuota_cuti_tahunan"                            => $kuota_cuti_tahunan,
                "kategori"                                      => $kategori,
                "lama_kontrak_kerja"                            => $lama_kontrak_kerja,
                "tgl_mulai_kontrak"                             => $tgl_mulai,
                "tgl_selesai_kontrak"                           => $tgl_selesai,
                "kontrak_kerja"                                 => $kontrak_kerja,
                "penempatan_kerja"                              => $penempatan_kerja,
                "site_job"                                      => $site_job,
                "nama_bank"                                     => $nama_bank,
                "nomor_rekening"                                => $nomor_rekening,
                // 30
                "kategori_jabatan"                              => $kategori_jabatan,
                "bpjs_ketenagakerjaan"                          => $bpjs_ketenagakerjaan,
                "no_bpjs_ketenagakerjaan"                       => $no_bpjs_ketenagakerjaan,
                "bpjs_pensiun"                                  => $bpjs_pensiun,
                "bpjs_kesehatan"                                => $bpjs_kesehatan,
                "no_bpjs_kesehatan"                             => $no_bpjs_kesehatan,
                "kelas_bpjs"                                    => $kelas_bpjs,
                "ptkp"                                          => $ptkp,
                "status_aktif"                                  => 'AKTIF',
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
            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage(), 1500);
        }
    }
}
