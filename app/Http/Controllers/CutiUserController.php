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
use App\Models\Karyawan;
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $kontrak = $user_karyawan->kontrak_kerja;
        $site_job = $user_karyawan->site_job;
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        // dd($lokasi_site_job);
        if ($kontrak == '') {
            $request->session()->flash('kontrakkerjaNULL');
            return redirect('/home');
        }
        $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        // dd($user);
        // dd($kontrak);
        // jika level staff/admin
        if ($user == NULL) {
            $request->session()->flash('jabatanNULL');
            return redirect('/home');
        } else {
            $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
            // dd($IdLevelAtasan);
            $get_user_backup = Karyawan::where('dept_id', $user_karyawan->dept_id)
                ->where('id', '!=', $user_karyawan->id)
                ->get();
            if ($lokasi_site_job->kategori_kantor == 'sps') {
                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)', $site_job])
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
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('karyawans.jabatan_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan1_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan2_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan3_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan4_id', $get_atasan_more->id)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->first();
                    }
                    // dd($atasan);
                } else {

                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
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
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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
                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
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
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                            ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                            ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                            ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                            ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                            ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            ->first();
                    }
                } else {
                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)', $site_job])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
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
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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
                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
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
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('jabatan_id', $get_atasan_more->id)
                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                            ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            ->first();
                    }
                } else {

                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                        ->whereIn('site_job', ['ALL SITES (SP, SPS, SIP)', $site_job])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                }
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    // dd($atasan->level_jabatan);
                    if ($atasan->level_jabatan <= 2) {
                        // dd('ok');
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        // dd('ok2');
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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
            } else if ($lokasi_site_job->kategori_kantor == 'all sps') {

                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
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
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('jabatan_id', $get_atasan_more->id)
                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                            ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            ->first();
                    }
                } else {

                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->whereNotIn('site_job', ['ALL SITES (SP)', 'CV. SUMBER PANGAN - KEDIRI', 'CV. SUMBER PANGAN - TUBAN'])
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
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
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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

                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
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
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')

                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('jabatan_id', $get_atasan_more->id)
                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            ->first();
                    }
                    // dd($atasan);
                } else {
                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
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
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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
            } else if ($lokasi_site_job->kategori_kantor == 'all sip') {

                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
                    ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - TUBAN', 'CV. SUMBER PANGAN - KEDIRI', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
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
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('jabatan_id', $get_atasan_more->id)
                            ->orWhere('jabatan1_id', $get_atasan_more->id)
                            ->orWhere('jabatan2_id', $get_atasan_more->id)
                            ->orWhere('jabatan3_id', $get_atasan_more->id)
                            ->orWhere('jabatan4_id', $get_atasan_more->id)
                            ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SP)', 'CV. SUMBER PANGAN - TUBAN', 'CV. SUMBER PANGAN - KEDIRI', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            ->first();
                    }
                    // dd($atasan);
                } else {
                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                        ->whereNotIn('site_job', ['ALL SITES (SPS)', 'ALL SITES (SIP)', 'CV. SURYA INTI PANGAN - MAKASAR', 'PT. SURYA PANGAN SEMESTA - KEDIRI', 'PT. SURYA PANGAN SEMESTA - NGAWI', 'PT. SURYA PANGAN SEMESTA - SUBANG'])
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
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
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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
                $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan1_id', $IdLevelAtasan->id)
                    ->orWhere('jabatan2_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                    // ->orWhere('jabatan4_id', $IdLevelAtasan->id)
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
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    } else if ($get_atasan_site->holding == 'sip') {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sp', 'sps'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->first();
                    } else {
                        $get_atasan_more = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                            ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                            ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                            ->where('jabatans.nama_jabatan', $get_atasan_site->nama_jabatan)
                            ->where('divisis.nama_divisi', $get_atasan_site->nama_divisi)
                            ->where('bagians.nama_bagian', $get_atasan_site->nama_bagian)
                            ->whereIn('jabatans.holding', ['sps', 'sip'])
                            ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                            ->orderBy('jabatans.holding', 'DESC')
                            ->first();
                    }
                    // dd($get_atasan_more);
                    if ($get_atasan_more == NULL) {
                        $atasan = NULL;
                    } else {
                        $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                            ->where('karyawans.jabatan_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan1_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan2_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan3_id', $get_atasan_more->id)
                            ->orWhere('karyawans.jabatan4_id', $get_atasan_more->id)
                            ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                            // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                            ->first();
                    }
                    // dd($atasan);
                } else {

                    $atasan = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                        ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
                        ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                        ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                        ->where('karyawans.jabatan_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan1_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan2_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan3_id', $IdLevelAtasan->id)
                        ->orWhere('karyawans.jabatan4_id', $IdLevelAtasan->id)
                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                        ->select('karyawans.*', 'jabatans.atasan_id', 'level_jabatans.level_jabatan')
                        ->first();
                    // dd($atasan);
                }
                // dd($atasan);
                // jika atasan tingkat 1 
                if ($atasan == '') {
                    $getUserAtasan  = NULL;
                    $getUserAtasan2  = NULL;
                } else {
                    // dd($atasan->level_jabatan);
                    if ($atasan->level_jabatan <= 2) {
                        // dd('ok');
                        $getUserAtasan  = $atasan;
                        $getUserAtasan2  = $atasan;
                    } else {
                        // dd('ok2');
                        if ($atasan->atasan_id) {
                            $get_nama_jabatan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
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
                                        ->whereIn('jabatans.holding', ['sp', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                } else if ($get_atasan_site1->holding == 'sip') {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sp', 'sps'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->first();
                                } else {
                                    $get_atasan_more1 = Jabatan::Join('divisis', 'divisis.id', 'jabatans.divisi_id')
                                        ->Join('bagians', 'bagians.id', 'jabatans.bagian_id')
                                        ->Join('departemens', 'departemens.id', 'divisis.dept_id')
                                        ->where('jabatans.nama_jabatan', $get_atasan_site1->nama_jabatan)
                                        ->where('divisis.nama_divisi', $get_atasan_site1->nama_divisi)
                                        ->where('bagians.nama_bagian', $get_atasan_site1->nama_bagian)
                                        ->whereIn('jabatans.holding', ['sps', 'sip'])
                                        ->select('jabatans.id', 'departemens.id as id_departemen', 'divisis.nama_divisi', 'jabatans.nama_jabatan', 'bagians.nama_bagian', 'jabatans.holding')
                                        ->orderBy('jabatans.holding', 'DESC')
                                        ->first();
                                }
                                if ($get_atasan_more1 == NULL) {
                                    $atasan1 = NULL;
                                } else {
                                    $atasan1 = Karyawan::where('jabatan_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan1_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan2_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan3_id', $get_atasan_more1->id)
                                        ->orWhere('jabatan4_id', $get_atasan_more1->id)
                                        ->select('karyawans.*')
                                        // ->orWhere('d.nama_jabatan', $get_name_jabatan->nama_jabatan)
                                        ->first();
                                }
                                // dd($atasan);
                            } else {

                                $atasan1 = Karyawan::where('jabatan_id', $atasan->atasan_id)
                                    ->orWhere('jabatan1_id', $atasan->atasan_id)
                                    ->orWhere('jabatan2_id', $atasan->atasan_id)
                                    ->orWhere('jabatan3_id', $atasan->atasan_id)
                                    ->orWhere('jabatan4_id', $atasan->atasan_id)
                                    ->select('karyawans.*')
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
            // $getUserAtasan  = Karyawan::where('jabatan_id', $getAsatan->id)->first();

            // dd($record_data);
            $get_kategori_cuti = KategoriCuti::where('status', 1)->orderBy('nama_cuti', 'ASC')->get();
            // dd($get_user_backup);
            $thnskrg = date('Y');
            return view('users.cuti.index', [
                'title'                 => 'Tambah Permintaan Cuti Karyawan',
                'data_user'             => $user,
                'user_karyawan'         => $user_karyawan,
                'data_cuti_user'        => Cuti::where('user_id', $user_karyawan->id)->orderBy('id', 'desc')->get(),
                'getUserAtasan'         => $getUserAtasan,
                'getUserAtasan2'        => $getUserAtasan2,
                'get_user_backup'       => $get_user_backup,
                'get_kategori_cuti'     => $get_kategori_cuti,
                'user'                  => $user,
                'thnskrg'               => $thnskrg,
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

        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        $get_cuti_id = Cuti::where('id', $id)->first();
        $get_kategori_cuti = KategoriCuti::all();
        // dd($get_user_backup);
        $get_user_backup = Karyawan::where('dept_id', $user_karyawan->dept_id)
            ->where('id', '!=', $user->id)
            ->where('dept_id', $user->dept_id)
            ->get();
        $get_user_atasan = Karyawan::where('id', $get_cuti_id->id_user_atasan)->first();
        $get_user_atasan2 = Karyawan::where('id', $get_cuti_id->id_user_atasan2)->first();
        return view(
            'users.cuti.edit',
            [
                'user' => $user,
                'user_karyawan' => $user_karyawan,
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $count_tbl_cuti = Cuti::whereDate('tanggal', $request->tanggal)->whereNotNull('no_form_cuti')->count();
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
        $no_form = $user_karyawan->kontrak_kerja . '/FCT/' . date('Y/m/d') . '/' . $no;
        if ($request->cuti == 'Diluar Cuti Tahunan') {
            $jumlah_hari = explode(' ', $request->jumlah_cuti);
            $jumlah_kuota = explode(' ', $request->kuota_cuti);
            $hari = trim($jumlah_hari[0]);
            $hitung_end_date        = now()->parse($request->tanggal_cuti)->addDays($hari);
            $kuota = trim($jumlah_kuota[0]);
            $data_interval  = $hari;
            $startDate = $request->tanggal_cuti;
            $endDate = $hitung_end_date;
            $cek_tgl = Cuti::where('tanggal_mulai', date('Y-m-d', strtotime($startDate)))->where('user_id', $user_karyawan->id)->count();
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
            $data->user_id                  = Karyawan::where('id', $user_karyawan->id)->value('id');
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
            $data->approve_atasan           = Karyawan::where('id', $request->id_user_atasan)->value('name');
            $data->approve_atasan2          = Karyawan::where('id', $request->id_user_atasan2)->value('name');
            $data->id_user_atasan           = Karyawan::where('id', $request->id_user_atasan)->value('id');
            $data->id_user_atasan2          = Karyawan::where('id', $request->id_user_atasan2)->value('id');
            $data->ttd_atasan               = NULL;
            $data->ttd_atasan2              = NULL;
            $data->waktu_approve            = NULL;
            $data->waktu_approve2           = NULL;
            $data->catatan                  = NULL;
            $data->catatan2                 = NULL;
            $data->no_form_cuti             = $no_form;
            $data->update();
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'object_id' => $data->id,
                'kategory_activity' => 'CUTI',
                'activity' => $data->nama_cuti,
                'description' => 'Pengajuan ' . $data->nama_cuti . ' No Form: ' . $data->no_form_cuti . ' Tanggal ' . $data->tanggal_mulai . ' - ' . $data->tanggal_selesai . ' Keterangan  ' . $data->keterangan_cuti,
                'read_status' => 0

            ]);
            $request->session()->flash('statuscutieditsuccess', 'Berhasil');
            return redirect('cuti/dashboard');
        } else {
            // cuti tahunan
            $date_range = explode(' - ', $request->tanggal_cuti);
            $startDate = trim($date_range[0]);
            $endDate = trim($date_range[1]);
            $cek_tgl = Cuti::where('tanggal_mulai', date('Y-m-d', strtotime($startDate)))->where('user_id', $user_karyawan->id)->count();
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
            $kuota_cuti     = Karyawan::where('id', $request->id_user)->first();
            // dd($data_interval);
            $hMin14         = date('Y-m-d', strtotime("+14 day", strtotime($request->tgl_pengajuan))); //2024-04-18
            $kuota_cuti     = Karyawan::where('id', $request->id_user)->first();
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
                $data->user_id                  = Karyawan::where('id', $user_karyawan->id)->value('id');
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
                $data->approve_atasan           = Karyawan::where('id', $request->id_user_atasan)->value('name');
                $data->approve_atasan2          = Karyawan::where('id', $request->id_user_atasan2)->value('name');
                $data->id_user_atasan           = Karyawan::where('id', $request->id_user_atasan)->value('id');
                $data->id_user_atasan2          = Karyawan::where('id', $request->id_user_atasan2)->value('id');
                $data->ttd_atasan               = NULL;
                $data->ttd_atasan2              = NULL;
                $data->waktu_approve            = NULL;
                $data->waktu_approve2           = NULL;
                $data->catatan                  = NULL;
                $data->catatan2                 = NULL;
                $data->no_form_cuti             = $no_form;
                $data->update();
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'object_id' => $data->id,
                    'kategory_activity' => 'CUTI',
                    'activity' => $data->nama_cuti,
                    'description' => 'Pengajuan ' . $data->nama_cuti . ' No Form: ' . $data->no_form_cuti . ' Tanggal ' . $data->tanggal_mulai . ' - ' . $data->tanggal_selesai . ' Keterangan  ' . $data->keterangan_cuti,
                    'read_status' => 0

                ]);
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('level_jabatans', 'jabatans.level_id', '=', 'level_jabatans.id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        $data   = Cuti::with('KategoriCuti')->where('cutis.id', $id)
            ->join('karyawans', 'karyawans.id', '=', 'cutis.user_id')
            ->select('cutis.*', 'karyawans.name', 'karyawans.kontrak_kerja', 'karyawans.kuota_cuti_tahunan')
            ->first();
        // dd($data);
        $get_id_backup = Karyawan::where('id', $data->user_id_backup)->first();
        return view('users.cuti.approvecuti', [
            'user'  => $user,
            'user_karyawan'  => $user_karyawan,
            'get_id_backup'  => $get_id_backup,
            'data'  => $data
        ]);
    }
    public function cutiAbsen(Request $request)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
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
            $cek_tgl = Cuti::where('tanggal_mulai', date('Y-m-d', strtotime($startDate)))->where('user_id', $user_karyawan->id)->count();
            if ($cek_tgl > 0) {
                $request->session()->flash('tgldigunakan', 'Anda Tidak Memiliki Kuota Cuti');
                return redirect('cuti/dashboard');
            }
            // dd($hitung_end_date);
            $date1          = new DateTime($request->tanggal_cuti);
            $date2          = new DateTime($hitung_end_date);
            $kategori_cuti = $request->kategori_cuti;
            // dd(Karyawan::where('id', $request->user_backup)->value('name'));
            $insert = new Cuti();
            $insert->user_id = Karyawan::where('id', $user_karyawan->id)->value('id');
            $insert->nama_user = Karyawan::where('id', $user_karyawan->id)->value('name');
            $insert->kategori_cuti_id = KategoriCuti::where('id', $kategori_cuti)->value('id');
            $insert->nama_cuti = $request->cuti;
            $insert->tanggal = date('Y-m-d H:i:s');
            $insert->tanggal_mulai = date('Y-m-d', strtotime($startDate));
            $insert->tanggal_selesai = date('Y-m-d', strtotime($endDate));
            $insert->total_cuti = $hari;
            $insert->keterangan_cuti = $request->keterangan_cuti;
            $insert->foto_cuti = NULL;
            $insert->status_cuti = 0;
            $insert->user_id_backup = Karyawan::where('id', $request->user_backup)->value('id');
            $insert->nama_user_backup = Karyawan::where('id', $request->user_backup)->value('name');
            $insert->approve_atasan = Karyawan::where('id', $request->id_user_atasan)->value('name');
            $insert->approve_atasan2 = Karyawan::where('id', $request->id_user_atasan2)->value('name');
            $insert->id_user_atasan = Karyawan::where('id', $request->id_user_atasan)->value('id');
            $insert->nama_user_atasan = Karyawan::where('id', $request->id_user_atasan)->value('name');
            $insert->id_user_atasan2 = Karyawan::where('id', $request->id_user_atasan2)->value('id');
            $insert->nama_user_atasan2 = Karyawan::where('id', $request->id_user_atasan2)->value('name');
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
            $cek_tgl = Cuti::where('tanggal_mulai', $format_startDate)->where('user_id', $user_karyawan->id)->count();
            if ($cek_tgl > 0) {
                $request->session()->flash('tgldigunakan', 'Anda Tidak Memiliki Kuota Cuti');
                return redirect('cuti/dashboard');
            }
            $format_hmin14 = date('Y-m-d', strtotime($hMin14));
            $kuota_cuti     = Karyawan::where('id', $request->id_user)->first();
            if ($format_startDate >= $format_hmin14) {
                if ($kuota_cuti->kuota_cuti_tahunan >= $data_interval) {
                    Cuti::create([
                        'user_id' => Karyawan::where('id', $user_karyawan->id)->value('id'),
                        'nama_user' => Karyawan::where('id', $user_karyawan->id)->value('name'),
                        'kategori_cuti_id' => KategoriCuti::where('id', $kategori_cuti)->value('id'),
                        'nama_cuti' => $request->cuti,
                        'tanggal' => date('Y-m-d H:i:s'),
                        'tanggal_mulai' => date('Y-m-d', strtotime($startDate)),
                        'tanggal_selesai' => date('Y-m-d', strtotime($endDate)),
                        'total_cuti' => $data_interval,
                        'keterangan_cuti' => $request->keterangan_cuti,
                        'foto_cuti' => NULL,
                        'status_cuti' => 0,
                        'user_id_backup' => Karyawan::where('id', $request->user_backup)->value('id'),
                        'nama_user_backup' => Karyawan::where('id', $request->user_backup)->value('name'),
                        'approve_atasan' => Karyawan::where('id', $request->id_user_atasan)->value('name'),
                        'approve_atasan2' => Karyawan::where('id', $request->id_user_atasan2)->value('name'),
                        'id_user_atasan' => Karyawan::where('id', $request->id_user_atasan)->value('id'),
                        'id_user_atasan2' => Karyawan::where('id', $request->id_user_atasan2)->value('id'),
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
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

                $data->status_cuti  = 3;
                $data->ttd_atasan2  = $uniqid;
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
                    $update_mapping_cuti->keterangan_absensi = 'CUTI';
                    $update_mapping_cuti->keterangan_absensi_pulang = 'CUTI';
                    $update_mapping_cuti->kelengkapan_absensi = 'CUTI APPROVED';
                    $update_mapping_cuti->cuti_id = $data->id;
                    $update_mapping_cuti->update();
                }
                $user_cuti = Karyawan::where('id', $data->user_id)->first();
                $user_cuti->kuota_cuti_tahunan = ($user_cuti->kuota_cuti_tahunan) - ($data->total_cuti);
                $user_cuti->update();
            } else if ($request->status_cuti == '1') {
                $data = Cuti::where('id', $request->id)->first();
                if ($user_karyawan->id == $data->id_user_atasan && $user_karyawan->id == $data->id_user_atasan2) {

                    $data->status_cuti  = 3;
                    $data->ttd_atasan  = $uniqid;
                    $data->ttd_atasan2  = $uniqid;
                    $data->catatan      = $request->catatan;
                    $data->catatan2      = $request->catatan;
                    $data->waktu_approve = date('Y-m-d H:i:s');
                    $data->waktu_approve2 = date('Y-m-d H:i:s');
                    $data->update();

                    $user_cuti = Karyawan::where('id', $data->user_id)->first();
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
                        $update_mapping_cuti->keterangan_absensi = 'CUTI';
                        $update_mapping_cuti->keterangan_absensi_pulang = 'CUTI';
                        $update_mapping_cuti->kelengkapan_absensi = 'CUTI APPROVED';
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
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        // dd($id);
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
        $cuti = Cuti::where('id', $id)->first();
        $departemen = Departemen::where('id', $user_karyawan->dept_id)->first();
        $pengganti = Karyawan::where('id', $cuti->user_id_backup)->first();
        // dd($pengganti);
        // dd(Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first());
        $data = [
            'title' => 'domPDF in Laravel 10',
            'data_cuti' => Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first(),
            'jabatan' => $jabatan,
            'user_karyawan' => $user_karyawan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'pengganti' => $pengganti,
        ];
        $pdf = PDF::loadView('users/cuti/form_cuti', $data);
        return $pdf->download('FORM_PENGAJUAN_CUTI_' . $user_karyawan->name . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function get_filter_month(Request $request)
    {
        $blnskrg = date('m');
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        if ($request->filter_month == '') {
            $data    = Cuti::with('KategoriCuti')->where('user_id', $user_karyawan->id)
                ->whereMonth('tanggal', $blnskrg)
                ->orderBy('tanggal', 'DESC')->get();
        } else {
            $data    = Cuti::with('KategoriCuti')->where('user_id', $user_karyawan->id)
                ->whereMonth('tanggal', $request->filter_month)
                ->orderBy('tanggal', 'DESC')->get();
        }
        // dd($data);
        return response()->json($data);
    }
}
