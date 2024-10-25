<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Departemen;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MappingShift;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;
use App\Models\Karyawan;
use App\Models\KategoriCuti;
use App\Models\LevelJabatan;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use PDF;

class PenugasanUserController extends Controller
{
    public function index()
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user_id        = $user_karyawan->id;
        $user           = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->leftJoin('level_jabatans', 'level_jabatans.id', '=', 'jabatans.level_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        // dd($user);
        $site_job = $user_karyawan->site_job;
        $master_lokasi = Lokasi::whereNotIn('kategori_kantor', ['all sps', 'all sp', 'all sip', 'all'])->get();
        $userLevel      = LevelJabatan::where('id', $user->level_id)->first();
        $lokasi_site_job = Lokasi::where('lokasi_kantor', $site_job)->first();
        // dd($user->kontrak_kerja);
        if ($user->kontrak_kerja == 'SP') {
            // Bu fitri
            $hrd = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('karyawans.*')
                ->get();
            $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->where('jabatans.holding', 'sp')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('karyawans.*')
                ->get();
        } else if ($user->kontrak_kerja == 'SPS') {
            $hrd = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('karyawans.*')
                ->get();
            // diana sps
            $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->where('jabatans.holding', 'sps')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('karyawans.*')
                ->get();
            // dd($finance);
        } else {
            $hrd = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('karyawans.*')
                ->get();
            // diana sps
            $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->where('jabatans.holding', 'sip')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('karyawans.*')
                ->get();
        }
        $IdLevelAtasan = Jabatan::where('id', $user->atasan_id)->first();
        if ($lokasi_site_job->kategori_kantor == 'sps') {
            $get_nama_jabatan = Karyawan::where('jabatan_id', $IdLevelAtasan->id)
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
                ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                ->orWhere('jabatan4_id', $IdLevelAtasan->id)
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
                ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                ->orWhere('jabatan4_id', $IdLevelAtasan->id)
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
                ->orWhere('jabatan3_id', $IdLevelAtasan->id)
                ->orWhere('jabatan4_id', $IdLevelAtasan->id)
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
        $departemen = Departemen::groupBy('nama_departemen')->get();
        $record_data        = Penugasan::join('karyawans', 'karyawans.id', 'penugasans.id_user')->where('id_user', $user_karyawan->id)
            ->select('penugasans.*', 'karyawans.name')->orderBy('tanggal_pengajuan', 'DESC')->get();
        // dd($record_data);
        $lokasi_kantor = Lokasi::whereNotIn('kategori_kantor', ['all', 'all sp', 'all sps', 'all sip'])->where('lokasi_kantor', '!=', $user->penempatan_kerja)->get();
        $get_kategori_cuti  = KategoriCuti::where('status', 1)->get();
        $get_user_backup    = Karyawan::where('dept_id', $user_karyawan->dept_id)->where('divisi_id', $user_karyawan->divisi_id)->where('id', '!=', $user_karyawan->id)->get();
        return view(
            'users.penugasan.index',
            [
                'title'                 => 'Tambah Permintaan Cuti Karyawan',
                'data_user'             => $user,
                'user_karyawan'         => $user_karyawan,
                'data_cuti_user'        => Penugasan::where('id_user', $user_id)->orderBy('id', 'desc')->get(),
                'getUserAtasan'         => $getUserAtasan,
                'get_user_backup'       => $get_user_backup,
                'get_kategori_cuti'     => $get_kategori_cuti,
                'user'                  => $user,
                'record_data'           => $record_data,
                'hrd'                   => $hrd,
                'lokasi_kantor'         => $lokasi_kantor,
                'departemen'            => $departemen,
                'master_lokasi'         => $master_lokasi,
            ]
        );
    }

    public function get_diminta(Request $request)
    {
        // dd($request->all());
        if ($request->level > 2) {

            $diminta = Karyawan::leftJoin('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->leftJoin('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->where('karyawans.dept_id', $request->dept)
                ->select('karyawans.id', 'karyawans.name')
                ->orderBy('karyawans.name')
                ->get();
        } else {
            $diminta = Karyawan::leftJoin('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
                ->leftJoin('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
                ->where('level_jabatans.level_jabatan', '<', $request->level)
                ->select('karyawans.id', 'karyawans.name')
                ->orderBy('karyawans.name')
                ->get();
        }
        echo "<option value=''>Pilih Diminta...</option>";
        foreach ($diminta as $diminta) {
            echo "<option value='$diminta->id' data-tokens='$diminta->name'>$diminta->name</option>";
        }
    }
    public function get_diminta_departemen(Request $request)
    {
        // dd($request->all());
        $diminta = Karyawan::leftJoin('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
            ->leftJoin('departemens', 'departemens.id', 'karyawans.dept_id')
            ->leftJoin('level_jabatans', 'level_jabatans.id', 'jabatans.level_id')
            ->where('level_jabatans.level_jabatan', '<', $request->level)
            ->where('departemens.nama_departemen', $request->value)
            ->select('karyawans.id', 'karyawans.name')
            ->orderBy('karyawans.name')
            ->get();
        echo "<option value=''>Pilih Diminta...</option>";
        foreach ($diminta as $diminta) {
            echo "<option value='$diminta->id' data-tokens='$diminta->name'>$diminta->name</option>";
        }
    }
    public function get_biaya_ditanggung(Request $request)
    {
        // dd($request->all());

        echo "<option value=''>Pilih Diminta...</option> <option value='$request->asal_kerja' data-tokens='$request->asal_kerja'>$request->asal_kerja</option> <option value='$request->alamat_kunjungan' data-tokens='$request->alamat_kunjungan'>$request->alamat_kunjungan</option>";
    }
    public function get_finance(Request $request)
    {
        // dd($request->all());
        if ($request->value == 'CV. SUMBER PANGAN - KEDIRI') {
            $lokasi = ['CV. SUMBER PANGAN - KEDIRI', 'ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)'];
        } else if ($request->value == 'PT. SURYA PANGAN SEMESTA - KEDIRI') {
            $lokasi = ['PT. SURYA PANGAN SEMESTA - KEDIRI', 'ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)'];
        } else if ($request->value == 'CV. SUMBER PANGAN - TUBAN') {
            $lokasi = ['CV. SUMBER PANGAN - TUBAN', 'ALL SITES (SP)', 'ALL SITES (SP, SPS, SIP)'];
        } else if ($request->value == 'PT. SURYA PANGAN SEMESTA - SUBANG') {
            $lokasi = ['PT. SURYA PANGAN SEMESTA - SUBANG', 'ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)'];
        } else if ($request->value == 'PT. SURYA PANGAN SEMESTA - NGAWI') {
            $lokasi = ['PT. SURYA PANGAN SEMESTA - NGAWI', 'ALL SITES (SPS)', 'ALL SITES (SP, SPS, SIP)'];
        } else if ($request->value == 'CV. SURYA INTI PANGAN - MAKASAR') {
            $lokasi = ['CV. SURYA INTI PANGAN - MAKASAR', 'ALL SITES (SIP)', 'ALL SITES (SP, SPS, SIP)'];
        }
        $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->whereIn('karyawans.penempatan_kerja', $lokasi)
            ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
            ->where('divisis.nama_divisi', 'FINANCE')
            ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
            ->select('karyawans.*', 'bagians.nama_bagian')
            ->orderBy('karyawans.name')
            ->get();
        echo "<option value=''>Pilih Finance...</option>";
        foreach ($finance as $finance) {
            echo "<option value='$finance->id' data-tokens='$finance->name'>$finance->name($finance->nama_bagian)</option>";
        }
    }

    public function tambahPenugasan(Request $request)
    {
        // dd($request->all());
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $date_now = Carbon::now();
        // dd('oke');
        if ($request->alamat_dikunjungi == NULL) {
            $alamat_dikunjungi = $request->alamat_dikunjungi1;
        } else {
            $alamat_dikunjungi = $request->alamat_dikunjungi;
        }
        $jumlah_hari = explode(' ', $request->tanggal_kunjungan);
        $startDate = trim($jumlah_hari[0]);
        $endDate = trim($jumlah_hari[2]);
        $date1          = new DateTime($startDate);
        $date2          = new DateTime($endDate);
        $tanggal = date('Y-m-d', strtotime($startDate));
        $tanggal_selesai = date('Y-m-d', strtotime($endDate));
        if ($request->diminta_oleh_kategori == 'Atasan') {
            $diminta_oleh = $request->diminta_oleh;
        } else if ($request->diminta_oleh_kategori == 'Saya Sendiri') {
            $diminta_oleh = $request->diminta_oleh_saya;
        } else {
            $diminta_oleh = $request->diminta_oleh;
        }

        Penugasan::create([
            'id_user'                       => Karyawan::where('id', $user_karyawan->id)->value('id'),
            'nama_user'                     => Karyawan::where('id', $user_karyawan->id)->value('name'),
            'id_user_atasan'                => Karyawan::where('id', $request->id_user_atasan)->value('id'),
            'id_jabatan'                    => Jabatan::where('id', $request->id_jabatan)->value('id'),
            'id_departemen'                 => Departemen::where('id', $request->id_departemen)->value('id'),
            'id_divisi'                     => Divisi::where('id', $request->id_divisi)->value('id'),
            'asal_kerja'                    => $request->asal_kerja,
            'id_diajukan_oleh'              => Karyawan::where('id', $request->id_diajukan_oleh)->value('id'),
            'nama_diajukan'                 => Karyawan::where('id', $request->id_diajukan_oleh)->value('name'),
            'ttd_id_diajukan_oleh'          => $request->ttd_id_diajukan_oleh,
            'waktu_ttd_id_diajukan_oleh'    => $request->waktu_ttd_id_diajukan_oleh,
            'id_diminta_oleh'               => Karyawan::where('id', $diminta_oleh)->value('id'),
            'nama_diminta'                  => Karyawan::where('id', $diminta_oleh)->value('name'),
            'ttd_id_diminta_oleh'           => $request->ttd_id_diminta_oleh,
            'waktu_ttd_id_diminta_oleh'     => $request->waktu_ttd_id_diminta_oleh,
            'id_disahkan_oleh'              => Karyawan::where('id', $request->id_disahkan_oleh)->value('id'),
            'nama_disahkan'                 => Karyawan::where('id', $request->id_disahkan_oleh)->value('name'),
            'ttd_id_disahkan_oleh'          => $request->ttd_id_disahkan_oleh,
            'waktu_ttd_id_disahkan_oleh'    => $request->waktu_ttd_id_disahkan_oleh,
            'id_user_hrd'                   => Karyawan::where('id', $request->proses_hrd)->value('id'),
            'nama_hrd'                      => Karyawan::where('id', $request->proses_hrd)->value('name'),
            'ttd_proses_hrd'                => $request->ttd_proses_hrd,
            'waktu_ttd_proses_hrd'          => $request->waktu_ttd_proses_hrd,
            'biaya_ditanggung'              => $request->biaya_ditanggung_oleh,
            'id_user_finance'               => Karyawan::where('id', $request->proses_finance)->value('id'),
            'nama_finance'                  => Karyawan::where('id', $request->proses_finance)->value('name'),
            'ttd_proses_finance'            => $request->ttd_proses_finance,
            'waktu_ttd_proses_finance'      => $request->waktu_ttd_proses_finance,
            'penugasan'                     => $request->penugasan,
            'wilayah_penugasan'             => $request->wilayah_penugasan,
            'tanggal_kunjungan'             => $tanggal,
            'selesai_kunjungan'             => $tanggal_selesai,
            'kegiatan_penugasan'            => $request->kegiatan_penugasan,
            'pic_dikunjungi'                => $request->pic_dikunjungi,
            'alamat_dikunjungi'             => $alamat_dikunjungi,
            'transportasi'                  => $request->transportasi,
            'kelas'                         => $request->kelas,
            'budget_hotel'                  => $request->budget_hotel,
            'makan'                         => $request->makan,
            'status_penugasan'              => 0,
            'tanggal_pengajuan'             => $request->tanggal_pengajuan,

        ]);
        $request->session()->flash('penugasansukses', 'Berhasil Membuat Perdin');
        return redirect('/penugasan/dashboard');
        // } else {
        //     $request->session()->flash('penugasangagal1');
        //     return redirect('/penugasan/dashboard');
        // }
    }

    public function penugasanEdit($id)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $user           = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        $penugasan      = Penugasan::join('jabatans', 'jabatans.id', 'penugasans.id_jabatan')
            ->join('departemens', 'departemens.id', 'penugasans.id_departemen')
            ->join('divisis', 'divisis.id', 'penugasans.id_divisi')
            ->join('karyawans', 'karyawans.id', 'penugasans.id_diminta_oleh')
            ->where('penugasans.id', $id)->first();
        // $id_penugasan   = $id;
        $master_lokasi = Lokasi::whereNotIn('kategori_kantor', ['all sps', 'all sp', 'all sip', 'all'])->get();
        $diminta = Karyawan::where(['id' => $penugasan->id_diminta_oleh])->first();
        $disahkan = Karyawan::where(['id' => $penugasan->id_disahkan_oleh])->first();
        if ($user->kontrak_kerja == 'SP') {
            // Bu fitri
            $hrd = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('karyawans.*')
                ->get();
            $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->where('jabatans.holding', 'sp')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('karyawans.*')
                ->get();
        } else if ($user->kontrak_kerja == 'SPS') {
            // dd('ok');
            $hrd = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->select('karyawans.*')
                ->get();
            // diana sps
            $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->where('jabatans.holding', 'sps')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('karyawans.*')
                ->get();
            // dd($finance);
        } else {
            $hrd = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                // ->where('jabatans.holding', 'sp')
                ->where('jabatans.nama_jabatan', 'MANAGER')
                ->where('bagians.nama_bagian', 'HRD & GA')
                ->where('divisis.nama_divisi', 'HRD & GA')
                ->where('departemens.nama_departemen', 'HRD & GA')
                ->select('karyawans.*')
                ->get();
            // diana sps
            $finance = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
                ->join('bagians', 'bagians.id', '=', 'jabatans.bagian_id')
                ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
                ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
                ->where('jabatans.holding', 'sip')
                ->where('bagians.nama_bagian', 'CASH AND BANK (CASHIER)')
                ->where('divisis.nama_divisi', 'FINANCE')
                ->where('departemens.nama_departemen', 'FINANCE AND ACCOUNTING')
                ->select('karyawans.*')
                ->get();
        }

        // dd($hrd);
        return view('users.penugasan.edit', [
            'penugasan'     => $penugasan,
            'user'          => $user,
            'diminta'          => $diminta,
            'disahkan'          => $disahkan,
            'hrd'          => $hrd,
            'finance'          => $finance,
            'id_penugasan'  => $id,
            'master_lokasi'  => $master_lokasi,
        ]);
    }

    public function penugasanUpdate(Request $request, $id)
    {
        dd($request->all());
        if ($request->alamat_dikunjungi == NULL) {
            $alamat_dikunjungi = $request->alamat_dikunjungi1;
        } else {
            $alamat_dikunjungi = $request->alamat_dikunjungi;
        }
        $folderPath     = public_path('signature/penugasan/');
        $image_parts    = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $uniqid         = date('y-m-d') . '-' . uniqid();
        $file           = $folderPath . $uniqid . '.' . $image_type;
        file_put_contents($file, $image_base64);
        $data                               = Penugasan::find($id);
        $data->asal_kerja                   = $request->asal_kerja;
        $data->penugasan                    = $request->penugasan;
        $data->wilayah_penugasan                    = $request->wilayah_penugasan;
        $data->tanggal_kunjungan            = $request->tanggal_kunjungan;
        $data->selesai_kunjungan            = $request->selesai_kunjungan;
        $data->kegiatan_penugasan           = $request->kegiatan_penugasan;
        $data->pic_dikunjungi               = $request->pic_dikunjungi;
        $data->alamat_dikunjungi            = $alamat_dikunjungi;
        $data->transportasi                 = $request->transportasi;
        $data->kelas                        = $request->kelas;
        $data->budget_hotel                 = $request->budget_hotel;
        $data->makan                        = $request->makan;
        $data->id_user_hrd                  = Karyawan::where('id', $request->proses_hrd)->value('id');
        $data->nama_hrd                     = Karyawan::where('id', $request->proses_hrd)->value('name');
        $data->id_user_finance              = Karyawan::where('id', $request->proses_finance)->value('id');
        $data->nama_finance                 = Karyawan::where('id', $request->proses_finance)->value('name');
        $data->ttd_id_diajukan_oleh         = $uniqid;
        $data->waktu_ttd_id_diajukan_oleh   = date('Y-m-d H:i:s');
        $data->status_penugasan             = 1;
        $data->save();
        $request->session()->flash('updatesukses', 'Berhasil Membuat Perdin');
        return redirect('/penugasan/dashboard');
    }


    public function approveShow($id)
    {
        $user       = Karyawan::join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->join('departemens', 'departemens.id', '=', 'karyawans.dept_id')
            ->join('divisis', 'divisis.id', '=', 'karyawans.divisi_id')
            ->where('karyawans.id', $user_karyawan->id)->first();
        $penugasan  = Penugasan::join('jabatans', 'jabatans.id', 'penugasans.id_jabatan')
            ->join('departemens', 'departemens.id', 'penugasans.id_departemen')
            ->join('karyawans', 'karyawans.id', 'penugasans.id_user')
            ->join('divisis', 'divisis.id', 'penugasans.id_divisi')
            ->where('penugasans.id', $id)->first();
        // dd($penugasan);
        // $id_penugasan   = $id;
        $diminta = Karyawan::where(['id' => $penugasan->id_diminta_oleh])->first();
        $disahkan = Karyawan::where(['id' => $penugasan->id_disahkan_oleh])->first();
        $hrd = Karyawan::where('id', 'e30d4a42-5562-415c-b1b6-f6b9ccc379a1')->first();
        if ($user->kontrak_kerja == 'sp') {
            // kasir SP
            $finance = Karyawan::where('id', '436da676-5782-4f4e-ad50-52b45060430c')->first();
        } else {
            // diana sps
            $finance = Karyawan::where('id', 'b709b754-7b00-4118-ab3f-e9b2760b08cf')->first();
        }
        $id_penugasan   = Penugasan::where('id', $id)->first();
        return view('users.penugasan.approve', [
            'penugasan' => $penugasan,
            'user'      => $user,
            'id_penugasan'  => $id_penugasan,
            'diminta'  => $diminta,
            'disahkan'  => $disahkan,
            'hrd'  => $hrd,
            'finance'  => $finance,
        ]);
    }

    public function approvePenugasan(Request $request, $id)
    {
        // dd($request->all());
        $folderPath     = public_path('signature/penugasan/');
        $image_parts    = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $uniqid         = date('Y-m-d') . '-' . uniqid();
        $file           = $folderPath . $uniqid . '.' . $image_type;
        file_put_contents($file, $image_base64);
        $data                               = Penugasan::find($id);
        if ($request->status_penugasan == 2) {
            $data->ttd_id_diminta_oleh          = $uniqid;
            $data->waktu_ttd_id_diminta_oleh    = date('Y-m-d H:i:s');
        } else if ($request->status_penugasan == 3) {
            $data->ttd_id_disahkan_oleh          = $uniqid;
            $data->waktu_ttd_id_disahkan_oleh    = date('Y-m-d H:i:s');
        } else if ($request->status_penugasan == 4) {
            $data->ttd_proses_hrd          = $uniqid;
            $data->waktu_ttd_proses_hrd    = date('Y-m-d H:i:s');
        } else if ($request->status_penugasan == 5) {
            $data->ttd_proses_finance          = $uniqid;
            $data->waktu_ttd_proses_finance    = date('Y-m-d H:i:s');
        }
        $data->status_penugasan             = $request->status_penugasan;
        $data->save();
        $request->session()->flash('approveperdinsukses', 'Berhasil Approve Perjalanan Dinas');
        return redirect('/home');
    }
    public function delete_penugasan(Request $request, $id)
    {
        // dd($id);
        $query = Penugasan::where('id', $id)->delete();
        $request->session()->flash('hapussukses', 'Berhasil MembuatHapus Perdin');
        return redirect('penugasan/dashboard');
    }
    public function cetak_form_penugasan($id)
    {
        $user_karyawan = Karyawan::where('id', Auth::user()->karyawan_id)->first();
        $penugasan = Penugasan::join('karyawans', 'karyawans.id', 'penugasans.id_user')->where('penugasans.id', $id)->first();
        $penugasan1 = Penugasan::join('karyawans', 'karyawans.id', 'penugasans.id_diminta_oleh')->where('penugasans.id', $id)->first();
        $penugasan2 = Penugasan::join('karyawans', 'karyawans.id', 'penugasans.id_disahkan_oleh')->where('penugasans.id', $id)->first();
        $departemen = Departemen::where('id', $penugasan->id_departemen)->first();
        $divisi = Divisi::where('id', $penugasan->id_divisi)->first();
        $jabatan = Jabatan::where('id', $penugasan->id_jabatan)->first();
        $departemen1 = Departemen::where('id', $penugasan1->dept_id)->first();
        $divisi1 = Divisi::where('id', $penugasan1->divisi_id)->first();
        $jabatan1 = Jabatan::where('id', $penugasan1->jabatan_id)->first();
        $departemen2 = Departemen::where('id', $penugasan2->dept_id)->first();
        $divisi2 = Divisi::where('id', $penugasan2->divisi_id)->first();
        $jabatan2 = Jabatan::where('id', $penugasan2->jabatan_id)->first();
        $pengganti = Karyawan::where('id', $penugasan->user_id_backup)->first();
        // dd(Cuti::with('KategoriCuti')->with('User')->where('cutis.id', $id)->where('cutis.status_cuti', '3')->first());
        $data = [
            'title' => 'domPDF in Laravel 10',
            'data_penugasan' => Penugasan::with('User')->where('penugasans.id', $id)->where('penugasans.status_penugasan', '5')->first(),
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'jabatan1' => $jabatan1,
            'divisi1' => $divisi1,
            'departemen1' => $departemen1,
            'jabatan2' => $jabatan2,
            'divisi2' => $divisi2,
            'departemen2' => $departemen2,
            'pengganti' => $pengganti
        ];
        $pdf = PDF::loadView('karyawans/penugasan/form_penugasan', $data)->setPaper('F4', 'landscape');;
        return $pdf->stream('FORM_PENGAJUAN_PENUGASAN_' . $user_karyawan->name . '_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
