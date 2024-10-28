<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Bagian;
use App\Models\Cities;
use App\Models\Departemen;
use App\Models\District;
use App\Models\Divisi;
use App\Models\Jabatan;
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.lihat_kontrak', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan
        ]);
    }
    public function lihat_dokumen()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.profile.lihat_dokumen', [
            'title' => 'Profile',
            'user_karyawan' => $user_karyawan
        ]);
    }
    public function lihat_rekan_kerja()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $rekan_kerja = Karyawan::where('dept_id', $user_karyawan->dept_id)->get();
        return view('users.profile.lihat_rekan_kerja', [
            'title' => 'Profile',
            'rekan_kerja' => $rekan_kerja,
            'user_karyawan' => $user_karyawan
        ]);
    }
    public function lihat_struktur_organisasi()
    {

        // syncfusion
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user_site_job = Lokasi::where('lokasi_kantor', $user_karyawan->site_job)->first();
        if ($user_site_job->kategori_kantor == 'sp') {
            $user_site_job_karyawan =  ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'];
        } else if ($user_site_job->kategori_kantor == 'sps') {
            $user_site_job_karyawan =  ['ALL SITES (SP, SPS, SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SP)'];
        } else {
            $user_site_job_karyawan = ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'];
        }

        $jabatan_karyawan = Jabatan::With('Bagian')->with(['Divisi' => function ($query) {
            $query->With('Departemen');
        }])->where('id', $user_karyawan->jabatan_id)->first();
        if ($jabatan_karyawan == NULL) {
            $jabatan = NULL;
        } else {
            $jabatan = $jabatan_karyawan->atasan_id;
        }
        $jabatan_karyawan_atasan1 = Jabatan::With('Bagian')->with(['Divisi' => function ($query) {
            $query->With('Departemen');
        }])->where('id', $jabatan)->first();
        if ($jabatan_karyawan_atasan1 == NULL) {
            $jabatan_atasan1 = NULL;
        } else {
            $jabatan_atasan1 = $jabatan_karyawan_atasan1->atasan_id;
        }
        $jabatan_karyawan_atasan2 = Jabatan::With('Bagian')->with(['Divisi' => function ($query) {
            $query->With('Departemen');
        }])->where('id', $jabatan_atasan1)->first();
        if ($jabatan_karyawan_atasan2 == NULL) {
            $jabatan_atasan2 = NULL;
        } else {
            $jabatan_atasan2 = $jabatan_karyawan_atasan2->atasan_id;
        }
        $jabatan_karyawan_atasan3 = Jabatan::With('Bagian')->with(['Divisi' => function ($query) {
            $query->With('Departemen');
        }])->where('id', $jabatan_atasan2)->first();
        if ($jabatan_karyawan_atasan3 == NULL) {
            $jabatan_atasan3 = NULL;
        } else {
            $jabatan_atasan3 = $jabatan_karyawan_atasan3->atasan_id;
        }
        // dd($jabatan_karyawan_atasan3);
        $jabatan_karyawan_atasan4 = Jabatan::With('Bagian')->with(['Divisi' => function ($query) {
            $query->With('Departemen');
        }])->where('id', $jabatan_atasan3)->first();
        if ($jabatan_karyawan_atasan4 == NULL) {
            $jabatan_atasan4 = NULL;
        } else {
            $jabatan_atasan4 = $jabatan_karyawan_atasan4->atasan_id;
        }
        $jabatan_karyawan_atasan5 = Jabatan::With('Bagian')->with(['Divisi' => function ($query) {
            $query->With('Departemen');
        }])->where('id', $jabatan_atasan4)->first();
        $jabatan = [$jabatan_karyawan, $jabatan_karyawan_atasan1, $jabatan_karyawan_atasan2, $jabatan_karyawan_atasan3, $jabatan_karyawan_atasan4, $jabatan_karyawan_atasan5];

        // dd($jabatan);
        if (count($jabatan) == 0) {
            $jabatan_struktur = NULL;
        } else {
            foreach ($jabatan as $jabatan) {
                if ($jabatan == NULL) {
                    $jabatan_karyawan = NULL;
                    $jabatan_karyawan_id = NULL;
                    $jabatan_karyawan_atasan = NULL;
                    break;
                } else {
                    $jabatan_karyawan = $jabatan['nama_jabatan'];
                    $jabatan_karyawan_id = $jabatan['id'];
                    $jabatan_karyawan_atasan = $jabatan['atasan_id'];
                    if ($jabatan->Bagian == NULL) {
                        $jabatan_bagian = NULL;
                    } else {
                        $jabatan_bagian = $jabatan->Bagian["nama_bagian"];
                    }
                    if ($jabatan->Divisi == NULL) {
                        $jabatan_divisi = NULL;
                    } else {
                        $jabatan_divisi = $jabatan->Divisi["nama_divisi"];
                        if ($jabatan->Divisi->Departemen == NULL) {
                            $jabatan_departemen = NULL;
                        } else {
                            $jabatan_departemen = $jabatan->Divisi->Departemen["nama_departemen"];
                        }
                    }
                    // continue;
                }
                // dd($jabatan_departemen);
                // $ok = $jabatan->User->toArray();
                $ok = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian_id')
                    // ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->whereIn('karyawans.site_job', $user_site_job_karyawan)
                    ->where('departemens.nama_departemen', $jabatan_departemen)
                    ->where('divisis.nama_divisi', $jabatan_divisi)
                    ->where('bagians.nama_bagian', $jabatan_bagian)
                    ->where('jabatans.nama_jabatan', $jabatan_karyawan)
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->orWhere('penempatan_kerja', 'ALL SITES (SP)')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok1 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan1_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi1_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept1_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian1_id')
                    // ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    ->whereIn('karyawans.site_job', $user_site_job_karyawan)
                    ->where('departemens.nama_departemen', $jabatan_departemen)
                    ->where('divisis.nama_divisi', $jabatan_divisi)
                    ->where('bagians.nama_bagian', $jabatan_bagian)
                    ->where('jabatans.nama_jabatan', $jabatan_karyawan)
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->orWhere('penempatan_kerja', 'ALL SITES (SP)')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok2 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan2_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi2_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept2_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian2_id')
                    // ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    // ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    // ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->whereIn('karyawans.site_job', $user_site_job_karyawan)
                    ->where('departemens.nama_departemen', $jabatan_departemen)
                    ->where('divisis.nama_divisi', $jabatan_divisi)
                    ->where('bagians.nama_bagian', $jabatan_bagian)
                    ->where('jabatans.nama_jabatan', $jabatan_karyawan)
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->where('karyawans.site_job', 'ALL SITES (SP, SPS, SIP)')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->orWhere('penempatan_kerja', 'ALL SITES (SP)')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok3 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan3_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi3_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept3_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian3_id')
                    // ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    // ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    // ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->whereIn('karyawans.site_job', $user_site_job_karyawan)
                    ->where('departemens.nama_departemen', $jabatan_departemen)
                    ->where('divisis.nama_divisi', $jabatan_divisi)
                    ->where('bagians.nama_bagian', $jabatan_bagian)
                    ->where('jabatans.nama_jabatan', $jabatan_karyawan)
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->where('karyawans.site_job', 'ALL SITES (SP, SPS, SIP)')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->orWhere('penempatan_kerja', 'ALL SITES (SP)')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                $ok4 = Karyawan::Join('jabatans', 'jabatans.id', 'karyawans.jabatan4_id')
                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi4_id')
                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept4_id')
                    ->join('bagians', 'bagians.id', '=', 'karyawans.bagian4_id')
                    // ->whereIn('karyawans.site_job', ['ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)'])
                    // ->where('divisis.nama_divisi', $jabatan["nama_divisi"])
                    // ->where('bagians.nama_bagian', $jabatan["nama_bagian"])
                    ->whereIn('karyawans.site_job', $user_site_job_karyawan)
                    ->where('departemens.nama_departemen', $jabatan_departemen)
                    ->where('divisis.nama_divisi', $jabatan_divisi)
                    ->where('bagians.nama_bagian', $jabatan_bagian)
                    ->where('jabatans.nama_jabatan', $jabatan_karyawan)
                    ->where('karyawans.status_aktif', 'AKTIF')
                    // ->where('karyawans.site_job', 'ALL SITES (SP, SPS, SIP)')
                    // ->orWhere('karyawans.site_job', 'CV. SUMBER PANGAN - KEDIRI')
                    // ->orWhere('penempatan_kerja', 'ALL SITES (SP)')
                    // ->take('5')
                    ->select('karyawans.name')
                    ->get()
                    ->toArray();
                // dd($ok);
                if ($ok == []) {
                    $user_name = NULL;
                } else {
                    // dd(json_encode($ok));
                    $user_name = str_replace('[{', '', json_encode($ok));
                    $user_name = str_replace('{', '<li>', $user_name);
                    $user_name = str_replace('"', '', $user_name);
                    $user_name = str_replace('}', '', $user_name);
                    $user_name = str_replace(']', '', $user_name);
                    $user_name = str_replace('name:', ' ', $user_name);
                    $user_name = str_replace(' ', '&nbsp;', $user_name);
                    $user_name = str_replace(',', '</li>', $user_name);
                }

                if ($ok1 == []) {
                    $user_name1 = NULL;
                } else {
                    $user_name1 = str_replace('[{', '', json_encode($ok1));
                    $user_name1 = str_replace('{', '<li>', $user_name1);
                    $user_name1 = str_replace('"', '', $user_name1);
                    $user_name1 = str_replace('}', '', $user_name1);
                    $user_name1 = str_replace(']', '', $user_name1);
                    $user_name1 = str_replace('name:', ' ', $user_name1);
                    $user_name1 = str_replace(' ', '&nbsp;', $user_name1);
                    $user_name1 = str_replace(',', '</li>', $user_name1);
                }
                if ($ok2 == []) {
                    $user_name2 = NULL;
                } else {
                    $user_name2 = str_replace('[{', '', json_encode($ok2));
                    $user_name2 = str_replace('{', '<li>', $user_name2);
                    $user_name2 = str_replace('"', '', $user_name2);
                    $user_name2 = str_replace('}', '', $user_name2);
                    $user_name2 = str_replace(']', '', $user_name2);
                    $user_name2 = str_replace('name:', ' ', $user_name2);
                    $user_name2 = str_replace(' ', '&nbsp;', $user_name2);
                    $user_name2 = str_replace(',', '</li>', $user_name2);
                }
                if ($ok3 == []) {
                    $user_name3 = NULL;
                } else {
                    $user_name3 = str_replace('[{', '', json_encode($ok3));
                    $user_name3 = str_replace('{', '<li>', $user_name3);
                    $user_name3 = str_replace('"', '', $user_name3);
                    $user_name3 = str_replace('}', '', $user_name3);
                    $user_name3 = str_replace(']', '', $user_name3);
                    $user_name3 = str_replace('name:', ' ', $user_name3);
                    $user_name3 = str_replace(' ', '&nbsp;', $user_name3);
                    $user_name3 = str_replace(',', '</li>', $user_name3);
                }
                if ($ok4 == []) {
                    $user_name4 = NULL;
                } else {
                    $user_name4 = str_replace('[{', '', json_encode($ok4));
                    $user_name4 = str_replace('{', '<li>', $user_name4);
                    $user_name4 = str_replace('"', '', $user_name4);
                    $user_name4 = str_replace('}', '', $user_name4);
                    $user_name4 = str_replace(']', '', $user_name4);
                    $user_name4 = str_replace('name:', ' ', $user_name4);
                    $user_name4 = str_replace(' ', '&nbsp;', $user_name4);
                    $user_name4 = str_replace(',', '</li>', $user_name4);
                }
                // $role = '<a class="btn btn-sm btn-primary"> oke</a>';
                $count_username = (count($ok) + count($ok1) + count($ok2) + count($ok3) + count($ok4)) . '&nbsp;Karyawan';

                $foto = '<img width=30 height=30 style="border-radius: 50%;" align=center margin_bottom=4 margin_top=4 src=https://hrd.sumberpangan.store:4430/public/admin/assets/img/avatars/1.png><br>';
                $jabatan_struktur[] = array('x' => $jabatan_karyawan . ' <br>(' . $jabatan_bagian . ')', 'id' => str_replace("-", "", $jabatan_karyawan_id), 'parent' => str_replace("-", "", $jabatan_karyawan_atasan), 'user' => $user_name  . $user_name1 . $user_name2 . $user_name3 . $user_name4, 'attributes' => array('role' => $count_username, 'photo' => $foto));
                // $jabatan_struktur[] = array('x' => $jabatan['nama_jabatan'] . ' (' . $jabatan['nama_bagian'] . ')', 'id' => str_replace("-", "", $jabatan['id']), 'parent' => str_replace("-", "", $jabatan['atasan_id']), 'attributes' => array('role' => $user_name  . $user_name1 . $user_name2 . $user_name3 . $user_name4, 'photo' => $foto));
            }
            // dd($jabatan_struktur);
        }
        return view('users.profile.lihat_struktur_organisasi', [
            'title' => 'Profile',
            'user' => $jabatan_struktur,
        ]);
    }
}
