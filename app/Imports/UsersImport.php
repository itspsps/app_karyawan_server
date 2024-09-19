<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Cities;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\Province;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $holding = request()->segment(count(request()->segments()));
        if ($row[]);
        $divisi = Divisi::where('nama_divisi', $row[38])->value('id');
        if ($row[13] == NULL) {
            $tgl_lahir = NULL;
        } else {
            $tgl_lahir = Carbon::createFromFormat('d/m/Y', $row[13])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[15] == NULL) {
            $tgl_join = NULL;
        } else {
            $tgl_join = Carbon::createFromFormat('d/m/Y', $row[15])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[29] == NULL) {
            $tgl_mulai = NULL;
        } else {
            $tgl_mulai = Carbon::createFromFormat('d/m/Y', $row[29])->format('Y-m-d');
            // dd($tgl_mulai);
        }
        if ($row[30] == NULL) {
            // dd($row);
            $tgl_selesai = NULL;
        } else {
            $tgl_selesai = Carbon::createFromFormat('d/m/Y', $row[30])->format('Y-m-d');
        }
        try {
            return new User([
                "nomor_identitas_karyawan" => $row[1],
                "name" => $row[2],
                "nik" => $row[3],
                "npwp" => $row[4],
                "fullname" => $row[5],
                "motto" => $row[6],
                "foto_karyawan" => $row[7],
                "email" => $row[8],
                "telepon" => $row[9],
                "username" => $row[10],
                "password" => Hash::make($row[11]),
                "tempat_lahir" => $row[12],
                "tgl_lahir" => $tgl_lahir,
                "gender" => $row[14],
                "tgl_join" => $tgl_join,
                "status_nikah" => $row[16],
                "provinsi" => Province::where('name', $row[17])->value('code'),
                "kabupaten" => Cities::where('name', $row[18])->value('code'),
                "kecamatan" =>  District::where('name', $row[19])->value('code'),
                "desa" => Village::where('name', $row[20])->value('code'),
                "rt" => $row[21],
                "rw" => $row[22],
                "detail_alamat" => $row[24],
                "alamat" => $row[23],
                "kuota_cuti_tahunan" => $row[25],
                "is_admin" => $row[26],
                "kategori" => $row[27],
                "lama_kontrak_kerja" => $row[28],
                "tgl_mulai_kontrak" => $tgl_mulai,
                "tgl_selesai_kontrak" => $tgl_selesai,
                "kontrak_kerja" => $row[31],
                "kontrak_site" => $row[32],
                "penempatan_kerja" => $row[33],
                "site_job" => $row[34],
                "nama_bank" => $row[35],
                "nomor_rekening" => $row[36],
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
