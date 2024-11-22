<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\MappingShift;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());

        $holding = request()->segment(count(request()->segments()));
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

        // dd($header1);
        // $datacolumn = [];
        // dd($datacolumn);
        return view('admin.report.index', [
            'holding' => $holding,
            'departemen' => $departemen,
            'period' => $period,
            'start_date' => $start_date,
            'datacolumn' => $datacolumn,
            'end_date' => $end_date,
            'data_columns_header' => $data_columns_header,
            'count_period' => $count_period,
        ]);
    }
    public function index_kedisiplinan(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
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
        $departemen = Departemen::where('holding', $holding)->orderBy('nama_departemen', 'ASC')->get();
        // dd($departemen);
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
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        return array('data_columns_header' => $data_columns_header, 'count_period' => $count_period, 'datacolumn' => $data_columns, 'filter_month' => $request->filter_month);
    }
    public function get_columns_kedisiplinan(Request $request)
    {
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        // dd($end_date);
        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $data_columns_header[] = ['header' => $date->format('d/m/Y')];
            $data_columns[] = ['data' => 'tanggal_' . $date->format('dmY'), 'name' => 'tanggal_' . $date->format('dmY')];
        }
        $count_period = count($period);

        return array('data_columns_header' => $data_columns_header, 'count_period' => $count_period, 'datacolumn' => $data_columns, 'filter_month' => $request->filter_month);
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
    public function datatable_kedisiplinan(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        // if (request()->ajax()) {
        $now = Carbon::parse($request->start_date);
        $now1 = Carbon::parse($request->end_date);
        $period = CarbonPeriod::create($now, $now1);
        // $now = Carbon::parse($request->filter_month)->startOfMonth();
        // dd(request()->ajax());
        if (request()->ajax()) {
            if (!empty($request->departemen_filter)) {
                // dd($date1, $date2);
                if (!empty($request->divisi_filter)) {
                    if (!empty($request->bagian_filter)) {
                        if (!empty($request->jabatan_filter)) {
                            $table = Karyawan::with('Cuti')
                                ->with('Izin')
                                ->with('Mappingshift')
                                ->where('dept_id', $request->departemen_filter)
                                ->where('divisi_id', $request->divisi_filter)
                                ->where('bagian_id', $request->bagian_filter)
                                ->where('jabatan_id', $request->jabatan_filter)
                                ->where('kontrak_kerja', $holding)
                                ->where('kategori', 'Karyawan Bulanan')
                                ->where('status_aktif', 'AKTIF')
                                ->get();
                        } else {
                            $table = Karyawan::with('Cuti')
                                ->with('Izin')
                                ->with('Mappingshift')
                                ->where('dept_id', $request->departemen_filter)
                                ->where('divisi_id', $request->divisi_filter)
                                ->where('bagian_id', $request->bagian_filter)
                                ->where('kontrak_kerja', $holding)
                                ->where('kategori', 'Karyawan Bulanan')
                                ->where('status_aktif', 'AKTIF')
                                ->get();
                        }
                    } else {
                        $table = Karyawan::with('Cuti')
                            ->with('Izin')
                            ->with('Mappingshift')
                            ->where('dept_id', $request->departemen_filter)
                            ->where('divisi_id', $request->divisi_filter)
                            ->where('kontrak_kerja', $holding)
                            ->where('kategori', 'Karyawan Bulanan')
                            ->where('status_aktif', 'AKTIF')
                            ->get();
                    }
                } else {
                    $table = Karyawan::with('Cuti')
                        ->with('Izin')
                        ->with('Mappingshift')
                        ->where('dept_id', $request->departemen_filter)
                        ->where('kontrak_kerja', $holding)
                        ->where('kategori', 'Karyawan Bulanan')
                        ->where('status_aktif', 'AKTIF')
                        ->get();
                }
            } else {
                // dd($now, $now1);
                // dd($tgl_mulai, $tgl_selesai);
                $table = Karyawan::where('kontrak_kerja', $holding)
                    ->where('kategori', 'Karyawan Bulanan')
                    ->where('status_aktif', 'AKTIF')
                    ->where('name', 'MUHAMMAD FAIZAL IZAK')
                    // ->where('name', 'ISMAIL')
                    ->select('karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan')
                    ->orderBy('karyawans.name', 'ASC')
                    // ->limit(2)
                    ->get();
                // dd($table);
            }
            $column = DataTables::of($table);
            foreach ($period as $date) {
                $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                    // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                    $jumlah_kehadiran = MappingShift::where('user_id', $row->id)
                        ->where('tanggal_masuk', $date->format('Y-m-d'))->value('status_absen');
                    if ($jumlah_kehadiran == '') {
                        return '-';
                    } else {
                        return $jumlah_kehadiran;
                    }
                });
                $data_tanggal[] = 'tanggal_' . $date->format('dmY');
            }
            $column->addColumn('btn_detail', function ($row) use ($holding) {
                $btn_detail = '<a id="btn_detail" type="button" href="' . url('rekap-data/detail', ['id' => $row->nomor_identitas_karyawan]) . '/' . $holding . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Detail</a>';
                return $btn_detail;
            });
            $column->addColumn('total_hadir_tepat_waktu', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $jumlah_hadir_tepat_waktu = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('keterangan_absensi', 'TEPAT WAKTU')->where('status_absen', 'HADIR KERJA')->count();
                // dd($jumlah_hadir_tepat_waktu);
                return $jumlah_hadir_tepat_waktu;
            });
            $column->addColumn('total_hadir_telat_hadir', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $jumlah_hadir_telat_hadir = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('telat', '<', '00:10:59')->count();
                return $jumlah_hadir_telat_hadir;
            });
            $column->addColumn('total_hadir_telat_hadir1', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_hadir_telat_hadir1 = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('telat', '>', '00:10:59')->count();
                return $total_hadir_telat_hadir1;
            });
            $column->addColumn('total_izin_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_izin_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('keterangan_absensi', 'IZIN SAKIT')
                            ->orWhere('keterangan_absensi', 'IZIN TIDAK MASUK');
                    })->where('keterangan_izin', 'TRUE')
                    ->count();
                return $total_izin_true;
            });
            $column->addColumn('total_cuti_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_cuti_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('keterangan_absensi', 'CUTI');
                    })->where('keterangan_cuti', 'TRUE')->count();
                return $total_cuti_true;
            });
            $column->addColumn('total_dinas_true', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_dinas_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('keterangan_absensi', 'PENUGASAN');
                    })->where('keterangan_dinas', 'TRUE')->count();
                return $total_dinas_true;
            });
            $column->addColumn('tidak_hadir_kerja', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $tidak_hadir_kerja = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'FALSE')->where('keterangan_cuti', 'FALSE')->where('keterangan_izin', 'FALSE')->count();
                return $tidak_hadir_kerja;
            });
            $column->addColumn('total_libur', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_libur = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'LIBUR')->count();
                return $total_libur;
            });
            $column->addColumn('total_semua', function ($row) use ($now, $now1) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $total_hadir = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'HADIR KERJA')->count();
                $total_libur = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'LIBUR')->count();
                $total_dinas_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('keterangan_absensi', 'PENUGASAN');
                    })->where('keterangan_dinas', 'TRUE')->count();
                $total_tidak_hadir = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->count();
                $total_cuti_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('keterangan_absensi', 'CUTI');
                    })->where('keterangan_cuti', 'TRUE')->count();
                $total_izin_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])
                    ->where('status_absen', 'TIDAK HADIR KERJA')
                    ->where(function ($query) {
                        $query->where('keterangan_absensi', 'IZIN SAKIT')
                            ->orWhere('keterangan_absensi', 'IZIN TIDAK MASUK');
                    })
                    ->where('keterangan_izin', 'TRUE')
                    ->count();
                $total_dinas_true = MappingShift::where('user_id', $row->id)->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'TRUE')->count();
                $total_semua = ($total_hadir + $total_cuti_true + $total_libur + $total_izin_true + $total_dinas_true + $total_tidak_hadir);
                return $total_semua;
            });
            return $column->rawColumns(['total_hadir_tepat_waktu', 'total_libur', 'btn_detail', 'total_hadir_telat_hadir', 'total_hadir_telat_hadir1', 'total_izin_true', 'total_cuti_true', 'total_dinas_true', 'total_pulang_cepat', 'tidak_hadir_kerja', 'total_semua'])
                ->make(true);
        }
    }
    public function datatable(Request $request)
    {
        // dd($request->filter_month);
        $holding = request()->segment(count(request()->segments()));
        // if (request()->ajax()) {

        $now = Carbon::parse($request->filter_month)->startOfMonth();
        $now1 = Carbon::parse($request->filter_month)->endOfMonth();
        $period = CarbonPeriod::create($now, $now1);

        // dd($now1);
        // dd($tgl_mulai, $tgl_selesai);
        $table = Karyawan::where('kontrak_kerja', $holding)
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF')
            // ->where('name', 'MUHAMMAD FAIZAL IZAK')
            ->select('karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan')
            ->orderBy('karyawans.name', 'ASC')
            // ->limit(50)
            ->get();
        // dd($table);
        $column = DataTables::of($table);
        foreach ($period as $date) {
            $column->addColumn('tanggal_' . $date->format('dmY'), function ($row) use ($date) {
                // $id_karyawan = Karyawan::where('nomor_identitas_karyawan', $row->nomor_identitas_karyawan)->value('id');
                $jumlah_kehadiran = MappingShift::where('user_id', $row->id)
                    ->where('tanggal_masuk', $date->format('Y-m-d'))->value('status_absen');
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
    public function get_divisi(Request $request)
    {
        // dd($request->all());
        $id_departemen    = $request->departemen_filter;

        $divisi      = Divisi::where('dept_id', $id_departemen)->where('holding', $request->holding)->orderBy('nama_divisi', 'ASC')->get();
        echo "<option value=''>Pilih Divisi...</option>";
        foreach ($divisi as $divisi) {
            echo "<option value='$divisi->id'>$divisi->nama_divisi</option>";
        }
    }
    public function get_bagian(Request $request)
    {
        $id_divisi    = $request->divisi_filter;

        $bagian      = Bagian::where('divisi_id', $id_divisi)->where('holding', $request->holding)->orderBy('nama_bagian', 'ASC')->get();
        echo "<option value=''>Pilih Bagian...</option>";
        foreach ($bagian as $bagian) {
            echo "<option value='$bagian->id'>$bagian->nama_bagian</option>";
        }
    }
    public function get_jabatan(Request $request)
    {
        $id_bagian    = $request->bagian_filter;
        $jabatan      = Jabatan::where('bagian_id', $id_bagian)->where('holding', $request->holding)->orderBy('nama_jabatan', 'ASC')->get();
        echo "<option value=''>Pilih Jabatan...</option>";
        foreach ($jabatan as $jabatan) {
            echo "<option value='$jabatan->id'>$jabatan->nama_jabatan</option>";
        }
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

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
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
