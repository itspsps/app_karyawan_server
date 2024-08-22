<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use App\Models\Bagian;
use App\Models\Cuti;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Lembur;
use App\Models\User;
use App\Models\MappingShift;
use App\Models\Shift;
use Carbon\Carbon;
use DateTime;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RekapDataController extends Controller
{
    public function index(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        date_default_timezone_set('Asia/Jakarta');

        // $bulan = date('m');
        // $tahun = date('Y');
        // $hari_per_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
        $tanggal_mulai = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $title = "Rekap Data Absensi Tanggal " . date('Y-m-01') . " s/d " . date('Y-m-d');

        $user = User::with('Cuti')->with('Izin')->get();
        // dd($user->Cuti->nama_cuti);

        if ($request["mulai"] && $request["akhir"]) {
            $tanggal_mulai = $request["mulai"];
            $tanggal_akhir = $request["akhir"];
            $title = "Rekap Data Absensi Tanggal " . $tanggal_mulai . " s/d " . $tanggal_akhir;
        }
        $departemen = Departemen::where('holding', $holding)->get();
        // dd($departemen);
        // dd(Carbon::createFromFormat('H:i:s', '17:12:00'));
        return view('admin.rekapdata.index', [
            'title' => $title,
            'data_user' => $user,
            'tanggal_mulai' => $tanggal_mulai,
            'departemen' => $departemen,
            'holding' => $holding,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }
    public function detail_index($id, Request $request)
    {
        // dd($id);
        $holding = request()->segment(count(request()->segments()));
        date_default_timezone_set('Asia/Jakarta');
        // $bulan = date('m');
        // $tahun = date('Y');
        // $hari_per_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan,$tahun);
        $tanggal_mulai = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $title = "Rekap Data Absensi Tanggal " . date('Y-m-01') . " s/d " . date('Y-m-d');

        $user = User::where('id', $id)->first();
        // dd($user->Cuti->nama_cuti);

        if ($request["mulai"] && $request["akhir"]) {
            $tanggal_mulai = $request["mulai"];
            $tanggal_akhir = $request["akhir"];
            $title = "Rekap Data Absensi Tanggal " . $tanggal_mulai . " s/d " . $tanggal_akhir;
        }
        // dd(Carbon::createFromFormat('H:i:s', '17:12:00'));
        return view('admin.rekapdata.detail', [
            'title' => $title,
            'data_user' => $user,
            'tanggal_mulai' => $tanggal_mulai,
            'holding' => $holding,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }
    public function datatable(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        // dd($request->all());
        // $now = Carbon::parse($request->filter_month)->startOfMonth();
        // dd($now);
        if (request()->ajax()) {
            if (!empty($request->departemen_filter)) {
                $date1 = Carbon::parse($request->filter_month)->startOfMonth();
                $date2 = Carbon::parse($request->filter_month)->endOfMonth();
                // dd($date1, $date2);
                if (!empty($request->divisi_filter)) {
                    if (!empty($request->bagian_filter)) {
                        if (!empty($request->jabatan_filter)) {
                            $table = User::with('Cuti')
                                ->with('Izin')
                                ->with('Mappingshift')
                                ->where('dept_id', $request->departemen_filter)
                                ->where('divisi_id', $request->divisi_filter)
                                ->where('bagian_id', $request->bagian_filter)
                                ->where('jabatan_id', $request->jabatan_filter)
                                ->where('kontrak_kerja', $holding)
                                ->where('kategori', 'Karyawan Bulanan')
                                ->get();
                        } else {
                            $table = User::with('Cuti')
                                ->with('Izin')
                                ->with('Mappingshift')
                                ->where('dept_id', $request->departemen_filter)
                                ->where('divisi_id', $request->divisi_filter)
                                ->where('bagian_id', $request->bagian_filter)
                                ->where('kontrak_kerja', $holding)
                                ->where('kategori', 'Karyawan Bulanan')
                                ->get();
                        }
                    } else {
                        $table = User::with('Cuti')
                            ->with('Izin')
                            ->with('Mappingshift')
                            ->where('dept_id', $request->departemen_filter)
                            ->where('divisi_id', $request->divisi_filter)
                            ->where('kontrak_kerja', $holding)
                            ->where('kategori', 'Karyawan Bulanan')
                            ->get();
                    }
                } else {
                    $table = User::with('Cuti')
                        ->with('Izin')
                        ->with('Mappingshift')
                        ->where('dept_id', $request->departemen_filter)
                        ->where('kontrak_kerja', $holding)
                        ->where('kategori', 'Karyawan Bulanan')
                        ->get();
                }
                return DataTables::of($table)
                    ->addColumn('btn_detail', function ($row) use ($holding) {
                        $btn_detail = '<a id="btn_detail" type="button" href="' . url('rekap-data/detail', ['id' => $row->id]) . '/' . $holding . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Detail</a>';
                        return $btn_detail;
                    })
                    ->addColumn('total_hadir_tepat_waktu', function ($row) use ($date1, $date2) {
                        $jumlah_hadir_tepat_waktu = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TEPAT WAKTU')->count();
                        return $jumlah_hadir_tepat_waktu . " x";
                    })
                    ->addColumn('total_hadir_telat_hadir', function ($row) use ($date1, $date2) {
                        $jumlah_hadir_telat_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->count();
                        return $jumlah_hadir_telat_hadir . " x";
                    })
                    ->addColumn('total_izin_true', function ($row) use ($date1, $date2) {
                        $total_izin_true = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_izin', 'TRUE')->count();
                        return $total_izin_true  . " x";
                    })
                    ->addColumn('total_cuti_true', function ($row) use ($date1, $date2) {
                        $total_cuti_true = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_cuti', 'TRUE')->count();
                        return $total_cuti_true  . " x";
                    })
                    ->addColumn('total_dinas_true', function ($row) use ($date1, $date2) {
                        $total_dinas_true = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'TRUE')->count();
                        return $total_dinas_true  . " x";
                    })

                    ->addColumn('tidak_hadir_kerja', function ($row) use ($date1, $date2) {
                        $tidak_hadir_kerja = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'FALSE')->where('keterangan_cuti', 'FALSE')->where('keterangan_izin', 'FALSE')->count() . " x";
                        return $tidak_hadir_kerja;
                    })
                    ->addColumn('total_semua', function ($row) use ($date1, $date2) {
                        $total_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'HADIR KERJA')->count();
                        $total_tidak_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'TIDAK HADIR KERJA')->count();
                        $total_semua = ($total_hadir + $total_tidak_hadir) . ' x';
                        return $total_semua;
                    })
                    ->rawColumns(['total_hadir_tepat_waktu', 'btn_detail', 'total_hadir_telat_hadir', 'total_izin_true', 'total_cuti_true', 'total_dinas_true', 'total_pulang_cepat', 'tidak_hadir_kerja', 'total_semua'])
                    ->make(true);
            } else {
                $now = Carbon::parse($request->filter_month)->startOfMonth();
                $now1 = Carbon::parse($request->filter_month)->endOfMonth();
                // dd($now1);
                // dd($tgl_mulai, $tgl_selesai);
                $table = User::with('Mappingshift')
                    ->where('kontrak_kerja', $holding)
                    ->where('kategori', 'Karyawan Bulanan')
                    // ->limit(210)
                    ->get();
                return DataTables::of($table)
                    ->addColumn('btn_detail', function ($row) use ($holding) {
                        $btn_detail = '<a id="btn_detail" type="button" href="' . url('rekap-data/detail', ['id' => $row->id]) . '/' . $holding . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Detail</a>';
                        return $btn_detail;
                    })
                    ->addColumn('total_hadir_tepat_waktu', function ($row) use ($now, $now1) {
                        $jumlah_hadir_tepat_waktu = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('keterangan_absensi', 'TEPAT WAKTU')->where('status_absen', 'HADIR KERJA')->count();
                        // dd($jumlah_hadir_tepat_waktu);
                        return $jumlah_hadir_tepat_waktu . " x";
                    })
                    ->addColumn('total_hadir_telat_hadir', function ($row) use ($now, $now1) {
                        $jumlah_hadir_telat_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('keterangan_absensi', 'TELAT HADIR')->count();
                        return $jumlah_hadir_telat_hadir . " x";
                    })
                    ->addColumn('total_izin_true', function ($row) use ($now, $now1) {
                        $total_izin_true = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_izin', 'TRUE')->count();
                        return $total_izin_true  . " x";
                    })
                    ->addColumn('total_cuti_true', function ($row) use ($now, $now1) {
                        $total_cuti_true = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_cuti', 'TRUE')->count();
                        return $total_cuti_true  . " x";
                    })
                    ->addColumn('total_dinas_true', function ($row) use ($now, $now1) {
                        $total_dinas_true = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'TRUE')->count();
                        return $total_dinas_true  . " x";
                    })

                    ->addColumn('tidak_hadir_kerja', function ($row) use ($now, $now1) {
                        $tidak_hadir_kerja = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'FALSE')->where('keterangan_cuti', 'FALSE')->where('keterangan_izin', 'FALSE')->count() . " x";
                        return $tidak_hadir_kerja;
                    })
                    ->addColumn('total_semua', function ($row) use ($now, $now1) {
                        $total_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'HADIR KERJA')->count();
                        $total_tidak_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'TIDAK HADIR KERJA')->count();
                        $total_semua = ($total_hadir + $total_tidak_hadir) . ' x';
                        return $total_semua;
                    })
                    ->rawColumns(['total_hadir_tepat_waktu', 'btn_detail', 'total_hadir_telat_hadir', 'total_izin_true', 'total_cuti_true', 'total_dinas_true', 'total_pulang_cepat', 'tidak_hadir_kerja', 'total_semua'])
                    ->make(true);
            }
        }
    }
    public function detail_datatable($id, Request $request)
    {
        $holding = request()->segment(count(request()->segments()));

        if (request()->ajax()) {
            if (!empty($request->filter_month)) {
                $jumlah_hari = explode('-', $request->filter_month);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[1]);
                $table = MappingShift::where('user_id', $id)
                    ->whereMonth('tanggal_masuk', $endDate)
                    ->get();
                // dd($table);
                return DataTables::of($table)
                    ->addColumn('foto_jam_absen', function ($row) {
                        if ($row->foto_jam_absen == '') {
                            $foto_absen_masuk = '';
                        } else {
                            $foto_absen_masuk = '<a target="_blank" href="https://karyawan.sumberpangan.store/laravel/storage/app/public/' . $row->foto_jam_absen . '">Foto Absen Masuk".$row->tanggal_masuk.$row->jam_absen."</a>';
                        }
                        return $foto_absen_masuk;
                    })
                    ->addColumn('foto_jam_pulang', function ($row) {
                        if ($row->foto_jam_pulang == '') {
                            $foto_absen_pulang = '';
                        } else {
                            $foto_absen_pulang = '<a target="_blank" href="https://karyawan.sumberpangan.store/laravel/storage/app/public/' . $row->foto_jam_pulang . '">Foto Absen Pulang' . $row->tanggal_pulang . $row->jam_pulang . '</a>';
                        }
                        return $foto_absen_pulang;
                    })
                    ->addColumn('jam_kerja', function ($row) {
                        if ($row->shift_id == '') {
                            $jam_kerja = '-';
                        } else {
                            $jam_masuk = Shift::where('id', $row->shift_id)->value('jam_masuk');
                            $jam_keluar = Shift::where('id', $row->shift_id)->value('jam_keluar');
                            $jam_kerja = $jam_masuk . ' - ' . $jam_keluar;
                        }
                        return $jam_kerja;
                    })
                    ->rawColumns(['jam_kerja', 'foto_jam_absen', 'foto_jam_pulang'])
                    ->make(true);
            } else {
                $month = date('m');
                $table = MappingShift::where('user_id', $id)
                    ->whereMonth('tanggal_masuk', $month)
                    ->get();
                // dd($table);
                return DataTables::of($table)
                    ->addColumn('foto_jam_absen', function ($row) {
                        if ($row->foto_jam_absen == '') {
                            $foto_absen_masuk = '';
                        } else {
                            $foto_absen_masuk = '<a target="_blank" href="https://karyawan.sumberpangan.store/laravel/storage/app/public/' . $row->foto_jam_absen . '">Foto Absen Masuk' . $row->tanggal_masuk . $row->jam_absen . '</a>';
                        }
                        return $foto_absen_masuk;
                    })
                    ->addColumn('foto_jam_pulang', function ($row) {
                        if ($row->foto_jam_pulang == '') {
                            $foto_absen_pulang = '';
                        } else {
                            $foto_absen_pulang = '<a target="_blank" href="https://karyawan.sumberpangan.store/laravel/storage/app/public/' . $row->foto_jam_pulang . '">Foto Absen Pulang' . $row->tanggal_pulang . $row->jam_pulang . '</a>';
                        }
                        return $foto_absen_pulang;
                    })
                    ->addColumn('jam_kerja', function ($row) {
                        $jam_masuk = Shift::where('id', $row->shift_id)->value('jam_masuk');
                        $jam_keluar = Shift::where('id', $row->shift_id)->value('jam_keluar');
                        $jam_kerja = $jam_masuk . ' - ' . $jam_keluar;
                        return $jam_kerja;
                    })
                    ->rawColumns(['jam_kerja', 'foto_jam_absen', 'foto_jam_pulang'])
                    ->make(true);
            }
        }
    }
    public function datatable_harian(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = User::with('Cuti')->with('Izin')->with('Mappingshift')->where('users.kontrak_kerja', $holding)->where('kategori', 'Karyawan Harian')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('total_hadir_tepat_waktu', function ($row) {
                    $jumlah_hadir_tepat_waktu = $row->MappingShift->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TEPAT WAKTU')->count();
                    return $jumlah_hadir_tepat_waktu . " x";
                })
                ->addColumn('total_hadir_telat_hadir', function ($row) {
                    $jumlah_hadir_telat_hadir = $row->MappingShift->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->count();
                    return $jumlah_hadir_telat_hadir . " x";
                })
                ->addColumn('total_izin_true', function ($row) {
                    $total_izin_true = $row->MappingShift->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_izin', 'TRUE')->count();
                    return $total_izin_true  . " x";
                })
                ->addColumn('total_cuti_true', function ($row) {
                    $total_cuti_true = $row->MappingShift->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_cuti', 'TRUE')->count();
                    return $total_cuti_true  . " x";
                })
                ->addColumn('total_dinas_true', function ($row) {
                    $total_dinas_true = $row->MappingShift->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'TRUE')->count();
                    return $total_dinas_true  . " x";
                })
                ->addColumn('total_menit_terlambat', function ($row) {
                    $total = $row->MappingShift->sum('telat');
                    $jam = floor($total / (60));
                    $menit = floor($total - ($jam * (60)));
                    $detik = $total % 60;

                    if ($jam <= 0 && $menit <= 0) {
                        $total_terlambat = '<span class="badge bg-label-success">TIDAK Pernah Telat</span>';
                    } else {
                        $total_terlambat = '<span class="badge bg-label-danger">' . $jam . ' Jam ' . $menit . ' Menit</span>';
                    }
                    return $total_terlambat;
                })
                ->addColumn('total_pulang_cepat', function ($row) {
                    $total = $row->MappingShift->sum('pulang_cepat');
                    $jam = floor($total / (60));
                    $menit = floor($total - ($jam * (60)));
                    $detik = $total % 60;

                    if ($jam <= 0 && $menit <= 0) {
                        $total_pulang_cepat =  '<span class="badge bg-label-success">Tidak Pernah Pulang Cepat</span>';
                    } else {
                        $total_pulang_cepat =   '<span class="badge bg-label-danger">' . $jam . ' Jam ' . $menit . ' Menit</span>';
                    }
                    return $total_pulang_cepat;
                })
                ->addColumn('tidak_hadir_kerja', function ($row) {
                    $tidak_hadir_kerja = $row->MappingShift->where('status_absen', 'TIDAK HADIR KERJA')->where('keterangan_dinas', 'FALSE')->where('keterangan_cuti', 'FALSE')->where('keterangan_izin', 'FALSE')->count() . " x";
                    return $tidak_hadir_kerja;
                })
                ->addColumn('total_semua', function ($row) {
                    $total_hadir = $row->MappingShift->where('status_absen', 'HADIR KERJA')->count();
                    $total_tidak_hadir = $row->MappingShift->where('status_absen', 'TIDAK HADIR KERJA')->count();
                    $total_semua = ($total_hadir + $total_tidak_hadir) . ' x';
                    return $total_semua;
                })
                ->rawColumns(['total_hadir_tepat_waktu', 'total_hadir_telat_hadir', 'total_izin_true', 'total_cuti_true', 'total_dinas_true', 'total_pulang_cepat', 'tidak_hadir_kerja', 'total_semua'])
                ->make(true);
        }
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
    public function ImportAbsensi(Request $request)
    {
        // dd('ok');
        $holding = request()->segment(count(request()->segments()));
        Excel::import(new AbsensiImport, $request->file_excel);

        return redirect('/rekap-data/' . $holding)->with('success', 'Import Karyawan Sukses');
    }

    public function ExportAbsensi($kategori)
    {
        $date = date('YmdHis');
        $holding = request()->segment(count(request()->segments()));
        $data =  Izin::leftJoin('users', 'users.id', 'izins.user_id')
            ->leftJoin('departemens', 'departemens.id', 'izins.departements_id')
            ->leftJoin('divisis', 'divisis.id', 'izins.divisi_id')
            ->leftJoin('jabatans', 'jabatans.id', 'izins.jabatan_id')
            ->where('izins.izin', $kategori)
            ->where('users.kontrak_kerja', $holding)
            // ->select('izins.no_form_izin', 'users.name', 'departemens.nama_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'izins.tanggal', 'izins.jam_masuk_kerja', 'izins.jam', 'izins.terlambat', 'izins.keterangan_izin', 'izins.ttd_pengajuan', 'izins.approve_atasan', 'izins.waktu_approve', 'izins.catatan', 'izins.status_izin')
            ->select('izins.*', 'users.name', 'departemens.nama_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan')
            ->get();
        return Excel::download(new IzinExport($holding, $kategori, $data), 'Data Izin Karyawan_' . $kategori . '_' . $holding . '_' . $date . '.xlsx');
    }
}
