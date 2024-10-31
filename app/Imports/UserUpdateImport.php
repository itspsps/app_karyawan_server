<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Cities;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
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
        return 3;
    }
    public function collection(Collection $rows)
    {
        // dd($row[2]=> PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        // dd($rows);
        foreach ($rows as $row) {
            if ($row[0] == NULL || $row[0] == 0) {
                $id = NULL;
            } else {
                $id = Karyawan::where('nomor_identitas_karyawan', $row[0])->value('nomor_identitas_karyawan');
            }
            if ($row[1] == NULL || $row[1] == 0) {
                $karyawan_id = NULL;
            } else {
                $karyawan_id = Karyawan::where('nomor_identitas_karyawan', $id)->value('id');
            }
            // dd($karyawan_id);
            if ($row[2] == NULL || $row[2] == 0) {
                $username = NULL;
            } else {
                $username = $row[2];
            }
            if ($row[3] == NULL || $row[3] == 0) {
                $password = NULL;
                $password_show = $password;
            } else {
                $password = Hash::make($row[3]);
                $password_show = $row[3];
            }
            if ($row[4] == NULL || $row[4] == 0) {
                $level = NULL;
            } else if ($row[4] == 'Karyawan' || $row[4] == 'karyawan') {
                $level = 'user';
            } else {
                $level = $row[4];
            }
            // dd($row[0], $id);
            User::Join('karyawans', 'karyawans.id', 'users.karyawan_id')
                ->where('karyawans.nomor_identitas_karyawan', $id)->update([
                    "karyawan_id"                                   => $karyawan_id,
                    "username"                                      => $username,
                    "password"                                      => $password,
                    "password_show"                                 => $password_show,
                    "is_admin"                                      => $level,
                    "updated_at"                                    => now(),
                ]);
        }
    }
}
