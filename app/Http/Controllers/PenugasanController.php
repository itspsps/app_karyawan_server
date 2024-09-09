<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Departemen;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\KategoriCuti;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Carbon\CarbonPeriod;
use DateTime;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PenugasanController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.penugasan.index', [
            'holding' => $holding,
        ]);
    }
    public function datatable_penugasan(Request $request)
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
                $table = Cuti::leftJoin('users', 'users.id', 'cutis.user_id')->where('nama_cuti', 'Cuti Tahunan')
                    ->where('users.kontrak_kerja', $holding)
                    ->whereBetween('tanggal', [$date1, $date2])
                    ->select('users.kontrak_kerja', 'cutis.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_cuti', function ($row) {
                        if ($row->status_cuti == 1) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_1"><h6 class="text-primary">' . $row->no_form_cuti . '</h6></a>';
                        } else if ($row->status_cuti == 3) {
                            $status = '<a href="' . url("cuti/cetak_form_cuti/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_cuti . '</h6></a>';
                        } else if ($row->status_cuti == 0) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_0"><h6 class="text-secondary">' . $row->no_form_cuti . '</h6></a>';
                        } else if ($row->status_cuti == 2) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_2"><h6 class="text-secondary">' . $row->no_form_cuti . '</h6></a>';
                        } else if ($row->status_cuti == 'NOT APPROVE') {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_not_approve"><h6 class="text-danger">' . $row->no_form_cuti . '</h6></a>';
                        }
                        $no_form_cuti = $status;
                        return $no_form_cuti;
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
                    ->addColumn('tanggal_mulai', function ($row) {
                        $get_tanggal_mulai = Carbon::parse($row->tanggal_mulai)->format('d-m-Y');
                        if ($get_tanggal_mulai == NULL) {
                            $tanggal_mulai = NULL;
                        } else {
                            $tanggal_mulai = $get_tanggal_mulai;
                        }
                        return $tanggal_mulai;
                    })
                    ->addColumn('tanggal_selesai', function ($row) {
                        $get_tanggal_selesai = Carbon::parse($row->tanggal_selesai)->format('d-m-Y');
                        if ($get_tanggal_selesai == NULL) {
                            $tanggal_selesai = NULL;
                        } else {
                            $tanggal_selesai = $get_tanggal_selesai;
                        }
                        return $tanggal_selesai;
                    })
                    ->addColumn('tanggal_masuk', function ($row) {
                        $get_tanggal_masuk = Carbon::parse($row->tanggal_selesai)->addDays(1)->format('d-m-Y');
                        if ($get_tanggal_masuk == NULL) {
                            $tanggal_masuk = NULL;
                        } else {
                            $tanggal_masuk = $get_tanggal_masuk;
                        }
                        return $tanggal_masuk;
                    })
                    ->addColumn('total_cuti', function ($row) {
                        if ($row->total_cuti == NULL) {
                            $total_cuti = NULL;
                        } else {
                            $total_cuti = $row->total_cuti . ' Hari';
                        }
                        return $total_cuti;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $user = User::where('id', $row->user_id)->first();
                        $departemen = Departemen::where('id', $user->dept_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $user = User::where('id', $row->user_id)->first();
                        $divisi = Divisi::where('id', $user->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $user = User::where('id', $row->user_id)->first();
                        $jabatan = Jabatan::where('id', $user->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('ttd_user', function ($row) use ($holding) {
                        if ($row->ttd_user == NULL) {
                            $btn_lihat_ttd_user = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_user = '<button id="btn_lihat_ttd_user" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_user . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_user)->format('d m Y') . '" data-nama="' . $row->nama_user . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_user;
                    })

                    ->addColumn('ttd_atasan', function ($row) use ($holding) {
                        if ($row->status_cuti == 1) {
                            $ttd_atasan = '<span class="badge bg-label-primary">Menunggu Approve Atasan 1</span>';
                        } else if ($row->status_cuti == 3) {
                            $ttd_atasan = '<button id="ttd_atasan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan . '" data-tgl = "' . Carbon::parse($row->waktu_approve)->format('d m Y') . '" data-nama="' . $row->approve_atasan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_cuti == 0) {
                            $ttd_atasan = '<span class="badge bg-label-secondary">Pengajuan Cuti</span>';
                        } else if ($row->status_cuti == 2) {
                            $ttd_atasan = '<span class="badge bg-label-secondary">Menunggu Approve Atasan 2</span>';
                        } else if ($row->status_cuti == 'NOT APPROVE') {
                            $ttd_atasan = '<span class="badge bg-label-danger">Cuti Ditolak</span>';
                        }
                        return $ttd_atasan;
                    })
                    ->addColumn('ttd_atasan2', function ($row) use ($holding) {
                        if ($row->status_cuti == 1) {
                            $ttd_atasan2 = '<span class="badge bg-label-primary">Menunggu Approve Atasan 1</span>';
                        } else if ($row->status_cuti == 3) {
                            $ttd_atasan2 = '<button id="ttd_atasan2" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_atasan2 . '" data-tgl = "' . Carbon::parse($row->waktu_approve2)->format('d m Y') . '" data-nama="' . $row->approve_atasan2 . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Atasan</button>';
                        } else if ($row->status_cuti == 0) {
                            $ttd_atasan2 = '<span class="badge bg-label-secondary">Pengajuan Cuti</span>';
                        } else if ($row->status_cuti == 2) {
                            $ttd_atasan2 = '<span class="badge bg-label-secondary">Menuggu Approve Atasan 2</span>';
                        } else if ($row->status_cuti == 'NOT APPROVE') {
                            $ttd_atasan2 = '<span class="badge bg-label-danger">Cuti Ditolak</span>';
                        }
                        return $ttd_atasan2;
                    })
                    ->addColumn('waktu_approve', function ($row) {
                        if ($row->waktu_approve == NULL) {
                            $waktu_approve = NULL;
                        } else {
                            $waktu_approve = Carbon::parse($row->waktu_approve)->format('d-m-Y H:i');;
                        }
                        return $waktu_approve;
                    })
                    ->addColumn('waktu_approve2', function ($row) {
                        if ($row->waktu_approve2 == NULL) {
                            $waktu_approve2 = NULL;
                        } else {
                            $waktu_approve2 = Carbon::parse($row->waktu_approve2)->format('d-m-Y H:i');;
                        }
                        return $waktu_approve2;
                    })
                    ->addColumn('status_cuti', function ($row) use ($holding) {
                        if ($row->status_cuti == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve Atasan 1</span>';
                        } else if ($row->status_cuti == 3) {
                            $status = '<span class="badge bg-label-success">Cuti Disetujui</span>';
                        } else if ($row->status_cuti == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Cuti</span>';
                        } else if ($row->status_cuti == 2) {
                            $status = '<span class="badge bg-label-secondary">Menunggu Approve Atasan 2</span>';
                        } else if ($row->status_cuti == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Cuti Ditolak</span>';
                        }
                        $status_cuti = $status;
                        return $status_cuti;
                    })
                    ->rawColumns(['no_form_cuti', 'tanggal', 'total_cuti', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_masuk', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'ttd_user', 'ttd_atasan', 'ttd_atasan2', 'waktu_approve', 'waktu_approve2', 'status_cuti'])
                    ->make(true);
            } else {
                $now = Carbon::now()->startOfMonth();
                $now1 = Carbon::now()->endOfMonth();

                // dd($tgl_mulai, $tgl_selesai);
                $table = Penugasan::leftJoin('users', 'users.id', 'penugasans.id_diajukan_oleh')
                    ->where('users.kontrak_kerja', $holding)
                    ->select('users.kontrak_kerja', 'penugasans.*')
                    ->get();
                // dd($table);
                return DataTables::of($table)

                    ->addColumn('no_form_penugasan', function ($row) {
                        if ($row->status_penugasan == 0) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_0"><h6 class="text-secondary">' . $row->no_form_penugasan . '</h6></a>';
                        } else if ($row->status_penugasan == 1) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_1"><h6 class="text-warning">' . $row->no_form_penugasan . '</h6></a>';
                        } else if ($row->status_penugasan == 2) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_2"><h6 class="text-secondary">' . $row->no_form_penugasan . '</h6></a>';
                        } else if ($row->status_penugasan == 3) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_2"><h6 class="text-info">' . $row->no_form_penugasan . '</h6></a>';
                        } else if ($row->status_penugasan == 4) {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_2"><h6 class="text-primary">' . $row->no_form_penugasan . '</h6></a>';
                        } else if ($row->status_penugasan == 5) {
                            $status = '<a href="' . url("penugasan/cetak_form_penugasan/" . $row->id) . '" target="_blank"><h6 class="text-success">' . $row->no_form_penugasan . '</h6></a>';
                        } else if ($row->status_penugasan == 'NOT APPROVE') {
                            $status = '<a href="javascript:void(0);" id="btn_cuti_not_approve"><h6 class="text-danger">' . $row->no_form_penugasan . '</h6></a>';
                        }
                        $no_form_penugasan = $status;
                        return $no_form_penugasan;
                    })
                    ->addColumn('tanggal_pengajuan', function ($row) {
                        $get_tanggal_pengajuan = Carbon::parse($row->tanggal_pengajuan)->format('d-m-Y');
                        if ($get_tanggal_pengajuan == NULL) {
                            $tanggal_pengajuan = NULL;
                        } else {
                            $tanggal_pengajuan = $get_tanggal_pengajuan;
                        }
                        return $tanggal_pengajuan;
                    })
                    ->addColumn('tanggal_kunjungan', function ($row) {
                        $get_tanggal_kunjungan = Carbon::parse($row->tanggal_kunjungan)->format('d-m-Y');
                        if ($get_tanggal_kunjungan == NULL) {
                            $tanggal_kunjungan = NULL;
                        } else {
                            $tanggal_kunjungan = $get_tanggal_kunjungan;
                        }
                        return $tanggal_kunjungan;
                    })
                    ->addColumn('selesai_kunjungan', function ($row) {
                        $get_selesai_kunjungan = Carbon::parse($row->selesai_kunjungan)->format('d-m-Y');
                        if ($get_selesai_kunjungan == NULL) {
                            $selesai_kunjungan = NULL;
                        } else {
                            $selesai_kunjungan = $get_selesai_kunjungan;
                        }
                        return $selesai_kunjungan;
                    })
                    ->addColumn('nama_departemen', function ($row) {
                        $user = User::where('id', $row->id_diajukan_oleh)->first();
                        $departemen = Departemen::where('id', $user->dept_id)->first();
                        if ($departemen == NULL) {
                            $nama_departemen = NULL;
                        } else {
                            $nama_departemen = $departemen->nama_departemen;
                        }
                        return $nama_departemen;
                    })
                    ->addColumn('nama_divisi', function ($row) {
                        $user = User::where('id', $row->id_diajukan_oleh)->first();
                        $divisi = Divisi::where('id', $user->divisi_id)->first();
                        if ($divisi == NULL) {
                            $nama_divisi = NULL;
                        } else {
                            $nama_divisi = $divisi->nama_divisi;
                        }
                        return $nama_divisi;
                    })
                    ->addColumn('nama_jabatan', function ($row) {
                        $user = User::where('id', $row->id_diajukan_oleh)->first();
                        $jabatan = Jabatan::where('id', $user->jabatan_id)->first();
                        if ($jabatan == NULL) {
                            $nama_jabatan = NULL;
                        } else {
                            $nama_jabatan = $jabatan->nama_jabatan;
                        }
                        return $nama_jabatan;
                    })
                    ->addColumn('ttd_user', function ($row) use ($holding) {
                        if ($row->ttd_id_diajukan_oleh == NULL) {
                            $btn_lihat_ttd_user = '<span class="badge bg-label-danger">KOSONG</span>';
                        } else {
                            $btn_lihat_ttd_user = '<button id="btn_lihat_ttd_pengajuan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_diajukan_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_diajukan_oleh)->format('d m Y') . '" data-nama="' . $row->nama_diajukan . '" class="btn btn-sm btn-info"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD</button>';
                        }
                        return $btn_lihat_ttd_user;
                    })
                    ->addColumn('ttd_diminta', function ($row) use ($holding) {
                        if ($row->status_penugasan == 0) {
                            $ttd_diminta = '<span class="badge bg-label-secondary">Pengajuan Perjalanan Dinas</span>';
                        } else if ($row->status_penugasan == 1) {
                            $ttd_diminta = '<span class="badge bg-label-primary">Menunggu Approve Diminta</span>';
                        } else if ($row->status_penugasan == 2) {
                            $ttd_diminta = '<button id="btn_lihat_ttd_diminta" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_diminta_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_diminta_oleh)->format('d m Y') . '" data-nama="' . $row->nama_diminta . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Diminta</button>';
                        } else if ($row->status_penugasan == 3) {
                            $ttd_diminta = '<button id="btn_lihat_ttd_diminta" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_diminta_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_diminta_oleh)->format('d m Y') . '" data-nama="' . $row->nama_diminta . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Diminta</button>';
                        } else if ($row->status_penugasan == 4) {
                            $ttd_diminta = '<button id="btn_lihat_ttd_diminta" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_diminta_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_diminta_oleh)->format('d m Y') . '" data-nama="' . $row->nama_diminta . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Diminta</button>';
                        } else if ($row->status_penugasan == 5) {
                            $ttd_diminta = '<button id="btn_lihat_ttd_diminta" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_diminta_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_diminta_oleh)->format('d m Y') . '" data-nama="' . $row->nama_diminta . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Diminta</button>';
                        } else if ($row->status_penugasan == 'NOT APPROVE') {
                            $ttd_diminta = '<span class="badge bg-label-danger">Perjalanan Dinas Ditolak</span>';
                        }
                        return $ttd_diminta;
                    })
                    ->addColumn('ttd_disahkan', function ($row) use ($holding) {
                        if ($row->status_penugasan == 0) {
                            $ttd_disahkan = '<span class="badge bg-label-secondary">Pengajuan Perjalanan Dinas</span>';
                        } else if ($row->status_penugasan == 1) {
                            $ttd_disahkan = '<span class="badge bg-label-primary">Menunggu Approve Diminta</span>';
                        } else if ($row->status_penugasan == 2) {
                            $ttd_disahkan = '<span class="badge bg-label-info">Menunggu Approve Diminta</span>';
                        } else if ($row->status_penugasan == 3) {
                            $ttd_disahkan = '<button id="btn_lihat_ttd_disahkan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_disahkan_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_disahkan_oleh)->format('d m Y') . '" data-nama="' . $row->nama_disahkan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Disahkan</button>';
                        } else if ($row->status_penugasan == 4) {
                            $ttd_disahkan = '<button id="btn_lihat_ttd_disahkan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_disahkan_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_disahkan_oleh)->format('d m Y') . '" data-nama="' . $row->nama_disahkan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Disahkan</button>';
                        } else if ($row->status_penugasan == 5) {
                            $ttd_disahkan = '<button id="btn_lihat_ttd_disahkan" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_id_disahkan_oleh . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_id_disahkan_oleh)->format('d m Y') . '" data-nama="' . $row->nama_disahkan . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Disahkan</button>';
                        } else if ($row->status_penugasan == 'NOT APPROVE') {
                            $ttd_disahkan = '<span class="badge bg-label-danger">Perjalanan Dinas Ditolak</span>';
                        }
                        return $ttd_disahkan;
                    })
                    ->addColumn('ttd_proses_hrd', function ($row) use ($holding) {
                        if ($row->status_penugasan == 0) {
                            $ttd_proses_hrd = '<span class="badge bg-label-secondary">Pengajuan Perjalan Dinas</span>';
                        } else if ($row->status_penugasan == 1) {
                            $ttd_proses_hrd = '<span class="badge bg-label-primary">Menunggu Approve Diminta</span>';
                        } else if ($row->status_penugasan == 2) {
                            $ttd_proses_hrd = '<span class="badge bg-label-secondary">Menuggu Approve Atasan 2</span>';
                        } else if ($row->status_penugasan == 3) {
                            $ttd_proses_hrd = '<span class="badge bg-label-secondary">Menunggu Approve HRD</span>';
                        } else if ($row->status_penugasan == 4) {
                            $ttd_proses_hrd = '<button id="btn_lihat_ttd_proses_hrd" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_proses_hrd . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_proses_hrd)->format('d m Y') . '" data-nama="' . $row->nama_hrd . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;HRD</button>';
                        } else if ($row->status_penugasan == 5) {
                            $ttd_proses_hrd = '<button id="btn_lihat_ttd_proses_hrd" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_proses_hrd . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_proses_hrd)->format('d m Y') . '" data-nama="' . $row->nama_hrd . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;HRD</button>';
                        } else if ($row->status_penugasan == 'NOT APPROVE') {
                            $ttd_proses_hrd = '<span class="badge bg-label-danger">Perjalan Dinas Ditolak</span>';
                        }
                        return $ttd_proses_hrd;
                    })
                    ->addColumn('ttd_proses_finance', function ($row) use ($holding) {
                        if ($row->status_penugasan == 0) {
                            $ttd_proses_finance = '<span class="badge bg-label-secondary">Pengajuan Perjalanan Dinas</span>';
                        } else if ($row->status_penugasan == 1) {
                            $ttd_proses_finance = '<span class="badge bg-label-primary">Menunggu Approve Diminta</span>';
                        } else if ($row->status_penugasan == 2) {
                            $ttd_proses_finance = '<span class="badge bg-label-secondary">Menuggu Approve Atasan 2</span>';
                        } else if ($row->status_penugasan == 3) {
                            $ttd_proses_finance = '<span class="badge bg-label-secondary">Menunggu Approve HRD</span>';
                        } else if ($row->status_penugasan == 4) {
                            $ttd_proses_finance = '<span class="badge bg-label-info">Menunggu Approve Finance</span>';
                        } else if ($row->status_penugasan == 5) {
                            $ttd_proses_finance = '<button id="btn_lihat_ttd_proses_finance" type="button" data-id="' . $row->id . '" data-ttd="' . $row->ttd_proses_finance . '" data-tgl = "' . Carbon::parse($row->waktu_ttd_proses_finance)->format('d m Y') . '" data-nama="' . $row->nama_finance . '"  class="btn btn-sm btn-success"><i class="menu-icon tf-icons mdi mdi-eye"></i> Lihat&nbsp;TTD&nbsp;Nama&nbsp;Finance</button>';
                        } else if ($row->status_penugasan == 'NOT APPROVE') {
                            $ttd_proses_finance = '<span class="badge bg-label-danger">Perjalanan Dinas Ditolak</span>';
                        }
                        return $ttd_proses_finance;
                    })
                    ->addColumn('status_penugasan', function ($row) use ($holding) {
                        if ($row->status_penugasan == 0) {
                            $status = '<span class="badge bg-label-secondary">Pengajuan Perjalan Dinas</span>';
                        } else if ($row->status_penugasan == 1) {
                            $status = '<span class="badge bg-label-primary">Menunggu Approve Diminta</span>';
                        } else if ($row->status_penugasan == 2) {
                            $status = '<span class="badge bg-label-secondary">Menunggu Approve Atasan 2</span>';
                        } else if ($row->status_penugasan == 3) {
                            $status = '<span class="badge bg-label-secondary">Menunggu Approve HRD</span>';
                        } else if ($row->status_penugasan == 4) {
                            $status = '<span class="badge bg-label-info">Menunggu Approve Finance</span>';
                        } else if ($row->status_penugasan == 5) {
                            $status = '<span class="badge bg-label-success">Perjalan Dinas Di Appove</span>';
                        } else if ($row->status_penugasan == 'NOT APPROVE') {
                            $status = '<span class="badge bg-label-danger">Perjalan Dinas Ditolak</span>';
                        }
                        $status_penugasan = $status;
                        return $status_penugasan;
                    })
                    ->rawColumns(['no_form_penugasan', 'tanggal_pengajuan', 'tanggal_kunjungan', 'selesai_kunjungan', 'ttd_proses_finance', 'ttd_proses_hrd', 'ttd_disahkan', 'ttd_diminta', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'ttd_user', 'status_penugasan'])
                    ->make(true);
            }
        }
    }
    public function cetak_admin_form_penugasan($id)
    {
        $penugasan = Penugasan::join('users', 'users.id', 'penugasans.id_user')->where('penugasans.id', $id)->first();
        $penugasan1 = Penugasan::join('users', 'users.id', 'penugasans.id_diminta_oleh')->where('penugasans.id', $id)->first();
        $penugasan2 = Penugasan::join('users', 'users.id', 'penugasans.id_disahkan_oleh')->where('penugasans.id', $id)->first();
        $departemen = Departemen::where('id', $penugasan->id_departemen)->first();
        $divisi = Divisi::where('id', $penugasan->id_divisi)->first();
        $jabatan = Jabatan::where('id', $penugasan->id_jabatan)->first();
        $departemen1 = Departemen::where('id', $penugasan1->dept_id)->first();
        $divisi1 = Divisi::where('id', $penugasan1->divisi_id)->first();
        $jabatan1 = Jabatan::where('id', $penugasan1->jabatan_id)->first();
        $departemen2 = Departemen::where('id', $penugasan2->dept_id)->first();
        $divisi2 = Divisi::where('id', $penugasan2->divisi_id)->first();
        $jabatan2 = Jabatan::where('id', $penugasan2->jabatan_id)->first();
        $pengganti = User::where('id', $penugasan->user_id_backup)->first();
        // dd(Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first());
        $data = [
            'title' => 'domPDF in Laravel 10',
            'data_penugasan' => Penugasan::with('User')->where('penugasans.id', $id)->where('penugasans.status_penugasan', '5')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'jabatan1' => $jabatan1,
            'divisi1' => $divisi1,
            'departemen1' => $departemen1,
            'jabatan2' => $jabatan2,
            'divisi2' => $divisi2,
            'departemen2' => $departemen2,
            'pengganti' => $pengganti,
        ];
        $pdf = PDF::loadView('admin/penugasan/form_admin_penugasan', $data)->setPaper('F4', 'landscape');;
        return $pdf->stream('FORM_PENGAJUAN_PENUGASAN_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
