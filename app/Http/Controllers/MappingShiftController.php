<?php

namespace App\Http\Controllers;

use App\Models\MappingShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MappingShiftController extends Controller
{
    function index()
    {
        $shift = Shift::whereNotIn('nama_shift', ['Office'])->get();
        $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();


        $user_shift = User::with(['MappingShift' => function ($query) {
            $query->with('Koordinator');
        }])->where('penempatan_kerja', Auth::user()->penempatan_kerja)
            ->where('kategori', 'Karyawan Harian')->get();
        // dd($koordinator);
        dd($user_shift);
        // dd(Auth::user()->kontrak_kerja);
        return view('users.mapping_shift.index', [
            'user' => $user,
            'shift' => $shift,
            'user_shift' => $user_shift
        ]);
    }
    public function prosesAddMappingShift(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');


        // dd($request->all());

        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);


        foreach ($daterange as $date) {
            $tanggal = $date->format("Y-m-d");

            if ($request["shift"] == '3ac53e9a-84d6-445e-9b48-fdb8a6b02cb2') {
                $request["status_absen"] = "Libur";
            } else {
                $request["status_absen"] = "Tidak Masuk";
            }

            $request["tanggal"] = $tanggal;

            $validatedData = $request->validate([
                'id_user' => 'required',
                'shift' => 'required',
                'tanggal' => 'required',
                'lokasi_bekerja' => 'required',
                'koordinator' => 'required',
                'status_absen' => 'required',
            ]);

            MappingShift::insert([
                'user_id' => User::where('id', $validatedData['id_user'])->value('id'),
                'shift_id' => Shift::where('id', $validatedData['shift'])->value('id'),
                'koordinator_id' => User::where('id', $validatedData['koordinator'])->value('id'),
                'lokasi_bekerja' => $validatedData['lokasi_bekerja'],
                'tanggal' => $validatedData['tanggal'],
                'status_absen' => $validatedData['status_absen'],
            ]);
        }
        $request->session()->flash('mappingshiftsuccess');
        return redirect('/mapping_shift/dashboard/');
    }
    public function prosesEditMappingShift(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');


        // dd($request->all());

        if ($request["shift_update"] == '3ac53e9a-84d6-445e-9b48-fdb8a6b02cb2') {
            $request["status_absen"] = "Libur";
        }


        $validatedData = $request->validate([
            'shift_update' => 'required',
            'tanggal_update' => 'required',
        ]);

        MappingShift::where('id', $request['id_mapping'])->update([
            'shift_id' => Shift::where('id', $validatedData['shift_update'])->value('id'),
            'tanggal' => $validatedData['tanggal_update'],
            'status_absen' => $request['status_absen'],
        ]);
        $request->session()->flash('mappingshiftupdatesuccess');
        return redirect('/mapping_shift/dashboard/');
    }
}
