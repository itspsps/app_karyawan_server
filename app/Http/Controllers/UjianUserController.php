<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentUser;
use App\Models\Ujian;
use App\Models\UjianEsaiJawab;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class UjianUserController extends Controller
{
    public function dt_ujian_pg($id)
    {
        $get_jabatan = RecruitmentUser::where('id', $id)->with([
            'Jabatan' => function ($query) {
                $query->with([
                    'LevelJabatan' => function ($query) {
                        $query;
                    }
                ]);
            }
        ])->with([
            'Cv' => function ($query) {
                $query;
            }
        ])
            ->first();
        if ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '0') {
            $ujian_esai = Ujian::where('nol', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
        } elseif ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '1') {
            $ujian_esai = Ujian::where('satu', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
        } elseif ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '2') {
            $ujian_esai = Ujian::where('dua', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
        } elseif ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '3') {
            $ujian_esai = Ujian::where('tiga', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
        } elseif ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '4') {
            $ujian_esai = Ujian::where('empat', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
            // dd($ujian, $ujian_esai);
        } elseif ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '5') {
            $ujian_esai = Ujian::where('lima', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
        } elseif ($get_jabatan->Jabatan->LevelJabatan->level_jabatan == '6') {
            $ujian_esai = Ujian::where('enam', '1')->where('esai', '1')->with([
                'ujianKategori' => function ($query) {
                    $query;
                }
            ])->with([
                'esaiJawab' => function ($query) use ($id) {
                    $query->where('recruitment_user_id', $id);
                }
            ])->get();
        }
        // dd($get_jabatan->Jabatan->LevelJabatan->level_jabatan, $ujian_esai);
        if (request()->ajax()) {
            return DataTables::of($ujian_esai)
                ->addColumn('nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('kategori', function ($row) {
                    return $row->ujianKategori->nama_kategori;
                })
                ->addColumn('jawaban', function ($row) use ($id) {
                    $holding = request()->segment(count(request()->segments()));
                    if ($row->esaiJawab == null) {
                        return '<button class="btn btn-info" disabled><small>belum dikerjakan</small></button>';
                    } else {
                        return '<a href="' . url("/dt/data-get_esai_interview/$row->kode/$id/$holding") . '" type="button" class="btn btn-info"><small>Jawaban</small></a>';
                    }
                })
                ->addColumn('nilai', function ($row) {
                    if ($row->esaiJawab == null) {
                        return 'belum dikerjakan';
                    } else {
                        if ($row->esaiJawab->nilai == null) {
                            return 'Belum Dinilai';
                        } else {
                            return $row->esaiJawab->nilai;
                        }
                    }
                })
                ->rawColumns(['nama', 'kategori', 'jawaban', 'nilai'])
                ->make(true);
        }
    }
    function show_esai($kode, $recruitment_user_id)
    {
        $holding = request()->segment(count(request()->segments()));
        $ujian = Ujian::where('kode', $kode)->with([
            'detailEsai' => function ($query) use ($recruitment_user_id, $kode) {
                $query->with([
                    'ujianEsaiJawabDetail' => function ($query) use ($recruitment_user_id, $kode) {
                        $query->where('kode', $kode)->where('recruitment_user_id', $recruitment_user_id)->get();
                    }
                ]);
            }
        ])->with([
            'esaiJawab' => function ($query) use ($recruitment_user_id) {
                $query->where('recruitment_user_id', $recruitment_user_id);
            }
        ])->first();
        // dd($ujian->detailEsai->ujianEsaiJawabDetail->kode, $ujian->detailEsai->ujianEsaiJawabDetail->recruitment_user_id);
        // dd($ujian);

        return view('admin.recruitment-users.interview.esai_interview', [
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
            'holding' => $holding,
            'recruitment_user_id' => $recruitment_user_id
        ]);
    }
    public function penilaian_esai(Request $request)
    {
        // dd($request->recruitment_user_id);
        $rules =
            [
                'nilai'             => 'required|numeric',
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

        // $holding = request()->segment(count(request()->segments()));
        // dd($validatedData);
        UjianEsaiJawab::where('recruitment_user_id', $request->recruitment_user_id)->where('kode', $request->kode)->update(
            [
                'nilai'             => $request->nilai,

            ]
        );

        // Merekam aktivitas pengguna
        return redirect('/dt/data-data_ujian_user/' . $request->recruitment_user_id . '/' . $request->holding . '')->with('success', 'data berhasil ditambahkan');
    }
}
