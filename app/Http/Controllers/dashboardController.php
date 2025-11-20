<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\Lembur;
use App\Models\ResetCuti;
use App\Models\ActivityLog;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Menu;
use App\Models\RoleUsers;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;

class dashboardController extends Controller
{
    public function dashboard_option($holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();
        // dd($holding, $getHolding);
        $getHoldingAll = Holding::all();
        $get_role = RoleUsers::where('role_user_id', Auth::user()->id)->pluck('role_menu_id')->toArray();
        // dd($get_role);
        if (count($get_role) == 0) {
            $roleId = null;
        } else {
            $roleId = $get_role;
        }
        if ($roleId == null) {
            $menus = collect();
        } else {
            $menus = Menu::whereIn('id', function ($query) use ($roleId) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->whereIn('role_id', $roleId);
            })
                ->whereNull('parent_id') // menu utama
                ->with('children')
                ->where('kategori', 'web')      // load submenunya
                ->orderBy('sort_order')
                ->get();
        }

        // dd($menus);
        return view('admin.dashboard.dashboard_option', [
            'title' => 'Dashboard',
            'holding' => $getHolding,
            'holdingAll' => $getHoldingAll,
            'menus' => $menus
        ]);
    }
    public function index($holding)
    {
        // dd('ok');
        // dd(Auth::user());
        // dd($holding);
        $getHolding = Holding::where('holding_code', $holding)->first();
        $getHoldingAll = Holding::all();
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
            ->where('kontrak_kerja', $getHolding->holding_category)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->count();
        $karyawan_habis_kontrak = Karyawan::with('Divisi')
            ->with('Jabatan')
            ->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('kontrak_kerja', $getHolding->holding_category)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->orderBy('tgl_selesai_kontrak', 'asc')
            ->take(6)
            ->get();

        // dd($count_karyawan_habis_kontrak);


        // dd(json_encode($nama_jabatan));
        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);

        foreach ($period as $date) {
            $label_absensi[] = $date->format('d/m/Y');
            $data_absensi_masuk[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.karyawan_id')
                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                ->whereIn('mapping_shifts.keterangan_absensi', ['TELAT HADIR', 'TEPAT WAKTU'])
                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                ->where('karyawans.kontrak_kerja', $getHolding->holding_category)->count();

            $data_absensi_pulang[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.karyawan_id')
                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                ->whereIn('mapping_shifts.keterangan_absensi_pulang', ['PULANG CEPAT', 'TEPAT WAKTU'])
                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                ->where('karyawans.kontrak_kerja', $getHolding->holding_category)->count();
        }
        // dd(json_encode($label_absensi));

        return view('admin.dashboard.index', [
            'count_karyawan_habis_kontrak' => $count_karyawan_habis_kontrak,
            'karyawan_habis_kontrak' => $karyawan_habis_kontrak,
            'date_now1' => $date_now1,
            'title' => 'Dashboard',
            'label_absensi' => json_encode($label_absensi),
            'data_absensi_masuk' => json_encode($data_absensi_masuk),
            'data_absensi_pulang' => json_encode($data_absensi_pulang),
            "karyawan_laki" => Karyawan::where('gender', 'Laki-Laki')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            "karyawan_perempuan" => Karyawan::where('gender', 'Perempuan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            "karyawan_office" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            "karyawan_shift" => Karyawan::where('kategori', 'Karyawan Harian')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            'jumlah_masuk' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'HADIR KERJA')->count(),
            'jumlah_tidak_masuk' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'TIDAK HADIR KERJA')->count(),
            'jumlah_libur' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Libur')->count(),
            'jumlah_cuti' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Cuti')->count(),
            'jumlah_izin_telat' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Izin Telat')->count(),
            'jumlah_izin_pulang_cepat' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count(),
            'jumlah_karyawan_lembur' => Lembur::where('tanggal', $tgl_skrg)->count(),
            'logs' => $logs,
            'holding' => $getHolding,
            'holdingAll' => $getHoldingAll,
        ]);
    }
    public function index_portal($holding)
    {
        // dd('ok');
        // dd(Auth::user());
        // dd($holding);
        $getHolding = Holding::where('holding_code', $holding)->first();
        $getHoldingAll = Holding::all();
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
            ->where('kontrak_kerja', $getHolding->holding_category)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->count();
        $karyawan_habis_kontrak = Karyawan::with('Divisi')
            ->with('Jabatan')
            ->where('status_aktif', 'AKTIF')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('kontrak_kerja', $getHolding->holding_category)
            ->whereBetween('tgl_selesai_kontrak', [$date_now, $date_30day])
            ->orderBy('tgl_selesai_kontrak', 'asc')
            ->take(6)
            ->get();

        // dd($count_karyawan_habis_kontrak);


        // dd(json_encode($nama_jabatan));
        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);

        foreach ($period as $date) {
            $label_absensi[] = $date->format('d/m/Y');
            $data_absensi_masuk[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.karyawan_id')
                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                ->whereIn('mapping_shifts.keterangan_absensi', ['TELAT HADIR', 'TEPAT WAKTU'])
                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                ->where('karyawans.kontrak_kerja', $getHolding->holding_category)->count();

            $data_absensi_pulang[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.karyawan_id')
                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                ->whereIn('mapping_shifts.keterangan_absensi_pulang', ['PULANG CEPAT', 'TEPAT WAKTU'])
                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                ->where('karyawans.kontrak_kerja', $getHolding->holding_category)->count();
        }
        // dd(json_encode($label_absensi));

        return view('admin.dashboard.index_portal', [
            'count_karyawan_habis_kontrak' => $count_karyawan_habis_kontrak,
            'karyawan_habis_kontrak' => $karyawan_habis_kontrak,
            'date_now1' => $date_now1,
            'title' => 'Dashboard',
            'label_absensi' => json_encode($label_absensi),
            'data_absensi_masuk' => json_encode($data_absensi_masuk),
            'data_absensi_pulang' => json_encode($data_absensi_pulang),
            "karyawan_laki" => Karyawan::where('gender', 'Laki-Laki')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            "karyawan_perempuan" => Karyawan::where('gender', 'Perempuan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            "karyawan_office" => Karyawan::where('kategori', 'Karyawan Bulanan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            "karyawan_shift" => Karyawan::where('kategori', 'Karyawan Harian')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->holding_category)->count(),
            'jumlah_masuk' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'HADIR KERJA')->count(),
            'jumlah_tidak_masuk' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'TIDAK HADIR KERJA')->count(),
            'jumlah_libur' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Libur')->count(),
            'jumlah_cuti' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Cuti')->count(),
            'jumlah_izin_telat' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Izin Telat')->count(),
            'jumlah_izin_pulang_cepat' => MappingShift::where('tanggal_masuk', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count(),
            'jumlah_karyawan_lembur' => Lembur::where('tanggal', $tgl_skrg)->count(),
            'logs' => $logs,
            'holding' => $getHolding,
            'holdingAll' => $getHoldingAll,
        ]);
    }

    public function graph_Dashboard_All($holding)
    {
        $getHolding = Holding::where('holding_code', $holding)->first();
        // chart karyawan departemen
        $count_karyawan_departemen = Karyawan::Join('departemens', 'departemens.id', 'karyawans.dept_id')
            ->where('karyawans.kontrak_kerja', $getHolding->id)
            ->where('status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'departemens.nama_departemen')
            ->groupBy('departemens.nama_departemen')
            ->pluck('jumlah', 'nama_departemen');
        $nama_departemen = $count_karyawan_departemen->keys();
        $jumlah_karyawan_departemen = $count_karyawan_departemen->values();
        // dd($nama_departemen, $jumlah_karyawan_departemen);

        // chart karyawan jabatan
        $count_karyawan_jabatan = Karyawan::Join('jabatans as a', 'a.id', 'karyawans.jabatan_id')
            ->where('status_aktif', 'AKTIF')
            ->where('karyawans.kontrak_kerja', $getHolding->id)
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
            ->where('karyawans.kontrak_kerja', $getHolding->id)
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
            ->where('karyawans.kontrak_kerja', $getHolding->id)
            ->select(DB::raw("COUNT(*) as jumlah"), 'a.nama_jabatan as nama_jabatan')
            ->groupBy('a.nama_jabatan')
            ->pluck('jumlah', 'nama_jabatan');
        $nama_jabatan2 = $count_karyawan_jabatan2->keys();
        $jumlah_karyawan_jabatan2 = $count_karyawan_jabatan2->values();

        // chart karyawan gender
        $count_karyawan_gender = Karyawan::where('karyawans.kontrak_kerja', $getHolding->id)
            ->where('karyawans.status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'gender')
            ->groupBy('karyawans.gender')
            ->pluck('jumlah', 'gender');
        $custom_gender_data = $count_karyawan_gender->mapWithKeys(function ($jumlah, $gender_code) {
            // Tentukan nama gender berdasarkan kode numerik
            $nama_gender = match ((int)$gender_code) {
                1 => 'LAKI-LAKI',
                2 => 'PEREMPUAN',
                default => 'Lainnya', // Jika ada kode lain, berikan fallback
            };

            // Kembalikan array dengan kunci (nama gender) dan nilai (jumlah) yang baru
            return [$nama_gender => $jumlah];
        });
        $nama_gender = $custom_gender_data->keys();
        $jumlah_karyawan_gender = $custom_gender_data->values();

        // chart karyawan kontrak
        $count_karyawan_kontrak = Karyawan::where('karyawans.kontrak_kerja', $getHolding->id)
            ->where('karyawans.status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'lama_kontrak_kerja')
            ->groupBy('karyawans.lama_kontrak_kerja')
            ->pluck('jumlah', 'lama_kontrak_kerja');
        $nama_kontrak = $count_karyawan_kontrak->keys();
        $jumlah_karyawan_kontrak = $count_karyawan_kontrak->values();

        // chart karyawan Status Penikahan
        $count_karyawan_status = Karyawan::where('karyawans.kontrak_kerja', $getHolding->id)
            ->where('status_aktif', 'AKTIF')
            ->select(DB::raw("COUNT(*) as jumlah"), 'status_nikah')
            ->groupBy('status_nikah')
            ->pluck('jumlah', 'status_nikah');
        $custom_status_data = $count_karyawan_status->mapWithKeys(function ($jumlah, $status_nikah) {
            // Tentukan nama gender berdasarkan kode numerik
            $nama_status = match ((int)$status_nikah) {
                1 => 'Belum Kawin',
                2 => 'Sudah Kawin',
                3 => 'Cerai Hidup',
                4 => 'Cerai Mati', // Jika ada kode lain, berikan fallback
            };

            // Kembalikan array dengan kunci (nama status) dan nilai (jumlah) yang baru
            return [$nama_status => $jumlah];
        });
        $nama_status = $custom_status_data->keys();
        $jumlah_karyawan_status = $custom_status_data->values();
        $jumlah_karyawan_jabatan_all = [];

        foreach ($jumlah_karyawan_jabatan->toArray() + $jumlah_karyawan_jabatan1->toArray() + $jumlah_karyawan_jabatan2->toArray() as $key => $value) {
            $jumlah_karyawan_jabatan_all[$key] = ($jumlah_karyawan_jabatan[$key] ?? 0) + ($jumlah_karyawan_jabatan1[$key] ?? 0) + ($jumlah_karyawan_jabatan2[$key] ?? 0);
        }

        // dd($nama_jabatan->toArray(), $jumlah_karyawan_jabatan->toArray(), $jumlah_karyawan_jabatan1->toArray(), $jumlah_karyawan_jabatan_all);
        return response()->json([
            'nama_departemen' => $nama_departemen,
            'jumlah_karyawan_departemen' => $jumlah_karyawan_departemen,
            'labels_jabatan_all' => $nama_jabatan,
            'data_karyawan_jabatan_all' => $jumlah_karyawan_jabatan_all,
            'labels_gender' => $nama_gender,
            'data_karyawan_gender' => $jumlah_karyawan_gender,
            'labels_kontrak' => $nama_kontrak,
            'data_karyawan_kontrak' => $jumlah_karyawan_kontrak,
            'labels_status' => $nama_status,
            'karyawan_laki' => Karyawan::where('gender', '1')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->id)->count(),
            'karyawan_perempuan' => Karyawan::where('gender', '2')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->id)->count(),
            'karyawan_office' => Karyawan::where('kategori', 'Karyawan Bulanan')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->id)->count(),
            'karyawan_shift' => Karyawan::where('kategori', 'Karyawan Harian')->where('status_aktif', 'AKTIF')->where('kontrak_kerja', $getHolding->id)->count(),
            'data_karyawan_status' => $jumlah_karyawan_status,
            'jumlah_user' => Karyawan::Join('users as b', 'b.karyawan_id', 'karyawans.id')->where('kontrak_kerja', $getHolding->id)->where('status_aktif', 'AKTIF')->where('b.is_admin', 'user')->whereNotNull('dept_id')->count(),
        ]);
    }
    public function holding()
    {
        // dd(Auth::user());
        $holding = Holding::with(['Site' => function ($query) {
            $query->whereIn('site_status', ['SITE', 'PUSAT']);
        }])->get();
        // dd($holding);
        return view('admin.dashboard.holding', ['holding' => $holding]);
    }
    public function get_grafik_absensi_karyawan($holding)
    {

        $holding = Holding::where('holding_code', $holding)->first();

        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        // dd($request->all());
        $label_absensi = [];
        $data_absensi_masuk = [];
        $data_absensi_pulang = [];
        foreach ($period as $date) {
            $label_absensi[] = $date->format('d/m/Y');
            $data_absensi_masuk[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.karyawan_id')
                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                ->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')
                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                ->where('karyawans.kontrak_kerja', $holding)->count();
            $data_absensi_pulang[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.karyawan_id')
                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                ->whereIn('mapping_shifts.keterangan_absensi_pulang', ['PULANG CEPAT', 'TEPAT WAKTU'])
                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                ->where('karyawans.kontrak_kerja', $holding)
                ->count();
        }
        $data_result = [
            'label_absensi' => $label_absensi,
            'data_absensi_masuk' => $data_absensi_masuk,
            'data_absensi_pulang' => $data_absensi_pulang
        ];
        // dd($data_result);
        return response()->json($data_result);
    }
}
