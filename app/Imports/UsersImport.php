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
        if ($row[5] == NULL || $row[5] == 0) {
            $status_aktif = NULL;
        } else if ($row[5] == 'Aktif' || $row[5] == 'aktif') {
            $status_aktif = 'AKTIF';
        } else if ($row[5] == 'Non Aktif' || $row[5] == 'non aktif') {
            $status_aktif = 'NON AKTIF';
        } else {
            $status_aktif = $row[5];
        }
        if ($row[6] == NULL || $row[6] == 0) {
            $alasan = NULL;
        } else {
            if ($status_aktif == 'AKTIF') {
                $alasan = NULL;
            } else if ($status_aktif == 'NON AKTIF') {
                $alasan = $row[6];
            } else {
                $alasan = $row[6];
            }
        }
        try {
            return new User([
                "karyawan_id"                                   => $karyawan_id,
                "username"                                      => $username,
                "password"                                      => $password,
                "password_show"                                 => $password_show,
                "is_admin"                                      => $level,
                "status_aktif"                                  => $status_aktif,
                "alasan"                                        => $alasan,

            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage(), 1500);
        }
    }
}
