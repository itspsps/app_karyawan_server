<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AbsenUserController extends Controller
{
    public function index(Request $request)
    {
        // dd('ok');
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
        $timenow = Carbon::now()->format('H:i:s');
        $hours_1_masuk = Carbon::parse($mapping_shift->shift->jam_masuk)->subHour(1)->format('H:i:s');
        $hours_1_pulang = Carbon::parse($mapping_shift->shift->jam_keluar)->subHour(-1)->format('H:i:s');
        $get_nama_shift = $mapping_shift->shift->nama_shift;
        // dd($hours_1_pulang);
        if ($get_nama_shift == 'Malam') {
            if ($hours_1_pulang > $timenow) {
                // dd('1');
                // dd('oke');
                $getshift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->first();
                $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglkmrn)->get();
            } else {
                // dd('2');
                $getshift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->first();
                $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->get();
            }
        } else {
            $getshift = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->first();
            $status_absen_skrg = MappingShift::where('user_id', $user_login)->where('tanggal_masuk', $tglskrg)->get();
            // dd($status_absen_skrg);
        }
        $cek_jam_maks_kerja = MappingShift::where('user_id', Auth::user()->id)->where('tanggal_masuk', $tglskrg)->first();
        $time_now = date('H:i');
        $date1          = new DateTime($cek_jam_maks_kerja->tanggal_masuk . $cek_jam_maks_kerja->jam_absen);
        $date2          = new DateTime($cek_jam_maks_kerja->tanggal_masuk . $time_now);
        $interval       = $date1->diff($date2);
        if ($getshift->jam_absen == '' || $getshift->jam_absen == NULL) {
            if ($timenow >= $hours_1_masuk) {
                // dd($timenow);
                if ($interval->h <= 6) {
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
                    'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday))
                ]);
            } else {
                Alert::error('Gagal', 'Anda Belum Masuk Jam Absensi');
                return redirect()->back()->with('Gagal', 'Anda Belum Masuk Jam Absensi');
            }
        } else if ($getshift->jam_absen != '' || $getshift->jam_absen != NULL) {
            // dd('OK1');
            if ($interval->h <= 6) {
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
                'lembur_yesterday' => array_map('intval', json_decode($lembur_yesterday))
            ]);
        }
    }

    public function recordabsen(Request $request)
    {
        $thnskrg = date('Y');
        return view('users.absen.data_absensi', [
            'thnskrg' => $thnskrg
        ]);
    }
    public function get_table_absensi(Request $request)
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
                    $data = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $request->filter_month)->orderBy('tanggal_masuk', 'DESC')->get();
                }
                return DataTables::of($data)->addIndexColumn()
                    ->addColumn('tanggal_masuk', function ($row) {
                        $result = Carbon::parse($row->tanggal)->isoFormat('D-MM-Y');
                        return $result;
                    })
                    ->addColumn('jam_absen', function ($row) {
                        if ($row->jam_absen == NULL) {
                            return $row->jam_absen;
                        } else {
                            $result = Carbon::parse($row->jam_absen)->format('H:m');
                            return $result;
                        }
                    })
                    ->addColumn('jam_pulang', function ($row) {
                        if ($row->jam_pulang == NULL) {
                            return $row->jam_pulang;
                        } else {
                            $result = Carbon::parse($row->jam_pulang)->format('H:m');
                            return $result;
                        }
                    })
                    ->rawColumns(['tanggal_masuk', 'jam_absen', 'jam_pulang'])
                    ->make(true);
            } else {
                $data = MappingShift::where('user_id', $user_login)->whereMonth('tanggal_masuk', $blnskrg)->whereBetween('tanggal_masuk', array($dateweek, $datenow))->orderBy('tanggal_masuk', 'DESC')->get();
                return DataTables::of($data)->addIndexColumn()
                    ->addColumn('tanggal_masuk', function ($row) {
                        $result = Carbon::parse($row->tanggal)->isoFormat('D-MM-Y');
                        return $result;
                    })
                    ->addColumn('jam_absen', function ($row) {
                        if ($row->jam_absen == NULL) {
                            return $row->jam_absen;
                        } else {
                            $result = Carbon::parse($row->jam_absen)->format('H:m');
                            return $result;
                        }
                    })
                    ->addColumn('jam_pulang', function ($row) {
                        if ($row->jam_pulang == NULL) {
                            return $row->jam_pulang;
                        } else {
                            $result = Carbon::parse($row->jam_pulang)->format('H:m');
                            return $result;
                        }
                    })
                    ->rawColumns(['tanggal_masuk', 'jam_absen', 'jam_pulang'])
                    ->make(true);
            }
        }
    }
}
