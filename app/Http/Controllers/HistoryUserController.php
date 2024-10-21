<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryUserController extends Controller
{
    public function index()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $history_absensi = ActivityLog::where('kategory_activity', 'ABSENSI')->where('user_id', Auth::user()->id)->get();
        $history_izin = ActivityLog::where('kategory_activity', 'IZIN')->where('user_id', Auth::user()->id)->get();
        $history_cuti = ActivityLog::where('kategory_activity', 'CUTI')->where('user_id', Auth::user()->id)->get();
        $history_penugasan = ActivityLog::where('kategory_activity', 'PENUGASAN')->where('user_id', Auth::user()->id)->get();
        return view('users.history.index', [
            'title' => 'History',
            'user_karyawan' => $user_karyawan,
            'history_absensi' => $history_absensi,
            'history_izin' => $history_izin,
            'history_cuti' => $history_cuti,
            'history_penugasan' => $history_penugasan,
        ]);
    }
}
