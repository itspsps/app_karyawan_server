<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Penugasan;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $user_karyawan  = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $dataizin       = Izin::with('User')->where('id_approve_atasan', $user_karyawan->id)
            ->where('status_izin', 1)
            // ->whereNotNull('ttd_pengajuan')
            ->get();
        // get atasan tingkat 
        $datacuti_tingkat1       = Cuti::with('KategoriCuti')
            ->where('status_cuti', 1)
            ->join('karyawans', 'karyawans.id', '=', 'cutis.user_id')
            ->where('id_user_atasan', $user_karyawan->id)
            ->whereNotNull('ttd_user')
            ->select('cutis.*', 'karyawans.name', 'karyawans.foto_karyawan')
            ->get();
        $datacuti_tingkat2       = Cuti::with('KategoriCuti')
            ->where('status_cuti', 2)
            ->join('karyawans', 'karyawans.id', '=', 'cutis.user_id')
            ->where('id_user_atasan2', $user_karyawan->id)
            ->whereNotNull('ttd_user')
            ->select('cutis.*', 'karyawans.name', 'karyawans.foto_karyawan')
            ->get();
        // dd($datacuti_tingkat2);
        $datapenugasan  = Penugasan::join('karyawans', 'karyawans.id', 'penugasans.id_user')
            ->where('penugasans.status_penugasan', '!=', 5)
            ->where('id_diminta_oleh', $user_karyawan->id)
            ->orWhere('id_disahkan_oleh', $user_karyawan->id)
            ->orWhere('id_user_hrd', $user_karyawan->id)
            ->orWhere('id_user_finance', $user_karyawan->id)
            ->select('penugasans.*', 'karyawans.name')
            ->get();

        // dd($user);
        // dd($dataizin);
        // dd($datacuti_tingkat1);
        // dd($datacuti_tingkat2);
        // dd($datapenugasan);
        return view(
            'users.approval.index',
            [
                'dataizin'          => $dataizin,
                'user_karyawan'     => $user_karyawan,
                'datacuti_tingkat1' => $datacuti_tingkat1,
                'datacuti_tingkat2' => $datacuti_tingkat2,
                'datapenugasan'     => $datapenugasan,
            ]
        );
    }
}
