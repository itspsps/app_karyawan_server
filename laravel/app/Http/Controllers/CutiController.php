<?php

namespace App\Http\Controllers;

use App\Exports\CutiExport;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\KategoriCuti;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class CutiController extends Controller
{
    public function index()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.cuti.index', [
            'holding' => $holding,
        ]);
    }

    public function tambah(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($request["tanggal_mulai"] == null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        } else {
            $request["tanggal_mulai"] = $request["tanggal_mulai"];
        }

        if ($request["tanggal_akhir"] == null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        } else {
            $request["tanggal_akhir"] = $request["tanggal_akhir"];
        }

        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $request["tanggal"] = $date->format("Y-m-d");

            $request['status_cuti'] = "Pending";
            $validatedData = $request->validate([
                'user_id' => 'required',
                'nama_cuti' => 'required',
                'tanggal' => 'required',
                'alasan_cuti' => 'required',
                'foto_cuti' => 'image|file|max:10240',
                'status_cuti' => 'required',
            ]);

            if ($request->file('foto_cuti')) {
                $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti');
            }

            Cuti::create($validatedData);
        }
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'tambah',
            'description' => 'Menambahkan data cuti baru dengan nama cuti ' . $request->nama_cuti,
        ]);

        return redirect('/cuti')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function delete($id)
    {
        $delete = Cuti::find($id);
        // Storage::delete($delete->foto_cuti);
        $delete->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'hapus',
            'description' => 'Menghapus data cuti dengan nama cuti ' . $delete->nama_cuti,
        ]);
        return redirect('/cuti')->with('success', 'Data Berhasil di Delete');
    }

    public function edit($id)
    {
        return view('cuti.edit', [
            'title' => 'Edit Permintaan Cuti',
            'data_cuti_user' => Cuti::findOrFail($id)
        ]);
    }

    public function editProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'nama_cuti' => 'required',
            'tanggal' => 'required',
            'alasan_cuti' => 'required',
            'foto_cuti' => 'image|file|max:10240',
        ]);

        if ($request->file('foto_cuti')) {
            // if ($request->foto_cuti_lama) {
            //     Storage::delete($request->foto_cuti_lama);
            // }
            $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti');
        }

        Cuti::where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/cuti');
    }

    public function dataCuti()
    {
        return view('cuti.datacuti', [
            'title' => 'Data Cuti Karyawan',
            'data_cuti' => Cuti::orderBy('id', 'desc')->get()
        ]);
    }

    public function datatable_cuti_tahunan(Request $request)
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
                $table = Cuti::leftJoin('users', 'users.id', 'cutis.user_id')->where('nama_cuti', 'Cuti Tahunan')
                    ->where('users.kontrak_kerja', $holding)
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
            }
        }
    }
    public function datatable_diluar_cuti_tahunan(Request $request)
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
                $table = Cuti::leftJoin('users', 'users.id', 'cutis.user_id')->where('nama_cuti', 'Diluar Cuti Tahunan')
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
                $table = Cuti::leftJoin('users', 'users.id', 'cutis.user_id')->where('nama_cuti', 'Diluar Cuti Tahunan')
                    ->where('users.kontrak_kerja', $holding)
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
                    ->addColumn('kategori_cuti', function ($row) {
                        $kategori = KategoriCuti::where('id', $row->kategori_cuti_id)->first();
                        if ($kategori == NULL) {
                            $kategori_cuti = NULL;
                        } else {
                            $kategori_cuti = $kategori->nama_cuti;
                        }
                        return $kategori_cuti;
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
                    ->rawColumns(['no_form_cuti', 'tanggal', 'kategori_cuti', 'total_cuti', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_masuk', 'nama_departemen', 'nama_divisi', 'nama_jabatan', 'ttd_user', 'ttd_atasan', 'ttd_atasan2', 'waktu_approve', 'waktu_approve2', 'status_cuti'])
                    ->make(true);
            }
        }
    }
    public function tambahAdmin()
    {
        return view('cuti.tambahadmin', [
            'title' => 'Tambah Cuti Karyawan',
            'data_user' => User::select('id', 'name')->get()
        ]);
    }

    public function getUserId(Request $request)
    {
        $id = $request["id"];
        $data_user = User::findOrfail($id);

        $cuti_dadakan = $data_user->cuti_dadakan;
        $cuti_bersama = $data_user->cuti_bersama;
        $cuti_menikah = $data_user->cuti_menikah;
        $cuti_diluar_tanggungan = $data_user->cuti_diluar_tanggungan;
        $cuti_khusus = $data_user->cuti_khusus;
        $cuti_melahirkan = $data_user->cuti_melahirkan;
        $cuti_telat = $data_user->cuti_telat;
        $cuti_pulang_cepat = $data_user->cuti_pulang_cepat;

        $data_cuti = array(
            [
                'nama' => 'Cuti Dadakan',
                'nama_cuti' => 'Cuti Dadakan (' . $cuti_dadakan . ')'
            ],
            [
                'nama' => 'Cuti Bersama',
                'nama_cuti' => 'Cuti Bersama (' . $cuti_bersama . ')'
            ],
            [
                'nama' => 'Cuti Menikah',
                'nama_cuti' => 'Cuti Menikah (' . $cuti_menikah . ')'
            ],
            [
                'nama' => 'Cuti Diluar Tanggungan',
                'nama_cuti' => 'Cuti Diluar Tanggungan (' . $cuti_diluar_tanggungan . ')'
            ],
            [
                'nama' => 'Cuti Khusus',
                'nama_cuti' => 'Cuti Khusus (' . $cuti_khusus . ')'
            ],
            [
                'nama' => 'Cuti Melahirkan',
                'nama_cuti' => 'Cuti Melahirkan (' . $cuti_melahirkan . ')'
            ],
            [
                'nama' => 'Izin Telat',
                'nama_cuti' => 'Izin Telat (' . $cuti_telat . ')'
            ],
            [
                'nama' => 'Izin Pulang Cepat',
                'nama_cuti' => 'Izin Pulang Cepat (' . $cuti_pulang_cepat . ')'
            ]
        );

        foreach ($data_cuti as $dc) {
            echo "<option value='$dc[nama]'>$dc[nama_cuti]</option>";
        }
    }
    public function cetak_form_cuti($id)
    {
        // dd('ok');
        $cuti = Cuti::With('User')->where('id', $id)->first();
        $jabatan = Jabatan::join('users', function ($join) {
            $join->on('jabatans.id', '=', 'users.jabatan_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
        })->where('users.id', $cuti->user_id)->get();
        $divisi = Divisi::join('users', function ($join) {
            $join->on('divisis.id', '=', 'users.divisi_id');
            $join->orOn('divisis.id', '=', 'users.divisi1_id');
            $join->orOn('divisis.id', '=', 'users.divisi2_id');
            $join->orOn('divisis.id', '=', 'users.divisi3_id');
            $join->orOn('divisis.id', '=', 'users.divisi4_id');
        })->where('users.id', $cuti->user_id)->get();
        $departemen = Departemen::join('users', function ($join) use ($cuti) {
            $join->on('departemens.id', '=', 'users.dept_id');
            $join->where('users.id', $cuti->user_id);
        })->first();
        // dd($departemen);
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
        $pdf = PDF::loadView('admin/cuti/form_cuti', $data);
        return $pdf->stream('FORM_PENGAJUAN_CUTI_' . $cuti->name_user . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function tambahAdminProses(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($request["tanggal_mulai"] == null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        } else {
            $request["tanggal_mulai"] = $request["tanggal_mulai"];
        }

        if ($request["tanggal_akhir"] == null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        } else {
            $request["tanggal_akhir"] = $request["tanggal_akhir"];
        }

        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $request["tanggal"] = $date->format("Y-m-d");

            $request['status_cuti'] = "Pending";
            $validatedData = $request->validate([
                'user_id' => 'required',
                'nama_cuti' => 'required',
                'tanggal' => 'required',
                'alasan_cuti' => 'required',
                'foto_cuti' => 'image|file|max:10240',
                'status_cuti' => 'required',
            ]);

            if ($request->file('foto_cuti')) {
                $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti');
            }

            Cuti::create($validatedData);
        }
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'tambah',
            'description' => 'Menambahkan data cuti karyawan dengan nama ' . User::findOrfail($request["user_id"])->name,
            'time' => date('Y-m-d H:i:s')
        ]);

        return redirect('/data-cuti')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function deleteAdmin($id)
    {
        $delete = Cuti::find($id);
        // Storage::delete($delete->foto_cuti);
        $delete->delete();
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'hapus',
            'description' => 'Menghapus data cuti karyawan dengan nama ' . User::findOrfail($delete->user_id)->name,
            'time' => date('Y-m-d H:i:s')
        ]);
        return redirect('/data-cuti')->with('success', 'Data Berhasil di Delete');
    }

    public function editAdmin($id)
    {
        return view('cuti.editadmin', [
            'title' => 'Edit Cuti Karyawan',
            'data_cuti_karyawan' => Cuti::findOrFail($id)
        ]);
    }

    public function editAdminProses(Request $request, $id)
    {
        $data_cuti = Cuti::where('id', $id)->get();

        foreach ($data_cuti as $dc) {
            $request["cuti_dadakan"] = $dc->User->cuti_dadakan;
            $request["cuti_bersama"] = $dc->User->cuti_bersama;
            $request["cuti_menikah"] = $dc->User->cuti_menikah;
            $request["cuti_diluar_tanggungan"] = $dc->User->cuti_diluar_tanggungan;
            $request["cuti_khusus"] = $dc->User->cuti_khusus;
            $request["cuti_melahirkan"] = $dc->User->cuti_melahirkan;
            $request["cuti_telat"] = $dc->User->cuti_telat;
            $request["cuti_pulang_cepat"] = $dc->User->cuti_pulang_cepat;
            $user_id = $dc->user_id;
            $foto_cuti = $dc->foto_cuti;
        }

        $mapping_shift = MappingShift::where('tanggal', $request['tanggal'])->where('user_id', $user_id)->get();

        if ($mapping_shift->count() == 0) {
            Alert::error('Error', 'Tidak Ada Shift Pada Tanggal ' . $request['tanggal'] . ', Harap Dimapping Terlebih Dahulu');
            return redirect('/data-cuti');
        } else {
            foreach ($mapping_shift as $mp) {
                $mp_id = $mp->id;
                $status_absen = $mp->status_absen;
                $shift_masuk = $mp->Shift->jam_masuk;
                $shift_pulang = $mp->Shift->jam_keluar;
                $jam_absen = $mp->jam_absen;
                $telat = $mp->telat;
                $lat_absen = $mp->lat_absen;
                $long_absen = $mp->long_absen;
                $jarak_masuk = $mp->jarak_masuk;
                $foto_jam_absen = $mp->foto_jam_absen;
                $jam_pulang = $mp->jam_pulang;
                $pulang_cepat = $mp->pulang_cepat;
                $lat_pulang = $mp->lat_pulang;
                $long_pulang = $mp->long_pulang;
                $jarak_pulang = $mp->jarak_pulang;
                $foto_jam_pulang = $mp->foto_jam_pulang;
            }

            if ($request["status_cuti"] == "Diterima") {
                if ($request["nama_cuti"] == "Izin Telat") {
                    $request['status_absen'] = $request["nama_cuti"];
                    $request['jam_absen'] = $shift_masuk;
                    $request['telat'] = 0;
                    $request['lat_absen'] = "-6.3707314";
                    $request['long_absen'] = "106.8138057";
                    $request['jarak_masuk'] = "0";
                    $request['jam_pulang'] = $jam_pulang;
                    $request['foto_jam_absen'] = $foto_cuti;
                    $request['pulang_cepat'] = $pulang_cepat;
                    $request['lat_pulang'] = $lat_pulang;
                    $request['long_pulang'] = $long_pulang;
                    $request['jarak_pulang'] = $jarak_pulang;
                    $request['foto_jam_pulang'] = $foto_jam_pulang;
                } elseif ($request["nama_cuti"] == "Izin Pulang Cepat") {
                    $request['status_absen'] = $request["nama_cuti"];
                    $request['jam_pulang'] = $shift_pulang;
                    $request['pulang_cepat'] = 0;
                    $request['lat_pulang'] = "-6.3707314";
                    $request['long_pulang'] = "106.8138057";
                    $request['jarak_masuk'] = $jarak_masuk;
                    $request['foto_jam_pulang'] = $foto_cuti;
                    $request['jam_absen'] = $jam_absen;
                    $request['telat'] = $telat;
                    $request['lat_absen'] = $lat_absen;
                    $request['long_absen'] = $long_absen;
                    $request['jarak_pulang'] = "0";
                    $request['foto_jam_absen'] = $foto_jam_absen;
                } else {
                    $request['status_absen'] = 'Cuti';
                }

                if ($request["nama_cuti"] == "Cuti Dadakan") {
                    $request["cuti_dadakan"] = $request["cuti_dadakan"] - 1;
                } elseif ($request["nama_cuti"] == "Cuti Bersama") {
                    $request["cuti_bersama"] = $request["cuti_bersama"] - 1;
                } elseif ($request["nama_cuti"] == "Cuti Menikah") {
                    $request["cuti_menikah"] = $request["cuti_menikah"] - 1;
                } elseif ($request["nama_cuti"] == "Cuti Diluar Tanggungan") {
                    $request["cuti_diluar_tanggungan"] = $request["cuti_diluar_tanggungan"] - 1;
                } elseif ($request["nama_cuti"] == "Cuti Khusus") {
                    $request["cuti_khusus"] = $request["cuti_khusus"] - 1;
                } elseif ($request["nama_cuti"] == "Cuti Melahirkan") {
                    $request["cuti_melahirkan"] = $request["cuti_melahirkan"] - 1;
                } elseif ($request["nama_cuti"] == "Izin Telat") {
                    $request["cuti_telat"] = $request["cuti_telat"] - 1;
                } else {
                    $request["cuti_pulang_cepat"] = $request["cuti_pulang_cepat"] - 1;
                }
            } else {
                $request["cuti_dadakan"];
                $request["cuti_bersama"];
                $request["cuti_menikah"];
                $request["cuti_diluar_tanggungan"];
                $request["cuti_khusus"];
                $request["cuti_melahirkan"];
                $request["cuti_telat"];
                $request["cuti_pulang_cepat"];
                $request['status_absen'] = $status_absen;
                $request["jam_absen"] = $jam_absen;
                $request["telat"] = $telat;
                $request["lat_absen"] = $lat_absen;
                $request["long_absen"] = $long_absen;
                $request["jarak_masuk"] = $jarak_masuk;
                $request["foto_jam_absen"] = $foto_jam_absen;
                $request["jam_pulang"] = $jam_pulang;
                $request["pulang_cepat"] = $pulang_cepat;
                $request["lat_pulang"] = $lat_pulang;
                $request["long_pulang"] = $long_pulang;
                $request["jarak_pulang"] = $jarak_pulang;
                $request["foto_jam_pulang"] = $foto_jam_pulang;
            }

            $rules1 = [
                'nama_cuti' => 'required',
                'tanggal' => 'required',
                'status_cuti' => 'required',
                'catatan' => 'nullable'
            ];

            $rules2 = [
                'cuti_dadakan' => 'required',
                'cuti_bersama' => 'required',
                'cuti_menikah' => 'required',
                'cuti_diluar_tanggungan' => 'required',
                'cuti_khusus' => 'required',
                'cuti_melahirkan' => 'required',
                'cuti_telat' => 'required',
                'cuti_pulang_cepat' => 'required',
            ];

            $rules3 = [
                'status_absen' => 'required',
                'jam_absen' => 'nullable',
                'telat' => 'nullable',
                'lat_absen' => 'nullable',
                'long_absen' => 'nullable',
                'jarak_masuk' => 'nullable',
                'foto_jam_absen' => 'nullable',
                'jam_pulang' => 'nullable',
                'pulang_cepat' => 'nullable',
                'foto_jam_pulang' => 'nullable',
                'lat_pulang' => 'nullable',
                'long_pulang' => 'nullable',
                'jarak_pulang' => 'nullable'
            ];

            $validatedData = $request->validate($rules1);
            $validatedData2 = $request->validate($rules2);
            $validatedData3 = $request->validate($rules3);


            Cuti::where('id', $id)->update($validatedData);
            User::where('id', $user_id)->update($validatedData2);
            MappingShift::where('id', $mp_id)->update($validatedData3);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'edit',
                'description' => 'Mengubah data cuti dengan id ' . $id . ' oleh ' . Auth::user()->name,
                'time' => Carbon::now()
            ]);

            $request->session()->flash('success', 'Data Berhasil di Update');
            return redirect('/data-cuti');
        }
    }
    public function ExportCuti($kategori)
    {
        $date = date('YmdHis');
        $holding = request()->segment(count(request()->segments()));
        $data =  Cuti::leftJoin('users', 'users.id', 'cutis.user_id')
            ->leftJoin('departemens', 'departemens.id', 'users.dept_id')
            ->leftJoin('divisis', 'divisis.id', 'users.divisi_id')
            ->leftJoin('jabatans', 'jabatans.id', 'users.jabatan_id')
            ->leftJoin('kategori_cuti', 'kategori_cuti.id', 'cutis.kategori_cuti_id')
            ->where('cutis.nama_cuti', $kategori)
            ->where('users.kontrak_kerja', $holding)
            // ->select('cutis.no_form_izin', 'users.name', 'departemens.nama_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'cutis.tanggal', 'cutis.jam_masuk_kerja', 'cutis.jam', 'cutis.terlambat', 'cutis.keterangan_izin', 'cutis.ttd_pengajuan', 'cutis.approve_atasan', 'cutis.waktu_approve', 'cutis.catatan', 'cutis.status_izin')
            ->select('cutis.*', 'users.name', 'departemens.nama_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'kategori_cuti.nama_cuti as kategori_cuti')
            ->get();
        // dd($kategori);
        return Excel::download(new CutiExport($holding, $kategori, $data), 'Data Cuti Karyawan_' . $kategori . '_' . $holding . '_' . $date . '.xlsx');
    }
}
