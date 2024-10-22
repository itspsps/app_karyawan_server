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
use Ramsey\Uuid\Uuid;

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
    public function save_capture_profile(Request $request)
    {
        // dd($request->all());
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $img = $request->image;
        $folderPath = "foto_karyawan/";
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName =  date('y-m-d') . '-' . Uuid::uuid4() . '.' . $image_type;
        $file = $folderPath . $fileName;
        // dd($file);
        Storage::put($file, $image_base64);

        $update_foto                = Karyawan::where('id', $user_karyawan->id)->first();
        $update_foto->foto_karyawan = $fileName;
        $update_foto->update();
        $request->session()->flash('profile_update_success');
        return redirect('profile');
    }
}
