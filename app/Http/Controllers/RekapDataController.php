<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use App\Models\Bagian;
use App\Models\Cuti;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Izin;
use App\Models\Jabatan;
use App\Models\Lembur;
use App\Models\User;
use App\Models\MappingShift;
use App\Models\Shift;
use App\Models\Titik;
use Carbon\Carbon;
use DateTime;
use PDF;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = User::with('Cuti')->with('Izin')->where('status_aktif', 'AKTIF')->get();
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

        $user = User::where('id', $id)->where('status_aktif', 'AKTIF')->first();
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
                                ->where('status_aktif', 'AKTIF')
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
                                ->where('status_aktif', 'AKTIF')
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
                            ->where('status_aktif', 'AKTIF')
                            ->get();
                    }
                } else {
                    $table = User::with('Cuti')
                        ->with('Izin')
                        ->with('Mappingshift')
                        ->where('dept_id', $request->departemen_filter)
                        ->where('kontrak_kerja', $holding)
                        ->where('kategori', 'Karyawan Bulanan')
                        ->where('status_aktif', 'AKTIF')
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
                        $jumlah_hadir_telat_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('telat', '<', '00:10:59')->count();
                        return $jumlah_hadir_telat_hadir . " x";
                    })
                    ->addColumn('total_hadir_telat_hadir1', function ($row) use ($date1, $date2) {
                        $total_hadir_telat_hadir1 = $row->MappingShift->whereBetween('tanggal_masuk', [$date1, $date2])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('telat', '>', '00:10:59')->count();
                        return $total_hadir_telat_hadir1 . " x";
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
                    ->rawColumns(['total_hadir_tepat_waktu', 'btn_detail', 'total_hadir_telat_hadir', 'total_hadir_telat_hadir1', 'total_izin_true', 'total_cuti_true', 'total_dinas_true', 'total_pulang_cepat', 'tidak_hadir_kerja', 'total_semua'])
                    ->make(true);
            } else {
                $now = Carbon::parse($request->filter_month)->startOfMonth();
                $now1 = Carbon::parse($request->filter_month)->endOfMonth();
                // dd($now1);
                // dd($tgl_mulai, $tgl_selesai);
                $table = User::with('Mappingshift')
                    ->where('kontrak_kerja', $holding)
                    ->where('kategori', 'Karyawan Bulanan')
                    ->where('status_aktif', 'AKTIF')
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
                        $jumlah_hadir_telat_hadir = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('telat', '<', '00:10:59')->count();
                        return $jumlah_hadir_telat_hadir . " x";
                    })
                    ->addColumn('total_hadir_telat_hadir1', function ($row) use ($now, $now1) {
                        $total_hadir_telat_hadir1 = $row->MappingShift->whereBetween('tanggal_masuk', [$now, $now1])->where('status_absen', 'HADIR KERJA')->where('keterangan_absensi', 'TELAT HADIR')->where('telat', '>', '00:10:59')->count();
                        return $total_hadir_telat_hadir1 . " x";
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
                    ->rawColumns(['total_hadir_tepat_waktu', 'btn_detail', 'total_hadir_telat_hadir', 'total_hadir_telat_hadir1', 'total_izin_true', 'total_cuti_true', 'total_dinas_true', 'total_pulang_cepat', 'tidak_hadir_kerja', 'total_semua'])
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
                            $foto_absen_masuk = '<a type="button" class="btn btn-sm btn-success" target="_blank" href="http://127.0.0.1:8000/storage/app/public/' . $row->foto_jam_absen . '">LIHAT</a>';
                        }
                        return $foto_absen_masuk;
                    })
                    ->addColumn('foto_jam_pulang', function ($row) {
                        if ($row->foto_jam_pulang == '') {
                            $foto_absen_pulang = '';
                        } else {
                            $foto_absen_pulang = '<a type="button" class="btn btn-sm btn-success" target="_blank" href="http://127.0.0.1:8000/storage/app/public/' . $row->foto_jam_pulang . '">LIHAT</a>';
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
                    ->addColumn('file_form', function ($row) {
                        if ($row->keterangan_izin == 'TRUE') {
                            $file_form = '<a type="button" class="btn btn-sm btn-info" target="_blank" href="' . url('rekapdata/cetak_form_izin', ['id' => $row->id]) . '">Download Form</a>';
                        } else if ($row->keterangan_cuti == 'TRUE') {
                            $file_form = '<a type="button" class="btn btn-sm btn-info" target="_blank" href="' . url('rekapdata/cetak_form_cuti', ['id' => $row->id]) . '">Download Form</a>';
                        } else if ($row->keterangan_dinas == 'TRUE') {
                            $file_form = '<a type="button" class="btn btn-sm btn-info" target="_blank" href="' . url('rekapdata/cetak_form_penugasan', ['id' => $row->id]) . '">Download Form</a>';
                        } else {
                            $file_form = NULL;
                        }
                        return $file_form;
                    })
                    ->addColumn('keterangan_izin', function ($row) {
                        if ($row->Izin == NULL) {
                            $keterangan_izin = NULL;
                        } else {
                            if ($row->Izin->izin == 'Datang Terlambat') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN DATANG TERLAMBAT&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN DATANG TERLAMBAT&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN DATANG TERLAMBAT&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Sakit') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN SAKIT&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN SAKIT&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN SAKIT&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Tidak Masuk (Mendadak)') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN TIDAK MASUK&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN TIDAK MASUK&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN TIDAK MASUK&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Pulang Cepat') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN PULANG CEPAT&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN PULANG CEPAT&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN PULANG CEPAT&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Keluar Kantor') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN KELUAR KANTOR&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN KELUAR KANTOR&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN KELUAR KANTOR&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            }
                        }
                        return $keterangan_izin;
                    })
                    ->addColumn('keterangan_cuti', function ($row) {
                        if ($row->Cuti == NULL) {
                            $keterangan_cuti = NULL;
                        } else {
                            if ($row->Cuti->nama_cuti == 'Diluar Cuti Tahunan') {
                                if ($row->keterangan_cuti == 'TRUE') {
                                    $keterangan_cuti = $row->Cuti->KategoriCuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_cuti == 'FALSE') {
                                    $keterangan_cuti = $row->Cuti->KategoriCuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_cuti = $row->Cuti->KategoriCuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Cuti->nama_cuti == 'Cuti Tahunan') {
                                if ($row->keterangan_cuti == 'TRUE') {
                                    $keterangan_cuti = $row->Cuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_cuti == 'FALSE') {
                                    $keterangan_cuti = $row->Cuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_cuti = $row->Cuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            }
                        }
                        return $keterangan_cuti;
                    })
                    ->addColumn('lokasi_absen', function ($row) {
                        $lokasi = Titik::Join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('titiks.id', $row->lokasi_absen)->first();
                        if ($lokasi == NULL) {
                            $lokasi_absen = '-';
                        } else {
                            $lokasi_absen = $lokasi->lokasi_kantor . '&nbsp;<br><span class="badge bg-label-primary">' . $lokasi->nama_titik . '</span>';
                        }

                        return $lokasi_absen;
                    })
                    ->addColumn('lokasi_absen_pulang', function ($row) {
                        $lokasi = Titik::Join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('titiks.id', $row->lokasi_absen_pulang)->first();
                        if ($lokasi == NULL) {
                            $lokasi_absen_pulang = '-';
                        } else {
                            $lokasi_absen_pulang = $lokasi->lokasi_kantor . '&nbsp;<br><span class="badge bg-label-primary">' . $lokasi->nama_titik . '</span>';
                        }

                        return $lokasi_absen_pulang;
                    })
                    ->rawColumns(['jam_kerja', 'foto_jam_absen', 'lokasi_absen', 'lokasi_absen_pulang', 'keterangan_izin', 'keterangan_cuti', 'file_form', 'foto_jam_pulang'])
                    ->make(true);
            } else {
                $month = date('m');
                $table = MappingShift::With('Cuti')->with('Izin')->where('user_id', $id)
                    // ->whereDate('tanggal_masuk', '2024-09-19')
                    ->whereMonth('tanggal_masuk', $month)
                    ->get();
                // dd($table);
                return DataTables::of($table)
                    ->addColumn('foto_jam_absen', function ($row) {
                        if ($row->foto_jam_absen == '') {
                            $foto_absen_masuk = '';
                        } else {
                            $foto_absen_masuk = '<a type="button" class="btn btn-sm btn-success" target="_blank" href="http://127.0.0.1:8000/storage/app/public/' . $row->foto_jam_absen . '">LIHAT</a>';
                        }
                        return $foto_absen_masuk;
                    })
                    ->addColumn('foto_jam_pulang', function ($row) {
                        if ($row->foto_jam_pulang == '') {
                            $foto_absen_pulang = '';
                        } else {
                            $foto_absen_pulang = '<a type="button" class="btn btn-sm btn-success" target="_blank" href="http://127.0.0.1:8000/storage/app/public/' . $row->foto_jam_pulang . '">LIHAT</a>';
                        }
                        return $foto_absen_pulang;
                    })
                    ->addColumn('file_form', function ($row) {
                        if ($row->keterangan_izin == 'TRUE') {
                            $file_form = '<a type="button" class="btn btn-sm btn-info" target="_blank" href="' . url('rekapdata/cetak_form_izin', ['id' => $row->id]) . '">Download Form</a>';
                        } else if ($row->keterangan_cuti == 'TRUE') {
                            $file_form = '<a type="button" class="btn btn-sm btn-info" target="_blank" href="' . url('rekapdata/cetak_form_cuti', ['id' => $row->id]) . '">Download Form</a>';
                        } else if ($row->keterangan_dinas == 'TRUE') {
                            $file_form = '<a type="button" class="btn btn-sm btn-info" target="_blank" href="' . url('rekapdata/cetak_form_penugasan', ['id' => $row->id]) . '">Download Form</a>';
                        } else {
                            $file_form = NULL;
                        }
                        return $file_form;
                    })
                    ->addColumn('keterangan_izin', function ($row) {
                        if ($row->Izin == NULL) {
                            $keterangan_izin = NULL;
                        } else {
                            if ($row->Izin->izin == 'Datang Terlambat') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN DATANG TERLAMBAT&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN DATANG TERLAMBAT&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN DATANG TERLAMBAT&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Sakit') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN SAKIT&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN SAKIT&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN SAKIT&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Tidak Masuk (Mendadak)') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN TIDAK MASUK&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN TIDAK MASUK&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN TIDAK MASUK&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Pulang Cepat') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN PULANG CEPAT&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN PULANG CEPAT&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN PULANG CEPAT&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Izin->izin == 'Keluar Kantor') {
                                if ($row->keterangan_izin == 'TRUE') {
                                    $keterangan_izin = 'IZIN KELUAR KANTOR&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_izin == 'FALSE') {
                                    $keterangan_izin = 'IZIN KELUAR KANTOR&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_izin = 'IZIN KELUAR KANTOR&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            }
                        }
                        return $keterangan_izin;
                    })
                    ->addColumn('keterangan_cuti', function ($row) {
                        if ($row->Cuti == NULL) {
                            $keterangan_cuti = NULL;
                        } else {
                            if ($row->Cuti->nama_cuti == 'Diluar Cuti Tahunan') {
                                if ($row->keterangan_cuti == 'TRUE') {
                                    $keterangan_cuti = $row->Cuti->KategoriCuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_cuti == 'FALSE') {
                                    $keterangan_cuti = $row->Cuti->KategoriCuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_cuti = $row->Cuti->KategoriCuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            } else if ($row->Cuti->nama_cuti == 'Cuti Tahunan') {
                                if ($row->keterangan_cuti == 'TRUE') {
                                    $keterangan_cuti = $row->Cuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">APPROVED</span>';
                                } else if ($row->keterangan_cuti == 'FALSE') {
                                    $keterangan_cuti = $row->Cuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-success">NOT APPROVE</span>';
                                } else {
                                    $keterangan_cuti = $row->Cuti->nama_cuti . '&nbsp;<br><span class="badge bg-label-primary">-</span>';
                                }
                            }
                        }
                        return $keterangan_cuti;
                    })
                    ->addColumn('lokasi_absen', function ($row) {
                        $lokasi = Titik::Join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('titiks.id', $row->lokasi_absen)->first();
                        if ($lokasi == NULL) {
                            $lokasi_absen = '-';
                        } else {
                            $lokasi_absen = $lokasi->lokasi_kantor . '&nbsp;<br><span class="badge bg-label-primary">' . $lokasi->nama_titik . '</span>';
                        }

                        return $lokasi_absen;
                    })
                    ->addColumn('lokasi_absen_pulang', function ($row) {
                        $lokasi = Titik::Join('lokasis', 'lokasis.id', 'titiks.lokasi_id')->where('titiks.id', $row->lokasi_absen_pulang)->first();
                        if ($lokasi == NULL) {
                            $lokasi_absen_pulang = '-';
                        } else {
                            $lokasi_absen_pulang = $lokasi->lokasi_kantor . '&nbsp;<br><span class="badge bg-label-primary">' . $lokasi->nama_titik . '</span>';
                        }

                        return $lokasi_absen_pulang;
                    })
                    ->addColumn('jam_kerja', function ($row) {
                        $jam_masuk = Shift::where('id', $row->shift_id)->value('jam_masuk');
                        $jam_keluar = Shift::where('id', $row->shift_id)->value('jam_keluar');
                        $jam_kerja = $jam_masuk . ' - ' . $jam_keluar;
                        return $jam_kerja;
                    })
                    ->rawColumns(['jam_kerja', 'foto_jam_absen', 'file_form', 'foto_jam_pulang', 'lokasi_absen', 'lokasi_absen_pulang', 'keterangan_cuti', 'keterangan_izin'])
                    ->make(true);
            }
        }
    }
    public function datatable_harian(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table = User::with('Cuti')->with('Izin')->with('Mappingshift')->where('users.kontrak_kerja', $holding)->where('kategori', 'Karyawan Harian')->where('status_aktif', 'AKTIF')->get();
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
    public function cetak_form_izin($id)
    {
        $mapping_shift = MappingShift::where('id', $id)->first();
        $jabatan = Jabatan::join('users', function ($join) {
            $join->on('jabatans.id', '=', 'users.jabatan_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
        })->where('users.id', $mapping_shift->user_id)->get();
        $divisi = Divisi::join('users', function ($join) {
            $join->on('divisis.id', '=', 'users.divisi_id');
            $join->orOn('divisis.id', '=', 'users.divisi1_id');
            $join->orOn('divisis.id', '=', 'users.divisi2_id');
            $join->orOn('divisis.id', '=', 'users.divisi3_id');
            $join->orOn('divisis.id', '=', 'users.divisi4_id');
        })->where('users.id', $mapping_shift->user_id)->get();
        $izin = Izin::where('id', $mapping_shift->izin_id)->first();
        $date1          = new DateTime($izin->tanggal);
        $date2          = new DateTime($izin->tanggal_selesai);
        $interval       = $date1->diff($date2);
        $data_interval  = $interval->days;
        // dd($data_interval);
        $departemen = Departemen::where('id', $izin->departements_id)->first();
        $user_backup = User::where('id', $izin->user_id_backup)->first();
        // dd(Izin::with('User')->where('izins.id', $mapping_shift->izin_id)->where('izins.status_izin', '2')->first());
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $izin->user_id)->where('tanggal_masuk', date('Y-m-d'))->first();
        $data = [
            'data_izin' => Izin::with('User')->where('izins.id', $mapping_shift->izin_id)->where('izins.status_izin', '2')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'jam_kerja' => $jam_kerja,
            'user_backup' => $user_backup,
            'data_interval' => $data_interval,
        ];
        if ($izin->izin == 'Datang Terlambat') {
            $pdf = PDF::loadView('admin/rekapdata/form_izin_terlambat', $data)->setPaper('A5', 'landscape');
            return $pdf->download('FORM_KETERANGAN_DATANG_TERLAMBAT_' . $mapping_shift->nama_karyawan . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Tidak Masuk (Mendadak)') {
            $pdf = PDF::loadView('admin/rekapdata/form_izin_tidak_masuk', $data);
            return $pdf->stream('FORM_PENGAJUAN_IZIN_TIDAK_MASUK_' . $mapping_shift->nama_karyawan . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Pulang Cepat') {
            $pdf = PDF::loadView('admin/rekapdata/form_izin_pulang_cepat', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_PENGAJUAN_IZIN_PULANG_CEPAT_' . $mapping_shift->nama_karyawan . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Keluar Kantor') {
            $pdf = PDF::loadView('admin/rekapdata/form_izin_keluar', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_PENGAJUAN_IZIN_KELUAR_KANTOR_' . $mapping_shift->nama_karyawan . '_' . date('Y-m-d H:i:s') . '.pdf');
        }
    }
    public function cetak_form_cuti($id)
    {
        $mapping_shift = MappingShift::where('id', $id)->first();
        $jabatan = Jabatan::join('users', function ($join) {
            $join->on('jabatans.id', '=', 'users.jabatan_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
        })->where('users.id', $mapping_shift->user_id)->get();
        $divisi = Divisi::join('users', function ($join) {
            $join->on('divisis.id', '=', 'users.divisi_id');
            $join->orOn('divisis.id', '=', 'users.divisi1_id');
            $join->orOn('divisis.id', '=', 'users.divisi2_id');
            $join->orOn('divisis.id', '=', 'users.divisi3_id');
            $join->orOn('divisis.id', '=', 'users.divisi4_id');
        })->where('users.id', $mapping_shift->user_id)->get();
        $cuti = Cuti::Join('users', 'cutis.user_id', 'users.id')->where('cutis.id', $mapping_shift->cuti_id)->first();
        $departemen = Departemen::where('id', $cuti->dept_id)->first();
        $pengganti = User::where('id', $cuti->user_id_backup)->first();
        // dd(Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first());
        $data = [
            'title' => 'domPDF in Laravel 10',
            'data_cuti' => Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $mapping_shift->cuti_id)->where('cutis.status_cuti', '3')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'pengganti' => $pengganti,
        ];
        $pdf = PDF::loadView('admin/rekapdata/form_cuti', $data);
        return $pdf->download('FORM_PENGAJUAN_CUTI_' . $mapping_shift->nama_karyawan . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function cetak_form_penugasan($id)
    {
        $jabatan = Jabatan::join('users', function ($join) {
            $join->on('jabatans.id', '=', 'users.jabatan_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
        })->where('users.id', Auth::user()->id)->get();
        $divisi = Divisi::join('users', function ($join) {
            $join->on('divisis.id', '=', 'users.divisi_id');
            $join->orOn('divisis.id', '=', 'users.divisi1_id');
            $join->orOn('divisis.id', '=', 'users.divisi2_id');
            $join->orOn('divisis.id', '=', 'users.divisi3_id');
            $join->orOn('divisis.id', '=', 'users.divisi4_id');
        })->where('users.id', Auth::user()->id)->get();
        $cuti = Cuti::where('id', $id)->first();
        $departemen = Departemen::where('id', Auth::user()->dept_id)->first();
        $pengganti = User::where('id', $cuti->user_id_backup)->first();
        // dd(Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first());
        $data = [
            'title' => 'domPDF in Laravel 10',
            'data_cuti' => Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'pengganti' => $pengganti,
        ];
        $pdf = PDF::loadView('users/cuti/form_cuti', $data);
        return $pdf->download('FORM_PENGAJUAN_CUTI_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
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
