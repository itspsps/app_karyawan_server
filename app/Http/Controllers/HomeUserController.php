<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Cuti;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Izin;
use App\Models\Jabatan;
use App\Models\LevelJabatan;
use App\Models\Penugasan;
use App\Models\Titik;
use Carbon\Carbon;
use DateTime;
use Facade\FlareClient\Time\Time;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class HomeUserController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->is_admin == 'admin') {
            return redirect('/dashboard/holding');
        } else {
            date_default_timezone_set('Asia/Jakarta');
            $user_login = auth()->user()->id;
            $lokasi_kantor = Auth::guard('web')->user()->penempatan_kerja;
            // dd($lokasi_kantor);
            $tanggal = "";
            // $dateweek = \Carbon\Carbon::today();
            // dd($dateweek);
            $tglskrg = date('Y-m-d');
            $blnskrg = date('m');
            $thnskrg = date('Y');
            // dd($blnskrg);
            $tglkmrn            = date('Y-m-d', strtotime('-1 days'));
            $mapping_shift      = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->first();
            $count_absen_hadir  = MappingShift::where('user_id', $user_login)->where('status_absen', 'HADIR KERJA')->whereMonth('tanggal_masuk', $blnskrg)
                ->count();
            $count_absen_sakit  = MappingShift::where('user_id', $user_login)->where('status_absen', 'Sakit')->where('tanggal_masuk', '<=', $tglskrg)
                ->whereMonth('tanggal_masuk', $blnskrg)
                ->count();
            $count_absen_izin  = MappingShift::where('user_id', $user_login)->where('status_absen', 'Izin')->where('tanggal_masuk', '<=', $tglskrg)
                ->whereMonth('tanggal_masuk', $blnskrg)
                ->count();
            $count_absen_telat  = MappingShift::where('user_id', $user_login)->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('tanggal_masuk', '<=', $tglskrg)
                ->whereMonth('tanggal_masuk', $blnskrg)
                ->count();
            $user           = Auth::user()->id;
            $dataizin       = Izin::with('User')->where('id_approve_atasan', $user)
                ->whereNotNull('ttd_pengajuan')
                ->where('status_izin', 1)
                ->get();
            // get atasan tingkat 
            $datacuti_tingkat1       = Cuti::with('KategoriCuti')
                ->where('status_cuti', 1)
                ->join('users', 'users.id', '=', 'cutis.user_id')
                ->where('id_user_atasan', $user)
                ->whereNotNull('ttd_user')
                ->select('cutis.*', 'users.name', 'users.foto_karyawan')
                ->get();
            // dd($datacuti_tingkat1);
            $datacuti_tingkat2       = Cuti::with('KategoriCuti')
                ->where('status_cuti', 2)
                ->join('users', 'users.id', '=', 'cutis.user_id')
                ->where('id_user_atasan2', $user)
                ->whereNotNull('ttd_user')
                ->select('cutis.*', 'users.name', 'users.foto_karyawan')
                ->get();
            $datapenugasan  = DB::table('penugasans')->join('users', 'users.id', 'penugasans.id_user')
                ->where('penugasans.status_penugasan', '!=', 5)
                ->where('id_diminta_oleh', $user)
                ->orWhere('id_disahkan_oleh', $user)
                ->orWhere('id_user_hrd', $user)
                ->orWhere('id_user_finance', $user)
                ->select('penugasans.*', 'users.fullname')
                ->get();
            // dd($datapenugasan);
            $data_user_penugasaan  = DB::table('penugasans')->join('users', 'users.id', 'penugasans.id_user')
                ->where('id_user', $user)
                ->where('penugasans.status_penugasan', '5')
                ->select('penugasans.*', 'users.name')
                ->get();
            // dd($dataizin->count() + $datacuti_tingkat1->count() + $datacuti_tingkat2->count() + $datapenugasan->count());
            if (count($data_user_penugasaan) != 0) {
                foreach ($data_user_penugasaan as $user_penugasan) {
                    if ($user_penugasan->wilayah_penugasan == 'Diluar Kantor') {
                        $kantor_penugasan = NULL;
                        $cek_absensi      = MappingShift::where('user_id', $user_login)
                            ->where('status_absen', 'NULL')
                            ->whereBetween('tanggal_masuk', [$user_penugasan->tanggal_kunjungan, $user_penugasan->selesai_kunjungan])
                            ->update([
                                'jam_absen' => '07:45:00',
                                'telat' => '0',
                                'jam_pulang' => '17:00:00',
                                'lembur' => '0',
                                'pulang_cepat' => '0',
                                'keterangan_absensi' => 'ABSENSI PENUGASAN DILUAR WILAYAH KANTOR',
                                'status_absen' => 'Masuk',
                            ]);
                    } else if ($user_penugasan->wilayah_penugasan == 'Wilayah Kantor') {
                        $kantor_penugasan = $user_penugasan->alamat_dikunjungi;
                        $cek_absensi      = MappingShift::where('user_id', $user_login)
                            ->where('status_absen', 'NULL')
                            ->whereBetween('tanggal_masuk', [$user_penugasan->tanggal_kunjungan, $user_penugasan->selesai_kunjungan])
                            ->update([
                                'keterangan_absensi' => 'ABSENSI PENUGASAN WILAYAH KANTOR',
                            ]);
                    }
                }
            } else {
                $kantor_penugasan = NULL;
            }
            $faceid = User::where('id', $user_login)->value('face_id');
            if ($mapping_shift == '' || $mapping_shift == NULL) {
                $jam_absen = null;
                $jam_pulang = null;
                $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                // dd($status_absen_skrg);

                return view('users.home.index', [
                    'title'             => 'Absen',
                    'jam_kerja'         => $jam_kerja,
                    'shift_karyawan'    => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->first(),
                    'count_absen_hadir' => $count_absen_hadir,
                    'thnskrg'           => $thnskrg,
                    'lokasi_kantor'     => $lokasi_kantor,
                    'status_absen_skrg' => $status_absen_skrg,
                    'faceid'            => $faceid,
                    'dataizin'          => $dataizin,
                    'data_count'        => $dataizin->count() + $datacuti_tingkat1->count() + $datacuti_tingkat2->count() + $datapenugasan->count(),
                    'datacuti_tingkat1' => $datacuti_tingkat1,
                    'datacuti_tingkat2' => $datacuti_tingkat2,
                    'datapenugasan'     => $datapenugasan,
                    // 'data_notif'          => $data_notif,
                    'count_absen_izin'     => $count_absen_izin,
                    'count_absen_sakit'     => $count_absen_sakit,
                    'count_absen_telat'     => $count_absen_telat,
                    'kantor_penugasan'     => $kantor_penugasan,
                    'location'     => Titik::all(),
                ]);
            } else {
                $jam_absen = $mapping_shift->jam_absen;
                $jam_pulang = $mapping_shift->jam_pulang;
                $status_absen_skrg = $mapping_shift->shift->nama_shift;

                $hours_1_masuk = Carbon::parse($mapping_shift->shift->jam_masuk)->subHour(1)->format('H:i:s');
                $hours_1_pulang = Carbon::parse($mapping_shift->shift->jam_keluar)->subHour(-1)->format('H:i:s');
                $timenow = Carbon::now()->format('H:i:s');
                // dd($status_absen_skrg);
                if ($status_absen_skrg == 'Malam') {
                    if ($jam_absen != null && $jam_pulang == null) {
                        if ($hours_1_pulang > $timenow) {
                            $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->orderBy('tanggal_masuk', 'DESC')->first();
                        } else {
                            $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                        }
                    } else {
                        $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                    }
                } else {
                    $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                }
                $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                // $hours_1 = Carbon::parse($status_absen_skrg->shift->jam_masuk)->subHour(-1)->format('H:i:s');
                // dd($hours_1);
                // dd($faceid);
                // dd($status_absen_skrg);
                // dd($dataizin);

                return view('users.home.index', [
                    'title'             => 'Absen',
                    'shift_karyawan'    => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->first(),
                    'count_absen_hadir' => $count_absen_hadir,
                    'thnskrg'           => $thnskrg,
                    'get_shift'         => $status_absen_skrg,
                    'lokasi_kantor'     => $lokasi_kantor,
                    'jam_kerja'         => $jam_kerja,
                    'status_absen_skrg' => $status_absen_skrg,
                    'faceid'            => $faceid,
                    'data_count'        => $dataizin->count() + $datacuti_tingkat1->count() + $datacuti_tingkat2->count() + $datapenugasan->count(),
                    'dataizin'          => $dataizin,
                    'datacuti_tingkat1' => $datacuti_tingkat1,
                    'datacuti_tingkat2' => $datacuti_tingkat2,
                    'datapenugasan'     => $datapenugasan,
                    'count_absen_izin'     => $count_absen_izin,
                    'count_absen_sakit'     => $count_absen_sakit,
                    'count_absen_telat'     => $count_absen_telat,
                    'kantor_penugasan'     => $kantor_penugasan,
                    'location'     => Titik::all(),

                ]);
            }
        }
    }
    public function create_face_id()
    {
        $user = Auth::user();
        // dd($user);
        return view('users.createfaceid.index', [
            'karyawan' => $user,
        ]);
    }
    public function get_notif()
    {
        $count_izin_0 = Izin::where('user_id', Auth::user()->id)->where('status_izin', '0')->count();
        $count_cuti_0 = Cuti::where('user_id', Auth::user()->id)->where('status_cuti', '0')->count();
        $count_penugasan_0 = Penugasan::where('id_user', Auth::user()->id)->where('status_penugasan', '0')->count();
        return response()->json([
            'count_izin' => $count_izin_0,
            'count_cuti' => $count_cuti_0,
            'count_penugasan' => $count_penugasan_0,
        ]);
    }
    public function get_notif_cuti()
    {
        $count_cuti_0 = Cuti::where('user_id', Auth::user()->id)->where('status_cuti', '0')->count();
        return json_encode($count_cuti_0);
    }
    public function get_notif_penugasan()
    {
        $count_penugasan_0 = Penugasan::where('id_user', Auth::user()->id)->where('status_penugasan', '0')->count();
        return json_encode($count_penugasan_0);
    }
    public function savefaceid(Request $request)
    {
        // dd($request->all());
        $query = User::where('id', $request->id_user)->first();
        $query->face_id = $request->faceid;
        $query->update();
        if ($query) {
            $request->session()->flash('simpanface_success');
        } else {
            $request->session()->flash('simpanface_error');
        }
        return redirect()->route('home');
    }
    public function form_datang_terlambat(Request $request)
    {
        // dd('oke');
        $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        // dd($jam_kerja);
        $site_job = Auth::guard('web')->user()->site_job;
        $kontrak = Auth::guard('web')->user()->kontrak_kerja;
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        if (Auth::user()->kategori == 'Karyawan Bulanan') {
            $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                ->where('users.id', Auth()->user()->id)->first();
            if ($user->level_jabatan == 6) {
                // dd('oke');
                $IdLevelAtasan  = LevelJabatan::where('level_jabatan', '5')->first();
                $IdLevelAtasan1  = LevelJabatan::where('level_jabatan', '4')->first();
                $IdLevelAtasan2  = LevelJabatan::where('level_jabatan', '3')->first();
                $IdLevelAtasan3  = LevelJabatan::where('level_jabatan', '2')->first();
                $IdLevelAtasan4  = LevelJabatan::where('level_jabatan', '1')->first();
                if ($lokasi_site_job->kategori_kantor == 'sps') {

                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $atasan = DB::table('users')
                                        ->join('jabatans', function ($join) use ($IdLevelAtasan4) {
                                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan4) {
                                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                                $query->where('level_jabatans.id', '=', $IdLevelAtasan4->id);
                                            });
                                        })
                                        ->where('is_admin', 'user')
                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->where('users.dept_id', $user->dept_id)
                                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                        ->first();
                                    if ($atasan == '') {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $atasan = DB::table('users')
                                        ->join('jabatans', function ($join) use ($IdLevelAtasan4) {
                                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan4) {
                                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                                $query->where('level_jabatans.id', '=', $IdLevelAtasan4->id);
                                            });
                                        })
                                        ->where('is_admin', 'user')
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->where('users.dept_id', $user->dept_id)
                                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                        ->first();
                                    if ($atasan == '') {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $atasan = DB::table('users')
                                        ->join('jabatans', function ($join) use ($IdLevelAtasan4) {
                                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan4) {
                                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                                $query->where('level_jabatans.id', '=', $IdLevelAtasan4->id);
                                            });
                                        })
                                        ->where('is_admin', 'user')
                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->where('users.dept_id', $user->dept_id)
                                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                        ->first();
                                    if ($atasan == '') {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $atasan = DB::table('users')
                                        ->join('jabatans', function ($join) use ($IdLevelAtasan4) {
                                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan4) {
                                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                                $query->where('level_jabatans.id', '=', $IdLevelAtasan4->id);
                                            });
                                        })
                                        ->where('is_admin', 'user')
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                        ->where('users.dept_id', $user->dept_id)
                                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                        ->first();
                                    if ($atasan == '') {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $atasan = DB::table('users')
                                        ->join('jabatans', function ($join) use ($IdLevelAtasan4) {
                                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan4) {
                                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                                $query->where('level_jabatans.id', '=', $IdLevelAtasan4->id);
                                            });
                                        })
                                        ->where('is_admin', 'user')
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->where('users.dept_id', $user->dept_id)
                                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                        ->first();
                                    if ($atasan == '') {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->where('jabatans.id', '=', 'users.jabatan_id')
                        ->onWhere('jabatans.id', '=', 'users.jabatan1_id')
                        ->onWhere('jabatans.id', '=', 'users.jabatan2_id')
                        ->onWhere('jabatans.id', '=', 'users.jabatan3_id')
                        ->onWhere('jabatans.id', '=', 'users.jabatan4_id')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $atasan = DB::table('users')
                                        ->join('jabatans', function ($join) use ($IdLevelAtasan4) {
                                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan4) {
                                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                                $query->where('level_jabatans.id', '=', $IdLevelAtasan4->id);
                                            });
                                        })
                                        ->where('is_admin', 'user')
                                        ->where('users.dept_id', $user->dept_id)
                                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                        ->first();
                                    if ($atasan == '') {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                }
            } else if ($user->level_jabatan == 5) {
                $IdLevelAtasan  = LevelJabatan::where('level_jabatan', '4')->first();
                $IdLevelAtasan1  = LevelJabatan::where('level_jabatan', '3')->first();
                $IdLevelAtasan2  = LevelJabatan::where('level_jabatan', '2')->first();
                $IdLevelAtasan3  = LevelJabatan::where('level_jabatan', '1')->first();
                if ($lokasi_site_job->kategori_kantor == 'sps') {

                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->where('users.dept_id', $user->dept_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->where('users.dept_id', $user->dept_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                    ->where('users.dept_id', $user->dept_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                    ->where('users.dept_id', $user->dept_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->where('users.dept_id', $user->dept_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                }
            } else if ($user->level_jabatan == 4) {
                $IdLevelAtasan  = LevelJabatan::where('level_jabatan', '3')->first();
                $IdLevelAtasan1  = LevelJabatan::where('level_jabatan', '2')->first();
                $IdLevelAtasan2  = LevelJabatan::where('level_jabatan', '1')->first();
                if ($lokasi_site_job->kategori_kantor == 'sps') {
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.dept_id', $user->dept_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $getUserAtasan  = NULL;
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $getUserAtasan  = NULL;
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $getUserAtasan  = NULL;
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $getUserAtasan  = NULL;
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $getUserAtasan  = NULL;
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->where('users.divisi_id', $user->divisi_id)
                            ->orWhere('users.divisi1_id', $user->divisi_id)
                            ->orWhere('users.divisi2_id', $user->divisi_id)
                            ->orWhere('users.divisi3_id', $user->divisi_id)
                            ->orWhere('users.divisi4_id', $user->divisi_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $atasan = DB::table('users')
                                ->join('jabatans', function ($join) use ($IdLevelAtasan2) {
                                    $join->on('jabatans.id', '=', 'users.jabatan_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                    $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                    $join->join('level_jabatans', function ($query) use ($IdLevelAtasan2) {
                                        $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                        $query->where('level_jabatans.id', '=', $IdLevelAtasan2->id);
                                    });
                                })
                                ->where('is_admin', 'user')
                                ->where('users.divisi_id', $user->divisi_id)
                                ->orWhere('users.divisi1_id', $user->divisi_id)
                                ->orWhere('users.divisi2_id', $user->divisi_id)
                                ->orWhere('users.divisi3_id', $user->divisi_id)
                                ->orWhere('users.divisi4_id', $user->divisi_id)
                                ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                ->first();
                            if ($atasan == '') {
                                $atasan = DB::table('users')
                                    ->join('jabatans', function ($join) use ($IdLevelAtasan3) {
                                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                        $join->join('level_jabatans', function ($query) use ($IdLevelAtasan3) {
                                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                            $query->where('level_jabatans.id', '=', $IdLevelAtasan3->id);
                                        });
                                    })
                                    ->where('is_admin', 'user')
                                    ->where('users.divisi_id', $user->divisi_id)
                                    ->orWhere('users.divisi1_id', $user->divisi_id)
                                    ->orWhere('users.divisi2_id', $user->divisi_id)
                                    ->orWhere('users.divisi3_id', $user->divisi_id)
                                    ->orWhere('users.divisi4_id', $user->divisi_id)
                                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                                    ->first();
                                if ($atasan == '') {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $getUserAtasan  = $atasan;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                }
            } else if ($user->level_jabatan == 3) {
                $IdLevelAtasan  = LevelJabatan::where('level_jabatan', '2')->first();
                $IdLevelAtasan1 = LevelJabatan::where('level_jabatan', '1')->first();
                if ($lokasi_site_job->kategori_kantor == 'sps') {

                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');

                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                }
            } else if ($user->level_jabatan == 2) {
                $IdLevelAtasan = LevelJabatan::where('level_jabatan', '1')->first();
                $IdLevelAtasan1 = LevelJabatan::where('level_jabatan', '0')->first();
                if ($lokasi_site_job->kategori_kantor == 'sps') {

                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');

                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                    $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                        ->where('id', '!=', Auth::user()->id)
                        ->where('is_admin', 'user')
                        ->where('divisi_id', Auth::user()->divisi_id)
                        ->orWhere('divisi1_id', Auth::user()->divisi_id)
                        ->orWhere('divisi2_id', Auth::user()->divisi_id)
                        ->orWhere('divisi3_id', Auth::user()->divisi_id)
                        ->orWhere('divisi4_id', Auth::user()->divisi_id)
                        ->get();
                    $atasan = DB::table('users')
                        ->join('jabatans', function ($join) use ($IdLevelAtasan) {
                            $join->on('jabatans.id', '=', 'users.jabatan_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                            $join->join('level_jabatans', function ($query) use ($IdLevelAtasan) {
                                $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                $query->where('level_jabatans.id', '=', $IdLevelAtasan->id);
                            });
                        })
                        ->where('is_admin', 'user')
                        ->where('users.divisi_id', $user->divisi_id)
                        ->orWhere('users.divisi1_id', $user->divisi_id)
                        ->orWhere('users.divisi2_id', $user->divisi_id)
                        ->orWhere('users.divisi3_id', $user->divisi_id)
                        ->orWhere('users.divisi4_id', $user->divisi_id)
                        ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                        ->first();
                    // jika atasan tingkat 1 
                    // dd($atasan);
                    if ($atasan == '') {
                        $atasan = DB::table('users')
                            ->join('jabatans', function ($join) use ($IdLevelAtasan1) {
                                $join->on('jabatans.id', '=', 'users.jabatan_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                                $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                                $join->join('level_jabatans', function ($query) use ($IdLevelAtasan1) {
                                    $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                                    $query->where('level_jabatans.id', '=', $IdLevelAtasan1->id);
                                });
                            })
                            ->where('is_admin', 'user')
                            ->where('users.dept_id', $user->dept_id)
                            ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                            ->first();
                        // dd($atasan);
                        if ($atasan == '') {
                            // dd('oke1');
                            $getUserAtasan  = NULL;
                        } else {
                            $getUserAtasan  = $atasan;
                        }
                    } else {
                        $getUserAtasan  = $atasan;
                    }
                }
            } else {
                $atasan = DB::table('users')
                    ->join('jabatans', function ($join) {
                        $join->on('jabatans.id', '=', 'users.jabatan_id');
                        $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
                        $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
                        $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
                        $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
                        $join->join('level_jabatans', function ($query) {
                            $query->on('level_jabatans.id', '=', 'jabatans.level_id');
                            $query->where('level_jabatans.level_jabatan', '=', '0');
                        });
                    })
                    ->where('users.divisi_id', $user->divisi_id)
                    ->orWhere('users.divisi1_id', $user->divisi_id)
                    ->orWhere('users.divisi2_id', $user->divisi_id)
                    ->orWhere('users.divisi3_id', $user->divisi_id)
                    ->orWhere('users.divisi4_id', $user->divisi_id)
                    ->select('users.*', 'jabatans.nama_jabatan', 'level_jabatans.level_jabatan')
                    ->first();
                // dd($atasan);
                $get_user_backup = NULL;
                $getUserAtasan = $atasan;
            }
        } else if (Auth::user()->kategori == 'Karyawan Harian') {
            $user = DB::table('users')->where('users.id', Auth()->user()->id)->first();
            $atasan = DB::table('users')
                ->join('mapping_shifts', function ($join) {
                    $join->on('mapping_shifts.koordinator_id', '=', 'users.id');
                })
                ->select('users.*', 'mapping_shifts.koordinator_id')
                ->first();
            // dd($atasan);
            $get_user_backup = NULL;
            $getUserAtasan = $atasan;
        }

        return view('users.absen.form_datang_terlambat', [
            'user' => $user,
            'jam_kerja' => $jam_kerja,
            'getUserAtasan' => $getUserAtasan,
        ]);
    }
    public function get_count_absensi_home(Request $request)
    {
        $blnskrg = date('m');
        $user_login = auth()->user()->id;
        if ($request->ajax()) {
            if (!empty($request->filter_month)) {
                $count_absen_hadir = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->where('status_absen', 'Masuk')->count();
            } else {
                $count_absen_hadir = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->where('status_absen', 'Masuk')->count();
            }
        }
        return $count_absen_hadir;
    }
    public function datatableHome(Request $request)
    {

        // dd($request->all());
        $user_login = auth()->user()->id;
        $dateweek = \Carbon\Carbon::today()->subDays(7);
        $datenow = \Carbon\Carbon::today();
        $blnskrg = date('m');
        // dd($firstDayofPreviousMonth);
        if ($request->ajax()) {
            if (!empty($request->filter_month)) {
                if ($request->filter_month == $blnskrg) {
                    $data = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->whereBetween('tanggal_masuk', array($dateweek, $datenow))->get();
                } else {
                    $data = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->limit(7)->orderBy('tanggal_masuk', 'DESC')->get();
                }
                return DataTables::of($data)->addIndexColumn()
                    ->addColumn('tanggal_masuk', function ($row) {
                        $result = Carbon::parse($row->tanggal_masuk)->isoFormat('D-MM-Y');;
                        return $result;
                    })
                    ->addColumn('jam_absen', function ($row) {
                        if ($row->jam_absen == NULL) {
                            return $row->jam_absen;
                        } else {
                            $result = Carbon::parse($row->jam_absen)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->addColumn('jam_pulang', function ($row) {
                        if ($row->jam_pulang == NULL) {
                            return $row->jam_pulang;
                        } else {
                            $result = Carbon::parse($row->jam_pulang)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->rawColumns(['tanggal_masuk', 'jam_absen', 'jam_pulang'])
                    ->make(true);
            } else {
                $data = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->whereBetween('tanggal_masuk', array($dateweek, $datenow))->orderBy('tanggal_masuk', 'DESC')->get();
                return DataTables::of($data)->addIndexColumn()
                    ->addColumn('tanggal_masuk', function ($row) {
                        $result = Carbon::parse($row->tanggal_masuk)->isoFormat('D-MM-Y');;
                        return $result;
                    })
                    ->addColumn('jam_absen', function ($row) {
                        if ($row->jam_absen == NULL) {
                            return $row->jam_absen;
                        } else {
                            $result = Carbon::parse($row->jam_absen)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->addColumn('jam_pulang', function ($row) {
                        if ($row->jam_pulang == NULL) {
                            return $row->jam_pulang;
                        } else {
                            $result = Carbon::parse($row->jam_pulang)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->rawColumns(['tanggal_masuk', 'jam_absen', 'jam_pulang'])
                    ->make(true);
            }
        }
    }
    public function HomeAbsen(Request $request)
    {
        $user_login = auth()->user()->id;
        $date_now = date('Y');
        $month_now = date('m');
        $month_yesterday = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MM');
        $month_yesterday1 = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');
        $month_now1 = \Carbon\Carbon::now()->isoFormat('MMMM');
        date_default_timezone_set('Asia/Jakarta');
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date('Y-m-d');
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $tidak_masuk = MappingShift::where('status_absen', 'TIDAK HADIR KERJA')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy(DB::raw("Month(tanggal_masuk)"))
            ->pluck('count');
        $masuk = MappingShift::where('mapping_shifts.status_absen', 'HADIR KERJA')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(mapping_shifts.tanggal_masuk) as count"))
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy(DB::raw("Month(tanggal_masuk)"))
            ->pluck('count');
        $telat = MappingShift::where('status_absen', 'HADIR KERJA')
            ->where('keterangan_absensi', 'TELAT HADIR')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy(DB::raw("Month(tanggal_masuk)"))
            ->pluck('count');
        // dd();
        $telat_now = MappingShift::whereMonth('tanggal_masuk', $month_now)
            ->where('user_id', $user_login)
            ->select(DB::raw("telat as count"))
            ->pluck('count');
        $telat_yesterday = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("telat as count"))
            ->pluck('count');
        $lembur_now = MappingShift::whereMonth('tanggal_masuk', $month_now)
            ->where('user_id', $user_login)
            ->select(DB::raw("lembur as count"))
            ->pluck('count');
        $lembur_yesterday = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("lembur as count"))
            ->pluck('count');
        $data_telat_now = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("tanggal_masuk as count"))
            ->pluck('count');
        $data_telat_yesterday = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("tanggal_masuk as count "))
            ->pluck('count');
        $get_mapping = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->first();
        if ($get_mapping == '' || $get_mapping == NULL) {
            $tanggal = $tglskrg;
            $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tanggal)->first();
        } else {
            $tanggal = $tglkmrn;
            $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tanggal)->first();
        }
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_absen = MappingShift::where('tanggal_masuk', $tglskrg)->where('user_id', auth()->user()->id);

        if ($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if ($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', auth()->user()->id)->whereBetween('tanggal_masuk', [$request["mulai"], $request["akhir"]]);
        }
        // dd($mapping_shift);
        if ($mapping_shift == NULL) {
            $request->session()->flash('Mapping_shift_kosong');
            return redirect('home');
        }
        $timenow = Carbon::now()->format('H:i:s');
        $hours_1_masuk = Carbon::parse($mapping_shift->shift->jam_masuk)->subHour(1)->format('H:i:s');
        // dd($hours_1_masuk);
        $jam_masuk = Carbon::parse($mapping_shift->shift->jam_masuk)->format('H:i:s');
        $hours_1_pulang = Carbon::parse($mapping_shift->shift->jam_keluar)->subHour(-1)->format('H:i:s');
        $get_nama_shift = $mapping_shift->shift->nama_shift;
        // dd($hours_1_pulang);
        if ($get_nama_shift == 'Malam') {
            if ($hours_1_pulang > $timenow) {
                // dd('1');
                // dd('oke');
                $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->orderBy('tanggal_masuk', 'DESC')->first();
            } else {
                // dd('2');
                $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
            }
        } else {
            $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
            // dd($status_absen_skrg);
        }
        $cek_jam_maks_kerja = MappingShift::With('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', $tglskrg)->first();
        $time_now = date('H:i:s');
        // dd($cek_jam_maks_kerja->Shift->jam_keluar);
        $date1          = new DateTime($cek_jam_maks_kerja->tanggal_masuk . $cek_jam_maks_kerja->Shift->jam_keluar);
        $date2          = new DateTime($cek_jam_maks_kerja->tanggal_masuk . $time_now);
        $interval       = $date1->diff($date2);
        // dd($date1, $date2, $interval);
        if ($status_absen_skrg->jam_absen == '' || $status_absen_skrg->jam_absen == NULL) {
            if ($timenow >= $jam_masuk) {
                // print_r($interval->p);

                if ($interval->h < 6) {
                    $request->session()->flash('jam_kerja_kurang');
                } else {
                }
                return view('users.absen.index', [
                    'title' => 'My Absen',
                    'shift_karyawan' => $status_absen_skrg,
                    'status_absen_skrg' => $status_absen_skrg,
                    'data_absen' => $data_absen->get(),
                    'masuk' => array_map('intval', json_decode($masuk)),
                    'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
                    'telat' => array_map('intval', json_decode($telat)),
                    'date_now' => $date_now,
                    'month_now1' => $month_now1,
                    'month_yesterday1' => $month_yesterday1,
                    'telat_now' => array_map('intval', json_decode($telat_now)),
                    'telat_yesterday' => array_map('intval', json_decode($telat_yesterday)),
                    'lembur_now' => array_map('intval', json_decode($lembur_now)),
                    'data_telat_now' => $data_telat_now,
                    'data_telat_yesterday' => $data_telat_yesterday,
                    'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday)),
                    'face' => User::select('id', 'name', 'face_id')->whereNotNull('face_id')->get(),
                    'karyawan' => User::select('id', 'name', 'face_id')->whereNotNull('face_id')->get(),
                    'angka' => 1,
                    'absensi' => MappingShift::where('tanggal_masuk', date('Y-m-d'))->get(),
                    'jumlah_absensi' => 1,
                    'faceid' => User::where('id', $user_login)->value('face_id'),

                ]);
            } else {
                if ($time_now > $hours_1_masuk) {
                    return view('users.absen.index', [
                        'title' => 'My Absen',
                        'shift_karyawan' => $status_absen_skrg,
                        'status_absen_skrg' => $status_absen_skrg,
                        'data_absen' => $data_absen->get(),
                        'masuk' => array_map('intval', json_decode($masuk)),
                        'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
                        'telat' => array_map('intval', json_decode($telat)),
                        'date_now' => $date_now,
                        'month_now1' => $month_now1,
                        'month_yesterday1' => $month_yesterday1,
                        'telat_now' => array_map('intval', json_decode($telat_now)),
                        'telat_yesterday' => array_map('intval', json_decode($telat_yesterday)),
                        'lembur_now' => array_map('intval', json_decode($lembur_now)),
                        'data_telat_now' => $data_telat_now,
                        'data_telat_yesterday' => $data_telat_yesterday,
                        'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday)),
                        'face' => User::select('id', 'name', 'face_id')->whereNotNull('face_id')->get(),
                        'karyawan' => User::select('id', 'name', 'face_id')->whereNotNull('face_id')->get(),
                        'angka' => 1,
                        'absensi' => MappingShift::where('tanggal_masuk', date('Y-m-d'))->get(),
                        'jumlah_absensi' => 1,
                        'faceid' => User::where('id', $user_login)->value('face_id'),
                    ]);
                } else {
                    Alert::error('Gagal', 'Anda Belum Masuk Jam Absensi');
                    return redirect()->back()->with('Gagal', 'Anda Belum Masuk Jam Absensi');
                }
            }
        } else if ($status_absen_skrg->jam_absen != '' || $status_absen_skrg->jam_absen != NULL) {
            $date1_pulang          = new DateTime($cek_jam_maks_kerja->tanggal_pulang . $cek_jam_maks_kerja->Shift->jam_masuk);
            $date2_pulang          = new DateTime($cek_jam_maks_kerja->tanggal_pulang . $time_now);
            $interval_pulang       = $date1_pulang->diff($date2_pulang);
            // dd($interval_pulang);
            $hitung_jam_kerja = ($interval_pulang->format('%H') . ':' . $interval_pulang->format('%I') . ':' . $interval_pulang->format('%S'));
            // dd($hitung_jam_kerja);
            if ($hitung_jam_kerja <= '06:00:00') {
                $request->session()->flash('jam_kerja_kurang');
            }
            return view('users.absen.index', [
                'title' => 'My Absen',
                'shift_karyawan' => $status_absen_skrg,
                'status_absen_skrg' => $status_absen_skrg,
                'data_absen' => $data_absen->get(),
                'masuk' => array_map('intval', json_decode($masuk)),
                'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
                'telat' => array_map('intval', json_decode($telat)),
                'date_now' => $date_now,
                'month_now1' => $month_now1,
                'month_yesterday1' => $month_yesterday1,
                'telat_now' => array_map('intval', json_decode($telat_now)),
                'telat_yesterday' => array_map('intval', json_decode($telat_yesterday)),
                'lembur_now' => array_map('intval', json_decode($lembur_now)),
                'data_telat_now' => $data_telat_now,
                'data_telat_yesterday' => $data_telat_yesterday,
                'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday)),
                'face' => User::select('id', 'name', 'face_id')->whereNotNull('face_id')->get(),
                'karyawan' => User::select('id', 'name', 'face_id')->whereNotNull('face_id')->get(),
                'angka' => 1,
                'absensi' => MappingShift::where('tanggal_masuk', date('Y-m-d'))->get(),
                'jumlah_absensi' => 1,
                'faceid' => User::where('id', $user_login)->value('face_id'),
            ]);
        }
    }

    public function proses_izin_datang_terlambat(Request $request)
    {
        // dd($request->all());
        $lokasi_kerja = Auth::guard('web')->user()->penempatan_kerja;
        if ($lokasi_kerja == '' || $lokasi_kerja == NULL) {
            $request->session()->flash('lokasikerjanull', 'Gagal Absen Masuk');
            return redirect('/home');
        } else {
            $cek_penugasan = MappingShift::where('id', Auth::user()->id)
                ->where('keterangan_absensi', 'ABSENSI PENUGASAN WILAYAH KANTOR')
                ->first();
            if ($cek_penugasan != '' || $cek_penugasan != NULL) {
                $request->session()->flash('penugasan_wilayah_kantor');
                return redirect('/home');
            } else {
                $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
                if ($jam_kerja == '' || $jam_kerja == NULL) {
                    $request->session()->flash('mapping_kosong');
                    return redirect('/izin/dashboard');
                } else {
                    if ($request->id_user_atasan == NULL || $request->id_user_atasan == '') {
                        if ($request->level_jabatan != '1') {
                            $request->session()->flash('atasankosong');
                            return redirect('/izin/dashboard');
                        } else {
                            // No form
                            $count_tbl_izin = Izin::where('izin', $request->izin)->count();
                            // dd($count_tbl_izin);
                            $countstr = strlen($count_tbl_izin + 1);
                            if ($countstr == '1') {
                                $no = '000' . $count_tbl_izin + 1;
                            } else if ($countstr == '2') {
                                $no = '00' . $count_tbl_izin + 1;
                            } else if ($countstr == '3') {
                                $no = '0' . $count_tbl_izin + 1;
                            } else {
                                $no = $count_tbl_izin + 1;
                            }
                            $jam_terlambat = $request->terlambat;
                            $jam_masuk_kerja = $request->jam_masuk;
                            $jam_pulang_cepat = NULL;
                            $img_name = NULL;
                            $jam_keluar = NULL;
                            $jam_kembali = NULL;
                            $id_backup = NULL;
                            $name_backup = NULL;
                            $catatan_backup = NULL;
                            $tanggal = $request->tanggal;
                            $tanggal_selesai = NULL;
                            $no_form = Auth::user()->kontrak_kerja . '/SK/FKDT/' . date('Y/m/d') . '/' . $no;
                            $folderPath     = public_path('signature/izin/');
                            $image_parts    = explode(";base64,", $request->signature);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type     = $image_type_aux[1];
                            $image_base64   = base64_decode($image_parts[1]);
                            $uniqid         = date('y-m-d') . '-' . uniqid();
                            $file           = $folderPath . $uniqid . '.' . $image_type;
                            file_put_contents($file, $image_base64);
                            $data                   = new Izin();
                            $data->user_id          = $request->id_user;
                            $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                            $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                            $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                            $data->telp             = $request->telp;
                            $data->terlambat        = $jam_terlambat;
                            $data->jam_masuk_kerja  = $jam_masuk_kerja;
                            $data->pulang_cepat     = $jam_pulang_cepat;
                            $data->email            = $request->email;
                            $data->fullname         = $request->fullname;
                            $data->izin             = $request->izin;
                            $data->user_id_backup   = $id_backup;
                            $data->user_name_backup = $name_backup;
                            $data->catatan_backup = $catatan_backup;
                            $data->foto_izin        = $img_name;
                            $data->jam_keluar        = $jam_keluar;
                            $data->jam_kembali        = $jam_kembali;
                            $data->tanggal          = $tanggal;
                            $data->tanggal_selesai   = $tanggal_selesai;
                            $data->jam              = $request->jam;
                            $data->keterangan_izin  = $request->keterangan_izin;
                            $data->approve_atasan   = $request->approve_atasan;
                            $data->id_approve_atasan = $request->id_user_atasan;
                            $data->status_izin      = 2;
                            $data->no_form_izin      = $no_form;
                            $data->ttd_pengajuan    = $uniqid;
                            $data->waktu_ttd_pengajuan    = date('Y-m-d');
                            $data->ttd_atasan      = NULL;
                            $data->waktu_approve      = NULL;
                            $data->save();
                            // jam telat
                            // dd($request->jam_masuk);
                            $date1          = new DateTime($tanggal . $request->jam_masuk);
                            $date2          = new DateTime($tanggal . $request->jam);
                            $interval       = $date1->diff($date2);
                            $toleransi_mnt = '00:05:00';
                            $jml_all = ($interval->format('%H') . ':' . $interval->format('%I') . ':' . $interval->format('%S'));
                            // $jum_all_toleransi = ($jml_all - $toleransi_mnt);
                            // 
                            $lokasi_kantor = Lokasi::where('lokasi_kantor', $lokasi_kerja)->first();
                            $lat_kantor = $lokasi_kantor->lat_kantor;
                            $long_kantor = $lokasi_kantor->long_kantor;
                            // absen
                            $update = MappingShift::where('id', $request->id_mapping)->first();
                            $update->jam_absen = date('H:i:s');
                            $update->telat = $jml_all;
                            $update->foto_jam_absen = $request['foto_jam_absen'];
                            $update->lat_absen = $request['lat_absen'];
                            $update->long_absen = $request['long_absen'];
                            $update->jarak_masuk = $request['jarak_masuk'];
                            $update->lokasi_absen = $request['lokasi_absen'];
                            $update->status_absen = 'HADIR KERJA';
                            $update->keterangan_absensi = 'TELAT HADIR';
                            $update->kelengkapan_absensi = 'BELUM PRESENSI PULANG';
                            $update->update();

                            ActivityLog::create([
                                'user_id' => Auth::user()->id,
                                'activity' => 'tambah',
                                'description' => 'Absen Masuk Pada Tanggal ' . $tanggal,
                                'status_absen_skrg' => MappingShift::where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->get(),
                            ]);
                            $request->session()->flash('absenmasuksuccess');
                            return redirect('home');
                        }
                    } else {
                        // No form
                        $count_tbl_izin = Izin::where('izin', $request->izin)->count();
                        // dd($count_tbl_izin);
                        $countstr = strlen($count_tbl_izin + 1);
                        if ($countstr == '1') {
                            $no = '000' . $count_tbl_izin + 1;
                        } else if ($countstr == '2') {
                            $no = '00' . $count_tbl_izin + 1;
                        } else if ($countstr == '3') {
                            $no = '0' . $count_tbl_izin + 1;
                        } else {
                            $no = $count_tbl_izin + 1;
                        }
                        $jam_terlambat = $request->terlambat;
                        $jam_masuk_kerja = $request->jam_masuk;
                        $jam_pulang_cepat = NULL;
                        $img_name = NULL;
                        $jam_keluar = NULL;
                        $jam_kembali = NULL;
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $catatan_backup = NULL;
                        $tanggal = $request->tanggal;
                        $tanggal_selesai = NULL;
                        $no_form = Auth::user()->kontrak_kerja . '/SK/FKDT/' . date('Y/m/d') . '/' . $no;
                        $folderPath     = public_path('signature/izin/');
                        $image_parts    = explode(";base64,", $request->signature);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type     = $image_type_aux[1];
                        $image_base64   = base64_decode($image_parts[1]);
                        $uniqid         = date('y-m-d') . '-' . uniqid();
                        $file           = $folderPath . $uniqid . '.' . $image_type;
                        file_put_contents($file, $image_base64);
                        $data                   = new Izin();
                        $data->user_id          = $request->id_user;
                        $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                        $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                        $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                        $data->telp             = $request->telp;
                        $data->terlambat        = $jam_terlambat;
                        $data->jam_masuk_kerja  = $jam_masuk_kerja;
                        $data->pulang_cepat     = $jam_pulang_cepat;
                        $data->email            = $request->email;
                        $data->fullname         = $request->fullname;
                        $data->izin             = $request->izin;
                        $data->user_id_backup   = $id_backup;
                        $data->user_name_backup = $name_backup;
                        $data->catatan_backup = $catatan_backup;
                        $data->foto_izin        = $img_name;
                        $data->jam_keluar        = $jam_keluar;
                        $data->jam_kembali        = $jam_kembali;
                        $data->tanggal          = $tanggal;
                        $data->tanggal_selesai   = $tanggal_selesai;
                        $data->jam              = $request->jam;
                        $data->keterangan_izin  = $request->keterangan_izin;
                        $data->approve_atasan   = $request->approve_atasan;
                        $data->id_approve_atasan = $request->id_user_atasan;
                        $data->status_izin      = 1;
                        $data->no_form_izin      = $no_form;
                        $data->ttd_pengajuan    = $uniqid;
                        $data->waktu_ttd_pengajuan    = date('Y-m-d');
                        $data->ttd_atasan      = NULL;
                        $data->waktu_approve      = NULL;
                        $data->save();
                        // jam telat
                        $date1          = new DateTime($tanggal . $request->jam_masuk);
                        $date2          = new DateTime($tanggal . $request->jam);
                        $interval       = $date1->diff($date2);
                        $toleransi_mnt = 05;
                        $jum_mnt  = $interval->format('%i');
                        $jum_mnt_toleransi = ($jum_mnt - $toleransi_mnt);
                        $jum_hours  = $interval->format('%H');
                        $jum_second  = $interval->format('%S');
                        $jml_all = ($jum_hours . ':' . $jum_mnt_toleransi . ':' . $jum_second);
                        // dd($jml_all);
                        // 
                        $lokasi_kantor = Lokasi::where('lokasi_kantor', $lokasi_kerja)->first();
                        $lat_kantor = $lokasi_kantor->lat_kantor;
                        $long_kantor = $lokasi_kantor->long_kantor;
                        // absen
                        $update = MappingShift::where('id', $request->id_mapping)->first();
                        $update->jam_absen = date('H:i:s');
                        $update->telat = $jml_all;
                        $update->foto_jam_absen = $request['foto_jam_absen'];
                        $update->lat_absen = $request['lat_absen'];
                        $update->long_absen = $request['long_absen'];
                        $update->jarak_masuk = $request['jarak_masuk'];
                        $update->lokasi_absen = $request['lokasi_absen'];
                        $update->status_absen = 'HADIR KERJA';
                        $update->keterangan_absensi = 'TELAT HADIR';
                        $update->kelengkapan_absensi = 'BELUM PRESENSI PULANG';
                        $update->update();

                        ActivityLog::create([
                            'user_id' => Auth::user()->id,
                            'activity' => 'tambah',
                            'description' => 'Absen Masuk Pada Tanggal ' . $tanggal,
                            'status_absen_skrg' => MappingShift::where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->get(),
                        ]);

                        $request->session()->flash('absenmasuksuccess');
                        return redirect('home');
                    }
                }
            }
        }
    }
    public function myLocation(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        // dd($request->all());
        return view('users.absen.locationmaps', [
            'title' => 'Maps',
            'lat' => $request->lat_location,
            'long' => $request->long_location,
            'lokasi_kantor' => Lokasi::first(),
            'user' => Auth::user()->name
        ]);
    }

    public function absenMasuk(Request $request)
    {
        if ($request->karyawan_id != Auth::user()->id) {
            $request->session()->flash('facetidaksesuai', 'Gagal Absen Masuk');
            return redirect('/home/absen');
        } else {
            $lokasi_kerja = Auth::guard('web')->user()->penempatan_kerja;
            if ($lokasi_kerja == '' || $lokasi_kerja == NULL) {
                $request->session()->flash('lokasikerjanull', 'Gagal Absen Masuk');
                return redirect('/home');
            } else {
                $cek_penugasan = MappingShift::where('id', $request->shift_karyawan)
                    ->where('keterangan_absensi', 'ABSENSI PENUGASAN WILAYAH KANTOR')
                    ->first();
                if ($cek_penugasan != '' || $cek_penugasan != NULL) {
                    $request->session()->flash('penugasan_wilayah_kantor');
                    return redirect('/home');
                } else {
                    // dd('ok1');
                    date_default_timezone_set('Asia/Jakarta');
                    $user_login = auth()->user()->id;
                    $lokasi_kantor = Lokasi::where('lokasi_kantor', $lokasi_kerja)->first();
                    // dd($lokasi_kantor);
                    if ($request["lat_absen"] == NULL && $request["long_absen"] == NULL) {
                        $request->session()->flash('latlongnull', 'Gagal Absen Masuk');
                        return redirect('/home');
                    } else {
                        if ($lokasi_kantor->kategori_kantor == 'all') {
                            $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->select('titiks.*')->get();
                            // dd($lokasi_all);
                            foreach ($lokasi_all as $lokasi) {
                                $rumus = $this->distance($request["lat_absen"], $request["long_absen"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                                if ($rumus < $lokasi->radius_titik) {
                                    $request["jarak_masuk"] = $rumus;
                                    $request["lokasi_absen"] = $lokasi->id;
                                }
                            }
                        } else if ($lokasi_kantor->kategori_kantor == 'all sps') {
                            $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('lokasis.kategori_kantor', 'sps')->select('titiks.*')->get();
                            // dd($lokasi_all);
                            foreach ($lokasi_all as $lokasi) {
                                $rumus = $this->distance($request["lat_absen"], $request["long_absen"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                                if ($rumus < $lokasi->radius_titik) {
                                    $request["jarak_masuk"] = $rumus;
                                    $request["lokasi_absen"] = $lokasi->id;
                                }
                            }
                        } else if ($lokasi_kantor->kategori_kantor == 'all sp') {
                            $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('lokasis.kategori_kantor', 'sp')->select('titiks.*')->get();
                            // dd($lokasi_all);
                            foreach ($lokasi_all as $lokasi) {
                                $rumus = $this->distance($request["lat_absen"], $request["long_absen"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                                if ($rumus < $lokasi->radius_titik) {
                                    $request["jarak_masuk"] = $rumus;
                                    $request["lokasi_absen"] = $lokasi->id;
                                }
                            }
                        } else {
                            $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('titiks.lokasi_id', $lokasi_kantor->id)->select('titiks.*')->get();
                            // dd($lokasi_all);
                            foreach ($lokasi_all as $lokasi) {
                                $rumus = $this->distance($request["lat_absen"], $request["long_absen"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                                if ($rumus < $lokasi->radius_titik) {
                                    $request["jarak_masuk"] = $rumus;
                                    $request["lokasi_absen"] = $lokasi->id;
                                }
                            }
                        }
                        if ($request['jarak_masuk'] == NULL) {
                            $request->session()->flash('absenmasukoutradius', 'Gagal Absen Masuk');
                            return redirect('/home');
                        }
                    }
                    // dd($lokasi_kantor);
                    $tglskrg = date('Y-m-d');

                    // dd('gak oke');
                    // dd($request["jarak_masuk"]);
                    $foto_jam_absen = $request["foto_jam_absen"];
                    $image_parts = explode(";base64,", $foto_jam_absen);
                    if ($image_parts[0] == NULL) {
                        $request->session()->flash('cameraoff');
                        return redirect('home/absen');
                    }

                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'foto_jam_absen/' . uniqid() . '.png';
                    // dd($image_parts);
                    Storage::put($fileName, $image_base64);


                    $request["foto_jam_absen"] = $fileName;

                    $mapping_shift = MappingShift::where('id', $request->shift_karyawan)->get();
                    // dd($mapping_shift);
                    foreach ($mapping_shift as $mp) {
                        $shift = $mp->Shift->jam_masuk;
                        $tanggal = $mp->tanggal_masuk;
                    }

                    $tgl_skrg = date("Y-m-d H:i:s");

                    // $date1          = new DateTime($tanggal . '09:00');
                    $date1          = new DateTime($tanggal . $shift);
                    $date2          = new DateTime($tgl_skrg);
                    if ($date1 >= $date2) {
                        $jml_all = 0;
                    } else {
                        // dd('ok1');
                        $interval       = $date1->diff($date2);
                        $jum_mnt  = ($interval->i);
                        $jum_hours  = ($interval->h);
                        $jum_hour_mnt  = ($jum_hours * 60);
                        $toleransi_mnt = 5;
                        $jml_all = ($jum_hour_mnt + $jum_mnt - $toleransi_mnt);
                    }
                    // dd($jml_all);
                    // dd($diff); // 5273
                    if ($jml_all <= 0) {
                        $telat = 0;
                        // dd($telat);
                    } else if ($jml_all > 0 && $jml_all <= 180) {
                        $telat = $jml_all;
                        $site_job = Auth::guard('web')->user()->site_job;
                        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
                        $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
                        // dd($jam_kerja);
                        $kontrak = Auth::guard('web')->user()->kontrak_kerja;
                        // dd($user);
                        if (Auth::user()->kategori == 'Karyawan Bulanan') {
                            $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                                ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                                ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                                ->where('users.id', Auth()->user()->id)->first();
                            // dd($user->atasan_id);
                            $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
                            // dd($IdLevelAtasan);
                            // $IdLevelAtasan1 = LevelJabatan::where('level_jabatan', '0')->first();
                            if ($lokasi_site_job->kategori_kantor == 'sps') {
                                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan2 == NULL || $atasan2 == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sp')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sps')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan2 == NULL || $atasan2 == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sp')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sps')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan2 == NULL || $atasan2 == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sp')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sps')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                        ->first();
                                    if ($atasan2 == NULL || $atasan2 == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sp')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sps')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->first();
                                    if ($atasan2 == NULL || $atasan2 == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sp')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sps')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all') {
                                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                    // dd($atasan2);
                                    if ($atasan2 == NULL || $atasan2 == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        if ($get_atasan_site->holding == 'sps') {
                                            // dd('ok');
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sp')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where('jabatans.holding', 'sps')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                            ->first();
                                        // dd($atasan);
                                        if ($atasan == NULL) {
                                            $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                                ->first();
                                            // dd($atasan2);
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            }
                        } else if (Auth::user()->kategori == 'Karyawan Harian') {
                            $user = DB::table('users')->where('users.id', Auth()->user()->id)->first();
                            $atasan = DB::table('users')
                                ->join('mapping_shifts', function ($join) {
                                    $join->on('mapping_shifts.koordinator_id', '=', 'users.id');
                                })
                                ->select('users.*', 'mapping_shifts.koordinator_id')
                                ->first();
                            // dd($atasan);
                            $get_user_backup = NULL;
                            $getUserAtasan = $atasan;
                        }
                        // dd('ok');
                        return view('users.absen.form_datang_terlambat', [
                            'jam_datang' => date('H:i:s'),
                            'jumlah_terlambat' => $jml_all,
                            'getUserAtasan' => $getUserAtasan,
                            'jam_kerja' => $jam_kerja,
                            'user' => $user,
                            'telat' => $telat,
                            'foto_jam_absen' => $request["foto_jam_absen"],
                            'jarak_masuk' => $request["jarak_masuk"],
                            'lat_absen' => $request["lat_absen"],
                            'long_absen' => $request["long_absen"],
                            'lokasi_absen' => $request["lokasi_absen"],
                        ]);
                    } else if ($jml_all > 180) {
                        // dd('ok1');
                        $telat = $jml_all;
                        $update = MappingShift::where('id', $request->shift_karyawan)->first();
                        $update->jam_absen = date('H:i:s');
                        $update->telat = $telat;
                        $update->foto_jam_absen = $request['foto_jam_absen'];
                        $update->lat_absen = $request['lat_absen'];
                        $update->long_absen = $request['long_absen'];
                        $update->jarak_masuk = $request['jarak_masuk'];
                        $update->lokasi_absen = $request['lokasi_absen'];
                        $update->status_absen = 'TIDAK HADIR KERJA';
                        $update->keterangan_absensi = 'TIDAK HADIR KERJA';
                        $update->kelengkapan_absensi = 'BELUM PRESENSI PULANG';
                        $update->update();

                        ActivityLog::create([
                            'user_id' => Auth::user()->id,
                            'activity' => 'tambah',
                            'description' => 'Absen Masuk Pada Tanggal ' . $tanggal,
                            'status_absen_skrg' => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->get(),
                        ]);
                        $request->session()->flash('absen_tidak_masuk');
                        return redirect('/home');
                    }


                    // dd(date('H:i:s'));
                    $update = MappingShift::where('id', $request->shift_karyawan)->first();
                    $update->jam_absen = date('H:i:s');
                    $update->telat = $telat;
                    $update->foto_jam_absen = $request['foto_jam_absen'];
                    $update->lat_absen = $request['lat_absen'];
                    $update->long_absen = $request['long_absen'];
                    $update->jarak_masuk = $request['jarak_masuk'];
                    $update->lokasi_absen = $request['lokasi_absen'];
                    $update->status_absen = 'HADIR KERJA';
                    $update->keterangan_absensi = 'TEPAT WAKTU';
                    $update->kelengkapan_absensi = 'BELUM PRESENSI PULANG';
                    $update->update();

                    ActivityLog::create([
                        'user_id' => Auth::user()->id,
                        'activity' => 'tambah',
                        'description' => 'Absen Masuk Pada Tanggal ' . $tanggal,
                        'status_absen_skrg' => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->get(),
                    ]);

                    // dd($tglskrg);
                    $request->session()->flash('absenmasuksuccess', 'Berhasil Absen Masuk');
                    return redirect('/home');
                }
            }
        }
    }

    public function absenPulang(Request $request)
    {
        // dd($interval->h);
        if ($request->karyawan_id != Auth::user()->id) {
            $request->session()->flash('facetidaksesuai', 'Gagal Absen Masuk');
            return redirect()->back();
        } else if ($request->karyawan_id == NULL) {
            $request->session()->flash('facetidaksesuai', 'Gagal Absen Masuk');
            return redirect()->back();
        } else {
            // dd($request->all());
            $lokasi_kerja = Auth::guard('web')->user()->penempatan_kerja;
            if ($lokasi_kerja == '' || $lokasi_kerja == NULL) {
                $request->session()->flash('lokasikerjanull', 'Gagal Absen Masuk');
                return redirect('/home');
            } else {
                date_default_timezone_set('Asia/Jakarta');
                $user_login = auth()->user()->id;
                $lokasi_kantor = Lokasi::where('lokasi_kantor', $lokasi_kerja)->first();
                // dd($lokasi_kantor);
                $request["jarak_pulang"] == NULL;
                if ($lokasi_kantor->kategori_kantor == 'all') {
                    $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->select('titiks.*')->get();
                    // dd($lokasi_all);
                    foreach ($lokasi_all as $lokasi) {
                        $rumus = $this->distance($request["lat_pulang"], $request["long_pulang"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                        if ($rumus < $lokasi->radius_titik) {
                            $request["jarak_pulang"] = $rumus;
                            $request["lokasi_absen_pulang"] = $lokasi->id;
                        }
                    }
                } else if ($lokasi_kantor->kategori_kantor == 'all sps') {
                    $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('lokasis.kategori_kantor', 'sps')->select('titiks.*')->get();
                    // dd($lokasi_all);
                    foreach ($lokasi_all as $lokasi) {
                        $rumus = $this->distance($request["lat_pulang"], $request["long_pulang"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                        if ($rumus < $lokasi->radius_titik) {
                            $request["jarak_pulang"] = $rumus;
                            $request["lokasi_absen_pulang"] = $lokasi->id;
                        }
                    }
                } else if ($lokasi_kantor->kategori_kantor == 'all sp') {
                    $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('lokasis.kategori_kantor', 'sp')->select('titiks.*')->get();
                    // dd($lokasi_all);
                    foreach ($lokasi_all as $lokasi) {
                        $rumus = $this->distance($request["lat_pulang"], $request["long_pulang"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                        if ($rumus < $lokasi->radius_titik) {
                            $request["jarak_pulang"] = $rumus;
                            $request["lokasi_absen_pulang"] = $lokasi->id;
                        }
                    }
                } else {
                    $lokasi_all = Titik::join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('titiks.lokasi_id', $lokasi_kantor->id)->select('titiks.*')->get();
                    // dd($lokasi_all);
                    foreach ($lokasi_all as $lokasi) {
                        $rumus = $this->distance($request["lat_pulang"], $request["long_pulang"], $lokasi->lat_titik, $lokasi->long_titik, "K") * 1000;

                        if ($rumus < $lokasi->radius_titik) {
                            $request["jarak_pulang"] = $rumus;
                            $request["lokasi_absen_pulang"] = $lokasi->id;
                        }
                    }
                }
                // dd($rumus);
                // dd($lokasi_absen);
                if ($request["jarak_pulang"] == NULL) {
                    $request->session()->flash('absenpulangoutradius', 'Gagal Absen Pulang');
                    return redirect('/home');
                }
                $tglskrg = date('Y-m-d');
                $foto_jam_pulang = $request["foto_jam_pulang"];

                $image_parts = explode(";base64,", $foto_jam_pulang);

                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'foto_jam_pulang/' . uniqid() . '.png';

                Storage::put($fileName, $image_base64);

                $request["foto_jam_pulang"] = $fileName;

                $mapping_shift = MappingShift::where('id', $request->shift_karyawan)->get();
                foreach ($mapping_shift as $mp) {
                    $shiftmasuk = $mp->Shift->jam_masuk;
                    $shiftpulang = $mp->Shift->jam_keluar;
                    $tanggal = $mp->tanggal_masuk;
                }
                $new_tanggal = "";
                $timeMasuk = strtotime($shiftmasuk);
                $timePulang = strtotime($shiftpulang);

                // dd($timeMasuk);
                if ($timePulang < $timeMasuk) {
                    $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
                } else {
                    $new_tanggal = $tanggal;
                }

                $tgl_skrg = date("Y-m-d");

                $awal = new DateTime($new_tanggal . $shiftmasuk);
                $akhir  = new DateTime($tgl_skrg . $request["jam_pulang"]);
                $diff  = $awal->diff($akhir);
                $hours = $diff->format('%H');
                $minutes = $diff->format('%I');
                $second = $diff->format('%S');
                $hitung_jam_kerja = ($hours . ':' . $minutes . ':' . $second);
                // dd($diff);
                if ($shiftpulang > $request["jam_pulang"]) {
                    // dd($hitung_jam_kerja);
                    if ($hitung_jam_kerja >= '06:00:00') {
                        $cek_tbl_izin = Izin::where('user_id', Auth::user()->id)->where('tanggal', $tgl_skrg)->where('izin', 'Pulang Cepat')->where('status_izin', '2')->first();

                        if ($cek_tbl_izin = '' || $cek_tbl_izin == NULL) {
                            $site_job = Auth::guard('web')->user()->site_job;
                            $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
                            if (Auth::user()->kategori == 'Karyawan Bulanan') {
                                $user = DB::table('users')->join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                                    ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                                    ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                                    ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                                    ->where('users.id', Auth()->user()->id)->first();
                                // dd($user->atasan_id);
                                $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
                                if ($lokasi_site_job->kategori_kantor == 'sps') {
                                    $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sp')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sps')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                                    $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sp')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sps')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                                    $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sp')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sps')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                                    $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                            ->first();
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sp')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sps')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                                    $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                            ->first();
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sp')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sps')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                                    $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $atasan2 = User::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                            ->first();
                                        // dd($atasan2);
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            if ($get_atasan_site->holding == 'sps') {
                                                // dd('ok');
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sp')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where('jabatans.holding', 'sps')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = User::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                                ->first();
                                            // dd($atasan);
                                            if ($atasan == NULL) {
                                                $atasan2 = User::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                                    ->first();
                                                // dd($atasan2);
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                }
                            } else if (Auth::user()->kategori == 'Karyawan Harian') {
                                $user = DB::table('users')->where('id', Auth()->user()->id)->first();
                                $atasan = DB::table('users')
                                    ->join('mapping_shifts', function ($join) {
                                        $join->on('mapping_shifts.koordinator_id', '=', 'users.id');
                                    })
                                    ->select('users.*', 'mapping_shifts.koordinator_id')
                                    ->first();
                                $getUserAtasan = $atasan;
                            }
                            $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
                            if ($jam_kerja == '' || $jam_kerja == NULL) {
                                $req_jm_klr = NULL;
                            } else {
                                $jam_masuk = \Carbon\Carbon::parse($jam_kerja->Shift->jam_masuk)->addMinute(5)->isoFormat('HH:mm');
                                // dd($jam_masuk);
                                if ($jam_kerja->jam_absen <= $jam_masuk) {
                                    $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_masuk);
                                } else {
                                    $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->jam_absen);
                                }
                            }
                            if ($req_jm_klr == '' || $req_jm_klr == NULL) {
                                $jam_min_plg_cpt = NULL;
                            } else {
                                $jam_min_plg_cpt = \Carbon\Carbon::parse($req_jm_klr)->addHour(6)->isoFormat('H:mm');
                            }
                            $akhir = new DateTime($new_tanggal . $shiftpulang);
                            $awal  = new DateTime($tgl_skrg . $request["jam_pulang"]);
                            $diff  = $awal->diff($akhir);
                            $hours = $diff->format('%H');
                            $minutes = $diff->format('%I');
                            $second = $diff->format('%S');
                            $hitung_pulang_cepat = ($hours . ':' . $minutes . ':' . $second);
                            // dd($hitung_pulang_cepat);
                            $pulang_cepat = $hitung_pulang_cepat;
                            return view('users.absen.form_pulang_cepat', [
                                'title'             => 'Tambah Izin Karyawan',
                                'data_user'         => $user,
                                'getUserAtasan'     => $getUserAtasan,
                                'user'              => $user,
                                'jam_kerja'       => $jam_kerja,
                                'jam_min_plg_cpt'       => $jam_min_plg_cpt,
                                'pulang_cepat' => $pulang_cepat,
                                'foto_jam_pulang' => $request["foto_jam_pulang"],
                                'jarak_pulang' => $request["jarak_pulang"],
                                'lat_pulang' => $request['lat_pulang'],
                                'long_pulang' => $request['long_pulang'],
                                'total_jam_kerja' => $hitung_jam_kerja,
                                'lokasi_absen_pulang' => $request['lokasi_absen_pulang'],
                            ]);
                        } else {
                            $akhir = new DateTime($new_tanggal . $shiftpulang);
                            $awal  = new DateTime($tgl_skrg . $request["jam_pulang"]);
                            $diff  = $awal->diff($akhir);
                            $hours = $diff->format('%H');
                            $minutes = $diff->format('%I');
                            $second = $diff->format('%S');
                            $hitung_pulang_cepat = ($hours . ':' . $minutes . ':' . $second);
                            $request["pulang_cepat"] = $hitung_pulang_cepat;
                            $status_absen = 'HADIR KERJA';
                            $keterangan_absensi_pulang = 'PULANG CEPAT';
                            $kelengkapan_absensi = 'PRESENSI LENGKAP';
                        }
                    } else {
                        // dd('ok1');
                        $cek_tbl_izin = Izin::where('user_id', Auth::user()->id)->where('tanggal', $tgl_skrg)->where('izin', 'Pulang Cepat')->where('status_izin', '2')->first();
                        if ($cek_tbl_izin = '' || $cek_tbl_izin == NULL) {
                            $request["pulang_cepat"] = '00:00:00';
                            $status_absen = 'TIDAK HADIR KERJA';
                            $keterangan_absensi_pulang = NULL;
                            $kelengkapan_absensi = NULL;
                        } else {
                            $akhir = new DateTime($new_tanggal . $shiftpulang);
                            $awal  = new DateTime($tgl_skrg . $request["jam_pulang"]);
                            $diff  = $awal->diff($akhir);
                            $hours = $diff->format('%H');
                            $minutes = $diff->format('%I');
                            $second = $diff->format('%S');
                            $hitung_pulang_cepat = ($hours . ':' . $minutes . ':' . $second);
                            $request["pulang_cepat"] = $hitung_pulang_cepat;
                            $status_absen = 'HADIR KERJA';
                            $keterangan_absensi_pulang = 'PULANG CEPAT';
                            $kelengkapan_absensi = 'PRESENSI LENGKAP';
                        }
                    }
                } else {
                    $request["pulang_cepat"] = '00:00:00';
                    $status_absen = 'HADIR KERJA';
                    $keterangan_absensi_pulang = 'TEPAT WAKTU';
                    $kelengkapan_absensi = 'PRESENSI LENGKAP';
                    // $request["pulang_cepat"] = ;
                    $status_absen = 'HADIR KERJA';
                }

                $validatedData = $request->validate([
                    'jam_pulang' => 'required',
                    'foto_jam_pulang' => 'required',
                    'lat_pulang' => 'required',
                    'long_pulang' => 'required',
                    'pulang_cepat' => 'required',
                    'jarak_pulang' => 'required',
                    'lokasi_absen_pulang' => 'required'
                ]);

                $update = MappingShift::where('id', $request->shift_karyawan)->first();
                $update->jam_pulang                 = $validatedData['jam_pulang'];
                $update->foto_jam_pulang            = $validatedData['foto_jam_pulang'];
                $update->lat_pulang                 = $validatedData['lat_pulang'];
                $update->long_pulang                = $validatedData['long_pulang'];
                $update->pulang_cepat               = $validatedData['pulang_cepat'];
                $update->jarak_pulang               = $validatedData['jarak_pulang'];
                $update->lokasi_absen_pulang        = $validatedData['lokasi_absen_pulang'];
                $update->total_jam_kerja            = $hitung_jam_kerja;
                $update->status_absen               = $status_absen;
                $update->keterangan_absensi_pulang  = $status_absen;
                $update->kelengkapan_absensi        = $kelengkapan_absensi;
                $update->update();

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'tambah',
                    'description' => 'Absen Pulang Pada Tanggal ' . $tanggal,
                    'status_absen_skrg' => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->get(),

                ]);
                $request->session()->flash('absenpulangsuccess', 'Berhasil Absen Pulang');
                return redirect('/home');
            }
        }
    }
    public function proses_izin_pulang_cepats(Request $request)
    {
        // dd($request->all());
        $jam_kerja = MappingShift::with('Shift')->where('user_id', Auth::user()->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $request->session()->flash('mapping_kosong');
            return redirect('/home');
        } else {
            // dd('ok');
            if ($request->id_user_atasan == NULL || $request->id_user_atasan == '') {
                if ($request->level_jabatan != '1') {
                    $request->session()->flash('atasankosong');
                    return redirect('/home');
                } else {
                    $count_tbl_izin = Izin::whereDate('tanggal', date('Y-m-d'))->where('izin', $request->izin)->count();
                    // dd($count_tbl_izin);
                    $countstr = strlen($count_tbl_izin + 1);
                    if ($countstr == '1') {
                        $no = '000' . $count_tbl_izin + 1;
                    } else if ($countstr == '2') {
                        $no = '00' . $count_tbl_izin + 1;
                    } else if ($countstr == '3') {
                        $no = '0' . $count_tbl_izin + 1;
                    } else {
                        $no = $count_tbl_izin + 1;
                    }
                    $no_form = Auth::user()->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
                    $tgl_skrg = date('Y-m-d');
                    $akhir = new DateTime($tgl_skrg . $request["jam_pulang"]);
                    $awal  = new DateTime($tgl_skrg . $request["jam_pulang_cepat"]);
                    $diff  = $awal->diff($akhir);
                    $hours = $diff->format('%H');
                    $minutes = $diff->format('%I');
                    $second = $diff->format('%S');
                    $hitung_pulang_cepat = ($hours . ':' . $minutes . ':' . $second);
                    // dd($hitung_pulang_cepat);
                    $folderPath     = public_path('signature/izin');
                    $image_parts    = explode(";base64,", $request->signature);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type     = $image_type_aux[1];
                    $image_base64   = base64_decode($image_parts[1]);
                    $uniqid         = date('y-m-d') . '-' . uniqid();
                    $file           = $folderPath . $uniqid . '.' . $image_type;
                    file_put_contents($file, $image_base64);
                    $data                   = new Izin();
                    $data->user_id          = $request->id_user;
                    $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                    $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                    $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                    $data->telp             = $request->telp;
                    $data->email            = $request->email;
                    $data->fullname         = $request->fullname;
                    $data->izin             = $request->izin;
                    $data->tanggal          = $request->tanggal;
                    $data->jam              = $request->jam_pulang_cepat;
                    $data->keterangan_izin  = $request->keterangan_izin;
                    $data->status_izin      = 1;
                    $data->ttd_pengajuan    = $uniqid;
                    $data->waktu_ttd_pengajuan  = date('Y-m-d');
                    $data->ttd_atasan      = NULL;
                    $data->no_form_izin       = $no_form;
                    $data->waktu_approve      = NULL;
                    $data->save();

                    $update = MappingShift::where('id', $request['id_mapping'])->first();
                    $update->jam_pulang           = $request['jam_pulang_cepat'];
                    $update->foto_jam_pulang      = $request['foto_jam_pulang'];
                    $update->lat_pulang           = $request['lat_pulang'];
                    $update->long_pulang          = $request['long_pulang'];
                    $update->pulang_cepat         = $hitung_pulang_cepat;
                    $update->jarak_pulang         = $request['jarak_pulang'];
                    $update->total_jam_kerja         = $request['total_jam_kerja'];
                    $update->lokasi_absen_pulang  = $request['lokasi_absen_pulang'];
                    $update->keterangan_absensi_pulang = 'PULANG CEPAT';
                    $update->kelengkapan_absensi  = 'PRESENSI LENGKAP';
                    $update->update();
                    $request->session()->flash('absenpulangsuccess', 'Berhasil Absen Pulang');
                    return redirect('/home');
                }
            } else {
                // No form
                $count_tbl_izin = Izin::whereDate('tanggal', date('Y-m-d'))->where('izin', $request->izin)->count();
                // dd($count_tbl_izin);
                $countstr = strlen($count_tbl_izin + 1);
                if ($countstr == '1') {
                    $no = '000' . $count_tbl_izin + 1;
                } else if ($countstr == '2') {
                    $no = '00' . $count_tbl_izin + 1;
                } else if ($countstr == '3') {
                    $no = '0' . $count_tbl_izin + 1;
                } else {
                    $no = $count_tbl_izin + 1;
                }

                // $req_plg_cpt = new DateTime(date('Y-m-d') . $request->jam_pulang_cepat);
                // $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_keluar);
                // $jam_plg_cpt = $req_plg_cpt->diff($req_jm_klr);
                // if ($jam_plg_cpt->h == 3 && $jam_plg_cpt->i > 0) {
                // }
                if ($jam_kerja->jam_absen == '' && $jam_kerja->jam_pulang == '') {
                    $request->session()->flash('absen_masuk_kosong');
                    return redirect('/home');
                } else if ($jam_kerja->jam_pulang != '') {
                    $request->session()->flash('absen_pulang_terisi');
                    return redirect('/home');
                } else {
                    // dd($request->all());
                    $id_backup = NULL;
                    $name_backup = NULL;
                    $jam_pulang_cepat = $request->jam_pulang_cepat;
                    $jam_terlambat = NULL;
                    $jam_masuk_kerja = NULL;
                    $img_name = NULL;
                    $jam_keluar = NULL;
                    $jam_kembali = NULL;
                    $catatan_backup = NULL;
                    $tanggal = date('Y-m-d');
                    $tanggal_selesai = NULL;
                    $no_form = Auth::user()->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
                }
                $tgl_skrg = date('Y-m-d');
                $akhir = new DateTime($tgl_skrg . $request["jam_pulang"]);
                $awal  = new DateTime($tgl_skrg . $request["jam_pulang_cepat"]);
                $diff  = $awal->diff($akhir);
                $hours = $diff->format('%H');
                $minutes = $diff->format('%I');
                $second = $diff->format('%S');
                $hitung_pulang_cepat = ($hours . ':' . $minutes . ':' . $second);
                // dd($hitung_pulang_cepat);
                $folderPath     = public_path('signature/izin');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd($request->all());
                $data                   = new Izin();
                $data->user_id          = $request->id_user;
                $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                $data->telp             = $request->telp;
                $data->terlambat        = $jam_terlambat;
                $data->jam_masuk_kerja  = $jam_masuk_kerja;
                $data->pulang_cepat     = $jam_pulang_cepat;
                $data->email            = $request->email;
                $data->fullname         = $request->fullname;
                $data->izin             = $request->izin;
                $data->tanggal          = $tanggal;
                $data->jam              = $jam_pulang_cepat;
                $data->keterangan_izin  = $request->keterangan_izin;
                $data->approve_atasan   = $request->approve_atasan;
                $data->id_approve_atasan = $request->id_user_atasan;
                $data->status_izin      = 1;
                $data->ttd_pengajuan    = $uniqid;
                $data->waktu_ttd_pengajuan  = date('Y-m-d');
                $data->no_form_izin     = $no_form;
                $data->ttd_atasan       = NULL;
                $data->waktu_approve    = NULL;
                $data->save();

                $update = MappingShift::where('id', $request['id_mapping'])->first();
                $update->jam_pulang           = $request['jam_pulang_cepat'];
                $update->foto_jam_pulang      = $request['foto_jam_pulang'];
                $update->lat_pulang           = $request['lat_pulang'];
                $update->long_pulang          = $request['long_pulang'];
                $update->pulang_cepat         = $hitung_pulang_cepat;
                $update->jarak_pulang         = $request['jarak_pulang'];
                $update->lokasi_absen_pulang  = $request['lokasi_absen_pulang'];
                $update->total_jam_kerja         = $request['total_jam_kerja'];
                $update->keterangan_absensi_pulang = 'PULANG CEPAT';
                $update->kelengkapan_absensi  = 'PRESENSI LENGKAP';
                $update->update();
                $request->session()->flash('absenpulangsuccess', 'Berhasil Absen Pulang');
                return redirect('/home');
            }
        }
    }
    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function dataAbsen(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_absen = MappingShift::where('tanggal_masuk', $tglskrg);

        if ($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if ($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["user_id"] && $request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', $request["user_id"])->whereBetween('tanggal_masuk', [$request["mulai"], $request["akhir"]]);
        }

        return view('absen.dataabsen', [
            'title' => 'Data Absen',
            'user' => User::select('id', 'name')->get(),
            'data_absen' => $data_absen->get()
        ]);
    }

    public function maps($lat, $long)
    {
        date_default_timezone_set('Asia/Jakarta');
        return view('users.absen.locationmaps', [
            'title' => 'Maps',
            'lat' => $lat,
            'long' => $long,
            'lokasi_kantor' => Lokasi::first()
        ]);
    }

    public function editMasuk($id)
    {
        return view('absen.editmasuk', [
            'title' => 'Edit Absen Masuk',
            'data_absen' => MappingShift::findOrFail($id),
            'lokasi_kantor' => Lokasi::first()
        ]);
    }

    public function prosesEditMasuk(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $mapping_shift = MappingShift::where('id', $id)->get();

        foreach ($mapping_shift as $mp) {
            $shift = $mp->Shift->jam_masuk;
            $tanggal = $mp->tanggal_masuk;
        }

        $awal  = strtotime($tanggal . $shift);
        $akhir = strtotime($tanggal . $request["jam_absen"]);
        $diff  = $akhir - $awal;

        if ($diff <= 0) {
            $request["telat"] = 0;
        } else {
            $request["telat"] = $diff;
        }

        $lokasi_kantor = Lokasi::first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_masuk"] = $this->distance($request["lat_absen"], $request["long_absen"], $lat_kantor, $long_kantor, "K") * 1000;

        $validatedData = $request->validate([
            'jam_absen' => 'required',
            'telat' => 'nullable',
            'foto_jam_absen' => 'image|max:5000',
            'lat_absen' => 'required',
            'long_absen' => 'required',
            'jarak_masuk' => 'required',
            'status_absen' => 'required'
        ]);

        if ($request->file('foto_jam_absen')) {
            if ($request->foto_jam_absen_lama) {
                Storage::delete($request->foto_jam_absen_lama);
            }
            $validatedData['foto_jam_absen'] = $request->file('foto_jam_absen')->store('foto_jam_absen');
        }

        MappingShift::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'tambah',
            'description' => 'Edit Absen Masuk Pada Tanggal ' . $tanggal
        ]);
        return redirect('/data-absen')->with('success', 'Berhasil Edit Absen Masuk (Manual)');
    }

    public function editPulang($id)
    {
        return view('absen.editpulang', [
            'title' => 'Edit Absen Pulang',
            'data_absen' => MappingShift::findOrFail($id),
            'lokasi_kantor' => Lokasi::first()
        ]);
    }

    public function prosesEditPulang(Request $request, $id)
    {
        $mapping_shift = MappingShift::where('id', $id)->get();
        foreach ($mapping_shift as $mp) {
            $shiftmasuk = $mp->Shift->jam_masuk;
            $shiftpulang = $mp->Shift->jam_keluar;
            $tanggal = $mp->tanggal_masuk;
        }
        $new_tanggal = "";
        $timeMasuk = strtotime($shiftmasuk);
        $timePulang = strtotime($shiftpulang);


        if ($timePulang < $timeMasuk) {
            $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
        } else {
            $new_tanggal = $tanggal;
        }

        $akhir = strtotime($new_tanggal . $shiftpulang);
        $awal  = strtotime($new_tanggal . $request["jam_pulang"]);
        $diff  = $akhir - $awal;

        if ($diff <= 0) {
            $request["pulang_cepat"] = 0;
        } else {
            $request["pulang_cepat"] = $diff;
        }

        $lokasi_kantor = Lokasi::first();
        $lat_kantor = $lokasi_kantor->lat_kantor;
        $long_kantor = $lokasi_kantor->long_kantor;

        $request["jarak_pulang"] = $this->distance($request["lat_pulang"], $request["long_pulang"], $lat_kantor, $long_kantor, "K") * 1000;

        $validatedData = $request->validate([
            'jam_pulang' => 'required',
            'foto_jam_pulang' => 'image|max:5000',
            'lat_pulang' => 'required',
            'long_pulang' => 'required',
            'pulang_cepat' => 'required',
            'jarak_pulang' => 'required'
        ]);

        if ($request->file('foto_jam_pulang')) {
            if ($request->foto_jam_pulang_lama) {
                Storage::delete($request->foto_jam_pulang_lama);
            }
            $validatedData['foto_jam_pulang'] = $request->file('foto_jam_pulang')->store('foto_jam_pulang');
        }

        MappingShift::where('id', $id)->update($validatedData);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'tambah',
            'description' => 'Edit Absen Pulang Pada Tanggal ' . $tanggal
        ]);

        return redirect('/data-absen')->with('success', 'Berhasil Edit Absen Pulang (Manual)');
    }

    public function deleteAdmin($id)
    {
        $delete = MappingShift::find($id);
        Storage::delete($delete->foto_jam_absen);
        Storage::delete($delete->foto_jam_pulang);
        $delete->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'hapus',
            'description' => 'Hapus Absen'
        ]);
        return redirect('/data-absen')->with('success', 'Data Berhasil di Delete');
    }

    public function myAbsen(Request $request)
    {
        $user_login = auth()->user()->id;
        $date_now = date('Y');
        $month_now = date('m');
        $month_yesterday = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MM');
        $month_yesterday1 = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');
        $month_now1 = \Carbon\Carbon::now()->isoFormat('MMMM');
        date_default_timezone_set('Asia/Jakarta');
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date('Y-m-d');
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->get();
        $tidak_masuk = MappingShift::where('status_absen', 'Tidak Masuk')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy(DB::raw("Month(tanggal_masuk)"))
            ->pluck('count');
        $masuk = MappingShift::where('mapping_shifts.status_absen', 'Masuk')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(mapping_shifts.tanggal_masuk) as count"))
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy(DB::raw("Month(tanggal_masuk)"))
            ->pluck('count');
        $telat = MappingShift::where('status_absen', 'Telat')
            ->where('user_id', $user_login)
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal_masuk', date('Y'))
            ->groupBy(DB::raw("Month(tanggal_masuk)"))
            ->pluck('count');
        // dd();
        $telat_now = MappingShift::whereMonth('tanggal_masuk', $month_now)
            ->where('user_id', $user_login)
            ->select(DB::raw("telat as count"))
            ->pluck('count');
        $telat_yesterday = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("telat as count"))
            ->pluck('count');
        $lembur_now = MappingShift::whereMonth('tanggal_masuk', $month_now)
            ->where('user_id', $user_login)
            ->select(DB::raw("lembur as count"))
            ->pluck('count');
        $lembur_yesterday = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("lembur as count"))
            ->pluck('count');
        $data_telat_now = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("tanggal_masuk as count"))
            ->pluck('count');
        $data_telat_yesterday = MappingShift::whereMonth('tanggal_masuk', $month_yesterday)
            ->where('user_id', $user_login)
            ->select(DB::raw("tanggal_masuk as count "))
            ->pluck('count');
        if ($mapping_shift->count() > 0) {
            foreach ($mapping_shift as $mp) {
                $jam_absen = $mp->jam_absen;
                $jam_pulang = $mp->jam_pulang;
            }
        } else {
            $jam_absen = "-";
            $jam_pulang = "-";
        }
        if ($jam_absen != null && $jam_pulang == null) {
            $tanggal = $tglkmrn;
        } else {
            $tanggal = $tglskrg;
        }

        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_absen = MappingShift::where('tanggal_masuk', $tglskrg)->where('user_id', auth()->user()->id);

        if ($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if ($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', auth()->user()->id)->whereBetween('tanggal_masuk', [$request["mulai"], $request["akhir"]]);
        }

        return view('absen.myabsen', [
            'title' => 'My Absen',
            'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tanggal)->get(),
            'data_absen' => $data_absen->get(),
            'masuk' => array_map('intval', json_decode($masuk)),
            'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
            'telat' => array_map('intval', json_decode($telat)),
            'date_now' => $date_now,
            'month_now1' => $month_now1,
            'month_yesterday1' => $month_yesterday1,
            'telat_now' => array_map('intval', json_decode($telat_now)),
            'telat_yesterday' => array_map('intval', json_decode($telat_yesterday)),
            'lembur_now' => array_map('intval', json_decode($lembur_now)),
            'data_telat_now' => $data_telat_now,
            'data_telat_yesterday' => $data_telat_yesterday,
            'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday))
        ]);
    }
}
