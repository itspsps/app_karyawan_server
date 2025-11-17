<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Menu;
use App\Models\RecruitmentInterview;
use App\Models\RecruitmentKeahlian;
use App\Models\RecruitmentKesehatan;
use App\Models\RecruitmentKesehatanKecelakaan;
use App\Models\RecruitmentKesehatanPengobatan;
use App\Models\RecruitmentKesehatanRS;
use App\Models\RecruitmentPendidikan;
use App\Models\RecruitmentRiwayat;
use App\Models\RecruitmentUser;
use App\Models\RecruitmentUserRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class InterviewController extends Controller
{
    public function index()
    {
        $roleId = Auth::user();
        $menus = Menu::whereIn('id', function ($query) use ($roleId) {
            $query->select('menu_id')
                ->from('role_menus')
                ->where('role_id', $roleId->role);
        })
            ->whereNull('parent_id') // menu utama
            ->with('children')
            ->where('kategori', 'web')      // load submenunya
            ->orderBy('sort_order')
            ->get();
        $user_karyawan  = Karyawan::where('id', Auth::user()->karyawan_id)
            ->with([
                'Jabatan' => function ($query) {
                    $query->with([
                        'Bagian' =>  function ($query) {
                            $query->with([
                                'Divisi' => function ($query) {
                                    $query->with([
                                        'Departemen' => function ($query) {
                                            $query->orderBy('nama_departemen', 'ASC');
                                        }
                                    ]);
                                    $query->orderBy('nama_divisi', 'ASC');
                                },
                            ]);
                        },
                    ])->with([
                        'LevelJabatan' => function ($query) {
                            $query;
                        },
                    ]);
                },

            ])->first();

        if ($user_karyawan->site_job == 'ALL SITES (SP, SPS, SIP)') {
            $getdepartemen = Departemen::where('id', $user_karyawan->Jabatan->Bagian->Divisi->Departemen->id)->first();
            if ($getdepartemen) {
                $departemen = Departemen::select('id')->where('nama_departemen', $getdepartemen->nama_departemen)->get()->toArray();
            } else {
                $departemen = [];
            }
        } else {
            $departemen = [];
        }
        // dd($departemen);
        // dd($user_karyawan->Jabatan->Bagian->Divisi->Departemen->id);
        $table = RecruitmentUser::with([
            'Cv' => function ($query) {
                $query;
            }
        ])->with([

            'interviewUser' => function ($query) {
                $query;
            }
        ])->with([
            'DataInterview' => function ($query) {
                $query;
            }
        ])
            ->with([
                'ujianEsaiJawab' => function ($query) {
                    $query->orderBy('recruitment_user_id')->with([
                        'ujian' => function ($query) {
                            $query->with([
                                'pembobotan' => function ($query) {
                                    $query;
                                }
                            ]);
                        }
                    ]);
                }
            ])->with([
                'waktuujian' => function ($query) {
                    $query->orderBy('recruitment_user_id')->with([
                        'ujian' => function ($query) {
                            $query->with([
                                'pembobotan' => function ($query) {
                                    $query;
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->with([
                'recruitmentAdmin' => function ($query) {
                    $query;
                }
            ])
            ->with([
                'Jabatan' => function ($query) {
                    $query->with([
                        'Bagian' =>  function ($query) {
                            $query->with([
                                'Divisi' => function ($query) {
                                    $query->with([
                                        'Departemen' => function ($query) {
                                            $query->orderBy('nama_departemen', 'ASC');
                                        }
                                    ]);
                                    $query->orderBy('nama_divisi', 'ASC');
                                },
                            ]);
                        },
                    ])->with([
                        'LevelJabatan' => function ($query) {
                            $query;
                        },
                    ]);
                },

            ])
            ->where('feedback_lanjutan', '1b')
            // ->whereIn('nama_dept', $departemen)
            ->get();


        // dd($table, $user_karyawan->Jabatan->LevelJabatan->level_jabatan);
        // dd($dataizin);
        // dd($datacuti_tingkat1);
        // dd($datacuti_tingkat2);
        // dd($datapenugasan);
        return view(
            'users.interview.index',
            [
                'menus' => $menus,
                'table'     => $table,
                'user_karyawan' => $user_karyawan
            ]
        );
    }
    public function detail($id)
    {
        $user_karyawan  = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $table = RecruitmentUser::with([
            'Cv' => function ($query) {
                $query;
            }
        ])->with([

            'interviewUser' => function ($query) {
                $query;
            }
        ])->with([
            'DataInterview' => function ($query) {
                $query;
            }
        ])
            ->with([
                'ujianEsaiJawab' => function ($query) {
                    $query->orderBy('recruitment_user_id')->with([
                        'ujian' => function ($query) {
                            $query->with([
                                'pembobotan' => function ($query) {
                                    $query;
                                }
                            ]);
                        }
                    ]);
                }
            ])->with([
                'waktuujian' => function ($query) {
                    $query->orderBy('recruitment_user_id')->with([
                        'ujian' => function ($query) {
                            $query->with([
                                'pembobotan' => function ($query) {
                                    $query;
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->with([
                'recruitmentAdmin' => function ($query) {
                    $query;
                }
            ])
            ->with([
                'Jabatan' => function ($query) {
                    $query->with([
                        'Bagian' =>  function ($query) {
                            $query->with([
                                'Divisi' => function ($query) {
                                    $query->with([
                                        'Departemen' => function ($query) {
                                            $query->orderBy('nama_departemen', 'ASC');
                                        }
                                    ]);
                                    $query->orderBy('nama_divisi', 'ASC');
                                },
                            ]);
                        },
                    ])->with([
                        'LevelJabatan' => function ($query) {
                            $query;
                        },
                    ]);
                },

            ])
            ->where('id', $id)
            ->first();
        $roleId = Auth::user();
        $menus = Menu::whereIn('id', function ($query) use ($roleId) {
            $query->select('menu_id')
                ->from('role_menus')
                ->where('role_id', $roleId->role);
        })
            ->whereNull('parent_id') // menu utama
            ->with('children')
            ->where('kategori', 'web')      // load submenunya
            ->orderBy('sort_order')
            ->get();
        // dd($table);
        return view(
            'users.interview.detail',
            [
                'menus' => $menus,
                'table'     => $table,
                'user_karyawan' => $user_karyawan
            ]
        );
    }
    public function pdfUserKaryawan($id)
    {

        $data_cv =  RecruitmentUser::with([
            'AuthLogin' => function ($query) use ($id) {
                $query->with([
                    'recruitmentCV' => function ($query) {
                        $query->orderBy('nama_lengkap')->with([
                            'provinsiKTP' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'kabupatenKTP' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'kecamatanKTP' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'desaKTP' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'provinsiNOW' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'kabupatenNOW' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'kecamatanNOW' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ])->with([
                            'desaNOW' => function ($query) {
                                $query->orderBy('id', 'ASC');
                            }
                        ]);
                    }
                ]);
            }
        ])->with([
            'Bagian' =>  function ($query) {
                $query->with([
                    'Divisi' => function ($query) {
                        $query->with([
                            'Departemen' => function ($query) {
                                $query->orderBy('nama_departemen', 'ASC');
                            }
                        ]);
                        $query->orderBy('nama_divisi', 'ASC');
                    }
                ]);
                $query->orderBy('nama_bagian', 'ASC');
            },
        ])
            ->where('id', $id)
            ->first();
        $kesehatan = RecruitmentKesehatan::where('id_user', $data_cv->AuthLogin->id)->first();
        $kesehatan_pengobatan = RecruitmentKesehatanPengobatan::where('id_user', $data_cv->AuthLogin->id)->get();
        $kesehatan_rs = RecruitmentKesehatanRS::where('id_user', $data_cv->AuthLogin->id)->get();
        $kesehatan_kecelakaan = RecruitmentKesehatanKecelakaan::where('id_user', $data_cv->AuthLogin->id)->get();
        $pendidikan = RecruitmentPendidikan::where('id_user', $data_cv->AuthLogin->id)->orderBy('tanggal_keluar', 'DESC')->get();
        $pekerjaan = RecruitmentRiwayat::where('id_user', $data_cv->AuthLogin->id)->orderBy('tanggal_keluar', 'DESC')->get();
        $pekerjaan_count = RecruitmentRiwayat::where('id_user', $data_cv->AuthLogin->id)->orderBy('tanggal_keluar', 'DESC')->count();
        $keahlian_count = RecruitmentKeahlian::where('id_user', $data_cv->AuthLogin->id)->count();
        $keahlian = RecruitmentKeahlian::where('id_user', $data_cv->AuthLogin->id)->get();
        // dd($pekerjaan);
        // dd($pendidikan);
        $pdf = Pdf::loadView('users.interview.cvpdf', [
            'ti$pekerjaan_counttle' => 'Data Recruitment'
        ], compact(
            'data_cv',
            'pendidikan',
            'pekerjaan',
            'pekerjaan_count',
            'keahlian_count',
            'keahlian',
            'kesehatan',
            'kesehatan_pengobatan',
            'kesehatan_rs',
            'kesehatan_kecelakaan'
        ));
        return $pdf->stream('users.interview.cvpdf');
    }
    public function prosesInterview(Request $request)
    {
        // dd($request->all());
        $id = $request->input('id');
        $catatan = $request->input('catatan');
        $status = $request->input('status');

        $recruitment = RecruitmentUser::where('id', $id)->first();
        $recruitment->status_lanjutan = $status;
        $recruitment->feedback_lanjutan = NULL;
        $recruitment->save();

        $record = new RecruitmentUserRecord();
        $record->id = Uuid::uuid4();
        $record->recruitment_user_id = $id;
        $record->status = $status;
        $record->created_at = date('Y-m-d H:i:s');
        $record->save();

        $interview = RecruitmentInterview::where('recruitment_user_id', $id)->first();
        $interview->catatan_interview_manager = $catatan;
        $interview->save();

        return response()->json([
            'code' => 200,
            'success' => true
        ]);
    }
}
