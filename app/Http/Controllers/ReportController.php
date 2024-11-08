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
    public function ExportReport(Request $request)
    {
        $date = date('YmdHis');
        $holding = request()->segment(count(request()->segments()));
        return Excel::download(new ReportExport($holding), 'Data Karyawan_' . $holding . '_' . $date . '.xlsx');
    }
}
