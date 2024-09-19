<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\Lembur;
use App\Models\ResetCuti;
use App\Models\ActivityLog;
use App\Models\Jabatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class dashboardController extends Controller
{
    public function index()
    {
        // $arr [4,2,1,6]
        // Echo dadine [2,1,-5...
        // $arr = [5, 4, 3, 2, 1];
        // dd(count($arr));

        // foreach ($arr as $a) {
        // $result = $a;
        // dd($result);
        // }
        // return view('admin.layouts.dashboard');
        $holding = request()->segment(count(request()->segments()));
        // $md5_sp = md5('sp');
        // $holding = md5($get_holding);
        // dd(request()->segment(count(request()->segments())));
        date_default_timezone_set('Asia/Jakarta');
        $tgl_skrg = date("Y-m-d");

        $logs = ActivityLog::query();
        $date_30day = Carbon::now()->addDay('30');
        $date_now = Carbon::now()->addDay('-30');
        $date_now1 = Carbon::now();
        // dd($date_30day);
        $logs = $logs->orderBy('created_at', 'desc')->limit(5)->get();
        $count_karyawan_habis_kontrak = User::with('Divisi')
            ->with('Jabatan')->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('kontrak_kerja', $holding)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->count();
        $karyawan_habis_kontrak = User::with('Divisi')
            ->with('Jabatan')
            ->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('kontrak_kerja', $holding)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->orderBy('tgl_selesai_kontrak', 'asc')
            ->take(6)
            ->get();

        // dd($count_karyawan_habis_kontrak);
        // chart karyawan departemen
        $count_karyawan_departemen = User::Join('departemens', 'departemens.id', 'users.dept_id')
            ->where('users.kontrak_kerja', $holding)
            ->where('is_admin', 'user')
            ->select(DB::raw("COUNT(*) as jumlah"), 'departemens.nama_departemen')
            ->groupBy('departemens.nama_departemen')
            ->pluck('jumlah', 'nama_departemen');
        $nama_departemen = $count_karyawan_departemen->keys();
        $jumlah_karyawan_departemen = $count_karyawan_departemen->values();

        // chart karyawan jabatan
        $count_karyawan_jabatan = User::Join('jabatans as a', 'a.id', 'users.jabatan_id')
            ->where('users.is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan = $count_karyawan_jabatan->keys();
        $jumlah_karyawan_jabatan = $count_karyawan_jabatan->values();

        // chart karyawan jabatan1
        $count_karyawan_jabatan1 = User::Join('jabatans as a', 'a.id', 'users.jabatan1_id')
            ->where('users.is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan1 = $count_karyawan_jabatan1->keys();
        $jumlah_karyawan_jabatan1 = $count_karyawan_jabatan1->values();

        // chart karyawan jabatan2
        $count_karyawan_jabatan2 = User::Join('jabatans as a', 'a.id', 'users.jabatan2_id')
            ->where('users.is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan2 = $count_karyawan_jabatan2->keys();
        $jumlah_karyawan_jabatan2 = $count_karyawan_jabatan2->values();

        // chart karyawan jabatan3
        $count_karyawan_jabatan3 = User::Join('jabatans as a', 'a.id', 'users.jabatan3_id')
            ->where('users.is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan3 = $count_karyawan_jabatan3->keys();
        $jumlah_karyawan_jabatan3 = $count_karyawan_jabatan3->values();

        // chart karyawan jabatan4
        $count_karyawan_jabatan4 = User::Join('jabatans as a', 'a.id', 'users.jabatan4_id')
            ->where('users.is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan4 = $count_karyawan_jabatan4->keys();
        $jumlah_karyawan_jabatan4 = $count_karyawan_jabatan4->values();

        // chart karyawan gender
        $count_karyawan_gender = User::where('is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'gender')
            ->groupBy('gender')
            ->pluck('jumlah', 'gender');
        $nama_gender = $count_karyawan_gender->keys();
        $jumlah_karyawan_gender = $count_karyawan_gender->values();

        // chart karyawan kontrak
        $count_karyawan_kontrak = User::where('is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'lama_kontrak_kerja')
            ->groupBy('lama_kontrak_kerja')
            ->pluck('jumlah', 'lama_kontrak_kerja');
        $nama_kontrak = $count_karyawan_kontrak->keys();
        $jumlah_karyawan_kontrak = $count_karyawan_kontrak->values();

        // chart karyawan Status Penikahan
        $count_karyawan_status = User::where('is_admin', 'user')
            ->where('users.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'status_nikah')
            ->groupBy('status_nikah')
            ->pluck('jumlah', 'status_nikah');
        $nama_status = $count_karyawan_status->keys();
        $jumlah_karyawan_status = $count_karyawan_status->values();
        // dd(json_encode($nama_jabatan));
        return view('admin.dashboard.index', [
            // 'arr' => $arr,
            'labels' => $nama_departemen,
            'data' => str_replace('"', '', $jumlah_karyawan_departemen),
            'count_karyawan_habis_kontrak' => $count_karyawan_habis_kontrak,
            'karyawan_habis_kontrak' => $karyawan_habis_kontrak,
            'date_now1' => $date_now1,
            'labels_jabatan' => $nama_jabatan,
            'data_karyawan_jabatan' => str_replace(['"', '[', ']'], '', $jumlah_karyawan_jabatan),
            'labels_jabatan1' => $nama_jabatan1,
            'data_karyawan_jabatan1' => str_replace(['"', '[', ']'], '', $jumlah_karyawan_jabatan1),
            'labels_jabatan2' => $nama_jabatan2,
            'data_karyawan_jabatan2' => str_replace(['"', '[', ']'], '', $jumlah_karyawan_jabatan2),
            'labels_jabatan3' => $nama_jabatan3,
            'data_karyawan_jabatan3' => str_replace(['"', '[', ']'], '', $jumlah_karyawan_jabatan3),
            'labels_jabatan4' => $nama_jabatan4,
            'data_karyawan_jabatan4' => str_replace(['"', '[', ']'], '', $jumlah_karyawan_jabatan4),
            'labels_gender' => $nama_gender,
            'data_karyawan_gender' => str_replace('"', '', $jumlah_karyawan_gender),
            'labels_kontrak' => $nama_kontrak,
            'data_karyawan_kontrak' => str_replace('"', '', $jumlah_karyawan_kontrak),
            'labels_status' => $nama_status,
            'data_karyawan_status' => str_replace('"', '', $jumlah_karyawan_status),
            'title' => 'Dashboard',
            "karyawan_laki" => User::where('gender', 'Laki-Laki')->where('kontrak_kerja', $holding)->count(),
            "karyawan_perempuan" => User::where('gender', 'Perempuan')->where('kontrak_kerja', $holding)->count(),
            "karyawan_office" => User::where('kategori', 'Karyawan Bulanan')->where('kontrak_kerja', $holding)->count(),
            "karyawan_shift" => User::where('kategori', 'Karyawan Harian')->where('kontrak_kerja', $holding)->count(),
            'jumlah_user' => User::where('kontrak_kerja', $holding)->where('is_admin', 'user')->whereNotNull('dept_id')->count(),
            'jumlah_masuk' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'HADIR KERJA')->count(),
            'jumlah_tidak_masuk' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'TIDAK HADIR KERJA')->count(),
            'jumlah_libur' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Libur')->count(),
            'jumlah_cuti' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Cuti')->count(),
            'jumlah_izin_telat' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Izin Telat')->count(),
            'jumlah_izin_pulang_cepat' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count(),
            'jumlah_karyawan_lembur' => Lembur::where('tanggal', $tgl_skrg)->count(),
            'logs' => $logs,
            'holding' => $holding,
        ]);
    }

    public function holding()
    {
        // dd($sp);
        $holding = request()->segment(count(request()->segments()));
        return view('admin.dashboard.holding', ['holding' => $holding]);
    }
}
