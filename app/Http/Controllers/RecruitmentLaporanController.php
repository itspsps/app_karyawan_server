<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\Models\Recruitment;
use App\Models\RecruitmentUser;
use App\Models\RecruitmentUserRecord;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RecruitmentLaporanController extends Controller
{
    public function index($holding)
    {
        $holdings = Holding::where('holding_code', $holding)->first();
        return view('admin.recruitment-users.laporan.index', [
            'holding' => $holdings
        ]);
    }
    public function dt_laporan_recruitment()
    {
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
                $query->orderBy('created_at', 'asc');
            }
        ])->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('waktu_melamar', function ($row) {
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
                ->addColumn('riwayat_lamaran', function ($row) {
                    foreach ($row->recruitmentUserRecord as $record) {
                        if ($record->status == '0') {
                            $items[] = '<li><span>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . '&nbsp;=&nbsp;<span class="badge m-2 bg-label-primary">Review&nbsp;HRD</span></span></li>';
                        } elseif ($record->status == '1') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-warning">Panggilan Wawancara</span></li>';
                        } elseif ($record->status == '2') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-info">Daftar Tunggu</span></li>';
                        } elseif ($record->status == '3') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-danger">Ditolak</span></li>';
                        } elseif ($record->status == '1a') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-success">Hadir Interview</span></li>';
                        } elseif ($record->status == '2a') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-danger">Tidak Hadir Interview</span></li>';
                        } elseif ($record->status == '1b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-warning">Interview Manager</span></li>';
                        } elseif ($record->status == '2b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-success">Diterima Bekerja</span></li>';
                        } elseif ($record->status == '3b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-danger">Tidak Lolos</span></li>';
                        } elseif ($record->status == '4b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-warning">Lolos Interview Manager</span></li>';
                        } elseif ($record->status == '5b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-danger">Ditolak Manager</span></li>';
                        } elseif ($record->status == '6b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-warning">Penawaran Posisi Lain</span></li>';
                        } elseif ($record->status == '7b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-success">Lolos Posisi Lain</span></li>';
                        } elseif ($record->status == '8b') {
                            $items[] = '<li>' . Carbon::parse($record->created_at)->isoFormat('DD-MM-YYYY') . ' = <span class="badge m-2 bg-label-info">Ditetapkan Sebagai Karyawan</span></li>';
                        } else {
                            $items[] = $record->status;
                        }
                    }
                    return implode('', $items);
                })->addColumn('status_detail', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return '<a href="/detail_riwayat/' . $row->id . '/' . $holding . '" class="btn btn-info btn-sm m-1">
                            <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>DETAIL&nbspRIWAYAT
                            </a>';
                })
                ->addColumn('hasil_final', function ($row) {
                    if ($row->status_lanjutan == null) {
                        return '<span class="badge bg-label-secondary">Belum Ditentukan</span>';
                    } elseif ($row->status_lanjutan == '1b') {
                        return '<span class="badge bg-label-warning">Interview Manager</span>';
                    } elseif ($row->status_lanjutan == '2b') {
                        return '<span class="badge bg-label-success">Diterima Bekerja</span>';
                    } elseif ($row->status_lanjutan == '3b') {
                        return '<span class="badge bg-label-danger">Tidak Lolos</span>';
                    } elseif ($row->status_lanjutan == '4b') {
                        return '<span class="badge bg-label-warning">Lolos Interview Manager</span>';
                    } elseif ($row->status_lanjutan == '5b') {
                        return '<span class="badge bg-label-danger">Ditolak Manager</span>';
                    } elseif ($row->status_lanjutan == '6b') {
                        return '<span class="badge bg-label-warning">Perubahan Posisi Lowongan</span>';
                    } elseif ($row->status_lanjutan == '7b') {
                        return '<span class="badge bg-label-success">Lolos Posisi Lain</span>';
                    } elseif ($row->status_lanjutan == '8b') {
                        return '<span class="badge bg-label-info">Ditetapkan Sebagai Karyawan</span>';
                    }
                })
                ->rawColumns([
                    'waktu_melamar',
                    'cv',
                    'nama_lengkap',
                    'posisi_yang_dilamar',
                    'status_detail',
                    'riwayat_lamaran',
                    'hasil_final',
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
        return view('admin.recruitment-users.laporan.report_pelamar', [
            'holding' => $holdings
        ]);
    }
}
