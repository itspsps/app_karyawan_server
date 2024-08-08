<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Cities;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\MappingShift;
use App\Models\Shift;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\Province;
use Maatwebsite\Excel\Concerns\ToModel;

class AbsensiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        if ($row[6] == NULL) {
            $tgl_masuk = NULL;
        } else {
            $tgl_masuk = Carbon::createFromFormat('d/m/Y', $row[6])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[7] == NULL) {
            $jam_masuk = NULL;
        } else {
            $jam_masuk = Carbon::createFromFormat('H:i:s', $row[7])->format('H:i:s');
            // dd($tgl_mulai);
        }
        if ($row[8] == NULL) {
            $telat = NULL;
        } else {
            $telat = Carbon::createFromFormat('H:i:s', $row[8])->format('H:i:s');
            // dd($tgl_mulai);
        }
        if ($row[9] == NULL) {
            $tgl_pulang = NULL;
        } else {
            $tgl_pulang = Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[10] == NULL) {
            $jam_pulang = NULL;
        } else {
            $jam_pulang = Carbon::createFromFormat('H:i:s', $row[10])->format('H:i:s');
            // dd($tgl_mulai);
        }
        if ($row[11] == NULL) {
            $pulang_cepat = NULL;
        } else {
            $pulang_cepat = Carbon::createFromFormat('H:i:s', $row[11])->format('H:i:s');
            // dd($tgl_mulai);
        }
        if ($row[12] == NULL) {
            $total_jam_kerja = NULL;
        } else {
            $total_jam_kerja = Carbon::createFromFormat('H:i:s', $row[12])->format('H:i:s');
            // dd($tgl_mulai);
        }
        if (User::where('name', $row[0])->value('id') == NULL) {
            $user_id = 'NOT ID';
        } else {
            $user_id = User::where('name', $row[0])->value('id');
        }
        if (User::where('name', $row[0])->value('nomor_identitas_karyawan') == NULL) {
            $nik_karyawan = 'NOT NIK';
        } else {
            $nik_karyawan = User::where('name', $row[0])->value('nomor_identitas_karyawan');
        }
        return new MappingShift([
            "user_id" => $user_id,
            "nik_karyawan" => $nik_karyawan,
            "nama_karyawan" => $row[0],
            "shift_id" => Shift::where('id', $row[1])->value('id'),
            "nama_shift" => $row[2],
            "koordinator_id" => User::where('name', $row[4])->value('id'),
            "nama_koordinator" => User::where('name', $row[4])->value('name'),
            "lokasi_bekerja" => $row[6],
            "tanggal_masuk" => $tgl_masuk,
            "jam_absen" => $jam_masuk,
            "telat" => $telat,
            "lat_absen" => NULL,
            "long_absen" => NULL,
            "jarak_masuk" => NULL,
            "foto_jam_absen" => NULL,
            "tanggal_pulang" => $tgl_pulang,
            "jam_pulang" => $jam_pulang,
            "pulang_cepat" => $pulang_cepat,
            "lembur" => NULL,
            "foto_jam_pulang" => NULL,
            "lat_pulang" => NULL,
            "long_pulang" => NULL,
            "jarak_pulang" => NULL,
            "total_jam_kerja" => $total_jam_kerja,
            "status_absen" => $row[13],
            "keterangan_absensi" => $row[14],
            "kelengkapan_absensi" => $row[15],
            "keterangan_izin" => $row[15],
            "kelengkapan_absensi" => $row[16],
            "keterangan_cuti" => $row[17],
            "keterangan_dinas" => $row[18],
        ]);
    }
}
