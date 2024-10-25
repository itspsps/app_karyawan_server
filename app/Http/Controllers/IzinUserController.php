<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use App\Models\Jabatan;
use App\Models\Izin;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Karyawan;
use App\Models\KategoriIzin;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use DateTime;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Excel;
use Ramsey\Uuid\Uuid;

class IzinUserController extends Controller
{
    public function index(Request $request)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $kontrak = $user_karyawan->kontrak_kerja;
        $site_job = $user_karyawan->site_job;
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        // dd($user_karyawan);
        if ($kontrak == '') {
            $request->session()->flash('kontrakkerjaNULL');
            return redirect('/home');
        }
        if ($user_karyawan->kategori == 'Karyawan Bulanan') {
            $user = Karyawan::Join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->where('karyawans.id', $user_karyawan->id)->first();
            // dd($user);
            if ($user == NULL) {
                $request->session()->flash('jabatanNULL');
                return redirect('/home');
            } else {
                $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
                // dd($IdLevelAtasan);
                if ($IdLevelAtasan == NULL) {
                    $getUserAtasan = NULL;
                    $get_user_backup = Karyawan::where('dept_id', $user_karyawan->dept_id)
                        ->where('id', '!=', $user_karyawan->id)
                        ->where('dept_id', $user_karyawan->dept_id)
                        ->get();
                } else {
                    // dd($lokasi_site_job->kategori_kantor);

                    if ($lokasi_site_job->kategori_kantor == 'sps') {
                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->first();
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            if ($IdLevelAtasan->atasan_id == NULL) {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                // dd($get_atasan_site);
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($atasan2 == NULL || $atasan2 == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->orderBy('jabatans.holding', 'DESC')
                                            ->first();
                                        // dd($get_atasan_more);
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->orderBy('jabatans.holding', 'DESC')
                                            ->first();
                                    }
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        // dd($atasan);
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $atasan2;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            if ($IdLevelAtasan->atasan_id == NULL) {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan2 == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            } else {
                                $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($atasan2 == NULL || $atasan2 == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    }
                                    // dd($get_atasan_more);
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $atasan2;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                            ->first();
                        // dd($get_nama_jabatan);

                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            if ($IdLevelAtasan->atasan_id == NULL) {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                    ->first();
                                if ($atasan2 == NULL || $atasan2 == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->where('jabatans.holding', 'sps')
                                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    }
                                    // dd($get_atasan_more);
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $atasan2;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            if ($IdLevelAtasan->atasan_id == NULL) {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                    ->first();
                                if ($atasan2 == NULL || $atasan2 == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    }
                                    // dd($get_atasan_more);
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SIP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $atasan2;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all sp') {
                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            if ($IdLevelAtasan->atasan_id == NULL) {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                // dd($get_atasan_more);
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                    ->first();
                                if ($atasan2 == NULL || $atasan2 == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    }
                                    // dd($get_atasan_more);
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG', 'CV. SURYA INTI PANGAN - MAKASAR'])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $atasan2;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all sip') {
                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            if ($IdLevelAtasan->atasan_id == NULL) {
                                $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $IdLevelAtasan->id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site->holding == 'sps') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site->holding == 'sip') {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sp'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                        ->where('jabatans.holding', ['sps'])
                                        ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more == NULL) {
                                    $getUserAtasan  = NULL;
                                } else {
                                    $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                        ->first();
                                    if ($atasan == NULL) {
                                        $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                            ->first();
                                        if ($atasan2 == NULL) {
                                            $getUserAtasan  = NULL;
                                        } else {
                                            $getUserAtasan  = $atasan2;
                                        }
                                    } else {
                                        $getUserAtasan  = $atasan;
                                    }
                                }
                            } else {
                                $atasan2 = Karyawan::where('jabatan_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan1_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan2_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan3_id', $IdLevelAtasan->atasan_id)
                                    ->orWhere('jabatan4_id', $IdLevelAtasan->atasan_id)
                                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                    ->first();
                                // dd($atasan2);
                                if ($atasan2 == NULL || $atasan2 == '') {
                                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->where('jabatans.id', $IdLevelAtasan->id)
                                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                    if ($get_atasan_site->holding == 'sps') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->orderBy('jabatans.holding', 'DESC')
                                            ->first();
                                    } else if ($get_atasan_site->holding == 'sip') {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->whereIn('jabatans.holding', ['sps', 'sp'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->first();
                                    } else {
                                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                            ->where('jabatans.holding', ['sps'])
                                            ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                            ->orderBy('jabatans.holding', 'DESC')
                                            ->first();
                                    }
                                    // dd($get_atasan_more);
                                    if ($get_atasan_more == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                            ->first();
                                        if ($atasan == NULL) {
                                            $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                                ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                                ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                                                ->first();
                                            if ($atasan2 == NULL) {
                                                $getUserAtasan  = NULL;
                                            } else {
                                                $getUserAtasan  = $atasan2;
                                            }
                                        } else {
                                            $getUserAtasan  = $atasan;
                                        }
                                    }
                                } else {
                                    $getUserAtasan  = $atasan2;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    } else if ($lokasi_site_job->kategori_kantor == 'all') {
                        $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->first();
                        // dd($get_nama_jabatan);
                        if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                            $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                ->where('jabatans.id', $IdLevelAtasan->id)
                                ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                ->first();
                            // dd($get_atasan_site);
                            if ($get_atasan_site->holding == 'sps') {
                                // dd('ok');
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sip'])
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            } else if ($get_atasan_site->holding == 'sip') {
                                // dd('ok');
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sp', 'sps'])
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                // dd($get_atasan_more);
                            } else {
                                $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                                    ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                                    ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                                    ->whereIn('jabatans.holding', ['sps', 'sip'])
                                    ->select('jabatans.id', 'jabatans.atasan_id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->orderBy('jabatans.holding', 'DESC')
                                    ->first();
                            }
                            // dd($get_atasan_more);
                            if ($get_atasan_more == NULL) {
                                $getUserAtasan  = NULL;
                            } else {
                                $atasan = Karyawan::where('jabatan_id', $get_atasan_more->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more->id)
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan);
                                if ($atasan == NULL) {
                                    $atasan2 = Karyawan::where('jabatan_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan1_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan2_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan3_id', $get_atasan_more->atasan_id)
                                        ->orWhere('jabatan4_id', $get_atasan_more->atasan_id)
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                    // dd($atasan2);
                                    if ($atasan2 == NULL) {
                                        $getUserAtasan  = NULL;
                                    } else {
                                        $getUserAtasan  = $atasan2;
                                    }
                                } else {
                                    $getUserAtasan  = $atasan;
                                }
                            }
                        } else {
                            $getUserAtasan  = $get_nama_jabatan;
                        }
                    }
                    $get_user_backup = Karyawan::where('dept_id', $user_karyawan->dept_id)
                        ->where('id', '!=', $user_karyawan->id)
                        ->where('dept_id', $user_karyawan->dept_id)
                        ->get();
                }
            }
        } else if ($user_karyawan->kategori == 'Karyawan Harian') {
            $user = Karyawan::where('id', $user_karyawan->id)->first();
            $atasan = Karyawan::Join('mapping_shifts', function ($join) {
                $join->on('mapping_shifts.koordinator_id', '=', 'karyawans.id');
            })
                ->select('karyawans.*', 'mapping_shifts.koordinator_id')
                ->first();
            $get_user_backup = NULL;
            $getUserAtasan = $atasan;
        }
        // dd($getUserAtasan);
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        $record_data    = Izin::where('user_id', $user_karyawan->id)->orderBy('created_at', 'DESC')->get();
        $kategori_izin = KategoriIzin::orderBy('id', 'ASC')->whereNotIn('nama_izin', ['Pulang Cepat', 'Datang Terlambat'])->get();
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $req_jm_klr = NULL;
        } else {
            $jam_masuk = \Carbon\Carbon::parse($jam_kerja->Shift->jam_masuk)->addMinute(5)->isoFormat('HH:mm');
            // dd($jam_masuk);
            if ($jam_kerja->jam_absen <= $jam_masuk) {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_masuk);
            } else {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->jam_absen);
            }
        }
        if ($req_jm_klr == '' || $req_jm_klr == NULL) {
            $jam_min_plg_cpt = NULL;
        } else {
            $jam_min_plg_cpt = \Carbon\Carbon::parse($req_jm_klr)->addHour(6)->isoFormat('H:mm');
        }
        // dd($req_jm_klr);
        return view('users.izin.index', [
            'title'             => 'Tambah Permintaan Cuti Karyawan',
            'data_user'         => $user,
            'user_karyawan'     => $user_karyawan,
            'data_izin_user'    => Cuti::where('user_id', $user_karyawan->id)->orderBy('id', 'desc')->get(),
            'getUserAtasan'     => $getUserAtasan,
            'user'              => $user,
            'record_data'       => $record_data,
            'kategori_izin'       => $kategori_izin,
            'jam_kerja'       => $jam_kerja,
            'get_user_backup'       => $get_user_backup,
            'jam_min_plg_cpt'       => $jam_min_plg_cpt,
        ]);
    }
    public function izinEdit($id)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        if ($user_karyawan->kategori == 'Karyawan Bulanan') {
            $user = Karyawan::Join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->where('karyawans.id', $user_karyawan->id)->first();
        } else if ($user_karyawan->kategori == 'Karyawan Harian') {
            $user = Karyawan::where('karyawans.id', $user_karyawan->id)->first();
        }
        $get_izin_id = Izin::where('id', $id)->first();
        $get_user_backup = Karyawan::where('dept_id', $user_karyawan->dept_id)
            ->where('id', '!=', $user_karyawan->id)
            ->where('dept_id', $user_karyawan->dept_id)
            ->get();
        $kategori_izin = KategoriIzin::orderBy('id', 'ASC')->get();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        // dd($jam_kerja);
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $req_jm_klr = NULL;
        } else {
            $jam_masuk = \Carbon\Carbon::parse($jam_kerja->Shift->jam_masuk)->addMinute(5)->isoFormat('HH:mm');
            // dd($jam_masuk);
            if ($jam_kerja->jam_absen <= $jam_masuk) {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_masuk);
            } else {
                $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->jam_absen);
            }
        }
        $jam_min_plg_cpt = \Carbon\Carbon::parse($req_jm_klr)->addHour(6)->isoFormat('H:mm');
        return view(
            'users.izin.edit',
            [
                'user' => $user,
                'user_karyawan' => $user_karyawan,
                'get_izin' => $get_izin_id,
                'kategori_izin' => $kategori_izin,
                'jam_kerja' => $jam_kerja,
                'get_user_backup' => $get_user_backup,
                'jam_min_plg_cpt' => $jam_min_plg_cpt,
            ]
        );
    }
    public function izinEditProses(Request $request)
    {
        // dd($request->all());
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        if ($request->signature !== null) {
            $count_tbl_izin = Izin::where('izin', $request->izin)->where('tanggal', $request->tanggal)->whereNotNull('no_form_izin')->count();
            // dd($count_tbl_izin);
            $get_izin = Izin::where('id', $request->id)->first();
            if ($request->izin == $get_izin->izin) {
                $add = 0;
            } else {
                $add = 1;
            }

            // dd($count_tbl_izin);
            $countstr = strlen($count_tbl_izin + 1);
            if ($countstr == '1') {
                $no = '000' . $count_tbl_izin + 1;
            } else if ($countstr == '2') {
                $no = '00' . $count_tbl_izin + 1;
            } else if ($countstr == '3') {
                $no = '0' . $count_tbl_izin + 1;
            } else {
                $no = $count_tbl_izin + 1;
            }
            if ($request->izin == 'Pulang Cepat') {
                $pulang_cepat = $request->jam_pulang_cepat;
                $terlambat = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $img_name = NULL;
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $catatan_backup = NULL;
                $jam_masuk = NULL;
                $tanggal = $request->tanggal;
                $tanggal_selesai = $request->tanggal;
                $no_form = $user_karyawan->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
            } else if ($request->izin == 'Keluar Kantor') {
                $jam_keluar = $request->jam_keluar;
                $jam_kembali = $request->jam_kembali;
                $pulang_cepat = NULL;
                $jam_masuk = NULL;
                $img_name = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $catatan_backup = NULL;
                $tanggal = $request->tanggal;
                $tanggal_selesai = $request->tanggal;
                $terlambat = NULL;
                $no_form = $user_karyawan->kontrak_kerja . '/MK/' . date('Y/m/d') . '/' . $no;
            } else if ($request->izin == 'Tidak Masuk (Mendadak)') {
                $jumlah_hari = explode(' ', $request->tanggal);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1          = new DateTime($startDate);
                $date2          = new DateTime($endDate);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $tanggal = date('Y-m-d', strtotime($startDate));
                $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                // dd($data_interval);
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $pulang_cepat = NULL;
                $terlambat = NULL;
                $jam_masuk = NULL;
                $img_name = NULL;
                $id_backup = $request->user_backup;
                $name_backup = Karyawan::where('id', $request->user_backup)->value('name');
                $catatan_backup = $request->catatan_backup;
                $no_form = $user_karyawan->kontrak_kerja . '/FPI/' . date('Y/m/d') . '/' . $no;
            } else if ($request->izin == 'Sakit') {
                if ($request->foto_izin_lama == 'TIDAK ADA' || $request->foto_izin_lama == NULL) {
                    if ($request['file_sakit']) {
                        // dd('ok');
                        $extension     = $request->file('file_sakit')->extension();
                        // dd($extension);
                        $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
                        $path           = Storage::putFileAs('foto_izin/', $request->file('file_sakit'), $img_name);
                    } else {
                        $img_name = 'TIDAK ADA';
                    }
                } else {
                    $delete           = Storage::delete('foto_izin/', $request->file('file_sakit'));
                }
                // $jam_pulang_cepat = $request->pulang_cepat;
                $jumlah_hari = explode(' ', $request->tanggal);
                $startDate = trim($jumlah_hari[0]);
                $endDate = trim($jumlah_hari[2]);
                $date1          = new DateTime($startDate);
                $date2          = new DateTime($endDate);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $tanggal = date('Y-m-d', strtotime($startDate));
                $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                // dd($tanggal_selesai);
                // $jam_pulang_cepat = $request->pulang_cepat;
                $terlambat = NULL;
                $jam_masuk = NULL;
                $pulang_cepat = NULL;
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $no_form = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $catatan_backup = NULL;
            } else if ($request->izin == 'Datang Terlambat') {
                // dd($request->all());
                $pulang_cepat = NULL;
                $jam_keluar = NULL;
                $jam_kembali = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $catatan_backup = NULL;
                $terlambat = $request->terlambat;
                $jam_masuk = $request->jam_masuk;
                $img_name = NULL;
                $tanggal = $request->tanggal;
                $tanggal_selesai = $request->tanggal;
                $no_form = $user_karyawan->kontrak_kerja . '/FKDT/' . date('Y/m/d') . '/' . $no;
            } else {
                $catatan_backup = NULL;
                $id_backup = NULL;
                $name_backup = NULL;
                $img_name = NULL;
                $pulang_cepat = NULL;
                $terlambat  = NULL;
                $jam_masuk = NULL;
            }
            $folderPath     = public_path('signature/izin/');
            $image_parts    = explode(";base64,", $request->signature);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type     = $image_type_aux[1];
            $image_base64   = base64_decode($image_parts[1]);
            $uniqid         = date('y-m-d') . '-' . uniqid();
            $file           = $folderPath . $uniqid . '.' . $image_type;
            file_put_contents($file, $image_base64);
            $data                   = Izin::where('id', $request['id'])->first();
            $data->user_id          = $request->id_user;
            $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
            $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
            $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
            $data->telp             = $request->telp;
            $data->email            = $request->email;
            $data->fullname         = $request->fullname;
            $data->izin             = $request->izin;
            $data->terlambat        = $terlambat;
            $data->tanggal          = $tanggal;
            $data->tanggal_selesai  = $tanggal_selesai;
            $data->catatan_backup  = $catatan_backup;
            $data->jam_masuk_kerja  = $jam_masuk;
            $data->pulang_cepat     = $pulang_cepat;
            $data->user_id_backup   = $id_backup;
            $data->user_name_backup = $name_backup;
            $data->jam_keluar       = $jam_keluar;
            $data->jam              = $request->jam;
            $data->jam_kembali     = $jam_kembali;
            $data->keterangan_izin  = $request->keterangan_izin;
            $data->no_form_izin  = $no_form;
            $data->approve_atasan   = $request->approve_atasan;
            $data->id_approve_atasan = $request->id_user_atasan;
            $data->ttd_pengajuan    = $uniqid;
            $data->waktu_ttd_pengajuan    = date('Y-m-d H:i:s');
            if ($request->level_jabatan == '1') {
                $data->status_izin      = 2;
            } else {
                $data->status_izin      = 1;
            }
            $data->update();

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'object_id' => $data->id,
                'kategory_activity' => 'IZIN',
                'activity' => 'Izin ' . $data->izin,
                'description' => 'Pengajuan Izin' . $data->izin . ' No Form: ' . $data->no_form_izin . ' Tanggal ' . $data->tanggal . ' - ' . $data->tanggal_selesai . ' Keterangan  ' . $data->keterangan_izin,
                'read_status' => 0

            ]);
            $request->session()->flash('izineditsuccess');
            return redirect('/izin/dashboard');
        } else {
            Alert::info('info', 'Tanda Tangan Harus Terisi');
            return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
        }
    }

    public function izinAbsen(Request $request)
    {
        // dd($request->all());
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $user_karyawan->id)->where('tanggal_masuk', date('Y-m-d'))->first();
        if ($jam_kerja == '' || $jam_kerja == NULL) {
            $request->session()->flash('mapping_kosong');
            return redirect('/izin/dashboard');
        } else {
            if ($request->id_user_atasan == NULL || $request->id_user_atasan == '') {
                $request->session()->flash('atasankosong');
                return redirect('/izin/dashboard');
            } else {
                // No form

                if ($request->izin == 'Datang Terlambat') {
                    // dd('ok');
                    if ($jam_kerja->jam_pulang != '') {
                        $request->session()->flash('absen_pulang_terisi');
                        return redirect('/izin/dashboard');
                    } else {
                        $jam_terlambat = $request->terlambat;
                        $jam_masuk_kerja = $request->jam_masuk;
                        $jam_pulang_cepat = NULL;
                        $img_name = NULL;
                        $jam_keluar = NULL;
                        $jam_kembali = NULL;
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $catatan_backup = NULL;
                        $tanggal = $request->tanggal;
                        $tanggal_selesai = NULL;
                        $no_form = NULL;
                    }
                } else if ($request->izin == 'Pulang Cepat') {
                    // $req_plg_cpt = new DateTime(date('Y-m-d') . $request->jam_pulang_cepat);
                    // $req_jm_klr = new DateTime(date('Y-m-d') . $jam_kerja->Shift->jam_keluar);
                    // $jam_plg_cpt = $req_plg_cpt->diff($req_jm_klr);
                    // if ($jam_plg_cpt->h == 3 && $jam_plg_cpt->i > 0) {
                    // }
                    if ($jam_kerja->jam_absen == '' && $jam_kerja->jam_pulang == '') {
                        $request->session()->flash('absen_masuk_kosong');
                        return redirect('/izin/dashboard');
                    } else if ($jam_kerja->jam_pulang != '') {
                        $request->session()->flash('absen_pulang_terisi');
                        return redirect('/izin/dashboard');
                    } else {
                        // dd($request->all());
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $jam_pulang_cepat = $request->jam_pulang_cepat;
                        $jam_terlambat = NULL;
                        $jam_masuk_kerja = NULL;
                        $img_name = NULL;
                        $jam_keluar = NULL;
                        $jam_kembali = NULL;
                        $catatan_backup = NULL;
                        $tanggal = date('Y-m-d');
                        $tanggal_selesai = NULL;
                        $no_form = NULL;
                    }
                } else if ($request->izin == 'Keluar Kantor') {
                    if ($jam_kerja->jam_pulang != '') {
                        $request->session()->flash('absen_pulang_terisi');
                        return redirect('/izin/dashboard');
                    } else {
                        $jam_keluar = $request->jam_keluar;
                        $jam_kembali = $request->jam_kembali;
                        $jam_pulang_cepat = NULL;
                        $jam_terlambat = NULL;
                        $jam_masuk_kerja = NULL;
                        $img_name = NULL;
                        $id_backup = NULL;
                        $name_backup = NULL;
                        $catatan_backup = NULL;
                        $tanggal = date('Y-m-d');
                        $tanggal_selesai = date('Y-m-d');
                    }
                    $cek_duplicate = Izin::whereBetween('tanggal', [$tanggal, $tanggal_selesai])->where('user_id', $request->id_user)->where('izin', $request->izin)->count();
                    // dd($cek_duplicate);
                    if ($cek_duplicate > 0) {
                        $request->session()->flash('dataizin_duplicate');
                        return redirect('/izin/dashboard');
                    }
                } else if ($request->izin == 'Tidak Masuk (Mendadak)') {
                    $jumlah_hari = explode(' ', $request->tanggal);
                    $startDate = trim($jumlah_hari[0]);
                    $endDate = trim($jumlah_hari[2]);
                    $date1          = new DateTime($startDate);
                    $date2          = new DateTime($endDate);
                    $interval       = $date1->diff($date2);
                    $data_interval  = $interval->days;
                    $tanggal = date('Y-m-d', strtotime($startDate));
                    $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                    // dd($data_interval);
                    $cek_duplicate = Izin::whereBetween('tanggal', [$tanggal, $tanggal_selesai])->where('user_id', $request->id_user)->where('izin', $request->izin)->count();
                    // dd($cek_duplicate);
                    if ($cek_duplicate > 0) {
                        $request->session()->flash('dataizin_duplicate');
                        return redirect('/izin/dashboard');
                    }
                    $jam_keluar = $request->jam_keluar;
                    $jam_kembali = $request->jam_kembali;
                    $jam_pulang_cepat = NULL;
                    $jam_terlambat = NULL;
                    $jam_masuk_kerja = NULL;
                    $img_name = NULL;
                    $id_backup = $request->user_backup;
                    $name_backup = Karyawan::where('id', $request->user_backup)->value('name');
                    $catatan_backup = $request->catatan_backup;
                    $no_form = NULL;
                } else if ($request->izin == 'Sakit') {
                    if ($request['file_sakit']) {
                        // dd($request->all());
                        // dd('ok');
                        $extension     = $request->file('file_sakit')->extension();
                        // dd($extension);
                        $img_name         = date('y-m-d') . '-' . Uuid::uuid4() . '.' . $extension;
                        $path           = Storage::putFileAs('foto_izin/', $request->file('file_sakit'), $img_name);
                    } else {
                        // dd($request->all());
                        $img_name = 'TIDAK ADA';
                    }
                    $jumlah_hari = explode(' ', $request->tanggal);
                    $startDate = trim($jumlah_hari[0]);
                    $endDate = trim($jumlah_hari[2]);
                    $date1          = new DateTime($startDate);
                    $date2          = new DateTime($endDate);
                    $interval       = $date1->diff($date2);
                    $data_interval  = $interval->days;
                    $tanggal = date('Y-m-d', strtotime($startDate));
                    $tanggal_selesai = date('Y-m-d', strtotime($endDate));
                    // $jam_pulang_cepat = $request->pulang_cepat;
                    $cek_duplicate = Izin::whereBetween('tanggal', [$request->tanggal, $tanggal_selesai])->where('user_id', $request->id_user)->where('izin', $request->izin)->count();
                    // dd($cek_duplicate);
                    if ($cek_duplicate > 0) {
                        $request->session()->flash('dataizin_duplicate');
                        return redirect('/izin/dashboard');
                    }
                    $jam_terlambat = NULL;
                    $jam_masuk_kerja = NULL;
                    $jam_pulang_cepat = NULL;
                    $jam_keluar = NULL;
                    $jam_kembali = NULL;
                    $no_form = NULL;
                    $id_backup = NULL;
                    $name_backup = NULL;
                    $catatan_backup = NULL;
                } else {
                    $id_backup = NULL;
                    $catatan_backup = NULL;
                    $name_backup = NULL;
                    $jam_keluar = NULL;
                    $jam_kembali = NULL;
                    $jam_masuk_kerja = NULL;
                    $jam_terlambat = NULL;
                    $jam_pulang_cepat = NULL;
                    $img_name = NULL;
                    $tanggal = $request->tanggal;
                    $tanggal_selesai = NULL;
                }
                $data                   = new Izin();
                $data->user_id          = $request->id_user;
                $data->departements_id  = Departemen::where('id', $request["departements"])->value('id');
                $data->jabatan_id       = Jabatan::where('id', $request["jabatan"])->value('id');
                $data->divisi_id        = Divisi::where('id', $request["divisi"])->value('id');
                $data->telp             = $request->telp;
                $data->terlambat        = $jam_terlambat;
                $data->jam_masuk_kerja  = $jam_masuk_kerja;
                $data->pulang_cepat     = $jam_pulang_cepat;
                $data->email            = $request->email;
                $data->fullname         = $request->fullname;
                $data->izin             = $request->izin;
                $data->user_id_backup   = $id_backup;
                $data->user_name_backup = $name_backup;
                $data->catatan_backup = $catatan_backup;
                $data->foto_izin        = $img_name;
                $data->jam_keluar        = $jam_keluar;
                $data->jam_kembali        = $jam_kembali;
                $data->tanggal          = $tanggal;
                $data->tanggal_selesai   = $tanggal_selesai;
                $data->jam              = $request->jam;
                $data->keterangan_izin  = $request->keterangan_izin;
                $data->approve_atasan   = $request->approve_atasan;
                $data->id_approve_atasan = $request->id_user_atasan;
                $data->status_izin      = 0;
                $data->no_form_izin      = $no_form;
                $data->ttd_atasan      = NULL;
                $data->waktu_approve      = NULL;
                $data->save();

                $request->session()->flash('izinsuccess');
                return redirect('/izin/dashboard');
            }
        }
    }

    public function izinApprove($id)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user   = Karyawan::Join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        $data   = Izin::where('id', $id)->first();
        $jam_kerja = MappingShift::with('Shift')->where('user_id', $data->user_id)->where('tanggal_masuk', date('Y-m-d'))->first();
        return view('users.izin.approveizin', [
            'user'  => $user,
            'user_karyawan'  => $user_karyawan,
            'jam_kerja'  => $jam_kerja,
            'data'  => $data
        ]);
    }

    public function izinApproveProses(Request $request)
    {
        // dd($request->all());

        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        if ($request->izin == 'Sakit') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/izin/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd($request->all());
                $date1          = new DateTime($request->tanggal);
                $date2          = new DateTime($request->tanggal_selesai);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $plus_1 = $data_interval + 1;
                $potong_cuti1hari = ($plus_1);
                $potong_cuti2hari = ($plus_1 * 2);
                $get_kuota_cuti = Karyawan::where('id', $request->id_user)->first();
                if ($request->foto_izin == NULL) {
                    if ($get_kuota_cuti->kuota_cuti_tahunan > $potong_cuti2hari) {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->ttd_atasan   = $uniqid;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'FALSE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->ttd_atasan   = $uniqid;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $user_pengajuan = Karyawan::where('id', $request->id_user)->first();
                            $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                            $user_pengajuan->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'kelengkapan_absensi' => 'Izin Sakit Disetujui',
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'TRUE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_success');
                            return response()->json($alert);
                        }
                    } else {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->ttd_atasan   = $uniqid;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'FALSE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->catatan      = $request->catatan;
                            $data->ttd_atasan   = $uniqid;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'kelengkapan_absensi' => 'Potong Gaji ,izin sakit tanpa SKD',
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'TRUE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_success');
                            return response()->json($alert);
                        }
                    }
                } else {
                    if ($get_kuota_cuti->kuota_cuti_tahunan > $potong_cuti1hari) {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'FALSE',
                                        'izin_id' => $request->id
                                    ]);
                            }

                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->ttd_atasan   = $uniqid;
                            $data->catatan      = $request->catatan;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $user_pengajuan = Karyawan::where('id', $request->id_user)->first();
                            $user_pengajuan->kuota_cuti = ($get_kuota_cuti->kuota_cuti - $potong_cuti1hari);
                            $user_pengajuan->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'kelengkapan_absensi' => 'Potong Gaji ,izin sakit tanpa SKD',
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'TRUE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_success');
                            return response()->json($alert);
                        }
                    } else {
                        if ($request->approve == 'not_approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = 'NOT APPROVE';
                            $data->catatan      = $request->catatan;
                            $data->ttd_atasan   = $uniqid;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'FALSE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_not_approve');
                            return response()->json($alert);
                        } else if ($request->approve == 'approve') {
                            $data = Izin::where('id', $request->id)->first();
                            $data->status_izin  = $request->status_izin;
                            $data->catatan      = $request->catatan;
                            $data->ttd_atasan   = $uniqid;
                            $data->waktu_approve = date('Y-m-d H:i:s');
                            $data->update();

                            $begin = new \DateTime($data->tanggal);
                            $end = new \DateTime($data->tanggal_selesai);
                            $end = $end->modify('+1 day');

                            $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                            $daterange = new \DatePeriod($begin, $interval, $end);


                            foreach ($daterange as $date) {
                                $tanggal = $date->format("Y-m-d");
                                $update = MappingShift::where('user_id', $request->id_user)
                                    ->whereDate('tanggal_masuk', $tanggal)
                                    ->update([
                                        'kelengkapan_absensi' => 'IZIN SAKIT',
                                        'status_absen' => 'TIDAK HADIR KERJA',
                                        'keterangan_izin' => 'TRUE',
                                        'izin_id' => $request->id
                                    ]);
                            }
                            $alert = $request->session()->flash('approveizin_success');
                            return response()->json($alert);
                        }
                        // dd('ok1');
                    }
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Tidak Masuk (Mendadak)') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/izin/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                $date1          = new DateTime($request->tanggal);
                $date2          = new DateTime($request->tanggal_selesai);
                $interval       = $date1->diff($date2);
                $data_interval  = $interval->days;
                $plus_1 = $data_interval + 1;
                $potong_cuti1hari = ($plus_1);
                $potong_cuti2hari = ($plus_1 * 2);
                $get_kuota_cuti = Karyawan::where('id', $request->id_user)->first();
                if ($get_kuota_cuti->kuota_cuti_tahunan > $plus_1) {
                    if ($request->approve == 'not_approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = 'NOT APPROVE';
                        $data->ttd_atasan   = $uniqid;
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();

                        $user_pengajuan = Karyawan::where('id', $request->id_user)->first();
                        $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                        $user_pengajuan->update();

                        $begin = new \DateTime($data->tanggal);
                        $end = new \DateTime($data->tanggal_selesai);
                        $end = $end->modify('+1 day');

                        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                        $daterange = new \DatePeriod($begin, $interval, $end);


                        foreach ($daterange as $date) {
                            $tanggal = $date->format("Y-m-d");
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereDate('tanggal_masuk', $tanggal)
                                ->update([
                                    'kelengkapan_absensi' => 'Potong saldo cuti 2  ,Atasan Not Approve',
                                    'status_absen' => 'TIDAK HADIR KERJA',
                                    'keterangan_izin' => 'FALSE',
                                    'izin_id' => $request->id
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_not_approve');
                        return response()->json($alert);
                    } else if ($request->approve == 'approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = $request->status_izin;
                        $data->ttd_atasan   = $uniqid;
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();

                        $user_pengajuan = Karyawan::where('id', $request->id_user)->first();
                        $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti1hari);
                        $user_pengajuan->update();

                        $begin = new \DateTime($data->tanggal);
                        $end = new \DateTime($data->tanggal_selesai);
                        $end = $end->modify('+1 day');

                        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                        $daterange = new \DatePeriod($begin, $interval, $end);


                        foreach ($daterange as $date) {
                            $tanggal = $date->format("Y-m-d");
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereDate('tanggal_masuk', $tanggal)
                                ->update([
                                    'kelengkapan_absensi' => 'Potong saldo cuti 2',
                                    'status_absen' => 'TIDAK HADIR KERJA',
                                    'keterangan_izin' => 'TRUE',
                                    'izin_id' => $request->id,
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_success');
                        return response()->json($alert);
                    }
                } else {
                    if ($request->approve == 'not_approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = 'NOT APPROVE';
                        $data->ttd_atasan   = $uniqid;
                        $data->catatan      = $request->catatan;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();

                        $user_pengajuan = Karyawan::where('id', $request->id_user)->first();
                        $user_pengajuan->kuota_cuti_tahunan = ($get_kuota_cuti->kuota_cuti_tahunan - $potong_cuti2hari);
                        $user_pengajuan->update();
                        $update_izin = Izin::where('id', $request->id)->where('user_id', $request->id_user)->where('izin', 'Tidak Masuk (Mendadak)')->where('status_izin', '1')->get();
                        // dd($update_izin);
                        $begin = new \DateTime($data->tanggal);
                        $end = new \DateTime($data->tanggal_selesai);
                        $end = $end->modify('+1 day');

                        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                        $daterange = new \DatePeriod($begin, $interval, $end);


                        foreach ($daterange as $date) {
                            $tanggal = $date->format("Y-m-d");
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereDate('tanggal_masuk', $tanggal)
                                ->update([
                                    'kelengkapan_absensi' => 'Potong Gaji ,izin Tidak Masuk,belum dapat saldo Cuti',
                                    'status_absen' => 'TIDAK HADIR KERJA',
                                    'keteragan_izin' => 'FALSE',
                                    'izin_id' => $request->id,
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_not_approve');
                        return response()->json($alert);
                    } else if ($request->approve == 'approve') {
                        $data = Izin::where('id', $request->id)->first();
                        $data->status_izin  = $request->status_izin;
                        $data->ttd_atasan   = $uniqid;
                        $data->catatan      = $request->catatan;
                        $data->no_form_izin      = $no_form;
                        $data->waktu_approve = date('Y-m-d H:i:s');
                        $data->update();
                        $begin = new \DateTime($data->tanggal);
                        $end = new \DateTime($data->tanggal_selesai);
                        $end = $end->modify('+1 day');

                        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                        $daterange = new \DatePeriod($begin, $interval, $end);


                        foreach ($daterange as $date) {
                            $tanggal = $date->format("Y-m-d");
                            $update = MappingShift::where('user_id', $request->id_user)
                                ->whereDate('tanggal_masuk', $tanggal)
                                ->update([
                                    'kelengkapan_absensi'    => 'Potong Gaji ,izin Tidak Masuk,belum dapat saldo Cuti',
                                    'status_absen'          => 'TIDAK HADIR KERJA',
                                    'no_form_izin'          => $no_form,
                                    'keterangan_izin'       => 'TRUE',
                                    'izin_id'               => $request->id,
                                ]);
                        }
                        $alert = $request->session()->flash('approveizin_success');
                        return response()->json($alert);
                    }
                    // dd('ok1');
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Pulang Cepat') {
            // dd($request->signature);
            // dd('ok');
            $no_form = $user_karyawan->kontrak_kerja . '/IP/' . date('Y/m/d') . '/' . $no;
            if ($request->signature != null) {
                $folderPath     = public_path('signature/izin/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd('ok');
                if ($request->approve == 'not_approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = 'NOT APPROVE';
                    $data->ttd_atasan   = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();

                    $mapping                       = MappingShift::where('tanggal_masuk', $request->tanggal)->where('user_id', $request->id_user)->first();
                    $mapping->keterangan_izin      = 'FALSE';
                    $mapping->izin_id              = $data->id;
                    $mapping->update();
                    $alert = $request->session()->flash('approveizin_not_approve');
                    return response()->json($alert);
                } else if ($request->approve == 'approve') {
                    // dd($request->tanggal);
                    // dd($mapping);
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = $request->status_izin;
                    $data->catatan      = $request->catatan;
                    $data->ttd_atasan   = $uniqid;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->no_form_izin         = $no_form;
                    $data->update();

                    $mapping                       = MappingShift::where('tanggal_masuk', $request->tanggal)->where('user_id', $request->id_user)->first();
                    $mapping->keterangan_izin      = 'TRUE';
                    $mapping->izin_id              = $data->id;
                    $mapping->update();

                    $alert = $request->session()->flash('approveizin_success');
                    return response()->json($alert);
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Datang Terlambat') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/izin/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd('ok');
                if ($request->approve == 'not_approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = 'NOT APPROVE';
                    $data->ttd_atasan      = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();

                    $mapping                       = MappingShift::where('user_id', $data->user_id)->where('tanggal_masuk', $data->tanggal)->first();
                    $mapping->keterangan_izin      = 'FALSE';
                    $mapping->izin_id              = $data->id;
                    $mapping->update();
                    $alert = $request->session()->flash('approveizin_not_approve');
                    return response()->json($alert);
                } else if ($request->approve == 'approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = $request->status_izin;
                    $data->ttd_atasan      = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();

                    $mapping                       = MappingShift::where('user_id', $request->id_user)->where('tanggal_masuk', $request->tanggal)->first();
                    $mapping->keterangan_izin      = 'TRUE';
                    $mapping->izin_id              = $data->id;
                    $mapping->update();
                    // dd($mapping);
                    $alert = $request->session()->flash('approveizin_success');
                    return response()->json($alert);
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        } else if ($request->izin == 'Keluar Kantor') {
            // dd($request->signature);
            // dd('ok');
            if ($request->signature != null) {
                $folderPath     = public_path('signature/izin/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
                // dd('ok');
                if ($request->approve == 'not_approve') {
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = 'NOT APPROVE';
                    $data->ttd_atasan  = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();

                    $mapping                       = MappingShift::where('tanggal_masuk', $request->tanggal)->where('user_id', $request->id_user)->first();
                    $mapping->keterangan_izin      = 'FALSE';
                    $mapping->izin_id              = $data->id;
                    $mapping->update();
                    $alert = $request->session()->flash('approveizin_not_approve');
                    return response()->json($alert);
                } else if ($request->approve == 'approve') {
                    // dd($request->tanggal);
                    // dd($mapping);
                    $data = Izin::where('id', $request->id)->first();
                    $data->status_izin  = $request->status_izin;
                    $data->ttd_atasan  = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();

                    $mapping                       = MappingShift::where('tanggal_masuk', $request->tanggal)->where('user_id', $request->id_user)->first();
                    $mapping->keterangan_izin      = 'TRUE';
                    $mapping->izin_id              = $data->id;
                    $mapping->update();

                    $alert = $request->session()->flash('approveizin_success');
                    return response()->json($alert);
                }
            } else {
                Alert::info('info', 'Tanda Tangan Harus Terisi');
                return redirect()->back()->with('info', 'Tanda Tangan Harus Terisi');
            }
        }
    }
    public function delete_izin(Request $request, $id)
    {
        // dd($id);
        $query = Izin::where('id', $id)->delete();
        $request->session()->flash('hapus_izin_sukses');
        return redirect('izin/dashboard');
    }
    public function cetak_form_izin_user($id)
    {
        // dd('ok');
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $jabatan = Jabatan::join('karyawans', function ($join) {
            $join->on('jabatans.id', '=', 'karyawans.jabatan_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'karyawans.jabatan4_id');
        })->where('karyawans.id', $user_karyawan->id)->get();
        $divisi = Divisi::join('karyawans', function ($join) {
            $join->on('divisis.id', '=', 'karyawans.divisi_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi1_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi2_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi3_id');
            $join->orOn('divisis.id', '=', 'karyawans.divisi4_id');
        })->where('karyawans.id', $user_karyawan->id)->get();
        $izin = Izin::where('id', $id)->first();
        $date1          = new DateTime($izin->tanggal);
        $date2          = new DateTime($izin->tanggal_selesai);
        $interval       = $date1->diff($date2);
        $data_interval  = $interval->days;
        // dd($data_interval);
        $departemen = Departemen::where('id', $user_karyawan->dept_id)->first();
        $user_backup = Karyawan::where('id', $izin->user_id_backup)->first();
        // dd(Izin::with('User')->where('izins.id', $id)->where('izins.status_izin', '2')->first());
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
        if ($izin->izin == 'Datang Terlambat') {
            // dd($data);
            $pdf = PDF::loadView('users/izin/form_izin_terlambat', $data)->setPaper('A5', 'landscape')->setOptions(['isRemoteEnabled' => true]);
            return $pdf->download('FORM_KETERANGAN_DATANG_TERLAMBAT_' . $user_karyawan->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Tidak Masuk (Mendadak)') {
            $pdf = PDF::loadView('users/izin/form_izin_tidak_masuk', $data);
            return $pdf->download('FORM_PENGAJUAN_IZIN_TIDAK_MASUK_' . $user_karyawan->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Pulang Cepat') {
            $pdf = PDF::loadView('users/izin/form_izin_pulang_cepat', $data)->setPaper('A5', 'landscape');
            return $pdf->download('FORM_PENGAJUAN_IZIN_PULANG_CEPAT_' . $user_karyawan->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        } else if ($izin->izin == 'Keluar Kantor') {
            $pdf = PDF::loadView('users/izin/form_izin_keluar', $data)->setPaper('A5', 'landscape');
            return $pdf->download('FORM_PENGAJUAN_IZIN_KELUAR_KANTOR_' . $user_karyawan->name . '_' . date('Y-m-d H:i:s') . '.pdf');
        }
    }
}
