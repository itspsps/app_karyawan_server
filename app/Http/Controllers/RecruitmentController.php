<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Bagian;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DB;

class RecruitmentController extends Controller
{
    public function pg_recruitment()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.index', [
            // return view('karyawan.index', [
            'title'             => 'Data Recruitment',
            'holding'           => $holding,
            'data_departemen'   => Departemen::all(),
            'data_bagian'       => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_dept'         => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi'       => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }

    function dt_recruitment(Request $request)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Recruitment::with([
            'Bagian' =>  function ($query) {
                $query->with([
                    'Divisi' => function ($query) {
                    $query->with([
                        'Departemen' => function ($query) {
                        $query->orderBy('nama_departemen', 'ASC');
                    }]);
                    $query->orderBy('nama_divisi', 'ASC');
                }]);
                $query->orderBy('nama_bagian', 'ASC');
            },
        ])->orderBy('created_recruitment', 'desc')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_departemen', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_departemen = 'vv';
                    } else {
                        $nama_departemen = $row->Bagian->Divisi->Departemen->nama_departemen;
                    }
                    return $nama_departemen;
                })
                ->addColumn('nama_divisi', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_divisi = 'v';
                    } else {
                        $nama_divisi = $row->Bagian->Divisi->nama_divisi;
                    }
                    return $nama_divisi;
                })
                ->addColumn('nama_bagian', function ($row) {
                    if ($row->Bagian == NULL) {
                        $nama_bagian = 'a';
                    } else {
                        $nama_bagian = $row->Bagian->nama_bagian;
                    }
                    return $nama_bagian;
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
                    $url = url('/pg/data-list-pelamar/'. $row->id .'/'.$holding);
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
                            data-holding="' .$holding. '"
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
                            data-tanggal="' . $row->created_recruitment . '"
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
                ->rawColumns(['nama_departemen','nama_divisi','nama_bagian','desc_recruitment','pelamar','status_recruitment','option'])
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
            'created_recruitment'   => 'required|max:255',
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
                'created_recruitment'   => $validatedData['created_recruitment'],
                'desc_recruitment'      => $validatedData['desc_recruitment'],
            ]
        );
        // Merekam aktivitas pengguna
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Recruitment baru ' . $request->name,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }

    function update_status($id)
    {
        // dd($id);
        $holding = request()->segment(count(request()->segments()));
        $recruitment = Recruitment::where('id', $id)->where('holding_recruitment', $holding)->first();
        if($recruitment->status_recruitment == 0){
            Recruitment::where('id', $id)->where('holding_recruitment', $holding)->update([
                'status_recruitment' => 1,
            ]);
        }else{
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
        if($data == null){
            $hapus = Recruitment::where('id', $id)->where('holding_recruitment', $holding)->delete();
            if ($hapus) {
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'hapus',
                    'description' => 'Hapus data Recruitment' . Auth::user()->name,
                ]);
                return redirect()->back()->with('success', 'Data Berhasil di Hapus');
            } else {
                return redirect()->back()->with('error', 'Data  Gagal di Hapus');
            }
        }else{
            dd('stop');
        }

    }

    function pg_list_pelamar($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.list_pelamar',[
            'title' => 'Data Recruitment',
            'holding'   => $holding,
            'id_recruitment'        => $id,
            'data_departemen' => Departemen::all(),
            'data_bagian' => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }

    function dt_list_pelamar($id)
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  RecruitmentUser::with([
            'Bagian' =>  function ($query) {
                $query->with([
                    'Divisi' => function ($query) {
                    $query->with([
                        'Departemen' => function ($query) {
                        $query->orderBy('nama_departemen', 'ASC');
                    }]);
                    $query->orderBy('nama_divisi', 'ASC');
                }]);
                $query->orderBy('nama_bagian', 'ASC');
            },
            'Cv' => function ($query) {
                $query->whereNotNull('users_career_id')->orderBy('id', 'ASC');
            },
            'AuthLogin' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
        ])
        ->where('holding', $holding)
        ->where('recruitment_admin_id', $id)
        ->orderBy('nama_bagian', 'ASC')
        ->get();
        if (request()->ajax()) {
            return DataTables::of($table)
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
                                data-email="' . $row->email . '"
                                data-no_hp="' . $row->Cv->no_hp . '"
                                data-alamatktp="' . $row->Cv->alamat_ktp . '"
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
                                data-img_pp="' . $row->file_pp . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Detail&nbsp;CV
                            </button>';
                    return $btn;
                })
                ->addColumn('select', function ($row) {
                    $select = $row->id;
                    return $select;
                })
                ->addColumn('status_recruitment', function ($row) {
                    if($row->status_recruitmentuser == 0){
                        $return = '<span class="badge rounded-pill bg-warning">Panggil Interview</span>';
                    }elseif($row->status_recruitmentuser == 1){
                        $return = '<span class="badge rounded-pill bg-info">Terjadwal Interview</span>';
                    }elseif($row->status_recruitmentuser == 2 && $row->tanggal_interview < Carbon::now()->format('d/m/Y')){
                        $return = '<span class="badge rounded-pill bg-danger">Tidak Konfirmasi</span>';
                    }elseif($row->status_recruitmentuser == 3){
                        $return = '<span class="badge rounded-pill bg-success">Lolos Interview</span>';
                    }elseif($row->status_recruitmentuser == 4){
                        $return = '<span class="badge rounded-pill bg-success">Hadir Interview</span>';
                    }elseif($row->status_recruitmentuser == 5){
                        $return = '<span class="badge rounded-pill bg-dark">Tidak Lolos Administrasi</span>';
                    }
                    return $return;
                })
                ->addColumn('departemen_id', function ($row) {
                    $departemen_id = $row->nama_dept;
                    return $departemen_id;
                })
                ->addColumn('email', function ($row) {
                    $return = $row->AuthLogin->email;
                    return $return;
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
                ->rawColumns(['detail_cv','select','status_recruitment','departemen_id','email','nama_departemen','nama_divisi','nama_bagian'])
                ->make(true);
        }
    }

    // Lolos Administrasi -> Panggil INterview
    function lolos_administrasi(Request $request)
    {
        // dd($request->all());
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
                // Mail::send('admin.recruitment-users.email.email_interview', [
                //     'user' => $user,
                //     'tanggal_interview' => $request->tanggal_interview,
                //     'jam_interview' => $request->jam_interview,
                //     'lokasi_interview' => $request->lokasi_interview,
                // ], function ($message) use ($user) {
                //     $message->to(
                //         $user->email,
                //         $user->nama_depan,
                //         $user->nama_belakang);
                //     $message->subject('Interview Invitation');
                // });
                $insert = RecruitmentInterview::create(
                    [
                        'id'                    => Uuid::uuid4(),
                        'holding'               => $holding,
                        'recruitment_userid'    => $user->id,
                        'tanggal_interview'     => $request->tanggal_interview,
                        'jam_interview'         => $request->tanggal_interview,
                        'lokasi_interview'      => $request->lokasi_interview,
                    ]
                );
                RecruitmentUser::where('status_recruitmentuser', 0)->where('id',$user->id)->update(['status_recruitmentuser' => 1]);
                // Merekam aktivitas pengguna
                ActivityLog::create([
                    'user_id' => $request->user()->id,
                    'activity' => 'create',
                    'description' => 'Menambahkan data Recruitment Interview ' . $request->name,
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
                RecruitmentUser::where('status_recruitmentuser', 0)->where('id',$user->id)->update(['status_recruitmentuser' => 5]);
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
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.data_interview', [
            // return view('karyawan.index', [
            'title' => 'Data Interview',
            'holding'   => $holding,
        ]);
    }

    function dt_data_interview()
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  Recruitment::with([
            'Bagian' =>  function ($query) {
                $query->with([
                    'Divisi' => function ($query) {
                    $query->with([
                        'Departemen' => function ($query) {
                        $query->orderBy('nama_departemen', 'ASC');
                    }]);
                    $query->orderBy('nama_divisi', 'ASC');
                }]);
                $query->orderBy('nama_bagian', 'ASC');
            },
        ])->where('holding_recruitment', $holding)->orderBy('nama_bagian', 'ASC')->get();
        // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
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
                ->addColumn('desc_recruitment', function ($row) {

                    $btn = '<button id="btn_lihat_syarat"
                                data-id="' . $row->id . '"
                                data-desc="' . $row->desc_recruitment . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Lihat Syarat
                            </button>';
                    return $btn;
                })
                ->addColumn('pelamar', function ($row) use ($holding) {
                    $url = url('/pg/data-list-interview/'. $row->id .'/'.$holding);
                    $btn = '<a href="' . $url . '" class="btn btn-sm btn-info">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                List&nbsp;Interview
                            </a>';
                    return $btn;
                })
                ->addColumn('status_recruitment', function ($row) use ($holding) {
                    if ($row->status_recruitment == 0) {
                        $status = '<button id="btn_status_aktif"
                            data-id="' . $row->id . '"
                            data-holding="' .$holding. '"
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
                            data-tanggal="' . $row->created_recruitment . '"
                            data-holding="' . $holding . '"
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
                ->rawColumns(['nama_departemen','nama_divisi','nama_bagian','pelamar','status_recruitment'])
                ->make(true);
        }
    }

    function pg_list_interview($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.data_listinterview',[
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
                        }]);
                        $query->orderBy('nama_divisi', 'ASC');
                    }]);
                    $query->orderBy('nama_bagian', 'ASC');
                },
                'AuthLogin' => function ($query) {
                    $query->orderBy('id', 'ASC');
                },
                'WaktuUjian' => function($query){
                    $query->orderBy('id','ASC');
                },
                'DataInterview' => function ($query){
                    $query->orderBy('id', 'ASC');
                }
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
                    if(!$row->WaktuUjian != null){
                        $btn = '<button id="btn_ujian"
                                data-id_recruitment_user="' . $row->id . '"
                                data-id_users_career="' . $row->AuthLogin->id . '"
                                data-id_users_auth="' . $row->WaktuUjian . '"
                                type="button" class="btn btn-sm" style="background-color:#e9ddff;">
                                <i class="tf-icons mdi mdi-book"></i>
                                Mulai&nbsp;Ujian
                            </button>';
                        return $btn;
                    }else{
                        $btn = '<button id="btn_prosesujian"
                                    type="button" class="btn btn-sm" style="background-color:#e9ddff;">
                                    <i class="tf-icons mdi mdi-book"></i>
                                    Proses&nbsp;Ujian
                                </button>';
                        return $btn;
                    }
                })
                ->addColumn('detail_cv', function ($row) use ($holding) {
                    $btn = '<button id="btn_lihat_cv"
                                data-id="' . $row->id . '"
                                data-nama_pelamar="' . $row->nama_depan . ' ' . $row->nama_tengah . ' ' . $row->nama_belakang . '"
                                data-tempat_lahir="' . $row->tempat_lahir . '"
                                data-tanggal_lahir="' . $row->tanggal_lahir . '"
                                data-gender="' . $row->gender . '"
                                data-status_nikah="' . $row->status_nikah . '"
                                data-nik="' . $row->nik . '"
                                data-departemen="' . $row->Bagian->Divisi->Departemen->nama_departemen . '"
                                data-divisi="' . $row->Bagian->Divisi->nama_divisi . '"
                                data-bagian="' . $row->Bagian->nama_bagian . '"
                                data-email="' . $row->email . '"
                                data-no_hp="' . $row->no_hp . '"
                                data-alamatktp="' . $row->alamat_ktp . '"
                                data-nama_sdmi="' . $row->nama_sdmi . '"
                                data-tahun_sdmi="' . $row->tahun_sdmi . '"
                                data-nama_smpmts="' . $row->nama_smpmts . '"
                                data-tahun_smpmts="' . $row->tahun_smpmts . '"
                                data-nama_smamasmk="' . $row->nama_smamasmk . '"
                                data-tahun_smamasmk="' . $row->tahun_smamasmk . '"
                                data-nama_universitas="' . $row->nama_universitas . '"
                                data-tahun_universitas="' . $row->tahun_universitas . '"
                                data-judul_keterampilan1="' . $row->judul_keterampilan1 . '"
                                data-ket_keterampilan1="' . $row->ket_keterampilan1 . '"
                                data-judul_keterampilan2="' . $row->judul_keterampilan2 . '"
                                data-ket_keterampilan2="' . $row->ket_keterampilan2 . '"
                                data-judul_keterampilan3="' . $row->judul_keterampilan3 . '"
                                data-ket_keterampilan3="' . $row->ket_keterampilan3 . '"
                                data-judul_pengalaman1="' . $row->judul_pengalaman1 . '"
                                data-lokasi_pengalaman1="' . $row->lokasi_pengalaman1 . '"
                                data-tahun_pengalaman1="' . $row->tahun_pengalaman1 . '"
                                data-judul_pengalaman2="' . $row->judul_pengalaman2 . '"
                                data-lokasi_pengalaman2="' . $row->lokasi_pengalaman2 . '"
                                data-tahun_pengalaman2="' . $row->tahun_pengalaman2 . '"
                                data-judul_pengalaman3="' . $row->judul_pengalaman3 . '"
                                data-lokasi_pengalaman3="' . $row->lokasi_pengalaman3 . '"
                                data-tahun_pengalaman3="' . $row->tahun_pengalaman3 . '"
                                data-prestasi1="' . $row->prestasi1 . '"
                                data-prestasi2="' . $row->prestasi2 . '"
                                data-prestasi3="' . $row->prestasi3 . '"
                                data-img_ktp="' . $row->file_ktp . '"
                                data-img_kk="' . $row->file_kk . '"
                                data-img_ijazah="' . $row->file_ijazah . '"
                                data-img_pp="' . $row->file_pp . '"
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
                                data-nama_pelamar="' . $row->nama_depan . ' ' . $row->nama_tengah . ' ' . $row->nama_belakang . '"
                                data-email="' . $row->email . '"
                                data-no_hp="' . $row->no_hp . '"
                                data-alamatktp="' . $row->alamat_ktp . '"
                                data-nama_sdmi="' . $row->nama_sdmi . '"
                                data-tahun_sdmi="' . $row->tahun_sdmi . '"
                                data-nama_smpmts="' . $row->nama_smpmts . '"
                                data-tahun_smpmts="' . $row->tahun_smpmts . '"
                                data-nama_smamasmk="' . $row->nama_smamasmk . '"
                                data-tahun_smamasmk="' . $row->tahun_smamasmk . '"
                                data-nama_universitas="' . $row->nama_universitas . '"
                                data-tahun_universitas="' . $row->tahun_universitas . '"
                                data-nilai_ujian="' . $row->DataInterview->nilai_ujian . '"
                                data-catatan_ujian="' . $row->DataInterview->catatan_ujian . '"
                                data-nilai_interview_hrd1="' . $row->DataInterview->nilai_interview_hrd1 . '"
                                data-nilai_interview_hrd2="' . $row->DataInterview->nilai_interview_hrd2 . '"
                                data-nilai_interview_hrd3="' . $row->DataInterview->nilai_interview_hrd3 . '"
                                data-nilai_interview_hrd4="' . $row->DataInterview->nilai_interview_hrd4 . '"
                                data-nilai_interview_hrd5="' . $row->DataInterview->nilai_interview_hrd5 . '"
                                data-catatan_interview_hrd="' . $row->DataInterview->catatan_interview_hrd . '"
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
                    if($row->DataInterview->status_interview == 0 || $row->DataInterview->status_interview == 1){
                        $btn = '<button id="btn_kehadiran"
                                data-recruitment_user_id="' . $row->id . '"
                                data-recruitment_interview_id="' . $row->DataInterview->id . '"
                                type="button" class="btn btn-sm btn-primary ">
                                <i class="tf-icons mdi mdi-account-clock"></i>
                                &nbsp;Absensi
                            </button>';
                        return $btn;
                    }elseif($row->DataInterview->status_interview == 3){
                        $return = '<span class="badge rounded-pill bg-success">Hadir</span>';
                        return $return;
                    }elseif($row->DataInterview->status_interview == 4){
                        $return = '<span class="badge rounded-pill bg-danger">Tidak Hadir</span>';
                        return $return;
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
                ->rawColumns(['email','ujian','detail_cv','penilaian','status_kehadiran','departemen_id','nama_departemen','nama_divisi','nama_bagian'])
                ->make(true);
        }
    }

    function absensi_kehadiran_interview(Request $request)
    {
        if($request->status_interview == 3){
            RecruitmentInterview::where('id', $request->show_recruitmentinterviewid3)->update([
                'status_interview'  => 3,
            ]);
            RecruitmentUser::where('id', $request->show_recruitmentuserid3)->update([
                'status_recruitmentuser'  => 3,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'activity' => 'create',
                'description' => 'Menambahkan data kehadrian ' . $request->name,
            ]);
            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        }else{
            RecruitmentInterview::where('id', $request->show_recruitmentinterviewid3)->update([
                'status_interview'  => 4,
            ]);
            RecruitmentUser::where('id', $request->show_recruitmentuserid3)->update([
                'status_recruitmentuser'  => 4,
            ]);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'activity' => 'create',
                'description' => 'Menambahkan data kehadrian ' . $request->name,
            ]);
            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        }
    }

    function kategori_ujian(Request $request)
    {
        $insert = DB::table('waktu_ujian')->insert([
            'kode' => $request->psikotes,
            'auth_id' => $request->id_userscareer,
            'recruitmentuser_id' => $request->id_recruitmentuser
        ]);
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'created',
            'description' => 'Created data exam status on ' . Auth::user()->name,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Dibuat');
    }

    function pg_ujian()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ujian.data_ujian',[
            'holding'=> $holding,
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
            'guru' => User::firstWhere('id', Auth::user()->id),
            'ujian' => Ujian::where('guru_id', Auth::user()->id)->get()
        ]);
    }

    function pg_ujian_pg()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.ujian.data_ujian_create',[
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
            'guru' => User::firstWhere('id', '008ceb79-9d9b-49c5-98a0-bc39c640e34b')->get(),
            'guru_kelas' => Gurukelas::where('guru_id', '008ceb79-9d9b-49c5-98a0-bc39c640e34b')->get(),
            'guru_mapel' => Gurumapel::where('guru_id', '008ceb79-9d9b-49c5-98a0-bc39c640e34b')->get(),
        ]);
    }

    function ujian_pg_store(Request $request)
    {
        $siswa = UserCareer::where('kelas_id', $request->kelas)->get();
        if ($siswa->count() == 0) {
            return redirect('pg-data-ujian/sp')->with('success', 'Belum ada user di kelas tersebut');
        }

        $kode = Str::random(30);
        $ujian = [
            'kode' => $kode,
            'nama' => $request->nama_ujian,
            'jenis' => 0,
            'guru_id' => Auth::user()->id,
            'kelas_id' => $request->kelas,
            'mapel_id' => $request->mapel,
            'jam' => $request->jam,
            'menit' => $request->menit,
            'acak' => $request->acak,
        ];
        $detail_ujian = [];
        $index = 0;
        $nama_soal =  $request->soal;
        foreach ($nama_soal as $soal) {
            array_push($detail_ujian, [
                'kode' => $kode,
                'soal' => $soal,
                'pg_1' => 'A. ' . $request->pg_1[$index],
                'pg_2' => 'B. ' . $request->pg_2[$index],
                'pg_3' => 'C. ' . $request->pg_3[$index],
                'pg_4' => 'D. ' . $request->pg_4[$index],
                'pg_5' => 'E. ' . $request->pg_5[$index],
                'jawaban' => $request->jawaban[$index]
            ]);
            $index++;
        }
        $email_siswa = '';
        $waktu_ujian = [];
        foreach ($siswa as $s) {
            $email_siswa .= $s->email . ',';
            array_push($waktu_ujian, [
                'kode' => $kode,
                'auth_id' => $s->id
            ]);
        }

        $email_siswa = Str::replaceLast(',', '', $email_siswa);
        $email_siswa = explode(',', $email_siswa);
        Ujian::insert($ujian);
        DetailUjian::insert($detail_ujian);
        WaktuUjian::insert($waktu_ujian);
        return redirect('pg-data-ujian/sp')->with('success', 'Ujian berhasil dibuat');
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
        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    function nilai_interview_hrd(Request $request)
    {
        RecruitmentInterview::where('id', $request->recruitment_interview_id1)->update([
            'nilai_interview_hrd1'       => $request->nilai_interview_hrd1,
            'nilai_interview_hrd2'       => $request->nilai_interview_hrd2,
            'nilai_interview_hrd3'       => $request->nilai_interview_hrd3,
            'nilai_interview_hrd4'       => $request->nilai_interview_hrd4,
            'nilai_interview_hrd5'       => $request->nilai_interview_hrd5,
            'catatan_interview_hrd'     => $request->catatan_interview_hrd,
            'status_interview_manager'  => $request->status_interview_manager
        ]);
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => 'create',
            'description' => 'Menambahkan data Nilai Interview ' . $request->name,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
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
        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }

    function pg_ranking()
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.data_rankinginterview', [
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
                    }]);
                    $query->orderBy('nama_divisi', 'ASC');
                }]);
                $query->orderBy('nama_bagian', 'ASC');
            },
        ])->where('holding_recruitment', $holding)->orderBy('nama_bagian', 'ASC')->get();
        if (request()->ajax()) {
            return DataTables::of($table)
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
                    $url = url('/pg/data-list-ranking/'. $row->id .'/'.$holding);
                    $btn = '<a href="' . $url . '" class="btn btn-sm btn-primary">
                                <i class="tf-icons mdi mdi-podium-gold me-1"></i>
                                List Ranking
                            </a>';
                    return $btn;
                })
                ->addColumn('tanggal', function ($row) {
                    $return = $row->created_recruitment;
                    return $return;
                })
                ->rawColumns(['nama_departemen','nama_divisi','nama_bagian','pelamar','tanggal'])
                ->make(true);
        }
    }

    function pg_list_ranking($id)
    {
        $holding = request()->segment(count(request()->segments()));
        return view('admin.recruitment-users.data_listranking',[
            'title' => 'Data Recruitment',
            'holding'   => $holding,
            'id_recruitment'        => $id,
            'data_departemen' => Departemen::all(),
            'data_bagian' => Bagian::with('Divisi')->where('holding', $holding)->get(),
            'data_dept' => Departemen::orderBy('nama_departemen', 'asc')->where('holding', $holding)->get(),
            'data_divisi' => Divisi::orderBy('nama_divisi', 'asc')->where('holding', $holding)->get()
        ]);
    }

    function dt_list_ranking()
    {
        $holding = request()->segment(count(request()->segments()));
        $table =  RecruitmentUser::with([
                'Bagian' =>  function ($query) {
                    $query->with([
                        'Divisi' => function ($query) {
                        $query->with([
                            'Departemen' => function ($query) {
                            $query->orderBy('nama_departemen', 'ASC');
                        }]);
                        $query->orderBy('nama_divisi', 'ASC');
                    }]);
                    $query->orderBy('nama_bagian', 'ASC');
                },
            ])->with([
                'DataInterview' => function ($query){
                    $query->orderBy('id', 'ASC');
                },
            ])
            ->where('holding', $holding)
            // ->where('status_recruitmentuser','!=', 0)
            // ->where('status_recruitmentuser','!=', 5)
            ->orderBy('nama_bagian', 'ASC')->get();
            // dd($table);
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('nama_pelamar', function ($row) {
                    if(($row->nama_depan != '' && $row->nama_tengah != '' && $row->nama_belakang != '')){
                        $nama_pelamar = $row->nama_depan.' '.$row->nama_tengah.' '.$row->nama_belakang;
                    }elseif($row->nama_depan != '' && $row->nama_tengah != '' && $row->nama_belakang == ''){
                        $nama_pelamar = $row->nama_depan.' '.$row->nama_tengah;
                    }else{
                        $nama_pelamar = $row->nama_depan;
                    }
                    return $nama_pelamar;
                })
                ->addColumn('status_kehadiran', function ($row) use ($holding) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge rounded-pill bg-success">Penilaian Interview</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge rounded-pill bg-danger">Tidak Hadir Interview</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge rounded-pill bg-warning">Belum Absen Interview</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge rounded-pill bg-dark">Tidak Lolos Administrasi</span>';
                        return $return;
                    }
                })
                ->addColumn('detail_cv', function ($row) use ($holding) {
                    $btn = '<button id="btn_lihat_cv"
                                data-id="' . $row->id . '"
                                data-nama_pelamar="' . $row->nama_depan . ' ' . $row->nama_tengah . ' ' . $row->nama_belakang . '"
                                data-tempat_lahir="' . $row->tempat_lahir . '"
                                data-tanggal_lahir="' . $row->tanggal_lahir . '"
                                data-gender="' . $row->gender . '"
                                data-status_nikah="' . $row->status_nikah . '"
                                data-nik="' . $row->nik . '"
                                data-departemen="' . $row->Bagian->Divisi->Departemen->nama_departemen . '"
                                data-divisi="' . $row->Bagian->Divisi->nama_divisi . '"
                                data-bagian="' . $row->Bagian->nama_bagian . '"
                                data-email="' . $row->email . '"
                                data-no_hp="' . $row->no_hp . '"
                                data-alamatktp="' . $row->alamat_ktp . '"
                                data-nama_sdmi="' . $row->nama_sdmi . '"
                                data-tahun_sdmi="' . $row->tahun_sdmi . '"
                                data-nama_smpmts="' . $row->nama_smpmts . '"
                                data-tahun_smpmts="' . $row->tahun_smpmts . '"
                                data-nama_smamasmk="' . $row->nama_smamasmk . '"
                                data-tahun_smamasmk="' . $row->tahun_smamasmk . '"
                                data-nama_universitas="' . $row->nama_universitas . '"
                                data-tahun_universitas="' . $row->tahun_universitas . '"
                                data-judul_keterampilan1="' . $row->judul_keterampilan1 . '"
                                data-ket_keterampilan1="' . $row->ket_keterampilan1 . '"
                                data-judul_keterampilan2="' . $row->judul_keterampilan2 . '"
                                data-ket_keterampilan2="' . $row->ket_keterampilan2 . '"
                                data-judul_keterampilan3="' . $row->judul_keterampilan3 . '"
                                data-ket_keterampilan3="' . $row->ket_keterampilan3 . '"
                                data-judul_pengalaman1="' . $row->judul_pengalaman1 . '"
                                data-lokasi_pengalaman1="' . $row->lokasi_pengalaman1 . '"
                                data-tahun_pengalaman1="' . $row->tahun_pengalaman1 . '"
                                data-judul_pengalaman2="' . $row->judul_pengalaman2 . '"
                                data-lokasi_pengalaman2="' . $row->lokasi_pengalaman2 . '"
                                data-tahun_pengalaman2="' . $row->tahun_pengalaman2 . '"
                                data-judul_pengalaman3="' . $row->judul_pengalaman3 . '"
                                data-lokasi_pengalaman3="' . $row->lokasi_pengalaman3 . '"
                                data-tahun_pengalaman3="' . $row->tahun_pengalaman3 . '"
                                data-prestasi1="' . $row->prestasi1 . '"
                                data-prestasi2="' . $row->prestasi2 . '"
                                data-prestasi3="' . $row->prestasi3 . '"
                                data-img_ktp="' . $row->file_ktp . '"
                                data-img_kk="' . $row->file_kk . '"
                                data-img_ijazah="' . $row->file_ijazah . '"
                                data-img_pp="' . $row->file_pp . '"
                                type="button" class="btn btn-sm btn-info ">
                                <i class="tf-icons mdi mdi-eye-circle-outline me-1"></i>
                                Detail&nbsp;CV
                            </button>';
                    return $btn;
                })
                ->addColumn('nilai_ujian', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_ujian.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_ujian.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_ujian.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('catatan_ujian', function ($row) {
                    // return $return;
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if (isset($row->DataInterview->catatan_ujian)) {
                            $return = 1;
                        }
                    }
                    $return = 0;
                })
                ->addColumn('nilai_interview_hrd1', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd1.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd1.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd1.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('nilai_interview_hrd2', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd2.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd2.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd2.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('nilai_interview_hrd3', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd3.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd3.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd3.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('nilai_interview_hrd4', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd4.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd4.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd4.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('nilai_interview_hrd5', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd5.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd5.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_hrd5.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('catatan_interview_hrd', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = $row->DataInterview->catatan_interview_hrd;
                            return $return;
                        }else{
                            $return = '-';
                            return $return;
                        }
                    }else{
                        $return = '-';
                        return $return;
                    }
                    return;
                })
                ->addColumn('nilai_interview_manager', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_manager.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_manager.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_manager.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->addColumn('catatan_interview_manager', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = $row->DataInterview->catatan_interview_manager;
                            return $return;
                        }else{
                            $return = '-';
                            return $return;
                        }
                    }else{
                        $return = '-';
                        return $return;
                    }
                    return;
                })
                ->addColumn('nilai_interview_manager', function ($row) {
                    if (isset($row->DataInterview) && $row->DataInterview !== null) {
                        if ($row->DataInterview->nilai_ujian != 0 && $row->DataInterview->status_interview == 3) {
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_manager.'</span>';
                            return $return;
                        }elseif($row->DataInterview->status_interview == 4){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_manager.'</span>';
                            return $return;
                        }elseif($row->DataInterview->nilai_ujian == 0 && $row->DataInterview->status_interview == 0){
                            $return = '<span class="badge badge-center rounded-pill bg-primary">'.$row->DataInterview->nilai_interview_manager.'</span>';
                            return $return;
                        }
                    }else{
                        $return = '<span class="badge badge-center rounded-pill bg-primary">0</span>';
                        return $return;
                    }
                    return;
                })
                ->rawColumns(['nama_pelamar','status_kehadiran','detail_cv',
                    'nilai_ujian','catatan_ujian','nilai_interview_hrd1',
                    'nilai_interview_hrd2','nilai_interview_hrd3',
                    'nilai_interview_hrd4','nilai_interview_hrd5',
                    'catatan_interview_hrd','nilai_interview_manager',
                    'catatan_interview_manager'
                    ])
                ->make(true);
        }
    }

}
