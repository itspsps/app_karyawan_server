<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Bagian;
use App\Models\DetailEsai;
use App\Models\Divisi;
use App\Models\User;
use App\Models\Recruitment;
use App\Models\RecruitmentUser;
use App\Models\RecruitmentInterview;
use App\Models\Gurumapel;
use App\Models\Gurukelas;
use App\Models\UserCareer;
use App\Models\Ujian;
use App\Models\DetailUjian;
use App\Models\WaktuUjian;
use App\Models\PgSiswa;
use App\Models\EssaySiswa;
use App\Models\DetailEssay;
use App\Models\InterviewAdmin;
use App\Models\InterviewUser;
use App\Models\Pembobotan;
use App\Models\RecruitmentCV;
use App\Models\RecruitmentKeahlian;
use App\Models\RecruitmentKesehatan;
use App\Models\RecruitmentKesehatanKecelakaan;
use App\Models\RecruitmentKesehatanPengobatan;
use App\Models\RecruitmentKesehatanRS;
use App\Models\RecruitmentPendidikan;
use App\Models\RecruitmentRiwayat;
use App\Models\RecruitmentUserRecord;
use App\Models\UjianEsaiJawabDetail;
use App\Models\UjianKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

use RealRashid\SweetAlert\Facades\Alert;
use DB;
use DivisionByZeroError;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Validation\Rules\Exists;
use PhpParser\Builder\Function_;

class RecruitmentController extends Controller
{
    public function pg_recruitment()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.recruitment.index', [
            // return view('karyawan.index', [
            'title'             => 'Data Recruitment',
            'holding'           => $holding,
            'data_departemen'   => Departemen::all(),
            'data_bagian'       => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_jabatan'      => Jabatan::with('Bagian')->where('holding', $holding)->get(),
            'data_dept'         => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi'       => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }

    function dt_recruitment(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
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
            },
        ])->where('holding_Recruitment', $holding)->orderBy('created_at', 'DESC')->get();;
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->addColumn('nama_jabatan', function ($row) {
                    if ($row->Jabatan == NULL) {
                        $nama_jabatan = 'vv';
                    } else {
                        $nama_jabatan = $row->Jabatan->nama_jabatan;
                    }
                    return $nama_jabatan;
                })
                ->addColumn('nama_bagian', function ($row) {
                    if ($row->Jabatan == NULL) {
                        $nama_bagian = 'a';
                    } else {
                        $nama_bagian = $row->Jabatan->Bagian->nama_bagian;
                    }
                    return $nama_bagian;
                })
                ->addColumn('nama_divisi', function ($row) {
                    if ($row->Jabatan == NULL) {
                        $nama_divisi = 'v';
                    } else {
                        $nama_divisi = $row->Jabatan->Bagian->Divisi->nama_divisi;
                    }
                    return $nama_divisi;
                })
                ->addColumn('nama_departemen', function ($row) {
                    if ($row->Jabatan == NULL) {
                        $nama_departemen = 'vv';
                    } else {
                        $nama_departemen = $row->Jabatan->Bagian->Divisi->Departemen->nama_departemen;
                    }
                    return $nama_departemen;
                })


                ->addColumn('desc_recruitment', function ($row) {

                    $btn = '<button id="btn_lihat_syarat"
                                data-id="' . $row->id . '"
                                data-desc="' . $row->desc_recruitment . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Lihat&nbsp;Syarat
                            </button>';
                    return $btn;
                })
                ->addColumn('pelamar', function ($row) use ($holding) {
                    $url = url('/pg/data-list-pelamar/' . $row->id . '/' . $holding);
                    $btn = '<a href="' . $url . '" class="btn btn-sm btn-info">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Lihat&nbsp;Pelamar
                            </a>';
                    return $btn;
                })
                ->addColumn('status_recruitment', function ($row) use ($holding) {
                    if ($row->status_recruitment == 0) {
                        $status = '<button id="btn_status_aktif"
                            data-id="' . $row->id . '"
                            data-holding="' . $holding . '"
                            type="button" class="btn btn-sm btn-success ">
                            <i class="tf-icons mdi mdi-account-search"> </i>
                            &nbsp;AKTIF
                        </button>';
                    } else {
                        $status = '<button id="btn_status_naktif"
                            data-id="' . $row->id . '"
                            data-holding="' . $holding . '"
                            type="button" class="btn btn-sm btn-danger ">
                            <i class="tf-icons mdi mdi-account-off"></i>
                            &nbspN&nbsp;AKTIF
                        </button>';
                    }
                    return $status;
                })

                ->addColumn('option', function ($row) use ($holding) {
                    $btn =
                        '<button id="btn_edit_recruitment"
                            data-id="' . $row->id . '"
                            data-dept="' . $row->nama_dept . '"
                            data-divisi="' . $row->nama_divisi . '"
                            data-bagian="' . $row->nama_bagian . '"
                            data-jabatan="' . $row->nama_jabatan . '"
                            data-tanggal_awal="' . $row->created_recruitment . '"
                            data-tanggal_akhir="' . $row->deadline_recruitment . '"
                            data-holding="' . $holding . '"
                            data-desc="' . $row->desc_recruitment . '"
                            type="button"
                            class="btn btn-icon btn-warning waves-effect waves-light">
                                <span class="tf-icons mdi mdi-pencil-outline"></span>
                        </button>
                        <button type="button" id="btn_delete_recruitment"
                            data-id="' . $row->id . '"
                            data-holding="' . $holding . '"
                            class="btn btn-icon btn-danger waves-effect waves-light">
                            <span class="tf-icons mdi mdi-delete-outline"></span>
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['created_at', 'nama_departemen', 'nama_divisi', 'nama_bagian', 'nama_jabatan', 'created_recruitment', 'deadline_recruitment', 'desc_recruitment', 'pelamar', 'status_recruitment', 'option'])
                ->make(true);
        }
    }

    function create(Request $request)
    {

        // dd($request->all());
        $validatedData = $request->validate([
            'penempatan'             => 'required',
            'nama_dept'             => 'required',
            'nama_divisi'           => 'required',
            'nama_bagian'           => 'required|max:255',
            'nama_jabatan'          => 'required',
            'created_recruitment'   => 'required',
            'deadline_recruitment'  => 'required',
            'desc_recruitment'      => 'required',
        ]);
        $holding = request()->segment(count(request()->segments()));
        // dd($validatedData);
        $insert = Recruitment::create(
            [
                'id'                    => Uuid::uuid4(),
                'holding_recruitment'   => $holding,
                'penempatan'            => $validatedData['penempatan'],
                'nama_dept'             => $validatedData['nama_dept'],
                'nama_divisi'           => $validatedData['nama_divisi'],
                'nama_bagian'           => $validatedData['nama_bagian'],
                'nama_jabatan'          => $validatedData['nama_jabatan'],
                'created_recruitment'   => $validatedData['created_recruitment'],
                'deadline_recruitment'  => $validatedData['deadline_recruitment'],
                'desc_recruitment'      => $validatedData['desc_recruitment'],
            ]
        );

        // Merekam aktivitas pengguna
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Recruitment baru ' . $request->name,
        ]);
        return redirect()->back()->with('success', 'data berhasil ditambahkan');
    }

    function update_status($id)
    {
        // dd($id);
        $holding = request()->segment(count(request()->segments()));
        $recruitment = Recruitment::where('id', $id)->where('holding_recruitment', $holding)->first();
        if ($recruitment->status_recruitment == 0) {
            Recruitment::where('id', $id)->where('holding_recruitment', $holding)->update([
                'status_recruitment' => 1,
            ]);
        } else {
            Recruitment::where('id', $id)->where('holding_recruitment', $holding)->update([
                'status_recruitment' => 0,
            ]);
        }
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'update',
            'description' => 'Update data Recruitment Status Close ' . Auth::user()->name,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Diupdate');
    }

    function update(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $recruitment = Recruitment::where('id', $request->id_recruitment)->where('holding_recruitment', $holding)->first();
        $data = Recruitment::where('id', $request->id_recruitment)->where('holding_recruitment', $holding)->update([
            'created_recruitment' => $request->created_recruitment_update,
            'deadline_recruitment' => $request->deadline_recruitment_update,
            'desc_recruitment' => $request->desc_recruitment_update,
        ]);
        if ($data) {
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'update',
                'description' => 'Update data Recruitment Description' . Auth::user()->name,
            ]);
            return redirect()->back()->with('success', 'Data Berhasil di Diupdate');
        } else {
            return redirect()->back()->with('error', 'Data  Gagal di Diupdate');
        }
    }

    function delete($id)
    {
        // dd($id);
        $holding = request()->segment(count(request()->segments()));
        $data = DB::table('recruitment_user')->where('recruitment_admin_id', $id)->first();
        if ($data == null) {
            $hapus = Recruitment::where('id', $id)->where('holding_recruitment', $holding)->delete();
            if ($hapus) {
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'hapus',
                    'description' => 'Hapus data Recruitment' . Auth::user()->name,
                ]);
                return redirect()->back()->with('success', 'data berhasil dihapus');
            } else {
                return redirect()->back()->with('error', 'Data  Gagal di Hapus');
            }
        } else {
            dd('stop');
        }
    }

    // function pg_list_pelamar($id)
    // {
    //     $holding = request()->segment(count(request()->segments()));
    //     return view('admin.recruitment-users.recruitment.list_pelamar', [
    //         'title' => 'Data Recruitment',
    //         'holding'   => $holding,
    //         'id_recruitment'        => $id,
    //         'data_departemen' => Departemen::all(),
    //         'data_bagian' => Bagian::with('Divisi')->where('holding', $holding)->get(),
    //         'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
    //         'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
    //     ]);
    // }
    function pg_list_pelamar($id)
    {
        $holding = request()->segment(count(request()->segments()));
        // $get_user = RecruitmentUser::where('recruitment_admin_id', $id)->first();
        // $get_cv = RecruitmentCV::where('users_career_id', $get_user->users_career_id)->first();
        // Hapus pelamar kadaluarsa
        $get_recruitment_user_id = RecruitmentUser::where('recruitment_admin_id', $id)
            ->whereDate('tanggal_konfirmasi', '<', date('Y-m-d H:i:s'))
            ->where('tanggal_konfirmasi', '!=', null)
            ->where('feedback', null)
            ->get();
        if ($get_recruitment_user_id != null) {
            foreach ($get_recruitment_user_id as $ii) {
                RecruitmentUser::where('id', $ii->id)->update([
                    'status'        => '3',
                    'status_user'   => '3'
                ]);
                RecruitmentUserRecord::insert([
                    'id' => Uuid::uuid4(),
                    'recruitment_user_id' => $ii->id,
                    'status' => '3',
                    'status_user' => '3'
                ]);
            }
        }
        // end Hapus pelamar kadaluarsa
        $user_meta =  RecruitmentUser::with([
            'AuthLogin' => function ($query) {
                $query->with([
                    'recruitmentCV' => function ($query) {
                        $query->orderBy('nama_lengkap');
                    }
                ]);
            }
        ])
            ->where('holding', $holding)
            ->where('status', '0')
            ->where('recruitment_admin_id', $id)
            ->get();
        $user_kandidat =  RecruitmentUser::with([
            'AuthLogin' => function ($query) {
                $query->with([
                    'recruitmentCV' => function ($query) {
                        $query->orderBy('nama_lengkap');
                    }
                ]);
            }
        ])
            ->where('holding', $holding)
            ->where('recruitment_admin_id', $id)
            ->whereIn('status', array('1', '1a', '2a'))
            ->get();
        $user_wait =  RecruitmentUser::with([
            'AuthLogin' => function ($query) {
                $query->with([
                    'recruitmentCV' => function ($query) {
                        $query->orderBy('nama_lengkap');
                    }
                ]);
            }
        ])
            ->where('holding', $holding)
            ->where('status', '2')
            ->where('recruitment_admin_id', $id)
            ->get();
        $user_reject =  RecruitmentUser::with([
            'AuthLogin' => function ($query) {
                $query->with([
                    'recruitmentCV' => function ($query) {
                        $query->orderBy('nama_lengkap');
                    }
                ]);
            }
        ])
            ->where('holding', $holding)
            ->where('status', '3')
            ->where('recruitment_admin_id', $id)
            ->get();

        return view('admin.recruitment-users.recruitment.list_pelamar', [
            'title' => 'Data Recruitment',
            'holding'   => $holding,
        ], compact('user_meta', 'user_wait', 'user_kandidat', 'user_reject'));
    }
    function pelamar_detail($id)
    {
        $holding = request()->segment(count(request()->segments()));
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
        return view('admin.recruitment-users.recruitment.user_detail', [
            'ti$pekerjaan_counttle' => 'Data Recruitment',
            'holding'   => $holding,
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
    }
    function pelamar_detail_pdf($id)
    {
        $holding = request()->segment(count(request()->segments()));
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
        $pdf = Pdf::loadView('admin.recruitment-users.recruitment.user_detail_pdf', [
            'ti$pekerjaan_counttle' => 'Data Recruitment',
            'holding'   => $holding,
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
        return $pdf->stream('admin.recruitment-users.recruitment.user_detail_pdf');
        // return view('admin.recruitment-users.recruitment.user_detail_pdf', [
        //     'ti$pekerjaan_counttle' => 'Data Recruitment',
        //     'holding'   => $holding,
        // ], compact(
        //     'data_cv',
        //     'pendidikan',
        //     'pekerjaan',
        //     'pekerjaan_count',
        //     'keahlian_count',
        //     'keahlian',
        //     'kesehatan',
        //     'kesehatan_pengobatan',
        //     'kesehatan_rs',
        //     'kesehatan_kecelakaan'
        // ));
    }
    public function pelamar_detail_ubah(Request $request)
    {
        // dd($request->all());
        $holding = request()->segment(count(request()->segments()));
        $recruitment_admin_id = RecruitmentUser::where('id', $request->recruitment_user_id)->first();
        // dd($request->nomor_whatsapp);
        // dd($recruitment_admin_id);
        if ($request->status == '1') {
            // Rule Untuk form wawancara
            $tanggal_wawancara = 'required';
            $tempat_wawancara = 'required';
            $waktu_wawancara = 'required';
            $link_wawancara = 'required';
        } else {
            $tanggal_wawancara = 'nullable';
            $waktu_wawancara = 'nullable';
            $tempat_wawancara = 'nullable';
            $link_wawancara = 'nullable';
        }
        if ($request->status == '1' && $request->online == '1') {
            $tempat_wawancara = 'required';
            $link_wawancara = 'nullable';
        } elseif ($request->status == '1' && $request->online == '2') {
            $tempat_wawancara = 'nullable';
            $link_wawancara = 'required';
        }
        $rules =
            [
                'status'             => 'required',
                'tanggal_wawancara'  => $tanggal_wawancara,
                'tempat_wawancara'   => $tempat_wawancara,
                'link_wawancara'   => $link_wawancara,
                'waktu_wawancara'    => $waktu_wawancara
            ];
        $customessages =
            [
                'required'             => ':attribute tidak boleh kosong!',
            ];
        $validasi = Validator::make(
            $request->all(),
            $rules,
            $customessages
        );


        if ($validasi->fails()) {
            $error = $validasi->errors()->first();
            Alert::error('Gagal', $error);
            return redirect()->back();
        }
        if ($request->online == '1') {
            $tempat_wawancara = $request->tempat_wawancara;
        } elseif ($request->online == '2') {
            $tempat_wawancara = $request->link_wawancara;
        }
        if ($request->status == '1') {
            // mencari nama PT
            if ($request->nama_holding == 'sp') {
                $nama_holding = 'CV SUMBER PANGAN';
            } elseif ($request->nama_holding == 'sps') {
                $nama_holding = 'PT SURYA PANGAN SEMESTA';
            } elseif ($request->nama_holding == 'sip') {
                $nama_holding = 'PT SURYA INTI PANGAN';
            }

            Carbon::setLocale('id');
            $tanggal_wawancara = Carbon::parse($request->tanggal_wawancara);
            $hari = $tanggal_wawancara->translatedFormat('l, j F Y');
            //kirim pesan ke whatsapp
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $request->nomor_whatsapp,
                    'message' =>
                    "PEMBERITAHUAN WAWANCARA!

Selamat $request->nama
Anda dinyatakan *LOLOS* tahap seleksi administrasi di $nama_holding, untuk posisi $request->nama_bagian

Kami ingin mengundang Anda untuk wawancara pada :

Tanggal : $hari
Waktu   : $request->waktu_wawancara WIB
Tempat  : $tempat_wawancara

untuk konfirmasi kehadiran bisa dilakukan di
asoy.com

*Konfirmasi maksimal 24 jam setelah pesan ini dikirim*
",

                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Xp5bwZZN22VPhojYcPEB' //change TOKEN to your actual token
                ),
            ));
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            if (isset($error_msg)) {
                echo $error_msg;
            }
            // end kirim pesan ke whatsapp
        }

        // $holding = request()->segment(count(request()->segments()));
        // dd($validatedData);
        RecruitmentUser::where('id', $request->recruitment_user_id)->update(
            [
                'status'             => $request->status,
                'status_user'        => $request->status,
                'tanggal_wawancara'  => $request->tanggal_wawancara,
                'tanggal_konfirmasi' => date('Y-m-d H:i:s', strtotime('+1 days')),
                'tempat_wawancara'   => $tempat_wawancara,
                'waktu_wawancara'    => $request->waktu_wawancara,
                'updated_at' => date('Y-m-d H:i:s')

            ]
        );
        RecruitmentUserRecord::insert(
            [
                'id'                    => Uuid::uuid4(),
                'recruitment_user_id'   => $request->recruitment_user_id,
                'status'                => $request->status,
                'status_user'           => $request->status,
                'created_at'            => date('Y-m-d H:i:s'),
            ]
        );
        // Merekam aktivitas pengguna
        return redirect('/pg/data-list-pelamar/' . $recruitment_admin_id->recruitment_admin_id . '/' . $holding . '')->with('success', 'data berhasil ditambahkan');
    }

    // Lolos Administrasi -> Panggil INterview
    function lolos_administrasi(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $selectedUsers = $request->users;

        if (!empty($selectedUsers) && is_array($selectedUsers)) {
            $userIds = array_keys($selectedUsers);
            $data_user = RecruitmentUser::with([
                'Bagian' => function ($query) {
                    $query->orderBy('nama_bagian', 'ASC');
                },
                'Cv' => function ($query) {
                    $query->orderBy('id', 'ASC');
                },
                'AuthLogin' => function ($query) {
                    $query->orderBy('id', 'ASC');
                }
            ])->whereIn('id', $userIds)->get();
            // dd($data_user);
            foreach ($data_user as $user) {

                // Insert interview data
                RecruitmentInterview::create([
                    'id' => Uuid::uuid4(),
                    'holding' => $holding,
                    'recruitment_admin_id' => $request->recruitment_admin_id,
                    'recruitment_userid' => $user->id,
                    'tanggal_interview' => $request->tanggal_interview,
                    'jam_interview' => $request->jam_interview,
                    'lokasi_interview' => $request->lokasi_interview,
                ]);

                // Update user status
                RecruitmentUser::where('status_recruitmentuser', 0)
                    ->where('id', $user->id)
                    ->update(['status_recruitmentuser' => 1]);
                //Merekam aktivitas pengguna
                ActivityLog::create([
                    'user_id' => $request->user()->id,
                    'activity' => 'create',
                    'description' => 'Pemanggilan Interview' . $request->name,
                ]);
            }
        } else {
            $data_user = collect();
        }
        return redirect()->back()->with('success', 'Data Berhasil di informasikan');
    }

    // Tidak Lolos Administrasi -> konfirmasi
    function tidak_lolos_administrasi(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $selectedUsers = $request->users;
        if (!empty($selectedUsers) && is_array($selectedUsers)) {
            $userIds = array_keys($selectedUsers);
            $data_user = RecruitmentUser::with([
                'Bagian' =>  function ($query) {
                    $query->orderBy('nama_bagian', 'ASC');
                },
            ])->whereIn('id', $userIds)->get();
            foreach ($data_user as $user) {
                RecruitmentUser::where('status_recruitmentuser', 0)->where('id', $user->id)->update(['status_recruitmentuser' => 5]);
                // Merekam aktivitas pengguna
                ActivityLog::create([
                    'user_id' => $request->user()->id,
                    'activity' => 'update',
                    'description' => 'Mengupdate data Recruitment User' . $request->name,
                ]);
            }
        } else {
            $data_user = collect();
        }
        return redirect()->back()->with('success', 'Data Berhasil diupdate');
    }

    function pg_data_interview()
    {
        // Hapus pelamar kadaluarsa
        $get_recruitment_user_id = RecruitmentUser::where('status', '1')
            ->whereDate('tanggal_wawancara', '<', date('Y-m-d'))
            ->get();
        // dd($get_recruitment_user_id);
        foreach ($get_recruitment_user_id as $ii) {
            RecruitmentUser::where('id', $ii->id)->update([
                'status' => '2a',
            ]);
            RecruitmentUserRecord::insert([
                'id' => Uuid::uuid4(),
                'recruitment_user_id' => $ii->id,
                'status' => '2a'
            ]);
        }

        // end Hapus pelamar kadaluar
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.interview.data_interview', [
            // return view('karyawan.index', [
            'title' => 'Data Interview',
            'holding'   => $holding,
        ]);
    }
    function dt_data_interview()
    {
        $holding = request()->segment(count(request()->segments()));
        $table = RecruitmentInterview::with([
            'recruitmentUser' => function ($query) use ($holding) {
                $query->where('holding', $holding)->whereDate('tanggal_wawancara', date('Y-m-d'))->with([
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
                    }
                ])->with([
                    'Cv' => function ($query) {
                        $query;
                    }
                ]);
            }
        ])
            ->whereHas('recruitmentUser', function ($query) {
                $query->whereDate('tanggal_wawancara', date('Y-m-d'));
            })
            ->whereHas('recruitmentUser', function ($query) use ($holding) {
                $query->where('holding', $holding);
            })
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('tanggal_wawancara', function ($row) {
                    return Carbon::parse($row->recruitmentUser->tanggal_wawancara)->format('d-m-Y');
                })
                ->addColumn('presensi', function ($row) {
                    if ($row->recruitmentUser->status == '1a') {
                        $btn = '<p class="p-2 bg-success text-white">Hadir</p>';
                    } elseif ($row->recruitmentUser->status == '2a') {
                        $btn = '<p class="p-2 bg-danger text-white">Tidak Hadir</p>';
                    } else {
                        $btn = '<button id="btn_presensi"
                                data-id="' . $row->id . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-account-search"> </i>
                                 &nbsp;Presensi
                            </button>';
                    }
                    return $btn;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->recruitmentUser->Cv->nama_lengkap;
                })
                ->addColumn('nama_bagian', function ($row) {
                    return $row->recruitmentUser->Bagian->nama_bagian;
                })
                ->addColumn('nama_divisi', function ($row) {
                    return $row->recruitmentUser->Bagian->Divisi->nama_divisi;
                })
                ->addColumn('nama_departemen', function ($row) {
                    return $row->recruitmentUser->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->rawColumns(['tanggal_wawancara', 'presensi', 'nama_lengkap', 'nama_bagian', 'nama_departemen', 'nama_divisi', 'nama_departemen'])
                ->make(true);
        }
    }
    function dt_data_interview1()
    {
        $holding = request()->segment(count(request()->segments()));
        $table = RecruitmentInterview::with([
            'recruitmentUser' => function ($query) use ($holding) {
                $query->where('holding', $holding)->where('status', '1a')->with([
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
                    }
                ])->with([
                    'Cv' => function ($query) {
                        $query;
                    }
                ]);
            }
        ])
            ->whereHas('recruitmentUser', function ($query) {
                $query->where('status', '1a');
            })
            ->whereHas('recruitmentUser', function ($query) use ($holding) {
                $query->where('holding', $holding);
            })
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($table);
        if (request()->ajax()) {

            return DataTables::of($table)
                ->addColumn('tanggal_wawancara', function ($row) {
                    return Carbon::parse($row->recruitmentUser->tanggal_wawancara)->format('d-m-Y');
                })
                ->addColumn('presensi', function ($row) {
                    if ($row->recruitmentUser->status == '1a') {
                        $btn = '<p class="p-2 bg-success text-white">Hadir</p>';
                    } elseif ($row->recruitmentUser->status == '2a') {
                        $btn = '<p class="p-2 bg-danger text-white">Tidak Hadir</p>';
                    } else {
                        $btn = '<button id="btn_presensi"
                                data-id="' . $row->id . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-account-search"> </i>
                                 &nbsp;Presensi
                            </button>';
                    }
                    return $btn;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->recruitmentUser->Cv->nama_lengkap;
                })
                ->addColumn('ujian', function ($row) {
                    $holding = request()->segment(count(request()->segments()));
                    return '<a href="' . url("/dt/data-data_ujian_user/$row->recruitment_user_id/$holding") . '" type="button" class="btn btn-info"><small>Lihat</small></a>';
                })
                ->addColumn('nama_bagian', function ($row) {
                    return $row->recruitmentUser->Bagian->nama_bagian;
                })
                ->addColumn('nama_divisi', function ($row) {
                    return $row->recruitmentUser->Bagian->Divisi->nama_divisi;
                })
                ->addColumn('nama_departemen', function ($row) {
                    return $row->recruitmentUser->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->rawColumns(['tanggal_wawancara', 'presensi', 'nama_lengkap', 'ujian', 'nama_bagian', 'nama_departemen', 'nama_divisi', 'nama_departemen'])
                ->make(true);
        }
    }
    function dt_data_interview2()
    {
        $holding = request()->segment(count(request()->segments()));
        $table = RecruitmentInterview::with([
            'recruitmentUser' => function ($query) use ($holding) {
                $query->where('holding', $holding)->where('status', '2a')->with([
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
                    }
                ])->with([
                    'Cv' => function ($query) {
                        $query;
                    }
                ]);
            }
        ])
            ->whereHas('recruitmentUser', function ($query) use ($holding) {
                $query->where('holding', $holding);
            })
            ->whereHas('recruitmentUser', function ($query) {
                $query->where('status', '2a');
            })
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('tanggal_wawancara', function ($row) {
                    return Carbon::parse($row->recruitmentUser->tanggal_wawancara)->format('d-m-Y');
                })
                ->addColumn('presensi', function ($row) {
                    if ($row->recruitmentUser->status == '1a') {
                        $btn = '<p class="p-2 bg-success text-white">Hadir</p>';
                    } elseif ($row->recruitmentUser->status == '2a') {
                        $btn = '<p class="p-2 bg-danger text-white">Tidak Hadir</p>';
                    } else {
                        $btn = '<button id="btn_presensi"
                                data-id="' . $row->id . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-account-search"> </i>
                                 &nbsp;Presensi
                            </button>';
                    }
                    return $btn;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->recruitmentUser->Cv->nama_lengkap;
                })
                ->addColumn('nama_bagian', function ($row) {
                    return $row->recruitmentUser->Bagian->nama_bagian;
                })
                ->addColumn('nama_divisi', function ($row) {
                    return $row->recruitmentUser->Bagian->Divisi->nama_divisi;
                })
                ->addColumn('nama_departemen', function ($row) {
                    return $row->recruitmentUser->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->rawColumns(['tanggal_wawancara', 'presensi', 'nama_lengkap', 'nama_bagian', 'nama_departemen', 'nama_divisi', 'nama_departemen'])
                ->make(true);
        }
    }
    public function data_ujian_user($id)
    {
        $holding = request()->segment(count(request()->segments()));
        $user_recruitment = RecruitmentUser::where('id', $id)->with([
            'Cv' => function ($query) {
                $query;
            }
        ])->first();
        $user_interview = RecruitmentInterview::where('recruitment_user_id', $id)->first();
        return view('admin.recruitment-users.interview.data_ujian_user', [
            // return view('karyawan.index', [
            'holding'   => $holding,
            'recruitment_user_id' => $id,
            'user_recruitment'   => $user_recruitment,
            'user_interview'   => $user_interview,
        ]);
    }

    public function presensi_recruitment_update(Request $request)
    {

        try {
            $konfirmasi = RecruitmentInterview::where('id', $request->id)->first();
            // dd($konfirmasi);
            $konfirmasi->updated_at = date('Y-m-d H:i:s');
            $konfirmasi->save();
            RecruitmentUser::where('id', $konfirmasi->recruitment_user_id)->update(
                [
                    'status'                => $request->status,
                    'updated_at'            => date('Y-m-d H:i:s'),
                ]
            );

            RecruitmentUserRecord::insert(
                [
                    'id'                    => Uuid::uuid4(),
                    'recruitment_user_id'   => $konfirmasi->recruitment_user_id,
                    'status'                => $request->status,
                    'created_at'            => date('Y-m-d H:i:s'),
                ]
            );
            if ($request->status == '1a') {
                $get_interview_user = InterviewUser::where('recruitment_user_id', $konfirmasi->recruitment_user_id)->first();
                if ($get_interview_user == null) {
                    $interview_admin  = InterviewAdmin::get();
                    foreach ($interview_admin as $ia) {
                        InterviewUser::create([
                            'parameter' => $ia->parameter,
                            'deskripsi' => $ia->deskripsi,
                            'recruitment_user_id' => $konfirmasi->recruitment_user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }


            return response()->json([
                'code' => 200,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function ranking_update_status(Request $request)
    {
        try {
            if ($request->status == '1b') {
                if ($request->online == 1) {
                    $link_wawancara = 'nullable';
                    $tempat_wawancara = 'required';
                } elseif ($request->online == 2) {
                    $link_wawancara = 'required';
                    $tempat_wawancara = 'nullable';
                }
                $validator = Validator::make(
                    $request->all(),
                    [
                        'tanggal_wawancara' => 'required',
                        'link_wawancara' => $link_wawancara,
                        'tempat_wawancara' => $tempat_wawancara,
                        'waktu_wawancara' => 'required',
                    ],
                    [
                        'required' => ':attribute tidak boleh kosong'
                    ]
                );
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ]);
                }
                if ($request->online == 1) {
                    $tempat = $request->tempat_wawancara;
                } elseif ($request->online == 2) {
                    $tempat = $request->link_wawancara;
                }
                // dd($request->all());
                RecruitmentUser::where('id', $request->id)->update(

                    [
                        'tanggal_wawancara_manager'             => $request->tanggal_wawancara,
                        'tempat_wawancara'              => $tempat,
                        'waktu_wawancara'       => $request->waktu_wawancara,
                        'status_lanjutan'               => $request->status,
                        'tanggal_konfirmasi_manager'    => date('Y-m-d H:i:s', strtotime('+1 days')),
                        'updated_at'                    => date('Y-m-d H:i:s'),
                    ]
                );
                RecruitmentUserRecord::insert(
                    [
                        'id'                        => Uuid::uuid4(),
                        'recruitment_user_id'       => $request->id,
                        'status'                    => $request->status,
                        'created_at'                => date('Y-m-d H:i:s'),
                    ]
                );
                $get_wa = RecruitmentUser::with([
                    'AuthLogin' => function ($query) {
                        $query;
                    }
                ])->where('id', $request->id)->first();
                // dd($get_wa);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $get_wa->Authlogin->nomor_whatsapp,
                        'message' =>
                        "Pemberitahuan

selamat Anda lolos wawancara manager
",
                        'countryCode' => '62', //optional
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Xp5bwZZN22VPhojYcPEB' //change TOKEN to your actual token
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);

                if (isset($error_msg)) {
                    echo $error_msg;
                }
            } elseif ($request->status == '2b') {
                // dd($request->all);
                $validator = Validator::make(
                    $request->all(),
                    [
                        'gaji' => 'required',
                        'tanggal_diterima' => 'required',
                    ],
                    [
                        'required' => ':attribute tidak boleh kosong'
                    ]
                );
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ]);
                }
                RecruitmentUser::where('id', $request->id)->update(

                    [
                        'status_lanjutan'       => $request->status,
                        'gaji'                  => $request->gaji,
                        'tanggal_diterima'      => $request->tanggal_diterima,
                        'notes'                 => $request->notes_langsung,
                        'konfirmasi_diterima'      => date('Y-m-d H:i:s', strtotime('+1 days')),
                        'updated_at'            => date('Y-m-d H:i:s'),
                    ]
                );
                RecruitmentUserRecord::insert(
                    [
                        'id'                        => Uuid::uuid4(),
                        'recruitment_user_id'       => $request->id,
                        'status'           => $request->status,
                        'created_at'                => date('Y-m-d H:i:s'),
                    ]
                );
                $get_wa = RecruitmentUser::with([
                    'AuthLogin' => function ($query) {
                        $query;
                    }
                ])->where('id', $request->id)->first();
                // dd($get_wa);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $get_wa->Authlogin->nomor_whatsapp,
                        'message' =>
                        "Pemberitahuan

selamat Anda lolos bekerja
",
                        'countryCode' => '62', //optional
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Xp5bwZZN22VPhojYcPEB' //change TOKEN to your actual token
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);

                if (isset($error_msg)) {
                    echo $error_msg;
                }
            } elseif ($request->status == '3b') {
                RecruitmentUser::where('id', $request->id)->update(

                    [
                        'status_lanjutan'      => $request->status,
                        'updated_at'            => date('Y-m-d H:i:s'),
                    ]
                );
                RecruitmentUserRecord::insert(
                    [
                        'id'                        => Uuid::uuid4(),
                        'recruitment_user_id'       => $request->id,
                        'status'                    => $request->status,
                        'created_at'                => date('Y-m-d H:i:s'),
                    ]
                );
                $get_wa = RecruitmentUser::with([
                    'AuthLogin' => function ($query) {
                        $query;
                    }
                ])->where('id', $request->id)->first();
                // dd($get_wa);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $get_wa->Authlogin->nomor_whatsapp,
                        'message' =>
                        "Pemberitahuan

Maaf Anda Belum lolos sesi interview dan ujian
",
                        'countryCode' => '62', //optional
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Xp5bwZZN22VPhojYcPEB' //change TOKEN to your actual token
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);

                if (isset($error_msg)) {
                    echo $error_msg;
                }
            } elseif ($request->status == '6b') {
                // dd($request->all());
                if ($request->online == 1) {
                    $link_wawancara = 'nullable';
                    $tempat_wawancara = 'required';
                } elseif ($request->online == 2) {
                    $link_wawancara = 'required';
                    $tempat_wawancara = 'nullable';
                }
                $validator = Validator::make(
                    $request->all(),
                    [
                        'lowongan_baru' => 'required',
                        'tanggal_wawancara' => 'required',
                        'link_wawancara' => $link_wawancara,
                        'tempat_wawancara' => $tempat_wawancara,
                        'waktu_wawancara' => 'required',
                    ],
                    [
                        'required' => ':attribute tidak boleh kosong'
                    ]
                );
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ]);
                }
                if ($request->online == 1) {
                    $tempat = $request->tempat_wawancara;
                } elseif ($request->online == 2) {
                    $tempat = $request->link_wawancara;
                }
                // dd($request->all());
                RecruitmentUser::where('id', $request->id)->update(

                    [
                        'tanggal_wawancara_manager'     => $request->tanggal_wawancara,
                        'tempat_wawancara'              => $tempat,
                        'waktu_wawancara'               => $request->waktu_wawancara,
                        'status_lanjutan'               => $request->status,
                        'tanggal_konfirmasi_manager'    => date('Y-m-d H:i:s', strtotime('+1 days')),
                        'updated_at'                    => date('Y-m-d H:i:s'),
                    ]
                );
                RecruitmentUserRecord::insert(
                    [
                        'id'                        => Uuid::uuid4(),
                        'lowongan_baru'             => $request->lowongan_baru,
                        'lowongan_lama'             => $request->lowongan_lama,
                        'recruitment_user_id'       => $request->id,
                        'status'                    => $request->status,
                        'created_at'                => date('Y-m-d H:i:s'),
                    ]
                );
                $get_wa = RecruitmentUser::with([
                    'AuthLogin' => function ($query) {
                        $query;
                    }
                ])->where('id', $request->id)->first();
                // dd($get_wa);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $get_wa->Authlogin->nomor_whatsapp,
                        'message' =>
                        "Pemberitahuan

selamat Anda lolos wawancara manager
",
                        'countryCode' => '62', //optional
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Xp5bwZZN22VPhojYcPEB' //change TOKEN to your actual token
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);

                if (isset($error_msg)) {
                    echo $error_msg;
                }
            } elseif ($request->status == '7b') {
                // dd($request->all);
                $validator = Validator::make(
                    $request->all(),
                    [
                        'gaji' => 'required',
                        'lowongan_baru' => 'required',
                        'tanggal_diterima' => 'required',
                    ],
                    [
                        'required' => ':attribute tidak boleh kosong'
                    ]
                );
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ]);
                }
                RecruitmentUser::where('id', $request->id)->update(

                    [
                        'status_lanjutan'       => $request->status,
                        'gaji'                  => $request->gaji,
                        'tanggal_diterima'      => $request->tanggal_diterima,
                        'notes'                 => $request->notes_langsung,
                        'konfirmasi_diterima'      => date('Y-m-d H:i:s', strtotime('+1 days')),
                        'updated_at'            => date('Y-m-d H:i:s'),
                    ]
                );
                RecruitmentUserRecord::insert(
                    [
                        'id'                        => Uuid::uuid4(),
                        'lowongan_baru'             => $request->lowongan_baru,
                        'lowongan_lama'             => $request->lowongan_lama,
                        'recruitment_user_id'       => $request->id,
                        'status'           => $request->status,
                        'created_at'                => date('Y-m-d H:i:s'),
                    ]
                );
                $get_wa = RecruitmentUser::with([
                    'AuthLogin' => function ($query) {
                        $query;
                    }
                ])->where('id', $request->id)->first();
                // dd($get_wa);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $get_wa->Authlogin->nomor_whatsapp,
                        'message' =>
                        "Pemberitahuan

selamat Anda lolos bekerja
",
                        'countryCode' => '62', //optional
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Xp5bwZZN22VPhojYcPEB' //change TOKEN to your actual token
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);

                if (isset($error_msg)) {
                    echo $error_msg;
                }
            }

            return response()->json([
                'code' => 200,
                'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    function pg_list_interview($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.interview.data_listinterview', [
            'title' => 'Data Recruitment',
            'holding'   => $holding,
            'id_recruitment'        => $id,
            'data_departemen' => Departemen::all(),
            'data_bagian' => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }

    function dt_list_interview($id)
    {
        $holding = request()->segment(count(request()->segments()));
        // dd($holding);
        $table =  RecruitmentUser::with([
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
            'AuthLogin' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'WaktuUjian' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'Cv' => function ($query) {
                $query->whereNotNull('users_career_id')->orderBy('id', 'ASC');
            },
            'DataInterview' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
        ])
            ->where('holding', $holding)
            ->where('recruitment_admin_id', $id)
            ->where('status_recruitmentuser', '!=', 0)
            ->where('status_recruitmentuser', '!=', 5)
            ->orderBy('nama_bagian', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('email', function ($row) {
                    $return = $row->AuthLogin->email;
                    return $return;
                })
                ->addColumn('ujian', function ($row) use ($holding) {
                    if (!$row->WaktuUjian != null) {
                        if ($row->DataInterview->status_interview == 0) {
                            $btn = '<button id="btn_warning"
                                        type="button" class="btn btn-sm" style="background-color:#e9ddff;">
                                        <i class="tf-icons mdi mdi-book"></i>
                                        Mulai&nbsp;Ujian
                                    </button>';
                            return $btn;
                        } else {
                            $btn = '<button id="btn_ujian"
                                    data-id_recruitment_user="' . $row->id . '"
                                    data-id_users_career="' . $row->AuthLogin->id . '"
                                    data-id_users_auth="' . $row->WaktuUjian . '"
                                    type="button" class="btn btn-sm" style="background-color:#e9ddff;">
                                    <i class="tf-icons mdi mdi-book"></i>
                                    Mulai&nbsp;Ujian
                                </button>';
                            return $btn;
                        }
                    } else {
                        if ($row->WaktuUjian->waktu_berakhir != null) {
                            return '<button
                                    type="button" class="btn btn-sm" style="background-color:#e9ddff;">
                                    <i class="tf-icons mdi mdi-book"></i>
                                    Ujian&nbsp;Selesai
                                </button>';
                        } else {
                            $btn = '<button id="btn_prosesujian"
                                        type="button" class="btn btn-sm" style="background-color:#e9ddff;">
                                        <i class="tf-icons mdi mdi-book"></i>
                                        Proses&nbsp;Ujian
                                    </button>';
                            return $btn;
                        }
                    }
                })
                ->addColumn('detail_cv', function ($row) use ($holding) {
                    $btn = '<button id="btn_lihat_cv"
                                data-id="' . $row->id . '"
                                data-nama_pelamar="' . $row->Cv->nama_depan . ' ' . $row->Cv->nama_tengah . ' ' . $row->Cv->nama_belakang . '"
                                data-tempat_lahir="' . $row->Cv->tempat_lahir . '"
                                data-tanggal_lahir="' . $row->Cv->tanggal_lahir . '"
                                data-gender="' . $row->Cv->gender . '"
                                data-status_nikah="' . $row->Cv->status_nikah . '"
                                data-nik="' . $row->Cv->nik . '"
                                data-departemen="' . $row->Bagian->Divisi->Departemen->nama_departemen . '"
                                data-divisi="' . $row->Bagian->Divisi->nama_divisi . '"
                                data-bagian="' . $row->Bagian->nama_bagian . '"
                                data-jabatan="' . $row->Bagian->nama_jabatan . '"
                                data-email="' . $row->Cv->email . '"
                                data-no_hp="' . $row->Cv->no_hp . '"
                                data-alamatktp="' . $row->Cv->detail_alamat . '"
                                data-nama_sdmi="' . $row->Cv->nama_sdmi . '"
                                data-tahun_sdmi="' . $row->Cv->tahun_sdmi . '"
                                data-nama_smpmts="' . $row->Cv->nama_smpmts . '"
                                data-tahun_smpmts="' . $row->Cv->tahun_smpmts . '"
                                data-nama_smamasmk="' . $row->Cv->nama_smamasmk . '"
                                data-tahun_smamasmk="' . $row->Cv->tahun_smamasmk . '"
                                data-nama_universitas="' . $row->Cv->nama_universitas . '"
                                data-tahun_universitas="' . $row->Cv->tahun_universitas . '"
                                data-judul_keterampilan1="' . $row->Cv->judul_keterampilan1 . '"
                                data-ket_keterampilan1="' . $row->Cv->ket_keterampilan1 . '"
                                data-judul_keterampilan2="' . $row->Cv->judul_keterampilan2 . '"
                                data-ket_keterampilan2="' . $row->Cv->ket_keterampilan2 . '"
                                data-judul_keterampilan3="' . $row->Cv->judul_keterampilan3 . '"
                                data-ket_keterampilan3="' . $row->Cv->ket_keterampilan3 . '"
                                data-judul_pengalaman1="' . $row->Cv->judul_pengalaman1 . '"
                                data-lokasi_pengalaman1="' . $row->Cv->lokasi_pengalaman1 . '"
                                data-tahun_pengalaman1="' . $row->Cv->tahun_pengalaman1 . '"
                                data-judul_pengalaman2="' . $row->Cv->judul_pengalaman2 . '"
                                data-lokasi_pengalaman2="' . $row->Cv->lokasi_pengalaman2 . '"
                                data-tahun_pengalaman2="' . $row->Cv->tahun_pengalaman2 . '"
                                data-judul_pengalaman3="' . $row->Cv->judul_pengalaman3 . '"
                                data-lokasi_pengalaman3="' . $row->Cv->lokasi_pengalaman3 . '"
                                data-tahun_pengalaman3="' . $row->Cv->tahun_pengalaman3 . '"
                                data-prestasi1="' . $row->Cv->prestasi1 . '"
                                data-prestasi2="' . $row->Cv->prestasi2 . '"
                                data-prestasi3="' . $row->Cv->prestasi3 . '"
                                data-img_ktp="' . $row->Cv->file_ktp . '"
                                data-img_kk="' . $row->Cv->file_kk . '"
                                data-img_ijazah="' . $row->Cv->file_ijazah . '"
                                data-img_pp="' . $row->Cv->file_pp . '"
                                type="button" class="btn btn-sm" style="background-color:#e9ddff">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Detail&nbsp;CV
                            </button>';
                    return $btn;
                })
                ->addColumn('penilaian', function ($row) use ($holding) {
                    $btn = '<button id="btn_penilaian"
                                data-recruitment_user_id="' . $row->id . '"
                                data-recruitment_interview_id="' . $row->DataInterview->id . '"
                                data-nama_pelamar="' . $row->Cv->nama_depan . ' ' . $row->Cv->nama_tengah . ' ' . $row->Cv->nama_belakang . '"
                                data-email="' . $row->Cv->email . '"
                                data-no_hp="' . $row->Cv->no_hp . '"
                                data-alamatktp="' . $row->Cv->detail_alamat . '"
                                data-nama_sdmi="' . $row->Cv->nama_sdmi . '"
                                data-tahun_sdmi="' . $row->Cv->tahun_sdmi . '"
                                data-nama_smpmts="' . $row->Cv->nama_smpmts . '"
                                data-tahun_smpmts="' . $row->Cv->tahun_smpmts . '"
                                data-nama_smamasmk="' . $row->Cv->nama_smamasmk . '"
                                data-tahun_smamasmk="' . $row->Cv->tahun_smamasmk . '"
                                data-nama_universitas="' . $row->Cv->nama_universitas . '"
                                data-tahun_universitas="' . $row->Cv->tahun_universitas . '"
                                data-nilai_ujian_analogi_verbal_antonim="' . $row->DataInterview->nilai_analogi_antonim . '"
                                data-nilai_ujian_analogi_verbal_sinonim="' . $row->DataInterview->nilai_analogi_sinonim . '"
                                data-nilai_ujian_nilai_penalaran="' . $row->DataInterview->nilai_penalaran . '"
                                data-nilai_ujian_nilai_aritmatika="' . $row->DataInterview->nilai_aritmatika . '"
                                data-nilai_total_psikotes="' . $row->DataInterview->nilai_aritmatika + $row->DataInterview->nilai_analogi_antonim + $row->DataInterview->nilai_analogi_sinonim + $row->DataInterview->nilai_penalaran . '"
                                data-nilai_kehadiran="' . $row->DataInterview->nilai_kehadiran . '"
                                data-catatan_ujian="' . $row->DataInterview->catatan_ujian . '"
                                data-nilai_leadership="' . $row->DataInterview->nilai_leadership . '"
                                data-catatan_leadership="' . $row->DataInterview->catatan_leadership . '"
                                data-nilai_planning="' . $row->DataInterview->nilai_planning . '"
                                data-catatan_planning="' . $row->DataInterview->catatan_planning . '"
                                data-nilai_problemsolving="' . $row->DataInterview->nilai_problemsolving . '"
                                data-catatan_problem_solving="' . $row->DataInterview->catatan_problem_solving . '"
                                data-nilai_quallity="' . $row->DataInterview->nilai_quallity . '"
                                data-catatan_quality="' . $row->DataInterview->catatan_quality . '"
                                data-nilai_creativity="' . $row->DataInterview->nilai_creativity . '"
                                data-catatan_creativity="' . $row->DataInterview->catatan_creativity . '"
                                data-nilai_teamwork="' . $row->DataInterview->nilai_teamwork . '"
                                data-catatan_teamwork="' . $row->DataInterview->catatan_teamwork . '"
                                data-total_nilai_interview_hrd="' . $row->DataInterview->total_nilai_interview_hrd . '"
                                data-nilai_interview_manager="' . $row->DataInterview->nilai_interview_manager . '"
                                data-catatan_interview_manager="' . $row->DataInterview->catatan_interview_manager . '"
                                data-img_pp="' . $row->file_pp . '"
                                data-status_interview="' . $row->DataInterview->status_interview . '"
                                data-status_interview_manager="' . $row->DataInterview->status_interview_manager . '"
                                type="button" class="btn btn-sm" style="background-color:#e9ddff">
                                <i class="tf-icons mdi mdi-account-edit me-1"></i>
                                Detail&nbsp;Penilaian
                            </button>';
                    return $btn;
                    // return 'b';
                })
                ->addColumn('status_kehadiran', function ($row) use ($holding) {
                    if ($row->DataInterview->status_interview == 0 || $row->DataInterview->status_interview == 1) {
                        $btn = '<button id="btn_kehadiran"
                                data-recruitment_user_id="' . $row->id . '"
                                data-recruitment_interview_id="' . $row->DataInterview->id . '"
                                type="button" class="btn btn-sm btn-primary ">
                                <i class="tf-icons mdi mdi-account-clock"></i>
                                &nbsp;Absensi
                            </button>';
                        return $btn;
                    } elseif ($row->DataInterview->status_interview == 3) {
                        return '<button id=""
                                type="button" class="btn btn-sm btn-success ">
                                <i class="tf-icons mdi mdi-account-clock"></i>
                                &nbsp;Hadir
                            </button>';
                    } elseif ($row->DataInterview->status_interview == 4) {
                        return '<button id=""
                                type="button" class="btn btn-sm btn-primary ">
                                <i class="tf-icons mdi mdi-account-clock"></i>
                                &nbsp;Tidak&nbsp;Hadir
                            </button>';
                    }
                    // return 'a';

                })
                ->addColumn('departemen_id', function ($row) {
                    $departemen_id = $row->nama_dept;
                    return $departemen_id;
                })
                ->addColumn('nama_departemen', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_departemen = NULL;
                    } else {
                        $nama_departemen = $row->Bagian->Divisi->Departemen->nama_departemen;
                    }
                    return $nama_departemen;
                })
                ->addColumn('nama_divisi', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_divisi = NULL;
                    } else {
                        $nama_divisi = $row->Bagian->Divisi->nama_divisi;
                    }
                    return $nama_divisi;
                })
                ->addColumn('nama_bagian', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_bagian = NULL;
                    } else {
                        $nama_bagian = $row->Bagian->nama_bagian;
                    }
                    return $nama_bagian;
                })
                ->rawColumns(['email', 'ujian', 'detail_cv', 'penilaian', 'status_kehadiran', 'departemen_id', 'nama_departemen', 'nama_divisi', 'nama_bagian'])
                ->make(true);
        }
    }

    function absensi_kehadiran_interview(Request $request)
    {
        if ($request->status_interview == 3) {
            RecruitmentInterview::where('id', $request->show_recruitmentinterviewid3)->update([
                'status_interview'  => 3,
                'nilai_kehadiran'   => 4
            ]);
            RecruitmentUser::where('id', $request->show_recruitmentuserid3)->update([
                'status_recruitmentuser'  => 3,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'activity' => 'create',
                'description' => 'Menambahkan data kehadrian ' . $request->name,
            ]);
            return redirect()->back()->with('success', 'data berhasil ditambahkan');
        } else {
            RecruitmentInterview::where('id', $request->show_recruitmentinterviewid3)->update([
                'status_interview'  => 4,
                'nilai_kehadiran'   => 0
            ]);
            RecruitmentUser::where('id', $request->show_recruitmentuserid3)->update([
                'status_recruitmentuser'  => 4,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'activity' => 'create',
                'description' => 'Menambahkan data kehadrian ' . $request->name,
            ]);
            return redirect()->back()->with('success', 'data berhasil ditambahkan');
        }
    }

    function kategori_ujian(Request $request)
    {
        // dd($request->all());
        $get_recruitment_admin_id = RecruitmentUser::where('id', $request->id_recruitmentuser)->first();
        $ujian = Ujian::where('kelas_id', $request->kelas)->get();
        foreach ($ujian as $u) {
            WaktuUjian::insert([
                'kode'      => $u->kode,
                'auth_id'   => $request->id_userscareer,
                'recruitment_admin_id' => $get_recruitment_admin_id->recruitment_admin_id
            ]);
        }
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Mengaktifkan ujian user' . $request->name,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Dibuat');
    }

    function pg_ujian()
    {
        $holding = request()->segment(count(request()->segments()));
        $pembobotan = Pembobotan::first();
        return view('admin.recruitment-users.ujian.data_ujian', [
            'holding' => $holding,
            'title' => 'Data Ujian',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/public/assets/cbt-malela/plugins/table/datatable/datatables.css") . '">
                <link rel="stylesheet" type="text/css" href="' . url("/public/assets/cbt-malela/plugins/table/datatable/dt-global_style.css") . '">
                <script src="' . url("/public/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'pembobotan' => $pembobotan
        ]);
    }
    function pembobotan_post(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'esai' => 'required|numeric',
                'pilihan_ganda' => 'required|numeric',
                'interview' => 'required|numeric',
                'interview_user' => 'required|numeric',
            ],
            [
                'required' => ':attribute tidak boleh kosong'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            Pembobotan::where('pembobotan_id', $request->pembobotan_id)->update([
                'esai' => $request->esai,
                'pilihan_ganda' => $request->pilihan_ganda,
                'interview' => $request->interview,
                'interview_user' => $request->interview_user,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_keahlian' => $data_keahlian,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    function dt_pembobotan()
    {
        $data = Pembobotan::get();
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('esai', function ($row) {
                    return $row->esai . '%';
                })
                ->addColumn('pilihan_ganda', function ($row) {
                    return $row->pilihan_ganda . '%';
                })
                ->addColumn('interview', function ($row) {
                    return $row->interview . '%';
                })
                ->addColumn('interview_user', function ($row) {
                    return $row->interview_user . '%';
                })
                ->addColumn('option', function ($row) {
                    return '
                        <button type="button" id="btn_modal_pembobotan"
                            data-pembobotan_id="' . $row->pembobotan_id . '"
                            data-esai="' . $row->esai . '"
                            data-pilihan_ganda="' . $row->pilihan_ganda . '"
                            data-interview="' . $row->interview . '"
                            data-interview_user="' . $row->interview_user . '"
                            class="btn btn-icon btn-info waves-effect waves-light">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </button>';
                })
                ->rawColumns(['esai', 'pilihan_ganda', 'interview', 'option'])
                ->make(true);
        }
    }


    function dt_ujian()
    {
        $data = Ujian::with([
            'ujianKategori' => function ($query) {
                $query;
            }
        ])
            ->where('esai', '0')
            ->orderBy('created_at', 'ASC')
            ->get();
        // dd($data);
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('kategori', function ($row) {
                    return $row->ujianKategori->nama_kategori;
                })
                ->addColumn('nol', function ($row) {
                    if ($row->nol == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('satu', function ($row) {
                    if ($row->satu == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('dua', function ($row) {
                    if ($row->dua == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('tiga', function ($row) {
                    if ($row->tiga == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('empat', function ($row) {
                    if ($row->empat == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('lima', function ($row) {
                    if ($row->lima == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('lima', function ($row) {
                    if ($row->lima == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('enam', function ($row) {
                    if ($row->enam == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('option', function ($row) {
                    if ($row->jenis == 0) {
                        $holding = request()->segment(count(request()->segments()));
                        return '<a href="/show-ujian/' . $row->kode . '/' . $holding . '" class="btn btn-primary btn-sm m-1">
                                    <span class="mdi mdi-eye-outline"></span>
                                </a>

                                  <a href="/edit-ujian/' . $row->kode . '/' . $holding . '" class="btn btn-info btn-sm m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </a>';
                    } elseif ($row->jenis == 1) {
                        return '<a href="/show-ujian_essay/' . $row->kode . '" class="btn btn-primary btn-sm">
                                    <span class="mdi mdi-eye-outline"></span>
                                </a>';
                    }
                })
                ->rawColumns(['created_at', 'kategori', 'nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'option'])
                ->make(true);
        }
    }
    function dt_esai()
    {
        $data = Ujian::with([
            'ujianKategori' => function ($query) {
                $query;
            }
        ])
            ->where('esai', '1')
            ->orderBy('created_at', 'ASC')
            ->get();
        // dd($data);
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('kategori', function ($row) {
                    return $row->ujianKategori->nama_kategori;
                })
                ->addColumn('nol', function ($row) {
                    if ($row->nol == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('satu', function ($row) {
                    if ($row->satu == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('dua', function ($row) {
                    if ($row->dua == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('tiga', function ($row) {
                    if ($row->tiga == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('empat', function ($row) {
                    if ($row->empat == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('lima', function ($row) {
                    if ($row->lima == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('lima', function ($row) {
                    if ($row->lima == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('enam', function ($row) {
                    if ($row->enam == '1') {
                        $jabatan = '<p class = "bg-success p-2 text-white">YA</p>';
                    } else {
                        $jabatan = '<p class = "bg-danger p-2 text-white">TIDAK</p>';
                    };
                    return $jabatan;
                })
                ->addColumn('option', function ($row) {
                    if ($row->jenis == 0) {
                        $holding = request()->segment(count(request()->segments()));
                        return '<a href="/show-esai/' . $row->kode . '/' . $holding . '" class="btn btn-primary btn-sm m-1">
                                    <span class="mdi mdi-eye-outline"></span>
                                </a>
                                 <a href="/edit-esai/' . $row->kode . '/' . $holding . '" class="btn btn-info btn-sm m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </a>';
                    } elseif ($row->jenis == 1) {
                        return '
                        <a href="/show-ujian_essay/ ' . $row->kode . '" class="btn btn-primary btn-sm">
                                    <span class="mdi mdi-eye-outline"></span>
                                </a>
                                <a href="/show-ujian_essay/ ' . $row->kode . '" class="btn btn-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </a>';
                    }
                })
                ->rawColumns(['created_at', 'kategori', 'nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'option'])
                ->make(true);
        }
    }
    function dt_ujian_kategori()
    {
        $data = UjianKategori::get();
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('nama_kategori', function ($row) {
                    return $row->nama_kategori;
                })
                ->addColumn('option', function ($row) {
                    $btn =
                        '<button type="button" id="btn_edit_ujian_kategori"
                            data-id="' . $row->id . '"
                            data-nama_kategori="' . $row->nama_kategori . '"
                            class="btn btn-icon btn-info waves-effect waves-light">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </button>
                        <button type="button" id="btn_delete_ujian_kategori"
                            data-id="' . $row->id . '"
                            class="btn btn-icon btn-danger waves-effect waves-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['created_at', 'nama_kategori', 'option'])
                ->make(true);
        }
    }
    public function delete_ujian_kategori(Request $request)
    {
        try {
            UjianKategori::where('id', $request->id)->delete();
            return response()->json([
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function ujian_kategori_post(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'nama_kategori' => 'required',
            ],
            [
                'required' => ':attribute tidak boleh kosong'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            UjianKategori::insert([
                'id' => Uuid::uuid4(),
                'nama_kategori' => $request->nama_kategori,
                'created_at'    => date('Y-m-d H:i:s'),

            ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_keahlian' => $data_keahlian,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function ujian_kategori_update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'nama_kategori' => 'required',
            ],
            [
                'required' => ':attribute tidak boleh kosong'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            UjianKategori::where('id', $request->id)->update([
                'nama_kategori' => $request->nama_kategori,
                'created_at'    => date('Y-m-d H:i:s'),

            ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_keahlian' => $data_keahlian,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function interview_admin_post(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'parameter' => 'required',
            ],
            [
                'required' => ':attribute tidak boleh kosong'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            InterviewAdmin::insert([
                'id'        => Uuid::uuid4(),
                'parameter' => $request->parameter,
                'deskripsi' => $request->deskripsi,
                'created_at' => date('Y-m-d H:i:s'),

            ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_keahlian' => $data_keahlian,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    function dt_interview_admin()
    {
        $data = InterviewAdmin::get();
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('parameter', function ($row) {
                    return $row->parameter;
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row->deskripsi;
                })
                ->addColumn('option', function ($row) {
                    $btn =
                        '<button type="button" id="btn_update_interview"
                            data-id="' . $row->id . '"
                            data-parameter="' . $row->parameter . '"
                            data-deskripsi="' . $row->deskripsi . '"
                            class="btn btn-icon btn-info waves-effect waves-light">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </button>
                        <button type="button" id="btn_delete_interview"
                            data-id="' . $row->id . '"
                            class="btn btn-icon btn-danger waves-effect waves-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </button>
                        ';
                    return $btn;
                })
                ->rawColumns(['created_at', 'parameter', 'deskripsi', 'option'])
                ->make(true);
        }
    }
    public function interview_admin_delete(Request $request)
    {
        try {
            InterviewAdmin::where('id', $request->id)->delete();
            return response()->json([
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function interview_admin_update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'parameter' => 'required',
            ],
            [
                'required' => ':attribute tidak boleh kosong'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }
        try {
            InterviewAdmin::where('id', $request->id)->update([
                'parameter' => $request->parameter,
                'deskripsi' => $request->deskripsi,
                'created_at'    => date('Y-m-d H:i:s'),

            ]);
            return response()->json([
                'code' => 200,
                // 'data' => $get_data,
                // 'data_keahlian' => $data_keahlian,
                // 'message' => 'Data Berhasil Diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }

    function show_ujian(Ujian $ujian)
    {
        // dd($ujian);
        $holding = request()->segment(count(request()->segments()));

        return view('admin.recruitment-users.ujian.data_show', [
            'title' => 'Detail Ujian Pilihan Ganda',
            'plugin' => '
                <link href="' . url("/public/assets/ew/css/style.css") . '" rel="stylesheet" type="text/css" />
                <script src="' . url("/public/assets/ew/js/examwizard.js") . '"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'ujian' => $ujian,
            'holding' => $holding
        ]);
    }
    function edit_ujian($kode)
    {
        // dd($ujian);
        $holding = request()->segment(count(request()->segments()));
        $ujian = Ujian::where('kode', $kode)->with([
            'ujianKategori' => function ($query) {
                $query;
            }
        ])->first();
        $detail_ujian = DetailUjian::where('kode', $kode)->get();

        return view('admin.recruitment-users.ujian.data_ujian_edit', [
            'title' => 'Detail Ujian Pilihan Ganda',
            'plugin' => '
                <link href="' . url("/public/assets/ew/css/style.css") . '" rel="stylesheet" type="text/css" />
                <script src="' . url("/public/assets/ew/js/examwizard.js") . '"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'ujian' => $ujian,
            'detail_ujian' => $detail_ujian,
            'holding' => $holding,
            'kategori' =>  UjianKategori::get(),
            'pembobotan' =>  Pembobotan::first()
        ]);
    }
    function show_esai(Ujian $ujian)
    {
        // dd($ujian);
        $holding = request()->segment(count(request()->segments()));

        return view('admin.recruitment-users.ujian.data_show_esai', [
            'title' => 'Detail Ujian Pilihan Ganda',
            'plugin' => '
                <link href="' . url("/public/assets/ew/css/style.css") . '" rel="stylesheet" type="text/css" />
                <script src="' . url("/public/assets/ew/js/examwizard.js") . '"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'ujian' => $ujian,
            'holding' => $holding
        ]);
    }

    function edit_esai($kode)
    {
        // dd($ujian);
        $holding = request()->segment(count(request()->segments()));
        $ujian = Ujian::where('kode', $kode)->with([
            'ujianKategori' => function ($query) {
                $query;
            }
        ])->first();
        $detail_esai = DetailEsai::where('kode', $kode)->get();
        // dd($detail_esai);

        return view('admin.recruitment-users.ujian.data_esai_edit', [
            'title' => 'Detail Ujian Pilihan Ganda',
            'plugin' => '
                <link href="' . url("/public/assets/ew/css/style.css") . '" rel="stylesheet" type="text/css" />
                <script src="' . url("/public/assets/ew/js/examwizard.js") . '"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'ujian' => $ujian,
            'detail_esai' => $detail_esai,
            'holding' => $holding,
            'kategori' =>  UjianKategori::get(),
            'pembobotan' =>  Pembobotan::first()
        ]);
    }

    function pg_ujian_pg()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ujian.data_ujian_create', [
            'holding'   => $holding,
            'title' => 'Tambah Ujian Pilihan Ganda',
            'plugin' => '
                <link href="' . asset("/assets/cbt-malela/plugins/file-upload/file-upload-with-preview.min.css") . '" rel="stylesheet" type="text/css" />
                <script src="' . asset("/assets/cbt-malela/plugins/file-upload/file-upload-with-preview.min.js") . '"></script>
                <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'kategori' =>  UjianKategori::get(),
            'pembobotan' =>  Pembobotan::first()
        ]);
    }
    function pg_esai_pg()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ujian.data_esai_create', [
            'holding'   => $holding,
            'title' => 'Tambah Ujian Pilihan Ganda',
            'plugin' => '
                <link href="' . asset("/assets/cbt-malela/plugins/file-upload/file-upload-with-preview.min.css") . '" rel="stylesheet" type="text/css" />
                <script src="' . asset("/assets/cbt-malela/plugins/file-upload/file-upload-with-preview.min.js") . '"></script>
                <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            ',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'kategori' =>  UjianKategori::get(),
            'pembobotan' =>  Pembobotan::first()
        ]);
    }

    function ujian_pg_store(Request $request)
    {
        // dd($request->all());
        // $siswa = UserCareer::where('kelas_id', $request->kelas)->get();
        // if ($siswa->count() == 0) {
        //     return redirect('pg-data-ujian/sp')->with('success', 'Belum ada user di kelas tersebut');
        // }

        $kode = Str::random(30);
        $ujian = [
            'kode' => $kode,
            'esai' => $request->esai,
            'nama' => $request->nama_ujian,
            'jenis' => 0,
            'kategori_id' => $request->kategori_id,
            'jam' => $request->jam,
            'menit' => $request->menit,
            'pembobotan_id' => $request->pembobotan_id,
            'acak' => $request->acak,
            'nol' => $request->nol,
            'satu' => $request->satu,
            'dua' => $request->dua,
            'tiga' => $request->tiga,
            'empat' => $request->empat,
            'lima' => $request->lima,
            'enam' => $request->enam,
            'soal_tampil' => $request->soal_tampil,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $detail_ujian = [];
        $index = 0;
        $nama_soal =  $request->soal;
        foreach ($nama_soal as $soal) {
            array_push($detail_ujian, [
                'kode' => $kode,
                'soal' => $soal,
                'pg_1' => $request->pg_1[$index],
                'pg_2' => $request->pg_2[$index],
                'pg_3' => $request->pg_3[$index],
                'pg_4' => $request->pg_4[$index],
                'pg_5' => $request->pg_5[$index],
                'jawaban' => $request->jawaban[$index]
            ]);
            $index++;
        }
        // $email_siswa = '';
        // $waktu_ujian = [];
        // foreach ($siswa as $s) {
        //     $email_siswa .= $s->email . ',';
        //     array_push($waktu_ujian, [
        //         'kode' => $kode,
        //         'auth_id' => $s->id
        //     ]);
        // }

        // $email_siswa = Str::replaceLast(',', '', $email_siswa);
        // $email_siswa = explode(',', $email_siswa);
        Ujian::insert($ujian);
        DetailUjian::insert($detail_ujian);
        // WaktuUjian::insert($waktu_ujian);
        return redirect('pg-data-ujian/' . $request->holding)->with('success', 'Ujian berhasil dibuat');
    }
    function ujian_pg_update(Request $request)
    {
        // dd($request->all());
        $ujian = [
            'nama' => $request->nama_ujian,
            'jenis' => 0,
            'pembobotan_id' => $request->pembobotan_id,
            'kategori_id' => $request->kategori_id,
            'jam' => $request->jam,
            'menit' => $request->menit,
            'acak' => $request->acak,
            'nol' => $request->nol,
            'satu' => $request->satu,
            'dua' => $request->dua,
            'tiga' => $request->tiga,
            'empat' => $request->empat,
            'lima' => $request->lima,
            'enam' => $request->enam,
            'soal_tampil' => $request->soal_tampil,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $get_detail = DetailUjian::where('kode', $request->kode)->get();
        foreach ($get_detail as $detail) {
            DetailUjian::where('id', $detail->id)->update([
                // $t = $request->soal[$request->id_detail_soal],
                'soal' =>  $request->soal[$detail->id],
                'pg_1' =>  $request->pg_1[$detail->id],
                'pg_2' =>  $request->pg_2[$detail->id],
                'pg_3' =>  $request->pg_3[$detail->id],
                'pg_4' =>  $request->pg_4[$detail->id],
                'pg_5' =>  $request->pg_5[$detail->id],
            ]);
            // dd($t);
        }
        // WaktuUjian::insert($waktu_ujian);
        Ujian::where('id', $request->id_soal)->update($ujian);
        return redirect('pg-data-ujian/' . $request->holding)->with('success', 'Ujian berhasil dibuat');
    }
    function esai_pg_store(Request $request)
    {
        $kode = Str::random(30);
        $ujian = [
            'kode' => $kode,
            'esai' => $request->esai,
            'nama' => $request->nama_ujian,
            'jenis' => 0,
            'pembobotan_id' => $request->pembobotan_id,
            'kategori_id' => $request->kategori_id,
            'jam' => $request->jam,
            'menit' => $request->menit,
            'acak' => $request->acak,
            'nol' => $request->nol,
            'satu' => $request->satu,
            'dua' => $request->dua,
            'tiga' => $request->tiga,
            'empat' => $request->empat,
            'lima' => $request->lima,
            'enam' => $request->enam,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $detail_ujian = [];
        $index = 0;
        $nama_soal =  $request->soal;
        foreach ($nama_soal as $soal) {
            array_push($detail_ujian, [
                'kode' => $kode,
                'soal' => $soal,
            ]);
            $index++;
        }
        Ujian::insert($ujian);
        DetailEsai::insert($detail_ujian);
        // WaktuUjian::insert($waktu_ujian);
        return redirect('pg-data-ujian/' . $request->holding)->with('success', 'Ujian berhasil dibuat');
    }
    function esai_pg_update(Request $request)
    {
        // dd($request->all());
        $ujian = [
            'esai' => $request->esai,
            'nama' => $request->nama_ujian,
            'jenis' => 0,
            'pembobotan_id' => $request->pembobotan_id,
            'kategori_id' => $request->kategori_id,
            'jam' => $request->jam,
            'menit' => $request->menit,
            'nol' => $request->nol,
            'satu' => $request->satu,
            'dua' => $request->dua,
            'tiga' => $request->tiga,
            'empat' => $request->empat,
            'lima' => $request->lima,
            'enam' => $request->enam,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $get_detail = DetailEsai::where('kode', $request->kode)->get();
        foreach ($get_detail as $detail) {
            DetailEsai::where('id', $detail->id)->update([
                // $t = $request->soal[$request->id_detail_soal];
                'soal' =>  $request->soal_update[$detail->id]
            ]);
            // dd($t);
        }
        // if ($request->has('soal')) {
        //     $detail_ujian = [];
        //     $index = 0;
        //     $nama_soal =  $request->soal;
        //     foreach ($nama_soal as $soal) {
        //         array_push($detail_ujian, [
        //             'kode' => $request->kode,
        //             'soal' => $soal,
        //         ]);
        //         $index++;
        //     }
        //     // dd($nama_soal);
        //     DetailEsai::insert($detail_ujian);
        // }
        // WaktuUjian::insert($waktu_ujian);
        Ujian::where('id', $request->id_soal)->update($ujian);
        return redirect('pg-data-ujian/' . $request->holding)->with('success', 'Ujian berhasil dibuat');
    }

    function ujian_pg_show(Ujian $ujian)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ujian.data_showujian', [
            'title' => 'Detail Ujian Pilihan Ganda',
            'menu' => [
                'menu' => 'ujian',
                'expanded' => 'ujian'
            ],
            'guru' => User::firstWhere('id', Auth::user()->id),
            'ujian' => $ujian,
            'holding' => $holding
        ]);
    }

    function ujian_pg_destroy(Ujian $ujian)
    {
        $data = WaktuUjian::where('kode', $ujian->kode)->delete();
        if ($ujian->jenis == 0) {
            $data = DetailUjian::where('kode', $ujian->kode)->delete();
            $data = PgSiswa::where('kode', $ujian->kode)->delete();
        } else {
            $data = DetailEssay::where('kode', $ujian->kode)->delete();
            $data = EssaySiswa::where('kode', $ujian->kode)->delete();
        }
        $data = Ujian::destroy($ujian->id);
        return redirect()->back()->with('success', 'data berhasil dihapus');
    }

    function nilai_interview_hrd(Request $request)
    {
        // dd($request->all());
        // dd($request->nilai_leadership + $request->nilai_planning + $request->nilai_problemsolving + $request->nilai_quallity + $request->nilai_creativity + $request->nilai_teamwork);
        RecruitmentInterview::where('id', $request->recruitment_interview_id1)->update([
            'nilai_leadership'          => $request->nilai_leadership,
            'catatan_leadership'        => $request->catatan_leadership,
            'nilai_planning'            => $request->nilai_planning,
            'catatan_planning'          => $request->catatan_planning,
            'nilai_problemsolving'      => $request->nilai_problemsolving,
            'catatan_problem_solving'   => $request->catatan_problem_solving,
            'nilai_quallity'            => $request->nilai_quallity,
            'catatan_quality'           => $request->catatan_quality,
            'nilai_creativity'          => $request->nilai_creativity,
            'catatan_creativity'        => $request->catatan_creativity,
            'nilai_teamwork'            => $request->nilai_teamwork,
            'catatan_teamwork'          => $request->catatan_teamwork,
            'total_nilai_interview_hrd' => 4 + ($request->nilai_leadership + $request->nilai_planning + $request->nilai_problemsolving + $request->nilai_quallity + $request->nilai_creativity + $request->nilai_teamwork),
            'status_interview_manager'  => $request->status_interview_manager
        ]);
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Nilai Interview ' . $request->name,
        ]);
        return redirect()->back()->with('success', 'data berhasil ditambahkan');
    }

    function nilai_interview_manager(Request $request)
    {
        // dd($request->all());
        RecruitmentInterview::where('id', $request->recruitment_interview_id2)->update([
            'nilai_interview_manager'   => $request->nilai_interview_manager,
            'catatan_interview_manager' => $request->catatan_interview_manager,
        ]);
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Nilai Interview ' . $request->name,
        ]);
        return redirect()->back()->with('success', 'data berhasil ditambahkan');
    }

    function pg_ranking()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ranking.data_rankinginterview', [
            // return view('karyawan.index', [
            'title' => 'Data Ranking',
            'holding'   => $holding,
        ]);
    }

    function dt_data_ranking()
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Recruitment::with([
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
            ->where('holding_recruitment', $holding)
            ->orderBy('created_at', 'DESC')
            ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->addColumn('nama_departemen', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_departemen = NULL;
                    } else {
                        $nama_departemen = $row->Bagian->Divisi->Departemen->nama_departemen;
                    }
                    return $nama_departemen;
                })
                ->addColumn('nama_divisi', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_divisi = NULL;
                    } else {
                        $nama_divisi = $row->Bagian->Divisi->nama_divisi;
                    }
                    return $nama_divisi;
                })
                ->addColumn('nama_bagian', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_bagian = NULL;
                    } else {
                        $nama_bagian = $row->Bagian->nama_bagian;
                    }
                    return $nama_bagian;
                })
                ->addColumn('pelamar', function ($row) use ($holding) {
                    $url = url('/pg/data-list-ranking/' . $row->id . '/' . $holding);
                    $btn = '<a href="' . $url . '" class="btn btn-sm btn-primary">
                                <i class="tf-icons mdi mdi-podium-gold me-1"></i>
                                &nbsp;List&nbsp;Ranking
                            </a>';
                    return $btn;
                })
                ->rawColumns(['created_at', 'nama_departemen', 'nama_divisi', 'nama_bagian', 'pelamar'])
                ->make(true);
        }
    }

    function pg_list_ranking($id)
    {
        $currentDate = date('Y-m-d');
        $recruitment_admin = Recruitment::with([
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
                ]);
            },

        ])
            ->where('deadline_recruitment', '>=', $currentDate)
            ->get();
        $holding = request()->segment(count(request()->segments()));
        // dd($holding);
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ranking.data_listranking', [
            'title' => 'Data Recruitment',
            'holding'   => $holding,
            'recruitment_admin'   => $recruitment_admin,
            'id_recruitment'        => $id,
            'data_departemen' => Departemen::all(),
            'data_bagian' => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }
    function dt_list_ranking($id)
    {
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
            ->where('recruitment_admin_id', $id)
            ->get();

        // dd($table);

        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->Cv->nama_lengkap;
                })
                ->addColumn('total_koefisien', function ($row) {
                    $esai_total = $row->ujianEsaiJawab->sum('nilai') ?? 0;
                    $pg_total = $row->waktuujian->sum('nilai') ?? 0;
                    $get_jabatan = $row->Jabatan->LevelJabatan->level_jabatan;
                    if ($get_jabatan == 0) {
                        $esai_count = Ujian::where('esai', 1)->where('nol', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('nol', '1')->count();
                    } elseif ($get_jabatan == 1) {
                        $esai_count = Ujian::where('esai', 1)->where('satu', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('satu', '1')->count();
                    } elseif ($get_jabatan == 2) {
                        $esai_count = Ujian::where('esai', 1)->where('dua', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('dua', '1')->count();
                    } elseif ($get_jabatan == 3) {
                        $esai_count = Ujian::where('esai', 1)->where('tiga', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('tiga', '1')->count();
                    } elseif ($get_jabatan == 4) {
                        $esai_count = Ujian::where('esai', 1)->where('empat', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('empat', '1')->count();
                    } elseif ($get_jabatan == 5) {
                        $esai_count = Ujian::where('esai', 1)->where('lima', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('lima', '1')->count();
                    } elseif ($get_jabatan == 6) {
                        $esai_count = Ujian::where('esai', 1)->where('enam', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('enam', '1')->count();
                    }
                    $interview_user = $row->interviewUser->sum('nilai') ?? 0;
                    $interview_admin = InterviewUser::where('recruitment_user_id', $row->id)->count() ?? 0;
                    $get_bobot = Pembobotan::first();
                    try {
                        $koefisien_esai = ($esai_total / $esai_count) * ($get_bobot->esai / 100);
                        $koefisien_pg = ($pg_total / $pg_count) * ($get_bobot->pilihan_ganda / 100);
                        $koefisien_interview = ($interview_user / $interview_admin * 10) * ($get_bobot->interview / 100);
                    } catch (DivisionByZeroError $e) {
                        $koefisien_esai = 0;
                        $koefisien_pg = 0;
                        $koefisien_interview = 0;
                    }


                    return round($koefisien_esai + $koefisien_pg + $koefisien_interview, 2);
                })
                ->addColumn('esai_average', function ($row) {

                    $esai_total = $row->ujianEsaiJawab->sum('nilai') ?? 0;
                    $get_jabatan = $row->Jabatan->LevelJabatan->level_jabatan;
                    if ($get_jabatan == 0) {
                        $esai_count = Ujian::where('esai', 1)->where('nol', '1')->count();
                    } elseif ($get_jabatan == 1) {
                        $esai_count = Ujian::where('esai', 1)->where('satu', '1')->count();
                    } elseif ($get_jabatan == 2) {
                        $esai_count = Ujian::where('esai', 1)->where('dua', '1')->count();
                    } elseif ($get_jabatan == 3) {
                        $esai_count = Ujian::where('esai', 1)->where('tiga', '1')->count();
                    } elseif ($get_jabatan == 4) {
                        $esai_count = Ujian::where('esai', 1)->where('empat', '1')->count();
                    } elseif ($get_jabatan == 5) {
                        $esai_count = Ujian::where('esai', 1)->where('lima', '1')->count();
                    } elseif ($get_jabatan == 6) {
                        $esai_count = Ujian::where('esai', 1)->where('enam', '1')->count();
                    }
                    return round($esai_total / $esai_count, 2);
                })

                ->addColumn('bobot_esai', function ($row) {
                    $get_bobot = Pembobotan::first();
                    $bobot = $get_bobot->esai;
                    return $bobot . '%';
                })
                ->addColumn('pg_average', function ($row) {

                    $pg_total = $row->waktuujian->sum('nilai') ?? 0;
                    $get_jabatan = $row->Jabatan->LevelJabatan->level_jabatan;
                    if ($get_jabatan == 0) {
                        $pg_count = Ujian::where('esai', 0)->where('nol', '1')->count();
                    } elseif ($get_jabatan == 1) {
                        $pg_count = Ujian::where('esai', 0)->where('satu', '1')->count();
                    } elseif ($get_jabatan == 2) {
                        $pg_count = Ujian::where('esai', 0)->where('dua', '1')->count();
                    } elseif ($get_jabatan == 3) {
                        $pg_count = Ujian::where('esai', 0)->where('tiga', '1')->count();
                    } elseif ($get_jabatan == 4) {
                        $pg_count = Ujian::where('esai', 0)->where('empat', '1')->count();
                    } elseif ($get_jabatan == 5) {
                        $pg_count = Ujian::where('esai', 0)->where('lima', '1')->count();
                    } elseif ($get_jabatan == 6) {
                        $pg_count = Ujian::where('esai', 0)->where('enam', '1')->count();
                    }
                    return round($pg_total / $pg_count, 2);
                })
                ->addColumn('bobot_pg', function ($row) {
                    $get_bobot = Pembobotan::first();
                    $bobot = $get_bobot->pilihan_ganda;
                    return $bobot . '%';
                })
                ->addColumn('interview_average', function ($row) {

                    try {
                        $interview_user = $row->interviewUser->sum('nilai') ?? 0;
                        $interview_admin = InterviewUser::where('recruitment_user_id', $row->id)->count() ?? 0;
                        $hasil = $interview_user / $interview_admin;
                    } catch (DivisionByZeroError $e) {
                        $hasil = 0;
                    }
                    return round($hasil * 10, 2);
                })
                ->addColumn('bobot_interview', function ($row) {
                    $get_bobot = Pembobotan::first();
                    $bobot = $get_bobot->interview;
                    return $bobot . '%';
                })

                ->rawColumns(['nama_lengkap', 'total_koefisien', 'esai_average', 'bobot_esai', 'pg_average', 'bobot_pg', 'interview_average', 'bobot_interview'])
                ->make(true);
        }
    }
    function dt_list_progres($id)
    {
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
            ->where('recruitment_admin_id', $id)
            ->get();

        // dd($table);

        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->Cv->nama_lengkap;
                })
                ->addColumn('pilih_status', function ($row) {
                    return '<button
                                data-id="' . $row->id . '"
                                type="button" class="btn btn-sm btn-info " id="btn_status_ranking">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Pilih&nbsp;
                            </button>' . '<button
                                data-id="' . $row->id . '"
                                type="button" class="btn btn-sm btn-info " id="btn_lolos">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Pilih&nbsp;
                            </button>' . '<button
                                data-id="' . $row->id . '"
                                type="button" class="btn btn-sm btn-info " id="btn_pemindahan">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Pindah&nbsp;
                            </button>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status_lanjutan == null) {
                        return '<p class="bg-secondary p-2 text-white">Belum Ditentukan</p>';
                    } elseif ($row->status_lanjutan == '1b') {
                        return '<p class="bg-warning p-2 text-white">Interview Manager</p>';
                    } elseif ($row->status_lanjutan == '2b') {
                        return '<p class="bg-success p-2 text-white">Diterima Bekerja</p>';
                    } elseif ($row->status_lanjutan == '3b') {
                        return '<p class="bg-danger p-2 text-white">Tidak Lolos</p>';
                    } elseif ($row->status_lanjutan == '4b') {
                        return '<p class="bg-warning p-2 text-white">Lolos Interview Manager</p>';
                    } elseif ($row->status_lanjutan == '5b') {
                        return '<p class="bg-danger p-2 text-white">Ditolak Manager</p>';
                    } elseif ($row->status_lanjutan == '6b') {
                        return '<p class="bg-warning p-2 text-white">Perubahan Posisi Lowongan</p>';
                    } elseif ($row->status_lanjutan == '7b') {
                        return '<p class="bg-success p-2 text-white">Lolos Posisi Lain</p>';
                    }
                })
                ->addColumn('total_koefisien', function ($row) {
                    $esai_total = $row->ujianEsaiJawab->sum('nilai') ?? 0;
                    $pg_total = $row->waktuujian->sum('nilai') ?? 0;
                    $get_jabatan = $row->Jabatan->LevelJabatan->level_jabatan;
                    if ($get_jabatan == 0) {
                        $esai_count = Ujian::where('esai', 1)->where('nol', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('nol', '1')->count();
                    } elseif ($get_jabatan == 1) {
                        $esai_count = Ujian::where('esai', 1)->where('satu', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('satu', '1')->count();
                    } elseif ($get_jabatan == 2) {
                        $esai_count = Ujian::where('esai', 1)->where('dua', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('dua', '1')->count();
                    } elseif ($get_jabatan == 3) {
                        $esai_count = Ujian::where('esai', 1)->where('tiga', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('tiga', '1')->count();
                    } elseif ($get_jabatan == 4) {
                        $esai_count = Ujian::where('esai', 1)->where('empat', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('empat', '1')->count();
                    } elseif ($get_jabatan == 5) {
                        $esai_count = Ujian::where('esai', 1)->where('lima', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('lima', '1')->count();
                    } elseif ($get_jabatan == 6) {
                        $esai_count = Ujian::where('esai', 1)->where('enam', '1')->count();
                        $pg_count = Ujian::where('esai', 0)->where('enam', '1')->count();
                    }

                    $interview_user = $row->interviewUser->sum('nilai') ?? 0;
                    $interview_admin = InterviewAdmin::count() ?? 0;
                    $get_bobot = Pembobotan::first();
                    try {
                        $koefisien_esai = ($esai_total / $esai_count) * ($get_bobot->esai / 100);
                        $koefisien_pg = ($pg_total / $pg_count) * ($get_bobot->pilihan_ganda / 100);
                        $koefisien_interview = ($interview_user / $interview_admin * 10) * ($get_bobot->interview / 100);
                    } catch (DivisionByZeroError $e) {
                        $koefisien_esai = 0;
                        $koefisien_pg = 0;
                        $koefisien_interview = 0;
                    }
                    return round($koefisien_esai + $koefisien_pg + $koefisien_interview, 2);
                })->addColumn('feedback', function ($row) {
                    if ($row->feedback_lanjutan == '1b') {
                        return '<p class="bg-warning p-2 text-white">Menyanggupi</p>';
                    } elseif ($row->feedback_lanjutan == '2b') {
                        return '<p class="bg-success p-2 text-white">Menyanggupi</p>';
                    } elseif ($row->feedback_lanjutan == '3b') {
                        return '<p class="bg-danger p-2 text-white">Menolak</p>';
                    } elseif ($row->feedback_lanjutan == '4b') {
                        return '<p class="bg-warning p-2 text-white">Menerima</p>';
                    }
                })
                ->addColumn('alasan_lanjutan', function ($row) {
                    return $row->alasan_lanjutan;
                })
                ->rawColumns(['nama_lengkap', 'pilih_status', 'status', 'total_koefisien', 'feedback', 'alasan_lanjutan'])
                ->make(true);
        }
    }


    // function dt_list_ranking($id)
    // {
    //     $holding = request()->segment(count(request()->segments()));
    //     $table =  RecruitmentUser::with([
    //         'Bagian' =>  function ($query) {
    //             $query->with([
    //                 'Divisi' => function ($query) {
    //                     $query->with([
    //                         'Departemen' => function ($query) {
    //                             $query->orderBy('nama_departemen', 'ASC');
    //                         }
    //                     ]);
    //                     $query->orderBy('nama_divisi', 'ASC');
    //                 }
    //             ]);
    //             $query->orderBy('nama_bagian', 'ASC');
    //         },
    //         'AuthLogin' => function ($query) {
    //             $query->orderBy('id', 'ASC');
    //         },
    //         'WaktuUjian' => function ($query) {
    //             $query->orderBy('id', 'ASC');
    //         },
    //         'Cv' => function ($query) {
    //             $query->whereNotNull('users_career_id')->orderBy('id', 'ASC');
    //         },
    //         'DataInterview' => function ($query) {
    //             $query->orderBy('id', 'ASC');
    //         },
    //     ])
    //         ->where('holding', $holding)
    //         ->where('recruitment_admin_id', $id)
    //         ->where('status_recruitmentuser', '!=', 0)
    //         ->where('status_recruitmentuser', '!=', 5)
    //         ->orderBy('nama_bagian', 'ASC')->get();
    //     // dd($table);
    //     // if (request()->ajax()) {
    //     return DataTables::of($table)
    //         ->addColumn('nama_pelamar', function ($row) {
    //             if (($row->Cv->nama_depan != '' && $row->Cv->nama_tengah != '' && $row->Cv->nama_belakang != '')) {
    //                 $nama_pelamar = $row->Cv->nama_depan . ' ' . $row->Cv->nama_tengah . ' ' . $row->Cv->nama_belakang;
    //             } elseif ($row->Cv->nama_depan != '' && $row->Cv->nama_tengah != '' && $row->Cv->nama_belakang == '') {
    //                 $nama_pelamar = $row->Cv->nama_depan . ' ' . $row->Cv->nama_tengah;
    //             } else {
    //                 $nama_pelamar = $row->Cv->nama_depan;
    //             }
    //             return $nama_pelamar;
    //         })
    //         ->addColumn('status_nilai', function ($row) use ($holding) {
    //             $data1 = (($row->DataInterview->nilai_analogi_antonim +
    //                 $row->DataInterview->nilai_analogi_sinonim +
    //                 $row->DataInterview->nilai_penalaran +
    //                 $row->DataInterview->nilai_aritmatika) * 70) / 100;
    //             $data2 = (($row->DataInterview->nilai_kehadiran +
    //                 $row->DataInterview->nilai_leadership +
    //                 $row->DataInterview->nilai_planning +
    //                 $row->DataInterview->nilai_problemsolving +
    //                 $row->DataInterview->nilai_quallity +
    //                 $row->DataInterview->nilai_creativity +
    //                 $row->DataInterview->nilai_teamwork) * 30) / 100;
    //             $return = $data1 + $data2;
    //             if ($return > 90) {
    //                 return '<span class="badge bg-label-success rounded-pill">Rekomendasi A+ </span>';
    //             } elseif ($return < 90 && $return > 75) {
    //                 return '<span class="badge bg-label-success rounded-pill">Rekomendasi A</span>';
    //             } elseif ($return < 75 && $return > 61) {
    //                 return '<span class="badge bg-label-info rounded-pill">Dipertimbangkan</span>';
    //             } else {
    //                 return '<span class="badge bg-label-danger rounded-pill">Ditolak</span>';
    //             }
    //         })
    //         ->addColumn('nilai_akhir', function ($row) use ($holding) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->status_interview == 3) {
    //                     $data1 = (($row->DataInterview->nilai_analogi_antonim +
    //                         $row->DataInterview->nilai_analogi_sinonim +
    //                         $row->DataInterview->nilai_penalaran +
    //                         $row->DataInterview->nilai_aritmatika) * 70) / 100;
    //                     $data2 = (($row->DataInterview->nilai_kehadiran +
    //                         $row->DataInterview->nilai_leadership +
    //                         $row->DataInterview->nilai_planning +
    //                         $row->DataInterview->nilai_problemsolving +
    //                         $row->DataInterview->nilai_quallity +
    //                         $row->DataInterview->nilai_creativity +
    //                         $row->DataInterview->nilai_teamwork) * 30) / 100;
    //                     $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;' . $data1 + $data2 . '&nbsp;</span>';
    //                     return $return;
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('detail_cv', function ($row) use ($holding) {
    //             $btn = '<button id="btn_lihat_cv"
    //                             data-id="' . $row->id . '"
    //                             data-nama_pelamar="' . $row->Cv->nama_depan . ' ' . $row->Cv->nama_tengah . ' ' . $row->Cv->nama_belakang . '"
    //                             data-tempat_lahir="' . $row->Cv->tempat_lahir . '"
    //                             data-tanggal_lahir="' . $row->Cv->tanggal_lahir . '"
    //                             data-gender="' . $row->Cv->gender . '"
    //                             data-status_nikah="' . $row->Cv->status_nikah . '"
    //                             data-nik="' . $row->Cv->nik . '"
    //                             data-departemen="' . $row->Bagian->Divisi->Departemen->nama_departemen . '"
    //                             data-divisi="' . $row->Bagian->Divisi->nama_divisi . '"
    //                             data-bagian="' . $row->Bagian->nama_bagian . '"
    //                             data-jabatan="' . $row->Bagian->nama_jabatan . '"
    //                             data-email="' . $row->Cv->email . '"
    //                             data-no_hp="' . $row->Cv->no_hp . '"
    //                             data-alamatktp="' . $row->Cv->detail_alamat . '"
    //                             data-nama_sdmi="' . $row->Cv->nama_sdmi . '"
    //                             data-tahun_sdmi="' . $row->Cv->tahun_sdmi . '"
    //                             data-nama_smpmts="' . $row->Cv->nama_smpmts . '"
    //                             data-tahun_smpmts="' . $row->Cv->tahun_smpmts . '"
    //                             data-nama_smamasmk="' . $row->Cv->nama_smamasmk . '"
    //                             data-tahun_smamasmk="' . $row->Cv->tahun_smamasmk . '"
    //                             data-nama_universitas="' . $row->Cv->nama_universitas . '"
    //                             data-tahun_universitas="' . $row->Cv->tahun_universitas . '"
    //                             data-judul_keterampilan1="' . $row->Cv->judul_keterampilan1 . '"
    //                             data-ket_keterampilan1="' . $row->Cv->ket_keterampilan1 . '"
    //                             data-judul_keterampilan2="' . $row->Cv->judul_keterampilan2 . '"
    //                             data-ket_keterampilan2="' . $row->Cv->ket_keterampilan2 . '"
    //                             data-judul_keterampilan3="' . $row->Cv->judul_keterampilan3 . '"
    //                             data-ket_keterampilan3="' . $row->Cv->ket_keterampilan3 . '"
    //                             data-judul_pengalaman1="' . $row->Cv->judul_pengalaman1 . '"
    //                             data-lokasi_pengalaman1="' . $row->Cv->lokasi_pengalaman1 . '"
    //                             data-tahun_pengalaman1="' . $row->Cv->tahun_pengalaman1 . '"
    //                             data-judul_pengalaman2="' . $row->Cv->judul_pengalaman2 . '"
    //                             data-lokasi_pengalaman2="' . $row->Cv->lokasi_pengalaman2 . '"
    //                             data-tahun_pengalaman2="' . $row->Cv->tahun_pengalaman2 . '"
    //                             data-judul_pengalaman3="' . $row->Cv->judul_pengalaman3 . '"
    //                             data-lokasi_pengalaman3="' . $row->Cv->lokasi_pengalaman3 . '"
    //                             data-tahun_pengalaman3="' . $row->Cv->tahun_pengalaman3 . '"
    //                             data-prestasi1="' . $row->Cv->prestasi1 . '"
    //                             data-prestasi2="' . $row->Cv->prestasi2 . '"
    //                             data-prestasi3="' . $row->Cv->prestasi3 . '"
    //                             data-img_ktp="' . $row->Cv->file_ktp . '"
    //                             data-img_kk="' . $row->Cv->file_kk . '"
    //                             data-img_ijazah="' . $row->Cv->file_ijazah . '"
    //                             data-img_pp="' . $row->Cv->file_pp . '"
    //                             type="button" class="btn btn-sm" style="background-color:#e9ddff">
    //                             <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
    //                             Detail&nbsp;CV
    //                         </button>';
    //             return $btn;
    //         })
    //         ->addColumn('nilai_analogi_antonim', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_analogi_antonim != 0 && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_analogi_antonim == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_analogi_antonim . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_analogi_sinonim', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_analogi_sinonim != 0 && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_analogi_sinonim == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_analogi_sinonim . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_penalaran', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_penalaran != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_penalaran == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_penalaran . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_aritmatika', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_aritmatika != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_aritmatika == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_aritmatika . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('total_nilai_psikotes', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_aritmatika != null && $row->DataInterview->status_interview == 3) {
    //                     $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;' .
    //                         $row->DataInterview->nilai_aritmatika +
    //                         $row->DataInterview->nilai_penalaran +
    //                         $row->DataInterview->nilai_analogi_antonim +
    //                         $row->DataInterview->nilai_analogi_sinonim
    //                         . '&nbsp;</span>';
    //                     return $return;
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_kehadiran', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_kehadiran != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_kehadiran == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_kehadiran . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_leadership', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_leadership != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_leadership == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_leadership . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_planning', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_planning != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_planning == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_planning . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_problemsolving', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_problemsolving != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_problemsolving == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_problemsolving . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_quallity', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_quallity != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_quallity == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_quallity . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_creativity', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_creativity != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_creativity == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_creativity . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('nilai_teamwork', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_teamwork != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_teamwork == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;' . $row->DataInterview->nilai_teamwork . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-primary" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->addColumn('total_nilai_interview', function ($row) {
    //             if (isset($row->DataInterview) && $row->DataInterview !== null) {
    //                 if ($row->DataInterview->nilai_teamwork != null && $row->DataInterview->status_interview == 3) {
    //                     if ($row->DataInterview->nilai_teamwork == 0) {
    //                         $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;0&nbsp;</span>';
    //                         return $return;
    //                     } else {
    //                         $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;' .
    //                             $row->DataInterview->nilai_kehadiran +
    //                             $row->DataInterview->nilai_leadership +
    //                             $row->DataInterview->nilai_planning +
    //                             $row->DataInterview->nilai_problemsolving +
    //                             $row->DataInterview->nilai_quallity +
    //                             $row->DataInterview->nilai_creativity +
    //                             $row->DataInterview->nilai_teamwork
    //                             . '&nbsp;</span>';
    //                         return $return;
    //                     }
    //                 } else {
    //                     $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;0&nbsp;</span>';
    //                     return $return;
    //                 }
    //             } else {
    //                 $return = '<span class="badge badge-center rounded-pill bg-success" style="width:35%">&nbsp;0&nbsp;</span>';
    //                 return $return;
    //             }
    //         })
    //         ->rawColumns([
    //             'nama_pelamar',
    //             'status_nilai',
    //             'nilai_akhir',
    //             'detail_cv',
    //             'nilai_analogi_antonim',
    //             'nilai_analogi_sinonim',
    //             'nilai_penalaran',
    //             'nilai_aritmatika',
    //             'total_nilai_psikotes',
    //             'nilai_kehadiran',
    //             'nilai_leadership',
    //             'nilai_planning',
    //             'nilai_problemsolving',
    //             'nilai_quallity',
    //             'nilai_creativity',
    //             'nilai_teamwork',
    //             'total_nilai_interview',

    //         ])
    //         ->make(true);
    //     // }
    // }
}
