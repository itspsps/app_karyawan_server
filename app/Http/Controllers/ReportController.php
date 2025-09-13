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
use App\Models\SolutionUser;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ParagonIE\Sodium\Core\Curve25519\H;
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
    public function get_columns(Request $request)
    {
        // dd($request->filter_month);
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
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
            'filter_month' => $request->filter_month
        );
    }
    public function get_columns_kedisiplinan(Request $request)
    {
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        return array(
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'filter_month' => $request->filter_month
        );
    }
    public function get_filter_month(Request $request)
    {
        // dd($request->filter_month);
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);
        return array('data_columns_header' => $data_columns_header, 'count_period' => $count_period, 'datacolumn' => $data_columns, 'filter_month' => $request->filter_month);
    }
    public function datatable_kedisiplinan(Request $request, $holding)
    {
        // dd($request->all());
        $holding = Holding::where('holding_code', $holding)->first();
        // if (request()->ajax()) {
        $now = Carbon::parse($request->filter_month)->startOfMonth();
        $now1 = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($now, $now1);
        // $now = Carbon::parse($request->filter_month)->startOfMonth();
        // dd(request()->ajax());
        if (request()->ajax()) {
            $query = Karyawan::with(['Absensi' => function ($q) use ($now, $now1) {
                $q->whereBetween('LogTime', [$now, $now1])
                    ->select('EnrollNumber', 'LogTime'); // supaya jelas field yg dibawa
            }])
                ->with(['MappingShift' => function ($q) use ($now, $now1) {
                    $q->with('Shift');
                    $q->whereBetween('tanggal_masuk', [$now, $now1]);
                }])->where('kontrak_kerja', $holding->id)
                ->where('nomor_identitas_karyawan', '=', '2002205090298')
                ->where('kategori', 'Karyawan Bulanan')
                ->where('status_aktif', 'AKTIF');

            if (!empty($request->departemen_filter)) {
                $query->where('dept_id', $request->departemen_filter);
            }

            if (!empty($request->divisi_filter)) {
                $query->where('divisi_id', $request->divisi_filter);
            }

            if (!empty($request->bagian_filter)) {
                $query->where('bagian_id', $request->bagian_filter);
            }

            if (!empty($request->jabatan_filter)) {
                $query->where('jabatan_id', $request->jabatan_filter);
            }
            $table = $query->select('karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan')
                ->orderBy('karyawans.name', 'ASC')
                ->get();
            // dd($table);
            $column = DataTables::of($table);
            foreach ($period as $date) {
                $colName = 'tanggal_' . $date->format('dmY');
                $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                    // ambil log dari eager load Absensi
                    $logs = $row->Absensi->filter(function ($log) use ($date) {
                        return Carbon::parse($log->LogTime)->isSameDay($date);
                    });

                    $shift = $row->MappingShift->firstWhere('tanggal_masuk', $date->toDateString());

                    if (!$shift) {
                        return '<span class="badge bg-danger">Belum diassign shift</span>';
                    }
                    if ($shift->status_absen == 'LIBUR') {
                        return '<span class="badge bg-info">Libur</span>';
                    }

                    if ($logs->isNotEmpty()) {
                        $checkIn  = Carbon::parse($logs->min('LogTime'))->format('H:i');
                        $checkOut = Carbon::parse($logs->max('LogTime'))->format('H:i');
                        if ($checkIn === $checkOut) $checkOut = '<p style="color:red;">Kosong</p>';
                        return "($checkIn - $checkOut)";
                    }
                    return '-';
                });
                $data_tanggal[] = $colName;
            }
            $column->addColumn('btn_detail', function ($row) use ($holding) {
                $btn_detail = '<a id="btn_detail" type="button" href="' . url('rekap-data/detail', ['id' => $row->nomor_identitas_karyawan]) . '/' . $holding->holding_code . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Detail</a>';
                return $btn_detail;
            });
            $column->addColumn('total_hadir_tepat_waktu', function ($row) use ($now, $now1) {
                $mapping_shift = $row->MappingShift;
                foreach ($mapping_shift as $data) {
                    if ($data->status_absen != 'LIBUR') {
                        if ($data->Shift != NULL) {
                            $timeplus5 = Carbon::parse($data->Shift->jam_masuk)->addMinutes(5)->format('H:i:s');
                            $datetime = $data->tanggal_masuk . ' ' . $timeplus5;
                            $logs = $row->Absensi->filter(function ($log) use ($data) {
                                return Carbon::parse($log->LogTime)->isSameDay($data->tanggal_masuk);
                            });
                            if ($logs->isNotEmpty()) {
                                $logtime  = $logs->min('LogTime'); // ambil check-in paling awal
                                $check_in = Carbon::parse($logtime)->format('H:i');
                                $shift    = Carbon::parse($timeplus5)->format('H:i');

                                if ($check_in <= $shift) {
                                    $row->tepat_waktu = ($row->tepat_waktu ?? 0) + 1;
                                }
                            }
                        }
                    }
                }


                return $row->tepat_waktu ?? 0;
            });
            $column->addColumn('total_hadir_telat_hadir', function ($row) use ($now, $now1) {
                $mapping_shift = $row->MappingShift;
                foreach ($mapping_shift as $data) {
                    if ($data->status_absen != 'LIBUR') {
                        if ($data->Shift != NULL) {
                            $plus5  = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_masuk)->addMinutes(5)->format('H:i');
                            $plus15 = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_masuk)->addMinutes(15)->format('H:i');
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
                return $row->telat_ringan ?? 0;
            });
            $column->addColumn('total_hadir_telat_hadir1', function ($row) use ($now, $now1) {
                $mapping_shift = $row->MappingShift;
                foreach ($mapping_shift as $data) {
                    if ($data->status_absen != 'LIBUR') {
                        if ($data->Shift != NULL) {
                            $plus15 = Carbon::parse($data->tanggal_masuk . ' ' . $data->Shift->jam_masuk)->addMinutes(15)->format('H:i');
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
                return $row->telat_berat ?? 0;
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
            $column->addColumn('tidak_hadir_kerja', function ($row) use ($now, $now1) {
                $tidak_hadir = 0;
                $today = Carbon::today();
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
                $row->tidak_hadir = $tidak_hadir;
                return $tidak_hadir;
            });
            $column->addColumn('total_libur', function ($row) use ($now, $now1) {
                $today = Carbon::today();

                // ambil hanya libur yang <= hari ini
                $total_libur = $row->MappingShift
                    ->filter(function ($shift) use ($today) {
                        return $shift->status_absen === 'LIBUR'
                            && Carbon::parse($shift->tanggal_masuk)->lte($today);
                    })
                    ->count();

                $row->libur = $total_libur;
                return $total_libur;
            });
            $column->addColumn('total_semua', function ($row) use ($now, $now1) {
                $today = Carbon::now()->format('Y-m-d');
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_telat_berat = $row->telat_berat ?? 0;
                $total_tepat_waktu = $row->tepat_waktu ?? 0;
                $total_libur = $row->libur ?? 0;
                $total_telat_ringan = $row->telat_ringan ?? 0;
                $total_dinas_true = $row->total_dinas ?? 0;
                $total_tidak_hadir = $row->tidak_hadir ?? 0;
                $total_cuti_true = $row->total_cuti ?? 0;
                $total_izin_true = $row->total_izin ?? 0;
                $total_semua = ($total_telat_berat + $total_tepat_waktu + $total_cuti_true + $total_libur + $total_izin_true + $total_dinas_true  + $total_telat_ringan + $total_tidak_hadir);

                return $total_semua;
            });
            $rawCols = array_merge([
                'total_hadir_tepat_waktu',
                'total_libur',
                'btn_detail',
                'total_hadir_telat_hadir',
                'total_hadir_telat_hadir1',
                'total_izin_true',
                'total_cuti_true',
                'total_dinas_true',
                'total_pulang_cepat',
                'tidak_hadir_kerja',
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
        $now = Carbon::parse($request->filter_month)->startOfMonth();
        $now1 = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($now, $now1);

        $karyawan  = Karyawan::where('kontrak_kerja', $holding->id)
            ->with(['Absensi' => function ($q) use ($now, $now1) {
                $q->whereBetween('LogTime', [$now, $now1]);
            }])
            ->with(['MappingShift' => function ($q) use ($now, $now1) {
                $q->whereBetween('tanggal_masuk', [$now, $now1]);
            }])
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF')
            // ->where('nomor_identitas_karyawan', '2002302270999')
            ->select('karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan')
            ->orderBy('karyawans.name', 'ASC')
            // ->limit(6)
            ->get();

        $totalHariKerja = collect($period)->count();
        $column = DataTables::of($karyawan);
        foreach ($period as $date) {
            $colName = 'tanggal_' . $date->format('dmY');
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                // ambil log dari eager load Absensi
                $logs = $row->Absensi->filter(function ($log) use ($date) {
                    return Carbon::parse($log->LogTime)->isSameDay($date);
                });

                $shift = $row->MappingShift->firstWhere('tanggal_masuk', $date->toDateString());

                if (!$shift) {
                    return '<span class="badge bg-danger">Belum diassign shift</span>';
                }
                if ($shift->status_absen == 'LIBUR') {
                    return '<span class="badge bg-info">Libur</span>';
                }

                if ($logs->isNotEmpty()) {
                    $checkIn  = Carbon::parse($logs->min('LogTime'))->format('H:i');
                    $checkOut = Carbon::parse($logs->max('LogTime'))->format('H:i');
                    if ($checkIn === $checkOut) $checkOut = '<p style="color:red;">Kosong</p>';
                    return "($checkIn - $checkOut)";
                }
                return '-';
            });
            $data_tanggal[] = $colName;
        }
        $column->addColumn('action', function ($row) {
            return '<button class="btn btn-sm btn-primary" onclick="edit(' . $row->USERID . ')">Edit</button>';
        });
        $column->addColumn('jumlah_hadir', function ($row) {
            $mapping_shift = $row->MappingShift;
            // return $mapping_shift;
            if ($mapping_shift->isEmpty()) {
                $jumlah_hadir = 0;
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
            }
            // simpan di row biar bisa dipakai di kolom lain
            $row->jumlah_hadir = $jumlah_hadir;
            $row->mapping_shift = $mapping_shift;
            return $jumlah_hadir;
        })
            ->addColumn('name', function ($row) {
                return $row->name;
            });
        $column->addColumn('total_tidak_hadir_kerja', function ($row) {

            $mapping_shift  = $row->MappingShift
                ->where('status_absen', '!=', 'LIBUR');
            if (!$mapping_shift->first()) {
                return '<span class="badge bg-danger">0</span>';
            }

            // hitung tidak hadir
            $total_tidak_hadir_kerja = $mapping_shift->count() - ($row->jumlah_hadir ?? 0);
            $row->total_tidak_hadir_kerja = $total_tidak_hadir_kerja;
            return $total_tidak_hadir_kerja;
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

        $rawCols = array_merge(['action', 'total_tidak_hadir_kerja', 'total_libur', 'total_cuti', 'name', 'jumlah_hadir'], $data_tanggal);
        return $column->rawColumns($rawCols)
            ->make('true');
    }
    public function datatable(Request $request, $holding)
    {
        // dd($request->filter_month, $holding);
        $holding = Holding::where('holding_code', $holding)->first();
        // if (request()->ajax()) {

        $now = Carbon::parse($request->filter_month)->startOfMonth();
        $now1 = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($now, $now1);

        // dd($holding);
        // dd($tgl_mulai, $tgl_selesai);
        $table = Karyawan::where('kontrak_kerja', $holding->id)
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF')
            // ->where('name', 'MUHAMMAD FAIZAL IZAK')
            ->select('karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan')
            ->orderBy('karyawans.name', 'ASC')
            // ->limit(6)
            ->get();
        // dd($table);
        $column = DataTables::of($table);
        foreach ($period as $date) {
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $jumlah_kehadiran = AttendanceLog::where('EnrollNumber', $row->nomor_identitas_karyawan)
                    ->where('LogTime', $date->format('Y-m-d'))->value('LogTime');
                if ($jumlah_kehadiran == '') {
                    return '-';
                } else {
                    return $jumlah_kehadiran;
                }
            });
            $data_tanggal[] = 'tanggal_' . $date->format('dmY');
        }
        $column->addColumn('total_hadir_kerja', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_hadir_kerja = MappingShift::where('user_id', $row->id)
                ->whereBetween('tanggal_masuk', [$now, $now1])
                ->where('status_absen', 'HADIR KERJA')->count();
            return $total_hadir_kerja;
        });
        // dd($oke);
        $column->addColumn('total_tidak_hadir_kerja', function ($row) use ($now, $now1) {
            // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
            $total_tidak_hadir_kerja = MappingShift::where('user_id', $row->id)
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
    public function get_divisi(Request $request, $holding)
    {
        // dd($request->all());
        $id_departemen    = $request->departemen_filter;
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        $divisi      = Divisi::where('dept_id', $id_departemen)->where('holding', $request->holding)->orderBy('nama_divisi', 'ASC')->get();
        // dd($divisi);
        if ($divisi == NULL || $divisi == '' || count($divisi) == '0') {
            $select = '<option value="">Pilih Divisi...</option>';
        } else {

            $select_divisi[] = "<option value=''>Pilih Divisi...</option>";
            foreach ($divisi as $divisi) {
                $select_divisi1[] = "<option value='$divisi->id'>$divisi->nama_divisi</option>";
            }
            $select = array_merge($select_divisi, $select_divisi1);
        }
        // dd($select);
        return array(
            'select' => $select,
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'filter_month' => $request->filter_month
        );
    }
    public function get_bagian(Request $request)
    {
        // dd($request->all());
        $id_divisi    = $request->divisi_filter;
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        $bagian      = Bagian::where('divisi_id', $id_divisi)->where('holding', $request->holding)->orderBy('nama_bagian', 'ASC')->get();
        if ($bagian == NULL || $bagian == '' || count($bagian) == '0') {
            $select = "<option value=''>Pilih Bagian...</option>";
        } else {

            $select_bagian[] = "<option value=''>Pilih Bagian...</option>";
            foreach ($bagian as $bagian) {
                $select_bagian1[] = "<option value='$bagian->id'>$bagian->nama_bagian</option>";
            }
            $select = array_merge($select_bagian + $select_bagian1);
        }
        dd($select_bagian1);
        return array(
            'select' => $select,
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
            'datacolumn' => $data_columns,
            'filter_month' => $request->filter_month
        );
    }
    public function get_jabatan(Request $request)
    {
        $id_bagian    = $request->bagian_filter;
        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);
        $jabatan      = Jabatan::where('bagian_id', $id_bagian)->where('holding', $request->holding)->orderBy('nama_jabatan', 'ASC')->get();
        if ($jabatan == NULL || $jabatan == '' || count($jabatan) == '0') {
            $select = '<option value="">Pilih Jabatan...</option>';
        } else {
            $select_jabatan[] = "<option value=''>Pilih Jabatan...</option>";
            foreach ($jabatan as $jabatan) {
                $select_jabatan1[] = "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
            }
            $select = array_merge($select_jabatan + $select_jabatan1);
        }
        return array('select' => $select, 'data_columns_header' => $data_columns_header, 'count_period' => $count_period, 'datacolumn' => $data_columns, 'filter_month' => $request->filter_month);
    }
    public function get_grafik_absensi(Request $request)
    {
        $get_holding = $request->holding;
        if ($get_holding == 'sp') {
            $holding = 'SP';
        } else if ($get_holding == 'sps') {
            $holding = 'SPS';
        } else {
            $holding = 'SIP';
        }

        $start_date = Carbon::parse($request->filter_month)->startOfMonth();
        $end_date = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($start_date, $end_date);
        // dd($request->all());
        foreach ($period as $date) {
            $label_absensi[] = $date->format('d/m/Y');
            if ($request->departemen_filter != '' || $request->departemen_filter != NULL) {
                if ($request->divisi_filter != '' || $request->divisi_filter != NULL) {
                    if ($request->bagian_filter != '' || $request->bagian_filter != NULL) {
                        if ($request->jabatan_filter != '' || $request->jabatan_filter != NULL) {
                            $data_absensi_masuk_tepatwaktu[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')
                                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->where('karyawans.jabatan_id', $request->jabatan_filter)
                                ->count();
                            $data_absensi_masuk_telat[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.keterangan_absensi', 'TELAT HADIR')
                                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->where('karyawans.jabatan_id', $request->jabatan_filter)
                                ->count();
                            $data_absensi_masuk_tidak_hadir[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->where('karyawans.jabatan_id', $request->jabatan_filter)
                                ->count();
                            $data_absensi_masuk_cuti[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                                ->where(function ($query) {
                                    $query->where('mapping_shifts.keterangan_absensi', 'CUTI');
                                })->where('mapping_shifts.keterangan_cuti', 'TRUE')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->where('karyawans.jabatan_id', $request->jabatan_filter)
                                ->count();
                        } else {
                            $data_absensi_masuk_tepatwaktu[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')
                                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->count();
                            $data_absensi_masuk_telat[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.keterangan_absensi', 'TELAT HADIR')
                                ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->count();
                            $data_absensi_masuk_tidak_hadir[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->count();
                            $data_absensi_masuk_cuti[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                                ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                                ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                                ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                                ->where(function ($query) {
                                    $query->where('mapping_shifts.keterangan_absensi', 'CUTI');
                                })->where('mapping_shifts.keterangan_cuti', 'TRUE')
                                ->where('karyawans.kontrak_kerja', $holding)
                                ->where('karyawans.dept_id', $request->departemen_filter)
                                ->where('karyawans.divisi_id', $request->divisi_filter)
                                ->where('karyawans.bagian_id', $request->bagian_filter)
                                ->count();
                        }
                    } else {
                        $data_absensi_masuk_tepatwaktu[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                            ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                            ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                            ->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')
                            ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                            ->where('karyawans.kontrak_kerja', $holding)
                            ->where('karyawans.dept_id', $request->departemen_filter)
                            ->where('karyawans.divisi_id', $request->divisi_filter)
                            ->count();
                        $data_absensi_masuk_telat[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                            ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                            ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                            ->where('mapping_shifts.keterangan_absensi', 'TELAT HADIR')
                            ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                            ->where('karyawans.kontrak_kerja', $holding)
                            ->where('karyawans.dept_id', $request->departemen_filter)
                            ->where('karyawans.divisi_id', $request->divisi_filter)
                            ->count();
                        $data_absensi_masuk_tidak_hadir[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                            ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                            ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                            ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                            ->where('karyawans.kontrak_kerja', $holding)
                            ->where('karyawans.dept_id', $request->departemen_filter)
                            ->where('karyawans.divisi_id', $request->divisi_filter)
                            ->count();
                        $data_absensi_masuk_cuti[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                            ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                            ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                            ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                            ->where(function ($query) {
                                $query->where('mapping_shifts.keterangan_absensi', 'CUTI');
                            })->where('mapping_shifts.keterangan_cuti', 'TRUE')
                            ->where('karyawans.kontrak_kerja', $holding)
                            ->where('karyawans.dept_id', $request->departemen_filter)
                            ->where('karyawans.divisi_id', $request->divisi_filter)
                            ->count();
                    }
                } else {
                    $data_absensi_masuk_tepatwaktu[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                        ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                        ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                        ->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')
                        ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                        ->where('karyawans.kontrak_kerja', $holding)
                        ->where('karyawans.dept_id', $request->departemen_filter)
                        ->count();
                    $data_absensi_masuk_telat[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                        ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                        ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                        ->where('mapping_shifts.keterangan_absensi', 'TELAT HADIR')
                        ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                        ->where('karyawans.dept_id', $request->departemen_filter)
                        ->where('karyawans.kontrak_kerja', $holding)
                        ->count();
                    $data_absensi_masuk_tidak_hadir[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                        ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                        ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                        ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                        ->where('karyawans.kontrak_kerja', $holding)
                        ->where('karyawans.dept_id', $request->departemen_filter)
                        ->count();
                    $data_absensi_masuk_cuti[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                        ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                        ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                        ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                        ->where(function ($query) {
                            $query->where('mapping_shifts.keterangan_absensi', 'CUTI');
                        })->where('mapping_shifts.keterangan_cuti', 'TRUE')
                        ->where('karyawans.kontrak_kerja', $holding)
                        ->where('karyawans.dept_id', $request->departemen_filter)
                        ->count();
                }
            } else if ($request->departemen_filter == '') {
                // dd('p');
                $data_absensi_masuk_tepatwaktu[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                    ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                    ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                    ->where('mapping_shifts.keterangan_absensi', 'TEPAT WAKTU')
                    ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->count();
                $data_absensi_masuk_telat[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                    ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                    ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                    ->where('mapping_shifts.keterangan_absensi', 'TELAT HADIR')
                    ->where('mapping_shifts.status_absen', 'HADIR KERJA')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->count();
                $data_absensi_masuk_tidak_hadir[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                    ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                    ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                    ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->count();
                $data_absensi_masuk_cuti[] = MappingShift::Join('karyawans', 'karyawans.id', 'mapping_shifts.user_id')
                    ->where('mapping_shifts.tanggal_masuk', $date->format('Y-m-d'))
                    ->where('mapping_shifts.tanggal_masuk', '<=', date('Y-m-d'))
                    ->where('mapping_shifts.status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('mapping_shifts.keterangan_absensi', 'CUTI');
                    })->where('mapping_shifts.keterangan_cuti', 'TRUE')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->count();
                // dd($date->format('dmY'), date('dmY'));
            }
        }
        $data_result = ['label_absensi' => $label_absensi, 'data_absensi_masuk_tepatwaktu' => $data_absensi_masuk_tepatwaktu, 'data_absensi_masuk_cuti' => $data_absensi_masuk_cuti, 'data_absensi_masuk_tidak_hadir' => $data_absensi_masuk_tidak_hadir, 'data_absensi_masuk_telat' => $data_absensi_masuk_telat];
        // dd($data_result);
        return response()->json($data_result);
    }
}
