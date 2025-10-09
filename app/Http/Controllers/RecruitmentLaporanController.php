<?php

namespace App\Http\Controllers;

use App\Models\Holding;
use App\Models\Recruitment;
use App\Models\RecruitmentUser;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

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
        ])->get();
        if (request()->ajax()) {
            return DataTables::of($table)
                ->addColumn('waktu_melamar', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->Cv->nama_lengkap;
                })
                ->addColumn('alamat', function ($row) {
                    if ($row->Cv->alamat_sekarang == 'sama') {
                        return $row->Cv->provinsiKTP->name . ', ' . $row->Cv->kabupatenKTP->name . ', ' . $row->Cv->desaKTP->name . ', RT. ' . $row->Cv->rw_ktp . ', RW. ' . $row->Cv->rw_ktp;
                    } else {
                        return $row->Cv->provinsiNOW->name . ', ' . $row->Cv->kabupatenNOW->name . ', ' . $row->Cv->desaNOW->name . ', RT. ' . $row->Cv->rw_now . ', RW. ' . $row->Cv->rw_now;
                    }
                })
                ->addColumn('tanggal_lahir', function ($row) {
                    return $row->Cv->tempat_lahir . ', ' . $row->Cv->tanggal_lahir;
                })
                // ->addColumn('usia', function ($row) {})
                ->addColumn('gender', function ($row) {
                    if ($row->Cv->jenis_kelamin == '1') {
                        return 'LAKI-LAKI';
                    } else {
                        return 'PEREMPUAN';
                    }
                })
                ->addColumn('nomor_whatsapp', function ($row) {
                    return $row->Authlogin->nomor_whatsapp;
                })
                ->addColumn('lama_nomor_wa', function ($row) {
                    return $row->Cv->lama_nomor_whatsapp . ' TAHUN, ' . $row->Cv->lama_nomor_bulan . ' BULAN';
                })
                ->addColumn('status_pernikahan', function ($row) {
                    if ($row->Cv->status_pernikahan == '1') {
                        return 'BELUM KAWIN';
                    } elseif ($row->Cv->status_pernikahan == '2') {
                        return 'KAWIN';
                    } elseif ($row->Cv->status_pernikahan == '3') {
                        return 'CERAI HIDUP';
                    } elseif ($row->Cv->status_pernikahan == '4') {
                        return 'CERAI MATI';
                    }
                })
                ->addColumn('pendidikan_terakhir', function ($row) {})
                ->addColumn('lembaga_pendidikan', function ($row) {})
                ->addColumn('jurusan', function ($row) {})
                ->addColumn('pengalaman_kerja', function ($row) {})
                ->addColumn('no_referensi', function ($row) {})
                ->addColumn('alamat_perusahaan', function ($row) {})
                ->addColumn('jabatan_terakhir', function ($row) {})
                ->addColumn('masa_kerja', function ($row) {})
                ->addColumn('gaji_terakhir', function ($row) {})
                ->addColumn('keahlian', function ($row) {})
                ->addColumn('cv', function ($row) {})
                ->addColumn('foto', function ($row) {})
                ->addColumn('posisi_yang_dilamar', function ($row) {
                    return $row->Jabatan->nama_jabatan . ', ' . $row->Jabatan->Bagian->nama_bagian . ', ' . $row->Jabatan->Bagian->Divisi->nama_divisi . ', ' . $row->Jabatan->Bagian->Divisi->Departemen->nama_departemen;
                })
                ->addColumn('riwayat_lamaran', function ($row) {})
                ->addColumn('hasil_final', function ($row) {})
                ->rawColumns([
                    'waktu_melamar',
                    'nama_lengkap',
                    'alamat',
                    'tanggal_lahir',
                    'gender',
                    'nomor_whatsapp',
                    'lama_nomor_wa',
                    'status_pernikahan',
                    'pendidikan_terakhir',
                    'lembaga_pendidikan',
                    'jurusan',
                    'pengalaman_kerja',
                    'no_referensi',
                    'alamat_perusahaan',
                    'jabatan_terakhir',
                    'masa_kerja',
                    'gaji_terakhir',
                    'keahlian',
                    'cv',
                    'foto',
                    'posisi_yang_dilamar',
                    'riwayat_lamaran',
                    'hasil_final',
                ])
                ->make(true);
        }
    }
}
