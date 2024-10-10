<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Cities;
use App\Models\City;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
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
            $karyawan_id = NULL;
        } else {
            $karyawan_id = Karyawan::where('name', $row[0])->value('id');
        }
        if ($row[1] == NULL || $row[1] == 0) {
            $username = NULL;
        } else {
            $username = $row[1];
        }
        if ($row[2] == NULL || $row[2] == 0) {
            $password = NULL;
        } else {
            $password = Hash::make($row[2]);
        }
        if ($row[3] == NULL || $row[3] == 0) {
            $level = NULL;
        } else {
            $level = $row[3];
        }

        try {
            return new User([
                "karyawan_id"                                   => $karyawan_id,
                "username"                                      => $username,
                "password"                                      => $password,
                "is_admin"                                      => $level,

            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage(), 1500);
        }
    }
}
