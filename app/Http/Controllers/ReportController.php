<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\FingerUser;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\MappingShift;
use App\Models\Shift;
use App\Models\SolutionUser;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ParagonIE\Sodium\Core\Curve25519\H;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(Request $request, $holding)
    {
        // dd($request->all());

        $holding = Holding::where('holding_code', $holding)->first();
        $departemen = Departemen::where('holding', $holding)->orderBy('nama_departemen', 'ASC')->get();
        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
            $data_columns_header[] = '<th>' . $date->format('d/m/Y') . '</th>';
        }
        $count_period = count($period);
        // dd($period->toArray());
        $data_columns1 = str_replace(['[', ']'], '', json_encode($data_columns));
        $data_columns2 = str_replace(['"'], "'", $data_columns1);
        $data_columns3 = str_replace(["'data'"], 'data', $data_columns2);
        $data_columns4 = str_replace(["'name'"], 'name', $data_columns3);
        $datacolumn = str_replace(["'searchable'"], 'searchable', $data_columns4);

        // $header1 = str_replace(['["', '"]','","'], '', json_encode($header));
        // $data_columns_header = str_replace(['\/'], "/", $header1);

        // $datacolumn = [];
        // dd($datacolumn);
        return view('admin.report.index', [
            'holding' => $holding,
            // 'data_finger' => $data_finger,
            'departemen' => $departemen,
            'period' => $period,
            'start_date' => $start_date,
            'datacolumn' => $datacolumn,
            'end_date' => $end_date,
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
        ]);
    }
    public function index_kedisiplinan(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        if ($holding == '' || $holding == null) {
            Alert::error('Error', 'Get Holding Error', 5000);
            return redirect()->route('dashboard_holding')->with('Error', 'Get Holding Error', 5000);
        }
        // dd($holding);
        date_default_timezone_set('Asia/Jakarta');

        // $bulan = date('m');
        // $tahun = date('Y');
        // $hari_per_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
        $tanggal_mulai = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $title = "Rekap Data Absensi Tanggal " . date('Y-m-01') . " s/d " . date('Y-m-d');

        $user = Karyawan::with('Cuti')->with('Izin')->where('status_aktif', 'AKTIF')->get();
        // dd($user->Cuti->nama_cuti);
        // dd($user);

        if ($request["mulai"] && $request["akhir"]) {
            $tanggal_mulai = $request["mulai"];
            $tanggal_akhir = $request["akhir"];
            $title = "Rekap Data Absensi Tanggal " . $tanggal_mulai . " s/d " . $tanggal_akhir;
        }
        $departemen = Departemen::where('holding', $holding->id)->orderBy('nama_departemen', 'ASC')->get();
        // dd($holding->id);
        // dd(Carbon::createFromFormat('H:i:s', '17:12:00'));
        return view('admin.report.index_kedisiplinan', [
            'title' => $title,
            'data_user' => $user,
            'tanggal_mulai' => $tanggal_mulai,
            'departemen' => $departemen,
            'holding' => $holding,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }
    public function index_kedisiplinan1(Request $request, $holding)
    {
        $holding = Holding::where('holding_code', $holding)->first();
        if ($holding == '' || $holding == null) {
            Alert::error('Error', 'Get Holding Error', 5000);
            return redirect()->route('dashboard_holding')->with('Error', 'Get Holding Error', 5000);
        }
        // dd($holding);
        date_default_timezone_set('Asia/Jakarta');

        // $bulan = date('m');
        // $tahun = date('Y');
        // $hari_per_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
        $tanggal_mulai = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $title = "Rekap Data Absensi Tanggal " . date('Y-m-01') . " s/d " . date('Y-m-d');

        $user = Karyawan::with('Cuti')->with('Izin')->where('status_aktif', 'AKTIF')->get();
        // dd($user->Cuti->nama_cuti);
        // dd($user);

        if ($request["mulai"] && $request["akhir"]) {
            $tanggal_mulai = $request["mulai"];
            $tanggal_akhir = $request["akhir"];
            $title = "Rekap Data Absensi Tanggal " . $tanggal_mulai . " s/d " . $tanggal_akhir;
        }
        $departemen = Departemen::where('holding', $holding->id)->orderBy('nama_departemen', 'ASC')->get();
        // dd($holding->id);
        // dd(Carbon::createFromFormat('H:i:s', '17:12:00'));
        return view('admin.report.index_kedisiplinan1', [
            'title' => $title,
            'data_user' => $user,
            'tanggal_mulai' => $tanggal_mulai,
            'departemen' => $departemen,
            'holding' => $holding,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }
    public function get_columns(Request $request)
    {
        // dd($request->all());
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => Carbon::parse($date)->isoFormat('dddd, DD/MM/YYYY')];
            $data_columns[] = [
                'data' => 'tanggal_' . $date->format('dmY'),
                'name' => 'tanggal_' . $date->format('dmY')
            ];
        }
        $count_period = count($period);

        return array(
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        );
    }
    public function get_columns_kedisiplinan(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => Carbon::parse($date)->isoFormat('dddd, D/M/YYYY')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        return array(
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        );
    }
    public function get_filter_month(Request $request)
    {
        // dd($request->filter_month);
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => Carbon::parse($date)->isoFormat('dddd, D/M/YYYY')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);
        return array('data_columns_header' => $data_columns_header, 'count_period' => $count_period, 'datacolumn' => $data_columns, 'filter_month' => $request->filter_month);
    }
    public function datatable_kedisiplinan(Request $request, $holding)
    {
        date_default_timezone_set('Asia/Jakarta');
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($request->all());
        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        $period = CarbonPeriod::create($now, $now1);
        if (request()->ajax()) {
            $query = Karyawan::with(['Departemen' => function ($q) {
                $q->select('id', 'nama_departemen');
            }])
                ->with(['Absensi' => function ($q) use ($now, $now1) {
                    $q->whereBetween('LogTime', [$now, $now1])
                        ->select('EnrollNumber', 'LogTime'); // supaya jelas field yg dibawa
                }])
                ->with(['MappingShift' => function ($q) use ($now, $now1) {
                    $q->with('Shift');
                    $q->whereBetween('tanggal_masuk', [$now, $now1]);
                }])->where('kontrak_kerja', $holding->id)
                // ->where('nomor_identitas_karyawan', '=', '2002305050895')
                ->where('kategori', 'Karyawan Bulanan')
                ->where('status_aktif', 'AKTIF');

            if (!empty($request->departemen_filter)) {
                $query->whereIn('dept_id', (array)$request->departemen_filter ?? []);
            }

            if (!empty($request->divisi_filter)) {
                $query->whereIn('divisi_id', (array)$request->divisi_filter ?? []);
            }

            if (!empty($request->bagian_filter)) {
                $query->whereIn('bagian_id', (array)$request->bagian_filter ?? []);
            }

            if (!empty($request->jabatan_filter)) {
                $query->whereIn('jabatan_id', (array)$request->jabatan_filter ?? []);
            }
            if (!empty($request->shift_filter)) {
                $query->where('shift', $request->shift_filter);
            }
            $table = $query->select('karyawans.dept_id', 'karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan', 'karyawans.shift')
                ->orderBy('karyawans.name', 'ASC')
                // ->limit(10)
                ->get();
            // dd($table);
            $non_shift = Shift::where('nama_shift', 'NON SHIFT')->first();
            $column = DataTables::of($table);
            foreach ($period as $date) {
                $colName = 'tanggal_' . $date->format('dmY');
                $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date, $non_shift) {
                    // ambil log dari eager load Absensi
                    if ($row->shift == 'NON SHIFT') {
                        // return $date->toDateString();
                        $jam_masuk = $row->Absensi->whereBetween('LogTime', [$date->toDateString() . ' 00:00:00', $date->toDateString() . ' 23:59:59']);
                        if (!$jam_masuk) {
                            return '<span class="badge bg-danger">Belum diassign shift</span>';
                        }
                        if ($non_shift->hari_libur == Carbon::parse($date->toDateString())->dayOfWeek) {
                            return '<span class="badge bg-info">LIBUR</span>';
                        }
                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($non_shift, $date) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_min_masuk)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                            return $getcheckIn->between($start, $end);
                        });
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                            if ($checkIn > $non_shift->jam_terlambat) {
                                $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                            } else {
                                $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                            }
                        } else {
                            $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($date) {
                                $getcheckIn = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($date->format('Y-m-d'));
                                return $getcheckIn->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_masuk->isNotEmpty()) {
                                $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkIn = '';
                                } else {
                                    $checkIn =
                                        'Tidak Absen';
                                }
                            }
                        }
                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date, $non_shift) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                            return $getcheckOut->between($start, $end);
                        });
                        // return $logs_absensi_pulang;
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                            if ($checkOut < $non_shift->jam_keluar) {
                                $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                            } else {
                                $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                            }
                        } else {
                            $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date) {
                                $getcheckOut = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($date->format('Y-m-d'));
                                return $getcheckOut->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_pulang->isNotEmpty()) {
                                $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                                if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                    $checkOut = 'Tidak Absen';
                                }
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkOut = '';
                                } else {
                                    $checkOut = 'Tidak Absen';
                                }
                            }
                        }
                        $nama_shift = $row->shift;
                    } else {
                        $jam_masuk = $row->MappingShift->firstWhere('tanggal_masuk', $date->toDateString());
                        if (!$jam_masuk) {
                            return '<span class="badge bg-danger">Belum diassign shift</span>';
                        }
                        if ($jam_masuk->status_absen == 'LIBUR') {
                            return '<span class="badge bg-info">Libur</span>';
                        }


                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_min_masuk)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                            return $getcheckIn->between($start, $end);
                        });
                        // return $start . ' - ' . $end;
                        // return $logs_absensi_masuk . ' - ' . Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('Y-m-d H:i') . ' - ' . Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('Y-m-d H:i');
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                            if ($checkIn > $jam_masuk->Shift->jam_terlambat) {
                                $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                            } else {
                                $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                            }
                        } else {
                            $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                                $getcheckIn = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($jam_masuk->tanggal_masuk);
                                return $getcheckIn->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_masuk->isNotEmpty()) {
                                $checkIn  = Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('H:i');
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkIn = '';
                                } else {
                                    $checkIn =
                                        'Tidak Absen';
                                }
                            }
                        }

                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                            return $getcheckOut->between($start, $end);
                        });
                        // return $logs_absensi_pulang;
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                            if (Carbon::parse($jam_masuk->tanggal_pulang)->format('Y-m-d') > $date->format('Y-m-d')) {

                                // $checkOut = $row->Absensi;
                                if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                    $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">(' . $checkOut  . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')' . '</span>';
                                } else {
                                    $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span> (' . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')';
                                }
                            } else {
                                if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                    $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                                } else {
                                    $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                                }
                            }
                        } else {
                            $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                                $getcheckOut = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($jam_masuk->tanggal_pulang);
                                return $getcheckOut->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_pulang->isNotEmpty()) {
                                $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                                if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                    $checkOut = 'Tidak Absen';
                                }
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkOut = '';
                                } else {
                                    $checkOut = 'Tidak Absen';
                                }
                            }
                        }
                        $nama_shift = $jam_masuk->Shift->nama_shift;
                    }
                    if ($checkIn == 'Tidak Absen' && $checkOut == 'Tidak Absen') {
                        $check_all = '<span style="color:red;">' . 'Tidak Hadir' . '</span>';
                        return $check_all;
                    }
                    // return $logs_absensi_masuk . ' ' . $logs_absensi_pulang;
                    return '<span style="white-space:nowrap;">' . $checkIn . '&nbsp;-&nbsp;' . $checkOut . '</span><br><span>(Shift : ' . $nama_shift . ')</span>';
                });
                $data_tanggal[] = $colName;
            }
            $column->addColumn('btn_detail', function ($row) use ($holding) {
                $btn_detail = '<a id="btn_detail" type="button" href="' . url('rekap-data/detail', ['id' => $row->nomor_identitas_karyawan]) . '/' . $holding->holding_code . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Detail</a>';
                return $btn_detail;
            });
            $column->addColumn('departemen', function ($row) use ($now, $now1) {
                if ($row->Departemen != NULL) {
                    $departemen = $row->Departemen->nama_departemen;
                } else {
                    $departemen = '-';
                }
                return $departemen;
            });
            $column->addColumn('total_datang_lebih_awal', function ($row) use ($period, $non_shift) {
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->format('Y-m-d'))->startOfDay();
                            $end = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_min_masuk)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });
                        // return $logs;
                        // $row->total_datang_lebih_awal = 0;
                        if ($logs->isNotEmpty()) {
                            $row->total_datang_lebih_awal = ($row->total_datang_lebih_awal ?? 0) + 1;
                        }
                    }
                } else {
                    $mapping_shift = $row->MappingShift;
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                                    $logTime = Carbon::parse($log->LogTime);
                                    $start = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->subHours(4)->format('H:i:59'));
                                    $end = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->format('H:i:59'));
                                    return $logTime->between($start, $end);
                                });
                                // return $logs;
                                if ($logs->isNotEmpty()) {
                                    $row->total_datang_lebih_awal = ($row->total_datang_lebih_awal ?? 0) + 1;
                                }
                            }
                        }
                    }
                }

                return $row->total_datang_lebih_awal ?? 0;
            });
            $column->addColumn('total_overtime_pulang', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    $row->total_overtime_pulang = 0;
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $jam_pulang = Carbon::parse($data->Shift->jam_keluar)->format('H:i');
                                // return $jam_pulang;
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_pulang);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->max('LogTime'); // ambil check-in paling Akhir
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    $batas_max = Carbon::parse($data->Shift->jam_keluar)->subHours(3)->format('H:i');
                                    // return $check_in . ' - ' . $jam_pulang . ' - ' . $batas_max;
                                    if ($check_in >= $batas_max && $check_in <= $jam_pulang) {
                                        $row->pulang_cepat += 1;
                                    }
                                    // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                                }
                            }
                        }
                    }
                }
                // return [$check_in, ' ', $jam_pulang, ' ', $batas_max];
                return $row->pulang_cepat ?? 0;
            });
            $column->addColumn('total_hadir_tepat_waktu', function ($row) use ($period, $non_shift) {
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_min_masuk)->format('H:i:59'));
                            $end = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_terlambat)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });
                        // return $logs;
                        if ($logs->isNotEmpty()) {
                            $row->tepat_waktu = ($row->tepat_waktu ?? 0) + 1;
                        }
                    }
                } else {
                    $mapping_shift = $row->MappingShift;
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                                    $logTime = Carbon::parse($log->LogTime);
                                    $start = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->format('H:i:59'));
                                    $end = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_terlambat)->format('H:i:59'));
                                    return $logTime->between($start, $end);
                                });
                                // return $logs;
                                if ($logs->isNotEmpty()) {
                                    $row->tepat_waktu = ($row->tepat_waktu ?? 0) + 1;
                                }
                            }
                        }
                    }
                }

                return $row->tepat_waktu ?? 0;
            });
            $column->addColumn('total_hadir_telat_hadir', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $plus5  = Carbon::parse($data->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->format('H:i');
                        $plus15 = Carbon::parse($data->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addMinutes(10)->format('H:i');
                        $logs = $row->Absensi->filter(function ($log) use ($data) {
                            return Carbon::parse($log->LogTime)->isSameDay($data->format('Y-m-d'));
                        });
                        if ($logs->isNotEmpty()) {
                            $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                            $check_in = Carbon::parse($logtime)->format('H:i');
                            if ($check_in > $plus5 && $check_in <= $plus15) {
                                $row->telat_ringan += 1;
                            }
                        }
                    }
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $plus5  = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_terlambat)->format('H:i');
                                $plus15 = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_terlambat)->addMinutes(10)->format('H:i');
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_masuk);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    if ($check_in > $plus5 && $check_in <= $plus15) {
                                        $row->telat_ringan += 1;
                                    }
                                }
                            }
                        }
                    }
                }
                return $row->telat_ringan ?? 0;
            });
            $column->addColumn('total_hadir_telat_hadir1', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $plus15 = Carbon::parse($data->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addMinutes(10)->format('H:i');
                        $logs = $row->Absensi->filter(function ($log) use ($data) {
                            return Carbon::parse($log->LogTime)->isSameDay($data->format('Y-m-d'));
                        });
                        if ($logs->isNotEmpty()) {
                            $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                            $check_in = Carbon::parse($logtime)->format('H:i');
                            if ($check_in > $plus15) {
                                $row->telat_berat += 1;
                            }
                            // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                        }
                    }
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $plus15 = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_terlambat)->addMinutes(10)->format('H:i');
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_masuk);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    if ($check_in > $plus15) {
                                        $row->telat_berat += 1;
                                    }
                                    // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                                }
                            }
                        }
                    }
                }
                return $row->telat_berat ?? 0;
            });
            $column->addColumn('total_pulang_cepat', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $jam_pulang = Carbon::parse($non_shift->jam_keluar)->format('H:i');
                        // return $jam_pulang;
                        $logs = $row->Absensi->filter(function ($log) use ($data) {
                            return Carbon::parse($log->LogTime)->isSameDay($data->format('Y-m-d'));
                        });
                        if ($logs->isNotEmpty()) {
                            $logtime  = $logs->max('LogTime'); // ambil check-in paling Akhir
                            $check_in = Carbon::parse($logtime)->format('H:i');
                            $batas_max = Carbon::parse($non_shift->jam_keluar)->subHours(3)->format('H:i');
                            // return $check_in . ' - ' . $jam_pulang . ' - ' . $batas_max;
                            if ($check_in >= $batas_max && $check_in <= $jam_pulang) {
                                $row->pulang_cepat += 1;
                            }
                            // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                        }
                    }
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $jam_pulang = Carbon::parse($data->Shift->jam_keluar)->format('H:i');
                                // return $jam_pulang;
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_pulang);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->max('LogTime'); // ambil check-in paling Akhir
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    $batas_max = Carbon::parse($data->Shift->jam_keluar)->subHours(3)->format('H:i');
                                    // return $check_in . ' - ' . $jam_pulang . ' - ' . $batas_max;
                                    if ($check_in >= $batas_max && $check_in <= $jam_pulang) {
                                        $row->pulang_cepat += 1;
                                    }
                                    // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                                }
                            }
                        }
                    }
                }
                // return [$check_in, ' ', $jam_pulang, ' ', $batas_max];
                return $row->pulang_cepat ?? 0;
            });
            $column->addColumn('total_izin_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_izin_true = $row->MappingShift
                    ->filter(function ($shift) {
                        return $shift->status_absen === 'TIDAK HADIR KERJA'
                            && ($shift->keterangan_absensi === 'IZIN SAKIT'
                                || $shift->keterangan_absensi === 'IZIN TIDAK MASUK')
                            && $shift->keterangan_izin === 'TRUE';
                    })
                    ->count();
                $row->total_izin = $total_izin_true;
                return $total_izin_true;
            });
            $column->addColumn('total_cuti_true', function ($row) use ($now, $now1) {
                $total_cuti_true = $row->MappingShift
                    ->filter(function ($shift) {
                        return $shift->status_absen === 'TIDAK HADIR KERJA'
                            && $shift->keterangan_absensi === 'CUTI'
                            && $shift->keterangan_cuti === 'TRUE';
                    })
                    ->count();

                $row->total_cuti = $total_cuti_true;
                return $total_cuti_true;
            });
            $column->addColumn('total_dinas_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_dinas_true = $row->MappingShift
                    ->filter(function ($shift) {
                        return $shift->status_absen === 'TIDAK HADIR KERJA'
                            && $shift->keterangan_absensi === 'PENUGASAN'
                            && $shift->keterangan_dinas === 'TRUE';
                    })
                    ->count();
                $row->total_dinas = $total_dinas_true;
                return $total_dinas_true;
            });
            $column->addColumn('tidak_hadir_kerja', function ($row) use ($period, $non_shift) {
                $tidak_hadir = 0;
                $today = Carbon::today();
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        // Kalau bukan libur & ada shift
                        if (Carbon::parse($data->format('Y-m-d'))->gt($today)) {
                            continue;
                        }
                        if ($data->dayOfWeek != $non_shift->hari_libur) {

                            // Cek log absensi pada tanggal itu
                            $logs = $row->Absensi->filter(function ($log) use ($data) {
                                return Carbon::parse($log->LogTime)
                                    ->isSameDay($data->format('Y-m-d'));
                            });

                            // Kalau kosong → tidak hadir
                            if ($logs->isEmpty()) {
                                $tidak_hadir++;
                            }
                        }
                    }
                } else {
                    foreach ($row->MappingShift as $data) {
                        // Kalau bukan libur & ada shift
                        if (Carbon::parse($data->tanggal_masuk)->gt($today)) {
                            continue;
                        }
                        if ($data->status_absen != 'LIBUR' && $data->Shift) {

                            // Cek log absensi pada tanggal itu
                            $logs = $row->Absensi->filter(function ($log) use ($data) {
                                return Carbon::parse($log->LogTime)
                                    ->isSameDay($data->tanggal_masuk);
                            });

                            // Kalau kosong → tidak hadir
                            if ($logs->isEmpty()) {
                                $tidak_hadir++;
                            }
                        }
                    }
                }
                $row->tidak_hadir = $tidak_hadir;
                return $tidak_hadir;
            });
            $column->addColumn('total_libur', function ($row) use ($period) {
                $today = Carbon::today();

                // ambil hanya libur yang <= hari ini
                if ($row->shift == 'NON SHIFT') {
                    $total_libur = collect($period)
                        ->filter(function ($date) {
                            // Carbon: 0 = Minggu, 6 = Sabtu
                            return $date->dayOfWeek === 0; // Minggu
                        })
                        ->count();
                } else {
                    $total_libur = $row->MappingShift
                        ->filter(function ($shift) use ($today) {
                            return $shift->status_absen === 'LIBUR'
                                && Carbon::parse($shift->tanggal_masuk)->lte($today);
                        })
                        ->count();
                }
                $row->libur = $total_libur;
                return $total_libur;
            });
            $column->addColumn('total_hadir', function ($row) {
                $total_tepat_waktu = $row->tepat_waktu ?? 0;
                $total_telat_berat = $row->telat_berat ?? 0;
                $total_datang_lebih_awal = $row->total_datang_lebih_awal ?? 0;
                $total_telat_ringan = $row->telat_ringan ?? 0;
                $total_hadir = $total_tepat_waktu + $total_telat_berat + $total_telat_ringan + $total_datang_lebih_awal;
                if ($total_hadir == 0) {
                    $jumlah_hadir = '<h6><span class="badge bg-label-danger">0</span></h6>';
                } else {
                    $jumlah_hadir = '<h6><span class="badge bg-label-success">' . $total_hadir . '</span></h6>';
                }
                $row->total_hadir = $total_hadir;
                return $jumlah_hadir;
            });
            $column->addColumn('net_hadir_kerja', function ($row) use ($period, $non_shift) {
                // return $row->Absensi;
                $total_cuti_true = $row->total_cuti ?? 0;
                $total_izin_true = $row->total_izin ?? 0;
                $total_dinas_true = $row->total_dinas ?? 0;
                $total_hadir = $row->total_hadir ?? 0;
                $net_hadir_kerja = $total_hadir + $total_izin_true + $total_dinas_true + $total_cuti_true;
                if ($total_hadir == 0 && $total_izin_true == 0 && $total_cuti_true == 0 && $total_dinas_true == 0) {
                    $jumlah_net_hadir_kerja = '<h6><span class="badge bg-label-danger">' . $net_hadir_kerja . '</span></h6>';
                } else {
                    $jumlah_net_hadir_kerja = '<h6><span class="badge bg-label-success">' . $net_hadir_kerja . '</span></h6>';
                }
                $row->net_hadir_kerja = $net_hadir_kerja;
                return $jumlah_net_hadir_kerja;
            });
            $column->addColumn('total_semua', function ($row) use ($now, $now1) {
                $today = Carbon::now()->format('Y-m-d');
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');

                $total_libur = $row->libur ?? 0;
                $net_hadir_kerja = $row->net_hadir_kerja ?? 0;
                $total_tidak_hadir = $row->tidak_hadir ?? 0;
                $total_semua = ($net_hadir_kerja  + $total_libur  + $total_tidak_hadir);
                if ($total_semua == 0) {
                    $total_semua = '<h6><span class="badge bg-label-danger">0</span></h6>';
                } else {
                    $total_semua = '<h6><span class="badge bg-label-success">' . $total_semua . '</span></h6>';
                }
                $row->total_semua = $total_semua;
                return $total_semua;
            });
            $rawCols = array_merge([
                'total_hadir_tepat_waktu',
                'total_libur',
                'btn_detail',
                'total_hadir_telat_hadir',
                'total_hadir_telat_hadir1',
                'total_pulang_cepat',
                'total_izin_true',
                'total_cuti_true',
                'total_dinas_true',
                'tidak_hadir_kerja',
                'total_datang_lebih_awal',
                'total_overtime_pulang',
                'departemen',
                'total_hadir',
                'net_hadir_kerja',
                'total_semua'
            ], $data_tanggal);
            return $column->rawColumns($rawCols)
                ->make(true);
        }
    }
    public function datatable_kedisiplinan1(Request $request, $holding)
    {
        date_default_timezone_set('Asia/Jakarta');
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($request->all());
        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        $period = CarbonPeriod::create($now, $now1);
        if (request()->ajax()) {
            $query = Karyawan::with(['Departemen' => function ($q) {
                $q->select('id', 'nama_departemen');
            }])->where('kontrak_kerja', $holding->id)
                // ->where('nomor_identitas_karyawan', '=', '2002305050895')
                ->where('kategori', 'Karyawan Bulanan')
                ->where('status_aktif', 'AKTIF');

            if (!empty($request->departemen_filter)) {
                $query->whereIn('dept_id', (array)$request->departemen_filter ?? []);
            }

            if (!empty($request->divisi_filter)) {
                $query->whereIn('divisi_id', (array)$request->divisi_filter ?? []);
            }

            if (!empty($request->bagian_filter)) {
                $query->whereIn('bagian_id', (array)$request->bagian_filter ?? []);
            }

            if (!empty($request->jabatan_filter)) {
                $query->whereIn('jabatan_id', (array)$request->jabatan_filter ?? []);
            }
            if (!empty($request->shift_filter)) {
                $query->where('shift', $request->shift_filter);
            }
            $table = $query->select('karyawans.dept_id', 'karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan', 'karyawans.shift')
                ->orderBy('karyawans.name', 'ASC')
                ->limit(10)
                ->get();
            $karyawanIds = $table->pluck('nomor_identitas_karyawan')->unique()->toArray();
            // ambil semua absensi relevant sekali
            $absensiAll = AttendanceLog::whereIn('EnrollNumber', $karyawanIds)
                ->whereBetween('LogTime', [$now, $now1])
                ->select('EnrollNumber', 'LogTime', 'MachineIp') // pilih kolom yg perlu
                ->get();
            // group menjadi lookup: ['ENROLL_YYYYMMDD' => Collection of logs]
            $absensiByEnrollAndDate = $absensiAll->groupBy(function ($item) {
                $dt = Carbon::parse($item->LogTime);
                return $item->EnrollNumber . '_' . $dt->format('Ymd');
            });

            // 3. ambil mapping shifts
            $mappingAll = MappingShift::whereIn('nomor_identitas_karyawan', $karyawanIds)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->with('Shift')
                ->get()
                ->groupBy('nomor_identitas_karyawan');

            // 4. precompute per-employee summary (single pass)
            $summaries = [];
            foreach ($table as $row) {
                $id = $row->nomor_identitas_karyawan;
                $summaries[$id] = [
                    'tepat_waktu' => 0,
                    'telat_ringan' => 0,
                    'telat_berat' => 0,
                    'total_hadir' => 0,
                    // ...
                ];
                foreach ($period as $date) {
                    $key = $id . '_' . $date->format('Ymd');
                    $logs = $absensiByEnrollAndDate->get($key, collect());
                    if ($logs->isEmpty()) continue;

                    $min = Carbon::parse($logs->min('LogTime'))->format('H:i');
                    $max = Carbon::parse($logs->max('LogTime'))->format('H:i');

                    // bandingkan dengan jam shift (ambil dari mappingAll atau non_shift)
                    // lalu update counters di $summaries[$id]
                }
            }

            // dd($absensiAll);
            $non_shift = Shift::where('nama_shift', 'NON SHIFT')->first();
            $column = DataTables::of($table);
            $column->addColumn('total_hadir_tepat_waktu', function ($row) use ($summaries) {
                return $summaries[$row->nomor_identitas_karyawan]['tepat_waktu'] ?? 0;
            });
            // dd($column);
            foreach ($period as $date) {
                $colName = 'tanggal_' . $date->format('dmY');
                $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date, $non_shift) {
                    // ambil log dari eager load Absensi
                    if ($row->shift == 'NON SHIFT') {
                        // return $date->toDateString();
                        $jam_masuk = $row->Absensi->whereBetween('LogTime', [$date->toDateString() . ' 00:00:00', $date->toDateString() . ' 23:59:59']);
                        if (!$jam_masuk) {
                            return '<span class="badge bg-danger">Belum diassign shift</span>';
                        }
                        if ($non_shift->hari_libur == Carbon::parse($date->toDateString())->dayOfWeek) {
                            return '<span class="badge bg-info">LIBUR</span>';
                        }
                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($non_shift, $date) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_min_masuk)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                            return $getcheckIn->between($start, $end);
                        });
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                            if ($checkIn > $non_shift->jam_terlambat) {
                                $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                            } else {
                                $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                            }
                        } else {
                            $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($date) {
                                $getcheckIn = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($date->format('Y-m-d'));
                                return $getcheckIn->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_masuk->isNotEmpty()) {
                                $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkIn = '';
                                } else {
                                    $checkIn =
                                        'Tidak Absen';
                                }
                            }
                        }
                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date, $non_shift) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                            return $getcheckOut->between($start, $end);
                        });
                        // return $logs_absensi_pulang;
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                            if ($checkOut < $non_shift->jam_keluar) {
                                $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                            } else {
                                $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                            }
                        } else {
                            $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date) {
                                $getcheckOut = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($date->format('Y-m-d'));
                                return $getcheckOut->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_pulang->isNotEmpty()) {
                                $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                                if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                    $checkOut = 'Tidak Absen';
                                }
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkOut = '';
                                } else {
                                    $checkOut = 'Tidak Absen';
                                }
                            }
                        }
                        $nama_shift = $row->shift;
                    } else {
                        $jam_masuk = $row->MappingShift->firstWhere('tanggal_masuk', $date->toDateString());
                        if (!$jam_masuk) {
                            return '<span class="badge bg-danger">Belum diassign shift</span>';
                        }
                        if ($jam_masuk->status_absen == 'LIBUR') {
                            return '<span class="badge bg-info">Libur</span>';
                        }


                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_min_masuk)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                            return $getcheckIn->between($start, $end);
                        });
                        // return $start . ' - ' . $end;
                        // return $logs_absensi_masuk . ' - ' . Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('Y-m-d H:i') . ' - ' . Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('Y-m-d H:i');
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                            if ($checkIn > $jam_masuk->Shift->jam_terlambat) {
                                $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                            } else {
                                $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                            }
                        } else {
                            $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                                $getcheckIn = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($jam_masuk->tanggal_masuk);
                                return $getcheckIn->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_masuk->isNotEmpty()) {
                                $checkIn  = Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('H:i');
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkIn = '';
                                } else {
                                    $checkIn =
                                        'Tidak Absen';
                                }
                            }
                        }

                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                            $end = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                            return $getcheckOut->between($start, $end);
                        });
                        // return $logs_absensi_pulang;
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                            if (Carbon::parse($jam_masuk->tanggal_pulang)->format('Y-m-d') > $date->format('Y-m-d')) {

                                // $checkOut = $row->Absensi;
                                if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                    $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">(' . $checkOut  . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')' . '</span>';
                                } else {
                                    $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span> (' . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')';
                                }
                            } else {
                                if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                    $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                                } else {
                                    $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                                }
                            }
                        } else {
                            $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                                $getcheckOut = Carbon::parse($log->LogTime);
                                $start = Carbon::parse($jam_masuk->tanggal_pulang);
                                return $getcheckOut->toDateString() === $start->toDateString();
                            });
                            if ($logs_absensi_pulang->isNotEmpty()) {
                                $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                                if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                    $checkOut = 'Tidak Absen';
                                }
                            } else {
                                if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                    $checkOut = '';
                                } else {
                                    $checkOut = 'Tidak Absen';
                                }
                            }
                        }
                        $nama_shift = $jam_masuk->Shift->nama_shift;
                    }
                    if ($checkIn == 'Tidak Absen' && $checkOut == 'Tidak Absen') {
                        $check_all = '<span style="color:red;">' . 'Tidak Hadir' . '</span>';
                        return $check_all;
                    }
                    // return $logs_absensi_masuk . ' ' . $logs_absensi_pulang;
                    return '<span style="white-space:nowrap;">' . $checkIn . '&nbsp;-&nbsp;' . $checkOut . '</span><br><span>(Shift : ' . $nama_shift . ')</span>';
                });
                $data_tanggal[] = $colName;
            }
            $column->addColumn('btn_detail', function ($row) use ($holding) {
                $btn_detail = '<a id="btn_detail" type="button" href="' . url('rekap-data/detail', ['id' => $row->nomor_identitas_karyawan]) . '/' . $holding->holding_code . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Detail</a>';
                return $btn_detail;
            });
            $column->addColumn('departemen', function ($row) use ($now, $now1) {
                if ($row->Departemen != NULL) {
                    $departemen = $row->Departemen->nama_departemen;
                } else {
                    $departemen = '-';
                }
                return $departemen;
            });
            $column->addColumn('total_datang_lebih_awal', function ($row) use ($period, $non_shift) {
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->format('Y-m-d'))->startOfDay();
                            $end = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_min_masuk)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });
                        // return $logs;
                        // $row->total_datang_lebih_awal = 0;
                        if ($logs->isNotEmpty()) {
                            $row->total_datang_lebih_awal = ($row->total_datang_lebih_awal ?? 0) + 1;
                        }
                    }
                } else {
                    $mapping_shift = $row->MappingShift;
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                                    $logTime = Carbon::parse($log->LogTime);
                                    $start = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->subHours(4)->format('H:i:59'));
                                    $end = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->format('H:i:59'));
                                    return $logTime->between($start, $end);
                                });
                                // return $logs;
                                if ($logs->isNotEmpty()) {
                                    $row->total_datang_lebih_awal = ($row->total_datang_lebih_awal ?? 0) + 1;
                                }
                            }
                        }
                    }
                }

                return $row->total_datang_lebih_awal ?? 0;
            });
            $column->addColumn('total_overtime_pulang', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    $row->total_overtime_pulang = 0;
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $jam_pulang = Carbon::parse($data->Shift->jam_keluar)->format('H:i');
                                // return $jam_pulang;
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_pulang);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->max('LogTime'); // ambil check-in paling Akhir
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    $batas_max = Carbon::parse($data->Shift->jam_keluar)->subHours(3)->format('H:i');
                                    // return $check_in . ' - ' . $jam_pulang . ' - ' . $batas_max;
                                    if ($check_in >= $batas_max && $check_in <= $jam_pulang) {
                                        $row->pulang_cepat += 1;
                                    }
                                    // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                                }
                            }
                        }
                    }
                }
                // return [$check_in, ' ', $jam_pulang, ' ', $batas_max];
                return $row->pulang_cepat ?? 0;
            });
            $column->addColumn('total_hadir_tepat_waktu', function ($row) use ($period, $non_shift) {
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_min_masuk)->format('H:i:59'));
                            $end = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_terlambat)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });
                        // return $logs;
                        if ($logs->isNotEmpty()) {
                            $row->tepat_waktu = ($row->tepat_waktu ?? 0) + 1;
                        }
                    }
                } else {
                    $mapping_shift = $row->MappingShift;
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                                    $logTime = Carbon::parse($log->LogTime);
                                    $start = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->format('H:i:59'));
                                    $end = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_terlambat)->format('H:i:59'));
                                    return $logTime->between($start, $end);
                                });
                                // return $logs;
                                if ($logs->isNotEmpty()) {
                                    $row->tepat_waktu = ($row->tepat_waktu ?? 0) + 1;
                                }
                            }
                        }
                    }
                }

                return $row->tepat_waktu ?? 0;
            });
            $column->addColumn('total_hadir_telat_hadir', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $plus5  = Carbon::parse($data->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->format('H:i');
                        $plus15 = Carbon::parse($data->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addMinutes(10)->format('H:i');
                        $logs = $row->Absensi->filter(function ($log) use ($data) {
                            return Carbon::parse($log->LogTime)->isSameDay($data->format('Y-m-d'));
                        });
                        if ($logs->isNotEmpty()) {
                            $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                            $check_in = Carbon::parse($logtime)->format('H:i');
                            if ($check_in > $plus5 && $check_in <= $plus15) {
                                $row->telat_ringan += 1;
                            }
                        }
                    }
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $plus5  = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_terlambat)->format('H:i');
                                $plus15 = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_terlambat)->addMinutes(10)->format('H:i');
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_masuk);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    if ($check_in > $plus5 && $check_in <= $plus15) {
                                        $row->telat_ringan += 1;
                                    }
                                }
                            }
                        }
                    }
                }
                return $row->telat_ringan ?? 0;
            });
            $column->addColumn('total_hadir_telat_hadir1', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $plus15 = Carbon::parse($data->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addMinutes(10)->format('H:i');
                        $logs = $row->Absensi->filter(function ($log) use ($data) {
                            return Carbon::parse($log->LogTime)->isSameDay($data->format('Y-m-d'));
                        });
                        if ($logs->isNotEmpty()) {
                            $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                            $check_in = Carbon::parse($logtime)->format('H:i');
                            if ($check_in > $plus15) {
                                $row->telat_berat += 1;
                            }
                            // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                        }
                    }
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $plus15 = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_terlambat)->addMinutes(10)->format('H:i');
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_masuk);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    if ($check_in > $plus15) {
                                        $row->telat_berat += 1;
                                    }
                                    // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                                }
                            }
                        }
                    }
                }
                return $row->telat_berat ?? 0;
            });
            $column->addColumn('total_pulang_cepat', function ($row) use ($period, $non_shift) {
                $mapping_shift = $row->MappingShift;
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        $jam_pulang = Carbon::parse($non_shift->jam_keluar)->format('H:i');
                        // return $jam_pulang;
                        $logs = $row->Absensi->filter(function ($log) use ($data) {
                            return Carbon::parse($log->LogTime)->isSameDay($data->format('Y-m-d'));
                        });
                        if ($logs->isNotEmpty()) {
                            $logtime  = $logs->max('LogTime'); // ambil check-in paling Akhir
                            $check_in = Carbon::parse($logtime)->format('H:i');
                            $batas_max = Carbon::parse($non_shift->jam_keluar)->subHours(3)->format('H:i');
                            // return $check_in . ' - ' . $jam_pulang . ' - ' . $batas_max;
                            if ($check_in >= $batas_max && $check_in <= $jam_pulang) {
                                $row->pulang_cepat += 1;
                            }
                            // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                        }
                    }
                } else {
                    foreach ($mapping_shift as $data) {
                        if ($data->status_absen != 'LIBUR') {
                            if ($data->Shift != NULL) {
                                $jam_pulang = Carbon::parse($data->Shift->jam_keluar)->format('H:i');
                                // return $jam_pulang;
                                $logs = $row->Absensi->filter(function ($log) use ($data) {
                                    return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_pulang);
                                });
                                if ($logs->isNotEmpty()) {
                                    $logtime  = $logs->max('LogTime'); // ambil check-in paling Akhir
                                    $check_in = Carbon::parse($logtime)->format('H:i');
                                    $batas_max = Carbon::parse($data->Shift->jam_keluar)->subHours(3)->format('H:i');
                                    // return $check_in . ' - ' . $jam_pulang . ' - ' . $batas_max;
                                    if ($check_in >= $batas_max && $check_in <= $jam_pulang) {
                                        $row->pulang_cepat += 1;
                                    }
                                    // return $check_in . ' - ' . $plus5 . ' - ' . $plus15;
                                }
                            }
                        }
                    }
                }
                // return [$check_in, ' ', $jam_pulang, ' ', $batas_max];
                return $row->pulang_cepat ?? 0;
            });
            $column->addColumn('total_izin_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_izin_true = $row->MappingShift
                    ->filter(function ($shift) {
                        return $shift->status_absen === 'TIDAK HADIR KERJA'
                            && ($shift->keterangan_absensi === 'IZIN SAKIT'
                                || $shift->keterangan_absensi === 'IZIN TIDAK MASUK')
                            && $shift->keterangan_izin === 'TRUE';
                    })
                    ->count();
                $row->total_izin = $total_izin_true;
                return $total_izin_true;
            });
            $column->addColumn('total_cuti_true', function ($row) use ($now, $now1) {
                $total_cuti_true = $row->MappingShift
                    ->filter(function ($shift) {
                        return $shift->status_absen === 'TIDAK HADIR KERJA'
                            && $shift->keterangan_absensi === 'CUTI'
                            && $shift->keterangan_cuti === 'TRUE';
                    })
                    ->count();

                $row->total_cuti = $total_cuti_true;
                return $total_cuti_true;
            });
            $column->addColumn('total_dinas_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_dinas_true = $row->MappingShift
                    ->filter(function ($shift) {
                        return $shift->status_absen === 'TIDAK HADIR KERJA'
                            && $shift->keterangan_absensi === 'PENUGASAN'
                            && $shift->keterangan_dinas === 'TRUE';
                    })
                    ->count();
                $row->total_dinas = $total_dinas_true;
                return $total_dinas_true;
            });
            $column->addColumn('tidak_hadir_kerja', function ($row) use ($period, $non_shift) {
                $tidak_hadir = 0;
                $today = Carbon::today();
                if ($row->shift == 'NON SHIFT') {
                    foreach ($period as $data) {
                        // Kalau bukan libur & ada shift
                        if (Carbon::parse($data->format('Y-m-d'))->gt($today)) {
                            continue;
                        }
                        if ($data->dayOfWeek != $non_shift->hari_libur) {

                            // Cek log absensi pada tanggal itu
                            $logs = $row->Absensi->filter(function ($log) use ($data) {
                                return Carbon::parse($log->LogTime)
                                    ->isSameDay($data->format('Y-m-d'));
                            });

                            // Kalau kosong → tidak hadir
                            if ($logs->isEmpty()) {
                                $tidak_hadir++;
                            }
                        }
                    }
                } else {
                    foreach ($row->MappingShift as $data) {
                        // Kalau bukan libur & ada shift
                        if (Carbon::parse($data->tanggal_masuk)->gt($today)) {
                            continue;
                        }
                        if ($data->status_absen != 'LIBUR' && $data->Shift) {

                            // Cek log absensi pada tanggal itu
                            $logs = $row->Absensi->filter(function ($log) use ($data) {
                                return Carbon::parse($log->LogTime)
                                    ->isSameDay($data->tanggal_masuk);
                            });

                            // Kalau kosong → tidak hadir
                            if ($logs->isEmpty()) {
                                $tidak_hadir++;
                            }
                        }
                    }
                }
                $row->tidak_hadir = $tidak_hadir;
                return $tidak_hadir;
            });
            $column->addColumn('total_libur', function ($row) use ($period) {
                $today = Carbon::today();

                // ambil hanya libur yang <= hari ini
                if ($row->shift == 'NON SHIFT') {
                    $total_libur = collect($period)
                        ->filter(function ($date) {
                            // Carbon: 0 = Minggu, 6 = Sabtu
                            return $date->dayOfWeek === 0; // Minggu
                        })
                        ->count();
                } else {
                    $total_libur = $row->MappingShift
                        ->filter(function ($shift) use ($today) {
                            return $shift->status_absen === 'LIBUR'
                                && Carbon::parse($shift->tanggal_masuk)->lte($today);
                        })
                        ->count();
                }
                $row->libur = $total_libur;
                return $total_libur;
            });
            $column->addColumn('total_hadir', function ($row) {
                $total_tepat_waktu = $row->tepat_waktu ?? 0;
                $total_telat_berat = $row->telat_berat ?? 0;
                $total_datang_lebih_awal = $row->total_datang_lebih_awal ?? 0;
                $total_telat_ringan = $row->telat_ringan ?? 0;
                $total_hadir = $total_tepat_waktu + $total_telat_berat + $total_telat_ringan + $total_datang_lebih_awal;
                if ($total_hadir == 0) {
                    $jumlah_hadir = '<h6><span class="badge bg-label-danger">0</span></h6>';
                } else {
                    $jumlah_hadir = '<h6><span class="badge bg-label-success">' . $total_hadir . '</span></h6>';
                }
                $row->total_hadir = $total_hadir;
                return $jumlah_hadir;
            });
            $column->addColumn('net_hadir_kerja', function ($row) use ($period, $non_shift) {
                // return $row->Absensi;
                $total_cuti_true = $row->total_cuti ?? 0;
                $total_izin_true = $row->total_izin ?? 0;
                $total_dinas_true = $row->total_dinas ?? 0;
                $total_hadir = $row->total_hadir ?? 0;
                $net_hadir_kerja = $total_hadir + $total_izin_true + $total_dinas_true + $total_cuti_true;
                if ($total_hadir == 0 && $total_izin_true == 0 && $total_cuti_true == 0 && $total_dinas_true == 0) {
                    $jumlah_net_hadir_kerja = '<h6><span class="badge bg-label-danger">' . $net_hadir_kerja . '</span></h6>';
                } else {
                    $jumlah_net_hadir_kerja = '<h6><span class="badge bg-label-success">' . $net_hadir_kerja . '</span></h6>';
                }
                $row->net_hadir_kerja = $net_hadir_kerja;
                return $jumlah_net_hadir_kerja;
            });
            $column->addColumn('total_semua', function ($row) use ($now, $now1) {
                $today = Carbon::now()->format('Y-m-d');
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');

                $total_libur = $row->libur ?? 0;
                $net_hadir_kerja = $row->net_hadir_kerja ?? 0;
                $total_tidak_hadir = $row->tidak_hadir ?? 0;
                $total_semua = ($net_hadir_kerja  + $total_libur  + $total_tidak_hadir);
                if ($total_semua == 0) {
                    $total_semua = '<h6><span class="badge bg-label-danger">0</span></h6>';
                } else {
                    $total_semua = '<h6><span class="badge bg-label-success">' . $total_semua . '</span></h6>';
                }
                $row->total_semua = $total_semua;
                return $total_semua;
            });
            $rawCols = array_merge([
                'total_hadir_tepat_waktu',
                'total_libur',
                'btn_detail',
                'total_hadir_telat_hadir',
                'total_hadir_telat_hadir1',
                'total_pulang_cepat',
                'total_izin_true',
                'total_cuti_true',
                'total_dinas_true',
                'tidak_hadir_kerja',
                'total_datang_lebih_awal',
                'total_overtime_pulang',
                'departemen',
                'total_hadir',
                'net_hadir_kerja',
                'total_semua'
            ], $data_tanggal);
            return $column->rawColumns($rawCols)
                ->make(true);
        }
    }
    public function datatable_finger(Request $request, $holding)
    {
        // 1. Ambil semua karyawan dari MySQL
        $holding = Holding::where('holding_code', $holding)->first();
        $now = Carbon::parse($request->start_date);
        $now1 = Carbon::parse($request->end_date);
        $period = CarbonPeriod::create($now, $now1);

        $query  = Karyawan::with(['Departemen' => function ($q) {
            $q->select('id', 'nama_departemen');
        }])
            ->with(['Absensi' => function ($q) use ($now, $now1) {
                $q->whereBetween('LogTime', [$now, $now1]);
                $q->select('EnrollNumber', 'LogTime');
            }])
            ->with(['MappingShift' => function ($q) use ($now, $now1) {
                $q->whereBetween('tanggal_masuk', [$now, $now1]);
            }])
            ->where('kontrak_kerja', $holding->id)
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF');
        $table = $query->select('karyawans.dept_id', 'karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan', 'karyawans.shift')
            ->orderBy('karyawans.name', 'ASC')
            // ->limit(10)
            ->get();
        $non_shift = Shift::where('nama_shift', 'NON SHIFT')->first();
        $column = DataTables::of($table);
        // dd($period, $now, $now1);
        foreach ($period as $date) {
            $colName = 'tanggal_' . $date->format('dmY');
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date, $non_shift) {
                // ambil log dari eager load Absensi
                if ($row->shift == 'NON SHIFT') {
                    // return $date->toDateString();
                    $jam_masuk = $row->Absensi->whereBetween('LogTime', [$date->toDateString() . ' 00:00:00', $date->toDateString() . ' 23:59:59']);
                    if (!$jam_masuk) {
                        return '<span class="badge bg-danger">Belum diassign shift</span>';
                    }
                    if ($non_shift->hari_libur == Carbon::parse($date->toDateString())->dayOfWeek) {
                        return '<span class="badge bg-info">LIBUR</span>';
                    }
                    $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($non_shift, $date) {
                        $getcheckIn = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_min_masuk)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                        return $getcheckIn->between($start, $end);
                    });
                    if ($logs_absensi_masuk->isNotEmpty()) {
                        $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                        if ($checkIn > $non_shift->jam_terlambat) {
                            $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                        } else {
                            $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                        }
                    } else {
                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($date) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d'));
                            return $getcheckIn->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkIn = '';
                            } else {
                                $checkIn =
                                    'Tidak Absen';
                            }
                        }
                    }
                    $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date, $non_shift) {
                        $getcheckOut = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                        return $getcheckOut->between($start, $end);
                    });
                    // return $logs_absensi_pulang;
                    if ($logs_absensi_pulang->isNotEmpty()) {
                        $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                        if ($checkOut < $non_shift->jam_keluar) {
                            $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                        } else {
                            $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                        }
                    } else {
                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d'));
                            return $getcheckOut->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                            if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                $checkOut = 'Tidak Absen';
                            }
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkOut = '';
                            } else {
                                $checkOut = 'Tidak Absen';
                            }
                        }
                    }
                    $nama_shift = $row->shift;
                } else {
                    $jam_masuk = $row->MappingShift->firstWhere('tanggal_masuk', $date->toDateString());
                    if (!$jam_masuk) {
                        return '<span class="badge bg-danger">Belum diassign shift</span>';
                    }
                    if ($jam_masuk->status_absen == 'LIBUR') {
                        return '<span class="badge bg-info">Libur</span>';
                    }


                    $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                        $getcheckIn = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_min_masuk)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                        return $getcheckIn->between($start, $end);
                    });
                    // return $start . ' - ' . $end;
                    // return $logs_absensi_masuk . ' - ' . Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('Y-m-d H:i') . ' - ' . Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('Y-m-d H:i');
                    if ($logs_absensi_masuk->isNotEmpty()) {
                        $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                        if ($checkIn > $jam_masuk->Shift->jam_terlambat) {
                            $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                        } else {
                            $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                        }
                    } else {
                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_masuk);
                            return $getcheckIn->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('H:i');
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkIn = '';
                            } else {
                                $checkIn =
                                    'Tidak Absen';
                            }
                        }
                    }

                    $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                        $getcheckOut = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                        return $getcheckOut->between($start, $end);
                    });
                    // return $logs_absensi_pulang;
                    if ($logs_absensi_pulang->isNotEmpty()) {
                        $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                        if (Carbon::parse($jam_masuk->tanggal_pulang)->format('Y-m-d') > $date->format('Y-m-d')) {

                            // $checkOut = $row->Absensi;
                            if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">(' . $checkOut  . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')' . '</span>';
                            } else {
                                $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span> (' . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')';
                            }
                        } else {
                            if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                            } else {
                                $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                            }
                        }
                    } else {
                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_pulang);
                            return $getcheckOut->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                            if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                $checkOut = 'Tidak Absen';
                            }
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkOut = '';
                            } else {
                                $checkOut = 'Tidak Absen';
                            }
                        }
                    }
                    $nama_shift = $jam_masuk->Shift->nama_shift;
                }
                if ($checkIn == 'Tidak Absen' && $checkOut == 'Tidak Absen') {
                    $check_all = '<span style="color:red;">' . 'Tidak Hadir' . '</span>';
                    return $check_all;
                }
                // return $logs_absensi_masuk . ' ' . $logs_absensi_pulang;
                return '<span style="white-space:nowrap;">' . $checkIn . '&nbsp;-&nbsp;' . $checkOut . '</span><br><span>(Shift : ' . $nama_shift . ')</span>';
            });
            $data_tanggal[] = $colName;
        }
        $column->addColumn('action', function ($row) {
            return '<button class="btn btn-sm btn-primary" onclick="edit(' . $row->USERID . ')">Edit</button>';
        });
        $column->addColumn('departemen', function ($row) {
            if ($row->Departemen != NULL) {
                $departemen = $row->Departemen->nama_departemen;
            } else {
                $departemen = '-';
            }
            return $departemen;
        });
        $column->addColumn('shift', function ($row) {

            return $row->shift;
        });
        $column->addColumn('jumlah_hadir', function ($row) use ($period, $non_shift) {
            if ($row->shift == 'NON SHIFT') {
                if ($row->Absensi->isEmpty()) {
                    $row->jumlah_hadir = 0;
                    $jumlah_hadir = '<h6><span class="badge bg-label-danger">0</span></h6>';
                } else {
                    foreach ($period as $data) {
                        $logs = $row->Absensi->filter(function ($log) use ($data, $non_shift) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_min_masuk)->format('H:i:59'));
                            $end = Carbon::parse($data->format('Y-m-d'))->setTimeFromTimeString(Carbon::parse($non_shift->jam_terlambat)->addHours(3)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });
                        // return $logs;
                        if ($logs->isNotEmpty()) {
                            $row->jumlah_hadir = ($row->jumlah_hadir ?? 0) + 1;
                            $jumlah_hadir = '<h6><span class="badge bg-label-success">' . $row->jumlah_hadir . '</span></h6>';
                        }
                    }
                }
                return $jumlah_hadir;
            } else {
                $mapping_shift = $row->MappingShift;
                // return $mapping_shift;
                if ($mapping_shift->isEmpty()) {
                    $row->jumlah_hadir = 0;
                    $jumlah_hadir = '<h6><span class="badge bg-label-danger">0</span></h6>';
                } else {
                    $jumlah_hadir = $row->MappingShift
                        ->where('status_absen', '!=', 'LIBUR')
                        ->filter(function ($shift) use ($row) {
                            // cek apakah ada absensi di tanggal shift
                            return $row->Absensi->contains(function ($log) use ($shift) {
                                return Carbon::parse($log->LogTime)->isSameDay($shift->tanggal_masuk);
                            });
                        })
                        ->count();
                    // simpan di row biar bisa dipakai di kolom lain
                    $row->jumlah_hadir = $jumlah_hadir;
                    $row->mapping_shift = $mapping_shift;
                }
                return '<h6><span class="badge bg-label-success">' . $jumlah_hadir . '</span></h6>';
            }
        })
            ->addColumn('name', function ($row) {
                return $row->name;
            });
        $column->addColumn('total_tidak_hadir_kerja', function ($row) {
            if ($row->shift == 'NON SHIFT') {
                return '<h6><span class="badge bg-label-danger">0</span></h6>';
            } else {
                $mapping_shift  = $row->MappingShift
                    ->where('status_absen', '!=', 'LIBUR');
                if (!$mapping_shift->first()) {
                    return '<h6><span class="badge bg-label-danger">0</span></h6>';
                }

                // hitung tidak hadir
                $total_tidak_hadir_kerja = $mapping_shift->count() - ($row->jumlah_hadir ?? 0);
                $row->total_tidak_hadir_kerja = $total_tidak_hadir_kerja;
                return '<h6><span class="badge bg-label-danger">' . $total_tidak_hadir_kerja . '</span></h6>';
            }
        });

        $column->addColumn('total_cuti', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_cuti = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'CUTI')
                ->where('keterangan_absensi_pulang', 'CUTI')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'TRUE')
                        ->orWhere('keterangan_cuti', 'True')
                        ->orWhere('keterangan_cuti', 'true');
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'FALSE')
                        ->orWhere('keterangan_izin', 'false')
                        ->orWhere('keterangan_izin', NULL)
                        ->orWhere('keterangan_izin', '');
                })
                ->count();
            $row->cuti = $total_cuti;
            return $total_cuti;
        });

        $column->addColumn('total_libur', function ($row) use ($now, $now1) {

            $total_libur = $row->MappingShift
                ->where('status_absen', 'LIBUR')->count();
            $row->libur = $total_libur;
            return $total_libur;
        });
        $column->addColumn('total_semua', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_hadir = ($row->jumlah_hadir ?? 0);
            $total_libur = ($row->libur ?? 0);
            $total_cuti = ($row->cuti ?? 0);

            $total_tidak_hadir = ($row->total_tidak_hadir_kerja ?? 0);
            $total_semua = ($total_hadir + $total_cuti  + $total_libur + $total_tidak_hadir);
            return $total_semua;
        });;

        $rawCols = array_merge([
            'action',
            'total_tidak_hadir_kerja',
            'total_libur',
            'total_cuti',
            'name',
            'jumlah_hadir',
            'departemen',
            'shift'
        ], $data_tanggal);
        return $column->rawColumns($rawCols)
            ->make('true');
    }
    public function datatable(Request $request, $holding)
    {
        // dd($request->filter_month, $holding);
        $holding = Holding::where('holding_code', $holding)->first();
        // if (request()->ajax()) {

        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        $period = CarbonPeriod::create($now, $now1);

        // dd($holding);
        // dd($tgl_mulai, $tgl_selesai);
        $query = Karyawan::with(['Departemen' => function ($q) {
            $q->select('id', 'nama_departemen');
        }])
            ->with(['Absensi' => function ($q) use ($now, $now1) {
                $q->whereBetween('LogTime', [$now, $now1])
                    ->select('EnrollNumber', 'LogTime'); // supaya jelas field yg dibawa
            }])
            ->with(['MappingShift' => function ($q) use ($now, $now1) {
                $q->with('Shift');
                $q->whereBetween('tanggal_masuk', [$now, $now1]);
            }])->where('kontrak_kerja', $holding->id)
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF');
        $table = $query->select('karyawans.dept_id', 'karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan', 'karyawans.shift')
            ->orderBy('karyawans.name', 'ASC')
            // ->limit(10)
            ->get();
        // dd($table);
        $column = DataTables::of($table);
        $non_shift = Shift::where('nama_shift', 'NON SHIFT')->first();
        foreach ($period as $date) {
            $colName = 'tanggal_' . $date->format('dmY');
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date, $non_shift) {
                // ambil log dari eager load Absensi
                if ($row->shift == 'NON SHIFT') {
                    // return $date->toDateString();
                    $jam_masuk = $row->Absensi->whereBetween('LogTime', [$date->toDateString() . ' 00:00:00', $date->toDateString() . ' 23:59:59']);
                    if (!$jam_masuk) {
                        return '<span class="badge bg-danger">Belum diassign shift</span>';
                    }
                    if ($non_shift->hari_libur == Carbon::parse($date->toDateString())->dayOfWeek) {
                        return '<span class="badge bg-info">LIBUR</span>';
                    }
                    $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($non_shift, $date) {
                        $getcheckIn = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_min_masuk)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                        return $getcheckIn->between($start, $end);
                    });
                    if ($logs_absensi_masuk->isNotEmpty()) {
                        $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                        if ($checkIn > $non_shift->jam_terlambat) {
                            $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                        } else {
                            $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                        }
                    } else {
                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($date) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d'));
                            return $getcheckIn->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkIn = '';
                            } else {
                                $checkIn =
                                    'Tidak Absen';
                            }
                        }
                    }
                    $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date, $non_shift) {
                        $getcheckOut = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($date->format('Y-m-d') . ' ' . $non_shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                        return $getcheckOut->between($start, $end);
                    });
                    // return $logs_absensi_pulang;
                    if ($logs_absensi_pulang->isNotEmpty()) {
                        $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                        if ($checkOut < $non_shift->jam_keluar) {
                            $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                        } else {
                            $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                        }
                    } else {
                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($date) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($date->format('Y-m-d'));
                            return $getcheckOut->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                            if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                $checkOut = 'Tidak Absen';
                            }
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkOut = '';
                            } else {
                                $checkOut = 'Tidak Absen';
                            }
                        }
                    }
                    $nama_shift = $row->shift;
                } else {
                    $jam_masuk = $row->MappingShift->firstWhere('tanggal_masuk', $date->toDateString());
                    if (!$jam_masuk) {
                        return '<span class="badge bg-danger">Belum diassign shift</span>';
                    }
                    if ($jam_masuk->status_absen == 'LIBUR') {
                        return '<span class="badge bg-info">Libur</span>';
                    }


                    $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                        $getcheckIn = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_min_masuk)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($jam_masuk->tanggal_masuk . ' ' . $jam_masuk->Shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('Y-m-d H:i:59');
                        return $getcheckIn->between($start, $end);
                    });
                    // return $start . ' - ' . $end;
                    // return $logs_absensi_masuk . ' - ' . Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('Y-m-d H:i') . ' - ' . Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('Y-m-d H:i');
                    if ($logs_absensi_masuk->isNotEmpty()) {
                        $checkIn  = Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i');
                        if ($checkIn > $jam_masuk->Shift->jam_terlambat) {
                            $checkIn = '<span style="color:rgba(var(--bs-warning-rgb));">' . $checkIn . '</span>';
                        } else {
                            $checkIn = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkIn . '</span>';
                        }
                    } else {
                        $logs_absensi_masuk = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckIn = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_masuk);
                            return $getcheckIn->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_masuk->isNotEmpty()) {
                            $checkIn  = Carbon::parse($logs_absensi_masuk->max('LogTime'))->format('H:i');
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkIn = '';
                            } else {
                                $checkIn =
                                    'Tidak Absen';
                            }
                        }
                    }

                    $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                        $getcheckOut = Carbon::parse($log->LogTime);
                        $start = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_pulang_cepat)->format('Y-m-d H:i:59');
                        $end = Carbon::parse($jam_masuk->tanggal_pulang . ' ' . $jam_masuk->Shift->jam_keluar)->addHours(5)->format('Y-m-d H:i:59');
                        return $getcheckOut->between($start, $end);
                    });
                    // return $logs_absensi_pulang;
                    if ($logs_absensi_pulang->isNotEmpty()) {
                        $checkOut  = Carbon::parse($logs_absensi_pulang->min('LogTime'))->format('H:i');
                        if (Carbon::parse($jam_masuk->tanggal_pulang)->format('Y-m-d') > $date->format('Y-m-d')) {

                            // $checkOut = $row->Absensi;
                            if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">(' . $checkOut  . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')' . '</span>';
                            } else {
                                $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span> (' . Carbon::parse($jam_masuk->tanggal_pulang)->format('d-m-Y') . ')';
                            }
                        } else {
                            if ($checkOut < $jam_masuk->Shift->jam_keluar) {
                                $checkOut = '<span style="color:rgba(var(--bs-danger-rgb));">' . $checkOut . '</span>';
                            } else {
                                $checkOut = '<span style="color:rgba(var(--bs-success-rgb));">' . $checkOut . '</span>';
                            }
                        }
                    } else {
                        $logs_absensi_pulang = $row->Absensi->filter(function ($log) use ($jam_masuk) {
                            $getcheckOut = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($jam_masuk->tanggal_pulang);
                            return $getcheckOut->toDateString() === $start->toDateString();
                        });
                        if ($logs_absensi_pulang->isNotEmpty()) {
                            $checkOut  = Carbon::parse($logs_absensi_pulang->max('LogTime'))->format('H:i');
                            if ($checkOut == Carbon::parse($logs_absensi_masuk->min('LogTime'))->format('H:i')) {
                                $checkOut = 'Tidak Absen';
                            }
                        } else {
                            if ($date->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                                $checkOut = '';
                            } else {
                                $checkOut = 'Tidak Absen';
                            }
                        }
                    }
                    $nama_shift = $jam_masuk->Shift->nama_shift;
                }
                if ($checkIn == 'Tidak Absen' && $checkOut == 'Tidak Absen') {
                    $check_all = '<span style="color:red;">' . 'Tidak Hadir' . '</span>';
                    return $check_all;
                }
                // return $logs_absensi_masuk . ' ' . $logs_absensi_pulang;
                return '<span style="white-space:nowrap;">' . $checkIn . '&nbsp;-&nbsp;' . $checkOut . '</span><br><span>(Shift : ' . $nama_shift . ')</span>';
            });
            $data_tanggal[] = $colName;
        }
        $column->addColumn('total_hadir_kerja', function ($row) use ($now, $now1) {
            $mapping_shift = $row->MappingShift;
            foreach ($mapping_shift as $data) {
                if ($data->status_absen != 'LIBUR') {
                    if ($data->Shift != NULL) {
                        $logs_masuk = $row->Absensi->filter(function ($log) use ($data) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->format('H:i:59'));
                            $end = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });

                        // return $logs;
                        if ($logs_masuk->isNotEmpty()) {
                            $row->hadir_kerja = ($row->hadir_kerja ?? 0) + 1;
                        }
                    }
                }
            }


            return $row->hadir_kerja ?? 0;
        });
        // dd($oke);
        $column->addColumn('total_tidak_hadir_kerja', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_tidak_hadir_kerja = $row->MappingShift->where(function ($query) {
                $query->where('keterangan_dinas', 'FALSE')
                    ->orWhere('keterangan_dinas', 'false')
                    ->orWhere('keterangan_dinas', NULL)
                    ->orWhere('keterangan_dinas', '');
            })
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'FALSE')
                        ->orWhere('keterangan_cuti', 'false')
                        ->orWhere('keterangan_cuti', NULL)
                        ->orWhere('keterangan_cuti', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'FALSE')
                        ->orWhere('keterangan_izin', 'false')
                        ->orWhere('keterangan_izin', NULL)
                        ->orWhere('keterangan_izin', '');
                });
            foreach ($total_tidak_hadir_kerja as $data) {
                if ($data->status_absen != 'LIBUR') {
                    if ($data->Shift != NULL) {
                        $logs_masuk = $row->Absensi->filter(function ($log) use ($data) {
                            $logTime = Carbon::parse($log->LogTime);
                            $start = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_min_masuk)->format('H:i:59'));
                            $end = Carbon::parse($data->tanggal_masuk)->setTimeFromTimeString(Carbon::parse($data->Shift->jam_terlambat)->addHours(3)->addMinutes(10)->format('H:i:59'));
                            return $logTime->between($start, $end);
                        });

                        // return $logs;
                        if ($logs_masuk->isNotEmpty()) {
                            $row->hadir_kerja = ($row->hadir_kerja ?? 0) + 1;
                        }
                    }
                }
            }


            return $row->hadir_kerja ?? 0;
            return $total_tidak_hadir_kerja;
        });
        $column->addColumn('total_cuti', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_cuti = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'CUTI')
                ->where('keterangan_absensi_pulang', 'CUTI')
                ->where('status_absen', 'TIDAK HADIR KERJA')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'TRUE')
                        ->orWhere('keterangan_cuti', 'True')
                        ->orWhere('keterangan_cuti', 'true');
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'FALSE')
                        ->orWhere('keterangan_izin', 'false')
                        ->orWhere('keterangan_izin', NULL)
                        ->orWhere('keterangan_izin', '');
                })
                ->count();
            return $total_cuti;
        });
        $column->addColumn('total_izin_sakit', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_izin_sakit = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'IZIN SAKIT')
                ->where('keterangan_absensi_pulang', 'IZIN SAKIT')
                ->where('status_absen', 'TIDAK HADIR KERJA')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'FALSE')
                        ->orWhere('keterangan_cuti', 'false')
                        ->orWhere('keterangan_cuti', NULL);
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'TRUE')
                        ->orWhere('keterangan_izin', 'True')
                        ->orWhere('keterangan_izin', 'true');
                })
                ->count();
            return $total_izin_sakit;
        });
        $column->addColumn('total_izin_lainnya', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_izin_lainnya = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'IZIN TIDAK MASUK')
                ->where('keterangan_absensi_pulang', 'IZIN TIDAK MASUK')
                ->where('status_absen', 'TIDAK HADIR KERJA')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'FALSE')
                        ->orWhere('keterangan_cuti', 'false')
                        ->orWhere('keterangan_cuti', NULL)
                        ->orWhere('keterangan_cuti', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'TRUE')
                        ->orWhere('keterangan_izin', 'True')
                        ->orWhere('keterangan_izin', 'true');
                })
                ->count();
            return $total_izin_lainnya;
        });
        $column->addColumn('total_libur', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_libur = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('status_absen', 'LIBUR')->count();
            return $total_libur;
        });
        $column->addColumn('total_semua', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_hadir = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('status_absen', 'HADIR KERJA')->count();
            $total_libur = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('status_absen', 'LIBUR')->count();
            $total_cuti = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'CUTI')
                ->where('keterangan_absensi_pulang', 'CUTI')
                ->where('status_absen', 'TIDAK HADIR KERJA')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'TRUE')
                        ->orWhere('keterangan_cuti', 'True')
                        ->orWhere('keterangan_cuti', 'true');
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'FALSE')
                        ->orWhere('keterangan_izin', 'false')
                        ->orWhere('keterangan_izin', NULL)
                        ->orWhere('keterangan_izin', '');
                })
                ->count();
            $total_izin_sakit = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'IZIN SAKIT')
                ->where('keterangan_absensi_pulang', 'IZIN SAKIT')
                ->where('status_absen', 'TIDAK HADIR KERJA')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'FALSE')
                        ->orWhere('keterangan_cuti', 'false')
                        ->orWhere('keterangan_cuti', NULL);
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'TRUE')
                        ->orWhere('keterangan_izin', 'True')
                        ->orWhere('keterangan_izin', 'true');
                })
                ->count();
            $total_izin_lainnya = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('keterangan_absensi', 'IZIN TIDAK MASUK')
                ->where('keterangan_absensi_pulang', 'IZIN TIDAK MASUK')
                ->where('status_absen', 'TIDAK HADIR KERJA')
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'FALSE')
                        ->orWhere('keterangan_cuti', 'false')
                        ->orWhere('keterangan_cuti', NULL)
                        ->orWhere('keterangan_cuti', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'TRUE')
                        ->orWhere('keterangan_izin', 'True')
                        ->orWhere('keterangan_izin', 'true');
                })
                ->count();
            $total_tidak_hadir = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where(function ($query) {
                    $query->where('status_absen', 'TIDAK HADIR KERJA')
                        ->orWhere('status_absen', NULL);
                })
                ->where(function ($query) {
                    $query->where('keterangan_dinas', 'FALSE')
                        ->orWhere('keterangan_dinas', 'false')
                        ->orWhere('keterangan_dinas', NULL)
                        ->orWhere('keterangan_dinas', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_cuti', 'FALSE')
                        ->orWhere('keterangan_cuti', 'false')
                        ->orWhere('keterangan_cuti', NULL)
                        ->orWhere('keterangan_cuti', '');
                })
                ->where(function ($query) {
                    $query->where('keterangan_izin', 'FALSE')
                        ->orWhere('keterangan_izin', 'false')
                        ->orWhere('keterangan_izin', NULL)
                        ->orWhere('keterangan_izin', '');
                })
                ->count();
            $total_semua = ($total_hadir + $total_cuti + $total_izin_sakit + $total_izin_lainnya + $total_libur + $total_tidak_hadir);
            return $total_semua;
        });
        return $column->rawColumns(['total_hadir_kerja', 'total_tidak_hadir_kerja', 'total_libur', 'total_semua', 'total_izin_lainnya', 'total_izin_sakit', 'total_cuti'])
            ->make(true);
        // }
    }
    public function get_divisi(Request $request)
    {
        // dd($request->all());
        $id_departemen    = $request->departemen_filter ?? [];

        $divisi      = Divisi::with('Departemen')
            ->whereIn('dept_id', (array)$id_departemen)
            ->where(
                'holding',
                $request->holding
            )->orderBy('nama_divisi', 'ASC')
            ->get()
            ->sortBy(function ($item) {
                return $item->Departemen->nama_departemen . ' ' . $item->nama_divisi;
            });
        // dd($divisi);
        if ($divisi == NULL || $divisi == '' || count($divisi) == '0') {
            $select = '<option value="">Pilih Divisi...</option>';
        } else {

            $select_divisi[] = "<option value=''>Pilih Divisi...</option>";
            $currentDept = null;
            foreach ($divisi as $divisi) {
                if ($currentDept !== $divisi->Departemen->nama_departemen) {
                    // tutup optgroup sebelumnya
                    if ($currentDept !== null) {
                        $select_divisi1[] = "</optgroup>";
                    }

                    // buka optgroup baru
                    $currentDept = $divisi->Departemen->nama_departemen;
                    $select_divisi1[] = "<optgroup label='{$divisi->Departemen->nama_departemen}'>";
                }
                $select_divisi1[] = "<option value='$divisi->id'>$divisi->nama_divisi</option>";
            }
            // tutup optgroup terakhir
            if ($currentDept !== null) {
                $select_divisi1[] = "</optgroup>";
            }
            $select = array_merge($select_divisi, $select_divisi1);
        }
        // dd($select);
        return array(
            'select' => $select,
        );
    }
    public function get_bagian(Request $request)
    {
        // dd($request->all());
        $id_divisi    = $request->divisi_filter;
        // dd($end_date);


        $bagian      = Bagian::with('Divisi')->whereIn('divisi_id', $id_divisi)->where('holding', $request->holding)->orderBy('nama_bagian', 'ASC')->get();
        if ($bagian == NULL || $bagian == '' || count($bagian) == '0') {
            $select = "<option value=''>Pilih Bagian...</option>";
        } else {

            $select_bagian[] = "<option value=''>Pilih Bagian...</option>";
            $currentBagian = null;
            foreach ($bagian as $bagian) {
                if ($currentBagian !== $bagian->Divisi->nama_divisi) {
                    // tutup optgroup sebelumnya
                    if ($currentBagian !== null) {
                        $select_bagian1[] = "</optgroup>";
                    }

                    // buka optgroup baru
                    $currentBagian = $bagian->Divisi->nama_divisi;
                    $select_bagian1[] = "<optgroup label='{$bagian->Divisi->nama_divisi}'>";
                }
                $select_bagian1[] = "<option value='$bagian->id'>$bagian->nama_bagian</option>";
            }
            // tutup optgroup terakhir
            if ($currentBagian !== null) {
                $select_bagian1[] = "</optgroup>";
            }
            $select = array_merge($select_bagian, $select_bagian1);
        }
        // dd($select_bagian1);
        return array(
            'select' => $select,
        );
    }
    public function get_jabatan(Request $request)
    {
        $id_bagian    = $request->bagian_filter;
        // dd($period);

        $jabatan      = Jabatan::where('bagian_id', $id_bagian)->where('holding', $request->holding)->orderBy('nama_jabatan', 'ASC')->get();
        // dd($jabatan, $id_bagian, $request->all());
        if ($jabatan == NULL || $jabatan == '' || count($jabatan) == '0') {
            $select = '<option value="">Pilih Jabatan...</option>';
        } else {
            $select_jabatan[] = "<option value=''>Pilih Jabatan...</option>";
            foreach ($jabatan as $jabatan) {
                $select_jabatan1[] = "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
            }
            $select = array_merge($select_jabatan, $select_jabatan1);
        }
        // dd($data_columns_header, $data_columns);
        return array(
            'select' => $select,
        );
    }
    public function get_grafik_absensi(Request $request)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $request->holding)->value('id');

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();
        $period = CarbonPeriod::create($start_date, $end_date);
        $query = MappingShift::with(['Shift', 'User'])
            ->whereBetween('tanggal_masuk', [$start_date, $end_date])
            ->whereHas('User', function ($q) use ($holding, $request) {
                $q->where('kontrak_kerja', $holding);

                if (!empty($request->departemen_filter)) {
                    $q->whereIn('dept_id', $request->departemen_filter);
                }

                if (!empty($request->divisi_filter)) {
                    $q->whereIn('divisi_id', $request->divisi_filter);
                }

                if (!empty($request->bagian_filter)) {
                    $q->whereIn('bagian_id', $request->bagian_filter);
                }

                if (!empty($request->jabatan_filter)) {
                    $q->whereIn('jabatan_id', $request->jabatan_filter);
                }
            });

        $baseQuery = $query->get();
        $label_absensi = [];


        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');
            $label_absensi[] = $tanggal;

            // default 0
            $data_absensi_masuk_tepatwaktu[$tanggal] = 0;
            $data_absensi_masuk_telat[$tanggal] = 0;
            $data_absensi_masuk_tidak_hadir[$tanggal] = 0;
            $data_absensi_masuk_cuti[$tanggal] = 0;
        }
        // dd($baseQuery);
        foreach ($baseQuery as $shiftData) {
            if ($shiftData->status_absen == 'LIBUR' || !$shiftData->Shift) continue;
            $tanggal = Carbon::parse($shiftData->tanggal_masuk)->format('Y-m-d');
            $timeplus5 = Carbon::parse($shiftData->Shift->jam_masuk)->addMinutes(6);
            // dd($start_hours);
            $logs = AttendanceLog::where('EnrollNumber', $shiftData->user->nomor_identitas_karyawan)->whereDate('LogTime', $shiftData->tanggal_masuk)->first();
            // dd($logs);
            if ($logs != NULL) {
                $logtime = $logs->LogTime;
                $check_in = Carbon::parse($logtime);
                $check_in5 = Carbon::parse($shiftData->tanggal_masuk)->setTimeFrom($timeplus5);
                $check_inmin1hours = Carbon::parse($shiftData->tanggal_masuk)->setTimeFrom($shiftData->Shift->jam_masuk)->subHours(1);
                // dd([
                //     'logtime'          => $check_in->toDateTimeString(),
                //     'check_inmin1hours' => $check_inmin1hours->toDateTimeString(),
                //     'check_in5'        => $check_in5->toDateTimeString(),
                // ]);

                if ($check_in->toDateTimeString() > $check_inmin1hours->toDateTimeString() && $check_in->toDateTimeString() < $check_in5->toDateTimeString()) {
                    $data_absensi_masuk_tepatwaktu[$tanggal]++;
                } else {
                    $data_absensi_masuk_telat[$tanggal]++;
                }
            } else {
                if ($shiftData->status_absen == 'TIDAK HADIR KERJA') {
                    if ($shiftData->keterangan_absensi == 'CUTI' && $shiftData->keterangan_cuti == 'TRUE') {
                        $data_absensi_masuk_cuti[$tanggal]++;
                    } else {
                        if ($shiftData->tanggal_masuk > date('Y-m-d')) {
                            continue;
                        }
                        $data_absensi_masuk_tidak_hadir[$tanggal]++;
                    }
                }
            }
        }

        $data_result = [
            'label_absensi' => $label_absensi,
            'data_absensi_masuk_tepatwaktu' => array_values($data_absensi_masuk_tepatwaktu),
            'data_absensi_masuk_cuti' => array_values($data_absensi_masuk_cuti),
            'data_absensi_masuk_tidak_hadir' => array_values($data_absensi_masuk_tidak_hadir),
            'data_absensi_masuk_telat' => array_values($data_absensi_masuk_telat)
        ];
        // dd($data_result);
        return response()->json($data_result);
    }
    public function get_grafik_absensi_nonshift(Request $request)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $request->holding)->value('id');

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();
        $period = CarbonPeriod::create($start_date, $end_date);
        $query = AttendanceLog::with(['Karyawan'])
            ->whereBetween(DB::raw('DATE(LogTime)'), [$start_date, $end_date])
            ->whereHas('User', function ($q) use ($holding, $request) {
                $q->where('kontrak_kerja', $holding);

                if (!empty($request->departemen_filter)) {
                    $q->whereIn('dept_id', $request->departemen_filter);
                }

                if (!empty($request->divisi_filter)) {
                    $q->whereIn('divisi_id', $request->divisi_filter);
                }

                if (!empty($request->bagian_filter)) {
                    $q->whereIn('bagian_id', $request->bagian_filter);
                }

                if (!empty($request->jabatan_filter)) {
                    $q->whereIn('jabatan_id', $request->jabatan_filter);
                }
            });

        $baseQuery = $query->get();
        $label_absensi = [];

        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');
            $label_absensi[] = $tanggal;

            // default 0
            $data_absensi_masuk_tepatwaktu[$tanggal] = 0;
            $data_absensi_masuk_telat[$tanggal] = 0;
            $data_absensi_masuk_tidak_hadir[$tanggal] = 0;
            $data_absensi_masuk_cuti[$tanggal] = 0;
        }
        // dd($baseQuery);
        foreach ($baseQuery as $shiftData) {
            if ($shiftData->status_absen == 'LIBUR' || !$shiftData->Shift) continue;
            $tanggal = Carbon::parse($shiftData->tanggal_masuk)->format('Y-m-d');
            $timeplus5 = Carbon::parse($shiftData->Shift->jam_masuk)->addMinutes(6);
            // dd($start_hours);
            $logs = AttendanceLog::where('EnrollNumber', $shiftData->user->nomor_identitas_karyawan)->whereDate('LogTime', $shiftData->tanggal_masuk)->first();
            // dd($logs);
            if ($logs != NULL) {
                $logtime = $logs->LogTime;
                $check_in = Carbon::parse($logtime);
                $check_in5 = Carbon::parse($shiftData->tanggal_masuk)->setTimeFrom($timeplus5);
                $check_inmin1hours = Carbon::parse($shiftData->tanggal_masuk)->setTimeFrom($shiftData->Shift->jam_masuk)->subHours(1);
                // dd([
                //     'logtime'          => $check_in->toDateTimeString(),
                //     'check_inmin1hours' => $check_inmin1hours->toDateTimeString(),
                //     'check_in5'        => $check_in5->toDateTimeString(),
                // ]);

                if ($check_in->toDateTimeString() > $check_inmin1hours->toDateTimeString() && $check_in->toDateTimeString() < $check_in5->toDateTimeString()) {
                    $data_absensi_masuk_tepatwaktu[$tanggal]++;
                } else {
                    $data_absensi_masuk_telat[$tanggal]++;
                }
            } else {
                if ($shiftData->status_absen == 'TIDAK HADIR KERJA') {
                    if ($shiftData->keterangan_absensi == 'CUTI' && $shiftData->keterangan_cuti == 'TRUE') {
                        $data_absensi_masuk_cuti[$tanggal]++;
                    } else {
                        if ($shiftData->tanggal_masuk > date('Y-m-d')) {
                            continue;
                        }
                        $data_absensi_masuk_tidak_hadir[$tanggal]++;
                    }
                }
            }
        }

        $data_result = [
            'label_absensi' => $label_absensi,
            'data_absensi_masuk_tepatwaktu' => array_values($data_absensi_masuk_tepatwaktu),
            'data_absensi_masuk_cuti' => array_values($data_absensi_masuk_cuti),
            'data_absensi_masuk_tidak_hadir' => array_values($data_absensi_masuk_tidak_hadir),
            'data_absensi_masuk_telat' => array_values($data_absensi_masuk_telat)
        ];
        // dd($data_result);
        return response()->json($data_result);
    }
}
