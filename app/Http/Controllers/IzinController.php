<?php

namespace App\Http\Controllers;

use App\Exports\IzinExport;
use App\Models\Cuti;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Izin;
use App\Models\Jabatan;
use App\Models\MappingShift;
use App\Models\Karyawan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class IzinController extends Controller
{
    public function index()
    {
        // dd(date('Y-m-d H:i:s'));
        $holding = request()->segment(count(request()->segments()));
        return view('admin.izin.index', [
            'holding' => $holding,
        ]);
    }
    public function datatable_terlambat(Request $request)
    {
        $cek_holding = request()->segment(count(request()->segments()));
        if ($cek_holding == 'sp') {
            $holding = 'SP';
        } else if ($cek_holding == 'sps') {
            $holding = 'SPS';
        } else {
            $holding = 'SIP';
        }

        if (request()->ajax()) {
            if (!empty($request->filter_month)) {
                $jumlah_hari = explode(' ', $request->filter_month);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1 = date('Y-m-d', strtotime($startDate));
                $date2 = date('Y-m-d', strtotime($endDate));
                // dd($date1, $date2);
                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Datang Terlambat')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->whereBetween('tanggal', [$date1, $date2])
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    // ->orderBy('izins.waktu_ttd_pengajuan', 'ASC')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('tanggal', function ($row) {
                        $get_tanggal = Carbon::parse($row->tanggal)->format('d-m-Y');
                        if ($get_tanggal == NULL) {
                            $tanggal = NULL;
                        } else {
                            $tanggal = $get_tanggal;
                        }
                        return $tanggal;
                    })
                    ->addColumn('jam_masuk_kerja', function ($row) {
                        $get_jam_masuk_kerja = Carbon::parse($row->jam_masuk_kerja)->format('H:i');
                        if ($get_jam_masuk_kerja == NULL) {
                            $jam_masuk_kerja = NULL;
                        } else {
                            $jam_masuk_kerja = $get_jam_masuk_kerja . ' WIB';
                        }
                        return $jam_masuk_kerja;
                    })
                    ->addColumn('jam', function ($row) {
                        $get_jam = Carbon::parse($row->jam)->format('H:i');
                        if ($get_jam == NULL) {
                            $jam = NULL;
                        } else {
                            $jam = $get_jam . ' WIB';
                        }
                        return $jam;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'tanggal', 'jam', 'jam_masuk_kerja', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            } else {
                $now = Carbon::now()->startOfMonth();
                $now1 = Carbon::now()->endOfMonth();

                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Datang Terlambat')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    // ->orderBy('izins.waktu_ttd_pengajuan', 'ASC')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="javascript:void(0);" id="btn_izin_1"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="javascript:void(0);" id="btn_izin_0"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="javascript:void(0);" id="btn_izin_not_approve"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('tanggal', function ($row) {
                        $get_tanggal = Carbon::parse($row->tanggal)->format('d-m-Y');
                        if ($get_tanggal == NULL) {
                            $tanggal = NULL;
                        } else {
                            $tanggal = $get_tanggal;
                        }
                        return $tanggal;
                    })
                    ->addColumn('jam_masuk_kerja', function ($row) {
                        $get_jam_masuk_kerja = Carbon::parse($row->jam_masuk_kerja)->format('H:i');
                        if ($get_jam_masuk_kerja == NULL) {
                            $jam_masuk_kerja = NULL;
                        } else {
                            $jam_masuk_kerja = $get_jam_masuk_kerja . ' WIB';
                        }
                        return $jam_masuk_kerja;
                    })
                    ->addColumn('jam', function ($row) {
                        $get_jam = Carbon::parse($row->jam)->format('H:i');
                        if ($get_jam == NULL) {
                            $jam = NULL;
                        } else {
                            $jam = $get_jam . ' WIB';
                        }
                        return $jam;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'tanggal', 'jam', 'jam_masuk_kerja', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            }
        }
    }
    public function datatable_pulangcepat(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));

        if (request()->ajax()) {
            if (!empty($request->filter_month)) {
                $jumlah_hari = explode(' ', $request->filter_month);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1 = date('Y-m-d', strtotime($startDate));
                $date2 = date('Y-m-d', strtotime($endDate));
                // dd($date1, $date2);
                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Pulang Cepat')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->whereBetween('tanggal', [$date1, $date2])
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('tanggal', function ($row) {
                        $get_tanggal = Carbon::parse($row->tanggal)->format('d-m-Y');
                        if ($get_tanggal == NULL) {
                            $tanggal = NULL;
                        } else {
                            $tanggal = $get_tanggal;
                        }
                        return $tanggal;
                    })
                    ->addColumn('pulang_cepat', function ($row) {
                        $get_pulang_cepat = Carbon::parse($row->pulang_cepat)->format('H:i');
                        if ($get_pulang_cepat == NULL) {
                            $pulang_cepat = NULL;
                        } else {
                            $pulang_cepat = $get_pulang_cepat . ' WIB';
                        }
                        return $pulang_cepat;
                    })

                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'tanggal', 'pulang_cepat', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            } else {
                $now = Carbon::now()->startOfMonth();
                $now1 = Carbon::now()->endOfMonth();

                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Pulang Cepat')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('tanggal', function ($row) {
                        $get_tanggal = Carbon::parse($row->tanggal)->format('d-m-Y');
                        if ($get_tanggal == NULL) {
                            $tanggal = NULL;
                        } else {
                            $tanggal = $get_tanggal;
                        }
                        return $tanggal;
                    })
                    ->addColumn('pulang_cepat', function ($row) {
                        $get_pulang_cepat = Carbon::parse($row->pulang_cepat)->format('H:i');
                        if ($get_pulang_cepat == NULL) {
                            $pulang_cepat = NULL;
                        } else {
                            $pulang_cepat = $get_pulang_cepat . ' WIB';
                        }
                        return $pulang_cepat;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'tanggal', 'pulang_cepat', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            }
        }
    }
    public function datatable_keluar_kantor(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));

        if (request()->ajax()) {
            if (!empty($request->filter_month)) {
                $jumlah_hari = explode(' ', $request->filter_month);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1 = date('Y-m-d', strtotime($startDate));
                $date2 = date('Y-m-d', strtotime($endDate));
                // dd($date1, $date2);
                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Keluar Kantor')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->whereBetween('tanggal', [$date1, $date2])
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('tanggal', function ($row) {
                        $get_tanggal = Carbon::parse($row->tanggal)->format('d-m-Y');
                        if ($get_tanggal == NULL) {
                            $tanggal = NULL;
                        } else {
                            $tanggal = $get_tanggal;
                        }
                        return $tanggal;
                    })
                    ->addColumn('jam_keluar', function ($row) {
                        $get_jam_keluar = Carbon::parse($row->jam_keluar)->format('H:i');
                        if ($get_jam_keluar == NULL) {
                            $jam_keluar = NULL;
                        } else {
                            $jam_keluar = $get_jam_keluar . ' WIB';
                        }
                        return $jam_keluar;
                    })
                    ->addColumn('jam_kembali', function ($row) {
                        $get_jam_kembali = Carbon::parse($row->jam_kembali)->format('H:i');
                        if ($get_jam_kembali == NULL) {
                            $jam_kembali = NULL;
                        } else {
                            $jam_kembali = $get_jam_kembali . ' WIB';
                        }
                        return $jam_kembali;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'tanggal', 'jam_keluar', 'jam_kembali', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            } else {
                $now = Carbon::now()->startOfMonth();
                $now1 = Carbon::now()->endOfMonth();

                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Keluar kantor')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('tanggal', function ($row) {
                        $get_tanggal = Carbon::parse($row->tanggal)->format('d-m-Y');
                        if ($get_tanggal == NULL) {
                            $tanggal = NULL;
                        } else {
                            $tanggal = $get_tanggal;
                        }
                        return $tanggal;
                    })
                    ->addColumn('jam_keluar', function ($row) {
                        $get_jam_keluar = Carbon::parse($row->jam_keluar)->format('H:i');
                        if ($get_jam_keluar == NULL) {
                            $jam_keluar = NULL;
                        } else {
                            $jam_keluar = $get_jam_keluar . ' WIB';
                        }
                        return $jam_keluar;
                    })
                    ->addColumn('jam_kembali', function ($row) {
                        $get_jam_kembali = Carbon::parse($row->jam_kembali)->format('H:i');
                        if ($get_jam_kembali == NULL) {
                            $jam_kembali = NULL;
                        } else {
                            $jam_kembali = $get_jam_kembali . ' WIB';
                        }
                        return $jam_kembali;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'tanggal', 'jam_keluar', 'jam_kembali', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            }
        }
    }
    public function datatable_sakit(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));

        if (request()->ajax()) {
            if (!empty($request->filter_month)) {
                $jumlah_hari = explode(' ', $request->filter_month);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1 = date('Y-m-d', strtotime($startDate));
                $date2 = date('Y-m-d', strtotime($endDate));
                // dd($date1, $date2);
                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Sakit')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->whereBetween('tanggal', [$date1, $date2])
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('foto_izin', function ($row) use ($holding) {
                        if ($row->foto_izin == NULL) {
                            $btn_lihat_foto_izin = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_foto_izin = '<button id="btn_lihat_foto_izin" type="button" data-id="' . $row->id . '" data-foto="' . $row->foto_izin . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;Foto&nbsp;Surat</button>';
                        }
                        return $btn_lihat_foto_izin;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['nama_departemen', 'foto_izin', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            } else {
                $now = Carbon::now()->startOfMonth();
                $now1 = Carbon::now()->endOfMonth();

                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Sakit')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('foto_izin', function ($row) use ($holding) {
                        if ($row->foto_izin == NULL) {
                            $btn_lihat_foto_izin = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_foto_izin = '<button id="btn_lihat_foto_izin" type="button" data-id="' . $row->id . '" data-foto="' . $row->foto_izin . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;Foto&nbsp;Surat</button>';
                        }
                        return $btn_lihat_foto_izin;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['nama_departemen', 'foto_izin', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            }
        }
    }
    public function datatable_tidak_masuk(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));

        if (request()->ajax()) {
            if (!empty($request->filter_month)) {
                $jumlah_hari = explode(' ', $request->filter_month);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1 = date('Y-m-d', strtotime($startDate));
                $date2 = date('Y-m-d', strtotime($endDate));
                // dd($date1, $date2);
                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Tidak Masuk (Mendadak)')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->whereBetween('tanggal', [$date1, $date2])
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('foto_izin', function ($row) use ($holding) {
                        if ($row->foto_izin == NULL) {
                            $btn_lihat_foto_izin = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_foto_izin = '<button id="btn_lihat_foto_izin" type="button" data-id="' . $row->id . '" data-foto="' . $row->foto_izin . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;Foto&nbsp;Surat</button>';
                        }
                        return $btn_lihat_foto_izin;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'nama_departemen', 'foto_izin', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            } else {
                $now = Carbon::now()->startOfMonth();
                $now1 = Carbon::now()->endOfMonth();

                // dd($tgl_mulai, $tgl_selesai);
                $table = Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')->where('izin', 'Tidak Masuk (Mendadak)')
                    ->where('karyawans.kontrak_kerja', $holding)
                    ->select('karyawans.kontrak_kerja', 'izins.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_izin', function ($row) {
                        if ($row->status_izin == 1) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-primary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 2) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 0) {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-secondary">' . $row->no_form_izin . '</h6></a>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<a href="' . url("izin/cetak_form_izin/" . $row->id) . '" target="_blank"><h6 class="text-danger">' . $row->no_form_izin . '</h6></a>';
                        }
                        $no_form_izin = $status;
                        return $no_form_izin;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $departemen = Departemen::where('id', $row->departements_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $divisi = Divisi::where('id', $row->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $jabatan = Jabatan::where('id', $row->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('foto_izin', function ($row) use ($holding) {
                        if ($row->foto_izin == NULL) {
                            $btn_lihat_foto_izin = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_foto_izin = '<button id="btn_lihat_foto_izin" type="button" data-id="' . $row->id . '" data-foto="' . $row->foto_izin . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;Foto&nbsp;Surat</button>';
                        }
                        return $btn_lihat_foto_izin;
                    })
                    ->addColumn('btn_lihat_ttd_pengajuan', function ($row) use ($holding) {
                        if ($row->ttd_pengajuan == NULL) {
                            $btn_lihat_ttd_pengajuan = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_pengajuan = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_pengajuan . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_pengajuan)->format('d m Y') . '" data-nama="' . $row->fullname . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_pengajuan;
                    })
                    ->addColumn('btn_lihat_ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $btn_lihat_ttd_atasan = '<button id="btn_lihat_ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_izin == 0) {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $btn_lihat_ttd_atasan = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        return $btn_lihat_ttd_atasan;
                    })
                    ->addColumn('status_izin', function ($row) use ($holding) {
                        if ($row->status_izin == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve</span>';
                        } else if ($row->status_izin == 2) {
                            $status = '<span class="badge bg-label-success">Izin Disetujui</span>';
                        } else if ($row->status_izin == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Izin</span>';
                        } else if ($row->status_izin == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Izin Ditolak</span>';
                        }
                        $status_izin = $status;
                        return $status_izin;
                    })
                    ->rawColumns(['no_form_izin', 'nama_departemen', 'foto_izin', 'nama_divisi', 'nama_jabatan', 'btn_lihat_ttd_pengajuan', 'btn_lihat_ttd_atasan', 'status_izin'])
                    ->make(true);
            }
        }
    }
    public function cetak_form_izin($id)
    {
        // dd('p');
        $izin = Izin::where('id', $id)->first();
        $jabatan = Jabatan::join('karyawans', function ($join) {
            $join->on('jabatans.id', '=', 'karyawans.jabatan_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan4_id');
        })->where('karyawans.id', $izin->user_id)->get();
        $divisi = Divisi::join('karyawans', function ($join) {
            $join->on('divisis.id', '=', 'karyawans.divisi_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi1_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi2_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi3_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi4_id');
        })->where('karyawans.id', $izin->user_id)->get();
        $date1          = new DateTime($izin->tanggal);
        $date2          = new DateTime($izin->tanggal_selesai);
        $interval       = $date1->diff($date2);
        $data_interval  = $interval->days + 1;
        // dd($data_interval);
        $departemen = Departemen::where('id', $izin->departements_id)->first();
        $user_backup = Karyawan::where('id', $izin->user_id_backup)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $izin->user_id)->where('tanggal_masuk', date('Y-m-d'))->first();
        $data = [
            'data_izin' => Izin::with('User')->where('izins.id', $id)->where('izins.status_izin', '2')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'jam_kerja' => $jam_kerja,
            'user_backup' => $user_backup,
            'data_interval' => $data_interval,
        ];
        // dd($data);
        if ($izin->izin == 'Datang Terlambat') {
            $pdf = PDF::loadView('admin/izin/form_izin_terlambat', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_KETERANGAN_DATANG_TERLAMBAT_' . $izin->fullname . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Tidak Masuk (Mendadak)') {
            $pdf = PDF::loadView('admin/izin/form_izin_tidak_masuk', $data);
            return $pdf->stream('FORM_PENGAJUAN_IZIN_TIDAK_MASUK_' . $izin->fullname . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Pulang Cepat') {
            $pdf = PDF::loadView('admin/izin/form_izin_pulang_cepat', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_PENGAJUAN_IZIN_PULANG_CEPAT_' . $izin->fullname . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Keluar Kantor') {
            $pdf = PDF::loadView('admin/izin/form_izin_keluar', $data)->setPaper('A5', 'landscape');
            return $pdf->stream('FORM_PENGAJUAN_IZIN_KELUAR_KANTOR_' . $izin->fullname . '_' . date('Y-m-d H:i:s') . '.pdf');
        }
    }
    public function ExportIzin($kategori)
    {
        $date = date('YmdHis');
        $holding = request()->segment(count(request()->segments()));
        $data =  Izin::leftJoin('karyawans', 'karyawans.id', 'izins.user_id')
            ->leftJoin('departemens', 'departemens.id', 'izins.departements_id')
            ->leftJoin('divisis', 'divisis.id', 'izins.divisi_id')
            ->leftJoin('jabatans', 'jabatans.id', 'izins.jabatan_id')
            ->where('izins.izin', $kategori)
            ->where('karyawans.kontrak_kerja', $holding)
            // ->select('izins.no_form_izin', 'karyawans.name', 'departemens.nama_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'izins.tanggal', 'izins.jam_masuk_kerja', 'izins.jam', 'izins.terlambat', 'izins.keterangan_izin', 'izins.ttd_pengajuan', 'izins.approve_atasan', 'izins.waktu_approve', 'izins.catatan', 'izins.status_izin')
            ->select('izins.*', 'karyawans.name', 'departemens.nama_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan')
            ->get();
        return Excel::download(new IzinExport($holding, $kategori, $data), 'Data Izin Karyawan_' . $kategori . '_' . $holding . '_' . $date . '.xlsx');
    }
}
