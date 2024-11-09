<?php

namespace App\Http\Controllers;

use App\Events\IzinPost;
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
use App\Models\Karyawan;
use App\Models\LevelJabatan;
use App\Models\Penugasan;
use App\Models\Titik;
use App\Notifications\TestPusherNotification;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
        } else if (auth()->user()->is_admin == 'hrd') {
            return redirect('/dashboard/holding');
        } else {
            $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();

            date_default_timezone_set('Asia/Jakarta');
            $user_login = $user_karyawan->id;
            // dd($user_login);
            $lokasi_kantor = $user_karyawan->penempatan_kerja;
            // dd($user_karyawan);
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
            $user           = $user_karyawan->id;
            $dataizin       = Izin::with('User')->where('id_approve_atasan', $user)
                ->whereNotNull('ttd_pengajuan')
                ->where('status_izin', 1);
            // dd($dataizin);
            // get atasan tingkat 
            $datacuti_tingkat1       = Cuti::with('KategoriCuti')
                ->where('status_cuti', 1)
                ->join('karyawans', 'karyawans.id', '=', 'cutis.user_id')
                ->where('id_user_atasan', $user)
                ->whereNotNull('ttd_user')
                ->select('cutis.*', 'karyawans.name', 'karyawans.foto_karyawan');
            // dd($dataizin);
            // dd($datacuti_tingkat1);
            $datacuti_tingkat2       = Cuti::with('KategoriCuti')
                ->where('status_cuti', 2)
                ->join('karyawans', 'karyawans.id', '=', 'cutis.user_id')
                ->where('id_user_atasan2', $user)
                ->whereNotNull('ttd_user')
                ->select('cutis.*', 'karyawans.name', 'karyawans.foto_karyawan');
            $datapenugasan  = Penugasan::with('User')
                ->where('status_penugasan', '>', 1)
                ->where('status_penugasan', '<', 5)
                ->where(function ($query) use ($user) {
                    $query->where('id_diminta_oleh', '=', $user)
                        ->orWhere('id_disahkan_oleh', '=', $user)
                        ->orWhere('id_user_hrd', '=', $user)
                        ->orWhere('id_user_finance', '=', $user);
                });
            // dd($datapenugasan);
            $data_user_penugasaan  = Penugasan::with('User')
                ->where('id_user', $user)
                ->where('penugasans.status_penugasan', '5')
                // ->select('penugasans.*', 'karyawans.name')
                ->get();
            // dd($dataizin, $datacuti_tingkat1, $datacuti_tingkat2, $datapenugasan);
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
            $faceid = Karyawan::where('id', $user_login)->value('face_id');
            if ($mapping_shift == '' || $mapping_shift == NULL) {
                $jam_absen = null;
                $jam_pulang = null;
                $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->orderBy('tanggal_masuk', 'DESC')->first();
                // dd($jam_kerja->status_absensi);

                return view('users.home.index', [
                    'title'             => 'Absen',
                    'jam_kerja'         => $jam_kerja,
                    'user_karyawan'     => $user_karyawan,
                    'shift_karyawan'    => MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->first(),
                    'count_absen_hadir' => $count_absen_hadir,
                    'thnskrg'           => $thnskrg,
                    'lokasi_kantor'     => $lokasi_kantor,
                    'status_absen_skrg' => $status_absen_skrg,
                    'faceid'            => $faceid,
                    'dataizin'          => $dataizin->take(5)->get(),
                    'data_count_all'    => $dataizin->count() + $datacuti_tingkat1->count() + $datacuti_tingkat2->count() + $datapenugasan->count(),
                    'data_count'        => $dataizin->take(5)->count() + $datacuti_tingkat1->take(2)->count() + $datacuti_tingkat2->take(2)->count() + $datapenugasan->take(2)->count(),
                    'datacuti_tingkat1' => $datacuti_tingkat1->take(2)->get(),
                    'datacuti_tingkat2' => $datacuti_tingkat2->take(2)->get(),
                    'datapenugasan'     => $datapenugasan->take(2)->get(),
                    // 'data_notif'     => $data_notif,
                    'count_absen_izin'  => $count_absen_izin,
                    'count_absen_sakit' => $count_absen_sakit,
                    'count_absen_telat' => $count_absen_telat,
                    'kantor_penugasan'  => $kantor_penugasan,
                    'location'          => Titik::all(),
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
                    'user_karyawan'     => $user_karyawan,
                    'thnskrg'           => $thnskrg,
                    'get_shift'         => $status_absen_skrg,
                    'lokasi_kantor'     => $lokasi_kantor,
                    'jam_kerja'         => $jam_kerja,
                    'status_absen_skrg' => $status_absen_skrg,
                    'faceid'            => $faceid,
                    'dataizin'          => $dataizin->take(5)->get(),
                    'data_count_all'    => $dataizin->count() + $datacuti_tingkat1->count() + $datacuti_tingkat2->count() + $datapenugasan->count(),
                    'data_count'        => $dataizin->take(5)->count() + $datacuti_tingkat1->take(2)->count() + $datacuti_tingkat2->take(2)->count() + $datapenugasan->take(2)->count(),
                    'datacuti_tingkat1' => $datacuti_tingkat1->take(2)->get(),
                    'datacuti_tingkat2' => $datacuti_tingkat2->take(2)->get(),
                    'datapenugasan'     => $datapenugasan->take(2)->get(),
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->get();
        // dd($user);
        return view('users.createfaceid.index', [
            'user_karyawan' => $user_karyawan,
            'karyawan' => $karyawan,
        ]);
    }
    public function get_notif()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $count_izin_0 = Izin::where('user_id', $user_karyawan->id)->where('status_izin', '0')->count();
        $count_cuti_0 = Cuti::where('user_id', $user_karyawan->id)->where('status_cuti', '0')->count();
        $count_penugasan_0 = Penugasan::where('id_user', $user_karyawan->id)->where('status_penugasan', '0')->count();
        return response()->json([
            'count_izin' => $count_izin_0,
            'count_cuti' => $count_cuti_0,
            'count_penugasan' => $count_penugasan_0,
        ]);
    }
    public function get_notif_cuti()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $count_cuti_0 = Cuti::where('user_id', $user_karyawan->id)->where('status_cuti', '0')->count();
        return json_encode($count_cuti_0);
    }
    public function get_notif_penugasan()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $count_penugasan_0 = Penugasan::where('id_user', $user_karyawan->id)->where('status_penugasan', '0')->count();
        return json_encode($count_penugasan_0);
    }
    public function savefaceid(Request $request)
    {
        // dd($request->all());
        $query = Karyawan::where('id', $request->karyawan_id)->first();
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        // dd($jam_kerja);
        $site_job = $user_karyawan->site_job;
        $kontrak = $user_karyawan->kontrak_kerja;
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        if ($user_karyawan->kategori == 'Karyawan Bulanan') {
            $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->where('karyawans.id', $user_karyawan->id)->first();
            $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
            if ($IdLevelAtasan == NULL) {
                $getUserAtasan = NULL;
            } else {
                if ($lokasi_site_job->kategori_kantor == 'sps') {
                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->first();
                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        if ($IdLevelAtasan->atasan_id == NULL) {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan->id)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            // dd($get_atasan_site);
                            if ($get_atasan_site->holding == 'sps') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sps'])
                                    ->orderBy('jabatans.holding', 'ASC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sps', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            if ($get_atasan_more == NULL) {
                                $getUserAtasan  = NULL;
                            } else {
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                            }
                        } else {
                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                    // dd($get_atasan_more);
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->orderBy('jabatans.holding', 'ASC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    // dd($atasan);
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                }
                            } else {
                                $getUserAtasan  = $atasan2;
                            }
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        ->first();
                    // dd($get_nama_jabatan);
                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sps'])
                                    ->orderBy('jabatans.holding', 'ASC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sps', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                ->first();
                            if ($atasan == NULL) {
                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->orderBy('jabatans.holding', 'ASC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                }
                            } else {
                                $getUserAtasan  = $atasan2;
                            }
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        ->first();
                    // dd($get_nama_jabatan);

                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sps'])
                                    ->orderBy('jabatans.holding', 'ASC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', 'sps')
                                    ->whereIn('jabatans.holding', ['sps', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            if ($get_atasan_more == NULL) {
                                $getUserAtasan  = NULL;
                            } else {
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                            }
                        } else {
                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->orderBy('jabatans.holding', 'ASC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                }
                            } else {
                                $getUserAtasan  = $atasan2;
                            }
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                        ->first();
                    // dd($get_nama_jabatan);
                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sps'])
                                    ->orderBy('jabatans.holding', 'ASC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sps', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            if ($get_atasan_more == NULL) {
                                $getUserAtasan  = NULL;
                            } else {
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                    ->first();
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    if ($atasan2 == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            }
                        } else {
                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->orderBy('jabatans.holding', 'ASC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $getUserAtasan  = $atasan2;
                            }
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->first();
                    // dd($get_nama_jabatan);
                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        if ($IdLevelAtasan->atasan_id == NULL) {
                            // dd('p');
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
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sps'])
                                    ->orderBy('jabatans.holding', 'ASC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sps', 'sip'])
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            if ($get_atasan_more == NULL) {
                                $getUserAtasan  = NULL;
                            } else {
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                    ->first();
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    if ($atasan2 == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            }
                        } else {
                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                ->first();
                            // dd($atasan2);
                            if ($atasan2 == NULL || $atasan2 == '') {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                // dd($get_atasan_site);
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->orderBy('jabatans.holding', 'ASC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    // dd($get_atasan_more);
                                }
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $getUserAtasan  = $atasan2;
                            }
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all sip') {
                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->first();
                    // dd($get_nama_jabatan);
                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sps', 'sp'])
                                    ->orderBy('jabatans.holding', 'ASC')
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->where('jabatans.holding', ['sps'])
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            if ($get_atasan_more == NULL) {
                                $getUserAtasan  = NULL;
                            } else {
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                    ->first();
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->first();
                                    if ($atasan2 == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            }
                        } else {
                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                ->first();
                            // dd($atasan2);
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sp'])
                                        ->orderBy('jabatans.holding', 'ASC')
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->where('jabatans.holding', ['sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $getUserAtasan  = $atasan2;
                            }
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                    // dd($get_nama_jabatan);
                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->where('jabatans.id', $IdLevelAtasan->id)
                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                        // dd($get_atasan_site);
                        if ($get_atasan_site->holding == 'sps') {
                            // dd('ok');
                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                ->whereIn('jabatans.holding', ['sp', 'sip'])
                                ->orderBy('jabatans.holding', 'DESC')
                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->orderBy('jabatans.holding', 'DESC')
                                ->first();
                        } else if ($get_atasan_site->holding == 'sip') {
                            // dd('ok');
                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                ->whereIn('jabatans.holding', ['sp', 'sps'])
                                ->orderBy('jabatans.holding', 'ASC')
                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            // dd($get_atasan_more);
                        } else {
                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                ->whereIn('jabatans.holding', ['sps', 'sip'])
                                ->orderBy('jabatans.holding', 'DESC')
                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->orderBy('jabatans.holding', 'DESC')
                                ->first();
                        }
                        // dd($get_atasan_more);
                        if ($get_atasan_more == NULL) {
                            $getUserAtasan  = NULL;
                        } else {
                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            // dd($atasan);
                            if ($atasan == NULL) {
                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                        }
                    } else {
                        $getUserAtasan  = $get_nama_jabatan;
                    }
                }
            }
        } else if ($user_karyawan->kategori == 'Karyawan Harian') {
            $user = Karyawan::where('karyawans.id', $user_karyawan->id)->first();
            $atasan = Karyawan::join('mapping_shifts', function ($join) {
                $join->on('mapping_shifts.koordinator_id', '=', 'karyawans.id');
            })
                ->select('karyawans.*', 'mapping_shifts.koordinator_id')
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
        $user_login =  Karyawan::where('karyawans.id', Auth::user()->karyawan_id)->value('id');
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->filter_month)) {
                $count_absen_hadir = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->where('status_absen', 'HADIR KERJA')->count();
                $count_telat = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->where('keterangan_absensi', 'TELAT HADIR')->where('status_absen', 'HADIR KERJA')->count();
                $count_izin = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_absensi', 'IZIN TIDAK MASUK')->count();
                $count_sakit = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_absensi', 'IZIN SAKIT')->count();
                // dd($count_absen_hadir);
            } else {
                $count_absen_hadir = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->where('status_absen', 'HADIR KERJA')->count();
                $count_telat = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->where('keterangan_absensi', 'TELAT HADIR')->where('status_absen', 'HADIR KERJA')->count();
                $count_izin = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_absensi', 'IZIN TIDAK MASUK')->count();
                $count_sakit = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_absensi', 'IZIN SAKIT')->count();
            }
        }
        $result = [
            'count_absen_hadir' => $count_absen_hadir,
            'count_telat' => $count_telat,
            'count_izin' => $count_izin,
            'count_sakit' => $count_sakit
        ];
        return $result;
    }
    public function datatableHome(Request $request)
    {

        // dd($request->all());
        $user_login = Karyawan::where('id', Auth::user()->karyawan_id)->value('id');
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
                        $result = Carbon::parse($row->tanggal_masuk)->isoFormat('DD-MM-Y');;
                        return $result;
                    })
                    ->addColumn('jam_absen', function ($row) {
                        if ($row->jam_absen == NULL) {
                            return '-';
                        } else {
                            $result = Carbon::parse($row->jam_absen)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->addColumn('jam_pulang', function ($row) {
                        if ($row->jam_pulang == NULL) {
                            return '-';
                        } else {
                            $result = Carbon::parse($row->jam_pulang)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->addColumn('keterangan', function ($row) {
                        $now = date('Y-m-d');
                        if ($row->tanggal_masuk == $now) {
                            if ($row->keterangan_absensi == '' || $row->keterangan_absensi == NULL) {
                                return '<span class="badge w-100 light badge-info">BELUM ABSENSI</span>';
                            } else {
                                return '-';
                            }
                        } else {
                            if ($row->status_absen == NULL) {
                                return '-';
                            } else if ($row->status_absen == 'CUTI') {
                                return '<span class="badge w-100 light badge-secondary">CUTI</span>';
                            } else if ($row->status_absen == 'LIBUR') {
                                return '<span class="badge w-100 light badge-warning">LIBUR</span>';
                            } else if ($row->status_absen == 'TIDAK HADIR KERJA') {
                                return '<span class="badge w-100 light badge-danger">TIDAK HADIR KERJA</span>';
                            } else if ($row->status_absen == 'HADIR KERJA') {
                                return '<span class="badge w-100 light badge-success">HADIR KERJA</span>';
                            } else {
                                return $row->status_absen;
                            }
                        }
                    })
                    ->rawColumns(['tanggal_masuk', 'jam_absen', 'jam_pulang', 'keterangan'])
                    ->make(true);
            } else {
                $data = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->whereBetween('tanggal_masuk', array($dateweek, $datenow))->orderBy('tanggal_masuk', 'DESC')->get();
                // dd($data);
                return DataTables::of($data)
                    ->addColumn('tanggal_masuk', function ($row) {
                        $result = Carbon::parse($row->tanggal_masuk)->isoFormat('DD-MM-Y');;
                        return $result;
                    })
                    ->addColumn('jam_absen', function ($row) {
                        if ($row->jam_absen == NULL) {
                            return '-';
                        } else {
                            $result = Carbon::parse($row->jam_absen)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->addColumn('jam_pulang', function ($row) {
                        if ($row->jam_pulang == NULL) {
                            return '-';
                        } else {
                            $result = Carbon::parse($row->jam_pulang)->isoFormat('HH:mm');;
                            return $result;
                        }
                    })
                    ->addColumn('keterangan', function ($row) {
                        $now = date('Y-m-d');
                        if ($row->tanggal_masuk == $now) {
                            if ($row->keterangan_absensi == '' || $row->keterangan_absensi == NULL) {
                                return '<span class="badge w-100 light badge-info">BELUM ABSENSI</span>';
                            } else {
                                return '-';
                            }
                        } else {
                            if ($row->status_absen == NULL) {
                                return '-';
                            } else if ($row->status_absen == 'CUTI') {
                                return '<span class="badge w-100 light badge-secondary">CUTI</span>';
                            } else if ($row->status_absen == 'LIBUR') {
                                return '<span class="badge w-100 light badge-warning">LIBUR</span>';
                            } else if ($row->status_absen == 'TIDAK HADIR KERJA') {
                                return '<span class="badge w-100 light badge-danger">TIDAK HADIR KERJA</span>';
                            } else if ($row->status_absen == 'HADIR KERJA') {
                                return '<span class="badge w-100 light badge-success">HADIR KERJA</span>';
                            } else {
                                return $row->status_absen;
                            }
                        }
                    })
                    ->rawColumns(['tanggal_masuk', 'jam_absen', 'jam_pulang', 'keterangan'])
                    ->make(true);
            }
        }
    }
    public function HomeAbsen(Request $request)
    {
        // dd('p');
        $user_login = Auth::user()->karyawan_id;
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $date_now = date('Y');
        $month_now = date('m');
        $month_yesterday = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MM');
        $month_yesterday1 = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');
        $month_now1 = \Carbon\Carbon::now()->isoFormat('MMMM');
        date_default_timezone_set('Asia/Jakarta');
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
        $data_absen = MappingShift::where('tanggal_masuk', $tglskrg)->where('user_id', $user_karyawan->id);

        if ($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if ($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', $user_karyawan->id)->whereBetween('tanggal_masuk', [$request["mulai"], $request["akhir"]]);
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
        // dd($status_absen_skrg->status_absen);
        if ($status_absen_skrg == NULL) {
            $request->session()->flash('Mapping_shift_kosong');
            return redirect('home');
        }
        if ($status_absen_skrg->status_absen == "LIBUR") {
            $request->session()->flash('jam_kerja_libur');
            return redirect('home');
        }
        $cek_jam_maks_kerja = MappingShift::With('Shift')->where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->first();
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
                    'user_karyawan' => $user_karyawan,
                    'shift_karyawan' => $status_absen_skrg,
                    'status_absen_skrg' => $status_absen_skrg,
                    'data_absen' => $data_absen->get(),
                    'masuk' => array_map('intval', json_decode($masuk)),
                    'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
                    'telat' => array_map('intval', json_decode($telat)),
                    'date_now' => $date_now,
                    'month_now1' => $month_now1,
                    'month_yesterday1' => $month_yesterday1,
                    'face' => Karyawan::where('id', $user_login)->whereNotNull('face_id')->select('id', 'name', 'face_id')->get(),
                    'karyawan' => Karyawan::where('id', $user_login)->whereNotNull('face_id')->select('id', 'name', 'face_id')->get(),
                    'angka' => 1,
                    'absensi' => MappingShift::where('tanggal_masuk', date('Y-m-d'))->where('user_id', $user_login)->get(),
                    'jumlah_absensi' => 1,
                    'faceid' => Karyawan::where('id', $user_login)->value('face_id'),

                ]);
            } else {
                if ($time_now > $hours_1_masuk) {
                    return view('users.absen.index', [
                        'title' => 'My Absen',
                        'user_karyawan' => $user_karyawan,
                        'shift_karyawan' => $status_absen_skrg,
                        'status_absen_skrg' => $status_absen_skrg,
                        'data_absen' => $data_absen->get(),
                        'masuk' => array_map('intval', json_decode($masuk)),
                        'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
                        'telat' => array_map('intval', json_decode($telat)),
                        'date_now' => $date_now,
                        'month_now1' => $month_now1,
                        'month_yesterday1' => $month_yesterday1,

                        'face' => Karyawan::where('id', $user_login)->whereNotNull('face_id')->select('id', 'name', 'face_id')->get(),
                        'karyawan' => Karyawan::where('id', $user_login)->whereNotNull('face_id')->select('id', 'name', 'face_id')->get(),
                        'angka' => 1,
                        'absensi' => MappingShift::where('tanggal_masuk', date('Y-m-d'))->where('user_id', $user_login)->get(),
                        'jumlah_absensi' => 1,
                        'faceid' => Karyawan::where('id', $user_login)->value('face_id'),
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
                'user_karyawan' => $user_karyawan,
                'masuk' => array_map('intval', json_decode($masuk)),
                'tidak_masuk' => array_map('intval', json_decode($tidak_masuk)),
                'telat' => array_map('intval', json_decode($telat)),
                'date_now' => $date_now,
                'month_now1' => $month_now1,
                'month_yesterday1' => $month_yesterday1,

                'face' => Karyawan::where('id', $user_login)->whereNotNull('face_id')->select('id', 'name', 'face_id')->get(),
                'karyawan' => Karyawan::where('id', $user_login)->whereNotNull('face_id')->select('id', 'name', 'face_id')->get(),
                'angka' => 1,
                'absensi' => MappingShift::where('tanggal_masuk', date('Y-m-d'))->where('user_id', $user_login)->get(),
                'jumlah_absensi' => 1,
                'faceid' => Karyawan::where('id', $user_login)->value('face_id'),
            ]);
        }
    }

    public function proses_izin_datang_terlambat(Request $request)
    {
        // dd($request->all());
        $cek_duplicate = Izin::whereDate('tanggal', $request->tanggal)->where('user_id', $request->id_user)->where('izin', $request->izin)->count();
        if ($cek_duplicate > 0) {
            return redirect('/home');
        }
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $lokasi_kerja = $user_karyawan->penempatan_kerja;
        if ($lokasi_kerja == '' || $lokasi_kerja == NULL) {
            $request->session()->flash('lokasikerjanull', 'Gagal Absen Masuk');
            return redirect('/home');
        } else {
            $cek_penugasan = MappingShift::where('id', $user_karyawan->id)
                ->where('keterangan_absensi', 'ABSENSI PENUGASAN WILAYAH KANTOR')
                ->first();
            if ($cek_penugasan != '' || $cek_penugasan != NULL) {
                $request->session()->flash('penugasan_wilayah_kantor');
                return redirect('/home');
            } else {
                $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
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
                            $count_tbl_izin = Izin::where('izin', $request->izin)->where('tanggal', date('Y-m-d'))->count();
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
                            $no_form = $user_karyawan->kontrak_kerja . '/SK/FKDT/' . date('Y/m/d') . '/' . $no;
                            // dd($no_form);
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
                                'object_id' => $update->id,
                                'kategory_activity' => 'ABSENSI',
                                'activity' => 'Absen Masuk',
                                'description' => 'Absen Masuk Tanggal ' . $tanggal . ' Jam ' . $update->jam_absen . ' Keterangan ' . $update->keterangan_absensi,
                                'read_status' => 0
                            ]);
                            ActivityLog::create([
                                'user_id' => Auth::user()->id,
                                'object_id' => $update->id,
                                'kategory_activity' => 'IZIN',
                                'activity' => 'Izin Datang Terlambat',
                                'description' => 'Pengajuan Datang Terlambat Tanggal ' . $tanggal . ' Jam ' . $update->jam_absen . ', Terlmbat : ' . $data->terlambat . ' Keterangan ' . $data->keterangan_izin,
                                'read_status' => 0
                            ]);
                            $request->session()->flash('absenmasuksuccess');
                            return redirect('home');
                        }
                    } else {
                        // No form
                        $count_tbl_izin = Izin::where('izin', $request->izin)->where('tanggal', date('Y-m-d'))->count();
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
                        $no_form = $user_karyawan->kontrak_kerja . '/SK/FKDT/' . date('Y/m/d') . '/' . $no;
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
                            'object_id' => $update->id,
                            'kategory_activity' => 'ABSENSI',
                            'activity' => 'Absen Masuk',
                            'description' => 'Absen Masuk Tanggal ' . $tanggal . ' Jam ' . $update->jam_absen . ' Keterangan ' . $update->keterangan_absensi,
                            'read_status' => 0
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        return view('users.absen.locationmaps', [
            'title' => 'Maps',
            'lat' => $request->lat_location,
            'long' => $request->long_location,
            'lokasi_kantor' => Lokasi::first(),
            'user_karyawan' => $user_karyawan
        ]);
    }

    public function absenMasuk(Request $request)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        if ($request->karyawan_id != Auth::user()->karyawan_id) {
            $request->session()->flash('karyawan_tidaksesuai', 'Gagal Absen Masuk');
            return redirect('/home/absen');
        } else {
            $lokasi_kerja = $user_karyawan->penempatan_kerja;
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
                    $lokasi_kantor = Lokasi::where('lokasi_kantor', $lokasi_kerja)->first();
                    $get_dept_sourching = Departemen::where('id', $user_karyawan->dept_id)->first();
                    // dd($lokasi_kantor);
                    if ($get_dept_sourching->nama_departemen == 'PURCHASING BAHAN BAKU') {
                        $request["jarak_masuk"] = 0;
                        $request["lokasi_absen"] = NULL;
                    } else {
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
                    } else if ($jml_all > 0 && $jml_all <= 185) {
                        $telat = $jml_all;
                        $site_job = $user_karyawan->site_job;
                        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
                        $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
                        // dd($jam_kerja);
                        $kontrak = $user_karyawan->kontrak_kerja;
                        // dd($user);
                        if ($user_karyawan->kategori == 'Karyawan Bulanan') {
                            $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                                ->where('karyawans.id', $user_karyawan->id)->first();
                            // dd($user->atasan_id);
                            $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
                            // dd($IdLevelAtasan);
                            // $IdLevelAtasan1 = LevelJabatan::where('level_jabatan', '0')->first();
                            if ($lokasi_site_job->kategori_kantor == 'sps') {
                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    if ($IdLevelAtasan->atasan_id == NULL) {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        // dd($get_atasan_site);
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        if ($get_atasan_more == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                        }
                                    } else {
                                        $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                // dd($atasan);
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                        $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                // dd($get_nama_jabatan);

                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        if ($get_atasan_more == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                        }
                                    } else {
                                        $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        if ($get_atasan_more == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        }
                                    } else {
                                        $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                        ->first();
                                                    if ($atasan2 == NULL) {
                                                        $getUserAtasan  = NULL;
                                                    } else {
                                                        $getUserAtasan  = $atasan2;
                                                    }
                                                } else {
                                                    $getUserAtasan  = $atasan;
                                                }
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    if ($IdLevelAtasan->atasan_id == NULL) {
                                        // dd('p');
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
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        if ($get_atasan_more == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        }
                                    } else {
                                        $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                            ->first();
                                        // dd($atasan2);
                                        if ($atasan2 == NULL || $atasan2 == '') {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_site);
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                        ->first();
                                                    if ($atasan2 == NULL) {
                                                        $getUserAtasan  = NULL;
                                                    } else {
                                                        $getUserAtasan  = $atasan2;
                                                    }
                                                } else {
                                                    $getUserAtasan  = $atasan;
                                                }
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all sip') {
                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        if ($get_atasan_more == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                    ->first();
                                                if ($atasan2 == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $getUserAtasan  = $atasan2;
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan;
                                            }
                                        }
                                    } else {
                                        $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                            ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                            ->first();
                                        // dd($atasan2);
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                        ->first();
                                                    if ($atasan2 == NULL) {
                                                        $getUserAtasan  = NULL;
                                                    } else {
                                                        $getUserAtasan  = $atasan2;
                                                    }
                                                } else {
                                                    $getUserAtasan  = $atasan;
                                                }
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            } else if ($lokasi_site_job->kategori_kantor == 'all') {
                                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($get_nama_jabatan);
                                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    // dd($get_atasan_site);
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->where(function ($query) {
                                                $query->where('jabatans.holding', 'sp')
                                                    ->orWhere('jabatans.holding', 'sip');
                                            })
                                            ->orderBy('jabatans.holding', 'DESC')
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        // dd($get_atasan_more);
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->where(function ($query) {
                                                $query->where('jabatans.holding', 'sp')
                                                    ->orWhere('jabatans.holding', 'sps');
                                            })
                                            // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                            ->orderBy('jabatans.holding', 'ASC')
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->where(function ($query) {
                                                $query->where('jabatans.holding', 'sps')
                                                    ->orWhere('jabatans.holding', 'sip');
                                            })
                                            ->orderBy('jabatans.holding', 'DESC')
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    }
                                    // dd($get_atasan_more);
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                            ->first();
                                        // dd($atasan);
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                    }
                                } else {
                                    $getUserAtasan  = $get_nama_jabatan;
                                }
                            }
                        } else if ($user_karyawan->kategori == 'Karyawan Harian') {
                            $user = Karyawan::where('karyawans.id', $user_karyawan->id)->first();
                            $atasan = Karyawan::join('mapping_shifts', function ($join) {
                                $join->on('mapping_shifts.koordinator_id', '=', 'karyawans.id');
                            })
                                ->select('karyawans.*', 'mapping_shifts.koordinator_id')
                                ->first();
                            // dd($atasan);
                            $get_user_backup = NULL;
                            $getUserAtasan = $atasan;
                        }

                        $get_count_terlambat = CarbonInterval::minute($jml_all)->cascade();
                        // dd($jml_all, $get_count_terlambat->h);
                        return view('users.absen.form_datang_terlambat', [
                            'jam_datang' => date('H:i:s'),
                            'jumlah_terlambat' => $get_count_terlambat,
                            'getUserAtasan' => $getUserAtasan,
                            'jam_kerja' => $jam_kerja,
                            'user' => $user,
                            'user_karyawan' => $user_karyawan,
                            'telat' => $telat,
                            'foto_jam_absen' => $request["foto_jam_absen"],
                            'jarak_masuk' => $request["jarak_masuk"],
                            'lat_absen' => $request["lat_absen"],
                            'long_absen' => $request["long_absen"],
                            'lokasi_absen' => $request["lokasi_absen"],
                        ]);
                    } else if ($jml_all > 185) {
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
                            'object_id' => $update->id,
                            'kategory_activity' => 'ABSENSI',
                            'activity' => 'Absen Masuk',
                            'description' => 'Absen Masuk Tanggal ' . $tanggal . ' Jam ' . $update->jam_absen . ' Keterangan ' . $update->keterangan_absensi,
                            'read_status' => 0
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
                        'object_id' => $update->id,
                        'kategory_activity' => 'ABSENSI',
                        'activity' => 'Absen Masuk',
                        'description' => 'Absen Masuk Tanggal ' . $tanggal . ' Jam ' . $update->jam_absen . ' Keterangan ' . $update->keterangan_absensi,
                        'read_status' => 0
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        if ($request->karyawan_id != $user_karyawan->id) {
            $request->session()->flash('karyawan_tidaksesuai', 'Gagal Absen Masuk');
            return redirect()->back();
        } else if ($request->karyawan_id == NULL) {
            $request->session()->flash('karyawan_tidaksesuai', 'Gagal Absen Masuk');
            return redirect()->back();
        } else {
            // dd($request->all());
            $lokasi_kerja = $user_karyawan->penempatan_kerja;
            if ($lokasi_kerja == '' || $lokasi_kerja == NULL) {
                $request->session()->flash('lokasikerjanull', 'Gagal Absen Masuk');
                return redirect('/home');
            } else {
                date_default_timezone_set('Asia/Jakarta');
                $user_login = Auth::user()->karyawan_id;
                $lokasi_kantor = Lokasi::where('lokasi_kantor', $lokasi_kerja)->first();
                // dd($lokasi_kantor);
                $get_dept_sourching = Departemen::where('id', $user_karyawan->dept_id)->first();
                // dd($lokasi_kantor);
                if ($get_dept_sourching->nama_departemen == 'PURCHASING BAHAN BAKU') {
                    $request["jarak_pulang"] = 0;
                    $request["lokasi_absen_pulang"] = NULL;
                    // dd('souching');
                } else {
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
                    if ($request["jarak_pulang"] == NULL) {
                        $request->session()->flash('absenpulangoutradius', 'Gagal Absen Pulang');
                        return redirect('/home');
                    }
                }
                // dd($rumus);
                // dd($lokasi_absen);

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
                        $cek_tbl_izin = Izin::where('user_id', Auth::user()->karyawan_id)->where('tanggal', $tgl_skrg)->where('izin', 'Pulang Cepat')->where('status_izin', '2')->first();

                        if ($cek_tbl_izin = '' || $cek_tbl_izin == NULL) {
                            $site_job = $user_karyawan->site_job;
                            $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
                            if ($user_karyawan->kategori == 'Karyawan Bulanan') {
                                $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                                    ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                                    ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                                    ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                                    ->where('karyawans.id', $user_karyawan->id)->first();
                                // dd($user->atasan_id);
                                $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
                                if ($lokasi_site_job->kategori_kantor == 'sps') {
                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        if ($IdLevelAtasan->atasan_id == NULL) {
                                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.id', $IdLevelAtasan->id)
                                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_site);
                                            if ($get_atasan_site->holding == 'sps') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                            }
                                        } else {
                                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                    // dd($get_atasan_more);
                                                } else if ($get_atasan_site->holding == 'sip') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sps');
                                                        })
                                                        // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                        ->orderBy('jabatans.holding', 'ASC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                } else {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sps')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                }
                                                if ($get_atasan_more == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                        ->first();
                                                    // dd($atasan);
                                                    if ($atasan == NULL) {
                                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                    // dd($get_atasan_more);
                                                } else if ($get_atasan_site->holding == 'sip') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sps');
                                                        })
                                                        // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                        ->orderBy('jabatans.holding', 'ASC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                } else {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sps')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                }
                                                // dd($get_atasan_more);
                                                if ($get_atasan_more == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                        ->first();
                                                    if ($atasan == NULL) {
                                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    // dd($get_nama_jabatan);

                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                            }
                                        } else {
                                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
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
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                    // dd($get_atasan_more);
                                                } else if ($get_atasan_site->holding == 'sip') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sps');
                                                        })
                                                        // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                        ->orderBy('jabatans.holding', 'ASC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                } else {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sps')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                }
                                                // dd($get_atasan_more);
                                                if ($get_atasan_more == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                        ->first();
                                                    if ($atasan == NULL) {
                                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                        ->first();
                                                    if ($atasan2 == NULL) {
                                                        $getUserAtasan  = NULL;
                                                    } else {
                                                        $getUserAtasan  = $atasan2;
                                                    }
                                                } else {
                                                    $getUserAtasan  = $atasan;
                                                }
                                            }
                                        } else {
                                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
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
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                    // dd($get_atasan_more);
                                                } else if ($get_atasan_site->holding == 'sip') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sps');
                                                        })
                                                        // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                        ->orderBy('jabatans.holding', 'ASC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                } else {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sps')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                }
                                                // dd($get_atasan_more);
                                                if ($get_atasan_more == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                        ->first();
                                                    if ($atasan == NULL) {
                                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                            ->first();
                                                        if ($atasan2 == NULL) {
                                                            $getUserAtasan  = NULL;
                                                        } else {
                                                            $getUserAtasan  = $atasan2;
                                                        }
                                                    } else {
                                                        $getUserAtasan  = $atasan;
                                                    }
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        if ($IdLevelAtasan->atasan_id == NULL) {
                                            // dd('p');
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            // dd($get_atasan_more);
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                        ->first();
                                                    if ($atasan2 == NULL) {
                                                        $getUserAtasan  = NULL;
                                                    } else {
                                                        $getUserAtasan  = $atasan2;
                                                    }
                                                } else {
                                                    $getUserAtasan  = $atasan;
                                                }
                                            }
                                        } else {
                                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                                                ->first();
                                            // dd($atasan2);
                                            if ($atasan2 == NULL || $atasan2 == '') {
                                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_site);
                                                if ($get_atasan_site->holding == 'sps') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                    // dd($get_atasan_more);
                                                } else if ($get_atasan_site->holding == 'sip') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sps');
                                                        })
                                                        // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                        ->orderBy('jabatans.holding', 'ASC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                } else {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sps')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                }
                                                if ($get_atasan_more == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                        ->first();
                                                    if ($atasan == NULL) {
                                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                            ->first();
                                                        if ($atasan2 == NULL) {
                                                            $getUserAtasan  = NULL;
                                                        } else {
                                                            $getUserAtasan  = $atasan2;
                                                        }
                                                    } else {
                                                        $getUserAtasan  = $atasan;
                                                    }
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all sip') {
                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        if ($IdLevelAtasan->atasan_id == NULL) {
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
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                                // dd($get_atasan_more);
                                            } else if ($get_atasan_site->holding == 'sip') {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sp')
                                                            ->orWhere('jabatans.holding', 'sps');
                                                    })
                                                    // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                    ->orderBy('jabatans.holding', 'ASC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            } else {
                                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                    ->where(function ($query) {
                                                        $query->where('jabatans.holding', 'sps')
                                                            ->orWhere('jabatans.holding', 'sip');
                                                    })
                                                    ->orderBy('jabatans.holding', 'DESC')
                                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                    ->first();
                                            }
                                            if ($get_atasan_more == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                    ->first();
                                                if ($atasan == NULL) {
                                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                        ->first();
                                                    if ($atasan2 == NULL) {
                                                        $getUserAtasan  = NULL;
                                                    } else {
                                                        $getUserAtasan  = $atasan2;
                                                    }
                                                } else {
                                                    $getUserAtasan  = $atasan;
                                                }
                                            }
                                        } else {
                                            $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                                ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                ->first();
                                            // dd($atasan2);
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
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                    // dd($get_atasan_more);
                                                } else if ($get_atasan_site->holding == 'sip') {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sp')
                                                                ->orWhere('jabatans.holding', 'sps');
                                                        })
                                                        // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                        ->orderBy('jabatans.holding', 'ASC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                } else {
                                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                        ->where(function ($query) {
                                                            $query->where('jabatans.holding', 'sps')
                                                                ->orWhere('jabatans.holding', 'sip');
                                                        })
                                                        ->orderBy('jabatans.holding', 'DESC')
                                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                        ->first();
                                                }
                                                // dd($get_atasan_more);
                                                if ($get_atasan_more == NULL) {
                                                    $getUserAtasan  = NULL;
                                                } else {
                                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                        ->first();
                                                    if ($atasan == NULL) {
                                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                            ->first();
                                                        if ($atasan2 == NULL) {
                                                            $getUserAtasan  = NULL;
                                                        } else {
                                                            $getUserAtasan  = $atasan2;
                                                        }
                                                    } else {
                                                        $getUserAtasan  = $atasan;
                                                    }
                                                }
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                } else if ($lokasi_site_job->kategori_kantor == 'all') {
                                    $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                    // dd($get_nama_jabatan);
                                    if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                                        $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.id', $IdLevelAtasan->id)
                                            ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                        // dd($get_atasan_site);
                                        if ($get_atasan_site->holding == 'sps') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                            // dd($get_atasan_more);
                                        } else if ($get_atasan_site->holding == 'sip') {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sp')
                                                        ->orWhere('jabatans.holding', 'sps');
                                                })
                                                // ->whereIn('jabatans.holding', ['sp', 'sps'])
                                                ->orderBy('jabatans.holding', 'ASC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        } else {
                                            $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                                ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                                ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                                ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                                ->where(function ($query) {
                                                    $query->where('jabatans.holding', 'sps')
                                                        ->orWhere('jabatans.holding', 'sip');
                                                })
                                                ->orderBy('jabatans.holding', 'DESC')
                                                ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                                ->first();
                                        }
                                        // dd($get_atasan_more);
                                        if ($get_atasan_more == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->id)
                                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                                ->first();
                                            // dd($atasan);
                                            if ($atasan == NULL) {
                                                $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
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
                                        }
                                    } else {
                                        $getUserAtasan  = $get_nama_jabatan;
                                    }
                                }
                            } else if ($user_karyawan->kategori == 'Karyawan Harian') {
                                $user = Karyawan::where('id', $user_karyawan->id)->first();
                                $atasan = Karyawan::join('mapping_shifts', function ($join) {
                                    $join->on('mapping_shifts.koordinator_id', '=', 'karyawans.id');
                                })
                                    ->select('karyawans.*', 'mapping_shifts.koordinator_id')
                                    ->first();
                                $getUserAtasan = $atasan;
                            }
                            $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
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
                                'user_karyawan'     => $user_karyawan,
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
                        $cek_tbl_izin = Izin::where('user_id', $user_karyawan->id)->where('tanggal', $tgl_skrg)->where('izin', 'Pulang Cepat')->where('status_izin', '2')->first();
                        if ($cek_tbl_izin = '' || $cek_tbl_izin == NULL) {
                            $request["pulang_cepat"] = '00:00:00';
                            $status_absen = 'TIDAK HADIR KERJA';
                            $keterangan_absensi_pulang = 'TIDAK HADIR KERJA';
                            $kelengkapan_absensi = 'TIDAK HADIR KERJA';
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
                    'lokasi_absen_pulang' => 'nullable'
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
                $update->keterangan_absensi_pulang  = $keterangan_absensi_pulang;
                $update->kelengkapan_absensi        = $kelengkapan_absensi;
                $update->update();

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'object_id' => $request->shift_karyawan,
                    'kategory_activity' => 'ABSENSI',
                    'activity' => 'Absen Pulang',
                    'description' => 'Absen Pulang Tanggal ' . $update->tanggal_pulang . ' Jam ' . $update->jam_pulang . ' Keterangan  ' . $update->status_absen,
                    'read_status' => 0

                ]);
                $request->session()->flash('absenpulangsuccess', 'Berhasil Absen Pulang');
                return redirect('/home');
            }
        }
    }
    public function proses_izin_pulang_cepats(Request $request)
    {
        // dd($request->all());
        $cek_duplicate = Izin::whereDate('tanggal', $request->tanggal)->where('user_id', $request->id_user)->where('izin', $request->izin)->count();
        if ($cek_duplicate > 0) {
            return redirect('/home');
        }
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
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
                    $no_form = $user_karyawan->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
                    $tgl_skrg = date('Y-m-d');
                    $akhir = new DateTime($tgl_skrg . $request["jam_pulang"]);
                    $awal  = new DateTime($tgl_skrg . $request["jam_pulang_cepat"]);
                    $diff  = $awal->diff($akhir);
                    $hours = $diff->format('%H');
                    $minutes = $diff->format('%I');
                    $second = $diff->format('%S');
                    $hitung_pulang_cepat = ($hours . ':' . $minutes . ':' . $second);
                    // dd($hitung_pulang_cepat);
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

                    ActivityLog::create([
                        'user_id' => Auth::user()->id,
                        'object_id' => $update->id,
                        'kategory_activity' => 'ABSENSI',
                        'activity' => 'Absen Pulang',
                        'description' => 'Absen Pulang Tanggal ' . $update->tanggal_pulang . ' Jam ' . $update->jam_pulang . ' Keterangan  ' . $update->keterangan_absensi_pulang,
                        'read_status' => 0

                    ]);
                    ActivityLog::create([
                        'user_id' => Auth::user()->id,
                        'object_id' => $data->id,
                        'kategory_activity' => 'IZIN',
                        'activity' => 'Izin Pulang Cepat',
                        'description' => 'Pengajuan Izin Pulang Cepat Pulang Tanggal ' . $data->tanggal . ' Jam ' . $data->pulang_cepat . ' Keterangan  ' . $data->keterangan_izin,
                        'read_status' => 0

                    ]);
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
                    $no_form = $user_karyawan->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
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
                $folderPath     = public_path('signature/izin/');
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
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'object_id' => $update->id,
                    'kategory_activity' => 'ABSENSI',
                    'activity' => 'Absen Pulang',
                    'description' => 'Absen Pulang Tanggal ' . $update->tanggal_pulang . ' Jam ' . $update->jam_pulang . ' Keterangan  ' . $update->keterangan_absensi_pulang,
                    'read_status' => 0

                ]);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'object_id' => $data->id,
                    'kategory_activity' => 'IZIN',
                    'activity' => 'Izin Pulang Cepat',
                    'description' => 'Pengajuan Izin Pulang Cepat Pulang Tanggal ' . $data->tanggal . ' Jam ' . $data->pulang_cepat . ' Keterangan  ' . $data->keterangan_izin,
                    'read_status' => 0

                ]);
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
            'user' => Karyawan::select('id', 'name')->get(),
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $date_now = date('Y');
        $month_now = date('m');
        $month_yesterday = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MM');
        $month_yesterday1 = \Carbon\Carbon::now()->subMonthsNoOverflow()->isoFormat('MMMM');
        $month_now1 = \Carbon\Carbon::now()->isoFormat('MMMM');
        date_default_timezone_set('Asia/Jakarta');
        $user_login = $user_karyawan->id;
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
            'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday)),
            'user_karyawan' => $user_karyawan
        ]);
    }
}
