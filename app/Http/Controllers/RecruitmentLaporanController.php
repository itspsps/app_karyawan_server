<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Holding;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Recruitment;
use App\Models\RecruitmentUser;
use App\Models\RecruitmentUserRecord;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RecruitmentLaporanController extends Controller
{
    public function index($holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        return view('admin.recruitment-users.laporan.index', [
            'holding' => $holdings
        ]);
    }
    public function dt_laporan_recruitment(Request $request, $holding)
    {
        // dd($request->departemen_filter);
        $holdings = Holding::where('holding_code', $holding)->first();
        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        // $period = CarbonPeriod::create($now, $now1);

        $query = RecruitmentUser::with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])->with([
            'Cv' => function ($query) {
                $query;
            }
        ])->with([
            'recruitmentAdmin' => function ($query) {
                $query;
            }
        ])->with([
            'AuthLogin' => function ($query) {
                $query;
            }
        ])
            ->where('holding', $holdings->id)
            ->whereBetween('created_at', [$now, $now1]);
        if (!empty($request->departemen_filter)) {
            $query->whereIn('nama_dept', (array)$request->departemen_filter ?? []);
        }

        if (!empty($request->divisi_filter)) {
            $query->whereIn('nama_divisi', (array)$request->divisi_filter ?? []);
        }

        if (!empty($request->bagian_filter)) {
            $query->whereIn('nama_bagian', (array)$request->bagian_filter ?? []);
        }

        if (!empty($request->jabatan_filter)) {
            $query->whereIn('nama_jabatan', (array)$request->jabatan_filter ?? []);
        }
        $table = $query->get();
        // dd($table);
        // dd($request->departemen_filter);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('tanggal_mulai', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->Cv->nama_lengkap;
                })
                ->addColumn('alamat', function ($row) {})
                ->addColumn('tanggal_lahir', function ($row) {})
                ->addColumn('posisi_yang_dilamar', function ($row) {
                    return $row->Jabatan->nama_jabatan . ', ' . $row->Jabatan->Bagian->nama_bagian . ', ' . $row->Jabatan->Bagian->Divisi->nama_divisi . ', ' . $row->Jabatan->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->addColumn('cv', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return '<a href="/pg/pelamar-detail/' . $row->id . '/' . $holding . '" class="btn btn-info btn-sm m-1">
                            <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>LIHAT&nbspCV
                            </a>';
                })
                ->addColumn('status_detail', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return '<a href="/detail_riwayat/' . $row->id . '/' . $holding . '" class="btn btn-info btn-sm m-1">
                            <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>DETAIL&nbspRIWAYAT
                            </a>';
                })
                ->addColumn('tanggal_berakhir', function ($row) {
                    $akhir = RecruitmentUserRecord::where('recruitment_user_id', $row->id)->orderBy('created_at', 'desc')->limit(1)->first();
                    return  $akhir->created_at;
                })
                ->addColumn('perkembangan_terakhir', function ($row) {
                    $akhir = RecruitmentUserRecord::where('recruitment_user_id', $row->id)->orderBy('created_at', 'desc')->limit(1)->first();
                    if ($akhir->status == '0') {
                        return '<span class="badge bg-label-primary">Review HRD</span>';
                    } elseif ($akhir->status == '1') {
                        return '<span class="badge bg-label-warning">Panggilan Wawancara</span>';
                    } elseif ($akhir->status == '2') {
                        return '<span class="badge bg-label-info">Lamaran Diterima</span>';
                    } elseif ($akhir->status == '3') {
                        return '<span class="badge bg-label-danger">Ditolak</span>';
                    } elseif ($akhir->status == '1a') {
                        return '<span class="badge bg-label-success">Hadir Interview</span>';
                    } elseif ($akhir->status == '2a') {
                        return '<span class="badge bg-label-danger">Tidak Hadir Interview</span>';
                    } elseif ($akhir->status == '1b') {
                        return '<span class="badge bg-label-warning">Interview Manager</span>';
                    } elseif ($akhir->status == '2b') {
                        return '<span class="badge bg-label-success">Diterima Bekerja</span>';
                    } elseif ($akhir->status == '3b') {
                        return '<span class="badge bg-label-danger">Tidak Lolos</span>';
                    } elseif ($akhir->status == '4b') {
                        return '<span class="badge bg-label-warning">Lolos Interview Manager</span>';
                    } elseif ($akhir->status == '5b') {
                        return '<span class="badge bg-label-danger">Ditolak Manager</span>';
                    } elseif ($akhir->status == '6b') {
                        return '<span class="badge bg-label-warning">Penawaran Posisi Lain</span>';
                    } elseif ($akhir->status == '7b') {
                        return '<span class="badge bg-label-success">Lolos Posisi Lain</span>';
                    } elseif ($akhir->status == '8b') {
                        return '<span class="badge bg-label-info">Ditetapkan Sebagai Karyawan</span>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns([
                    'tanggal_mulai',
                    'cv',
                    'nama_lengkap',
                    'posisi_yang_dilamar',
                    'status_detail',
                    'tanggal_berakhir',
                    'perkembangan_terakhir',
                ])
                ->make(true);
        }
    }
    public function dt_laporan_recruitment_print(Request $request, $holding)
    {
        // dd($request->departemen_filter);
        $holdings = Holding::where('holding_code', $holding)->first();
        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        // $period = CarbonPeriod::create($now, $now1);

        $query = RecruitmentUser::with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])->with([
            'Cv' => function ($query) {
                $query;
            }
        ])->with([
            'recruitmentAdmin' => function ($query) {
                $query;
            }
        ])->with([
            'AuthLogin' => function ($query) {
                $query;
            }
        ])
            ->where('holding', $holdings->id)
            ->whereBetween('created_at', [$now, $now1]);
        if (!empty($request->departemen_filter)) {
            $query->whereIn('nama_dept', (array)$request->departemen_filter ?? []);
        }

        if (!empty($request->divisi_filter)) {
            $query->whereIn('nama_divisi', (array)$request->divisi_filter ?? []);
        }

        if (!empty($request->bagian_filter)) {
            $query->whereIn('nama_bagian', (array)$request->bagian_filter ?? []);
        }

        if (!empty($request->jabatan_filter)) {
            $query->whereIn('nama_jabatan', (array)$request->jabatan_filter ?? []);
        }
        $table = $query->get();
        // dd($table);
        // dd($request->departemen_filter);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('tanggal_mulai', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->Cv->nama_lengkap;
                })
                ->addColumn('alamat', function ($row) {})
                ->addColumn('tanggal_lahir', function ($row) {})
                ->addColumn('posisi_yang_dilamar', function ($row) {
                    return $row->Jabatan->nama_jabatan . ', ' . $row->Jabatan->Bagian->nama_bagian . ', ' . $row->Jabatan->Bagian->Divisi->nama_divisi . ', ' . $row->Jabatan->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->addColumn('cv', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return url('/pg/pelamar-detail/' . $row->id . '/' . $holding);
                })
                ->addColumn('status_detail', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return url('/detail_riwayat/' . $row->id . '/' . $holding);
                })
                ->addColumn('tanggal_berakhir', function ($row) {
                    $akhir = RecruitmentUserRecord::where('recruitment_user_id', $row->id)->orderBy('created_at', 'desc')->limit(1)->first();
                    return  $akhir->created_at;
                })
                ->addColumn('perkembangan_terakhir', function ($row) {
                    $akhir = RecruitmentUserRecord::where('recruitment_user_id', $row->id)->orderBy('created_at', 'desc')->limit(1)->first();
                    if ($akhir->status == '0') {
                        return '<span class="badge bg-label-primary">Review HRD</span>';
                    } elseif ($akhir->status == '1') {
                        return '<span class="badge bg-label-warning">Panggilan Wawancara</span>';
                    } elseif ($akhir->status == '2') {
                        return '<span class="badge bg-label-info">Lamaran Diterima</span>';
                    } elseif ($akhir->status == '3') {
                        return '<span class="badge bg-label-danger">Ditolak</span>';
                    } elseif ($akhir->status == '1a') {
                        return '<span class="badge bg-label-success">Hadir Interview</span>';
                    } elseif ($akhir->status == '2a') {
                        return '<span class="badge bg-label-danger">Tidak Hadir Interview</span>';
                    } elseif ($akhir->status == '1b') {
                        return '<span class="badge bg-label-warning">Interview Manager</span>';
                    } elseif ($akhir->status == '2b') {
                        return '<span class="badge bg-label-success">Diterima Bekerja</span>';
                    } elseif ($akhir->status == '3b') {
                        return '<span class="badge bg-label-danger">Tidak Lolos</span>';
                    } elseif ($akhir->status == '4b') {
                        return '<span class="badge bg-label-warning">Lolos Interview Manager</span>';
                    } elseif ($akhir->status == '5b') {
                        return '<span class="badge bg-label-danger">Ditolak Manager</span>';
                    } elseif ($akhir->status == '6b') {
                        return '<span class="badge bg-label-warning">Penawaran Posisi Lain</span>';
                    } elseif ($akhir->status == '7b') {
                        return '<span class="badge bg-label-success">Lolos Posisi Lain</span>';
                    } elseif ($akhir->status == '8b') {
                        return '<span class="badge bg-label-info">Ditetapkan Sebagai Karyawan</span>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns([
                    'tanggal_mulai',
                    'cv',
                    'nama_lengkap',
                    'posisi_yang_dilamar',
                    'status_detail',
                    'tanggal_berakhir',
                    'perkembangan_terakhir',
                ])
                ->make(true);
        }
    }
    public function detail_riwayat($id, $holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        $table = RecruitmentUser::with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])->with([
            'Cv' => function ($query) {
                $query;
            }
        ])->with([
            'AuthLogin' => function ($query) {
                $query;
            }
        ])->with([
            'recruitmentUserRecord' => function ($query) {
                $query;
            }
        ])
            ->where('id', $id)
            ->orderBy('created_at', 'asc')
            ->first();
        $first_day = RecruitmentUserRecord::where('recruitment_user_id', $id)->orderBy('created_at', 'asc')->first();
        $last_day = RecruitmentUserRecord::where('recruitment_user_id', $id)->orderBy('created_at', 'desc')->first();

        $first_day_period = Carbon::parse($first_day->created_at);
        $last_day_period = Carbon::parse($last_day->created_at);

        $total_day = $first_day_period->diffInDays($last_day_period);

        // dd($first_day_period, $last_day_period, $total_day);
        return view('admin.recruitment-users.laporan.detail_riwayat', [
            'holding' => $holdings,
            'id' => $id,
            'table' => $table,
            'total_day' => $total_day
        ]);
    }
    public function dt_riwayat_recruitment($id, $holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        $table = RecruitmentUserRecord::with([
            'recruitmentUser' => function ($query) {
                $query->with([
                    'Jabatan' => function ($query) {
                        $query->with([
                            'Bagian' => function ($query) {
                                $query->with([
                                    'Divisi' => function ($query) {
                                        $query->with([
                                            'Departemen' => function ($query) {
                                                $query->orderBy('nama_departemen', 'ASC');
                                            }
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                        $query->orderBy('nama_jabatan', 'ASC');
                    }
                ])->with([
                    'Cv' => function ($query) {
                        $query;
                    }
                ])->with([
                    'AuthLogin' => function ($query) {
                        $query;
                    }
                ]);
            }
        ])
            ->where('recruitment_user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('waktu', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status_user == '0') {
                        return '<span class="badge bg-label-primary">Review HRD</span>';
                    } elseif ($row->status_user == '1') {
                        return '<span class="badge bg-label-warning">Panggilan Wawancara</span>';
                    } elseif ($row->status_user == '2') {
                        return '<span class="badge bg-label-info">Lamaran Diterima</span>';
                    } elseif ($row->status_user == '3') {
                        return '<span class="badge bg-label-danger">Ditolak</span>';
                    } elseif ($row->status == '1a') {
                        return '<span class="badge bg-label-success">Hadir Interview</span>';
                    } elseif ($row->status == '2a') {
                        return '<span class="badge bg-label-danger">Tidak Hadir Interview</span>';
                    } elseif ($row->status == '1b') {
                        return '<span class="badge bg-label-warning">Interview Manager</span>';
                    } elseif ($row->status == '2b') {
                        return '<span class="badge bg-label-success">Diterima Bekerja</span>';
                    } elseif ($row->status == '3b') {
                        return '<span class="badge bg-label-danger">Tidak Lolos</span>';
                    } elseif ($row->status == '4b') {
                        return '<span class="badge bg-label-warning">Lolos Interview Manager</span>';
                    } elseif ($row->status == '5b') {
                        return '<span class="badge bg-label-danger">Ditolak Manager</span>';
                    } elseif ($row->status == '6b') {
                        return '<span class="badge bg-label-warning">Penawaran Posisi Lain</span>';
                    } elseif ($row->status == '7b') {
                        return '<span class="badge bg-label-success">Lolos Posisi Lain</span>';
                    } elseif ($row->status == '8b') {
                        return '<span class="badge bg-label-info">Ditetapkan Sebagai Karyawan</span>';
                    } else {
                        return $row->status;
                    }
                })
                ->addColumn('feedback', function ($row) {
                    if ($row->feedback == null) {
                        return '';
                    } elseif ($row->feedback == '1') {
                        return '<span class="badge bg-label-warning">Menyanggupi</span>';
                    } elseif ($row->feedback == '1b') {
                        return '<span class="badge bg-label-warning">Menyanggupi</span>';
                    } elseif ($row->feedback == '2b') {
                        return '<span class="badge bg-label-success">Menerima</span>';
                    } elseif ($row->feedback == '3') {
                        return '<span class="badge bg-label-danger">Tidak Hadir</span>';
                    } elseif ($row->feedback == '3b') {
                        return '<span class="badge bg-label-danger">Tidak Hadir</span>';
                    }
                })
                ->addColumn('waktu_feedback', function ($row) {
                    return $row->updated_at;
                })
                ->rawColumns([
                    'waktu',
                    'status',
                    'feedback',
                    'waktu_feedback',
                ])
                ->make(true);
        }
    }
    public function report_pelamar($holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        if ($holdings == '' || $holdings == null) {
            Alert::error('Error', 'Get Holding Error', 5000);
            return redirect()->route('dashboard_holding')->with('Error', 'Get Holding Error', 5000);
        }
        // dd($holding);
        date_default_timezone_set('Asia/Jakarta');

        $departemen = Departemen::where('holding', $holdings->id)->orderBy('nama_departemen', 'ASC')->get();
        return view('admin.recruitment-users.laporan.report_pelamar', [
            'holding' => $holdings,
            'departemen' => $departemen,
        ]);
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

    // Laporan Recruitment
    public function laporan_recruitment($holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        if ($holdings == '' || $holdings == null) {
            Alert::error('Error', 'Get Holding Error', 5000);
            return redirect()->route('dashboard_holding')->with('Error', 'Get Holding Error', 5000);
        }
        date_default_timezone_set('Asia/Jakarta');

        $departemen = Departemen::where('holding', $holdings->id)->orderBy('nama_departemen', 'ASC')->get();
        return view('admin.recruitment-users.laporan.report_recruitment', [
            'holding' => $holdings,
            'departemen' => $departemen,
        ]);
    }
    public function dt_laporan_recruitment2(Request $request, $holding)
    {
        // dd($request->departemen_filter);
        $holdings = Holding::where('holding_code', $holding)->first();
        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        // $period = CarbonPeriod::create($now, $now1);

        $query = Recruitment::with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])
            ->where('holding_recruitment', $holdings->id)
            ->whereBetween('created_at', [$now, $now1]);
        if (!empty($request->departemen_filter)) {
            $query->whereIn('nama_dept', (array)$request->departemen_filter ?? []);
        }

        if (!empty($request->divisi_filter)) {
            $query->whereIn('nama_divisi', (array)$request->divisi_filter ?? []);
        }

        if (!empty($request->bagian_filter)) {
            $query->whereIn('nama_bagian', (array)$request->bagian_filter ?? []);
        }

        if (!empty($request->jabatan_filter)) {
            $query->whereIn('nama_jabatan', (array)$request->jabatan_filter ?? []);
        }
        $table = $query->get();
        // dd($table);
        // dd($request->departemen_filter);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('posisi_kosong', function ($row) {
                    return $row->Jabatan->nama_jabatan . ', ' . $row->Jabatan->Bagian->nama_bagian . ', ' . $row->Jabatan->Bagian->Divisi->nama_divisi . ', ' . $row->Jabatan->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->addColumn('penggantian_penambahan', function ($row) {
                    if ($row->penggantian_penambahan == '1') {
                        return '<span class="badge bg-label-success">Penggantian</span>';
                    } else {
                        return '<span class="badge bg-label-info">Penambahan</span>';
                    }
                })
                ->addColumn('kuota', function ($row) {
                    return $row->kuota . ' Orang';
                })
                ->addColumn('jumlah_pelamar', function ($row) {
                    $jumlah = RecruitmentUser::where('recruitment_admin_id', $row->id)->count() ?? 0;
                    return $jumlah . ' Orang';
                })
                ->addColumn('jumlah_diterima', function ($row) {
                    $jumlah = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->count() ?? 0;
                    return $jumlah . ' Orang';
                })
                ->addColumn('pelamar_terpilih', function ($row) {
                    $terpilih = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->with([
                        'Cv' => function ($query) {
                            $query;
                        }
                    ])->get() ?? 0;
                    $items = [];
                    foreach ($terpilih as $tp) {
                        $items[] = '<li>' . $tp->Cv->nama_lengkap . '</li>';
                    }
                    return implode('', $items);
                })
                ->addColumn('kuota_terpenuhi', function ($row) {
                    $pelamar = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->count() ?? 0;
                    $terpenuhi = $row->kuota - $pelamar;
                    if ($terpenuhi == '0') {
                        return '<span class="badge bg-label-success">Terpenuhi</span>';
                    } elseif ($terpenuhi < '0') {
                        return '<span class="badge bg-label-info">Melebihi Kuota</span>';
                    } elseif ($terpenuhi > '0') {
                        return '<span class="badge bg-label-danger">Belum Terpenuhi</span>';
                    }
                })
                ->addColumn('tgl_terpenuhi', function ($row) {
                    $pelamar_total = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->count() ?? 0;
                    $pelamar_tgl = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->orderBy('created_at', 'desc')->first();
                    $terpenuhi = $row->kuota - $pelamar_total;
                    if ($pelamar_tgl != null) {
                        if ($terpenuhi == '0') {
                            return $pelamar_tgl->tanggal_diterima;
                        } elseif ($terpenuhi < '0') {
                            return $pelamar_tgl->tanggal_diterima;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tanggal_mulai', function ($row) {
                    return $row->created_recruitment;
                })
                ->addColumn('tanggal_mulai', function ($row) {
                    return $row->created_recruitment;
                })
                ->addColumn('tanggal_akhir', function ($row) {
                    return $row->end_recruitment;
                })
                ->addColumn('deadline', function ($row) {
                    return $row->deadline_recruitment;
                })
                ->addColumn('waktu_yg_dibutuhkan', function ($row) {
                    $pelamar_total = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->count() ?? 0;
                    $pelamar_tgl = RecruitmentUser::where('recruitment_admin_id', $row->id)->where('status_lanjutan', '8b')->where('feedback_lanjutan', '2b')->orderBy('created_at', 'desc')->first();
                    $terpenuhi = $row->kuota - $pelamar_total;
                    if ($pelamar_tgl != null) {
                        if ($terpenuhi == '0') {
                            $awal = Carbon::parse($row->created_recruitment);
                            $akhir = Carbon::parse($pelamar_tgl->tanggal_diterima);
                            $total_day1 = $awal->diffInDays($akhir);
                            return $total_day1 . " Hari";
                        } elseif ($terpenuhi < '0') {
                            $awal = Carbon::parse($row->created_recruitment);
                            $akhir = Carbon::parse($pelamar_tgl->tanggal_diterima);
                            $total_day2 = $awal->diffInDays($akhir);
                            return $total_day2 . " Hari";
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->rawColumns([
                    'posisi_kosong',
                    'penggantian_penambahan',
                    'kuota',
                    'jumlah_pelamar',
                    'jumlah_diterima',
                    'pelamar_terpilih',
                    'kuota_terpenuhi',
                    'tgl_terpenuhi',
                    'tanggal_mulai',
                    'tanggal_akhir',
                    'deadline',
                    'waktu_yg_dibutuhkan',
                ])
                ->make(true);
        }
    }
    // Laporan Recruitment End
    // Laporan Recruitment Per Divisi
    public function report_per_divisi($holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        return view('admin.recruitment-users.laporan.report_per_divisi', [
            'holding' => $holdings
        ]);
    }
    public function dt_per_divisi(Request $request, $holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        $table = Recruitment::with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])
            ->where('holding_recruitment', $holdings->id)
            ->groupBy('nama_divisi')
            ->orderBy('nama_divisi', 'asc')
            ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('divisi', function ($row) {
                    return $row->Jabatan->Bagian->Divisi->nama_divisi;
                })
                ->addColumn('jumlah_rekrutmen', function ($row) {
                    $jml_rec = Recruitment::where('nama_divisi', $row->nama_divisi)->count();
                    return $jml_rec;
                })
                ->addColumn('jumlah_pelamar', function ($row) {
                    $pelamar = RecruitmentUser::where('nama_divisi', $row->nama_divisi)->count() ?? 0;
                    return $pelamar;
                })
                ->addColumn('rata_rata', function ($row) {
                    $tanggal_awal = RecruitmentUserRecord::selectRaw(
                        '
                        recruitment_user_id,
                        MIN(created_at) AS tanggal_awal,
                        MAX(created_at) AS tanggal_akhir,
                        DATEDIFF(MAX(created_at), MIN(created_at)) as total_hari'
                    )
                        ->with([
                            'recruitmentUser' => function ($query) {
                                $query;
                            }
                        ])
                        ->whereHas('recruitmentUser', function ($query) use ($row) {
                            $query->where('nama_divisi', $row->nama_divisi);
                        })
                        ->orderBy('recruitment_user_id')
                        ->groupBy('recruitment_user_id')
                        ->get();

                    $total_hari = $tanggal_awal->sum('total_hari');
                    $pelamar = RecruitmentUser::where('nama_divisi', $row->nama_divisi)->count();

                    $rata_rata = $pelamar > 0 ? $total_hari / $pelamar : 0;

                    return round($rata_rata, 2) . ' Hari';
                })
                ->addColumn('detail', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return '<a href="/detail_per_divisi/' . $row->nama_divisi . '/' . $holding . '" class="btn btn-info btn-sm m-1">
                            <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>DETAIL
                            </a>';
                })
                ->rawColumns([
                    'divisi',
                    'jumlah_peserta',
                    'rata_rata',
                    'detail',
                ])
                ->make(true);
        }
    }
    public function dt_per_divisi_print(Request $request, $holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        $table = Recruitment::with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])
            ->where('holding_recruitment', $holdings->id)
            ->groupBy('nama_divisi')
            ->orderBy('nama_divisi', 'asc')
            ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('divisi', function ($row) {
                    return $row->Jabatan->Bagian->Divisi->nama_divisi;
                })
                ->addColumn('jumlah_rekrutmen', function ($row) {
                    $jml_rec = Recruitment::where('nama_divisi', $row->nama_divisi)->count();
                    return $jml_rec;
                })
                ->addColumn('jumlah_pelamar', function ($row) {
                    $pelamar = RecruitmentUser::where('nama_divisi', $row->nama_divisi)->count() ?? 0;
                    return $pelamar;
                })
                ->addColumn('rata_rata', function ($row) {
                    $tanggal_awal = RecruitmentUserRecord::selectRaw(
                        '
                        recruitment_user_id,
                        MIN(created_at) AS tanggal_awal,
                        MAX(created_at) AS tanggal_akhir,
                        DATEDIFF(MAX(created_at), MIN(created_at)) as total_hari'
                    )
                        ->with([
                            'recruitmentUser' => function ($query) {
                                $query;
                            }
                        ])
                        ->whereHas('recruitmentUser', function ($query) use ($row) {
                            $query->where('nama_divisi', $row->nama_divisi);
                        })
                        ->orderBy('recruitment_user_id')
                        ->groupBy('recruitment_user_id')
                        ->get();

                    $total_hari = $tanggal_awal->sum('total_hari');
                    $pelamar = RecruitmentUser::where('nama_divisi', $row->nama_divisi)->count();

                    $rata_rata = $pelamar > 0 ? $total_hari / $pelamar : 0;

                    return round($rata_rata, 2) . ' Hari';
                })
                ->addColumn('detail', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return url('/detail_per_divisi/' . $row->nama_divisi . '/' . $holding);
                })
                ->rawColumns([
                    'divisi',
                    'jumlah_peserta',
                    'rata_rata',
                    'detail',
                ])
                ->make(true);
        }
    }
    public function detail_per_divisi($id, $holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        $divisi = RecruitmentUser::where('nama_divisi', $id)->with([
            'Jabatan' => function ($query) {
                $query->with([
                    'Bagian' => function ($query) {
                        $query->with([
                            'Divisi' => function ($query) {
                                $query->with([
                                    'Departemen' => function ($query) {
                                        $query->orderBy('nama_departemen', 'ASC');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
                $query->orderBy('nama_jabatan', 'ASC');
            }
        ])
            ->with([
                'Cv' => function ($query) {
                    $query;
                }
            ])
            ->with([
                'recruitmentUserRecord' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])
            ->where('holding', $holdings->id)
            ->get();

        foreach ($divisi as $dd) {
            $record = $dd->recruitmentUserRecord;
            if ($record->isNotEmpty()) {
                $awal = $record->sortBy('created_at')->first();
                $akhir = $record->sortByDesc('created_at')->first();
                $tgl_awal = Carbon::parse($awal->created_at) ?? 0;
                $tgl_akhir = Carbon::parse($akhir->created_at) ?? 0;
                $total = $tgl_awal->diffInDays($tgl_akhir);
            } else {
                $total = 0;
            }
        }
        $nama_divisi = Divisi::where('id', $id)->first();
        // $pembagian = $waktu->groupBy('recruitment_user_id');

        $tanggal_awal = RecruitmentUserRecord::selectRaw(
            '
        recruitment_user_id,
        MIN(created_at) AS tanggal_awal,
        MAX(created_at) AS tanggal_akhir,
        DATEDIFF(MAX(created_at), MIN(created_at)) as total_hari,
        status,
        created_at,
        updated_at'
        )
            ->with([
                'recruitmentUser' => function ($query) {
                    $query;
                }
            ])
            ->whereHas('recruitmentUser', function ($query) use ($id) {
                $query->with([
                    'Cv' => function ($query) {
                        $query;
                    }
                ])->where('nama_divisi', $id);
            })
            ->orderBy('recruitment_user_id')
            ->groupBy('recruitment_user_id')
            ->get();

        $total_hari = $tanggal_awal->sum('total_hari');
        $pelamar = RecruitmentUser::where('nama_divisi', $id)->count();

        $rata_rata = $pelamar > 0 ? $total_hari / $pelamar : 0;

        // dd($tanggal_awal);


        return view('admin.recruitment-users.laporan.detail_per_divisi', [
            'holding' => $holdings,
            'divisi' => $divisi,
            'nama_divisi' => $nama_divisi,
            'rata_rata' => $rata_rata,
        ]);
    }
    // Laporan Recruitment Per Divisi End
}
