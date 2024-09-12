<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Izin;
use App\Models\Jabatan;
use App\Models\KategoriCuti;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use App\Models\ResetCuti;
use PDF;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;

class CutiUserController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth()->user()->id;
        $kontrak = Auth::guard('web')->user()->kontrak_kerja;
        $site_job = Auth::guard('web')->user()->site_job;
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        // dd($lokasi_site_job);
        if ($kontrak == '') {
            $request->session()->flash('kontrakkerjaNULL');
            return redirect('/home');
        }
        $user = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        // dd($user->level_jabatan);
        // dd($kontrak);
        // jika level staff/admin
        if ($user == NULL) {
            $request->session()->flash('jabatanNULL');
            return redirect('/home');
        } else {
            $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
            // dd($IdLevelAtasan);
            $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
                ->where('id', '!=', Auth::user()->id)
                ->where('is_admin', 'user')
                ->get();
            if ($lokasi_site_job->kategori_kantor == 'sps') {
                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                    ->first();
                // dd($get_nama_jabatan);
                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                        ->where('jabatans.id', $IdLevelAtasan->atasan_id)
                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                        ->first();
                    if ($get_atasan_site->holding == 'sps') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sp')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sps')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    }

                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan1_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan2_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan3_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan4_id', $get_atasan_more->id)
                        ->select('users.*', 'jabatans.atasan_id')
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                    // dd($atasan);
                } else {

                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('users.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                    // dd($atasan);
                }
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    if ($atasan->level_jabatan <= 2) {
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = User::where('jabatan_id', $atasan->atasan_id)
                                ->orWhere('jabatan1_id', $atasan->atasan_id)
                                ->orWhere('jabatan2_id', $atasan->atasan_id)
                                ->orWhere('jabatan3_id', $atasan->atasan_id)
                                ->orWhere('jabatan4_id', $atasan->atasan_id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            if ($get_nama_jabatan1 == NULL || $get_nama_jabatan1 == '') {
                                $get_atasan_site1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $atasan->atasan_id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site1->holding == 'sps') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sp')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }

                                $atasan1 = User::where('jabatan_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan);
                            } else {

                                $atasan1 = User::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan1);
                            }
                            if ($atasan1 == NULL) {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan;
                            } else {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan1;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                            $getUserAtasan2  = $atasan;
                        }
                    }
                }
            } else if ($lokasi_site_job->kategori_kantor == 'sp') {
                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                    ->first();
                // dd($get_nama_jabatan);
                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                        ->where('jabatans.id', $IdLevelAtasan)
                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                        ->first();
                    if ($get_atasan_site->holding == 'sps') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sp')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sps')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('users.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                } else {
                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('users.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                    // dd($atasan);
                }
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    if ($atasan->level_jabatan <= 2) {
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = User::where('jabatan_id', $atasan->atasan_id)
                                ->orWhere('jabatan1_id', $atasan->atasan_id)
                                ->orWhere('jabatan2_id', $atasan->atasan_id)
                                ->orWhere('jabatan3_id', $atasan->atasan_id)
                                ->orWhere('jabatan4_id', $atasan->atasan_id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            if ($get_nama_jabatan1 == NULL || $get_nama_jabatan1 == '') {
                                $get_atasan_site1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $atasan->atasan_id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site1->holding == 'sps') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sp')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }

                                $atasan1 = User::where('jabatan_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan);
                            } else {

                                $atasan1 = User::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan1);
                            }
                            if ($atasan1 == NULL) {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan;
                            } else {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan1;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                            $getUserAtasan2  = $atasan;
                        }
                    }
                }
            } else if ($lokasi_site_job->kategori_kantor == 'sip') {
                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan)
                    ->orWhere('jabatan1_id', $IdLevelAtasan)
                    ->orWhere('jabatan2_id', $IdLevelAtasan)
                    ->orWhere('jabatan3_id', $IdLevelAtasan)
                    ->orWhere('jabatan4_id', $IdLevelAtasan)
                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                    ->first();
                // dd($get_nama_jabatan);
                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                        ->where('jabatans.id', $IdLevelAtasan)
                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                        ->first();
                    if ($get_atasan_site->holding == 'sps') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sp')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sps')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    }
                    // dd($get_atasan_more);

                    $atasan = User::where('jabatan_id', $get_atasan_more->id)
                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                } else {

                    $atasan = User::where('jabatan_id', $IdLevelAtasan)
                        ->orWhere('jabatan1_id', $IdLevelAtasan)
                        ->orWhere('jabatan2_id', $IdLevelAtasan)
                        ->orWhere('jabatan3_id', $IdLevelAtasan)
                        ->orWhere('jabatan4_id', $IdLevelAtasan)
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                }
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                } else {
                    $getUserAtasan  = $atasan;
                }
            } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                    ->first();
                // dd($get_nama_jabatan);
                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                        ->where('jabatans.id', $IdLevelAtasan->id)
                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                        ->first();
                    if ($get_atasan_site->holding == 'sps') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sp')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sps')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    }
                    // dd($get_atasan_more);

                    $atasan = User::where('jabatan_id', $get_atasan_more->id)
                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                } else {

                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('users.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                }
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    if ($atasan->level_jabatan <= 2) {
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = User::where('jabatan_id', $atasan->atasan_id)
                                ->orWhere('jabatan1_id', $atasan->atasan_id)
                                ->orWhere('jabatan2_id', $atasan->atasan_id)
                                ->orWhere('jabatan3_id', $atasan->atasan_id)
                                ->orWhere('jabatan4_id', $atasan->atasan_id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            if ($get_nama_jabatan1 == NULL || $get_nama_jabatan1 == '') {
                                $get_atasan_site1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $atasan->atasan_id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site1->holding == 'sps') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sp')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }

                                $atasan1 = User::where('jabatan_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan);
                            } else {

                                $atasan1 = User::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan1);
                            }
                            if ($atasan1 == NULL) {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan;
                            } else {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan1;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                            $getUserAtasan2  = $atasan;
                        }
                    }
                }
            } else if ($lokasi_site_job->kategori_kantor == 'all sp') {

                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                    ->first();
                // dd($get_nama_jabatan);
                if ($get_nama_jabatan == NULL || $get_nama_jabatan == '') {
                    $get_atasan_site = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                        ->where('jabatans.id', $IdLevelAtasan->id)
                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                        ->first();
                    if ($get_atasan_site->holding == 'sps') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sp')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sps')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    }
                    // dd($get_atasan_more);

                    $atasan = User::where('jabatan_id', $get_atasan_more->id)
                        ->orWhere('jabatan1_id', $get_atasan_more->id)
                        ->orWhere('jabatan2_id', $get_atasan_more->id)
                        ->orWhere('jabatan3_id', $get_atasan_more->id)
                        ->orWhere('jabatan4_id', $get_atasan_more->id)
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('users.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                } else {
                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan4_id', $IdLevelAtasan->id)
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                }
                // jika atasan tingkat 1 
                // dd($atasan);
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    if ($atasan->level_jabatan <= 2) {
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = User::where('jabatan_id', $atasan->atasan_id)
                                ->orWhere('jabatan1_id', $atasan->atasan_id)
                                ->orWhere('jabatan2_id', $atasan->atasan_id)
                                ->orWhere('jabatan3_id', $atasan->atasan_id)
                                ->orWhere('jabatan4_id', $atasan->atasan_id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            if ($get_nama_jabatan1 == NULL || $get_nama_jabatan1 == '') {
                                $get_atasan_site1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $atasan->atasan_id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site1->holding == 'sps') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sp')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }

                                $atasan1 = User::where('jabatan_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan);
                            } else {

                                $atasan1 = User::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan1);
                            }
                            if ($atasan1 == NULL) {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan;
                            } else {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan1;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                            $getUserAtasan2  = $atasan;
                        }
                    }
                }
            } else if ($lokasi_site_job->kategori_kantor == 'all') {
                $get_nama_jabatan = User::where('jabatan_id', $IdLevelAtasan->id)
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
                        ->where('jabatans.id', $IdLevelAtasan->atasan_id)
                        ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                        ->first();
                    if ($get_atasan_site->holding == 'sps') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sp')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->where('jabatans.holding', 'sps')
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    }

                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan1_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan2_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan3_id', $get_atasan_more->id)
                        ->orWhere('users.jabatan4_id', $get_atasan_more->id)
                        ->select('users.*', 'jabatans.atasan_id')
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->first();
                    // dd($atasan);
                } else {

                    $atasan = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'users.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
                        ->where('users.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('users.jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('users.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                    // dd($atasan);
                }
                // dd($atasan);
                // jika atasan tingkat 1 
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    if ($atasan->level_jabatan <= 2) {
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = User::where('jabatan_id', $atasan->atasan_id)
                                ->orWhere('jabatan1_id', $atasan->atasan_id)
                                ->orWhere('jabatan2_id', $atasan->atasan_id)
                                ->orWhere('jabatan3_id', $atasan->atasan_id)
                                ->orWhere('jabatan4_id', $atasan->atasan_id)
                                // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                ->first();
                            if ($get_nama_jabatan1 == NULL || $get_nama_jabatan1 == '') {
                                $get_atasan_site1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                    ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                    ->where('jabatans.id', $atasan->atasan_id)
                                    ->select('jabatans.id', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                    ->first();
                                if ($get_atasan_site1->holding == 'sps') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sp')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->where('jabatans.holding', 'sps')
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                }

                                $atasan1 = User::where('jabatan_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                    ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan);
                            } else {

                                $atasan1 = User::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('users.*')
                                    // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                    ->first();
                                // dd($atasan1);
                            }
                            if ($atasan1 == NULL) {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan;
                            } else {
                                $getUserAtasan  = $atasan;
                                $getUserAtasan2  = $atasan1;
                            }
                        } else {
                            $getUserAtasan  = $atasan;
                            $getUserAtasan2  = $atasan;
                        }
                    }
                }
            }

            // dd($getUseratasan2);
            // $getUserAtasan  = User::where('jabatan_id', $getAsatan->id)->first();
            $record_data    = Cuti::with('KategoriCuti')->where('user_id', Auth::user()->id)
                // ->select('cutis.*', '_cuti')
                ->orderBy('tanggal', 'DESC')->get();
            // dd($record_data);
            $get_kategori_cuti = KategoriCuti::where('status', 1)->get();
            // dd($get_user_backup);
            return view('users.cuti.index', [
                'title'             => 'Tambah Permintaan Cuti Karyawan',
                'data_user'         => $user,
                'data_cuti_user'    => Cuti::where('user_id', $user_id)->orderBy('id', 'desc')->get(),
                'getUserAtasan'     => $getUserAtasan,
                'getUserAtasan2'     => $getUserAtasan2,
                'get_user_backup'     => $get_user_backup,
                'get_kategori_cuti'     => $get_kategori_cuti,
                'user'              => $user,
                'record_data'       => $record_data
            ]);
        }
    }

    public function get_cuti(Request $request)
    {
        $id_cuti    = $request->id_cuti;
        $cuti      = KategoriCuti::where('status', 1)->where('id', $id_cuti)->first();
        // dd($cuti);
        echo "$cuti->jumlah_cuti";
    }
    public function cutiEdit($id)
    {
        $user = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        $get_cuti_id = Cuti::where('id', $id)->first();
        $get_kategori_cuti = KategoriCuti::all();
        // dd($get_user_backup);
        $get_user_backup = User::where('dept_id', Auth::user()->dept_id)
            ->where('id', '!=', $user->id)
            ->where('is_admin', 'user')
            ->where('dept_id', $user->dept_id)
            ->get();
        $get_user_atasan = User::where('id', $get_cuti_id->id_user_atasan)->first();
        $get_user_atasan2 = User::where('id', $get_cuti_id->id_user_atasan2)->first();
        return view(
            'users.cuti.edit',
            [
                'user' => $user,
                'get_kategori_cuti' => $get_kategori_cuti,
                'get_user_atasan' => $get_user_atasan,
                'get_user_atasan2' => $get_user_atasan2,
                'get_user_backup' => $get_user_backup,
                'get_cuti' => $get_cuti_id,
            ]
        );
    }
    public function cutiUpdateProses(Request $request)
    {
        // dd($request->all());
        $no_form = NULL;
        if ($request->cuti == 'Diluar Cuti Tahunan') {
            $jumlah_hari = explode(' ', $request->jumlah_cuti);
            $jumlah_kuota = explode(' ', $request->kuota_cuti);
            $hari = trim($jumlah_hari[0]);
            $hitung_end_date        = now()->parse($request->tanggal_cuti)->addDays($hari);
            $kuota = trim($jumlah_kuota[0]);
            $data_interval  = $hari;
            $startDate = $request->tanggal_cuti;
            $endDate = $hitung_end_date;
            $cek_tgl = Cuti::where('tanggal_mulai', date('Y-m-d', strtotime($startDate)))->where('user_id', Auth::user()->id)->count();
            if ($request->tanggal_cuti_old != date('Y-m-d', strtotime($startDate))) {
                if ($cek_tgl > 0) {
                    $request->session()->flash('tgldigunakan', 'Anda Tidak Memiliki Kuota Cuti');
                    return redirect('cuti/dashboard');
                }
            }
            $date1          = new DateTime($request->tanggal_cuti);
            $date2          = new DateTime($hitung_end_date);
            $kategori_cuti = $request->kategori_cuti;

            if ($request->signature !== null) {
                $folderPath     = public_path('signature/cuti/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
            } else {
                $uniqid = NULL;
            }
            $data = Cuti::where('id', $request->id)->first();
            $data->user_id                  = User::where('id', Auth::user()->id)->value('id');
            $data->kategori_cuti_id         = KategoriCuti::where('id', $kategori_cuti)->value('id');
            $data->nama_cuti                = $request->cuti;
            $data->tanggal                  = date('Y-m-d H:i:s');
            $data->tanggal_mulai            = date('Y-m-d', strtotime($startDate));
            $data->tanggal_selesai          = date('Y-m-d', strtotime($endDate));
            $data->total_cuti               = $data_interval;
            $data->keterangan_cuti          = $request->keterangan_cuti;
            $data->foto_cuti                = NULL;
            $data->ttd_user                 = $uniqid;
            $data->waktu_ttd_user           = date('Y-m-d H:i:s');
            $data->status_cuti              = 1;
            $data->user_id_backup           = $request->user_backup;
            $data->approve_atasan           = User::where('id', $request->id_user_atasan)->value('name');
            $data->approve_atasan2          = User::where('id', $request->id_user_atasan2)->value('name');
            $data->id_user_atasan           = User::where('id', $request->id_user_atasan)->value('id');
            $data->id_user_atasan2          = User::where('id', $request->id_user_atasan2)->value('id');
            $data->ttd_atasan               = NULL;
            $data->ttd_atasan2              = NULL;
            $data->waktu_approve            = NULL;
            $data->waktu_approve2           = NULL;
            $data->catatan                  = NULL;
            $data->catatan2                 = NULL;
            $data->no_form_cuti             = $no_form;
            $data->update();

            $request->session()->flash('statuscutieditsuccess', 'Berhasil');
            return redirect('cuti/dashboard');
        } else {
            // cuti tahunan
            $date_range = explode(' - ', $request->tanggal_cuti);
            $startDate = trim($date_range[0]);
            $endDate = trim($date_range[1]);
            $cek_tgl = Cuti::where('tanggal_mulai', date('Y-m-d', strtotime($startDate)))->where('user_id', Auth::user()->id)->count();
            if ($request->tanggal_cuti_old != date('Y-m-d', strtotime($startDate))) {
                if ($cek_tgl > 0) {
                    $request->session()->flash('tgldigunakan', 'Anda Tidak Memiliki Kuota Cuti');
                    return redirect('cuti/dashboard');
                }
            }
            $date1          = new DateTime($startDate);
            $date2          = new DateTime($endDate);
            $interval       = $date1->diff($date2);
            $data_interval  = $interval->days + 1;
            $kategori_cuti = NULL;
            $hMin14         = now()->parse($request->startDate)->addDays(14); //2024-04-18
            $format_startDate = date('Y-m-d', strtotime($startDate));
            $format_hmin14 = date('Y-m-d', strtotime($hMin14));
            $kuota_cuti     = User::where('id', $request->id_user)->first();
            // dd($data_interval);
            $hMin14         = date('Y-m-d', strtotime("+14 day", strtotime($request->tgl_pengajuan))); //2024-04-18
            $kuota_cuti     = User::where('id', $request->id_user)->first();
            // dd($file_save);
            if ($request->signature !== null) {
                $folderPath     = public_path('signature/cuti/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
            } else {
                $uniqid = NULL;
            }
            if ($kuota_cuti->kuota_cuti_tahunan >= $data_interval) {
                $data = Cuti::where('id', $request->id)->first();
                $data->user_id                  = User::where('id', Auth::user()->id)->value('id');
                $data->kategori_cuti_id         = KategoriCuti::where('id', $kategori_cuti)->value('id');
                $data->nama_cuti                = $request->cuti;
                $data->tanggal                  = date('Y-m-d H:i:s');
                $data->tanggal_mulai            = date('Y-m-d', strtotime($startDate));
                $data->tanggal_selesai          = date('Y-m-d', strtotime($endDate));
                $data->total_cuti               = $data_interval;
                $data->keterangan_cuti          = $request->keterangan_cuti;
                $data->foto_cuti                = NULL;
                $data->ttd_user                 = $uniqid;
                $data->waktu_ttd_user           = date('Y-m-d H:i:s');
                $data->status_cuti              = 1;
                $data->user_id_backup           = $request->user_backup;
                $data->approve_atasan           = User::where('id', $request->id_user_atasan)->value('name');
                $data->approve_atasan2          = User::where('id', $request->id_user_atasan2)->value('name');
                $data->id_user_atasan           = User::where('id', $request->id_user_atasan)->value('id');
                $data->id_user_atasan2          = User::where('id', $request->id_user_atasan2)->value('id');
                $data->ttd_atasan               = NULL;
                $data->ttd_atasan2              = NULL;
                $data->waktu_approve            = NULL;
                $data->waktu_approve2           = NULL;
                $data->catatan                  = NULL;
                $data->catatan2                 = NULL;
                $data->no_form_cuti             = $no_form;
                $data->update();

                $request->session()->flash('statuscutieditsuccess', 'Berhasil');
                return redirect('cuti/dashboard');
            } else {
                $request->session()->flash('statuscutiediterror', 'Anda Tidak Memiliki Kuota Cuti');
                return redirect('cuti/dashboard');
            }
        }
    }
    public function cutiApprove($id)
    {
        $user = User::join('jabatans', 'jabatans.id', '=', 'users.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'users.dept_id')
            ->join('divisis', 'divisis.id', '=', 'users.divisi_id')
            ->where('users.id', Auth()->user()->id)->first();
        $data   = Cuti::with('KategoriCuti')->where('cutis.id', $id)
            ->join('users', 'users.id', '=', 'cutis.user_id')
            ->select('cutis.*', 'users.name', 'users.fullname', 'users.kuota_cuti_tahunan')
            ->first();
        // dd($data);
        $get_id_backup = User::where('id', $data->user_id_backup)->first();
        return view('users.cuti.approvecuti', [
            'user'  => $user,
            'get_id_backup'  => $get_id_backup,
            'data'  => $data
        ]);
    }
    public function cutiAbsen(Request $request)
    {
        // dd($request->all());
        if ($request->approve_atasan == '') {
            $request->session()->flash('atasan1NULL');
            return redirect('cuti/dashboard');
        } else if ($request->approve_atasan2 == '') {
            $request->session()->flash('atasan2NULL');
            return redirect('cuti/dashboard');
        } else {
        }
        // No form
        $no_form = NULL;
        // dd($no_form);
        if ($request->cuti == 'Diluar Cuti Tahunan') {
            $jumlah_hari = explode(' ', $request->jumlah_cuti);
            $jumlah_kuota = explode(' ', $request->kuota_cuti);
            $hari = trim($jumlah_hari[0]);
            $hitung_end_date        = Carbon::parse($request->tanggal_cuti)->addDays($hari)->addDays(-1);
            $kuota = trim($jumlah_kuota[0]);
            $data_interval  = $hari;
            $startDate = $request->tanggal_cuti;
            $endDate = $hitung_end_date;
            $cek_tgl = Cuti::where('tanggal_mulai', date('Y-m-d', strtotime($startDate)))->where('user_id', Auth::user()->id)->count();
            if ($cek_tgl > 0) {
                $request->session()->flash('tgldigunakan', 'Anda Tidak Memiliki Kuota Cuti');
                return redirect('cuti/dashboard');
            }
            // dd($hitung_end_date);
            $date1          = new DateTime($request->tanggal_cuti);
            $date2          = new DateTime($hitung_end_date);
            $kategori_cuti = $request->kategori_cuti;
            // dd(User::where('id', $request->user_backup)->value('name'));
            $insert = new Cuti();
            $insert->user_id = User::where('id', Auth::user()->id)->value('id');
            $insert->nama_user = User::where('id', Auth::user()->id)->value('name');
            $insert->kategori_cuti_id = KategoriCuti::where('id', $kategori_cuti)->value('id');
            $insert->nama_cuti = $request->cuti;
            $insert->tanggal = date('Y-m-d H:i:s');
            $insert->tanggal_mulai = date('Y-m-d', strtotime($startDate));
            $insert->tanggal_selesai = date('Y-m-d', strtotime($endDate));
            $insert->total_cuti = $hari;
            $insert->keterangan_cuti = $request->keterangan_cuti;
            $insert->foto_cuti = NULL;
            $insert->status_cuti = 0;
            $insert->user_id_backup = User::where('id', $request->user_backup)->value('id');
            $insert->nama_user_backup = User::where('id', $request->user_backup)->value('name');
            $insert->approve_atasan = User::where('id', $request->id_user_atasan)->value('name');
            $insert->approve_atasan2 = User::where('id', $request->id_user_atasan2)->value('name');
            $insert->id_user_atasan = User::where('id', $request->id_user_atasan)->value('id');
            $insert->nama_user_atasan = User::where('id', $request->id_user_atasan)->value('name');
            $insert->id_user_atasan2 = User::where('id', $request->id_user_atasan2)->value('id');
            $insert->nama_user_atasan2 = User::where('id', $request->id_user_atasan2)->value('name');
            $insert->ttd_atasan = NULL;
            $insert->ttd_atasan2 = NULL;
            $insert->waktu_approve = NULL;
            $insert->waktu_approve2 = NULL;
            $insert->catatan = NULL;
            $insert->catatan2 = NULL;
            $insert->no_form_cuti = $no_form;
            $insert->save();
        } else {
            // cuti tahunan
            $date_range = explode(' - ', $request->tanggal_cuti);
            $startDate = trim($date_range[0]);
            $endDate = trim($date_range[1]);
            $date1          = new DateTime($startDate);
            $date2          = new DateTime($endDate);
            $interval       = $date1->diff($date2);
            $data_interval  = $interval->days + 1;
            $kategori_cuti = NULL;
            $hMin14         = now()->parse($request->startDate)->addDays(14); //2024-04-18
            $format_startDate = date('Y-m-d', strtotime($startDate));
            $cek_tgl = Cuti::where('tanggal_mulai', $format_startDate)->where('user_id', Auth::user()->id)->count();
            if ($cek_tgl > 0) {
                $request->session()->flash('tgldigunakan', 'Anda Tidak Memiliki Kuota Cuti');
                return redirect('cuti/dashboard');
            }
            $format_hmin14 = date('Y-m-d', strtotime($hMin14));
            $kuota_cuti     = User::where('id', $request->id_user)->first();
            if ($format_startDate >= $format_hmin14) {
                if ($kuota_cuti->kuota_cuti_tahunan >= $data_interval) {
                    Cuti::create([
                        'user_id' => User::where('id', Auth::user()->id)->value('id'),
                        'nama_user' => User::where('id', Auth::user()->id)->value('name'),
                        'kategori_cuti_id' => KategoriCuti::where('id', $kategori_cuti)->value('id'),
                        'nama_cuti' => $request->cuti,
                        'tanggal' => date('Y-m-d H:i:s'),
                        'tanggal_mulai' => date('Y-m-d', strtotime($startDate)),
                        'tanggal_selesai' => date('Y-m-d', strtotime($endDate)),
                        'total_cuti' => $data_interval,
                        'keterangan_cuti' => $request->keterangan_cuti,
                        'foto_cuti' => NULL,
                        'status_cuti' => 0,
                        'user_id_backup' => User::where('id', $request->user_backup)->value('id'),
                        'nama_user_backup' => User::where('id', $request->user_backup)->value('name'),
                        'approve_atasan' => User::where('id', $request->id_user_atasan)->value('name'),
                        'approve_atasan2' => User::where('id', $request->id_user_atasan2)->value('name'),
                        'id_user_atasan' => User::where('id', $request->id_user_atasan)->value('id'),
                        'id_user_atasan2' => User::where('id', $request->id_user_atasan2)->value('id'),
                        'ttd_atasan' => NULL,
                        'ttd_atasan2' => NULL,
                        'waktu_approve' => NULL,
                        'waktu_approve2' => NULL,
                        'catatan' => NULL,
                        'catatan2' => NULL,
                        'no_form_cuti' => $no_form,
                    ]);

                    $request->session()->flash('addcutisuccess', 'Berhasil');
                    return redirect('cuti/dashboard');
                } else {
                    $request->session()->flash('addcutierror1', 'Anda Tidak Memiliki Kuota Cuti');
                    return redirect('cuti/dashboard');
                }
            } else {
                $request->session()->flash('addcutierror2', 'Pengajuan Harus H-14 untuk cuti');
                return redirect('cuti/dashboard');
            }
        }
        $request->session()->flash('addcutisuccess', 'Berhasil');
        return redirect('cuti/dashboard');
    }
    public function cutiApproveProses(Request $request)
    {
        if ($request->approve == 'not_approve') {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/cuti/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
            } else {
                $uniqid = NULL;
            }
            if ($request->status_cuti == '2') {
                $data = Cuti::where('id', $request->id)->first();
                $data->status_cuti  = 'NOT APPROVE';
                $data->ttd_atasan2  = $uniqid;
                $data->catatan2      = $request->catatan;
                $data->waktu_approve = date('Y-m-d H:i:s');
                $data->update();
            } else if ($request->status_cuti == '1') {
                $data = Cuti::where('id', $request->id)->first();
                $data->status_cuti  = 'NOT APPROVE';
                $data->catatan      = $request->catatan;
                $data->ttd_atasan  = $uniqid;
                $data->waktu_approve = date('Y-m-d H:i:s');
                $data->update();
            }
            $alert = $request->session()->flash('approvecuti_not_approve');
            return response()->json($alert);
        } else {
            if ($request->signature != null) {
                $folderPath     = public_path('signature/cuti/');
                $image_parts    = explode(";base64,", $request->signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $uniqid         = date('y-m-d') . '-' . uniqid();
                $file           = $folderPath . $uniqid . '.' . $image_type;
                file_put_contents($file, $image_base64);
            } else {
                $uniqid = NULL;
            }
            if ($request->status_cuti == '2') {
                $data = Cuti::where('id', $request->id)->first();
                $count_tbl_cuti = Cuti::whereDate('tanggal', $data->tanggal)->whereNotNull('no_form_cuti')->count();
                // dd($count_tbl_cuti);
                $countstr = strlen($count_tbl_cuti + 1);
                if ($countstr == '1') {
                    $no = '0000' . $count_tbl_cuti + 1;
                } else if ($countstr == '2') {
                    $no = '000' . $count_tbl_cuti + 1;
                } else if ($countstr == '3') {
                    $no = '00' . $count_tbl_cuti + 1;
                } else if ($countstr == '4') {
                    $no = '0' . $count_tbl_cuti + 1;
                } else {
                    $no = $count_tbl_cuti + 1;
                }
                $no_form = Auth::user()->kontrak_kerja . '/FCT/' . date('Y/m/d') . '/' . $no;
                $data->status_cuti  = 3;
                $data->ttd_atasan2  = $uniqid;
                $data->no_form_cuti  = $no_form;
                $data->catatan2      = $request->catatan;
                $data->waktu_approve2 = date('Y-m-d H:i:s');
                $data->update();

                $begin = new \DateTime($data->tanggal_mulai);
                $end = new \DateTime($data->tanggal_selesai);
                $end = $end->modify('+1 day');

                $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                $daterange = new \DatePeriod($begin, $interval, $end);


                foreach ($daterange as $date) {
                    $tanggal = $date->format("Y-m-d");
                    $update_mapping_cuti = MappingShift::where('user_id', $data->user_id)->where('tanggal_masuk', $tanggal)->first();
                    $update_mapping_cuti->status_absen = 'TIDAK HADIR KERJA';
                    $update_mapping_cuti->keterangan_cuti = 'TRUE';
                    $update_mapping_cuti->cuti_id = $data->id;
                    $update_mapping_cuti->update();
                }
                $user_cuti = User::where('id', $data->user_id)->first();
                $user_cuti->kuota_cuti_tahunan = ($user_cuti->kuota_cuti_tahunan) - ($data->total_cuti);
                $user_cuti->update();
            } else if ($request->status_cuti == '1') {
                $data = Cuti::where('id', $request->id)->first();
                if (Auth::user()->id == $data->id_user_atasan && Auth::user()->id == $data->id_user_atasan2) {
                    $count_tbl_cuti = Cuti::whereDate('tanggal', $data->tanggal)->whereNotNull('no_form_cuti')->count();
                    // dd($count_tbl_cuti);
                    $countstr = strlen($count_tbl_cuti + 1);
                    if ($countstr == '1') {
                        $no = '0000' . $count_tbl_cuti + 1;
                    } else if ($countstr == '2') {
                        $no = '000' . $count_tbl_cuti + 1;
                    } else if ($countstr == '3') {
                        $no = '00' . $count_tbl_cuti + 1;
                    } else if ($countstr == '4') {
                        $no = '0' . $count_tbl_cuti + 1;
                    } else {
                        $no = $count_tbl_cuti + 1;
                    }
                    $no_form = Auth::user()->kontrak_kerja . '/FCT/' . date('Y/m/d') . '/' . $no;
                    $data->status_cuti  = 3;
                    $data->ttd_atasan  = $uniqid;
                    $data->ttd_atasan2  = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->catatan2      = $request->catatan;
                    $data->no_form_cuti  = $no_form;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->waktu_approve2 = date('Y-m-d H:i:s');
                    $data->update();

                    $user_cuti = User::where('id', $data->user_id)->first();
                    $user_cuti->kuota_cuti_tahunan = ($user_cuti->kuota_cuti_tahunan) - ($data->total_cuti);
                    $user_cuti->update();

                    $begin = new \DateTime($data->tanggal_mulai);
                    $end = new \DateTime($data->tanggal_selesai);
                    $end = $end->modify('+1 day');

                    $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
                    $daterange = new \DatePeriod($begin, $interval, $end);


                    foreach ($daterange as $date) {
                        $tanggal = $date->format("Y-m-d");
                        $update_mapping_cuti = MappingShift::where('user_id', $data->user_id)->where('tanggal_masuk', $tanggal)->first();
                        $update_mapping_cuti->status_absen = 'TIDAK HADIR KERJA';
                        $update_mapping_cuti->keterangan_cuti = 'TRUE';
                        $update_mapping_cuti->cuti_id = $data->id;
                        $update_mapping_cuti->update();
                    }
                } else {

                    $data->status_cuti  = 2;
                    $data->ttd_atasan  = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->update();
                }
            }
            $alert = $request->session()->flash('approvecuti_success');
            return response()->json($alert);
        }
    }

    public function delete_cuti(Request $request, $id)
    {
        // dd($id);
        $query = Cuti::where('id', $id)->delete();
        $request->session()->flash('hapus_cuti_sukses');
        return redirect('cuti/dashboard');
    }
    public function cetak_form_cuti($id)
    {
        // dd($id);
        $jabatan = Jabatan::join('users', function ($join) {
            $join->on('jabatans.id', '=', 'users.jabatan_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan1_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan2_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan3_id');
            $join->orOn('jabatans.id', '=', 'users.jabatan4_id');
        })->where('users.id', Auth::user()->id)->get();
        $divisi = Divisi::join('users', function ($join) {
            $join->on('divisis.id', '=', 'users.divisi_id');
            $join->orOn('divisis.id', '=', 'users.divisi1_id');
            $join->orOn('divisis.id', '=', 'users.divisi2_id');
            $join->orOn('divisis.id', '=', 'users.divisi3_id');
            $join->orOn('divisis.id', '=', 'users.divisi4_id');
        })->where('users.id', Auth::user()->id)->get();
        $cuti = Cuti::where('id', $id)->first();
        $departemen = Departemen::where('id', Auth::user()->dept_id)->first();
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
        $pdf = PDF::loadView('users/cuti/form_cuti', $data);
        return $pdf->download('FORM_PENGAJUAN_CUTI_' . Auth::user()->name . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
