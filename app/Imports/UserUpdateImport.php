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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\Province;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UserUpdateImport implements ToCollection, WithStartRow
{
    /**
     * @param array $row
     *
     *
     */
    public function startRow(): int
    {
        return 2;
    }
    public function collection(Collection $rows)
    {
        $holding = request()->segment(count(request()->segments()));
        foreach ($rows as $row) {
            // dd($row);
            if ($row[11] == NULL) {
                $tgl_lahir = NULL;
            } else {
                $tgl_lahir = Carbon::createFromFormat('Y-m-d', $row[11])->format('Y-m-d');
                // dd($tgl_mulai);
            }
            if ($row[13] == NULL) {
                $tgl_join = NULL;
            } else {
                $tgl_join = Carbon::createFromFormat('Y-m-d', $row[13])->format('Y-m-d');
                // dd($tgl_mulai);
            }
            if ($row[25] == NULL) {
                $tgl_mulai = NULL;
            } else {
                $tgl_mulai = Carbon::createFromFormat('Y-m-d', $row[25])->format('Y-m-d');
                // dd($tgl_mulai);
            }
            if ($row[26] == NULL) {
                // dd($row);
                $tgl_selesai = NULL;
            } else {
                $tgl_selesai = Carbon::createFromFormat('Y-m-d', $row[26])->format('Y-m-d');
            }
            if ($row[32] == NULL) {
                $kategori_holding = $holding;
            } else {
                $kategori_holding = $row[32];
            }
            $departemen = Departemen::where('nama_departemen', $row[33])->where('holding', $kategori_holding)->value('id');
            $divisi = Divisi::where('nama_divisi', $row[34])->where('holding', $kategori_holding)->value('id');
            $bagian = Bagian::where('nama_bagian', $row[35])->where('holding', $kategori_holding)->value('id');

            $departemen1 = Departemen::where('nama_departemen', $row[37])->where('holding', $kategori_holding)->value('id');
            $divisi1 = Divisi::where('nama_divisi', $row[38])->where('holding', $kategori_holding)->value('id');
            $bagian1 = Bagian::where('nama_bagian', $row[39])->where('holding', $kategori_holding)->value('id');

            $departemen2 = Departemen::where('nama_departemen', $row[41])->where('holding', $kategori_holding)->value('id');
            $divisi2 = Divisi::where('nama_divisi', $row[42])->where('holding', $kategori_holding)->value('id');
            $bagian2 = Bagian::where('nama_bagian', $row[43])->where('holding', $kategori_holding)->value('id');

            $departemen3 = Departemen::where('nama_departemen', $row[45])->where('holding', $kategori_holding)->value('id');
            $divisi3 = Divisi::where('nama_divisi', $row[46])->where('holding', $kategori_holding)->value('id');
            $bagian3 = Bagian::where('nama_bagian', $row[47])->where('holding', $kategori_holding)->value('id');

            $departemen4 = Departemen::where('nama_departemen', $row[49])->where('holding', $kategori_holding)->value('id');
            $divisi4 = Divisi::where('nama_divisi', $row[50])->where('holding', $kategori_holding)->value('id');
            $bagian4 = Bagian::where('nama_bagian', $row[51])->where('holding', $kategori_holding)->value('id');
            try {
                return  User::where('id', $row[0])->update([
                    "nomor_identitas_karyawan" => $row[1],
                    "name" => $row[2],
                    "nik" => $row[3],
                    "npwp" => $row[4],
                    "fullname" => $row[5],
                    "motto" => $row[6],
                    "email" => $row[7],
                    "telepon" => $row[8],
                    "username" => $row[9],
                    "tempat_lahir" => $row[10],
                    "tgl_lahir" => $tgl_lahir,
                    "gender" => $row[12],
                    "tgl_join" => $tgl_join,
                    "status_nikah" => $row[14],
                    "provinsi" => Province::where('name', $row[15])->value('code'),
                    "kabupaten" => Cities::where('name', $row[16])->value('code'),
                    "kecamatan" =>  District::where('name', $row[17])->value('code'),
                    "desa" => Village::where('name', $row[18])->value('code'),
                    "rt" => $row[19],
                    "rw" => $row[20],
                    "detail_alamat" => $row[21],
                    "alamat" => $row[21],
                    "kuota_cuti_tahunan" => $row[22],
                    "kategori" => $row[23],
                    "lama_kontrak_kerja" => $row[24],
                    "tgl_mulai_kontrak" => $tgl_mulai,
                    "tgl_selesai_kontrak" => $tgl_selesai,
                    "kontrak_kerja" => $row[27],
                    "penempatan_kerja" => $row[28],
                    "site_job" => $row[29],
                    "nama_bank" => $row[30],
                    "nomor_rekening" => $row[31],
                    "kategori_jabatan" => $row[32],
                    "dept_id" => Departemen::where('id', $departemen)->value('id'),
                    "divisi_id" => Divisi::where('id', $divisi)->value('id'),
                    "bagian_id" => Bagian::where('id', $bagian)->value('id'),
                    "jabatan_id" => Jabatan::where('nama_jabatan', $row[36])->where('divisi_id', $divisi)->where('bagian_id', $bagian)->value('id'),
                    "dept1_id" => Departemen::where('id', $departemen1)->value('id'),
                    "divisi1_id" => Divisi::where('id', $divisi1)->value('id'),
                    "jabatan1_id" => Jabatan::where('nama_jabatan', $row[40])->where('divisi_id', $divisi1)->where('bagian_id', $bagian1)->value('id'),
                    "dept2_id" => Departemen::where('id', $departemen2)->value('id'),
                    "divisi2_id" => Divisi::where('id', $divisi2)->value('id'),
                    "jabatan2_id" => Jabatan::where('nama_jabatan', $row[44])->where('divisi_id', $divisi2)->where('bagian_id', $bagian2)->value('id'),
                    "dept3_id" => Departemen::where('id', $departemen3)->value('id'),
                    "divisi3_id" => Divisi::where('id', $divisi3)->value('id'),
                    "jabatan3_id" => Jabatan::where('nama_jabatan', $row[48])->where('divisi_id', $divisi3)->where('bagian_id', $bagian3)->value('id'),
                    "dept4_id" => Departemen::where('id', $departemen4)->value('id'),
                    "divisi4_id" => Divisi::where('id', $divisi4)->value('id'),
                    "jabatan4_id" => Jabatan::where('nama_jabatan', $row[52])->where('divisi_id', $divisi4)->where('bagian_id', $bagian4)->value('id'),
                    "bpjs_ketenagakerjaan" => $row[53],
                    "no_bpjs_ketenagakerjaan" => $row[54],
                    "bpjs_pensiun" => $row[55],
                    "bpjs_kesehatan" => $row[56],
                    "no_bpjs_kesehatan" => $row[57],
                    "kelas_bpjs" => $row[58],
                    "ptkp" => $row[59]
                ]);
            } catch (\Exception $e) {

                return redirect()->back()->with('error', $e->getMessage(), 10000);
            }
        }
    }
}
