<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Cities;
use App\Models\District;
use App\Models\Karyawan;
use App\Models\Provincies;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Intervention\Image\Laravel\Facades\Image;

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
        $img = $request->file('gallery_image');
        $folderPath = "foto_karyawan/";
        // $image_parts = explode(";base64,", $img);
        // $image_type_aux = explode("image/", $image_parts[0]);
        // $image_type = $image_type_aux[1];
        // $image_base64 = base64_decode($image_parts[1]);
        $fileName =  date('y-m-d') . '-' . Uuid::uuid4() . '.jpeg';
        $file = $folderPath . $fileName;
        $width = 500;
        $height = null;
        $img = Image::read($img->getRealPath());
        // dd($img);
        $img->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path('../storage/app/public/foto_karyawan/') . $fileName);
        // Storage::disk('s3')->put("{$app}/{$directory}/{$fileName}", $img, 'public');
        // Storage::put($file, $img, 'public');
        if ($img) {
            $update_foto                = Karyawan::where('id', $user_karyawan->id)->first();
            $update_foto->foto_karyawan = $fileName;
            $update_foto->update();
            $request->session()->flash('profile_update_success');
        } else {
            $request->session()->flash('profile_update_error');
        }
        return redirect('profile');
    }
    public function detail_profile(Request $request)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.detail_profile', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan,
        ]);
    }
    public function save_detail_profile(Request $request)
    {
        // dd($request->all());
        if ($request->status_nomor == "tidak") {
            $status_nomor = 'required';
        } else if ($request->status_nomor == "ya") {
            $status_nomor = 'nullable';
        } else {
            $status_nomor = 'required';
        }
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format email salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $credentials = $request->validate([
            'name' => 'required|max:255',
            'nik' => 'required|max:16',
            'unique:karyawans,nik,' . $request->nik,
            'email' => 'max:255|nullable',
            'email:rfc,dns',
            'email_address',
            'unique:karyawans,email,' . $request->email,
            'telepon' => 'max:13',
            'nullable',
            'min:11',
            'status_nomor' => 'nullable',
            'nomor_wa' => 'max:13|' . $status_nomor . '|min:11',
            'tempat_lahir' => 'required|max:255',
            'tgl_lahir' => 'required|max:255',
            'golongan_darah' => 'required|max:255',
            'agama' => 'required|max:255',
            'gender' => 'required',
            'status_nikah' => 'required',
            'strata_pendidikan' => 'required|max:255',
            'instansi_pendidikan' => 'required|max:255',
            'jurusan_akademik' => 'nullable',
        ], $customMessages);
        if ($credentials['status_nomor'] == '') {
            $status_nomor = 'tidak';
        } else {
            $status_nomor = $credentials['status_nomor'];
        }
        $query = Karyawan::where('id', $request->karyawan_id)->first();
        $query->nik                                   = $credentials['nik'];
        $query->name                                  = $credentials['name'];
        $query->email                                 = $credentials['email'];
        $query->telepon                               = $credentials['telepon'];
        $query->status_nomor                          = $credentials['status_nomor'];
        $query->tempat_lahir                          = $credentials['tempat_lahir'];
        $query->tgl_lahir                             = $credentials['tgl_lahir'];
        $query->golongan_darah                        = $credentials['golongan_darah'];
        $query->agama                                 = $credentials['agama'];
        $query->gender                                = $credentials['gender'];
        $query->status_nikah                          = $credentials['status_nikah'];
        $query->strata_pendidikan                     = $credentials['strata_pendidikan'];
        $query->instansi_pendidikan                   = $credentials['instansi_pendidikan'];
        $query->jurusan_akademik                      = $credentials['jurusan_akademik'];
        $query->update();
        if ($query) {
            $request->session()->flash('profile_update_success');
        } else {
            $request->session()->flash('profile_update_error');
        }
        return redirect('profile');
    }
    public function detail_alamat(Request $request)
    {
        $data_provinsi = Provincies::orderBy('name', 'ASC')->get();
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.detail_alamat', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan,
            'data_provinsi' => $data_provinsi,
        ]);
    }
    public function save_detail_alamat(Request $request)
    {
        // dd($request->all());
        if ($request->status_alamat == "ya") {
            $provinsi_domisili = 'nullable';
            $kabupaten_domisili = 'nullable';
            $kecamatan_domisili = 'nullable';
            $desa_domisili = 'nullable';
            $rt_domisili = 'nullable|max:255';
            $rw_domisili = 'nullable|max:255';
            $alamat_domisili = 'nullable|max:255';
        } else if ($request->status_alamat == "tidak") {
            $provinsi_domisili = 'required';
            $kabupaten_domisili = 'required';
            $kecamatan_domisili = 'required';
            $desa_domisili = 'required';
            $rt_domisili = 'required|max:255';
            $rw_domisili = 'required|max:255';
            $alamat_domisili = 'required|max:255';
        } else if ($request->status_alamat == NULL) {
            $provinsi_domisili = 'nullable';
            $kabupaten_domisili = 'nullable';
            $kecamatan_domisili = 'nullable';
            $desa_domisili = 'nullable';
            $rt_domisili = 'nullable|max:255';
            $rw_domisili = 'nullable|max:255';
            $alamat_domisili = 'nullable|max:255';
        }
        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format email salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $credentials = $request->validate([
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'rt' => 'required|max:255',
            'rw' => 'required|max:255',
            'alamat' => 'required|max:255',
            'status_alamat' => 'nullable|max:11',
            'provinsi_domisili' => $provinsi_domisili,
            'kabupaten_domisili' => $kabupaten_domisili,
            'kecamatan_domisili' => $kecamatan_domisili,
            'desa_domisili' => $desa_domisili,
            'rt_domisili' => $rt_domisili,
            'rw_domisili' => $rw_domisili,
            'alamat_domisili' => $alamat_domisili,
        ], $customMessages);

        if ($credentials['status_alamat'] == "tidak") {

            $provinsi = Provincies::where('code', $credentials['provinsi'])->value('code');
            $provinsi1 = Provincies::where('code', $credentials['provinsi_domisili'])->value('code');
            $kabupaten = Cities::where('code', $credentials['kabupaten'])->value('code');
            $kabupaten1 = Cities::where('code', $credentials['kabupaten_domisili'])->value('code');
            $kecamatan = District::where('code', $credentials['kecamatan'])->value('code');
            $kecamatan1 = District::where('code', $credentials['kecamatan_domisili'])->value('code');
            $desa = Village::where('code', $credentials['desa'])->value('code');
            $desa1 = Village::where('code', $credentials['desa_domisili'])->value('code');
            $detail_alamat = Provincies::where('code', $provinsi)->value('name') . ' , ' . Cities::where('code', $kabupaten)->value('name') . ' , ' . District::where('code', $kecamatan)->value('name') . ' , ' . Village::where('code', $desa)->value('name') . ' , RT. ' . $credentials['rt'] . ' , RW. ' . $credentials['rw'] . ' , ' . $credentials['alamat'];
            $detail_alamat1 = Provincies::where('code', $provinsi1)->value('name') . ' , ' . Cities::where('code', $kabupaten1)->value('name') . ' , ' . District::where('code', $kecamatan1)->value('name') . ' , ' . Village::where('code', $desa1)->value('name') . ' , RT. ' . $credentials['rt_domisili'] . ' , RW. ' . $credentials['rw_domisili'] . ' , ' . $credentials['alamat_domisili'];
        } else {
            $provinsi = Provincies::where('code', $credentials['provinsi'])->value('code');
            $provinsi1 = $provinsi;
            $kabupaten = Cities::where('code', $credentials['kabupaten'])->value('code');
            $kabupaten1 = $kabupaten;
            $kecamatan = District::where('code', $credentials['kecamatan'])->value('code');
            $kecamatan1 = $kecamatan;
            $desa = Village::where('code', $credentials['desa'])->value('code');
            $desa1 = $desa;
            $detail_alamat = Provincies::where('code', $provinsi)->value('name') . ' , ' . Cities::where('code', $kabupaten)->value('name') . ' , ' . District::where('code', $kecamatan)->value('name') . ' , ' . Village::where('code', $desa)->value('name') . ' , RT. ' . $credentials['rt'] . ' , RW. ' . $credentials['rw'] . ' , ' . $credentials['alamat'];
            $detail_alamat1 = $detail_alamat;
        }
        $query                                        = Karyawan::where('id', $request->karyawan_id)->first();
        $query->provinsi                              = $provinsi;
        $query->kabupaten                             = $kabupaten;
        $query->kecamatan                             = $kecamatan;
        $query->desa                                  = $desa;
        $query->rt                                    = $credentials['rt'];
        $query->rw                                    = $credentials['rw'];
        $query->detail_alamat                         = $detail_alamat;
        $query->detail_alamat_domisili                = $detail_alamat1;
        $query->alamat                                = $credentials['alamat'];
        $query->status_alamat                         = $credentials['status_alamat'];
        $query->provinsi_domisili                     = $provinsi1;
        $query->kabupaten_domisili                    = $kabupaten1;
        $query->kecamatan_domisili                    = $kecamatan1;
        $query->desa_domisili                         = $desa1;
        $query->update();
        if ($query) {
            $request->session()->flash('profile_update_success');
        } else {
            $request->session()->flash('profile_update_error');
        }
        return redirect('profile');
    }
    public function save_detail_account(Request $request)
    {
        // dd($request->all());

        $customMessages = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute tidak boleh sama',
            'email' => ':attribute format email salah',
            'min' => ':attribute Kurang',
            'max' => ':attribute Melebihi Batas Maksimal'
        ];
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required|min:8|',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
        ], $customMessages);
        $query                                        = User::where('id', $request->user_id)->first();
        $query->username                              = $credentials['username'];
        $query->password                              = Hash::make($credentials['password']);
        $query->password_show                         = $credentials['password'];
        $query->update();
        if ($query) {
            $request->session()->flash('profile_update_success');
        } else {
            $request->session()->flash('profile_update_error');
        }
        return redirect('profile');
    }
    public function detail_account(Request $request)
    {
        return view('users.profile.detail_user', [
            'title' => 'Profile',
        ]);
    }
    public function lihat_jabatan()
    {
        $user_karyawan = Karyawan::With('Departemen')->with('Divisi')->with('Bagian')->with('Jabatan')->where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.lihat_jabatan', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan
        ]);
    }
    public function lihat_kontrak_kerja()
    {
        $user_karyawan = Karyawan::With('Departemen')->with('Divisi')->with('Bagian')->with('Jabatan')->where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.lihat_jabatan', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan
        ]);
    }
}
