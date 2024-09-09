<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Izin;
use App\Models\Penugasan;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $user           = Auth::user()->id;
        $dataizin       = Izin::with('User')->where('id_approve_atasan', $user)
            ->where('status_izin', 1)
            // ->whereNotNull('ttd_pengajuan')
            ->get();
        // get atasan tingkat 
        $datacuti_tingkat1       = Cuti::with('KategoriCuti')
            ->where('status_cuti', 1)
            ->join('users', 'users.id', '=', 'cutis.user_id')
            ->where('id_user_atasan', $user)
            ->whereNotNull('ttd_user')
            ->select('cutis.*', 'users.name', 'users.foto_karyawan')
            ->get();
        $datacuti_tingkat2       = Cuti::with('KategoriCuti')
            ->where('status_cuti', 2)
            ->join('users', 'users.id', '=', 'cutis.user_id')
            ->where('id_user_atasan2', $user)
            ->whereNotNull('ttd_user')
            ->select('cutis.*', 'users.name', 'users.foto_karyawan')
            ->get();
        // dd($datacuti_tingkat2);
        $datapenugasan  = Penugasan::join('users', 'users.id', 'penugasans.id_user')
            ->where('penugasans.status_penugasan', '!=', 5)
            ->where('id_diminta_oleh', $user)
            ->orWhere('id_disahkan_oleh', $user)
            ->orWhere('id_user_hrd', $user)
            ->orWhere('id_user_finance', $user)
            ->select('penugasans.*', 'users.fullname')
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
                'datacuti_tingkat1' => $datacuti_tingkat1,
                'datacuti_tingkat2' => $datacuti_tingkat2,
                'datapenugasan'     => $datapenugasan,
            ]
        );
    }
}
