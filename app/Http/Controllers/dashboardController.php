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
use App\Models\Karyawan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class dashboardController extends Controller
{
    public function index()
    {
        // dd('ok');
        // dd(Auth::user());
        $holding = request()->segment(count(request()->segments()));
        date_default_timezone_set('Asia/Jakarta');
        $tgl_skrg = date("Y-m-d");

        $logs = ActivityLog::query();
        $date_30day = Carbon::now()->addDay('30');
        $date_now = Carbon::now()->addDay('-30');
        $date_now1 = Carbon::now();
        // dd($date_30day);
        $logs = $logs->orderBy('created_at', 'desc')->limit(5)->get();
        $count_karyawan_habis_kontrak = Karyawan::with('Divisi')
            ->with('Jabatan')
            ->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('kontrak_kerja', $holding)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->count();
        $karyawan_habis_kontrak = Karyawan::with('Divisi')
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
        $count_karyawan_departemen = Karyawan::Join('departemens', 'departemens.id', 'karyawans.dept_id')
            ->where('karyawans.kontrak_kerja', $holding)
            ->where('status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'departemens.nama_departemen')
            ->groupBy('departemens.nama_departemen')
            ->pluck('jumlah', 'nama_departemen');
        $nama_departemen = $count_karyawan_departemen->keys();
        $jumlah_karyawan_departemen = $count_karyawan_departemen->values();
        // dd($jumlah_karyawan_departemen);

        // chart karyawan jabatan
        $count_karyawan_jabatan = Karyawan::Join('jabatans as a', 'a.id', 'karyawans.jabatan_id')
            ->where('status_aktif', 'AKTIF')
            ->where('karyawans.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan = $count_karyawan_jabatan->keys();
        $jumlah_karyawan_jabatan = $count_karyawan_jabatan->values();

        // chart karyawan jabatan1
        $count_karyawan_jabatan1 = Karyawan::Join('jabatans as a', 'a.id', 'karyawans.jabatan1_id')
            ->Join('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('status_aktif', 'AKTIF')
            ->where('karyawans.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan1 = $count_karyawan_jabatan1->keys();
        $jumlah_karyawan_jabatan1 = $count_karyawan_jabatan1->values();

        // chart karyawan jabatan2
        $count_karyawan_jabatan2 = Karyawan::Join('jabatans as a', 'a.id', 'karyawans.jabatan2_id')
            ->Join('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('status_aktif', 'AKTIF')
            ->where('karyawans.kontrak_kerja', $holding)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan2 = $count_karyawan_jabatan2->keys();
        $jumlah_karyawan_jabatan2 = $count_karyawan_jabatan2->values();

        // chart karyawan jabatan3
        $count_karyawan_jabatan3 = Karyawan::Join('jabatans as a', 'a.id', 'karyawans.jabatan3_id')
            ->Join('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('karyawans.kontrak_kerja', $holding)
            ->where('status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan3 = $count_karyawan_jabatan3->keys();
        $jumlah_karyawan_jabatan3 = $count_karyawan_jabatan3->values();

        // chart karyawan jabatan4
        $count_karyawan_jabatan4 = Karyawan::Join('jabatans as a', 'a.id', 'karyawans.jabatan4_id')
            ->Join('users as b', 'b.karyawan_id', 'karyawans.id')
            ->where('b.is_admin', 'user')
            ->where('karyawans.kontrak_kerja', $holding)
            ->where('status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan4 = $count_karyawan_jabatan4->keys();
        $jumlah_karyawan_jabatan4 = $count_karyawan_jabatan4->values();

        // chart karyawan gender
        $count_karyawan_gender = Karyawan::where('karyawans.kontrak_kerja', $holding)
            ->where('karyawans.status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'gender')
            ->groupBy('karyawans.gender')
            ->pluck('jumlah', 'gender');
        $nama_gender = $count_karyawan_gender->keys();
        $jumlah_karyawan_gender = $count_karyawan_gender->values();

        // chart karyawan kontrak
        $count_karyawan_kontrak = Karyawan::where('karyawans.kontrak_kerja', $holding)
            ->where('karyawans.status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'lama_kontrak_kerja')
            ->groupBy('karyawans.lama_kontrak_kerja')
            ->pluck('jumlah', 'lama_kontrak_kerja');
        $nama_kontrak = $count_karyawan_kontrak->keys();
        $jumlah_karyawan_kontrak = $count_karyawan_kontrak->values();

        // chart karyawan Status Penikahan
        $count_karyawan_status = Karyawan::where('karyawans.kontrak_kerja', $holding)
            ->where('status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'status_nikah')
            ->groupBy('status_nikah')
            ->pluck('jumlah', 'status_nikah');
        $nama_status = $count_karyawan_status->keys();
        $jumlah_karyawan_status = $count_karyawan_status->values();
        // dd(json_encode($nama_jabatan));
        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);

        foreach ($period as $date) {
            $label_absensi[] = $date->format('d/m/Y');
            $data_absensi_masuk[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))->whereIn('mapping_shifts.keterangan_absensi', ['TELAT HADIR', 'TEPAT WAKTU'])->where('mapping_shifts.status_absen', 'HADIR KERJA')->where('karyawans.kontrak_kerja', $holding)->count();
            $data_absensi_pulang[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))->whereIn('mapping_shifts.keterangan_absensi_pulang', ['PULANG CEPAT', 'TEPAT WAKTU'])->where('mapping_shifts.status_absen', 'HADIR KERJA')->where('karyawans.kontrak_kerja', $holding)->count();
        }
        // dd(json_encode($label_absensi));
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
            'label_absensi' => json_encode($label_absensi),
            'data_absensi_masuk' => json_encode($data_absensi_masuk),
            'data_absensi_pulang' => json_encode($data_absensi_pulang),
            "karyawan_laki" => Karyawan::where('gender', 'Laki-Laki')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $holding)->count(),
            "karyawan_perempuan" => Karyawan::where('gender', 'Perempuan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $holding)->count(),
            "karyawan_office" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $holding)->count(),
            "karyawan_shift" => Karyawan::where('kategori', 'Karyawan Harian')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $holding)->count(),
            'jumlah_user' => Karyawan::Join('users as b', 'b.karyawan_id', 'karyawans.id')->where('kontrak_kerja', $holding)->where('status_aktif', 'AKTIF')->where('b.is_admin', 'user')->whereNotNull('dept_id')->count(),
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
        // dd(Auth::user());
        $holding = request()->segment(count(request()->segments()));
        return view('admin.dashboard.holding', ['holding' => $holding]);
    }
    public function get_grafik_absensi_karyawan(Request $request)
    {
        if ($request->get_holding == NULL) {
            $get_holding = request()->segment(count(request()->segments()));
        } else {
            $get_holding = $request->get_holding;
        }
        // dd($get_holding);
        if ($get_holding == 'sp') {
            $holding = 'SP';
        } else if ($get_holding == 'sps') {
            $holding = 'SPS';
        } else if ($get_holding == 'sip') {
            $holding = 'SIP';
        }

        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        // dd($request->all());
        foreach ($period as $date) {
            $label_absensi[] = $date->format('d/m/Y');
            $data_absensi_masuk[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')->where('mapping_shifts.status_absen', 'HADIR KERJA')->where('karyawans.kontrak_kerja', $holding)->count();
            $data_absensi_pulang[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))->whereIn('mapping_shifts.keterangan_absensi_pulang', ['PULANG CEPAT', 'TEPAT WAKTU'])->where('mapping_shifts.status_absen', 'HADIR KERJA')->where('karyawans.kontrak_kerja', $holding)->count();
        }
        $data_result = ['label_absensi' => $label_absensi, 'data_absensi_masuk' => $data_absensi_masuk, 'data_absensi_pulang' => $data_absensi_pulang];
        // dd($data_result);
        return response()->json($data_result);
    }
}
