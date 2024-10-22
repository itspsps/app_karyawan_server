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

class ProfileUserController extends Controller
{
    public function index()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.index', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan
        ]);
    }
    public function change_photoprofile_camera()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.change_photoprofile_camera', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan
        ]);
    }
}
