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

class UserUpdateImport implements ToCollection
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
                $id = $row[0];
            }
            if ($row[1] == NULL || $row[1] == 0) {
                $karyawan_id = NULL;
            } else {
                $karyawan_id = Karyawan::where('name', $row[1])->value('id');
            }
            // dd($karyawan_id);
            if ($row[2] == NULL || $row[2] == 0) {
                $username = NULL;
            } else {
                $username = $row[2];
            }
            if ($row[3] == NULL || $row[3] == 0) {
                $password = NULL;
            } else {
                $password = Hash::make($row[3]);
            }
            if ($row[4] == NULL || $row[4] == 0) {
                $level = NULL;
            } else {
                $level = $row[4];
            }
            try {
                User::where('id', $id)->update([
                    "karyawan_id"                                   => $karyawan_id,
                    "username"                                      => $username,
                    "password"                                      => $password,
                    "is_admin"                                      => $level,
                    "updated_at"                                    => now(),
                ]);
            } catch (\Throwable $e) {

                return redirect()->back()->with('error', $e->getMessage(), 1500);
            }
        }
    }
}
